<?php

# Fill our vars and run on cli
# $ php -f db-connect-test.php
//error_reporting(-1);
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set("Asia/Riyadh");


$exportDataArr = unserialize($argv[1]);


$super_id= $exportDataArr['super_id'];
$slip_no = $exportDataArr['slip_no'];
$comment = $exportDataArr['comment'];
$box_pieces = $exportDataArr['box_pieces'];
$cc_id = $exportDataArr['cc_id'];
$open_package_flag = $exportDataArr['open_package_flag'];


    $Allarra = array('super_id' =>$super_id,'slip_no' =>$slip_no,'comment' =>$comment,'box_pieces' =>$box_pieces,'cc_id' =>$cc_id, 'open_package_flag'=>$open_package_flag);
    // echo "<pre> fm file ";   print_r($Allarra); die; 

    $url = "https://fm.diggipacks.com/CourierCompany/BulkForwardCompanyReady";
    $dataJson = json_encode($Allarra);
    // echo "<pre> fm sile ";   print_r($Allarra); die; 
    $headers = array("Content-type: application/json");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
   
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
    curl_setopt($ch, CURLOPT_HEADER, 0);
   
    curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 100); 
    
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    $response = curl_exec($ch);
    curl_close($ch);
    // echo $response;die;
    // print "<pre>"; print_r($response);
    //  die;


?>