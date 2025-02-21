<?php
include_once "config.php";
include_once "connect.php";
include_once "user/auth.php";


$access = 1;

?>
<?php $title = 'Список остатков';
include_once "templates/header.php";
include_once "templates/menu.php";
?>


<?php if ($access==1) { ?>
    <h2 class="title">Остатки <?php echo $shopName; ?></h2>
<br><label for="brandSearch">Поиск по бренду:</label>
<input type="text" id="brandSearch" onkeyup="filterTable()">

<br><label for="modelSearch">Поиск по модели:</label>
<input type="text" id="modelSearch" onkeyup="filterTable()">

<a href="add.php">Добавить новую позицию</a>

<p><table id="productTable">
    <thead>
    <tr>
        <th>Бренд</th>
        <th>Модель</th>
        <th>Остатки</th>
        <th>Цена</th>
        <th>Опт</th>
        <th>БН</th>
        <th>Примечание</th>
        <th>Расположение</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody>
    <?php


    $query_sv = "SELECT * FROM bace WHERE ost != '0' ORDER BY brand, model ASC";
    $stmt_sv = $conn->prepare($query_sv);
    if ($stmt_sv->execute())
    {
        $result_sv = $stmt_sv->get_result();
        while ($row_sv=$result_sv->fetch_assoc()) {
            echo "<tr><td><span class='hidden'>Бренд</span><a href=\"history.php?id=$row_sv[id]\">$row_sv[brand]</a></td>";
            echo "<td><div class='hidden'>Модель</div><a href=\"history.php?id=$row_sv[id]\">$row_sv[model]</a></td>";
            echo "<td><div class='hidden'>Остаток</div>$row_sv[ost]</td>";
            echo "<td><div class='hidden'>Цена</div>$row_sv[price]</td>";
//            echo "<td>$row_sv[opt] ".MinOpt ($conn, $row_sv['brand'], $row_sv['model'])."</td>";
            echo "<td><div class='hidden'>Опт</div>$row_sv[opt]</td>";
            echo "<td><div class='hidden'>БН</div>$row_sv[bn]</td>";
            echo "<td><div class='hidden'>Примечание</div>$row_sv[text]</td>";
            echo "<td><div class='hidden'>Склад</div>$row_sv[position]</td>";
            echo "<td>
                <form action=\"edit.php\" method=\"post\">
                <input type=\"hidden\" name=\"id\" value=\"$row_sv[id]\">
                    <select id=\"type\" name=\"type\">
                        <option value=\"add\">Добавить</option>
                        <option value=\"edit\">Изменить</option>
                        <option value=\"reserve\">Резерв</option>
                        <option value=\"delete\">Удалить</option>
                    </select>
                    <button>Применить</button>
                </form>
                
              </td></tr>";
        }
    }
    $stmt_sv->close();

    ?>


    </tbody>
</table>

<script>
    function filterTable() {
        const brandFilter = document.getElementById("brandSearch").value.toLowerCase();
        const modelFilter = document.getElementById("modelSearch").value.toLowerCase();
        const table = document.getElementById("productTable");
        const rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            const brand = rows[i].getElementsByTagName("td")[0].textContent.toLowerCase();
            const model = rows[i].getElementsByTagName("td")[1].textContent.toLowerCase();
            rows[i].style.display =
                (brand.includes(brandFilter) && model.includes(modelFilter)) ? "" : "none";
        }
    }

    function handleAction(select) {
        const action = select.value;
        const row = select.parentElement.parentElement;
        const brand = row.getElementsByTagName("td")[0].textContent;
        const model = row.getElementsByTagName("td")[1].textContent;

        if (action) {
            alert(`Вы выбрали действие "${action}" для продукта: ${brand} ${model}`);
            select.value = ""; // Сбросить выбор
        }
    }
</script>

<?php } else {
    echo "Доступ запрещен";
}?>

<?php include_once "templates/footer.php"; ?>

