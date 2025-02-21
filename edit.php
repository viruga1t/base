<?php
include_once "config.php";
include_once "connect.php";
include_once "user/auth.php";
include_once "mod/validation/mistake.php";
include_once "mod/validation/checkNum.php";


$errors = [];
$access = 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_POST['id'])) {
        $access = 1;
        $id = checkNum($_POST['id']);
        $type = $_POST['type'];
    }

        if (($_SERVER['REQUEST_METHOD']) === 'POST') {
            $query_sv = "SELECT * FROM bace WHERE id = $id";

            $stmt_sv = $conn->prepare($query_sv);
            if ($stmt_sv->execute()) {
                $result_sv = $stmt_sv->get_result();
                while ($row_sv = $result_sv->fetch_assoc()) {

                    $brand = $row_sv['brand'];
                    $model = $row_sv['model'];
                    $price = $row_sv['price'];
                    $opt = $row_sv['opt'];
                    $notes = $row_sv['text'];
                    $location_new = $row_sv['position'];
                    $stock = $row_sv['ost'];
                    $today = date("Y-m-d H:i:s");

                    if (($type === 'edit') and (isset($_POST['acc']) and ($_POST['acc'] === "1"))) {
                        $model_new = $_POST['model'];
                        $payment_new = $_POST['payment'];
                        $notes_new = $_POST['notes'];
                        $newPos= $_POST['location'];
                        $brand_new = $_POST['brand'];
                        $price_new = $_POST['price'];
                        $wholesale_new = $_POST['wholesale'];
                        $stock_new = $_POST['stock'];
                        $query = "UPDATE bace SET brand = '$brand_new', model = '$model_new', price = '$price_new', opt = '$wholesale_new', bn = '$payment_new', text = '$notes_new', position = '$newPos', ost = '$stock_new' WHERE id = $id";
                        $mysqli->query($query);

                        $query = "INSERT INTO base_hystory (id_position, user, comment, ost, date) VALUES ('$id', '$user_id', 'Изменил позицию на ".$stock_new."', '0', '$today')";
                        $mysqli->query($query);
                    }

                    if (($type === 'add') and (isset($_POST['acc']) and ($_POST['acc'] === "1"))) {
                        $stock_new = $_POST['stock'];
                        $stock = $stock + $stock_new;
                        $query = "UPDATE bace SET ost = $stock  WHERE id = $id";
                        $mysqli->query($query);

                        $query = "INSERT INTO base_hystory (id_position, user, comment, ost, date) VALUES ('$id', '$user_id', 'Добавлена', '$stock_new', '$today')";
                        $mysqli->query($query);
                    }

                    if (($type === 'delete') and (isset($_POST['acc']) and ($_POST['acc'] === "1"))) {
                        $stock_new = $_POST['stock'];
                        $stock = $stock - $stock_new;
                        if ($stock>= 0) {
                            $query = "UPDATE bace SET ost = $stock  WHERE id = $id";
                            $mysqli->query($query);

                            $query = "INSERT INTO base_hystory (id_position, user, comment, ost, date) VALUES ('$id', '$user_id', 'Удалено', '$stock_new', '$today')";
                            $mysqli->query($query);

                        } else {
                            $errors += ['Edit' => "Нельзя удалить товара больше, чем есть на складе"];
                        }

                    }

                    if (($type === 'reserve') and (isset($_POST['acc']) and ($_POST['acc'] === "1"))) {
                        $stock_new = $_POST['stock'];
                        $stock = $stock - $stock_new;
                        if ($stock>= 0) {
                            $query = "UPDATE bace SET ost = $stock  WHERE id = $id";
                            $mysqli->query($query);

                            $query = "INSERT INTO base_hystory (id_position, user, comment, ost, date) VALUES ('$id', '$user_id', 'Резерв', '$stock_new', '$today')";
                            $mysqli->query($query);
                        } else {
                            $errors += ['Edit' => "Нельзя поставить в резерв товара больше, чем есть на складе"];
                        }

                    }


                }
            }

        }
$title = 'Редактирование';
$style = "edit";
        include_once "templates/header.php";

?>

<div class="form-container">


    <?php if ($access==1) { ?>
    <form method="post" action="">
    <?php if ($type === 'edit') { ?>


        <label for="brand">Бренд:</label>
        <input type="text" id="brand" name="brand" value="<?php echo $brand; ?>" required>

        <label for="model">Модель:</label>
        <input type="text" id="model" name="model" value="<?php echo $model; ?>" required>

        <label for="stock">Остаток:</label>
        <input type="number" id="stock" name="stock" value="<?php echo $stock; ?>" required>

        <label for="price">Цена:</label>
        <input type="number" id="price" name="price" value="<?php echo $price; ?>" required>

        <label for="wholesale">Опт:</label>
        <input type="number" id="wholesale" name="wholesale" value="<?php echo $opt; ?>" required>

        <label for="payment">Оплата:</label>
        <select id="payment" name="payment" required>
            <option value="1">БН</option>
            <option value="0">Нал</option>
        </select>

        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="type" value="<?php echo $type; ?>">
        <input type="hidden" name="acc" value="1">

        <label for="notes">Примечание:</label>
        <input type="text" id="notes" name="notes" value="<?php echo $notes; ?>">

        <label for="location">Склад:<?php echo $location_new;  ?></label>
        <select id="location" name="location" required>
            <?php
                foreach ($storages as $storage) {
                    echo "<option value=\"$storage\"";
                    echo ($location_new == $storage) ? "selected" : "";
                    echo ">$storage</option>";
                }
            ?>
        </select>

        <?php } ?>

        <?php if ($type === 'add' or $type === 'reserve' or $type === 'delete') { ?>

        <label for="stock">Количество:</label>
        <input type="number" id="stock" name="stock" value="<?php echo $stock; ?>" required>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="type" value="<?php echo $type; ?>">
            <input type="hidden" name="acc" value="1">
        <?php } ?>

        <?php mistake('Edit',$errors); ?>
        <button type="submit">Изменить</button>
        <p><a href="index.php">Вернуться</a>
    </form>
</div>



<?php } else {
    echo "Доступ запрещен";
}?>

</body>