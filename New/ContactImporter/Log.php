<?php
/**
 * Log class for all log action
 *
 * User: leonhelmus
 * Date: 18-7-2018
 * Time: 14:51
 */

namespace ContactImporter;

class Log
{
    /**
     * Directory where output will be stored
     */
    const LOG_DIRECTORY_MAP = "Log";

    /**
     *  Standard file name without date
     */
    const FILE_NAME = "Log_";

    protected $debug = false;

    /**
     * Write to log if validation failed
     *
     * @param $log
     */
    public function writeToEmptyValueLog($log) {
        if($this->debug) {
            //Something to write to txt log
            $fileName = self::FILE_NAME . "Empty_" . date("Y-m-d") . ".log";
            $filePath = dirname(__FILE__) . "\\" . self::LOG_DIRECTORY_MAP . "\\" . $fileName;
            //Save string to log, use FILE_APPEND to append.
            file_put_contents($filePath, $log, FILE_APPEND);
        }
    }

    public function writeToValidationLog($log) {
        if($this->debug) {
            //Something to write to txt log
            $fileName = self::FILE_NAME . "Validation_" . date("Y-m-d") . ".log";
            $filePath = dirname(__FILE__) . "\\" . self::LOG_DIRECTORY_MAP . "\\" . $fileName;
            //Save string to log, use FILE_APPEND to append.
            file_put_contents($filePath, $log, FILE_APPEND);
        }
    }

    public function writeToAPILog($log) {
        if($this->debug) {
            //Something to write to txt log
            $fileName = self::FILE_NAME . "API_" . date("Y-m-d") . ".log";
            $filePath = dirname(__FILE__) . "\\" . self::LOG_DIRECTORY_MAP . "\\" . $fileName;
            //Save string to log, use FILE_APPEND to append.
            file_put_contents($filePath, $log, FILE_APPEND);
        }
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }
}