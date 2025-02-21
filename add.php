<?php
include_once "config.php";
include_once "connect.php";
include_once "user/auth.php";
include_once "mod/validation/mistake.php";

$errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $brand = $_POST['brand'];
            $model = $_POST['model'];
            $stock = $_POST['stock'];
            if (!is_numeric($stock)) {
                $errors += ["stock" => "Поле должно быть числом!"];
            }
            $price = $_POST['price'];
            if (!is_numeric($stock)) {
                $errors += ["price" => "Поле должно быть числом!"];
            }

            $wholesale = $_POST['wholesale'];
            if (!is_numeric($stock)) {
                $errors += ["wholesale" => "Поле должно быть числом!"];
            }

            $payment = $_POST['payment'];
            $notes = $_POST['notes'];
            $location = $_POST['location'];

            //add user
            if (empty($errors)) {

                $query = "INSERT INTO bace (brand, model, ost, price, opt, bn, text, position) VALUES ('$brand', '$model', '$stock', '$price', '$wholesale', '$payment', '$notes', '$location')";
                $mysqli->query($query);


                $lastId = $mysqli->insert_id;
                $today = date("Y-m-d H:i:s");

                $query = "INSERT INTO base_hystory (id_position, user, comment, ost, date) VALUES ('$lastId', '$user_id', 'Добавлена', '$stock', '$today')";
                $mysqli->query($query);
            }


        }
$title = 'Редактирование';
$style = "edit";
include_once "templates/header.php";

?>

<div class="form-container">
    <h2>Добавить новый товар</h2>


    <form method="post" action="">
        <label for="brand">Бренд:</label>
        <input type="text" id="brand" name="brand" required>

        <label for="model">Модель:</label>
        <input type="text" id="model" name="model" required>

        <label for="stock">Остаток:</label>
        <input type="number" id="stock" name="stock" required>
        <?php mistake('stock',$errors); ?>

        <label for="price">Цена:</label>
        <input type="text" id="price" name="price" required>
        <?php mistake('price',$errors); ?>

        <label for="wholesale">Опт:</label>
        <input type="text" id="wholesale" name="wholesale" required>
        <?php mistake('wholesale',$errors); ?>

        <label for="payment">Оплата:</label>
        <select id="payment" name="payment" required>
            <option value="1">БН</option>
            <option value="0">Нал</option>
        </select>

        <label for="notes">Примечание:</label>
        <input type="text" id="notes" name="notes">

        <label for="location">Склад:</label>
        <select id="location" name="location" required>
            <?php
            foreach ($storages as $storage) {
                echo "<option value=\"$storage\">$storage</option>";
            }
            ?>
        </select>

        <button type="submit">Добавить товар</button>
        <p><a href="index.php">Вернуться</a>
    </form>
</div>



</body>
</html>
