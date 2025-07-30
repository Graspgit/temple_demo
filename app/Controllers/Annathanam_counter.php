<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use App\Models\MemberPaymentGatewayData;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\RequestModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Annathanam_counter extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		helper('common_helper');
		$this->model = new PermissionModel();
		if (($this->session->get('log_id_frend')) == false) {
			$data['dn_msg'] = 'Please Login';
			return redirect()->to('/member_login');
		}
	}

	public function index()
	{
		$data['phone_codes'] = $this->db->table("phone_code")->orderBy('dailing_code', 'ASC')->get()->getResultArray();
		$data['packages'] = $this->db->table('annathanam_packages')->select('id, name_eng, name_tamil, amount, view')->where('status', 1)->get()->getResultArray();
		$data['payment_mode'] = $this->db->table('payment_mode')->where("paid_through", "COUNTER")->where("annathanam", 1)->where('status', 1)->orderby('menu_order', "ASC")->get()->getResultArray();

		$yr=date('Y');
		$mon=date('m');
		$query = $this->db->query("SELECT ref_no FROM annathanam_new where id=(select max(id) from annathanam_new where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
		$data['bill_no']= 'AT' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['ref_no'],-5))+1)));
		$query = $this->db->query("SELECT annathanam FROM terms_conditions ");
		$result = $query->getRowArray();
		$data['terms'] = json_decode($result['annathanam'], true);
		$settings = $this->db->table('settings')->where('type', 4)->get()->getResultArray();
		$setting_array = array();
		if(count($settings) > 0){
			foreach ($settings as $item) {
				$setting_array[$item['setting_name']] = $item['setting_value'];
			}
		}
		$data['setting'] = $setting_array;

		echo view('frontend/layout/header');
		echo view('frontend/annathanam/index', $data);
		//echo view('frontend/layout/footer');
	}
	
	public function view_annathanam() {

		if(!$this->model->permission_validate('annathanam', 'view')){
			return redirect()->to(base_url().'/dashboard');}
		$id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('annathanam_new')->where('id', $id)->get()->getRowArray();
		$data['phone_codes'] = $this->db->table("phone_code")->orderBy('dailing_code', 'ASC')->get()->getResultArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->get()->getResultArray();
		$data['packages'] = $this->db->table('annathanam_packages')->where('status', 1)->get()->getResultArray();
		$data['items'] = $this->db->table('annathanam_booked_items')
					->select('annathanam_items.name_eng, annathanam_items.name_tamil')
					->join('annathanam_items', 'annathanam_booked_items.item_id = annathanam_items.id')
					->where('annathanam_booked_items.annathanam_id', $id)->get()->getResultArray();

		$data['addon_items'] = $this->db->table('annathanam_booked_addon')
					->select('annathanam_items.name_eng, annathanam_items.name_tamil, annathanam_booked_addon.item_id, annathanam_booked_addon.item_amount, annathanam_booked_addon.item_total_amount')
					->join('annathanam_items', 'annathanam_booked_addon.item_id = annathanam_items.id')
					->where('annathanam_booked_addon.annathanam_id', $id)->get()->getResultArray();

		$data['view'] = true;
		echo view('frontend/layout/header');
		echo view('frontend/annathanam/index', $data);
	}

	public function save_annathanam(){
		// echo "<pre>";
		// print_r($_POST);
		// exit;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$this->db->transStart();
		try{
			$data['name'] = $_POST['name'];
			$data['phone_code'] = $_POST['phone_code'];
			$data['phone_no'] = $_POST['phone_no'];
			$mobile = $_POST['phone_code'] . $_POST['phone_no'];
			$data['slot_time'] = $_POST['time'];
			if(!empty($_POST['package_id'] == 1)) $data['is_special'] = 1;
			else $data['is_special'] = 0;
			$data['package_id'] = $package_id = $_POST['package_id'];
			$data['dob'] = $_POST['dob'];
			$tot_amt = $_POST['total_amount'];
			$sub_total = $tot_amt;
			if (!empty($_POST['discount_amount'])) {
				$data['discount_amount'] = $_POST['discount_amount'];
				$sub_total += $_POST['discount_amount'];
			}
			$data['sub_total'] = $sub_total;
			$data['no_of_pax'] = $_POST['no_of_pax'];
			$data['amount'] = $_POST['amount'];
			$data['total_amount'] = $tot_amt;
			$data['payment_type'] = !empty($_POST['payment_type']) ? $_POST['payment_type']: 'full';
			$data['booking_through'] = 'COUNTER';
			$time_session = ($data['slot_time'] == 'Breakfast') ? "AM" : "PM";
			$data['serve_time'] = $_POST['hour'] . ':' . $_POST['minute'] .' '. $time_session;
			
			$yr= date('Y', strtotime($_POST['date']));
			$mon= date('m', strtotime($_POST['date']));
			$query = $this->db->query("SELECT ref_no FROM annathanam_new WHERE id=(SELECT max(id) FROM annathanam_new WHERE year(date)='". $yr ."' AND month(date)='". $mon ."')")->getRowArray();
			$data['ref_no'] = 'AT' . date('y', strtotime($_POST['date'])) . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
			$data['date'] = date('Y-m-d', strtotime($_POST['date']));
			$data['event_date'] = date('Y-m-d', strtotime($_POST['event_date']));
			$data['added_by'] = $this->session->get('log_id_frend');
			$data['created'] = date('Y-m-d H:i:s');
			
			$data['payment_mode'] = $pay_id = $_POST['pay_method'];
			$payment_mode = $this->db->table('payment_mode')->where("id", $pay_id)->get()->getRowArray();
			$pay_method = $payment_mode['name'];
			$data['payment_status'] = !empty($pay_method) ? 2 : 1;
	
			$res = $this->db->table('annathanam_new')->insert($data);
			$annathanam_id = $this->db->insertID();

			$devotee_id = $this->devotee_save($data);
			if (!empty($devotee_id)){
				$activity_details = json_encode([
					'type' => 'Annathanam Booked',
					'booking_id' => $ins_id
				]);
				$this->save_activity_log($devotee_id, 4, $activity_details);
			}
		
			if(!empty($package_id)){
				$items = $this->db->table('annathanam_package_items')->join('annathanam_items', 'annathanam_package_items.item_id = annathanam_items.id')
							->where('annathanam_package_items.package_id', $package_id)->where('annathanam_package_items.add_on', 0)->get()->getResultArray();				
										
				foreach ($items as $item) {
					$data_item = array(
						'annathanam_id' => $annathanam_id,
						'package_id' => $data['package_id'],
						'item_id' => $item['item_id'],
						'add_on' => $item['add_on']
					);
					$res = $this->db->table('annathanam_booked_items')->insert($data_item);

					$settings = $this->db->table('settings')->where('type', 4)->where('setting_name', 'enable_madapalli')->get()->getRowArray();
					if ($settings['setting_value'] == 1) {
						$anna_set = $this->db->table('annathanam_items')->where('id', $item['item_id'])->get()->getRowArray();
						$madapalli_details['date'] = $_POST['event_date'];
						$madapalli_details['type'] = 2;
						$madapalli_details['booking_id'] = $annathanam_id;
						$madapalli_details['product_id'] = $item['item_id'];
						$madapalli_details['quantity'] = $data['no_of_pax'];
						$madapalli_details['amount'] = 0;
						$madapalli_details['session'] = $_POST['time'];
						$madapalli_details['serve_time'] = $data['serve_time'];
						$madapalli_details['customer_name'] = $_POST['name'];
						$madapalli_details['pro_name_eng'] = $anna_set['name_eng'];
						$madapalli_details['customer_mobile'] = $mobile;
						$madapalli_details['status'] = 0;
						$madapalli_details['created_by'] = $this->session->get('log_id_frend');
						$madapalli_details['created_at'] = date('Y-m-d H:i:s');
						$madapalli_details['updated_at'] = date('Y-m-d H:i:s');
						$res_m1 = $this->db->table('madapalli_booking_details')->insert($madapalli_details);
						
						if ($res_m1){
							$preparation_details = $this->db->table('madapalli_preparation_details')->where('date', $_POST['event_date'])->where('type', 2)->get()->getResultArray();
							$product_found1 = false;

							foreach ($preparation_details as $detail) {
								if ($detail['product_id'] == $madapalli_details['product_id'] && $detail['session'] == $madapalli_details['session'] && $detail['type'] == $madapalli_details['type']) {
									$new_quantity = $detail['quantity'] + $madapalli_details['quantity'];
									$update_data1 = [
										'quantity' => $new_quantity,
										'updated_at' => date('Y-m-d H:i:s')
									];
									$this->db->table('madapalli_preparation_details')->where('id', $detail['id'])->update($update_data1);
									$product_found1 = true;
									break;
								}
							}

							if (!$product_found1) {
								$insert_data1 = [
									'date' => $_POST['event_date'],
									'type' => 2,
									'session' => $_POST['time'],
									'product_id' => $madapalli_details['product_id'],
									'pro_name_eng' => $anna_set['name_eng'],
									'pro_name_tamil' => $anna_set['name_tamil'],
									'quantity' => $madapalli_details['quantity'],
									'status' => 0,
									'created_by' => $this->session->get('log_id_frend'),
									'created_at' => date('Y-m-d H:i:s'),
									'updated_at' => date('Y-m-d H:i:s')
								];
								$res_n = $this->db->table('madapalli_preparation_details')->insert($insert_data1);
							}
						}
					}
				}
			}
	
			if(!empty($_POST['add_on'])){
				foreach ($_POST['add_on'] as $addon_item) {
					$data_addon_item = array(
						'annathanam_id' => $annathanam_id,
						'package_id' => $data['package_id'],
						'item_id' => $addon_item['id'],
						'quantity' => $addon_item['quantity'],
						'item_amount' => $addon_item['amount'],
						'item_total_amount' => $addon_item['total_amount'],
						'add_on' => 1
					);
					$this->db->table('annathanam_booked_addon')->insert($data_addon_item);

					$settings = $this->db->table('settings')->where('type', 4)->where('setting_name', 'enable_madapalli')->get()->getRowArray();			
					if ($settings['setting_value'] == 1) {
						
						$anna_set = $this->db->table('annathanam_items')->where('id', $addon_item['id'])->get()->getRowArray();
						$madapalli_details['date'] = $_POST['event_date'];
						$madapalli_details['type'] = 2;
						$madapalli_details['booking_id'] = $annathanam_id;
						$madapalli_details['product_id'] = $addon_item['id'];
						$madapalli_details['quantity'] = $addon_item['quantity'];
						$madapalli_details['amount'] = $addon_item['total_amount'];
						$madapalli_details['session'] = $_POST['time'];
						$madapalli_details['serve_time'] = $data['serve_time'];
						$madapalli_details['customer_name'] = $_POST['name'];
						$madapalli_details['pro_name_eng'] = $anna_set['name_eng'];
						$madapalli_details['customer_mobile'] = $mobile;
						$madapalli_details['status'] = 0;
						$madapalli_details['created_by'] = $this->session->get('log_id_frend');
						$madapalli_details['created_at'] = date('Y-m-d H:i:s');
						$madapalli_details['updated_at'] = date('Y-m-d H:i:s');
						$res_m2 = $this->db->table('madapalli_booking_details')->insert($madapalli_details);

						if ($res_m2){
							$preparation_details = $this->db->table('madapalli_preparation_details')->where('date', $_POST['event_date'])->where('type', 2)->get()->getResultArray();
							$product_found2 = false;

							foreach ($preparation_details as $detail) {
								if ($detail['product_id'] == $madapalli_details['product_id'] && $detail['session'] == $madapalli_details['session'] && $detail['type'] == $madapalli_details['type']) {
									$new_quantity = $detail['quantity'] + $madapalli_details['quantity'];
									$update_data2 = [
										'quantity' => $new_quantity,
										'updated_at' => date('Y-m-d H:i:s')
									];
									$this->db->table('madapalli_preparation_details')->where('id', $detail['id'])->update($update_data2);
									$product_found2 = true;
									break;
								}
							}

							if (!$product_found2) {
								$insert_data2 = [
									'date' => $_POST['event_date'],
									'type' => 2,
									'session' => $_POST['time'],
									'product_id' => $madapalli_details['product_id'],
									'pro_name_eng' => $anna_set['name_eng'],
									'pro_name_tamil' => $anna_set['name_tamil'],
									'quantity' => $madapalli_details['quantity'],
									'status' => 0,
									'created_by' => $this->session->get('log_id_frend'),
									'created_at' => date('Y-m-d H:i:s'),
									'updated_at' => date('Y-m-d H:i:s')
								];
								$this->db->table('madapalli_preparation_details')->insert($insert_data2);
							}
						}
					}
				}
			}

			if(!empty($_POST['special'])){
				foreach ($_POST['special'] as $item) {
					$data_special_item = array(
						'annathanam_id' => $annathanam_id,
						'type_id' => $item['type_id'],
						'item_id' => $item['id'],
						'quantity' => $item['quantity'],
						'item_amount' => $item['amount'],
						'total_amount' => $item['total_amount']
					);
					$this->db->table('annathanam_booked_special')->insert($data_special_item);

					$settings = $this->db->table('settings')->where('type', 4)->where('setting_name', 'enable_madapalli')->get()->getRowArray();
					if ($settings['setting_value'] == 1) {
						$anna_set = $this->db->table('annathanam_special_items')->where('id', $item['id'])->get()->getRowArray();
						$madapalli_details['date'] = $_POST['event_date'];
						$madapalli_details['type'] = 2;
						$madapalli_details['booking_id'] = $annathanam_id;
						$madapalli_details['product_id'] = $item['id'];
						$madapalli_details['quantity'] = $item['quantity'];
						$madapalli_details['amount'] = $item['total_amount'];
						$madapalli_details['session'] = $_POST['time'];
						$madapalli_details['customer_name'] = $_POST['name'];
						$madapalli_details['pro_name_eng'] = $anna_set['name_eng'];
						$madapalli_details['customer_mobile'] = $mobile;
						$madapalli_details['status'] = 0;
						$madapalli_details['created_by'] = $this->session->get('log_id_frend');
						$madapalli_details['created_at'] = date('Y-m-d H:i:s');
						$madapalli_details['updated_at'] = date('Y-m-d H:i:s');
						$res_m1 = $this->db->table('madapalli_booking_details')->insert($madapalli_details);

						if ($res_m1){
							$preparation_details = $this->db->table('madapalli_preparation_details')->where('date', $_POST['event_date'])->where('type', 2)->get()->getResultArray();
							$product_found1 = false;

							foreach ($preparation_details as $detail) {
								if ($detail['product_id'] == $madapalli_details['product_id'] && $detail['session'] == $madapalli_details['session'] && $detail['type'] == $madapalli_details['type']) {
									$new_quantity = $detail['quantity'] + $madapalli_details['quantity'];
									$update_data1 = [
										'quantity' => $new_quantity,
										'updated_at' => date('Y-m-d H:i:s')
									];
									$this->db->table('madapalli_preparation_details')->where('id', $detail['id'])->update($update_data1);
									$product_found1 = true;
									break;
								}
							}

							if (!$product_found1) {
								$insert_data1 = [
									'date' => $_POST['event_date'],
									'type' => 2,
									'session' => $_POST['time'],
									'product_id' => $madapalli_details['product_id'],
									'pro_name_eng' => $anna_set['name_eng'],
									'pro_name_tamil' => $anna_set['name_tamil'],
									'quantity' => $madapalli_details['quantity'],
									'status' => 0,
									'created_by' => $this->session->get('log_id_frend'),
									'created_at' => date('Y-m-d H:i:s'),
									'updated_at' => date('Y-m-d H:i:s')
								];
								$this->db->table('madapalli_preparation_details')->insert($insert_data1);
							}
						}
					}
				}
			}

			if(!empty($_POST['addi_item'])){
				$addon = $this->db->table('annathanam_items')->where('add_on', 2)->get()->getRowArray();
				foreach ($_POST['addi_item'] as $item) {
					$data_addi_item = array(
						'annathanam_id' => $annathanam_id,
						'name' => $item['name'],
						'quantity' => $item['quantity'],
						'amount' => $item['amount'],
						'total_amount' => $item['total_amount']
					);
					$this->db->table('annathanam_booked_additional')->insert($data_addi_item);

					$settings = $this->db->table('settings')->where('type', 4)->where('setting_name', 'enable_madapalli')->get()->getRowArray();			
					if ($settings['setting_value'] == 1) {
						
						$madapalli_details['date'] = $_POST['event_date'];
						$madapalli_details['type'] = 2;
						$madapalli_details['is_additional'] = 1;
						$madapalli_details['booking_id'] = $annathanam_id;
						$madapalli_details['product_id'] = $addon['id'];
						$madapalli_details['quantity'] = $item['quantity'];
						$madapalli_details['amount'] = $item['total_amount'];
						$madapalli_details['session'] = $_POST['time'];
						$madapalli_details['serve_time'] = $data['serve_time'];
						$madapalli_details['customer_name'] = $_POST['name'];
						$madapalli_details['pro_name_eng'] = $item['name'];
						$madapalli_details['customer_mobile'] = $mobile;
						$madapalli_details['status'] = 0;
						$madapalli_details['created_by'] = $this->session->get('log_id_frend');
						$madapalli_details['created_at'] = date('Y-m-d H:i:s');
						$madapalli_details['updated_at'] = date('Y-m-d H:i:s');
						$res_m2 = $this->db->table('madapalli_booking_details')->insert($madapalli_details);

						if ($res_m2){
							$insert_data2 = [
								'date' => $_POST['event_date'],
								'type' => 2,
								'is_additional' => 1,
								'session' => $_POST['time'],
								'product_id' => $madapalli_details['product_id'],
								'pro_name_eng' => $item['name'],
								'pro_name_tamil' => $item['name'],
								'quantity' => $item['quantity'],
								'status' => 0,
								'created_by' => $this->session->get('log_id_frend'),
								'created_at' => date('Y-m-d H:i:s'),
								'updated_at' => date('Y-m-d H:i:s')
							];
							$this->db->table('madapalli_preparation_details')->insert($insert_data2);
						}
					}
				}
			}

			$pay_details = array();
			if($pay_id){
				$payment_mode_details = $this->db->table("payment_mode")->where('id', $pay_id)->get()->getRowArray();
				$pay_details['payment_key'] = $pay_method;
				$pay_details['annathanam_id'] = $annathanam_id;
				$pay_details['is_repayment'] = 0;
				$pay_details['payment_mode_id'] = $pay_id;
				$pay_details['paid_through'] = 'COUNTER';
				$pay_details['pay_status'] = !empty($pay_method) ? 2 : 1;
				$pay_details['payment_mode_title'] = $payment_mode_details['name'];
				$pay_details['booking_ref_no'] = $data['ref_no'];
				if($data['payment_type'] == 'partial') $pay_details['amount'] = $_POST['paid_amount'];
				else $pay_details['amount'] = $tot_amt;
				if(empty($pay_details['amount'])){
					$this->db->transRollback();
					echo 'Invalid Amount';
					$redirect_url = base_url("/annathanam_counter");
					echo <<<PPR
						<script>
						setTimeout(function(){
							location.href = '$redirect_url';
						}, 3500);
						</script>
					PPR;
					exit;
				}
				$pay_details['paid_date'] = date('Y-m-d');
				$this->requestmodel = new RequestModel();
				$ip = $this->requestmodel->getIpAddress();
				$pay_details['ip'] = $ip;
				if ($ip != 'unknown') {
					$ip_details = $this->requestmodel->getLocation($ip);
					$pay_details['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
					$pay_details['ip_details'] = json_encode($ip_details);
				}
				$res_3 = $this->db->table('annathanam_booked_pay_details')->insert($pay_details);

				if ($res_3) {
					$this->devotee_payment_details($devotee_id, $pay_details);
				}

				$booking_ref_data = array();
				$booking_ref_data['paid_amount'] = $pay_details['amount'];
				if($data['payment_type'] == 'partial'){ 
					$booking_ref_data['payment_status'] = 1;
				}else {
					$booking_ref_data['payment_status'] = 2;
				}
				$booking_ref_data['booking_status'] = 1;
				$this->db->table("annathanam_new")->where('id', $annathanam_id)->update($booking_ref_data);

				$payment_gateway_data = array();
				$payment_gateway_data['annathanam_booking_id'] = $annathanam_id;
				$payment_gateway_data['pay_method'] = $pay_method;
				$this->db->table('annathanam_payment_gateway_datas')->insert($payment_gateway_data);

			}else{
				$this->db->transRollback();
				echo 'Invalid Payment';
				$redirect_url = base_url("/annathanam_counter");
				echo <<<PPR
					<script>
					setTimeout(function(){
						location.href = '$redirect_url';
					}, 3500);
					</script>
				PPR;
				exit;
			}

			$this->account_migration($annathanam_id);
			$this->db->transComplete();
			
			$msg_data['id'] = $annathanam_id;
			$msg_data['succ'] = 'Annathanam added successfully';
			
		}catch (Exception $e) {
			$this->db->transRollback();
			echo $e->getMessage();
			$redirect_url = base_url("/annathanam_counter");
			echo <<<PPR
			<script>
			setTimeout(function(){
				location.href = '$redirect_url';
			}, 3500);
			</script>
			PPR;
		}
		echo json_encode($msg_data);
		exit;
	}

	public function devotee_payment_details($devotee_id, $pay_details) {
		if (!empty($devotee_id) && !empty($pay_details)) {

			$devotee_pay['devotee_id'] = $devotee_id;
			$devotee_pay['module_type'] = 3;
			$devotee_pay['booking_id'] = $pay_details['annathanam_id'];
			$devotee_pay['ref_no'] = $pay_details['booking_ref_no'];
			$devotee_pay['paid_date'] = $pay_details['paid_date'];
			$devotee_pay['is_repayment'] = $pay_details['is_repayment'];
			$devotee_pay['amount'] = $pay_details['amount'];
			$devotee_pay['payment_mode_id'] = $pay_details['payment_mode_id'];
			$devotee_pay['payment_mode_title'] = $pay_details['payment_mode_title'];
			$devotee_pay['pay_status'] = $pay_details['pay_status'];
			$devotee_pay['paid_through'] = 'COUNTER';
			$devotee_pay['created_by'] = $this->session->get('log_id_frend');
			$devotee_pay['created_at'] = date('Y-m-d H:i:s');

			$this->db->table('devotee_payment_details')->insert($devotee_pay);      
		}
	}

	public function devotee_save($data) {
		if (!empty($data['name']) && !empty($data['phone_code']) && !empty($data['phone_no'])) {
			$existing_devotee = $this->db->table('devotee_management')
										->where('phone_code', $data['phone_code'])
										->where('phone_number', $data['phone_no'])
										->get()
										->getRowArray();

			if ($existing_devotee) {
				$update_data = [];

				if (empty($existing_devotee['dob']) && !empty($data['dob']) ) {
					$update_data['dob'] = $data['dob'];
				}
				if (empty($existing_devotee['email']) && !empty($data['email_id'])) {
					$update_data['email'] = $data['email_id'];
				}
				if (empty($existing_devotee['ic_no']) && !empty($data['ic_no'])) {
					$update_data['ic_no'] = $data['ic_no'];
				}
				if (empty($existing_devotee['address']) && !empty($data['address'])) {
					$update_data['address'] = $data['address'];
				}

				if ($existing_devotee['is_member'] == 0) {
					$mobile = $data['phone_code'].$data['phone_no'];
					$member = $this->db->table('member')->where('mobile', $mobile)->get()->getRowArray();
					if ($member) {
						$update_data = [
						'is_member' => 1,
						'member_id' => $member['id']
						];
					}
				}

				if (!empty($update_data)) {
					$update_data['updated_by'] = $data['added_by'];
					$update_data['updated_at'] = date('Y-m-d H:i:s');
					$dvt_update = $this->db->table('devotee_management')->where('id', $existing_devotee['id'])->update($update_data);

					if ($dvt_update) {
						$updated_fields = [];

						if (isset($update_data['dob'])) {
							$updated_fields['dob'] = $data['dob'];
						}
						if (isset($update_data['email'])) {
							$updated_fields['email'] = $data['email_id'];
						}
						if (isset($update_data['address'])) {
							$updated_fields['address'] = $data['address'];
						}
						if (isset($update_data['ic_no'])) {
							$updated_fields['ic_no'] = $data['ic_no'];
						}

						$activity_details = json_encode([
							'type' => 'Devotee updated',
							'updated_fields' => $updated_fields 
						]);

						$this->save_activity_log($existing_devotee['id'], 2, $activity_details);
					}
				}
				return $existing_devotee['id'];

			} else {
				$new_devotee = [
					'name' => !empty($data['name']) ? $data['name'] : null,
					'dob' => !empty($data['dob']) ? $data['dob'] : null,
					'phone_code' => !empty($data['phone_code']) ? $data['phone_code'] : null,
					'phone_number' => !empty($data['phone_no']) ? $data['phone_no'] : null,
					'email' => !empty($data['email']) ? $data['email'] : null,
					'ic_no' => !empty($data['ic_no']) ? $data['ic_no'] : null,
					'address' => !empty($data['address']) ? $data['address'] : null,
					'user_module_tag' => 4,
					'added_through' => 'COUNTER', 
					'created_by' => $this->session->get('log_id_frend'), 
					'created_at' => date('Y-m-d H:i:s'),
					'ip' => $data['ip'], 
					'ip_location' => $data['ip_location'], 
					'ip_details' => $data['ip_details'], 
				];

				$mobile = $new_devotee['phone_code'].$new_devotee['phone_number'];
				$member = $this->db->table('member')->where('mobile', $mobile)->get()->getRowArray();
				if ($member) {
					$new_devotee = [
						'is_member' => 1,
						'member_id' => $member['id']
					];
				}

				$mgm_save = $this->db->table('devotee_management')->insert($new_devotee);
				$devotee_id = $this->db->insertID();

				if ($mgm_save) {
					$activity_details = json_encode([
						'type' => 'Devotee added',
						'name' => $new_devotee['name'],
						'phone' => $new_devotee['phone_code'] . $new_devotee['phone_number'],
						'dob' => !empty($new_devotee['dob']) ? $new_devotee['dob'] : null,
						'email' => !empty($new_devotee['email']) ? $new_devotee['email'] : null,
						'address' => !empty($new_devotee['address']) ? $new_devotee['address'] : null,
					]);
					$this->save_activity_log($devotee_id, 1, $activity_details);
				}
				return $devotee_id;
			}
		}
	}

	private function save_activity_log($devotee_id, $activity_type, $activity_fields = null) {
		$activity = array();
		$activity['devotee_id'] = $devotee_id;
		$activity['date'] = date('Y-m-d');
		$activity['time'] = date('H:i:s');  
		$activity['module_type'] = 4; 
		$activity['activity_type'] = $activity_type; 
		$activity['details'] = $activity_fields;
		$activity['added_through'] = 'COUNTER';
		$activity['created_by'] = $this->session->get('log_id_frend');
		$activity['created_at'] = date('Y-m-d H:i:s');

		$this->db->table('devotee_activity')->insert($activity);
	}

	public function get_devotee_details() {
		$phone_code = $_POST['code'];
		$phone_number = $_POST['number'];
		$dev_data = $this->db->table('devotee_management')->where('phone_code', $phone_code)->where('phone_number', $phone_number)->get()->getRowArray();
		
		  $msg_data['name'] = $dev_data['name'];
		  $msg_data['dob'] = $dev_data['dob'];
		echo json_encode($msg_data);
		exit();
	}

	public function get_items_by_package_id()
	{
		$package_id = $_POST['id'];
		$item_ids = $this->db->table('annathanam_package_items')->select('item_id')->where('package_id', $package_id)->get()->getResultArray();
		$item_ids = array_column($item_ids, 'item_id');

		$allItems = [];
		$items = []; 
		$addons = []; 

		if (!empty($item_ids)) {
			$allItems = $this->db->table('annathanam_items')->select('id, name_eng, name_tamil, description, amount, add_on')
						->whereIn('id', $item_ids)->where('status', 1)->get()->getResultArray();
						
			foreach ($allItems as $item) {
				if ($item['add_on'] == 0) {
					$items[] = $item; 
				} else if ($item['add_on'] == 1 || $item['add_on'] == 2) {
					$addons[] = $item; 
				}
			}
		}

		$response = [
			'items' => $items, 'addons' => $addons
		];

		echo json_encode($response);
	}

	public function get_annathanam_addon() {
		$addon_id = $_POST['id'];
		$get_result_details = $this->db->table("annathanam_items")->select('annathanam_items.*')->where("id", $addon_id)->get()->getResultArray();
		
		echo json_encode($get_result_details);
	}

	public function get_addon_name() {
		$id = $_POST['id'];
		$res = $this->db->table("annathanam_items")->where("id", $id)->get()->getRowArray();
		$data['name'] = $res['name_eng'] . '/' . $res['name_tamil'];
		$data['amount'] = $res['amount'];
		$data['description'] = $res['description'];
		echo json_encode($data);
	}

	public function get_special_items()
	{
		$result = [];
		$special_types = $this->db->table('annathanam_special_types')->select('id, name')->where('status', 1)->get()->getResultArray();
								
		foreach ($special_types as $type) {
			$type_id = $type['id'];
			$special_items = $this->db->table('annathanam_special_items')
									->select('id, name_eng, name_tamil, amount')
									->where('type_id', $type_id)
									->where('status', 1)
									->get()
									->getResultArray();

			foreach ($special_items as &$item) {
				$item['type_id'] = $type_id;  
			}

			$result[$type['name']] = [
				'type_id' => $type_id,
				'items' => $special_items
			];
		}

		$addon_items = $this->db->table('annathanam_items')
						->select('id, name_eng, name_tamil, amount, add_on')
						->whereIn('add_on', [1,2])
						->where('status', 1)
						->get()
						->getResultArray();

		$response = [
			'special' => $result,
			'addons' => $addon_items
		 ];

		echo json_encode($response);
	}

	public function get_addon_items()
	{
		$result = [];
		$special_types = $this->db->table('annathanam_items')->select('id, name_eng, name_tamil, amount, add_on')->where('status', 1)->get()->getResultArray();

		echo json_encode($special_types);
	}

	public function get_terms() {
		$name = $this->request->getPost('name');
	
		if (!$name) {
			return $this->response->setJSON(['success' => false]);
		}

		$query = $this->db->query("SELECT annathanam FROM terms_conditions");
		$result = $query->getRowArray();
		$terms = json_decode($result['annathanam'], true);

		$terms_html = '';
		foreach ($terms as $term) {
			$replaced_term = str_replace('[person_name]', $name, $term);
			$stripped_term = strip_tags($replaced_term);
			
			$terms_html .= '
			<div class="form-group">
				<label class="custom-checkbox">
					' . strip_tags($stripped_term) . '
					<input type="checkbox" class="term-checkbox" name="terms[]" value="' . strip_tags($stripped_term) . '">
					<span class="checkmark"></span>
				</label>
			</div>';
		}

		return $this->response->setJSON([
			'success' => true,
			'terms' => $terms_html
		]);
	}

	public function print_annathanam()
	{
		$id=  $this->request->uri->getSegment(3);
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		
	    $annathanam = $this->db->table('annathanam_new')
							->select('annathanam_new.*, annathanam_packages.name_eng, annathanam_packages.name_tamil, annathanam_packages.amount')
							->join('annathanam_packages', 'annathanam_packages.id = annathanam_new.package_id', 'left')
							->where('annathanam_new.id', $id)
							->get()
							->getRowArray();
		
		if($annathanam['is_special'] == 0){
			$data['items'] = $this->db->table('annathanam_booked_items')
										->select('annathanam_items.name_eng, annathanam_items.name_tamil')
										->join('annathanam_items', 'annathanam_booked_items.item_id = annathanam_items.id')
										->where('annathanam_booked_items.annathanam_id', $id)->get()->getResultArray();
		} else {
			$data['special_items'] = $this->db->table('annathanam_booked_special')
										->select('annathanam_special_items.*, annathanam_booked_special.quantity')
										->join('annathanam_special_items', 'annathanam_booked_special.item_id = annathanam_special_items.id')
										->where('annathanam_booked_special.annathanam_id', $id)->get()->getResultArray();
		}

		$data['data'] = $annathanam;
		$data['addon_items'] = $this->db->table('annathanam_booked_addon')
											->select('annathanam_items.*, annathanam_booked_addon.quantity')
											->join('annathanam_items', 'annathanam_booked_addon.item_id = annathanam_items.id')
											->where('annathanam_booked_addon.annathanam_id', $id)->get()->getResultArray();
								
		$data['additional'] = $this->db->table('annathanam_booked_additional')->where('annathanam_id', $id)->get()->getResultArray();
		
		$setting = $this->db->table('settings')->where('type', 4)->where('setting_name', 'enable_terms')->get()->getRowArray();
		if ($setting['setting_value'] == 1) {
			$terms_res = $this->db->table("terms_conditions")->get()->getRowArray();
			$terms = json_decode($terms_res['annathanam'], true);
			$data['terms'] = $terms;
		}
		
		echo view('annathanam_new/print_annathanam',$data);
	}

	public function gtpaymentdata()
	{
		$id = $_POST['id'];
		$res = $this->db->table("annathanam_new")->where("id", $id)->get()->getRowArray();
		//$amt = $res['amount'] + $res['commision'];
		$amt = $res['total_amount'];
		$data['amt'] = $amt;
		$res1 = $this->db->table("annathanam_booked_pay_details")->selectSum('amount')->where("annathanam_id", $id)->get()->getRowArray();
		$paid_amount = $res1['amount'];
		$data['paid_amount'] = $paid_amount;
		$data['bal_amount'] = $amt - $paid_amount;

		echo json_encode($data);
	}

	public function update_booking_status()
	{
		$id = $_POST['id'];
		$status = $_POST['status'];

		$data = [
			'booking_status' => $status
		];

		$res = $this->db->table('annathanam_new')->where('id', $id)->update($data);
	
		// if($this->db->affectedRows() > 0) {
		if($res){
			echo json_encode(['success' => true, 'message' => 'Booking Cancelled Successfully.']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Failed to Cancel the Booking.']);
		}
	}


	public function save_repayment()
	{
		if(!empty($_POST['payment_mode']) && !empty($_POST['pay_amount'])&& !empty($_POST['booking_id'])){
			$date = $_POST['date'];
			$pay_amount = $_POST['pay_amount'];
			$payment_mode = $_POST['payment_mode'];
			$booking_id = $_POST['booking_id'];
			$count = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getNumRows();
			if($count > 0){
				$payment_mode_details = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getRowArray();
				$annathanam_details = $this->db->table("annathanam_new")->where('id', $booking_id)->get()->getRowArray();
				if($annathanam_details['total_amount'] >= ($annathanam_details['paid_amount'] + $pay_amount)){
					$booking_payment_ins_data = array();
					$booking_payment_ins_data['annathanam_id'] = $booking_id;
					$booking_payment_ins_data['booking_ref_no'] = $annathanam_details['ref_no'];
					$booking_payment_ins_data['is_repayment'] = 1;
					$booking_payment_ins_data['payment_mode_id'] = $payment_mode;
					$booking_payment_ins_data['paid_date'] = !empty($date) ? $date : date('Y-m-d');
					$booking_payment_ins_data['amount'] = $pay_amount;
					$booking_payment_ins_data['payment_mode_title'] = $payment_mode_details['name'];
					$paid_through = 'COUNTER';
					if($paid_through != 'ADMIN' && $paid_through != 'COUNTER') $booking_payment_ins_data['payment_ref_no'] = $ubayam_details['ref_no'];
					$booking_payment_ins_data['paid_through'] = $paid_through;
					$booking_payment_ins_data['pay_status'] = ($paid_through == 'ADMIN' || $paid_through == 'COUNTER') ? 2 : 1;
					$this->requestmodel = new RequestModel();
					$ip = $this->requestmodel->getIpAddress();
					$booking_payment_ins_data['ip'] = $ip;
					if ($ip != 'unknown') {
						$ip_details = $this->requestmodel->getLocation($ip);
						$booking_payment_ins_data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
						$booking_payment_ins_data['ip_details'] = json_encode($ip_details);
					} 
					// $this->paid_amount += $booking_payment_ins_data['amount'];
					$res = $this->db->table("annathanam_booked_pay_details")->insert($booking_payment_ins_data);
					$booked_pay_id = $this->db->insertID();
					$this->db->query("UPDATE annathanam_new SET paid_amount = paid_amount + ? WHERE id = ?", [$pay_amount, $booking_id]);
					$query = $this->db->table('annathanam_new')->where('id', $booking_id)->get()->getRowArray();
					if ($query['total_amount'] == $query['paid_amount']) {
						$this->db->query("UPDATE annathanam_new SET payment_status = 2 WHERE id = ?", [$booking_id]);
					}
					$this->partial_account_migration($booked_pay_id);

					echo json_encode(['status' => true, 'message' => 'Repayment saved successfully.']);
				}else{
					echo json_encode(['status' => false, 'message' => 'Payment amount not exceed Total.']);
				}
			} else {
				echo json_encode(['status' => false, 'message' => 'Failed to save repayment.']);
			}
		} else {
			echo json_encode(['status' => false, 'message' => 'Failed to save repayment.']);
		}
		exit;
	}
	public function partial_account_migration($booked_pay_id){
		$succ = true;
		$yr = date('Y');
    	$mon = date('m');
		$booked_pay_details_cnt = $this->db->table("annathanam_booked_pay_details")->where("id", $booked_pay_id)->get()->getNumRows();	
		if ($booked_pay_details_cnt > 0) {
			$booked_pay_details = $this->db->table("annathanam_booked_pay_details")->where("id", $booked_pay_id)->get()->getResultArray();
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
			$booking_id = $booked_pay_details[0]['annathanam_id'];
			$annathanam_new = $this->db->table("annathanam_new")->where("id", $booking_id)->get()->getRowArray();
			foreach ($booked_pay_details as $row) {
				$paymentmode = $this->db->table('payment_mode')->where('id', $row['payment_mode_id'])->get()->getRowArray();
				if (!empty($paymentmode['ledger_id'])) {
					$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
					if (empty($number))
						$num = 1;
					else
						$num = $number['number'] + 1;
					// Get Entry Code
					$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();

					$entries['entry_code'] = 'REC' . date('y', strtotime($row['paid_date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
					$entries['entrytype_id'] = '1';
					$entries['number'] = $num;
					$entries['date'] = $row['paid_date'];
					$entries['dr_total'] = $row['amount'];
					$entries['cr_total'] = $row['amount'];
					$entries['narration'] = 'Annathanam(' . $annathanam_new['ref_no'] . ')' . "\n" . 'name:' . $annathanam_new['name'] . "\n" . 'NRIC:' . $annathanam_new['ic_number'] . "\n" . 'email:' . $annathanam_new['email'] . "\n";
					$entries['inv_id'] = $booking_id;
					$entries['type'] = 8;
					//Insert Entries
					$ent = $this->db->table('entries')->insert($entries);
					$en_id = $this->db->insertID();
					if (!empty($en_id)) {
						// Trade Debtors => Credit
						$eitems_hall_book['entry_id'] = $en_id;
						$eitems_hall_book['ledger_id'] = $cr_id1;
						$eitems_hall_book['amount'] = $row['amount'];
						$eitems_hall_book['dc'] = 'C';
						$eitems_hall_book['details'] = 'Annathanam(' . $annathanam_new['ref_no'] . ')';
						$this->db->table('entryitems')->insert($eitems_hall_book);
						// PETTY CASH => Debit 
						$eitems_cash_led['entry_id'] = $en_id;
						$eitems_cash_led['ledger_id'] = $paymentmode['ledger_id'];
						$eitems_cash_led['amount'] = $row['amount'];
						$eitems_cash_led['dc'] = 'D';
						$eitems_cash_led['details'] = 'Annathanam(' . $annathanam_new['ref_no'] . ')';
						$this->db->table('entryitems')->insert($eitems_cash_led);
					}
				}else{
					$succ = false;
					return $succ;
				}
			}
		}else{
			$succ = false;
			return $succ;
		}
	}

	public function account_migration($ins_id)
	{
    	$yr = date('Y');
    	$mon = date('m');
		$data = $this->db->table('annathanam_new')->where('id', $ins_id)->get()->getRowArray();
		$payment_mode_details = $this->db->table('payment_mode')->where('id', $data['payment_mode'])->get()->getRowArray();

		$incomes_group = $this->db->table('groups')->where('code', '8000')->get()->getRowArray();
		if (!empty($incomes_group)) {
			$sls_id = $incomes_group['id'];
		} else {
			$sls1['parent_id'] = 0;
			$sls1['name'] = 'Incomes';
			$sls1['code'] = '8000';
			$sls1['added_by'] = $this->session->get('log_id');
			$this->db->table('groups')->insert($sls1);
			$sls_id = $this->db->insertID();
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

		$number1 = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
		if (empty($number1))
			$num1 = 1;
		else
			$num1 = $number1['number'] + 1;
		$qry1 = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();

		$entries1['entry_code'] = 'JOR' . date('y', strtotime($data['date'])) . $mon . (sprintf("%05d", (((float) substr($qry1['entry_code'], -5)) + 1)));
		$entries1['entrytype_id'] = '4';
		$entries1['number'] = $num1;
		$entries1['date'] = date("Y-m-d", strtotime($data['date']));
		$entries1['dr_total'] = $data['total_amount'];
		$entries1['cr_total'] = $data['total_amount'];
		$entries1['narration'] = 'Annathanam(' . $data['ref_no'] . ')' . "\n" . 'name:' . $data['name'] . "\n" . 'NRIC:' . "\n" . 'email:' . $data['email'] . "\n";
		$entries1['inv_id'] = $ins_id;
		$entries1['type'] = 1;
		$ent = $this->db->table('entries')->insert($entries1);
		$en_id1 = $this->db->insertID();

		$debtor_amount = 0;
		if($data['is_special'] == 0){
			$annathanam_details = $this->db->table('annathanam_packages')->where('id', $data['package_id'])->get()->getRowArray();

			if(!empty($annathanam_details['ledger_id'])){
				$dr_id = $annathanam_details['ledger_id'];
			} else {
				$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
				if(!empty($ledger1)){
					$dr_id = $ledger1['id'];
				} else {
					$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code','desc')->get()->getRowArray();
					$set_right_code = (int) $right_code['right_code'] + 1;
					$set_right_code = sprintf("%04d", $set_right_code);
					$lednew1['group_id'] = $sls_id;
					$lednew1['name'] = 'All Incomes';
					$lednew1['left_code'] = '8913';
					$lednew1['right_code'] = $set_right_code;
					$lednew1['op_balance'] = '0';
					$lednew1['op_balance_dc'] = 'D';
					$this->db->table('ledgers')->insert($lednew1);
					$dr_id = $this->db->insertID();
				}
			}
			$amount = (float) $data['amount'] * $data['no_of_pax'];

			// Debit the Product's Ledger (dr_id)
			$eitems_d['entry_id'] = $en_id1;
			$eitems_d['ledger_id'] = $dr_id;
			$eitems_d['amount'] = $amount;
			$eitems_d['details'] = 'Annathanam(' . $data['ref_no'] . ')';
			$eitems_d['dc'] = 'C';
			$cr_res = $this->db->table('entryitems')->insert($eitems_d);
			$debtor_amount += $amount;

		} else{
			$booked_special_cnt = $this->db->table("annathanam_booked_special")->where("annathanam_id", $ins_id)->get()->getNumRows();
			if($booked_special_cnt > 0){
				$booked_special_details = $this->db->table("annathanam_booked_special")->join('annathanam_special_items', 'annathanam_special_items.id = annathanam_booked_special.item_id')->select('annathanam_special_items.*, annathanam_booked_special.quantity')->where("annathanam_booked_special.annathanam_id", $ins_id)->get()->getResultArray();

				foreach($booked_special_details as $row){
					if(!empty($row['ledger_id'])){
						$dr_id = $row['ledger_id'];
					}else{
						$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
						if(!empty($ledger1)){
							$dr_id = $ledger1['id'];
						}else{
							$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code','desc')->get()->getRowArray();
							$set_right_code = (int) $right_code['right_code'] + 1;
							$set_right_code = sprintf("%04d", $set_right_code);
							$led1['group_id'] = $sls_id;
							$led1['name'] = 'All Incomes';
							$led1['left_code'] = '8913';
							$led1['right_code'] = $set_right_code;
							$led1['op_balance'] = '0';
							$led1['op_balance_dc'] = 'D';
							$led_ins1 = $this->db->table('ledgers')->insert($led1);
							$dr_id = $this->db->insertID();
						}
					}
					$amount = (float) $row['amount'] * $row['quantity'];

					// Debit the Product's Ledger (dr_id)
					$eitems_d['entry_id'] = $en_id1;
					$eitems_d['ledger_id'] = $dr_id;
					$eitems_d['amount'] = $amount;
					$eitems_d['details'] = $row['name_eng'] . '(' . $data['ref_no'] . ')';
					$eitems_d['dc'] = 'C';
					$cr_res = $this->db->table('entryitems')->insert($eitems_d);
					$debtor_amount += $amount;
				}
			}
		}

		$booked_addon_cnt = $this->db->table("annathanam_booked_addon")->where("annathanam_id", $ins_id)->get()->getNumRows();
		$booked_additional_cnt = $this->db->table("annathanam_booked_additional")->where("annathanam_id", $ins_id)->get()->getNumRows();
		if($booked_addon_cnt > 0){
			$booked_addon_details = $this->db->table("annathanam_booked_addon")->join('annathanam_items', 'annathanam_items.id = annathanam_booked_addon.item_id')->select('annathanam_items.*, annathanam_booked_addon.quantity')->where("annathanam_booked_addon.annathanam_id", $ins_id)->where('annathanam_items.add_on', 1)->get()->getResultArray();

			foreach($booked_addon_details as $row){
				if(!empty($row['ledger_id'])){
					$dr_id = $row['ledger_id'];
				}else{
					$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
					if(!empty($ledger1)){
						$dr_id = $ledger1['id'];
					}else{
						$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code','desc')->get()->getRowArray();
						$set_right_code = (int) $right_code['right_code'] + 1;
						$set_right_code = sprintf("%04d", $set_right_code);
						$led1['group_id'] = $sls_id;
						$led1['name'] = 'All Incomes';
						$led1['left_code'] = '8913';
						$led1['right_code'] = $set_right_code;
						$led1['op_balance'] = '0';
						$led1['op_balance_dc'] = 'D';
						$led_ins1 = $this->db->table('ledgers')->insert($led1);
						$dr_id = $this->db->insertID();
					}
				}
				$amount = (float) $row['amount'] * $row['quantity'];

				// Debit the Product's Ledger (dr_id)
				$eitems_d['entry_id'] = $en_id1;
				$eitems_d['ledger_id'] = $dr_id;
				$eitems_d['amount'] = $amount;
				$eitems_d['details'] = $row['name_eng'] . '(' . $data['ref_no'] . ')';
				$eitems_d['dc'] = 'C';
				$cr_res = $this->db->table('entryitems')->insert($eitems_d);
				$debtor_amount += $amount;
			}
		}

		if($booked_additional_cnt > 0){
			$booked_additional_details = $this->db->table("annathanam_booked_additional")->where("annathanam_id", $ins_id)->get()->getResultArray();
			$additional = $this->db->table("annathanam_items")->where("add_on", 2)->get()->getRowArray();
			foreach($booked_additional_details as $row){
				if(!empty($additional['ledger_id'])){
					$dr_id = $additional['ledger_id'];
				}else{
					$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
					if(!empty($ledger1)){
						$dr_id = $ledger1['id'];
					}else{
						$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code','desc')->get()->getRowArray();
						$set_right_code = (int) $right_code['right_code'] + 1;
						$set_right_code = sprintf("%04d", $set_right_code);
						$led1['group_id'] = $sls_id;
						$led1['name'] = 'All Incomes';
						$led1['left_code'] = '8913';
						$led1['right_code'] = $set_right_code;
						$led1['op_balance'] = '0';
						$led1['op_balance_dc'] = 'D';
						$led_ins1 = $this->db->table('ledgers')->insert($led1);
						$dr_id = $this->db->insertID();
					}
				}
				$amount = (float) $row['amount'] * $row['quantity'];

				// Debit the Product's Ledger (dr_id)
				$eitems_d['entry_id'] = $en_id1;
				$eitems_d['ledger_id'] = $dr_id;
				$eitems_d['amount'] = $amount;
				$eitems_d['details'] = $row['name'] . '(' . $data['ref_no'] . ')';
				$eitems_d['dc'] = 'C';
				$cr_res = $this->db->table('entryitems')->insert($eitems_d);
				$debtor_amount += $amount;

			}
		}

		$eitems_c['entry_id'] = $en_id1;
		$eitems_c['ledger_id'] = $cr_id1;
		$eitems_c['amount'] = $debtor_amount;
		$eitems_c['details'] = 'Annathanam Amount';
		$eitems_c['dc'] = 'D';
		$deb_res = $this->db->table('entryitems')->insert($eitems_c);

		$booked_pay_cnt = $this->db->table("annathanam_booked_pay_details")->where("annathanam_id", $ins_id)->get()->getNumRows();
		if($booked_pay_cnt > 0){
			$booked_pay_details = $this->db->table("annathanam_booked_pay_details")->where("annathanam_id", $ins_id)->get()->getResultArray();

			foreach ($booked_pay_details as $row) {
				$paymentmode = $this->db->table('payment_mode')->where('id', $row['payment_mode_id'])->get()->getRowArray();
				
				$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
				if (empty($number))
					$num = 1;
				else
					$num = $number['number'] + 1;
				// Get Entry Code
				$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();

				$entries['entry_code'] = 'REC' . date('y', strtotime($row['paid_date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
				$entries['entrytype_id'] = '1';
				$entries['number'] = $num;
				$entries['date'] = $row['paid_date'];
				$entries['dr_total'] = $row['amount'];
				$entries['cr_total'] = $row['amount'];
				$entries['narration'] = 'Annathanam(' . $data['ref_no'] . ')' . "\n" . 'name:' . $data['name'] . "\n" . 'NRIC:' . $data['ic_no'] . "\n" . 'email:' . $data['email_id'] . "\n";
				$entries['inv_id'] = $ins_id;
				$entries['type'] = 8;
				//Insert Entries
				$ent = $this->db->table('entries')->insert($entries);
				$en_id = $this->db->insertID();
				if (!empty($en_id)) {
					// Trade Debtors => Credit
					$eitems_hall_book['entry_id'] = $en_id;
					$eitems_hall_book['ledger_id'] = $cr_id1;
					$eitems_hall_book['amount'] = $row['amount'];
					$eitems_hall_book['dc'] = 'C';
					$eitems_hall_book['details'] = 'Annathanam Amount';
					$this->db->table('entryitems')->insert($eitems_hall_book);
					// PETTY CASH => Debit 
					$eitems_cash_led['entry_id'] = $en_id;
					$eitems_cash_led['ledger_id'] = $paymentmode['ledger_id'];
					$eitems_cash_led['amount'] = $row['amount'];
					$eitems_cash_led['dc'] = 'D';
					$eitems_cash_led['details'] = 'Annathanam Amount';
					$this->db->table('entryitems')->insert($eitems_cash_led);
				}
			}
		}
	}

	public function send_whatsapp_msg($id)
	{
		$data['data'] = $annathanam = $this->db->table('annathanam')->where('id', $id)->get()->getRowArray();
		$tmpid = 1;
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		$data['annathanam_items'] = $this->db->table('annathanam_item')
											->join('annathanam_vegetables','annathanam_vegetables.id = annathanam_item.vegetable_id')
											->select('annathanam_vegetables.name_eng,annathanam_vegetables.name_tamil')
											->where('annathanam_item.annathanam_id', $id)
											->get()
											->getResultArray();
		if (!empty ($annathanam['phone_no'])) {
			$html = view('frontend/annathanam/pdf', $data);
			$options = new Options();
			$options->set('isHtml5ParserEnabled', true);
			$options->set(array('isRemoteEnabled' => true));
			$options->set('isPhpEnabled', true);
			$dompdf = new Dompdf($options);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			$filePath = FCPATH . 'uploads/documents/invoice_annathanam_' . $id . '.pdf';

			file_put_contents($filePath, $dompdf->output());
			$message_params = array();
			$message_params[] = $annathanam['name'];
			$message_params[] = $annathanam['slot_time'];
			$message_params[] = $annathanam['total_amount'];
			$media['url'] = base_url() . '/uploads/documents/invoice_annathanam_' . $id . '.pdf';
			$media['filename'] = 'annathanam_invoice.pdf';
			$annathanam['phone_code'] = !empty($annathanam['phone_code']) ? $annathanam['phone_code'] : '+60';
			$mobile_number = $annathanam['phone_code'] . $annathanam['phone_no'];
			// $mobile_number = '+919092615446';
			// print_r($mobile_number);
			// print_r($message_params);
			// print_r($media);
			// die; 
			$whatsapp_resp = whatsapp_aisensy($mobile_number, $message_params, 'annadhanam_live', $media);
			//print_r($whatsapp_resp);
			//echo $whatsapp_resp['success'];
			/* if($whatsapp_resp['success']) */
		}
	}

	public function save_annathanam_old(){
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$this->db->transStart();
		try{
			// Receive data from POST request
			$data['name'] = $_POST['name'];
			$data['phone_code'] = $_POST['phone_code'];
			$data['phone_no'] = $_POST['phone_no'];
			$data['slot_time'] = $_POST['time'];
			$data['package_id'] = $_POST['package_id'];
			$data['dob'] = $_POST['dob'];
			$data['amount'] = $_POST['amount'];
			$data['no_of_pax'] = $_POST['no_of_pax'];
			$data['total_amount'] = $_POST['total_amount'];
			$data['payment_type'] = !empty($_POST['payment_type']) ? $_POST['payment_type']: 'full';
			$data['booking_through'] = 'COUNTER';
			$pay_method = (!empty($_POST['pay_method']) ? $_POST['pay_method'] : 'cash');
			//$data['payment_status'] = (($pay_method == 'cash' || $pay_method == 'online' || $pay_method == 'qr' || $pay_method == 'nets_pay' || $pay_method == 'pay_now' || $pay_method == 'cheque') ? 2 : 1);

			$items = json_decode($_POST['pack_items'], true);
			$addon_items = json_decode($_POST['addon_pack_items'], true);
		
			
			$yr= date('Y', strtotime($_POST['date']));
			$mon= date('m', strtotime($_POST['date']));
			$query = $this->db->query("SELECT ref_no FROM annathanam_new WHERE id=(SELECT max(id) FROM annathanam_new WHERE year(date)='". $yr ."' AND month(date)='". $mon ."')")->getRowArray();
			$data['ref_no'] = 'AT' .date('y', strtotime($_POST['date'])) . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
			$data['date'] = date('Y-m-d', strtotime($_POST['date']));
			$data['event_date'] = date('Y-m-d', strtotime($_POST['event_date']));
			$data['added_by'] = $this->session->get('log_id_frend');
			$data['created'] = date('Y-m-d H:i:s');
			
			$pay_method = (!empty ($_POST['pay_method']) ? $_POST['pay_method'] : 'cash');
			$query = "SELECT id FROM payment_mode WHERE LOWER(REPLACE(REPLACE(REPLACE(name, ' ', ''), '_', ''), '-', '')) = LOWER(REPLACE(REPLACE(REPLACE(?, ' ', ''), '_', ''), '-', '')) AND paid_through = 'COUNTER' ";	
			$result = $this->db->query($query, [$pay_method])->getRowArray();
			$payment_mode = $result['id'];
			$data['payment_mode'] = $payment_mode;
	
			$res = $this->db->table('annathanam_new')->insert($data);
			$annathanam_id = $this->db->insertID();
	
			if(!empty($items)){
				foreach ($items as $item) {
					$data_item = array(
						'annathanam_id' => $annathanam_id,
						'package_id' => $data['package_id'],
						'item_id' => $item['item_id'],
						'add_on' => $item['add_on']
					);
					$res = $this->db->table('annathanam_booked_items')->insert($data_item);
				}
			}
	
			if(!empty($addon_items)){
				foreach ($addon_items as $addon_item) {
					$data_addon_item = array(
						'annathanam_id' => $annathanam_id,
						'package_id' => $data['package_id'],
						'item_id' => $addon_item['item_id'],
						'item_amount' => $addon_item['item_amount'],
						'item_total_amount' => $addon_item['item_total_amount'],
						'add_on' => $addon_item['add_on']
					);
					$this->db->table('annathanam_booked_addon')->insert($data_addon_item);
				}
			}
			$pay_details = array();
			// if($pay_method == 'cash') $payment_mode_id = 6;
			// if($pay_method == 'online') $payment_mode_id = 8;
			// if($pay_method == 'qr') $payment_mode_id = 9;
			// if($pay_method == 'nets_pay') $payment_mode_id = 10;
			// if($pay_method == 'cheque') $payment_mode_id = 12;
			// if($pay_method == 'pay_now') $payment_mode_id = 13;
			// $count = $this->db->table("payment_mode")->where('id', $payment_mode_id)->get()->getNumRows();
			if($payment_mode){
				$payment_mode_details = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getRowArray();
				$pay_details['payment_key'] = $pay_method;
				$pay_details['annathanam_id'] = $annathanam_id;
				$pay_details['is_repayment'] = 0;
				$pay_details['payment_mode_id'] = $payment_mode;
				$pay_details['paid_through'] = 'COUNTER';
				$pay_details['pay_status'] = (($pay_method == 'cash' || $pay_method == 'online' || $pay_method == 'qr' || $pay_method == 'nets_pay' || $pay_method == 'pay_now' || $pay_method == 'cheque') ? 2 : 1);
				$pay_details['payment_mode_title'] = $payment_mode_details['name'];
				$pay_details['booking_ref_no'] = $data['ref_no'];
				if($data['payment_type'] == 'partial') $pay_details['amount'] = $_POST['paid_amount'];
				else $pay_details['amount'] = $data['total_amount'];
				if(empty($pay_details['amount'])){
					$this->db->transRollback();
					echo 'Invalid Amount';
					$redirect_url = base_url("/annathanam_counter");
					echo <<<PPR
						<script>
						setTimeout(function(){
							location.href = '$redirect_url';
						}, 3500);
						</script>
					PPR;
					exit;
				}
				$pay_details['paid_date'] = date('Y-m-d');
				$this->requestmodel = new RequestModel();
				$ip = $this->requestmodel->getIpAddress();
				$pay_details['ip'] = $ip;
				if ($ip != 'unknown') {
					$ip_details = $this->requestmodel->getLocation($ip);
					$pay_details['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
					$pay_details['ip_details'] = json_encode($ip_details);
				}
				$this->db->table('annathanam_booked_pay_details')->insert($pay_details);
				$booking_ref_data = array();
				$booking_ref_data['paid_amount'] = $pay_details['amount'];
				if($data['payment_type'] == 'partial'){ 
					$booking_ref_data['payment_status'] = 1;
				}else {
					$booking_ref_data['payment_status'] = 2;
				}
				$booking_ref_data['booking_status'] = 1;
				$this->db->table("annathanam_new")->where('id', $annathanam_id)->update($booking_ref_data);

				$payment_gateway_data = array();
				$payment_gateway_data['annathanam_booking_id'] = $annathanam_id;
				$payment_gateway_data['pay_method'] = $pay_method;
				$this->db->table('annathanam_payment_gateway_datas')->insert($payment_gateway_data);

			}else{
				$this->db->transRollback();
				echo 'Invalid Payment';
				$redirect_url = base_url("/annathanam_counter");
				echo <<<PPR
					<script>
					setTimeout(function(){
						location.href = '$redirect_url';
					}, 3500);
					</script>
				PPR;
				exit;
			}

			$this->account_migration($annathanam_id);
			$this->db->transComplete();
			return redirect()->to(base_url("/annathanam_counter?annathanam_id=$annathanam_id"));
		}catch (Exception $e) {
			$this->db->transRollback();
			echo $e->getMessage();
			$redirect_url = base_url("/annathanam_counter");
			echo <<<PPR
			<script>
			setTimeout(function(){
				location.href = '$redirect_url';
			}, 3500);
			</script>
PPR;
		}
	}

}
