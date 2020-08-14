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

    // See what action we need to do
    if (not_blank($action)) {
        $action = $_GET['action'];
        $url = $_GET['callback'];
    } else {
        if (not_blank($_GET['callback'])) {
            header('Location: ' . $_GET['callback']);
            die();
        } else {
            header('Location: https://cleberg.io/vox-populi/');
            die();
        }
    }

    // Follow a new blog
    if ($action == "follow") {
        $blogName = $_GET['blog_name'];
        if(not_blank($blogName)) {
            $client->follow($blogName);
            
            // Add parameter so we can send a success alert
            $separator = "?";
            if (strpos($url,"?")!=false) {
                $separator = "&";
            }
            $new_url = $url . $separator . 'followed=true';
            header('Location: ' . $new_url);
            die();
        } else {
            // Add parameter so we can send a failure alert
            $separator = "?";
            if (strpos($url,"?")!=false) {
                $separator = "&";
            }
            $new_url = $url . $separator . 'followed=false';
            header('Location: ' . $new_url);
            die();
        }
    }

    // Unfollow a blog
    if ($action == "unfollow") {
        $blogName = $_GET['blog_name'];
        if(not_blank($blogName)) {
            $client->unfollow($blogName);
            echo "Success";
            
            // Add parameter so we can send a success alert
            $separator = "?";
            if (strpos($url,"?")!=false) {
                $separator = "&";
            }
            $new_url = $url . $separator . 'unfollowed=true';
            header('Location: ' . $new_url);
            die();
        } else {
            echo "Error: No blog name supplied.";
            // Add parameter so we can send a failure alert
            $separator = "?";
            if (strpos($url,"?")!=false) {
                $separator = "&";
            }
            $new_url = $url . $separator . 'unfollowed=false';
            header('Location: ' . $new_url);
            die();
        }
    }

?>