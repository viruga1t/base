<?php
include_once "config.php";
include_once "connect.php";
include_once "user/auth.php";
include_once "mod/validation/checkNum.php";
include_once "mod/userName.php";
$user = userName($user_id, $conn);
$userName = $user['firstName'].' '.$user['lastName'];

         if ((isset($_POST['type'])) and (isset($_POST['id_h'])) and (isset($_POST['id_p'])) and (isset($_POST['ost']))) {
            $type = $_POST['type'];
            $today = date("Y-m-d H:i:s");
            $id_h = $_POST['id_h'];
            $id_p = $_POST['id_p'];
            $ost = 0;
            $ost = $_POST['ost'];

            if ($type === 'sale') {

                $query = "UPDATE base_hystory SET comment = 'Удалено' WHERE id = $id_h";


                $mysqli->query($query);

            } else if ($type === 'add') {

                $query = "DELETE FROM base_hystory WHERE id = $id_h";

                $mysqli->query($query);

                $query = "UPDATE bace SET ost = ost + $ost WHERE id = $id_p";

                $mysqli->query($query);

            } else {
                echo "Что-то пошло не так";
            }

        }
         $title = "Мои резервы";
         $style = "reserve";
include_once "templates/header.php";
include_once "templates/menu.php";
?>

<h2 class="title">Мои резервы</h2>



<table id="reserveTable">
    <thead>
    <tr>
        <th>Бренд</th>
        <th>Модель</th>
        <th>Комментарий</th>
        <th>Дата</th>
        <th>Кол-во</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody>

    <?php

    $query = "
    SELECT 
        b.brand, 
        b.model, 
        h.ost, 
        h.date,
        b.id AS b_id,
        h.id AS h_id
    FROM 
        base_hystory h
    INNER JOIN 
        bace b 
    ON 
        h.id_position = b.id
    WHERE
        h.comment = 'Резерв' and h.user = '$user_id'        
    ";

    // Выполнение запроса
    $result = $conn->query($query);

    // Проверка результатов и вывод таблицы
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ost = $row['ost'];
            $id_h = $row['h_id'];
            $id_p = $row['b_id'];

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['brand']) . "</td>";
            echo "<td>" . htmlspecialchars($row['model']) . "</td>";
            echo "<td></td>";
            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ost']) . "</td>";
            echo '<td class="res__button">
            <form action="#" method="post">
                <input type="hidden" name="id_h" value="'.$id_h.'">
                <input type="hidden" name="id_p" value="'.$id_p.'">
                <input type="hidden" name="type" value="sale">
                <input type="hidden" name="ost" value="'.$ost.'">
                <button class="reserve">Реализовать резерв</button>
            </form>
            <form action="#" method="post">
                <input type="hidden" name="id_h" value="'.$id_h.'">
                <input type="hidden" name="id_p" value="'.$id_p.'">
                <input type="hidden" name="type" value="add">
                <input type="hidden" name="ost" value="'.$ost.'">
                <button class="unreserve">Снять резерв</button>
            </form>
                   </td>';
            echo "</tr>";
        }
    }
    ?>



    </tbody>
</table>

</body>
</html>

