<?php
//Подключение к БД

$conn = new mysqli ($serverName, $userName, $password, $dbName);
if ($conn->connect_errno) {
    echo 'Failed to connect';
    exit();
}

$mysqli = mysqli_connect($serverName, $userName, $password, $dbName);
mysqli_set_charset($mysqli, "utf8");

if ($mysqli->connect_errno) {
    echo "MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
?>
