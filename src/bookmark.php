<?php
    # $Id$

    include_once "funcs.php";
    include_once "vars.php";

    # Do some sort of authentication here.

    do_login();

    $username = $_SESSION['username'];
    $edit_mode = false;

    # Are we setting a new bookmark, or are we just looking?
    if (isset($_GET['url'])) {
        add_bookmark($username, $_GET['url'], $_GET['title']);
        header("Location: {$_GET['url']}");
        exit(0);
    } elseif (isset($_GET['move'])) {
        if ($_GET['move'] == 'u' or $_GET['move'] == 'd') {
            move_bookmark($username, $_GET['entry'], $_GET['move']);
            $edit_mode = true;
        }
    }

    echo "<html><title> {$_SESSION['username']}'s " .
        "bookmarks</title>";
    echo '<link rel="StyleSheet" href="dtree.css" type="text/css" />';
    echo '<script type="text/javascript" src="dtree.js"></script>';

    echo $set_bookmark;

    echo '<body><div class="dtree">';
    echo '<p><a href="javascript: d.openAll();">open all</a> ';
    echo '| <a href="javascript: d.closeAll();">close all</a></p>';
    echo '<script type="text/javascript">';
    echo "\nd = new dTree('d');\n";
    echo "d.add(0, -1, 'Bookmarks');\n";

    if (isset($_GET['edit'])) {
        $edit_mode = true;
    }

    $bookmarks = get_bookmarks($username);

    # Build the bookmark structure on the screen

    foreach ($bookmarks as $bookmark) {
        echo "d.add(";
        echo $bookmark[0];
        echo ",";
        echo $bookmark[1];
        echo ",'";
        if ($edit_mode) {
            echo htmlspecialchars($bookmark[2], ENT_QUOTES);
            echo "<a href=bookmark.php?entry={$bookmark[0]}&move=u>[up]</a> ";
            echo "<a href=bookmark.php?entry={$bookmark[0]}&" .
                "move=d>[down]</a>";
            echo "','";
            echo "edit_bookmark.php?entry={$bookmark[0]}";
        } else {
            echo htmlspecialchars($bookmark[2], ENT_QUOTES);
            echo "','";
            echo $bookmark[3];
        }
        echo "');\n";
    }

    echo "document.write(d);\n";
    echo "</script>\n";
    echo "<hr>\n";
    echo "<a href={$_SERVER['SCRIPT_URI']}?edit=1>[Edit]</a>&nbsp;&nbsp;";
    echo "<a href=create_folder.php>[New Folder]</a>&nbsp";
    echo "<a href=\"login.php?Logout=true\">[Logout]</a>&nbsp";

    if ($edit_mode) {
        echo "<a href={$_SERVER['SCRIPT_URI']}>[Back]</a>";
    }

?>
</body>
</html>
