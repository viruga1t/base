<?php
include_once "../config.php";
include_once "../connect.php";


$errors = [];

include_once "../mod/validation/minCharacters.php";
include_once "../mod/validation/maxCharacters.php";
include_once '../mod/validation/checkNumSym.php';
include_once "../mod/validation/checkSym.php";
include_once "../mod/validation/mistake.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username'])) {
        checkNumSym ('username');
        minCharacters ('username', 3);
        maxCharacters('username', 12);
    }

    if (isset($_POST['password']) and isset($_POST['confirm_password'])) {
        if ($_POST['password']!==$_POST['confirm_password']) {
            $errors += ['password' => 'Поля Пароль и Подтверждение пароля не совпадают.'];
        } else {
            minCharacters('password', 6);
            maxCharacters('password', 20);
        }
    }

    if (isset($_POST['firstname'])) {
        checkSym('firstname');
        minCharacters('firstname',3);
        maxCharacters('firstname',12);
    }

    if (isset($_POST['lastname'])) {
        checkSym('lastname');
        minCharacters('lastname', 3);
        maxCharacters('lastname',20);
    }

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors += ['email' => 'Поле электронной почты неверно.'];
        }
    }

    if (empty($errors)) {
        $username = $mysqli->real_escape_string($_POST['username']);
        $result = $mysqli->query("SELECT * FROM users WHERE login='$username'");
        if ($result->num_rows == 1) {
            $errors += ['username' => 'Такой логин уже существует.'];
        } else {
            $newpassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (login, firsname, lastname, email, password, is_admin, is_hr, is_block) VALUES ('$username', '$_POST[firstname]', '$_POST[lastname]', '$email', '$newpassword', '0', '0', '1')";
            $mysqli->query($sql);
            header("Location: login.php");
            exit;
        }
    }
}
$title = "Регистрация пользователя";
$style = "form";
include_once "../templates/header.php";
?>

<div class="login-container">
    <h2>Регистрация</h2>
    <form action="" method="post">
        <input type="text" class="input-field" placeholder="Login" <?php if (isset($username)) {echo "value=\"".$username."\"";} ?> name="username" />
        <?php mistake('username',$errors); ?>
        <input type="text" class="input-field" placeholder="First Name" <?php if (isset($firsname)) {echo "value=\"".$firsname."\"";} ?> name="firstname" />
        <?php mistake('firstname',$errors); ?>
        <input type="text" class="input-field" placeholder="Last Name" <?php if (isset($lastname)) {echo "value=\"".$lastname."\"";} ?> name="lastname" />
        <?php mistake('lastname',$errors); ?>
        <input type="password" class="input-field" placeholder="Password" name="password"  />
        <?php mistake('password',$errors); ?>
        <input type="password" class="input-field" placeholder="Confirm password" name="confirm_password"  />
        <input type="text" class="input-field" placeholder="E-Mail" <?php if (isset($email)) {echo "value=\"".$email."\"";} ?> name="email" />
        <?php mistake('email',$errors); ?>
        <button class="login-button">Регистрация</button>
    </form>
    <div class="login-text"><a href="login.php">Вход</a></div>
</div>
</body>
</html>
