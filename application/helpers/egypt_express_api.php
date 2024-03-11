<?php



$postArr = json_decode(file_get_contents('php://input'), true);


if(!empty($postArr)){

      if($postArr['action'] == 'do_forward'){

              $headers = array("Content-type: application/json");
            
            
              
            
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $postArr['api_url']);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
              curl_setopt($ch, CURLOPT_TIMEOUT, 10);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $postArr['data']);
              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
              $response = curl_exec($ch);
              //echo curl_error($ch);die; 
              curl_close($ch);

              echo $response;
      }

      if($postArr['action'] == 'get_label'){

        
        $headers = array(
          'Content-Type: application/json'
        );
      
      
      
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $postArr['api_url']);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postArr['data']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      
        $response = curl_exec($ch);
        
        curl_close($ch);
        echo $response;

      }



  
}

die();
