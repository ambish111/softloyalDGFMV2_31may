<?php

$conn = new mysqli("digipack.ctikm53hr4st.us-east-1.rds.amazonaws.com", "digipack", "digipack2022", "diggipacks_db");

// Check connection
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
    exit();
}
$sql = "SELECT * FROM `frwd_shipment_log` where status = 'Success' and cc_id = 80 and super_id = 54 and slip_no IN ('DGF15527555562','DGF14093002671','DGF18087633907','DGF19193592660','DGF15727986621','DGF13741415028','DGF14281527700','DGF12976704359','DGF13990809505','DGF11033321586','DGF18688382546','DGF14832726760','DGF19096033270','DGF11492509635','DGF16776522451','DGF18147774256','DGF19692709869','DGF12724895293','DGF12844510195','DGF14319857806','DGF13235791463','DGF14125552132','DGF14630431614','DGF11056168489','DGF11700579209','DGF19398051726','DGF16930012884','DGF19371936627','DGF18852961866','DGF12892039304','DGF13647233420','DGF19256299628','DGF17020115333','DGF16332702704','DGF16442298124')"; 
$query = $conn->query($sql);

if ($query->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($query)) {

        echo "<pre>";print_r($row);die;
     echo '<br>'.   $url = $row['shopify_url'];
       // if (!empty($row['shopify_tag'])) {
            //$url .= "?tags=" . $row['shopify_tag'];

            getOrders($url, $row);
            
        //}
        
    }
}
//$mysqli->close();
$conn->close();
   
    