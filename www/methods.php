<?php
/**
 * Created by PhpStorm.
 * User: Пётр
 * Date: 18.05.2017
 * Time: 20:46
 */
session_start();
if($_POST['butt'] == 1)
{
    if(safe($_POST['LAST_NAME'], $_POST['NAME'], $_POST['PHONE']) !== true):
        $_SESSION['ERROR_MSG'] = "Данные неверны";
        header("location: index.php?name={$_POST['NAME']}&last_name={$_POST['LAST_NAME']}&phone={$_POST['PHONE']}");

    else:
        unset($_SESSION['ERROR_MSG']);
        header("location: index.php");
    endif;

}
if($_POST['butt'] == 2)
{
    $foundName = search($_POST['S_LAST_NAME']);
    $_SESSION['foundName'] = $foundName;
    $_SESSION['lastSearched'] = $_POST['S_LAST_NAME'];
    header("location: index.php");
}
if($_POST['butt'] == 3)
{
    $_SESSION['fullList'] = getList();
    header("location: index.php");
}

if($_GET['delete'])
{
    bookDelete($_GET['id']);
    //var_dump($_GET['id']);
    if ($_SESSION['showMode'] == 'search'):
        $foundName = search($_SESSION['lastSearched']);
        $_SESSION['foundName'] = $foundName;
    endif;
    header("location: index.php");
}

if($_POST['butt'] == 5)
{
    $_SESSION['showMode'] = 'showAll';
    header("location: index.php");
}

function bookDelete($id)
{
    $file=file("adressbook.txt");
    $fp=fopen("adressbook.txt","w");
    unset($file[$id]);
    fputs($fp,implode("",$file));
    fclose($fp);
}

function valid($str, $pattern)
{
    preg_match($pattern, $str, $matches);
    if (($str == $matches[0]) && $str)
        return true;
    else return false;
}

function safe($last_name, $name, $phone)
{
    $allGood = true;
    $errArr = array();
    //Проверки
    $patternName = "/([абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ]+)-?([абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ]+)/";
    $patternPhone = "/8[0-9]{10}/";
    $errArr['LAST_NAME'] = valid($_POST['LAST_NAME'], $patternName);
    $errArr['NAME'] = valid($_POST['NAME'], $patternName);
    $errArr['PHONE'] = valid($_POST['PHONE'], $patternPhone);
    $allGood = $errArr['LAST_NAME'] && $errArr['NAME'] && $errArr['PHONE'];
    if ($allGood)
    {
        $file=file("adressbook.txt");
        $fp=fopen("adressbook.txt","w");
        $text="{$_POST['LAST_NAME']}|{$_POST['NAME']}|{$_POST['PHONE']}"."\n";
        $file[] = $text;
        sort($file);
        fputs($fp,implode("",$file));
        fclose($fp);
        //$fp = fopen('adressbook.txt', 'at');

        //fwrite($fp, $text);
        //fclose($fp);
        return true;
    }
    else
        return $errArr;
}

function search($last_name)
{
    $i = 0;
    $response = array();
    $_SESSION['showMode'] = 'search';
    if (strlen($last_name) > 2) {
        $fp = fopen('adressbook.txt', 'rt');
        while (!feof($fp)) {
            $str = fgets($fp);
            $sub_str = stristr($str, '|', true);
            if (stripos($sub_str, $last_name) === 0):
                $sub_arr = explode('|', $str);
                $new_arr = array(
                    'LAST_NAME' => $sub_arr[0],
                    'NAME' => $sub_arr[1],
                    'PHONE' => $sub_arr[2],
                );
                $response[$i] = $new_arr;
                //$response[] = $str;
            endif;
            $i++;
        }
        fclose($fp);
        return $response;
    }
    else {
        return false;
    }

}

function getList()
{
    $response = array();
    $fp = fopen('adressbook.txt', 'rt');
    while (!feof($fp))
    {
        $str = fgets($fp);
        if($str):
            $sub_arr = explode('|', $str);
            $new_arr = array(
                'LAST_NAME' => $sub_arr[0],
                'NAME' => $sub_arr[1],
                'PHONE' => $sub_arr[2],
            );
            $response[] = $new_arr;
        endif;
    }
    fclose($fp);
    return $response;
}

function showArr($arr)
{
    if ($arr)
        foreach ($arr as $str)
        {
            $sub_arr = explode('|', $str);
            foreach ($sub_arr as $s) {
                print $s." ";
            }
            echo "<br>";
        }
    else
        print "Ничего не найдено";
}

function showList($arr)
{
    if ($arr)
        foreach ($arr as $key => $sub_arr)
        {
            foreach ($sub_arr as $s) {
                print $s." ";
            }
            echo "<a href='' id=\"{$key}\">    Удалить</a> <br>";
        }
}