<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use App\Models\RequestModel;

class Kattalai_archanai_online extends BaseController
{
    function __construct(){
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
		$login_id = $_SESSION['log_id_frend'];

		if (!$this->model->list_validate('archanai_ticket')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data['permission'] = $this->model->get_permission('archanai_ticket');
		$data['staff'] = $this->db->table('staff')->where('is_admin', 0)->get()->getResultArray();
		$data['rasi'] = $this->db->table('rasi')->get()->getResultArray();
		$data['nat'] = $this->db->table('natchathram')->get()->getResultArray();
		$group = $this->db->query("SELECT * FROM archanai_group order by name asc")->getResultArray();
		
		$data['archanai'] = $this->db->table('kattalai_archanai')->select('*')->get()->getResultArray();
		$data['archanai_diety'] = $this->db->table('archanai_diety')->select('id, name')->where('kattalai_deity', 1)->get()->getResultArray();
		//print_r($data['rasi']); exit();
		//$data['data'] = $this->db->table('archanai')->get()->getResultArray();
		$yr = date('Y');
		$mon = date('m');
		$query = $this->db->query("SELECT ref_no FROM archanai_booking where id=(select max(id) from archanai_booking where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();

		$data['bill_no'] = 'AR' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
		$data['payment_mode'] = $this->db->table('payment_mode')->where("paid_through", "COUNTER")->where("kattalai_archanai", 1)->where('status', 1)->orderby('menu_order', "ASC")->get()->getResultArray();
		$settings = $this->db->table('settings')->where('type', 7)->get()->getResultArray();
		$setting_array = array();
		if(count($settings) > 0){
			foreach ($settings as $item) {
				$setting_array[$item['setting_name']] = $item['setting_value'];
			}
		}
		$data['setting'] = $setting_array;

		echo view('frontend/layout/header');
		echo view('frontend/kattalai_archanai/index', $data);
		//echo view('frontend/layout/footer');		
	}

	public function get_natchathram()
	{
		$rasi_id = $_POST['rasi_id'];
		$res = $this->db->table('rasi')->where('id', $rasi_id)->get()->getRowArray();
		if (!empty ($res['natchathra_id'])) {
			$data = array("natchathra_id" => $res['natchathra_id'], "rasi_id" => $res['rasi_id']);
		} else {
			$res_natchathrams = $this->db->table('natchathram')->get()->getResultArray();
			$data_bf = array();
			foreach ($res_natchathrams as $res_natchathram) {
				$data_bf[] = $res_natchathram['id'];
			}
			$dataip = implode(',', $data_bf);
			$data = array("natchathra_id" => $dataip, "rasi_id" => $res['rasi_id']);
		}
		echo json_encode($data);
		exit;
	}
	public function get_natchathram_name()
	{
		$id = $_POST['id'];
		$res = $this->db->table('natchathram')->select('id, name_eng, name_tamil')->where('id', $id)->get()->getRowArray();
		$data = array("id" => $res['id'], "name_eng" => $res['name_eng'], "name_tamil" => $res['name_tamil']);
		echo json_encode($data);
		exit;
	}
	public function fetch_amount_old() {
		$archanaitype_id = $this->input->post('archanaitype_id');
		
		// Fetch the amount for the given archanaitype_id from the database
		// $this->db->select('amount');
		// $this->db->where('id', $archanaitype_id);
		// $query = $this->db->get('kattalai_archanai');  // Replace with your actual table
		$query = $this->db->table('kattalai_archanai')->where('id', $archanaitype_id)->get()->getRowArray();
		// if ($query->num_rows() > 0) {
		// 	$result = $query->row();
		// 	echo json_encode(['amount' => $result->amount]);
		// } else {
		// 	echo json_encode(['amount' => 0]);
		// }
		echo json_encode(['amount' => $query['amount']]);
	}
	public function fetch_amount() {
		$archanaitype_id = $_POST['archanaitype_id'];
		
		$query = $this->db->table('kattalai_archanai')->where('id', $archanaitype_id)->get()->getRowArray();
	
		if ($query) {
			echo json_encode(['amount' => $query['amount']]);
		} else {
			echo json_encode(['amount' => 0, 'error' => 'Archanaitype not found']);
		}
	}

	public function save_booking()
	{
		// echo "<pre>";
		// print_r($_POST);
		// exit;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$data = [];
		if (empty($_POST['name']) || empty($_POST['rasi_id'])) {
			$this->session->setFlashdata('fail', 'Please Try Again');
			$msg_data['err'] = 'Please enter devotee details';
		} 
		elseif (empty($_POST['pay_method']) || empty($_POST['tot_amt'])) {
			$this->session->setFlashdata('fail', 'Please Try Again');
			$msg_data['err'] = 'Please enter payment details';
		}  else {
			$yr = date('Y');
			$mon = date('m');

			$data['archanai_type_id'] = $_POST['archanaitype_id'];
			$data['date'] = date('Y-m-d');
			$query = $this->db->query("SELECT ref_no FROM kattalai_archanai_booking where id=(select max(id) from kattalai_archanai_booking where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
			$data['ref_no'] = 'KAR' . date('y', strtotime(date('Y-m-d'))) . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
			$data['deity_id'] = $_POST['deity_id'];
			$data['name'] = $_POST['devotee_name'];
			$data['mobile_no'] = $_POST['contact_no'];
			$data['email'] = $_POST['email'];
			$data['description'] = $_POST['description'];
			$data['amount'] = $_POST['tot_amt'];
			$data['daytype'] = $_POST['day'];
			$data['added_by'] = $this->session->get('log_id_frend');
			$data['payment_mode'] = $payment_mode = $_POST['pay_method'];
			$payment_method = $this->db->table('payment_mode')->where("id", $payment_mode)->get()->getRowArray();
			$pay_method = $payment_method['name'];

			$data['created'] = date('Y-m-d H:i:s');
			$data['booking_through'] = "COUNTER";
			$data['payment_type'] = $_POST['payment_type'];
			//$data['payment_status'] = (($pay_method == 'cash' || $pay_method == 'online' || $pay_method == 'qr' || $pay_method == 'nets_pay' || $pay_method == 'pay_now' || $pay_method == 'cheque') ? 2 : 1);

			if (!empty($_POST['day'])) {
				$day = $_POST['day'];
				switch ($day) {
					case 'daily':
						if (!empty($_POST['sdate']) && !empty($_POST['edate'])) {
							$sdate = date('Y-m-d', strtotime($_POST['sdate']));
							$edate = date('Y-m-d', strtotime($_POST['edate']));

							$sdate = \DateTime::createFromFormat('d/m/Y', $_POST['sdate'])->format('Y-m-d');
							$edate = \DateTime::createFromFormat('d/m/Y', $_POST['edate'])->format('Y-m-d');
							$data['no_of_days'] = $_POST['tdate'];
							$data['start_date'] = $sdate;
							$data['end_date'] = $edate;
							
							$this->db->table('kattalai_archanai_details')->insert($data);
						}
						break;
			
					case 'weekly':
						if (!empty($_POST['wsdate']) && !empty($_POST['wedate']) && !empty($_POST['numDays'])) {
							// Convert wsdate and wedate from dd/mm/yyyy to Y-m-d format
							$wsdate = \DateTime::createFromFormat('d/m/Y', $_POST['wsdate'])->format('Y-m-d');
							$wedate = \DateTime::createFromFormat('d/m/Y', $_POST['wedate'])->format('Y-m-d');
			
							$data['dayofweek'] = $_POST['dayOfWeek'];
							$data['start_date'] = $wsdate;
							$data['end_date'] = $wedate;
							$data['no_of_days'] = $_POST['numDays'];
						}
						break;
			
					case 'years':
						if (!empty($_POST['yearsdate']) && !empty($_POST['yearedate']) && !empty($_POST['ydate'])) {
							$yearsdate = \DateTime::createFromFormat('d/m/Y', $_POST['yearsdate'])->format('Y-m-d');
							$yearedate = \DateTime::createFromFormat('d/m/Y', $_POST['yearedate'])->format('Y-m-d');
							$data['start_date'] = $yearsdate;
							$data['end_date'] = $yearedate;
							$data['no_of_days'] = $_POST['ydate'];
						}
						break;
			
					case 'days':
						$data['no_of_days'] = $_POST['totalDays'];
						break;
			
					default:
						$this->session->setFlashdata('fail', 'Invalid day type.');
						$msg_data['err'] = 'Please select a valid day type.';
						$msg_data['status'] = false;
						$msg_data['success'] = false;
						return;
				}
			
			} else {
				$this->session->setFlashdata('fail', 'Day selection is required.');
				$msg_data['err'] = 'Please select a day type.';
				$msg_data['status'] = false;
				$msg_data['success'] = false;
			}
			// var_dump($data);
			// exit;
			$res = $this->db->table('kattalai_archanai_booking')->insert($data);
			$booking_id = $this->db->insertID();

			if($res){
				$pay_details = array();
				$payment_mode_details = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getRowArray();
				$pay_details['booking_id'] = $booking_id;
				$pay_details['archanai_type_id'] = $data['archanai_type_id'];
				$pay_details['payment_mode_id'] = $payment_mode;
				$pay_details['is_repayment'] = 0;
				$pay_details['paid_through'] = 'COUNTER';
				$pay_details['pay_status'] = 2;
				$pay_details['payment_mode_title'] = $payment_mode_details['name'];
				$pay_details['booking_ref_no'] = $data['ref_no'];
				if($data['payment_type'] == 'partial') $pay_details['amount'] = $_POST['paid_amount'];
				else $pay_details['amount'] = $data['amount'];

				$pay_details['paid_date'] = date('Y-m-d');
				$this->requestmodel = new RequestModel();
				$ip = $this->requestmodel->getIpAddress();
				$pay_details['ip'] = $ip;
				if ($ip != 'unknown') {
				$ip_details = $this->requestmodel->getLocation($ip);
				$pay_details['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
				$pay_details['ip_details'] = json_encode($ip_details);
				}
				$res_3 = $this->db->table('kattalai_archanai_pay_details')->insert($pay_details);

				$booking_ref_data = array();
				$booking_ref_data['paid_amount'] = $pay_details['amount'];
				$booking_ref_data['booking_status'] = 1;
				if($data['payment_type'] == 'partial') $booking_ref_data['payment_status'] = 1;
								else $booking_ref_data['payment_status'] = 2;
				$this->db->table("kattalai_archanai_booking")->where('id', $booking_id)->update($booking_ref_data);

				if (!empty($_POST['multiDatePicker'])) {
					$dates = explode(',', $_POST['multiDatePicker']); 
					
					foreach ($dates as $date) {
						$formatted_date = date('Y-m-d', strtotime(str_replace('/', '-', trim($date))));
						$date_data = [
							'booking_id' => $booking_id,
							'date' => $formatted_date
						];
		
						$this->db->table('kattalai_archanai_dates')->insert($date_data);
					}
				}

				if (!empty($_POST['deities']) && !empty($_POST['deity_name'])) {
					$ids = $_POST['deities'];
					$names = $_POST['deity_name'];
				
					foreach ($ids as $index => $id) {
						$id = $ids[$index];
						$name = $names[$index];
				
						if (empty($id) || empty($name)) {
							continue;  
						}
				
						$data = [
							'booking_id' => $booking_id,
							'deity_id' => $id,
							'deity_name' => $name,
						];
				
						$this->db->table('kattalai_archanai_deity_details')->insert($data);
					}
				}

				if (!empty($_POST['name']) && !empty($_POST['rasi_id'])) {
					$names = $_POST['name'];
					$rasi_ids = $_POST['rasi_id'];
					$natchathra_ids = $_POST['natchathra_id'];
				
					foreach ($names as $index => $name) {
						$rasi_id = $rasi_ids[$index];
						$natchathra_id = $natchathra_ids[$index];
				
						if (empty($name) || empty($rasi_id) || empty($natchathra_id)) {
							continue;  
						}
				
						$data = [
							'booking_id' => $booking_id,
							'archanai_type_id' => $_POST['archanaitype_id'],
							'name' => $name,
							'rasi' => $rasi_id,
							'natchathiram' => $natchathra_id
						];
				
						$this->db->table('kattalai_archanai_details')->insert($data);
					}
				}

				$payment_gateway_data = array();
				$payment_gateway_data['booking_id'] = $booking_id;
				$payment_gateway_data['pay_method'] = $pay_method;
				$this->db->table('kattalai_archanai_payment_gateway_datas')->insert($payment_gateway_data);
				$prasadam_payment_gateway_id = $this->db->insertID();
				$this->account_migration($booking_id);
			}
			$this->session->setFlashdata('succ', 'Booking Successfully Done');
			$msg_data['succ'] = 'Booking Successfully Done';
			$msg_data['id'] = $booking_id;
			$msg_data['status'] = true;
			$msg_data['success'] = true;
		}
		echo json_encode($msg_data);
		exit();
	}

	public function account_migration($ins_id)
	{
		$yr = date('Y');
        $mon = date('m');
		$data = $this->db->table('kattalai_archanai_booking')->where('id', $ins_id)->get()->getRowArray();
		$payment_mode_details = $this->db->table('payment_mode')->where('id', $data['payment_mode'])->get()->getRowArray();
		$sales_group = $this->db->table('groups')->where('code', '4000')->get()->getRowArray();
		
		if(!empty($sales_group)){
			$sls_id = $sales_group['id'];
		} else {
			$sls1['parent_id'] = 0;
			$sls1['name'] = 'Sales';
			$sls1['code'] = '4000';
			$sls1['added_by'] = $this->session->get('log_id');
			$this->db->table('groups')->insert($sls1);
			$sls_id = $this->db->insertID();
		}
	
		//$is_partial = $data['payment_type']; // Assuming 'is_partial' field in 'prasadam' table indicates if it's a partial payment
		$archanai_booking_details = $this->db->table('kattalai_archanai_pay_details')->where('booking_id', $ins_id)->get()->getResultArray();
		
		foreach ($archanai_booking_details as $abd) {
			// $prasadam_details = $this->db->table('prasadam_setting')->where('id', $pbd['prasadam_id'])->get()->getRowArray();
			$archanai_details = $this->db->table('kattalai_archanai')->where('id', $abd['archanai_type_id'])->get()->getRowArray();

			if(!empty($archanai_details['ledger_id'])){
				$dr_id = $archanai_details['ledger_id'];
			} else {
				$ledger1 = $this->db->table('ledgers')->where('name', 'All Sales')->where('group_id', $sls_id)->get()->getRowArray();
				if(!empty($ledger1)){
					$dr_id = $ledger1['id'];
				} else {
					$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '4913')->orderBy('right_code','desc')->get()->getRowArray();
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
	
			$cr_id = $payment_mode_details['ledger_id'];
			$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
      		$number1 = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
		
			if (empty($number) && empty($number1)) {
				$num = 1;
        		$num1 = 1;
			} else {
				$num = $number['number'] + 1;
        		$num1 = $number1['number'] + 1;
			}
	
			$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();
			$entries['entry_code'] = 'REC' . date('y', strtotime($data['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
			$entries['entrytype_id'] = '1';
			$entries['number'] = $num;
			$entries['date'] = date("Y-m-d", strtotime($data['date']));
			
			if ($data['payment_type'] == 'partial') {
				// For Partial Payment
				$td_ledger = $this->db->table('ledgers')->where('name', 'TRADE RECEIVABLE')->where('group_id', 3)->where('left_code', '1200')->get()->getRowArray();
				if (!empty($td_ledger)) {
					$trade_receivable_id = $td_ledger['id'];
				} else {
					$cled1['group_id'] = 3;
					$cled1['name'] = 'TRADE RECEIVABLE';
					$cled1['code'] = '1200/005';
					$cled1['op_balance'] = '0';
					$cled1['op_balance_dc'] = 'D';
					$cled1['left_code'] = '1200';
					$cled1['right_code'] = '005';
					$this->db->table('ledgers')->insert($cled1);
					$trade_receivable_id = $this->db->insertID();
				}
				
				// Transfer total amount to Trade Receivable
				$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();
				$entries['entry_code'] = 'JOR' . date('y', strtotime($data['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
				$entries['date'] = date("Y-m-d", strtotime($data['date']));
				$entries['number'] = $num1;
				$entries['entrytype_id'] = '4';
				$entries['dr_total'] = $data['amount']; // Assuming 'total_amount' is the field for total booking amount
				$entries['cr_total'] = $data['amount'];
				$entries['narration'] = 'Kattalai Archanai(' . $data['ref_no'] . ')';
				$entries['inv_id'] = $ins_id;
				$entries['type'] = '13';
				$ent = $this->db->table('entries')->insert($entries);
				$en_id1 = $this->db->insertID();
  
				  // Transfer total amount to Trade Receivable
		  		$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();
				$entries['entry_code'] = 'REC' . date('y', strtotime($data['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
				$entries['date'] = date("Y-m-d", strtotime($data['date']));
				$entries['number'] = $num;
				$entries['entrytype_id'] = '1';
				$entries['dr_total'] = $abd['amount']; // Assuming 'total_amount' is the field for total booking amount
				$entries['cr_total'] = $abd['amount'];
				$entries['narration'] = 'Kattalai Archanai(' . $data['ref_no'] . ')';
				$entries['inv_id'] = $ins_id;
				$entries['type'] = '13';
				$ent = $this->db->table('entries')->insert($entries);
				$en_id2 = $this->db->insertID();
	
				if (!empty($en_id1) && !empty($en_id2)) {
					// Debit the Product's Ledger (dr_id)
					$eitems_d['entry_id'] = $en_id1;
					$eitems_d['ledger_id'] = $dr_id;
					$eitems_d['amount'] = $data['amount'];
					$eitems_d['details'] = 'Kattalai Archanai(' . $data['ref_no'] . ')';
					$eitems_d['dc'] = 'C';
					$cr_res = $this->db->table('entryitems')->insert($eitems_d);
	
					// Credit Trade Receivable (trade_receivable_id)
					$eitems_c['entry_id'] = $en_id1;
					$eitems_c['ledger_id'] = $trade_receivable_id;
					$eitems_c['amount'] = $data['amount'];
					$eitems_c['details'] = 'Kattalai Archanai(' . $data['ref_no'] . ')';
					$eitems_c['dc'] = 'D';
					$deb_res = $this->db->table('entryitems')->insert($eitems_c);
					
					// Now, handle the partial payment amount
					// Debit Trade Receivable
					$eitems_d['entry_id'] = $en_id2;
					$eitems_d['ledger_id'] = $trade_receivable_id;
					$eitems_d['amount'] = $abd['amount'];
					$eitems_d['details'] = 'Kattalai Archanai(' . $data['ref_no'] . ')';
					$eitems_d['dc'] = 'C';
					$cr_res = $this->db->table('entryitems')->insert($eitems_d);
	
					// Credit Payment Mode (cr_id)
					$eitems_c['entry_id'] = $en_id2;
					$eitems_c['ledger_id'] = $cr_id;
					$eitems_c['amount'] = $abd['amount'];
					$eitems_c['details'] = 'Kattalai Archanai(' . $data['ref_no'] . ')';
					$eitems_c['dc'] = 'D';
					$deb_res = $this->db->table('entryitems')->insert($eitems_c);
				}
			} else {
				// For Full Payment (the usual process)
				$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();
			  	$entries['entry_code'] = 'REC' . date('y', strtotime($data['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
			  	$entries['date'] = date("Y-m-d", strtotime($data['date']));
				$entries['number'] = $num;
				$entries['entrytype_id'] = '1';
				$entries['dr_total'] = $data['amount'];
				$entries['cr_total'] = $data['amount'];
				$entries['narration'] = 'Kattalai_archanai(' . $data['ref_no'] . ')';
				$entries['inv_id'] = $ins_id;
				$entries['type'] = '13';
				$ent = $this->db->table('entries')->insert($entries);
				$en_id = $this->db->insertID();
	
				if (!empty($en_id)) {
					$ent_id[] = $en_id;
	
					$eitems_d['entry_id'] = $en_id;
					$eitems_d['ledger_id'] = $dr_id;
					$eitems_d['amount'] = $data['amount'];
					$eitems_d['details'] = 'Kattalai_archanai(' . $data['ref_no'] . ')';
					$eitems_d['dc'] = 'C';
					$cr_res = $this->db->table('entryitems')->insert($eitems_d);
	
					$eitems_c['entry_id'] = $en_id;
					$eitems_c['ledger_id'] = $cr_id;
					$eitems_c['amount'] = $data['amount'];
					$eitems_c['details'] = 'Kattalai_archanai(' . $data['ref_no'] . ')';
					$eitems_c['dc'] = 'D';
					$deb_res = $this->db->table('entryitems')->insert($eitems_c);
				}
			}
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
				$annathanam_details = $this->db->table("kattalai_archanai_booking")->where('id', $booking_id)->get()->getRowArray();
				if($annathanam_details['amount'] >= ($annathanam_details['paid_amount'] + $pay_amount)){
					$booking_payment_ins_data = array();
					$booking_payment_ins_data['booking_id'] = $booking_id;
					$booking_payment_ins_data['is_repayment'] = 1;
					$booking_payment_ins_data['booking_ref_no'] = $annathanam_details['ref_no'];
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

					$this->paid_amount += $booking_payment_ins_data['amount'];
					$res = $this->db->table("kattalai_archanai_pay_details")->insert($booking_payment_ins_data);
					$booked_pay_id = $this->db->insertID();
					$this->db->query("UPDATE kattalai_archanai_booking SET paid_amount = paid_amount + ? WHERE id = ?", [$pay_amount, $booking_id]);
					$query = $this->db->table('kattalai_archanai_booking')->where('id', $booking_id)->get()->getRowArray();
					if ($query['amount'] == $query['paid_amount']) {
						$this->db->query("UPDATE kattalai_archanai_booking SET payment_status = 2 WHERE id = ?", [$booking_id]);
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
		$booked_pay_details_cnt = $this->db->table("kattalai_archanai_pay_details")->where("id", $booked_pay_id)->get()->getNumRows();	
		if ($booked_pay_details_cnt > 0) {
			$booked_pay_details = $this->db->table("kattalai_archanai_pay_details")->where("id", $booked_pay_id)->get()->getResultArray();
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
			$booking_id = $booked_pay_details[0]['booking_id'];
			$prasadam = $this->db->table("kattalai_archanai_booking")->where("id", $booking_id)->get()->getRowArray();
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
					$entries['narration'] = 'Kattalai Archanai(' . $prasadam['ref_no'] . ')';
					$entries['inv_id'] = $booking_id;
					$entries['type'] = 13;
					//Insert Entries
					$ent = $this->db->table('entries')->insert($entries);
					$en_id = $this->db->insertID();
					if (!empty($en_id)) {
						// Trade Debtors => Credit
						$eitems_hall_book['entry_id'] = $en_id;
						$eitems_hall_book['ledger_id'] = $cr_id1;
						$eitems_hall_book['amount'] = $row['amount'];
						$eitems_hall_book['dc'] = 'C';
						$eitems_hall_book['details'] = 'Kattalai Archanai Amount';
						$this->db->table('entryitems')->insert($eitems_hall_book);
						// PETTY CASH => Debit 
						$eitems_cash_led['entry_id'] = $en_id;
						$eitems_cash_led['ledger_id'] = $paymentmode['ledger_id'];
						$eitems_cash_led['amount'] = $row['amount'];
						$eitems_cash_led['dc'] = 'D';
						$eitems_cash_led['details'] = 'Kattalai Archanai Amount';
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

	public function gtpaymentdata()
    {
      $id = $_POST['id'];
      $res = $this->db->table("kattalai_archanai_booking")->where("id", $id)->get()->getRowArray();
      $amt = $res['amount'];
      $data['amt'] = $amt;
      $res1 = $this->db->table("kattalai_archanai_pay_details")->selectSum('amount')->where("booking_id", $id)->get()->getRowArray();
      $paid_amount = $res1['amount'];
      $data['paid_amount'] = $paid_amount;
      $data['bal_amount'] = $amt - $paid_amount;

      echo json_encode($data);
    }

	public function print_booking_report($id)
	{
		//$id=  $this->request->uri->getSegment(3);
		$tmpid = 1;
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
	    $data['data'] = $this->db->table('kattalai_archanai_booking')
							->select('kattalai_archanai_booking.*, kattalai_archanai.name_eng, kattalai_archanai.name_tamil, kattalai_archanai.amount as unit_price')
							->join('kattalai_archanai', 'kattalai_archanai.id = kattalai_archanai_booking.archanai_type_id', 'left')
							->where('kattalai_archanai_booking.id', $id)
							->get()
							->getRowArray();

		$data['deities'] = $this->db->table('kattalai_archanai_deity_details as kadd')
							->select('kadd.deity_name')
							->where('kadd.booking_id', $id)
							->get()
							->getResultArray();

		$data['dates'] = $this->db->table('kattalai_archanai_booking as kab')
							->join('kattalai_archanai_dates as kad', 'kad.booking_id = kab.id', 'left')
							->where('kab.daytype', 'days')
							->get()
							->getResultArray();

		$data['details'] = $this->db->table('kattalai_archanai_details')
							->select('kattalai_archanai_details.*, rasi.name_eng as rasi, natchathram.name_eng as natchathiram')
							->join('rasi', 'rasi.id = kattalai_archanai_details.rasi', 'left')
							->join('natchathram', 'natchathram.id = kattalai_archanai_details.natchathiram', 'left')
							->where('kattalai_archanai_details.booking_id', $id)
							->get()
							->getResultArray();
		
		$data['deity_count'] = $this->db->table('kattalai_archanai_deity_details as kadd')
							->where('kadd.booking_id', $id)
							->countAllResults();
		
		echo view('kattalai_archanai/print_page',$data);
	}

	public function print_booking($arch_book_id)
	{
		$id = $this->request->uri->getSegment(3);
		// $data['qry1'] = $archanai_booking = $this->db->table('archanai_booking')->where('id', $id)->get()->getRowArray();
		$view_file = 'frontend/kattalai_archanai/print_imin';
		
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		$data['archanai_book_id'] = $id;
		$data['data'] = $this->db->table('kattalai_archanai_booking')
					->select('kattalai_archanai_booking.*, kattalai_archanai.name_eng, kattalai_archanai.name_tamil, kattalai_archanai.amount as unit_price')
					->join('kattalai_archanai', 'kattalai_archanai.id = kattalai_archanai_booking.archanai_type_id', 'left')
					->where('kattalai_archanai_booking.id', $id)
					->get()
					->getRowArray();
		
		
		$data['dates'] = $this->db->table('kattalai_archanai_dates')->select('date')->where('booking_id', $id)->get()->getResultArray();

		$data['deities'] = $this->db->table('kattalai_archanai_deity_details as kadd')
					->join('archanai_diety as ad', 'ad.id = kadd.deity_id', 'left')
					->select('ad.name, ad.name_tamil')
					->where('kadd.booking_id', $id)
					->get()
					->getResultArray();

		$data['details'] = $this->db->table('kattalai_archanai_details')
					->select('kattalai_archanai_details.*, rasi.name_eng as rasi, natchathram.name_eng as natchathiram')
					->join('rasi', 'rasi.id = kattalai_archanai_details.rasi', 'left')
					->join('natchathram', 'natchathram.id = kattalai_archanai_details.natchathiram', 'left')
					->where('kattalai_archanai_details.booking_id', $id)
					->get()
					->getResultArray();

		$data['deity_count'] = $this->db->table('kattalai_archanai_deity_details as kadd')
					->where('kadd.booking_id', $id)
					->countAllResults();
		
		echo view($view_file, $data);

	// 		} elseif ($archanai_booking['payment_status'] == '1') {
	// 			$archanai_payment_gateway_datas = $this->db->table('archanai_payment_gateway_datas')->where('archanai_booking_id', $arch_book_id)->get()->getRowArray();
	// 			if (!empty($archanai_payment_gateway_datas['reference_id'])) {
	// 				$reference_id = $archanai_payment_gateway_datas['reference_id'];
	// 				$result_data = $this->initiatePayment_response($reference_id);
	// 				$response_data = json_decode($result_data, true);
	// 				$payment_gateway_up_data = array();
	// 				$payment_gateway_up_data['response_data'] = $result_data;
	// 				$this->db->table('archanai_payment_gateway_datas')->where('id', $archanai_payment_gateway_datas['id'])->update($payment_gateway_up_data);
	// 				if (!empty($response_data['status'])) {
	// 					if ($response_data['status'] == 'completed') {
	// 						$archanai_booking_up_data = array();
	// 						$archanai_booking_up_data['payment_status'] = 2;
	// 						$this->db->table('archanai_booking')->where('id', $id)->update($archanai_booking_up_data);
	// 						$this->account_migration($id);
	// 						$tmpid = $this->session->get('profile_id');
	// 						$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
	// 						$data['archanai_book_id'] = $id;
	// 						$data['booking'] = $this->db->table('archanai_booking_details', 'archanai', 'archanai_booking_rasi', 'rasi', 'natchathram')
	// 							->join('archanai', 'archanai.id = archanai_booking_details.archanai_id', 'left')
	// 							->join('archanai_diety', 'archanai_diety.id = archanai_booking_details.diety_id', 'left')
	// 							->where('archanai_booking_details.archanai_booking_id', $id)
	// 							->select('archanai.*, archanai_diety.name as diety_name,archanai_diety.name_tamil as diety_tamil')
	// 							->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
	// 							->get()
	// 							->getResultArray();
	// 						$data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
	// 							->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
	// 							->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
	// 							->where('archanai_booking_rasi.archanai_booking_id', $id)
	// 							->select('archanai_booking_rasi.*')
	// 							->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
	// 							->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
	// 							->get()
	// 							->getResultArray();
	// 						$data['vehicles'] = $this->db->table('archanai_booking_vehicle')
	// 							->where('archanai_booking_vehicle.archanai_booking_id', $id)
	// 							->select('archanai_booking_vehicle.*')
	// 							->get()
	// 							->getResultArray();
	// 						echo view($view_file, $data);
	// 					} else {
	// 						$archanai_booking_up_data = array();
	// 						$archanai_booking_up_data['payment_status'] = 3;
	// 						$this->db->table('archanai_booking')->where('id', $id)->update($archanai_booking_up_data);
	// 						redirect()->to("/cancelled_booking");
	// 						exit;
	// 					}
	// 				}
	// 			} else {
	// 				redirect()->to("/cancelled_booking");
	// 				exit;
	// 			}
	// 		}
	// 	} else {
	// 		$tmpid = $this->session->get('profile_id');
	// 		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
	// 		$data['archanai_book_id'] = $id;
	// 		$data['booking'] = $this->db->table('archanai_booking_details', 'archanai', 'archanai_booking_rasi', 'rasi', 'natchathram')
	// 			->join('archanai', 'archanai.id = archanai_booking_details.archanai_id', 'left')
	// 			->join('archanai_diety', 'archanai_diety.id = archanai_booking_details.diety_id', 'left')
	// 			->where('archanai_booking_details.archanai_booking_id', $id)
	// 			->select('archanai.*,archanai_diety.name as diety_name,archanai_diety.name_tamil as diety_tamil')
	// 			->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
	// 			->get()
	// 			->getResultArray();
	// 		$data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
	// 			->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
	// 			->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
	// 			->where('archanai_booking_rasi.archanai_booking_id', $id)
	// 			->select('archanai_booking_rasi.*')
	// 			->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
	// 			->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
	// 			->get()
	// 			->getResultArray();
	// 		//echo $this->db->getLastQuery();
	// 		//echo "<pre>"; print_r($data); exit();
	// 		$data['vehicles'] = $this->db->table('archanai_booking_vehicle')
	// 			->where('archanai_booking_vehicle.archanai_booking_id', $id)
	// 			->select('archanai_booking_vehicle.*')
	// 			->get()
	// 			->getResultArray();
	// 		echo view($view_file, $data);
	// 	}
	}


}    
    
    
    
    