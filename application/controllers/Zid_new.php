<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Zid_new extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('utility_helper');
        $this->load->helper('zid_helper');
        $this->load->model('Zid_model');
    }

    public function getOrder($uniqueid, $shipId = null) {
        // echo 'xxx'; exit;
         
        // error_reporting(-1);
        // ini_set('display_errors', 1);
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataJson = json_encode($_POST);
        if (!file_exists('zidLogNewC/' . date('Y-m-d') . '/' . $uniqueid)) {
            mkdir('zidLogNewC/' . date('Y-m-d') . '/' . $uniqueid, 0777, true);
        }
        if (!file_exists('zidLogNewC/zidlock/' . date('Y-m-d') . '/' . $uniqueid)) {
            mkdir('zidLogNewC/zidlock/' . date('Y-m-d') . '/' . $uniqueid, 0777, true);
        }
        //==================log write start========

        $fr = fopen('zidLogNewC/' . date('Y-m-d') . '/' . $uniqueid . '/' . $_POST['id'] . '-' . date('ymdhis') . ' .json', 'w+');
        fwrite($fr, $dataJson);

        fclose($fr);

        ignore_user_abort();
        $file = fopen("zidLogNewC/zidlock/" . date('Y-m-d') . "/" . $uniqueid . "/zidcron" . $_POST['id'] . ".lock", "w+");
        ;

        // exclusive lock, LOCK_NB serves as a bitmask to prevent flock() to block the code to run while the file is locked.
        // without the LOCK_NB, it won't go inside the if block to echo the string
        if (!flock($file, LOCK_EX | LOCK_NB)) {
            echo "Unable to obtain lock, the previous process is still going on.";
        } else {
            $result = $this->zidOrders($uniqueid, $_POST);
            echo $result;
        }
        fclose($file);
    }

    private function zidOrders($uniqueid, $postData) {
        $Order = $postData;
        $customers = $this->Zid_model->fetch_zid_customers($uniqueid);
        //print_r($customers); die;
        if (!empty($customers)) {
            $super_id = $customers['super_id'];
            $booking_id = $Order['id'];

            
            if ($customers['zid_access'] == 'FM') {
                $check_booking_id = exist_booking_id_new($booking_id, $customers['id']);
            }

            if ($customers['zid_access'] == 'LM') {
                $check_booking_id = $this->Zid_model->existLmBookingId_new($booking_id, $customers['id']);
            }


//print_r($check_booking_id);
            if (!empty($check_booking_id)) {

              //  echo $check_booking_id['code']; die;
              //  echo $customers['zid_access']; die;
                $result1['order'] = $Order;
                
                if ($result1['order']['order_status']['code'] == 'cancelled') {

                    if ($customers['zid_access'] == 'FM') {
                         
                       // print_r($check_booking_id);
                        if ($check_booking_id['code'] == 'OG') {

                          // echo "yyy".$check_booking_id['slip_no']; die;
                            $statusvalue['user_id'] = $customers['id'];
                            $statusvalue['user_type'] = 'user';
                            $statusvalue['slip_no'] = $check_booking_id['slip_no'];
                            $statusvalue['new_status'] = 9;
                            $statusvalue['deleted'] = 'N';
                            $statusvalue['code'] = 'C';
                            $statusvalue['Activites'] = 'Order Canceled';
                            $statusvalue['Details'] = 'Order Canceled';
                            $statusvalue['comment'] = "Order Canceled From ZID";
                            $statusvalue['entry_date'] = date('Y-m-d H:i:s');
                            $statusvalue['super_id'] = $super_id;

                            
                            $shipupdateAray['code'] = 'C';
                            $shipupdateAray['delivered'] = 9;
                            $shipupdateAray['close_date'] = date('Y-m-d');

                            // $shipupdateAray_w['slip_no'] = $check_booking_id['slip_no'];
                            // $shipupdateAray_w['super_id'] = $super_id;
                            // $shipupdateAray_w['booking_id'] = $booking_id;
                            if (!empty($check_booking_id['slip_no']) && !empty($shipupdateAray)) {
                                echo "ss";
                                $this->Zid_model->updateShipmentCancel($shipupdateAray, $check_booking_id['slip_no'], $super_id);
                            }
                        } else {
                            $statusvalue['user_id'] = $customers['id'];
                            $statusvalue['user_type'] = 'user';
                            $statusvalue['slip_no'] = $check_booking_id['slip_no'];
                            $statusvalue['new_status'] = $check_booking_id['delivered'];
                            $statusvalue['deleted'] = 'N';
                            $statusvalue['code'] = $check_booking_id['code'];
                            $statusvalue['Activites'] = 'Order Cancel Request From ZID';
                            $statusvalue['Details'] = 'Order Cancel Request From ZID';
                            $statusvalue['comment'] = "From ZID";
                            $statusvalue['entry_date'] = date('Y-m-d H:i:s');
                            $statusvalue['super_id'] = $super_id;
                        }


                        if (!empty($statusvalue)) {
                            $this->Zid_model->addorder_history($statusvalue);
                        }
                        /// echo "sssssss";
                        // print_r($shipupdateAray_w);
                        $data = array("Success" => "Successfully Updated");
                        return json_encode($data);
                    }

                    if ($customers['zid_access'] == 'LM') {

                        if ($check_booking_id['code'] == 'B') {
                            $statusInsertData = array(
                                'slip_no' => $check_booking_id['slip_no'],
                                'new_location' => $check_booking_id['origin'],
                                'new_status' => '33',
                                'pickup_time' => date("H:i:s"),
                                'pickup_date' => date('Y-m-d H:i'),
                                'Activites' => "Order Canceled",
                                'Details' => "Order Canceled",
                                "comment" => "Order Canceled From ZID",
                                'entry_date' => date('Y-m-d H:i'),
                                'user_id' => $customers['id'],
                                'user_type' => 'customer',
                                'code' => "CC",
                                'super_id' => $super_id
                            );
                            $shipment_update = array(
                                "code" => "CC",
                                "delivered" => 33
                            );
                            if (!empty($check_booking_id['slip_no']) && !empty($shipment_update)) {
                                $this->Zid_model->updateShipmentCancel_lm($shipment_update, $check_booking_id['slip_no'], $super_id);
                            }
                        } else {

                            $statusInsertData = array(
                                'slip_no' => $check_booking_id['slip_no'],
                                'new_location' => $check_booking_id['origin'],
                                'new_status' => $check_booking_id['delivered'],
                                'pickup_time' => date("H:i:s"),
                                'pickup_date' => date('Y-m-d H:i'),
                                'Activites' => "Order Cancel Request From ZID",
                                'Details' => "Order Cancel Request From ZID",
                                "comment" => "From ZID",
                                'entry_date' => date('Y-m-d H:i'),
                                'user_id' => $customers['id'],
                                'user_type' => 'customer',
                                'code' => $check_booking_id['code'],
                                'super_id' => $super_id
                            );
                        }
                        if (!empty($statusInsertData)) {
                            $this->Zid_model->addorder_history_lm($statusInsertData);
                        }
                        $data = array("Success" => "Successfully Updated");
                        return json_encode($data);
                    }
                } else {
                    $data = array("error" => "Something Went Wrong !");
                    return json_encode($data);
                }
            } else {
                $data = array("error" => "Order Not Found!");
                return json_encode($data);
            }
        } else {
            $data = array("error" => "Invalid Customer");
            return json_encode($data);
        }
    }

}

?>