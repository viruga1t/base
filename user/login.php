<?php
include_once "../config.php";
include_once "../connect.php";

$errors = [];

include_once "../mod/validation/minCharacters.php";
include_once "../mod/validation/maxCharacters.php";
include_once "../mod/validation/checkNumSym.php";
include_once "../mod/validation/mistake.php";

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username'])) {
        checkNumSym ('username');
        minCharacters ('username', 3);
        maxCharacters('username', 12);
    }

    if (isset($_POST['password'])) {
        minCharacters('password', 6);
        maxCharacters('password', 20);
    }

    if (empty($errors)) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and  (isset($_POST['username']))) {
            $username = $mysqli->real_escape_string($_POST['username']);
            $password = $mysqli->real_escape_string($_POST['password']);
            $result = $mysqli->query("SELECT * FROM users WHERE login='$username'");

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    echo 'OK - password verify';

                    if ($user['is_block'] == '1') {
                        header("Location: block.php");
                        exit;
                    }

                    $_SESSION['user_id'] = $user['id'];
                    $token = generateToken();
                    $_SESSION['token'] = $token;

                    $is_hr = $user['is_hr'];
                    $is_admin = $user['is_admin'];
                    $_SESSION['is_hr'] = $is_hr;

                    $session_id = session_id();
                    $user_id = $user['id'];

                    $mysqli->query("INSERT INTO sessions (session_id, user_id, token, is_admin, is_hr) VALUES ('$session_id', '$user_id', '$token', '$is_admin', '$is_hr')");


                    header("Location: ../index.php");
                    exit;
                } else {
                    echo "Invalid password";
                       header("Location: login.php");
                    exit;
                }
            } else {
                $errors += ['Auth' => "Такой пользователь не зарегистрирован!"];
            }
        }

    }

}


$title = "Панель входа...";
$style = "form";
include_once "../templates/header.php";
?>
<div class="login-container">
    <h2>Вход</h2>
    <form action="" method="post">
        <input type="text" class="input-field" placeholder="Login" name="username" />
        <?php mistake('username',$errors); ?>
        <input type="password" class="input-field" placeholder="Password" name="password"  />
        <?php mistake('password',$errors); ?>
        <?php mistake('Auth',$errors); ?>
        <button class="login-button">Enter</button>
    </form>
    <div class="login-text"><a href="registration.php">Регистрация</a></div>
</div>
</body>
</html>