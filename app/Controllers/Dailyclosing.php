<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Dailyclosing extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		helper('common');
		$this->model = new PermissionModel();
		if (($this->session->get('log_id')) == false && $this->session->get('role') != 1) {
			$data['dn_msg'] = 'Please Login';
			return redirect()->to('/login');
		}
		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
	}
	//future ref
    public function daily_donation($fdate, $tdate, $booking_type)
    {
        $builder = $this->db->table('donation_product ab')
    	    ->select("name as product_name,abd.product_id as product_id,ref_code,sum(quantity) as quantity,sum(abd.total_amount) as amount")
    	    ->join("donation_product_item abd","ab.id = abd.donation_prod_id")
    	    ->join("donation_setting a","a.id = abd.product_id")
    	    ->where("date BETWEEN '$fdate' AND '$tdate'")
    	    ->groupby("ref_code");
    		
    		if($booking_type!="")
    		     $builder->where("SUBSTRING(ref_code, 3, 2)",$booking_type);
    		    
    	    $datas = $builder->get()
    	    ->getResultArray();
    	    
    	    $res = [];
    	    foreach($datas as $iter)
    	    {
    	        if(strlen($iter["ref_code"])!=6)continue;
    	        
    	        $typ = substr($iter["ref_code"], 2, 2);
    	        if($typ != "CT" && $typ != "KI") //counter or kios
    	        continue;
    	        
    	        $paymode = substr($iter["ref_code"], 4, 2); //paymentmode
    	        $paymode_name = "Others";
    	        $res[($typ=="CT"?"Counter":"KIOSK")][$paymode_name][$iter["product_id"]] = $iter;
    	        
    	    }
    	return $res;
    }
	//future ref
	public function daily_hall_booking($fdate,$tdate,$filter)
	{
	    $builder = $this->db->table('hall_booking ab')
	    ->select("name as product_name,id as product_id,ref_code,shortcode,sum(total_amount) as amount")
	    ->join("hall_booking_details abd","ab.id = abd.hall_booking_id")
	    ->where("payment_status",2)
	    ->where("date BETWEEN '$fdate' AND '$tdate'")
	    ->groupby("ref_code");
	    
	    if($booking_type!="")
    		     $builder->where("SUBSTRING(ref_code, 3, 2)",$booking_type);
    		    
	    $datas = $builder->get()
	    ->getResultArray();
	    
	    $paymodes = [];
	    $datapaymode = $this->db->table("payment_mode")
	    ->get()
	    ->getResultArray();
	    foreach($datapaymode as $iter)
	    {
	        $paymodes[$iter["shortcode"]] = $iter["name"];
	    }
	    
	    $deitys = [];
	    $deitydata = $this->db->table("archanai_diety")
	    ->get()
	    ->getResultArray();
	    foreach($deitydata as $iter)
	    {
	        $deitys[$iter["id"]] = $iter["name"];
	    }
	    
	    $res = [];
	    foreach($datas as $iter)
	    {
	        if(strlen($iter["ref_code"])!=6)continue;
	        
	        $typ = substr($iter["ref_code"], 2, 2);
	        if($typ != "CT" && $typ != "KI") //counter or kios
	        continue;
	        
	        $paymode = substr($iter["ref_code"], 4, 2); //paymentmode
	        $paymode_name = (isset($paymodes[$paymode])?$paymodes[$paymode]:$paymode);
	        $deity_name = (isset($deitys[$iter["deity_id"]])?$deitys[$iter["deity_id"]]:$iter["deity_id"]);
	        $res[($typ=="CT"?"Counter":"KIOSK")][$paymode_name][$deity_name][$iter["product_id"]] = $iter;
	        
	    }
	    return $res;
	}
	
	public function wrapBuilder($filter,&$builder,$typ='arch')
	{
	    if(empty($filter))return ;
	    
	    foreach($filter as $col=>$iter)
	    {
	        if(isset($iter["like"]))
    	        $builder->like($col,$iter["like"]);
    	    else if($col == "username")
    	    {
    	        if("arch" == $typ)
    	            $builder->whereIn("entry_by",$iter); 
    	    }
    	    else
    	       $builder->whereIn($col,$iter); 
	    }
	}
	public function index() {
		// echo '<pre>';
		// print_r($_POST);
		// exit;
		if (!empty($_POST['dailyclosing_start_date']))
			$dailyclosing_start_date = $_POST['dailyclosing_start_date'];
		else
			$dailyclosing_start_date = date("Y-m-d");
		if (!empty($_POST['dailyclosing_end_date']))
			$dailyclosing_end_date = $_POST['dailyclosing_end_date'];
		else
			$dailyclosing_end_date = date("Y-m-d");
		// $booking_type = '';
		if (!empty($_POST['booking_type']))
			$booking_type = $_POST['booking_type'];
		/* $archanai_data_direct = daily_archanai_booking_withcurrentdate_overall($current_date, $booking_type = "DIRECT");
					$archanai_data_online = daily_archanai_booking_withcurrentdate_overall($current_date, $booking_type = "ONLINE"); */
		$data['archanai_details'] = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		/* $hallbooking_data_direct = daily_hall_booking_withcurrentdate($current_date, $booking_type = "DIRECT");
					$hallbooking_data_online = daily_hall_booking_withcurrentdate($current_date, $booking_type = "ONLINE");
					$data['hallbooking_details'] = array_merge($hallbooking_data_direct,$hallbooking_data_online); */
		$data['archanai_group_details'] = daily_group_deity_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['hallbooking_details'] = daily_hall_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);

		$ubayam_data = daily_ubayam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date,$booking_type);
		$data['ubayam_details'] = $ubayam_data;
		$donation_data = daily_donation_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['donation_details'] = $donation_data;
		$prasadam_data = daily_prasadam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		$data['prasadam_details'] = $prasadam_data;
		$annathanam_data = daily_annathanam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['annathanam_details'] = $annathanam_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['product_offering_details'] = $product_offering_data;
		$repayment_data = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['repayment_details'] = $repayment_data;
		$payment_voucher_data = daily_payment_voucher_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$repayment_data = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['repayment_details'] = $repayment_data;
	
		$data['payment_voucher_details'] = $payment_voucher_data;
		$data['dailyclosing_start_date'] = $dailyclosing_start_date;
		$data['dailyclosing_end_date'] = $dailyclosing_end_date;
		$data['booking_type'] = $booking_type;


		$data['login_opt'] = $this->db->table('login')->where('member_comes', 'counter')
			->get()->getResultArray();
		$data['payment_mode_opt'] = $this->db->table('payment_mode')->where("paid_through","DIRECT")->where('status', 1)
			->get()->getResultArray();
		$data['deity_opt'] = $this->db->table('archanai_diety')
			->get()->getResultArray();
		$data['group_opt'] = $this->db->table('archanai_group')
			->get()->getResultArray();
		$data['category_opt'] = $this->db->table('archanai_category')
			->get()->getResultArray();
		$data['product_opt'] = $this->db->table('archanai')
			->get()->getResultArray();

		// echo '<pre>';
		// print_r($data['hallbooking_details']);
		// exit;

		echo view('template/header');
		echo view('template/sidebar');
		echo view('daily_closing/index', $data);
		echo view('template/footer');
	}
	
	public function daily_archanai_booking($fdate,$tdate,$timezone,$filter)
	{
	    //print_r($_POST);
	    //die("t");
	    $builder = $this->db->table('archanai_booking ab')
	    ->select("name_eng as product_name,ab.paid_through,archanai_category.name as category_name,groupname,pay_method,abd.deity_id as deity_id,archanai_id as product_id,shortcode,sum(quantity) as quantity,sum(total_amount) as amount")
	    ->join("login","login.id = ab.entry_by")
	    ->join("archanai_booking_details abd","ab.id = abd.archanai_booking_id")
	    ->join("archanai a","a.id = abd.archanai_id")
	    ->join("archanai_category","archanai_category.id = a.archanai_category","left")
	    ->join("archanai_payment_gateway_datas","archanai_payment_gateway_datas.archanai_booking_id = ab.id")
	    ->where("payment_status",2)
	    ->where("date BETWEEN '$fdate' AND '$tdate'")
	    ->groupby("archanai_id,abd.deity_id");
	    if(!empty($timezone) && count($timezone) == 1)
	    {
	        if($timezone[0]=="am")
	            $builder->where("Hour(created) >=0 && Hour(created) < 12");
	       else 
	            $builder->where("Hour(created) >= 12");
	        
	    }
	    $this->wrapBuilder($filter,$builder);
    		    
	    $datas = $builder->get()
	    //echo $this->db->getLastQuery();
	    //die("t");
	    ->getResultArray();
	    //print_r($datas);
	    //die("r");
	    /*
	    $paymodes = [];
	    $datapaymode = $this->db->table("payment_mode")
	    ->get()
	    ->getResultArray();
	    foreach($datapaymode as $iter)
	    {
	        $paymodes[$iter["shortcode"]] = $iter["name"];
	    }
	    */
	    
	    $deitys = [];
	    $deitydata = $this->db->table("archanai_diety")
	    ->get()
	    ->getResultArray();
	    
	    foreach($deitydata as $iter)
	    {
	        $deitys[$iter["id"]] = $iter["name"];
	    }
	    
	    $res = [];
	    foreach($datas as $iter)
	    {
	        //if(strlen($iter["ref_code"])!=6)continue;
	        $paid_through = $iter["paid_through"];
	        $pay_method = $iter["pay_method"];
	        //$typ = substr($iter["ref_code"], 2, 2);COUNTER
	        if($paid_through != "COUNTER" && $paid_through != "KIOSK") //counter or kios
	        continue;
	        
	        //$paymode = substr($pay_method, 4, 2); //paymentmode
	        $paymode_name = $pay_method;//(isset($paymodes[$paymode])?$paymodes[$paymode]:$paymode);
	        $deity_name = (isset($deitys[$iter["deity_id"]])?$deitys[$iter["deity_id"]]:$iter["deity_id"]);
	        $res[$paid_through][$paymode_name][$deity_name][$iter["product_id"]] = $iter;
	        
	    }
	    //print_r($res);
	   
	    return $res;
	}
	public function daily_archanai_booking_group($fdate,$tdate,$timezone,$filter,$summary_filter)
	{
	    if(empty($summary_filter))return;
	    
	    $groups = [];
	    foreach($summary_filter as $iter)
	    {
	      $groups[] = $iter;  
	    }
	    $builder = $this->db->table('archanai_booking ab')
	    ->select(implode(",",$groups).",login.name as login_name,archanai_category.name as category_name,archanai_diety.name as deity_name,name_eng as product_name,ab.paid_through,pay_method,archanai_id as product_id,shortcode,sum(quantity) as quantity,sum(total_amount) as amount")
	    ->join("login","login.id = ab.entry_by")
	    ->join("archanai_booking_details abd","ab.id = abd.archanai_booking_id")
	    ->join("archanai a","a.id = abd.archanai_id")
	    ->join("archanai_category","archanai_category.id = a.archanai_category","left")
	    ->join("archanai_payment_gateway_datas","archanai_payment_gateway_datas.archanai_booking_id = ab.id")
	    ->join("archanai_diety","archanai_diety.id = abd.deity_id")
	    ->where("payment_status",2)
	    ->where("date BETWEEN '$fdate' AND '$tdate'")
	    ->groupby(implode(",",$groups));
	    if(!empty($timezone) && count($timezone) == 1)
	    {
	        if($timezone[0]=="am")
	            $builder->where("Hour(created) >=0 && Hour(created) < 12");
	       else 
	            $builder->where("Hour(created) >= 12");
	        
	    }
	    $this->wrapBuilder($filter,$builder);
    		    
	    $datas = $builder->get()
	    //echo $this->db->getLastQuery();
	    //die("t");
	    ->getResultArray();
	    
	    //username,deity_id,group,category,product
	    //print_r($datas); 
	    //die("y");
	    
	    $res = [];
	    foreach($datas as $iter)
	    {
            foreach($summary_filter as $iter1)
            {
                if($iter1 == "abd.deity_id")
                    $diter = "deity_name";
                else if($iter1 == "archanai_category")
                    $diter = "category_name";
                else if($iter1 == "added_by")
                    $diter = "login_name";
                else 
                    $diter = $iter1;
                    
                if(isset($res[$iter1][$iter[$diter]]["amount"]))
                {
                    $res[$iter1][$iter[$diter]]["amount"] += floatval($iter["amount"]);
                    $res[$iter1][$iter[$diter]]["quantity"] += floatval($iter["quantity"]);
                }
                else
                {
                    $res[$iter1][$iter[$diter]]["amount"] = floatval($iter["amount"]);
                    $res[$iter1][$iter[$diter]]["quantity"] = floatval($iter["quantity"]);
                }
            }
	    }
	   // print_r($res);
	   //die("t");
	    return $res;
	}
	public function daily_prasadam_booking($fdate,$tdate,$filter)
	{
    	$builder = $this->db->table('prasadam ab')
    	    ->select("name_eng as product_name,abd.prasadam_id as product_id,ref_code,shortcode,sum(quantity) as quantity,sum(ab.total_amount) as amount")
    	    ->join("prasadam_booking_details abd","ab.id = abd.prasadam_booking_id")
    	    ->join("login","login.id = ab.added_by")
    	    ->join("prasadam_setting a","a.id = abd.prasadam_id")
    	    //->join("archanai_category","archanai_category.id = a.archanai_category","left")
    	    ->where("payment_status",2)
    	    ->where("date BETWEEN '$fdate' AND '$tdate'")
    	    ->groupby("prasadam_id,ref_code");
    		
    		$this->wrapBuilder($filter,$builder);
    		    
    	    $datas = $builder->get()
    	    //	echo $this->db->getLastQuery();
	    //die("t");
    	    ->getResultArray();
    	    
    	    $paymodes = [];
    	    $datapaymode = $this->db->table("payment_mode")
    	    ->get()
    	    ->getResultArray();
    	    foreach($datapaymode as $iter)
    	    {
    	        $paymodes[$iter["shortcode"]] = $iter["name"];
    	    }
    	    
    	    $res = [];
    	    foreach($datas as $iter)
    	    {
    	        if(strlen($iter["ref_code"])!=6)continue;
    	        
    	        $typ = substr($iter["ref_code"], 2, 2);
    	        if($typ != "CT" && $typ != "KI") //counter or kios
    	        continue;
    	        
    	        $paymode = substr($iter["ref_code"], 4, 2); //paymentmode
    	        $paymode_name = (isset($paymodes[$paymode])?$paymodes[$paymode]:$paymode);
    	        //$deity_name = (isset($deitys[$iter["deity_id"]])?$deitys[$iter["deity_id"]]:$iter["deity_id"]);
    	        $res[($typ=="CT"?"Counter":"KIOSK")][$paymode_name][$iter["product_id"]] = $iter;
    	        
    	    }
    	return $res;
    }
    public function getLists($fdate,$tdate,$filter,$req_type='package')
    {
        
        if($req_type == "package")
        {
            $builder = $this->db->table('templebooking ab')
    	    ->select("a.name as product_name,abd.package_id as product_id,ab.deity_id,ref_code,sum(quantity) as quantity,sum(abd.amount) as amount")
    	    ->join("login","login.id = ab.created_by")
    	    ->join("booked_packages abd","ab.id = abd.booking_id")
    	    ->join("temple_packages a","a.id = abd.package_id")
    	    ->where("payment_status",2)
    	    ->where("booking_date BETWEEN '$fdate' AND '$tdate'")
    	    ->groupby("package_id,ab.deity_id,ref_code");
    	    
    		$this->wrapBuilder($filter,$builder);
    	
    	    $datas = $builder->get()
    	    //	echo $this->db->getLastQuery();
	    //die("t");
    	    ->getResultArray();
        }
        else
        {
            $builder = $this->db->table('templebooking ab')
    	    ->select("a.name as product_name,abd.booked_package_id as product_id,abd.service_id,ref_code,ab.deity_id,sum(quantity) as quantity,sum(abd.amount) as amount")
    	    ->join("login","login.id = ab.created_by")
    	    ->join("booked_services abd","ab.id = abd.booking_id")
    	    ->join("temple_services a","a.id = abd.service_id")
    	    ->where("payment_status",2)
    	    ->where("booking_date BETWEEN '$fdate' AND '$tdate'")
    	    ->groupby("abd.service_id,ab.deity_id,ref_code");
    	    
    	    $this->wrapBuilder($filter,$builder);
    		    
    	    $datas = $builder->get()
    	    //	echo $this->db->getLastQuery();
	    //die("t");
    	    ->getResultArray();
        }
	    
	    $paymodes = [];
	    $datapaymode = $this->db->table("payment_mode")
	    ->get()
	    ->getResultArray();
	    foreach($datapaymode as $iter)
	    {
	        $paymodes[$iter["shortcode"]] = $iter["name"];
	    }
	    
	    $deitys = [];
	    $deitydata = $this->db->table("archanai_diety")
	    ->get()
	    ->getResultArray();
	    foreach($deitydata as $iter)
	    {
	        $deitys[$iter["id"]] = $iter["name"];
	    }
	    
	    if($req_type != "package")
	    {
    	    $service_ids = [];
    	    $servicedata = $this->db->table("temple_services")
    	    ->get()
    	    ->getResultArray();
    	    foreach($servicedata as $iter)
    	    {
    	        $service_ids[$iter["id"]] = $iter["name"];
    	    }
	    }
	    if($req_type == "package")
	    {
    	    $package_ids = [];
    	    $packagedata = $this->db->table("temple_packages")
    	    ->get()
    	    ->getResultArray();
    	    foreach($packagedata as $iter)
    	    {
    	        $package_ids[$iter["id"]] = $iter["name"];
    	    }
	    }
	    
	    $res = [];
	    foreach($datas as $iter)
	    {
	        if(strlen($iter["ref_code"])!=6)continue;
	        
	        $typ = substr($iter["ref_code"], 2, 2);
	        if($typ != "CT" && $typ != "KI") //counter or kios
	        continue;
	        
	        $paymode = substr($iter["ref_code"], 4, 2); //paymentmode
	        $paymode_name = (isset($paymodes[$paymode])?$paymodes[$paymode]:"Others");
	        $deity_name = (isset($deitys[$iter["deity_id"]])?$deitys[$iter["deity_id"]]:"Others");
	        
	        if($req_type == "package")
	        {
	            //$package_name = (isset($package_ids[$iter["package_id"]])?$package_ids[$iter["package_id"]]:"Others");
	            $res[($typ=="CT"?"Counter":"KIOSK")][$paymode_name][$deity_name][intval($iter["product_id"])] = $iter;
	        }
	        else
	        {
	            //$service_name = (isset($service_ids[$iter["service_id"]])?$service_ids[$iter["service_id"]]:"Others");
	            $res[($typ=="CT"?"Counter":"KIOSK")][$paymode_name][$deity_name][intval($iter["service_id"])] = $iter;
	        }
	        
	    }
	    return $res;
    }
    public function daily_ubayam_booking($dailyclosing_start_date, $dailyclosing_end_date,$filter)
    {
        $res = $this->getLists($dailyclosing_start_date,$dailyclosing_end_date,$filter);
		$data['list'] = $res;
		$res = $this->getLists($dailyclosing_start_date,$dailyclosing_end_date,$filter,'service');
		$data['list_service'] = $res;
		return $data;
    }
    
	public function getDailyReport()
	{

	    global $lang;
	    
	    //filters 
	    $filter = [];
	    if (!empty($_POST['username']))
	        $filter["username"] = $_POST['username'];
	    if (!empty($_POST['payment_mode'])) //payment_mode
	        $filter["archanai_payment_gateway_datas.pay_method"] = $_POST['payment_mode'];
	    if (!empty($_POST['deity']))
	        $filter["abd.deity_id"] = $_POST['deity'];
	    if (!empty($_POST['group']))
	        $filter["groupname"] = ["like"=>$_POST['group']];
	    if (!empty($_POST['category']))
	        $filter["archanai_category"] = $_POST['category'];
	    if (!empty($_POST['product_name']))
	        $filter["name_eng"] = ["like"=>$_POST['product_name']];
	        
	   /*
	    //summary filters
	    $summary_filter = [];
	    if (in_array($_POST['summary_username'],["username"]))
	        $summary_filter[] = $_POST['summary_username'];
	    if (in_array($_POST['summary_payment_mode'],["username"]))
	        $filter[] = $_POST['summary_payment_mode'];
	    if (!empty($_POST['summary_deity']))
	        $filter[] = $_POST['summary_deity'];
	    if (!empty($_POST['summary_group']))
	        $filter[] = $_POST['summary_group'];
	    if (!empty($_POST['summary_category']))
	        $filter[] = $_POST['summary_category'];
	    if (!empty($_POST['summary_product_name']))
	        $filter[] = $_POST['summary_product_name'];
	    */
		if (!empty($_POST['dailyclosing_start_date']))
			$dailyclosing_start_date = $_POST['dailyclosing_start_date'];
		else
			$dailyclosing_start_date = date("Y-m-d");
		if (!empty($_POST['dailyclosing_end_date']))
			$dailyclosing_end_date = $_POST['dailyclosing_end_date'];
		else
			$dailyclosing_end_date = date("Y-m-d");
		$timezone = [];
		if (!empty($_POST['timezone']))
	        $timezone = $_POST['timezone'];
	        
		$booking_type = '';
		
		//table structure
		$data["title"] = [
		    "archanai_details"=>[
		        "title"=>"Archani Details",
		        "cols"=>[
		            "col"=>["product_name"=>"Archanai Name","category_name"=>"Category","groupname"=>"Group Name","paid_through"=>"Paid Through","quantity"=>"Quantity","amount"=>"Amount"],
		            "number_format_arr"=>["amount"],"right_align_col"=>["amount"],"center_align_col"=>["product_name","sl_no","sl_to","quantity"]
		            ]
		      ],
		      /*
		    "ubayam_details"=>[
		        "title"=>"Ubhayam Details",
		        "cols"=>[
		            "col"=>["product_name"=>"Ubayam Name","category"=>"Category","group_name"=>"Group Name","paid_through"=>"Paid Through","quantity"=>"Quantity","amount"=>"Amount"],
		            "number_format_arr"=>["amount"],"right_align_col"=>["amount"],"center_align_col"=>["product_name","sl_no","sl_to","quantity"]
		            ]
		        ],
		    "ubayam_details_service"=>[
		        "title"=>"Ubhayam Service Details",
		        "cols"=>[
		            "col"=>["product_name"=>"Ubayam Name","category"=>"Category","group_name"=>"Group Name","paid_through"=>"Paid Through","quantity"=>"Quantity","amount"=>"Amount"],
		            "number_format_arr"=>["amount"],"right_align_col"=>["amount"],"center_align_col"=>["product_name","sl_no","sl_to","quantity"]
		            ]
		        ],
		    "prasadam_details"=>[
		        "title"=>"Prasadam Details",
		        "cols"=>[
		            "col"=>["product_name"=>"Prasadam Name","category"=>"Category","group_name"=>"Group Name","paid_through"=>"Paid Through","quantity"=>"Quantity","amount"=>"Amount"],
		            "number_format_arr"=>["amount"],"right_align_col"=>["amount"],"center_align_col"=>["product_name","sl_no","sl_to","quantity"]
		            ]
		        ],
		        */
		    ];
		    
		  //  print_r($_POST);
		//die("r");
		$data["lang"] = $lang;
		$data['archanai_details'] = $this->daily_archanai_booking($dailyclosing_start_date, $dailyclosing_end_date,$timezone,$filter);
	//	print_r($data);
		if(empty($_POST["summary_filter"]))
		    $_POST["summary_filter"][] = "pay_method";
		    
		$data['archanai_summary'] = $this->daily_archanai_booking_group($dailyclosing_start_date, $dailyclosing_end_date,$timezone,$filter,$_POST["summary_filter"]);
		//$ubayam_data = $this->daily_ubayam_booking($dailyclosing_start_date, $dailyclosing_end_date,$filter);
		//$data['ubayam_details'] = $ubayam_data["list"];
		//$data['ubayam_details_service'] = $ubayam_data["list_service"];
		//$prasadam_data = $this->daily_prasadam_booking($dailyclosing_start_date, $dailyclosing_end_date, $filter);
		//$data['prasadam_details'] = $prasadam_data;
		
		$data['ubayam_details'] = [];
		$data['prasadam_details'] = [];
		
		//$data['archanai_group_details'] = daily_group_deity_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		//$data['hallbooking_details'] = $this->daily_hall_booking($dailyclosing_start_date, $dailyclosing_end_date);

		//$donation_data = $this->daily_donation($dailyclosing_start_date, $dailyclosing_end_date, $booking_type);
		//$data['donation_details'] = $donation_data;
		//$annathanam_data = daily_annathanam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		//$data['annathanam_details'] = $annathanam_data;
		//$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		//$data['product_offering_details'] = $product_offering_data;
		
		//$payment_voucher_data = daily_payment_voucher_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		//$repayment_data = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		//$data['repayment_details'] = $repayment_data;
		//$data['payment_voucher_details'] = $payment_voucher_data;
		
		$data['login_opt'] = $this->db->table('login')->where('member_comes', 'counter')
			->get()->getResultArray();
		$data['payment_mode_opt'] = $this->db->table('payment_mode')->where("paid_through","DIRECT")->where('status', 1)
			->get()->getResultArray();
		$data['deity_opt'] = $this->db->table('archanai_diety')
			->get()->getResultArray();
		$data['group_opt'] = $this->db->table('archanai_group')
			->get()->getResultArray();
		$data['category_opt'] = $this->db->table('archanai_category')
			->get()->getResultArray();
		$data['product_opt'] = $this->db->table('archanai')
			->get()->getResultArray();
			
		$data['dailyclosing_start_date'] = $dailyclosing_start_date;
		$data['dailyclosing_end_date'] = $dailyclosing_end_date;
		$data['username'] = $_POST["username"];
		$data['payment_mode'] = $_POST["payment_mode"];
		$data['deity'] = $_POST["deity"];
		$data['group'] = $_POST["group"];
		$data['category'] = $_POST["category"];
		$data['product_name'] = $_POST["product_name"];
		$data['booking_type'] = $_POST["booking_type"];
        $data['archanai_group_details'] = [];
        $data['hallbooking_details'] = [];
        $data['donation_details'] = [];
		$data['annathanam_details'] = [];
		$data['payment_voucher_details'] = [];
		
		echo view('template/header');
		echo view('template/sidebar');
		echo view('daily_closing/getDailyReport', $data);
		echo view('template/footer');
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
		/* $archanai_data_direct = daily_archanai_booking_withcurrentdate($current_date, $booking_type = "DIRECT");
					$archanai_data_online = daily_archanai_booking_withcurrentdate($current_date, $booking_type = "ONLINE");
					$data['archanai_details'] = array_merge($archanai_data_online,$archanai_data_direct); */
		$data['archanai_details'] = daily_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['archanai_group_details'] = daily_group_deity_archanai_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['hallbooking_details'] = daily_hall_booking_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$ubayam_data = daily_ubayam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['ubayam_details'] = $ubayam_data;
		$donation_data = daily_donation_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['donation_details'] = $donation_data;
		$prasadam_data = daily_prasadam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['prasadam_details'] = $prasadam_data;
		$annathanam_data = daily_annathanam_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['annathanam_details'] = $annathanam_data;
		$product_offering_data = daily_product_offering_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['product_offering_details'] = $product_offering_data;
		$payment_voucher_data = daily_payment_voucher_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['payment_voucher_details'] = $payment_voucher_data;
		$repayment_data = daily_repayment_data_withcurrentdate($dailyclosing_start_date, $dailyclosing_end_date);
		$data['repayment_details'] = $repayment_data;
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		$data['dailyclosing_start_date'] = $dailyclosing_start_date;
		$data['dailyclosing_end_date'] = $dailyclosing_end_date;
		echo view('daily_closing/print_page', $data);
	}
}
