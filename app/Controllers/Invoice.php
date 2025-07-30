<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Invoice extends BaseController
{
    function __construct(){
        parent:: __construct();
        helper('url');
        $this->model = new PermissionModel();
		if( ($this->session->get('login') ) == false && $this->session->get('role') != 1){
            $data['dn_msg'] = 'Please Login';
            header('Location: '.base_url().'/login');
            exit;
		}
    }
	
	public function getCSAmount()
	{
	    $customer_supplier_id = intval($_POST["customer_supplier_id"]);
	    $invoice_type = intval($_POST["invoice_type"]);
	    $tot = 0;
	    if($customer_supplier_id > 0 && $invoice_type > 0)
	    {
	        if($invoice_type == 1) //sales
	        {
	            $inv_data = $this->db->table("invoice")->select("sum(grand_total) as total")
	            ->where("invoice_type",1)
	            ->where("paid_amount < grand_total")
	            ->where("customer_supplier_id",$customer_supplier_id)
	            ->get()->getRowArray();
	            $tot = (isset($inv_data["total"])?floatval($inv_data["total"]):0);
	        }
	        else if($invoice_type == 2)
	        {
	            $inv_data = $this->db->table("invoice")->select("sum(grand_total) as total")
	            ->where("invoice_type",2)
	            ->where("paid_amount < grand_total")
	            ->where("customer_supplier_id",$customer_supplier_id)
	            ->get()->getRowArray();
	            $tot = (isset($inv_data["total"])?floatval($inv_data["total"]):0);
	        }
	    }
	    echo $tot;
	}
	
	public function getCSBalAmount()
	{
	    $customer_supplier_id = intval($_POST["customer_supplier_id"]);
	    $invoice_type = intval($_POST["invoice_type"]);
	    $tot = 0;
	    if($customer_supplier_id > 0 && $invoice_type > 0)
	    {
	        if($invoice_type == 1) //sales
	        {
	            $inv_data = $this->db->table("invoice")->select("sum(grand_total - paid_amount) as balance")
	            ->where("invoice_type",1)
	            ->where("paid_amount < grand_total")
	            ->where("customer_supplier_id",$customer_supplier_id)
	            ->get()->getRowArray();
	            $tot = (isset($inv_data["balance"])?floatval($inv_data["balance"]):0);
	            $tot = ($tot > 0 ? $tot : 0);
	        }
	        else if($invoice_type == 2)
	        {
	            $inv_data = $this->db->table("invoice")->select("sum(grand_total - paid_amount) as balance")
	            ->where("invoice_type",2)
	            ->where("paid_amount < grand_total")
	            ->where("customer_supplier_id",$customer_supplier_id)
	            ->get()->getRowArray();
	            $tot = (isset($inv_data["balance"])?floatval($inv_data["balance"]):0);
	            $tot = ($tot > 0 ? $tot : 0);
	        }
	    }
	    echo $tot;
	}
	
	public function getCustOrSupp()
	{
	    $invoice_type = $_POST["invoice_type"];
	    $name = intval($_POST["name"]);
	    if($invoice_type == 1)
	    {
	        $table = "customer";
	        $sel = "customer_name as name,id";
	        $opt = "<option value=''>Select Customer <span style='color: red;'>*</span></option>";
	    }
	    else
	    {
	       $table = "supplier"; 
	       $sel = "supplier_name as name,id";
	       $opt = "<option value=''>Select Supplier <span style='color: red;'>*</span></option>";
	    }
	    
	    $data = $this->db->table($table)->select($sel)->get()->getResultArray();
	    
	    foreach($data as $iter)
	        $opt .= "<option ".($name == $iter['id']?'selected':'')." value='".$iter["id"]."'>".$iter['name']."</option>";
	        
	   echo $opt;
	}
	
	public function invoice_purchase()
	{
	    $data['suppliers'] = $this->db->table('invoice')->where("invoice_type",2)->get()->getResultArray();
		
		$supp = $this->db->table("supplier")->get()->getResultArray();
		$cust = $this->db->table("customer")->get()->getResultArray();
		
		$supparr = [];
		foreach($supp as $iter)
		{
		    $supparr[$iter["id"]] = $iter["supplier_name"];
		}
		
		$custarr = [];
		foreach($cust as $iter)
		{
		    $custarr[$iter["id"]] = $iter["customer_name"];
		}
		
		$data["supparr"] = $supparr;
		$data["custarr"] = $custarr;
	
		echo view('template/header');
		echo view('template/sidebar');
		echo view('invoice/list_purchase', $data);
		echo view('template/footer');
	}
	
	public function index() {
		$data['suppliers'] = $this->db->table('invoice')->where("invoice_type",1)->get()->getResultArray();
		
		$supp = $this->db->table("supplier")->get()->getResultArray();
		$cust = $this->db->table("customer")->get()->getResultArray();
		
		$supparr = [];
		foreach($supp as $iter)
		{
		    $supparr[$iter["id"]] = $iter["supplier_name"];
		}
		
		$custarr = [];
		foreach($cust as $iter)
		{
		    $custarr[$iter["id"]] = $iter["customer_name"];
		}
		
		$data["supparr"] = $supparr;
		$data["custarr"] = $custarr;
	
		echo view('template/header');
		echo view('template/sidebar');
		echo view('invoice/list', $data);
		echo view('template/footer');
    }
	
	public function getOpt($data)
	{
	    $opt_purchase = "<option value=''>Select Purchase</option>";
		foreach($data as $iter)
		{
		   $opt_purchase .= "<option value='".$iter["id"]."'>".$iter["name"]."</option>";  
		}
		return $opt_purchase;
	}
	
	public function add() {
		echo view('template/header');
		echo view('template/sidebar');
		
		
		$settings_data = $this->db->table("settings")->where("setting_name","sales_default_discount_ledger")->get()->getRowArray();
		$data["default_sales_discount_ledger"] = isset($settings_data["setting_value"])?$settings_data["setting_value"]:0;
		
		$settings_data2 = $this->db->table("settings")->where("setting_name","purchase_default_discount_ledger")->get()->getRowArray();
		$data["default_purchase_discount_ledger"] = isset($settings_data2["setting_value"])?$settings_data2["setting_value"]:0;
		
		$ledger_arr = ["purchase_default_discount_ledger","purchase_default_ledger","purchase_default_tax_ledger","sales_default_discount_ledger","sales_default_ledger","sales_default_tax_ledger"
		];
		foreach($ledger_arr as $iter)
		{
		    $data[$iter] = 0;
		}
		
		$settings_data = $this->db->table("settings")->where("setting_name in(".implode(",",array_map(function($v) {
    return '"' . $v . '"';
}, $ledger_arr)
		)
		.")")->get()->getResultArray();
		
		foreach($settings_data as $iter)
		{ 
		    $data[$iter["setting_name"]] = $iter["setting_value"];
		}
		$typ = intval($_REQUEST["req_typ"])==0?1:$_REQUEST["req_typ"];
		$data["supplier"]["invoice_no"] = $this->getInvNo($typ);
		//print_r($data);
		// Get revenue ledgers for sales (Group codes 4000 series)
	$data['sales_ledgers'] = $this->db->query("SELECT id,name, right_code, left_code FROM `ledgers` where group_id in (SELECT id FROM `groups` WHERE code in (1200, 4000) or parent_id in (SELECT id FROM `groups` WHERE code in (1200, 4000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (1200, 4000))))")->getResultArray();
		$data['purchase_ledgers'] = $this->db->query("SELECT id,name, right_code, left_code FROM `ledgers` where group_id in (SELECT id FROM `groups` WHERE code in (1200, 5000) or parent_id in (SELECT id FROM `groups` WHERE code in (1200, 5000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (1200, 5000))))")->getResultArray();
	
		
		echo view('invoice/add',$data);
		echo view('template/footer');
    }
	
	public function edit($id) {
		$data['supplier'] = $this->db->table('invoice')->where("id", $id)->get()->getRowArray();
		$data['data_list'] = $this->db->table('invoice_details')->where("invoice_master_id", $id)->get()->getResultArray();
		
		
		if(intval($data['supplier']["po_id"])>0)
		{
		   
		$dat = $this->db->table("purchase_order")->where("id",$data['supplier']["po_id"])->get()->getRowArray();
		//print_r($dat);die("yy");
		$data["po_no"] = (isset($dat["po_no"])?($dat["po_no"]):""
		);
		}
		//print_r($data);
		//die("tt");
		echo view('template/header');
		echo view('template/sidebar');
		
		$settings_data = $this->db->table("settings")->where("setting_name","sales_default_discount_ledger")->get()->getRowArray();
		$data["default_sales_discount_ledger"] = isset($settings_data["setting_value"])?$settings_data["setting_value"]:0;
		
		$settings_data2 = $this->db->table("settings")->where("setting_name","purchase_default_discount_ledger")->get()->getRowArray();
		$data["default_purchase_discount_ledger"] = isset($settings_data2["setting_value"])?$settings_data2["setting_value"]:0;
		
		$ledger_arr = ["purchase_default_discount_ledger","purchase_default_ledger","purchase_default_tax_ledger","sales_default_discount_ledger","sales_default_ledger","sales_default_tax_ledger"
		];
		foreach($ledger_arr as $iter)
		{
		    $data[$iter] = 0;
		}
		
		$settings_data = $this->db->table("settings")->where("setting_name in(".implode(",",
		    array_map(function($v) {
    return '"' . $v . '"';
}, $ledger_arr)
		).")")->get()
		//echo $this->db->getLastQuery();
		//die("uu");
		->getResultArray();
		
		
		foreach($settings_data as $iter)
		{
		    $data[$iter["setting_name"]] = $iter["setting_value"];
		}
		
		$data['sales_ledgers'] = $this->db->query("SELECT id,name, right_code, left_code FROM `ledgers` where group_id in (SELECT id FROM `groups` WHERE code in (1200, 4000) or parent_id in (SELECT id FROM `groups` WHERE code in (1200, 4000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (1200, 4000))))")->getResultArray();
		$data['purchase_ledgers'] = $this->db->query("SELECT id,name, right_code, left_code FROM `ledgers` where group_id in (SELECT id FROM `groups` WHERE code in (1200, 5000) or parent_id in (SELECT id FROM `groups` WHERE code in (1200, 5000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (1200, 5000))))")->getResultArray();
	
		
		echo view('invoice/add', $data);
		echo view('template/footer');
    }
	
	public function view($id) {
		$data['supplier'] = $this->db->table('invoice')->where("id", $id)->get()->getRowArray();
		$data['data_list'] = $this->db->table('invoice_details')->where("invoice_master_id", $id)->get()->getResultArray();
		
		$data['sales_ledgers'] = $this->db->query("SELECT id,name, right_code, left_code FROM `ledgers` where group_id in (SELECT id FROM `groups` WHERE code in (1200, 4000) or parent_id in (SELECT id FROM `groups` WHERE code in (1200, 4000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (1200, 4000))))")->getResultArray();
		$data['purchase_ledgers'] = $this->db->query("SELECT id,name, right_code, left_code FROM `ledgers` where group_id in (SELECT id FROM `groups` WHERE code in (1200, 5000) or parent_id in (SELECT id FROM `groups` WHERE code in (1200, 5000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (1200, 5000))))")->getResultArray();
	
		$data['view'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('invoice/add', $data);
		echo view('template/footer');
    }
    
    public function create_ledger($searcharr,$inp)
    {
        $searchstr = "";
        foreach($searcharr as $iter)
        {
            $searchstr .= "'".$iter."',";
        }
        $searchstr = rtrim($searchstr,",");
        $hadgroupdata = $this->db->table("groups")->where("LOWER(name) in (".$searchstr.")")->get()->getRowArray(); 
        
        $left_code = $inp["left_code"];
        if(!isset($hadgroupdata["name"])) //create group
        {
            $right_code = (isset($inp["right_code"])?$inp["right_code"]:"0001");
            $data = [];
            $data["parent_id"] = $inp["parent_id"];
            $data["name"] = $inp["group_name"];
            $data["code"] = $left_code;
            $data["fixed"] = 1;
            $data["added_by"] = "";
            $data["created"] = Date("Y-m-d H:i:s");
            
            $this->db->table("groups")->insert($data);
            $group_id = $this->db->insertID();
        }
        else 
        {
            $group_id = $hadgroupdata["id"];
            $ledger_data = $this->db->table("ledgers")->select("(IFNULL(MAX(`right_code`), 0) + 1) as right_code")->where("group_id",$group_id)->get()->getRowArray();
            $codarr = ["000","00","0",""];
            $right_code = $codarr[strlen($ledger_data["right_code"])-1].$ledger_data["right_code"];
        }
        
        //create ledger
        $inp1 = [];
        $inp1["group_id"] = $group_id;
        $inp1["name"] = $inp["ledger_name"];
        $inp1["left_code"] = $left_code;
        $inp1["right_code"] = $right_code;
        $this->db->table("ledgers")->insert($inp1);
        return $this->db->insertID();
    }
    public function getInvNo($invoice_type,$invoice_master_id=0)
    {
        $invoice_master_id = intval($invoice_master_id);
        if($invoice_master_id==0)
        {
            $max_data = $this->db->table("invoice")->select("max(id) as id")->where("invoice_type",$invoice_type)->get()->getRowArray();
            $invoice_master_id = $max_data["id"];
        }
        $lab = ($invoice_type == 1?"SAIV0000":"PUIV0000");
        return $inv_no = $lab.$invoice_master_id;
    }
    public function account_migration($inv_id)
    {
        $invoice_data = $this->db->table("invoice")->where("id",$inv_id)->get()->getRowArray();
        if(!isset($invoice_data["id"]))
            return false;
        
        // Check if already migrated
        $account_migration = $invoice_data["account_migration"];
        if($account_migration == 1)
            return true; // Already migrated
          
        $invoice_detail_data = $this->db->table("invoice_details")->where("invoice_master_id",$inv_id)->get()->getResultArray();  
        $inv_date = $invoice_data['date'];
        $customer_supplier_id = intval($invoice_data['customer_supplier_id']);
        $total_before_discount = floatval($invoice_data['total']);
        $discount = floatval($invoice_data['discount']);
        $grand_total = floatval($invoice_data['grand_total']);
        $inv_no = $invoice_data['invoice_no'];
        $typ = $invoice_data['invoice_type'];
        
        if($typ == 1)
            $tab = "customer";
        else
            $tab = "supplier";
            
        $customer_supplier_ledger_data = $this->db->table($tab)->where("id",$customer_supplier_id)->get()->getRowArray();
        
        if(!isset($customer_supplier_ledger_data["ledger_id"]) || $customer_supplier_ledger_data["ledger_id"] == 0)
        {
            return false; // Customer/Supplier ledger not found
        }
        
        $customer_supplier_ledger = $customer_supplier_ledger_data["ledger_id"];
        $yr = date('Y', strtotime($inv_date));
        $mon = date('m', strtotime($inv_date));
        $qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();
        $entries = array();
        $entries['entry_code'] = 'JOR' . date('y', strtotime($inv_date)) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));

        $dat2 = $this->db->table("entries")->select("max(number) as num")->where("entrytype_id",4)->get()->getRowArray();
        $entries['entrytype_id'] = '4';
        $entries['number'] = intval($dat2["num"])+1;
        $entries['date'] = $inv_date;
        $entries['dr_total'] = $grand_total;
        $entries['cr_total'] = $grand_total;
        $entries['narration'] = ($typ == 1?'SALES INVOICE(' . $inv_no . ')':'PURCHASE INVOICE(' . $inv_no . ')');
        $entries['inv_id'] = $inv_id;
        $entries['type'] = ($typ == 1?'18':'19'); //sales 18, purchase 19
        
        $ent = $this->db->table('entries')->insert($entries);
        $en_id = $this->db->insertID();
        
        if (!empty($en_id)) {
            
            // CORRECTED JOURNAL ENTRIES ACCORDING TO MALAYSIAN ACCOUNTING STANDARDS
            
            if($typ == 1) // SALES INVOICE
            {
                // 1. Debit: Trade Debtors (Customer Account) with Grand Total
                $eitems_debtor = array();
                $eitems_debtor['entry_id'] = $en_id;
                $eitems_debtor['ledger_id'] = $customer_supplier_ledger;
                $eitems_debtor['amount'] = $grand_total;
                $eitems_debtor['dc'] = 'D'; // Debit Trade Debtors
                $eitems_debtor['details'] = 'SALES INVOICE (' . $inv_no . ')';
                $this->db->table('entryitems')->insert($eitems_debtor);
                
                // 2. Credit: Revenue Accounts (from invoice details)
                $tax = 0;
                foreach($invoice_detail_data as $iter)
                {
                    if($iter["ledger_id"] > 0) // Use the selected ledger from invoice details
                    {
                        $eitems_revenue = array();
                        $eitems_revenue['entry_id'] = $en_id;
                        $eitems_revenue['ledger_id'] = $iter["ledger_id"];
                        
                        $eitems_revenue['amount'] = $iter["amount"];
                        $rate = floatval($iter["rate"]);
                        $qty = floatval($iter["qty"]);
                        $amt1 = $rate * $qty;
                        $tax_p = floatval($iter["tax"]);
                        $tax1 = 0;
                        if($tax_p > 0)
                        {
                            $tax1 = floatval($amt1 * ($tax_p/100));
                        }
                        $tax += $tax1;
                        $eitems_revenue['dc'] = 'C'; // Credit Revenue
                        $eitems_revenue['details'] = 'SALES INVOICE (' . $inv_no . ') - ' . $iter["description"];
                        $this->db->table('entryitems')->insert($eitems_revenue);
                    }
                }
                
                //2->a tax 
                if($tax > 0)
                {
                    $tax_ledger = $this->getTaxLedger(1);
                    if($tax_ledger > 0)
                    {
                        $eitems_discount = array();
                        $eitems_discount['entry_id'] = $en_id;
                        $eitems_discount['ledger_id'] = $tax_ledger;
                        $eitems_discount['amount'] = $tax;
                        $eitems_discount['dc'] = 'C'; // Debit Discount Given
                        $eitems_discount['details'] = 'SALES Tax (' . $inv_no . ')';
                        $this->db->table('entryitems')->insert($eitems_discount);
                    }
                }
                
                // 3. Debit: Discount Given (if any)
                if($discount > 0)
                {
                    $discount_ledger = $this->getDiscountLedger($typ);
                    if($discount_ledger > 0)
                    {
                        $eitems_discount = array();
                        $eitems_discount['entry_id'] = $en_id;
                        $eitems_discount['ledger_id'] = $discount_ledger;
                        $eitems_discount['amount'] = $discount;
                        $eitems_discount['dc'] = 'D'; // Debit Discount Given
                        $eitems_discount['details'] = 'SALES DISCOUNT (' . $inv_no . ')';
                        $this->db->table('entryitems')->insert($eitems_discount);
                    }
                }
            }
            else // PURCHASE INVOICE
            {
                // 1. Credit: Trade Creditors (Supplier Account) with Grand Total  
                $eitems_creditor = array();
                $eitems_creditor['entry_id'] = $en_id;
                $eitems_creditor['ledger_id'] = $customer_supplier_ledger;
                $eitems_creditor['amount'] = $grand_total;
                $eitems_creditor['dc'] = 'C'; // Credit Trade Creditors
                $eitems_creditor['details'] = 'PURCHASE INVOICE (' . $inv_no . ')';
                $this->db->table('entryitems')->insert($eitems_creditor);
                
                // 2. Debit: Direct Cost/Expense Accounts (from invoice details)
                $tax = 0;
                foreach($invoice_detail_data as $iter)
                {
                    if($iter["ledger_id"] > 0) // Use the selected ledger from invoice details
                    {
                        $eitems_expense = array();
                        $eitems_expense['entry_id'] = $en_id;
                        $eitems_expense['ledger_id'] = $iter["ledger_id"];
                        $rate = floatval($iter["rate"]);
                        $qty = floatval($iter["qty"]);
                        $amt1 = $rate * $qty;
                        $tax_p = floatval($iter["tax"]);
                        $tax1 = 0;
                        if($tax_p > 0)
                        {
                            $tax1 = floatval($amt1 * ($tax_p/100));
                        }
                        $tax += $tax1;
                        $eitems_expense['amount'] = $iter["amount"];
                        $eitems_expense['dc'] = 'D'; // Debit Expense/Cost
                        $eitems_expense['details'] = 'PURCHASE INVOICE (' . $inv_no . ') - ' . $iter["description"];
                        $this->db->table('entryitems')->insert($eitems_expense);
                    }
                }
                
                    
                //2->a tax 
                if($tax > 0)
                {
                    $tax_ledger = $this->getTaxLedger(2);
                   
                    if($tax_ledger > 0)
                    {
                        $eitems_discount = array();
                        $eitems_discount['entry_id'] = $en_id;
                        $eitems_discount['ledger_id'] = $tax_ledger;
                        $eitems_discount['amount'] = $tax;
                        $eitems_discount['dc'] = 'D'; // Debit Discount Given
                        $eitems_discount['details'] = 'SALES Tax (' . $inv_no . ')';
                        $this->db->table('entryitems')->insert($eitems_discount);
                    }
                }
                
                // 3. Credit: Discount Received (if any)
                if($discount > 0)
                {
                    $discount_ledger = $this->getDiscountLedger($typ);
                    if($discount_ledger > 0)
                    {
                        $eitems_discount = array();
                        $eitems_discount['entry_id'] = $en_id;
                        $eitems_discount['ledger_id'] = $discount_ledger;
                        $eitems_discount['amount'] = $discount;
                        $eitems_discount['dc'] = 'C'; // Credit Discount Received
                        $eitems_discount['details'] = 'PURCHASE DISCOUNT (' . $inv_no . ')';
                        $this->db->table('entryitems')->insert($eitems_discount);
                    }
                }
            }
            
            // Mark as migrated
            $this->db->table('invoice')->where('id', $inv_id)->update(['account_migration' => 1]);
        }
        
        return true;
    }
    
    public function getTaxLedger($typ)
    {
        if($typ == 1) // Sales
        {
            $setting = $this->db->table("settings")->where("setting_name","sales_default_tax_ledger")->get()->getRowArray();
            return isset($setting["setting_value"]) ? intval($setting["setting_value"]) : 0;
        }
        else // Purchase
        {
            $setting = $this->db->table("settings")->where("setting_name","purchase_default_tax_ledger")->get()->getRowArray();
            return isset($setting["setting_value"]) ? intval($setting["setting_value"]) : 0;
        }
    }
    private function getDiscountLedger($typ)
    {
        if($typ == 1) // Sales
        {
            $setting = $this->db->table("settings")->where("setting_name","sales_default_discount_ledger")->get()->getRowArray();
            return isset($setting["setting_value"]) ? intval($setting["setting_value"]) : 0;
        }
        else // Purchase
        {
            $setting = $this->db->table("settings")->where("setting_name","purchase_default_discount_ledger")->get()->getRowArray();
            return isset($setting["setting_value"]) ? intval($setting["setting_value"]) : 0;
        }
    }

	public function store() {
		$id = $_POST['id'];
		$errmsgarr = [];
		
		// Validation
		if(!in_array(intval($_POST['invoice_type']),[1,2]))
		{
		   $errmsgarr[] = "invoice type"; 
		}
		if(intval($_POST['customer_supplier_id']) == 0)
		{
		   $errmsgarr[] = "customer or supplier id"; 
		}
		if($_POST['date']=="")
		{
		   $errmsgarr[] = "date"; 
		}
		if($_POST['invoice_no']=="" || strlen($_POST['invoice_no'])>100)
		{
		   $errmsgarr[] = "challan no"; 
		}
		if(floatval($_POST['total']) == 0)
		{
		   $errmsgarr[] = "total"; 
		}
		if(!empty($id) && intval($id) == 0)
		{
		    $errmsgarr[] = "id"; 
		}
		if(floatval($_POST['grand_total']) <= 0)
		{
		    $errmsgarr[] = "grand total"; 
		}
		
		// Validate that ledger_id is provided for each line item
		if(isset($_POST["description"]) && is_array($_POST["description"]))
		{
		    for($i=0;$i<count($_POST["description"]);$i++)
		    {
		        if(trim($_POST["description"][$i]) != "" && intval($_POST["ledger_id"][$i]) == 0)
		        {
		            $errmsgarr[] = "ledger selection for item: " . $_POST["description"][$i];
		        }
		    }
		}
		
		$inv_type = intval($_POST['invoice_type']);
		$view_file = ($inv_type == 1?"/invoice":"/invoice/invoice_purchase");
		
		if(empty($errmsgarr))
		{
    		$data['invoice_type'] = $inv_type;
    		$data['customer_supplier_id'] = intval($_POST['customer_supplier_id']);
    		$data['date'] = $_POST['date'];
    		$data['challan_no'] = (isset($_POST["challan_no"])?$_POST["challan_no"]:'');
    		$data['remarks'] = $_POST['remarks'];
    		$data['total'] = floatval($_POST['total']);
    		$data['discount'] = floatval($_POST['discount']);
    		$data['grand_total'] = floatval($_POST['grand_total']);
    		
    		if(empty($id)){
    		    $data['paid_amount'] = 0;
    		    $data['due_amount'] = floatval($_POST['grand_total']);
    		    $data['created_at'] = Date("Y-m-d H:i:s");
    		    
    		    try
    		    {
    		        $this->db->transBegin();
        			$builder = $this->db->table('invoice')->insert($data);
        		    if($builder){
        		        
        		        $invoice_master_id = $this->db->insertID();
        		        $inv_no = ($inv_type == 1?"SAIV0000":"PUIV0000").$invoice_master_id;
        		        $this->db->table('invoice')->where("id",$invoice_master_id)->update(["invoice_no"=>$inv_no]);
        		        
        		        // Insert invoice details with proper ledger_id
        		        $description = $_POST["description"];
        		        for($i=0;$i<count($_POST["description"]);$i++)
        		        {
        		            if(trim($_POST["description"][$i]) == "" || floatval($_POST["rate"][$i]) == 0 || floatval($_POST["qty"][$i]) == 0 )continue;
        		            
        		            $inp = [];
        		            $inp["invoice_master_id"] = $invoice_master_id;
        		            $inp["description"] = $_POST["description"][$i];
        		            $inp["ledger_id"] = intval($_POST["ledger_id"][$i]); // This is crucial for accounting
        		            $inp["type"] = intval($_POST["type"][$i]) == 0?2:intval($_POST["type"][$i]);
        		            $inp["rate"] = floatval($_POST["rate"][$i]);
        		            $inp["qty"] = floatval($_POST["qty"][$i]);
        		            $inp["tax"] = (isset($_POST["tax"][$i])?floatval($_POST["tax"][$i]):0);
        		            $inp["amount"] = floatval($_POST["amount"][$i]);
        		            $inp["created_at"] = Date("Y-m-d H:i:s");
        		            
        		            $this->db->table('invoice_details')->insert($inp);
        		        }
        		        
        		        // Account migration
        		        if(!$this->account_migration($invoice_master_id))
        		        {
        		            $this->db->transRollback();
                		    $this->session->setFlashdata('fail', 'Account Migration Failed - Please check ledger configuration');
                		    return redirect()->to($view_file);
        		        }
        		        
        		        $this->db->transCommit();
            		    $this->session->setFlashdata('succ', 'Invoice Added Successfully');
        				return redirect()->to($view_file);
            		}else{
            		    $this->db->transRollback();
            		    $this->session->setFlashdata('fail', 'Please Try Again');
            		    return redirect()->to($view_file);
            		}
    		    }
    		    catch(Exception $ex)
    		    {
    		        $this->db->transRollback();
    		        $this->session->setFlashdata('fail', 'Please Try Again - ' . $ex->getMessage());
            		return redirect()->to($view_file);
    		    }
    		}
    		else // Update existing invoice
    		{
    		    try
    		    {
    		        $this->db->transBegin();
        		    $data['paid_amount'] = floatval($_POST['paid_amount']);
        		    $data['due_amount'] = floatval($_POST['due_amount']);
        		    $data['updated_at'] = Date("Y-m-d H:i:s");
        		    
                    $builder = $this->db->table('invoice')->where('id', $id)->update($data);
        		    if($builder){
        		        $invoice_master_id = $id;
        		        $description = $_POST["description"];
        		        
        		        $i = 0;
        		        foreach($description as $iter)
        		        {
        		            if(trim($_POST["description"][$i]) == "" || floatval($_POST["rate"][$i]) == 0 || floatval($_POST["qty"][$i]) == 0 ){
        		                $i++;
        		                continue;
        		            }
        		            
        		            $inp = [];
        		            if(!isset($_POST["upd_id"][$i])) //new
        		            {
            		            $inp["invoice_master_id"] = $invoice_master_id;
            		            $inp["description"] = $_POST["description"][$i];
            		            $inp["type"] = intval($_POST["type"][$i]) == 0?2:intval($_POST["type"][$i]);
            		            $inp["ledger_id"] = intval($_POST["ledger_id"][$i]);
            		            $inp["rate"] = floatval($_POST["rate"][$i]);
            		            $inp["qty"] = floatval($_POST["qty"][$i]);
            		            $inp["tax"] = (isset($_POST["tax"][$i])?floatval($_POST["tax"][$i]):0);
            		            $inp["amount"] = floatval($_POST["amount"][$i]);
            		            $inp["created_at"] = Date("Y-m-d H:i:s");
            		            $this->db->table('invoice_details')->insert($inp);
        		            }
        		            else
        		            {
            		            $inp["description"] = $_POST["description"][$i];
            		            $inp["type"] = intval($_POST["type"][$i]) == 0?2:intval($_POST["type"][$i]);
            		            $inp["ledger_id"] = intval($_POST["ledger_id"][$i]);
            		            $inp["rate"] = floatval($_POST["rate"][$i]);
            		            $inp["qty"] = floatval($_POST["qty"][$i]);
            		            $inp["tax"] = (isset($_POST["tax"][$i])?floatval($_POST["tax"][$i]):0);
            		            $inp["amount"] = floatval($_POST["amount"][$i]);
            		            $inp["updated_at"] = Date("Y-m-d H:i:s");
            		            
            		            $this->db->table('invoice_details')->where("id",$_POST["upd_id"][$i])->update($inp);
        		            }
        		            $i++;
        		        }
        		        
        		        //Delete removed items
        		        $del_arr = explode(",",$_POST["del_arr"]);
        		        if(!empty($del_arr))
        		        {
        		            $del_arr1 = [];
        		            foreach($del_arr as $iter)
        		            {
        		                if(intval($iter)>0)
        		                    $del_arr1[] = $iter;
        		            }
        		            
        		            if(!empty($del_arr1))
        		            {
        		                $this->db->table('invoice_details')->whereIn("id",$del_arr1)->delete();   
        		            }
        		        }
        		        
        		        // Reset account migration to regenerate entries
        		        $this->db->table('invoice')->where('id', $id)->update(['account_migration' => 0]);
        		        
        		        // Delete old entries
        		        $old_entries = $this->db->table('entries')->where('inv_id', $id)->get()->getResultArray();
        		        foreach($old_entries as $entry)
        		        {
        		            $this->db->table('entryitems')->where('entry_id', $entry['id'])->delete();
        		            $this->db->table('entries')->where('id', $entry['id'])->delete();
        		        }
        		        
        		        // Re-run account migration
        		        if(!$this->account_migration($invoice_master_id))
        		        {
        		            $this->db->transRollback();
                		    $this->session->setFlashdata('fail', 'Account Migration Failed - Please check ledger configuration');
                		    return redirect()->to($view_file);
        		        }
        		        
        		        $this->db->transCommit();
            		    $this->session->setFlashdata('succ', 'Invoice Updated Successfully');
        				return redirect()->to($view_file);
            		}else{
            		    $this->db->transRollback();
            		    $this->session->setFlashdata('fail', 'Please Try Again');
            		    return redirect()->to($view_file);
            		}
    		    }
    		    catch(Exception $ex)
    		    {
    		        $this->db->transRollback();
    		        $this->session->setFlashdata('fail', 'Please Try Again - ' . $ex->getMessage());
            		return redirect()->to($view_file);
    		    }
    		}
		}
		else
		{
		    $this->session->setFlashdata('fail', implode(",",$errmsgarr)." are invalid or empty");
        	return redirect()->to($view_file);
		}
	}
}