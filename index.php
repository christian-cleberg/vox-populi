<?php

require __DIR__ . '/vendor/autoload.php';
    
session_start();

$consumer_key = getenv('CONSUMER_KEY');
$consumer_secret = getenv('CONSUMER_SECRET');
$client = new Tumblr\API\Client($consumer_key, $consumer_secret);
$requestHandler = $client->getRequestHandler();
$requestHandler->setBaseUrl('https://www.tumblr.com/');

// Check if the user has already authenticated
if(isset($_SESSION['perm_token']) && !empty($_SESSION['perm_token']) && isset($_SESSION['perm_secret']) && !empty($_SESSION['perm_secret'])) {
    header('Location: https://cleberg.io/vox-populi/dashboard/');
    die();
}
// Check if the user was here earlier by checking cookies
else if (isset($_COOKIE['perm_token']) && !empty($_COOKIE['perm_token']) && isset($_COOKIE['perm_secret']) && !empty($_COOKIE['perm_secret'])) {
    header('Location: https://cleberg.io/vox-populi/dashboard/');
    die();
} else {

// Echo a hello message
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
            <span class="homepage--dots"></span>
            <div class="container-fluid d-flex flex-column align-items-center justify-content-center" style="min-height:100vh;">
                <div class="card w-50 h-50">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h1 class="card-title mb-0">Welcome to Vox Populi</h1>
                        <p class="card-text mb-4" style="font-size:1rem;">Use the button below to sign in with your Tumblr account.</p>
                        <a class="bx--btn bx--btn--danger py-2 px-5" href="./dashboard/">Sign In</a>
                    </div>
                </div>
            </div>
        </body>
        </html>';

}

?>