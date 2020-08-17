<?php

// Initialize the session.
// If you are using session_name("something"), don't forget it now!
session_start();

// Delete authentication cookies
unset($_COOKIE["perm_token"]);
setcookie("perm_token", "", time() - 3600, "/vox-populi/dashboard");
unset($_COOKIE["perm_secret"]);
setcookie("perm_secret", "", time() - 3600, "/vox-populi/dashboard");
unset($_COOKIE["PHPSESSID"]);
setcookie("PHPSESSID", "", time() - 3600, "/vox-populi/dashboard");

// Unset all of the session variables.
$_SESSION = array();
unset($_SESSION['perm_token']);
unset($_SESSION['perm_secret']);
session_unset();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
session_write_close();

// Go back to sign-in page
header('Location: https://cleberg.io/vox-populi/');
die();

?>