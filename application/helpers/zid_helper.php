<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('Getwarehouse_Dropdata')) {

    function Getwarehouse_Dropdata() {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,name FROM warehouse_category where status='Y' and deleted='N' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' ";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}

if (!function_exists('Getallstorage_drop')) {

    function Getallstorage_drop() {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM storage_table where deleted='N' AND status='Y' AND super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}

function AddSKUfromZid($data = null) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->db->insert('items_m', $data);
 // echo $ci->db->last_query();exit;
}

function exist_zidsku_id($sku=null, $super_id=null) {
    $ci = & get_instance();
    $ci->load->database();
    $sql = "select id from items_m where sku='" . $sku . "' and super_id='" . $super_id . "'";
    $query = $ci->db->query($sql);
    $countdata = $query->num_rows();
    $row = $query->row_array();
    //echo $ci->db->last_query();exit;
    if ($countdata > 0)
        return $row['id'];
    else
        return false;
}

function GetAllQtyforSellerby_ID($sku = null, $cust_id = null) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->db->select('items_m.zid_pid,SUM(item_inventory.quantity) as quantity,items_m.sku as sku');
    $ci->db->from('item_inventory');
    $ci->db->join('items_m', 'items_m.id=item_inventory.item_sku');
    $ci->db->where('item_inventory.super_id', $ci->session->userdata('user_details')['super_id']);
    $ci->db->where('items_m.super_id', $ci->session->userdata('user_details')['super_id']);
    $ci->db->where('items_m.id', $sku);
    $ci->db->where('item_inventory.seller_id', $cust_id);
    $query = $ci->db->get();
    return $row = $query->row_array();
}



function GetAllQtyforSeller($sku = null, $cust_id = null) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->db->select('items_m.zid_pid,customer.manager_token,SUM(item_inventory.quantity) as quantity,items_m.sku as sku');
    $ci->db->from('item_inventory');
    $ci->db->join('items_m', 'items_m.id=item_inventory.item_sku');
    $ci->db->join('customer', 'customer.id=item_inventory.seller_id');
    $ci->db->where('item_inventory.super_id', $ci->session->userdata('user_details')['super_id']);
    $ci->db->where('items_m.sku', $sku);
    $ci->db->where('item_inventory.seller_id', $cust_id);
    $ci->db->group_by('item_inventory.item_sku');
    $query = $ci->db->get();
   // echo $ci->db->last_query();
    return $row = $query->row_array();
}

function GetAllQtyforSellerSalla_new($cust_id = null) {
    $ci = & get_instance();
    $ci->load->database();
    
    // $ci->db->select('customer.uniqueid, SUM(item_inventory.quantity) as quantity,items_m.sku');
    $ci->db->select('items_m.zid_pid,customer.manager_token,customer.salla_athentication,customer.zid_sid, SUM(item_inventory.quantity) as quantity,items_m.sku,customer.uniqueid');
    $ci->db->from('item_inventory');
    $ci->db->join('items_m', 'items_m.id=item_inventory.item_sku');
    $ci->db->join('customer', 'customer.id=item_inventory.seller_id');
    $ci->db->where('item_inventory.super_id', $ci->session->userdata('user_details')['super_id']);
     //$ci->db->where('items_m.sku', 'car-04');
    $ci->db->where('item_inventory.seller_id', $cust_id);
     // $ci->db->where('items_m.zid_pid!=','');
    $ci->db->group_by('item_inventory.item_sku');
   
    $query = $ci->db->get();
   // echo $ci->db->last_query(); die;
    return $row = $query->result_array();
}



function GetAllQtyforSeller_new($cust_id = null) {
    $ci = & get_instance();
    $ci->load->database();
    
    $ci->db->select('items_m.zid_pid,customer.manager_token,customer.zid_sid, SUM(item_inventory.quantity) as quantity,items_m.sku');
    $ci->db->from('item_inventory');
    $ci->db->join('items_m', 'items_m.id=item_inventory.item_sku');
    $ci->db->join('customer', 'customer.id=item_inventory.seller_id');
    $ci->db->where('item_inventory.super_id', $ci->session->userdata('user_details')['super_id']);
     //$ci->db->where('items_m.sku', 'car-04');
    $ci->db->where('item_inventory.seller_id', $cust_id);
      $ci->db->where('items_m.zid_pid!=','');
    $ci->db->group_by('item_inventory.item_sku');
   
    $query = $ci->db->get();
   // echo $ci->db->last_query(); die;
    return $row = $query->result_array();
}

function GetAllQtyforSeller_new_zid_count($cust_id = null,$super_id=null,$sku=null) {
    $ci = & get_instance();
    $ci->load->database();
   
    $ci->db->select('count(item_inventory.id) as tcount');
    $ci->db->from('item_inventory');
    $ci->db->join('items_m', 'items_m.id=item_inventory.item_sku');
    $ci->db->join('customer', 'customer.id=item_inventory.seller_id');
    $ci->db->where('item_inventory.super_id', $super_id);
    if(!empty($sku))
    {
     $ci->db->where('items_m.sku', $sku);
        
    }
    $ci->db->where('item_inventory.seller_id', $cust_id);
    $ci->db->where('items_m.zid_pid!=','');
   // $ci->db->group_by('item_inventory.item_sku');
    $query = $ci->db->get();
    //echo $ci->db->last_query(); die;
    
    return $row = $query->row_array()['tcount'];
}

function GetAllQtyforSeller_new_zid($cust_id = null,$page_no=null,$super_id=null,$limit=null,$sku=null) {
    $ci = & get_instance();
    $ci->load->database();
   
        //$limit=200;
     if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
    $ci->db->select('items_m.zid_pid,customer.manager_token,customer.zid_sid, SUM(item_inventory.quantity) as quantity,items_m.sku');
    $ci->db->from('item_inventory');
    $ci->db->join('items_m', 'items_m.id=item_inventory.item_sku');
    $ci->db->join('customer', 'customer.id=item_inventory.seller_id');
    $ci->db->where('item_inventory.super_id', $super_id);
     if(!empty($sku))
    {
     $ci->db->where('items_m.sku', $sku);
        
    }
    $ci->db->where('item_inventory.seller_id', $cust_id);
      $ci->db->where('items_m.zid_pid!=','');
    $ci->db->group_by('item_inventory.item_sku');
   $ci->db->limit($limit,$start);
    $query = $ci->db->get();
   // echo $ci->db->last_query(); die;
    
    return $row = $query->result_array();
}


//*************************Quantity Update function in Zid*************************//


function update_zid_product($quantity = null, $pid = null, $token = null, $storeID = null,$cust_id=null,$sku=null) 
{
      sleep(1);
    $param = array(
        'quantity' => $quantity,
        'id' => $pid,
    );

    $cust_data = GetcustDataSAllaZID($cust_id);
    if ($cust_data['zid_authorization'] != NULL) {
        $bearer = $cust_data['zid_authorization'];
    } else {
        $bearer = site_configTable('zid_provider_token');
    }

    
    // $bearer = site_configTable('zid_provider_token');
    $param = json_encode($param);
    $curl = curl_init();
    $url = "https://api.zid.sa/v1/products/" . $pid . "/";
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS => $param,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$bearer,
            'X-MANAGER-TOKEN: ' . $token,
            'STORE-ID: ' . $storeID,
            'ROLE: Manager',
            'Content-Type: application/json',
            'Accept-Language: en',
        ),
    ));

     $response = curl_exec($curl);

    curl_close($curl);
    $ci = & get_instance();
    $ci->load->database();
    $datalog = array(
  
        'log'=> $response ,
        'cust_id'=>  $cust_id,
       'sku'=>$sku,
       'qty'=>$quantity,
        'system_name'=> 'zid',
        'super_id'=>  $ci->session->userdata('user_details')['super_id'],
        'entry_date'=>date("Y-m-d H:i:s")
    );
      
    $ci->db->insert('zid_qty_update', $datalog);
}
function update_zid_product_cron($quantity = null, $pid = null, $token = null, $storeID = null,$cust_id=null,$sku=null,$super_id=null) 
{
      sleep(1);
    $param = array(
        'quantity' => $quantity,
        'id' => $pid,
    );

    $cust_data = GetcustDataSAllaZID($cust_id);
    if ($cust_data['zid_authorization'] != NULL) {
        $bearer = $cust_data['zid_authorization'];
    } else {
        $bearer = site_configTable('zid_provider_token');
    }
    //echo $bearer; die;
    // $bearer = site_configTable('zid_provider_token');
    $param = json_encode($param);
    $curl = curl_init();
    $url = "https://api.zid.sa/v1/products/" . $pid . "/";
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS => $param,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$bearer,
            'X-MANAGER-TOKEN: ' . $token,
            'STORE-ID: ' . $storeID,
            'ROLE: Manager',
            'Content-Type: application/json',
            'Accept-Language: en',
        ),
    ));

   echo   $response = curl_exec($curl);

    curl_close($curl);
    $ci = & get_instance();
    $ci->load->database();
    $datalog = array(
  
        'log'=> $response ,
        'cust_id'=>  $cust_id,
       'sku'=>$sku,
       'qty'=>$quantity,
        'system_name'=> 'zidC',
        'super_id'=>  $super_id,
        'entry_date'=>date("Y-m-d H:i:s")
    );
      
    $ci->db->insert('zid_qty_update', $datalog);
}

//**************************************************************************//


function updateZidStatus($orderID = null, $token = null, $status = null, $code = null, $label = null, $trackingurl = null,$cust_id=null) {
      sleep(1);
    //echo 'werwqerwqrewqerwqrwqerqew'.$token.'testerewrwrwerewrwererweer';
     $cust_data = GetcustDataSAllaZID($cust_id);
    $url = 'https://api.zid.sa/v1/managers/store/orders/' . $orderID . '/change-order-status';
    $curl = curl_init();
   // $bearer = site_configTable('zid_provider_token');
  //echo '<pre><br>'.$token;
  //print_r(array('order_status' => $status, 'waybill_url' => $label, 'tracking_url' => $trackingurl, 'tracking_number' => $code));
    
    
    $data=json_encode(array('order_status' => $status, 'waybill_url' => $label, 'tracking_url' => $trackingurl, 'tracking_number' => $code));
     if ($cust_data['zid_authorization'] != NULL) {
        $bearer = $cust_data['zid_authorization'];
    } else {
        $bearer = site_configTable('zid_provider_token');
    }
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(array('order_status' => $status, 'waybill_url' => $label, 'tracking_url' => $trackingurl, 'tracking_number' => $code)),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$bearer,
            'X-MANAGER-TOKEN: ' . $token,
            'Content-Type: application/json',
           
            'Accept-Language: en',
        ),
    ));

     $response = curl_exec($curl);

    curl_close($curl);
    $ci = & get_instance();
    $ci->load->database();
   
    $datalog = array(
        'slip_no' =>  $code,
        'status_id' =>  $status,
        'note' =>  $trackingurl,
        'log'=> $response ,
        'cust_id'=>  $cust_id,
        'booking_id'=> $orderID,
        'system_name'=> 'zid tracking system',
        'r_log'=>$data,
        'entry_date'=>date('Y-m-d H:i:s'),
        'super_id'=>  $ci->session->userdata('user_details')['super_id']
    );
    
    
    $ci->db->insert('zid_status_update', $datalog);
   // echo $ci->db->last_query();
}

function ZidPcURL($storeID=null, $store_link=null, $bearer=null,$token=null) {

    sleep(1);
    $curl = curl_init();
    // echo $bearer; die;
    $header=array(
       
        'Authorization: Bearer ' . $bearer,
        'STORE-ID: ' . $storeID,
            'Content-Type: application/json',
        'Accept-Language: ar',
        'ROLE: manager',
        'X-MANAGER-TOKEN: '.$token,


    );
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $store_link,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => $header
));

     $response = curl_exec($curl); 

    curl_close($curl);
    return $response;
}

function checkZidSkuExist($sku=null, $pid=null) {
    $ci = & get_instance();
    $ci->load->database();
    $sql = "select id from items_m where sku='" . $sku . "' and zid_pid = '" . $pid . "'";
    $query = $ci->db->query($sql);
   //echo $ci->db->last_query(); exit;
    $countdata = $query->num_rows();
    return $query->row_array();
}



function deliveryOption($cust_id=null) {
    $ci = & get_instance();
    $ci->load->database();
    $sql = "SELECT  `zid_delivery_name`FROM `zid_deliver_options` WHERE `cust_id` = '" . $cust_id . "'";
    $query = $ci->db->query($sql);
$result=$query->row_array();
    
    return $result['zid_delivery_name'];
}


function deliveryOption_shipping_method_id($cust_id) {
    $ci = & get_instance();
    $ci->load->database();
    $sql = "SELECT  `shipping_method_id` FROM `zid_deliver_options` WHERE `cust_id` = '" . $cust_id . "'";
    $query = $ci->db->query($sql);
$result=$query->result_array();
$dData=array();
    foreach($result as $r)
    {
        array_push($dData, $r['shipping_method_id']);
    }
    return $dData;
}
function deliveryOption_new($cust_id=null) {
    $ci = & get_instance();
    $ci->load->database();
    $sql = "SELECT  delivery_id FROM `zid_deliver_options` WHERE `cust_id` = '" . $cust_id . "'";
    $query = $ci->db->query($sql);
$result=$query->result_array();
$retArray=array();
foreach($result as $r)
    {
      array_push( $retArray,$r['delivery_id']);
    }
    return $retArray;
}


//*************************Quantity Update function in Salla*************************//

function salla_provider_qty_update($qty=null,$customerId=null,$sku=null,$seller_id=null,$slip_no=null,$o_qty=null)
{

    $curl = curl_init();

  
    $postParam=json_encode(array('auth-token'=>'$2y$04$rncDoc3yqrue9Fc6Ey29JOs1Qws4J6yVr9UbF2kDMKWv//xAhJ72y','customerId'=>$customerId,'quantity'=>$qty));
  // echo 'https://s.salla.sa/webhook/diggipacks/product/'.$sku;
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://s.salla.sa/webhook/diggipacks/product/'.$sku,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$postParam,
  CURLOPT_HTTPHEADER => array(
    'content-type: application/json',
  
  ),
));

 $response = curl_exec($curl);

curl_close($curl);

$ci = & get_instance();
$ci->load->database();
//$this->ci->load->library('session');
$datalog = array(

    'log'=> $response ,
    'cust_id'=>  $seller_id,
    'sku'=>$sku,
    'qty'=>$qty,
    'system_name'=> 'salla provider',
    'super_id'=>  $ci->session->userdata('user_details')['super_id'],
    'temp_order_no'=>isset($slip_no)?$slip_no:'N/A',
    'order_from'=>'S',
    'o_qty'=>isset($o_qty)?$o_qty:0
);





$ci->db->insert('salla_qty', $datalog);
 //echo $ci->db->last_query();exit;

}

function update_salla_qty_product($quantity = null, $pid = null, $token = null,$cust_id=null) 
{
   
    
    $param=array('quantity'=>$quantity);
    $request = json_encode($param);
     $url = "https://api.salla.dev/admin/v2/products/quantities/bySku/". $pid ;
  
  
    $curl = curl_init();

       curl_setopt_array($curl, [
           CURLOPT_URL => $url,
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 30,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "PUT",
           CURLOPT_POSTFIELDS => $request,
           CURLOPT_HTTPHEADER => [
               "Authorization: Bearer " . $token,
               "Accept-Language: AR",
               "Content-Type: application/json",
              
           ],
       ]);
 $response = curl_exec($curl);
        curl_close($curl);
        $ci = & get_instance();
        $ci->load->database();
        //$this->ci->load->library('session');
        $datalog = array(
        
            'log'=> $response ,
            'cust_id'=>  $cust_id,
        'sku'=>$pid,
        'qty'=>$quantity,
            'system_name'=> 'salla',
            'super_id'=>  $ci->session->userdata('user_details')['super_id']
        );





    $ci->db->insert('salla_qty', $datalog);

}
// comment 12-07-2023 
function update_status_salla_old($status = null, $note = null, $token = null,$id=null,$cust_id=null,$slip_no=null) 
{
   

    $param = array(
        'status_id' => $status,
        'note' => $note,
    );
    $request = json_encode($param);
    
    $url = "https://api.salla.dev/admin/v2/orders/" . $id . "/status"; 
    $curl = curl_init();

       curl_setopt_array($curl, [
           CURLOPT_URL => $url ,
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 30,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => $request,
           CURLOPT_HTTPHEADER => [
               "Authorization: Bearer " . $token,
               "Accept-Language: AR",
               "Content-Type: application/json",
              
           ],
       ]);
 $response = curl_exec($curl);
    $err = curl_error($curl);

    $ci = & get_instance();
    $ci->load->database();
    $datalog = array(
    'slip_no' =>  $slip_no,
    'status_id' =>  $status,
    'note' =>  $note,
    'log'=> $response ,
    'cust_id'=>  $cust_id,
    'booking_id'=> $id,
    'system_name'=> 'salla',
    'super_id'=>  $ci->session->userdata('user_details')['super_id']
    );




    $ci->db->insert('salla_out_log', $datalog);

    /// echo $ci->db->last_query();exit;
}


function update_shipment_salla($tracking_link = null, $token = null, $id = null, $cust_id = null, $slip_no = null, $pdf_label = null, $super_id=null,$data=array()) {


    $customerData = GetcustDataSAllaZID($cust_id);
    if ($customerData['salla_new'] == 1) {
        
        $url = "https://api.salla.dev/admin/v2/shipments/" . $id;
        
        if(!empty($data['slip_no']))
        {
         $param['shipment_number']=$data['slip_no'];   
        }
        else
        {
          $param['shipment_number']=$slip_no;  
        }
        
        $param['tracking_link']=$tracking_link;
        $param['tracking_number']=$slip_no;
        $param['pdf_label']=$pdf_label;
        $param['shipment_type']='shipment';
        if(!empty($data['salla_order_id']))
        {
        $param['order_id']=$data['salla_order_id'];
        }
    } else {
        $url = "https://api.salla.dev/admin/v2/orders/" . $id . "/update-shipment";
        $param = array(
            "tracking_link" => $tracking_link,
            "shipment_number" => $slip_no,
            "pdf_label" => $pdf_label,
            "shipment_type" => "shipment"
        );
    }
    

    
    $request = json_encode($param);
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS => $request,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $token,
            "Accept-Language: EN",
            "Content-Type: application/json",
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    $ci = & get_instance();
    $ci->load->database();
    $datalog = array(
        'slip_no' => $slip_no,
        'status_id' => !empty($tracking_link) ? $tracking_link : "",
        'note' => $pdf_label,
        'log' => $response,
        'cust_id' => $cust_id,
        'booking_id' => $id,
        'r_log' => $request,
        'system_name' => 'salla-fm-order-update ',
        'super_id' => $super_id
    );

    $ci->db->insert('salla_out_log', $datalog);

    //echo $ci->db->last_query();exit;
}


function GetcustDataSAllaZID($cust_id = null) {
    $ci = & get_instance();
    $ci->load->database();
    $sql = "select stock_update_auto_zid,stock_update_auto_salla,zid_authorization,salla_new,manager_token,salla_athentication,zid_sid,sync_product_zid from customer where id='" . $cust_id . "'";
    $query = $ci->db->query($sql);
    $countdata = $query->num_rows();
    if ($countdata > 0) {
        $row = $query->row_array();
    } else {
        return array();
    }
    return $row;
}

function update_status_salla($status = null, $note = null, $token = null,$id=null,$cust_id=null,$slip_no=null, $pdf_link = null, $super_id = null, $data = array()) 
{
   

    $customerData = GetcustDataSAllaZID($cust_id);
    
    if ($customerData['salla_new'] == 1) {
        $param['status'] = $data['status'];
        $param['status_note'] = $note;
        $param['shipment_number'] = $slip_no;
        $url = "https://api.salla.dev/admin/v2/shipments/" . $id;
        $method = "PUT";
    } else {
        $url = "https://api.salla.dev/admin/v2/orders/" . $id . "/status";
        $param = array(
            'status_id' => $status,
            'note' => $note,
        );
        $method = "POST";
    }
    

    //print_r($param); die;
    $request = json_encode($param);

    // $url = "https://api.salla.dev/admin/v2/orders/" . $id . "/status"; 
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => $request,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $token,
            "Accept-Language: AR",
            "Content-Type: application/json",
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    $ci = & get_instance();
    $ci->load->database();
    $datalog = array(
        'slip_no' => isset($slip_no)?$slip_no:"",
        'status_id' => isset($status)?$status:"",
        'note' => isset($note)?$note:"",
        'log' => isset($response)?$response:"",
        'cust_id' => isset($cust_id)?$cust_id:"",
        'booking_id' => isset($id)?$id:"",
        'system_name' => 'salla status update fm',
        'super_id' => $ci->session->userdata('user_details')['super_id']
    );

    $ci->db->insert('salla_out_log', $datalog);
    //echo $ci->db->last_query();die;
}

function getSallaStatus($token, $cust_id) {
    $url = "https://api.salla.dev/admin/v2/orders/statuses";
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $token,
        ],
    ]);
    $response = curl_exec($curl);
    $responseArray = json_decode($response, true);

    $keys = array_keys(array_column($responseArray['data'], 'name'), 'مسترجع');

    //print_r($keys);
    $rerurnStatus = $responseArray['data'][$keys[0]]['id'];
    $ci = & get_instance();
    $ci->load->database();
    $ci->db->where('id', $cust_id);
    $ci->db->update('customer', array('salla_return_status' => $rerurnStatus));

    return $rerurnStatus;
}