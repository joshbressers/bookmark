<?php
    # $Id$

    $base_uri = 'http://your.site/url/bookmark.php';
    $user_found = false;

    # Create the string to display the links for this page.
    $set_bookmark = 'Here are the links you will need to set and view' .
        ' your bookmarks.<br><a href="javascript:void(location.href=\'' .
        $_SERVER["SCRIPT_URI"] . '?url=\'+location.href+\'&title=\'+' .
        'document.title)">Set bookmark</a><br><a href="' .
        $_SERVER["SCRIPT_URI"] . '">View bookmarks</a><hr>';
?>
