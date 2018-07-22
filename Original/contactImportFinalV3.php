<?php

/***
 * The FitForMe importer script
 * Feed ERP contacts to our advertising API based on interest
 */

$xmlArray = json_decode(json_encode(simplexml_load_string(file_get_contents('contactlist.xml'))), true);

$categories = $xmlArray['categories']['category'];
$people = $xmlArray['people']['person'];

/**
 * validate class
 *
 * @param $mail
 * @return bool
 */
function validate($mail) {
    $positionApenstaartje = strpos($mail, '@');
    $positionDot = strrpos($mail, '.');
    if ($positionApenstaartje!=false && $positionDot!=false && $positionApenstaartje< $positionDot) {
        return true;
    }
}

/**
 * validate class
 *
 * @param $phone
 * @return bool
 */
function validate2($phone) {
    if (strlen($phone) > 9) {
        return true;
    }
}

/**
 * READ file class
 */
file_put_contents("output.csv", "name,email,phone,dob,credit card type,interests space seperated\r\n");

for ($i=0;$i<count($xmlArray['people']['person']);$i++) {
    $csvLine = $people[$i]['name'] . ",";

    $interests = array();
    if (isset($people[$i]['profile']['interest'])) {
        for ($j=0; $j<count($people[$i]['profile']['interest']);$j++) {
            if (isset($people[$i]['profile']['interest'][$j])) {
                $categorieNummer = $people[$i]['profile']['interest'][$j]['@attributes']['category'];

                /**
                 * Aparte class eerst alle categories ophalen
                 */
                for ($k = 0; $k < count($xmlArray['categories']['category']); $k++) {
                    if ($categories[$k]['@attributes']['id'] == $categorieNummer) {
                        array_push($interests, $categories[$k]['name']);
                    }
                }
            }
        }
    }

    $email = substr($people[$i]['emailaddress'], strpos($people[$i]['emailaddress'],':') + 1);
    if (validate($email) == true) {
        $csvLine .= $email . ",";
    }

    if (validate2($people[$i]['phone']) == true) {
        $csvLine .= $people[$i]['phone'] . ",";
    }

    $csvLine .= date('Y', time() - ($people[$i]['age'] * 86400 * 365)) . ",";

    $creditcardcheck = json_decode(file_get_contents('https://api.bincodes.com/cc/?format=xml&api_key=b8f5aa89bf7dcb3f2473b049b9c746c6&cc=' .
        str_replace(' ', '', $people[$i]['creditcard'])), true);

    $csvLine .= $creditcardcheck['card'] . ",";

    for ($x = 0;$x<count($interests);$x++) {
        $csvLine .= $interests[$x] . " ";
    }

    /**
     * single write
     */
    file_put_contents("output.csv", $csvLine . "\r\n", FILE_APPEND);
}