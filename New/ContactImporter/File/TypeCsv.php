<?php
/**
 * All functions that are used for creating a CSV
 *
 * TODO::Change name from TypeCsv to Csv. Couldn't do it because of error: Failed opening 'ContactImporter/File/Csv.php' for inclusion (include_path='E:\Programs\xampp\php\PEAR')
 *
 *
 * User: leonhelmus
 * Date: 18-7-2018
 * Time: 14:19
 */

namespace ContactImporter\File;

class TypeCsv
{
    /**
     * Directory where output will be stored
     */
    const OUTPUT_DIRECTORY_MAP = "Output";
    /**
     *  Standard file name without date
     */
    const FILE_NAME = "importContact";

    /**
     * Delimiter used in file
     *
     * @var string
     */
    protected $delimiter = ',';


    /**
     * Open file
     *
     * @return bool|resource
     */
    public function openFile() {
        $fileName = self::FILE_NAME.date("Y-m-d.H-i-s").".csv";
        $filePath = dirname(__DIR__)."\\".self::OUTPUT_DIRECTORY_MAP."\\".$fileName;
        $fp = fopen($filePath,'w');
        return $fp;
    }

    /**
     * Write to file
     *
     * @param $fp
     * @param $fields
     * @return bool|int
     */
    public function writeToFile($fp, $fields) {
        return fputcsv(
            $fp,
            $fields,
            $this->getDelimiter()
        );
    }

    /**
     * Close file
     *
     * @param $fp
     */
    public function closeFile($fp) {
        fclose($fp);
    }

    /**
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * @param string $delimiter
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }
}