function like(post_id, reblog_key) {
    $.ajax({
        url: "action.php?action=like&post_id=" + post_id + "&reblog_key=" + reblog_key,
        success: function(result){
            if (result == "success") {
                $("a[data-id='" + post_id + "'] svg").css("fill", "#dc3545");
            } else {
                alert("Error: Failed to like post.");
            }
        }
    });
}

function unlike(post_id, reblog_key) {
    $.ajax({
        url: "action.php?action=unlike&post_id=" + post_id + "&reblog_key=" + reblog_key,
        success: function(result){
            if (result == "success") {
                $("a[data-id='" + post_id + "'] svg").css("fill", "none");
            } else {
                alert("Error: Failed to unlike post.");
            }
        }
    });
}

function follow(blog_name, uuid) {
    $.ajax({
        url: "action.php?action=follow&blog_name=" + blog_name,
        success: function(result){
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
        url: "action.php?action=unfollow&blog_name=" + blog_name,
        success: function(result){
            if (result == "success") {
                alert("Successfully unfollowed blog.");
                $("a[data-follow-id='" + uuid + "']").css("display", "none");
            } else {
                alert("Error: Failed to unlike post.");
            }
        }
    });
}