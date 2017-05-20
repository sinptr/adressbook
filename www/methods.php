<?php
/**
 * Created by PhpStorm.
 * User: Пётр
 * Date: 18.05.2017
 * Time: 20:46
 */

mb_internal_encoding("UTF-8");

/**
 * Удаляет строку в файле с индексом $id
 * @param $id
 */
function delete($id)
{
    $file=file("adressbook.txt");
    $fp=fopen("adressbook.txt","w");
    unset($file[$id]);
    fputs($fp,implode("",$file));
    fclose($fp);
}

/**
 * Проверяет строку на соответствие шаблону
 * @param $str
 * @param $pattern
 * @return bool
 */
function valid($str, $pattern)
{
    preg_match($pattern, $str, $matches);
    if (($str == $matches[0]) && $str)
        return true;
    else return false;
}

/**
 * Сохраняет запись в файл
 * @param $last_name
 * @param $name
 * @param $phone
 * @return array|bool
 */
function save($last_name, $name, $phone)
{
    $errors = array();
    $patternName = "/([абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ]+)-?([абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ]+)/";
    $patternPhone = "/8[0-9]{10}/";
    $last_name = trim($last_name);
    $name = trim($name);
    $phone = trim($phone);
    $allGood = array();
    if(!($allGood[] = valid($last_name, $patternName)))
    {
        $errors['LAST_NAME'] = "Фамилия введена неверно";
    }
    if(!($allGood[] = valid($name, $patternName)))
    {
        $errors['NAME'] = "Имя введено неверно";
    }
    if(!($allGood[] = valid($phone, $patternPhone)))
    {
        $errors['PHONE'] = "Телефон введен неверно";
    }
    if ($allGood[0] && $allGood[1] && $allGood[2])
    {
        $file=file("adressbook.txt");
        $fp=fopen("adressbook.txt","w+");
        $text= "{$last_name}|{$name}|{$phone}"."\n";
        $file[] = $text;
        sort($file);
        fputs($fp,implode("",$file));
        fclose($fp);
        return true;
    }
    else {
        return $errors;
    }
}

/**
 * Ищет все записи в файле с фамилиями начинающимися с $last_name
 * @param $last_name
 * @return array|bool
 */
function search($last_name)
{
    $i = 0;
    $last_name = trim($last_name);
    $response = array();
    if (strlen($last_name) > 2) {
        $fp = fopen('adressbook.txt', 'rt');
        while (!feof($fp)) {
            $str = fgets($fp);
            $sub_str = stristr($str, '|', true);
            if (mb_stripos($sub_str, $last_name) === 0):
                $sub_arr = explode('|', $str);
                $new_arr = array(
                    'LAST_NAME' => $sub_arr[0],
                    'NAME' => $sub_arr[1],
                    'PHONE' => $sub_arr[2],
                );
                $response[$i] = $new_arr;
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

/**
 * Возвращает все строки файла в виде массива
 * @return array
 */
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
