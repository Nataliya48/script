<?php

include 'switch.php';

$control = new Control("/home/nata/Рабочий стол/Control"); //Директория с csv файлами


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $control->switcher();
}

$work = $control->getWorkTotal();
$rest = $control->getRestTotal();

include 'template.php';

//var_dump(count($work['table']));
//$tmp = $work['table'][count($work['table']) - 1][0];
//unset($work['table'][count($work['table']) - 1]);
//var_dump($work['table']);