<?php
 
 echo'<!doctype html>
 <html>
     <head>
         <meta charset="utf-8">
         <title>AWB print of  </title> 
 
         <style>
 
 
             img {
                 -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
                 filter: grayscale(100%);
             }
             .invoice-box {
                 max-width: 800px;
                 margin: auto;
                 padding: 10px;
                 border: 1px solid #eee;
                 box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                 font-size: 12px;
                 line-height: 24px;
                 font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                 color: #555;
                 height:850px
             }
 
             .invoice-box table {
                 width: 100%;
                 line-height: inherit;
                 text-align: left;
             }
 
             .invoice-box table td {
                 padding: 5px;
                 vertical-align: top;
             }
 
             .invoice-box table tr td:nth-child(2) {
                 text-align: left;
             }
 
             .invoice-box table tr.top table td {
                 padding-bottom: 10px;
             }
 
             .invoice-box table tr.top table td.title {
                 font-size: 45px;
                 line-height: 45px;
                 color: #333;
             }
             table {
                 font-family: arial, sans-serif;
                 border-collapse: collapse;
                 width: 100%;
             }
 
             td, th {
 
                 text-align: left;
                 padding: 0px;
             } 
         </style>
     </head> 
     <body>';
     $tollfree=site_configTable('tollfree');
         foreach ($status_update_data as $key => $val) {



            if($status_update_data[$key]['forwarded'] == 1 && !empty($status_update_data[$key]['frwd_company_awb'])){

                $fp_status = site_configTable('fastcoo_partner_status');
                $counrierArr = $this->Ccompany_model->GetdeliveryCompanyUpdateQry($status_update_data[$key]['frwd_company_id'],$status_update_data[$key]['cust_id'],$status_update_data[$key]['super_id'],$fp_status);

                $label_info_from = GetallCutomerBysellerId($status_update_data[$key]['cust_id'],'label_info_from');
    
                if($label_info_from == '1'){
            
                    $sellername = GetallCutomerBysellerId($status_update_data[$key]['cust_id'],'company');
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $store_address = GetallCutomerBysellerId($status_update_data[$key]['cust_id'],'address');
                    $senderphone = GetallCutomerBysellerId($status_update_data[$key]['cust_id'],'phone');
                    $senderemail = GetallCutomerBysellerId($status_update_data[$key]['cust_id'],'email');
            
                }else{
                    $sellername =  $status_update_data[$key]['sender_name'];
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $store_address = $status_update_data[$key]['sender_address'];
                    $senderphone = $status_update_data[$key]['sender_phone'];
                    $senderemail = $status_update_data[$key]['sender_email'];
                }
            
                if(!empty($status_update_data[$key]['label_sender_name'])){
                    $sellername =  $status_update_data[$key]['label_sender_name'];    
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                }

                
        }else{
                $sellername = $status_update_data[$key]['sender_name'];
                $store_address = $status_update_data[$key]['sender_address'];
                $senderphone = $status_update_data[$key]['sender_phone'];
                $senderemail = $status_update_data[$key]['sender_email'];
        }




 
 //echo $status_update_data[$key]['origin']; die;
            $destination = getdestinationfieldshow($status_update_data[$key]['destination'], 'city');
            $origin = getdestinationfieldshow($status_update_data[$key]['origin'], 'city');
            $dcode = getdestinationfieldshow($status_update_data[$key]['destination'], 'city_code');
            $country = getdestinationfieldshow($status_update_data[$key]['origin'], 'country');
            $countryd = getdestinationfieldshow($status_update_data[$key]['destination'], 'country');
            $oringincode = getdestinationfieldshow($status_update_data[$key]['origin'], 'city_code');
            $account_no = getallsellerdatabyID($status_update_data[$key]['cust_id'],'uniqueid');
            $email_sender = getallsellerdatabyID($status_update_data[$key]['cust_id'], 'email');
            $phone = getallsellerdatabyID($status_update_data[$key]['cust_id'], 'phone');
            $address = getallsellerdatabyID($status_update_data[$key]['cust_id'], 'address');
             $limit = $status_update_data[$key]['pieces'];
             for ($i = 1; $i <= $limit; $i++) {
               if ($status_update_data[$key]['weight'] > $status_update_data[$key]['volumetric_weight'])
                     $weight = $status_update_data[$key]['weight'];
                 else
                     $weight = $status_update_data[$key]['volumetric_weight'];
 
                // echo $this->config->item('base_url_super') . site_configTable('logo'); die;
 
                echo '<div class="invoice-box">
                                                         <table cellpadding="0" cellspacing="0"  border="1">
                                                                 
                                                                         
                                                                                         <tr>
                                                                                                 <td class="" style="width:34%; ">
                                                                                                         <img src="' . SUPERPATH . Getsite_configData_field('logo')  . '"  style="height:17% !important; width:100px !important">
                                                                                                 </td>
                                                                                                 
                                                                                                 
                                                                                                 <td style=" text-align:center" class="center" > 
                                                                                                 <img src="https://lm.fastcoo-tech.com/application/third_party/qrcodegen.php?data=' . $status_update_data[$key]['slip_no'] . '"  style="'.$style3.'"  style="height:17% !important; width:17% !important;" /> 
                                                                                                 </td>
                                                                                                 <td align="center" style="width:33%; font-size:20px;">';
 
                                                 if ($status_update_data[$key]['mode'] == 'COD') {
                                                     
                                                        echo '<strong> COD </strong> <br>' . $status_update_data[$key]['total_cod_amt'] . '';
                                                    
                                                 }
                                                                                                 echo '</td>
                                                                                         </tr>
                                             </table>
                                                                 <table border="1">
                                                                 
                                                                                         <tr  align="center">
                                                                         <td style="font-size:10px; width:50%">
                                                                                  <strong> From</strong>  :  <br />
                                                                                 <strong>' . $status_update_data[$key]['sender_name'] . '  </strong><br />
                                                                                 <strong>Address</strong>:' .$address . '
                                                                                  <br /><strong>Mobile</strong>: ' . $phone. ' <br /><strong>Email:</strong>: ' . $email_sender . '
                                                                                  <br /><strong>City</strong>: ' . $origin . '
                                                                                      <br /><strong>Country</strong>: ' . $country . '
                                                                         </td>
                                                                         </tr>
                                     <tr>
                                                                                 
                                                                         <td class="center "  style="font-size:10px;">
                                                                                  <strong> To</strong>  :  <br />
                                                                                 <strong>' . $status_update_data[$key]['reciever_name'] . ' </strong> <br />
                                                                                 <strong>Address</strong>:' . $status_update_data[$key]['reciever_address'] . '
                                                                                  <br /><strong>Mobile</strong>: ' . $status_update_data[$key]['reciever_phone'] . '
                                                                                      <br /><strong>Email</strong>: ' . $status_update_data[$key]['reciever_email'] . '
                                                                                          
                                                                                  <br /><strong>City</strong>: ' . $destination . '
                                                                                       <br /><strong>Country</strong>: ' . $countryd . '
                                                                         </td>
                                                                         
                                                                 </tr> 
                                                                                 
                                                                         
                                                                 </tr> 
                                                         </table>
                                                         <table cellpadding="0" cellspacing="0" border="1">	   
                                                         <tr> <td colspan="2"  style="font-size:12px; align:center" align="center" >
                            
                                                                                 ' . barcodeRuntime_new($status_update_data[$key]['slip_no']) . ' <br>
                                                                                 <strong>' . $status_update_data[$key]['slip_no'] . '  ' . $i . '/' . $status_update_data[$key]['pieces'] . '</strong>
                                         </td>
                                                                         
                                                                 </tr> ';
 
 
               
 
               
 
                 if (!empty($status_update_data[$key]['frwd_awb_no'])) {
                    echo '  
                                                         
                                                                 <tr align="center">
                                 <td colspan="2"  style="font-size:12px; align:center" align="center">
                                     
                                 ' . barcodeRuntime_new($status_update_data[$key]['frwd_awb_no']) . '
                               <br>
                                 <strong>' . $status_update_data[$key]['frwd_awb_no'] . '</strong>
                             </td></tr> ';
                 }
                 if (!empty($status_update_data[$key]['booking_id'])) {
                
                     echo '  
                              
                                  <tr align="center">
                                  <td colspan=""  style="font-size:12px; align:center" align="center">
                                    
                                                                                 ' . barcodeRuntime_new($status_update_data[$key]['booking_id']) . '
                                                                         <br>
                                                                                 <strong>' . $status_update_data[$key]['booking_id'] . '</strong>
                                                                         </td>
                                      
 
                                      <td style="font-size:10px; align:center">
                                     
                                          <strong> Account number </strong>: ' . $account_no . '
                                      
                                          <br><strong> Date </strong> : ' . date("d-m-Y H:i:s", strtotime($status_update_data[$key]['entrydate'])) . '
                                     
                                          <br><strong> Weight</strong> : ' . $weight . 'Kg' . '
                                    
                                          <br><strong> Pieces </strong>: ' . $status_update_data[$key]['pieces'] . '
 
                                      </td>
                                      
                                      
                                      
                                      </tr> ';
                  }
 
                echo '   <tr><td colspan="2" style="font-size:8px;" >
                                                                                 <strong> Description </strong>:' . $status_update_data[$key]['status_describtion'] . '
                                                                         </td></tr></table>
                                                 </div> ';
             }
         }
       ?>
         </body></html>