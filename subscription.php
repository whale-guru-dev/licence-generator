<?php
session_start();

if($_POST['type'] == 'purchase') {
    $pType = $_POST['pType'];
//    free, month, year
    $uid = $_SESSION['uid'];
    $lkey = rand(1000,9999) . '-' . rand(1000,9999) . '-' . rand(1000,9999) . '-' . rand(1000,9999) . '-' . rand(1000,9999);
    $purchased = date('Y-m-d');
    if($pType == 'free') {
        $expired = date('Y-m-d', strtotime('+1 years'));
    } else if($pType=='month'){
        $expired = date('Y-m-d', strtotime('+1 months'));
    } else if($pType=='year') {
        $expired = date('Y-m-d', strtotime('+1 years'));
    }


    //$conn = mysqli_connect("localhost", "root", "", "lc-gen");
    $conn = mysqli_connect("localhost", "lcgen", "SyEAY-d-2m", "lcgen");
    $query = "INSERT INTO licence (uid, lKey, pType, purchased, expired) VALUES ('" . $_SESSION['uid'] . "','" . $lkey . "','".$pType."','".$purchased."','".$expired."')";
    if (mysqli_query($conn, $query)) {
        mysqli_close($conn);
        header('Location: index.php');
        exit();
    } else {
        mysqli_close($conn);
        header('Location: index.php');
        exit();
    }
} else if($_POST['type'] == 'repurchase') {
    //$conn = mysqli_connect("localhost", "root", "", "lc-gen");
    $conn = mysqli_connect("localhost", "lcgen", "SyEAY-d-2m", "lcgen");
    $result = mysqli_query($conn, "SELECT * FROM licence WHERE uid='" . $_SESSION['uid'] . "'");
    $count = mysqli_num_rows($result);

    if ($count == 0) {
        mysqli_close($conn);
        header('Location: index.php');
        exit();
    } else {
        if (mysqli_query($conn, "DELETE FROM licence WHERE uid='" . $_SESSION['uid'] . "'")) {
            mysqli_close($conn);
            header('Location: index.php');
            exit();
        } else {
            mysqli_close($conn);
            header('Location: index.php');
            exit();
        }
    }
}