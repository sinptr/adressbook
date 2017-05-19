<?php include("methods.php");
session_start();
if (!isset($_SESSION['showMode']))
    $_SESSION['showMode'] = 'showAll';
?>
<html>
<head>
    <meta charset="UTF-8">
    <h1 align="center">Адресная книга</h1>
</head>
<body>
    <div align="center" style="border: 2px solid black">
        <h3>Добавить контакт</h3>
        <?php
            if ($_SESSION['ERROR_MSG'])
                echo "<h4 style='color: crimson'>{$_SESSION['ERROR_MSG']}</h4>";
        ?>
        <form action="methods.php" method="post">
            <div>Фамилия: <input style="margin-left: 62px" type="text" name="LAST_NAME" value="<?php echo $_GET['last_name']?>"/></div>
            <div>Имя: <input style="margin-left: 104px" type="text" name="NAME" value="<?php echo $_GET['name']?>"/></div>
            <div>Номер телефона: <input type="text" name="PHONE" value="<?php echo $_GET['phone']?>"/></div>
            <br>
            <button type="submit" name="butt" value="1">Сохранить</button>
        </form>
    </div>
    <div align="center"  style="margin-top: 2px; border: 2px solid black">
        <h3>Поиск</h3>
        <?php
        if ($_GET['ERROR_MSG'])
            echo "<h4 style='color: crimson'>{$_GET['ERROR_MSG']}</h4>"
        ?>
        <form action="methods.php" method="post">
            <div>Фамилия: <input style="margin-left: 62px" type="text" name="S_LAST_NAME"/></div>
            <br>
            <button type="submit" name="butt" value="2">Найти</button>
        </form>
    </div>
    <div align="center"  style="margin-top: 2px; border: 2px solid black">
        <table border="1" width="666px">
            <caption>Список контактов</caption>
            <tr>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Действие</th>
            </tr>
            <?php
                if ($_SESSION['showMode'] === 'search')
                {
                    $list = $_SESSION['foundName'];
                }
                if ($_SESSION['showMode'] === 'showAll')
                {
                    $list = getList();
                }
                if ($list)
                foreach ($list as $id => $element)
                {
                    echo "<tr><td>{$element['LAST_NAME']}</td><td>{$element['NAME']}</td><td>{$element['PHONE']}</td><td><a href='methods.php?delete=1&id={$id}'>Удалить</a></td></tr>";
                }
            ?>
        </table>

        <?php if ($_SESSION['showMode'] === 'search'): ?>
            <form action="methods.php" method="post">
                <button type="submit" name="butt" value="5">Показать все</button>
            </form>
        <?php endif ?>
    </div>
</body>
</html>