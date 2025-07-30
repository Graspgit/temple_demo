<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Offering extends BaseController
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
    
    public function offering_category(){
        $data['list'] = $this->db->table('offering_category')->get()->getResultArray();
        echo view('template/header');
		echo view('template/sidebar');
		echo view('offering/offering_category', $data);
		echo view('template/footer');
    }
    public function category_validation()
	{
		$name = trim($_POST['name']);

		$data = array();
		if (empty($name)) {
			$data['err'] = "Please Fill Required Fields";
			$data['succ'] = '';
		} else {
			$data['succ'] = "Form validate";
			$data['err'] = '';
		}
		echo json_encode($data);
	}
    public function save_category()
	{
    	$id = $_POST['id'];
		$data['name'] = trim($_POST['name']);
		$data['status'] = !empty($_POST['status']) ? $_POST['status'] : 0;

		if (empty($id)) {
			$builder = $this->db->table('offering_category')->insert($data);
			if ($builder) {
				$this->session->setFlashdata('succ', 'Offering Category Added Successfully');
				return redirect()->to(base_url() . "/offering/offering_category");} else {
				$this->session->setFlashdata('fail', 'Please Try Again');
				return redirect()->to(base_url() . "/offering/offering_category");}
		} else {

			$builder = $this->db->table('offering_category')->where('id', $id)->update($data);
			if ($builder) {
				$this->session->setFlashdata('succ', 'Offering Category Update Successfully');
				return redirect()->to(base_url() . "/offering/offering_category");} else {
				$this->session->setFlashdata('fail', 'Please Try Again');
				return redirect()->to(base_url() . "/offering/offering_category");}
		}

	}
	public function del_cat_check()
	{
		$id = $_POST['id'];
		$res = $this->db->table("product_category")->where("off_cat_id", $id)->get()->getResultArray();
		echo count($res);
	}
	public function delete_category()
	{
		$id = $this->request->uri->getSegment(3);
		$res = $this->db->table('offering_category')->delete(['id' => $id]);
		if ($res) {
			$this->session->setFlashdata('succ', 'Donation Category Delete Successfully');
			return redirect()->to(base_url() . "/master/offering_category");} else {
			$this->session->setFlashdata('fail', 'Please Try Again');
			return redirect()->to(base_url() . "/master/offering_category");}
	}
	public function edit_category()
	{
		$id = $this->request->uri->getSegment(3);
		$data['data'] = $this->db->table('offering_category')->where('id', $id)->get()->getRowArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('offering/add_category', $data);
		echo view('template/footer');
	}
	public function view_category()
	{
		$id = $this->request->uri->getSegment(3);
		$data['data'] = $this->db->table('offering_category')->where('id', $id)->get()->getRowArray();
		$data['view'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('offering/add_category', $data);
		echo view('template/footer');
	}
	public function product_category(){
        $data['offer'] = $this->db->table('offering_category')->where('status', '1')->get()->getResultArray();
        $data['list'] = $this->db->table('product_category')
						->select('product_category.*, offering_category.name as category')
						->join('offering_category', 'offering_category.id = product_category.off_cat_id')
						->get()->getResultArray();
        echo view('template/header');
		echo view('template/sidebar');
		echo view('offering/product_category', $data);
		echo view('template/footer');
    }
    public function product_category_validation()
	{
		$name = trim($_POST['name']);

		$data = array();
		if (empty($name)) {
			$data['err'] = "Please Fill Required Fields";
			$data['succ'] = '';
		} else {
			$data['succ'] = "Form validate";
			$data['err'] = '';
		}
		echo json_encode($data);
	}
    public function save_product_category()
	{
    	$id = $_POST['id'];
		$data['off_cat_id'] = trim($_POST['off_cat_id']);
		$data['name'] = trim($_POST['name']);
		$data['status'] = !empty($_POST['status']) ? $_POST['status'] : 0;
		if(!empty($_FILES['image']['name']) > 0){
			echo $_FILES['image']['name'];
			$name = time() . '_' .$_FILES['image']['name'];
			$target_dir = "uploads/offering/";
			move_uploaded_file($_FILES['image']['tmp_name'],$target_dir.$name);
			$data['image'] = $name;
		}

		if (empty($id)) {
			$builder = $this->db->table('product_category')->insert($data);
			if ($builder) {
				$this->session->setFlashdata('succ', 'Product Category Added Successfully');
				return redirect()->to(base_url() . "/offering/product_category");} else {
				$this->session->setFlashdata('fail', 'Please Try Again');
				return redirect()->to(base_url() . "/offering/product_category");}
		} else {

			$builder = $this->db->table('product_category')->where('id', $id)->update($data);
			if ($builder) {
				$this->session->setFlashdata('succ', 'Product Category Update Successfully');
				return redirect()->to(base_url() . "/offering/product_category");} else {
				$this->session->setFlashdata('fail', 'Please Try Again');
				return redirect()->to(base_url() . "/offering/product_category");}
		}

	}
	
	public function delete_pro_category()
	{

		$id = $this->request->uri->getSegment(3);
		$res = $this->db->table('product_category')->delete(['id' => $id]);
		if ($res) {
			$this->session->setFlashdata('succ', 'Donation Category Delete Successfully');
			return redirect()->to(base_url() . "/master/product_category");} else {
			$this->session->setFlashdata('fail', 'Please Try Again');
			return redirect()->to(base_url() . "/master/product_category");}
	}

	public function edit_pro_category()
	{

		$id = $this->request->uri->getSegment(3);
		$data['offer'] = $this->db->table('offering_category')->get()->getResultArray();
		$data['data'] = $this->db->table('product_category')->where('id', $id)->get()->getRowArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('offering/add_pro_category', $data);
		echo view('template/footer');
	}
	public function view_pro_category()
	{

		$id = $this->request->uri->getSegment(3);
		$data['offer'] = $this->db->table('offering_category')->get()->getResultArray();
		$data['data'] = $this->db->table('product_category')->where('id', $id)->get()->getRowArray();
		$data['view'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('offering/add_pro_category', $data);
		echo view('template/footer');
	}
	
	public function product_offering(){
        $data['offer'] = $this->db->table('offering_category')->where('status', '1')->get()->getResultArray();
        $data['list'] = $this->db->table('product_offering')->get()->getResultArray();
        echo view('template/header');
		echo view('template/sidebar');
		echo view('offering/product_offering', $data);
		echo view('template/footer');
    }
    public function add_product_offering(){
        $data['offer'] = $this->db->table('offering_category')->where('status', '1')->get()->getResultArray();
        $data['product'] = $this->db->table('product_category')->get()->getResultArray();
        echo view('template/header');
		echo view('template/sidebar');
		echo view('offering/add_product_offering', $data);
		echo view('template/footer');
    }
    
    public function get_category()
	{
		$json_resp = array();
		if (!empty ($_REQUEST['id'])) {
		    $id = $_REQUEST['id'];
    		$res = $this->db->table('product_category')->where('off_cat_id', $id)->where('status', '1')->get()->getResultArray();
    		if ($res)
				$json_resp['data'] = $res;
			else
				$json_resp['data'] = array();
		}
		echo json_encode($json_resp);
		exit;
	}
	public function pro_offering_save()
	{
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// exit;
	    $id = $_POST['id'];
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		
		$data['date'] = date('Y-m-d', strtotime($_POST['date']));
		$data['name'] = trim($_POST['name']);
	    $data['phone'] = trim($_POST['phone']);
	    $data['address'] = trim($_POST['address']);
		$yr= date('Y', strtotime($_POST['date']));
		$mon= date('m', strtotime($_POST['date']));
		$query = $this->db->query("SELECT ref_no FROM product_offering WHERE id=(SELECT max(id) FROM product_offering WHERE year(date)='". $yr ."' AND month(date)='". $mon ."')")->getRowArray();
		$data['ref_no'] = 'PO' .date('y', strtotime($_POST['date'])) . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
		$data['paid_through'] = 'ADMIN';
	    $data['created'] = date("Y-m-d H:i:s");
	    $res = $this->db->table('product_offering')->insert($data);
		$ins_id = $this->db->insertID();
		if (!empty($_POST['sout'])) {
			foreach ($_POST['sout'] as $row) {
				$sdata['pro_off_id'] = $ins_id;
				$sdata['offering_id'] = $row['offering_id'];
				$sdata['product_id'] = $row['product_id'];
				$sdata['grams'] = $row['grams'];
				$sdata['quantity'] = $row['quantity'];
				$sdata['value'] = $row['value'];
				$detail_res = $this->db->table("product_offering_detail")->insert($sdata);
		
				$productCategory = $this->db->table("product_category")
											->select('id, off_cat_id, stock_grams, stock_items')
											->where('off_cat_id', $row['offering_id'])
											->where('id', $row['product_id'])
											->get()
											->getRowArray();
		
				if (!empty($productCategory)) {
					$updatedData = [
						'stock_grams' => $productCategory['stock_grams'] + $row['grams'],
						'stock_items' => $productCategory['stock_items'] + $row['quantity']
					];
					
					$res = $this->db->table("product_category")
							 ->where('id', $row['product_id'])
							 ->update($updatedData);
				}
			}
		}
		
		if($res){
			$msg_data['succ'] = 'Stock In Added Successfully';
			$msg_data['id'] = $ins_id;
		}else{
			$msg_data['err'] = 'Please Try Again';
		}
		
		echo json_encode($msg_data);
		exit();
	}
	public function edit_prod_offering()
	{
		$id = $this->request->uri->getSegment(3);
		$data['offer'] = $this->db->table('offering_category')->get()->getResultArray();
		$data['data'] = $this->db->table('product_offering')->where('id', $id)->get()->getRowArray();
		$data['pro'] = $this->db->table('product_offering_detail')->where('pro_off_id', $id)->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('offering/add_product_offering', $data);
		echo view('template/footer');
	}
	public function view_prod_offering()
	{
		$id = $this->request->uri->getSegment(3);
		$data['offer'] = $this->db->table('offering_category')->get()->getResultArray();
		$data['data'] = $this->db->table('product_offering')->where('id', $id)->get()->getRowArray();
		$data['pro'] = $this->db->table('product_offering_detail')->where('pro_off_id', $id)->get()->getResultArray();
		$data['view'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('offering/add_product_offering', $data);
		echo view('template/footer');
	}
	
	
	
	
	public function report()
	{
	    $data['offer'] = $this->db->table('offering_category')->get()->getResultArray();
	    $data['data'] = $this->db->table('product_offering')->get()->getResultArray();
        echo view('template/header');
		echo view('template/sidebar');
		echo view('offering/report', $data);
		echo view('template/footer');
	}
	
	// public function offering_rep_ref()
	// {
	// 	$fdt = date('Y-m-d', strtotime($_POST['fdt']));
	// 	$tdt = date('Y-m-d', strtotime($_POST['tdt']));
	// 	$type = $_POST['type'];
	// 	$ptype = $_POST['ptype'];
	// 	$data = [];
	// 	$qry = $this->db->query("select * from `product_offering` where created >= '$fdt' and created <= '$tdt'")->getResultArray();
	// 	if(!empty($type)) {
	// 	    $qry = $this->db->query("select * from `product_offering` inner join product_offering_detail on product_offering_detail.pro_off_id = product_offering.id where product_offering_detail.offering_id = $type and product_offering.created >= '$fdt' and product_offering.created <= '$tdt'")->getResultArray();
	// 	}
	// 	if(!empty($type)&&!empty($ptype)) {
	// 	    $qry = $this->db->query("select * from `product_offering` inner join product_offering_detail on product_offering_detail.pro_off_id = product_offering.id where product_offering_detail.offering_id = $type and product_offering_detail.product_id = $ptype and product_offering.created >= '$fdt' and product_offering.created <= '$tdt'")->getResultArray();
	// 	}
		
		
	// 	//$qry = $this->db->table('product_offering')->select('*')->where('created' >= '$fdt')->orderBy('id', 'desc')->get()->getResultArray();
		
	// 	foreach ($qry as $row) {
			
	// 		$data[] = array(
	// 			$row['name'],
	// 			$row['phone'],
	// 			$row['address']
	// 		);
	// 	}
	// 	$result = array(
	// 		"draw" => 0,
	// 		"recordsTotal" => $i - 1,
	// 		"recordsFiltered" => $i - 1,
	// 		"data" => $data,
	// 	);
	// 	echo json_encode($result);
	// 	exit();
	// }

	public function offering_rep_ref()
	{
		$fdt = date('Y-m-d', strtotime($_POST['fdt']));
		$tdt = date('Y-m-d', strtotime($_POST['tdt']));
		$type = $_POST['type'];
		$ptype = $_POST['ptype'];
		$data = [];
		
		$sql = "SELECT 
					product_offering.*,
					product_offering_detail.*,
					product_category.name AS product_name,  
					offering_category.name AS category_name
					FROM 
					product_offering
					INNER JOIN 
					product_offering_detail ON product_offering_detail.pro_off_id = product_offering.id
					INNER JOIN 
					product_category ON product_offering_detail.product_id = product_category.id
					INNER JOIN 
					offering_category ON product_offering_detail.offering_id = offering_category.id";

		$params = [];

		if (!empty($type)) {
		$sql .= " WHERE product_offering_detail.offering_id = ?";
		$params[] = $type;
		}

		if (!empty($ptype)) {
		$sql .= empty($type) ? " WHERE" : " AND";
		$sql .= " product_offering_detail.product_id = ?";
		$params[] = $ptype;
		}

		$qry = $this->db->query($sql, $params)->getResultArray();

		$totalGramsByCategory = [];
		$i = 1;
		foreach ($qry as $row) {
			
			$data[] = array(
				$i++,
				'<p style="text-align:left;">' . $row['name'] . '</p>',
				$row['phone'],
				$row['category_name'],
				$row['product_name'],
				$row['grams']
			);
			if (isset($totalGramsByCategory[$row['category_name']])) {
				$totalGramsByCategory[$row['category_name']] += $row['grams'];
			} else {
				$totalGramsByCategory[$row['category_name']] = $row['grams'];
			}
		}
		$result = array(
			"draw" => 0,
			"recordsTotal" => $i - 1,
			"recordsFiltered" => $i - 1,
			"data" => $data,
			"totals" => $totalGramsByCategory
		);
		echo json_encode($result);
		exit();
	}

	public function print_offering()
	{
		if(!$this->model->permission_validate('annathanam', 'print')){
			return redirect()->to(base_url().'/dashboard');}
		$id=  $this->request->uri->getSegment(3);
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		
	    $data['data'] = $this->db->table('product_offering')->select('product_offering.*')
							->where('id', $id)
							->get()
							->getRowArray();
		
		$data['offering_items'] = $this->db->table('product_offering_detail')
											->select('product_offering_detail.*, product_category.name as product_name, offering_category.name as category_name')
											->join('offering_category', 'product_offering_detail.offering_id = offering_category.id')
											->join('product_category', 'product_offering_detail.product_id = product_category.id')
											->where('product_offering_detail.pro_off_id', $id)->get()->getResultArray();
		
		echo view('offering/print_offering',$data);
	}
	
	
	
}
