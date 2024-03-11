<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Split extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Split_model');
    }

    public function process($slip_no = null) {
        if (menuIdExitsInPrivilageArray(164) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $data['slip_no'] = $slip_no;
        $this->load->view('ShipmentM/split_order', $data);
    }

    public function Getcheckdata() {
        $post_data = json_decode(file_get_contents('php://input'), true);
        $data = $this->Split_model->GetorderData($post_data['slip_no']);
        $return['result'] = $data;
        $return['count'] = count($data);
        echo json_encode($return);
    }

    public function GetproceedNewOrder() {
        $post_data = json_decode(file_get_contents('php://input'), true);
        $new_awb = Generate_awb_number_fm_slip();
        if (empty($new_awb)) {
            $new_awb = Generate_awb_number_fm_slip();
        }
        if (!empty($post_data) && !empty($new_awb)) {
            $shipArr = $this->Split_model->Getshipdata($post_data[0]['slip_no']);
            if (!empty($shipArr)) {

                $new_order_piece = 0;
                foreach ($post_data as $key => $val) {
                    $val['piece'];
                }
                $pieceArr = array_column($post_data, 'piece');

                $new_order_piece = array_sum($pieceArr);
               // die;

               
                $total_piece = $shipArr['pieces'];
                $new_piece = $total_piece - $new_order_piece;
                
                $old_ship['pieces']=$new_piece;
                $old_ship['ms_type'] = 'M';
                
                $ship_w = array("cust_id" => $shipArr['cust_id'], 'slip_no' => $post_data[0]['slip_no']);
                $shipArr['ms_awb'] = $post_data[0]['slip_no'];
                $shipArr['slip_no'] = $new_awb;
                $shipArr['sku'] = $post_data[0]['sku'];
                $shipArr['ms_type'] = 'S';
                $shipArr['backorder'] = 0;
                $shipArr['pieces'] = $new_order_piece;
                $shipArr['entrydate'] = date('Y-m-d H:i:s');
                $i = 0;
                $j = 1;
                $old_sku = implode(',', array_column($post_data, 'sku'));
                $statusActivites .= "sku removed " . $old_sku;
                $statusvalue[$i]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $statusvalue[$i]['user_type'] = 'fulfillment';
                $statusvalue[$i]['slip_no'] = $post_data[0]['slip_no'];
                $statusvalue[$i]['new_status'] = 11;
                $statusvalue[$i]['code'] = 'OG';
                $statusvalue[$i]['Activites'] = 'Order Generated';
                $statusvalue[$i]['Details'] = addslashes($statusActivites);
                $statusvalue[$i]['comment'] = 'Order Split By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
                $statusvalue[$i]['entry_date'] = date('Y-m-d H:i');
                $statusvalue[$i]['pickup_date'] = date('Y-m-d H:i:s');
                $statusvalue[$i]['super_id'] = $this->session->userdata('user_details')['super_id'];

                $statusvalue[$j]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $statusvalue[$j]['user_type'] = 'fulfillment';
                $statusvalue[$j]['slip_no'] = $new_awb;
                $statusvalue[$j]['new_status'] = 11;
                $statusvalue[$j]['code'] = 'OG';
                $statusvalue[$j]['Activites'] = 'Order Generated';
                $statusvalue[$j]['Details'] = 'Order Generated';
                $statusvalue[$j]['comment'] = 'Order Split By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
                $statusvalue[$j]['entry_date'] = date('Y-m-d H:i');
                $statusvalue[$j]['pickup_date'] = date('Y-m-d H:i:s');
                $statusvalue[$j]['super_id'] = $this->session->userdata('user_details')['super_id'];
                $item_ids = array_column($post_data, 'id');

                $sku_data['slip_no'] = $new_awb;
                $sku_data['entry_date'] = date('Y-m-d H:i');
                $sku_data['ms_type'] = 'S';

                 //  echo "<pre>";
                 //print_r($old_ship); die;
                
                if ($this->Split_model->updateshipment($shipArr)) {
                     $this->Split_model->old_shipment_update($old_ship,$ship_w);
                    $this->Split_model->updatestaus($statusvalue);
                    $this->Split_model->updatediamention_fm($sku_data, $item_ids);
                }
                $this->session->set_flashdata('msg', 'successfully Created');

                //$return=true;
            } else {
                $this->session->set_flashdata('n_error', 'try again');
            }
        } else {
            $this->session->set_flashdata('n_error', 'try again');
        }

        echo json_encode(true);
        // $data = $this->Split_model->GetorderData($post_data['slip_no']);  
    }

}

?>