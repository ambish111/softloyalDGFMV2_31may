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

    public  function checkhtml()
    {
      include_once APPPATH.'/third_party/simple_html_dom.php';
      $table = '<tr><th>Sr No.  / الرقم التسلسلي </th>					
      <th>AWB no. /  رقم البوليصة </th>
      <th>Pieces </th>
      <th>Weight (Kg) / الوزن (ك.غ)</th>
      <th>Picking Charge / سعر الالتقاط </th>
      <th>Packing Charge / سعر التغليف </th>
      <th>Special Packing / التغليف الخاص  </th>
      <th>Pallet Charge / سعر الطبليه </th>
      <th>Return Charges </th>
    <th>Outbound Charge / سعر الشحنات الصادره </th> 
    
      <th>Cancel Charge / سعر الالغاء  </th>
      <th>Box Charge / سعر الكرتون  </th>

      <th>Total Without Vat /  المجموع بدون ضرائب  </th>
      <th>Total Vat /   مجموع الضرائب </th>
      <th>Total With Vat / المجموع مع قيمة الضريبة المضافة </th>
    </tr>';
$html = str_get_html($table);

header('Content-type: application/ms-excel');
header('Content-Disposition: attachment; filename=sample.csv');

$fp = fopen("php://output", "w");

foreach($html->find('tr') as $element)
{
  $td = array();
  foreach( $element->find('td') as $row)  
  {
      $td [] = $row->plaintext;
  }
  fputcsv($fp, $td);
}

fclose($fp);
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
            $invoiceData=$results['result'];
            $img=base64_encode(file_get_contents('https://super.diggipacks.net/assets/clientlogo/1648296008.png'));
            $header_row_head = array("","","","","","","","Draft Invoice - الفاتورة المبدئية","","","","","","","");
            fputcsv($csv_file, $header_row_head, ',', '"');
            //$header_row_head_box1 = array("UID Account Number:- 163991736582 - رقم الحساب","",$img,"Name Of Company - اسم الشركة -");
            //fputcsv($csv_file, $header_row_head_box1, ',', '"');
            //$header_row_head_box2 = array("Customers Name:- مؤسسة تاجر الدولي للتجارة - اسم العميل","","","","Vat Id No.:- - الرقم الضريبي");
            //fputcsv($csv_file, $header_row_head_box2, ',', '"');
            
            
             $header_row = array("Sr No. / الرقم التسلسلي","AWB no. / رقم البوليصة","Pieces","Weight (Kg) / الوزن (ك.غ)","Picking Charge / سعر الالتقاط","Packing Charge / سعر التغليف","Special Packing / التغليف الخاص","Pallet Charge / سعر الطبليه","Return Charges","Outbound Charge / سعر الشحنات الصادره","Cancel Charge / سعر الالغاء","Box Charge / سعر الكرتون","Total Without Vat / المجموع بدون ضرائب","Total Vat / مجموع الضرائب","Total With Vat / المجموع مع قيمة الضريبة المضافة");
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
					if($rowData['special_packing_seller']>0)
					{
						$chargesArray['special_packing']=$chargesArray['special_packing']+$rowData['special_packing_seller'];	
					}
					else
					{
						$chargesArray['special_packing']=$chargesArray['special_packing']+$rowData['special_packing_warehouse'];
					}
					array_push($slipNOCharges,$rowData);
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
                    $total_with_vat
                );
                  fputcsv($csv_file, $row, ',', '"'); 
              }
              $tot = round(($chargesArray['pallet_charge']+$chargesArray['return_charge']+$chargesArray['picking_charge']+$chargesArray['packing_charge']+$chargesArray['special_packing']+$chargesArray['outbound_charge']+$chargesArray['box_charge']),2);  
              $totvat  = round((($tot * 15)/100),2) ;
               $tot_with_vat  = round(($tot + $totvat),2);
               $bank_fees = GetalldashboardClientField($invoiceData[0]['cust_id'], 'bank_fees');
         					
             $header_row2=array("",
             "Total Charges - التكلفة الإجمالية",
             "","",
             $chargesArray['picking_charge'],
             $chargesArray['packing_charge'],
             $chargesArray['special_packing'],
             $chargesArray['pallet_charge'],
             $chargesArray['return_charge'],
             $chargesArray['outbound_charge'],
             $chargesArray['cancel_charge'],
             $chargesArray['box_charge'],
             $tot,$totvat,$tot_with_vat);
              
            
              fputcsv($csv_file, $header_row2, ',', '"');

              $TOTAL =round(($chargesArray['pallet_charge']+$chargesArray['return_charge']+$chargesArray['storage_charge']+$chargesArray['onhold_charges']+$chargesArray['inventory_charge']+$chargesArray['portal_charge']+$chargesArray['sku_barcode_print']+$chargesArray['packing_charge']+$chargesArray['picking_charge']+$chargesArray['special_packing']+$chargesArray['inbound_charge']+$chargesArray['outbound_charge']+$chargesArray['box_charge']),2);
						$TOTALvat    =round( (($TOTAL * 15)/100),2) ;
						$TOTAL_with_vat  =round( ($TOTAL + $TOTALvat),2);
              $header_row2_blank=array("");
              fputcsv($csv_file, $header_row2_blank, ',', '"');
              $header_row2_summary_1=array("","Summary - ملخص","");
              fputcsv($csv_file, $header_row2_summary_1, ',', '"');
              $header_row2_summary_2=array("Total Packing Charges / مجموع سعر التغليف",($chargesArray['packing_charge']>0)?$chargesArray['packing_charge']:0);
              fputcsv($csv_file, $header_row2_summary_2, ',', '"');
              $header_row2_summary_3=array("Total Picking Charges / مجموع سعر التقاط المنتجات",($chargesArray['picking_charge']>0)?$chargesArray['picking_charge']:0);
              fputcsv($csv_file, $header_row2_summary_3, ',', '"');
              $header_row2_summary_4=array("Total Special Packing Charges / مجموع سعر التغليف الخاص",($chargesArray['special_packing']>0)?$chargesArray['special_packing']:0);
              fputcsv($csv_file, $header_row2_summary_4, ',', '"');
              $header_row2_summary_5=array("Total Inbound Charges / مجموع سعر الوارد",($chargesArray['inbound_charge']>0)?$chargesArray['inbound_charge']:0);
              fputcsv($csv_file, $header_row2_summary_5, ',', '"');
              $header_row2_summary_6=array("Total Outbound Charges / مجموع سعر الصادر",($chargesArray['outbound_charge']>0)?$chargesArray['outbound_charge']:0);
              fputcsv($csv_file, $header_row2_summary_6, ',', '"');
              $header_row2_summary_7=array("Total Inventory Charges /مجموع رسوم الجرد",($chargesArray['inventory_charge']>0)?$chargesArray['inventory_charge']:0);
              fputcsv($csv_file, $header_row2_summary_7, ',', '"');
              $header_row2_summary_8=array("Total Portal Charges / مجموع سعر لوحة تحكم العميل",($chargesArray['portal_charge']>0)?$chargesArray['portal_charge']:0);
              fputcsv($csv_file, $header_row2_summary_8, ',', '"');
              $header_row2_summary_9=array("Total Sku Barcode Print / مجموع سعر طباعه الباركود",($chargesArray['sku_barcode_print']>0)?$chargesArray['sku_barcode_print']:0);
              fputcsv($csv_file, $header_row2_summary_9, ',', '"');
              $header_row2_summary_10=array("Total Box Charges / مجموع سعر الكرتون",($chargesArray['box_charge']>0)?$chargesArray['box_charge']:0);
              fputcsv($csv_file, $header_row2_summary_10, ',', '"');
              $header_row2_summary_11=array("Total Onhold Charges / مجموع سعر الشحنات المعلقه",($chargesArray['onhold_charges']>0)?$chargesArray['onhold_charges']:0);
              fputcsv($csv_file, $header_row2_summary_11, ',', '"');
              $header_row2_summary_12=array("Total Storage Charges / مجموع سعر التخزين",($chargesArray['storage_charge']>0)?$chargesArray['storage_charge']:0);
              fputcsv($csv_file, $header_row2_summary_12, ',', '"');
              $header_row2_summary_13=array("Total Pallet Charges / مجموع سعر الطبليات",($chargesArray['pallet_charge']>0)?$chargesArray['pallet_charge']:0);
              fputcsv($csv_file, $header_row2_summary_13, ',', '"');
              $header_row2_summary_14=array("Total Return Charges",($chargesArray['return_charge']>0)?$chargesArray['return_charge']:0);
              fputcsv($csv_file, $header_row2_summary_14, ',', '"');
              $header_row2_summary_15=array("Total Fees before VAT - إجمالي الرسوم قبل ضريبة القيمة المضافة",$TOTAL);
              fputcsv($csv_file, $header_row2_summary_15, ',', '"');
              $header_row2_summary_16=array("Discount / الخصم",$discount);
              $TOTAL= $TOTAL-$discount;
              $TOTALvat= round((($TOTAL * 15)/100),2) ;
              $TOTAL_with_vat=$TOTAL+ $TOTALvat;
              fputcsv($csv_file, $header_row2_summary_16, ',', '"');
              $header_row2_summary_17=array("Total After Discount / إجمالي المبالغ بعد الخصم",$TOTAL);
              fputcsv($csv_file, $header_row2_summary_17, ',', '"');
              $header_row2_summary_18=array("Total Vat Fees / مجموع الضريبه المضافه",$TOTALvat);
              fputcsv($csv_file, $header_row2_summary_18, ',', '"');
              $header_row2_summary_19=array("Total Fees After VAT % / إجمالي الرسوم بعد ضريبة القيمة المضافة",$TOTAL_with_vat);
              fputcsv($csv_file, $header_row2_summary_19, ',', '"');
              $header_row2_summary_20=array("Grand Total / المجموع الكلي",$TOTAL_with_vat);
              fputcsv($csv_file, $header_row2_summary_20, ',', '"');
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

}
