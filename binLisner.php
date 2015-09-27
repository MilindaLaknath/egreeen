<?php

date_default_timezone_set("Asia/Colombo");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


ini_set('error_log', 'sms-app-error.log');
require 'class/db.php';
require_once './libs/sms/Log.php';
require_once './libs/sms/SMSReceiver.php';
require_once './libs/MtUssdSender.php';

// Code for LBS importing
include_once 'lbs/libs/LbsClient.php';
include_once 'lbs/libs/LbsRequest.php';
include_once 'lbs/libs/LbsResponse.php';
include_once "lbs/libs/KLogger.php";
include 'lbs/conf/lbs-conf.php';
// import end

$production = true;

if ($production == false) {
    $SMS_SERVER_URL = "http://localhost:7000/sms/send";
    $LBS_SERVER_URL = "http://localhost:7000/lbs/locate";
} else {
    $SMS_SERVER_URL = "https://api.dialog.lk/sms/send";
    $LBS_SERVER_URL = 'https://api.dialog.lk/lbs/locate';
}

define('APP_ID', 'APP_013169');
define('APP_PASSWORD', 'ee0364803d67c2752aad888bb1592878');

$logger = new Logger();



try {

    // Creating a receiver and intialze it with the incomming data
    $receiver = new SMSReceiver(file_get_contents('php://input'));

//    Creating a sender
//    $sender = new SMSSender(SERVER_URL, APP_ID, APP_PASSWORD);

    $message = $receiver->getMessage(); // Get the message sent to the app
    $address = $receiver->getAddress(); // Get the phone no from which the message was sent 

    $logger->WriteLog($receiver->getAddress());

    // Code for LBS
    $request = new LbsRequest($LBS_SERVER_URL);
    $request->setAppId(APP_ID);
    $request->setAppPassword(APP_PASSWORD);
    $request->setSubscriberId($address);
    $request->setServiceType($SERVICE_TYPE);
    $request->setFreshness($FRESHNESS);
    $request->setHorizontalAccuracy($HORIZONTAL_ACCURACY);
    $request->setResponseTime($RESPONSE_TIME);
// LBS end


    list($keyword, $type, $gcid) = explode(" ", $message);



    if (isset($type) || isset($gcid)) {
        // Code for LBS
        $lbsClient = new LbsClient();
        $lbsResponse = new LbsResponse($lbsClient->getResponse($request));
        $lbsResponse->setTimeStamp(getModifiedTimeStamp($lbsResponse->getTimeStamp())); //Changing the timestamp format. Ex: from '2013-03-15T17:25:51+05:30' to '2013-03-15 17:25:51'
        // LBS end
        //DB save


        $saveBin = "INSERT INTO bin_tb(bin_id,type,gcid,lat,lng,date_time) VALUES('" . $address . "','" . $type . "','" . $gcid . "','" . $lbsResponse->getLatitude() . "','" . $lbsResponse->getLongitude() . "',CURRENT_TIMESTAMP)";
        $ccc = mysqli_query($connection, $saveBin);
        if (mysqli_error($connection)) {
            $logger->WriteLog(mysqli_error($connection));
        }
        //http post request
        $params = array(
            "BinId" => $address
        );
        httpPost("http://ideamarthosting.dialog.lk/milindalApps/IdeaApp/tocollector.php", $params);
    } else {
        
    }
} catch (SMSServiceException $e) {
    $logger->WriteLog($e->getErrorCode() . ' ' . $e->getErrorMessage());
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
//set POST variables
    $url = 'http://greeen.brightron.net/greeen_project/index.php/ajax/insert';
    $fields = array('lng' => 6.8830417,
        'lat' => 79.8556852,
        'gcid' => 2,
        'type' => 2
    );

//url-ify the data for the POST
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');

//open connection
    $ch = curl_init();

//set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

//execute post
    $result = curl_exec($ch);

//close connection
    curl_close($ch);
}

?>
