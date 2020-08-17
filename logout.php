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

// Unset cookies
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}

// Go back to sign-in page
header('Location: https://cleberg.io/vox-populi/');
die();

?>