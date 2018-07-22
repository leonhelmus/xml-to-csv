<?php
/**
 * Run shell script to import contacts
 *
 * User: leonhelmus
 * Date: 18-7-2018
 * Time: 13:15
 */

namespace ContactImporter;


use Exception;

include_once("ShellAbstract.php");
include_once("Processor.php");

ini_set('memory_limit','31M');

class ImportContacts extends ShellAbstract
{

    /**
     * @var Processor
     */
    private $processor;

    /**
     * ImportContacts constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->processor = new Processor();
    }

    /**
     * Run shell script
     *
     * This script will read an XML file and create a new csv file
     */
    public function run() {
        try {
            $fileName = $this->getFileName();
            $debug = $this->debug();
            $this->processor->process(
                $fileName,
                $debug
            );
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        echo "\033[32m You've succesfully created a XML to CSV! \033[0m \n";
    }

    /**
     * Get the file name using argument
     *
     * @return bool|string
     * @throws Exception
     */
    protected function getFileName() {
        $file = $this->getArg("file");
        if(!$file) {
            throw new Exception("\033[31m Argument -file is missing \033[0m \n");
        } elseif(!is_string($file)) {
            throw new Exception("\033[31m Argument -file needs to be a string \033[0m \n");
        }
        return $file;
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function debug() {
        $debug = strtolower($this->getArg("debug")) == "true" ? true : false;
        if(!is_bool($debug)) {
            throw new Exception("\033[31m Argument -file needs to be a boolean \033[0m \n");
        }
        return $debug;
    }
}



$shell = new ImportContacts();
$shell->run();