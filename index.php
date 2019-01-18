<?php
include 'switch.php';

try {
    $control = new Control("/home/nata/Рабочий стол/Control"); //Директория с csv файлами
} catch (Exception $e) {
    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $control->switcher();
}

$work = $control->getWorkTotal();
$rest = $control->getRestTotal();

$lpt = $control->lastPeriodTime();
$statusTime = $control->getStatusTime();

include 'template.php';