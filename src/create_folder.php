<?php
    # $Id$

    include_once "funcs.php";
    include_once "vars.php";

    do_login();

    if (isset($_GET['Create'])) {
        # Somehow delete the entry
        new_folder($_SESSION['username'], $_GET['folder']);
        header("Location: {$base_uri}");
    } elseif (isset($_GET['Back'])) {
        header("Location: {$base_uri}");
    }

    echo "<html><title> {$_SESSION['username']}'s " .
        "bookmarks</title>";

    echo "<form method=get>";

    echo 'Folder Name<br>';
    echo '<input type="text" name="folder" size=20><br><br>';


    echo '<hr>';
    echo '<input type=submit name="Create" value="Create">';
    echo '<input type=submit name="Back" value="Back">';
?>
