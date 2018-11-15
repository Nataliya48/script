<?php

/*
 * http://php.net/manual/ru/function.mkdir.php
 * http://php.net/manual/ru/function.chmod.php
 * http://php.net/manual/ru/function.file-put-contents.php
 * http://php.net/manual/ru/function.is-dir.php
 * http://php.net/manual/ru/function.is-writable.php
 */

class Control {

    private $path;
    private $status;

    const WORK  = 'work';
    const REST  = 'rest';
    var $statusFile;
    var $workFile;
    var $restFile;

    private function timeToSeconds($time) {
        return strtotime("1970-01-01 {$time} UTC");
    }

    private function timeDiff($first, $second) {
        $diff = $this->timeToSeconds($second) - $this->timeToSeconds($first);
        return gmdate('H:i:s', abs($diff));
    }

    public function __construct($storagePath) {

        $day = date('d.m');

        if (!is_dir($storagePath . '/' . $day)) {
            mkdir($storagePath . '/' . $day);
            chmod($storagePath . '/' . $day,0777);
        }
        $this->path = $storagePath . '/' . $day . '/';
        $this->statusFile = $this->path . 'status.csv';
        $this->workFile = $this->path . 'work.csv';
        $this->restFile = $this->path . 'rest.csv';

        if (!is_writable($this->statusFile)){
            file_put_contents($this->statusFile, ''); //rest\work
            chmod($this->statusFile,0777);
        }
        if (!is_writable($this->workFile)){
            file_put_contents($this->workFile, '');
            chmod($this->workFile,0777);
        }
        if (!is_writable($this->restFile)){
            file_put_contents($this->restFile, '');
            chmod($this->restFile,0777);
        }
        $this->loadStatus();

    }

    private function loadStatus() {
        $this->status = trim(file_get_contents($this->statusFile));
        $this->status = explode(',', $this->status);
    }

    public function switcher() {

        $time = date('H:i:s');

        if (count($this->status) == 2) {
            if ($this->status[0] === self::WORK) {
                file_put_contents($this->workFile, $this->status[1] . ',' . $time . ',' . $this->timeDiff($time, $this->status[1]) . "\n", FILE_APPEND);
                file_put_contents($this->statusFile, self::REST . ',' . $time);
            } elseif ($this->status[0] === self::REST) {
                if (($this->timeToSeconds(date('H:i:s')) - $this->timeToSeconds($this->status[1])) > 120) {
                    file_put_contents($this->restFile, $this->status[1] . ',' . $time . ',' . $this->timeDiff($time, $this->status[1]) . "\n", FILE_APPEND);
                }
                file_put_contents($this->statusFile, self::WORK . ',' . $time);
            }
        } else {
            file_put_contents($this->statusFile, self::WORK . ',' . $time);
        }
        $this->loadStatus();

    }

    public function getButtonTitle() {
        if ($this->status[0] === self::WORK) {
            return 'Start rest';
        } else {
            return 'Start work';
        }
    }

    public function getWorkTotal() {
        return $this->getTotal($this->workFile);
    }

    public function getRestTotal() {
        return $this->getTotal($this->restFile);
    }

    private function getTotal($file) {
        $result = [
            'table' => [['Start', 'End', 'Total']],
            'sum' => 0,
        ];
        $lines = explode("\n", trim(file_get_contents($file)));
        foreach ($lines as $line) {
            $cols = explode(',', trim($line));
            if (count($cols) == 3) {
                $result['table'][] = $cols;
                $result['sum'] += $this->timeToSeconds($cols[2]);
            }
        }
        $result['sum'] = gmdate('H:i:s', $result['sum']);
        return $result;
    }

}

