<?php

    include_once "funcs.php";

    if (isset($_REQUEST['Logout'])) {
        do_logout();
    }

    if (isset($_POST['Login'])) {
        do_login($_POST['username'], $_POST['password']);
    }

?>

<html>
    <form method="post">
        Please Login
        <hr>
        <table>
            <tr>
                <td>Username</td>
                <td><input type="text" name="username" size=20></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" size=20></td>
            </tr>
            <tr>
                <td rowspan=2>
                    <input type="submit" name="Login" value="Login">
                </td>
            </tr>
        </table>
    </form>
</html>
