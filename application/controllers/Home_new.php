<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home_new extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ItemCategory_model');
        $this->load->model('Item_model');
        $this->load->model('Cartoon_model');
        $this->load->model('ItemInventory_model');
        $this->load->model('Seller_model');
        $this->load->model('Shipment_model');
        $this->load->helper('form');
        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function getordercheck() {

        for ($i = 0; $i <= 5; $i++) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.diggipacks.com/API/createOrder',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
    "sign": "3F0820DD12DD2A3B59CB91D3A140A715",
    "format": "json",
    "signMethod": "md5",
    "param": {
        "sender_name": "test Store",
        "sender_email": "test@diggipacks.com",
        "origin": "Riyadh",
        "sender_phone": "9876543210",
        "sender_address": "Riyadh",
        "receiver_name": "test",
        "receiver_phone": "9876543210",
        "receiver_email": null,
        "description": null,
        "destination": "Riyad2",
        "BookingMode": "COD",
        "receiver_address": " الملك عبدالعزيز المرواء",
        "reference_id": "TESTSEP13",
        "codValue": "1000",
        "productType": "parcel",
        "service": 3,
        "weight": 0.055,
        "skudetails": [
            {
                "sku": "TEST01",
                "description": "",
                "cod": 200,
                "piece": 1,
                "wieght": 0.055
            }
        ],
        "zid_store_id": 5329,
        "street_number": "الملك عبدالعزيز",
        "area_name": "المرواء",
        "order_from": "M"
    },
    "method": "createOrder",
    "customerId": "162167631425"
}',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            echo $response;
        }
    }

    public function GetRtc1() {

        die;
        // $stock_json = file_get_contents('https://fm.diggipacks.com/assets/Rtc.json');
        /// echo "<pre>";
        /// $stockFileArray = json_decode($stock_json, true);
        // $NewStockArray = $stockFileArray['Sheet1'];
        // print_r($stockFileArray);
        //   die;
//$slip_nos=array_column($NewStockArray, 'slip_no');
//print_r($slip_nos); die;
        //$this->db->where("super_id", '175');
        //  $this->db->where_in("slip_no", $slip_nos);
        //  $this->db->where("code", 'ROP');
        //  $this->db->update("shipment_fm", array("code" => 'RTC','delivered'=>'8'));
    }

    public function getcountStock() {
        $query = $this->db->query("SELECT sum(quantity) as qty,seller_id as cust_id,super_id,(select sku from items_m where items_m.id=item_inventory.item_sku) as sku FROM `item_inventory` WHERE super_id='54' and seller_id=214 group by item_sku");
        // echo "<pre>";
        //$seller_data = $query->result_array();
        //$this->db->insert_batch('stocks',$seller_data);
        //print_r($seller_data);
    }

    private function sendRequest($dataJson) {

        $url = "https://api.diggipacks.com/API/createOrder";
        $headers = array(
            "Content-type: application/json",
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

        $response = curl_exec($ch);
        echo $response . "<br>";
    }

    public function PushOrder() {
        $pushed_array = array();
        $query = $this->db->query("SELECT * FROM `api_log` WHERE super_id='54' and booking_id IN(47593566,47525517,47541848,47589746,47527841,47584625,47533111,47573109,47585387,47523090,47514218,47514129,47529022,47614791,47547932,47610404,47559416,47587455,47596099,47510110,47521099,47585200) group by booking_id order by id desc");
        echo "<pre>";
        $seller_data = $query->result_array();
        foreach ($seller_data as $row) {
            if (!in_array($row['booking_id'], $pushed_array)) {
                echo $row['booking_id'] . "<br>";
                array_push($pushed_array, $row['booking_id']);
                $param_data = json_decode($row['details']);
                $dataJson = json_encode($param_data);
                //  $resps = $this->sendRequest($dataJson);  
            }
            // print_r($param_data);
        }

        //print_r($seller_data);
        //
    }

    public function shopify() {
        // $query=$this->db->query("SELECT * FROM `customer` WHERE id='173'");
        // $seller_data = $query->row_array();
        // echo  shopifyFulfill('DGF16153968444','17339',$seller_data);
    }

    public function city() {
        echo phpinfo();
        die;
        $query = $this->db->query("SELECT `id` FROM `country` WHERE `super_id` = '54' AND `city` != '' and city not in ('Riyadh','Dammam','JEDDAH') AND `deleted` = 'N' GROUP BY `city` ORDER BY `city`");
        $stock_data = $query->result_array();
        $data = array_column($stock_data, 'id');
        echo "<pre>";
        echo json_encode($data);
        // print_r($data); die;
    }

    public function getUpdateWeight() {
        die("stop");
        $this->db->select('id,slip_no,code,super_id');
        $this->db->from('shipment_fm');
        $this->db->where('super_id!=', 6);
        $this->db->where_not_in('code', array('POD', 'RTC', 'C'));
        $this->db->where('weight', '0');
        $this->db->where('deleted', 'N');
        $this->db->order_by('id', 'desc');
        // $this->db->limit(20);

        $query = $this->db->get();
        // $results = $query->result_array();
        foreach ($results as $result) {
            $totalweight = 0;
            $data['weight'] = 0;
            $this->db->where('diamention_fm.super_id', $result['super_id']);
            $this->db->where('items_m.super_id', $result['super_id']);
            $this->db->select('diamention_fm.id,diamention_fm.sku,diamention_fm.cod,diamention_fm.piece,items_m.id as sku_id,items_m.weight');
            $this->db->from('diamention_fm');
            $this->db->join('items_m', 'items_m.sku = diamention_fm.sku', 'LEFT');
            $this->db->where('diamention_fm.slip_no', $result['slip_no']);
            $query2 = $this->db->get();
            // echo $this->db->last_query() . "<br>";
            // $skuData = $query2->result_array();
            //$skuData = $this->Shipment_model->GetDiamationDetailsBYslipNo($result['slip_no']);
            //print_r($skuData);
            foreach ($skuData as $skuDetails) {
                $weightcount = $skuDetails['weight'];
                $totalweight = $totalweight + ($weightcount * $skuDetails['piece']);
                $data['weight'] = $totalweight;
            }

            //  $sql = "update shipment_fm set weight='$totalweight' where id='" . $result['id'] . "' and slip_no='" . $result['slip_no'] . "'";
            // $this->db->query($sql);
            //echo $sql . "<br>";
        }
    }

    public function updateRevers_item() {
        die("stop");
        // $query= $this->db->query("SELECT reverse_awb,slip_no,booking_id,super_id,cust_id,entrydate,reverse_type  FROM `shipment_fm` WHERE `reverse_awb` IN ('DGF13495668456','DGF16248057662','DGF11312754669','DGF16557629866','DGF19744348164','DGF11325949520','DGF11727044705','DGF14250194396','DGF16514136116','DGF17292703936','DGF13706756989','DGF14563246078','DGF16191343082','DGF13679684130','DGF18854784362','DGF11386994548','DGF13103619256','DGF16459918035','DGF18194164395','DGF18416048812','DGF14531150300','DGF19960159033','DGF17028832613','DGF16422643435','DGF11595384125','DGF18069717139','DGF19188449412','DGF18117391916','DGF14505877110','DGF17286224775','DGF11217722956','DGF11330005452','DGF15820307238','DGF15271878877','DGF14634932578','DGF16604501418','DGF16287037707','DG3918554900','DG8958761921','DGF18533212597','DGF15394748928','DGF11107331502','DGF13123279303','DGF16832036503','DGF16395133836','DGF18805938591','DGF18206161070','DGF11398947196','DGF12812320988','DGF18854815811','DGF17357292132','DGF14738645843','DGF14562194858','DGF12503524257','DGF18730850925','DGF12630314327','DGF13690645618','DGF1REV2634668671','DGF1REV8943284381','DGF1REV2235317278','DGF1REV2291558734','DGF1REV7072020001','DGF1REV3001999299','DGF1REV3065479107','DGF1REV4797085700','DGF1REV2451305130','DGF1REV8194153532','DGF1REV2695783969','DGF1REV3351479739','DGF1REV2491785938','DGF1REV6970040163','DGF1REV5632819419','DGF1REV2797977901','DGF1REV9181626526','DGF1REV6051298801','DGF1REV7166126385','DGF1REV6633836633','DGF1REV7136905462','DGF1REV8414070108','DGF1REV9137741731','DGF1REV7035958522','DGF1REV6579980953','DGF1REV9961522806','DGF1REV7984186145','DGF1REV8392249110','DGF1REV3871911717','DGF1REV9069840457','DGF1REV3073367069','DGF1REV5585096379','DGF1REV3372733605','DGF1REV5917604629','DGF1REV6762617309','DGF1REV9046670886','DGF1REV8825627564','DGF1REV1678265783','DGF1REV7358834973','DGF1REV6868881945','DGF1REV1210694613','DGF1REV7347925695','DGF1REV1580583440','DGF1REV8776772145','DGF1REV9908928715','DGF1REV6746600392','DGF1REV2299619966','DGF1REV7546623016','DGF1REV3150168887','DGF1REV8245187910','DGF1REV9946603511','DGF1REV8901625736','DGF1REV3889004115','DGF1REV2949684512','DGF1REV1358062416','DGF1REV1785423821','DGFTREV6565441651','DGF1REV7056643298','DGUREV7870075345','DGUREV7643469971','DGUREV6024393749','DGUREV1849556581','DGUREV9084421607','DGUREV2938863390','DGUREV3885625099','DGUREV1645695193','DGUREV5945229187','DGUREV7267717319','DGUREV9168023193','DGUREV9797705581','DGUREV9262912121','DGUREV4406220607','DGUREV9498288779','DGUREV5541166045','DGUREV5783424335','DGUREV1424758589','DGUREV6855124247','DGUREV1271770283','DGUREV3095366319','DGUREV1322357041','DGUREV9333739652','DGUREV6141440546','DGUREV3605696498','DGUREV7033283279','DGUREV4823394580','DGUREV8801990257','DGUREV1547579243','DGUREV8552029780','DGUREV3669441057','DGUREV5214515972','DGUREV3414110056','DGUREV3583689653','DGUREV8985204888','DGUREV2546245856','DGUREV9284005837','DGUREV7562736348','DGUREV4443433749','DGUREV9036929070','DGUREV3232131377','DGUREV5038159467','DGUREV1117629578','DGUREV3961696923','DGUREV2897281362','DGUREV3231097500','DGUREV6378368090','DGUREV5209243674','DGUREV8865512064','DGUREV3648717508','DGUREV8410148769','DGUREV6091406500','DGUREV9207680108','DGUREV5506784911','DGUREV4141178007','DGUREV6394524191','DGUREV2195777390','DGUREV2787827146','DGUREV6174253049','DGUREV8129425049','DGUREV6032523844','DGUREV3608810514','DGUREV2040870287','DGUREV2687050972','DGUREV2269916171','DGUREV6624007019','DGUREV7245166223','DGUREV4215095306','DGUREV5983894182','DGUREV6814631528','DGUREV7340636297','DGUREV8968962350','DGUREV2530167414','DGUREV8694755732','DGUREV4581158768','DGUREV7463765786','DGUREV5412667253','DGJREV9743424386','DGJREV2195259083','DGJREV6407048339','DGJREV9545423365','DGJREV9972632126','DGJREV1485458608','DGJREV8616044952','DGJREV5633780576','DGJREV7670094405','DGJREV8457613168','DGJREV5238931987','DGJREV3115460034','DGF1REV1567865538','DGFTREV9804888853','DGFTREV5079089581','DGFTREV6789438401','DGFTREV3855600466','DGFTREV2477644830','DGFTREV3583113566','DGFTREV7628850519','DGFTREV5575669713','DGFTREV2897065047','DGFTREV1191520687','DGFTREV2619717229','DGFTREV9278615679','DGFTREV5604834013','DGFTREV6946927265','DGFTREV1587423455','DGFTREV7795549880','DGFTREV7984404086','DGFT9112523019','DGFT1265343586','DGFT1698707971','DGFT8567165968','DGFT8309372879','DGF1REV4442540776','DGF1REV8848593312','DGF1REV5795916612','DGF1REV9881710043','DGF1REV3182411949','DGF1REV5503062705','DGF1REV6617935887','DGF1REV2637117452','DGF1REV4663365473','DGF1REV5122442712','DGF1REV4000224225','DGF1REV1445161486','DGF1REV5346729080','DGF1REV6494418091','DGF1REV3424122956','DGF1REV1357429460','DGUREV1737296279','DGUREV8312613719','DGUREV9570696453','DGUREV8512623481','DGUREV2936882746','DGUREV4660912192','DGUREV4099685109','DGUREV1241994904','DGUREV5448858167','DGUREV3943272880','DGUREV9540575396','DGUREV9972048719','DGUREV1931490455','DGF17987397852','DGF17331528509','DGJREV1424086875','DGJREV1430811525','DGF1REV1734140309','DGUREV4052624863','DGFT4770605486','DGFT4916145190','DGFT7182863763','DGFT2404055630','DGFT2870435145','DGFT6300874377','DG9164309577','DG6622698480','DG5483169096','FWL7354302232','DGF14390053671','DGF16696535822','DGF13169954232','DGF11625315478','DGF19787652223','DGF19211472014','DGF13458626596','DGF18525391690','DGF15179767620','DGF11885395418','DG4742312557','DGF15089118037','DGF18126437067','DGF11608457472','DGF18307748890','DGF19799966040','DGF12224016608','DGF19258103874','DGF16593257460','DGF12665412612','DGF17253053691','DGF13894022386','DGF13460549162','DGF15176851313','DGF11247432214','DGF18430045123','DGF14471998916','DGF15610065201','DGF11626902482','DGF15623863729','DGF13134406309','DGF16574609066','DGF11963813064','DGF15297232357','DGF19326352866','DGF15324480935','DGF17887690047','DGF19788470364','DGF12118554639','DGF18942073535','DGF19307446168','DGF16263259803','DGF14071737060','DGF13866307451','DGF17988251251','DGF17476920239','DGF14428529541','DGF12812798488','DGF17213471875','DGF17588983367','DGF16295463813','DGF18231718758','DGF13214395242','DGF12765410091','DGF12115867183','DGF12820759222','DGF11813556285','DGF16892791186','DGF15332425081','DGF12077057165','DGF15394584722','DGF18129403020','DGF17594177830','DGF15200730115','DGF18346968556','DGF17660811952','DGF17513192099','DGF11537441227','DGF1REV7291764798','DGF1REV6373995779','DGF1REV4431642630','DGF1REV5399411265','DGF1REV1734381816','DGF1REV1746453027','DGF1REV2976060490','DGF1REV1898579138','DGF1REV3296253617','DGF1REV7025880933','DGUREV3407949935','DGUREV7414469097','DGUREV5084157402','DGUREV5112947300','DGUREV7811080281','DGUREV4501526045','DGUREV3284170892','DGUREV5838841217','DGUREV7859720229','DGUREV2506878919','DGUREV3460789247','DGUREV3440165082','DGUREV9570748440','DGUREV2813834838','DGUREV1253281020','DGUREV5594335952','DGUREV7739477131','DGUREV4597009161','DGUREV8523222284','DGUREV8172939769','DGUREV4463714914','DGUREV9649699165','DGUREV6661873889','DGUREV9305373479','DGUREV7447920612','DGUREV2021311882','DGUREV5687819018','DGUREV5848569910','DGUREV5143314077','DGUREV9650659875','DGUREV4974544255','DGUREV7067559993','DGUREV5255197201','DGUREV4825755468','DGUREV7465510466','DGUREV4660269810','DGUREV2387000383','DGUREV8310596636','DGUREV8265031945','DGUREV3122638060','DGUREV2734196016','DGUREV9225788785','DGUREV4759130779','DGUREV4326436256','DGUREV5526506825','DGUREV7188813216','DGUREV9671746481','DGUREV6130719000','DGUREV3951658743','DGUREV1116052805','DGUREV3078891738','DGUREV1444873102','DGUREV9149990247','DGUREV7648290567','DGUREV8248122849','DGUREV7643881581','DGUREV2837320245','DGUREV6509674319','DGF17496094454','DGF19016436267','DGF1REV6726032468','DG8254328108','DGJREV9950922988','DGJREV3935011409','DGJREV1064295720','DGJREV6625302410','DGF1REV6229097517','DGF13902708812')");
        $results = $query->result_array();

        // echo "<pre>";
        //print_r($results);
        //   foreach($results as $data)
        {
            $slip_no = $data['slip_no'];
            $super_id = $data['super_id'];
            $reverse_awb = $data['reverse_awb'];

            // $query2= $this->db->query("SELECT `sku`, `description`, `deducted_shelve`, `booking_id`, `slip_no`, `cod`, `piece`, `length`, `width`, `height`, `wieght`, `deleted`, `wh_id`, `super_id`, `cust_id`, `entry_date`, `free_sku`, `back_reason` from diamention_fm where slip_no='$slip_no' and super_id='$super_id'");
            // $results2=$query2->result_array();
            // foreach($results2 as $key=>$dval)
            {
                // $dval['booking_id']=$slip_no; 
                // $dval['slip_no']=$reverse_awb; 
                // $this->db->insert("diamention_fm",$dval);
                // echo $this->db->last_query()."<br>";
            }
        }
    }

    public function updateImile() {
        die('stop');
        //  $query= $this->db->query("SELECT slip_no,pickup_date,entry_date  FROM `status_fm` WHERE `slip_no` IN ('DGF12697770435','DGF13806366766','DGF13834286895','DGF11016307351','DGF17521902282','DGF18864417110','DGF15035369658','DGF13337000080','DGF11287552201','DGF12632712470','DGF11457738244','DGF18547214022','DGF18395830455','DGF14139674307','DGF15295372199','DGF13034140861','DGF17204502625','DGF12311627074','DGF12376125564','DGF18298296440','DGF15518453158','DGF17401592656','DGF15480530718','DGF11305662025','DGF16069332720','DGF16179766898','DGF19150618348','DGF17407893409','DGF17544898673','DGF12202510658','DGF16318609084','DGF17917848528','DGF16854451243','DGF12266780033','DGF16912363674','DGF12193924655','DGF17442710583','DGF14181977344','DGF12981527889','DGF12726856024','DGF16033654503','DGF12181223416','DGF15509076069','DGF15262661603','DGF11427550888','DGF11164378479','DGF14839881226','DGF15871390379','DGF17854658914','DGF15730799695') AND `code` LIKE 'PC' group by slip_no order by id desc");
        // $results=$query->result_array();
        // echo "<pre>"; print_r($results); die;
        foreach ($results as $data) {
            $sql = "update shipment_fm set 3pl_pickup_date='" . $data['entry_date'] . "' where slip_no='" . $data['slip_no'] . "'";
            echo $sql . "<br>";
            // $this->db->query($sql);
            // echo $this->db->last_query();
        }

        // echo "<pre>"; print_r($results);
    }

    public function updatefromtemp() {

        $this->db->select('*');
        $this->db->from('item_100');

        $this->db->where('sku!=', '');

        $query = $this->db->get();
        //  echo $this->db->last_query()."<br>";
        $NewStockArray = $query->result_array();

        foreach ($NewStockArray as $key => $Val) {
            $location = trim($Val['stock_location']);
            $Shelve_Location = $Val['shelve'];

            $Qty = trim($Val['qty']);
            $seller_id = 182;

            if ($seller_id == 182) {

                $SKU = $Val['sku'];
                $this->db->select('items_m.id,items_m.sku,items_m.sku_size,quantity');
                $this->db->from('item_inventory');
                $this->db->join('items_m', 'items_m.id = item_inventory.item_sku', 'left');
                $this->db->where('item_inventory.stock_location', trim($location));
                $this->db->where('items_m.sku', trim($SKU));
                $this->db->where('items_m.super_id', 54);
                $this->db->where('item_inventory.super_id', 54);
                $query = $this->db->get();
                //echo $this->db->last_query()."<br>";
                $result = $query->row_array();

                if (!empty($result)) {


                    //  $sql="update item_inventory set quantity='".$Qty."',shelve_no='".$Shelve_Location."' where  stock_location='".$location."' and item_sku='".$result['id']."' and super_id='54'";
                    // echo $sql."<br>";
                    // $this->db->query($sql);
                    // "sku-".$SKU."===size-".$result['sku_size']."=========qty in table-".$result['quantity']."======qty in file-".$Qty."===== stock location -".$location."<br>";
                } else {


                    $this->db->select('items_m.id,items_m.sku,items_m.sku_size');
                    $this->db->from('items_m');

                    $this->db->where('items_m.sku', trim($SKU));
                    $this->db->where('items_m.super_id', 54);

                    $query2 = $this->db->get();
                    //echo $this->db->last_query()."<br>";
                    $result_1 = $query2->row_array();

                    if (!empty($result_1['id'])) {

                        $in_sql = "insert into item_inventory(`item_sku`, `quantity`, `update_date`, `seller_id`, `shelve_no`, `stock_location`,`itype`, `wh_id`, `super_id`)values('" . $result_1['id'] . "','$Qty','" . date("Y-m-d H:i:s") . "','$seller_id','$Shelve_Location','$location','B2C','20','54')";
                        $this->db->query($in_sql);

                        // echo $SKU."=========".$in_sql . "<br>"; 
                    } else {
                        echo '<br> no sku:--:' . $SKU . "<br>";
                    }
                }

                // echo "sku-".$SKU."===size-".$result['sku_size']."=========qty in table-".$result['quantity']."======qty in file-".$Qty."===== stock location -".$location."<br>";
            }
        }
    }

    public function Getcheck_inventory() {

        die("stop");
        $in_sql = $this->db->query("select * from item_inventory_new where super_id='175' and seller_id>0 and item_sku>0");
        $in_data = $in_sql->result_array();
        foreach ($in_data as $data) {
            $location = $data['stock_location'];
            $seller_id = $data['seller_id'];
            $super_id = $data['super_id'];
            $item_sku = $data['item_sku'];
            $update_date = $data['update_date'];
            $shelve_no = $data['shelve_no'];
            $wh_id = $data['wh_id'];
            $quantity = $data['quantity'];

            $st_sql = $this->db->query("select id from stockLocation where stock_location='$location' and seller_id='$seller_id'");
            $st_data = $st_sql->row_array();
            if (!empty($st_data)) {
                $insert_in_sql = "insert into item_inventory(`item_sku`, `quantity`, `update_date`, `seller_id`, `shelve_no`, `stock_location`,`itype`, `wh_id`, `super_id`)values('" . $item_sku . "','$quantity','" . $update_date . "','$seller_id','$shelve_no','$location','B2C','$wh_id','$super_id')";
                // $this->db->query($insert_in_sql);
                echo $insert_in_sql . "===inventory==<br>";
            } else {

                $in_location_sql = "INSERT INTO `stockLocation`(`seller_id`, `stock_location`, `super_id`) VALUES ('" . $seller_id . "','" . $location . "','" . $super_id . "')";
                //$this->db->query($in_location_sql);
                echo $in_location_sql . "===st location==<br>";
            }
        }
    }

    public function getupdatestock() {



        //die('stop');

        $stock_json = file_get_contents('https://fm.diggipacks.com/assets/fc5_inventory.json');
        echo "<pre>";
        $stockFileArray = json_decode($stock_json, true);
        $NewStockArray = $stockFileArray['Sheet1'];
        print_r($NewStockArray);
        die;

        foreach ($NewStockArray as $key => $Val) {
            $location = trim($Val['Stock_StockLocation']);
            $Shelve_Location = $Val['Shelve'];
            $SKU = trim($Val['Sku']);
            $Qty = trim($Val['QUANTITY']);
            $seller_id = 258;
            $super_id = 54;
            $wh_id = 20;

            $st_sql = $this->db->query("select id from stockLocation where stock_location='$location'");
            $st_data = $st_sql->row_array();
            if (!empty($st_data)) {
                $this->db->select('items_m.id,items_m.sku,items_m.sku_size');
                $this->db->from('items_m');

                $this->db->where('items_m.sku', trim($SKU));
                $this->db->where('items_m.super_id', $super_id);

                $query2 = $this->db->get();
                //echo $this->db->last_query()."<br>";
                $result_1 = $query2->row_array();

                if (!empty($result_1)) {

                    // $in_sql="update item_inventory set shelve_no='$Shelve_Location' where seller_id='$seller_id' and item_sku='".$result_1['id']."' and stock_location='$location'";

                    $in_sql = "insert into item_inventory(`item_sku`, `quantity`, `update_date`, `seller_id`, `shelve_no`, `stock_location`,`itype`, `wh_id`, `super_id`)values('" . $result_1['id'] . "','$Qty','" . date("Y-m-d H:i:s") . "','$seller_id','$Shelve_Location','$location','B2C','$wh_id','$super_id')";

                    /// echo $in_sql."<br>";
                    // $this->db->query($in_sql);










                    echo $SKU . "=========" . $in_sql . "<br>";
                } else {
                    echo $SKU . "=========wrong" . "<br>";
                }
            }
        }



        // print_r($NewStockArray);
        die;
        //  echo count($result);
    }

    public function Gettemp_update() {
        die('stop');
        echo '<pre>';
        $data = array('DG1127900240', 'DG1166111769', 'DG1170464613', 'DG1397501955', 'DG1419755673', 'DG1536351584', 'DG1542969792', 'DG1613370859', 'DG1659496087', 'DG1761333252', 'DG1841767223', 'DG1907636597', 'DG1934746455', 'DG2080203220', 'DG2230897857', 'DG2250454375', 'DG2501409763', 'DG2764420784', 'DG2766389940', 'DG2767604805', 'DG2826370785', 'DG2862220071', 'DG2885866908', 'DG2905209700', 'DG2923730886', 'DG3041036593', 'DG3261358413', 'DG3272019343', 'DG3640920945', 'DG3704611298', 'DG3914384216', 'DG3939771119', 'DG4106314171', 'DG4298184044', 'DG4410749852', 'DG4540813851', 'DG4671446633', 'DG4756040551', 'DG4838764281', 'DG5233016711', 'DG5326438495', 'DG5352033291', 'DG5449951396', 'DG5471864323', 'DG5508940645', 'DG5570918603', 'DG5599228817', 'DG5678827510', 'DG6109156183', 'DG6192998835', 'DG6241293664', 'DG6897507612', 'DG7079828347', 'DG7103985063', 'DG7186020895', 'DG7358857310', 'DG7365787057', 'DG7693146539', 'DG7866136481', 'DG7915823854', 'DG8463635135', 'DG8567832417', 'DG8607226046', 'DG8700018534', 'DG8711280208', 'DG9362301097', 'DG9398270434', 'DG9547036424', 'DG9609917910', 'DG9766669126', 'DG9781433555', 'DG9823969836', 'DG9934734411', 'JDK3001497664', 'JDK5763185984', 'JDK5975744565', 'JDK8984943931');

        $newArray = array_unique($data);
//       
//                $this->db->select('*');
//                 $this->db->from('inventory_activity');
//                 $this->db->where_in('inventory_activity.awb_no', $newArray);
//                 $this->db->where('type','deducted');
//                 $this->db->where('super_id','5');
//                 $query = $this->db->get();
//                 $result = $query->result_array();
//                 print_r($result);
//               die;


        $this->db->select('shipment_fm.slip_no,shipment_fm.cust_id');
        $this->db->from('shipment_fm');
        $this->db->where_in('shipment_fm.slip_no', $newArray);
        $this->db->where('shipment_fm.super_id', '5');
        $this->db->where('shipment_fm.deleted', 'N');

        $this->db->group_by('shipment_fm.slip_no');

        $query = $this->db->get();
        $result = $query->result_array();

        foreach ($result as $key => $val) {
            $newslip = $val['slip_no'];
            $cust_id = $val['cust_id'];
            $this->db->select('diamention_fm.piece,diamention_fm.slip_no,diamention_fm.sku,items_m.id as item_sku,items_m.sku_size,item_inventory.quantity,item_inventory.seller_id');
            $this->db->from('diamention_fm');
            $this->db->where('diamention_fm.slip_no', $newslip);
            $this->db->where('diamention_fm.super_id', '5');
            $this->db->where('diamention_fm.deleted', 'N');
            $this->db->where('item_inventory.seller_id', $cust_id);
            $this->db->join('items_m', 'items_m.sku = diamention_fm.sku', 'left');
            $this->db->join('item_inventory', 'item_inventory.item_sku = items_m.id', 'left');
            $this->db->group_by('item_inventory.item_sku');

            $query2 = $this->db->get();
            //  echo $this->db->last_query();
            $result2 = $query2->result_array();

            foreach ($result2 as $row) {
                $sql = "update item_inventory set quantity='" . $row['piece'] . "' where seller_id='" . $row['seller_id'] . "' and super_id=5 and item_sku='" . $row['item_sku'] . "'";

                //echo $sql."<br>"; 
            }

            // print_r($result2);
        }
        //  print_r($result);

        die;

        print_r($newArray);
    }

    public function GetgenrateBaarcode($text = null) {
        echo $has_pass = password_hash('fast@124@2021', PASSWORD_DEFAULT);
        die;

        $barcodpath1 = checknewbarrcode("", 'BSE5756776074', 60, 'horizontal', 'code128', true, 1);
        $barcodpath2 = checknewbarrcode("", 'BSE5756776074', 70, 'horizontal', 'code128', true, 1);
        $barcodpath2last = checknewbarrcode("", 'BSE5756776074', 80, 'horizontal', 'code128', true, 1);

        $barcodpath3 = checknewbarrcode("", 'BSE5756776074', 60, 'horizontal', 'code128a', true, 1);
        $barcodpath4 = checknewbarrcode("", 'BSE5756776074', 70, 'horizontal', 'code128a', true, 1);
        $barcodpath4last = checknewbarrcode("", 'BSE5756776074', 80, 'horizontal', 'code128a', true, 1);

        $barcodpath5 = checknewbarrcode("", 'BSE5756776074', 60, 'horizontal', 'code39', true, 1);
        $barcodpath6 = checknewbarrcode("", 'BSE5756776074', 70, 'horizontal', 'code39', true, 1);
        $barcodpath6last = checknewbarrcode("", 'BSE5756776074', 80, 'horizontal', 'code39', true, 1);

        $barcodpath7 = checknewbarrcode("", 'BSE5756776074', 60, 'horizontal', 'code25', true, 1);
        $barcodpath8 = checknewbarrcode("", 'BSE5756776074', 70, 'horizontal', 'code25', true, 1);
        $barcodpath8last = checknewbarrcode("", 'BSE5756776074', 80, 'horizontal', 'code25', true, 1);

        $barcodpath9 = checknewbarrcode("", 'BSE5756776074', 60, 'horizontal', 'codabar', true, 1);
        $barcodpath10 = checknewbarrcode("", 'BSE5756776074', 70, 'horizontal', 'codabar', true, 1);
        $barcodpath10last = checknewbarrcode("", 'BSE5756776074', 80, 'horizontal', 'codabar', true, 1);

        echo '<div><h3>Sample 1</h3>';
        echo '<table><tr><td width="300"><img alt="coding sips" src="' . $barcodpath1 . '"></td>';
        echo '<td width="300"><img alt="coding sips" src="' . $barcodpath2 . '"></td>';
        echo '<td width="300"> <img alt="coding sips" src="' . $barcodpath2last . '"></td></tr></table>';

        echo '</div>';
        echo '<div><h3>Sample 2</h3>';
        echo '<table><tr><td width="300"><img alt="coding sips" src="' . $barcodpath3 . '"></td>';
        echo '<td width="300"><img alt="coding sips" src="' . $barcodpath4 . '"></td>';
        echo '<td width="300"><img alt="coding sips" src="' . $barcodpath4last . '"></td></tr></table>';

        echo '</div>';
        echo '<div><h3>Sample 3</h3>';
        echo '<table><tr><td width="300"><img alt="coding sips" src="' . $barcodpath5 . '"></td>';
        echo '<td width="300"><img alt="coding sips" src="' . $barcodpath6 . '"></td>';
        echo '<td width="300"><img alt="coding sips" src="' . $barcodpath6last . '"></td></tr></table>';

        echo '</div>';
        echo '<div><h3>Sample 4</h3>';
        echo '<table><tr><td width="300"><img alt="coding sips" src="' . $barcodpath7 . '"></td>';
        echo '<td width="300"><img alt="coding sips" src="' . $barcodpath8 . '"></td>';
        echo '<td width="300"><img alt="coding sips" src="' . $barcodpath8last . '"></td></tr></table>';

        echo '</div>';
        echo '<div><h3>Sample 5</h3>';
        echo '<table><tr><td width="300"><img alt="coding sips" src="' . $barcodpath9 . '"></td>';
        echo '<td width="300"><img alt="coding sips" src="' . $barcodpath10 . '"></td>';
        echo '<td width="300"><img alt="coding sips" src="' . $barcodpath10last . '"></td></tr></table>';

        echo '</div>';
        die;
    }

    public function GetCreateShipRows() {


        $this->db->select('shipment_fm.delivered,shipment_fm.code,shipment_fm.slip_no');
        $this->db->from('shipment_fm');
        $this->db->where_in('shipment_fm.code', 'POD');
        $this->db->where_in('shipment_fm.delivered', '7');
        $this->db->where(" shipment_fm.id BETWEEN 32846  and 34077");
        $this->db->join('status_fm', 'status_fm.slip_no = shipment_fm.slip_no');
        // $this->db->where_in('status_fm.code','DL');
        //  $this->db->where_in('status_fm.new_status','5');
        // $this->db->where_not_in('status_fm.new_status','9');
        // $this->db->where_not_in('status_fm.code','C');
        // $this->db->limit(10);
        $query = $this->db->get();
        $result = $query->result_array();
        echo count($result);
        die;
        /*  $query=$this->db->query('select * from shipment_fm limit 2000,5362');
          $result=$query->result_array();
          echo count($result);
          die;

          foreach($result as $data)
          {



          //   $addedArr=array('delivered'=>'8','code'=>'RTC');
          // $this->db->update('shipment_fm',$addedArr,array('slip_no'=>$data['slip_no']));
          }


          //echo $this->db->insert_batch('status_fm', $addedArr);

          //// echo '<pre>';
          // print_r($addedArr);
          // echo $query->num_rows(); die;
          die;
          die;

          /* $this->db->select('shipment_fm.delivered,shipment_fm.code,shipment_fm.slip_no');
          $this->db->from('shipment_fm');
          $this->db->where_in('shipment_fm.code','PG');
          $this->db->where_in('shipment_fm.delivered','2');
          $this->db->where(" shipment_fm.id BETWEEN 32846  and 34077");
          $this->db->join('status_fm', 'status_fm.slip_no = shipment_fm.slip_no');
          $this->db->where_in('status_fm.code','DL');
          $this->db->where_in('status_fm.new_status','5');
          $this->db->where_not_in('status_fm.new_status','9');
          $this->db->where_not_in('status_fm.code','C');
          // $this->db->limit(10);
          $query = $this->db->get();
          $result=$query->result_array(); */
        /*  $query=$this->db->query('select * from shipment_fm limit 2000,5362');
          $result=$query->result_array();
          foreach($result as $data)
          {
          $Activites="Reverse order as per customer request &rarr; Original AWB #".$data['slip_no'];
          $Details="Reverse order as per customer request &rarr; Original AWB #".$data['slip_no'];
          $addedArr[]=array('slip_no'=>$data['slip_no'],'new_location'=>$data['origin'],'new_status'=>1,'pickup_time'=>$data['entrydate'],'pickup_date'=>$data['entrydate'],'Activites'=>$Activites,'Details'=>$Details,'entry_date'=>$data['entrydate'],'user_id'=>$data['cust_id'],'user_type'=>'user','code'=>'OC');
          } */


        // echo '<pre>';
        // print_r($addedArr);
        //$this->db->insert_batch('status_fm', $addedArr); 
        //  $statusInsertData.=" ('".$data['slip_no']."','".$data['sender_city']."','".$data['delivered']."','".$data['CURRENT_TIME']."','".$entrydate."','".$Activites."','".$Details."','".$entrydate."','".$data['user_id']."','".$user_type."','".$this->getStatusCode($data['delivered'])."'),";
    }

    public function GetcheekGraph() {
        $totalorderschart = $this->Shipment_model->Getalltotalchartmonth();
        echo '<pre>';
        print_r($totalorderschart);
    }

    public function index() {
        // echo md5('Am@2021@@@'); die;


        $year = $this->input->post('year');
        if ($this->session->userdata('user_details')) {

            $Total_Shipments = $this->Shipment_model->count();
            $Total_Rts = $this->Shipment_model->countRTS();
            $Item_Inventory = $this->ItemInventory_model->count_all();
            $Item_Inventory_expire = $this->ItemInventory_model->count_all_expire('exp');
            $Total_Items = $this->Item_model->count();
            $Total_Cartoons = $this->Cartoon_model->count_all();
            $totalorderschart = $this->Shipment_model->Getalltotalchartmonth($year);

            //print_r($totalorderschart); die;
            //print_r(json_encode($totalorderschart));die;
            $Total_Sellers = $this->Seller_model->count();
            $this->load->view('home', [
                'Total_Shipments' => $Total_Shipments,
                'Total_Rts' => $Total_Rts,
                'Item_Inventory' => $Item_Inventory,
                'Total_Items' => $Total_Items,
                'Total_Cartoons' => $Total_Cartoons,
                'Total_Sellers' => $Total_Sellers,
                'totalorderschart' => $totalorderschart,
                'Item_Inventory_expire' => $Item_Inventory_expire
            ]);

            //$this->load->view('home');
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function logout() {


        $this->session->unset_userdata('user_details');
        $this->session->sess_destroy();
        $this->load->library('session');
        redirect(base_url() . 'Login');
    }

}
