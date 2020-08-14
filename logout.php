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

// Echo a goodbye message
echo '<!doctype html><html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta http-equiv="x-ua-compatible" content="ie=edge">
            <meta name="robots" content="none">
            <link rel="apple-touch-icon" sizes="180x180" href="./assets/favicon/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="./assets/favicon/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="./assets/favicon/favicon-16x16.png">
            <link rel="manifest" href="./assets/favicon/site.webmanifest">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
                integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
            <link rel="stylesheet"  href="./assets/css/app.css">
            <title>Vox Populi - A Tumblr Web Client</title>
        </head>
        <body>
            <div class="container d-flex align-items-center justify-content-center" style="min-height:100vh;">
                <h1>Goodbye!</h1>
                <a class="btn btn-danger" href="./">Sign in</a>
            </div>
        </body>
        </html>';

?>