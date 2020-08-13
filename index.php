<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="author" content="Christian Cleberg">
    <meta name="description" content="Vox Populi is a web client for Tumblr, allowing users with tokens to access their dashboards.">
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
    <link rel="manifest" href="./favicon/site.webmanifest">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>Vox Populi - A Tumblr Web Client</title>
    <style>
        body {
            background: #121212;
            color: #ccc;
        }

        p {
            font-size: 0.8rem;
        }

        a,
        a:hover {
            color: #dc3545;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: white;
        }

        .nvabar {
            background: #121212;
        }

        .card {
            background: #202020;
        }

        .card-body img {
            width: 100%;
        }

        .card-footer {
            border: none;
        }

        hr {
            border-top: 1px solid #ccc;
        }

        blockquote {
            background: #3b3b3b;
            border-left: 2px solid #dc3545;
            margin: 0.5rem auto;
            padding: 0.25rem 0.5rem;
        }

        blockquote p {
            margin-bottom: 0;
        }

        .avatar {
            height: 32px;
            margin-right: 0.75rem;
            width: 32px;
        }

        .feather {
            margin: 0 0.2rem;
        }

        .page-link {
            color: #dc3545;
            background-color: #202020;
            border: 1px solid #3b3b3b;
        }

        .page-link:hover {
            color: #dc3545;
            background-color: #202020;
            border-color: #3b3b3b;
        }

        .page-link:focus {
            box-shadow: 0 0 0 .2rem rgba(220, 53, 69, .25);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Vox Populi</a>
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
                        <a class="dropdown-item" href="./?type=audio">Audio</a>
                        <a class="dropdown-item" href="./?type=chat">Chat</a>
                        <a class="dropdown-item" href="./?type=link">Link</a>
                        <a class="dropdown-item" href="./?type=photo">Photo</a>
                        <a class="dropdown-item" href="./?type=text">Text</a>
                        <a class="dropdown-item" href="./?type=video">Video</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="./">All Posts</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">GitHub <i data-feather="arrow-up-right"></i></a>
                </li>
            </ul>
            <!--
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
            -->
        </div>
    </nav>

    <div class="container">
        <?php
            require __DIR__ . '/vendor/autoload.php';

            $consumer_key = getenv('CONSUMER_KEY');
            $consumer_secret = getenv('CONSUMER_SECRET');
            $token = getenv('TOKEN');
            $token_secret = getenv('TOKEN_SECRET');

            // Authenticate via OAuth
            $client = new Tumblr\API\Client(
                $consumer_key,
                $consumer_secret,
                $token,
                $token_secret
            );
        
            // Make the request
            $client->getUserInfo();
            foreach ($client->getUserInfo()->user->blogs as $blog) {
                echo '<h1 class="text-center py-4">Welcome, <a href="https://' . $blog->name . '.tumblr.com">' . $blog->name . '</a>!</h1>';
            }

            // Create function to allow a client to get a set of posts
            function get_dashboard_posts($client, $post_start, $limit, $post_type) {
                if ($post_type != NULL) {
                    $dashboard_posts = $client->getDashboardPosts(array('limit' => $limit, 'offset' => $post_start, 'type' => $post_type));
                } else {
                    $dashboard_posts = $client->getDashboardPosts(array('limit' => $limit, 'offset' => $post_start));
                }
                $card_columns = '<div class="card-columns">';
                foreach ($dashboard_posts->posts as $post) {
                    $card_columns .= '<div class="card">';
                    $card_columns .= '<div class="card-header"><a href="' . $post->blog->url . '" target="_blank"><img class="avatar" src="' . $client->getBlogAvatar($post->blog_name, 32) . '"></a><a href="' . $post->blog->url . '">' . $post->blog_name . '</a></div>';
                    if ($post->type == 'photo') {
                        $card_columns .= '<img src="' . $post->photos[0]->original_size->url . '" class="card-img" alt="...">';
                    }
                    if ($post->type == 'video') {
                        $card_columns .= '<video controls poster="'.$post->thumbnail_url.'" class="card-img"><source src="'.$post->video_url.'"></source></video>';
                    }
                    $card_columns .= '<div class="card-body">';
                    if ($post->type == 'photo') {
                        if (isset($post->caption) && $post->caption !== '') {
                            $card_columns .= '<p class="card-text">' . $post->caption . '</p>';
                        }
                    }
                    if ($post->type == 'text') {
                        if (isset($post->title) && $post->title !== '') {
                            $card_columns .= '<p class="card-text">' . $post->title . '</p>';
                        }
                        if (isset($post->body) && $post->body !== '') {
                            $card_columns .= '<p class="card-text">' . $post->body . '</p>';
                        }
                    }
                    if ($post->type == 'answer') {
                        $card_columns .= '<p class="card-text"><b>' . $post->asking_name . '</b>: ' . $post->question . '</p>';
                        $card_columns .= '<hr>';
                        $card_columns .= $post->answer;
                    }
                    if (isset($post->reblog->comment) && $post->reblog->comment !== '') {
                        $card_columns .= '<p class="card-text">' . $post->reblog->comment . '</p>';
                    }
                    // $card_columns .= '<a href="' . $post->post_url . '" class="card-link">Visit Post &rarr;</a>';
                    $card_columns .= '<div class="card-footer d-flex justify-content-between align-items-center p-0"><div class="note-count">' . $post->note_count . ' notes</div>';
                    $card_columns .= '<div class="post-icons"><a href="#"><i data-feather="send"></i></a>';
                    $card_columns .= '<a href="#"><i data-feather="message-square"></i></a>';
                    $card_columns .= '<a href="#"><i data-feather="repeat"></i></a>';
                    $card_columns .= '<a href="#"><i data-feather="heart"></i></a></div></div>';
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
        ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page <= 1 ? 'disabled' : '');?>">
                    <a class="page-link" href="./?page=<?php echo ($page-1); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item <?php echo ($page <= 1 ? 'disabled' : '');?>"><a class="page-link"
                        href="./?page=<?php echo ($page-1); ?>"><?php echo ($page-1); ?></a></li>
                <li class="page-item"><a class="page-link" href="#"><?php echo $page; ?></a></li>
                <li class="page-item"><a class="page-link"
                        href="./?page=<?php echo ($page+1); ?>"><?php echo ($page+1); ?></a></li>
                <li class="page-item">
                    <a class="page-link" href="./?page=<?php echo ($page+1); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <script>
        feather.replace()
    </script>
</body>

</html>
