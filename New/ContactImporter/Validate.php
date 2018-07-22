<?php
/**
 * In this class all validation rules will be added
 *
 * User: leonhelmus
 * Date: 18-7-2018
 * Time: 14:05
 */

namespace ContactImporter;

use SimpleXMLElement;

include_once("Log.php");

class Validate
{
    /**
     * @var Log
     */
    private $log;

    private $stopCheckingCreditcard = false;

    /**
     * Validate constructor.
     */
    public function __construct()
    {
        $this->log = new Log();
    }

    /**
     * Set debug setting
     *
     * @param bool $debug
     */
    public function debug($debug) {
        $this->log->setDebug($debug);
    }

    /**
     * Validate email
     *
     * @param string $email
     * @return string
     */
    public function validateEmail($id, $email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->log->writeToValidationLog("ID:".$id." - Email:".$email." - Not an valid email address \n");
            $email = '';
        }
        return $email;
    }

    /**
     * There must be more than 9 digits in the field
     *
     * @param string $id
     * @param string $phone
     * @return bool
     */
    public function validatePhone($id,$phone) {
        if(empty($phone)) {
            $this->log->writeToEmptyValueLog("ID:".$id." - Phone:".$phone." - Not filled in \n");
        } elseif(strlen($phone) < 9) {
            $this->log->writeToValidationLog("ID:".$id." - Phone:".$phone." - Not an valid phone number \n");
            $phone = '';
        }
        return $phone;
    }

    /**
     * Validate Credit Card
     *
     * TODO:: add all errors of API to validation see https://www.bincodes.com/api-creditcard-checker/
     *
     * because this API is expensive i won't try to get this information anymore when i get an 1004 error
     *
     * @param string $id
     * @param string $creditCard
     * @return string
     */
    public function validateCreditCard($id, $creditCard) {
        if(!$this->stopCheckingCreditcard) {
            if (empty($creditCard)) {
                $this->log->writeToEmptyValueLog("ID:" . $id . " - Credit Card:" . $creditCard . " - Not filled in \n");
                return '';
            }
            $response = file_get_contents('https://api.bincodes.com/cc/?format=xml&api_key=b8f5aa89bf7dcb3f2473b049b9c746c6&cc=' . $creditCard);
            $xmlObjects = new SimpleXMLElement($response);
            if ((string)$xmlObjects->error === "1004") {
                $this->log->writeToAPILog("ID:" . $id . " - Creditcard API Usage Limit Exceeded \n");
                $this->stopCheckingCreditcard = true;
                echo "\033[31m Creditcard API Usage Limit Exceeded for API bincodes \033[0m \n";
                return '';
            }
            /**
             * if no errors get card type
             */
            return $creditCard->card;
        }
        return '';
    }

    /**
     * Calculate based on age which dob it is. Only year is possible.
     *
     * @param string $id
     * @param int $age
     * @return false|string
     */
    public function validateDob($id,$age) {
        if(empty($age)) {
            $this->log->writeToEmptyValueLog("ID:".$id." - Age:".$age." - Not filled in \n");
            return '';
        }
        $time = strtotime("-".$age." year", time());
        return date("Y", $time);
    }
}