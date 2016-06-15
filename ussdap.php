<?php

date_default_timezone_set("Asia/Colombo");


ini_set('error_log', 'ussd-app-error.log');

require 'class/db.php';
require 'libs/MoUssdReceiver.php';
require 'libs/MtUssdSender.php';
require 'libs/sms/SMSSender.php';
require 'class/operationsClass.php';
require 'libs/Log.php';

// Code for LBS importing
include_once 'lbs/libs/LbsClient.php';
include_once 'lbs/libs/LbsRequest.php';
include_once 'lbs/libs/LbsResponse.php';
include_once "lbs/libs/KLogger.php";
include 'lbs/conf/lbs-conf.php';
// import end



$production = false;

$APP_ID = "APP_ID";
$PASSWORD = "APP_PASSWORD";




if ($production == false) {
    $ussdserverurl = 'http://localhost:7000/ussd/send';
    $LBS_SERVER_URL = "http://localhost:7000/lbs/locate";
    $SMS_SERVER_URL = "http://localhost:7000/sms/send";
} else {
    $ussdserverurl = 'https://api.dialog.lk/ussd/send';
    $LBS_SERVER_URL = 'https://api.dialog.lk/lbs/locate';
    $SMS_SERVER_URL = "https://api.dialog.lk/sms/send";
}

$log = new Logger();
$receiver = new UssdReceiver();
$sender = new UssdSender($ussdserverurl, $APP_ID, $PASSWORD);
$operations = new Operations();



$receiverSessionId = $receiver->getSessionId();
$content = $receiver->getMessage(); // get the message content
$address = $receiver->getAddress(); // get the sender's address
$requestId = $receiver->getRequestID(); // get the request ID
$applicationId = $receiver->getApplicationId(); // get application ID
$encoding = $receiver->getEncoding(); // get the encoding value
$version = $receiver->getVersion(); // get the version
$sessionId = $receiver->getSessionId(); // get the session ID;
$ussdOperation = $receiver->getUssdOperation(); // get the ussd operation
// Code for LBS
$request = new LbsRequest($LBS_SERVER_URL);
$request->setAppId($APP_ID);
$request->setAppPassword($PASSWORD);
$request->setSubscriberId($address);
$request->setServiceType($SERVICE_TYPE);
$request->setFreshness($FRESHNESS);
$request->setHorizontalAccuracy($HORIZONTAL_ACCURACY);
$request->setResponseTime($RESPONSE_TIME);
// LBS end


$responseMsg = "I want to Recycle \n";
$response_msg_qry = "SELECT * FROM egcollectables";
$responseMsg_reslt = mysqli_query($connection, $response_msg_qry);
while ($line = mysqli_fetch_array($responseMsg_reslt)) {
    $responseMsg .= $line["id"] . "." . $line["type"] . "\n";
}

$responseMsg .= "99.Exit";


if ($ussdOperation == "mo-init") {

    try {
        // Code for LBS
        $lbsClient = new LbsClient();
        $lbsResponse = new LbsResponse($lbsClient->getResponse($request));
        $lbsResponse->setTimeStamp(getModifiedTimeStamp($lbsResponse->getTimeStamp())); //Changing the timestamp format. Ex: from '2013-03-15T17:25:51+05:30' to '2013-03-15 17:25:51'
        // LBS end

        $sessionArrary = array("sessionid" => $sessionId, "tel" => $address, "menu" => "main", "pg" => "", "others" => "", "longitude" => $lbsResponse->getLongitude(), "latitude" => $lbsResponse->getLatitude());     //  longitude   latitude

        $operations->setSessions($sessionArrary);

        $sender->ussd($sessionId, $responseMsg, $address);

//        $sender->ussd($sessionId, $resp, $address);
    } catch (Exception $e) {

        $sender->ussd($sessionId, 'Sorry error occured try again', $address);
    }
} else {

    $flag = 0;

    $sessiondetails = $operations->getSession($sessionId);
    $cuch_menu = $sessiondetails['menu'];
    $operations->session_id = $sessiondetails['sessionsid'];

    switch ($cuch_menu) {

        case "main":  // Following is the main menu
            if ($receiver->getMessage() == "99") {
                $sender->ussd($sessionId, 'Thank you using our service !', $address, 'mt-fin');
            } elseif ($receiver->getMessage() == "5" || $receiver->getMessage() == "6") {
                $operations->session_menu = "sub";
                $operations->saveSesssion();
                $operations->res_arry = $operations->getGcolectors("All", $sessiondetails["latitude"], $sessiondetails["longitude"]);
                $sender->ussd($sessionId, $operations->setArtoStr($operations->res_arry, $sessionId), $address);
            } elseif ($receiver->getMessage() == "") {
                $operations->session_menu = "main";
                $operations->saveSesssion();
                $sender->ussd($sessionId, $responseMsg, $address);
            } else {
                $operations->session_menu = "sub";
                $operations->saveSesssion();
                $operations->res_arry = $operations->getGcolectors($receiver->getMessage(), $sessiondetails["latitude"], $sessiondetails["longitude"]);
//                $log->WriteLog($receiver->getMessage()."\nLon");
//                $log->WriteLog($sessiondetails["longitude"]."\nLat");
//                $log->WriteLog($sessiondetails["latitude"]);
                $sender->ussd($sessionId, $operations->setArtoStr($operations->res_arry, $sessionId, $receiver->getMessage()), $address);
            }
            break;
        case "sub":
            $respons_no = $receiver->getMessage();
            if ($respons_no == "99") {
                $sender->ussd($sessionId, 'Thank you using our service !', $address, 'mt-fin');
                break;
            } else {

                $operations->session_menu = "main";
                $operations->saveSesssion();
//                $rep_msg = $operations->getGcolDetails($respons_no, $operations->res_arry);
                $sender->ussd($sessionId, "Request completed. Provide the PIN number in the sms to the collector.\nThank You !", $address, 'mt-fin');
                
                $rep_msg = $operations->getGcolDetails($respons_no, $sessionId, $operations);
                $sms_sender = new SMSSender($SMS_SERVER_URL, $APP_ID, $PASSWORD);
                $sms_sender->sms($rep_msg, $address);
                
                $collectorid = $operations->getColectorId($sessionId);
                $garbid = $operations->getGcTypeId($sessionId);
                $mobNO = $operations->getUsrNo($sessionId);
                
                $params = array(
                    "collector" => $collectorid,
                    "e_type" => $garbid,
                    "phone_number" => $mobNO
                );
                httpPost("http://greeen.brightron.net/greeen_project/index.php/ajax/add_recycle_from_phone", $params);

//                $sender->ussd($sessionId, "Request completed provide the pin number in the sms to the collector.\nThank You !", $address, 'mt-fin');
                $operations->closeConn();
                
                break;
            }

        case "99":
            $sender->ussd($sessionId, 'Thank you using our service !', $address, 'mt-fin');
            break;
        default:
            $operations->session_menu = "main";
            $operations->saveSesssion();
            $sender->ussd($sessionId, 'Incorrect option ' . $cuch_menu, $address);
            break;
    }
}

function getModifiedTimeStamp($timeStamp) {
    try {
        $date = new DateTime($timeStamp, new DateTimeZone('Asia/Colombo'));
    } catch (Exception $e) {
        echo $e->getMessage();
        exit(1);
    }
    return $date->format('Y-m-d H:i:s');
}

function httpPost($url, $params) {
    $postData = '';
    //create name value pairs seperated by &
    foreach ($params as $k => $v) {
        $postData .= $k . '=' . $v . '&';
    }
    rtrim($postData, '&');

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, count($postData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    $output = curl_exec($ch);

    curl_close($ch);
    return $output;
}

?>
