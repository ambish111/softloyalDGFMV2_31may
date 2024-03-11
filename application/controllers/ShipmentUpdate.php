<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShipmentUpdate extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }

        $this->load->model('Ccompany_model');
        $this->load->model('Shipment_model');
        $this->load->model('Item_model');
        $this->load->helper('utility');

    }

    public function tracking_update_status(){
        $postData = json_decode(file_get_contents('php://input'), true);
        
        $slip_no = $postData['slip_no'];
        //echo $slip_no;die;
        $ShipArr = $this->Shipment_model->getShipmentArr($slip_no);
        //print "<pre>"; print_r($ShipArr);die;
        $frwd_company_id = $ShipArr['frwd_company_id'];
        $super_id = $this->session->userdata('user_details')['super_id'];
        $timeEntry = $this->Shipment_model->trackingTimeUpdateManual($super_id,$slip_no);
         //echo "<pre>"; print_r($ShipArr); die;
        if(!empty($ShipArr)){
            $cCompany = $this->Ccompany_model->ccNamebiid($frwd_company_id);     
    
            $companyurl = array(
                0 => array("company" => "iMile", "url" => "Imile_track.php"),
                1 => array("company" => "Aramex", "url" => "Aramex_track.php"),
                2 => array("company" => "Wadha", "url" => "Wadha_track.php"),
                3 => array("company" => "Zajil", "url" => "Zajil_track.php"),
                4 => array("company" => "UPS", "url" => "UPS_track.php"),
                5 => array("company" => "Thabit", "url" => "Thabit_track.php"),
                6 => array("company" => "Tamex", "url" => "Tamex_track.php"),
                7 => array("company" => "Swex-Express", "url" => "Swex_Express_track.php"),
                8 => array("company" => "Saudi Post", "url" => "Saudipost_track.php"),
                9 => array("company" => "SpeedMile", "url" => "SpeedMile_track.php"),
                10 => array("company" => "SpeedAF", "url" => "SpeedAF_track.php"),
                11 => array("company" => "Smsa", "url" => "smsatrack_update.php"),
                12 => array("company" => "SMI-EXPRESS", "url" => "Smi_Express_track.php"),
                13 => array("company" => "SMB", "url" => "SMB_track.php"),
                14 => array("company" => "SLS", "url" => "SLStrackingFM.php"),
                15 => array("company" => "Shipadelivery", "url" => "Shipadeliver_track.php"),
                16 => array("company" => "Safearrival", "url" => "Safearrival_track.php"),
                17 => array("company" => "Saee", "url" => "Saee_track.php"),
                18 => array("company" => "Red Box", "url" => "redbox_track.php"),
                19 => array("company" => "Postagexp", "url" => "postage_track.php"),
                20 => array("company" => "NAQEL", "url" => "naqel_track.php"),
                21 => array("company" => "Nafath", "url" => "nafath_track.php"),
                22 => array("company" => "MICGO", "url" => "MICGO_track.php"),
                23 => array("company" => "Makhdoom", "url" => "Makhdoom_update.php"),
                24 => array("company" => "Lastpoint", "url" => "Lastpoint_track.php"),
                25 => array("company" => "Labaih", "url" => "Labaih_track.php"),
                26 => array("company" => "Kudhha", "url" => "Kudhha_track.php"),
                27 => array("company" => "Kasib Logistic", "url" => "Kasib_track.php"),
                28 => array("company" => "Gazal", "url" => "Ghazal_track.php"),
                29 => array("company" => "FLOW", "url" => "Flow_track.php"),
                30 => array("company" => "Flamingo", "url" => "Flamingo_track.php"),
                31 => array("company" => "Fetchr", "url" => "Fetchr_track.php"),
                32 => array("company" => "FedEx-Sab Express", "url" => "FedEx_sab_Express_track.php"),
                33 => array("company" => "Dots", "url" => "Dots_track.php"),
                34 => array("company" => "'DHL V2", "url" => "dhl_track.php"),
                35 => array("company" => "Clex", "url" => "Clex_update.php"),
                36 => array("company" => "Bosta", "url" => "Bosta_track.php"),
                37 => array("company" => "Bawani", "url" => "Bawani_track.php"),
                38 => array("company" => "Barqfleet", "url" => "Barq_track.php"),
                39 => array("company" => "Atheryoun", "url" => "Atheryoun_track.php"),
                40 => array("company" => "AJOUL", "url" => "AJOUL_track.php"),
                41 => array("company" => "Ajeek", "url" => "Ajeek_track.php"),
                42 => array("company" => "AJA", "url" => "Aja_track.php"),
                43 => array("company" => "FDA", "url" => "FDA_track.php"),
                44 => array("company" => "MomentsKsa", "url" => "MomentKSA_track.php"),
                45 => array("company" => "Smsa International", "url" => "smsatrack_int_update.php"),
                46 => array("company" => "Aramex International", "url" => "Aramex_int_track.php"),
                47 => array("company" => "Saudi Post V2", "url" => "SaudipostV2_track.php"),
                48 => array("company" => "Turbo-EG", "url" => "TurboEg_track.php"),
                49 => array("company" => "Makhdoom V2", "url" => "MakhdoomV2_track.php"),
                50 => array("company" => "R2-EXPRESS", "url" => "R2_Express_track.php"),
                51 => array("company" => "R2S Express", "url" => "R2S_Express_track.php"),
                52 => array("company" => "Business Flow", "url" => "Business_flow_track.php"),
                53 => array("company" => "Aymakan", "url" => "Aymakan_track.php"),
                54 => array("company" => "J&T", "url" => "JT_track.php"),
                55 => array("company" => "Roz Express", "url" => "Roz_Express_track.php"),
                56 => array("company" => "Shttle", "url" => "shttle_track.php"),
                57 => array("company" => "LAFASTA", "url" => "Lafasta_track.php"),
                58 => array("company" => "GLT", "url" => "GLTtracking.php"),
                59 => array("company" => "J&T Reverse", "url" => "JT_Reverse_track.php"),

            );
    
            foreach ($companyurl as $key => $val) {
                if ($companyurl[$key]['company'] == $cCompany) {
                    $trackUrl = $companyurl[$key]['url'];
                }
              
            }
            if(empty($trackUrl)){
                $trackUrl = 'Fastcoo_tech_track.php';
            }
            // echo $trackUrl; die;
            $param = array('super_id' => $super_id, 'slip_no'=>$slip_no);
            $paramJsone = json_encode($param);
            // echo $paramJsone;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://3.233.43.226/fastcoo/fs_files/fm-track/systemTrack/'.$trackUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $paramJsone,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
    
            $response = curl_exec($curl);
            $erorr = curl_error($curl);

            $jsonObjects = explode('}', $response);

            // Parse the first JSON object
            $firstJsonObject = json_decode($jsonObjects[0] . '}', true);
            echo json_encode($firstJsonObject);
            die;
        }
        // echo "<pre>"; print_r($trackUrl); die;
    }

    public function update_3pl_info(){
        $this->load->view('ShipmentM/update_3pl_info');
    }

    public function ShipmentUpdate_info(){
        $postData = json_decode(file_get_contents('php://input'), true);
        $slipData = explode("\n", $postData['slip_no']);
        $shipmentLoopArray = array_unique($slipData);
        $clientAwb = explode("\n", $postData['client_awb']);
        $shipmentClientArray = array_unique($clientAwb);
        $succssArray  = array();
        $errorArray = array();
        if(empty($postData['client_awb'])){
           
            foreach($shipmentLoopArray as $slipNo){
                $client_details =  $this->Shipment_model->getShipment3plDetails($slipNo,$postData['cc_id']);
                // echo "<pre>"; print_r($client_details); die;
                $client_awb = $client_details[0]['frwd_awb_no'];
                if(!empty($client_awb)){
                    $data_update =  $this->Shipment_model->updateShipment3plDetails($client_awb,$postData['cc_id'],$slipNo);
                    array_push($succssArray, $slipNo);
                }else{
                    array_push($errorArray, $slipNo);
                }
                
            }
           
        }else{
            foreach($shipmentLoopArray as $key=>$slipNo){
                $client_awb = $clientAwb[$key];
                if(!empty($client_awb)){
                    $data_update =  $this->Shipment_model->updateShipment3plDetails($client_awb,$postData['cc_id'],$slipNo);
                    array_push($succssArray, $slipNo);
                }else{
                    array_push($errorArray, $slipNo);
                }
                
            }
            
        }
       
        $return['Success_msg']=$succssArray;
        $return['Error_msg'] = $errorArray;
        echo json_encode($return);
    }
    

}

?>