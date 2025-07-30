<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\RequestModel;
use App\Models\Common_model;

class Donation_online extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		helper('common_helper');
		$this->model = new PermissionModel();
		$this->common_model = new Common_model();
		if (($this->session->get('log_id_frend')) == false) {
			$data['dn_msg'] = 'Please Login';
			return redirect()->to('/member_login');
		}
	}
	/*
	public function pledge_view()
	{
	    
	    $check_user = $this->db->table("favourite")->where("");
	}
	*/
	//init pledge
	public function pledge_post()
	{
	    if(!empty($_POST['total_amount']) && !empty($_POST['pay_method']) && !empty($_POST['pay_for']))
	    {
	    $phonecode = $_POST["phonecode"];
	    $mobile = $_POST["mobile"];
	    $customer_no = $this->db->table("pledge")->where("phone_code",$phonecode)->where("mobile",$mobile)->get()->getRowArray();
	    if(floatval($_POST["pledge_amount"]) == 0)
	    {
	        $msg_data['pay_status'] = false;
			$msg_data['err'] = 'Pledge amount must not be empty';
		
		    echo json_encode($msg_data);
		    exit();
	    }
	    
	    if(isset($customer_no["phone_code"])) //existing phonecode
	    {
		   
	       $pledge_amount = floatval($customer_no["pledge_amount"]);
	       $cpledge = $pledge_amount + floatval($_POST["pledge_amount"]);
	       $current_total_amt = (floatval($customer_no["balance_amt"]) + floatval($_POST["pledge_amount"]));
	       $bamt = (floatval($customer_no["balance_amt"]) + floatval($_POST["pledge_amount"])) - floatval($_POST["total_amount"]);
	       
	       $paid_pledge = (floatval($_POST["pledge_amount"])>0?floatval($_POST["pledge_amount"]):0);
	       $cur_donation = floatval($_POST["total_amount"]);
	       $inp = [
	            //"name" => $_POST["name"],
	            //"phonecode" => $_POST["phonecode"],
	            //"mobile" => $_POST["mobile"],
	            //"email_id" => $_POST["email_id"],
	            //"ic_or_passport" => $_POST["ic_number"],
	            //"address" => $_POST["address"],
	            //"description" => $_POST["description"],
	            "balance_amt" => (floatval($bamt)>0?$bamt:0),
	            "pledge_amount" => ($cpledge>0?$cpledge:0),
	            "updated_date" => Date("Y-m-d H:i:s")
	            ];
	            
	            $this->db->table("pledge")->where("phone_code",$phonecode)->where("mobile",$mobile)->update($inp);
	            $pledge_id = $customer_no["id"];
	            
	    }
	    else
	    {
	        $current_total_amt = (floatval($_POST["pledge_amount"])>0?(floatval($_POST["pledge_amount"])):0);
	        $bamt = (floatval($_POST["pledge_amount"])>0?(floatval($_POST["pledge_amount"])-floatval($_POST["total_amount"])):0);
	        $cpledge = (floatval($_POST["pledge_amount"])>0?floatval($_POST["pledge_amount"]):0);
	        $inp = [
	            "name" => $_POST["name"],
	            "phone_code" => $_POST["phonecode"],
	            "mobile" => $_POST["mobile"],
	            "email_id" => $_POST["email_id"],
	            "ic_or_passport" => $_POST["ic_number"],
	            "address" => $_POST["address"],
	            "description" => $_POST["description"],
	            "pledge_amount" => $cpledge,
	            "balance_amt" => $bamt,
	            "created_date" => Date("Y-m-d H:i:s")
	            ];
	            $this->db->table("pledge")->insert($inp);
	            $pledge_id = $this->db->insertID();
	            $paid_pledge = (floatval($_POST["pledge_amount"])>0?floatval($_POST["pledge_amount"]):0);
	            $cur_donation = floatval($_POST["total_amount"]);
	    }
	    
	    $this->db->table("pledge_entry")->insert([
	        "entry_date" => Date("Y-m-d"),
	        "donated_pledge_amt"=>$paid_pledge,
	        "current_total_amt"=>$current_total_amt,
	        "current_donation_amount"=>$cur_donation,
	        "pledge_id"=>$pledge_id,
	        "pledge_type"=>intval($_POST["pay_for"]),
	        "created_at"=>Date("Y-m-d H:i:s")
	        ]);
	    $pledge_entry_id = intval($this->db->insertID());
	    
	        if($pledge_entry_id>0 && intval($_POST['total_amount'])>0)
	            $this->save($pledge_entry_id);
	    }
	    else
	    {
	        echo "Field is Required";
	        exit;
	    }
	    //$data["customer_no"] = $customer_no;
	}
	public function payment_check_pledge()
	{
		$pledge_id = $_POST["pledge_id"];

		$customer_no = $this->db->table("pledge")->where("id", $pledge_id)->get()->getRowArray();

		$res = ["amt" => 0, "paid_amount" => 0, "balance_amount" => 0];

		if (isset($customer_no["phone_code"])) {
			$pledge_amount = floatval($customer_no["pledge_amount"]);
			$balance_amount = floatval($customer_no["balance_amt"]);

			// ✅ Calculate paid amount correctly
			$paid_amount = $pledge_amount - $balance_amount;

			$res["amt"] = $pledge_amount;              // Total pledge amount
			$res["paid_amount"] = $paid_amount;       // Amount already paid 
			$res["balance_amount"] = $balance_amount; // Remaining balance
		}

		echo json_encode($res, true);
		exit;
	}
	public function pledge_balance_post()
	{
	    if(!empty($_POST['total_amount']) && intval($_POST['total_amount'])>0 && !empty($_POST['pay_method']) && !empty($_POST['pay_for']))
	    {
	    $pledge_id = $_POST["pledge_id"];
	    //$phonecode = $_POST["phonecode"];
	    //$mobile = $_POST["mobile"];
	    $customer_no = $this->db->table("pledge")->where("id",$pledge_id)->get()->getRowArray();
	    if(floatval($_POST["pledge_amount"]) > 0)
	    {
	        $msg_data['pay_status'] = false;
			$msg_data['err'] = 'Pledge amount must not be empty';
		
		    echo json_encode($msg_data);
		    exit();
	    }
	    
	    if(isset($customer_no["phone_code"])) //existing phonecode
	    {
	       
	       $paid_pledge = 0;
	       $pledge_amount = $cur_pledge = floatval($customer_no["balance_amt"]);
	       $bal_amt = $pledge_amount - floatval($_POST["total_amount"]);
	       $cur_donation = floatval($_POST["total_amount"]);
	       
	       $inp = [
	            //"name" => $_POST["name"],
	            //"phonecode" => $_POST["phonecode"],
	            //"mobile" => $_POST["mobile"],
	            //"email_id" => $_POST["email_id"],
	            //"ic_or_passport" => $_POST["ic_number"],
	            //"address" => $_POST["address"],
	            //"description" => $_POST["description"],
	            //"pledge_amount" => $_POST["pledge_amount"],
	            "balance_amt" => ($bal_amt>0?$bal_amt:0),
	            "updated_date" => Date("Y-m-d H:i:s")
	            ];
	            
	            $this->db->table("pledge")
	            ->where("id",$pledge_id)
	            //->where("phone_code",$phonecode)->where("mobile",$mobile)
	            ->update($inp);
	            $pledge_id = $customer_no["id"];
	            
	    }
	    else
	    {
	        $msg_data['pay_status'] = false;
		    $msg_data['err'] = 'User not exist';
		
		    echo json_encode($msg_data);
		    exit();
	        
	    }
	    
	    $this->db->table("pledge_entry")->insert([
	        "entry_date" => $_POST["date"],
	        "donated_pledge_amt"=>$paid_pledge,
	        "current_total_amt"=>$cur_pledge,
	        "current_donation_amount"=>$cur_donation,
	        "pledge_id"=>$pledge_id,
	        "pledge_type"=>intval($_POST["pay_for"]),
	        "created_at"=>Date("Y-m-d H:i:s")
	        ]);
	    $pledge_entry_id = intval($this->db->insertID());
	    //echo "tt";
	    if($pledge_entry_id>0)
	        $this->save($pledge_entry_id);
	    }
	    else
	    {
	        echo "Field is Required";
	        exit;
	    }    
	    //$data["customer_no"] = $customer_no;
	}
	
	/*
	public function pledge_post()
	{
	    
	    $phonecode = $_POST["phonecode"];
	    $mobile = $_POST["mobile"];
	    $customer_no = $this->db->table("pledge")->where("phone_code",$phonecode)->where("mobile",$mobile)->get()->getRowArray();
	    
	    if(isset($customer_no["phone_code"])) //existing phonecode
	    {
	       $pledge_amount = floatval($customer_no["pledge_amount"]);
	       if(floatval($_POST["pledge_amount"]) == 0)
	       {
	           $bal_amt = $pledge_amount - floatval($_POST["total_amount"]);
	       }
	       else if(floatval($_POST["pledge_amount"]) > 0 && floatval($_POST["total_amount"]) > 0)
	       {
	           $bal_amt = ($pledge_amount + floatval($_POST["pledge_amount"])) - floatval($_POST["total_amount"]);
	       }
	       else
	       {
	           $bal_amt = $pledge_amount + floatval($_POST["pledge_amount"]);
	       }
	       
	       $inp = [
	            //"name" => $_POST["name"],
	            //"phonecode" => $_POST["phonecode"],
	            //"mobile" => $_POST["mobile"],
	            "email_id" => $_POST["email_id"],
	            "ic_or_passport" => $_POST["ic_number"],
	            "address" => $_POST["address"],
	            "description" => $_POST["description"],
	            "pledge_amount" => $bal_amt,
	            "updated_date" => Date("Y-m-d H:i:s")
	            ];
	            
	            $this->db->table("pledge")->where("phone_code",$phonecode)->where("mobile",$mobile)->update($inp);
	            $pledge_id = $customer_no["id"];
	    }
	    else
	    {
	        
	        $inp = [
	            "name" => $_POST["name"],
	            "phone_code" => $_POST["phonecode"],
	            "mobile" => $_POST["mobile"],
	            "email_id" => $_POST["email_id"],
	            "ic_or_passport" => $_POST["ic_number"],
	            "address" => $_POST["address"],
	            "description" => $_POST["description"],
	            "pledge_amount" => $_POST["pledge_amount"],
	            "created_date" => Date("Y-m-d H:i:s")
	            ];
	            $this->db->table("pledge")->insert($inp);
	            $pledge_id = $this->db->insertID();
	    }
	    
	    $this->db->table("pledge_entry")->insert(["pledge_id"=>$pledge_id,"pledge_type"=>intval($_POST["pay_for"]),"created_at"=>Date("Y-m-d H:i:s")]);
	    $pledge_entry_id = intval($this->db->insertID());
	    if($pledge_entry_id>0 && !empty($_POST['total_amount']) && intval($_POST['total_amount'])>0 && !empty($_POST['pay_method']) && !empty($_POST['pay_for']))
	        $this->save($pledge_entry_id);
	        
	    //$data["customer_no"] = $customer_no;
	}
	public function pledge_post_update()
	{
	    $id = $_POST["id"];
	    $phonecode = $_POST["phonecode"];
	    $mobile = $_POST["mobile"];
	    $dat1 = $this->db->table("pledge_entry")->where("id",$id)->get()->getRowArray();
	    
	    if(isset($dat1["id"])) //existing phonecode
	    {
	       $customer_no = $this->db->table("pledge")->where("pledge_id",$id)->get()->getRowArray();
	       $dat2 = $this->db->table("donation")->where("pludge_id",$id)->get()->getRowArray();
	       if(!isset($customer_no["pledge_amount"]) && !isset($dat2["pludge_id"]))
	           return;
	       
	       $pledge_amount = floatval($customer_no["pledge_amount"]);
	       $amt2 = floatval($dat2["total_amount"]);
	       $rem_amt = floatval($_POST['total_amount']) - $amt2;
	       $pledge_amount += $rem_amt;
	       $inp = [
	            //"name" => $_POST["name"],
	            //"phonecode" => $_POST["phonecode"],
	            //"mobile" => $_POST["mobile"],
	            "email_id" => $_POST["email_id"],
	            "ic_or_passport" => $_POST["ic_number"],
	            "address" => $_POST["address"],
	            "description" => $_POST["description"],
	            "pledge_amount" => floatval($pledge_amount),
	            "updated_date" => Date("Y-m-d H:i:s")
	            ];
	            
	            $this->db->table("pledge")->where("pledge_id",$id)->update($inp);
	            $pledge_id = $customer_no["id"];
	    
	    
	    $this->db->table("pledge_entry")->where("id",$id)->update(["updated_at"=>Date("Y-m-d H:i:s")]);
	    if(!empty($_POST['total_amount']) && intval($_POST['total_amount'])>0 && !empty($_POST['pay_method']) && !empty($_POST['pay_for']))
	        $this->update();
	    }
	        
	    //$data["customer_no"] = $customer_no;
	}
	*/
	public function index()
	{
		exit;
		$data['list'] = $this->db->table('donation', 'donation_setting.name as pname')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname')
			->select('donation.*')
			->orderBy('date', 'DESC')
			->get()->getResultArray();
		echo view('frontend/layout/header');
		echo view('frontend/donation/index', $data);
		echo view('frontend/layout/footer');
	}
	public function add()
	{
		$login_id = $_SESSION['log_id_frend'];
		$data['payment_mode'] = $this->db->table('payment_mode')->where("paid_through", "COUNTER")->where("donation", 1)->where('status', 1)->get()->getResultArray();
		$default_group = $this->db->query("SELECT * FROM cashdonation_group ORDER BY id ASC LIMIT 1")->getRowArray();
		$data['default'] = str_replace(' ', '_', strtolower($default_group['name']));

		// Get all groups
		$group = $this->db->query("SELECT * FROM cashdonation_group ORDER BY name ASC")->getResultArray();

		// Initialize the sett_don array
		$data['sett_don'] = [];
		$settings = $this->db->table('settings')->where('type', 2)->get()->getResultArray();
		$setting_array = array();
		if (count($settings) > 0) {
			foreach ($settings as $item) {
				$setting_array[$item['setting_name']] = $item['setting_value'];
			}
		}
		$data['setting'] = $setting_array;

		// ADD THIS: Get profile tax_no to check if Tax Exempt Receipt should be shown
		$profile = $this->db->table('admin_profile')->select('tax_no')->where('id', 1)->get()->getRowArray();
		$data['profile_tax_no'] = !empty($profile['tax_no']) ? $profile['tax_no'] : '';

		// Fetch donation settings for each group with non-empty ledger_id
		foreach ($group as $row) {
			$settings = $this->db->table('donation_setting')
				->where('groupname', $row['name'])
				->where('ledger_id !=', '')
				->get()
				->getResultArray();
			if (!empty($settings)) {
				$data['sett_don'][$row['name']] = $settings;
			}
		}

		// Fetch phone codes
		$data['phone_codes'] = $this->db->table("phone_code")
			->orderBy('dailing_code', 'ASC')
			->get()
			->getResultArray();

		// Fetch recent donations made by the current user
		$data['reprintlists'] = $this->db->query("
        SELECT id, amount, ref_no, date 
        FROM donation 
        WHERE added_by = '$login_id' 
        AND paid_through = 'COUNTER' 
        AND payment_status = 2 
        ORDER BY id DESC 
        LIMIT 3
    ")->getResultArray();

		// Render views
		echo view('frontend/layout/header');
		echo view('frontend/donation_new/index', $data);
		// echo view('frontend/layout/footer');
	}

    public function edit_pledge($id)
    {
        $login_id = $_SESSION['log_id_frend'];
		$data['payment_mode'] = $this->db->table('payment_mode')->where("paid_through", "COUNTER")->where("donation", 1)->where('status', 1)->get()->getResultArray();
		$default_group = $this->db->query("SELECT * FROM cashdonation_group ORDER BY id ASC LIMIT 1")->getRowArray();
		$data['default'] = str_replace(' ', '_', strtolower($default_group['name']));

		// Get all groups
		$group = $this->db->query("SELECT * FROM cashdonation_group ORDER BY name ASC")->getResultArray();

		// Initialize the sett_don array
		$data['sett_don'] = [];
		$settings = $this->db->table('settings')->where('type', 2)->get()->getResultArray();
		$setting_array = array();
		if (count($settings) > 0) {
			foreach ($settings as $item) {
				$setting_array[$item['setting_name']] = $item['setting_value'];
			}
		}
		$data['setting'] = $setting_array;
		// Fetch donation settings for each group with non-empty ledger_id
		foreach ($group as $row) {
			$settings = $this->db->table('donation_setting')
				->where('groupname', $row['name'])
				->where('ledger_id !=', '')
				->get()
				->getResultArray();
			if (!empty($settings)) {
				$data['sett_don'][$row['name']] = $settings;
			}
		}

		// Fetch phone codes
		$data['phone_codes'] = $this->db->table("phone_code")
			->orderBy('dailing_code', 'ASC')
			->get()
			->getResultArray();

		// Fetch recent donations made by the current user
		$data['reprintlists'] = $this->db->query("
        SELECT id, amount, ref_no, date 
        FROM donation 
        WHERE added_by = '$login_id' 
        AND paid_through = 'COUNTER' 
        AND payment_status = 2 
        ORDER BY id DESC 
        LIMIT 3
    ")->getResultArray();


        $dat = $this->db->table("pledge_entry")->join("pledge","pledge.id = pledge_entry.pledge_id")->where("pledge_entry.id",$id)->get()
        //echo $this->db->getLastQuery();
        //die("r");
        ->getRowArray();
        //$dat = $this->db->table("pledge")->where("id",$id)->get()->getRowArray();
        if(!isset($dat["id"]))
        {
            echo "Invalid Entry";
            exit;
        }
        
        $don_data = $this->db->table("donation")->select("target_amount,pay_for,payment_mode")->where("pledge_id",$id)->get()->getRowArray();
        $donation_setting = $this->db->table("donation_setting")->select("name,image,donation_cat_id")->where("id",$dat["pledge_type"])->get()->getRowArray();
        $data["pledge_data"] = $dat;
        $data["don_data"] = $don_data;
        
        
        echo view('frontend/layout/header');
		echo view('frontend/donation_new/edit_pledge', $data);
    }
	private function generateTaxReceiptNumber($date)
	{
		$date_parts = explode('-', $date);
		$yr = $date_parts[0];
		$mon = $date_parts[1];
		$year_short = date('y', strtotime($date));

		// Get the last tax receipt number for the given month and year
		$query = $this->db->query("
        SELECT tax_receipt_no 
        FROM donation 
        WHERE id = (
            SELECT MAX(id) 
            FROM donation 
            WHERE YEAR(date) = '$yr' 
            AND MONTH(date) = '$mon' 
            AND tax_receipt_no IS NOT NULL
            AND tax_receipt_no LIKE 'TX%'
        )
    ")->getRowArray();

		if (!empty($query['tax_receipt_no'])) {
			// Extract the number from the last receipt
			$last_number = (int) substr($query['tax_receipt_no'], -5);
			$next_number = $last_number + 1;
		} else {
			// First tax receipt for this month
			$next_number = 1;
		}

		// Format the receipt number
		$receipt_no = 'TX' . $year_short . $mon . sprintf("%05d", $next_number);

		return $receipt_no;
	}


	// Save function
	public function save($pledge_entry_id = 0)
	{
	    
		$msg_data = array();
		//$this->db->transStart();
		try{
			if(!empty($_POST['total_amount']) && !empty($_POST['pay_method']) && !empty($_POST['pay_for'])){
				$msg_data['err'] = '';
				$msg_data['succ'] = '';
				$date = !empty($_POST['date']) ? explode('-', $_POST['date']) : explode('-', date('Y-m-d'));
				$yr = $date[0];
				$mon = $date[1];
				$query = $this->db->query("SELECT ref_no FROM donation where id=(select max(id) from donation where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
				$data['ref_no'] = 'DO' . date('y', strtotime($_POST['date'])) . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
				// Generate tax receipt number if it's a tax-exempt donation
				$data['is_tax_redemption'] = !empty($_POST['is_tax_redemption']) ? 1 : 0;

				// Generate tax receipt number if it's a tax-exempt donation
				if ($data['is_tax_redemption'] == 1) {
					$data['tax_receipt_no'] = $this->generateTaxReceiptNumber(!empty($_POST['date']) ? $_POST['date'] : date('Y-m-d'));
				} else {
					$data['tax_receipt_no'] = null; // Ensure it's null if not tax exempt
				}
				$data['date'] = !empty($_POST['date']) ? $_POST['date'] : date('Y-m-d');
				$data['pay_for'] = trim($_POST['pay_for']);
				$data['name'] = trim($_POST['name']);
				$data['address'] = trim($_POST['address']);
				$data['ic_number'] = trim($_POST['ic_number']);
				$mble_phonecode = !empty ($_POST['phonecode']) ? $_POST['phonecode'] : "";
				$mble_number = !empty ($_POST['mobile']) ? $_POST['mobile'] : "";
				$data['payment_mode'] = $pay_id = $_POST['pay_method'];
				$payment_mode = $this->db->table('payment_mode')->where("id", $pay_id)->get()->getRowArray();
				$pay_method = $payment_mode['name'];
				$is_payment_gateway = $payment_mode['is_payment_gateway'];
				$payment_key = $payment_mode['pay_key'];

				$data['mobile'] = $mble_phonecode . $mble_number;
				$data['email'] = trim($_POST['email_id']);
				$data['description'] = trim($_POST['description']);
				$data['amount'] = trim($_POST['total_amount']);
				$data['target_amount'] = 0;
				$data['collected_amount'] = 0;
				$data['pledge_id'] = $pledge_entry_id;
				$data['paid_through'] = "COUNTER";
				$data['payment_status'] = empty($is_payment_gateway) ? 2 : 1;
				$data['added_by'] = $this->session->get('log_id_frend');
				$data['created'] = date('Y-m-d H:i:s');
				$data['modified'] = date('Y-m-d H:i:s');
				$data['is_tax_redemption'] = !empty($_POST['is_tax_redemption']) ? 1 : 0;
				//ip location and ip details
				$ip = 'unknown';
				$this->requestmodel = new RequestModel();
				$ip = $this->requestmodel->getIpAddress();
				if ($ip != 'unknown') {
					$ip_details = $this->requestmodel->getLocation($ip);
					$data['ip'] = $ip;
					$data['ip_location'] = (!empty ($ip_details['country']) ? $ip_details['country'] : 'Unknown');
					$data['ip_details'] = json_encode($ip_details);
				}

				// Proceed with saving data
				$this->db->table('donation')->insert($data);
				$ins_id = $this->db->insertID();

				$payment_gateway_data = array();
				$payment_gateway_data['donation_booking_id'] = $ins_id;
				$payment_gateway_data['pay_method'] = $pay_method;
				$payment_gateway_data['payment_mode'] = $pay_id;
				$res_3 = $this->db->table('donation_payment_gateway_datas')->insert($payment_gateway_data);
				$donation_payment_gateway_id = $this->db->insertID();
				
				$devotee_id = $this->devotee_save($data, $mble_phonecode, $mble_number);
				if (!empty($devotee_id)){
					$activity_details = json_encode([
						'type' => 'Cash Donation Received',
						'booking_id' => $ins_id
					]);
					$this->save_activity_log($devotee_id, 4, $activity_details);

					if ($res_3) {
						$this->devotee_payment_details($devotee_id, $payment_gateway_data, $data['ref_no'], $data['amount'], $data['payment_status']);
					}
				}
				
				if($payment_key == 'rhb_qr'){
					$ref_no = PAYMENT_PREFIX . '_DONA_' . $ins_id;
					if (PAYMENT_TEST) {
						$pay_amount = 1;
					} else {
						$pay_amount = bcdiv($data['amount'], '1', 2);
					}

					$bill_num = (string) ($ins_id + 200000000000);
					$url = 'https://dnqr.synexisasia.com/v1/qr';

					$json_data = [
						"merchantCode" => RHB_MERCHANTCODE,
						"userId" => RHB_USERID,
						"amount" => $pay_amount,
						"billNumber" => $bill_num,
						"transactionReference" => $ref_no,
					];

					// Assuming common_repository->postJson() posts JSON and returns JSON string
					//$response = $this->common_model->postJson($url, $json_data);
					//$response = json_decode($response);
					$response = "";
					if (!empty($response->qrCode)) {
						$payment_gateway_up_data = [
							'request_data' => json_encode($json_data),
						];

						$this->db->table('donation_payment_gateway_datas')
							->where('id', $donation_payment_gateway_id)
							->update($payment_gateway_up_data);

						$msg_data['qr_code'] = $response->qrCode;
						$msg_data['total_amount'] = bcdiv($data['amount'], '1', 2);
					} else {
						$msg_data = [
							'err' => 'QR code not generated. Kindly rebook the ticket.',
						];
						// return json_encode($msg_data);
					}
					$msg_data['pay_status'] = false;
					$msg_data['payment_key'] = $payment_key;
				}else $msg_data['pay_status'] = true;

				if ($data['payment_status'] == 2) {
					//$this->account_migration($ins_id);
				//	$this->send_whatsapp_msg($ins_id);
				//	$this->send_mail_to_customer($ins_id);
				}
				//$this->db->transComplete();
				// $this->session->setFlashdata('succ', 'Donation Added Successfully');
				$msg_data['succ'] = $msg_data['message'] = 'Donation Added Successfully';
				$msg_data['status'] = true;
				$msg_data['id'] = $ins_id;
				$msg_data['is_tax_redemption'] = $data['is_tax_redemption'];
			}else{
				$this->db->transRollback();
				$msg_data['pay_status'] = false;
				$msg_data['err'] = 'Please fill all required fields';
			}
		}catch (Exception $e) {
			//$this->db->transRollback(); // Rollback the transaction if an error occurs
			$msg_data['err'] = $e->getMessage(); 
		}
		echo json_encode($msg_data);
		exit();
	}

	public function devotee_save($data, $mble_phonecode, $mble_number) {
		if (!empty($data['name']) && !empty($mble_phonecode) && !empty($mble_number)) {
			$existing_devotee = $this->db->table('devotee_management')
										->where('phone_code', $mble_phonecode)
										->where('phone_number', $mble_number)
										->get()
										->getRowArray();

			if ($existing_devotee) {
				$update_data = [];

				if (empty($existing_devotee['dob']) && !empty($data['dob']) ) {
					$update_data['dob'] = $data['dob'];
				}
				if (empty($existing_devotee['email']) && !empty($data['email'])) {
					$update_data['email'] = $data['email'];
				}
				if (empty($existing_devotee['ic_no']) && !empty($data['ic_number'])) {
					$update_data['ic_no'] = $data['ic_number'];
				}
				if (empty($existing_devotee['address']) && !empty($data['address'])) {
					$update_data['address'] = $data['address'];
				}

				if ($existing_devotee['is_member'] == 0) {
					$mobile = $mble_phonecode . $mble_number;
					$member = $this->db->table('member')->where('mobile', $mobile)->get()->getRowArray();
					if ($member) {
						$update_data = [
						'is_member' => 1,
						'member_id' => $member['id']
						];
					}
				}

				if (!empty($update_data)) {
					$update_data['updated_by'] = $this->session->get('log_id_frend');
					$update_data['updated_at'] = date('Y-m-d H:i:s');
					$dvt_update = $this->db->table('devotee_management')->where('id', $existing_devotee['id'])->update($update_data);

					if ($dvt_update) {
						$updated_fields = [];

						if (isset($update_data['dob'])) {
							$updated_fields['dob'] = $data['dob'];
						}
						if (isset($update_data['email'])) {
							$updated_fields['email'] = $data['email'];
						}
						if (isset($update_data['address'])) {
							$updated_fields['address'] = $data['address'];
						}
						if (isset($update_data['ic_no'])) {
							$updated_fields['ic_no'] = $data['ic_number'];
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
					'phone_code' => !empty($mble_phonecode) ? $mble_phonecode : null,
					'phone_number' => !empty($mble_number) ? $mble_number : null,
					'email' => !empty($data['email']) ? $data['email'] : null,
					'ic_no' => !empty($data['ic_number']) ? $data['ic_number'] : null,
					'address' => !empty($data['address']) ? $data['address'] : null,
					'user_module_tag' => 2,
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

	public function save_activity_log($devotee_id, $activity_type, $activity_fields = null) {
		$activity = array();
		$activity['devotee_id'] = $devotee_id;
		$activity['date'] = date('Y-m-d');
		$activity['time'] = date('H:i:s');  
		$activity['module_type'] = 2; 
		$activity['activity_type'] = $activity_type; 
		$activity['details'] = $activity_fields;
		$activity['added_through'] = 'COUNTER';
		$activity['created_by'] = $this->session->get('log_id_frend');
		$activity['created_at'] = date('Y-m-d H:i:s');

		$this->db->table('devotee_activity')->insert($activity);
	}

	public function devotee_payment_details($devotee_id, $pay_details, $ref_no, $amount, $pay_status) {
		if (!empty($devotee_id) && !empty($pay_details)) {

			$devotee_pay['devotee_id'] = $devotee_id;
			$devotee_pay['module_type'] = 2;
			$devotee_pay['booking_id'] = $pay_details['donation_booking_id'];
			$devotee_pay['ref_no'] = $ref_no;
			$devotee_pay['paid_date'] = date('Y-m-d');
			$devotee_pay['is_repayment'] = 0;
			$devotee_pay['amount'] = $amount;
			$devotee_pay['payment_mode_id'] = $pay_details['payment_mode'];
			$devotee_pay['payment_mode_title'] = $pay_details['pay_method'];
			$devotee_pay['pay_status'] = $pay_status;
			$devotee_pay['paid_through'] = 'COUNTER';
			$devotee_pay['created_by'] = $this->session->get('log_id_frend');
			$devotee_pay['created_at'] = date('Y-m-d H:i:s');

			$this->db->table('devotee_payment_details')->insert($devotee_pay);      
		}
	}

	public function get_devotee_details() {
		$phone_code = $_POST['code'];
		$phone_number = $_POST['number'];
		$dev_data = $this->db->table('devotee_management')->where('phone_code', $phone_code)->where('phone_number', $phone_number)->get()->getRowArray();
		
		$msg_data['name'] = $dev_data['name'];
		$msg_data['ic_no'] = $dev_data['ic_no'];
		$msg_data['email'] = $dev_data['email'];
		$msg_data['address'] = $dev_data['address'];
		echo json_encode($msg_data);
		exit();
	}

	public function print_tax_exempt($donation_id)
	{
		$donation = $this->db->query("
        SELECT d.*, ds.name as donation_name, pm.name as payment_method_name
        FROM donation d 
        LEFT JOIN donation_setting ds ON d.pay_for = ds.id
        LEFT JOIN payment_mode pm ON d.payment_mode = pm.id
        WHERE d.id = ?", [$donation_id])->getRowArray();

		// if (!$donation) {
		// 	die('Donation not found');
		// }

		// Get admin profile data including tax_no
		$admin_profile = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

		$data['donation'] = $donation;
		$data['admin_profile'] = $admin_profile;
		$data['amount_in_words'] = $this->numberToWords($donation['amount']);

		echo view('frontend/donation/tax_exempt_receipt', $data);
	}

	// Helper function to convert number to words
	private function numberToWords($number)
	{
		$number = (float) $number;
		$whole = floor($number);
		$decimal = round(($number - $whole) * 100);

		$words = $this->convertNumberToWords($whole);

		if ($decimal > 0) {
			$words .= ' and ' . $this->convertNumberToWords($decimal) . ' sen';
		}

		return 'Ringgit Malaysia ' . ucfirst($words) . ' only';
	}

	private function convertNumberToWords($number)
	{
		$ones = array(
			0 => '',
			1 => 'one',
			2 => 'two',
			3 => 'three',
			4 => 'four',
			5 => 'five',
			6 => 'six',
			7 => 'seven',
			8 => 'eight',
			9 => 'nine',
			10 => 'ten',
			11 => 'eleven',
			12 => 'twelve',
			13 => 'thirteen',
			14 => 'fourteen',
			15 => 'fifteen',
			16 => 'sixteen',
			17 => 'seventeen',
			18 => 'eighteen',
			19 => 'nineteen'
		);

		$tens = array(
			0 => '',
			1 => '',
			2 => 'twenty',
			3 => 'thirty',
			4 => 'forty',
			5 => 'fifty',
			6 => 'sixty',
			7 => 'seventy',
			8 => 'eighty',
			9 => 'ninety'
		);

		if ($number < 20) {
			return $ones[$number];
		} elseif ($number < 100) {
			return $tens[intval($number / 10)] . ' ' . $ones[$number % 10];
		} elseif ($number < 1000) {
			return $ones[intval($number / 100)] . ' hundred ' . $this->convertNumberToWords($number % 100);
		} elseif ($number < 1000000) {
			return $this->convertNumberToWords(intval($number / 1000)) . ' thousand ' . $this->convertNumberToWords($number % 1000);
		} elseif ($number < 1000000000) {
			return $this->convertNumberToWords(intval($number / 1000000)) . ' million ' . $this->convertNumberToWords($number % 1000000);
		} else {
			return $this->convertNumberToWords(intval($number / 1000000000)) . ' billion ' . $this->convertNumberToWords($number % 1000000000);
		}
	}
	public function print_individual_report()
	{
	    $pledge_id = $_POST["pledge_id"];
	    $customer_data = $this->db->table("pledge")->where("id",$pledge_id)->get()->getRowArray();
	    if(!isset($customer_data))
	    {
	        echo "User not found";
	        exit;
	    }
	    
	    $name = $customer_data["name"];
	    $data = $this->db->table("pledge_entry")->join("pledge","pledge.id = pledge_entry.pledge_id")->where("pledge_id",$pledge_id)->get()->getResultArray();
	    $res = [];
	    $res['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
	    if(isset($_POST["submit_print_imim"])) //print imim
	    {
	       $res["data"] = $data;
	       $res["name"] = $name;
	       echo view('frontend/donation_new/pledge_ind_imim', $res); 
	    }
	    else if(isset($_POST["submit_print_pdf"])) //print pdf
	    {
	        $res["data"] = $data;
	        $res["name"] = $name;
	        $file_name = "Pledge_Report_".$name;
			$dompdf = new \Dompdf\Dompdf();
			$options = $dompdf->getOptions();
			$options->set(array('isRemoteEnabled' => true));
			$dompdf->setOptions($options);
			$dompdf->loadHtml(view('frontend/donation_new/pledge_ind_pdf', $res));
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			$dompdf->stream($file_name);
	    }
	    else if(isset($_POST["submit_print_excel"])) //print excel
	    {
	        $fileName = "Pledge_Report_".$name;
			$spreadsheet = new Spreadsheet();

			$sheet = $spreadsheet->getActiveSheet();
			$sheet->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$style = array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				)
			);

			$sheet->getStyle("A1:H1")->applyFromArray($style);
			$sheet->mergeCells('A1:H1');
			$sheet->setCellValue('A1', "Pledge Report");
			$sheet->setCellValue('A2', 'S.No');
			$sheet->setCellValue('B2', 'Date');
			$sheet->setCellValue('C2', 'Name');
			$sheet->setCellValue('D2', 'Phone');
			$sheet->setCellValue('E2', 'Pledge Init');
			$sheet->setCellValue('F2', 'Pledge Amount');
			$sheet->setCellValue('G2', 'Donated Amount');
			$sheet->setCellValue('H2', 'Balance Amount');
			$rows = 3;
			$si = 1;
			//$excel_format_data = $this->excel_format_get_archanai_report($data['fdate'], $data['tdate']);
			// var_dump($excel_format_data);
			// exit;
			foreach ($data as $val) {
			    $bal_amt = floatval($val['current_total_amt']) -floatval($val['current_donation_amount']);
				$sheet->setCellValue('A' . $rows, $si++);
				$sheet->setCellValue('B' . $rows, $val['date']);
				$sheet->setCellValue('C' . $rows, $val['name']);
				$sheet->setCellValue('D' . $rows, $val['phone_code'].$val['mobile']);
				$sheet->setCellValue('E' . $rows, $val['donated_pledge_amt']);
				$sheet->setCellValue('F' . $rows, $val['current_total_amt']);
				$sheet->setCellValue('G' . $rows, $val['current_donation_amount']);
				$sheet->setCellValue('H' . $rows, ($bal_amt>0?$bal_amt:0));
				$rows++;
				$si++;
			}
			$writer = new Xlsx($spreadsheet);
			$writer->save('uploads/excel/' . $fileName . '.xlsx');
			return $this->response->download('uploads/excel/' . $fileName . '.xlsx', null)->setFileName($fileName . '.xlsx');
	    }
	    else //normal print
	    {
	        
	        $res["data"] = $data;
	        $res["name"] = $name;
	        
	        echo view('frontend/donation_new/pledge_ind_print', $res); 
	    }
	    
	}
	
	
	public function print_pledge_report()
	{
	    
	    $from_date = isset($_POST["from_date"])?$_POST["from_date"]:Date("Y-m-d");
        $to_date = isset($_POST["to_date"])?$_POST["to_date"]:Date("Y-m-d");
        //$payfor = isset($_POST["payfor"])?intval($_POST["payfor"]):0;
        $mobile = isset($_POST["mobile_no"])?($_POST["mobile_no"]):'';
        $status = isset($_POST["status"])?intval($_POST["status"]):0;
        
        $data = $this->db->table("pledge")
                ->select("id,name,phone_code,mobile,pledge_amount,balance_amt,created_date")
                ->where("Date(created_date) >=",$from_date)
                ->where("Date(created_date) <=",$to_date);
        if($mobile != "")
            $data = $data->like("concat(phone_code,mobile)", $mobile);
        
        if($status > 0)
            $data = $data->where(($status==1?"balance_amt > 0":"balance_amt <= 0"));
            
        $data = $data->orderBy("id","desc")->get()
        //echo $this->db->getLastQuery();
        //die("r");
        ->getResultArray();
	    
	    $name = $customer_data["name"];
	    //$data = $this->db->table("pledge_entry")->join("pledge","pledge.id = pledge_entry.pledge_id")->where("pledge_id",$pledge_id)->get()->getResultArray();
	    $res = [];
	    $res['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
	    if(isset($_POST["submit_print_imim"])) //print imim
	    {
	       $res["data"] = $data;
	       $res["name"] = $name;
	       echo view('frontend/donation_new/pledge_imim', $res); 
	    }
	    else if(isset($_POST["submit_print_pdf"])) //print pdf
	    {
	        $res["data"] = $data;
	        $res["name"] = $name;
	        $file_name = "Pledge_Report_".$name;
			$dompdf = new \Dompdf\Dompdf();
			$options = $dompdf->getOptions();
			$options->set(array('isRemoteEnabled' => true));
			$dompdf->setOptions($options);
			$dompdf->loadHtml(view('frontend/donation_new/pledge_pdf', $res));
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			$dompdf->stream($file_name);
	    }
	    else if(isset($_POST["submit_print_excel"])) //print excel
	    {
	        $fileName = "Pledge_Report_".$name;
			$spreadsheet = new Spreadsheet();

			$sheet = $spreadsheet->getActiveSheet();
			$sheet->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$style = array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				)
			);

			$sheet->getStyle("A1:H1")->applyFromArray($style);
			$sheet->mergeCells('A1:H1');
			$sheet->setCellValue('A1', "Pledge Report");
			$sheet->setCellValue('A2', 'S.No');
			$sheet->setCellValue('B2', 'Date');
			$sheet->setCellValue('C2', 'Name');
			$sheet->setCellValue('D2', 'Phone');
			$sheet->setCellValue('E2', 'Pledge Amount');
			$sheet->setCellValue('F2', 'Donated Amount');
			$sheet->setCellValue('G2', 'Balance Amount');
			$rows = 3;
			$si = 1;
			//$excel_format_data = $this->excel_format_get_archanai_report($data['fdate'], $data['tdate']);
			// var_dump($excel_format_data);
			// exit;
			$paid_amt = floatval($row['pledge_amount']) - floatval($row['balance_amt']);
			foreach ($data as $val) {
			    $bal_amt = floatval($val['current_total_amt']) -floatval($val['current_donation_amount']);
				$sheet->setCellValue('A' . $rows, $si++);
				$sheet->setCellValue('B' . $rows, $val['date']);
				$sheet->setCellValue('C' . $rows, $val['name']);
				$sheet->setCellValue('D' . $rows, $val['phone_code'].$val['mobile']);
				$sheet->setCellValue('E' . $rows, $val['pledge_amount']);
				$sheet->setCellValue('F' . $rows, $paid_amt);
				$sheet->setCellValue('G' . $rows, $val['balance_amt']);
				$rows++;
				$si++;
			}
			$writer = new Xlsx($spreadsheet);
			$writer->save('uploads/excel/' . $fileName . '.xlsx');
			return $this->response->download('uploads/excel/' . $fileName . '.xlsx', null)->setFileName($fileName . '.xlsx');
	    }
	    else //normal print
	    {
	        
	        $res["data"] = $data;
	        $res["name"] = $name;
	        
	        echo view('frontend/donation_new/pledge_print', $res); 
	    }
	    
	}
	public function getIndPledgeList($pledge_id)
	{
	    //$data = $this->db->table("pledge_entry")->join("pledge","pledge.id = pledge_entry.pledge_id")->where("pledge_id",$pledge_id)->get()->getResultArray();
	    $data["pledge_id"] = $pledge_id;
	    //echo view('frontend/layout/header');
		echo view('frontend/donation_new/ind_pledge_list', $data);
	}
	//#123
	public function getIndPledgeListRep()
	{
	    
	    $pledge_id = $_POST["pledge_id"];
	    $dat = $this->db->table("pledge_entry")->join("pledge","pledge.id = pledge_entry.pledge_id")->where("pledge_id",$pledge_id)->get()->getResultArray();
	    
	    $data = [];
        $i = 1;
		foreach ($dat as $row) {
		    $donation_name = $dname[$row["id"]];
        
            $bal_amt = floatval($row['pledge_amount']) - floatval($row['balance_amt']);
			$data[] = array(
				$i++,
				date('d-m-Y', strtotime($row['created_date'])),
				$row['name'],
				$row['phone_code'].$row["mobile"],
				number_format($row['donated_pledge_amt'], '2', '.', ','),
				number_format($row['current_total_amt'], '2', '.', ','),
				number_format($row["current_donation_amount"], '2', '.', ','),
				number_format(($row['current_total_amt']-$row["current_donation_amount"]), '2', '.', ','),
                $print = '<a class="btn btn-primary btn-rad btn-payment" title="Repayment" onclick="getRepayment(\''.$row["phone_code"].'\',\''.$row["mobile"].'\',\''.$row["id"].'\',\''.$row["donation_name"].'\')"><i class="fa fa-credit-card"></i></a>
                <a class="btn btn-primary btn-rad btn-payment" title="Individual List" onclick="getIndList(\''.$row["id"].'\')"><i class="fa fa-credit-card"></i></a>
                ',				
				
			);
		
		}
 
		$result = array(
			"draw" => 0,
			"recordsTotal" => $i - 1,
			"recordsFiltered" => $i - 1,
			"data" => $data,
		);
		
		echo json_encode($result);
		exit();
	}
	public function update()
	{
	    	$msg_data = array();
		$this->db->transStart();
		try{
			if(!empty($_POST['total_amount']) && !empty($_POST['pay_method']) && !empty($_POST['pay_for'])){
				$msg_data['err'] = '';
				$msg_data['succ'] = '';
				$date = !empty($_POST['date']) ? explode('-', $_POST['date']) : explode('-', date('Y-m-d'));
				$yr = $date[0];
				$mon = $date[1];
				//$data['date'] = !empty($_POST['date']) ? $_POST['date'] : date('Y-m-d');
				$data['pay_for'] = trim($_POST['pay_for']);
				$data['name'] = trim($_POST['name']);
				$data['address'] = trim($_POST['address']);
				$data['ic_number'] = trim($_POST['ic_number']);
				$mble_phonecode = !empty ($_POST['phonecode']) ? $_POST['phonecode'] : "";
				$mble_number = !empty ($_POST['mobile']) ? $_POST['mobile'] : "";
				$data['payment_mode'] = $pay_id = $_POST['pay_method'];
				$payment_mode = $this->db->table('payment_mode')->where("id", $pay_id)->get()->getRowArray();
				$pay_method = $payment_mode['name'];
				$is_payment_gateway = $payment_mode['is_payment_gateway'];
				$payment_key = $payment_mode['pay_key'];

				$data['mobile'] = $mble_phonecode . $mble_number;
				$data['email'] = trim($_POST['email_id']);
				$data['description'] = trim($_POST['description']);
				$data['amount'] = trim($_POST['total_amount']);
				$data['target_amount'] = 0;
				$data['collected_amount'] = 0;
				//$data['paid_through'] = "COUNTER";
				//$data['payment_status'] = empty($is_payment_gateway) ? 2 : 1;
				$data['added_by'] = $this->session->get('log_id_frend');
				$data['created'] = date('Y-m-d H:i:s');
				$data['modified'] = date('Y-m-d H:i:s');

				//ip location and ip details
				$ip = 'unknown';
				$this->requestmodel = new RequestModel();
				$ip = $this->requestmodel->getIpAddress();
				if ($ip != 'unknown') {
					$ip_details = $this->requestmodel->getLocation($ip);
					$data['ip'] = $ip;
					$data['ip_location'] = (!empty ($ip_details['country']) ? $ip_details['country'] : 'Unknown');
					$data['ip_details'] = json_encode($ip_details);
				}


				// Proceed with saving data
				$this->db->table('donation')->where("pledge_id",$id)->update($data);
				
			
				if ($data['payment_status'] == 2) {
					//$this->account_migration($ins_id);
					//$this->send_whatsapp_msg($ins_id);
					//$this->send_mail_to_customer($ins_id);
				}
				$this->db->transComplete();
				// $this->session->setFlashdata('succ', 'Donation Added Successfully');
				$msg_data['succ'] = 'Donation Updated Successfully';
				$msg_data['id'] = $ins_id;
			}else{
				$this->db->transRollback();
				$msg_data['pay_status'] = false;
				$msg_data['err'] = 'Please fill all required fields';
			}
		}catch (Exception $e) {
			$this->db->transRollback(); // Rollback the transaction if an error occurs
			$msg_data['err'] = $e->getMessage(); 
		}
		echo json_encode($msg_data);
		exit();
	}
	public function send_mail_to_customer($id)
	{
		$donation = $this->db->table("donation")->where("id", $id)->get()->getRowArray();
		if (!empty ($donation['email'])) {
			$tmpid = 1;
			$temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
			$temple_title = "Temple " . $temple_details['name'];
			$qr_url = base_url() . "/donation/reg/";
			$mail_data['qr_image'] = qrcode_generation($id, $qr_url);
			$mail_data['don_id'] = $id;
			$mail_data['temple_details'] = $temple_details;
			$message = view('donation/mail_template', $mail_data);
			$to = $donation['email'];
			$subject = $temple_details['name'] . " Cash Donation";
			$to_mail = array("prithivitest@gmail.com", $to);
			send_mail_with_content($to_mail, $message, $subject, $temple_title);
		}
	}
	public function payment_process($don_book_id)
	{
		$donation_booking = $this->db->table('donation')->where('id', $don_book_id)->get()->getRowArray();
		$donation_payment_gateway_datas = $this->db->table('donation_payment_gateway_datas')->where('donation_booking_id', $don_book_id)->get()->getResultArray();
		if (count($donation_payment_gateway_datas) > 0) {
			if ($donation_payment_gateway_datas[0]['pay_method'] == 'adyen') {
				if (!empty ($donation_payment_gateway_datas[0]['request_data'])) {
					$request_data = $donation_payment_gateway_datas[0]['request_data'];
					$response = json_decode($request_data, true);
				} else {
					$tmpid = 1;
					$temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
					$result = $this->initiatePayment($donation_booking['amount'], $don_book_id, $temple_details['address1'] . $temple_details['address2'], $temple_details['city'], $temple_details['email']);
					$response = json_decode($result, true);
					$payment_gateway_up_data = array();
					$payment_gateway_up_data['request_data'] = $result;
					$payment_gateway_up_data['reference_id'] = $response['id'];
					$this->db->table('donation_payment_gateway_datas')->where('id', $donation_payment_gateway_datas[0]['id'])->update($payment_gateway_up_data);
				}
				if (!empty ($response['url']) && !empty ($response['id'])) {
					return redirect()->to($response['url']);
				}
			}elseif($donation_payment_gateway_datas[0]['pay_method'] == 'ipay_merch_qr'){
				//$view_file = 'frontend/ipay88/ipay_merch_qr';
				$view_file = 'frontend/ipay88/ipay_merch_qr_camera';
				$data['don_book_id'] = $don_book_id;
				$data['list'] = $this->db->table('payment_option')->where('status', 1)->get()->getResultArray();
				$data['submit_url'] = '/donation_online/initiate_ipay_merch_qr/' . $don_book_id;
				echo view($view_file, $data);
			}elseif($donation_payment_gateway_datas[0]['pay_method'] == 'ipay_merch_online'){
				$view_file = 'frontend/ipay88/ipay_merch_online';
				$data['id'] = $don_book_id;
				$data['controller'] = 'donation_online';
				echo view($view_file, $data);
			} else {
				// $redirect_url = base_url() . '/donation_online/print_booking/' . $don_book_id;
				$redirect_url = base_url() . '/donation_online/print_report_a5/' . $don_book_id;
				return redirect()->to($redirect_url);
			}
		} else {
			$tmpid = 1;
			$temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
			$result = $this->initiatePayment($donation_booking['amount'], $don_book_id, $temple_details['address1'] . $temple_details['address2'], $temple_details['city'], $temple_details['email']);
			$response = json_decode($result, true);
			if (!empty ($response['url']) && !empty ($response['id'])) {
				$payment_gateway_data = array();
				$payment_gateway_data['donation_booking_id'] = $don_book_id;
				$payment_gateway_data['pay_method'] = 'adyen';
				$payment_gateway_data['request_data'] = $result;
				$payment_gateway_data['reference_id'] = $response['id'];
				$this->db->table('donation_payment_gateway_datas')->insert($payment_gateway_data);
				$donation_payment_gateway_id = $this->db->insertID();
				if (!empty ($donation_payment_gateway_id)) {
					return redirect()->to($response['url']);
				}
			}
		}
	}
	public function ipay88_online_response($donation_id) {
		include_once FCPATH . 'app/Libraries/ipay88-master/IPay88.class.php';
		$MerchantCode = 'M01236';
		$MerchantKey = 'HQgUUZLVzg';
		$ipay88 = new \IPay88($MerchantCode);
		$ipay88->setMerchantKey($MerchantKey);
		$response = $ipay88->getResponse();
		//print_r($response);
		if($response['status']){
			$donation_up_data = array();
			$donation_up_data['payment_status'] = 2;
			$this->db->table('donation')->where('id', $donation_id)->update($donation_up_data);
			$this->account_migration($donation_id);
			$this->session->setFlashdata('succ', 'Donation Successfully Completed');
			$redirect_url = base_url() . '/archanai_booking/print_booking/' .$donation_id;
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
	public function initiate_ipay_merch_online($donation_id) {
		include_once FCPATH . 'app/Libraries/ipay88-master/IPay88.class.php';
		$payment_id = !empty($_REQUEST['payment_id']) ? $_REQUEST['payment_id'] : '';
		$donation_booking = $this->db->table('donation')->where('id', $donation_id)->get()->getRowArray();
		$email = !empty($donation_booking['email']) ? $donation_booking['email'] : 'dd@ipay88.com.my';
		$name = !empty($donation_booking['name']) ? $donation_booking['name'] : 'Prithivi';
		$mobile_no = !empty($donation_booking['mobile']) ? $donation_booking['mobile'] : '9856734562';
		$description='Donation';
		$final_amt = $donation_booking['amount'];
		$final_amount = number_format($final_amt, '2','.','');
		$final_amt_str = (string) ($final_amt * 1000);
		$MerchantCode = 'M01236';
		$MerchantKey = 'HQgUUZLVzg';
		$ref_no = 'DON_' . $donation_id;
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
		$ipay88->setField('UserName', $name);
		$ipay88->setField('UserEmail', $email);
		$ipay88->setField('UserContact', $mobile_no);
		$ipay88->setField('Remark', $description);
		$ipay88->setField('Lang', 'utf-8');
		$ipay88->setField('ResponseURL',  base_url() . '/donation_online/ipay88_online_response/' . $donation_id);
		$ipay88->setField('BackendURL',  base_url() . '/donation_online/ipay88_online_response/' . $donation_id);
		$ipay88->generateSignature();
		$ipay88_fields = $ipay88->getFields();
		$data['ipay88_fields'] = $ipay88_fields;
		$data['epayment_url'] = \Ipay88::$epayment_url;
		$view_file = 'frontend/ipay88/ipay_merch_online_process';
		echo view($view_file, $data);
	}
	public function initiatePayment($amount, $orderid, $address, $city, $email)
	{
		if (file_get_contents('php://input') != '') {
			$request = json_decode(file_get_contents('php://input'), true);
		} else {
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
			"reference" => $orderid,
			'countryCode' => "MY",
			'shopperReference' => "order_" . $orderid,
			'shopperEmail' => $email,
			'shopperLocale' => "en-US",
			"billingAddress" => [
				"street" => $address,
				"postalCode" => "46000",
				"city" => $city,
				"houseNumberOrName" => "1/23",
				"country" => "MY",
				"stateOrProvince" => "KL"
			],
			"deliveryAddress" => [
				"street" => $address,
				"postalCode" => "46000",
				"city" => $city,
				"houseNumberOrName" => "1/23",
				"country" => "MY",
				"stateOrProvince" => "KL"
			],
			'returnUrl' => base_url() . '/donation_online/print_booking/' . $orderid,
			'merchantAccount' => $merchantAccount
		];
		$json_data = json_encode($data);
		$curlAPICall = curl_init();
		curl_setopt($curlAPICall, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curlAPICall, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlAPICall, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($curlAPICall, CURLOPT_URL, $url);
		curl_setopt(
			$curlAPICall,
			CURLOPT_HTTPHEADER,
			array(
				"x-api-key: " . $apikey,
				"Content-Type: application/json",
				"Content-Length: " . strlen($json_data)
			)
		);
		$result = curl_exec($curlAPICall);
		if ($result === false) {
			throw new Exception(curl_error($curlAPICall), curl_errno($curlAPICall));
		}
		curl_close($curlAPICall);
		return $result;
	}
	public function initiatePayment_response($pay_id)
	{
		if (file_get_contents('php://input') != '') {
			$request = json_decode(file_get_contents('php://input'), true);
		} else {
			$request = array();
		}
		$apikey = "AQExhmfuXNWTK0Qc+iSGm3I5puqPTYhFHpxGTXFfyXa4nWlGJfnh+XuzwV6dTmmMJv6GnBDBXVsNvuR83LVYjEgiTGAH-09p02SzaBtpvbU0D3ZRFu8cWY44ivj4mqeMXogk0Ogk=-@e*vZIt9AWvaNN:.";
		$merchantAccount = "VivaantechsolutionscomECOM";
		$url = "https://checkout-test.adyen.com/v70/paymentLinks/" . $pay_id;
		$curlAPICall = curl_init();
		curl_setopt($curlAPICall, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($curlAPICall, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlAPICall, CURLOPT_URL, $url);
		// Api key
		curl_setopt(
			$curlAPICall,
			CURLOPT_HTTPHEADER,
			array(
				"x-api-key: " . $apikey
			)
		);
		$result = curl_exec($curlAPICall);
		if ($result === false) {
			throw new Exception(curl_error($curlAPICall), curl_errno($curlAPICall));
		}
		curl_close($curlAPICall);
		return $result;
	}
	public function account_migration($donation_id)
	{
		$donation = $this->db->table('donation')->where('id', $donation_id)->get()->getRowArray();
		if ($donation['paid_through'] == 'COUNTER') {
			$donation_payment_gateway_datas = $this->db->table('donation_payment_gateway_datas')->where('donation_booking_id', $donation_id)->get()->getRowArray();
			// if ($donation_payment_gateway_datas['pay_method'] == 'cash')
			// 	$payment_id = 6; ////  goto cash Ledger
			// elseif ($donation_payment_gateway_datas['pay_method'] == 'online')
			// 	$payment_id = 8; ////  goto online Ledger
			// elseif ($donation_payment_gateway_datas['pay_method'] == 'qr')
			// 	$payment_id = 9; ////  goto qr Ledger
			// elseif ($donation_payment_gateway_datas['pay_method'] == 'nets_pay')
			// 	$payment_id = 10; ////  goto nets Ledger
			// elseif ($donation_payment_gateway_datas['pay_method'] == 'pay_now')
			// 	$payment_id = 13; ////  goto Pay Now Ledger
			// elseif ($donation_payment_gateway_datas['pay_method'] == 'cheque')
			// 	$payment_id = 12; ////  goto Cheque Ledger
			// else
			// 	$payment_id = 4; ////  goto Qr or Online Payment Ledger

			$payment_mode_details = $this->db->table('payment_mode')->where('id', $donation_payment_gateway_datas['payment_mode'])->get()->getRowArray();
			if (empty ($payment_mode_details['id']))
				$payment_mode_details = $this->db->table('payment_mode')->get()->getRowArray();
			
			/* $ledger = $this->db->table('ledgers')->where('name', 'Donation')->where('group_id', 29)->where('left_code', '7012')->get()->getRowArray();
			if (!empty ($ledger)) {
				$dr_id = $ledger['id'];
			} else {
				$led['group_id'] = 29;
				$led['name'] = 'Donation';
				$led['left_code'] = '7012';
				$led['right_code'] = '000';
				$led['op_balance'] = '0';
				$led['op_balance_dc'] = 'D';
				$led_ins = $this->db->table('ledgers')->insert($led);
				$dr_id = $this->db->insertID();
			} */
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
			$donation_details = $this->db->table('donation_setting')->where('id', $donation['pay_for'])->get()->getRowArray();
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
			$number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
			if (empty ($number)) {
				$num = 1;
			} else {
				$num = $number['number'] + 1;
			}
			$date = explode('-', $donation['date']);
			$yr = $date[0];
			$mon = $date[1];
			$qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();
			$entries['entry_code'] = 'REC' . date('y', strtotime($donation['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));

			$entries['entrytype_id'] = '1';
			$entries['number'] = $num;
			$entries['date'] = $donation['date'];
			$entries['dr_total'] = $donation['amount'];
			$entries['cr_total'] = $donation['amount'];
			$entries['narration'] = 'Cash Donation(' . $donation['ref_no'] . ')' . "\n" . 'name:' . $donation['name'] . "\n" . 'NRIC:' . $donation['ic_number'] . "\n" . 'email:' . $donation['email'] . "\n";
			$entries['inv_id'] = $donation_id;
			$entries['type'] = '2';
			$ent = $this->db->table('entries')->insert($entries);
			$en_id = $this->db->insertID();
			if (!empty ($en_id)) {
				$eitems_d['entry_id'] = $en_id;
				$eitems_d['ledger_id'] = $dr_id;
				$eitems_d['amount'] = $donation['amount'];
				$eitems_d['details'] = 'Cash Donation(' . $donation['ref_no'] . ')';
				$eitems_d['dc'] = 'C';
				$this->db->table('entryitems')->insert($eitems_d);

				$eitems_c['entry_id'] = $en_id;
				$eitems_c['ledger_id'] = $payment_mode_details['ledger_id'];
				$eitems_c['details'] = 'Cash Donation(' . $donation['ref_no'] . ')';
				$eitems_c['amount'] = $donation['amount'];
				$eitems_c['dc'] = 'D';
				$this->db->table('entryitems')->insert($eitems_c);
			}
		}
	}
	public function print_booking($don_book_id)
	{

		$id = $this->request->uri->getSegment(3);

		$data['qry1'] = $donation = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname')
			->select('donation.*')
			->where('donation.id', $id)
			->get()->getRowArray();
		// $view_file = 'frontend/donation/print_page';
		$view_file = 'frontend/donation/print_imin';
		if ($donation['paid_through'] == 'COUNTER') {
			if ($donation['payment_status'] == '2') {
				//$data['qry2'] = $this->db->table('donation_details')->where('donation_id', $id)->get()->getResultArray();
				//echo "<pre>"; print_r($id); exit();
				$tmpid = $this->session->get('profile_id');
				$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
				$data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
				//echo $this->db->getLastQuery();
				//echo "<pre>"; print_r($data); exit();
				echo view($view_file, $data);
			} elseif ($donation['payment_status'] == '1') {
				$donation_payment_gateway_datas = $this->db->table('donation_payment_gateway_datas')->where('donation_booking_id', $don_book_id)->get()->getRowArray();
				if (!empty ($donation_payment_gateway_datas['reference_id'])) {
					$reference_id = $donation_payment_gateway_datas['reference_id'];
					$result_data = $this->initiatePayment_response($reference_id);
					$response_data = json_decode($result_data, true);
					$payment_gateway_up_data = array();
					$payment_gateway_up_data['response_data'] = $result_data;
					$this->db->table('donation_payment_gateway_datas')->where('id', $donation_payment_gateway_datas['id'])->update($payment_gateway_up_data);
					if (!empty ($response_data['status'])) {
						if ($response_data['status'] == 'completed') {
							$donation_up_data = array();
							$donation_up_data['payment_status'] = 2;
							$this->db->table('donation')->where('id', $id)->update($donation_up_data);
							$this->account_migration($id);
							$tmpid = $this->session->get('profile_id');
							$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
							$data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
							echo view($view_file, $data);
						} else {
							$donation_up_data = array();
							$donation_up_data['payment_status'] = 3;
							$this->db->table('donation')->where('id', $id)->update($donation_up_data);
							redirect()->to("/cancelled_booking");
							exit;
						}
					}
				} else {
					redirect()->to("/cancelled_booking");
					exit;
				}
			}
		} else {
			$tmpid = 1;
			$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
			//echo $this->db->getLastQuery();
			//echo "<pre>"; print_r($data); exit();
			echo view($view_file, $data);
		}
	}

	public function print_report($don_book_id)
	{
		$id = $this->request->uri->getSegment(3);
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		$data['data'] = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname, donation.amount, donation.name, donation.mobile')
			->select('donation.*')
			->where('donation.id', $id)
			->get()->getRowArray();
 $data['pay_details'] = $this->db->table("donation_payment_gateway_datas")->where("donation_booking_id", $id)->get()->getResultArray();
			// print_r($data['data']);
			// exit;
		echo view('frontend/donation/print_report', $data);
	}
	public function print_report_a5($id)
	{
		// Fetch the donation details by ID
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
		$data['data'] = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->join('donation_payment_gateway_datas as dpgd', 'dpgd.donation_booking_id = donation.id')
			->select('donation.*, donation_setting.name as pname, dpgd.pay_method')
			->where('donation.id', $id)
			->get()
			->getRowArray();

	

		echo view('frontend/report/donation_print_a5', $data);
		
	}

	public function get_donation_amount()
	{
		$id = $_POST['id'];
		$res = $this->db->table('donation_setting')->where('id', $id)->get()->getRowArray();
		echo !empty ($res['amount']) ? $res['amount'] : 0;
	}
	public function reprint_booking($id)
	{
		$data['qry1'] = $donation = $this->db->table('donation')
			->join('donation_setting', 'donation_setting.id = donation.pay_for')
			->select('donation_setting.name as pname')
			->select('donation.*')
			->where('donation.id', $id)
			->get()->getRowArray();
		// $view_file = 'frontend/donation/print_page';
		$view_file = 'frontend/donation/print_imin';
		$tmpid = 1;
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		$data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
		echo view($view_file, $data);
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
		if (!empty ($donation['mobile'])) {
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
	
	public function payment_check(){
		$data = [];
		if (!empty($_REQUEST['donation_id'])) {
			$booking_id = $_REQUEST['donation_id'];
			$user_id = $_SESSION['log_id_frend'];

			// Check if booking exists
			$donation_cnt = $this->db->table('donation')
				->where('id', $booking_id)
				->countAllResults(false); // false to NOT reset query builder to allow reuse if needed

			if ($donation_cnt > 0) {
				try {
					// Get booking record
					$donation = $this->db->table('donation')
						->where('id', $booking_id)
						->get()
						->getRow();

					// Get payment gateway data
					$payment_gateway_datas = $this->db->table('donation_payment_gateway_datas')->select('donation_payment_gateway_datas.*, payment_mode.pay_key')->join('payment_mode', 'payment_mode.id = donation_payment_gateway_datas.payment_mode', 'left')->where('donation_payment_gateway_datas.donation_booking_id', $booking_id)->get()->getRowArray();
					if ($donation->payment_status == 1) {
							if($payment_gateway_datas['pay_key'] == 'rhb_qr'){
								$rtn = $this->initiate_rhb_qr($booking_id, $donation, $payment_gateway_datas);
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
										'error_msg' => "We’re sorry! your payment didn’t went through, kindly try again. If payment has been deducted but Donation didn’t print. Kindly contact the Bank or Payment Gateway",
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
					}elseif ($donation->payment_status == 3) {
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
	public function initiate_rhb_qr($donation_id, $donation, $payment_gateway_datas){
		$request_data = json_decode($payment_gateway_datas['request_data']);
		$payment_gateway_datas_id = $payment_gateway_datas['id'];
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
			$this->db->table('donation_payment_gateway_datas')
			   ->where('id', $payment_gateway_datas_id)
			   ->update(['response_data' => $response]);
			$response_data = json_decode($response);

			if (!empty($response_data->paymentStatus)) {
				if ($response_data->paymentStatus === 'FOUND') {
					$rtn['status'] = 'success';

					// Update archanai_booking payment_status = 2
					$this->db->table('donation')
					   ->where('id', $donation_id)
					   ->update(['payment_status' => 2]);

					// Call migration functions
					$this->account_migration($donation_id);
					$this->send_whatsapp_msg($donation_id);
					$this->send_mail_to_customer($donation_id);

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

		if (!empty($_REQUEST['donation_id'])) {
			$booking_id = $_REQUEST['donation_id'];
			$user_id = $_SESSION['log_id_frend'];

			// Check if booking exists
			$donation_cnt = $this->db->table('donation')
				->where('id', $booking_id)
				->countAllResults(false); // false to NOT reset query builder to allow reuse if needed

			if ($donation_cnt > 0) {
				try {
					// Get booking record
					$donation = $this->db->table('donation')
						->where('id', $booking_id)
						->get()
						->getRow();

					// Get payment gateway data
					$payment_gateway_datas = $this->db->table('donation_payment_gateway_datas')
						->where('donation_booking_id', $booking_id)
						->get()
						->getRowArray();

					if ($donation->payment_status == 1) {
						if (!empty($payment_gateway_datas) && $payment_gateway_datas['pay_method'] === 'rhb_qr') {
							// Call your initiate_rhb_qr method (assumed exists in this class)
							$rtn = $this->initiate_rhb_qr($booking_id, $donation, $payment_gateway_datas);

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
								$this->db->table('donation')->where('id', $booking_id)->update(['payment_status' => 3]);

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
							$this->db->table('donation')->where('id', $booking_id)->update(['payment_status' => 3]);

							$data = [
								'status' => true,
								'pay_status' => false,
								'order_status' => 'failed',
								'org_msg' => 'Transaction Failed',
								'error_msg' => "We’re sorry! your payment is failed. Kindly try again.",
							];
							return json_encode($data);
						}
					} elseif ($donation->payment_status == 3) {
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
