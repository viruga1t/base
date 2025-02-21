<?php
include_once "../config.php";
include_once "../connect.php";

    $session_id = session_id();
    $user_id = $_SESSION['user_id'];

    $mysqli->query("DELETE FROM sessions WHERE session_id='$session_id' AND user_id='$user_id'");

    session_destroy();
    header("Location: login.php");

exit;
?>
