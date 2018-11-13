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

    private function timeToSeconds($time) {
        return strtotime("1970-01-01 {$time} UTC") + 3 * 60 * 60;
    }

    private function timeDiff($first, $second) {
        $diff = $this->timeToSeconds($second) - $this->timeToSeconds($first);
        return gmdate('H:i:s', abs($diff));
    }

    public function __construct($storagePath) {

        $day = gmdate('d.m');

        if (!is_dir($storagePath . '/' . $day)) {
            mkdir($storagePath . '/' . $day, 0777);
        }
        $this->path = $storagePath . '/' . $day . '/';

        if (!is_writable($this->path . 'status.csv')){
            file_put_contents($this->path . 'status.csv', ''); //rest\work
        }
        if (!is_writable($this->path . 'work.csv')){
            file_put_contents($this->path . 'work.csv', '');
        }
        if (!is_writable($this->path . 'rest.csv')){
            file_put_contents($this->path . 'rest.csv', '');
        }
        $this->loadStatus();

    }

    private function loadStatus() {
        $this->status = trim(file_get_contents($this->path . 'status.csv'));
        $this->status = explode(',', $this->status);
    }

    public function switcher() {

        $time = gmdate('H:i:s');

        if (count($this->status) == 2) {
            if ($this->status[0] === self::WORK) {
                file_put_contents($this->path . 'work.csv', $this->status[1] . ',' . $time . ',' . $this->timeDiff($time, $this->status[1]) . "\n", FILE_APPEND);
                file_put_contents($this->path . 'status.csv', self::REST . ',' . $time);
            } elseif ($this->status[0] === self::REST) {
                if (($this->timeToSeconds(gmdate('H:i:s')) - $this->timeToSeconds($this->status[1])) > 120) {
                    file_put_contents($this->path . 'rest.csv', $this->status[1] . ',' . $time . ',' . $this->timeDiff($time, $this->status[1]) . "\n", FILE_APPEND);
                }
                file_put_contents($this->path . 'status.csv', self::WORK . ',' . $time);
            }
        } else {
            file_put_contents($this->path . 'status.csv', self::WORK . ',' . $time);
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

    public function report() {
        file_get_contents();
    }
}

