<?php

/* Define PXL, ADMIN */
define ('PXL', true, true);
define ('ADMIN', true, true);

/* Call config file */
require_once '../config.php';

/* Destroy session if user want to sign out */
if (isset($_GET['action']) && $_GET['action'] == 'sign_out') :
    unset($_SESSION['admin']);
    session_destroy();
    redirect_to('/admin/signin.php?signed_out=true', true);
endif;

/* Redirect to portfolio */
redirect_to('/admin/portfolio.php', true);
