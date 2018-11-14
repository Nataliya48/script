<?php

include('switch.php');

$control = new Control("/home/nata/Рабочий стол/Control"); //Директория с csv файлами


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $control->switcher();
    echo '\/';
}

echo '<form method="post">
        <input type="submit" value="' . $control->getButtonTitle() . '">
    </form>';

var_dump($control->getWorkTotal());
var_dump($control->getRestTotal());
?>


