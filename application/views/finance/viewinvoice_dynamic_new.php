<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>assets/jszip.js"></script>
<script type="text/javascript" src="<?=base_url();?>assets/jszip-utils.js"></script>
<script type="text/javascript" src="<?=base_url();?>assets/FileSaver.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"  />
<div class="centercontent tables">
	<div id="contentwrapper" class="contentwrapper">
		<div class="contenttitle2">
			<h3>
				
		    Invoice detail( <?=$invoiceDatainfo[0]['invoice_no'];?>)
                    <?php  $currency = site_configTable("default_currency"); // get default currency    ?>
  
      </h3>
			<br /> </div>
		<a onclick="javascript:printDiv('printme')" style="cursor:pointer;">
			<button style="float:right;" class="btn btn-danger"><i class="fa fa-print fa-2x"></i></button>
		</a>
		
		
		<a class="btn-primary" type="button" id="btnExport" value="Export to Excel" style="float:;"><i class="fa fa-file-excel fa-2x"></i></a>
		<br />
		<div id="printme" style="100%;">
			<!-- This code is for print button -->
			<script language="javascript">
			function printDiv(divName) {
				var printContents = document.getElementById(divName).innerHTML;
				var originalContents = document.body.innerHTML;
				document.body.innerHTML = printContents;
				window.print();
				document.body.innerHTML = originalContents;
			}
			</script>
			<!-- Export To Excel -->
			<!-- Export To Excel -->
			<style type="text\css" media="print"> @media #print { display: none; } </style>
			<style>
			table,
			th,
			td {
				border: 1px solid black;
				padding: 2px;
			}
			
			th {
				background-color: #CCC;
				color: #000;
				width: 10%;
			}
			</style>
			<br />
			<div id='print'>
			
				<table   cellpadding="0" cellspacing="0" border="0" style="margin:0 auto;width: 100%;">
				<thead>
				<tr>
			
					<th>Sr No.  / الرقم التسلسلي </th>					
					<th>AWB no. /  رقم البوليصة </th>
					<th>Pieces </th>
<!--					<th>Weight (Kg) / الوزن (ك.غ)</th>-->
<!--					<th>Picking Charge / سعر الالتقاط </th>-->
<!--					<th>Packing Charge / سعر التغليف </th>-->
					<th>Special Packing Seller/ التغليف المخصص من البائع </th>
                                        <th>Special Packing Warehouse / التغليف المخصص من المستودع </th>
					<th>Pallet Charge / سعر الطبليه </th>
					<th>Return Charges </th>
				
					
					<th>Outbound Charge / سعر الشحنات الصادره </th> 
				
					<th>Cancel Charge / سعر الالغاء  </th>
					<th>Box Charge / سعر الكرتون  </th>
		
					<th>Total Without Vat /  المجموع بدون ضرائب  </th>
<!--					<th>Total Vat /   مجموع الضرائب </th>
					<th>Total With Vat / المجموع مع قيمة الضريبة المضافة </th>-->
				</tr>
				</thead>
				<tbody>
				<?php
					$discount=$invoiceDatainfo[0]['discount'];
					
					foreach ($invoiceData as $key => $rowData) {
					  //	$pickup_charge += $rowData['pickup_charge'];
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
 						$picking_charge = $rowData['picking_charge'];
 						$packing_charge = $rowData['packing_charge'];
 						// $dispatch_charge = $rowData['dispatch_charge'];
 						$inbound_charge = $rowData['inbound_charge'];
 						$outbound_charge = $rowData['outbound_charge'];
						 $pallet_charge= $rowData['pallet_charge'];
 						$box_charge = $rowData['box_charge'];
 						$shipping_charge = $rowData['shipping_charge'];
 						$sku_barcode_print = $rowData['sku_barcode_print'];
                                                //$packing_charge+$picking_charge
 						$total_without_vat = round(( $pallet_charge+$box_charge+$special_packing+$cancel_charge+$outbound_charge+$return_charge),2);
 						$totalvat    =round( (($total_without_vat * 15)/100),2) ;
 						$total_with_vat  =round( ($total_without_vat + $totalvat),2);
 						 $counter = $key + 1;
 						echo ' <tr>
								<td align="center">' . $counter. '</td>
								<td align="center">' . $rowData['slip_no'] . '</td>	
								<td align="center">' . $rowData['pieces'] . '</td>	
								<!--<td align="center">' . $rowData['weight'] . '</td>							
						        <td align="center">' . $rowData['picking_charge'] . '</td>
						        <td align="center">' . $rowData['packing_charge'] . '</td>-->
						        <td align="center">' . $special_packing_seller . '</td>
                                                              <td align="center">' . $special_packing_warehouse . '</td>
						       
								<td align="center">' . $rowData['pallet_charge'] . '</td>
								<td align="center">' . $rowData['return_charge'] . '</td>
						       
						        <td align="center">' . $rowData['outbound_charge'] . '</td>
						      						
						        <td align="center">' . $rowData['cancel_charge'] . '</td>							
						        <td align="center">' . $rowData['box_charge'] . '</td>							
											
						        <td align="center">' . $total_without_vat . '</td>							
						      <!--<td align="center">' . $totalvat . '</td>							
						        <td align="center">' . $total_with_vat . '</td>-->								    
						      </tr>';
					}

					?>


					<tr>	
						
					<?php  
                                        //$totalValue['picking_charge']+$totalValue['packing_charge']+
					$tot = round(($totalValue['pallet_charge']+$totalValue['return_charge']+$totalValue['special_packing']+$totalValue['outbound_charge']+$totalValue['box_charge']),2);  
					$totvat  = round((($tot * 15)/100),2) ;
 					$tot_with_vat  = round(($tot + $totvat),2);
 					$bank_fees = GetalldashboardClientField($invoiceData[0]['cust_id'], 'bank_fees');
					?>	
					<th colspan="3"> Total Charges - التكلفة الإجمالية</th>					
<!--					<th><?=$totalValue['picking_charge'];?></th>
					<th><?=$totalValue['packing_charge'];?></th>-->
					<th><?=$totalValue['special_packing_seller'];?></th>	
                                        <th><?=$totalValue['special_packing_warehouse'];?></th>	
					<th><?=$totalValue['pallet_charge'];?></th>	
					<th><?=$totalValue['return_charge'];?></th>					
				
				
					<th><?=$totalValue['outbound_charge'];?></th>
				
					<th><?=$totalValue['cancel_charge'];?></th>					
					<th><?=$totalValue['box_charge'];?></th>
					<th><?=$tot;?></th>					
<!--					<th><?=$totvat;?></th>					
					<th><?=$tot_with_vat;?></th>					-->
				</tr>
				
				<tr>
					<td colspan="15" align="justify">&nbsp;</td>
				</tr>
				
					<br>
					<?php 
                                        //$totalValue['packing_charge']+$totalValue['picking_charge']+
						$TOTAL =round(($totalValue['pallet_charge']+$totalValue['return_charge']+$totalValue['storage_charge']+$totalValue['onhold_charges']+$totalValue['inventory_charge']+$totalValue['portal_charge']+$totalValue['sku_barcode_print']+$totalValue['special_packing']+$totalValue['inbound_charge']+$totalValue['outbound_charge']+$totalValue['box_charge']),2);
						$TOTALvat    =round( (($TOTAL * 15)/100),2) ;
						$TOTAL_with_vat  =round( ($TOTAL + $TOTALvat),2);
					?>

					 <?php 
					/*$TOTAL = round(($tot + $totalValue['pallet_charge']+  $totalValue['pickup_charge']+$totalValue['storage_charge']+$totalValue['onhold_charges']),2);
					$TOTALvat    = round((($TOTAL * 15)/100),2) ;
 					$TOTAL_with_vat  = round(($TOTAL + $TOTALvat),2);*/
					?> 
				
			
				
				</tbody>
				
				


				</table>
				
					
				</div>
				
				<!-- Export To Excel -->
				<!--<script type="text/javascript" src="<?= base_url(); ?>assets/zip_request.js"></script>-->
				<script>
				var tableToExcel = (function () {
                                var uri = 'data:application/vnd.ms-excel;base64,', template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
                                                    , base64 = function (s)  {
                                                    return window.btoa(unescape(encodeURIComponent(s)))
                                                    }
                                            , format = function (s, c)  {
                                            return s.replace(/{(\w+)}/g, function (m, p)  {
                                            return c[p];
                                            })
                                            }
                                            return function (table, name)  {
                                            if (!table.nodeType)
                                                    table = document.getElementById(table)
                                                    var     ctx     =     {worksheet:  name ||     'Worksheet',     table:  table.innerHTML}
                                            var     blob     =     new     Blob([format(template,     ctx)]);
                                            var     blobURL     =     window.URL.createObjectURL(blob);
                                            return blobURL;
                                            }
                                            })()

                                            $("#btnExport").click(function  ()  {
												
                                    var       todaysDate       =       'Invoice detail <?=$invoiceDatainfo[0]['invoice_no'];?> ' + new Date();
                                    var blobURL = tableToExcel('printme', 'Invoice');
                                    $(this).attr('download', todaysDate + '.xls')
                                            $(this).attr('href', blobURL);
                                    });
				
				</script>
				<form action='' id='new_form' method='POST'>
					<input type='hidden' id='new_id' name='exceldata'> </form>