<?php

date_default_timezone_set("Asia/Riyadh");

class Zid {

    // Properties
    public $db;
    public $athentication;
    public $store_link;
    public $api_url;
    public $format;
    public $signMethod;
    public $method;
    public $customer_id;

    public function __construct() {
        // Host : digipack.ctikm53hr4st.us-east-1.rds.amazonaws.com
        // User : digipack
        // Password : digipack2022
        // Port : 3306
      
        $this->store_link = "https://api.zid.sa/v1/";
        $db_host = 'digipack.ctikm53hr4st.us-east-1.rds.amazonaws.com';
        $db_user = 'digipack';
        $db_password = "digipack2022";
        $db_name = 'diggipacks_db';

        $this->db = new mysqli($db_host, $db_user, $db_password, $db_name);
        $this->db->set_charset("UTF8");
        if ($this->db->connect_error) {
            die('Connect Error (' . $this->db->connect_errno . ') '
                    . $this->db->connect_error);
        }
        $sql = $this->db->query("select zid_provider_token from site_config where zid_provider_token!='' limit 1 ");
       
        if($sql->num_rows >0 ){
            $sresult = $sql->fetch_array();
            $auth_zid= $sresult['zid_provider_token'];


        }
        $this->athentication= $auth_zid;
    }

    private function exist_booking_id($booking_id, $cust_id) {
        $check_query = $this->db->query("select id from shipment_fm where booking_id='" . trim($booking_id) . "' and cust_id = '" . trim($cust_id) . "' and deleted='N'");
        $result = $check_query->fetch_array();

        if ($check_query->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

 

    private function Get_client_city($client_name, $cityid) {

        $query = $this->db->query("select city from country where deleted='N' and city!='' and status='Y' and id='" . $cityid . "'");
        $result = $query->fetch_assoc();
        if ($result) {
            return $result['city'];
        }
        return false;
    }

    private function OrderCount($cURL, $athentication, $manager_token, $user_Agent, $page) {
        $curl = curl_init();
//echo $cURL;
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$cURL",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $athentication",
                "X-MANAGER-TOKEN: $manager_token",
               
                "Accept-Language: en",
            ),
        ));

      $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);

        if ($page == 0) {
            return $result['total_order_count'];
        } else {
            return $result;
        }
    }

    public function pushOrder($cust_id=null) {

        $listingQry = $this->db->query("select * from customer where  id='".$cust_id."'  ");
    
while( $customer_data=$listingQry->fetch_array())
{
     echo '<pre>';
    // print_r($customer_data);
        $zid_status = $customer_data['zid_status'];
        $uniqueid = $customer_data['uniqueid'];
        $customer_sender_city = $customer_data['city'];
          $manager_token = $customer_data['manager_token'];
        $user_Agent = $customer_data['user_Agent'] . "/1.00.00 (web)";

       $cURL = $this->store_link . "managers/store/orders?per_page=100&order_status=".$zid_status;
        $result1 = $this->OrderCount($cURL, $this->athentication, $manager_token, $user_Agent, 0);
       // echo '<pre>xxxx'; print_r($result1); exit;
        $PageCount = $result1 / 100;
        $PageCount = ceil($PageCount);

        if ($PageCount > 1) {
            if ($PageCount > 5) {
                $LoopCount = 10;
            } else {
                $LoopCount = $PageCount;
            }
            for ($i = 1; $i <= $PageCount; $i++) {
                $cURL = $this->store_link . "managers/store/orders?per_page=100&order_status=".$zid_status."&page=$i";
                $result = $this->OrderCount($cURL, $this->athentication, $manager_token, $user_Agent, $i);
//echo '<pre>xxxx'; print_r($result);
                foreach ($result['orders'] as $orders) {

                    //echo '<pre>xxxx'; print_r($orders);
                    $booking_id = $orders['id'];
                    $check_booking_id = $this->exist_booking_id($booking_id, $cust_id);

                    if ($check_booking_id) {
                        echo $booking_id . " Exist<br>";
                    } else {

                        $nData= $this-> GetHttprequest($this->athentication,$manager_token, $booking_id);
                        // echo '<pre>yyy'; print_r($nData);
                         $orderdata = $nData['order'];
                           echo'<br> else'. $this->CreateOrderrequest($orderdata,$uniqueid); //exit;
                    }
                }
            }
        } else {
            $cURL = $this->store_link . "managers/store/orders?per_page=100&order_status=".$zid_status."&page=1";
            $result = $this->OrderCount($cURL, $this->athentication, $manager_token, $user_Agent, 1);

            foreach ($result['orders'] as $orders) {

               
                $booking_id = $orders['id'];
                $check_booking_id = $this->exist_booking_id($booking_id, $cust_id);

                if ($check_booking_id) {
                    echo $booking_id . " Exist<br>";
                } else {
                  $nData= $this-> GetHttprequest($this->athentication,$manager_token, $booking_id);
                 // echo '<pre>yyy'; print_r($nData);
                  $orderdata = $nData['order'];
                    echo'<br> else '.$booking_id.':'. $this->CreateOrderrequest($orderdata,$uniqueid); //exit;
                    }
                   
                }
            }
        }
    }
    private function GetHttprequest($zid_provider_token = 0, $manager_token = 0, $order_no = 0) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.sa/v1/managers/store/orders/$order_no/view",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                //"Accept: en",
                "Accept-Language: en",
                "X-MANAGER-TOKEN: " . $manager_token,
                "Authorization:Bearer " . $zid_provider_token,
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result = json_decode($response, true);
        return $result;
    }

    private function CreateOrderrequest($data=array(),$cust_id=null) {
        $data_json=json_encode($data);
       // echo "<br>". $data_json; die;
       $curl = curl_init();
        $url="https://fm.diggipacks.com/zid/getOrder/$cust_id";
       curl_setopt_array($curl, array(
           CURLOPT_URL =>$url ,
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS =>$data_json,
           CURLOPT_HTTPHEADER => array(
               'Content-Type: application/json',
              
           ),
       ));

       $response = curl_exec($curl);

       curl_close($curl);
       // $result = json_decode($response, true);
       return $response;
   }

    }
   

    

$filehandle = fopen("/var/www/html/diggipack_new/demofulfillment/zidlock/cron.lock", "c+");
if (flock($filehandle, LOCK_EX | LOCK_NB)) {
    $zid = new Zid();
    $cust_id=$_GET['cust_id'];
    $zid->pushOrder($cust_id);
    flock($filehandle, LOCK_UN);  // don't forget to release the lock
} else {
    // $myfile = fopen("/var/www/html/fastcoo-solution/demorashof/logs.txt", "a") or die("Unable to open file!");
    // $txt = "cron run at: " . date('Y-m-d H:i:s');
    // fwrite($myfile, "\n" . $txt);
    // fclose($myfile);
    // throw an exception here to stop the next cron job
}

fclose($filehandle);
