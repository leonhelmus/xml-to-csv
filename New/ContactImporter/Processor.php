<?php
/**
 * This file will process the xml to csv with transformations
 *
 * User: leonhelmus
 * Date: 18-7-2018
 * Time: 16:23
 */

namespace ContactImporter;

use ContactImporter\File\TypeCsv;
use ContactImporter\File\Xml;
use ContactImporter\Source\Categories;
use Exception;

include_once("File/TypeCsv.php");
include_once("File/Xml.php");
include_once("Source/Categories.php");
include_once("Log.php");
include_once("Validate.php");

class Processor
{
    /**
     * Headers Csv file
     *
     * TODO:: Ask management if header 'interests space seperated' is mend to look like this.
     */
    const HEADERS = [
        'name',
        'email',
        'phone',
        'dob',
        'credit card type',
        'interests space seperated'
    ];
    /**
     * @var TypeCsv
     */
    private $csv;
    /**
     * @var Xml
     */
    private $xml;
    /**
     * @var Log
     */
    private $log;
    /**
     * @var Categories
     */
    private $categories;
    /**
     * @var Validate
     */
    private $validate;

    /**
     * ImportContacts constructor.
     */
    public function __construct()
    {
        $this->xml = new Xml();
        $this->log = new Log();
        $this->categories = new Categories();
        $this->validate = new Validate();
        $this->csv = new TypeCsv();
    }

    /**
     * process XML file to CSV
     *
     * @param $fileName
     * @param bool $debug
     * @throws Exception
     */
    public function process($fileName, $debug = false) {
        $this->validate->debug($debug);
        if($this->xml->setFilePath($fileName)) {
            $xmlObjects = $this->xml->readXml();
            if(!empty($xmlObjects->categories)) {
                $this->categories->setCategories($xmlObjects->categories);
            }
            if(!empty($xmlObjects->people)) {
                $fp = $this->csv->openFile();
                $this->csv->writeToFile($fp, self::HEADERS);
                foreach ($xmlObjects->people->person as $person) {
                    $data = [];
                    $id = (string)$person->attributes()->id[0];
                    $data[] = $person->name;
                    $data[] = $this->validate->validateEmail(
                        $id,
                        $this->cleanEmail((string)$person->emailaddress)
                    );
                    $data[] = $this->validate->validatePhone(
                        $id,
                        (string)$person->phone
                    );
                    $data[] = $this->validate->validateDob(
                        $id,
                        (int)$person->age
                    );
                    $data[] = $this->validate->validateCreditCard(
                        $id,
                        $this->cleanCreditCard((string)$person->creditcard)
                    );

                    $data[] = $this->getInterests($person->profile);
                    $this->csv->writeToFile($fp, $data);
                }
                $this->csv->closeFile($fp);
            } else {
                throw new Exception("\033[31m Argument -file no people were found, please add people in your XML \033[0m \n");
            }
        }
    }

    /**
     * Get all interests of customer seperated by punt komma (it was space seperated but this is not handy because some names have spaces)
     *
     * @param \SimpleXMLElement $profile
     * @return string
     */
    public function getInterests($profile) {
        $interestsLine = '';
        if($profile) {
            foreach ($profile->interest as $interest) {
                $category = (string)$interest->attributes()->category;
                $interestsLine .= $this->categories->getCategory($category) . ";";
            }
        }
        return $interestsLine;
    }

    /**
     * Remove mailto: from email field
     *
     * @param string $email
     * @return string
     */
    public function cleanEmail($email) {
        return str_replace('mailto:','', $email);
    }

    /**
     * Remove white space from credit card
     *
     * @param string $creditCard
     * @return string
     */
    public function cleanCreditCard($creditCard) {
        $creditCardCleaned = preg_replace('/\s+/', '', $creditCard);
        return $creditCardCleaned;
    }

}