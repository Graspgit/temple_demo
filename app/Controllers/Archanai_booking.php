<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;
use App\Models\RequestModel;
use App\Models\Common_model;

class Archanai_booking extends BaseController
{
    function __construct(){
        parent:: __construct();
        helper('url');
		helper('common_helper');
        $this->model = new PermissionModel();
		$this->common_model = new Common_model();
        if( ($this->session->get('log_id_frend') ) == false ){
            $data['dn_msg'] = 'Please Login';
            return redirect()->to('/member_login');
		}
    }
	
	public function index_old(){
      $login_id = $_SESSION['log_id_frend'];
	  
	  if(!$this->model->list_validate('archanai_ticket')){
			return redirect()->to(base_url().'/dashboard');}
	  $data['permission'] = $this->model->get_permission('archanai_ticket');
	  $data['staff'] = $this->db->table('staff')->get()->getResultArray();
	  $data['rasi'] = $this->db->table('rasi')->get()->getResultArray();
	  $data['nat'] = $this->db->table('natchathram')->get()->getResultArray();
	  $group = $this->db->query("SELECT * FROM archanai_group order by name asc")->getResultArray();
	  $data['archanai'][''] = $this->db->table('archanai')->where('groupname', '')->where('view_archanai', 1)->orderBy('order_no', 'ASC')->get()->getResultArray();
	  foreach($group as $row){ 
			$data['archanai'][$row['name']] = $this->db->table('archanai')->where('groupname', $row['name'])->where('view_archanai', 1)->orderBy('order_no', 'ASC')->get()->getResultArray();
	  }
      //$data['data'] = $this->db->table('archanai')->get()->getResultArray();
		$yr=date('Y');
		$mon=date('m');
		$query   = $this->db->query("SELECT ref_no FROM archanai_booking where id=(select max(id) from archanai_booking where year (date)='". $yr ."' and month (date)='". $mon ."')")->getRowArray();

      $data['bill_no']= 'AR' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['ref_no'],-5))+1)));
	  
	  echo view('frontend/layout/header');
      //echo view('template/sidebar');
      echo view('frontend/archanai/index', $data);
      echo view('frontend/layout/footer');
    }

	public function index()
	{
	   // $phone_codes
		$login_id = $_SESSION['log_id_frend'];
		/* if(!$this->model->list_validate('archanai_ticket')){
			return redirect()->to(base_url().'/dashboard');} */
		$data['payment_mode'] = $this->db->table('payment_mode')->where("paid_through", "COUNTER")->where("archanai", 1)->where('status', 1)->orderby('menu_order', 'ASC')->get()->getResultArray();
		$data['permission'] = $this->model->get_permission('archanai_ticket');
		$settings = $this->db->table('settings')->where('type', 1)->get()->getResultArray();
		$setting_array = array();
		if (count($settings) > 0) {
			foreach ($settings as $item) {
				$setting_array[$item['setting_name']] = $item['setting_value'];
			}
		}
		$data['setting'] = $setting_array;
		//$data['archanai_settings'] = $this->db->table('setting_archanai')->get()->getRowArray();
		$data['staff'] = $this->db->table('staff')->get()->getResultArray();
		$data['rasi'] = $this->db->table('rasi')->get()->getResultArray();
		$data['nat'] = $this->db->table('natchathram')->get()->getResultArray();
		$group = $this->db->query("SELECT * FROM archanai_group order by order_no asc")->getResultArray();
		$diety = $this->db->query("SELECT * FROM archanai_diety")->getResultArray();
		$i = 0;
		$data['archanai'] = array();
		foreach ($group as $row) {
			$group_achanai_list = $this->db->table('archanai')->where('groupname', $row['name'])->where('view_archanai', 1)->orderBy('order_no', 'ASC')->get()->getResultArray();
			$g_a_list = array();
			if (count($diety) > 0) {
				foreach ($diety as $d_row) {
					foreach ($group_achanai_list as $gal) {
						$diety_ids = explode(',', $gal['deity_id']);
						if (in_array($d_row['id'], $diety_ids)) {
							$gal['deity_id'] = $d_row['id'];
							$gal['code'] = $d_row['code'];
							$gal['diety_img_url'] = !empty($d_row['image']) ? base_url() . '/uploads/diety/' . $d_row['image'] : '';
							$g_a_list[] = $gal;
						}
					}
				}
			}
			
			if (count($g_a_list) > 0) {
				// usort($g_a_list, fn($a, $b) => $a['name_eng'] <=> $b['name_eng']);
				usort($g_a_list, function ($a, $b) {
					return $a['order_no'] <=> $b['order_no'];
				});
				if (empty($i))
					$data['default'] = str_replace(' ', '_', strtolower($row['name']));
				$data['archanai'][$row['id']]['title'] = $row['name'];
				$data['archanai'][$row['id']]['datas'] = $g_a_list;
				$i++;
			}
		}
		$yr = date('Y');
		$mon = date('m');
		// $booking_settings = $this->db->table('booking_setting')->get()->getResultArray();
		// $setting = array();
		// if(count($booking_settings) > 0){
		// 	foreach($booking_settings as $bs){
		// 		$setting[$bs['meta_key']] = $bs['meta_value'];
		// 	}
		// }
		// $data['setting'] = $setting;
		$query = $this->db->query("SELECT ref_no FROM archanai_booking where id=(select max(id) from archanai_booking where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
		$data['bill_no'] = 'AR' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
		$data['reprintlists'] = $this->db->query("SELECT id,amount,ref_no,date FROM archanai_booking WHERE entry_by = '" . $login_id . "' and paid_through = 'COUNTER' AND payment_status = 2 ORDER BY id DESC LIMIT 3")->getResultArray();
		
		$data["phone_codes"] = [["dailing_code"=>"+60"]];
		echo view('frontend/layout/header');
		echo view('frontend/archanai_new/index', $data);
	}
    public function getPhoneNo()
	{
	    $filter =[];
	   
	    $typ = $_POST["type"];
	    if($typ == "mobile")
	    {
    	    if(
    	        !isset($_POST["mobile"]) ||$_POST["mobile"] == ""
    	    )
    	    {
    	        echo json_encode(["status"=>false,"errMsg"=>"Mobile no is required"]);
    	        return;
    	        
    	    }
    	    
    	    $mobile_code = (isset($_POST["mobile_code"])?$_POST["mobile_code"]:"");
    	    $mobile = $_POST["mobile"];
    	    if($mobile_code!="")
    	        $filter["mobile_code"] = $mobile_code;
	    }
	    else
	    {
	     if(
    	        !isset($_POST["name"]) ||$_POST["name"] == ""
    	    )
    	    {
    	        echo json_encode(["status"=>false,"errMsg"=>"Name is required"]);
    	        return;
    	        
    	    } 
    	    $name = $_POST["name"];
	    }
	   
	    //echo $name;
	    if($typ == "mobile")
	    {
	        if($mobile_code != "")
        	    $data = $this->db->table("favourite")
        	        ->select("name,mobile,mobile_code")
        	        ->like('mobile_code', $mobile_code, 'after')
        	        ->like('mobile', $mobile, 'after')
        	        ->groupBy("name,mobile,mobile_code")
            	    ->get()
            	    ->getResultArray();
    	    else
    	        $data = $this->db->table("favourite")
    	            ->select("name,mobile,mobile_code")
    	            ->like('mobile', $mobile, 'after')
    	            ->groupBy("name,mobile,mobile_code")
            	    ->get()
            	    ->getResultArray();
	    }
	    else
	    {
	       
	    
	        $data = $this->db->table("favourite")
	                ->select("name,mobile,mobile_code")
            	    ->like('name', $name, 'after')
            	    ->groupBy("name,mobile,mobile_code")
            	    ->get()
            	    ->getResultArray();
            	   // die("r");
	    }
	    
	    //create options
	    $opt ="";
	    foreach($data as $iter)
	    {
	        $opt .= "<div style='color:blue;cursor:pointer' onclick='setCustomer(\"".$iter['name']."\",
	        \"".$iter['mobile_code']."\",
	        \"".$iter['mobile']."\")'>
	        ".$iter['name'].'('.
	        $iter['mobile_code'].$iter['mobile'].')'."</div>";
	    }
	    echo json_encode(["status"=>true,"data"=>$opt]);
	    return; 
	}

    
    public function index_old_new(){
		$login_id = $_SESSION['log_id_frend'];
		/* if(!$this->model->list_validate('archanai_ticket')){
				return redirect()->to(base_url().'/dashboard');} */
		$data['payment_mode'] = $this->db->table('payment_mode')->where("paid_through", "COUNTER")->where("archanai", 1)->where('status', 1)->orderby('menu_order', 'ASC')->get()->getResultArray();
		$data['permission'] = $this->model->get_permission('archanai_ticket');
		$data['staff'] = $this->db->table('staff')->get()->getResultArray();
		$data['rasi'] = $this->db->table('rasi')->get()->getResultArray();
		$data['nat'] = $this->db->table('natchathram')->get()->getResultArray();
		$group = $this->db->query("SELECT * FROM archanai_group order by order_no asc")->getResultArray();
		$data['archanai'][''] = $this->db->table('archanai')->where('groupname', '')->where('view_archanai', 1)->where('ledger_id !=', '')->orderBy('order_no', 'ASC')->get()->getResultArray();
		foreach($group as $row){ 
			$data['archanai'][$row['name']] = $this->db->table('archanai')->where('groupname', $row['name'])->where('view_archanai', 1)->where('ledger_id !=', '')->orderBy('order_no', 'ASC')->get()->getResultArray();
		}

			$yr=date('Y');
			$mon=date('m');
			$query   = $this->db->query("SELECT ref_no FROM archanai_booking where id=(select max(id) from archanai_booking where year (date)='". $yr ."' and month (date)='". $mon ."')")->getRowArray();

		$data['bill_no']= 'AR' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['ref_no'],-5))+1)));
		$archanai_group_data = $this->db->table('archanai_group')->where('order_no',1)->get()->getRowArray();
		$data['default'] = !empty($archanai_group_data['name']) ? $archanai_group_data['name'] : "ARCHANAI"; 
		$data['reprintlists'] = $this->db->query("SELECT id,amount,ref_no,date FROM archanai_booking WHERE entry_by = '".$login_id."' and paid_through = 'COUNTER' AND payment_status = 2 ORDER BY id DESC LIMIT 3")->getResultArray();
		$booking_settings = $this->db->table('booking_setting')->get()->getResultArray();
		$setting = array();
		if(count($booking_settings) > 0){
			foreach($booking_settings as $bs){
				$setting[$bs['meta_key']] = $bs['meta_value'];
			}
		}
		$data['setting'] = $setting;

	  echo view('frontend/layout/header');
      echo view('frontend/archanai_new/index', $data);
    }
    
    public function get_group_val() {
	    $argrp = $_POST['argrp'];
		$res = $this->db->table('archanai_group')->where('name',$argrp)->get()->getRowArray();
		
		$data = array("rasi"=>$res['rasi'], "name"=>$res['name']);
		
		echo json_encode($data);
		exit;
	}
    
	public function get_natchathram() {
	    $rasi_id = $_POST['rasi_id'];
		$res = $this->db->table('rasi')->where('id',$rasi_id)->get()->getRowArray();
		if(!empty($res['natchathra_id']))
		{
			$data = array("natchathra_id"=>$res['natchathra_id'], "rasi_id"=>$res['rasi_id']);
		}
		else
		{
			$res_natchathrams =  $this->db->table('natchathram')->get()->getResultArray();
			$data_bf = array();
			foreach($res_natchathrams as $res_natchathram)
			{
				$data_bf[] = $res_natchathram['id'];
			}
			$dataip = implode(',', $data_bf);
			$data = array("natchathra_id"=>$dataip, "rasi_id"=>$res['rasi_id']);
		}
		echo json_encode($data);
		exit;
	}
	public function get_natchathram_name() {
	    $id = $_POST['id'];
		$res =  $this->db->table('natchathram')->where('id',$id)->get()->getRowArray();
		$data = array("id"=>$res['id'], "name_eng"=>$res['name_eng']);
		echo json_encode($data);
		exit;
	}
	
    public function save(){
		// echo '<pre>';
		// print_r($_POST); 
		// exit; 
		$msg_data = array();
		$this->db->transStart();
		try{
			$msg_data['err'] = '';
			$msg_data['succ'] = '';
			$pay_id = $_POST['pay_method'];
			$payment_mode = $this->db->table('payment_mode')->where("id", $pay_id)->get()->getRowArray();
			$pay_method = $payment_mode['name'];
			$is_payment_gateway = $payment_mode['is_payment_gateway'];
			$payment_key = $payment_mode['pay_key'];
			$data['date'] = $date = $_POST['date'];
		
			$yr = date('Y', strtotime($date));
			$mon = date('m', strtotime($date));
			$query = $this->db->query("SELECT ref_no FROM archanai_booking where id=(select max(id) from archanai_booking where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
			$data['ref_no'] = 'AR' . $yr . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
			$data['entry_by'] = $this->session->get('log_id_frend');
			$data['sep_print'] = (!empty($_REQUEST['sep_print']) ? $_REQUEST['sep_print'] : 0);
			$data['created'] = date('Y-m-d H:i:s');
			$data['rasi_id'] = $rasi_id;
			$data['natchathra_id'] = $natchathra_id;
			$data['paid_through'] = "COUNTER";
			$data['payment_status'] = empty($is_payment_gateway) ? 2 : 1;

			$data['comission_to'] = 0;
			$tot_amt = 0;
			$tot_camt = 0;
			//echo 'Payment Status: ' . $data['payment_status'];
			//die();
			$ip = 'unknown';
			$this->requestmodel = new RequestModel();
			$ip = $this->requestmodel->getIpAddress();
			if ($ip != 'unknown') {
				$ip_details = $this->requestmodel->getLocation($ip);
				$data['ip'] = $ip;
				$data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
				$data['ip_details'] = json_encode($ip_details);
			}
			foreach ($_POST['arch'] as $arch) {
				$cash = $this->db->table('archanai')->where('id', $arch['id'])->get()->getRowArray();
				$amt = $arch['qty'] * $cash['amount'];
				$camt = $arch['qty'] * $cash['commission'];
				$tot_amt += $amt;
				$tot_camt += $camt;
			}
			$sub_total = $tot_amt;
			$data['sub_total'] = $sub_total;
			$discount_amount = 0;
			if(!empty($_REQUEST['discount_amount'])){
				$discount_amount = $_REQUEST['discount_amount'];
				if($discount_amount > $sub_total) $discount_amount = $sub_total;
			}
			$tot_amt -= $discount_amount;
			$data['discount_amount'] = $discount_amount;
			$data['amount'] = $tot_amt;
			$data['paid_amount'] = !empty($_POST['paid_amount']) ? $_POST['paid_amount'] : $tot_amt;
			$data['balance_amount'] = $data['paid_amount'] - $tot_amt;
			$data['comission'] = $tot_camt;
			$res = $this->db->table('archanai_booking')->insert($data);
			$arch_book_id = $this->db->insertID();
			if ($res) {
				// if (!empty($comission_to)){
				// $this->db->query("update staff set commission_amt=commission_amt+$tot_camt where id=$comission_to ");

				// }
				$cash_amt = 0;
				if (!empty($_POST['arch'])) {
					foreach ($_POST['arch'] as $arch) {
						$count_today_tharpanam = get_today_tharpanam_count($arch_book_id, $arch['id'], $arch['qty']);
						$data_arch_book['archanai_booking_id'] = $arch_book_id;
						$data_arch_book['archanai_id'] = $arch['id'];
						$data_arch_book['deity_id']  =	$arch['diety_id'];
						$data_arch_book['quantity'] = $arch['qty'];
						$data_arch_book['tharpanam_start'] = $count_today_tharpanam['start'];
						$data_arch_book['tharpanam_end'] = $count_today_tharpanam['end'];
						$data_arch_book['created'] = date('Y-m-d H:i:s');
						$cash = $this->db->table('archanai')->where('id', $arch['id'])->get()->getRowArray();
						$data_arch_book['amount'] = $cash['amount'];
						$data_arch_book['commision'] = $cash['commission'];
						$amt = $arch['qty'] * $cash['amount'];
						$camt = $arch['qty'] * $cash['commission'];
						$data_arch_book['total_amount'] = $amt - $camt;
						$data_arch_book['total_commision'] = $camt;
						$cash_amt = $cash_amt + $amt;
						if (!empty($cash['comission_to']) && !empty($camt)) {
							$data_arch_book['comission_to'] = $cash['comission_to'];
							$this->db->query("update staff set commission_amt=commission_amt+$camt where id=" . $cash['comission_to']);
						}
						$res_2 = $this->db->table('archanai_booking_details')->insert($data_arch_book);
						/*STOCK DEDECTION SECTION START */
						$archanai_dedection_data = $this->db->table('archanai')->where('id', $arch['id'])->get()->getRowArray();
						if (!empty($archanai_dedection_data['dedection_from_stock'])) {
							if ($archanai_dedection_data['dedection_from_stock'] == 1) {
								$am_raw_items = $this->db->table("archanai_raw_material_items")
									->where("product_id", $arch['id'])
									->get()->getResultArray();
								if (count($am_raw_items) > 0) {
									$staff_row = $this->db->table('staff')->where('name', 'admin')->where('is_admin', 1)->get()->getRowArray();
									$data_rtout['date'] = $_POST['dt'];
									$data_rtout['staff_name'] = !empty($staff_row['id']) ? $staff_row['id'] : NULL;
									$query_out = $this->db->query("SELECT invoice_no FROM stock_outward where id=(select max(id) from stock_outward where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
									$data_rtout['invoice_no'] = 'AR' . date('y', strtotime($_POST['dt'])) . $mon . (sprintf("%05d", (((float) substr($query_out['invoice_no'], -5)) + 1)));
									$data_rtout['date'] = $data['date'];
									$data_rtout['added_by'] = $this->session->get('log_id_frend');
									$data_rtout['modified'] = date("Y-m-d H:i:s");
									$data_rtout['created'] = date("Y-m-d H:i:s");
									$this->db->table('stock_outward')->insert($data_rtout);
									$ins_id_rtout = $this->db->insertID();
									$tot_outward_list_amt = 0;
									foreach ($am_raw_items as $pr_raw_item) {
										$tot_req_qty = $arch['qty'] * $pr_raw_item['qty'];
										$av_data_r = $this->db->table("raw_matrial_groups")->where("id", $pr_raw_item['raw_id'])->get()->getRowArray();
										$avl_stack_r['opening_stock'] = $av_data_r['opening_stock'] - $tot_req_qty;
										$this->db->table('raw_matrial_groups')->where('id', $pr_raw_item['raw_id'])->update($avl_stack_r);

										$uom_item_data = $this->db->table('raw_matrial_groups')->where('id', $pr_raw_item['raw_id'])->get()->getRowArray();
										$uom_id = $uom_item_data['uom_id'];
										$item_raw_name = $uom_item_data['name'];
										$item_raw_rate = $uom_item_data['price'];
										$item_raw_qty = $tot_req_qty;
										$item_raw_amt = $uom_item_data['price'] * $tot_req_qty;
										$data_rtout_list['stack_out_id'] = $ins_id_rtout;
										$data_rtout_list['item_type'] = 2;
										$data_rtout_list['item_id'] = $pr_raw_item['raw_id'];
										$data_rtout_list['item_name'] = $item_raw_name;
										$data_rtout_list['uom_id'] = $uom_id;
										$data_rtout_list['rate'] = $item_raw_rate;
										$data_rtout_list['quantity'] = $item_raw_qty;
										$data_rtout_list['amount'] = $item_raw_amt;
										$data_rtout_list['dedection_from'] = "Archanai";
										$data_rtout_list['dedection_id'] = $pr_raw_item['product_id'];
										$data_rtout_list['created'] = date("Y-m-d H:i:s");
										$data_rtout_list['modified'] = date("Y-m-d H:i:s");
										$this->db->table('stock_outward_list')->insert($data_rtout_list);
										$tot_outward_list_amt = $tot_outward_list_amt + $item_raw_amt;
									}
									$this->db->table('stock_outward')->where('id', $ins_id_rtout)->update(array("total_amount" => $tot_outward_list_amt));
								}
							}
						}
						/*STOCK DEDECTION SECTION END */
					}
				}
				if (!empty($_POST['rasi'])) {
					foreach ($_POST['rasi'] as $rasi) {
						$data_arch_rasi['archanai_booking_id'] = $arch_book_id;
						$data_arch_rasi['name'] = $rasi['arc_name'];
						$data_arch_rasi['rasi_id'] = $rasi['rasi_ids'];
						$data_arch_rasi['natchathram_id'] = $rasi['natchathra_ids'];
						$this->db->table('archanai_booking_rasi')->insert($data_arch_rasi);
					}
				}
				if (!empty($_POST['vehicle'])) {
					foreach ($_POST['vehicle'] as $vehicle) {
						$data_arch_vehicle['archanai_booking_id'] = $arch_book_id;
						$data_arch_vehicle['name'] = $vehicle['vle_name'];
						$data_arch_vehicle['vehicle_no'] = $vehicle['vle_no'];
						$this->db->table('archanai_booking_vehicle')->insert($data_arch_vehicle);
					}
				}
				$payment_gateway_data = array();
				$payment_gateway_data['archanai_booking_id'] = $arch_book_id;
				$payment_gateway_data['pay_method'] = $pay_method;
				$payment_gateway_data['payment_mode'] = $pay_id;
				$this->db->table('archanai_payment_gateway_datas')->insert($payment_gateway_data);
				$archanai_payment_gateway_id = $this->db->insertID();
				if($payment_key == 'rhb_qr'){
					$ref_no = PAYMENT_PREFIX . '_ARCH_' . $arch_book_id;
					if (PAYMENT_TEST) {
						$pay_amount = 1;
					} else {
						$pay_amount = bcdiv($tot_amt, '1', 2);
					}

					$bill_num = (string) ($arch_book_id + 100000000000);
					$url = 'https://dnqr.synexisasia.com/v1/qr';

					$json_data = [
						"merchantCode" => RHB_MERCHANTCODE,
						"userId" => RHB_USERID,
						"amount" => $pay_amount,
						"billNumber" => $bill_num,
						"transactionReference" => $ref_no,
					];

					// Assuming common_repository->postJson() posts JSON and returns JSON string
					$response = $this->common_model->postJson($url, $json_data);
					$response = json_decode($response);
					if (!empty($response->qrCode)) {
						$payment_gateway_up_data = [
							'request_data' => json_encode($json_data),
						];

						$this->db->table('archanai_payment_gateway_datas')
							->where('id', $archanai_payment_gateway_id)
							->update($payment_gateway_up_data);

						$msg_data['qr_code'] = $response->qrCode;
						$msg_data['total_amount'] = bcdiv($tot_amt, '1', 2);
					} else {
						$msg_data = [
							'err' => 'QR code not generated. Kindly rebook the ticket.',
						];
						// return json_encode($msg_data);
					}
					$msg_data['pay_status'] = false;
					$msg_data['payment_key'] = $payment_key;
				}else $msg_data['pay_status'] = true;
				if ($data['payment_status'] == 2)
					$this->account_migration($arch_book_id);
				$this->db->transComplete();
				if ($res_2) {
					// $this->session->setFlashdata('succ', 'Archanai Booking Added Successfully');
					$msg_data['succ'] = 'Archanai Booking Added Successfully';
					$msg_data['id'] = $arch_book_id;
				} else {
					$this->session->setFlashdata('fail', 'Please Try Again');
					$msg_data['err'] = 'Please Try Again';
				}
			}
		}catch (Exception $e) {
			$this->db->transRollback(); // Rollback the transaction if an error occurs
			$msg_data['err'] = $e->getMessage(); 
		}
		echo json_encode($msg_data);
		exit();
    }
	public function initiate_ipay_merch_qr($arch_book_id) {
		$barcode = !empty($_REQUEST['barcode']) ? $_REQUEST['barcode'] : '';
		$payment_id = !empty($_REQUEST['payment_id']) ? $_REQUEST['payment_id'] : '';
		$archanai_booking = $this->db->table('archanai_booking')->where('id', $arch_book_id)->get()->getRowArray();
		if(!empty($barcode) && !empty($archanai_booking['amount']) && !empty($payment_id)){
			$final_amt = $archanai_booking['amount'];
			$description = $archanai_booking['ref_no'];
			$xml_response = $this->initiatePaymentIpayMerchantQr('archanai',$barcode,'ARCH_' . $arch_book_id,$final_amt,$payment_id,$description);
			$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml_response['response_data']);
			$xml = new \SimpleXMLElement($response);
			$body = $xml->xpath('//sBody')[0];
			$aStatus = $body->EntryPageFunctionalityV2Response->EntryPageFunctionalityV2Result[0]->aStatus;
			$archanai_payment_gateway_datas = $this->db->table('archanai_payment_gateway_datas')->where('archanai_booking_id', $arch_book_id)->get()->getRowArray();
			$payment_gateway_up_data = array();
			$payment_gateway_up_data['request_data'] = $xml_response['request_data'];
			$payment_gateway_up_data['response_data'] = $xml_response['response_data'];
			$this->db->table('archanai_payment_gateway_datas')->where('id', $archanai_payment_gateway_datas['id'])->update($payment_gateway_up_data);
			if($aStatus == 1){
				$archanai_booking_up_data = array();
				$archanai_booking_up_data['payment_status'] = 2;
				$this->db->table('archanai_booking')->where('id', $arch_book_id)->update($archanai_booking_up_data);
				$this->account_migration($arch_book_id);
				$this->session->setFlashdata('succ', 'Archanai Booking Successfully');
				$redirect_url = base_url() . '/archanai_booking/print_booking/' .$arch_book_id;
				return redirect()->to($redirect_url);
			}else{
				$this->session->setFlashdata('fail', 'Payment Failed. Please Try Again');
				$redirect_url = base_url() . '/archanai_booking/';
				return redirect()->to($redirect_url);
			}
		}else{
			$this->session->setFlashdata('fail', 'Payment Failed. Please Try Again');
			$redirect_url = base_url() . '/archanai_booking/';
			return redirect()->to($redirect_url);
		}
	}
	public function initiatePaymentIpayMerchantQr($module,$barcode,$ref_no,$final_amt,$payment_id=336,$description='Archanai',$email = 'dd@ipay88.com.my') {
		$MerchantCode = "M15137";
		$MerchantKey = "Vx7AbhyzGK";
		$url = "https://payment.ipay88.com.my/ePayment/WebService/MHGatewayService/GatewayService.svc";
		$final_amt = 0.10;
		$final_amt_str = '010';
		$signature = hash('sha256', $MerchantKey . $MerchantCode . $ref_no . $final_amt_str . 'MYR' . $barcode);
		$xml_post_string='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mob="https://www.mobile88.com" xmlns:mhp="http://schemas.datacontract.org/2004/07/MHPHGatewayService.Model">
		   <soapenv:Header/>
		   <soapenv:Body>
			  <mob:EntryPageFunctionalityV2>
				 <mob:requestModelObj>
					<mhp:Amount>' . $final_amt . '</mhp:Amount>
					<mhp:BackendURL></mhp:BackendURL>
					<mhp:BarcodeNo>' . $barcode .'</mhp:BarcodeNo>
					<mhp:Currency>MYR</mhp:Currency>
					<mhp:MerchantCode>' . $MerchantCode . '</mhp:MerchantCode>
					<mhp:PaymentId>' . $payment_id . '</mhp:PaymentId>
					<mhp:ProdDesc>' . $description . '</mhp:ProdDesc>
					<mhp:RefNo>' . $ref_no . '</mhp:RefNo>
					<mhp:Remark>good</mhp:Remark>
					<mhp:Signature>' . $signature . '</mhp:Signature>
					<mhp:SignatureType>SHA256</mhp:SignatureType>
					<mhp:TerminalID></mhp:TerminalID>
					<mhp:UserContact>0179871656</mhp:UserContact>
					<mhp:UserEmail>' . $email . '</mhp:UserEmail>
					<mhp:UserName>fira</mhp:UserName>
					<mhp:lang>UTF-8</mhp:lang>
					<mhp:xfield1/>
				 </mob:requestModelObj>
			  </mob:EntryPageFunctionalityV2>
		   </soapenv:Body>
		</soapenv:Envelope>';
		$headers = array(
			"Accept-Encoding: gzip,deflate",
			"Content-Type: text/xml; charset=utf-8",
			"Host: payment.ipay88.com.my",
			"Content-length: ".strlen($xml_post_string),
			"SOAPAction: https://www.mobile88.com/IGatewayService/EntryPageFunctionalityV2"
		);
		//print_r($headers);
/* 		if($module == 'archanai'){
			$archanai_payment_gateway_datas = $this->db->table('archanai_payment_gateway_datas')->where('archanai_booking_id', $ref_no)->get()->getRowArray();
			$payment_gateway_up_data = array();
			$payment_gateway_up_data['request_data'] = $xml_post_string;
			$this->db->table('archanai_payment_gateway_datas')->where('id', $archanai_payment_gateway_datas['id'])->update($payment_gateway_up_data);
		} */
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		 
		$response = curl_exec($ch);
		//print_r($response);
		$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
		curl_close($ch);
		return array('request_data' => $xml_post_string, 'response_data' => $response);
	}
	public function ipay88_online_response($arch_book_id) {
		include_once FCPATH . 'app/Libraries/ipay88-master/IPay88.class.php';
		$MerchantCode = 'M01236';
		$MerchantKey = 'HQgUUZLVzg';
		$ipay88 = new \IPay88($MerchantCode);
		$ipay88->setMerchantKey($MerchantKey);
		$response = $ipay88->getResponse();
		//print_r($response);
		if($response['status']){
			$archanai_booking_up_data = array();
			$archanai_booking_up_data['payment_status'] = 2;
			$this->db->table('archanai_booking')->where('id', $arch_book_id)->update($archanai_booking_up_data);
			$this->account_migration($arch_book_id);
			$this->session->setFlashdata('succ', 'Archanai Booking Successfully');
			$redirect_url = base_url() . '/archanai_booking/print_booking/' .$arch_book_id;
			return redirect()->to($redirect_url);
		}else{
			$this->session->setFlashdata('fail', 'Payment Failed');
			echo'<script>
    window.onunload = refreshParent;
	window.close();
    function refreshParent() {
        window.opener.location.reload();
    }
</script>';
		}
	}
	public function initiate_ipay_merch_online($arch_book_id) {
		include_once FCPATH . 'app/Libraries/ipay88-master/IPay88.class.php';
		$payment_id = !empty($_REQUEST['payment_id']) ? $_REQUEST['payment_id'] : '';
		$archanai_booking = $this->db->table('archanai_booking')->where('id', $arch_book_id)->get()->getRowArray();
		$email = 'dd@ipay88.com.my';
		$description='Archanai';
		$final_amt = $archanai_booking['amount'];
		$final_amount = number_format($final_amt, '2','.','');
		$final_amt_str = (string) ($final_amt * 1000);
		$MerchantCode = 'M01236';
		$MerchantKey = 'HQgUUZLVzg';
		$ref_no = 'ARCH_' . $arch_book_id;
		$refno_pay = $ref_no;
		$module = 'archanai';
		// $final_amount = '1.00';
		// $final_amt_str = '1000';
		$ipay88 = new \IPay88($MerchantCode);
		$ipay88->setMerchantKey($MerchantKey);
		$ipay88->setField('PaymentId', 16);
		$ipay88->setField('RefNo', $refno_pay);
		$ipay88->setField('Amount', $final_amount);
		$ipay88->setField('Currency', 'MYR');
		$ipay88->setField('ProdDesc', $description);
		$ipay88->setField('UserName', 'Prithivi');
		$ipay88->setField('UserEmail', $email);
		$ipay88->setField('UserContact', '9856734562');
		$ipay88->setField('Remark', $description);
		$ipay88->setField('Lang', 'utf-8');
		$ipay88->setField('ResponseURL',  base_url() . '/archanai_booking/ipay88_online_response/' . $arch_book_id);
		$ipay88->setField('BackendURL',  base_url() . '/archanai_booking/ipay88_online_response/' . $arch_book_id);
		$ipay88->generateSignature();
		$ipay88_fields = $ipay88->getFields();
		$data['ipay88_fields'] = $ipay88_fields;
		$data['epayment_url'] = \Ipay88::$epayment_url;
		$view_file = 'frontend/ipay88/ipay_merch_online_process';
		echo view($view_file, $data);
	}
	public function payment_process($arch_book_id) {
		$archanai_booking = $this->db->table('archanai_booking')->where('id', $arch_book_id)->get()->getRowArray();
		$archanai_payment_gateway_datas = $this->db->table('archanai_payment_gateway_datas')->where('archanai_booking_id', $arch_book_id)->get()->getResultArray();
		if(count($archanai_payment_gateway_datas) > 0){
			if($archanai_payment_gateway_datas[0]['pay_method'] == 'adyen'){
				if(!empty($archanai_payment_gateway_datas[0]['request_data'])){
					$request_data = $archanai_payment_gateway_datas[0]['request_data'];
					$response = json_decode($request_data, true);
				}else{
					$tmpid = 1;
					$temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
					$result = $this->initiatePayment($archanai_booking['amount'],$arch_book_id,$temple_details['address1'] . $temple_details['address2'],$temple_details['city'],$temple_details['email']);
					$response = json_decode($result, true);
					$payment_gateway_up_data = array();
					$payment_gateway_up_data['request_data'] = $result;
					$payment_gateway_up_data['reference_id'] = $response['id'];
					$this->db->table('archanai_payment_gateway_datas')->where('id', $archanai_payment_gateway_datas[0]['id'])->update($payment_gateway_up_data);
				}
				if(!empty($response['url']) && !empty($response['id'])){
					return redirect()->to($response['url']);
				}
			}elseif($archanai_payment_gateway_datas[0]['pay_method'] == 'ipay_merch_qr'){
				//$view_file = 'frontend/ipay88/ipay_merch_qr';
				$view_file = 'frontend/ipay88/ipay_merch_qr_camera';
				$data['arch_book_id'] = $arch_book_id;
				$data['list'] = $this->db->table('payment_option')->where('status', 1)->get()->getResultArray();
				$data['submit_url'] = '/archanai_booking/initiate_ipay_merch_qr/' . $arch_book_id;
				echo view($view_file, $data);
			}elseif($archanai_payment_gateway_datas[0]['pay_method'] == 'ipay_merch_online'){
				$view_file = 'frontend/ipay88/ipay_merch_online';
				$data['id'] = $arch_book_id;
				$data['controller'] = 'archanai_booking';
				echo view($view_file, $data);
			}else{
				$redirect_url = base_url() . '/archanai_booking/print_booking/' .$arch_book_id;
				return redirect()->to($redirect_url);
			}
		}else{
			$tmpid = 1;
			$temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
			$result = $this->initiatePayment($archanai_booking['amount'],$arch_book_id,$temple_details['address1'] . $temple_details['address2'],$temple_details['city'],$temple_details['email']);
			$response = json_decode($result, true);
			if(!empty($response['url']) && !empty($response['id'])){
				$payment_gateway_data = array();
				$payment_gateway_data['archanai_booking_id'] = $arch_book_id;
				$payment_gateway_data['pay_method'] = 'adyen';
				$payment_gateway_data['request_data'] = $result;
				$payment_gateway_data['reference_id'] = $response['id'];
				$this->db->table('archanai_payment_gateway_datas')->insert($payment_gateway_data);
				$archanai_payment_gateway_id = $this->db->insertID();
				if(!empty($archanai_payment_gateway_id)){
					return redirect()->to($response['url']);
				}
			}
		}
	}
	public function initiatePayment($amount,$orderid,$address,$city,$email) {
		if(file_get_contents('php://input') != '') {
			$request = json_decode(file_get_contents('php://input'), true);
		}else{
			$request = array();
		}
		$apikey = "AQExhmfuXNWTK0Qc+iSGm3I5puqPTYhFHpxGTXFfyXa4nWlGJfnh+XuzwV6dTmmMJv6GnBDBXVsNvuR83LVYjEgiTGAH-09p02SzaBtpvbU0D3ZRFu8cWY44ivj4mqeMXogk0Ogk=-@e*vZIt9AWvaNN:.";
		$merchantAccount = "VivaantechsolutionscomECOM";
		$url = "https://checkout-test.adyen.com/v70/paymentLinks";
		$final_amt = $amount * 100;
		$data = [
			'amount' => [
			  'currency' => 'MYR',
			  'value' => $final_amt
			],
			"reference"=> $orderid,
			'countryCode' => "MY",
			'shopperReference' => "order_".$orderid,
			'shopperEmail' => $email,
			'shopperLocale' => "en-US",
			"billingAddress" => [
				"street"=>$address,
				"postalCode"=> "46000",
				"city"=> $city,
				"houseNumberOrName"=> "1/23",
				"country"=> "MY",
				"stateOrProvince"=> "KL"
			],
			"deliveryAddress" => [
				"street"=> $address,
				"postalCode"=> "46000",
				"city"=> $city,
				"houseNumberOrName"=> "1/23",
				"country"=> "MY",
				"stateOrProvince"=> "KL"
			],
			'returnUrl' => base_url() . '/archanai_booking/print_booking/' .$orderid,
			'merchantAccount' => $merchantAccount
		];
		$json_data = json_encode($data);
		$curlAPICall = curl_init();
		curl_setopt($curlAPICall, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curlAPICall, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlAPICall, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($curlAPICall, CURLOPT_URL, $url);
		curl_setopt($curlAPICall, CURLOPT_HTTPHEADER,
			array(
				"x-api-key: " . $apikey,
				"Content-Type: application/json",
				"Content-Length: " . strlen($json_data)
			)
		);
		$result = curl_exec($curlAPICall);
		if($result === false){
			throw new Exception(curl_error($curlAPICall), curl_errno($curlAPICall));
		}
		curl_close($curlAPICall);
		return $result;
    }
	public function initiatePayment_response($pay_id) {
		if(file_get_contents('php://input') != '') {
			$request = json_decode(file_get_contents('php://input'), true);
		}else{
			$request = array();
		}
		$apikey = "AQExhmfuXNWTK0Qc+iSGm3I5puqPTYhFHpxGTXFfyXa4nWlGJfnh+XuzwV6dTmmMJv6GnBDBXVsNvuR83LVYjEgiTGAH-09p02SzaBtpvbU0D3ZRFu8cWY44ivj4mqeMXogk0Ogk=-@e*vZIt9AWvaNN:.";
		$merchantAccount = "VivaantechsolutionscomECOM";
		$url = "https://checkout-test.adyen.com/v70/paymentLinks/".$pay_id;
		$curlAPICall = curl_init();
		curl_setopt($curlAPICall, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($curlAPICall, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlAPICall, CURLOPT_URL, $url);
		// Api key
		curl_setopt($curlAPICall, CURLOPT_HTTPHEADER,
			array(
				"x-api-key: " . $apikey
			)
		);
		$result = curl_exec($curlAPICall);
		if($result === false){
			throw new Exception(curl_error($curlAPICall), curl_errno($curlAPICall));
		}
		curl_close($curlAPICall);
		return $result;
    }
	public function account_migration($arch_book_id)
	{
		$archanai_booking = $this->db->table('archanai_booking')->where('id', $arch_book_id)->get()->getRowArray();
		$archanai_booking_details = $this->db->table('archanai_booking_details')->where('archanai_booking_id', $arch_book_id)->get()->getResultArray();
		$booking_settings = $this->db->table('booking_setting')->get()->getResultArray();
		$setting = array();
		if(count($booking_settings) > 0){
			foreach($booking_settings as $bs){
				$setting[$bs['meta_key']] = $bs['meta_value'];
			}
		}

		$td_ledger = $this->db->table('ledgers')->where('name', 'TRADE RECEIVABLE')->where('group_id', 3)->where('left_code', '1200')->get()->getRowArray();
		if (!empty($td_ledger)) {
		  $cr_id1 = $td_ledger['id'];
		} else {
		  $cled1['group_id'] = 3;
		  $cled1['name'] = 'TRADE RECEIVABLE';
		  $cled1['code'] = '1200/005';
		  $cled1['op_balance'] = '0';
		  $cled1['op_balance_dc'] = 'D';
		  $cled1['left_code'] = '1200';
		  $cled1['right_code'] = '005';
		  $this->db->table('ledgers')->insert($cled1);
		  $cr_id1 = $this->db->insertID();
		}

		if ($archanai_booking['paid_through'] == 'COUNTER') {
			$archanai_payment_gateway_datas = $this->db->table('archanai_payment_gateway_datas')->where('archanai_booking_id', $arch_book_id)->get()->getRowArray();

			// if ($archanai_payment_gateway_datas['pay_method'] == 'cash')
			// 	$payment_id = 6; ////  goto cash Ledger
			// elseif ($archanai_payment_gateway_datas['pay_method'] == 'online')
			// 	$payment_id = 7; ////  goto online Ledger
			// elseif ($archanai_payment_gateway_datas['pay_method'] == 'qr')
			// 	$payment_id = 12; ////  goto qr Ledger
			// elseif ($archanai_payment_gateway_datas['pay_method'] == 'nets_pay')
			// 	$payment_id = 10; ////  goto nets Ledger
			// elseif ($archanai_payment_gateway_datas['pay_method'] == 'pay_now')
			// 	$payment_id = 13; ////  goto Pay Now Ledger
			// elseif ($archanai_payment_gateway_datas['pay_method'] == 'cheque')
			// 	$payment_id = 11; ////  goto Cheque Ledger
			// elseif ($archanai_payment_gateway_datas['pay_method'] == 'card')
			// 	$payment_id = 13; ////  goto card Ledger
			// else
			// 	$payment_id = 4; ////  goto Qr or Online Payment Ledger

			$payment_id = $archanai_payment_gateway_datas['payment_mode'];
			$payment_mode_details = $this->db->table('payment_mode')->where('id', $payment_id)->get()->getRowArray();
			if (empty($payment_mode_details['id']))
				$payment_mode_details = $this->db->table('payment_mode')->get()->getRowArray();
			$sales_group = $this->db->table('groups')->where('name', 'Sales')->where('parent_id', 26)->get()->getRowArray();
			if (!empty($sales_group)) {
				$sls_id = $sales_group['id'];
			} else {
				$sls1['parent_id'] = 26;
				$sls1['name'] = 'Sales';
				$sls1['code'] = '330';
				$sls1['added_by'] = $this->session->get('log_id');
				$led_ins1 = $this->db->table('groups')->insert($sls1);
				$sls_id = $this->db->insertID();
			}
			$discount_amount = !empty($archanai_booking['discount_amount']) ? (float) $archanai_booking['discount_amount'] : 0;
			if(!empty($discount_amount)){

				if (count($archanai_booking_details) > 0) {
					$cash_amt = 0;
					$com_amt = 0;
					foreach ($archanai_booking_details as $arch) {
						$amt = $arch['quantity'] * $arch['amount'];
						$camt = $arch['quantity'] * $arch['commission'];
						$cash_amt = $cash_amt + $amt;
						$com_amt = $com_amt + $camt;
					}
					$number = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
					if (empty($number)) {
						$num = 1;
					} else {
						$num = $number['number'] + 1;
					}
					$yr = date('Y', strtotime($archanai_booking['date']));
					$mon = date('m', strtotime($archanai_booking['date']));
					$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();
					$entries = array();
					$entries['entry_code'] = 'JOR' . date('y', strtotime($archanai_booking['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));

					$entries['entrytype_id'] = '4';
					$entries['number'] = $num;
					$entries['date'] = $archanai_booking['date'];
					$entries['dr_total'] = $cash_amt;
					$entries['cr_total'] = $cash_amt;
					$entries['narration'] = 'Archanai Booking(' . $archanai_booking['ref_no'] . ')';
					$entries['inv_id'] = $arch_book_id;
					$entries['type'] = '3';
					$ent = $this->db->table('entries')->insert($entries);
					$en_id = $this->db->insertID();
					if (!empty($en_id)) {
						foreach ($archanai_booking_details as $arch) {
							$archanai_details = $this->db->table('archanai')->where('id', $arch['archanai_id'])->get()->getRowArray();
							/* $archanai_name = $archanai_details['name_eng'];
											$ledger1 = $this->db->table('ledgers')->where('name', $archanai_name)->where('group_id', $sls_id)->get()->getRowArray();
											if(!empty($ledger1)){
												$dr_id = $ledger1['id'];
											} */
							if (!empty($archanai_details['ledger_id'])) {
								$dr_id = $archanai_details['ledger_id'];
							} else {
								$ledger1 = $this->db->table('ledgers')->where('name', 'All Sales')->where('group_id', $sls_id)->get()->getRowArray();
								if (!empty($ledger1)) {
									$dr_id = $ledger1['id'];
								} else {
									$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '4913')->orderBy('right_code', 'desc')->get()->getRowArray();
									$set_right_code = (int) $right_code['right_code'] + 1;
									$set_right_code = sprintf("%04d", $set_right_code);
									$led1 = array();
									$led1['group_id'] = $sls_id;
									$led1['name'] = 'All Sales';
									$led1['left_code'] = '4913';
									$led1['right_code'] = $set_right_code;
									$led1['op_balance'] = '0';
									$led1['op_balance_dc'] = 'D';
									$led_ins1 = $this->db->table('ledgers')->insert($led1);
									$dr_id = $this->db->insertID();
								}
							}
							$cash_amount = $arch['quantity'] * $arch['amount'];
							$eitems_d = array();
							$eitems_d['entry_id'] = $en_id;
							$eitems_d['ledger_id'] = $dr_id;
							$eitems_d['amount'] = $cash_amount;
							$eitems_d['dc'] = 'C';
							$eitems_d['details'] = 'Amount for Archanai (' . $archanai_booking['ref_no'] . ')';
							$this->db->table('entryitems')->insert($eitems_d);
						}
						$eitems_c = array();
						$eitems_c['entry_id'] = $en_id;
						$eitems_c['ledger_id'] = $cr_id1;
						/* if ($comission_to!=0)
										$eitems_c['amount'] = $cash_amt-$com_amt;
									else */
						$eitems_c['amount'] = $cash_amt;
						$eitems_c['dc'] = 'D';
						$eitems_c['details'] = 'Amount for Archanai (' . $archanai_booking['ref_no'] . ')';
						$this->db->table('entryitems')->insert($eitems_c);
						$debtor_amount += $cash_amt;
					}

					if (!empty($discount_amount)) {
						$number1 = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
						if (empty($number1))
							$num1 = 1;
						else
							$num1 = $number1['number'] + 1;
						$qry1 = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();
						$entries1 = array();
						$entries1['entry_code'] = 'JOR' . date('y', strtotime($archanai_booking['date'])) . $mon . (sprintf("%05d", (((float) substr($qry1['entry_code'], -5)) + 1)));
						$entries1['entrytype_id'] = '4';
						$entries1['number'] = $num1;
						$entries1['date'] = $archanai_booking['date'];
						$entries1['dr_total'] = $discount_amount;
						$entries1['cr_total'] = $discount_amount;
						$entries1['narration'] = 'Archanai Booking(' . $archanai_booking['ref_no'] . ')';
						$entries1['inv_id'] = $arch_book_id;
						$entries1['type'] = 3;
						//Insert Entries
						$ent = $this->db->table('entries')->insert($entries1);
						$en_id2 = $this->db->insertID();
						if (!empty($en_id2)) {
							$discount_ledger_id = !empty($setting['discount_archanai_ledger_id']) ? $setting['discount_archanai_ledger_id'] : 169;
							$booking_type_name = 'Archanai';
							
							// Trade Debtors => Credit
							$eitems_hall_book['entry_id'] = $en_id2;
							$eitems_hall_book['ledger_id'] = $cr_id1;
							$eitems_hall_book['amount'] = $discount_amount;
							$eitems_hall_book['dc'] = 'C';
							$eitems_hall_book['details'] = 'Discount for ' . $booking_type_name . '(' . $archanai_booking['ref_no'] . ')';
							$this->db->table('entryitems')->insert($eitems_hall_book);
			
							//  Discount => Debit 
							$eitems_disc_ent = array();
							$eitems_disc_ent['entry_id'] = $en_id2;
							$eitems_disc_ent['ledger_id'] = $discount_ledger_id;
							$eitems_disc_ent['amount'] = $discount_amount;
							// $eitems_disc_ent['is_discount'] = 1;
							$eitems_disc_ent['dc'] = 'D';
							$eitems_disc_ent['details'] = 'Discount for ' . $booking_type_name . '(' . $archanai_booking['ref_no'] . ')';
							$this->db->table('entryitems')->insert($eitems_disc_ent);
						}
					}
					$debtor_amount -= $discount_amount;
					if(!empty($debtor_amount)){
						$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
						if (empty($number)) {
							$num = 1;
						} else {
							$num = $number['number'] + 1;
						}
						$yr = date('Y', strtotime($archanai_booking['date']));
						$mon = date('m', strtotime($archanai_booking['date']));
						$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();
						$entries['entry_code'] = 'REC' . date('y', strtotime($archanai_booking['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));

						$entries['entrytype_id'] = '1';
						$entries['number'] = $num;
						$entries['date'] = $archanai_booking['date'];
						$entries['dr_total'] = $debtor_amount;
						$entries['cr_total'] = $debtor_amount;
						$entries['narration'] = 'Archanai Booking(' . $archanai_booking['ref_no'] . ')';
						$entries['inv_id'] = $arch_book_id;
						$entries['type'] = '3';
						$ent = $this->db->table('entries')->insert($entries);
						$en_id = $this->db->insertID();
						if (!empty($en_id)) {
							$eitems_d = array();
							$eitems_d['entry_id'] = $en_id;
							$eitems_d['ledger_id'] = $cr_id1;
							$eitems_d['amount'] = $debtor_amount;
							$eitems_d['dc'] = 'C';
							$eitems_d['details'] = 'Amount for Archanai (' . $archanai_booking['ref_no'] . ')';
							$this->db->table('entryitems')->insert($eitems_d);
							
							$eitems_c = array();
							$eitems_c['entry_id'] = $en_id;
							$eitems_c['ledger_id'] = $payment_mode_details['ledger_id'];
							$eitems_c['amount'] = $archanai_booking['paid_amount'];
							$eitems_c['dc'] = 'D';
							$eitems_c['details'] = 'Amount for Archanai (' . $archanai_booking['ref_no'] . ')';
							$this->db->table('entryitems')->insert($eitems_c);
							$donation_amount = $debtor_amount - $archanai_booking['paid_amount'] - $archanai_booking['balance_amount'];
							if(!empty($donation_amount)){
								if(!empty($temple_details['archanai_ledger_id'])) $archanai_id = $temple_details['archanai_ledger_id'];
								else{
									$incomes_group = $this->db->table('groups')->where('code', '8000')->get()->getRowArray();
									if(!empty($incomes_group)){
										$inc_id = $incomes_group['id'];
									}else{
										$inc = array();
										$inc1['parent_id'] = 0;
										$inc1['name'] = 'Incomes';
										$inc1['code'] = '8000';
										$inc1['added_by'] = $this->session->get('log_id');
										$this->db->table('groups')->insert($sls1);
										$inc_id = $this->db->insertID();
									}
									$ledger1 = $this->db->table('ledgers')->where('name', 'Archanai Surplus')->where('group_id', $inc_id)->get()->getRowArray();
									if(!empty($ledger1)){
										$archanai_id = $ledger1['id'];
									}else{
										$led1 = array();
										$led1['group_id'] = $inc_id;
										$led1['name'] = 'Archanai Surplus';
										$led1['left_code'] = '8000';
										$led1['right_code'] = '0003';
										$led1['op_balance'] = '0';
										$led1['op_balance_dc'] = 'D';
										$led_ins1 = $this->db->table('ledgers')->insert($led1);
										$archanai_id = $this->db->insertID();
									}
								}
								$eitems_c = array();
								$eitems_c['entry_id'] = $en_id;
								$eitems_c['ledger_id'] = $archanai_id;
								$eitems_c['amount'] = $donation_amount;
								$eitems_c['dc'] = 'D';
								$eitems_c['details'] = 'Amount for Archanai (' . $archanai_booking['ref_no'] . ')';
								$this->db->table('entryitems')->insert($eitems_c);
							}
						}
					}
					return true;

				} else
					return false;
			}else{
				if (count($archanai_booking_details) > 0) {
					$tot_cash_amt = 0;
					$tot_com_amt = 0;
					foreach ($archanai_booking_details as $arch) {
						$amt = $arch['quantity'] * $arch['amount'];
						$camt = $arch['quantity'] * $arch['commission'];
						$tot_cash_amt += $amt;
						$tot_com_amt += $camt;
					}
					$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
					if (empty($number)) {
						$num = 1;
					} else {
						$num = $number['number'] + 1;
					}
					$yr = date('Y', strtotime($archanai_booking['date']));
					$mon = date('m', strtotime($archanai_booking['date']));
					$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();
					$entries['entry_code'] = 'REC' . date('y', strtotime($archanai_booking['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));

					$entries['entrytype_id'] = '1';
					$entries['number'] = $num;
					$entries['date'] = $archanai_booking['date'];
					$entries['dr_total'] = $tot_cash_amt;
					$entries['cr_total'] = $tot_cash_amt;
					$entries['narration'] = 'Archanai Booking(' . $archanai_booking['ref_no'] . ')';
					$entries['inv_id'] = $arch_book_id;
					$entries['type'] = '3';
					$ent = $this->db->table('entries')->insert($entries);
					$en_id = $this->db->insertID();
					if (!empty($en_id)) {
						foreach ($archanai_booking_details as $arch) {
							$archanai_details = $this->db->table('archanai')->where('id', $arch['archanai_id'])->get()->getRowArray();
							if (!empty($archanai_details['ledger_id'])) {
								$dr_id = $archanai_details['ledger_id'];
							} else {
								$ledger1 = $this->db->table('ledgers')->where('name', 'All Sales')->where('group_id', $sls_id)->get()->getRowArray();
								if (!empty($ledger1)) {
									$dr_id = $ledger1['id'];
								} else {
									$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '4913')->orderBy('right_code', 'desc')->get()->getRowArray();
									$set_right_code = (int) $right_code['right_code'] + 1;
									$set_right_code = sprintf("%04d", $set_right_code);
									$led1 = array();
									$led1['group_id'] = $sls_id;
									$led1['name'] = 'All Sales';
									$led1['left_code'] = '4913';
									$led1['right_code'] = $set_right_code;
									$led1['op_balance'] = '0';
									$led1['op_balance_dc'] = 'D';
									$led_ins1 = $this->db->table('ledgers')->insert($led1);
									$dr_id = $this->db->insertID();
								}
							}
							$cash_amount = $arch['quantity'] * $arch['amount'];
							$eitems_d = array();
							$eitems_d['entry_id'] = $en_id;
							$eitems_d['ledger_id'] = $dr_id;
							$eitems_d['amount'] = $cash_amount;
							$eitems_d['dc'] = 'C';
							$eitems_d['details'] = 'Amount for Archanai (' . $archanai_booking['ref_no'] . ')';
							$this->db->table('entryitems')->insert($eitems_d);
						}
						$eitems_c = array();
						$eitems_c['entry_id'] = $en_id;
						$eitems_c['ledger_id'] = $payment_mode_details['ledger_id'];
						$eitems_c['amount'] = $archanai_booking['paid_amount'];
						$eitems_c['dc'] = 'D';
						$eitems_c['details'] = 'Amount for Archanai (' . $archanai_booking['ref_no'] . ')';
						$this->db->table('entryitems')->insert($eitems_c);
						$donation_amount = $tot_cash_amt - $archanai_booking['paid_amount'] - $archanai_booking['balance_amount'];
						if(!empty($donation_amount)){
							if(!empty($temple_details['archanai_ledger_id'])) $archanai_id = $temple_details['archanai_ledger_id'];
							else{
								$incomes_group = $this->db->table('groups')->where('code', '8000')->get()->getRowArray();
								if(!empty($incomes_group)){
									$inc_id = $incomes_group['id'];
								}else{
									$inc = array();
									$inc1['parent_id'] = 0;
									$inc1['name'] = 'Incomes';
									$inc1['code'] = '8000';
									$inc1['added_by'] = $this->session->get('log_id');
									$this->db->table('groups')->insert($sls1);
									$inc_id = $this->db->insertID();
								}
								$ledger1 = $this->db->table('ledgers')->where('name', 'Archanai Surplus')->where('group_id', $inc_id)->get()->getRowArray();
								if(!empty($ledger1)){
									$archanai_id = $ledger1['id'];
								}else{
									$led1 = array();
									$led1['group_id'] = $inc_id;
									$led1['name'] = 'Archanai Surplus';
									$led1['left_code'] = '8000';
									$led1['right_code'] = '0003';
									$led1['op_balance'] = '0';
									$led1['op_balance_dc'] = 'D';
									$led_ins1 = $this->db->table('ledgers')->insert($led1);
									$archanai_id = $this->db->insertID();
								}
							}
							$eitems_c = array();
							$eitems_c['entry_id'] = $en_id;
							$eitems_c['ledger_id'] = $archanai_id;
							$eitems_c['amount'] = $donation_amount;
							$eitems_c['dc'] = 'D';
							$eitems_c['details'] = 'Amount for Archanai (' . $archanai_booking['ref_no'] . ')';
							$this->db->table('entryitems')->insert($eitems_c);
						}
					}
					return true;

				} else
					return false;
			}
		}else
			return false;
	}
	public function account_migration_old($arch_book_id)
	{
		$archanai_booking = $this->db->table('archanai_booking')->where('id', $arch_book_id)->get()->getRowArray();
		$booking_settings = $this->db->table('booking_setting')->get()->getResultArray();
		$setting = array();
		if(count($booking_settings) > 0){
			foreach($booking_settings as $bs){
				$setting[$bs['meta_key']] = $bs['meta_value'];
			}
		}
		$archanai_booking_details = $this->db->table('archanai_booking_details')->where('archanai_booking_id', $arch_book_id)->get()->getResultArray();
		if ($archanai_booking['paid_through'] == 'COUNTER') {
			$archanai_payment_gateway_datas = $this->db->table('archanai_payment_gateway_datas')->where('archanai_booking_id', $arch_book_id)->get()->getRowArray();
			if ($archanai_payment_gateway_datas['pay_method'] == 'cash')
				$payment_id = 6; ////  goto cash Ledger
			elseif ($archanai_payment_gateway_datas['pay_method'] == 'online')
				$payment_id = 7; ////  goto online Ledger
			elseif ($archanai_payment_gateway_datas['pay_method'] == 'qr')
				$payment_id = 12; ////  goto qr Ledger
			elseif ($archanai_payment_gateway_datas['pay_method'] == 'card')
				$payment_id = 13; ////  goto Pay Now Ledger
			elseif ($archanai_payment_gateway_datas['pay_method'] == 'cheque')
				$payment_id = 11; ////  goto Cheque Ledger
			else
				$payment_id = 4; ////  goto Qr or Online Payment Ledger
			$payment_mode_details = $this->db->table('payment_mode')->where('id', $payment_id)->get()->getRowArray();
			if (empty($payment_mode_details['id']))
				$payment_mode_details = $this->db->table('payment_mode')->get()->getRowArray();
			$sales_group = $this->db->table('groups')->where('name', 'Sales')->where('parent_id', 26)->get()->getRowArray();
			if (!empty($sales_group)) {
				$sls_id = $sales_group['id'];
			} else {
				$sls1['parent_id'] = 26;
				$sls1['name'] = 'Sales';
				$sls1['code'] = '330';
				$sls1['added_by'] = $this->session->get('log_id');
				$led_ins1 = $this->db->table('groups')->insert($sls1);
				$sls_id = $this->db->insertID();
			}
			// Debit ledger
			/*$ledger1 = $this->db->table('ledgers')->where('name', 'ARCHANAI COLLECTION')->where('group_id', $sls_id)->get()->getRowArray();
				if(!empty($ledger1)){
					$dr_id = $ledger1['id'];
				}else{
					$led1['group_id'] = $sls_id;
					$led1['name'] = 'ARCHANAI COLLECTION';
					$led1['left_code'] = '7013';
					$led1['right_code'] = '000';
					$led1['op_balance'] = '0';
					$led1['op_balance_dc'] = 'D';
					$led_ins1 = $this->db->table('ledgers')->insert($led1);
					$dr_id = $this->db->insertID();
				}*/
			if (count($archanai_booking_details) > 0) {
				$cash_amt = 0;
				$com_amt = 0;
				foreach ($archanai_booking_details as $arch) {
					$amt = $arch['quantity'] * $arch['amount'];
					$camt = $arch['quantity'] * $arch['commission'];
					$cash_amt = $cash_amt + $amt;
					$com_amt = $com_amt + $camt;
				}

				$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
				if (empty($number)) {
					$num = 1;
				} else {
					$num = $number['number'] + 1;
				}
				$yr = date('Y', strtotime($archanai_booking['date']));
				$mon = date('m', strtotime($archanai_booking['date']));
				$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();
				$entries['entry_code'] = 'REC' . date('y', strtotime($archanai_booking['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));

				$entries['entrytype_id'] = '1';
				$entries['number'] = $num;
				$entries['date'] = $archanai_booking['date'];
				$entries['dr_total'] = $cash_amt;
				$entries['cr_total'] = $cash_amt;
				$entries['narration'] = 'Archanai Booking(' . $archanai_booking['ref_no'] . ')';
				$entries['inv_id'] = $arch_book_id;
				$entries['type'] = '3';
				$ent = $this->db->table('entries')->insert($entries);
				$en_id = $this->db->insertID();
				if (!empty($en_id)) {
					foreach ($archanai_booking_details as $arch) {
						$archanai_details = $this->db->table('archanai')->where('id', $arch['archanai_id'])->get()->getRowArray();
						/* $archanai_name = $archanai_details['name_eng'];
										  $ledger1 = $this->db->table('ledgers')->where('name', $archanai_name)->where('group_id', $sls_id)->get()->getRowArray();
										  if(!empty($ledger1)){
											  $dr_id = $ledger1['id'];
										  } */
						if (!empty($archanai_details['ledger_id'])) {
							$dr_id = $archanai_details['ledger_id'];
						} else {
							$ledger1 = $this->db->table('ledgers')->where('name', 'All Sales')->where('group_id', $sls_id)->get()->getRowArray();
							if (!empty($ledger1)) {
								$dr_id = $ledger1['id'];
							} else {
								$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '4913')->orderBy('right_code', 'desc')->get()->getRowArray();
								$set_right_code = (int) $right_code['right_code'] + 1;
								$set_right_code = sprintf("%04d", $set_right_code);
								$led1['group_id'] = $sls_id;
								$led1['name'] = 'All Sales';
								$led1['left_code'] = '4913';
								$led1['right_code'] = $set_right_code;
								$led1['op_balance'] = '0';
								$led1['op_balance_dc'] = 'D';
								$led_ins1 = $this->db->table('ledgers')->insert($led1);
								$dr_id = $this->db->insertID();
							}
						}
						$cash_amount = $arch['quantity'] * $arch['amount'];
						$eitems_d = array();
						$eitems_d['entry_id'] = $en_id;
						$eitems_d['ledger_id'] = $dr_id;
						$eitems_d['amount'] = $cash_amt;
						$eitems_d['dc'] = 'C';
						$eitems_d['details'] = 'Amount for Archanai (' . $archanai_booking['ref_no'] . ')';
						$this->db->table('entryitems')->insert($eitems_d);
					}
					$eitems_c['entry_id'] = $en_id;
					$eitems_c['ledger_id'] = $payment_mode_details['ledger_id'];
					/* if ($comission_to!=0)
									   $eitems_c['amount'] = $cash_amt-$com_amt;
								   else */
					$eitems_c['amount'] = $cash_amt;
					$eitems_c['dc'] = 'D';
					$eitems_c['details'] = 'Amount for Archanai (' . $archanai_booking['ref_no'] . ')';
					$this->db->table('entryitems')->insert($eitems_c);

					if (!empty($archanai_booking['discount_amount'])) {
						$number1 = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
						if (empty($number1))
							$num1 = 1;
						else
							$num1 = $number1['number'] + 1;
						$qry1 = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();
			
						$entries1['entry_code'] = 'JOR' . date('y', strtotime($archanai_booking['date'])) . $mon . (sprintf("%05d", (((float) substr($qry1['entry_code'], -5)) + 1)));
						$entries1['entrytype_id'] = '4';
						$entries1['number'] = $num1;
						$entries1['date'] = $archanai_booking['date'];
						$entries1['dr_total'] = $archanai_booking['discount_amount'];
						$entries1['cr_total'] = $archanai_booking['discount_amount'];
						$entries1['narration'] = 'Archanai Booking(' . $archanai_booking['ref_no'] . ')';
						$entries1['inv_id'] = $arch_book_id;
						$entries1['type'] = 3;
						//Insert Entries
						$ent = $this->db->table('entries')->insert($entries1);
						$en_id2 = $this->db->insertID();
						if (!empty($en_id2)) {
			
							$discount_ledger_id = !empty($setting['discount_archanai_ledger_id']) ? $setting['discount_archanai_ledger_id'] : 1097;
							$booking_type_name = 'Archanai';
							
							// Hall Booking => Credit
							$eitems_hall_book['entry_id'] = $en_id2;
							$eitems_hall_book['ledger_id'] = $dr_id;
							$eitems_hall_book['amount'] = $archanai_booking['discount_amount'];
							$eitems_hall_book['dc'] = 'C';
							$eitems_hall_book['details'] = 'Discount for ' . $booking_type_name . '(' . $archanai_booking['ref_no'] . ')';
							$this->db->table('entryitems')->insert($eitems_hall_book);
			
							//  Trade Debtors => Debit 
							$eitems_disc_ent = array();
							$eitems_disc_ent['entry_id'] = $en_id2;
							$eitems_disc_ent['ledger_id'] = $discount_ledger_id;
							$eitems_disc_ent['amount'] = $archanai_booking['discount_amount'];
							$eitems_disc_ent['is_discount'] = 1;
							$eitems_disc_ent['dc'] = 'D';
							$eitems_disc_ent['details'] = 'Discount for ' . $booking_type_name . '(' . $archanai_booking['ref_no'] . ')';
							$this->db->table('entryitems')->insert($eitems_disc_ent);
			
						}else{
							$succ = false;
							return $succ;
						}
					}
				}
				return true;
			} else
				return false;
		} else
			return false;
	}
	 public function print_booking($arch_book_id){
	     
	 	$id = $this->request->uri->getSegment(3);
		$data['qry1'] = $archanai_booking = $this->db->table('archanai_booking')->where('id', $id)->get()->getRowArray();
		$settings = $this->db->table('settings')->where('type', 1)->get()->getResultArray();
		$setting_array = array();
		if (count($settings) > 0) {
			foreach ($settings as $item) {
				$setting_array[$item['setting_name']] = $item['setting_value'];
			}
		}
		$data['setting'] = $setting_array;
		
		// $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
		// $data['qrcdoee'] = qrcode_generation($id,$url, 95, 95);
		if($archanai_booking['sep_print'] == 1) $view_file = 'frontend/archanai/sep_print_imin';
		//if($archanai_booking['sep_print'] == 1) $view_file = 'frontend/archanai/print_sep';
		else $view_file = 'frontend/archanai/print_imin';
		//else $view_file = 'frontend/archanai/print';
		
		if($archanai_booking['paid_through'] == 'COUNTER' || $archanai_booking['paid_through'] == 'KIOSK'){
			if(true || $archanai_booking['payment_status'] == '2'){
			    
				//$data['qry2'] = $this->db->table('archanai_booking_details')->where('archanai_booking_id', $id)->get()->getResultArray();
				//echo "<pre>"; print_r($id); exit();
				$tmpid = $this->session->get('profile_id');
				$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

				$data['booking'] = $this->db->table('archanai_booking_details', 'archanai', 'archanai_booking_rasi', 'rasi', 'natchathram')
							->join('archanai', 'archanai.id = archanai_booking_details.archanai_id', 'left')
							->join('archanai_diety', 'archanai_diety.id = archanai_booking_details.deity_id', 'left')
							->where('archanai_booking_details.archanai_booking_id', $id )
							->select('archanai.*, archanai_diety.name_tamil as diety_name')
							->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
							->get()->getResultArray();	
							
				$data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
							->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
							->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
							->where('archanai_booking_rasi.archanai_booking_id', $id )
							->select('archanai_booking_rasi.*')
							->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
							->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
							->get()
							->getResultArray();
				$data['vehicles'] = $this->db->table('archanai_booking_vehicle')
							->where('archanai_booking_vehicle.archanai_booking_id', $id )
							->select('archanai_booking_vehicle.*')
							->get()
							->getResultArray();
				$settings_arr = $this->db->table('settings')->where('type', 1)->get()->getResultArray();
				$settings = array();
				if(count($settings_arr) > 0){
					foreach($settings_arr as $val){
						$settings[$val['setting_name']] = $val['setting_value'];
					}
				}
				$trans_details = array();
				$payment_name = $this->db->table('archanai_payment_gateway_datas')->where('archanai_booking_id', $id)->get()->getRowArray();
				$data['payment_name'] = $payment_name;
				if(!empty($payment_name['payment_mode']) && !empty($payment_name['response_data'])){
					$payment_mode_details = $this->db->table('payment_mode')->where('id', $payment_name['payment_mode'])->get()->getRowArray();
					if($payment_mode_details['pay_key'] == 'rhb_qr'){
						$response_data = json_decode($payment_name['response_data']);
						$trans_details['Reference No:'] = $response_data->referenceNo;
					}
				}
				$data['trans_details'] = $trans_details;
				$data['settings'] = $settings;
				
				//echo $this->db->getLastQuery();
				//echo "<pre>"; print_r($data); exit();
				echo view($view_file, $data);
			}elseif($archanai_booking['payment_status'] == '1'){
				$archanai_payment_gateway_datas = $this->db->table('archanai_payment_gateway_datas')->where('archanai_booking_id', $arch_book_id)->get()->getRowArray();
				if(!empty($archanai_payment_gateway_datas['reference_id'])){
					$reference_id = $archanai_payment_gateway_datas['reference_id'];
					$result_data = $this->initiatePayment_response($reference_id);
					$response_data = json_decode($result_data, true);
					$payment_gateway_up_data = array();
					$payment_gateway_up_data['response_data'] = $result_data;
					$this->db->table('archanai_payment_gateway_datas')->where('id', $archanai_payment_gateway_datas['id'])->update($payment_gateway_up_data);
					if(!empty($response_data['status'])){
						if($response_data['status'] == 'completed'){
							$archanai_booking_up_data = array();
							$archanai_booking_up_data['payment_status'] = 2;
							$this->db->table('archanai_booking')->where('id', $id)->update($archanai_booking_up_data);
							$this->account_migration($id);
							$tmpid = $this->session->get('profile_id');
							$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

							$data['booking'] = $this->db->table('archanai_booking_details', 'archanai', 'archanai_booking_rasi', 'rasi', 'natchathram')
								->join('archanai', 'archanai.id = archanai_booking_details.archanai_id', 'left')
								->join('archanai_diety', 'archanai_diety.id = archanai_booking_details.diety_id', 'left')
								->where('archanai_booking_details.archanai_booking_id', $id)
								->select('archanai.*, archanai_diety.name as diety_name,archanai_diety.name_tamil as diety_tamil')
								->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
								->get()
								->getResultArray();
								
							$data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
										->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
										->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
										->where('archanai_booking_rasi.archanai_booking_id', $id )
										->select('archanai_booking_rasi.*')
										->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
										->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
										->get()
										->getResultArray();
							$data['vehicles'] = $this->db->table('archanai_booking_vehicle')
										->where('archanai_booking_vehicle.archanai_booking_id', $id )
										->select('archanai_booking_vehicle.*')
										->get()
										->getResultArray();
							$settings_arr = $this->db->table('settings')->where('type', 1)->get()->getResultArray();
							$settings = array();
							if(count($settings_arr) > 0){
								foreach($settings_arr as $val){
									$settings[$val['setting_name']] = $val['setting_value'];
								}
							}
							$data['settings'] = $settings;
							echo view($view_file, $data);
						}else{
							$archanai_booking_up_data = array();
							$archanai_booking_up_data['payment_status'] = 3;
							$this->db->table('archanai_booking')->where('id', $id)->update($archanai_booking_up_data);
							redirect()->to("/cancelled_booking");
							exit;
						}
					}
				}else{
					redirect()->to("/cancelled_booking");
					exit;
				}
			}
		}else{
			$tmpid = $this->session->get('profile_id');
			$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();

			$data['booking'] = $this->db->table('archanai_booking_details', 'archanai', 'archanai_booking_rasi', 'rasi', 'natchathram')
				->join('archanai', 'archanai.id = archanai_booking_details.archanai_id', 'left')
				->join('archanai_diety', 'archanai_diety.id = archanai_booking_details.diety_id', 'left')
				->where('archanai_booking_details.archanai_booking_id', $id)
				->select('archanai.*,archanai_diety.name as diety_name,archanai_diety.name_tamil as diety_tamil')
				->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
				->get()
				->getResultArray();
				
			$data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
						->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
						->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
						->where('archanai_booking_rasi.archanai_booking_id', $id )
						->select('archanai_booking_rasi.*')
						->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
						->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
						->get()
						->getResultArray();
						//echo $this->db->getLastQuery();
			//echo "<pre>"; print_r($data); exit();
			$data['vehicles'] = $this->db->table('archanai_booking_vehicle')
										->where('archanai_booking_vehicle.archanai_booking_id', $id )
										->select('archanai_booking_vehicle.*')
										->get()
										->getResultArray();
			echo view($view_file, $data);
		}
	 }
     public function print_booking_sep($arch_book_id){
		 
	 	$id = $this->request->uri->getSegment(3);

		$data['qry1'] = $this->db->table('archanai_booking')->where('id', $id)->get()->getRowArray();
		//$data['qry2'] = $this->db->table('archanai_booking_details')->where('archanai_booking_id', $id)->get()->getResultArray();
		//echo "<pre>"; print_r($id); exit();
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();

		$data['booking'] = $this->db->table('archanai_booking_details', 'archanai')
					->join('archanai', 'archanai.id = archanai_booking_details.archanai_id')
					->where('archanai_booking_details.archanai_booking_id', $id )
					->select('archanai.*')
					->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
					->get()
					->getResultArray();
					//echo $this->db->getLastQuery();
		//echo "<pre>"; print_r($data); exit();
		// $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
		// $data['qrcdoee'] = qrcode_generation($id,$url, 95, 95);
		echo view('frontend/archanai/print_sep', $data);
	 }
	public function cancelled_booking() {
	    echo view('frontend/layout/header');
        echo view('frontend/archanai_new/cancelled_booking');
        echo view('frontend/layout/footer');
    }
	public function reprint_booking($id) {
		$data['qry1'] = $archanai_booking = $this->db->table('archanai_booking')->where('id', $id)->get()->getRowArray();
		$view_file = 'frontend/archanai/print';
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		$data['booking'] = $this->db->table('archanai_booking_details', 'archanai', 'archanai_booking_rasi', 'rasi', 'natchathram')
					->join('archanai', 'archanai.id = archanai_booking_details.archanai_id', 'left')
					->where('archanai_booking_details.archanai_booking_id', $id )
					->select('archanai.*')
					->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
					->get()
					->getResultArray();
		$data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
					->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
					->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
					->where('archanai_booking_rasi.archanai_booking_id', $id )
					->select('archanai_booking_rasi.*')
					->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
					->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
					->get()
					->getResultArray();
		$data['vehicles'] = $this->db->table('archanai_booking_vehicle')
					->where('archanai_booking_vehicle.archanai_booking_id', $id )
					->select('archanai_booking_vehicle.*')
					->get()
					->getResultArray();
		// $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
		// $data['qrcdoee'] = qrcode_generation($id,$url, 95, 95);
		echo view($view_file, $data);
	}
	public function new_print_booking($id) {
		$data['qry1'] = $archanai_booking = $this->db->table('archanai_booking')->where('id', $id)->get()->getRowArray();
		$view_file = 'frontend/archanai/new_archanai_print';
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		$data['booking'] = $this->db->table('archanai_booking_details', 'archanai', 'archanai_booking_rasi', 'rasi', 'natchathram')
					->join('archanai', 'archanai.id = archanai_booking_details.archanai_id', 'left')
					->where('archanai_booking_details.archanai_booking_id', $id )
					->select('archanai.*')
					->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
					->get()
					->getResultArray();
		$data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
					->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
					->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
					->where('archanai_booking_rasi.archanai_booking_id', $id )
					->select('archanai_booking_rasi.*')
					->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
					->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
					->get()
					->getResultArray();
		// $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
		// $data['qrcdoee'] = qrcode_generation($id,$url, 95, 95);
		echo view($view_file, $data);
	}
	public function payment_check(){
		$data = [];
		if (!empty($_REQUEST['archanai_booking_id'])) {
			$booking_id = $_REQUEST['archanai_booking_id'];
			$user_id = $_SESSION['log_id_frend'];

			// Check if booking exists
			$archanai_cnt = $this->db->table('archanai_booking')
				->where('id', $booking_id)
				->countAllResults(false); // false to NOT reset query builder to allow reuse if needed

			if ($archanai_cnt > 0) {
				try {
					// Get booking record
					$archanai_booking = $this->db->table('archanai_booking')
						->where('id', $booking_id)
						->get()
						->getRow();

					// Get payment gateway data
					$archanai_payment_gateway_datas = $this->db->table('archanai_payment_gateway_datas')->select('archanai_payment_gateway_datas.*, payment_mode.pay_key')->join('payment_mode', 'payment_mode.id = archanai_payment_gateway_datas.payment_mode', 'left')->where('archanai_payment_gateway_datas.archanai_booking_id', $booking_id)->get()->getRowArray();
					if ($archanai_booking->payment_status == 1) {
							if($archanai_payment_gateway_datas['pay_key'] == 'rhb_qr'){
								$rtn = $this->initiate_rhb_qr($booking_id, $archanai_booking, $archanai_payment_gateway_datas);
								if($rtn['status'] == 'pending'){
									$data = array(
										'status' => true,
										'pay_status' => false,
										'order_status' => 'pending',
										'org_msg' => $rtn['org_msg'],
										'error_msg' => "Transaction is still pending",
									);
									return json_encode($data);
								}elseif($rtn['status'] == 'success'){
									$data = array(
										'status' => true,
										'pay_status' => true,
										'order_status' => 'success',
										'org_msg' => $rtn['org_msg'],
										'error_msg' => "Thank you for using SMMDT Self Kiosk",
									);
									return json_encode($data);
								}elseif($rtn['status'] == 'failed'){
									$data = array(
										'status' => false,
										'pay_status' => false,
										'order_status' => 'failed',
										'org_msg' => $rtn['org_msg'],
										'error_msg' => "We’re sorry! your payment is failed. Kindly try again.",
									);
									return json_encode($data);
								}else{
									$data = array(
										'status' => false,
										'pay_status' => false,
										'order_status' => 'unidentify',
										'org_msg' => $rtn['org_msg'],
										'error_msg' => "We’re sorry! your payment didn’t went through, kindly try again. If payment has been deducted but Archanai didn’t print. Kindly contact the Bank or Payment Gateway",
									);
									return json_encode($data);
								}
							}else{
								$data = [
									'status' => true,
									'pay_status' => false,
									'order_status' => 'failed',
									'org_msg' => 'Invalid Payment',
									'error_msg' => "Invalid Payment",
								];
								return json_encode($data);
							}
					}elseif ($archanai_booking->payment_status == 3) {
						$data = [
							'status' => true,
							'pay_status' => false,
							'order_status' => 'failed',
							'org_msg' => 'Transaction Failed',
							'error_msg' => "We’re sorry! your payment is failed. Kindly try again.",
						];
						return json_encode($data);
					} else {
						$data = [
							'status' => true,
							'pay_status' => true,
							'order_status' => 'success',
							'org_msg' => 'Transaction Successful',
							'error_msg' => "Thank you for using SMMDT Self Kiosk",
						];
						return json_encode($data);
					}
				}catch (\Exception $e) {
					$data = [
						'status' => false,
						'pay_status' => false,
						'error_msg' => $e->getMessage(),
					];
					return json_encode($data);
				}
			} else {
				$data = [
					'status' => false,
					'pay_status' => false,
					'org_msg' => 'Transaction Failed',
					'error_msg' => "Invalid Archani.",
				];
				return json_encode($data);
			}
		} else {
			$data = [
				'status' => false,
				'pay_status' => false,
				'org_msg' => 'Transaction Failed',
				'error_msg' => "Invalid Archani.",
			];
			return json_encode($data);
		}
	}
	public function initiate_rhb_qr($arch_book_id, $archanai_booking, $archanai_payment_gateway_datas){
		$request_data = json_decode($archanai_payment_gateway_datas['request_data']);
		$payment_gateway_datas_id = $archanai_payment_gateway_datas['id'];
		$rtn = [];

		if (!empty($request_data->billNumber) && !empty($request_data->transactionReference)) {
			$merchant_id = config('Variables')->rhb_userId;  // Adjust if needed

			$json_data = [
				'billNumber'   => $request_data->billNumber,
				'userId'       => RHB_USERID,
				'referenceNo'  => $request_data->transactionReference,
			];

			$url = 'https://dnqr.synexisasia.com/v1/qr/status';

			// Call your common_repository method to get JSON response
			$response = $this->common_model->getJson($url, $json_data);


			// Update archanai_payment_gateway_datas table with response_data
			$this->db->table('archanai_payment_gateway_datas')
			   ->where('id', $payment_gateway_datas_id)
			   ->update(['response_data' => $response]);
			$response_data = json_decode($response);

			if (!empty($response_data->paymentStatus)) {
				if ($response_data->paymentStatus === 'FOUND') {
					$rtn['status'] = 'success';

					// Update archanai_booking payment_status = 2
					$this->db->table('archanai_booking')
					   ->where('id', $arch_book_id)
					   ->update(['payment_status' => 2]);

					// Call migration functions
					$this->account_migration($arch_book_id);
					// $this->commission_migration($arch_book_id);

					$rtn['org_msg'] = 'Transaction Successful';
				} else {
					$rtn['status'] = 'pending';
					$rtn['org_msg'] = 'Transaction Pending';
				}
			} else {
				$rtn['status'] = 'unidentify';
				$rtn['org_msg'] = 'Server Down';
			}
		} else {
			$rtn['status'] = 'unidentify';
			$rtn['org_msg'] = 'Server Down';
		}

		return $rtn;
	}
	
	public function cancel_booking(){
		$data = [];

		if (!empty($_REQUEST['archanai_booking_id'])) {
			$booking_id = $_REQUEST['archanai_booking_id'];
			$user_id = $_SESSION['log_id_frend'];

			// Check if booking exists
			$archanai_cnt = $this->db->table('archanai_booking')
				->where('id', $booking_id)
				->countAllResults(false); // false to NOT reset query builder to allow reuse if needed

			if ($archanai_cnt > 0) {
				try {
					// Get booking record
					$archanai_booking = $this->db->table('archanai_booking')
						->where('id', $booking_id)
						->get()
						->getRow();

					// Get payment gateway data
					$archanai_payment_gateway_datas = $this->db->table('archanai_payment_gateway_datas')
						->where('archanai_booking_id', $booking_id)
						->get()
						->getRowArray();

					if ($archanai_booking->payment_status == 1) {
						if (!empty($archanai_payment_gateway_datas) && $archanai_payment_gateway_datas['pay_method'] === 'rhb_qr') {
							// Call your initiate_rhb_qr method (assumed exists in this class)
							$rtn = $this->initiate_rhb_qr($booking_id, $archanai_booking, $archanai_payment_gateway_datas);

							if ($rtn['status'] == 'success') {
								$data = [
									'status' => true,
									'pay_status' => true,
									'order_status' => 'success',
									'org_msg' => $rtn['org_msg'],
									'error_msg' => "Thank you for using SMMDT Self Kiosk",
								];
								return json_encode($data);
							} else {
								// Update payment_status to 3 = failed
								$this->db->table('archanai_booking')->where('id', $booking_id)->update(['payment_status' => 3]);

								$data = [
									'status' => true,
									'pay_status' => false,
									'order_status' => 'failed',
									'org_msg' => 'Transaction Failed',
									'error_msg' => "We’re sorry! your payment is failed. Kindly try again.",
								];
								return json_encode($data);
							}
						} else {
							// Update payment_status to 3 = failed
							$this->db->table('archanai_booking')->where('id', $booking_id)->update(['payment_status' => 3]);

							$data = [
								'status' => true,
								'pay_status' => false,
								'order_status' => 'failed',
								'org_msg' => 'Transaction Failed',
								'error_msg' => "We’re sorry! your payment is failed. Kindly try again.",
							];
							return json_encode($data);
						}
					} elseif ($archanai_booking->payment_status == 3) {
						$data = [
							'status' => true,
							'pay_status' => false,
							'order_status' => 'failed',
							'org_msg' => 'Transaction Failed',
							'error_msg' => "We’re sorry! your payment is failed. Kindly try again.",
						];
						return json_encode($data);
					} else {
						$data = [
							'status' => true,
							'pay_status' => true,
							'order_status' => 'success',
							'org_msg' => 'Transaction Successful',
							'error_msg' => "Thank you for using SMMDT Self Kiosk",
						];
						return json_encode($data);
					}
				} catch (\Exception $e) {
					$data = [
						'status' => false,
						'pay_status' => false,
						'error_msg' => $e->getMessage(),
					];
					return json_encode($data);
				}
			} else {
				$data = [
					'status' => false,
					'pay_status' => false,
					'org_msg' => 'Transaction Failed',
					'error_msg' => "Invalid Archani.",
				];
				return json_encode($data);
			}
		} else {
			$data = [
				'status' => false,
				'pay_status' => false,
				'org_msg' => 'Transaction Failed',
				'error_msg' => "Invalid Archani.",
			];
			return json_encode($data);
		}
	}


}
