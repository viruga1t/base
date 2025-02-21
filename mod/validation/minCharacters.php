<?php
function minCharacters (string $field, int $number) {
    global $errors;
    $text = $_POST[$field];
    if (strlen(trim($text))<=$number) {
        $errors += [$field => "Длина поля должна быть более $number символов."];
    }
}
?>
