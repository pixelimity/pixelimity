<?php
/**
 * Installer file to make the site ready to used
 * @package pixelimity
 */
/* ------------------------------------------------------------------------------------------------------ \
                                        HELPER
\ ------------------------------------------------------------------------------------------------------ */

/**
 * isset( $_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' # port 443 is not always for ssl
 * load balancer fix that has x forwarder protocol set with. add $_SERVER['HTTPS'] -> 'on' value
 */
if (isset( $_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS'] = 'on';
}

/* GETTING PROTOCOL 
----------------------------------------- */
$protocol = ( isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https' ) ||
            ( isset( $_SERVER['HTTPS']) && ( strtolower( $_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1' ) )
            ? 'https' : 'http';

/* GETTING DOCUMENT ROOT USING REALPATH AVOID SYMLINK
----------------------------------------- */
$docroot = function_exists('realpath') ? realpath($_SERVER['DOCUMENT_ROOT']) : $_SERVER['DOCUMENT_ROOT'];
// clean path ( windows directory separator fix )
$docroot = rtrim(str_replace( array( "\\", "\\\\" ), "/", $docroot ), '/');

/* GETTING DOCUMENT ROOT USING REALPATH AVOID DIR SYMLINK IF PROVIDE
----------------------------------------- */
$base    = function_exists('realpath') ? realpath(dirname(__FILE__)) : dirname(__FILE__);
$base    = str_replace( array( "\\", "\\\\" ), "/", $base );

/* GETTING HOST AS DOMAIN
----------------------------------------- */
$host    = ( isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : false );
// if no host exits
if( ! $host ) :
    header( 'HTTP/1.1 400 Bad Request');
    die('Server Not Supported, HTTP_HOST / SERVER_NAME is not defined!');
endif;

/* ------------------------------------------------------------------------------------------------------ \
                                        END HELPER
\ ------------------------------------------------------------------------------------------------------ */

/* Define some parameters */
define ('BASE', $base);
define ('PATH', '/'.trim(str_replace( $docroot, '', BASE ), '/') );
define ('DIR',  $base );
define ('URL',  rtrim( $protocol.'://'. $host.PATH, '/' ) );
if (file_exists(DIR .'/install/config.php')) :
    header("Location: ". URL);
endif;

if (isset($_POST['submit_install'])) :
    $data = array();
    $error = false;

    foreach ($_POST['data'] as $key => $value) {
      $data[$key] = $value;
    }

    $data['site_name'] = ($data['site_name']) ? $data['site_name'] : 'Pixelimity';
    $data['site_description'] = ($data['site_description']) ? $data['site_description'] : 'My Online Portfolio';
    $data['username'] = ($data['username']) ? $data['username'] : 'admin';

    if (empty($data['password'])) :
        $error = true;
        $message = 'Please enter your account password';
    elseif (strlen($data['password']) < 6) :
        $error = true;
        $message = 'Your password is too short. 6 Characters minimum.';
    elseif (empty($data['email'])) :
        $error = true;
        $message = 'Please enter you account email.';
    elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) :
        $error   = true;
        $message = 'Your email is invalid.';
    endif;

    if (!$error) :
        try {
            error_reporting(0);
            $db = new PDO('mysql:host='. $data['dbhost'] .';dbname='. $data['dbname'], $data['dbuser'], $data['dbpassword'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            $sql = str_replace(
                array('{{site_name}}', '{{site_description}}', '{{username}}', '{{password}}', '{{email}}'),
                array($data['site_name'], $data['site_description'], $data['username'], md5($data['password']), $data['email']),
                file_get_contents(DIR .'/install/db.sql')
            );
            $db->exec($sql);

            $config = file_get_contents(DIR .'/install/config-sample.php');
            $config = str_replace(
                array('{{db_host}}', '{{db_name}}', '{{db_user}}', '{{db_password}}'),
                array($data['dbhost'], $data['dbname'], $data['dbuser'], $data['dbpassword']),
                $config
            );

            if (!file_exists(DIR .'/install/config.php')) :
                $config_file = fopen(DIR .'/install/config.php', 'w');
                fwrite($config_file, $config);
                fclose($config_file);
            endif;

            if (!file_exists(DIR .'/.htaccess')) :
                $rewrite = '
# add rewrite rule for pixelimity route friendly url
Options -Indexes
ErrorDocument 404 '. PATH .'/index.php?error
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !(\.[a-zA-Z0-9]{1,5}|/)$
RewriteRule (.*)$ '. PATH .'/$1/ [R=301,QSA]
RewriteRule ^portfolio/([a-z-0-9-/]+)/$ index.php?portfolio=$1 [QSA]
RewriteRule ^tag/([a-z-0-9-/]+)/$ index.php?tag=$1 [QSA]
RewriteRule ^page/([a-z-0-9-/]+)/$ index.php?page=$1 [QSA]
';
                $config_file = fopen(DIR .'/.htaccess', 'w');
                fwrite($config_file, str_replace('\t', '', $rewrite));
                fclose($config_file);
            endif;

            /**
             * Mail administrator when site is ready
             */
            $subject = 'Your site has been installed.';
            $message = "
                Hello ". $data['email'] .", your site has been installed. You can sign in with these account details:\n
                Sign In URL: ". URL ."/admin/signin.php\n
                Username: ". $data['username'] ."\n
                Password: ". $data['password'] ."\n\n
                Thanks to using Pixelimity";
            $message = str_replace('\t', '', $message);
            $header  = 'From: Dicky Syaputra <dickysyaputra@gmail.com>\r\nReply-To: dickysyaputra@gmail.com';
            mail($admin_data['email'], $subject, $message, $header);

            header("Location: ". URL ."/admin/signin.php?installed=true");
        } catch (PDOException $e) {
            $message = 'The database setup is error. Please check again.';
        }
    endif;
endif;

?>
<!DOCTYPE html>
<html>
<head>
  <!-- meta characterset -->
  <meta charset="utf-8">
  <!-- title -->
  <title>Install ~ Pixelimity</title>
  <!-- meta viewport as responsive mobile device compatibilities -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- meta robots not allowed index this script -->
  <meta name="robots" content="noindex, nofollow, noodp, noydir, noarchive">
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo URL .'/admin/css/admin.css'; ?>">
  <link rel="shortcut icon" href="<?php echo URL .'/admin/images/favicon.png'; ?>">
</head>
<body class="install">
  <nav id="admin-menu">
    <div class="logo">
      <a href="<?php echo URL; ?>/install"><img src="<?php echo URL; ?>/admin/images/admin-logo.png" alt="Pixelimity" /></a>
    </div>
    <ul>
      <li><a href="http://dickecil.net/pixelimity" target="_blank">See Demo &rarr;</a></li>
      <li><a href="<?php echo URL; ?>/docs" target="_blank">Read documentation</a></li>
    </ul>
  </nav>
  <!-- nav#admin-menu -->
  <div id="main">
    <form class="form-edit clearfix" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
      <header id="page-header" class="clearfix scrollfixed">
        <h1>Install Pixelimity</h1>
        <div class="actions">
          <input type="submit" class="btn btn-blue btn-submit" name="submit_install" value="Complete Install">
        </div>
      </header>
      <!-- #page-header -->
      <div id="content">
        <h3 class="field-section-title">Database Setup</h3>
        <div class="message clearfix">
<?php if (isset($message)) : ?>
          <div class="msg msg-form msg-error">
            <?php echo $message; ?>
          </div>
          <!-- .msg-error -->
<?php elseif (isset($_GET['installed']) && $_GET['installed'] == 'true') : ?>
          <div class="msg msg-form">
            Pixelimity was successfully installed. You can <a href="<?php echo URL; ?>/admin/login.php">login now</a> to access your admin.
          </div>
          <!-- .msg -->
<?php endif; ?>
        </div>
        <div class="field">
          <label for="dbhost" class="label">Host<span>Enter your database host. Default: localhost.</span></label>
          <input type="text" name="data[dbhost]" id="dbhost" required="required" class="input dbhost" value="<?php echo (isset($data['dbhost'])) ? $data['dbhost'] : 'localhost'; ?>">
        </div>
        <div class="field">
          <label for="dbuser" class="label">Username<span>Enter your database user. Default: root.</span></label>
          <input type="text" name="data[dbuser]" id="dbuser" required="required" class="input dbuser" value="<?php echo (isset($data['dbuser'])) ? $data['dbuser'] : 'root'; ?>">
        </div>
        <div class="field">
          <label for="dbpassword" class="label">Password<span>Enter your database password. Default: empty.</span></label>
          <input type="text" name="data[dbpassword]" id="dbpassword" class="input dbpassword" value="<?php echo (isset($data['dbpassword'])) ? $data['dbpassword'] : ''; ?>">
        </div>
        <div class="field">
          <label for="dbname" class="label">Name<span>Enter your database name. Default: pixelimity.</span></label>
          <input type="text" name="data[dbname]" id="dbname" required="required" class="input dbname" value="<?php echo (isset($data['dbname'])) ? $data['dbname'] : 'pixelimity'; ?>">
        </div>
        <h3 class="field-section-title">Account Setup</h3>
        <div class="field">
          <label for="username" class="label">Username<span>Enter your username. Default: admin.</span></label>
          <input type="text" name="data[username]" id="username" required="required" class="input username" value="<?php echo (isset($data['username'])) ? $data['username'] : 'admin'; ?>">
        </div>
        <div class="field">
          <label for="password" class="label">Password<span>Enter your password. Default: empty.</span></label>
          <input type="password" name="data[password]" id="password" required="required" class="input password" value="<?php echo (isset($data['password'])) ? $data['password'] : ''; ?>">
        </div>
        <div class="field">
          <label for="email" class="label">Email<span>Enter your email. Default: empty.</span></label>
          <input type="text" name="data[email]" id="email" required="required" class="input email" value="<?php echo (isset($data['email'])) ? $data['email'] : ''; ?>">
        </div>
        <h3 class="field-section-title">Site Setup</h3>
        <div class="field">
          <label for="site-name" class="label">Name<span>Enter your site name. Default: Pixelimity.</span></label>
          <input type="text" name="data[site_name]" id="site-name" class="input site-name" value="<?php echo (isset($data['site_name'])) ? $data['site_name'] : 'Pixelimity'; ?>">
        </div>
        <div class="field">
          <label for="site-description" class="label">Description<span>Enter your site description. Default: My Online Portfolio.</span></label>
          <input type="text" name="data[site_description]" id="site-description" class="input site-description" value="<?php echo (isset($data['site_description'])) ? $data['site_description'] : 'My Online Portfolio'; ?>">
        </div>
      </div>
      <!-- #content -->
    </form>
    <!-- form.form-edit -->
  </div>
  <!-- .main -->
  <script type="text/javascript" src="<?php echo URL .'/admin/js/jquery.min.js'; ?>"></script>
  <script type="text/javascript" src="<?php echo URL .'/admin/js/admin.js'; ?>"></script>
</body>
</html>
