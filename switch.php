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

    //const WORK  = 'work';
    //const REST  = 'break';

    private function timeToSeconds($time) {
        return strtotime("1970-01-01 {$time} UTC");
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
            file_put_contents($this->path . '/status.csv', ''); //break\work
        }
        if (!is_writable($this->path . 'work.csv')){
            file_put_contents($this->path . '/work.csv', '');
        }
        if (!is_writable($this->path . 'rest.csv')){
            file_put_contents($this->path . 'rest.csv', '');
        }

        $this->status = trim(file_get_contents($this->path . 'status.csv'));
        $this->status = explode(',', $this->status);

    }

    public function switcher() {

        $time = gmdate('H:i:s');

        if (count($this->status) == 2) {
            if ($this->status[0] === 'work') {
                file_put_contents($this->path . 'work.csv', $this->status[1] . ',' . $time . ',' . $this->timeDiff($time, $this->status[1]) . "\n", FILE_APPEND);
                file_put_contents($this->path . 'status.csv', 'break,' . $time);
            } elseif ($this->status[0] === 'break') {
                file_put_contents($this->path . 'rest.csv', $this->status[1] . ',' . $time . ',' . $this->timeDiff($time, $this->status[1]) . "\n", FILE_APPEND);
                file_put_contents($this->path . 'status.csv', 'work,' . $time);
            }
        } else {
            file_put_contents($this->path . 'status.csv', 'work,' . $time);
        }

    }

    public function status() {



    }
}

