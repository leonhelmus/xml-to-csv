<?php
/**
 * All functions to process XML
 *
 * User: leonhelmus
 * Date: 18-7-2018
 * Time: 14:19
 */

namespace ContactImporter\File;


use Exception;

class Xml
{
    /**
     * Directory where input will be stored
     */
    const INPUT_DIRECTORY_MAP = "Input";

    protected $filePath = '';

    public function readXml() {
        return new \SimpleXMLElement(
            file_get_contents($this->getFilePath())
        );
    }

    /**
     * Get file to read
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set File to read
     *
     * @param $fileName
     * @return bool
     * @throws Exception
     */
    public function setFilePath($fileName)
    {
        $filePath = dirname(__DIR__)."\\".self::INPUT_DIRECTORY_MAP."\\".$fileName;
        if(file_exists($filePath)) {
            $this->filePath = $filePath;
            $this->isXml($filePath);
        } else {
            throw new Exception("\033[31m Argument -file file does not exist \033[0m \n");
        }
        return true;
    }

    /**
     * Check if file is XML
     *
     * @param $filePath
     * @throws Exception
     */
    public function isXml($filePath) {
        $pathInfo = pathinfo($filePath);
        if($pathInfo['extension'] !== "xml") {
            throw new Exception("\033[31m Argument -file file is not xml \033[0m \n");
        }
    }

}