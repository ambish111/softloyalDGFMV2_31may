<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Zones_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function add_company($data) {


        $this->db->trans_start();
        $this->db->insert('zone_list_fm', $data);
        //echo $this->db->last_query();die;
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    public function add_company_customer($data) {


        $this->db->trans_start();
        $this->db->insert('zone_customer_fm', $data);
        //echo $this->db->last_query();
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    // public function all($limit , $start){
    // 	$this->db->limit($limit, $start);
    // 	$query = $this->db->get('seller_m');
    // 	if($query->num_rows()>0){
    // 			// return $query->result();
    // 		foreach ($query->result() as $row) {
    // 			$data[] = $row;
    // 		}
    // 		return $data;
    // 	}
    // }

    public function fetch_all_cities() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,city');
        $this->db->where('city!=', '');
        $this->db->where('deleted', 'N');
        $this->db->order_by('city');
        $query = $this->db->get('country');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function fetch_all_cities_new() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,city');
        $this->db->where('city!=', '');
        $this->db->where('deleted', 'N');
        $this->db->order_by('city');
        $query = $this->db->get('country');
        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function all() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('zone_list_fm');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function all_customer() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('zone_customer_fm');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function previousCity_customer($id = null) {
        if ($id != null) {
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

            $this->db->where('id', $id);
            $this->db->order_by('id', 'desc');
            $query = $this->db->get('zone_customer_fm');

            //echo $this->db->last_query(); die;
            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
        }
    }
    
     public function Checkcustomer_data($cust_id = null) {
        if ($cust_id != null) {
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('cust_id', $cust_id);
            $query = $this->db->get('zone_customer_fm');
            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
        }
    }

    public function previousCity($id = null) {
        if ($id != null) {
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

            $this->db->where('id', $id);
            $this->db->order_by('id', 'desc');
            $query = $this->db->get('zone_list_fm');

            //echo $this->db->last_query(); die;
            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
        }
    }

    public function count() {

        return $this->db->count_all("zone_list_fm");
    }

    public function edit_view($id) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $query = $this->db->get('zone_list_fm');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function edit_view_customerdata($id) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $query = $this->db->get('zone_list_fm');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function edit_custimer($id, $data) {

        $this->db->where('id', $id);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update('zone_list_fm', $data);
    }

    public function find($id) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        // $this->db->get_where('seller_m',array('id'=>$id));
        $query = $this->db->get('zone_list_fm');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function custList() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('access_fm', 'Y');
        $this->db->select('id,company as name');
        $this->db->order_by('id', 'desc');

        $query = $this->db->get('customer');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function Zone() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', 0);
        $query = $this->db->get('zone_list_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function ZoneCustomer() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', 0);
        $query = $this->db->get('zone_customer_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function customer($seller_id, $customer_id) {
        $data = array(
            'id' => $seller_id
        );
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->where('id', $customer_id);
        return $this->db->update('zone_list_fm', $data);
    }

    public function update_seller_id($seller_id, $customer_id) {
        $data = array(
            'zone_list_fm' => $customer_id
        );
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->where('id', $seller_id);
        return $this->db->update('zone_list_fm', $data);
    }

    public function find_customer($id) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $query = $this->db->get('zone_list_fm');

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function find_customer_sellerm($id) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $query = $this->db->get('zone_list_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function find_customer_sellerm_cust($id) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $query = $this->db->get('zone_customer_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function find1() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $query = $this->db->get('zone_list_fm');

        if ($query->num_rows() > 0) {

            return $query->result();
        }
    }

    public function UpdateZoneCompanyLIst(array $data, $id = null) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update('zone_list_fm', $data, array('id' => $id));
    }

    public function UpdateZoneCompanyLIstCustomer(array $data, $id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update('zone_customer_fm', $data, array('id' => $id));
    }

    public function find2() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id!=', 0);
        $query = $this->db->get('zone_list_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function getCityColumnByCname($companyName = NULL) {
        //strtolower
        $cityColumnArray = array(
            'smsa' => 'samsa_city',
            'micgo' => 'MICGO_city',
            'aramex' => 'aramex_city',
            'aramex international' => 'aramex_city',
            //''=>'dots_city',
            'imile' => 'imile_city',
            'naqel' => 'naqel_city_code',
            'esnad' => 'esnad_city',
            'samana' => 'samana_city',
            //''=>'agility_city',
            'safearrival' => 'safe_arrival',
            'aymakan' => 'aymakan',
            'zajil' => 'zajil',
            'clex' => 'clex',
            //''=>'rabel_city',
            //''=>'speedzi_city',
            'barqfleet' => 'barq_city',
            'labaih' => 'labaih',
            'makhdoom' => 'makhdoom',
            //'aramex international'=>'aramex_international',
            'saee' => 'saee_city',
            'ajeek' => 'ajeek_city',
            'emdad' => 'emdad_city',
            'shipsy' => 'shipsy_city',
            'shipadelivery' => 'shipsa_city',
            'saudi post' => 'saudipost_id',
            //''=>'ara1bic_name',
            'tamex' => 'tamex_city',
            'sls' => 'sls',
            //''=>'moovo',
            'alamalkon' => 'alamalkon',
            'burqexpres' => 'burq_city',
            'thabit' => 'thabit_city',
            'fetchr' => 'fetchr_city',
            'glt' => 'GLT',
            'wadha' => 'Wadha',
            'ejack' => 'ejack_city',
            'beez' => 'beez_city',
            'ajoul' => 'ajoul_city_code',
            'flow' => 'flow_city',
            'ups' => 'ups_city',
            'mahmool' => 'mahmool_city',
            'mylerz' => 'mylerz_city',
            'j&t' => 'jt_city',
            'bosta v2' => 'bosta_city',
            'fedex' => 'fedex_city',
            'egyptexpress' => 'egyptexpress_city',
            'j&t eg'=>'jt_eg_city',
            'proconnect'=>'proconnect_city',
            'kwickbox'=>'kwickbox_city'
        );

        $columnName = '';
        if (!empty($companyName)) {
            $cname = strtolower($companyName);

            if (array_key_exists($cname, $cityColumnArray)) {
                $columnName = $cityColumnArray[$cname];
            }
        }

        return $columnName;
    }

    public function get_cities_by_cc_city($cc_city = NULL) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,city');
        $this->db->where('city!=', '');
        $this->db->where('deleted', 'N');
        $this->db->order_by('city');
       // $this->db->group_by('city');
        $query = $this->db->get('country');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

}
