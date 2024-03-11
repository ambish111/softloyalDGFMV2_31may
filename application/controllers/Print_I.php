<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Print_I extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Print_I_model');
    }

    public function BulkPrintinvoice_view() {
if (menuIdExitsInPrivilageArray(167) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $this->load->view('print/bulk_print_invoice');
    }

    public function BulkPrintInvoice() {

        $show_awb_no = $this->input->post('show_awb_no');
        $slipData_arr = explode("\r\n", trim($show_awb_no));
        $slipData = array_unique($slipData_arr);
      //  print_r($slipData_arr); die;
        if (!empty($slipData)) {
            $returncondition = $this->print_ready($slipData);
            if (empty($returncondition)) {

                $this->session->set_flashdata('something', 'please enter AWB No.');
                redirect(base_url() . 'print/invoice');
            }
            else
            {
               $this->session->set_flashdata('something', 'please enter AWB No.');
            redirect(base_url() . 'print/invoice'); 
            }
        } else {
            $this->session->set_flashdata('something', 'please enter AWB No.');
            redirect(base_url() . 'print/invoice');
        }
    }

    public function print_ready($slip_nos = array()) {


        $status_update_data = $this->Print_I_model->Get_shipdata($slip_nos);

        if (sizeof($status_update_data) > 0) {
            $fileArray = array();
            $not_found_arr=array();
             
            foreach ($status_update_data as $val) {

                $filePath = $val['product_invoice'];
               // $filePath='/var/www/html/employees/jagdish/diggipacks/fulfillment/assets/product_invoice/' . $val['slip_no'] . '.pdf';
              //  echo $filePath."<br>";
               // echo filesize($filePath);
                if (file_exists($filePath) && filesize($filePath) > 0) {
                    array_push($fileArray, $filePath);
                }
                else
                {
                   
                    array_push($not_found_arr, $val['slip_no']);
                }
            }
            
        }
         // echo "<pre>";
        // print_r($fileArray);die;
        require('./fpdf_new/fpdf.php');
        require('./fpdi/fpdi.php');

         $files = $fileArray;
        if (!empty($files)) {
           
            $pdf = new FPDI('P', 'mm');

            // iterate over array of files and merge
            foreach ($files as $file) {
                //echo $forwardCompany;  die;
                $pageCount = $pdf->setSourceFile($file);
                for ($i = 0; $i < $pageCount; $i++) {
                    $tpl = $pdf->importPage($i + 1, '/MediaBox');
                    $pdf->addPage();
                    $pdf->useTemplate($tpl);
                }
            }


            $pdf->Output('I', 'Invoice-' . date('Ymdhis') . '.pdf');
            die;
        } else {
            return false;
        }
    }

}

?>