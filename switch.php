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
        mkdir($storagePath . '/' . $day);
    }
    $this->path = $storagePath . '/' . $day;

    //Создание файлов с проверкой:
    if (!is_writable($this->path . '/status.csv')){//время начала и признак начала чего(работы/отдыха), тоже в csv, вроде "09:30:00,work"
        file_put_contents($this->path . '/status.csv'); //relax\work
    }
    if (!is_writable($this->path . '/work.csv')){//время начала работы, время конец работы, длина интервала работы
        file_put_contents($this->path . '/work.csv');
    }
    if (!is_writable($this->path . '/rest.csv')){//время начала перерыва, время конец перерыва, длина интервала перерыва
        file_put_contents($this->path . '/rest.csv');
    }

}

//переключатель работа/отдых
//тут мы получаем инфу из файла status.csv
//очевидно, если файла нет или он пустой - значит мы только начинаем рабочий день, по этому в work.csv, rest.csv ничего не записываем
//если в status.csv есть инфа, то мы берем оттуда время, статус
//берем текущее время, добавляем в work.csv или rest.csv(смотря че за статус) инфу в виде "время из status.csv,текущее время, длина интервала"
//в status.csv сохраняем текущее время, и признак время начала какого интервала мы записываем
public function switcher() { //FILE_APPEND Если файл filename уже существует, данные будут дописаны в конец файла вместо того, чтобы его перезаписать.

    if (empty(file_get_contents($this->path . '/status.csv'))) {
        file_put_contents($this->path . '/status.csv', 1, FILE_APPEND);
    }

    $status = trim(file_get_contents($this->path . '/status.csv'));
    $status = explode(',', $status);
    if (count($status) == 2) {
// тут признак $status[0]
// тут время $status[1]
    }

//хранить во временных переменных время начала, время конца,
// после того как оба значения будут известны, высчитать и все три значения записать в файл

//откуда я буду брать значения нажатых кнопок?
//откуда я буду брать признак нажатой кнопки?


//В зависимости от признака выбирать файл, в который я буду записывать:

//        if (признак = 'work'){
//        } elseif (признак = 'relax'){

//        }





}
}

