<?php

    session_start();

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
        header("Location: ../user/login.php");
        exit;
    }

    $session_id = session_id();
    $user_id = $_SESSION['user_id'];
    $token = $_SESSION['token'];
    $is_hr = $_SESSION['is_hr'];


    $result = $mysqli->query("SELECT * FROM sessions WHERE session_id='$session_id' AND user_id='$user_id' AND token='$token' AND is_hr='$is_hr'");

    if ($result->num_rows != 1) {

        session_destroy();
        header("Location: login.php");
        exit;
    }


?>