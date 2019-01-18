<?php

/*
 * http://php.net/manual/ru/function.mkdir.php
 * http://php.net/manual/ru/function.chmod.php
 * http://php.net/manual/ru/function.file-put-contents.php
 * http://php.net/manual/ru/function.is-dir.php
 * http://php.net/manual/ru/function.is-writable.php
 */

class Control
{

    private $path;
    private $status;

    const WORK = 'work';
    const REST = 'rest';
    private $statusFile;
    private $workFile;
    private $restFile;

    /**
     * Перевод времени из формата H:i:s в секунды
     *
     * @param $time H:i:s
     * @return false|int
     */
    private function timeToSeconds($time)
    {
        return strtotime("1970-01-01 {$time} UTC");
    }

    /**
     * Разница во времени в формате H:i:s
     *
     * @param $first начало периода
     * @param $second конец периода
     * @return false|string H:i:s
     */
    private function timeDiff($first, $second)
    {
        $diff = $this->timeToSeconds($second) - $this->timeToSeconds($first);
        return gmdate('H:i:s', abs($diff));
    }

    /**
     * Создание файла при его отсутствии
     *
     * @param $filePath путь к файлу, который будет создан
     * @throws Exception если отсутствуют права на запись в файл
     */
    private function createFileIfNotExists($filePath)
    {
        if (!file_exists($filePath)) {
            file_put_contents($filePath, '');
            chmod($filePath, 0777);
        }
        if (!is_writable($filePath)) {
            throw new Exception('File unavailable for writing: ' . $filePath);
        }
    }

    /**
     * Control constructor.
     * @param $storagePath директория расположения csv файлов
     * @throws Exception если отсутствуют права на запись в директорию
     */
    public function __construct($storagePath)
    {

        $day = date('d.m');

        if (!is_dir($storagePath . '/' . $day)) {
            mkdir($storagePath . '/' . $day);
            chmod($storagePath . '/' . $day, 0777);
        }
        $this->path = $storagePath . '/' . $day . '/';
        if (!is_writable($storagePath . '/' . $day)) {
            throw new Exception('Directory unavailable for writing: ' . $storagePath . '/' . $day);
        }
        $this->statusFile = $this->path . 'status.csv';
        $this->workFile = $this->path . 'work.csv';
        $this->restFile = $this->path . 'rest.csv';
        $this->createFileIfNotExists($this->statusFile);
        $this->createFileIfNotExists($this->workFile);
        $this->createFileIfNotExists($this->restFile);
        $this->loadStatus();

    }

    /**
     * Получение статуса
     */
    private function loadStatus()
    {
        $this->status = trim(file_get_contents($this->statusFile));
        $this->status = explode(',', $this->status);
    }

    /**
     * Переключатель
     */
    public function switcher()
    {
        $time = date('H:i:s');

        if (count($this->status) == 2) {
            if ($this->status[0] === self::WORK) {
                file_put_contents($this->workFile, $this->status[1] . ',' . $time . ',' . $this->timeDiff($time, $this->status[1]) . "\n", FILE_APPEND);
                file_put_contents($this->statusFile, self::REST . ',' . $time);
            } elseif ($this->status[0] === self::REST) {
                if (($this->timeToSeconds(date('H:i:s')) - $this->timeToSeconds($this->status[1])) > 120) {
                    file_put_contents($this->restFile, $this->status[1] . ',' . $time . ',' . $this->timeDiff($time, $this->status[1]) . "\n", FILE_APPEND);
                    file_put_contents($this->statusFile, self::WORK . ',' . $time);
                } else {
                    $workTotal = explode("\n", trim(file_get_contents($this->workFile)));
                    $start = explode(',', $workTotal[count($workTotal) - 1])[0];
                    unset($workTotal[count($workTotal) - 1]);
                    file_put_contents($this->workFile, implode("\n", $workTotal) . "\n");
                    file_put_contents($this->statusFile, self::WORK . ',' . $start);
                }
            }
        } else {
            file_put_contents($this->statusFile, self::WORK . ',' . $time);
        }
        $this->loadStatus();

    }

    /**
     * @return string имя кнопки
     */
    public function getButtonTitle()
    {
        if ($this->status[0] === self::WORK) {
            return 'Start rest';
        } else {
            return 'Start work';
        }
    }

    /**
     * Формирование массива файла work.csv
     *
     * @return array
     */
    public function getWorkTotal()
    {
        return $this->getTotal($this->workFile, self::WORK);
    }

    /**
     * Формирование массива файла rest.csv
     *
     * @return array
     */
    public function getRestTotal()
    {
        return $this->getTotal($this->restFile, self::REST);
    }

    /**
     * Формирование массива для вывода в отчет
     *
     * @param $file для считывания из файлов work.csv или rest.csv
     * @param $type признак, считанный из файла status.csv
     * @return array
     */
    private function getTotal($file, $type)
    {
        $result = [
            'table' => [],
            'sum'   => 0,
        ];
        $lines = explode("\n", trim(file_get_contents($file)));
        foreach ($lines as $line) {
            $cols = explode(',', trim($line));
            if (count($cols) == 3) {
                $result['table'][] = $cols;
                $result['sum'] += $this->timeToSeconds($cols[2]);
            }
        }
        if ($type === $this->status[0]) {
            $result['table'][] = [
                $this->status[1],
                date('H:i:s'),
                $this->lastPeriodTime()
            ];
            $result['sum'] += $this->timeToSeconds($this->lastPeriodTime());
        }
        $result['sum'] = gmdate('H:i:s', $result['sum']);
        return $result;
    }

    /**
     * Формирование периода от времени из status.csv до текущего
     *
     * @return false|string
     */
    public function lastPeriodTime()
    {
        return $this->timeDiff(date('H:i:s'), $this->status[1]);
    }

    /**
     * Получение времени последнего нажатия на кнопку status.csv
     *
     * @return mixed
     */
    public function getStatusTime()
    {
        return $this->status[1];
    }

    /**
     * Получение даты для отчета при нажатии на календарь
     */
    public function getDateForReport()
    {
        //var_dump($this->path);
        //$this->getTotal("");
    }

    //для отчета нужно получить с формы дату и обрезать ее до даты и месяца
    //после того как получили дату вызываем стандартные методы печати отчета

}

