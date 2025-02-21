<?php
function checkSym (string $field) {
    global $errors;
    $text = $_POST[$field];
    if (!preg_match('/^[a-zA-Z-_.]+$/',$text)) {
        $errors += [$field => 'Имя пользователя должно содержать только буквы и цифры.'];
    }
}
?>