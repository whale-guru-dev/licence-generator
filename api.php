<?php
// This file just demonstrates the Basic Authentication acessing an API.
require_once 'auth.php';

header('Content-Type: application/json');

if (!empty($_POST['email']) && !empty($_POST['password'])) {
    //$conn = mysqli_connect("localhost", "root", "", "lc-gen");
    $conn = mysqli_connect("localhost", "lcgen", "SyEAY-d-2m", "lcgen");
    $authResult = mysqli_query($conn, "SELECT * FROM users WHERE email='" . $_POST['email'] . "'");

    $authCount = mysqli_num_rows($authResult);

    if ($authCount == 0) {
        mysqli_close($conn);
        echo json_encode(array('auth' => false, 'msg' => 'There is no user.'));
    } else {
        $user = mysqli_fetch_row($authResult);
        if($user[2] != md5($_POST['password'])) {
            echo json_encode(array('auth' => false, 'msg' => 'Incorrect Password'));
        } else {
            $licenceResult = mysqli_query($conn, "SELECT * FROM licence WHERE uid='" . $user[0] . "'");
            $licenceCount = mysqli_num_rows($licenceResult);

            if ($licenceCount == 0) {
                echo json_encode(array('auth' => true, 'msg' => 'There is no plan.'));
            } else {
                $licence = mysqli_fetch_row($licenceResult);
                $now = date('Y-m-d');

                if (strtotime($licence[5]) - time() < 0) {
                    echo json_encode(array('auth' => true, 'plan' => $licence[3],'msg' => 'Your plan is expired.'));
                } else {
                    $now = time(); // or your date as well
                    $expired = strtotime($licence[5]);
                    $datediff = $expired - $now;

                    echo json_encode(array('auth' => true, 'plan' => $licence[3],'msg' => $datediff.' days left.'));
                }
            }
        }

        mysqli_close($conn);
    }
}

echo json_encode(array('Hello' => 'World', 'version' => '1.0'));
