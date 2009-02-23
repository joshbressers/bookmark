<?php
    # $Id$

    include_once "funcs.php";
    include_once "vars.php";

    do_login();

    $username = $_SESSION['username'];

    if (isset($_GET['Delete'])) {
        # Somehow delete the entry
        delete_record_number($username, $_GET['entry']);
        header("Location: {$base_uri}");
    } elseif (isset($_GET['Save'])) {
        # Somehow save a new entry
        update_record_number($username, $_GET['entry'],
            $_GET['var_name'], $_GET['var_val']);
        header("Location: {$base_uri}");
    } elseif (isset($_GET['Move'])) {
        # Move the link
        move_record_number($username, $_GET['entry'],
            $_GET['op']);
        header("Location: {$base_uri}");
    }

    echo "<html><title> {$username}'s " .
        "bookmarks</title>";

    $record = get_record_number($username, $_GET['entry']);

    echo "<form method=get>";

    echo 'Bookmark Title<br>';
    echo '<input type="text" name="var_name" value="' .
    $record[2] . '" size=' . (strlen($record[2]) + 5) .
        '><br><br>';
    if ($record[3] != NULL) {
        echo 'URL<br>';
        echo '<input type="text" name="var_val" value="' .
        $record[3] . '" size=' . (strlen($record[3]) + 5) . '><br>';
    }

    # XXX: Add the logic to show folders here.

    echo "<br><select name='op'>\n";

    foreach (get_folders($username) as $folder) {
        echo "<option value='{$folder[0]}'>{$folder[1]}";
        echo "</option>\n";
    }
    
    echo "</select>\n";
    echo '<hr>';
    echo '<input type=hidden name="entry" value="' . $_GET['entry'] .
        '">';
    echo '<input type=submit name="Save" value="Save">';
    echo '<input type=submit name="Move" value="Move">';
    echo '<input type=submit name="Delete" value="Delete">';
?>
