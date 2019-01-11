<?php
include 'switch.php';

$control = new Control("/home/nata/Рабочий стол/Control"); //Директория с csv файлами

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $control->switcher();
}

$work = $control->getWorkTotal();
$rest = $control->getRestTotal();

//у меня есть последнее (нижнее) время работы от начала последнего интервала до текущего времени.
//вызываю метод, который возвращает это время и суммируем с временем, которое возвращает сумму времени в работе


$lwt = $control->lastPeriodTime();
//$control->getWorkTotal();

include 'template.php';