<?php
/**
 * Sign in Page
 * @package pixelimity
 */
/* Define PXL, ADMIN */
define ('PXL', true, true);
define ('ADMIN', true, true);

/* Call config file */
require_once ('../config.php');

/* Redirect if user alread signed in */
if (is_signed_in()) :
    redirect_to('/admin/portfolio.php', true);
endif;

/**
 * Set page title
 */
if (isset($_GET['action']) && $_GET['action'] == 'new_password' && isset($_GET['rp_code'])) :
    $title = 'Create New Password';
elseif (isset($_GET['action']) && $_GET['action'] == 'lost_password') :
    $title = 'Lost Password';
else :
    $title = 'Sign In';
endif;

/* Check if reset password code is valid */
if (isset($_GET['action']) && $_GET['action'] == 'new_password' && isset($_GET['rp_code'])) :
    global $db;
    
    $rp_code = $_GET['rp_code'];
    if ($db->row_count('admin', 'rp_code', $rp_code) == 0) :
        redirect_to('/admin/signin.php?action=lost_password&error=rp_code', true);
    endif;

endif;
    
/* Action for sign in */
if (isset($_POST['signin'])) :
    global $db;
    
    $data = array();
    $exists = true;
    $return = '';

    /* Get return URL */
    if (isset($_GET['return'])) 
        $return = urldecode($_GET['return']);
    
    /* Set data */
    foreach ($_POST['data'] as $key => $value)
        $data[$key] = $value;
        
    /* If username and password don't exists */
    if ($db->row_count('admin', 'username', array($data['username']) ) == 0) :
        $exists = false;
    endif;

    /* Set error notification */
    if (empty($data['username']) || empty($data['password']) || !$exists || 
        // else if complete but not matched hashes
        (
            ! empty($data['username']) && ! empty($data['password']) && $exists &&
            // check password hashes
            ! check_passwordhashes( $data['username'] , md5( $data['password'] ) )
            )
        ) :
        $message = 'Sign in failed. Please try again.';
    else :
    
        /* Set session */
        $_SESSION['ADMIN'] = array('u' => $data['username'], 'hash' => md5($data['username']), 'time' => time());
        
        /* Redirect */
        if ($return) :
            redirect_to($return, true);
        else :
            redirect_to('/admin/portfolio.php', true);
        endif;

    endif;
endif;

/* Action for lost password */
if (isset($_POST['lost_password'])) :
    global $db;
    
    $data = array();
    $exists = true;
    
    /* Set data */
    foreach ($_POST['data'] as $key => $value)
        $data[$key] = $value;
        
    /* If username or email don't exists */
    if ($db->row_count('admin', 'username', array($data['username_or_email'], $data['username_or_email']), ' OR email = ?') == 0)
        $exists = false;

    /* Set error notification */
    if (empty($data['username_or_email']) || !$exists) :
        $message = 'An error occured. Please try again.';
    else :
        /* Get user data */
        $admin_data = $db->select('admin', 'username', array($data['username_or_email'], $data['username_or_email']), ' OR email = ?');
        
        /* Mail confirmation code admin */
        $subject = 'Confirmation Link to Reset Password';
        $message = "
            Someone requested that the password be reset for the following account:\n
            ". get_site_url() ."\n
            Username: ". $admin_data['username'] ."\n
            If this was a mistake, just ignore this email and nothing will happen. \n
            To reset your password, visit the following address:\n
            ". get_admin_url() ."/signin.php?action=new_password&amp;rp_code=". time();
        $message = str_replace('\t', '', $message);
        $header = 'From: '. get_site_name() .'<'. $admin_data['email'] .'>\r\nReply-To: '. $admin_data['email'];
        mail($admin_data['email'], $subject, $message, $header);
        
        /* Update reset password code */
        $db->update('admin', array(':rp_code' => time()), 'username', array($data['username_or_email'], $data['username_or_email']), ' OR email = ?');
        
        /* Redirect */
        redirect_to('/admin/signin.php?action=lost_password&send=true');
    endif;
endif;

/* Action for create new password */
if (isset($_POST['new_password'])) :
    global $db;
    
    $data = array();
    
    /* Set data */
    foreach ($_POST['data'] as $key => $value)
        $data[$key] = $value;
        
    /* Set error notification */
    if (empty($data['password']) || empty($data['confirm_password'])) :
        $message = 'Please complete your form first.';
    elseif (strlen($data['password']) < 6) :
        $message = 'Your password is too short. 6 characters minimum.';
    elseif ($data['password'] != $data['confirm_password']) :
        $message = 'Your password don\'t match.';
    else :
        /* Update password */
        $db->update('admin', array(':password' => md5($data['password'])), 'rp_code', $rp_code);
        
        /* Redirect */
        redirect_to('/admin/signin.php?action=lost_password&reset=true');
    endif;
endif;

/* Start compress HTML */
ob_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- meta characterset -->
  <meta charset="utf-8">
  <!-- title -->
  <title><?php echo $title; ?> &lsaquo; Admin ~ <?php site_name(); ?></title>
  <!-- meta viewport as responsive mobile device compatibilities -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- meta robots not allowed index this script -->
  <meta name="robots" content="noindex, nofollow, noodp, noydir, noarchive">
  <link rel="stylesheet" type="text/css" media="screen" href="<?php admin_url(); ?>/css/admin.css">
  <link rel="stylesheet" type="text/css" media="screen" href="<?php admin_url(); ?>/css/signin.css">
  <link rel="shortcut icon" href="<?php admin_url(); ?>/images/favicon.png">
</head>
<body class="signin">
  <div class="container">
    <div class="logo"><a href="<?php site_url(); ?>"><img src="<?php echo admin_url(); ?>/images/login-logo.png" alt="logo" /></a></div>
<?php if (isset($_GET['action']) && $_GET['action'] == 'new_password' && isset($_GET['rp_code'])) : 
        if (isset($message)) : ?>
    <div class="msg2 error-text"><?php echo $message; ?></div>
<?php   endif; ?>
    <form method="post" action="">
      <p><input type="password" name="data[password]" id="password" required="required" class="input password" placeholder="Password"></p>
      <p><input type="password" name="data[confirm_password]" id="confirm-password" required="required" class="input confirm-password" placeholder="Confirm Password"></p>
      <p class="submit">
        <input type="submit" name="new_password" class="btn btn-blue" value="Create New Password">
        <a class="lost-password" href="<?php admin_url(); ?>/signin.php">Back</a>
      </p>
    </form>
    <!-- form -->
<?php  elseif (isset($_GET['action']) && $_GET['action'] == 'lost_password') : 
        if (isset($message)) : ?>
    <div class="msg2 error-text"><?php echo $message; ?></div>
<?php elseif (isset($_GET['reset']) && $_GET['reset'] == 'true') : ?>
    <div class="msg2">Your password has been reset.</div>
<?php elseif (isset($_GET['send']) && $_GET['send'] == 'true') : ?>
    <div class="msg2">A confirmation link has been sent to your email. <br/>Please check your email now.</div>
<?php elseif (isset($_GET['error']) && $_GET['error'] == 'rp_code') : ?>
    <div class="msg2 error-text">Your reset password code is invalid.</div>
<?php else : ?>
    <div class="msg2 info-text">A confirmation link will be sent to your email.</div>
<?php endif; ?>
    <form method="post" action="">
      <p><input required="required" type="text" name="data[username_or_email]" id="username-or-email" class="input username-or-email" placeholder="Username or Email"></p>
      <p class="submit">
        <input type="submit" name="lost_password" class="btn btn-blue" value="Get Confirmation Link">
        <a class="lost-password" href="<?php admin_url(); ?>/signin.php">Back</a>
      </p>
    </form>
    <!-- form -->
<?php else :
    if (isset($message)) : ?>
    <div class="msg2 error-text"><?php echo $message; ?></div>
<?php elseif (isset($_GET['signed_out']) && $_GET['signed_out'] == 'true') : ?>
    <div class="msg2 info-text">You already signed out.</div>
<?php elseif (isset($_GET['installed']) && $_GET['installed'] == 'true') : ?>
    <div class="msg2 info-text">Your site has been installed. Please signin now.</div>
<?php endif; ?>
    <form method="post" action="">
      <p><input type="text" name="data[username]" id="username" required="required" class="input username" placeholder="Username"></p>
      <p><input type="password" name="data[password]" id="password" required="required" class="input password" placeholder="Password"></p>
      <p class="submit">
        <input type="submit" name="signin" class="btn btn-blue" value="Sign In">
        <a class="lost-password" href="<?php admin_url(); ?>/signin.php?action=lost_password">Lost password?</a>
      </p>
    </form>
    <!-- form -->
<?php endif; ?>
  </div>
  <!-- .container -->
</body>    
</html>
<?php 
/* End compress HTML */
ob_end_flush(); 
