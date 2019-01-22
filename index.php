<?php
include 'switch.php';

try {
    $control = new Control("/home/nata/Рабочий стол/Control"); //Директория с csv файлами

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $control->switcher();
    }

    $work = $control->getWorkTotal();
    $rest = $control->getRestTotal();

    $lpt = $control->lastPeriodTime();
    $statusTime = $control->getStatusTime();

} catch (Exception $e) {
    $errorMsg = 'Выброшено исключение: ' .  $e->getMessage() . "\n";
}

include 'template.php';