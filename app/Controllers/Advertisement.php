<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Advertisement extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		$this->model = new PermissionModel();
		if (($this->session->get('login')) == false && $this->session->get('role') != 1) {
			$data['dn_msg'] = 'Please Login';
			return redirect()->to('/login');
		}
	}

	// public function index()
	// {
	// 	$data = $this->db->table('booked_session_block')->get()->getRowArray();
	// 	//var_dump($data);
	// 	//exit;
	// 	echo view('template/header');
	// 	echo view('template/sidebar');
	// 	echo view('sessionblock/index', $data);
	// 	echo view('template/footer');
	// }

	public function index()
	{
		$builder = $this->db->table('advertisement');

		$builder->select('advertisement.id, advertisement.type, advertisement.status');
		$builder->orderBy('advertisement.id', 'DESC');  // Sort by date in ascending order

		$data['bookings'] = $builder->get()->getResultArray();

		// var_dump($data);
		// exit;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('advertisement/index', $data); 
		echo view('template/footer');
	}
	public function special_event()
	{
		$builder = $this->db->table('temple_session_spl_event');

		$builder->select('temple_session_spl_event.id, temple_session_spl_event.date, temple_session_spl_event.event_type as event, 
						temple_session_spl_event.booking_slot_id as session, temple_session_spl_event.description, 
						booking_slot_new.slot_name as session');
		$builder->join('booking_slot_new', 'temple_session_spl_event.booking_slot_id = booking_slot_new.id', 'left');
		$builder->orderBy('temple_session_spl_event.date', 'ASC');  // Sort by date in ascending order

		$data['bookings'] = $builder->get()->getResultArray();

		// var_dump($data);
		// exit;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('advertisement/index', $data); 
		echo view('template/footer');
	}


	public function add()
	{
		$data['slot_details'] = $this->db->table('booking_slot_type_new')
										 ->select('booking_slot_type_new.slot_type, booking_slot_new.slot_name, booking_slot_type_new.booking_slot_id')
										 ->join('booking_slot_new', 'booking_slot_new.id = booking_slot_type_new.booking_slot_id', 'left')
										 ->get()->getResultArray();

		echo view('template/header');
		echo view('template/sidebar');
		echo view('advertisement/add', $data); 
		echo view('template/footer');
	}
	public function add_special_event()
	{
		$data['slot_details'] = $this->db->table('booking_slot_type_new')
										 ->select('booking_slot_type_new.slot_type, booking_slot_new.slot_name, booking_slot_type_new.booking_slot_id')
										 ->join('booking_slot_new', 'booking_slot_new.id = booking_slot_type_new.booking_slot_id', 'left')
										 ->get()->getResultArray();

		echo view('template/header');
		echo view('template/sidebar');
		echo view('sessionblock/session_block_event', $data); 
		echo view('template/footer');
	}

	public function edit($id)
	{
		$data['data'] = $this->db->table('advertisement')
								->where('id', $id)
								->get()->getRowArray();

		// $data['data'] = $this->db->table('booked_session_block')
		// 						->select('booked_session_block.*, booking_slot_new.slot_name as event_type')
		// 						->join('booking_slot_new', 'booked_session_block.booking_slot_id = booking_slot_new.id', 'left')
		// 						->where('booked_session_block.id', $id)
		// 												->get()
		// 												->getRowArray();

	
		
		$data['edit'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('advertisement/add', $data); 
		echo view('template/footer');
	}

	public function view($id)
	{

		$data['data'] = $this->db->table('temple_session_spl_event')
								->where('id', $id)
								->get()->getRowArray();

		$data['slot_details'] = $this->db->table('booking_slot_type_new')
										->select('booking_slot_type_new.slot_type, booking_slot_new.slot_name, booking_slot_type_new.booking_slot_id')
										->join('booking_slot_new', 'booking_slot_new.id = booking_slot_type_new.booking_slot_id', 'left')
										->get()->getResultArray();

		$data['view'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('advertisement/add', $data); 
		echo view('template/footer');
	}

	
	

	public function delete($id)
	{

		$res=$this->db->table('advertisement')->delete(['id' => $id]);
		
		if ($res) {
			session()->setFlashdata('succ', 'Advertisement deleted successfully.');
		} else {
			session()->setFlashdata('fail', 'Please try again.');
		}
		
		return redirect()->to(base_url('/advertisement'));
	}

	public function save()
{
    $id = $this->request->getPost('id');
    $upload_type = $this->request->getPost('upload_type'); 
    $uploadDir_banner = 'uploads/advertisement/';
    $status = $this->request->getPost('active_status');
    
    $bannerImage = '';
    
    if ($upload_type == '1') {
        if (!empty($_FILES['image_upload']['name'])) {
            // print_r($_FILES['image_upload']);
            // die;
            $bannerImage = time() . '_' . $_FILES['image_upload']['name'];
            $bannerTargetPath = $uploadDir_banner . $bannerImage;
            
            if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $bannerTargetPath)) {
                if ($id) {
                    $existingEvent = $this->db->table('advertisement')->where('id', $id)->get()->getRow();
                    if ($existingEvent->file_name && file_exists($uploadDir_banner . $existingEvent->file_name)) {
                        unlink($uploadDir_banner . $existingEvent->file_name);
                    }
                }
            }
        } elseif ($id) {
            $existingEvent = $this->db->table('advertisement')->where('id', $id)->get()->getRow();
            $bannerImage = $existingEvent->file_name;
        }
    }
    
    elseif ($upload_type == '2') {
        $bannerImage = $this->request->getPost('video_url'); 
    }
    
 
    if ($id) {
        $appeventsData = [
            'type' => $upload_type,
            'file_name' => $bannerImage,
            'status' => $status,
            'updated_by' => $this->session->get('log_id'),
        ];
        $this->db->table('advertisement')->update($appeventsData, ['id' => $id]);
        $appeventsId = $id;
    } else {
        $appeventsData = [
            'type' => $upload_type,
            'file_name' => $bannerImage,
            'status' => $status,
            'created_by' => $this->session->get('log_id'),
            'updated_by' => $this->session->get('log_id'),
        ];
        $this->db->table('advertisement')->insert($appeventsData);
        $appeventsId = $this->db->insertID();
    }
    
    session()->setFlashdata('succ', 'Advertisement saved successfully.');
    return redirect()->to('/advertisement');
}



	

}