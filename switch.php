<?php

/*В общем я сделал класс, который считал время без бд, чисто на 3 файлах.
Идея была такой, есть любая удобная для тебя папка, например /home/natasha/work/storage/.
При инциализации экземплера класса в этой папке создается новая папка с именем соотвествующем текущему дню в формате 'dd.mm',
вроде 29.10, если она не была создана ранее.
В папке будет храниться статстика за текущий день. В ней будут созданы файлы work.csv, rest.csv, status.csv.
work.csv - время начала работы, время конец работы, длина интервала работы.
rest.csv - то же самое, но по перерыву.
status.csv - хранит время начала и признак начала чего(работы/отдыха), тоже в csv, вроде "09:30:00,work"*/

/*
 * http://php.net/manual/ru/function.mkdir.php
 * http://php.net/manual/ru/function.chmod.php
 * http://php.net/manual/ru/function.file-put-contents.php
 * http://php.net/manual/ru/function.is-dir.php
 * http://php.net/manual/ru/function.is-writable.php
 */

class Control {

private $path;

    //конструктор, в него передается путь рабочей папки
    //в нем же создаются все нужные файлы и папки
    //ахтунг, если файл есть - создавать не нужно, чтобы не перезатереть результат
    //тут же проверяются права на запись, чтобы не было фигни, когда папка есть, а прав у вебсервера писать в нее нет
    public function __construct($storagePath) {

        $day = gmdate('d.m'); //Текущий день и месяц

        if (!is_dir($storagePath . '/' . $day)) {
            mkdir($storagePath . '/' . $day, 0777);
        }
        $this->path = $storagePath . '/' . $day;

        //Создание файлов с проверкой:
        if (!is_writable($this->path . '/status.csv')){//время начала и признак начала чего(работы/отдыха), тоже в csv, вроде "09:30:00,work"
            file_put_contents($this->path . '/status.csv', ''); //break\work
        }
        if (!is_writable($this->path . '/work.csv')){//время начала работы, время конец работы, длина интервала работы
            file_put_contents($this->path . '/work.csv', '');
        }
        if (!is_writable($this->path . '/rest.csv')){//время начала перерыва, время конец перерыва, длина интервала перерыва
            file_put_contents($this->path . '/rest.csv', '');
        }

    }

    //переключатель работа/отдых
    //тут мы получаем инфу из файла status.csv
    //очевидно, если файла нет или он пустой - значит мы только начинаем рабочий день, по этому в work.csv, rest.csv ничего не записываем
    //если в status.csv есть инфа, то мы берем оттуда время, статус
    //берем текущее время, добавляем в work.csv или rest.csv(смотря че за статус) инфу в виде "время из status.csv,текущее время, длина интервала"
    //в status.csv сохраняем текущее время, и признак время начала какого интервала мы записываем
    public function switcher() {

        $time = gmdate('H:i:s');

        //Получаем инфу из status.csv, если он пустой, то это начало рабочего дня и записывает признак work со временем иначе получаем что это и время
        $status = trim(file_get_contents($this->path . '/status.csv'));
        $status = explode(',', $status);

        function timeToSeconds($time) {
            return strtotime("1970-01-01 {$time} UTC");
        }

        //Функция вычисления времени перерыва или в работе
        function timeDiff($first, $second) {
            $diff = timeToSeconds($second) - timeToSeconds($first);
            return gmdate('H:i:s', abs($diff));
        }

        if (count($status) == 2) {
            //записываем время начала и относительно признака выбираем файл, в какой записываем
            if ($status[0] === 'work') {
                file_put_contents($this->path . '/work.csv', $status[1] . ',' . $time . ',' . timeDiff($time, $status[1]) . "\n", FILE_APPEND);
                file_put_contents($this->path . '/status.csv', 'break,' . $time);
            } elseif ($status[0] === 'break') {
                file_put_contents($this->path . '/rest.csv', $status[1] . ',' . $time . ',' . timeDiff($time, $status[1]) . "\n", FILE_APPEND);
                file_put_contents($this->path . '/status.csv', 'work,' . $time);
            }
        } else {
            file_put_contents($this->path . '/status.csv', 'work,' . $time);
        }

    }
}

