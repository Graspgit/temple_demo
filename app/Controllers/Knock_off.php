<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Knock_off extends BaseController
{
    function __construct(){
        parent:: __construct();
        helper('url');
        $this->model = new PermissionModel();
		if( ($this->session->get('login') ) == false && $this->session->get('role') != 1){
            $data['dn_msg'] = 'Please Login';
            return redirect()->to('/login');
		}
    }
    
	public function getList()
	{
	    $type = $_POST["type"];
	    $invoice_id = intval($_POST["invoice_id"]);
	    $supplier_id = intval($_POST["supplier_id"]);
	    $knock_off_id = intval($_POST["knock_off_id"]);
	    
	    $list = "";
	    if($type == 1)
	    {
	        $ledger_data = $this->db->table("customer")->where("id",$supplier_id)->get()->getRowArray();
	        $ledger_id = intval($ledger_data["ledger_id"]);
	    }
	    else
	    {
	        $ledger_data = $this->db->table("supplier")->where("id",$supplier_id)->get()->getRowArray();
	        $ledger_id = intval($ledger_data["ledger_id"]);
	    }
	    
	    if($ledger_id == 0) return $list;
	    
	    $inv_list = [];
	    if($knock_off_id > 0)
	        $inv_list = $this->db->table("knock_off")->where("id",$knock_off_id)->get()->getRowArray();
	    
	    $inv_ids = isset($inv_list["inv_detail_ids"])?explode(",",$inv_list["inv_detail_ids"]):[];
	    
	    // Get available payment/receipt entries for this customer/supplier
	    // For sales (type=1) - get receipt entries (entrytype_id=1) where customer was credited
	    // For purchase (type=2) - get payment entries (entrytype_id=2) where supplier was debited
	    
	    if($type == 1) // Sales - Get Receipts
	    {
	        $entry_data = $this->db->table("entries")
	                      ->select("entries.id, entries.narration, entries.dr_total, entries.date, entries.entry_code")
	                      ->join("entryitems","entryitems.entry_id = entries.id")
	                      ->where("entryitems.ledger_id",$ledger_id)
	                      ->where("entryitems.dc","C") // Customer was debited in receipt
	                      ->where("entries.entrytype_id",1) // Receipt entries
	                      ->where("entries.inv_id IS NULL") // Not yet knocked off
	                      ->groupBy("entries.id")
	                      ->get()
	                      ->getResultArray();
	    }
	    else // Purchase - Get Payments  
	    {
	        $entry_data = $this->db->table("entries")
	                      ->select("entries.id, entries.narration, entries.cr_total as dr_total, entries.date, entries.entry_code")
	                      ->join("entryitems","entryitems.entry_id = entries.id")
	                      ->where("entryitems.ledger_id",$ledger_id)
	                      ->where("entryitems.dc","D") // Supplier was credited in payment
	                      ->where("entries.entrytype_id",2) // Payment entries
	                      ->where("entries.inv_id IS NULL") // Not yet knocked off
	                      ->groupBy("entries.id")
	                      ->get()
	                      ->getResultArray();
	    }
	    
	    $list = $this->getWrapList($entry_data,$inv_ids);
	    echo json_encode(["list"=>$list],true);
	}
	
	public function getAmt()
	{
	    $type = $_POST["type"];
	    $invoice_id = intval($_POST["invoice_id"]);
	    
	    $inv_data = $this->db->table("invoice")->select("(grand_total - paid_amount) as due_amount")->where("id",$invoice_id)->get()->getRowArray();
	    $due_amt = 0;
	    if(isset($inv_data["due_amount"]))
	        $due_amt = floatval($inv_data["due_amount"]);
	        
	    echo $due_amt;
	}
	
	public function getInvoices()
	{
		// Validate inputs
		if(!isset($_REQUEST["type"]) || !isset($_REQUEST["supplier_id"])) {
			http_response_code(400);
			echo json_encode(["error" => "Missing required parameters"]);
			return;
		}

		$type = intval($_REQUEST["type"]);
		$invoice_id = isset($_REQUEST["invoice_id"]) ? intval($_REQUEST["invoice_id"]) : 0;
		$supplier_id = intval($_REQUEST["supplier_id"]);
		$knock_off_id = isset($_REQUEST["knock_off_id"]) ? intval($_REQUEST["knock_off_id"]) : 0;
		
		$list = "";
		
		try {
			if($type === 1) {
				$ledger_data = $this->db->table("customer")->where("id",$supplier_id)->get()->getRowArray();
			} else {
				$ledger_data = $this->db->table("supplier")->where("id",$supplier_id)->get()->getRowArray();
			}
			
			if(!$ledger_data || !isset($ledger_data["ledger_id"])) {
				echo json_encode(["list" => $list, "invoice" => ""]);
				return;
			}
			
			$ledger_id = intval($ledger_data["ledger_id"]);
			
			$inv_list = [];
			if($knock_off_id > 0) {
				$inv_list = $this->db->table("knock_off")->where("id",$knock_off_id)->get()->getRowArray();
			}
			
			$inv_ids = isset($inv_list["inv_detail_ids"]) ? explode(",",$inv_list["inv_detail_ids"]) : [];
			
			if($type === 1) { // Sales
				$entry_data = $this->db->table("entries")
							->select("entries.id, entries.narration, entries.dr_total, entries.date, entries.entry_code")
							->join("entryitems","entryitems.entry_id = entries.id")
							->where("entryitems.ledger_id",$ledger_id)
							->where("entryitems.dc","D")
							->where("entries.entrytype_id",1)
							->where("entries.inv_id IS NULL")
							->groupBy("entries.id")
							->get()
							->getResultArray();
			} else { // Purchase
				$entry_data = $this->db->table("entries")
							->select("entries.id, entries.narration, entries.cr_total as dr_total, entries.date, entries.entry_code")
							->join("entryitems","entryitems.entry_id = entries.id")
							->where("entryitems.ledger_id",$ledger_id)
							->where("entryitems.dc","C")
							->where("entries.entrytype_id",2)
							->where("entries.inv_id IS NULL")
							->groupBy("entries.id")
							->get()
							->getResultArray();
			}
			
			$list = $this->getWrapList($entry_data,$inv_ids);
			$invoice = $this->getInvoice($supplier_id,$type,$invoice_id);
			echo json_encode(["list" => $list, "invoice" => $invoice]);
			
		} catch(Exception $e) {
			http_response_code(500);
			echo json_encode(["error" => "Internal server error"]);
			// Log the actual error for debugging
			error_log($e->getMessage());
		}
	}
	
	public function getInvoice($supplier_id,$typ,$invoice_id)
	{
	    $data = $this->db->table("invoice")
	            ->select("id, invoice_no, (grand_total - paid_amount) as due_amount")
	            ->where("invoice_type",$typ)
	            ->where("customer_supplier_id",$supplier_id)
	            ->where("grand_total > paid_amount") // Only unpaid or partially paid invoices
	            ->get()
	            ->getResultArray();
	            
	   $opt = "<option value=''>Select Invoice</option>";
	   
	   foreach($data as $iter)
	   {
	       $opt .= "<option ".($iter['id'] == $invoice_id?"selected":"")." value='".$iter['id']."'>".$iter['invoice_no']." (Due: ".number_format($iter['due_amount'],2).")</option>";
	   }
	   return $opt;
	}
	
	public function getWrapList($data,$inv_ids)
	{
	    $sno = 1;
	    $tab = "<table border='1'><tr><th align='center'>SNo</th><th align='center'><input type='checkbox' style='position: relative;z-index: 1; opacity: 1; left: 0px;' id='head_checkbox' name='head_checkbox' value='1' /></th><th width='50%' align='center'>Details</th><th width='15%' align='center'>Date</th><th width='20%' align='center'>Amount</th></tr>";
	    foreach($data as $iter)
	    {
	       $tab .= "<tr><td align='center'>".$sno++."</td><td align='center'><input ".(in_array($iter["id"],$inv_ids)?"checked":"")." type='checkbox' id='sub_checkbox' style='position: relative;z-index: 1; opacity: 1; left: 0px;' name='sub_checkbox' class='sub_checkbox' value='1' /></td><td style='padding-left:3px'>".$iter['entry_code']." - ".$iter['narration']."</td><td align='center'>".date('d/m/Y',strtotime($iter['date']))."</td><td style='padding-right:3px' id='amt' align='right'>".number_format($iter['dr_total'],2)."</td><input type='hidden' id='entry_id' name='entry_id' value='".$iter["id"]."' /></tr>"; 
	    }
	    $tab .= "<tr><td colspan='4' align='right' style='padding-right:3px'><b>Total</b></td><td align='right' id='total' style='padding-right:3px'><b>0.00</b></td></table>";
	    return $tab;
	}
	
	public function index() {
		$data['suppliers'] = $this->db->table('knock_off')->where("type",1)->get()->getResultArray();
		
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
		echo view('knock_off/list', $data);
		echo view('template/footer');
    }
    
    public function index_purchase() {
		$data['suppliers'] = $this->db->table('knock_off')->where("type",2)->get()->getResultArray();
		
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
		echo view('knock_off/list_supplier', $data);
		echo view('template/footer');
    }
	
	public function add() {
		echo view('template/header');
		echo view('template/sidebar');
		echo view('knock_off/add');
		echo view('template/footer');
    }
    
	public function edit($id) {
		$data['supplier'] = $this->db->table('knock_off')->where("id", $id)->get()->getRowArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('knock_off/add', $data);
		echo view('template/footer');
    }
    
	public function view($id) {
		$data['supplier'] = $this->db->table('knock_off')->where("id", $id)->get()->getRowArray();
		$data['view'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('knock_off/add', $data);
		echo view('template/footer');
    }
    
	public function store() {
		$id = $_POST['id'];
		$data['type'] = $type = intval($_POST['type']);
		$data['supplier_id'] = $supplier_id = intval($_POST['supplier_id']);
		$data['invoice_id'] = $inv_id = intval($_POST['invoice_id']);
		
		$view_file = ($type == 1?"/Knock_off":"/Knock_off/index_purchase");
		
		if(intval($_POST['hid_amount']) == 0)
		{
		   $this->session->setFlashdata('fail', 'Amount must not be zero');
    	   return redirect()->to($view_file); 
		}
		
		$inv_list = $this->db->table("invoice")->where("id",$inv_id)->get()->getRowArray();
		if(!isset($inv_list["id"]))
		{
		    $this->session->setFlashdata('fail', 'Invoice not found');
    	   return redirect()->to($view_file); 
		}
		
		$paid_amt = floatval($inv_list["paid_amount"]);
		$grand_total = floatval($inv_list["grand_total"]);
		$knock_off_amount = floatval($_POST['hid_amount']);
		
		// Check if knock off amount doesn't exceed due amount
		$due_amount = $grand_total - $paid_amt;
		if($knock_off_amount > $due_amount)
		{
		    $this->session->setFlashdata('fail', 'Knock off amount cannot exceed due amount');
    	   return redirect()->to($view_file);
		}
		
		$inv_detail_ids = explode(",",$_POST["inv_detail_ids"]);
		
		try {
		    $this->db->transBegin();
		    
    		if(empty($id)){
    		    $data['inv_detail_ids'] = $_POST["inv_detail_ids"];
    		    $data['created_at'] = Date("Y-m-d H:i:s");
    			$builder = $this->db->table('knock_off')->insert($data);
    			
    		    if($builder){
    		        $knock_off_id = $this->db->insertID();
    		        
    		        // Update invoice paid amount
    		        $new_paid_amount = $paid_amt + $knock_off_amount;
    		        $this->db->table("invoice")->where("id",$inv_id)->update(["paid_amount" => $new_paid_amount]);
    		        
    		        // Update entries with inv_id and type for knocked off payments/receipts
    		        $det_ids = [];
    		        foreach($inv_detail_ids as $iter)
    		        {
    		            if(intval($iter) > 0)
    		                $det_ids[] = $iter;
    		        }
    		   
    		        if(!empty($det_ids))
    		        {
    		            $inv_type = $inv_list["invoice_type"];
    		            $this->db->table("entries")->whereIn("id",$det_ids)->update([
    		                "inv_id" => $inv_id,
    		                "type" => ($inv_type == 1 ? 18 : 19) // 18 for sales, 19 for purchase
    		            ]);
    		        }   
    		        
    		        $this->db->transCommit();
        		    $this->session->setFlashdata('succ', 'Knock off Added Successfully');
    				return redirect()->to($view_file);
        		}else{
        		    $this->db->transRollback();
        		    $this->session->setFlashdata('fail', 'Please Try Again');
        	    return redirect()->to($view_file);
        		}
    		}
    		else // Update existing knock off
    		{
    		    // Get old knock off data
    		    $old_knock_off = $this->db->table('knock_off')->where('id', $id)->get()->getRowArray();
    		    if(!$old_knock_off) {
    		        $this->db->transRollback();
    		        $this->session->setFlashdata('fail', 'Knock off record not found');
        	        return redirect()->to($view_file);
    		    }
    		    
    		    // Reverse old knock off entries
    		    $old_entry_ids = explode(",", $old_knock_off['inv_detail_ids']);
    		    foreach($old_entry_ids as $entry_id) {
    		        if(intval($entry_id) > 0) {
    		            $this->db->table("entries")->where("id", $entry_id)->update([
    		                "inv_id" => null,
    		                "type" => null
    		            ]);
    		        }
    		    }
    		    
    		    // Get the old amount to reverse from invoice
    		    $old_amount = $this->calculateKnockOffAmount($old_entry_ids);
    		    
    		    // Update invoice paid amount (subtract old, add new)
    		    $current_invoice = $this->db->table("invoice")->where("id",$inv_id)->get()->getRowArray();
    		    $current_paid = floatval($current_invoice["paid_amount"]);
    		    $new_paid_amount = $current_paid - $old_amount + $knock_off_amount;
    		    $this->db->table("invoice")->where("id",$inv_id)->update(["paid_amount" => $new_paid_amount]);
    		    
    		    // Update knock off record
    		    $data['inv_detail_ids'] = $_POST["inv_detail_ids"];
    		    $data['updated_at'] = Date("Y-m-d H:i:s");
                $builder = $this->db->table('knock_off')->where('id', $id)->update($data);
                
    		    if($builder){
    		        $inv_type = $inv_list["invoice_type"];
    		        
    		        // Apply new knock off entries
    		        $det_ids = [];
    		        foreach($inv_detail_ids as $iter)
    		        {
    		            if(intval($iter) > 0)
    		                $det_ids[] = $iter;
    		        }
    		        
    		        if(!empty($det_ids))
    		        {
    		            $this->db->table("entries")->whereIn("id",$det_ids)->update([
    		                "inv_id" => $inv_id,
    		                "type" => ($inv_type == 1 ? 18 : 19)
    		            ]);
    		        }   
    		        
    		        $this->db->transCommit();
        		    $this->session->setFlashdata('succ', 'Knock off Updated Successfully');
    				return redirect()->to($view_file);
        		}else{
        		    $this->db->transRollback();
        		    $this->session->setFlashdata('fail', 'Please Try Again');
        		    return redirect()->to($view_file);
        		}
    		}
		} catch (Exception $e) {
		    $this->db->transRollback();
		    $this->session->setFlashdata('fail', 'Error: ' . $e->getMessage());
		    return redirect()->to($view_file);
		}
	}
	
	/**
	 * Calculate total amount for given entry IDs
	 */
	private function calculateKnockOffAmount($entry_ids)
	{
	    $total = 0;
	    foreach($entry_ids as $entry_id) {
	        if(intval($entry_id) > 0) {
	            $entry = $this->db->table("entries")->where("id", $entry_id)->get()->getRowArray();
	            if($entry) {
	                if($entry['entrytype_id'] == 1) { // Receipt
	                    $total += floatval($entry['dr_total']);
	                } else if($entry['entrytype_id'] == 2) { // Payment
	                    $total += floatval($entry['cr_total']);
	                }
	            }
	        }
	    }
	    return $total;
	}
}