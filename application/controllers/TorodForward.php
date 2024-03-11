<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class TorodForward extends MY_Controller  {


        function __construct() {
            // error_reporting(-1);
            // ini_set('display_errors', 1);
            parent::__construct(); 
            if(menuIdExitsInPrivilageArray(22)=='N')
            {
                //redirect(base_url().'notfound'); die;
                
            }  
            
            $this->load->model('Ccompany_model');
            $this->load->model('Shipment_model');
            $this->load->model('ItemInventory_model');
            $this->load->library('form_validation');
            $this->load->helper('utility');
            $this->load->helper('speedaf');
            $this->load->library('M_pdf');
            $this->load->model('Torodcompany_model');
        }

    
   
 
        

    
        public function BulkForwardCompanyTorod()
        {
            // echo "test";die;
            $postData = json_decode(file_get_contents('php://input'), true);
            // echo "<pre>"; print_r($postData); die;
            $CURRENT_TIME = date('H:i:s');
            $CURRENT_DATE = date('Y-m-d H:i:s');
            $super_id = !empty($postData['super_id']) ? $postData['super_id'] : '';
            $warehouse_name = $postData['otherArr']['warehouse'];
            if(!empty($postData['slip_arr']) && !empty($postData['otherArr']))
            {
            $shipmentLoopArray = $postData['slip_arr']; 
            $postData['cc_id']=$postData['otherArr']['cc_id'];
            }else{
                $slipData = explode("\n", $postData['slip_no']);
                $shipmentLoopArray = array_unique($slipData);
            }
            $torod_cc_id = $postData['cc_id'];
            // echo $torod_cc_id; die;
            $invalid_slipNO=array();
            $succssArray=array();
            if($postData['comment']!=''){
                $comment = $postData['comment'];
            }else{
                $comment = '';
            }
            // echo "<pre>"; print_r($shipmentLoopArray); die;   
            if(!empty($shipmentLoopArray))
            { 
                if(!empty($postData))
                { 
                    $box_pieces = !empty($postData['otherArr']['box_pieces']) ? $postData['otherArr']['box_pieces'] : '';   
                    $box_pieces1 = !empty($postData['box_pieces']) ? $postData['box_pieces'] : '';
                    if(!empty($box_pieces)){
                        $box_pieces1 = $box_pieces;
                    }
                foreach ($shipmentLoopArray as $key => $slipNo) 
                {
                    $slipNo=  str_replace('','',$slipNo);
                    $ShipArr=$this->Torodcompany_model->GetTorodDetailsQry(trim($slipNo));
                    $torod_order_id = $ShipArr['torod_order_id'];
                    // print "<pre>111"; print_r($ShipArr);die;
                    if(!empty($ShipArr && $torod_order_id))
                    {
                        
                    //   echo "test"; die;  
                    $counrierArr_table=$this->Torodcompany_model->GetdeliveryCompanyUpdateQry($this->session->userdata('user_details')['super_id'],'Torod');
                    //  print "<pre>111"; print_r($counrierArr_table);die;
                    $c_id = $counrierArr_table['cc_id'];
                    $cc_id = $counrierArr_table['cc_id'];

                    
                if ($counrierArr_table['type'] == 'test') {
                    // echo "test";die;
                        $user_name = $counrierArr_table['user_name_t'];
                        $password = $counrierArr_table['password_t'];
                        $courier_account_no = $counrierArr_table['courier_account_no_t'];
                        $courier_pin_no = $counrierArr_table['courier_pin_no_t'];
                        $start_awb_sequence = $counrierArr_table['start_awb_sequence_t'];
                        $end_awb_sequence = $counrierArr_table['end_awb_sequence_t'];
                        $company = $counrierArr_table['company'];
                        $api_url = $counrierArr_table['api_url_t'];
                        $company_type  = $counrierArr_table['company_type'];
                        $create_order_url = $counrierArr_table['create_order_url'];
                        $account_entity_code = $counrierArr_table['account_entity_code_t'];
                        $account_country_code = $counrierArr_table['account_country_code_t'];                    
                        $auth_token = $counrierArr_table['auth_token_t'];
                        $payment_type = $counrierArr_table['payment_type_t'];
                        $aramex_payment_type = $counrierArr_table['aramex_payment_type_t'];
                        $hub_code = $counrierArr_table['hub_code_t'];
                        $service_code = $counrierArr_table['service_code_t'];
                        $token_api_url = $counrierArr_table['token_api_url_t'];
                        $nafath_username = $counrierArr_table['nafat_username_t'];
                        $imile_istemprature = $counrierArr_table['imile_istemprature'];
                    
                    } else {
                        $user_name = $counrierArr_table['user_name'];
                        $password = $counrierArr_table['password'];
                        $courier_account_no = $counrierArr_table['courier_account_no'];
                        $courier_pin_no = $counrierArr_table['courier_pin_no'];
                        $start_awb_sequence = $counrierArr_table['start_awb_sequence'];
                        $end_awb_sequence = $counrierArr_table['end_awb_sequence'];
                        $company = $counrierArr_table['company'];
                        $api_url = $counrierArr_table['api_url'];
                        $auth_token = $counrierArr_table['auth_token'];
                        $company_type  = $counrierArr_table['company_type'];
                        $create_order_url = $counrierArr_table['create_order_url']; 
                        $account_entity_code = $counrierArr_table['account_entity_code'];
                        $account_country_code = $counrierArr_table['account_country_code'];
                        $payment_type = $counrierArr_table['payment_type'];
                        $aramex_payment_type = $counrierArr_table['aramex_payment_type'];
                        $hub_code = $counrierArr_table['hub_code'];
                        $service_code = $counrierArr_table['service_code'];
                        $token_api_url = $counrierArr_table['token_api_url'];
                        $nafath_username = $counrierArr_table['nafat_username'];
                        $imile_istemprature = $counrierArr_table['imile_istemprature'];
                    }
                    $counrierArr['user_name'] = $user_name; 
                    $counrierArr['payment_type'] = $payment_type; 
                    $counrierArr['password'] = $password;
                    $counrierArr['courier_account_no'] = $courier_account_no;
                    $counrierArr['courier_pin_no'] = $courier_pin_no;
                    $counrierArr['start_awb_sequence'] = $start_awb_sequence;
                    $counrierArr['end_awb_sequence'] = $end_awb_sequence;
                    $counrierArr['company'] = $company;
                    $counrierArr['api_url'] = $api_url;
                    $counrierArr['create_order_url'] = $create_order_url;
                    $counrierArr['company_type'] = $company_type ;
                    $counrierArr['auth_token'] = $auth_token;
                    $counrierArr['account_entity_code'] = $account_entity_code;
                    $counrierArr['account_country_code'] = $account_country_code;
                    $counrierArr['type'] = $counrierArr_table['type'];
                    $counrierArr['cc_id'] = $counrierArr_table['cc_id'];
                    $counrierArr['aramex_payment_type'] = $aramex_payment_type;
                    $counrierArr['hub_code'] = $hub_code;
                    $counrierArr['service_code'] = $service_code;
                    $counrierArr['token_api_url'] = $token_api_url;
                    $counrierArr['nafath_username'] = $nafath_username;
                    $counrierArr['imile_istemprature'] = $imile_istemprature;
                
                    
                    //    print "<pre>"; print_r($counrierArr);die;
        
                    $sku_data = $this->Ccompany_model->Getskudetails_forward($slipNo);
                    $sku_all_names = array();
                    $sku_total = 0;
            
                    foreach ($sku_data as $key => $val) {
                            if($ShipArr['super_id'] == 1143){
                                $skunames_quantity = $sku_data[$key]['sku'] . "/ Qty:" . $sku_data[$key]['piece'];
                            }else{
                                $skunames_quantity = $sku_data[$key]['name'] . "/ Qty:" . $sku_data[$key]['piece'];
                            }
                            
                            $sku_total = $sku_total + $sku_data[$key]['piece'];
                            array_push($sku_all_names, $skunames_quantity);
                    }
                    $sku_all_names = implode(",", $sku_all_names);
                    // echo $sku_all_names ; die ;

                    if ($sku_total != 0) {
                        $complete_sku = $sku_all_names;
                    } else {
                        $complete_sku = $sku_all_names;
                    }
                    // echo	"compp = ".$complete_sku; die; 
                    $super_id = $this->session->userdata('user_details')['super_id']; 
                    // echo $super_id ; die ;
                    // echo $sku_all_names; die;
                    
                    $ShipArr['sku_data']= $sku_data;

                    $pay_mode = trim($ShipArr['mode']);
                    $cod_amount = $ShipArr['total_cod_amt'];

                    if ($pay_mode == 'COD') {
                            $pay_mode = 'P';
                            $CashOnDeliveryAmount = array("Value" => $cod_amount,
                                    "CurrencyCode" => "SAR");
                            $services = 'CODS';
                    } elseif ($pay_mode == 'CC') {
                            $pay_mode = 'P';
                            $CashOnDeliveryAmount = null;                      
                            $services = '';
                    }

                    // echo $company; die;

                    if($company == 'Torod'){
                        // echo $warehouse_name; die;
                        $this->load->helper('torod');
                        // echo "test";die;
                        $response = OrderShipToTorod($ShipArr, $counrierArr, $torod_cc_id,$c_id,$super_id,$warehouse_name);
                        // echo "<pre>"; print_r($response); die;
                        if($response['error'] == "false"){
                            $comment = 'Forwarded to Torod';
                            $Update_data = $this->Torodcompany_model->Update_Shipment_Status($slipNo, $response['data']['client_awb'], $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $response['data']['label'], $c_id, $super_id,'',$box_pieces1,'',$torod_order_id);
                            
                            array_push($succssArray, $slipNo);
                        }else{
                            $returnArr['responseError'][] = $slipNo . ':' .$response['msg'];
                        }
                    }                             
                }else{
                    array_push($invalid_slipNO, $slipNo);
                }
            }
        } 
            $return['invalid_slipNO']=$invalid_slipNO;
            $return['Error_msg']=$returnArr['responseError'];
            //$return['Success_msg']=$returnArr['successAwb'];
            $return['Success_msg']=$succssArray;
        
            
            echo json_encode($return);
        }
        
    } 

// public function Torodforwardshipments(){
//     $this->load->view("ShipmentM/torod_forward_shipments");
// }

    public function torodLog(){
        $this->load->view("generalsetting/TorodLogview");
    }

    public function Torodfilter(){
        $_POST = json_decode(file_get_contents('php://input'), true);


        $status = $_POST['status'];

        $shipments = $this->Torodcompany_model->warehouseTorodLogview($status);


        //$shiparrayexcel = $shipmentsexcel['result'];
        $shiparray = $shipments['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;

        $tolalShip = $shipments['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $tolalShip;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }

        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function torod_Automation(){

        $this->load->view('ShipmentM/torod_automation');
    }

    public function update_automation(){
        $auto_val = $_POST['automation'];
            $this->Torodcompany_model->Update_Automation_Torod($auto_val);
            $this->session->set_flashdata('Success', "Automation Updated Successfully");

            redirect(base_url('torod_automation'));
    // echo "<pre>"; print_r($_POST); die;
    }
    
    

}
?>
