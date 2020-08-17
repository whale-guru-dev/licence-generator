<?php
session_start();

function authHTML()
{
    // 	if not auhtenticaded session go to login.php
    if (empty($_SESSION['userlogin'])) {
        header('Location: login.php');
        exit();
    }
}

function authAPI()
{
    $user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
    $pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';

    if (!isValidUser($user, $pass)) {
        $_SESSION['userlogin'] = FALSE;
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        die("Not authorized");
    }

    $_SESSION['userlogin'] = $user;
}

function isValidUser($user, $pass)
{
    $conn = mysqli_connect("localhost", "root", "", "lc-gen");
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='" . $user . "' and password = '" . md5($pass) . "'");

    $count = mysqli_num_rows($result);

    if ($count == 0) {
        mysqli_close($conn);
        return FALSE;
    } else {
        $user = mysqli_fetch_row($result);
        $_SESSION['uid'] = $user[0];
        mysqli_close($conn);
        return TRUE;
    }
}

function isValidRegister($email, $pass)
{
    $conn = mysqli_connect("localhost", "root", "", "lc-gen");
    $chechIfAlready = mysqli_query($conn, "SELECT * FROM users WHERE email='" . $email . "'");
    $count = mysqli_num_rows($chechIfAlready);
    if ($count == 0) {
        $query = "INSERT INTO users (email, password) VALUES ('" . $email . "','" . md5($pass) . "')";
        if (mysqli_query($conn, $query)) {
            $uid = mysqli_insert_id($conn);
            $_SESSION['uid'] = $uid;
            mysqli_close($conn);
            return TRUE;
        } else {
            return FALSE;
        }

    } else {
        return FALSE;
    }
}
