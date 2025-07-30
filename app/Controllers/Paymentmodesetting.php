<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Paymentmodesetting extends BaseController
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
		$data['payment_modes'] = $this->db->table('payment_mode')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('paymentmode/list', $data);
		echo view('template/footer');
    }
	
	public function add() {
		$data['ledgers'] = $this->db->table('ledgers')
		->whereIn('group_id', function($builder) {
			return $builder->select('id')
				->from('groups')
				->groupStart()
					->where('code', '1200')
					->orWhereIn('parent_id', function($subBuilder) {
						return $subBuilder->select('id')
							->from('groups')
							->groupStart()
								->where('code', '1200')
								->orWhereIn('parent_id', function($subSubBuilder) {
									return $subSubBuilder->select('id')
										->from('groups')
										->where('code', '1200');
								})
							->groupEnd();
					})
				->groupEnd();
		})
		->get()
		->getResultArray();

		echo view('template/header');
		echo view('template/sidebar');
		echo view('paymentmode/add', $data);
		echo view('template/footer');
    }
	public function edit($id) {
		$data['ledgers'] = $this->db->table('ledgers')
		->whereIn('group_id', function($builder) {
			return $builder->select('id')
				->from('groups')
				->groupStart()
					->where('code', '1200')
					->orWhereIn('parent_id', function($subBuilder) {
						return $subBuilder->select('id')
							->from('groups')
							->groupStart()
								->where('code', '1200')
								->orWhereIn('parent_id', function($subSubBuilder) {
									return $subSubBuilder->select('id')
										->from('groups')
										->where('code', '1200');
								})
							->groupEnd();
					})
				->groupEnd();
		})
		->get()
		->getResultArray();
		$data['payment_mode'] = $this->db->table('payment_mode')->where("id", $id)->get()->getRowArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('paymentmode/add', $data);
		echo view('template/footer');
    }
	public function store() {
		// echo '<pre>';
		// print_r($_POST);
		// echo '</pre>';
		// exit;
		
		$id = $_POST['id'];
		$data['name'] = $_POST['name'];
		$data['description'] = $_POST['description'];
		$data['ledger_id'] = $_POST['ledger_name'];
		$data['menu_order'] = $_POST['order'];
		$data['paid_through'] = $_POST['paid_through'];
		$data['status'] = !empty($_POST['status']) ? $_POST['status'] : 0;
		$data['archanai'] = !empty($_POST['archanai']) ? $_POST['archanai'] : 0;
		$data['prasadam'] = !empty($_POST['prasadam']) ? $_POST['prasadam'] : 0;
		$data['annathanam'] = !empty($_POST['annathanam']) ? $_POST['annathanam'] : 0;
		$data['donation'] = !empty($_POST['donation']) ? $_POST['donation'] : 0;
		$data['hall_booking'] = !empty($_POST['hall_booking']) ? $_POST['hall_booking'] : 0;
		$data['ubayam'] = !empty($_POST['ubayam']) ? $_POST['ubayam'] : 0;
		$data['kattalai_archanai'] = !empty($_POST['kattalai_archanai']) ? $_POST['kattalai_archanai'] : 0;
		$data['outdoor'] = !empty($_POST['outdoor']) ? $_POST['outdoor'] : 0;
		$data['expenses'] = !empty($_POST['expenses']) ? $_POST['expenses'] : 0;
		$data['catering'] = !empty($_POST['catering']) ? $_POST['catering'] : 0;

		if(empty($id)){
			$builder = $this->db->table('payment_mode')->insert($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Payment Mode Added Successfully');
				return redirect()->to("/paymentmodesetting");
    		}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to("/paymentmodesetting");
    		}
		}
		else
		{
            $builder = $this->db->table('payment_mode')->where('id', $id)->update($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Payment Mode Update Successfully');
				return redirect()->to("/paymentmodesetting");
    		}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to("/paymentmodesetting");
    		}
		}
	}
	public function findledgernameExists()
    {
		$ledger_name =  $this->request->getPost('ledger_name');
        $updateid = $this->request->getPost('update_id');
		if(!empty($updateid))
		{
			$query = $this->db->table('payment_mode')->where(['ledger_id' => $ledger_name,'id !=' => $updateid,'status'=>1])->countAllResults();
		}
        else
		{
			$query = $this->db->table('payment_mode')->where(['ledger_id' => $ledger_name,'status'=>1])->countAllResults();
		}
        if($query > 0){
            echo "false";
        }else{
            echo "true";
        }
    }
	public function findtemplenameExists()
    {
		$ledger_name =  $this->request->getPost('ledger_name');
        $updateid = $this->request->getPost('update_id');
		if(!empty($updateid))
		{
			$query = $this->db->table('payment_mode')->where(['ledger_id' => $ledger_name,'id !=' => $updateid,'status'=>1])->countAllResults();
		}
        else
		{
			$query = $this->db->table('payment_mode')->where(['ledger_id' => $ledger_name,'status'=>1])->countAllResults();
		}
        if($query > 0){
            echo "false";
        }else{
            echo "true";
        }
    }
}