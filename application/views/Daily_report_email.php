<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1">
         
    </head>
    
<body
  style="font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">

  
  <table id="tabllereport"
    style="margin: 0px auto 20px;background-color:#fff;padding5:0px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border: solid 1px black;">
    <thead>
  
<tr>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"></span>
        </p>
    </td>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"></span>
        </p>
    </td>
    <td style="width: 20%;" style="font-size: 50px;" colspan="1">
        <p style="font-size: 50px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px; text-align: right;"> <img style="display: block; margin: auto; max-width: 150px;"
            src="https://fm.diggipacks.com/assets/dg_b.jpg" alt="logo"></span>
        </p>
    </td>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"></span>
        </p>
    </td>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"></span>
        </p>
    </td>
</tr>

    </thead>
    <tbody>
    <tr>
    <td style="width: 40%;" colspan="2">
    <p style="font-size:20px;margin:0 0 6px 0;"><span style="font-weight:bold;min-width:150px">Daily Report التقرير اليومي</span><b style="font-weight:normal;margin:0"></b></p>
    </td>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"></span>
        </p>
    </td>
  
    <td style="width: 20%; text-align:right;" colspan="2">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;">
          <p style="font-size:20px;margin:0 0 6px 0;"><span style="font-weight:bold;min-width:146px">Date:
              </span><span style="font-weight:bold;min-width:146px; background-color: #EBEDEF;"><?= date("D M Y") ?></span> </p>
          <p style="font-size:20px;margin:0 0 0 0;"><span style="font-weight:bold;min-width:146px">Client: </b>
          <?=$cust_data['name'];?> </span> </p></span>
        </p>
    </td>
</tr>

      <tr>
        <td style="height:35px;"></td>
      </tr>
       <tr>
    <td style="width: 20%;text-align: center;">
        <p style="font-size: 20px; margin: 0;text-align: center;">
            <span style="font-weight: bold; min-width: 150px;">Order Generated</span>
        </p>
    </td>
    <td style="width: 20%;text-align: center;">
        <p style="font-size: 20px; margin: 0;text-align: center;">
            <span style="font-weight: bold; min-width: 150px;">Under Process</span>
        </p>
    </td>
    <td style="width: 20%;text-align: center;">
        <p style="font-size: 20px; margin: 0;text-align: center;">
            <span style="font-weight: bold; min-width: 150px; text-align: right;">Delivered</span>
        </p>
    </td>
    <td style="width: 20%;text-align: center;">
        <p style="font-size: 20px; margin: 0;text-align: center;">
            <span style="font-weight: bold; min-width: 150px;">Return</span>
        </p>
    </td>
    <td style="width: 20%; text-align: center;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;">Reverse</span>
        </p>
    </td>
</tr>
<tr>
    <td style="width: 20%;text-align: center;">
        <p style="font-size: 20px; margin: 0;text-align: center;">
            <span style="font-weight: bold; min-width: 150px;"><?php echo ($data['t_og']>0)?$data['t_og']:0;?></span>
        </p>
    </td>
    <td style="width: 20%;text-align: center;">
        <p style="font-size: 20px; margin: 0;text-align: center;">
            <span style="font-weight: bold; min-width: 150px;"><?php  
                            
                            $under_process=$data['t_oc']+$data['t_ap']+$data['t_pk']+$data['t_dl']+$data['t_dop']+$data['t_ofd'];
                            echo ($data['$under_process']>0)?$data['$under_process']:0;?></span>
        </p>
    </td>
    <td style="width: 20%;text-align: center;">
        <p style="font-size: 20px; margin: 0;text-align: center;">
            <span style="font-weight: bold; min-width: 150px; text-align: right;"><?php echo ($data['t_pod']>0)?$data['t_pod']:0;?></span>
        </p>
    </td>
    <td style="width: 20%;text-align: center;">
        <p style="font-size: 20px; margin: 0;text-align: center;">
            <span style="font-weight: bold; min-width: 150px;"><?php echo ($data['t_rtc']>0)?$data['t_rtc']:0;?></span>
        </p>
    </td>
    <td style="width: 20%; text-align: center;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"><?php echo ($data['r_type']>0)?$data['r_type']:0;?></span>
        </p>
    </td>
</tr>
<tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;">
Total Closed Orders</span>
        </p>
    </td>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"></span>
        </p>
    </td>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px; text-align: right;"></span>
        </p>
    </td>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"></span>
        </p>
    </td>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"></span>
        </p>
    </td>
</tr>  
      <tr>
        <td style="width:50%;padding:20px;vertical-align:top">
          <p style="margin:0 0 20px 0;padding:0;font-size:20px;"><span
              style="display:block;font-weight:bold;font-size:20px"> Delivered</span> </p>
         
     
        </td>
        <td style="width:50%;padding:20px;vertical-align:top">
          <p style="margin:0 0 20px 0;padding:0;font-size:20px;"><span
              style="display:block;font-weight:bold;font-size:20px;"><?php echo ($data['t_pod']>0)?$data['t_pod']:0;?></span></p>
      
        </td>
      </tr> 
       <tr>
        <td style="width:50%;padding:20px;vertical-align:top">
          <p style="margin:0 0 20px 0;padding:0;font-size:20px;"><span
              style="display:block;font-weight:bold;font-size:20px"> Rturn</span> </p>
         
     
        </td>
        <td style="width:50%;padding:20px;vertical-align:top">
          <p style="margin:0 0 20px 0;padding:0;font-size:20px;"><span
              style="display:block;font-weight:bold;font-size:20px;"><?php echo ($data['t_rtc']>0)?$data['t_rtc']:0;?></span></p>
      
        </td>
      </tr> 
      
    </tbody>
   
  </table>
</body>

</html>