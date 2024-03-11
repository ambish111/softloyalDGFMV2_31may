<?php

# Fill our vars and run on cli
# $ php -f db-connect-test.php
//error_reporting(-1);
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set("Asia/Riyadh");

$super_id = $_REQUEST['super_id'];
if(empty($super_id)){
    $exportDataArr = unserialize($argv[1]);
    $super_id=$exportDataArr['super_id'];
    
}

// echo json_encode($super_id);die;




$mysqli = new mysqli("digipack.ctikm53hr4st.us-east-1.rds.amazonaws.com", "digipack", "digipack2022", "diggipacks_db");
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

// echo "select shipment_fm.slip_no,shipment_fm.super_id from shipment_fm LEFT JOIN customer on shipment_fm.cust_id=customer.id  where shipment_fm.delivered IN (1) and shipment_fm.code='OC' and shipment_fm.deleted='N' and shipment_fm.forwarded='0'  and shipment_fm.super_id !=0 and customer.auto_forward='Y' and customer.access_fm='Y' and shipment_fm.super_id='".$super_id."' and shipment_fm.wh_id in('41','42','43') group by shipment_fm.slip_no limit 50";die;

$result = $mysqli->query("select shipment_fm.slip_no,shipment_fm.super_id from shipment_fm LEFT JOIN customer on shipment_fm.cust_id=customer.id  where shipment_fm.delivered IN (1) and shipment_fm.code='OC' and shipment_fm.deleted='N' and shipment_fm.forwarded='0'  and shipment_fm.super_id !=0 and customer.auto_forward='Y' and customer.access_fm='Y' and shipment_fm.super_id='".$super_id."' and shipment_fm.wh_id in('41','42','43') group by shipment_fm.slip_no limit 50");

// $result = $mysqli->query("select shipment_fm.slip_no,shipment_fm.super_id from shipment_fm LEFT JOIN customer on shipment_fm.cust_id=customer.id  where shipment_fm.delivered IN (1) and shipment_fm.code='OC' and shipment_fm.deleted='N' and shipment_fm.forwarded='0'  and shipment_fm.super_id !=0 and customer.auto_forward='Y' and customer.access_fm='Y' and shipment_fm.super_id='".$super_id."'  group by shipment_fm.slip_no limit 50");

$ShipmentArray = $result->fetch_all(MYSQLI_ASSOC);
//    echo "<br/><pre>"; print_r($ShipmentArray);  die; 
if (!empty($ShipmentArray)) {

    foreach ($ShipmentArray as $key => $ShipRows) {
//echo "<br/><pre> ShipRows = "; print_r($ShipRows);  //die;

        GetrequestShippongCompany($ShipRows['slip_no'], $ShipRows['super_id']);
    }
}
die; 
function GetrequestShippongCompany($slip_no = null, $super_id = null) {

    $param = array('super_id' =>$super_id,'slip_no'=>$slip_no,'cc_id'=>$exportDataArr['param']['cc_id'],'comment'=>$exportDataArr['param']['comment'],'box_pieces'=>$exportDataArr['param']['box_pieces'],'open_package_flag'=>$open_package_flag);
    
    // print "<pre>";  print_r($param); die; 
    // $paramJsone=json_encode($param);
    // exec('/usr/bin/php  /var/www/html/diggipack_new/demofulfillment/autobulk_assign_fm.php ' . escapeshellarg(serialize($param)) . ' 2>&1 &/dev/null 2>&1 & ',$output);
    //   echo json_encode($output) ;die;
      //  ;
    

      shell_exec('/usr/bin/php /var/www/html/diggipack_new/demofulfillment/autobulk_assign_fm.php ' . escapeshellarg(serialize($param)) . ' > /dev/null 2>/dev/null &');
  
}

?>