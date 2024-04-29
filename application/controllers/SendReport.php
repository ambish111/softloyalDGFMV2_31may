<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SendReport extends CI_Controller {

    function __construct() {
        parent::__construct();
//          ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
        $this->load->model('EmailReport_model');
        $this->load->helper('email');
        $this->load->library('M_pdf');
    }

    function generatePDF($filename = null, $html = null) {
        // echo $html;die;
        // print_r($filename);die;
        $mpdf = new mPDF('utf-8', array(200, 120));
        $htmlWithStyle = $html;
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $write = $mpdf->WriteHTML($htmlWithStyle, 2);

        $mpdf->Output('assets/email_report_daily/' . $filename, 'I');
    }

    public function RunMail() {

        $CustArr = $this->EmailReport_model->GetCustomers();
        // print_r($CustArr); die;
        
        foreach ($CustArr as $row) {

           
            
            //==================csv==========================================================//
             $csv_result=$this->EmailReport_model->GetShipmentNumbers_daily_orders($row['id'], $row['super_id']);
             $filename_csv = "Daily_Shipment_List_".date("Y-m-d-H:i:s").".csv";
             $filePath = 'assets/email_report_daily/' . $filename_csv;
             write_file($filePath, $csv_result);
            $csv_file_url=base_url()."assets/email_report_daily/".$filename_csv; 
            //===========================================================================//
       
             $shipMentArr = $this->EmailReport_model->GetShipmentNumbers_daily($row['id'], $row['super_id']);
            $html = $this->load->view('Daily_report_email', ['data' => $shipMentArr, 'cust_data' => $row], true);
            $mail_body = $this->load->view('Daily_report_email_body', ['data' => $shipMentArr, 'cust_data' => $row], true);
            // echo $mail_body; die;
            $pdfFilename = "pdf_" . time() . ".pdf";
            $this->generatePDF($pdfFilename, $html);

            $recipient = 'jagdish@fastcoo.com';
            $subject = 'Daily Report ' . date("d-m-Y H:i:s");
            $body = $mail_body;
              $pdf_file_url=base_url()."assets/email_report_daily/".$pdfFilename; 
            Sendmail($recipient, $subject, $body, 'Diggipacks Daily Report', $pdf_file_url,$csv_file_url);
            // Delete PDF file after sending email
            unlink($filePath);
             unlink("assets/email_report_daily/".$pdfFilename);
            
        }

        //Sendmail('jkhudiya@gmail.com','sub','mess','title');
    }

    public function RunMail_month() {

        $CustArr = $this->EmailReport_model->GetCustomers();
        // print_r($CustArr); die;
        foreach ($CustArr as $row) {

            
            
            //==================csv==========================================================//
             $csv_result=$this->EmailReport_model->GetShipmentNumbers_orders($row['id'], $row['super_id']);
             $filename_csv = "Monthly_Shipment_List_".date("Y-m-d-H:i:s").".csv";
             $filePath = 'assets/email_report_daily/' . $filename_csv;
             write_file($filePath, $csv_result);
            $csv_file_url=base_url()."assets/email_report_daily/".$filename_csv; 
            //===========================================================================//
            
            $shipMentArr = $this->EmailReport_model->GetShipmentNumbers($row['id'], $row['super_id']);
            $html = $this->load->view('monthly_report_email', ['data' => $shipMentArr, 'cust_data' => $row], true);
            $mail_body = $this->load->view('Monthly_report_email_body', ['data' => $shipMentArr, 'cust_data' => $row], true);
           
         
            // echo $mail_body; die;
            $pdfFilename = "pdf_" . time() . ".pdf";
            $this->generatePDF($pdfFilename, $html);

            $recipient = 'jagdish@fastcoo.com';
            $subject = 'Monthly Report ' . date("d-m-Y H:i:s");
            $body = $mail_body;
             $pdf_file_url=base_url()."assets/email_report_daily/".$pdfFilename; 
            Sendmail($recipient, $subject, $body, 'Diggipacks Monthly Report', $pdf_file_url,$csv_file_url);
            unlink($filePath);
             unlink("assets/email_report_daily/".$pdfFilename);
             
        }

        //Sendmail('jkhudiya@gmail.com','sub','mess','title');
    }

}
