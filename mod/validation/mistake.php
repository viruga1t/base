<?php
function mistake($value,$errors) {
    if (isset($errors[$value])) {
        echo "<div class=\"text-error\">".$errors[$value]."<div>";
    }
}
?>
