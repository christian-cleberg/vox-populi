<?php

// Delete authentication cookies
setcookie("perm_token", "", time() - 3600);
setcookie("perm_secret", "", time() - 3600);

// Clear and destory session
session_start();
session_unset();
session_destroy();
session_write_close();
setcookie(session_name(),'',0,'/');
session_regenerate_id(true);

// Go back to sign-in page
header('Location: https://cleberg.io/vox-populi/');
die();

?>