<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\RequestModel;

class Donation extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		helper('common');
		$this->model = new PermissionModel();
		if (($this->session->get('login')) == false && $this->session->get('role') != 1) {
			$data['dn_msg'] = 'Please Login';
			return redirect()->to('/login');
		}
	}

	public function index()
	{
		//$data['list'] = $this->db->table('donation')->get()->getResultArray();
		if (!$this->model->list_validate('cash_donation')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data['permission'] = $this->model->get_permission('cash_donation');
		$data['list'] = $this->db->table('donation', 'donation_setting.name as pname')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname')
			->select('donation.*')
			->orderBy('date', 'DESC')
			->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('donation/index', $data);
		echo view('template/footer');
	}
	public function add()
	{
		if (!$this->model->permission_validate('cash_donation', 'create_p')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data['sett_don'] = $this->db->table('donation_setting')->get()->getResultArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where("donation", 1)->where('status', 1)->where('paid_through', 'DIRECT')->get()->getResultArray();
		$data['phone_codes'] = $this->db->table("phone_code")->orderBy('dailing_code', 'ASC')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('donation/add', $data);
		echo view('template/footer');
	}

	public function edit()
	{
		if (!$this->model->permission_validate('cash_donation', 'edit')) {
			return redirect()->to(base_url() . '/dashboard');}

		$id = $this->request->uri->getSegment(3);
		$data['sett_don'] = $this->db->table('donation_setting')->get()->getResultArray();
		$data['data'] = $this->db->table('donation')->where('id', $id)->get()->getRowArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->get()->getResultArray();
		$data['edit'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('donation/add', $data);
		echo view('template/footer');
	}

	public function view()
	{
		if (!$this->model->permission_validate('cash_donation', 'view')) {
			return redirect()->to(base_url() . '/dashboard');}

		$id = $this->request->uri->getSegment(3);
		$data['sett_don'] = $this->db->table('donation_setting')->get()->getResultArray();
		$data['data'] = $this->db->table('donation')->where('id', $id)->get()->getRowArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->get()->getResultArray();
		$data['view'] = true;
		$data['edit'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('donation/add', $data);
		echo view('template/footer');
	}

	public function save($pledge_entry_id = 0)
	{
		$email = \Config\Services::email();
		$id = $_POST['id'];
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$date = explode('-', $_POST['date']);
		$yr = $date[0];
		$mon = $date[1];
		
		// Generate reference number
		$query = $this->db->query("SELECT ref_no FROM donation where id=(select max(id) from donation where year (date)='" . $yr . "' and month (date)='" . $mon . "') ")->getRowArray();
		$data['ref_no'] = 'DO' . date('y', strtotime($_POST['date'])) . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
		
		// Collect basic donation data
		$data['date'] = $_POST['date'];
		$data['pay_for'] = trim($_POST['pay_for']);
		$data['name'] = trim($_POST['name']);
		$data['address'] = trim($_POST['address']);
		$data['ic_number'] = trim($_POST['ic_number']);
		$data['pledge_id'] = $pledge_entry_id;
		
		// Handle mobile number
		if(empty($_POST['edit_status'])){
			$mble_phonecode = !empty($_POST['phonecode'])?$_POST['phonecode']:"";
			$mble_number = !empty($_POST['mobile'])?$_POST['mobile']:"";
			$data['mobile']  = $mble_phonecode.$mble_number;
		}
		else{
			$data['mobile'] = $_POST['mobile'];
		}
	
		$data['description'] = trim($_POST['description']);
		$data['amount'] = trim($_POST['amount']);
		$data['target_amount'] = $_POST['targetamt'];
		$data['collected_amount'] = $_POST['collectedamt'];
		$data['payment_mode'] = $_POST['paymentmode'];
		$data['email'] = $_POST['email'];
		$data['added_by'] = $this->session->get('log_id');
	
		// Handle IP location
		$ip = 'unknown';
		$this->requestmodel = new RequestModel();
		$ip = $this->requestmodel->getIpAddress();
		if ($ip != 'unknown') {
			$ip_details = $this->requestmodel->getLocation($ip);
			$data['ip'] = $ip;
			$data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
			$data['ip_details'] = json_encode($ip_details);
		}
	
		// PLEDGE PROCESSING - This is what was missing!
		$pledge_entry_id = 0;
		
		// Check if this is a pledge donation
		if (!empty($_POST['is_pledge']) && $_POST['is_pledge'] == '1') {
			$pledge_amount = !empty($_POST['pledge_amount']) ? floatval($_POST['pledge_amount']) : 0;
			
			if ($pledge_amount <= 0) {
				$msg_data['err'] = 'Pledge amount must be greater than 0';
				echo json_encode($msg_data);
				exit();
			}
			
			// Check if pledge already exists for this user (by mobile or email)
			$existing_pledge = null;
			if (!empty($data['mobile'])) {
				$existing_pledge = $this->db->table('pledge')
					->where('mobile', $data['mobile'])
					->where('balance_amt >', 0)
					->get()->getRowArray();
			}
			
			if (empty($existing_pledge) && !empty($data['email'])) {
				$existing_pledge = $this->db->table('pledge')
					->where('email_id', $data['email'])
					->where('balance_amt >', 0)
					->get()->getRowArray();
			}
			
			if (!empty($existing_pledge)) {
				// Use existing pledge
				$pledge_id = $existing_pledge['id'];
				
				// Check if pledge amount doesn't exceed balance
				if ($pledge_amount > $existing_pledge['balance_amt']) {
					$msg_data['err'] = 'Pledge amount cannot exceed balance amount of RM ' . number_format($existing_pledge['balance_amt'], 2);
					echo json_encode($msg_data);
					exit();
				}
				
				// Update existing pledge balance
				$new_balance = $existing_pledge['balance_amt'] - $pledge_amount;
				$this->db->table('pledge')
					->where('id', $pledge_id)
					->update(['balance_amt' => $new_balance, 'updated_date' => date('Y-m-d H:i:s')]);
					
			} else {
				// Create new pledge record
				$pledge_data = [
					'name' => $data['name'],
					'phone_code' => !empty($_POST['phonecode']) ? $_POST['phonecode'] : '+60',
					'mobile' => !empty($data['mobile']) ? str_replace($_POST['phonecode'], '', $data['mobile']) : '',
					'email_id' => $data['email'],
					'ic_or_passport' => $data['ic_number'],
					'address' => $data['address'],
					'description' => $data['description'],
					'pledge_amount' => $pledge_amount,
					'balance_amt' => $pledge_amount,
					'created_date' => date('Y-m-d H:i:s')
				];
				
				$this->db->table('pledge')->insert($pledge_data);
				$pledge_id = $this->db->insertID();
			}
			
			// Create pledge entry record
			$pledge_entry_data = [
				'pledge_id' => $pledge_id,
				'entry_date' => $data['date'],
				'pledge_type' => 1, // Assuming 1 for donation pledge
				'donated_pledge_amt' => $pledge_amount,
				'current_total_amt' => $pledge_amount,
				'current_donation_amount' => $data['amount'],
				'status' => 1,
				'created_at' => date('Y-m-d H:i:s')
			];
			
			$this->db->table('pledge_entry')->insert($pledge_entry_data);
			$pledge_entry_id = $this->db->insertID();
			
			// Update donation data with pledge_id
			$data['pledge_id'] = $pledge_entry_id;
		}
	
		// Validation
		if (!empty($data['pay_for']) && !empty($data['name']) && !empty($data['amount']) && !empty($data['date']) && $data['amount'] > 0) {
			
			// Rest of your existing ledger and accounting logic...
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
			
			$donation_details = $this->db->table('donation_setting')->where('id', $data['pay_for'])->get()->getRowArray();
			if(!empty($donation_details['ledger_id'])){
				$dr_id = $donation_details['ledger_id'];
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
			
			if (empty($id)) {
				$data['payment_status'] = 1;
				$builder = $this->db->table('donation')->insert($data);
				$ins_id = $this->db->insertID();
				
				// Rest of your existing code for user sync, WhatsApp, accounting entries, email, etc.
				if (!empty($data['mobile'])) {
					$users_all_data = array();
					if (substr($data['mobile'], 0, 1) == '+') {
						$users_all_data['mobile'] = substr($data['mobile'], 3);
						$users_all_data['country_phone_code'] = substr($data['mobile'], 0, 3);
					} else {
						$users_all_data['mobile'] = $data['mobile'];
						$users_all_data['country_phone_code'] = '+61';
					}
					$users_all_data['name'] = $data['name'];
					$users_all_data['address'] = $data['address'];
					$users_all_data['nric'] = $data['ic_number'];
					$users_all_data['email'] = $data['email'];
					sync_users_all_tag($users_all_data, 2);
				}
				
				$this->send_whatsapp_msg($ins_id);
				$data['created'] = date('Y-m-d H:i:s');
				$data['modified'] = date('Y-m-d H:i:s');
				
				// Continue with your existing accounting entry logic...
				if ((!empty($dr_id)) && (!empty($payment_mode_details['ledger_id']))) {
					// Your existing accounting code here...
					$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
					if (empty($number)) {
						$num = 1;
					} else {
						$num = $number['number'] + 1;
					}
					$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();
					$entries['entry_code'] = 'REC' . date('y', strtotime($_POST['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
	
					$entries['entrytype_id'] = '1';
					$entries['number'] = $num;
					$entries['date'] = $data['date'];
					$entries['dr_total'] = $data['amount'];
					$entries['cr_total'] = $data['amount'];
					$entries['narration'] = 'Cash Donation(' . $data['ref_no'] . ')' . "\n" . 'name:' . $data['name'] . "\n" . 'NRIC:' . $data['ic_number'] . "\n" . 'email:' . $data['email'] . "\n";
					$entries['inv_id'] = $ins_id;
					$entries['type'] = '2';
					$ent = $this->db->table('entries')->insert($entries);
					$en_id = $this->db->insertID();
					
					if (!empty($en_id)) {
						$eitems_d['entry_id'] = $en_id;
						$eitems_d['ledger_id'] = $dr_id;
						$eitems_d['amount'] = $data['amount'];
						$eitems_d['details'] = 'Cash Donation(' . $data['ref_no'] . ')';
						$eitems_d['dc'] = 'C';
						$this->db->table('entryitems')->insert($eitems_d);
	
						$eitems_c['entry_id'] = $en_id;
						$eitems_c['ledger_id'] = $payment_mode_details['ledger_id'];
						$eitems_c['amount'] = $data['amount'];
						$eitems_c['details'] = 'Cash Donation(' . $data['ref_no'] . ')';
						$eitems_c['dc'] = 'D';
						$this->db->table('entryitems')->insert($eitems_c);
						
						if ($builder) {
							// Email sending logic...
							if (!empty($_POST['email'])) {
								$temple_title = "Temple " . $_SESSION['site_title'];
								$qr_url = base_url() . "/donation/reg/";
								$mail_data['qr_image'] = qrcode_generation($ins_id, $qr_url);
								$mail_data['don_id'] = $ins_id;
								$tmpid = 1;
								$mail_data['temple_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
								$message = view('donation/mail_template', $mail_data);
								$to = $_POST['email'];
								$subject = $_SESSION['site_title'] . " Cash Donation";
								$email->setTo($to);
								$email->setFrom('templetest@grasp.com.my', $temple_title);
								$email->setSubject($subject);
								$email->setMessage($message);
								$email->send();
							}
							$msg_data['succ'] = 'Donation Added Successfully';
							$msg_data['id'] = $ins_id;
							$msg_data['err'] = '';
						}
					} else {
						$msg_data['err'] = 'Please Try Again';
					}
				} else {
					$msg_data['err'] = 'Please Try Again Ledger is empty';
				}
			}
		} else {
			$msg_data['err'] = 'Please Fill Required Field';
		}
		
		echo json_encode($msg_data);
		exit();
	}

	// Add these methods to your existing controller


	public function reg()
	{
		echo "welcome";
	}
	public function delete()
	{
		if (!$this->model->permission_validate('cash_donation', 'delete_p')) {
			return redirect()->to(base_url() . '/dashboard');}

		$id = $this->request->uri->getSegment(3);
		$res = $this->db->table('donation')->delete(['id' => $id]);
		if ($res) {
			$this->session->setFlashdata('succ', 'Donation Delete Successfully');
			return redirect()->to(base_url() . "/donation");} else {
			$this->session->setFlashdata('fail', 'Please Try Again');
			return redirect()->to(base_url() . "/donation");}
	}

	public function print_page($don_book_id)
	{
		$id = $this->request->uri->getSegment(3);
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		$data['data'] = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname, donation.amount, donation.name, donation.mobile')
			->select('donation.*')
			->where('donation.id', $id)
			->get()->getRowArray();

		// echo view('donation/print_page', $data);
		echo view('donation/print_report', $data);
	}

	public function print_page_old()
	{

		if (!$this->model->permission_validate('cash_donation', 'print')) {
			return redirect()->to(base_url() . '/dashboard');}
		$id = $this->request->uri->getSegment(3);
		// echo  $id;
		//  exit ;
		$data['qry1'] = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname')
			->select('donation.*')
			->where('donation.id', $id)
			->get()->getRowArray();
		$data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
		echo view('donation/print_page', $data);
	}
	public function get_donation_amount()
	{
		$json_resp = array();
		if (!empty($_REQUEST['setting_id'])) {
			$id = $_REQUEST['setting_id'];
			$res = $this->db->table('donation_setting ds')->join('donation d', 'ds.id = d.pay_for', 'left')->select('max(ds.amount) as total_amount')->select('COALESCE(sum(d.amount), 0) as collected_amount')->where(['ds.id' => $id])->get()->getRowArray();
			if ($res)
				$json_resp['data'] = $res;
			else
				$json_resp['data'] = array();
		}
		echo json_encode($json_resp);
		exit;
	}
	public function send_whatsapp_msg($id)
	{
		$data['qry1'] = $donation = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname')
			->select('donation.*')
			->where('donation.id', $id)
			->get()->getRowArray();
		$tmpid = 1;
		$data['temple_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		$data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
		if (!empty($donation['mobile'])) {
			$html = view('donation/pdf', $data);
			$options = new Options();
			$options->set('isHtml5ParserEnabled', true);
			$options->set(array('isRemoteEnabled' => true));
			$options->set('isPhpEnabled', true);
			$dompdf = new Dompdf($options);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			$filePath = FCPATH . 'uploads/documents/invoice_donation_' . $id . '.pdf';

			file_put_contents($filePath, $dompdf->output());
			$message_params = array();
			/* $message_params[] = date('d M, Y', strtotime($donation['dt']));
					 $message_params[] = date('h:i A', strtotime($donation['created_at']));
					 $message_params[] = $donation['amount'];
					 // $message_params[] = $ubayam['paidamount'];
					 $message_params[] = $donation['balanceamount']; */
			$media['url'] = base_url() . '/uploads/documents/invoice_donation_' . $id . '.pdf';
			$media['filename'] = 'donation_invoice.pdf';
			$mobile_number = $donation['mobile'];
			//$mobile_number = '+919092615446';
			// print_r($mobile_number);
			// print_r($message_params);
			// print_r($media);
			// die; 
			$whatsapp_resp = whatsapp_aisensy($mobile_number, $message_params, 'donation_live', $media);
			// print_r($whatsapp_resp);
			//echo $whatsapp_resp['success'];
			/* if($whatsapp_resp['success']) 
					 //echo 'success';
					 echo view('hallbooking/whatsapp_resp_suc');
					 else 
					 //echo 'fail'; 
					 echo view('hallbooking/whatsapp_resp_fail'); */
		}
	}
}
