<?php
function checkNum ($number) {
    if (!preg_match('/^[0-9]+$/',$number)) {
        echo "<p>Ошибка!";
    } else {
        return $number;
    }
}
?>