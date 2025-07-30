<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Archanai extends BaseController
{
    function __construct(){
        parent:: __construct();
        helper('url');
		helper("common_helper");
		$this->model = new PermissionModel();
        if( ($this->session->get('login') ) == false && $this->session->get('role') != 1){
            $data['dn_msg'] = 'Please Login';
            return redirect()->to('/login');
		}
    }
    
    public function index(){
        if(!$this->model->list_validate('archanai_setting')){
			return redirect()->to('/dashboard');
		}
		$data['permission'] = $this->model->get_permission('archanai_setting');
		$data['staff'] = $this->db->table('staff')->get()->getResultArray();
		$data['list'] = $this->db->table('archanai')
								->select('archanai.*,archanai_category.name as category_name')
								->join('archanai_category', 'archanai.archanai_category = archanai_category.id')
								->get()->getResultArray();

		echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/index', $data);
		echo view('template/footer');
    }
	public function add()
	{
		
		if(!$this->model->permission_validate('archanai_setting', 'create_p')){
			return redirect()->to('/dashboard');
		}
		$data['limit_type']=get_enum_value('archanai','limit_type');
		$data['staff'] = $this->db->table('staff')->get()->getResultArray();
		$data['archanai_categories'] = $this->db->table('archanai_category')->where('status',1)->get()->getResultArray();
		$data['archanai_group'] = $this->db->table('archanai_group')->get()->getResultArray();
		$data['archanai_diety'] = $this->db->table('archanai_diety')->get()->getResultArray();
		//$three_level_group = get_three_level_in_group($code = array("4000"));
		$data['ledgers'] = $this->db->query("SELECT * FROM ledgers where group_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000) or parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000)) or parent_id IN (select id from `groups` where parent_id IN (SELECT id FROM `groups` WHERE code in (4000, 8000))))")->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/add', $data);
		echo view('template/footer');
	}
	
	public function edit(){
	    if(!$this->model->permission_validate('archanai_setting', 'edit')){
			return redirect()->to('/dashboard');
		}
		$data['limit_type']=get_enum_value('archanai','limit_type');
		$id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('archanai')->where('id', $id)->get()->getRowArray();
		$data['staff'] = $this->db->table('staff')->get()->getResultArray();
	    $data['archanai_group'] = $this->db->table('archanai_group')->get()->getResultArray();
		$data['archanai_categories'] = $this->db->table('archanai_category')->where('status',1)->get()->getResultArray();
		$data['archanai_diety'] = $this->db->table('archanai_diety')->get()->getResultArray();
		//$three_level_group = get_three_level_in_group($code = array("4000"));
		$data['ledgers'] = $this->db->query("SELECT * FROM ledgers where group_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000) or parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000)) or parent_id IN (select id from `groups` where parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000))))")->getResultArray();
	    
		//echo '<pre>';
		//print_r($data['data']);
		echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/add', $data);
		echo view('template/footer');
	}
	

	public function view(){
	    if(!$this->model->permission_validate('archanai_setting', 'view')){
			return redirect()->to('/dashboard');
		}
		$data['limit_type']=get_enum_value('archanai','limit_type');
	    $id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('archanai')->where('id', $id)->get()->getRowArray();
	    $data['archanai_group'] = $this->db->table('archanai_group')->get()->getResultArray();
		$data['archanai_categories'] = $this->db->table('archanai_category')->where('status',1)->get()->getResultArray();
		$data['archanai_diety'] = $this->db->table('archanai_diety')->get()->getResultArray();
		
		//$three_level_group = get_three_level_in_group($code = array("4000"));
		$data['ledgers'] = $this->db->query("SELECT * FROM ledgers where group_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000) or parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000)) or parent_id IN (select id from `groups` where parent_id IN (SELECT id FROM `groups` WHERE code IN (4000, 8000))))")->getResultArray();
	    $data['view'] = true;
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/add', $data);
		echo view('template/footer');
	}
	
	public function category_store() {
	    $data['name']		 =	trim($_POST['groupname']);
	    $data['created']  =	date('Y-m-d H:i:s');
		$data['modified'] = date('Y-m-d H:i:s');
		$builder = $this->db->table('archanai_group')->insert($data);
		if($builder){
    	    return redirect()->to(base_url()."/archanai/add");
    	}
	}
	
	public function save(){
        $id = $_POST['id'];
		// Handle image removal after form submit
		if (!empty($_POST['remove_image'])) {
			$record = $this->db->table('archanai')->select('image')->where('id', $id)->get()->getRowArray();
			if (!empty($record['image'])) {
				$oldImagePath = FCPATH . 'uploads/archanai/' . $record['image'];
				if (file_exists($oldImagePath)) {
					unlink($oldImagePath);
				}
				$this->db->table('archanai')->where('id', $id)->update(['image' => null]);
			}
		}

		if (!empty($_POST['remove_watermark'])) {
			$record = $this->db->table('archanai')->select('watermark_image')->where('id', $id)->get()->getRowArray();
			if (!empty($record['watermark_image'])) {
				$oldWatermarkPath = FCPATH . 'uploads/archanai/watermark/' . $record['watermark_image'];
				if (file_exists($oldWatermarkPath)) {
					unlink($oldWatermarkPath);
				}
				$this->db->table('archanai')->where('id', $id)->update(['watermark_image' => null]);
			}
		}

		$comission_to = $_POST['comission_to'];
		if(isset($_POST['view_archanai'])) $data['view_archanai'] = 0;
		else $data['view_archanai'] = 1;
		if(isset($_POST['show_deity'])) $data['show_deity'] = 1;
		else $data['show_deity'] = 0;
		if(!empty($_POST['sep_grouping'])) $data['is_sep_group'] = 1;
		else $data['is_sep_group'] = 0;
		if(!empty($_POST['stock_archanai'])) $data['dedection_from_stock'] = 1;
		else $data['dedection_from_stock'] = 0;
		$kazhanji_option = $_POST['settings'][1]['kazhanji_option'];
		$kazhanji_option_text = isset($_POST['settings'][1]['kazhanji_option_text']) ? trim($_POST['settings'][1]['kazhanji_option_text']) : '';
		$kazhanji_option_image = '';
		if (!empty($_FILES['archanai_kazhanji_upload']['name'])) {
			$name = time() . '_' . $_FILES['archanai_kazhanji_upload']['name'];
			$target_dir = "uploads/kazhanji/";
			move_uploaded_file($_FILES['archanai_kazhanji_upload']['tmp_name'], $target_dir . $name);
			$kazhanji_option_image = $name;
		}
		$data['name_eng']	 =	trim($_POST['name_eng']);
		$data['comission_to'] =   $comission_to;
		$data['name_tamil']		 =	trim($_POST['name_tamil']);
		$data['amount']	 =	trim($_POST['amount']);
		$data['commission']	 =	trim($_POST['commission']);
		$data['commission_percentage']	 =	trim($_POST['com_per']);
		$data['special_date'] = (isset($_POST['special_date'])?trim($_POST['special_date']):"");
		$data['groupname']		 =	trim($_POST['groupname']);
		if(!empty($_POST['diety_id'])) $data['deity_id']   = implode(",",$_POST['diety_id']);
		$data['order_no'] =	trim($_POST['order_no']);
		$data['archanai_category'] =	$_POST['archanai_category'];
		if(!empty($_POST['ledger_id'])) $data['ledger_id'] = $_POST['ledger_id'];
		$data['added_by']	 =	$this->session->get('log_id');
		$data['kazhanji_option'] = $kazhanji_option;
		$data['kazhanji_option_text'] = $kazhanji_option_text;
		$data['kazhanji_option_image'] = $kazhanji_option_image;
		$data['is_limit'] = trim($_POST['is_limit']);
		$data['limit_count'] = trim($_POST['limit_count']);
		$data['limit_type'] = trim($_POST['limit_type']);
	
		
		if(!empty($_FILES['archanai_image']['name']) > 0){
			if(empty($id)){
				echo $_FILES['archanai_image']['name'];
				$name = time() . '_' .$_FILES['archanai_image']['name'];
				$target_dir = "uploads/archanai/";
				move_uploaded_file($_FILES['archanai_image']['tmp_name'],$target_dir.$name);
				$data['image'] = $name;
			} else{
				$existingImage = $this->db->table('archanai')->select('image')->where('id', $id)->get()->getRow();
                if ($existingImage && !empty($existingImage->image)) {
					$oldImagePath = "uploads/archanai/" . $existingImage->image;
					if (file_exists($oldImagePath)) {
						unlink($oldImagePath);
					}
				}   
				$name = time() . '_' . $_FILES['archanai_image']['name'];
				$target_dir = "uploads/archanai/";
				move_uploaded_file($_FILES['archanai_image']['tmp_name'], $target_dir . $name);
				$data['image'] = $name;                      
			}
			
		}
		if(!empty($_FILES['watermark_image']['name']) > 0){
			if(empty($id)) {
				echo $_FILES['watermark_image']['name'];
				$name = time() . '_' .$_FILES['watermark_image']['name'];
				$target_dir = "uploads/archanai/watermark/";
				move_uploaded_file($_FILES['watermark_image']['tmp_name'],$target_dir.$name);
				$data['watermark_image'] = $name;
			} else {
				$existingImage = $this->db->table('archanai')->select('watermark_image')->where('id', $id)->get()->getRow();
                if ($existingImage && !empty($existingImage->image)) {
					$oldImagePath = "uploads/archanai/watermark/" . $existingImage->image;
					if (file_exists($oldImagePath)) {
						unlink($oldImagePath);
					}
				}   
				$name = time() . '_' . $_FILES['watermark_image']['name'];
				$target_dir = "uploads/archanai/watermark/";
				move_uploaded_file($_FILES['watermark_image']['tmp_name'], $target_dir . $name);
				$data['watermark_image'] = $name;
			}
		}
		$hallEditorArray = $_POST['desc_editor'];
		//$hallEditorJson = $_POST['desc_editor'];
		//$hallEditorArray = json_decode($hallEditorJson, true);
		$data['description'] = json_encode($hallEditorArray);
		if(empty($id)){
		    $data['created']  =	date('Y-m-d H:i:s');
			$data['modified'] = date('Y-m-d H:i:s');
		    $builder = $this->db->table('archanai')->insert($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Archanai Added Successfully');
    		    return redirect()->to(base_url()."/archanai");
    		}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to(base_url()."/archanai");}
		}else{
            $data['modified' ] = date('Y-m-d H:i:s');
            $builder = $this->db->table('archanai')->where('id', $id)->update($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Archanai Update Successfully');
    		    return redirect()->to(base_url()."/archanai");
    		}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to(base_url()."/archanai");}
		}
		exit;
	}
	
	public function delete(){
	    if(!$this->model->permission_validate('archanai_setting', 'delete_p')){
			return redirect()->to('/dashboard');
		}
	    $id=  $this->request->uri->getSegment(3);
		$res = $this->db->table('archanai')->delete(['id' => $id]);
		if($res){
		    $this->session->setFlashdata('succ', 'Archanai Delete Successfully');
		    return redirect()->to(base_url()."/archanai");}else{
		    $this->session->setFlashdata('fail', 'Please Try Again');
		    return redirect()->to(base_url()."/archanai");}
		exit;
	}
	public function del_check(){
			$id = $_POST['id'];
			$res = $this->db->table("archanai_booking_details")->where("archanai_id", $id)->get()->getResultArray();
			echo count($res);
		}
	public function get_group() {
		$name = $_POST['search'];
		$data = array();
		$res = $this->db->query("select name from archanai_group where name like '%".$name."%' ")->getResultArray();
		foreach($res as $row){
			$data[] = $row['name'];
		}
		$datas = implode(',', $data);
		echo json_encode($data);
	}
	
	public function rasi(){
        if(!$this->model->list_validate('archanai_setting')){
			return redirect()->to('/dashboard');
		}
		$data['permission'] = $this->model->get_permission('archanai_setting');
		$data['list'] = $this->db->table('rasi')->get()->getResultArray();

		echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/rasi', $data);
		echo view('template/footer');
    }
	
	public function rasi_add()
	{
		if(!$this->model->permission_validate('archanai_setting', 'create_p')){
			return redirect()->to('/dashboard');
		}
		$data['nat'] = $this->db->table('natchathram')->get()->getResultArray();
		//echo "<pre>"; print_r($data); die();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/rasi_add', $data);
		echo view('template/footer');
	}
	
	public function rasi_validation(){
		$name_eng = trim($_POST['name_eng']);
		$name_tamil = trim($_POST['name_tamil']);
		
		$data = array();
		if (empty($name_eng) || empty($name_tamil)) {
		  $data['err'] = "Please Fill Required Fields";
		  $data['succ']= '';
		}else{
		  $data['succ'] = "Form validate";
		  $data['err'] ='';
		}
		echo json_encode($data);
	  }
	
	public function rasi_save(){
        $id = $_POST['id'];	
		$data['name_eng']	 =	trim($_POST['name_eng']);
		$data['name_tamil']		 =	trim($_POST['name_tamil']);
		$nat = $_POST['natchathra_id'];
		if(!empty($nat)) {
		    $natchathra = implode(',',$nat);
		    $data['natchathra_id'] = $natchathra;
		} else { 
		    $data['natchathra_id'] = ""; 
		}
		//print_r($nat); die();
		if(empty($id)){
		    $data['created']  =	date('Y-m-d H:i:s');
			$data['modified'] = date('Y-m-d H:i:s');
		    $builder = $this->db->table('rasi')->insert($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Rasi Added Successfully');
    		    return redirect()->to(base_url()."/archanai/rasi");}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to(base_url()."/archanai/rasi");}
		}else{
            $data['modified' ] = date('Y-m-d H:i:s');
            $builder = $this->db->table('rasi')->where('id', $id)->update($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Rasi Update Successfully');
    		    return redirect()->to(base_url()."/archanai/rasi");}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to(base_url()."/archanai/rasi");}
		}
		exit;
	}
	
	public function rasi_edit(){
	    if(!$this->model->permission_validate('archanai_setting', 'edit')){
			return redirect()->to('/dashboard');
		}
	    $id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('rasi')->where('id', $id)->get()->getRowArray();
		$data['nat'] = $this->db->table('natchathram')->get()->getResultArray();
		//echo"<pre>"; print_r($data); die();
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/rasi_add', $data);
		echo view('template/footer');
	}
	
	public function rasi_view(){
	    if(!$this->model->permission_validate('archanai_setting', 'view')){
			return redirect()->to('/dashboard');
		}
	    $id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('rasi')->where('id', $id)->get()->getRowArray();
		$data['nat'] = $this->db->table('natchathram')->get()->getResultArray();
	    $data['view'] = true;
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/rasi_add', $data);
		echo view('template/footer');
	}
	public function rasi_delete(){
	    if(!$this->model->permission_validate('archanai_setting', 'delete_p')){
			return redirect()->to('/dashboard');
		}
	    $id=  $this->request->uri->getSegment(3);
		$res = $this->db->table('rasi')->delete(['id' => $id]);
		if($res){
		    $this->session->setFlashdata('succ', 'Rasi Delete Successfully');
		    return redirect()->to(base_url()."/archanai/rasi");}else{
		    $this->session->setFlashdata('fail', 'Please Try Again');
		    return redirect()->to(base_url()."/archanai/rasi");}
		exit;
	}
	
	public function natchathiram(){
        if(!$this->model->list_validate('archanai_setting')){
			return redirect()->to('/dashboard');
		}
		$data['permission'] = $this->model->get_permission('archanai_setting');
		$data['list'] = $this->db->table('natchathram')->get()->getResultArray();

		echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/natchathiram', $data);
		echo view('template/footer');
    }
	
	public function natchathiram_add()
	{
		if(!$this->model->permission_validate('archanai_setting', 'create_p')){
			return redirect()->to('/dashboard');
		}
		echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/natchathiram_add');
		echo view('template/footer');
	}
	
	public function nat_validation(){
		$name_eng = trim($_POST['name_eng']);
		$name_tamil = trim($_POST['name_tamil']);
		
		$data = array();
		if (empty($name_eng) || empty($name_tamil)) {
		  $data['err'] = "Please Fill Required Fields";
		  $data['succ']= '';
		}else{
		  $data['succ'] = "Form validate";
		  $data['err'] ='';
		}
		echo json_encode($data);
	  }
	
	public function natchathiram_save(){
        $id = $_POST['id'];	
		
		$data['name_eng']	 =	trim($_POST['name_eng']);
		$data['name_tamil']		 =	trim($_POST['name_tamil']);
		if(empty($id)){
		    $data['created']  =	date('Y-m-d H:i:s');
			$data['modified'] = date('Y-m-d H:i:s');
		    $builder = $this->db->table('natchathram')->insert($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Natchathiram Added Successfully');
    		    return redirect()->to(base_url()."/archanai/natchathiram");}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to(base_url()."/archanai/natchathiram");}
		}else{
            $data['modified' ] = date('Y-m-d H:i:s');
            $builder = $this->db->table('natchathram')->where('id', $id)->update($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Natchathiram Update Successfully');
    		    return redirect()->to(base_url()."/archanai/natchathiram");}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to(base_url()."/archanai/natchathiram");}
		}
		exit;
	}
	
	public function natchathiram_edit(){
	    if(!$this->model->permission_validate('archanai_setting', 'edit')){
			return redirect()->to('/dashboard');
		}
	    $id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('natchathram')->where('id', $id)->get()->getRowArray();
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/natchathiram_add', $data);
		echo view('template/footer');
	}
	
	public function natchathiram_view(){
	    if(!$this->model->permission_validate('archanai_setting', 'view')){
			return redirect()->to('/dashboard');
		}
	    $id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('natchathram')->where('id', $id)->get()->getRowArray();
	    $data['view'] = true;
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/natchathiram_add', $data);
		echo view('template/footer');
	}
	public function natchathiram_delete(){
	    if(!$this->model->permission_validate('archanai_setting', 'delete_p')){
			return redirect()->to('/dashboard');
		}
	    $id=  $this->request->uri->getSegment(3);
		$res = $this->db->table('natchathram')->delete(['id' => $id]);
		if($res){
		    $this->session->setFlashdata('succ', 'Rasi Delete Successfully');
		    return redirect()->to(base_url()."/archanai/natchathiram");}else{
		    $this->session->setFlashdata('fail', 'Please Try Again');
		    return redirect()->to(base_url()."/archanai/natchathiram");}
		exit;
	}

	public function group_list() {
		$data['list'] = $this->db->table('archanai_group')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/group', $data);
		echo view('template/footer');
    }
    
    public function save_group(){
        $id = $_POST['id'];
		$data['name']	 =	trim($_POST['name']);
		$data['order_no']	 =	trim($_POST['order_no']);
		if(isset($_POST['rasi'])) 
		$data['rasi'] = 1;
		else $data['rasi'] = 0;
		
		if($data['name']){
			if(empty($id)){
				$data['created']  =	date('Y-m-d H:i:s');
				$builder = $this->db->table('archanai_group')->insert($data);
				if($builder){ $this->session->setFlashdata('succ', 'Group Added Successfully');}
				else{ $this->session->setFlashdata('fail', 'Please Try Again'); }
			}else{
				$data['modified']  =	date('Y-m-d H:i:s');
				$builder = $this->db->table('archanai_group')->where('id', $id)->update($data);
				if($builder){ $this->session->setFlashdata('succ', 'Group Update Successfully');}
				else{ $this->session->setFlashdata('fail', 'Please Try Again'); }
			}
		}else{
			$this->session->setFlashdata('fail', 'Please Fill Category'); 
		}
		return redirect()->to(base_url()."/archanai/group_list");
	}
	
	public function edit_group(){
	    $id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('archanai_group')->where('id', $id)->get()->getRowArray();
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/add_group', $data);
		echo view('template/footer');
	}
	
	public function view_group(){
		$id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('archanai_group')->where('id', $id)->get()->getRowArray();
	    $data['view'] = true;
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/add_group', $data);
		echo view('template/footer');
	}
	public function delete_group(){
		$id=  $this->request->uri->getSegment(3);
		$archanai_group = $this->db->table('archanai_group')->where('id', $id)->get()->getRowArray();
		$existing_check = $this->db->table('archanai')->where('groupname', $archanai_group['name'])->get()->getResultArray();
		if(count($existing_check) > 0)
		{
			$this->session->setFlashdata('fail', 'This group already chooosed in archanai.');
		}
		else
		{
			$res = $this->db->table('archanai_group')->delete(['id' => $id]);
			if($res){$this->session->setFlashdata('succ', 'Group Delete Successfully');}
			else{ $this->session->setFlashdata('fail', 'Please Try Again');}
		}
		return redirect()->to(base_url()."/archanai/group_list");
	}
	
	public function diety_list() {
		$data['list'] = $this->db->table('archanai_diety')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/diety', $data);
		echo view('template/footer');
    }
	public function save_diety(){
        $id = !empty($_POST['id']) ? $_POST['id'] : 0;
		$data = array();
		$data['name']	 =	trim($_POST['name']);
		$data['name_tamil']	 =	trim($_POST['name_tamil']);
		$data['code']	 =	trim($_POST['code']);
		$data['abishegam_status'] = !empty($_POST['abi_status']) ? $_POST['abi_status'] : 0;
		$data['abishegam_amount'] = $_POST['abi_amount'];
		$data['homam_status'] = !empty($_POST['hom_status']) ? $_POST['hom_status'] : 0;
		$data['homam_amount'] = $_POST['hom_amount'];
		if(!empty($_POST['ledger_id'])) $data['ledger_id'] = $_POST['ledger_id'];

		if(!empty($_FILES['image']['name']) > 0){
			$logoimg = time() . '_' .$_FILES['image']['name'];
			$target_dir = "uploads/diety/";
			move_uploaded_file($_FILES['image']['tmp_name'],$target_dir.$logoimg);
			$data['image'] = $logoimg;
		}
		
		if($data['name']){
			if(empty($id)){
				$data['created']  =	date('Y-m-d H:i:s');
				$builder = $this->db->table('archanai_diety')->insert($data);
				if($builder){ $this->session->setFlashdata('succ', 'Diety Added Successfully');}
				else{ $this->session->setFlashdata('fail', 'Please Try Again'); }
			}else{
				$data['modified']  =	date('Y-m-d H:i:s');
				$builder = $this->db->table('archanai_diety')->where('id', $id)->update($data);
				if($builder){ $this->session->setFlashdata('succ', 'Diety Update Successfully');}
				else{ $this->session->setFlashdata('fail', 'Please Try Again'); }
			}
		}else{
			$this->session->setFlashdata('fail', 'Please Fill Category'); 
		}
		return redirect()->to(base_url()."/archanai/diety_list");
	}
	public function edit_diety(){
	    $id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('archanai_diety')->where('id', $id)->get()->getRowArray();
		$data['ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->whereIn('group_id', $three_level_group)->orderBy('right_code','asc')->get()->getResultArray();
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/add_diety', $data);
		echo view('template/footer');
	}
	
	public function view_diety(){
		$id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('archanai_diety')->where('id', $id)->get()->getRowArray();
		$data['ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->whereIn('group_id', $three_level_group)->orderBy('right_code','asc')->get()->getResultArray();
	    $data['view'] = true;
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('archanai/add_diety', $data);
		echo view('template/footer');
	}
	public function delete_diety(){
		$id=  $this->request->uri->getSegment(3);
		$archanai_group = $this->db->table('archanai_diety')->where('id', $id)->get()->getRowArray();
		$existing_check = $this->db->table('archanai')->where('diety_id', $archanai_group['id'])->get()->getResultArray();
		if(count($existing_check) > 0)
		{
			$this->session->setFlashdata('fail', 'This Diety already chooosed in archanai.');
		}
		else
		{
			$res = $this->db->table('archanai_diety')->delete(['id' => $id]);
			if($res){$this->session->setFlashdata('succ', 'Diety Delete Successfully');}
			else{ $this->session->setFlashdata('fail', 'Please Try Again');}
		}
		return redirect()->to(base_url()."/archanai/diety_list");
	}
	
	public function deity_validation()
	{
		$data = [
			'err' => '',
			'succ' => ''
		];
		if (empty($_POST['name'])) {
			$data['err'] = "Please Fill English Name Fields";
		} 
		elseif (empty($_POST['name_tamil'])) {
			$data['err'] = "Please Fill Tamil Name Fields";
		} 
		else if (isset($_POST['abi_status']) && $_POST['abi_status'] == '1' && (!isset($_POST['abi_amount']) || !is_numeric($_POST['abi_amount']) || $_POST['abi_amount'] <= 0)) {
			$data['err'] = "Please Fill Amount for Abishegam";
		} 
		else if (isset($_POST['hom_status']) && $_POST['hom_status'] == '1' && (!isset($_POST['hom_amount']) || !is_numeric($_POST['hom_amount']) || $_POST['hom_amount'] <= 0)) {
			$data['err'] = "Please Fill Amount for Homam";
		} 
		else {
			$data['succ'] = "Form validate";
		}
		echo json_encode($data);
		exit;
	}
	
	
	public function deity_rep_ref()
	{
		//$fdata = $_REQUEST['fdt'];
		//$tdata = $_REQUEST['tdt'];
		$fltername = $_POST['fltername'];
        $data = [];
		$query1 = $this->db->table('archanai')
		          ->join('archanai_diety', 'archanai_diety.id = archanai.diety_id');
		if($fltername) {
			$query1 = $query1->where('archanai.diety_id', $fltername);
		}
		$res = $query1->get()->getResultArray();
		$i = 1;
		foreach ($res as $row) {
				$data[] = array(
					$i++,
					$row['name_eng'],
					$row['name_tamil'],
					$row['name'],
			);
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
