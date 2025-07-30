<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Afficial_company extends BaseController
{
	function __construct()
	{
	    
		parent::__construct();
		helper('url');
		$this->model = new PermissionModel();
		if (($this->session->get('login')) == false && $this->session->get('role') != 1) {
			$data['dn_msg'] = 'Please Login';
			header('Location: ' . base_url() . '/login');
			exit;
		}
	}

	public function index()
	{
	    
		$builder = $this->db->table('afficialcompany')->get()->getRowArray();
        $top = json_decode($builder["top"]);
        
        $data1 = $top->images;
 		$data = [];
		foreach($data1 as $iter)
		    $data[] = (array)$iter;
		
		echo view('template/header');
		echo view('template/sidebar');
		echo view('afficial_company/index', ["data"=>$data]); 
		
		echo view('template/footer');
	}

	public function add()
	{
	    $data =[];
		echo view('template/header');
		echo view('template/sidebar');
		echo view('afficial_company/add', $data); 
		echo view('template/footer');
	}
    
	public function edit($id)
	{
	    
		$builder = $this->db->table('afficialcompany')->get()->getRowArray();
        
        $top = json_decode($builder["top"]);
        $data = $top->images;
        $edit_data = [];
        foreach($data as $iter)
        {
            if($iter->name == $id)
                $edit_data = (array)$iter;
        }
        $data["data"] = $edit_data;
		$data['edit'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('afficial_company/add', $data); 
		echo view('template/footer');
	}
    public function save_edit()
    {
       session()->setFlashdata('succ', 'Slider saved successfully.');
        return redirect()->to('/afficial_company'); 
    }
	public function delete($id)
	{
		$builder = $this->db->table('afficialcompany')->get()->getRowArray();
        if(!isset($builder["top"]))
        {
            echo "Record not found";
            return;
        }
        $id1 = $builder["id"];
        $top = json_decode($builder["top"]);
        $data = $top->images;
        
        $del_data = [];
        foreach($data as $iter)
        {
            if($iter->name != $id)
                $del_data[] = $iter;
        }
        $del_data["images"] = $del_data;
		$del_data["title"] = $top->title;
        $del_data["Address"] = $top->Address;
		$top1 = json_encode($del_data);
		$top = str_replace('\\', '', $top1);
		        
        $appeventsData = [
            'updated_at' => Date("Y-m-d H:i:s"), //$this->session->get('log_id'),
            'top'=>$top
        ];
        $this->db->table('afficialcompany')->update($appeventsData, ["id"=>$id1]);

		if (true) {
			session()->setFlashdata('succ', 'Slider deleted successfully.');
		} else {
			session()->setFlashdata('fail', 'Please try again.');
		}
		
		return redirect()->to(base_url('/afficial_company'));
	}

	public function save()
    {
        $builder = $this->db->table('afficialcompany')->get()->getRowArray();
        if(!isset($builder["top"]))
        {
            echo "Record not found";
            return;
        }
        $id1 = $builder["id"];
        $autogen_id = intval($builder["autogen_id"]);
        $top = json_decode($builder["top"]);
        $data = $top->images;
    
        $id = $this->request->getPost('id');
        $upload_type = $this->request->getPost('upload_type'); 
        $uploadDir_banner = 'uploads/afficial_company/';
        $status = $this->request->getPost('active_status');
        
        $bannerTargetPath = '';
        if (true) {
            if (!empty($_FILES['image_upload']['name'])) {
                $bannerImage = time() . '_' . $_FILES['image_upload']['name'];
                $bannerTargetPath = $uploadDir_banner . $bannerImage;
                
                if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $bannerTargetPath)) {
                    if ($id) {
                        $existingEvent = $this->db->table('afficialcompany')->where('id', $id)->get()->getRow();
                        if ($existingEvent->file_name && file_exists($uploadDir_banner . $existingEvent->file_name)) {
                            unlink($uploadDir_banner . $existingEvent->file_name);
                        }
                    }
                }
            } 
        }
        
        $variables = config("variables")->array_var;
        $link = $variables["slider_image_path"];
        if($bannerTargetPath!="")
        {
            
            if ($id!="" &&  (str_contains($id, 'Slide'))) { //update
                $ins_data = [];
                foreach($data as $iter)
                {
                    echo $id;
                    if($iter->name == $id)
                    {
                        $ins_data1 = [];
                        $indata1["url"] = $link.$bannerTargetPath;
                        $indata1["name"] = $iter->name;
                        $ins_data[] = $indata1;
                    }
                    else
                        $ins_data[] = $iter;
                }
                $ins_data["images"] = $ins_data;
                $ins_data["title"] = $top->title;
                $ins_data["Address"] = $top->Address;
		        $top = json_encode($ins_data);
		        $top = str_replace('\\', '', $top);
                $appeventsData = [
                    'updated_at' => Date("Y-m-d H:i:s"), //$this->session->get('log_id'),
                    'top'=>$top
                ];
                
                $this->db->table('afficialcompany')->update($appeventsData, ["id"=>$id1]);
                $appeventsId = $id;
            } else {
                //print_r($data);
                $cnt = $autogen_id+1;
                $ins_data1 = [];
                $indata1["url"] = $link.$bannerTargetPath;
                $indata1["name"] = "Slider ".$cnt;
                $ins_data2 = $data;
                $ins_data2[] = $indata1;
                $ins_data["images"] = $ins_data2;
                $ins_data["title"] = $top->title;
                $ins_data["Address"] = $top->Address;
                $top = json_encode(($ins_data));
                $top = str_replace('\\', '', $top);
                $appeventsData = [
                    'updated_at' => Date("Y-m-d H:i:s"), //$this->session->get('log_id'),
                    'top'=>$top,
                    'autogen_id'=>$cnt
                ];
                $this->db->table('afficialcompany')->update($appeventsData,["id"=>$id1]);
            }
        }
        session()->setFlashdata('succ', 'Slider saved successfully.');
        return redirect()->to('/afficial_company');
    }

}