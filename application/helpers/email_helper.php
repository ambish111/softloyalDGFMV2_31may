<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function Sendmail($to = null, $subject = null, $message = null, $sitetitle = null,$attachment=null,$attachment_csv=null) {
    $ci = & get_instance();
    $ci->load->database();
   // echo $to; die;  
    $from='no-reply@fastcoo.com';
    $config = array(
        'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => 587, // 465 or 587
        'smtp_user' => 'no-reply@fastcoo.com',
        'smtp_pass' => 'F#^@t879$$1547FS',
        'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
        'mailtype' => 'html', //plaintext 'text' mails or 'html'
        //'smtp_timeout' => '10', //in seconds
        'charset' => 'utf-8',
            // 'crlf' => "\r\n",
            //'newline' => "\r\n"
    );

    $ci->email->initialize($config);
    $ci->email->set_newline("\r\n");    
    $ci->email->from($from, $sitetitle);
    $ci->email->to($to);
  //  echo $attachment; die;
    if(!empty($attachment))
    {
    $ci->email->attach($attachment);
    }
      if(!empty($attachment_csv))
    {
    $ci->email->attach($attachment_csv);
    }
    $ci->email->subject($subject);
    $ci->email->message($message);

    if ($ci->email->send()) {
        return true;
    } else {
        show_error($ci->email->print_debugger());
    }
}
