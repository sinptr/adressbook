<?php include("methods.php"); ?>
<html>
<head>
    <meta charset="UTF-8">
    <h1 align="center">Адресная книга</h1>
</head>

<?php
    if (isset($_REQUEST['delete'])):
        delete($_REQUEST['delete']);
        header("Location: index.php");
        endif;
?>

<body>
    <div align="center" style="border: 2px solid black">
        <h3>Добавить контакт</h3>
        <p style="color: red">
            <?php
                if ($_REQUEST['save'])
                {
                    $errors = save($_POST['LAST_NAME'], $_POST['NAME'], $_POST['PHONE']);
                    if ($errors === true)
                    {
                        header("Location: index.php");
                    }
                    else
                    {
                        foreach ($errors as $err)
                        {
                            echo $err."<br />";
                        }
                    }
                }
            ?>
        </p>
        <form action="" method="post">
            <table>
                <tr>
                    <td>Фамилия</td>
                    <td>
                        <input type="text" name="LAST_NAME" value="<?php echo $_REQUEST['LAST_NAME']?>">
                    </td>
                </tr>
                <tr>
                    <td>Имя</td>
                    <td>
                        <input type="text" name="NAME" value="<?php echo $_REQUEST['NAME']?>">
                    </td>
                </tr>
                <tr>
                    <td>Телефон</td>
                    <td>
                        <input type="text" name="PHONE" value="<?php echo $_REQUEST['PHONE']?>">
                    </td>
                </tr>
                <tr>
                   <td><button type="submit" name="save" value="Сохранить">Сохранить</button></td>
                </tr>
            </table>
        </form>
    </div>
    <div align="center"  style="margin-top: 2px; border: 2px solid black">
        <h3>Поиск</h3>
        <form action="" method="post">
            <table>
                <tr>
                    <td>Фамилия</td>
                    <td>
                        <input type="text" name="last_name_s" value="<? echo $_POST['last_name_s']?>">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><button type="submit" name="search" value="Поиск">Поиск</button></td>
                </tr>
            </table>
        </form>
        <table border="1" width="666px">
            <tr>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Действие</th>
            </tr>
            <?php
                if($_REQUEST['last_name_s'])
                    $list = search($_REQUEST['last_name_s']);
                else
                    $list = getList();
                foreach ($list as $id => $element)
                {
                    echo "<tr><td>{$element['LAST_NAME']}</td><td>{$element['NAME']}</td><td>{$element['PHONE']}</td><td><a href='index.php?delete={$id}'>Удалить</a></td></tr>";
                }
            ?>
        </table>
    </div>
</body>
</html>