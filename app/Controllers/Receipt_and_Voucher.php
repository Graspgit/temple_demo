<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Receipt_and_Voucher extends BaseController
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
	
	public function index() {
	    
		$data['suppliers'] = $this->db->table('receipt_and_voucher')->get()->getResultArray();
		
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
		echo view('receipt_and_voucher/list', $data);
		echo view('template/footer');
    }
	
	public function add() {
		echo view('template/header');
		echo view('template/sidebar');
		echo view('receipt_and_voucher/add');
		echo view('template/footer');
    }
	public function edit($id) {
		$data['supplier'] = $this->db->table('receipt_and_voucher')->where("id", $id)->get()->getRowArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('receipt_and_voucher/add', $data);
		echo view('template/footer');
    }
	public function view($id) {
		$data['supplier'] = $this->db->table('receipt_and_voucher')->where("id", $id)->get()->getRowArray();
		$data['view'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('receipt_and_voucher/add', $data);
		echo view('template/footer');
    }
	public function store() {
		$id = $_POST['id'];
		$data['rv_type'] = $_POST['rv_type'];
		$data['rv_date'] = $_POST['rv_date'];
		$data['name'] = $_POST['name'];
		$data['amount'] = $_POST['amount'];
		$data['remarks'] = $_POST['remarks'];
		
		if(floatval($_POST["balance_amount"]) < $_POST["amount"])
		{
		    $this->session->setFlashdata('fail', 'Balance amount must not be greater than amount');
			return redirect()->to("/Receipt_and_Voucher");
		}
		$errmsgarr = [];
		
		if(intval($_POST['rv_type']) == 0)
		{
		    
		}
		
		if(empty($id)){
		    $data['created_at'] = Date("Y-m-d H:i:s");
			$builder = $this->db->table('receipt_and_voucher')->insert($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Receipt and Voucher Added Successfully');
				return redirect()->to("/Receipt_and_Voucher");
    		}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to("/Receipt_and_Voucher");
    		}
		}
		else
		{
		    $data['updated_at'] = Date("Y-m-d H:i:s");
            $builder = $this->db->table('receipt_and_voucher')->where('id', $id)->update($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Receipt and Voucher Update Successfully');
				return redirect()->to("/Receipt_and_Voucher");
    		}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to("/Receipt_and_Voucher");
    		}
		}
	}
}