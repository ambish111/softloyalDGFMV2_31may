<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MylerzClass {

    public $param;
    

    public function __construct($param = "")
    { 
        // $this->param =$param;
        // $this->pdf = new mPDF('utf-8',array(101,152),0, '', 0, 0, 0, 0, 0, 0);
        
    }


    function getToken($username='',$password='',$api_url=''){
        

        // $url =  "http://fastcoo.net/fastcoo-tech/fs_files/mylerz_api.php";

        $url =  "http://3.233.43.226/diggipacks/fs_files/mylerz_api.php";
        // $token_api_url = $courierArr['api_url']."authorize?client_secret=".$courierArr['courier_pin_no']."&client_id=".$courierArr['courier_account_no']."&username=".$courierArr['user_name']."&password=".$courierArr['password'];
        // echo $api_url;die;

        // $password= urlencode($password);

        $Allarra = array('token_api_url' => $api_url,'action'=>'get_token','username'=>$username,'password'=>$password);
        // $Allarra = array('token_api_url' => $token_api_url,'action'=>'get_token','ship_url'=>$API_URL,'data'=>$json_final_date);
        // echo $url ; 
        // print "<pre>"; print_r($Allarra);die;
        

        $dataJson = json_encode($Allarra);
        
        $headers = array("Content-type: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  0);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  0);
         $response = curl_exec($ch);
        
        curl_close($ch);

        $responseArray = json_decode($response,true);
        return $responseArray;
    }

    function forwardShipment($sellername= null,$ShipArr=array(), $counrierArr=array(), $token= null,$complete_sku= null,$c_id= null,$box_pieces1= null,$super_id= null)
    {

        //$wharehouse = $sellername." ".$counrierArr['company'];
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'mylerz_city', $super_id);        
        $sender_country = getdestinationfieldshow($ShipArr['origin'], 'country', $super_id);        
        $currency = site_configTable("default_currency");
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'mylerz_city', $super_id);
        $receiver_city_code = getdestinationfieldshow($ShipArr['destination'], 'mylerz_city_code', $super_id);
        $receiver_country = getdestinationfieldshow($ShipArr['destination'], 'country' , $super_id);
        
      

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $wharehouse = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $wharehouse = $wharehouse ." - ". site_configTable('company_name'); 
            }
            $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $seller_name =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $seller_name = $seller_name ." - ". site_configTable('company_name'); 
            }
            $store_address = $ShipArr['sender_address'];
            $senderphone = $ShipArr['sender_phone'];
            $senderemail = $ShipArr['sender_email'];
        }
        
        if(!empty($ShipArr['label_sender_name'])){
            $wharehouse =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $wharehouse = $wharehouse ." - ". site_configTable('company_name'); 
            }
        }
        


         $API_URL = $counrierArr['api_url'] . "/api/Orders/AddOrders";
  

        if (empty($box_pieces1)) {
            $box_pieces = 1;
        } else {
            $box_pieces = $box_pieces1;
        }

        if ($ShipArr['weight'] == 0) {
            $weight = 1;
        } else {
            $weight = $ShipArr['weight'];
        }

        $payMode = $ShipArr['mode'];
        if($ShipArr['mode'] == "COD"){
            $cod_amount = $ShipArr['total_cod_amt'];

        }
        elseif ($ShipArr['mode'] == 'CC'){
            $cod_amount = 0;
            $payMode ='PP';
        }

       
        $piecec_array = array();
        for($i=1;$i<=$box_pieces;$i++){
            $piecec_array[] = array(
                "PieceNo" => $i,
                "weight"=>$weight,
                "ItemCategory"=> "Goods",
                "Dimensions"=> "",
                "SpecialNotes"=>$ShipArr['comment']
               );
           }

        $date = date('Y-m-d');
        $time = date('h:i:s');
        $PickupDueDate  = $date."T".$time;
        //echo $PickupDueDate;die;
        $array_data = array(
                array(
                "WarehouseName" => $wharehouse,
                "PickupDueDate"  => $PickupDueDate,
                "Package_Serial" => 1,
                "Service" => "ND",
                "Reference" => $ShipArr['slip_no'],
                "Description" => $complete_sku,
                "Total_Weight" => $weight,
                "Service_Type" => "DTD",
                "Service_Category" => "DELIVERY",
                "Payment_Type" => $payMode,
                "COD_Value"  => $cod_amount,
                "Customer_Name" => $ShipArr['reciever_name'],
                "Mobile_No" => $ShipArr['reciever_phone'],
                "Building_No" => "",
                "Street" => $ShipArr['reciever_address'],
                "Floor_No" => "",
                "Apartment_No" => "",
                "Country" => $receiver_country,
                "City" => $receiver_city,
                "Neighborhood" => $receiver_city_code,
                "District" => "",
                "GeoLocation" => "",
                "Address_Category" =>  "H",
                "CustVal" =>  "",
                "Currency" =>  $currency,
                "Pieces"=> $piecec_array
            )
                
        );
        // echo   $ShipArr['status_descr'] ; die;
             $json_string = json_encode($array_data);
       
        // print_r($array_data); die; 
        
        $url =  "http://3.233.43.226/diggipacks/fs_files/mylerz_api.php";
        //    $password= urlencode($counrierArr['password']);
        // die; 
        $token_api_url = $courierArr['api_url']."authorize?client_secret=".$courierArr['courier_pin_no']."&client_id=".$courierArr['courier_account_no']."&username=".$courierArr['user_name']."&password=".$courierArr['password'];
        
        $Allarra = array('api_url' => $API_URL,'action'=>'forward_shipment','data'=>$json_string,'token'=>$token);
        $dataJson = json_encode($Allarra);
        // echo $dataJson; die; 
        
        $headers = array("Content-type: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  0);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  0);
        $response = curl_exec($ch);
        // echo $response; die; 
        curl_close($ch);

        $responseData = json_decode($response,true);
            // echo "<pre>"; print_r($responseData); die; 
        
        
        $CI =& get_instance();
        $CI->load->model('Ccompany_model');

        if($responseData['IsErrorState'] === false){
            $successstatus  = "Success";
        }else{
            $successstatus  = "Fail";
        }
        $CI->Ccompany_model->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_string);
        return $responseData;
    }

    
    function getLabel($client_awb = null,$token = null, $api_url=  null,$slipNo=null){


        $url = "http://3.233.43.226/diggipacks/fs_files/mylerz_api.php";
        $labelUrl = $api_url.'/api/Packages/GetAWB';

        $Allarra = array('api_url' => $labelUrl,'action'=>'get_label','client_awb'=>$client_awb,'slipNo'=>$slipNo,'token'=>$token);

        

        $dataJson = json_encode($Allarra);
        
        $headers = array("Content-type: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  0);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  0);
        $response = curl_exec($ch);
        
        curl_close($ch);

        $responseData = json_decode($response,true);
        return $responseData;
       


    }

    


}	