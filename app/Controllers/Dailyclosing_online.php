<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Dailyclosing_online extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		helper('common');
		$this->model = new PermissionModel();
		if (($this->session->get('log_id_frend')) == false) {
			$data['dn_msg'] = 'Please Login';
			return redirect()->to('/member_login');
		}
	}
	public function index()
	{
		if (!empty($_POST['dailyclosing_start_date']))
			$dailyclosing_start_date = $_POST['dailyclosing_start_date'];
		else
			$dailyclosing_start_date = date("Y-m-d");
		if (!empty($_POST['dailyclosing_end_date']))
			$dailyclosing_end_date = $_POST['dailyclosing_end_date'];
		else
			$dailyclosing_end_date = date("Y-m-d");
		$login_id = $_SESSION['log_id_frend'];
		$archanai_data_online = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['archanai_details'] = $archanai_data_online;
		$archanai_group_data_online = daily_group_deity_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['archanai_group_details'] = $archanai_group_data_online;

		// $archanai_data_online = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		// $archanai_diety_data_online = daily_diety_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
	
		// $data['archanai_details'] = $archanai_data_online;
		// $data['archanai_diety_details'] = $archanai_diety_data_online;
		$cur_float_cash_cnt = $this->db->table('floating_daily_cash')->where('date', date("Y-m-d"))->get()->getNumRows();
		$cur_float_cash_details = $this->db->table('floating_daily_cash')->where('date', date("Y-m-d"))->get()->getRow();
		$prev_float_cash = $this->db->table('floating_daily_cash')->where('date <', date("Y-m-d"))->orderBy('date', 'DESC')->limit(1, 0)->get()->getRow();
		$data['prev_float_cash'] = $prev_float_cash;
		$data['cur_float_cash_cnt'] = $cur_float_cash_cnt;
		$data['cur_float_cash_details'] = $cur_float_cash_details;
		$hallbooking_data_online = daily_hall_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['hallbooking_details'] = $hallbooking_data_online;
		$ubayam_data = daily_ubayam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['ubayam_details'] = $ubayam_data;
		$donation_data = daily_donation_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['donation_details'] = $donation_data;
		$payment_voucher_data = daily_payment_voucher_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['payment_voucher_details'] = $payment_voucher_data;
		$prasadam_data = daily_prasadam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['prasadam_details'] = $prasadam_data;
		$annathanam_data = daily_annathanam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['annathanam_details'] = $annathanam_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['product_offering_details'] = $product_offering_data;
		$repayment_data = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$kattalai_archanai_data = daily_kattalai_archanai_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['Kattalai_archanai_details'] = $kattalai_archanai_data;
		$data['repayment_details'] = $repayment_data;
		$data['floating_cash'] = $this->db->query("SELECT sum(amount) as amount, checked_by FROM `floating_cash` WHERE date >= '$dailyclosing_start_date' AND date <= '$dailyclosing_end_date' GROUP BY checked_by")->getRowArray();
		$data['dailyclosing_start_date'] = $dailyclosing_start_date;
		$data['dailyclosing_end_date'] = $dailyclosing_end_date;
		$data['staff'] = $this->db->table('staff')->get()->getResultArray();
		$data['archanai'] = $this->db->table('archanai')->get()->getResultArray();

		// echo '<pre>';
		// print_r($data['archanai_group_details']);
		// exit;

		echo view('frontend/layout/header');
		echo view('frontend/daily_closing/index', $data);
	}

	public function get_archanai_products() {
		$staff_id = $_POST['id'];
		$date = $_POST['date'];
		$archanai = $this->db->table("archanai_booking ab")
			->join("archanai_booking_details abd", "abd.archanai_booking_id = ab.id")
			->join("archanai a", "a.id = abd.archanai_id")
			->select('a.name_eng, a.id') 
			->where('abd.comission_to', $staff_id) 
			->where('ab.date', $date) 
			->groupby('a.id')
			->get()->getResultArray();

		echo json_encode($archanai);
		exit;
	}

	public function get_archanai_details() {
		$archanai_id = $_POST['id'];
		$date = $_POST['date'];
		$archanai = $this->db->table("archanai_booking ab")
			->join("archanai_booking_details abd", "abd.archanai_booking_id = ab.id")
			->join("archanai a", "a.id = abd.archanai_id")
			->select('SUM(abd.quantity) as total_quantity, SUM(abd.total_commision) as total_commission') 
			->where('abd.archanai_id', $archanai_id) 
			->where('ab.date', $date) 
			->get()->getResultArray();

		echo json_encode($archanai);
		exit;
	}

	public function print($fromdate, $todate)
	{
		$tmpid = $this->session->get('profile_id');
		if (!empty($fromdate))
			$dailyclosing_start_date = date('Y-m-d', $fromdate);
		else
			$dailyclosing_start_date = date("Y-m-d");
		if (!empty($todate))
			$dailyclosing_end_date = date('Y-m-d', $todate);
		else
			$dailyclosing_end_date = date("Y-m-d");
		$login_id = $_SESSION['log_id_frend'];
		$cur_float_cash_cnt = $this->db->table('floating_daily_cash')->where('date', date("Y-m-d"))->get()->getNumRows();
		$cur_float_cash_details = $this->db->table('floating_daily_cash')->where('date', date("Y-m-d"))->get()->getRow();
		$prev_float_cash = $this->db->table('floating_daily_cash')->where('date <', date("Y-m-d"))->orderBy('date', 'DESC')->limit(1, 0)->get()->getRow();
		$data['prev_float_cash'] = $prev_float_cash;
		$data['cur_float_cash_cnt'] = $cur_float_cash_cnt;
		$data['cur_float_cash_details'] = $cur_float_cash_details;
		$archanai_data_online = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['archanai_details'] = $archanai_data_online;
		$archanai_group_data_online = daily_group_deity_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['archanai_group_details'] = $archanai_group_data_online;
		$hallbooking_data_online = daily_hall_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['hallbooking_details'] = $hallbooking_data_online;
		$ubayam_data = daily_ubayam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['ubayam_details'] = $ubayam_data;
		$donation_data = daily_donation_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['donation_details'] = $donation_data;
		$payment_voucher_data = daily_payment_voucher_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['payment_voucher_details'] = $payment_voucher_data;
		$prasadam_data = daily_prasadam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['prasadam_details'] = $prasadam_data;
		$annathanam_data = daily_annathanam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['annathanam_details'] = $annathanam_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['product_offering_details'] = $product_offering_data;
		$repayment_data = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$kattalai_archanai_data = daily_kattalai_archanai_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['Kattalai_archanai_details'] = $kattalai_archanai_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['repayment_details'] = $repayment_data;
		$data['floating_cash'] = $this->db->query("SELECT sum(amount) as amount, checked_by FROM `floating_cash` WHERE date >= '$dailyclosing_start_date' AND date <= '$dailyclosing_end_date' GROUP BY checked_by")->getRowArray();
		$data['dailyclosing_start_date'] = $dailyclosing_start_date;
		$data['dailyclosing_end_date'] = $dailyclosing_end_date;
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

		// echo '<pre>';
		// print_r($data['archanai_group_details']);
		// exit;
		echo view('frontend/daily_closing/print_page', $data);
	}


	public function print_a4($fromdate, $todate)
	{
		$tmpid = $this->session->get('profile_id');

		if (!empty($fromdate))
			$dailyclosing_start_date = date('Y-m-d', $fromdate);
		else
			$dailyclosing_start_date = date("Y-m-d");
		if (!empty($todate))
			$dailyclosing_end_date = date('Y-m-d', $todate);
		else
			$dailyclosing_end_date = date("Y-m-d");
		$login_id = $_SESSION['log_id_frend'];
		$cur_float_cash_cnt = $this->db->table('floating_daily_cash')->where('date', date("Y-m-d"))->get()->getNumRows();
		$cur_float_cash_details = $this->db->table('floating_daily_cash')->where('date', date("Y-m-d"))->get()->getRow();
		$prev_float_cash = $this->db->table('floating_daily_cash')->where('date <', date("Y-m-d"))->orderBy('date', 'DESC')->limit(1, 0)->get()->getRow();
		$data['prev_float_cash'] = $prev_float_cash;
		$data['cur_float_cash_cnt'] = $cur_float_cash_cnt;
		$data['cur_float_cash_details'] = $cur_float_cash_details;
		$archanai_data_online = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['archanai_details'] = $archanai_data_online;
		$archanai_group_data_online = daily_group_deity_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['archanai_group_details'] = $archanai_group_data_online;
		/* $archanai_diety_data_online = daily_diety_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['archanai_diety_details'] = $archanai_diety_data_online; */
		$hallbooking_data_online = daily_hall_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['hallbooking_details'] = $hallbooking_data_online;
		$ubayam_data = daily_ubayam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['ubayam_details'] = $ubayam_data;
		$donation_data = daily_donation_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['donation_details'] = $donation_data;
		$prasadam_data = daily_prasadam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['prasadam_details'] = $prasadam_data;
		$annathanam_data = daily_annathanam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['annathanam_details'] = $annathanam_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['product_offering_details'] = $product_offering_data;
		$payment_voucher_data = daily_payment_voucher_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['payment_voucher_details'] = $payment_voucher_data;
		$repayment_data = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['repayment_details'] = $repayment_data;
		$kattalai_archanai_data = daily_kattalai_archanai_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['Kattalai_archanai_details'] = $kattalai_archanai_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "COUNTER", $login_id);
		$data['floating_cash'] = $this->db->query("SELECT sum(amount) as amount, checked_by FROM `floating_cash` WHERE date >= '$dailyclosing_start_date' AND date <= '$dailyclosing_end_date' GROUP BY checked_by")->getRowArray();
		$data['dailyclosing_start_date'] = $dailyclosing_start_date;
		$data['dailyclosing_end_date'] = $dailyclosing_end_date;
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		echo view('frontend/daily_closing/print_a4', $data);
	}
	public function saveOpenFloat()
	{
		if(!empty($this->request->getPost('opening_float')) && !empty($this->request->getPost('checked_op'))){
			try{
				// Get POST data
				$opening_float = $this->request->getPost('opening_float');
				$checked_op = $this->request->getPost('checked_op');
				$log_id = $this->session->get('log_id_frend');
				// Get today's date
				$today_date = date('Y-m-d');

				// Prepare the data for insertion
				$floating_data = [
					'date' => $today_date,
					'opening' => $opening_float,
					'checked_op' => $checked_op,
					'paid_through' => 'COUNTER',
					'created_by' => $log_id,
					'modified_by' => $log_id
				];

				// Delete any existing record for today's date
				$this->db->table('floating_daily_cash')
					->where('date', $today_date)
					->where('paid_through', 'COUNTER')
					->where('created_by', $log_id)
					->delete();

				// Insert the new record
				if ($this->db->table('floating_daily_cash')->insert($floating_data)) {
					// Send success response
					return $this->response->setJSON(['status' => 'success', 'message' => 'Floating Cash saved successfully']);
				} else {
					// Send error response
					return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save Floating Cash']);
				}
			}catch (Exception $e) {
				return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
			}
		}else{
			return $this->response->setJSON(['status' => 'error', 'message' => 'Please fill all required field']);
		}
	}
	public function saveCloseFloat()
	{
		if(!empty($this->request->getPost('closing')) && !empty($this->request->getPost('checked_cl'))){
			try{
				// Get POST data
				$closing = $this->request->getPost('closing');
				$checked_cl = $this->request->getPost('checked_cl');
				$income = !empty($this->request->getPost('income')) ? $this->request->getPost('income') : 0;
				$expense = !empty($this->request->getPost('expense')) ? $this->request->getPost('expense') : 0;
				$log_id = $this->session->get('log_id_frend');
				// Get today's date
				$today_date = date('Y-m-d');

				// Prepare the data for insertion
				$floating_data = [
					'closing' => $closing,
					'checked_cl' => $checked_cl,
					'income' => $income,
					'expense' => $expense,
					'modified_by' => $log_id
				];

				// Delete any existing record for today's date
				$res = $this->db->table('floating_daily_cash')->where('date', $today_date)->where('paid_through', 'COUNTER')->where('created_by', $log_id)->update($floating_data);

				// Insert the new record
				if ($res) {
					// Send success response
					return $this->response->setJSON(['status' => 'success', 'message' => 'Closing Balance saved successfully']);
				} else {
					// Send error response
					return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save Closing Cash']);
				}
			}catch (Exception $e) {
				return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
			}
		}else{
			return $this->response->setJSON(['status' => 'error', 'message' => 'Please fill all required field']);
		}
	}
	
	public function full()
	{
		if (!empty($_POST['dailyclosing_start_date']))
			$dailyclosing_start_date = $_POST['dailyclosing_start_date'];
		else
			$dailyclosing_start_date = date("Y-m-d");
		if (!empty($_POST['dailyclosing_end_date']))
			$dailyclosing_end_date = $_POST['dailyclosing_end_date'];
		else
			$dailyclosing_end_date = date("Y-m-d");
		$login_id = '';
		$archanai_data_online = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['archanai_details'] = $archanai_data_online;
		$archanai_group_data_online = daily_group_deity_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['archanai_group_details'] = $archanai_group_data_online;

		// $archanai_data_online = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		// $archanai_diety_data_online = daily_diety_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
	
		// $data['archanai_details'] = $archanai_data_online;
		// $data['archanai_diety_details'] = $archanai_diety_data_online;
		$cur_float_cash_cnt = $this->db->table('floating_daily_cash')->where('date', date("Y-m-d"))->get()->getNumRows();
		$cur_float_cash_details = $this->db->table('floating_daily_cash')->where('date', date("Y-m-d"))->get()->getRow();
		$prev_float_cash = $this->db->table('floating_daily_cash')->where('date <', date("Y-m-d"))->orderBy('date', 'DESC')->limit(1, 0)->get()->getRow();
		$data['prev_float_cash'] = $prev_float_cash;
		$data['cur_float_cash_cnt'] = $cur_float_cash_cnt;
		$data['cur_float_cash_details'] = $cur_float_cash_details;
		$hallbooking_data_online = daily_hall_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['hallbooking_details'] = $hallbooking_data_online;
		$ubayam_data = daily_ubayam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['ubayam_details'] = $ubayam_data;
		$donation_data = daily_donation_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['donation_details'] = $donation_data;
		$payment_voucher_data = daily_payment_voucher_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['payment_voucher_details'] = $payment_voucher_data;
		$prasadam_data = daily_prasadam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['prasadam_details'] = $prasadam_data;
		$annathanam_data = daily_annathanam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['annathanam_details'] = $annathanam_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['product_offering_details'] = $product_offering_data;
		$repayment_data = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$kattalai_archanai_data = daily_kattalai_archanai_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['Kattalai_archanai_details'] = $kattalai_archanai_data;
		$data['repayment_details'] = $repayment_data;
		$data['floating_cash'] = $this->db->query("SELECT sum(amount) as amount, checked_by FROM `floating_cash` WHERE date >= '$dailyclosing_start_date' AND date <= '$dailyclosing_end_date' GROUP BY checked_by")->getRowArray();
		$data['dailyclosing_start_date'] = $dailyclosing_start_date;
		$data['dailyclosing_end_date'] = $dailyclosing_end_date;

		// echo '<pre>';
		// print_r($data['archanai_group_details']);
		// exit;
		echo view('frontend/layout/header');
		echo view('frontend/daily_closing/full', $data);
	}

	public function print_full($fromdate, $todate)
	{
		$tmpid = $this->session->get('profile_id');
		if (!empty($fromdate))
			$dailyclosing_start_date = date('Y-m-d', $fromdate);
		else
			$dailyclosing_start_date = date("Y-m-d");
		if (!empty($todate))
			$dailyclosing_end_date = date('Y-m-d', $todate);
		else
			$dailyclosing_end_date = date("Y-m-d");
		$login_id = '';
		$cur_float_cash_cnt = $this->db->table('floating_daily_cash')->where('date', date("Y-m-d"))->get()->getNumRows();
		$cur_float_cash_details = $this->db->table('floating_daily_cash')->where('date', date("Y-m-d"))->get()->getRow();
		$prev_float_cash = $this->db->table('floating_daily_cash')->where('date <', date("Y-m-d"))->orderBy('date', 'DESC')->limit(1, 0)->get()->getRow();
		$data['prev_float_cash'] = $prev_float_cash;
		$data['cur_float_cash_cnt'] = $cur_float_cash_cnt;
		$data['cur_float_cash_details'] = $cur_float_cash_details;
		$archanai_data_online = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['archanai_details'] = $archanai_data_online;
		$archanai_group_data_online = daily_group_deity_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['archanai_group_details'] = $archanai_group_data_online;
		$hallbooking_data_online = daily_hall_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['hallbooking_details'] = $hallbooking_data_online;
		$ubayam_data = daily_ubayam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['ubayam_details'] = $ubayam_data;
		$donation_data = daily_donation_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['donation_details'] = $donation_data;
		$payment_voucher_data = daily_payment_voucher_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['payment_voucher_details'] = $payment_voucher_data;
		$prasadam_data = daily_prasadam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['prasadam_details'] = $prasadam_data;
		$annathanam_data = daily_annathanam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['annathanam_details'] = $annathanam_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['product_offering_details'] = $product_offering_data;
		$repayment_data = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$kattalai_archanai_data = daily_kattalai_archanai_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['Kattalai_archanai_details'] = $kattalai_archanai_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['repayment_details'] = $repayment_data;
		$data['floating_cash'] = $this->db->query("SELECT sum(amount) as amount, checked_by FROM `floating_cash` WHERE date >= '$dailyclosing_start_date' AND date <= '$dailyclosing_end_date' GROUP BY checked_by")->getRowArray();
		$data['dailyclosing_start_date'] = $dailyclosing_start_date;
		$data['dailyclosing_end_date'] = $dailyclosing_end_date;
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

		// echo '<pre>';
		// print_r($data['archanai_group_details']);
		// exit;
		echo view('frontend/daily_closing/print_page', $data);
	}


	public function print_a4_full($fromdate, $todate)
	{
		$tmpid = $this->session->get('profile_id');

		if (!empty($fromdate))
			$dailyclosing_start_date = date('Y-m-d', $fromdate);
		else
			$dailyclosing_start_date = date("Y-m-d");
		if (!empty($todate))
			$dailyclosing_end_date = date('Y-m-d', $todate);
		else
			$dailyclosing_end_date = date("Y-m-d");
		$login_id = '';
		$cur_float_cash_cnt = $this->db->table('floating_daily_cash')->where('date', date("Y-m-d"))->get()->getNumRows();
		$cur_float_cash_details = $this->db->table('floating_daily_cash')->where('date', date("Y-m-d"))->get()->getRow();
		$prev_float_cash = $this->db->table('floating_daily_cash')->where('date <', date("Y-m-d"))->orderBy('date', 'DESC')->limit(1, 0)->get()->getRow();
		$data['prev_float_cash'] = $prev_float_cash;
		$data['cur_float_cash_cnt'] = $cur_float_cash_cnt;
		$data['cur_float_cash_details'] = $cur_float_cash_details;
		$archanai_data_online = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['archanai_details'] = $archanai_data_online;
		$archanai_group_data_online = daily_group_deity_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['archanai_group_details'] = $archanai_group_data_online;
		/* $archanai_diety_data_online = daily_diety_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['archanai_diety_details'] = $archanai_diety_data_online; */
		$hallbooking_data_online = daily_hall_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['hallbooking_details'] = $hallbooking_data_online;
		$ubayam_data = daily_ubayam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['ubayam_details'] = $ubayam_data;
		$donation_data = daily_donation_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['donation_details'] = $donation_data;
		$prasadam_data = daily_prasadam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['prasadam_details'] = $prasadam_data;
		$annathanam_data = daily_annathanam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['annathanam_details'] = $annathanam_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['product_offering_details'] = $product_offering_data;
		$payment_voucher_data = daily_payment_voucher_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['payment_voucher_details'] = $payment_voucher_data;
		$repayment_data = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['repayment_details'] = $repayment_data;
		$kattalai_archanai_data = daily_kattalai_archanai_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['Kattalai_archanai_details'] = $kattalai_archanai_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type = "", $login_id);
		$data['floating_cash'] = $this->db->query("SELECT sum(amount) as amount, checked_by FROM `floating_cash` WHERE date >= '$dailyclosing_start_date' AND date <= '$dailyclosing_end_date' GROUP BY checked_by")->getRowArray();
		$data['dailyclosing_start_date'] = $dailyclosing_start_date;
		$data['dailyclosing_end_date'] = $dailyclosing_end_date;
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		echo view('frontend/daily_closing/print_a4', $data);
	}

}
