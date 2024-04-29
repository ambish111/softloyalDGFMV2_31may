<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1">
          
    </head>
     
<body
  style="font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">

  
  <table id="tabllereport"
    style="margin: 0px auto 20px;background-color:#fff;padding5:0px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px red;">
    <thead>
    <!-- <tr>
    <th style="text-align: center;" colspan="2">
        <img style="display: block; margin: auto; max-width: 150px;"
            src="https://fm.diggipacks.com/assets/dg_b.jpg" alt="logo">
    </th>
</tr> -->
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
    <p style="font-size:20px;margin:0 0 6px 0;"><span style="font-weight:bold;min-width:150px">Monthly Report التقرير اليومي</span><b style="font-weight:normal;margin:0"></b></p>
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
              <?= date("D M Y") ?></span> </p>
          <p style="font-size:20px;margin:0 0 0 0;"><span style="font-weight:bold;min-width:146px">Client: </b>
           <?=$cust_data['name'];?> </span> </p></span>
        </p>
    </td>
</tr>

      <tr>
        <td style="height:35px;"></td>
      </tr>
     
      <tr>
    
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
 
</tr>
      <tr>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;">Total</span>
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
            <span style="font-weight: bold; min-width: 150px;"><?php 
            $total=$data['t_og']+$data['t_dl']+$data['t_rtc']+$data['t_c']+$data['t_pod']+$data['t_it'];
            
            echo ($total>0)?$total:0;
            ?></span>
        </p>
    </td>
</tr>
<tr>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;">Cancelled</span>
        </p>
    </td>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"> </span>
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
            <span style="font-weight: bold; min-width: 150px;"><?=($data['t_c']>0)?$data['t_c']:0;?> </span>
        </p>
    </td>
</tr>
<tr>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;">Delivered </span>
        </p>
    </td>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"> </span>
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
            <span style="font-weight: bold; min-width: 150px;"> <?=($data['t_pod']>0)?$data['t_pod']:0;?></span>
        </p>
    </td>
</tr>
<tr>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"> Return</span>
        </p>
    </td>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"> </span>
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
            <span style="font-weight: bold; min-width: 150px;"><?=($data['t_rtc']>0)?$data['t_rtc']:0;?> </span>
        </p>
    </td>
</tr>
<tr>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"> In Transit</span>
        </p>
    </td>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"> </span>
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
            <span style="font-weight: bold; min-width: 150px;"><?=($data['t_it']>0)?$data['t_it']:0;?> </span>
        </p>
    </td>
</tr>
<tr>
    <td colspan="2">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"> Dispatched To LM</span>
        </p>
    </td>
    <!-- <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"> </span>
        </p>
    </td> -->
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
            <span style="font-weight: bold; min-width: 150px;"><?=($data['t_dl']>0)?$data['t_dl']:0;?> </span>
        </p>
    </td>
</tr>
<tr>
<td colspan="2">
<p style="font-size:20px;margin:0;"><span style="font-weight:bold;min-width:150px;">Order Generated</span></p>
</td>

    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"> </span>
        </p>
    </td>
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px; text-align: right;"></span>
        </p>
    </td>
 
    <td style="width: 20%;">
        <p style="font-size: 20px; margin: 0;">
            <span style="font-weight: bold; min-width: 150px;"><?=($data['t_og']>0)?$data['t_og']:0;?> </span>
        </p>
    </td>
</tr>
    </tbody>
   
  </table>
</body>

</html>