<?php
echo "ssssss"; die;
# Fill our vars and run on cli
# $ php -f db-connect-test.php
//error_reporting(-1);
error_reporting(E_ALL);
ini_set('display_errors', false);
ini_set('display_startup_errors', false);
date_default_timezone_set("Asia/Riyadh");

$exportDataArr = unserialize($argv[1]);
$super_id=  $exportDataArr['super_id'];


$slips=$exportDataArr['param']['slip_no'];
$slipData = explode(PHP_EOL, $slips);

    print_r($exportDataArr); die; 
foreach($slipData as $s)
{
    
    $param = array('super_id' =>$super_id,'slip_no'=>$s,'cc_id'=>$exportDataArr['param']['cc_id'],'comment'=>$exportDataArr['param']['comment'],'box_pieces'=>$exportDataArr['param']['box_pieces']);
    
    //  print_r($param); die; 
    // $paramJsone=json_encode($param);
  //  exec('/usr/bin/php  /var/www/html/diggipacks/fullfillment/autobulk_assign_fm.php ' . escapeshellarg(serialize($param)) . ' 2>&1 &/dev/null 2>&1 & ',$output);
      //  print_r($output);

      shell_exec('/usr/bin/php /var/www/html/diggipack_new/demofulfillment/autobulk_assign_fm.php ' . escapeshellarg(serialize($param)) . ' > /dev/null 2>/dev/null &');

}

?>