<?php

date_default_timezone_set("Asia/Riyadh");


date_default_timezone_set("Asia/Riyadh");

class ZidStatusUpdate {

// Properties
    public $db;
    public $api_url;
    public $auth_token;

    public function __construct() {

        $this->store_link = "https://api.zid.sa/v1/";
        $this->athentication = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxMTciLCJqdGkiOiJhZmQ0NWQxOWI2MjU1ODMzMmYyMDAzNzRlZDhlYmFiNGZmNTU0ZTg0NDJkNWJkYzdmNTczN2QzNTNlYmZkZGIyMWEwOWJmY2U1MGJlNmVlMiIsImlhdCI6MTY0MjY3MjE4Mi4wMzkzMzksIm5iZiI6MTY0MjY3MjE4Mi4wMzkzNDIsImV4cCI6MTY3NDIwODE4MS45MzAzNjMsInN1YiI6IjE4NTkzMiIsInNjb3BlcyI6WyJ0aGlyZC1wYXJ0aWVzLWFwaXMiXX0.Mglp60a7EB1nj5q0j2YDo1Oy6MWD-Yx3LMPXNelBkT4pLoadl9antLYmKh3QDc6LbFRM6H218HFDrORwC3lc0BqKxvKJpVYeDIFKFOR2u5gP9igX8nEl3UF-XaLKU7t2r-MXeBY3GIeBUS1XaOjMWi-j-k4koKzsR-1RszbfDiA_GSPlZFLMDL1o8hFmrO1reRgeXTgL0qq9h9I-tecpY_9pHsMEDjgszUsNOnEehlZ1l3V3XyXo-3WKmKqeKI0Ml_wKv-5QC-DYVhj2TyScUN0_hw0zaHo7yD2fLOHbwGG8HxioBy5jpPD6UBsMSQ24GjyFWQiZDn3J-oPaO2DijlJKTh6Nk4Tfqv5rzDHZ6R9Kt3MMdLUL7NcqCPBStmkRuP8RgDESm2alKMF8kPcM8b7RJHyZ4XmwxkZ5u87wB3tAwelQiNdY636F9aBQlNbWq2RNCItFbcl0RNbk4g1ERT6F9i2R79eoRzNc85E0KXiKfvhQCKHRmQ6wlKxWmTG_ehFPCgWTcnK6ChVKxek1GDAJMuF2VGYr7UvqKClzz2-RjkkS4K8OOuaXqqfIEDgd6UBo4cgutwqtKyQkb48MnHwzeFhlPjwj1L8TaMdNRYxvHh_b7bUpnxpk_RgXmo9Z-pAfgiE0YbVVHjVjqelKH8UgSv9nni6r4faM4RY7JE0';

        $db_host = 'ajouldb-db-instance-1.ctikm53hr4st.us-east-1.rds.amazonaws.com';
        $db_user = 'ajoulMaster';
        $db_password = "Ajouldb118";
        $db_name = 'fastcoo_online_db_v4';

        $this->db = new mysqli($db_host, $db_user, $db_password, $db_name);
        $this->db->set_charset("UTF8");
        if ($this->db->connect_error) {
            die('Connect Error (' . $this->db->connect_errno . ') '
                    . $this->db->connect_error);
        }
    }

    /**
     * @param type $user_id
     * @return type array()
     * description: fetch all order with POD,DL,RTF
     */
    private function getOrdres($user_id) {
        $sql2 = "SELECT booking_id, slip_no, code, zid_status_update FROM shipment_fm WHERE deleted = 'N' AND status = 'Y' AND code IN('POD', 'DL', 'RTF') AND cust_id = '" . $user_id . "'";

        $result = $this->db->query($sql2);

        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $orders;
    }

    public function updateOrders() {

        $customers = $this->fetchZidCustomers();

        if ($customers) {
            foreach ($customers as $customer) {
                $user_id = $customer['id'];
                //if ($user_id = 683) {
                    $manager_token = $customer['manager_token'];
                    $user_Agent = "Fastcoo/1.00.00 (web)";//$customer['user_Agent'] . "/1.00.00 (web)";
                    //echo "<pre>";print_r($customer);exit;
                    $orders = $this->getOrdres($user_id);

                    if ($orders) {
                        foreach ($orders as $order) {
                            $booking_id = $order['booking_id'];

                            $status = "";
                            $note = "";
                            $tracking_url = "";
                            if ($order['code'] == "POD") {
                                $status = "delivered";
                                $updatedStatus = explode(",", $order['zid_status_update']);
                                if (in_array("POD", $updatedStatus)) {
                                    $cronActive = 0;
                                } else {
                                    $cronActive = 1;
                                }
                            } 

                            if ($cronActive == 1) {
                                $zid_update = $this->zidUpdate($booking_id, $status, $manager_token, $user_Agent);
                                if ($zid_update['message']['description'] == 'Order status has been changed successfully') {
                                    $this->updateShipment($order['slip_no'], $order['code'], $order['zid_status_update']);
                                }
                                echo $order['slip_no'] . " with booking id is " . $order['booking_id'];
                                echo "<pre>";
                                print_r($zid_update);
                            }
                        }
                    } else {
                        /* order not found */
                    }
                //}
            }
        } else {
            /* customer not found */
        }
    }

    private function updateShipment($slip_no, $code, $status) {
        if ($status == '') {
            $code = $code;
        } else {
            $code = $status . ',' . $code;
        }
        $sql = "UPDATE shipment_fm SET zid_status_update='" . $code . "' WHERE slip_no='" . $slip_no . "' LIMIT 1";
        $this->db->query($sql);
    }

    /**
     * description: fetch all salla customers
     * @return type array
     */
    private function fetchZidCustomers() {
        $sql = "select id,seller_id,uniqueid,name,city, manager_token, user_Agent from customer where zid_active = 'Y' and manager_token!='' AND status= 'Y' and access_fm='Y' ";
        $result = $this->db->query($sql);
        $customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        //echo "<pre>";print_r($customers);exit;
        return $customers;
    }

    function zidUpdate($booking_id, $status, $manager_token, $user_Agent) {
        $header = array(
            "Authorization" => "Bearer " . $this->athentication,
            "X-MANAGER-TOKEN" => $manager_token,
            "User-Agent" => 'Fastcoo/1.00.00 (web)',
            "Accept-Language" => "en"
        );
        //echo "<pre>";print_r($header);exit;
        $url = "https://api.zid.sa/v1/managers/store/orders/" . $booking_id . "/change-order-status";

        $status_update = $this->s_curl($url, $header, $status);
        return $status_update;
    }

    private function s_curl($url, $header, $status) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('order_status' => $status),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $header['Authorization'],
                'X-MANAGER-TOKEN: ' . $header['X-MANAGER-TOKEN'],
                'User-Agent: ' . 'Fastcoo/1.00.00 (web)',
                'Accept-Language: en'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);
        return $response;
    }

}

$zid = new ZidStatusUpdate();
$zid->updateOrders();
?>


