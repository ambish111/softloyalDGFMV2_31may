<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/


$hook['post_system'][] = array(
    'class'    => 'WooCommerce',
    'function' => 'SendRequest',
    'filename' => 'WooCommerce.php',
    'filepath' => 'hooks',
    'params'   => array()
);
$hook['post_controller'][] = array(
    'class'    => 'Webhook',
    'function' => 'orderRequest',
    'filename' => 'Webhook.php',
    'filepath' => 'hooks',
    'params'   => array()
);
$hook['post_system'][] = array(
    'class'    => 'StockUpdate',
    'function' => 'orderRequest',
    'filename' => 'StockUpdate.php',
    'filepath' => 'hooks',
    'params'   => array()
);