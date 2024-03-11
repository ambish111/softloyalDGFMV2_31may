<?php

date_default_timezone_set("Asia/Riyadh");

class SallaTrackOrderUpdate {

// Properties
    public $db;
    public $api_url;
    public $super_id;

    public function __construct() {
        error_reporting(-1);
        ini_set('display_errors', 1);
        ini_set('memory_limit', -1);
        $this->api_url = "http://s.salla.sa/webhook/diggipacks/order/";
        $this->super_id = 5;

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
    }

    public function allOrders() {
        $customers = $this->fetchSallaCustomers();
        echo '<pre>';
        //print_r( $customers);

        if ($customers) {
            foreach ($customers as $customer) {
                if ($customer['salla_provider'] == 1 || 1 == 1) {
                    $auth_token = '$2y$04$rncDoc3yqrue9Fc6Ey29JOs1Qws4J6yVr9UbF2kDMKWv//xAhJ72y';
                    $tracking_url1 = "https://track.diggipacks.com";

                    // if (!$this->is_valid_domain_name($tracking_url1)) {
                    //     $tracking_url1 = "https://track.diggipacks.com";
                    // }

                    $new_tracking_url = $this->addhttp($tracking_url1);
                    $orders = $this->sallaOrders($customer);

                    //print_r( $orders); exit;
                    $DL_array = array('3', '4', '5', '8', '13', '29', '28', '20');
                    $RTC_array = array('6', '32');

                    if ($orders) {
                        foreach ($orders as $order) {

                            if (in_array($order['delivered'], $DL_array)) {
                                $order['code'] = 'DL';
                            }
                            if (in_array($order['delivered'], $RTC_array)) {
                                $order['code'] = 'RTC';
                            }
                           // echo '<pre>';
                            //  print_r($order); exit;
                            $shippers_ref_no = $order['booking_id'];
                            $super_id = $order['super_id'];
                             $tracking_number = $order['frwd_awb_no'];
                            $tracking_url = $new_tracking_url . '/' . $order['slip_no'];

                            if ($order['code'] == 'POD' && $order['salla_track_status_updated'] != 1) {
                                //echo 'ty'; exit;
                                $status = 9;
                                $note = 'delivered';
                                $this->Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer, $super_id);
                                $this->shipmentUpdate($order['slip_no'], 1);
                            } else if ($order['code'] == 'RTC' && $order['salla_track_status_updated'] != 0) {

                                $status = 5;
                                $note = 'cancelled';
                                $this->Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer, $super_id);

                                $this->shipmentUpdate($order['slip_no'], 0);
                            } else if ($order['code'] == 'DL' && $order['salla_track_status_updated'] != 3) {
                                $status = 8;
                                $note = 'delivering';
                                $this->Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer, $super_id);

                                $this->shipmentUpdate($order['slip_no'], 3);
                            } else if ($order['code'] == 'D3PL' && $order['salla_track_status_updated'] != 2) {
                                $status = 8;
                                $note = 'delivering';
                                $this->Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer, $super_id);

                                $this->shipmentUpdate($order['slip_no'], 2);
                            }
                            //}
                        }
                    }
                }
            }
        }
    }

    private function shipmentUpdate($slip_no, $salla_track_status_updated) {
        $cond = '';
        if ($salla_track_status_updated == 1 || $salla_track_status_updated == 5) {
            $cond = " ,deliver_status=1";
        }
        echo $sql = "update shipment set salla_track_status_updated = '" . $salla_track_status_updated . "' " . $cond . " where slip_no='" . $slip_no . "' ";
        $result = $this->db->query($sql);
    }

    private function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

    private function sallaOrders($customers) {
        $today = date('Y-m-d');
        $lastdate = date('Y-m-d', strtotime('-30 days'));
       $sql = "select * from shipment where cust_id='" . $customers['cust_id'] . "' and delivered IN ('3','4','5','8','13','29','28','20','6','32','11') and deleted='N' and deliver_status='0'";
        $result = $this->db->query($sql);
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $orders;
    }

    
    private function is_valid_domain_name($domain_name) {
        return (preg_match("/^([a-zd](-*[a-zd])*)(.([a-zd](-*[a-zd])*))*$/i", $domain_name) //valid characters check
                && preg_match("/^.{1,253}$/", $domain_name) //overall length check
                && preg_match("/^[^.]{1,63}(.[^.]{1,63})*$/", $domain_name) ); //length of every label
    }

    function Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer, $super_id) {
        sleep(1);
        $data = array(
            'auth-token' => '$2y$04$rncDoc3yqrue9Fc6Ey29JOs1Qws4J6yVr9UbF2kDMKWv//xAhJ72y',
            'status' => $status,
            'note' => $note,
            'tracking_url' => $tracking_url,
            'tracking_number' => $tracking_number
        );
        echo 'xxx';

      $url = 'https://s.salla.sa/webhook/diggipacks/order/' . $shippers_ref_no;
        $dataJson = json_encode($data);
        $headers = array(
            "Content-type: application/json",
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

        $response = curl_exec($ch);

        $sql = "INSERT INTO `log_salla`(`log_r`, `booking_id`, `cust_id`, `super_id`, `return_log`) VALUES ('" . $dataJson . "','$shippers_ref_no','" . $customer['cust_id'] . "','$super_id','$response')";
        $this->db->query($sql);

        echo '<pre>';
        echo ($response);
    }

    /**
     * description: fetch all salla customers
     * @return type array
     */
    private function fetchSallaCustomers() {

        
        

        // $sql = "select c.id as cust_id,c.uniqueid,s.salla_provider,s.site_url,s.salla_provider_token,c.email,c.phone,c.user_Agent,c.address,c.seller_id,c.super_id,salla_athentication,salla_active,uniqueid,name,city, order_status,company from customer c  left join site_config s on s.super_id = c.super_id where c.salla_access='LM' and s.super_id IN (146) and c.id in (353,369,371)";
         
           $sql = "select c.id as cust_id,c.uniqueid,s.salla_provider,s.site_url,s.salla_provider_token,c.email,c.phone,c.user_Agent,c.address,c.seller_id,c.super_id,salla_athentication,salla_active,uniqueid,name,city, order_status,company from customer c  left join site_config s on s.super_id = c.super_id where c.salla_access='LM' and c.salla_provider=1 and s.salla_provider=1";
//echo $sql; die;
        $result = $this->db->query($sql);
        $customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $customers;
    }

    function __destruct() {
        $this->db->close();
    }

}

$salla = new SallaTrackOrderUpdate();
$salla->allOrders();
?>

