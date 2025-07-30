<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Kattalai_archanai extends BaseController
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
    
    public function setting(){
        if(!$this->model->list_validate('archanai_setting')){
			return redirect()->to(base_url().'/dashboard');}
		$data['permission'] = $this->model->get_permission('archanai_setting');
		$data['list'] = $this->db->table('kattalai_archanai')
								->select('*')->get()->getResultArray();

		echo view('template/header');
		echo view('template/sidebar');
		echo view('kattalai_archanai/setting_list', $data);
		echo view('template/footer');
    }

    public function add()
	{
		if(!$this->model->permission_validate('archanai_setting', 'create_p')){
			return redirect()->to(base_url().'/dashboard');}
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->where('paid_through', 'DIRECT')->get()->getResultArray();
		$three_level_group = get_three_level_in_group($code = array("4000","8000"));
		$data['ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->whereIn('group_id', $three_level_group)->orderBy('right_code','asc')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('kattalai_archanai/setting_add', $data);
		echo view('template/footer');
	}
    
    public function save(){
        $id = $_POST['id'];	
		
		if(isset($_POST['view_archanai'])) $data['view_archanai'] = 0;
		else $data['view_archanai'] = 1;
		$data['name_eng']	 =	trim($_POST['name_eng']);
		$data['name_tamil']		 =	trim($_POST['name_tamil']);
		$data['amount']	 =	trim($_POST['amount']);
		$data['commission']	 =	trim($_POST['commission']);
		//$data['commission_percentage']	 =	trim($_POST['com_per']);
		$data['order_no'] =	trim($_POST['order_no']);
		if(!empty($_POST['ledger_id'])) $data['ledger_id'] = $_POST['ledger_id'];
		$data['added_by']	 =	$this->session->get('log_id');
		$hallEditorArray = $_POST['desc_editor'];
		$data['description'] = json_encode($hallEditorArray);
		
		if(empty($id)){
		    $data['created']  =	date('Y-m-d H:i:s');
			$data['modified'] = date('Y-m-d H:i:s');
		    $builder = $this->db->table('kattalai_archanai')->insert($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Kattalai Archanai Added Successfully');
    		    return redirect()->to(base_url()."/kattalai_archanai/setting");}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to(base_url()."/kattalai_archanai/setting");}
		}else{
            $data['modified' ] = date('Y-m-d H:i:s');
            $builder = $this->db->table('kattalai_archanai')->where('id', $id)->update($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Kattalai Archanai Update Successfully');
    		    return redirect()->to(base_url()."/kattalai_archanai/setting");}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to(base_url()."/kattalai_archanai/setting");}
		}
	}
	
	public function edit(){
	    if(!$this->model->permission_validate('archanai_setting', 'edit')){
			return redirect()->to(base_url().'/dashboard');}
	    $id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('kattalai_archanai')->where('id', $id)->get()->getRowArray();
		$three_level_group = get_three_level_in_group($code = array("4000","8000"));
		$data['ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->whereIn('group_id', $three_level_group)->orderBy('right_code','asc')->get()->getResultArray();
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('kattalai_archanai/setting_add', $data);
		echo view('template/footer');
	}
	
	public function view(){
	    if(!$this->model->permission_validate('archanai_setting', 'view')){
			return redirect()->to(base_url().'/dashboard');}
	    $id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('kattalai_archanai')->where('id', $id)->get()->getRowArray();
	    $data['archanai_group'] = $this->db->table('archanai_group')->get()->getResultArray();
		$data['archanai_categories'] = $this->db->table('archanai_category')->where('status',1)->get()->getResultArray();
		$data['archanai_diety'] = $this->db->table('archanai_diety')->get()->getResultArray();
		$three_level_group = get_three_level_in_group($code = array("4000","8000"));
		$data['ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->whereIn('group_id', $three_level_group)->orderBy('right_code','asc')->get()->getResultArray();
	    $data['view'] = true;
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('kattalai_archanai/setting_add', $data);
		echo view('template/footer');
	}
	
	public function delete(){
	    if(!$this->model->permission_validate('archanai_setting', 'delete_p')){
			return redirect()->to(base_url().'/dashboard');}
	    $id=  $this->request->uri->getSegment(3);
		$res = $this->db->table('kattalai_archanai')->delete(['id' => $id]);
		if($res){
		    $this->session->setFlashdata('succ', 'Archanai Delete Successfully');
		    return redirect()->to(base_url()."/kattalai_archanai/setting");}else{
		    $this->session->setFlashdata('fail', 'Please Try Again');
		    return redirect()->to(base_url()."/kattalai_archanai/setting");}
	}
	public function del_check(){
		$id = $_POST['id'];
		$res = $this->db->table("archanai_booking_details")->where("archanai_id", $id)->get()->getResultArray();
		echo count($res);
	}
	
	public function booking()
	{
		//echo '<pre>';
		if (!$this->model->list_validate('archanai_ticket')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data['permission'] = $this->model->get_permission('archanai_ticket');
		$data['staff'] = $this->db->table('staff')->where('is_admin', 0)->get()->getResultArray();
		$data['rasi'] = $this->db->table('rasi')->get()->getResultArray();
		$data['nat'] = $this->db->table('natchathram')->get()->getResultArray();
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->where('paid_through', 'DIRECT')->get()->getResultArray();
		$group = $this->db->query("SELECT * FROM archanai_group order by name asc")->getResultArray();
		
		$data['archanai'] = $this->db->table('kattalai_archanai')->select('*')->get()->getResultArray();
		$data['archanai_diety'] = $this->db->table('archanai_diety')->get()->getResultArray();
		//print_r($data['rasi']); exit();
		//$data['data'] = $this->db->table('archanai')->get()->getResultArray();
		$yr = date('Y');
		$mon = date('m');
		$query = $this->db->query("SELECT ref_no FROM archanai_booking where id=(select max(id) from archanai_booking where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();

		$data['bill_no'] = 'AR' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->where('paid_through', 'DIRECT')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('kattalai_archanai/booking', $data);
		echo view('template/footer');
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
		/*if(!empty($id)) {
			$res =  $this->db->table('natchathram')->where('id',$id)->get()->getRowArray();
		} else {
			$res =  $this->db->table('natchathram')->get()->getResultArray();
		}*/
		$res = $this->db->table('natchathram')->where('id', $id)->get()->getRowArray();
		$data = array("id" => $res['id'], "name_eng" => $res['name_eng']);
		echo json_encode($data);
		exit;
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

		if (!empty($_POST['name']) && !empty($_POST['rasi'])) {
			$msg_data['err'] = 'Please enter devotees details';
		} elseif (!empty($_POST['payment_mode']) && !empty($_POST['total_amount'])){
			$msg_data['err'] = 'Please enter payment details';
		} else {
			$yr = date('Y');
			$mon = date('m');
			$query = $this->db->query("SELECT ref_no FROM kattalai_archanai_booking where id=(select max(id) from kattalai_archanai_booking where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
			$data['ref_no'] = 'KAR' . date('y', strtotime(date('Y-m-d'))) . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
			$data['date'] = date('Y-m-d');
			$data['deity_id'] = $_POST['tot_amt'];
			$data['mobie_code'] = $_POST['tot_amt'];
			$data['mobile_no'] = $_POST['tot_amt'];
			$data['email'] = $_POST['tot_amt'];
			$data['description'] = $_POST['tot_amt'];
			
			$data['added_by'] = $this->session->get('log_id_frend');
			$data['amount'] = $_POST['tot_amt'];
			$data['paid_amount'] = $_POST['paid_amount'];
			$pay_method = (!empty($_POST['pay_method']) ? $_POST['pay_method'] : 'cash');
			$data['sep_print'] = (!empty($_REQUEST['sep_print']) ? $_REQUEST['sep_print'] : 0);
			$data['created'] = date('Y-m-d H:i:s');
			$data['rasi_id'] = $rasi_id;
			$data['natchathra_id'] = $natchathra_id;
			$data['paid_through'] = "COUNTER";
			$data['payment_status'] = (($pay_method == 'cash' || $pay_method == 'online' || $pay_method == 'qr' || $pay_method == 'nets_pay' || $pay_method == 'pay_now' || $pay_method == 'cheque') ? 2 : 1);

		}
	}

	public function arch_book_rep_view()
	{

		if (!$this->model->list_validate('archanai_report')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data['grp'] = $this->db->table('archanai_group')->get()->getResultArray();
		$data['arch'] = $this->db->table('archanai') ->orderby('name_eng', 'ASC')->get()->getResultArray();
	
		echo view('template/header');
		echo view('template/sidebar');
		echo view('kattalai_archanai/kattalai_report', $data);
		echo view('template/footer');
	}
	
	public function kattalai_archanai_report()
	{
		/*if (!empty($_POST['fdt'])) $from_date = $_POST['fdt'];
		else $from_date = date('Y-m-01');

		if (!empty($_POST['tdt'])) $to_date = $_POST['tdt'];
		else $to_date = date('Y-m-d');
		$group_filter = $_POST['group_filter'];

		$builder = $this->db->table('kattalai_archanai_booking as kab')
            ->select('kab.id, kab.name, kab.date, kab.daytype, kab.amount, kab.paid_amount, kab.payment_type')
            ->where('kab.date >=', $from_date)
            ->where('kab.date <=', $to_date);

		if (!empty($group_filter) && $group_filter != '0') {
			$builder->where('kab.daytype', $group_filter);
		}
		
		$builder->orderBy('kab.date', 'DESC');

		$data['list'] = $builder->get()->getResultArray();

		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->where('paid_through', 'COUNTER')->get()->getResultArray();
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['group_filter'] = $group_filter;*/

		echo view('template/header');
		echo view('template/sidebar');
		echo view('kattalai_archanai/kattalai_report');
		echo view('template/footer');
		
	}
	
	public function kattalai_archanai_report_ref()
	{
		if (!empty($_POST['fdt'])) $from_date = $_POST['fdt'];
		else $from_date = date('Y-m-01');

		if (!empty($_POST['tdt'])) $to_date = $_POST['tdt'];
		else $to_date = date('Y-m-d');
		$group_filter = $_POST['group_filter'];
		//echo $group_filter; die();

		$builder = $this->db->table('kattalai_archanai_booking as kab')
            ->select('kab.id, kab.name, kab.date, kab.daytype, kab.amount, kab.paid_amount, kab.payment_type')
            ->where('kab.date >=', $from_date)
            ->where('kab.date <=', $to_date);

		if (!empty($group_filter) && $group_filter != '0') {
			$builder->where('kab.daytype', $group_filter);
		}
		// echo $group_filter; die();
		$builder->orderBy('kab.date', 'DESC');

		$res = $builder->get()->getResultArray();

		$data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->where('paid_through', 'COUNTER')->get()->getResultArray();
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['group_filter'] = $group_filter;
		
		$i = 0;
		//print_r($res);die;
		$i = 1;
		$data = array();
		if (!empty($res)) {
			foreach ($res as $aname) {
				$data[] = array(
					$i++,
					date("d-m-Y", strtotime($aname['date'])),
					$aname['name'],
					$aname['daytype'],
					$aname['payment_type'],
					$aname['amount'],
					$aname['paid_amount']
				);
			}
		}
		//die;
		$result = array(
			"draw" => 0,
			"recordsTotal" => $i - 1,
			"recordsFiltered" => $i - 1,
			"data" => $data,
		);
		echo json_encode($result);
		exit();
	}







}    
    
    
    
    