<?php
include_once "config.php";
include_once "connect.php";
include_once "user/auth.php";
include_once "mod/validation/checkNum.php";
include_once "mod/userName.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET' and isset($_GET['id'])) {
    $id = checkNum($_GET['id']);
}

$title = 'История изменение остатков';
include_once "templates/header.php";
include_once "templates/menu.php";
?>
<h2 class="title">Изменение Остатков</h2>

    <ul class="tree" id="inventoryTree">
    <!-- Дерево будет построено динамически с помощью JavaScript -->
</ul>

<script>
    // Пример данных об изменениях остатков
    const inventoryChanges = [
    <?php


    $query_sv = "SELECT * FROM base_hystory WHERE id_position = $id ORDER BY date ASC";
    $stmt_sv = $conn->prepare($query_sv);
    if ($stmt_sv->execute())
    {

        $result_sv = $stmt_sv->get_result();
        while ($row_sv=$result_sv->fetch_assoc()) {
            $user = userName($row_sv['user'], $conn);
            $userName = $user['firstName'].' '.$user['lastName'];
         echo "{ date: '$row_sv[date]', action: '$row_sv[comment]', quantity: $row_sv[ost], user: '$userName' },";
        }

    }
    ?>
    ];


    // Функция для построения дерева
    function buildTree(data) {
        const tree = document.getElementById('inventoryTree');

        // Корневой узел (например, "Остаток на начало")
        const rootLi = document.createElement('li');
        const rootSpan = document.createElement('span');
        rootSpan.textContent = 'Остаток на начало: 0 единиц';
        rootLi.appendChild(rootSpan);
        tree.appendChild(rootLi);

        let currentBalance = 0;

        data.forEach(change => {
            const dateLi = document.createElement('li');

            // Иконка для разворачивания/сворачивания
            const toggleSpan = document.createElement('span');
            toggleSpan.textContent = '+';
            toggleSpan.classList.add('toggle');
            dateLi.appendChild(toggleSpan);

            // Обновляем баланс и отображаем его сразу
            if (change.action === 'Добавлена' || change.action === 'Возвращено') {
                currentBalance += change.quantity;
            } else if (change.action === 'Удалено' || change.action === 'Резерв') {
                currentBalance -= change.quantity;
            }

            // Создаем заголовок узла с общей информацией
            const dateSpan = document.createElement('span');
            dateSpan.textContent = `${change.date}: ${change.action} ${change.quantity} единиц (Остаток: ${currentBalance} единиц)`;
            dateLi.appendChild(dateSpan);

            // Обработчик клика для разворачивания/сворачивания
            dateLi.addEventListener('click', function(e) {
                e.stopPropagation(); // Предотвращаем всплытие события
                this.classList.toggle('open');
                const toggle = this.querySelector('.toggle');
                toggle.textContent = this.classList.contains('open') ? '-' : '+';
            });

            // Создаем вложенный список для деталей изменения
            const detailsUl = document.createElement('ul');

            const userLi = document.createElement('li');
            const userSpan = document.createElement('span');
            userSpan.textContent = `Изменил: ${change.user}`;
            userLi.appendChild(userSpan);

            detailsUl.appendChild(userLi);
            dateLi.appendChild(detailsUl);

            tree.appendChild(dateLi);
        });
    }

    // Вызов функции для построения дерева с данными
    buildTree(inventoryChanges);
</script>

<?php
include_once "templates/footer.php";
?>

