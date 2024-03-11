<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Buk_update extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Buk_update_model');
    }

    public function cod_update_3pl() {
        if (menuIdExitsInPrivilageArray(161) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('ShipmentM/cod_update_3pl');
    }

    function cod_update_3pl_format() {
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array(
            "AWB Number",
            "3PL Received COD",
            "COD Received Date",
        );

        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="3PL COD Update Bulk.xls"');
        $object_writer->save('php://output');
    }

    function shipmentsBulk() {
        if (isset($_FILES["file"]["name"])) {
            $path = $_FILES["file"]["tmp_name"];
            $this->load->library("excel");
            $current_date = date("Y-m-d");
            $object = PHPExcel_IOFactory::load($path);
            $errors = array();
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {


                    $awb_number = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());

                    $cod_received_3pl = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $cod_received_date = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());

                    if (!empty($awb_number) && !empty($cod_received_3pl) && !empty($cod_received_date)) {
                        $cod_received_date = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($cod_received_date));
                        $year = date("Y", strtotime($cod_received_date));
                        $month = date("m", strtotime($cod_received_date));
                        $day = date("d", strtotime($cod_received_date));
                        $checkdate = checkdate($month, $day, $year);
                        if ($checkdate == true) {
                            if ($cod_received_3pl > 0) {
                                $result = $this->Buk_update_model->ShipData($awb_number);
                                if (!empty($result)) {


                                    if ($result['cod_received_3pl'] <= 0) {
                                        $update_array = array("cod_received_3pl" => $cod_received_3pl, 'cod_received_date' => $cod_received_date);
                                        $req = $this->Buk_update_model->Getupdateshipemnt($update_array, $awb_number);
                                        if ($req == true) {
                                            $n_array = array("valid_awb" => $awb_number);
                                            array_push($errors, $n_array);
                                        } else {
                                            $n_array = array("faild_awb" => $awb_number);
                                            array_push($errors, $n_array);
                                        }
                                    } else {
                                        $n_array = array("cod_already" => $awb_number);
                                        array_push($errors, $n_array);
                                    }
                                } else {
                                    
                                   
                                    
                                    $n_array2 = array("invalid_awb" => $awb_number);
                                    array_push($errors, $n_array2);
                                }
                            } else {
                                $n_array = array("invalid_cod" => $row);
                                array_push($errors, $n_array);
                            }
                        } else {
                            $n_array = array("invalid_date" => $row);
                            array_push($errors, $n_array);
                        }
                    } else {
                        $n_array = array("empty_row" => $row);
                        array_push($errors, $n_array);
                    }
                }
                // echo $result;
            }
            

            //echo "<pre>";
           // print_r($errors);
           // die;
            
            $return_arr['invalid_awb'] = array_column($errors, 'invalid_awb');
            $return_arr['empty_row'] = array_column($errors, 'empty_row');
            $return_arr['invalid_date'] = array_column($errors, 'invalid_date');
            $return_arr['invalid_cod'] = array_column($errors, 'invalid_cod');
            $return_arr['cod_already'] = array_column($errors, 'cod_already');
            $return_arr['valid_awb'] = array_column($errors, 'valid_awb');
            $return_arr['faild_awb'] = array_column($errors, 'faild_awb');
            $this->session->set_flashdata('error_all', $return_arr);
            
        } else {
            $this->session->set_flashdata('error', 'file error Bulk add failed');
            
        }
        
        redirect('cod_update_3pl');
        
    }

}

?>