<?php

include('switch.php');

$control = new Control("/home/nata/Рабочий стол/Control"); //Директория с csv файлами


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $control->switcher();
    echo '\/';
}
?>

<form method="post">
    <input type="submit" value="Свитчер">
</form>