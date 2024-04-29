<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EmailReport_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function GetCustomers() {

        $this->db->select('id,super_id,uniqueid,name,company,email_report');
        $this->db->from('customer');
        $this->db->where('id', 214);
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $query = $this->db->get();
         //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
    
    
    
     public function GetShipmentNumbers_daily($cust_id = null, $super_id = null) {

        $this->db->select('
                 sum(case when delivered = 1 then 1 else 0 end) AS t_oc,
                  sum(case when delivered =2 then 1 else 0 end) AS t_pg,
                  sum(case when delivered =3 then 1 else 0 end) AS t_ap,
                 sum(case when delivered = 4 then 1 else 0 end) AS t_pk, 
                 sum(case when delivered = 5 then 1 else 0 end) AS t_dl, 
                  sum(case when delivered = 7 then 1 else 0 end) AS t_pod,
                 sum(case when delivered = 8 then 1 else 0 end) AS t_rtc,
                 sum(case when delivered = 11 then 1 else 0 end) AS t_og, 
                 sum(case when delivered = 18 then 1 else 0 end) AS t_rop, 
                 sum(case when delivered = 19 then 1 else 0 end) AS t_dop,
                 sum(case when delivered = 20 then 1 else 0 end) AS t_fd, 
                 sum(case when delivered = 21 then 1 else 0 end) AS t_ofd,
                 sum(case when delivered = 22 then 1 else 0 end) AS t_rog,
                 sum(case when delivered = 30 then 1 else 0 end) AS t_rpod,
                 sum(case when delivered = 16 then 1 else 0 end) AS t_it,
                sum(case when reverse_type = 1 then 1 else 0 end) AS r_type');
        $this->db->from('shipment_fm');
       // $this->db->where('YEAR(entrydate)', '2024');
        $this->db->where('DATE(entrydate)', date("Y-m-d"));
        $this->db->where('super_id', $super_id);
        $this->db->where('cust_id', $cust_id);
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $query = $this->db->get();
      // echo $this->db->last_query(); die;
            return $query->row_array();
        
    }
    public function GetShipmentNumbers_orders($cust_id = null, $super_id = null) {

            $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $date = new DateTime(date("Y-m-d"));
        $date->modify('-1 month');
        $monthCheck = $date->format('m');
          $columns_type = "`shipment_fm`.`typeship`,`shipment_fm`.`sap_order_number`,";
        $this->db->select("`shipment_fm`.`booking_id`,
`shipment_fm`.`slip_no`,
(select main_status from status_main_cat_fm where status_main_cat_fm.id=shipment_fm.delivered) AS MAINSTATUS,
`customer`.`name`,
 `customer`.`company`,
 `customer`.`uniqueid`,
 `shipment_fm`.`entrydate`,
(select city from country where country.id=shipment_fm.origin) AS origin,
(select country from country where country.id=shipment_fm.origin) AS originCountry,
  (select city from country where country.id=shipment_fm.destination) AS destination,
  (select country from country where country.id=shipment_fm.destination) AS destinationCountry,
  (select company from courier_company where courier_company.cc_id=shipment_fm.frwd_company_id AND  courier_company.deleted = 'N' AND courier_company.super_id= " . $super_id . " limit 1) AS ForwardedCompany,
 `shipment_fm`.`reciever_name`,
 `shipment_fm`.`reciever_address`,
 `shipment_fm`.`reciever_phone`,
 `shipment_fm`.`sender_address`,
 `shipment_fm`.`sender_phone`,
 `shipment_fm`.`order_type`,
 `shipment_fm`.`sender_email`,
 `shipment_fm`.`mode`,
 `shipment_fm`.`total_cod_amt`,
 `shipment_fm`.`weight`,
 `shipment_fm`.`pieces`,
 `shipment_fm`.`shippers_ac_no`,
 `shipment_fm`.`frwd_company_awb`,
 shipment_fm.sender_name,
shipment_fm.shippers_ref_no,
 IF (shipment_fm.cust_id ='154',  shipment_fm.sender_name,customer.company) AS SENDER_NAME,
 (select company from customer where customer.id=shipment_fm.cust_id) AS STORE_NAME,
 
 shipment_fm.status_describtion,
 (select sub_status from status_category_fm where status_category_fm.code=shipment_fm.code) AS threePLSTATUS,
 shipment_fm.pay_invoice_no,
 shipment_fm.pay_invoice_status,
 shipment_fm.rec_invoice_status,
 date(shipment_fm.entrydate) AS ENTRY_DATE,
 time(shipment_fm.entrydate) AS entry_TIME,
 shipment_fm.3pl_pickup_date,
 shipment_fm.frwd_date,
 shipment_fm.no_of_attempt,
 shipment_fm.close_date,
 shipment_fm.laststatus_first,
 shipment_fm.cod_received_3pl,
 shipment_fm.cod_received_date,
 shipment_fm.fd1_date,
 shipment_fm.laststatus_second,
 shipment_fm.fd2_date,
  IF (shipment_fm.reverse_type='1','Reverse order','Fullfillment order') AS ShipmentType ,
 shipment_fm.laststatus_last,
 shipment_fm.fd3_date,
 shipment_fm.3pl_close_date,
  shipment_fm.dispatch_date,
 IF (shipment_fm.product_invoice is not null, 'Yes','No') AS Invoice,
 IF (shipment_fm.delivered='19', (select Details from status_fm where status_fm.slip_no=shipment_fm.slip_no order by status_fm.id desc limit 1),'') AS LastStatus ,
 IFNULL(DATEDIFF(close_date, 3pl_pickup_date) , DATEDIFF(CURRENT_TIMESTAMP() , 3pl_pickup_date)  )  AS transaction_days,
 (select name from warehouse_m where warehouse_m.id=shipment_fm.wh_id) as warehouse,
 $columns_type
shipment_fm.frwd_company_awb,shipment_fm.audit_status");
        $this->db->from('shipment_fm');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
        $this->db->where('YEAR(shipment_fm.entrydate)', date("Y"));
        $this->db->where('MONTH(shipment_fm.entrydate)', $monthCheck);
        $this->db->where('shipment_fm.super_id', $super_id);
        $this->db->where('shipment_fm.cust_id', $cust_id);
       // $this->db->group_by('MONTH(entrydate)');
        $this->db->where('shipment_fm.status', 'Y');
        $this->db->where('shipment_fm.deleted', 'N');
        //$this->db->limit(10);
        $query = $this->db->get();
          //echo $this->db->last_query(); die;
        $delimiter = ",";
        $newline = "\r\n";
        
        
       
       return $data = chr(239) . chr(187) . chr(191) .$this->dbutil->csv_from_result($query, $delimiter, $newline);
        
    }
    public function GetShipmentNumbers_daily_orders($cust_id = null, $super_id = null) {

           $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
         $columns_type = "`shipment_fm`.`typeship`,`shipment_fm`.`sap_order_number`,";
        $this->db->select("`shipment_fm`.`booking_id`,
`shipment_fm`.`slip_no`,
(select main_status from status_main_cat_fm where status_main_cat_fm.id=shipment_fm.delivered) AS MAINSTATUS,
`customer`.`name`,
 `customer`.`company`,
 `customer`.`uniqueid`,
 `shipment_fm`.`entrydate`,
(select city from country where country.id=shipment_fm.origin) AS origin,
(select country from country where country.id=shipment_fm.origin) AS originCountry,
  (select city from country where country.id=shipment_fm.destination) AS destination,
  (select country from country where country.id=shipment_fm.destination) AS destinationCountry,
  (select company from courier_company where courier_company.cc_id=shipment_fm.frwd_company_id AND  courier_company.deleted = 'N' AND courier_company.super_id= " . $super_id . " limit 1) AS ForwardedCompany,
 `shipment_fm`.`reciever_name`,
 `shipment_fm`.`reciever_address`,
 `shipment_fm`.`reciever_phone`,
 `shipment_fm`.`sender_address`,
 `shipment_fm`.`sender_phone`,
 `shipment_fm`.`order_type`,
 `shipment_fm`.`sender_email`,
 `shipment_fm`.`mode`,
 `shipment_fm`.`total_cod_amt`,
 `shipment_fm`.`weight`,
 `shipment_fm`.`pieces`,
 `shipment_fm`.`shippers_ac_no`,
 `shipment_fm`.`frwd_company_awb`,
 shipment_fm.sender_name,
shipment_fm.shippers_ref_no,
 IF (shipment_fm.cust_id ='154',  shipment_fm.sender_name,customer.company) AS SENDER_NAME,
 (select company from customer where customer.id=shipment_fm.cust_id) AS STORE_NAME,
 
 shipment_fm.status_describtion,
 (select sub_status from status_category_fm where status_category_fm.code=shipment_fm.code) AS threePLSTATUS,
 shipment_fm.pay_invoice_no,
 shipment_fm.pay_invoice_status,
 shipment_fm.rec_invoice_status,
 date(shipment_fm.entrydate) AS ENTRY_DATE,
 time(shipment_fm.entrydate) AS entry_TIME,
 shipment_fm.3pl_pickup_date,
 shipment_fm.frwd_date,
 shipment_fm.no_of_attempt,
 shipment_fm.close_date,
 shipment_fm.laststatus_first,
 shipment_fm.cod_received_3pl,
 shipment_fm.cod_received_date,
 shipment_fm.fd1_date,
 shipment_fm.laststatus_second,
 shipment_fm.fd2_date,
  IF (shipment_fm.reverse_type='1','Reverse order','Fullfillment order') AS ShipmentType ,
 shipment_fm.laststatus_last,
 shipment_fm.fd3_date,
 shipment_fm.3pl_close_date,
  shipment_fm.dispatch_date,
 IF (shipment_fm.product_invoice is not null, 'Yes','No') AS Invoice,
 IF (shipment_fm.delivered='19', (select Details from status_fm where status_fm.slip_no=shipment_fm.slip_no order by status_fm.id desc limit 1),'') AS LastStatus ,
 IFNULL(DATEDIFF(close_date, 3pl_pickup_date) , DATEDIFF(CURRENT_TIMESTAMP() , 3pl_pickup_date)  )  AS transaction_days,
 (select name from warehouse_m where warehouse_m.id=shipment_fm.wh_id) as warehouse,
 $columns_type
shipment_fm.frwd_company_awb,shipment_fm.audit_status");
        $this->db->from('shipment_fm');
       // $this->db->where('YEAR(entrydate)', '2024');
        $this->db->where('DATE(shipment_fm.entrydate)', date("Y-m-d"));
        $this->db->where('shipment_fm.super_id', $super_id);
        $this->db->where('shipment_fm.cust_id', $cust_id);
        $this->db->where('shipment_fm.status', 'Y');
       $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
        $this->db->where('shipment_fm.deleted', 'N');
        $query = $this->db->get();
      // echo $this->db->last_query(); die;
         $delimiter = ",";
        $newline = "\r\n";
        
        // echo $this->db->last_query(); die;
       return $data = chr(239) . chr(187) . chr(191) .$this->dbutil->csv_from_result($query, $delimiter, $newline);
       
      
        
        
    }

    public function GetShipmentNumbers($cust_id = null, $super_id = null) {

        $date = new DateTime(date("Y-m-d"));
        $date->modify('-1 month');
        $monthCheck = $date->format('m');
        $this->db->select('
                 sum(case when delivered = 1 then 1 else 0 end) AS t_oc,
                  sum(case when delivered =2 then 1 else 0 end) AS t_pg,
                  sum(case when delivered =3 then 1 else 0 end) AS t_ap,
                 sum(case when delivered = 4 then 1 else 0 end) AS t_pk, 
                 sum(case when delivered = 5 then 1 else 0 end) AS t_dl, 
                  sum(case when delivered = 7 then 1 else 0 end) AS t_pod,
                 sum(case when delivered = 8 then 1 else 0 end) AS t_rtc,
                  sum(case when delivered = 9 then 1 else 0 end) AS t_c, 
                 sum(case when delivered = 11 then 1 else 0 end) AS t_og, 
                 sum(case when delivered = 18 then 1 else 0 end) AS t_rop, 
                 sum(case when delivered = 19 then 1 else 0 end) AS t_dop,
                 sum(case when delivered = 20 then 1 else 0 end) AS t_fd, 
                 sum(case when delivered = 21 then 1 else 0 end) AS t_ofd,
                 sum(case when delivered = 22 then 1 else 0 end) AS t_rog,
                 sum(case when delivered = 30 then 1 else 0 end) AS t_rpod,
                 sum(case when delivered = 16 then 1 else 0 end) AS t_it,
                sum(case when reverse_type = 1 then 1 else 0 end) AS r_type');
        $this->db->from('shipment_fm');
        $this->db->where('YEAR(entrydate)', date("Y"));
        $this->db->where('MONTH(entrydate)', $monthCheck);
        $this->db->where('super_id', $super_id);
        $this->db->where('cust_id', $cust_id);
       // $this->db->group_by('MONTH(entrydate)');
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $query = $this->db->get();
       //echo $this->db->last_query(); die;
       
            return $query->row_array();
        
    }
    
    

}
