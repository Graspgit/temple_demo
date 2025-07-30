<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;
use App\Models\PurchaseOrderModel;
use App\Models\SupplierModel;
use App\Models\CustomerModel;
use App\Models\PurchaseOrderDetailsModel;

class Purchase_order extends BaseController
{
	protected $purchaseOrderModel;
    protected $supplierModel;
    protected $customerModel;
    protected $purchaseOrderDetailsModel;

    function __construct(){
        parent:: __construct();
        helper('url');
        $this->model = new PermissionModel();
		$this->purchaseOrderModel = new PurchaseOrderModel();
        $this->supplierModel = new SupplierModel();
        $this->customerModel = new CustomerModel();
        $this->purchaseOrderDetailsModel = new PurchaseOrderDetailsModel();
		if( ($this->session->get('login') ) == false && $this->session->get('role') != 1){
            $data['dn_msg'] = 'Please Login';
            header('Location: '.base_url().'/login');
            exit;
		}
    }
    
    /*******************************************************************************************/
    
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
    
    public function account_migration($inv_id)
    {
        $invoice_data = $this->db->table("invoice")->where("id",$inv_id)->get()->getRowArray();
        if(!isset($invoice_data["id"]))
            return false;
        
        // Check if already migrated
        $account_migration = $invoice_data["account_migration"];
        if($account_migration == 1)
            return true;
          
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
            return false;
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
        $entries['type'] = ($typ == 1?'18':'19');
        
        $ent = $this->db->table('entries')->insert($entries);
        $en_id = $this->db->insertID();
        
        if (!empty($en_id)) {
            
            if($typ == 1) // SALES INVOICE
            {
                // 1. Debit: Trade Debtors (Customer Account) with Grand Total
                $eitems_debtor = array();
                $eitems_debtor['entry_id'] = $en_id;
                $eitems_debtor['ledger_id'] = $customer_supplier_ledger;
                $eitems_debtor['amount'] = $grand_total;
                $eitems_debtor['dc'] = 'D';
                $eitems_debtor['details'] = 'SALES INVOICE (' . $inv_no . ')';
                $this->db->table('entryitems')->insert($eitems_debtor);
                
                // 2. Credit: Revenue Accounts (from invoice details)
                foreach($invoice_detail_data as $iter)
                {
                    if($iter["ledger_id"] > 0)
                    {
                        $eitems_revenue = array();
                        $eitems_revenue['entry_id'] = $en_id;
                        $eitems_revenue['ledger_id'] = $iter["ledger_id"];
                        $eitems_revenue['amount'] = $iter["amount"];
                        $eitems_revenue['dc'] = 'C';
                        $eitems_revenue['details'] = 'SALES INVOICE (' . $inv_no . ') - ' . $iter["description"];
                        $this->db->table('entryitems')->insert($eitems_revenue);
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
                        $eitems_discount['dc'] = 'D';
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
                $eitems_creditor['dc'] = 'C';
                $eitems_creditor['details'] = 'PURCHASE INVOICE (' . $inv_no . ')';
                $this->db->table('entryitems')->insert($eitems_creditor);
                
                // 2. Debit: Direct Cost/Expense Accounts (from invoice details)
                foreach($invoice_detail_data as $iter)
                {
                    if($iter["ledger_id"] > 0)
                    {
                        $eitems_expense = array();
                        $eitems_expense['entry_id'] = $en_id;
                        $eitems_expense['ledger_id'] = $iter["ledger_id"];
                        $eitems_expense['amount'] = $iter["amount"];
                        $eitems_expense['dc'] = 'D';
                        $eitems_expense['details'] = 'PURCHASE INVOICE (' . $inv_no . ') - ' . $iter["description"];
                        $this->db->table('entryitems')->insert($eitems_expense);
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
                        $eitems_discount['dc'] = 'C';
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
    
    /*******************************************************************************************/
    public function getPONo($invoice_type,$invoice_master_id=0,$req_for='PO')
    {
        $invoice_master_id = intval($invoice_master_id);
        if($invoice_master_id==0)
        {
            $max_data = $this->db->table("purchase_order")->select("max(id) as id")->where("invoice_type",$invoice_type)->get()->getRowArray();
            $invoice_master_id = $max_data["id"];
        }
        $lab = ($invoice_type == 1?"SO0000":"PO0000"); // Changed from RE0000 to SO0000 for Sales Orders
        if($req_for == "Invoice")
        {
            $lab = ($invoice_type == 1?"SAIV0000":"PUIV0000");
        }
        return $inv_no = $lab.$invoice_master_id;
    }
	public function approve()
	{
	    $id = $_POST["id"];
	    $type = $_POST["type"];
	    $invoice_no = trim($_POST["invoice_no"]);
	    
	    if($invoice_no == "")
	    {
	        echo json_encode(["res"=>false,"errMsg"=>"Invoice No must not be empty"]);
	        return;
	    }
	    
	    $data1 = $this->db->table("purchase_order")->where("id",$id)->where("is_approved",0)->get()->getRowArray();
	        
	    if(!isset($data1["id"]))
	    {
	        echo json_encode(["res"=>false,"errMsg"=>"Record not found or already approved"]);
	        return;
	    }
	    
	    // Check if ledger_id is provided for all purchase order details
	    $data_details = $this->db->table("purchase_order_details")->where("invoice_master_id",$id)->get()->getResultArray();
	    
	    foreach($data_details as $detail)
	    {
	        if(!isset($detail["ledger_id"]) || intval($detail["ledger_id"]) == 0)
	        {
	            echo json_encode(["status"=>false,"errMsg"=>"Please assign ledger accounts to all items before approval"]);
	            return;
	        }
	    }
	    
	    try {
	        $this->db->transBegin();
	        
	        $data = [];
	        $data['invoice_type'] = $inv_type = intval($data1['invoice_type']);
    		$data['customer_supplier_id'] = intval($data1['customer_supplier_id']);
    		$data['date'] = $data1['date'];
    		//$data['challan_no'] = (isset($data1["challan_no"])?$data1["challan_no"]:"");
    		$data['invoice_no'] = $invoice_no;
    		$data['remarks'] = $data1['remarks'];
    		$data['total'] = floatval($data1['total']);
    		$data['discount'] = floatval($data1['discount']);
    		$data['grand_total'] = floatval($data1['grand_total']);
    	    $data["po_id"] = $id;
    		$data['paid_amount'] = 0;
    		$data['due_amount'] = floatval($data1['grand_total']);
    		$data['created_at'] = Date("Y-m-d H:i:s");
    		    
    		$builder = $this->db->table('invoice')->insert($data);
    		    
    		if($builder){
    		    $invoice_master_id = $this->db->insertID();
    		    //$inv_no = $this->getPONo($inv_type,$invoice_master_id,"Invoice");
    		    //$this->db->table('invoice')->where("id",$invoice_master_id)->update(["invoice_no"=>$inv_no]);
    		    
            	foreach($data_details as $iter)
            	{
            	    $inp = [];
        	        $inp["invoice_master_id"] = $invoice_master_id;
        	        $inp["description"] = $iter["description"];
        	        $inp["type"] = intval($iter["type"]) == 0?2:intval($iter["type"]);
        	        $inp["ledger_id"] = intval($iter["ledger_id"]); // This is crucial
        	        $inp["rate"] = floatval($iter["rate"]);
        	        $inp["qty"] = floatval($iter["qty"]);
        	        $inp["tax"] = floatval($iter["tax"]);
        	        $inp["amount"] = floatval($iter["amount"]);
        	        $inp["created_at"] = Date("Y-m-d H:i:s");
        	        
                    $this->db->table('invoice_details')->insert($inp);
            	}
            	    
        	    if(!$this->account_migration($invoice_master_id))
                {
                    $this->db->transRollback();
                    echo json_encode(["status"=>false,"errMsg"=>"Account migration failed"]);
                    return;
                }
                
                $this->db->table('purchase_order')->where("id",$id)->update(["is_approved"=>1]);
                $this->db->transCommit();
                echo json_encode(["status"=>true,"message"=>"Purchase order approved and invoice created successfully"]);
    		}
    		else {
    		    $this->db->transRollback();
    		    echo json_encode(["status"=>false,"errMsg"=>"Failed to create invoice"]);
    		}
    		
	    } catch (Exception $e) {
	        $this->db->transRollback();
	        echo json_encode(["status"=>false,"errMsg"=>"Error: " . $e->getMessage()]);
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
	
	public function purchase_order_purchase()
	{
	    $data['suppliers'] = $this->db->table('purchase_order')->where("invoice_type",2)->get()->getResultArray();
		
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
		echo view('purchase_order/list_purchase', $data);
		echo view('template/footer');
	}
	
	public function index() {
		$data['suppliers'] = $this->db->table('purchase_order')->where("invoice_type",1)->get()->getResultArray();
		
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
		echo view('purchase_order/list', $data);
		echo view('template/footer');
    }
	public function getPONoType()
	{
	    $invoice_type = intval($_POST["invoice_type"]);
	    
	    $no = "";
	    if($invoice_type>0)
	    {
	        $no = $this->getPONo($invoice_type);
	    }
	    
	    echo $no; // Fixed: was missing echo statement
	}
	public function add() {
		$settings_data = $this->db->table("settings")->where('type', 8)->get()->getResultArray();
		$sales_settings = array();
		foreach($settings_data as $iter){
		    $sales_settings[$iter["setting_name"]] = $iter["setting_value"];
		}
		$settings_data = $this->db->table("settings")->where('type', 9)->get()->getResultArray();
		$purchase_settings = array();
		foreach($settings_data as $iter){
		    $purchase_settings[$iter["setting_name"]] = $iter["setting_value"];
		}
		$typ = intval($_REQUEST["req_typ"])==0?1:$_REQUEST["req_typ"];
		$data["supplier"]["invoice_no"] = $this->getPONo($typ);
		$data['sales_settings'] = $sales_settings;
		$data['purchase_settings'] = $purchase_settings;
		
		// Add default ledger settings
		$ledger_arr = ["purchase_default_discount_ledger","purchase_default_ledger","purchase_default_tax_ledger","sales_default_discount_ledger","sales_default_ledger","sales_default_tax_ledger"];
		foreach($ledger_arr as $iter)
		{
		    $data[$iter] = 0;
		}
		
		$settings_data = $this->db->table("settings")->where("setting_name in(".implode(",",
		    array_map(function($v) {
                return '"' . $v . '"';
            }, $ledger_arr)
		).")")->get()->getResultArray();
		
		foreach($settings_data as $iter)
		{
		    $data[$iter["setting_name"]] = $iter["setting_value"];
		}
		
		// Get ledger options for purchase orders
		$data['sales_ledgers'] = $this->db->query("SELECT id,name, right_code, left_code FROM `ledgers` where group_id in (SELECT id FROM `groups` WHERE code in (1200, 4000) or parent_id in (SELECT id FROM `groups` WHERE code in (1200, 4000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (1200, 4000))))")->getResultArray();
		$data['purchase_ledgers'] = $this->db->query("SELECT id,name, right_code, left_code FROM `ledgers` where group_id in (SELECT id FROM `groups` WHERE code in (1200, 5000) or parent_id in (SELECT id FROM `groups` WHERE code in (1200, 5000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (1200, 5000))))")->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('purchase_order/add', $data);
		echo view('template/footer');
    }
    
	public function edit($id) {
		$data['supplier'] = $this->db->table('purchase_order')->where("id", $id)->get()->getRowArray();
		$data['data_list'] = $this->db->table('purchase_order_details')->where("invoice_master_id", $id)->get()->getResultArray();
		
		echo view('template/header');
		echo view('template/sidebar');
	
		$ledger_arr = ["purchase_default_discount_ledger","purchase_default_ledger","purchase_default_tax_ledger","sales_default_discount_ledger","sales_default_ledger","sales_default_tax_ledger"];
		foreach($ledger_arr as $iter)
		{
		    $data[$iter] = 0;
		}
		
		$settings_data = $this->db->table("settings")->where("setting_name in(".implode(",",
		    array_map(function($v) {
                return '"' . $v . '"';
            }, $ledger_arr)
		).")")->get()->getResultArray();
		
		foreach($settings_data as $iter)
		{
		    $data[$iter["setting_name"]] = $iter["setting_value"];
		}
		
		$data['sales_ledgers'] = $this->db->query("SELECT id,name, right_code, left_code FROM `ledgers` where group_id in (SELECT id FROM `groups` WHERE code in (1200, 4000) or parent_id in (SELECT id FROM `groups` WHERE code in (1200, 4000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (1200, 4000))))")->getResultArray();
		$data['purchase_ledgers'] = $this->db->query("SELECT id,name, right_code, left_code FROM `ledgers` where group_id in (SELECT id FROM `groups` WHERE code in (1200, 5000) or parent_id in (SELECT id FROM `groups` WHERE code in (1200, 5000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (1200, 5000))))")->getResultArray();
		
		echo view('purchase_order/add', $data);
		echo view('template/footer');
    }
    
	public function view($id) {
		$data['supplier'] = $this->db->table('purchase_order')->where("id", $id)->get()->getRowArray();
		$data['data_list'] = $this->db->table('purchase_order_details')->where("invoice_master_id", $id)->get()->getResultArray();
		$data['view'] = true;
		
		echo view('template/header');
		echo view('template/sidebar');
		
		$data['sales_ledgers'] = $this->db->query("SELECT id,name, right_code, left_code FROM `ledgers` where group_id in (SELECT id FROM `groups` WHERE code in (1200, 4000) or parent_id in (SELECT id FROM `groups` WHERE code in (1200, 4000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (1200, 4000))))")->getResultArray();
		$data['purchase_ledgers'] = $this->db->query("SELECT id,name, right_code, left_code FROM `ledgers` where group_id in (SELECT id FROM `groups` WHERE code in (1200, 5000) or parent_id in (SELECT id FROM `groups` WHERE code in (1200, 5000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (1200, 5000))))")->getResultArray();
		
		echo view('purchase_order/add', $data);
		echo view('template/footer');
    }
    
	public function store() {
		$id = $_POST['id'];
		$errmsgarr = [];
		
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
		        if(trim($_POST["description"][$i]) != "" && (!isset($_POST["ledger_id"][$i]) || intval($_POST["ledger_id"][$i]) == 0))
		        {
		            $errmsgarr[] = "ledger selection for item: " . $_POST["description"][$i];
		        }
		    }
		}
		
		$inv_type = intval($_POST['invoice_type']);
		$view_file = ($inv_type == 1?"/purchase_order":"/purchase_order/purchase_order_purchase");
		
		if(empty($errmsgarr))
		{
    		$data['invoice_type'] = $inv_type;
    		$data['customer_supplier_id'] = intval($_POST['customer_supplier_id']);
    		$data['date'] = $_POST['date'];
    		//$data['challan_no'] = $_POST["challan_no"];
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
        			$builder = $this->db->table('purchase_order')->insert($data);
        		    if($builder){
        		        $invoice_master_id = $this->db->insertID();
        		        
        		        $invoice_po_no = $this->getPONo($inv_type,$invoice_master_id);
        		        $this->db->table('purchase_order')->where("id",$invoice_master_id)->update(["po_no"=>$invoice_po_no]);
        		        $description = $_POST["description"];
        		        
        		        for($i=0;$i<count($_POST["description"]);$i++)
        		        {
        		            if(trim($_POST["description"][$i]) == "" || floatval($_POST["rate"][$i]) == 0 || floatval($_POST["qty"][$i]) == 0 )continue;
        		            
        		            $inp = [];
        		            $inp["invoice_master_id"] = $invoice_master_id;
        		            $inp["description"] = $_POST["description"][$i];
        		            $inp["type"] = intval($_POST["type"][$i]) == 0?2:intval($_POST["type"][$i]);
        		            $inp["ledger_id"] = isset($_POST["ledger_id"][$i]) ? intval($_POST["ledger_id"][$i]) : 0;
        		            $inp["rate"] = floatval($_POST["rate"][$i]);
        		            $inp["qty"] = floatval($_POST["qty"][$i]);
        		            $inp["tax"] = (isset($_POST["tax"][$i])?floatval($_POST["tax"][$i]):0);
        		            $inp["amount"] = floatval($_POST["amount"][$i]);
        		            $inp["created_at"] = Date("Y-m-d H:i:s");
        		            
        		            $this->db->table('purchase_order_details')->insert($inp);
        		        }
        		        
        		        $this->db->transCommit();
            		    $this->session->setFlashdata('succ', ($inv_type == 1 ? 'Sales' : 'Purchase') . ' Order Added Successfully');
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
    		else // Update existing purchase order
    		{
    		    $data['updated_at'] = Date("Y-m-d H:i:s");
    		    try
    		    {
    		        $this->db->transBegin();
        		    $data['paid_amount'] = floatval($_POST['paid_amount']);
        		    $data['due_amount'] = floatval($_POST['due_amount']);
        		    
                    $builder = $this->db->table('purchase_order')->where('id', $id)->update($data);
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
            		            $inp["ledger_id"] = isset($_POST["ledger_id"][$i]) ? intval($_POST["ledger_id"][$i]) : 0;
            		            $inp["rate"] = floatval($_POST["rate"][$i]);
            		            $inp["qty"] = floatval($_POST["qty"][$i]);
            		            $inp["tax"] = (isset($_POST["tax"][$i])?floatval($_POST["tax"][$i]):0);
            		            $inp["amount"] = floatval($_POST["amount"][$i]);
            		            $inp["created_at"] = Date("Y-m-d H:i:s");
            		            $this->db->table('purchase_order_details')->insert($inp);
        		            }
        		            else
        		            {
            		            $inp["description"] = $_POST["description"][$i];
            		            $inp["type"] = intval($_POST["type"][$i]) == 0?2:intval($_POST["type"][$i]);
            		            $inp["ledger_id"] = isset($_POST["ledger_id"][$i]) ? intval($_POST["ledger_id"][$i]) : 0;
            		            $inp["rate"] = floatval($_POST["rate"][$i]);
            		            $inp["qty"] = floatval($_POST["qty"][$i]);
            		            $inp["tax"] = (isset($_POST["tax"][$i])?floatval($_POST["tax"][$i]):0);
            		            $inp["amount"] = floatval($_POST["amount"][$i]);
            		            $inp["updated_at"] = Date("Y-m-d H:i:s");
            		            
            		            $this->db->table('purchase_order_details')->where("id",intval($_POST["upd_id"][$i]))->update($inp);
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
        		                $this->db->table('purchase_order_details')->whereIn("id",$del_arr1)->delete();   
        		            }
        		        }
        		        
        		        $this->db->transCommit();
            		    $this->session->setFlashdata('succ', ($inv_type == 1 ? 'Sales' : 'Purchase') . ' Order Updated Successfully');
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
	 public function print($id = null)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Purchase Order ID is required');
        }

        try {
            // Get purchase order data
            $purchaseOrder = $this->purchaseOrderModel->find($id);
            
            if (!$purchaseOrder) {
                return redirect()->back()->with('error', 'Purchase Order not found');
            }

            // Get supplier or customer data based on invoice type
            if ($purchaseOrder['invoice_type'] == 2) { // Purchase
                $supplierCustomer = $this->supplierModel->find($purchaseOrder['customer_supplier_id']);
                $entityType = 'supplier';
            } else { // Sales
                $supplierCustomer = $this->customerModel->find($purchaseOrder['customer_supplier_id']);
                $entityType = 'customer';
            }

            // Get purchase order details
            $orderDetails = $this->purchaseOrderDetailsModel
                ->where('invoice_master_id', $id)
                ->findAll();

            // Get company settings (you may need to create this table)
            $companySettings = $this->getCompanySettings();

            $data = [
                'purchaseOrder' => $purchaseOrder,
                'supplierCustomer' => $supplierCustomer,
                'orderDetails' => $orderDetails,
                'entityType' => $entityType,
                'companySettings' => $companySettings,
                'title' => (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'Sales Order Print' : 'Purchase Order Print') . ' - ' . ($purchaseOrder['po_no'] ?? 'PO-' . $purchaseOrder['id'])
            ];

            return view('purchase_order/print', $data);

        } catch (\Exception $e) {
            log_message('error', 'Purchase Order Print Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF of purchase order
     */
    public function generatePDF($id = null)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Purchase Order ID is required');
        }

        try {
            // Get purchase order data (same as print method)
            $purchaseOrder = $this->purchaseOrderModel->find($id);
            
            if (!$purchaseOrder) {
                return redirect()->back()->with('error', 'Purchase Order not found');
            }

            if ($purchaseOrder['invoice_type'] == 2) {
                $supplierCustomer = $this->supplierModel->find($purchaseOrder['customer_supplier_id']);
                $entityType = 'supplier';
            } else {
                $supplierCustomer = $this->customerModel->find($purchaseOrder['customer_supplier_id']);
                $entityType = 'customer';
            }

            $orderDetails = $this->purchaseOrderDetailsModel
                ->where('invoice_master_id', $id)
                ->findAll();

            $companySettings = $this->getCompanySettings();

            $data = [
                'purchaseOrder' => $purchaseOrder,
                'supplierCustomer' => $supplierCustomer,
                'orderDetails' => $orderDetails,
                'entityType' => $entityType,
                'companySettings' => $companySettings
            ];

            // Load TCPDF library (install via composer: composer require tecnickcom/tcpdf)
            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // Set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor($companySettings['company_name'] ?? 'Your Company');
            $pdf->SetTitle((($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'Sales Order' : 'Purchase Order') . ' - ' . ($purchaseOrder['po_no'] ?? 'PO-' . $purchaseOrder['id']));
            $pdf->SetSubject(($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'Sales Order' : 'Purchase Order');

            // Set margins
            $pdf->SetMargins(15, 15, 15);
            $pdf->SetHeaderMargin(5);
            $pdf->SetFooterMargin(10);

            // Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // Add a page
            $pdf->AddPage();

            // Generate HTML content
            $html = view('purchase_order/pdf_template', $data);

            // Print text using writeHTMLCell()
            $pdf->writeHTML($html, true, false, true, false, '');

            // Output PDF
            $filename = (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'SalesOrder_' : 'PurchaseOrder_') . ($purchaseOrder['po_no'] ?? 'PO-' . $purchaseOrder['id']) . '_' . date('Y-m-d') . '.pdf';
            $pdf->Output($filename, 'D'); // D = download, I = inline view

        } catch (\Exception $e) {
            log_message('error', 'PDF Generation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }

    /**
     * Get company settings from admin_profile table
     */
    private function getCompanySettings()
    {
        $adminProfileModel = new \App\Models\AdminProfileModel();
        $profile = $adminProfileModel->first();
        
        if (!$profile) {
            // Return default settings if no profile found
            return [
                'company_name' => 'YOUR COMPANY NAME',
                'address1' => 'Your Company Address Line 1',
                'address2' => 'Your Company Address Line 2',
                'city' => 'Your City',
                'postcode' => '12345',
                'telephone' => '+60-XXX-XXXXXX',
                'fax_no' => '+60-XXX-XXXXXX',
                'email' => 'info@yourcompany.com',
                'website' => 'www.yourcompany.com'
            ];
        }
        
        return [
            'company_name' => $profile['name'] ?? 'YOUR COMPANY NAME',
            'company_name_tamil' => $profile['name_tamil'] ?? '',
            'address1' => $profile['address1'] ?? '',
            'address2' => $profile['address2'] ?? '',
            'city' => $profile['city'] ?? '',
            'city_tamil' => $profile['city_tamil'] ?? '',
            'postcode' => $profile['postcode'] ?? '',
            'telephone' => $profile['telephone'] ?? '',
            'mobile' => $profile['mobile'] ?? '',
            'fax_no' => $profile['fax_no'] ?? '',
            'email' => $profile['email'] ?? '',
            'website' => $profile['website'] ?? '',
            'regno' => $profile['regno'] ?? '',
            'gstno' => $profile['gstno'] ?? '',
            'since_eng' => $profile['since_eng'] ?? '',
            'since_tamil' => $profile['since_tamil'] ?? '',
            'image' => $profile['image'] ?? '',
            'ar_image' => $profile['ar_image'] ?? ''
        ];
    }

    /**
     * AJAX method to get purchase order data as JSON
     */
    public function getPurchaseOrderData($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['error' => 'Purchase Order ID is required']);
        }

        try {
            $purchaseOrder = $this->purchaseOrderModel->find($id);
            
            if (!$purchaseOrder) {
                return $this->response->setJSON(['error' => 'Purchase Order not found']);
            }

            if ($purchaseOrder['invoice_type'] == 2) {
                $supplierCustomer = $this->supplierModel->find($purchaseOrder['customer_supplier_id']);
                $entityType = 'supplier';
            } else {
                $supplierCustomer = $this->customerModel->find($purchaseOrder['customer_supplier_id']);
                $entityType = 'customer';
            }

            $orderDetails = $this->purchaseOrderDetailsModel
                ->where('invoice_master_id', $id)
                ->findAll();

            $data = [
                'po' => $purchaseOrder,
                'supplier' => $supplierCustomer,
                'customer' => $supplierCustomer,
                'items' => $orderDetails,
                'entityType' => $entityType
            ];

            return $this->response->setJSON($data);

        } catch (\Exception $e) {
            log_message('error', 'AJAX Purchase Order Data Error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }
}