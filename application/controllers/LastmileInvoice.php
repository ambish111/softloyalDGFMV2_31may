<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LastmileInvoice extends MY_Controller {

    function __construct() {
        parent::__construct();
        if (menuIdExitsInPrivilageArray(21) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $this->load->model('LastMile_model');
        $this->load->model('Seller_model');
        $this->load->model('Shipment_model');

        $this->load->helper('utility');
        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function GetcustomerShowdata() {
        $return = $this->Seller_model->find3();
        echo json_encode($return);
    }

    public function GetstaffDropData() {
        $return = getstaff_multycreated();
        echo json_encode($return);
    }

    public function showPayableInvoiceData() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $returnArray = $this->LastMile_model->getviewPayableInvoice($_POST);
        $maniarray = $returnArray['result'];
        foreach ($maniarray as $key => $val) {
            $maniarray[$key]['cod_paid_by1'] = Get_user_name($val['cod_paid_by'], 'user');
            $maniarray[$key]['invoice_created_by'] = Get_user_name($val['invoice_created_by'], 'user');
            $maniarray[$key]['receivable_paid_by'] = Get_user_name($val['receivable_paid_by'], 'user');
            $maniarray[$key]['cod_paid_by'] = Get_user_name($val['cod_paid_by'], 'user');

            $invocieCountArray = invoiceCountnew($val['invoice_no']);
            $InvocieDetailsArray = invoiceDetailnew($val['invoice_no']);

            $maniarray[$key]['invoiceCount'] = $invocieCountArray['total_numCount'];
            $maniarray[$key]['monthly_invoice_no'] = $monthly_invoice_no['total_numCount'];
            $maniarray[$key]['cod_charge_sum'] = $InvocieDetailsArray['cod_charge_sum'];
            $maniarray[$key]['return_charge_sum'] = $InvocieDetailsArray['return_charge_sum'];
            $maniarray[$key]['service_charge_sum'] = $InvocieDetailsArray['service_charge_sum'];
            $maniarray[$key]['vat_sum'] = $InvocieDetailsArray['vat_sum'];
            $maniarray[$key]['cod_amount_sum'] = $InvocieDetailsArray['cod_amount_sum'];
        }
        $dataArray['result'] = $maniarray;
        $dataArray['count'] = $returnArray['count'];
        echo json_encode($dataArray);
    }

    public function viewLmInvoice() {

        $sellers = $this->Seller_model->find3();

        $bulk = array(
            'sellers' => $sellers,
        );

        $this->load->view('lminvoice/viewLmInvoice', $bulk);
    }

    public function createInvoice() {

        $sellers = $this->Seller_model->find3();

        $bulk = array(
            'sellers' => $sellers,
        );

        $this->load->view('lminvoice/create_invoice', $bulk);
    }

    public function createInvoice3pl() {

        $this->load->view('lminvoice/create_invoice_3pl');
    }

    public function createInvoiceAuto() {
        // error_reporting(-1);
        //     ini_set('display_errors', 1);
        $sellers = $this->Seller_model->find2();

        $bulk = array(
            'sellers' => $sellers,
        );
        $dataArray = $this->input->post();
        if (isset($dataArray['create_invoice'])) {
            $awbDataArray = explode(',', $dataArray['awb_array']);
            $pData = array();
            $pData['slip_no'] = $awbDataArray;
            $pData['cust_id'] = $dataArray['seller'];

            $checkReturn = $this->createInvoiceCalulation_auto($pData);
            $this->session->set_flashdata('msg', 'Invoice Successfully Created!');
            redirect(base_url('viewLmInvoice'));
            //print_r($checkReturn['where_in']); die();
        }
        if (isset($dataArray['check_invoice'])) {
            $invoiceData = $this->LastMile_model->allInvoiceData($dataArray);
            $pData = array();
            $alreadyExits = array();
            foreach ($invoiceData as $val) {
                $invoiceCheck = $this->LastMile_model->checkInvoiceExistSingle($val['slip_no']);
                if (!empty($invoiceCheck))
                    $alreadyExits[] = $invoiceCheck[0];
                else
                    $pData['slip_no'][] = $val['slip_no'];
            }
            $pData['cust_id'] = $dataArray['seller'];

            $checkReturn = $this->CheckInvoiceCalulation_auto($pData);

            if (!empty($alreadyExits)) {
                //   echo '<pre>';
                //   print_r($alreadyExits); exit;
                $filename2 = "already-In-Invoice" . date("Y-m-d") . "-" . $dataArray['seller'] . ".csv";
                if (file_exists('assets/lminvoice/' . $filename2))
                    unlink('assets/lminvoice/' . $filename2);
                $csv_file2 = fopen('assets/lminvoice/' . $filename2, 'w+');

                $header_row3 = array("AWB_NO", "INVOICE NUMBER", "CLOSE DATE");
                fputcsv($csv_file2, $header_row3, ',', '"');
                foreach ($alreadyExits as $result) {
                    $row = array(
                        $result['awb_no'],
                        $result['invoice_no'],
                        $result['close_date'],
                    );

                    fputcsv($csv_file2, $row, ',', '"');
                }

                fclose($csv_file2);
                $bulk['already_path'] = $filename2;
            }
            if (!empty($checkReturn['zero_value'])) {
                $filename = "Rateissue-" . date("Y-m-d") . "-" . $dataArray['seller'] . ".csv";

                $header_row = array("AWB_NO", "DESTINATION", "WEIGHT", "MODE", "CLOSE_DATE", "3PL_CLOSE_DATE");
                fputcsv($csv_file, $header_row, ',', '"');
                foreach ($checkReturn['zero_value'] as $result) {
                    $row = array(
                        $result['slip_no'],
                        $result['destination'],
                        $result['weight'],
                        $result['mode'],
                        $result['close_date'],
                        $result['3pl_close_date'],
                    );
                    fputcsv($csv_file, $row, ',', '"');
                }

                fclose($csv_file);
                $bulk['zero_value'] = 1;
                $bulk['zero_value_path'] = $filename;
            }
            if (!empty($checkReturn['avail'])) {
                $AvailableData = array();
                $filename1 = "Available_to_Invoice" . date("Y-m-d") . "-" . $dataArray['seller'] . ".csv";
                if (file_exists('assets/lminvoice/' . $filename1))
                    unlink('assets/lminvoice/' . $filename1);
                $csv_file1 = fopen('assets/lminvoice/' . $filename1, 'w+');

                $header_row1 = array("AWB_NO");
                fputcsv($csv_file1, $header_row1, ',', '"');
                foreach ($checkReturn['avail'] as $result) {
                    $row = array(
                        $result['slip_no']
                    );
                    $AvailableData[] = $result['slip_no'];
                    fputcsv($csv_file1, $row, ',', '"');
                }

                fclose($csv_file1);
                $bulk['avail_path'] = $filename1;
            }
            $bulk['avail'] = implode(',', $AvailableData);

            $bulk['cust_id'] = $dataArray['seller'];
        }
        // print_r($bulk); die();
        $this->load->view('lminvoice/create_invoice_auto', $bulk);
    }

    public function PaymentConfirmUpdaye() {
        $dataArray = $this->input->post();

        if (!empty($_FILES['pro_image']['name'])) {
            $config['upload_path'] = 'assets/invoice_copy/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = $d1 = 'invoice' . mktime(date(h), date(i), date(s), date(m), date(d), date(y));

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('pro_image')) {
                $uploadData = $this->upload->data();
                $imgpath = $config['upload_path'] . '' . $uploadData['file_name'];
            }
        }


        //if(!empty($imgpath))
        {
            $CURRENT_DATE = date("Y-m-d H:i:s");
            $updateinvoiceAarray = array('cod_pay_status' => 'Y', 'cod_paid_by' => $this->session->userdata('user_details')['user_id'], 'cod_paid_date' => $CURRENT_DATE, 'pay_voucher' => $imgpath);
            $updateinvoiceAarrayW = array('invoice_no' => $dataArray['invoice_no'], 'cust_id' => $dataArray['cust_id']);

            $return1 = $this->LastMile_model->GetupdateFinalInvocie($updateinvoiceAarray, $updateinvoiceAarrayW);
        }
        $this->session->set_flashdata('msg', 'Successfully updated!');

        redirect(base_url('viewLmInvoice'));
    }

    public function discountUpdate() {

        $dataArray = $dataArray = $this->input->post();
        print_r($dataArray);
        $invoice_no = $dataArray['invoice_no'];
        $discount = $dataArray['discount'];
        $CURRENT_DATE = date("Y-m-d H:i:s");

        $updateinvoiceAarrayW = array('invoice_no' => $dataArray['invoice_no'], 'cust_id' => $dataArray['cust_id'], 'discount' => $discount);
        $res_data = $this->LastMile_model->addInvoiceUpdateDiscount($updateinvoiceAarrayW);

        $this->session->set_flashdata('msg', 'Discount updated!');

        redirect(base_url('viewLmInvoice'));
    }

    public function bankFeesUpdate() {

        $dataArray = $dataArray = $this->input->post();
        //print_r($dataArray);
        $invoice_no = $dataArray['invoice_no'];
        $bank_fees = $dataArray['bank_fees'];
        $CURRENT_DATE = date("Y-m-d H:i:s");

        $updateinvoiceAarrayW = array('invoice_no' => $dataArray['invoice_no'], 'cust_id' => $dataArray['cust_id'], 'bank_fees' => $bank_fees);
        $res_data = $this->LastMile_model->addInvoiceUpdateDiscount($updateinvoiceAarrayW);

        $this->session->set_flashdata('msg', 'Bank Fees updated!');

        redirect(base_url('viewLmInvoice'));
    }

    public function payableInvoice_update() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;
        $invoice_no = $dataArray['invoice_no'];
        $CURRENT_DATE = date("Y-m-d H:i:s");
        $updateinvoiceAarrayW = array('invoice_no' => $dataArray['invoice_no'], 'cust_id' => $dataArray['cust_id']);
        $updateinvoiceAarray = array('receivable_pay_status' => 'Y', 'receivable_paid_by' => $this->session->userdata('user_details')['user_id'], 'receivable_paid_date' => $CURRENT_DATE, 'rec_voucher' => $dataArray['rec_voucher']);
        $res_data = $this->LastMile_model->addInvoiceUpdate($updateinvoiceAarray, $updateinvoiceAarrayW);

        //===============================================//


        echo json_encode($res_data);
    }

    public function ShowEditpay() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $table_id = $_POST['id'];

        $returnArray = $this->LastMile_model->Getpay_edit($table_id);

        echo json_encode($returnArray);
    }

    public function codreceivablePrint($invoice_no = null) {
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', 1200);
        $result = $this->LastMile_model->codreceivablePrintQry($invoice_no);
        $view['invoiceData'] = $result;

        $this->load->view('lminvoice/bulkallinvoice', $view);
    }

    public function codreceivableArray($invoice_no = null) {
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', 1200);
        $results = $this->LastMile_model->codreceivablePrintQry($invoice_no);

        // print_r($results);exit;
        $filename = "Invoice- " . $invoice_no . ".csv";

        $csv_file = fopen('php://output', 'w');
        // header('Content-Encoding: UTF-8');
        //  header('Content-Type: application/vnd.ms-excel');
        fputs($csv_file, "\xEF\xBB\xBF"); // UTF-8 BOM !!!!!
        header('Content-Encoding: UTF-8');
        header("Content-Type: text/csv");
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $header_row = array("Sr.No.", "Ref No. / الرقم المرجعي	", "Awb No / رقم البوليصة", "Carrier Name", "Status / الحالة	", "Close Date / تاريخ التوصيل	", "	Origin / المصدر	", "Destination / الوجهة", "Weight (Kg) / الوزن", "No. of Pieces / عدد القطع", "Service Type / نوع الخدمة", "COD Amount / قيمة التحصيل", "COD fees / رسوم التحصيل	", "Shipping Service / المجموع الصافي", "VAT / الضريبة", "Grand Total / المجموع الكلي");
        fputcsv($csv_file, $header_row, ',', '"');
        $tCod = 0;
        $tcodfees = 0;
        $treturnCharge = 0;
        $tshippingCharge = 0;
        $tTotal = 0;
        $tVat = 0;
        $tgrandTotal = 0;
        $destinationArray = array();

        foreach ($results as $key => $result) {
            //$key+1;
            $result['sn_no'] = $key + 1;
            if (empty($destinationArray[$result['destination']])) {
                $destinationArray[$result['destination']] = getdestinationfieldshow($result['destination'], 'city');
            }
            $result['destination'] = $destinationArray[$result['destination']];
            if (empty($destinationArray[$result['origin']])) {
                $destinationArray[$result['origin']] = getdestinationfieldshow($result['origin'], 'city');
            }
            $result['origin'] = $destinationArray[$result['origin']];
            $total = $result['cod_charge'] + $result['return_charge'] + $result['service_charge'];
            $vat = round(($total * $result['vat']) / 100, 2);
            $grand_total = $total + $vat;

            $tCod = $tCod + $result['cod_amount'];
            $tcodfees = $tcodfees + $result['cod_charge'];
            // $treturnCharge=$treturnCharge+$result['return_charge'];
            $tshippingCharge = $tshippingCharge + $result['service_charge'];
            $tTotal = $tTotal + $total;
            $tVat = $tVat + $vat;
            $tgrandTotal = $tgrandTotal + $grand_total;

            $in_frwd_company_id = $result['frwd_company_id'];
            if ($in_frwd_company_id == 0) {
                $out_frwd_company_id = GetshpmentDataByawb($result['awb_no'], 'frwd_company_id');
                $carrier_name = GetCourCompanynameId($out_frwd_company_id, 'company');
            } else {
                $carrier_name = GetCourCompanynameId($result['frwd_company_id'], 'company');
            }

            $row = array(
                $result['sn_no'],
                $result['refrence_no'],
                $result['awb_no'],
                $carrier_name,
                $result['status'],
                $result['close_date'],
                // $result['d_attempt'],
                $result['origin'],
                $result['destination'],
                $result['weight'],
                $result['qty'],
                $result['mode'],
                $result['cod_amount'],
                $result['cod_charge'],
                // $result['return_charge'],
                $result['service_charge'],
                //$total,
                $vat,
                $grand_total,
            );
            fputcsv($csv_file, $row, ',', '"');
        }
        // $footer_row = array("", "","","","","","","","","","TOTA","COD Amount / قيمة التحصيل","COD fees / رسوم التحصيل	","Return Charge / قيمة الإرجاع","Shipping Service / المجموع الصافي","Total / المجموع	","VAT / الضريبة","Grand Total / المجموع الكلي");
        //     fputcsv($csv_file, $footer_row, ',', '"');
        $footer_data = array("", "", "", "", "", "", "", "", "", "", "TOTAL", $tCod, $tcodfees, $treturnCharge, $tshippingCharge, $tTotal, $tVat, $tgrandTotal);
        fputcsv($csv_file, $footer_data, ',', '"');
        fclose($csv_file);
//    echo '<pre>';
//    print_r( $row); exit;
        // $this->load->view('lminvoice/bulkallinvoice', $view);
    }

    public function getWeight($slipNo) {
        $this->load->model('Ccompany_model');
        $sku_data = $this->Ccompany_model->Getskudetails_forward($slipNo);
        $sku_all_names = array();
        $sku_total = 0;
        $total_weight = 0;
        $totalcustomerAmt = 0;
        foreach ($sku_data as $key => $val) {

            $total_weight += ($sku_data[$key]['weight'] * $sku_data[$key]['piece']);
        }
        return $total_weight;
    }

    public function CheckInvoiceCalulation_auto($post) {

        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', 1200);
        $this->load->model('Finance_model');

        $show_awb_no = $post['slip_no'];
        $cust_id = $post['cust_id'];
        $invoiceNo = $this->session->userdata('user_details')['super_id'] . date('Ymd') . $cust_id;
        $date = date('Y-m-d');
        $invoiceCheck = $this->LastMile_model->checkInvoiceExist($show_awb_no);

        $vat = site_configTable('default_service_tax');

        $bank_fees = getallsellerdatabyID($cust_id, 'bank_fees', $this->session->userdata('user_details')['super_id']);
        $areadyExit = array();
        $priceZero = array();
        foreach ($invoiceCheck as $key1 => $val1) {

            if (in_array($val1['awb_no'], $slipData)) {
                array_push($areadyExit, $val1['awb_no']);
            }
        }

        $finalArray = array_values(array_diff($show_awb_no, $areadyExit));
        $shipmentdata = $this->Shipment_model->getawbdataqueryInvoice($finalArray, $cust_id);
        $chargeData = $this->LastMile_model->calculateShipCharge($cust_id);
        //$returnData = $this->LastMile_model->calculateReturn($cust_id);
        //echo json_encode($chargeData); exit;
//        foreach ($returnData as $rdata) {
//            if ($rdata['name'] == 'Additional Return')
//                $additionalReturn = $rdata['rate'];
//
//
//            if ($rdata['name'] == 'Return') {
//                $return = $rdata['rate'];
//                $setPiece = $rdata['setpiece'];
//            }
//        }
        $destinationArray = array();

        //print_r($shipmentdata);
        foreach ($shipmentdata as $key => $val) {

            if ($val['weight'] == 0) {
                $weight = $this->getWeight($val['slip_no']);
                $updateData = array('slip_no' => $val['slip_no'], 'weight' => $weight);

                $this->Finance_model->updateTable('shipment_fm', $updateData);
                $val['weight'] = $weight;
            }
            $keyCheck = null;
            if ($val['code'] == 'POD') {

                foreach ($chargeData as $key1 => $val1) {
                    $cityArray = json_decode($val1['city_id'], true);
                    //echo $val['destination'];
                    //echo '<br>'.	in_array($val['destination'],$cityArray); exit;
                    //print_r($cityArray);
                    if (in_array($val['destination'], $cityArray) == true) {
                        $keyCheck = $key1;
                        //break;	
                    }
                }

                //$keyCheck = array_search($val['cc_id'], array_column($chargeData, 'cc_id'));
                $flat_price = $chargeData[$keyCheck]['price'];
                $price = $chargeData[$keyCheck]['flat_price'];
                $max_weight = $chargeData[$keyCheck]['max_weight'];

                if ($val['weight'] > $max_weight) {
                    $additionalWeight = $val['weight'] - $max_weight;
                } else {
                    $additionalWeight = 0;
                }

                $shipCharge = $price + ($flat_price * $additionalWeight);
                $return_charge = 0;
                $status = 'Delivered';
            } else {
                foreach ($chargeData as $key1 => $val1) {
                    $cityArray = json_decode($val1['city_id'], true);
                    //echo $val['destination'];
                    //echo '<br>'.	in_array($val['destination'],$cityArray); exit;
                    //print_r($cityArray);
                    if (in_array($val['destination'], $cityArray) == true) {
                        $keyCheck = $key1;
                        //break;	
                    }
                }

                //$keyCheck = array_search($val['cc_id'], array_column($chargeData, 'cc_id'));
                $flat_price = $chargeData[$keyCheck]['r_price'];
                $price = $chargeData[$keyCheck]['r_flat_price'];
                $max_weight = $chargeData[$keyCheck]['r_max_weight'];

                if ($val['weight'] > $max_weight) {
                    $additionalWeight = $val['weight'] - $max_weight;
                } else {
                    $additionalWeight = 0;
                }

                $shipCharge = 0;
                $return_charge = $price + ($flat_price * $additionalWeight);
                $status = 'Return';
            }
            if ($val['mode'] == 'COD' && $val['code'] == 'POD') {
                $codAmount = $val['total_cod_amt'];
            } else {
                $codAmount = 0;
            }
            if ($shipCharge == 0 && $return_charge == 0) {
                if (empty($destinationArray[$val['destination']])) {
                    $destinationArray[$val['destination']] = getdestinationfieldshow($val['destination'], 'city');
                }
                $val['destination'] = $destinationArray[$val['destination']];
                array_push($priceZero, $val);
            } else {
                $invoiceArray[] = array(
                    'slip_no' => $val['slip_no'],
                );
                //$where_in[] = array('slip_no' => $val['slip_no'], 'pay_invoice_no' => $invoiceNo);
            }
        }
        $finalArrayData['zero_value'] = $priceZero;
        $finalArrayData['avail'] = $invoiceArray;

        return $finalArrayData;
    }

    public function CreateInvoiceCalulation_auto($post) {

        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', 1200);
        $this->load->model('Finance_model');

        $show_awb_no = $post['slip_no'];
        $cust_id = $post['cust_id'];
        $invoiceNo = $this->session->userdata('user_details')['super_id'] . date('Ym') . $cust_id;
        $date = date('Y-m-d');
        $invoiceCheck = $this->LastMile_model->checkInvoiceExist($show_awb_no);

        $vat = site_configTable('default_service_tax');

        $bank_fees = getallsellerdatabyID($cust_id, 'bank_fees', $this->session->userdata('user_details')['super_id']);
        $areadyExit = array();
        $priceZero = array();
        foreach ($invoiceCheck as $key1 => $val1) {

            if (in_array($val1['awb_no'], $slipData)) {
                array_push($areadyExit, $val1['awb_no']);
            }
        }

        $finalArray = array_values(array_diff($show_awb_no, $areadyExit));
        $shipmentdata = $this->Shipment_model->getawbdataqueryInvoice($finalArray, $cust_id);
        $chargeData = $this->LastMile_model->calculateShipCharge($cust_id);
        //$returnData = $this->LastMile_model->calculateReturn($cust_id);
        //echo json_encode($chargeData); exit;
//        foreach ($returnData as $rdata) {
//            if ($rdata['name'] == 'Additional Return')
//                $additionalReturn = $rdata['rate'];
//
//
//            if ($rdata['name'] == 'Return') {
//                $return = $rdata['rate'];
//                $setPiece = $rdata['setpiece'];
//            }
//        }
        $destinationArray = array();

        //print_r($shipmentdata);
        foreach ($shipmentdata as $key => $val) {

            if ($val['weight'] == 0) {
                $weight = $this->getWeight($val['slip_no']);
                $updateData = array('slip_no' => $val['slip_no'], 'weight' => $weight);

                $this->Finance_model->updateTable('shipment_fm', $updateData);
                $val['weight'] = $weight;
            }
            $keyCheck = null;
            if ($val['code'] == 'POD') {
                $status = 'Delivered';
            } else if ($val['code'] == 'RTC' && $val['reverse_type'] == 1) {
                $status = 'RPOD';
            } else if ($val['code'] == 'RPOD' && $val['reverse_type'] == 1) {
                $status = 'RPOD';
            } else {
                $status = 'Return';
            }


            foreach ($chargeData as $key1 => $val1) {
                $cityArray = json_decode($val1['city_id'], true);
                //echo $val['destination'];
                //echo '<br>'.	in_array($val['destination'],$cityArray); exit;
                //print_r($cityArray);
                if (in_array($val['destination'], $cityArray) == true) {
                    $keyCheck = $key1;
                    //break;	
                }
            }
            if ($val['code'] == 'POD' || $val['reverse_type'] == 1) {

                //$keyCheck = array_search($val['cc_id'], array_column($chargeData, 'cc_id'));
                if (!empty($chargeData[$keyCheck]['flat_price']) && $chargeData[$keyCheck]['flat_price'] > 0) {
                    $flat_price = $chargeData[$keyCheck]['price'];
                    $price = $chargeData[$keyCheck]['flat_price'];
                    $max_weight = $chargeData[$keyCheck]['max_weight'];
                } else {
                    $other_citydata = $this->LastMile_model->calculateShipCharge_other_city($cust_id);
                    $flat_price = $other_citydata['price'];
                    $price = $other_citydata['flat_price'];
                    $max_weight = $other_citydata['max_weight'];
                }

                if ($val['weight'] > $max_weight) {
                    $additionalWeight = $val['weight'] - $max_weight;
                } else {
                    $additionalWeight = 0;
                }

                $shipCharge = $price + ($flat_price * $additionalWeight);
                $return_charge = 0;
            } else {
                if (!empty($chargeData[$keyCheck]['flat_price']) && $chargeData[$keyCheck]['r_flat_price'] > 0) {
                    $flat_price = $chargeData[$keyCheck]['r_price'];
                    $price = $chargeData[$keyCheck]['r_flat_price'];
                    $max_weight = $chargeData[$keyCheck]['r_max_weight'];
                } else {
                    $other_citydata = $this->LastMile_model->calculateShipCharge_other_city($cust_id);
                    $flat_price = $other_citydata['r_price'];
                    $price = $other_citydata['r_flat_price'];
                    $max_weight = $other_citydata['r_max_weight'];
                }

                if ($val['weight'] > $max_weight) {
                    $additionalWeight = $val['weight'] - $max_weight;
                } else {
                    $additionalWeight = 0;
                }

                $return_charge = $price + ($flat_price * $additionalWeight);
                $shipCharge = 0;
            }
            if ($val['mode'] == 'COD' && $val['code'] == 'POD') {
                $codAmount = $val['total_cod_amt'];
            } else {
                $codAmount = 0;
            }
            //&& $return_charge == 0
            if ($shipCharge == 0 && $return_charge == 0) {
                if (empty($destinationArray[$val['destination']])) {
                    $destinationArray[$val['destination']] = getdestinationfieldshow($val['destination'], 'city');
                }
                $val['destination'] = $destinationArray[$val['destination']];
                array_push($priceZero, $val);
            } else {
                $invoiceArray[] = array(
                    'invoice_no' => $invoiceNo,
                    'entry_date' => $date,
                    'cust_id' => $cust_id,
                    'receiver_name' => $val['reciever_name'],
                    'origin' => $val['origin'],
                    'destination' => $val['destination'],
                    'awb_no' => $val['slip_no'],
                    'refrence_no' => $val['booking_id'],
                    'qty' => $val['pieces'],
                    'weight' => $val['weight'],
                    'status' => $status,
                    'mode' => $val['mode'],
                    'bank_fees' => $bank_fees,
                    'cod_charge' => '0.00',
                    'return_charge' => $return_charge,
                    'service_charge' => $shipCharge,
                    'cod_amount' => $codAmount,
                    'vat' => $vat,
                    'close_date' => $val['close_date'],
                    'frwd_company_id' => !empty($val['frwd_company_id']) ? $val['frwd_company_id'] : "",
                    'invoice_created_by' => $this->session->userdata('user_details')['user_id'],
                    'invoice_created_date' => $date,
                    'invoice_date' => $date,
                    'super_id' => $this->session->userdata('user_details')['super_id']
                );
                $where_in[] = array('slip_no' => $val['slip_no'], 'pay_invoice_no' => $invoiceNo);
            }
        }
        if (!empty($invoiceArray) && empty($priceZero)) {

            $this->LastMile_model->updateShipmet($where_in);
            $this->LastMile_model->addlmIncoice($invoiceArray);
        }
        $finalArrayData['zero_value'] = $priceZero;
        $finalArrayData['avail'] = $invoiceArray;
        $finalArrayData['where_in'] = $where_in;

        return $finalArrayData;
    }

    public function CreateInvoiceCalulation() {

        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', 1200);
        $this->load->model('Finance_model');
        $_POST = json_decode(file_get_contents('php://input'), true);
        $show_awb_no = $_POST['slip_no'];
        $cust_id = $_POST['cust_id'];
        $invoiceNo = $this->session->userdata('user_details')['super_id'] . date('Ymd') . $cust_id;
        $date = date('Y-m-d');
        $invoiceCheck = $this->LastMile_model->checkInvoiceExist($show_awb_no);

        $vat = site_configTable('default_service_tax');

        $bank_fees = getallsellerdatabyID($cust_id, 'bank_fees', $this->session->userdata('user_details')['super_id']);
        $areadyExit = array();
        $priceZero = array();
        foreach ($invoiceCheck as $key1 => $val1) {

            if (in_array($val1['awb_no'], $slipData)) {
                array_push($areadyExit, $val1['awb_no']);
            }
        }

        $finalArray = array_values(array_diff($show_awb_no, $areadyExit));
        $shipmentdata = $this->Shipment_model->getawbdataqueryInvoice($finalArray, $cust_id);
        $chargeData = $this->LastMile_model->calculateShipCharge($cust_id);
        //$returnData = $this->LastMile_model->calculateReturn($cust_id);
        //echo json_encode($chargeData); exit;
//        foreach ($returnData as $rdata) {
//            if ($rdata['name'] == 'Additional Return')
//                $additionalReturn = $rdata['rate'];
//
//
//            if ($rdata['name'] == 'Return') {
//                $return = $rdata['rate'];
//                $setPiece = $rdata['setpiece'];
//            }
//        }
       // print_r($shipmentdata); die;
        
        foreach ($shipmentdata as $key => $val) {

            if ($val['weight'] == 0) {
                $weight = $this->getWeight($val['slip_no']);
                $updateData = array('slip_no' => $val['slip_no'], 'weight' => $weight);

                $this->Finance_model->updateTable('shipment_fm', $updateData);
                $val['weight'] = $weight;
            }
            $keyCheck = null;
            foreach ($chargeData as $key1 => $val1) {
                $cityArray = json_decode($val1['city_id'], true);
                //echo $val['destination'];
                //echo '<br>'.	in_array($val['destination'],$cityArray); exit;
                //print_r($cityArray);
                if (in_array($val['destination'], $cityArray) == true) {
                    $keyCheck = $key1;
                    //break;	
                }
            }
            if ($val['code'] == 'POD' || $val['reverse_type'] == 1) {
                //$keyCheck = array_search($val['cc_id'], array_column($chargeData, 'cc_id'));
                if (!empty($chargeData[$keyCheck]['flat_price']) && $chargeData[$keyCheck]['flat_price'] > 0) {
                    $flat_price = $chargeData[$keyCheck]['price'];
                    $price = $chargeData[$keyCheck]['flat_price'];
                    $max_weight = $chargeData[$keyCheck]['max_weight'];
                } else {
                    $other_citydata = $this->LastMile_model->calculateShipCharge_other_city($cust_id);
                    $flat_price = $other_citydata['price'];
                    $price = $other_citydata['flat_price'];
                    $max_weight = $other_citydata['max_weight'];
                }

                if ($val['weight'] > $max_weight) {
                    $additionalWeight = $val['weight'] - $max_weight;
                } else {
                    $additionalWeight = 0;
                }

                $shipCharge = $price + ($flat_price * $additionalWeight);
                $return_charge = 0;
            }

            if ($val['code'] == 'RTC' && $val['reverse_type'] == 0) {
                //$keyCheck = array_search($val['cc_id'], array_column($chargeData, 'cc_id'));
                if (!empty($chargeData[$keyCheck]['r_flat_price'])) {
                    $flat_price = $chargeData[$keyCheck]['r_price'];
                    $price = $chargeData[$keyCheck]['r_flat_price'];
                    $max_weight = $chargeData[$keyCheck]['r_max_weight'];
                } else {
                    $other_citydata = $this->LastMile_model->calculateShipCharge_other_city($cust_id);
                    $flat_price = $other_citydata['r_price'];
                    $price = $other_citydata['r_flat_price'];
                    $max_weight = $other_citydata['r_max_weight'];
                }


                if ($val['weight'] > $max_weight) {
                    $additionalWeight = $val['weight'] - $max_weight;
                } else {
                    $additionalWeight = 0;
                }

                $return_charge = $price + ($flat_price * $additionalWeight);
                $shipCharge = 0;
            }
            if ($val['code'] == 'POD') {
                $status = 'Delivered';
            } else if ($val['code'] == 'RTC' && $val['reverse_type'] == 1) {
                $status = 'RPOD';
            } else if ($val['code'] == 'RPOD' && $val['reverse_type'] == 1) {
                $status = 'RPOD';
            } else {
                $status = 'Return';
            }
            if ($val['mode'] == 'COD' && $val['code'] == 'POD') {
                $codAmount = $val['total_cod_amt'];
            } else {
                $codAmount = 0;
            }
            //&& $return_charge == 0
            //echo $shipCharge."//".$return_charge; die;
            if ($shipCharge == 0 && $val['code'] == 'POD') {
                array_push($priceZero, $val['slip_no']);
            } else {
                $invoiceArray[] = array(
                    'invoice_no' => $invoiceNo,
                    'entry_date' => $date,
                    'cust_id' => $cust_id,
                    'receiver_name' => $val['reciever_name'],
                    'origin' => $val['origin'],
                    'destination' => $val['destination'],
                    'awb_no' => $val['slip_no'],
                    'refrence_no' => $val['booking_id'],
                    'qty' => $val['pieces'],
                    'weight' => $val['weight'],
                    'status' => $status,
                    'mode' => $val['mode'],
                    'bank_fees' => $bank_fees,
                    'cod_charge' => '0.00',
                    'return_charge' => $return_charge,
                    'service_charge' => $shipCharge,
                    'cod_amount' => $codAmount,
                    'vat' => $vat,
                    'close_date' => $val['close_date'],
                    'frwd_company_id' => !empty($val['frwd_company_id']) ? $val['frwd_company_id'] : "",
                    'invoice_created_by' => $this->session->userdata('user_details')['user_id'],
                    'invoice_created_date' => $date,
                    'invoice_date' => $date,
                    'super_id' => $this->session->userdata('user_details')['super_id']
                );
                $where_in[] = array('slip_no' => $val['slip_no'], 'pay_invoice_no' => $invoiceNo);
            }
        }

        //  print_r($invoiceArray);
        if (!empty($invoiceArray) && empty($priceZero)) {

            $this->LastMile_model->updateShipmet($where_in);
            $this->LastMile_model->addlmIncoice($invoiceArray);
            // echo $this->db->last_query(); die;
        }

        

        echo json_encode(array('price_zero' => $priceZero));
    }

    public function checkInvoice() {
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', 1200);
        $_POST = json_decode(file_get_contents('php://input'), true);
        $show_awb_no = $_POST['slip_no'];
        $cust_id = $_POST['seller'];
        $SlipNos = preg_replace('/\s+/', ',', $show_awb_no);
        $slip_arr = explode(",", $SlipNos);
        $slipData = array_unique($slip_arr);
        // echo '<pre>';
        // print_r($slipData); exit;
        $data['traking_awb_no'] = $slipData;

        $invoiceCheck = $this->LastMile_model->checkInvoiceExist($slipData);

        $areadyExit = array();
        foreach ($invoiceCheck as $key1 => $val1) {

            if (in_array($val1['awb_no'], $slipData)) {
                array_push($areadyExit, $val1['awb_no']);
            }
        }

        $finalArray = array_values(array_diff($slipData, $areadyExit));
        $shipmentdata = $this->Shipment_model->getawbdataqueryInvoice($finalArray, $cust_id);

        $belongToOther = array();
        $Available = array();
        $statusNotcorrect = array();
        $destinationIssue = array();
        $statusArray = array('POD', 'RTC');

        foreach ($shipmentdata as $key => $val) {
            if ($val['cust_id'] != $cust_id) {
                array_push($belongToOther, $val['slip_no']);
            } elseif (!in_array($val['code'], $statusArray)) {
                array_push($statusNotcorrect, $val['slip_no']);
            } elseif (empty($val['destination'])) {
                array_push($destinationIssue, $val['slip_no']);
            } else {
                array_push($Available, $val['slip_no']);
            }
        }

        $finalArray['belongToOther'] = $belongToOther;
        $finalArray['statusNotcorrect'] = $statusNotcorrect;
        $finalArray['destinationIssue'] = $destinationIssue;
        $finalArray['Available'] = $Available;
        $finalArray['areadyExit'] = $areadyExit;

        echo json_encode($finalArray);
    }

    function Import3plInvoice() {
        $this->load->model('Finance_model');
        if (isset($_FILES["file"]["name"])) {
            $path = $_FILES["file"]["tmp_name"];
            $this->load->library("excel");
            $object = PHPExcel_IOFactory::load($path);
            // echo '<pre>';
            $date = date('Y-m-d');
            $vat = site_configTable('default_service_tax');
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $i = 0;
                $anyErrorShow = 1;

                for ($row = 2; $row <= $highestRow; $row++) {
                    $delivery_awb = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $selleraccountid = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $close_date1 = date('d-m-Y', PHPExcel_Shared_Date::ExcelToPHP(trim($worksheet->getCellByColumnAndRow(2, $row)->getValue())));
                    $close_date = date("Y-m-d", strtotime($close_date1));

                    if (!empty($delivery_awb) && !empty($selleraccountid) && !empty($worksheet->getCellByColumnAndRow(2, $row)->getValue())) {

                        $seller_id = GetallaccountidBysellerID($selleraccountid);

                        $bank_fees = getallsellerdatabyID($seller_id, 'bank_fees', $this->session->userdata('user_details')['super_id']);
                        $invoiceNo = $this->session->userdata('user_details')['super_id'] . date('Ymd') . $seller_id;
                        if ($seller_id > 0) {

                            $shipData = $this->LastMile_model->Getcheck3plInvoiceData($delivery_awb, $seller_id);
                            if (!empty($shipData)) {
                                if ($shipData['cust_id'] == $seller_id) {
                                    $cust_id = $shipData['cust_id'];
                                    $checkInvoiceExist = $this->LastMile_model->checkInvoiceExistSingle($shipData['slip_no']);
                                    if (empty($checkInvoiceExist)) {

                                        $chargeData = $this->LastMile_model->calculateShipCharge($cust_id);
                                        $returnData = $this->LastMile_model->calculateReturn($cust_id);
                                        foreach ($returnData as $rdata) {
                                            if ($rdata['name'] == 'Additional Return')
                                                $additionalReturn = $rdata['rate'];


                                            if ($rdata['name'] == 'Return') {
                                                $return = $rdata['rate'];
                                                $setPiece = $rdata['setpiece'];
                                            }
                                        }
                                        if ($shipData['weight'] == 0) {
                                            $weight = $this->getWeight($shipData['slip_no']);
                                            $updateData = array('weight' => $weight);

                                            $this->LastMile_model->updateTable($updateData, $shipData['slip_no']);
                                            $shipData['weight'] = $weight;
                                        }

                                        foreach ($chargeData as $key1 => $val1) {
                                            $cityArray = json_decode($val1['city_id'], true);
                                            //echo $val['destination'];
                                            //echo '<br>'.	in_array($val['destination'],$cityArray); exit;
                                            //print_r($cityArray);
                                            if (in_array($shipData['destination'], $cityArray) == true) {
                                                $keyCheck = $key1;
                                                //break;	
                                            }
                                        }
                                        if ($shipData['code'] == 'POD' || $shipData['reverse_type'] == 1) {


                                            //$keyCheck = array_search($val['cc_id'], array_column($chargeData, 'cc_id'));

                                            if (!empty($chargeData[$keyCheck]['flat_price']) && $chargeData[$keyCheck]['flat_price'] > 0) {
                                                $flat_price = $chargeData[$keyCheck]['price'];
                                                $price = $chargeData[$keyCheck]['flat_price'];
                                                $max_weight = $chargeData[$keyCheck]['max_weight'];
                                            } else {
                                                $other_citydata = $this->LastMile_model->calculateShipCharge_other_city($cust_id);
                                                $flat_price = $other_citydata['price'];
                                                $price = $other_citydata['flat_price'];
                                                $max_weight = $other_citydata['max_weight'];
                                            }

                                            if ($shipData['weight'] > $max_weight) {
                                                $additionalWeight = $shipData['weight'] - $max_weight;
                                            } else {
                                                $additionalWeight = 0;
                                            }

                                            $shipCharge = $price + ($flat_price * $additionalWeight);
                                            $return_charge = 0;
                                            // $status = 'Delivered';
                                        }
                                        if ($shipData['code'] == 'RTC' && $shipData['reverse_type'] == 0) {


                                            //$keyCheck = array_search($val['cc_id'], array_column($chargeData, 'cc_id'));

                                            if (!empty($chargeData[$keyCheck]['r_flat_price']) && $chargeData[$keyCheck]['r_flat_price'] > 0) {
                                                $flat_price = $chargeData[$keyCheck]['r_price'];
                                                $price = $chargeData[$keyCheck]['r_flat_price'];
                                                $max_weight = $chargeData[$keyCheck]['r_max_weight'];
                                            } else {
                                                $other_citydata = $this->LastMile_model->calculateShipCharge_other_city($cust_id);
                                                $flat_price = $other_citydata['r_price'];
                                                $price = $other_citydata['r_flat_price'];
                                                $max_weight = $other_citydata['r_max_weight'];
                                            }

                                            if ($shipData['weight'] > $max_weight) {
                                                $additionalWeight = $shipData['weight'] - $max_weight;
                                            } else {
                                                $additionalWeight = 0;
                                            }

                                            $return_charge = $price + ($flat_price * $additionalWeight);
                                            $shipCharge = 0;
                                            // $status = 'Delivered';
                                        }
                                        if ($shipData['code'] == 'POD') {
                                            $status = 'Delivered';
                                        } else if ($shipData['code'] == 'RTC' && $shipData['reverse_type'] == 1) {
                                            $status = 'RPOD';
                                        } else if ($shipData['code'] == 'RPOD' && $shipData['reverse_type'] == 1) {
                                            $status = 'RPOD';
                                        } else {
                                            $status = 'Return';
                                        }


                                        if ($shipData['mode'] == 'COD' && $shipData['code'] == 'POD') {
                                            $codAmount = $shipData['total_cod_amt'];
                                        } else {
                                            $codAmount = 0;
                                        }

                                        //$return_charge
                                        if ($shipCharge == 0 && $return_charge == 0) {
                                            $alertArray['priceZero'][] = $delivery_awb;
                                        } else {
                                            $alertArray['valid_no'][] = $delivery_awb;
                                            $invoiceArray[] = array(
                                                'invoice_no' => $invoiceNo,
                                                'entry_date' => $date,
                                                'cust_id' => $cust_id,
                                                'receiver_name' => $shipData['reciever_name'],
                                                'origin' => $shipData['origin'],
                                                'destination' => $shipData['destination'],
                                                'awb_no' => $shipData['slip_no'],
                                                'refrence_no' => $shipData['booking_id'],
                                                'qty' => $shipData['pieces'],
                                                'weight' => $shipData['weight'],
                                                'status' => $status,
                                                'mode' => $shipData['mode'],
                                                'bank_fees' => $bank_fees,
                                                'cod_charge' => '0.00',
                                                'return_charge' => $return_charge,
                                                'service_charge' => $shipCharge,
                                                'cod_amount' => $codAmount,
                                                'vat' => $vat,
                                                'close_date' => $close_date,
                                                'frwd_company_id' => !empty($shipData['frwd_company_id']) ? $shipData['frwd_company_id'] : "",
                                                'invoice_created_by' => $this->session->userdata('user_details')['user_id'],
                                                'invoice_created_date' => $date,
                                                'invoice_date' => $date,
                                                'super_id' => $this->session->userdata('user_details')['super_id']
                                            );
                                            $where_in[] = array('slip_no' => $shipData['slip_no'], 'pay_invoice_no' => $invoiceNo);
                                        }
                                    } else {
                                        $alertArray['already_created'][] = $delivery_awb;
                                    }
                                } else {
                                    $alertArray['different_seller'][] = $delivery_awb;
                                }
                            } else {
                                $alertArray['invalid_order_nummber'][] = $delivery_awb;
                            }
                        } else {
                            $alertArray['invalid_account_no'][] = $selleraccountid;
                        }
                    } else {
                        if (empty($delivery_awb)) {
                            $alertArray['3pl_awb_empty'][] = $row;
                        }
                        if (empty($selleraccountid)) {
                            $alertArray['account_empty'][] = $row;
                        }
                        if (empty($worksheet->getCellByColumnAndRow(2, $row)->getValue())) {
                            $alertArray['close_date_empty'][] = $row;
                        }
                    }

                    $i++;
                }
            }

            //  echo $row;
            // echo "<pre>";
            // print_r($palletsA_error);
            //print_r($invoiceArray);
            //print_r($alertArray);
            //die;

            if (!empty($invoiceArray)) {
                $this->LastMile_model->updateShipmet($where_in);
                $Status = $this->LastMile_model->addlmIncoice($invoiceArray);
            }

            if ($Status == TRUE) {

                $this->session->set_flashdata('errorA', $alertArray);
                redirect('createInvoice3pl');
            } else {

                $this->session->set_flashdata('errorA', $alertArray);
                redirect('createInvoice3pl');
            }
        } else {
            $this->session->set_flashdata('error', 'file error Bulk add failed');
            redirect('createInvoice3pl');
        }
    }

}

?>