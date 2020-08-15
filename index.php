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
    
    // Echo HTML contents
    echo '<!doctype html><html lang="en">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <meta http-equiv="x-ua-compatible" content="ie=edge">
                <meta name="author" content="Christian Cleberg">
                <meta name="description" content="Vox Populi is a web client for Tumblr, allowing you to access you personal Tumblr dashboard without ads.">
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
                <nav class="navbar navbar-expand-lg navbar-dark">
                    <div class="container">
                        <a class="navbar-brand" href="./">Vox Populi</a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
            
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-0 ml-auto">
                                <li class="nav-item active">
                                    <a class="nav-link" href="./">Home <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Filter Posts
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="./'; echo (isset($_GET['page']) ? ("?page=" . $_GET['page'] . "&") : "?"); echo 'type=answer">Answer</a>
                                        <a class="dropdown-item" href="./'; echo (isset($_GET['page']) ? ("?page=" . $_GET['page'] . "&") : "?"); echo 'type=audio">Audio</a>
                                        <a class="dropdown-item" href="./'; echo (isset($_GET['page']) ? ("?page=" . $_GET['page'] . "&") : "?"); echo 'type=chat">Chat</a>
                                        <a class="dropdown-item" href="./'; echo (isset($_GET['page']) ? ("?page=" . $_GET['page'] . "&") : "?"); echo 'type=link">Link</a>
                                        <a class="dropdown-item" href="./'; echo (isset($_GET['page']) ? ("?page=" . $_GET['page'] . "&") : "?"); echo 'type=photo">Photo</a>
                                        <a class="dropdown-item" href="./'; echo (isset($_GET['page']) ? ("?page=" . $_GET['page'] . "&") : "?"); echo 'type=quote">Quote</a>
                                        <a class="dropdown-item" href="./'; echo (isset($_GET['page']) ? ("?page=" . $_GET['page'] . "&") : "?"); echo 'type=text">Text</a>
                                        <a class="dropdown-item" href="./'; echo (isset($_GET['page']) ? ("?page=" . $_GET['page'] . "&") : "?"); echo 'type=video">Video</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="./'; echo (isset($_GET['page']) ? ("?page=" . $_GET['page']) : ""); echo '">All Posts</a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link logout" href="logout.php">Logout</a>
                                </li>
                            </ul>
                            <!--
                            <form class="form-inline my-2 my-lg-0">
                                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                            </form>
                            -->
                        </div>
                    </div>
                </nav>
                <div class="container">';

            // Get the user's blog name for welcome message
            if ($client->getUserInfo()) {
                $client->getUserInfo();
            } else {
                // Echo rate-limit error message
                $rate_error = '<div id="rate-limit-exceeded" class="alert alert-danger my-5"><b>[429] Error</b>: Tumblr rate limit exceeded. Please visit again tomorrow.</div>';
                echo $rate_error;
                die();
            }
            foreach ($client->getUserInfo()->user->blogs as $blog) {
                echo '<h1 class="text-center py-4">Welcome, <a href="https://' . $blog->name . '.tumblr.com">' . $blog->name . '</a>!</h1>';
            }

            // Create function to allow a client to get 20 posts per page
            function get_dashboard_posts($client, $post_start, $limit, $post_type) {
                if ($post_type != NULL) {
                    $dashboard_posts = $client->getDashboardPosts(array('limit' => $limit, 'offset' => $post_start, 'reblog_info' => true, 'type' => $post_type));
                } else {
                    $dashboard_posts = $client->getDashboardPosts(array('limit' => $limit, 'offset' => $post_start, 'reblog_info' => true));
                }
                $card_columns = '<div class="card-columns">';
                foreach ($dashboard_posts->posts as $post) {
                    $card_columns .= '<div class="card" data-type="' . $post->type . '" data-id="' . $post->id_string . '">';
                    $card_columns .= '<div class="card-header d-flex justify-content-between"><div class="card-header-blog"><a href="' . $post->blog->url . '" target="_blank"><img class="avatar" src="' . $client->getBlogAvatar($post->blog_name, 32) . '"></a>';
                    $card_columns .= '<a href="' . $post->blog->url . '">' . $post->blog_name . '</a>';
                    if (not_blank($post->reblogged_from_name)) {
                        $card_columns .= '<i data-feather="repeat"></i><a href="' . $post->reblogged_from_url . '">' . $post->reblogged_from_name . '</a>';
                        if (!$post->reblogged_from_following) {
                            $card_columns .= '<a href="javascript:void(0);" onclick="follow(\'' . $post->reblogged_from_name . '\', ' . $post->reblogged_from_uuid . ');" data-follow-id="' . $post->reblogged_from_uuid . '"><span class="badge badge-secondary ml-2">Follow</span></a>';
                        }
                    }
                    $card_columns .= '</div>';
                    
                    // Add 'follow user' button if not following blog
                    if ($post->followed) {
                        $card_columns .= '</div>';
                    } else {
                        $card_columns .= '<a href="javascript:void(0);" onclick="follow(\'' . $post->blog_name . '\', ' . $post->blog->uuid . ');" title="Follow" data-id="' . $post->blog->uuid . '"><i data-feather="user-plus"></i></a></div>';
                    }
                    
                    // Add root blog (original poster)
                    if ($post->reblogged_root_following) {
                        $card_columns .= '<div class="card-header d-flex justify-content-between"><div class="card-header-blog">';
                        $card_columns .= '<a href="' . $post->reblogged_root_url . '" target="_blank"><img class="avatar" src="' . $client->getBlogAvatar($post->reblogged_root_name, 32) . '"></a>';
                        $card_columns .= '<a href="' . $post->reblogged_root_url . '">' . $post->reblogged_root_name . '</a></div>';
                        $card_columns .= '</div>';
                    } else {
                        $card_columns .= '<div class="card-header d-flex justify-content-between"><div class="card-header-blog">';
                        $card_columns .= '<a href="' . $post->reblogged_root_url . '" target="_blank"><img class="avatar" src="' . $client->getBlogAvatar($post->reblogged_root_name, 32) . '"></a>';
                        $card_columns .= '<a href="' . $post->reblogged_root_url . '">' . $post->reblogged_root_name . '</a></div>';
                        if (strpos($post->reblogged_root_name, 'deactivated') == false) {
                            $card_columns .= '<a href="javascript:void(0);" onclick="follow(\'' . $post->reblogged_root_name . '\');" title="Follow" data-id="' . $post->reblogged_root_uuid . '"><i data-feather="user-plus"></i></a>';
                        }
                        $card_columns .= '</div>';
                    }
                    
                    if ($post->type == 'photo') {
                        $card_columns .= '<a href="' . $post->photos[0]->original_size->url . '"><img src="' . $post->photos[0]->original_size->url . '" class="card-img" alt="..."></a>';
                    }
                    if ($post->type == 'video') {
                        $card_columns .= '<video controls poster="'.$post->thumbnail_url.'" class="card-img"><source src="'.$post->video_url.'"></source></video>';
                    }
                    $card_columns .= '<div class="card-body">';
                    if ($post->type == 'photo') {
                        if (not_blank($post->caption)) {
                            $card_columns .= '<p class="card-text post-caption">' . $post->caption . '</p>';
                        }
                    }
                    if ($post->type == 'text') {
                        if (not_blank($post->title)) {
                            $card_columns .= '<h5 class="card-title post-title">' . $post->title . '</h5>';
                        }
                        if (not_blank($post->body)) {
                            $card_columns .= '<p class="card-text post-body">' . $post->body . '</p>';
                        }
                    }
                    if ($post->type == 'answer') {
                        $card_columns .= '<p class="card-text"><b>' . $post->asking_name . '</b>: ' . $post->question . '</p>';
                        $card_columns .= '<hr>';
                        $card_columns .= $post->answer;
                    }
                    if ($post->type == 'quote') {
                        $card_columns .= '<blockquote class="card-text post-quote">' . $post->summary;
                        $card_columns .= '<br><br><cite>' . $post->reblog->comment . '</cite></blockquote>';
                    }
                    if ($post->type == 'chat') {
                        for ($i = 0; $i <= count($post->dialogue); $i++) {
                            $card_columns .= '<blockquote class="card-text post-chat-' . $i . '">' . (not_blank($post->dialogue[$i]->name) ? ('<b>' . $post->dialogue[$i]->name . '</b>: ') : "") . $post->dialogue[$i]->phrase . '</blockquote>';
                        }
                    }
                    /* This seems to just post a duplicate of a QA answer 
                    if (isset($post->reblog->comment) && $post->reblog->comment !== '') {
                        $card_columns .= '<p class="card-text reblog-comment">' . $post->reblog->comment . '</p>';
                    }
                    */
                    // $card_columns .= '<a href="' . $post->post_url . '" class="card-link">Visit Post &rarr;</a>';
                    $card_columns .= '<div class="card-footer d-flex justify-content-between align-items-center p-0"><div class="note-count">' . number_format($post->note_count, 0) . ' notes</div>';
                    $card_columns .= '<div class="post-icons"><a href="' . $post->post_url . '" target="_blank" title="View on Tumblr"><i data-feather="external-link"></i></a>';
                    $card_columns .= '<a href="#" title="Share"><i data-feather="send"></i></a>';
                    $card_columns .= '<a href="#" title="Comment"><i data-feather="message-square"></i></a>';
                    $card_columns .= '<a href="#" title="Reblog"><i data-feather="repeat"></i></a>';
                    
                    if ($post->liked != true) {
                        // Like this post
                        $card_columns .= '<a href="javascript:void(0);" onclick="like(\'' . urlencode($post->id) . '\', \'' . urlencode($post->reblog_key) . '\');" title="Like" data-id="' . $post->id . '"><i data-feather="heart"></i></a></div></div>';
                    } else {
                        // Unlike this post
                        $card_columns .= '<a href="javascript:void(0);" onclick="unlike(\'' . urlencode($post->id) . '\', \'' . urlencode($post->reblog_key) . '\');" title="Unlike" data-id="' . $post->id . '"><i data-feather="heart"></i></a></div></div>';
                    }
                    
                    $card_columns .= '</div></div>';
                }
                $card_columns .= '</div>';
                return $card_columns;
            }

            // Create a loop to call as many dashboard posts as you want (results are returned in sets of 20 per API rules)
            // Can specify post type: text, chat, link, photo, audio, video, NULL
            if (isset($_GET['type'])) {
                $post_type = $_GET['type'];
            } else {
                $post_type = NULL;
            }
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            } else {
                $page = 1;
            }
            $post_start = (($page - 1) * 20) + 1;
            $limit = 20;
            echo get_dashboard_posts($client, $post_start, $limit, $post_type);
            
            // Echo HTML page navigation
            // Embedded PHP tags here are calculating page numbers for URL parameters
            echo '
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item '; echo ($page <= 1 ? "disabled" : ""); echo'">
                    <a class="page-link" href="./?page='; echo ($page-1); echo (isset($_GET['type']) ? "&type=" . $_GET['type'] : ""); echo '" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item '; echo ($page <= 1 ? "disabled" : "");  echo '"><a class="page-link"
                        href="./?page='; echo ($page-1); echo (isset($_GET['type']) ? "&type=" . $_GET['type'] : ""); echo '">'; echo ($page-1); echo '</a></li>
                <li class="page-item active"><a class="page-link" href="#">'; echo $page; echo '</a></li>
                <li class="page-item"><a class="page-link"
                        href="./?page='; echo ($page+1); echo (isset($_GET['type']) ? "&type=" . $_GET['type'] : ""); echo '">'; echo ($page+1); echo '</a></li>
                <li class="page-item">
                    <a class="page-link" href="./?page='; echo ($page+1); echo (isset($_GET['type']) ? "&type=" . $_GET['type'] : ""); echo'" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <footer class="py-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    <p><a href="https://cleberg.io">Christian Cleberg</a> © 2016 - '; echo date("Y"); echo '</p>
                    <p><a href="https://github.com/christian-cleberg/vox-populi" target="_blank">GitHub Source Code</a></p>
                </div>
                <div class="col">
                    <a class="float-right" href="#">Back to top</a>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="./assets/js/app.js"></script>
</body>

</html>';
?>