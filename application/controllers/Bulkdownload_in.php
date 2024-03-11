<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bulkdownload_in extends MY_Controller {

    function __construct() {
        parent::__construct();
        // if (menuIdExitsInPrivilageArray(154) == 'N') {
        //     redirect(base_url() . 'notfound');
        //     die;
        // }
        $this->load->model('Finance_model');
    }

    public function getExport($invoiceNo = null) {
        
        if (!empty($invoiceNo)) {
            ini_set('memory_limit', '1024M');
            ini_set('max_execution_time', 60000); //increase max_execution_time to 10 min if data set is very large
            //create a file
            $filename = "Invoice detail ".$invoiceNo." " . date("Y.m.d") . ".csv";
            $csv_file = fopen('php://output', 'w');
            // header('Content-Encoding: UTF-8');
            //  header('Content-Type: application/vnd.ms-excel');
            fputs($csv_file, "\xEF\xBB\xBF"); // UTF-8 BOM !!!!!
            header('Content-Encoding: UTF-8');
            header("Content-Type: text/csv");
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $results=$this->Finance_model->Geteditinvoicedynamicdata_bulk(array('invoice_no' => $invoiceNo));
            
             $header_row = array("Sr No. / الرقم التسلسلي","AWB no. / رقم البوليصة","Pieces","Weight (Kg) / الوزن (ك.غ)","Picking Charge / سعر الالتقاط","Packing Charge / سعر التغليف","Special Packing / التغليف الخاص","Pallet Charge / سعر الطبليه","Return Charges","Outbound Charge / سعر الشحنات الصادره","Cancel Charge / سعر الالغاء","Box Charge / سعر الكرتون","Total Without Vat / المجموع بدون ضرائب","Total Vat / مجموع الضرائب","Total With Vat / المجموع مع قيمة الضريبة المضافة","Status Code");
              fputcsv($csv_file, $header_row, ',', '"');
              $chargesArray=array();
			  $slipNOCharges=array();
			///echo '<pre>'; print_r($ItemArray); die;
		$viewInfo=array();
              foreach ($results['result'] as $key=>$rowData) {
                if($key==0);
                {
                   array_push($viewInfo,$rowData); 
                }
       if($rowData['portal_charge'] > 0)
                       {
                           $chargesArray['portal_charge']=$rowData['portal_charge'];
                       }
                $invoice_no = $rowData['invoice_no'];
 						$slip_no = $rowData['slip_no'];
 						$cancel_charge = $rowData['cancel_charge'];
 						$special_packing_seller = $rowData['special_packing_seller'];
 						$special_packing_warehouse = $rowData['special_packing_warehouse'];
	 						if($special_packing_seller > 0)
	 						{
	 							$special_packing = $special_packing_seller;
	 						}
	 						else { 
	 							$special_packing = $special_packing_warehouse;
	 						}

                             if($rowData['booking_id'] == '')
			 {
			
				$chargesArray['pickup_charge']=$chargesArray['pickup_charge']+$rowData['pickup_charge'];
				$chargesArray['storage_charge']= $chargesArray['storage_charge']+$rowData['storage_charge'];
				$chargesArray['onhold_charges']=$chargesArray['onhold_charges']+$rowData['onhold_charges'];
				$chargesArray['inventory_charge']=$chargesArray['inventory_charge']+$rowData['inventory_charge'];
				$chargesArray['sku_barcode_print']= ($chargesArray['sku_barcode_print'] + $rowData['sku_barcode_print']);		

				$chargesArray['cancel_charge']=($chargesArray['cancel_charge']+$rowData['cancel_charge']);
				$chargesArray['packing_charge']=$chargesArray['packing_charge']+$rowData['packing_charge'];
				$chargesArray['picking_charge']=$chargesArray['picking_charge']+$rowData['picking_charge'];
				$chargesArray['dispatch_charge']=$chargesArray['dispatch_charge']+$rowData['dispatch_charge'];
				$chargesArray['outbound_charge']=$chargesArray['outbound_charge']+$rowData['outbound_charge'];
				$chargesArray['box_charge']= $chargesArray['box_charge']+$rowData['box_charge'];
				$chargesArray['inbound_charge']=$chargesArray['inbound_charge']+$rowData['inbound_charge'];
				$chargesArray['return_charge']=$chargesArray['return_charge']+$rowData['return_charge'];
				$chargesArray['shipping_charge']=$chargesArray['shipping_charge']+$rowData['shipping_charge'];
				$chargesArray['pallet_charge']=$chargesArray['pallet_charge']+$rowData['pallet_charge'];

			 }
			 else
			 {

				
			 	
				$chargesArray['cancel_charge']=($chargesArray['cancel_charge']+$rowData['cancel_charge']);
				$chargesArray['packing_charge']=$chargesArray['packing_charge']+$rowData['packing_charge'];
				$chargesArray['picking_charge']=$chargesArray['picking_charge']+$rowData['picking_charge'];
				$chargesArray['dispatch_charge']=$chargesArray['dispatch_charge']+$rowData['dispatch_charge'];
				$chargesArray['outbound_charge']=$chargesArray['outbound_charge']+$rowData['outbound_charge'];
				$chargesArray['box_charge']=$chargesArray['box_charge']+$rowData['box_charge'];
				$chargesArray['portal_charge'] = $chargesArray['portal_charge'];
				$chargesArray['inbound_charge']=$chargesArray['inbound_charge']+$rowData['inbound_charge'];
				$chargesArray['return_charge']=$chargesArray['return_charge']+$rowData['return_charge'];
				$chargesArray['shipping_charge']=$chargesArray['shipping_charge']+$rowData['shipping_charge'];
				$chargesArray['pallet_charge']=$chargesArray['pallet_charge']+$rowData['pallet_charge'];
					if($val['special_packing_seller']>0)
					{
						$chargesArray['special_packing']=$chargesArray['special_packing']+$rowData['special_packing_seller'];	
					}
					else
					{
						$chargesArray['special_packing']=$chargesArray['special_packing']+$rowData['special_packing_warehouse'];
					}
					array_push($slipNOCharges,$val);
			 }
 						$return_charge = $rowData['return_charge'];
 						$picking_charge = $rowData['picking_charge'];
 						$packing_charge = $rowData['packing_charge'];
 						// $dispatch_charge = $rowData['dispatch_charge'];
 						$inbound_charge = $rowData['inbound_charge'];
 						$outbound_charge = $rowData['outbound_charge'];
						 $pallet_charge= $rowData['pallet_charge'];
 						$box_charge = $rowData['box_charge'];
 						$shipping_charge = $rowData['shipping_charge'];
 						$sku_barcode_print = $rowData['sku_barcode_print'];
 						$total_without_vat = round(( $pallet_charge+$packing_charge+$box_charge+$picking_charge+$special_packing+$cancel_charge+$outbound_charge+$return_charge),2);
 						$totalvat    =round( (($total_without_vat * 15)/100),2) ;
 						$total_with_vat  =round( ($total_without_vat + $totalvat),2);
 						 $counter = $key + 1;
                         
                  $row = array(
                    $counter,
                    $rowData['slip_no'],
                    $rowData['pieces'],
                    $rowData['weight'],
                    $rowData['picking_charge'],
                    $rowData['packing_charge'],
                    $special_packing,
                    $rowData['pallet_charge'],
                    $rowData['return_charge'],
                    $rowData['outbound_charge'],
                    $rowData['cancel_charge'],
                    $rowData['box_charge'],
                    $total_without_vat,
                    $totalvat,
                    $total_with_vat,
                     $rowData['status'],
                      
                );
                  fputcsv($csv_file, $row, ',', '"'); 
              }
             // $header_row2=array("","Total Charges - التكلفة الإجمالية","","","","","","","","","","","","","");
              
            
             // fputcsv($csv_file, $header_row2, ',', '"');
             // $row2=array("","","","","","","","","","","","","","","");
              //fputcsv($csv_file, $row2, ',', '"'); 
             
               fclose($csv_file);
        }
    }
     public function getExport_new($invoiceNo = null) {
        
         
        if (!empty($invoiceNo)) {
            ini_set('memory_limit', '1024M');
            ini_set('max_execution_time', 60000); //increase max_execution_time to 10 min if data set is very large
            //create a file
            $filename = "Invoice detail ".$invoiceNo." " . date("Y.m.d") . ".csv";
            $csv_file = fopen('php://output', 'w');
            // header('Content-Encoding: UTF-8');
            //  header('Content-Type: application/vnd.ms-excel');
            fputs($csv_file, "\xEF\xBB\xBF"); // UTF-8 BOM !!!!!
            header('Content-Encoding: UTF-8');
            header("Content-Type: text/csv");
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $results=$this->Finance_model->Geteditinvoicedynamicdata_bulk(array('invoice_no' => $invoiceNo));
            
             $header_row = array("Sr No. / الرقم التسلسلي","AWB no. / رقم البوليصة","Pieces","Special Packing Seller/ التغليف المخصص من البائع","Special Packing Warehouse / التغليف المخصص من المستودع","Pallet Charge / سعر الطبليه","Return Charges","Outbound Charge / سعر الشحنات الصادره","Cancel Charge / سعر الالغاء","Box Charge / سعر الكرتون","Total Without Vat / المجموع بدون ضرائب","Status Code");
              fputcsv($csv_file, $header_row, ',', '"');
              $chargesArray=array();
			  $slipNOCharges=array();
			///echo '<pre>'; print_r($ItemArray); die;
		$viewInfo=array();
              foreach ($results['result'] as $key=>$rowData) {
                if($key==0);
                {
                   array_push($viewInfo,$rowData); 
                }
       if($rowData['portal_charge'] > 0)
                       {
                           $chargesArray['portal_charge']=$rowData['portal_charge'];
                       }
                $invoice_no = $rowData['invoice_no'];
 						$slip_no = $rowData['slip_no'];
 						$cancel_charge = $rowData['cancel_charge'];
 						$special_packing_seller = $rowData['special_packing_seller'];
 						$special_packing_warehouse = $rowData['special_packing_warehouse'];
	 						if($special_packing_seller > 0)
	 						{
	 							$special_packing = $special_packing_seller;
	 						}
	 						else { 
	 							$special_packing = $special_packing_warehouse;
	 						}

                             if($rowData['booking_id'] == '')
			 {
			
				$chargesArray['pickup_charge']=$chargesArray['pickup_charge']+$rowData['pickup_charge'];
				$chargesArray['storage_charge']= $chargesArray['storage_charge']+$rowData['storage_charge'];
				$chargesArray['onhold_charges']=$chargesArray['onhold_charges']+$rowData['onhold_charges'];
				$chargesArray['inventory_charge']=$chargesArray['inventory_charge']+$rowData['inventory_charge'];
				$chargesArray['sku_barcode_print']= ($chargesArray['sku_barcode_print'] + $rowData['sku_barcode_print']);		

				$chargesArray['cancel_charge']=($chargesArray['cancel_charge']+$rowData['cancel_charge']);
				$chargesArray['packing_charge']=$chargesArray['packing_charge']+$rowData['packing_charge'];
				$chargesArray['picking_charge']=$chargesArray['picking_charge']+$rowData['picking_charge'];
				$chargesArray['dispatch_charge']=$chargesArray['dispatch_charge']+$rowData['dispatch_charge'];
				$chargesArray['outbound_charge']=$chargesArray['outbound_charge']+$rowData['outbound_charge'];
				$chargesArray['box_charge']= $chargesArray['box_charge']+$rowData['box_charge'];
				$chargesArray['inbound_charge']=$chargesArray['inbound_charge']+$rowData['inbound_charge'];
				$chargesArray['return_charge']=$chargesArray['return_charge']+$rowData['return_charge'];
				$chargesArray['shipping_charge']=$chargesArray['shipping_charge']+$rowData['shipping_charge'];
				$chargesArray['pallet_charge']=$chargesArray['pallet_charge']+$rowData['pallet_charge'];

			 }
			 else
			 {

				
			 	
				$chargesArray['cancel_charge']=($chargesArray['cancel_charge']+$rowData['cancel_charge']);
				$chargesArray['packing_charge']=$chargesArray['packing_charge']+$rowData['packing_charge'];
				$chargesArray['picking_charge']=$chargesArray['picking_charge']+$rowData['picking_charge'];
				$chargesArray['dispatch_charge']=$chargesArray['dispatch_charge']+$rowData['dispatch_charge'];
				$chargesArray['outbound_charge']=$chargesArray['outbound_charge']+$rowData['outbound_charge'];
				$chargesArray['box_charge']=$chargesArray['box_charge']+$rowData['box_charge'];
				$chargesArray['portal_charge'] = $chargesArray['portal_charge'];
				$chargesArray['inbound_charge']=$chargesArray['inbound_charge']+$rowData['inbound_charge'];
				$chargesArray['return_charge']=$chargesArray['return_charge']+$rowData['return_charge'];
				$chargesArray['shipping_charge']=$chargesArray['shipping_charge']+$rowData['shipping_charge'];
				$chargesArray['pallet_charge']=$chargesArray['pallet_charge']+$rowData['pallet_charge'];
					if($val['special_packing_seller']>0)
					{
						$chargesArray['special_packing']=$chargesArray['special_packing']+$rowData['special_packing_seller'];	
					}
					else
					{
						$chargesArray['special_packing']=$chargesArray['special_packing']+$rowData['special_packing_warehouse'];
					}
					array_push($slipNOCharges,$val);
			 }
 						$return_charge = $rowData['return_charge'];
 						$picking_charge = $rowData['picking_charge'];
 						$packing_charge = $rowData['packing_charge'];
 						// $dispatch_charge = $rowData['dispatch_charge'];
 						$inbound_charge = $rowData['inbound_charge'];
 						$outbound_charge = $rowData['outbound_charge'];
						 $pallet_charge= $rowData['pallet_charge'];
 						$box_charge = $rowData['box_charge'];
 						$shipping_charge = $rowData['shipping_charge'];
 						$sku_barcode_print = $rowData['sku_barcode_print'];
 						$total_without_vat = round(( $pallet_charge+$packing_charge+$box_charge+$picking_charge+$special_packing+$cancel_charge+$outbound_charge+$return_charge),2);
 						$totalvat    =round( (($total_without_vat * 15)/100),2) ;
 						$total_with_vat  =round( ($total_without_vat + $totalvat),2);
 						 $counter = $key + 1;
                         
                  $row = array(
                    $counter,
                    $rowData['slip_no'],
                    $rowData['pieces'],
                   // $rowData['weight'],
                   // $rowData['picking_charge'],
                    //$rowData['packing_charge'],
                    $special_packing_seller,
                       $special_packing_warehouse,
                      
                    $rowData['pallet_charge'],
                    $rowData['return_charge'],
                    $rowData['outbound_charge'],
                    $rowData['cancel_charge'],
                    $rowData['box_charge'],
                    $total_without_vat,
                   // $totalvat,
                    //$total_with_vat,
                     $rowData['status'],
                      
                );
                  fputcsv($csv_file, $row, ',', '"'); 
              }
             // $header_row2=array("","Total Charges - التكلفة الإجمالية","","","","","","","","","","","","","");
              
            
             // fputcsv($csv_file, $header_row2, ',', '"');
             // $row2=array("","","","","","","","","","","","","","","");
              //fputcsv($csv_file, $row2, ',', '"'); 
             
               fclose($csv_file);
        }
    }

    
	public function export($invoiceNo = null) {
        
        if (!empty($invoiceNo)) {
            ini_set('memory_limit', '1024M');
            ini_set('max_execution_time', 60000); //increase max_execution_time to 10 min if data set is very large
            //create a file
            $filename = "Invoice detail ".$invoiceNo." " . date("Y.m.d") . ".csv";
            $csv_file = fopen('php://output', 'w');
            // header('Content-Encoding: UTF-8');
            //  header('Content-Type: application/vnd.ms-excel');
            fputs($csv_file, "\xEF\xBB\xBF"); // UTF-8 BOM !!!!!
            header('Content-Encoding: UTF-8');
            header("Content-Type: text/csv");
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $results=$this->Finance_model->Geteditinvoicedata(array('invoice_no' => $invoiceNo));
            
             $header_row = array("Sr No.","AWB no.","Pieces","Weight (Kg)","Handling Fees","Special Packing","Return Charges","Cancel Charge","Total Without Vat","Total Vat","Total With Vat");
              fputcsv($csv_file, $header_row, ',', '"');
              $chargesArray=array();
			  $slipNOCharges=array();
			///echo '<pre>'; print_r($ItemArray); die;
		$viewInfo=array();
              foreach ($results['result'] as $key=>$rowData) {
                $invoice_no = $rowData['invoice_no'];
 						$slip_no = $rowData['slip_no'];
 						$cancel_charge = $rowData['cancel_charge'];
 						$special_packing_seller = $rowData['special_packing_seller'];
 						$special_packing_warehouse = $rowData['special_packing_warehouse'];
	 						if($special_packing_seller > 0)
	 						{
	 							$special_packing = $special_packing_seller;
	 						}
	 						else { 
	 							$special_packing = $special_packing_warehouse;
	 						}
 						$return_charge = $rowData['return_charge'];
 						$handline_fees = $rowData['handline_fees'];
 						$shipping_charge = $rowData['shipping_charge'];
 						$total_without_vat = round(($handline_fees+$special_packing+$cancel_charge+$return_charge),2);
 						$totalvat    = round((($total_without_vat * 15)/100),2) ;
 						$total_with_vat  = round(($total_without_vat + $totalvat),2);
 						 $counter = $key + 1;
                       
                  $row = array(
                    $counter,
                    $rowData['slip_no'],
                    $rowData['pieces'],
                    $rowData['weight'],
                    $rowData['handline_fees'],
                   	$special_packing,
                    $return_charge,
                    $rowData['cancel_charge'],
                    $total_without_vat,
                    $total_without_vat,
                    $totalvat,
                    $total_with_vat
                );
                  fputcsv($csv_file, $row, ',', '"'); 
              }
             // $header_row2=array("","Total Charges - التكلفة الإجمالية","","","","","","","","","","","","","");
              
            
             // fputcsv($csv_file, $header_row2, ',', '"');
             // $row2=array("","","","","","","","","","","","","","","");
              //fputcsv($csv_file, $row2, ',', '"'); 
             
               fclose($csv_file);
        }
    }
    public function export_new($invoiceNo = null) {
        
        if (!empty($invoiceNo)) {
            ini_set('memory_limit', '1024M');
            ini_set('max_execution_time', 60000); //increase max_execution_time to 10 min if data set is very large
            //create a file
            $filename = "Invoice detail ".$invoiceNo." " . date("Y.m.d") . ".csv";
            $csv_file = fopen('php://output', 'w');
            // header('Content-Encoding: UTF-8');
            //  header('Content-Type: application/vnd.ms-excel');
            fputs($csv_file, "\xEF\xBB\xBF"); // UTF-8 BOM !!!!!
            header('Content-Encoding: UTF-8');
            header("Content-Type: text/csv");
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $results=$this->Finance_model->Geteditinvoicedata(array('invoice_no' => $invoiceNo));
            
             $header_row = array("Sr No.","AWB no.","Pieces","Handling Fees","Special Packing Seller","Special Packing Warehouse","Return Charges","Cancel Charge","Total Without Vat");
              fputcsv($csv_file, $header_row, ',', '"');
              $chargesArray=array();
			  $slipNOCharges=array();
			///echo '<pre>'; print_r($ItemArray); die;
		$viewInfo=array();
              foreach ($results['result'] as $key=>$rowData) {
                $invoice_no = $rowData['invoice_no'];
 						$slip_no = $rowData['slip_no'];
 						$cancel_charge = $rowData['cancel_charge'];
 						$special_packing_seller = $rowData['special_packing_seller'];
 						$special_packing_warehouse = $rowData['special_packing_warehouse'];
	 						if($special_packing_seller > 0)
	 						{
	 							$special_packing = $special_packing_seller;
	 						}
	 						else { 
	 							$special_packing = $special_packing_warehouse;
	 						}
 						$return_charge = $rowData['return_charge'];
 						$handline_fees = $rowData['handline_fees'];
 						$shipping_charge = $rowData['shipping_charge'];
 						$total_without_vat = round(($handline_fees+$special_packing+$cancel_charge+$return_charge),2);
 						$totalvat    = round((($total_without_vat * 15)/100),2) ;
 						$total_with_vat  = round(($total_without_vat + $totalvat),2);
 						 $counter = $key + 1;
                       
                  $row = array(
                    $counter,
                    $rowData['slip_no'],
                    $rowData['pieces'],
                   // $rowData['weight'],
                    $rowData['handline_fees'],
                   	$special_packing_seller,
                      $special_packing_warehouse,
                    $return_charge,
                    $rowData['cancel_charge'],
                    $total_without_vat,
                   // $total_without_vat,
                   // $totalvat,
                   // $total_with_vat
                );
                  fputcsv($csv_file, $row, ',', '"'); 
              }
             // $header_row2=array("","Total Charges - التكلفة الإجمالية","","","","","","","","","","","","","");
              
            
             // fputcsv($csv_file, $header_row2, ',', '"');
             // $row2=array("","","","","","","","","","","","","","","");
              //fputcsv($csv_file, $row2, ',', '"'); 
             
               fclose($csv_file);
        }
    }

}
