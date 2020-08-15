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
        $token = $_SESSION['perm_token'];
        $token_secret = $_SESSION['perm_secret'];
    }
    // Check if the user was here earlier by checking cookies
    else if (isset($_COOKIE['perm_token']) && !empty($_COOKIE['perm_token']) && isset($_COOKIE['perm_secret']) && !empty($_COOKIE['perm_secret'])) {
        $token = $_COOKIE['perm_token'];
        $token_secret = $_COOKIE['perm_secret'];
    }
    // Check if this is the user's first visit
    else if (!isset($_GET['oauth_verifier'])) {
    
        // Grab the oauth token
        $resp = $requestHandler->request('POST', 'oauth/request_token', array());
        $out = $result = $resp->body;
        $data = array();
        parse_str($out, $data);
        
        // Save temporary tokens to session
        $_SESSION['tmp_token'] = $data['oauth_token'];
        $_SESSION['tmp_secret'] = $data['oauth_token_secret'];
    
        // Redirect user to Tumblr auth page
        session_regenerate_id(true);
        $header_url = 'https://www.tumblr.com/oauth/authorize?oauth_token=' . $data['oauth_token'];
        header('Location: ' . $header_url);
        die();
    
    }
    // Check if the user was just sent back from the Tumblr authentication site
    else {
    
        $verifier = $_GET['oauth_verifier'];
    
        // Use the stored temporary tokens
        $client->setToken($_SESSION['tmp_token'], $_SESSION['tmp_secret']);
    
        // Access the permanent tokens
        $resp = $requestHandler->request('POST', 'oauth/access_token', array('oauth_verifier' => $verifier));
        $out = $result = $resp->body;
        $data = array();
        parse_str($out, $data);
    
        // Set permanent tokens
        $token = $data['oauth_token'];
        $token_secret = $data['oauth_token_secret'];;
        $_SESSION['perm_token'] = $data['oauth_token'];
        $_SESSION['perm_secret'] = $data['oauth_token_secret'];

        // Set cookies in case the user comes back later
        setcookie("perm_token", $_SESSION['perm_token']);
        setcookie("perm_secret", $_SESSION['perm_secret']);
        
        // Redirect user to homepage for a clean URL
        session_regenerate_id(true);
        $header_url = 'https://cleberg.io/vox-populi/';
        header('Location: ' . $header_url);
        die();
    
    }

    // Authenticate via OAuth
    $client = new Tumblr\API\Client(
        $consumer_key,
        $consumer_secret,
        $token,
        $token_secret
    );
    
    // Set up a function to check if variables are blank later (this function accepts 0, 0.0, and "0" as valid)
    function not_blank($value) {
        return !empty($value) && isset($value) && $value !== '';
    }
    
    // Grab the callback URL
    $url = $_GET['callback'];
    if (!not_blank($url)) {
        $url = 'Location: https://cleberg.io/vox-populi/';
    }

    // See what action we need to do
    $action = $_GET['action'];
    if (!not_blank($action)) {
        header('Location: ' . $url);
        die();
    }

    // Follow a new blog
    if ($action == "follow") {
        $blogName = $_GET['blog_name'];
        if(not_blank($blogName)) {
            $client->follow($blogName);
            print 'success';
        } else {
            print 'failure';
        }
    }

    // Unfollow a blog
    if ($action == "unfollow") {
        $blogName = $_GET['blog_name'];
        if(not_blank($blogName)) {
            $client->unfollow($blogName);
            print 'success';
        } else {
            print 'failure';
        }
    }
    
    // Like a post
    if ($action == "like") {
        $postId = $_GET['post_id'];
        $reblogKey = $_GET['reblog_key'];
        if(not_blank($postId) && not_blank($reblogKey)) {
            $client->like($postId, $reblogKey);
            print 'success';
        } else {
            print 'failure';
        }
    }
    
    // Unlike a post
    if ($action == "unlike") {
        $postId = $_GET['post_id'];
        $reblogKey = $_GET['reblog_key'];
        if(not_blank($postId) && not_blank($reblogKey)) {
            $client->unlike($postId, $reblogKey);
            print 'success';
        } else {
            print 'failure';
        }
    }
?>