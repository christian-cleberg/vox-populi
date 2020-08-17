<?php

// Delete authentication cookies
setcookie("perm_token", "", time() - 3600);
setcookie("perm_secret", "", time() - 3600);
setcookie("PHPSESSID", "", time() - 3600);

// Clear and destory session
session_start();
session_unset();
session_destroy();
session_write_close();

// Unset session vars
unset($_SESSION['perm_token']);
unset($_SESSION['perm_secret']);

// Go back to sign-in page
header('Location: https://cleberg.io/vox-populi/');
die();

?>