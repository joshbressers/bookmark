<?php
    # $Id: funcs.php 277 2004-10-16 17:53:40Z bress $

    include_once "vars.php";
    include_once "DB.php";

    $dsn = "mysql://bookmark:bookmark@localhost/bookmark";
    $db = DB::connect($dsn);
    if (DB::isError($db))
        die("Can't connect to database");

    function do_login() {
        global $db;

        # Do some sort of authentication here.
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            # The user hasn't authenticated yet, complain to them.

            show_login();

        } else {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];

            $sth = $db->prepare("SELECT * FROM users where username=?"
                . " AND password=?");
            $result =& $db->execute($sth, array($username, $password));

            if (DB::isError($result))
                   die ("Foo SELECT failed: " . $result->getMessage() . "\n");

            # Find the record we want.
            if ($result->numRows() == 1) {
                    $user_found = true;
            }

            if ($user_found) {
                # Do nothing right now.
            } else {
                show_login();
            }
        }
    }

    function show_login() {
        header('WWW-Authenticate: Basic realm="Bookmarks"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'You must login';
        exit;
    }

    function get_bookmarks($username) {
        global $db;
        $bookshelf = array();   # Damn I'm clever

        $query = 'SELECT bookmarks.id, bookmarks.folder_id, '
                . 'bookmarks.name, bookmarks.url'
                . ' FROM bookmarks, users'
                . ' WHERE bookmarks.user_id = users.id AND'
                . " users.username = ?"
                . " ORDER BY order_weight";

        $sth = $db->prepare($query);
        $result =& $db->execute($sth, $username);

        if (DB::isError($result))
            die ("get_bookmark SELECT failed: "
                . $result->getMessage() . "\n");

        while ($row = $result->fetchRow()) {
            array_push($bookshelf, $row);
        }

        return $bookshelf;
    }

    function get_folders($username) {
        global $db;
        $bookshelf = array();

        $query = 'SELECT bookmarks.id, bookmarks.name '
                . ' FROM bookmarks, users'
                . ' WHERE bookmarks.user_id = users.id AND'
                . ' bookmarks.url = \'\' AND'
                . " users.username = ?";

        $sth = $db->prepare($query);
        $result =& $db->execute($sth, $username);

        while ($row = $result->fetchRow()) {
            array_push($bookshelf, $row);
        }

        return $bookshelf;
    }

    function get_record_number($username, $record_number) {
        global $db;
        $bookmarks = get_bookmarks($username);

        $found_record = NULL;

        $query = 'SELECT bookmarks.id, bookmarks.folder_id, '
                . 'bookmarks.name, bookmarks.url'
                . ' FROM bookmarks, users'
                . ' WHERE bookmarks.user_id = users.id AND'
                . ' bookmarks.id = ? AND'
                . " users.username = ?";

        $sth = $db->prepare($query);
        $result =& $db->execute($sth, array($record_number, $username));

        if ($result->numRows() == 1) {
                $found_record = $result->fetchRow();
        }

        return $found_record;
    }

    function update_record_number($username, $record_number, $name,
                                            $url) {

        global $db;
        $query = 'UPDATE bookmarks SET name=?'
            . ", url=?"
            . " WHERE id=?";

        $sth = $db->prepare($query);
        $result =& $db->execute($sth, array($name, $url, $record_number));

        if (DB::isError($result))
            die ("update_record_number failed: "
                . $result->getMessage() . "\n");

    }

    function move_bookmark($username, $record_number, $direction) {

        global $db;
        $move_value = 0;

        if ($direction == 'u') {
            $move_value = -1;
        } elseif ($direction == 'd') {
            $move_value = 1;
        }

        $query = "UPDATE bookmarks SET order_weight = order_weight + ?"
            . " WHERE id = ?";

        $sth = $db->prepare($query);
        $result =& $db->execute($sth, array($move_value, $record_number));

    }

    function delete_record_number($username, $record_number) {
        global $db;
        $query = 'DELETE FROM bookmarks WHERE id=?';
        $sth = $db->prepare($query);
        $result =& $db->execute($sth, $record_number);

    }

    function new_folder($username, $folder_name) {
        global $db;
        $query = 'SELECT id from users where username = ?';
        $sth = $db->prepare($query);
        $result =& $db->execute($sth, $username);
        $found_record = $result->fetchRow();
        $user_id = $found_record[0];

        $query = 'INSERT INTO bookmarks (name, user_id) VALUES (?, ?)';
        $sth = $db->prepare($query);
        $result =& $db->execute($sth, array($folder_name, $user_id));
    }

    function add_bookmark($username, $url, $title) {
        global $db;
        $query = 'SELECT id from users where username = ?';
        $sth = $db->prepare($query);
        $result =& $db->execute($sth, $username);
        $found_record = $result->fetchRow();
        $user_id = $found_record[0];

        $query = 'INSERT INTO bookmarks (name, url, user_id) ' .
            'VALUES (?, ?, ?)';
        $sth = $db->prepare($query);
        $result =& $db->execute($sth, array($title, $url, $user_id));
    }

    function move_record_number($username, $record_number, $move_number) {
        global $db;
        $query = 'UPDATE bookmarks SET folder_id=? WHERE id=?';
        $sth = $db->prepare($query);
        $result =& $db->execute($sth, array($move_number, $record_number));
    }

?>
