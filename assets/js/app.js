function like(post_id, reblog_key) {
    $.ajax({
        url: "https://cleberg.io/vox-populi/action.php?action=like&post_id=" + post_id + "&reblog_key=" + reblog_key,
        success: function (result) {
            if (result == "success") {
                $("a[data-like-id='" + post_id + "'] svg").css("fill", "#dc3545");
            } else {
                alert("Error: Failed to like post.");
            }
        }
    });
}

function unlike(post_id, reblog_key) {
    $.ajax({
        url: "https://cleberg.io/vox-populi/action.php?action=unlike&post_id=" + post_id + "&reblog_key=" + reblog_key,
        success: function (result) {
            if (result == "success") {
                $("a[data-unlike-id='" + post_id + "'] svg").css("fill", "none");
            } else {
                alert("Error: Failed to unlike post.");
            }
        }
    });
}

function follow(blog_name, uuid) {
    $.ajax({
        url: "https://cleberg.io/vox-populi/action.php?action=follow&blog_name=" + blog_name,
        success: function (result) {
            if (result == "success") {
                $("a[data-follow-id='" + uuid + "']").css("display", "none");
            } else {
                alert("Error: Failed to like post.");
            }
        }
    });
}

function unfollow(blog_name, uuid) {
    $.ajax({
        url: "https://cleberg.io/vox-populi/action.php?action=unfollow&blog_name=" + blog_name,
        success: function (result) {
            if (result == "success") {
                alert("Successfully unfollowed blog.");
                $("a[data-follow-id='" + uuid + "']").css("display", "none");
            } else {
                alert("Error: Failed to unlike post.");
            }
        }
    });
}

function reblog(blog_name, id, reblog_key) {
    $.ajax({
        url: "https://cleberg.io/vox-populi/action.php?action=reblog&blog_name=" + blog_name + "&id=" + id + "&reblog_key" + reblog_key,
        success: function (result) {
            if (result == "success") {
                alert("Successfully reblogged post.");
                $("a[data-reblog-id='" + post_id + "'] svg").css("fill", "#dc3545");
            } else {
                alert("Error: Failed to reblog post.");
            }
        }
    });
}

function unreblog(blog_name, id, reblog_key) {
    $.ajax({
        url: "https://cleberg.io/vox-populi/action.php?action=unreblog&blog_name=" + blog_name + "&id=" + id + "&reblog_key" + reblog_key,
        success: function (result) {
            if (result == "success") {
                alert("Successfully reblogged post.");
                $("a[data-unreblog-id='" + post_id + "'] svg").css("fill", "none");
            } else {
                alert("Error: Failed to reblog post.");
            }
        }
    });
}

function toggleNav() {
    // If we're on a small screen (sidebar is hidden on small screens)
    if ($(window).width() <= 768) {
        // Show the nav button and allow it to open the sidebar
        $('#sidebarButton').click(function () {
            // If the sidebar is showing...
            if ($('.sidebar').width() >= $(document).width()) {
                // ... then remove the sidebar
                $('.sidebar').width('0%');
                $('.sidebar').addClass('d-none');
                // ... and increase the size of the main area
                $('.main').width('100%');
                $('.main').removeClass('d-none');
            }
            // If the sidebar is hidden...
            else {
                // ... then add the sidebar
                $('.sidebar').width('100%');
                $('.sidebar').removeClass('d-none');
                // ... and remove the main area
                $('.main').width('0%');
                $('.main').addClass('d-none');
            }
        });

        // Show the nav button and allow it to open the sidebar
        $('.sidebar-box a').click(function () {
            // If the sidebar is showing...
            if ($('.sidebar').width() >= $(document).width()) {
                // ... then remove the sidebar
                $('.sidebar').width('0%');
                $('.sidebar').addClass('d-none');
                // ... and increase the size of the main area
                $('.main').width('100%');
                $('.main').removeClass('d-none');
            }
        });
    }
}

function toggleSearch() {
    // If we're on a small screen, we want to take up the whole nav
    if ($(window).width() <= 768) {
        $('#search-button').click(function () {
            $('#search-button').addClass('d-none');
            $('.navbar-brand').addClass('d-none');
            $('#search-form').removeClass('d-none');
        });
        $('#search-close-button').click(function () {
            $('#search-button').removeClass('d-none');
            $('.navbar-brand').removeClass('d-none');
            $('#search-form').addClass('d-none');
        });
    } else {
        $('#search-button').click(function () {
            $('#search-button').addClass('d-none');
            $('#search-form').removeClass('d-none');
        });
        $('#search-close-button').click(function () {
            $('#search-button').removeClass('d-none');
            $('#search-form').addClass('d-none');
        });
    }
}

$(document).ready(function () {
    feather.replace({"height": 16,"width": 16});

    $('.btn-to-top').click(function () {$(window).scrollTop(0);});

    toggleNav();
    toggleSearch();
});
