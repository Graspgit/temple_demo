<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Devotee_management extends BaseController
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
	public function index()
	{
        $data['modules'] = $this->db->query('SELECT id, name FROM module ORDER BY id')->getResultArray();
        echo view('template/header');
		echo view('template/sidebar');
		echo view('devotee/index', $data);
        echo view('template/footer');
	}

    public function getDevotees() {

		$is_member = $this->request->getPost('is_member');
        $user_module_tag = $this->request->getPost('user_module_tag');
        $package_id = $this->request->getPost('package_id');

        $sql = 'SELECT dm.id, dm.name, dm.dob, dm.phone_code, dm.phone_number, dm.email, dm.is_member 
                FROM devotee_management dm 
                WHERE 1=1 ';

        $params = [];
        if ($is_member !== null && $is_member !== '') {
            $sql .= ' AND dm.is_member = ?';
            $params[] = $is_member;
        }

        if ($user_module_tag !== null && $user_module_tag !== '' && $package_id == null) {
            $sql .= ' AND dm.user_module_tag = ?';
            $params[] = $user_module_tag;
        }

        if ($package_id !== null && $package_id !== '') {
            $sql .= ' AND EXISTS (
                        SELECT 1
                        FROM devotee_activity da
                        WHERE da.devotee_id = dm.id 
                        AND da.activity_type = 4
                        AND da.module_type = 6
                        AND JSON_EXTRACT(da.details, "$.booking_id") IN (
                            SELECT bp.booking_id
                            FROM booked_packages bp
                            WHERE bp.package_id = ' . (int)$package_id . '  -- Directly inserting package_id
                            GROUP BY bp.booking_id
                            HAVING COUNT(bp.booking_id) >= 1
                        )
                    )';
        }

        $query = $this->db->query($sql, $params);
        $devotees = $query->getResultArray();

		return $this->response->setJSON($devotees);
	}

    public function getPackages() {
        $module_id = $this->request->getPost('module_id');
        $package_type = $module_id == 6 ? 2 : 1;
        
        $query = $this->db->query('SELECT id, name FROM temple_packages WHERE package_type = ? AND status = 1', [$package_type]);
        $packages = $query->getResultArray();

        echo json_encode($packages);
		exit();
    }

    public function add() {
        $data['rasi'] = $this->db->table('rasi')->get()->getResultArray();
        $data['natchathram'] = array();
        $data['phone_codes'] = $this->db->table("phone_code")->orderBy('dailing_code', 'ASC')->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('devotee/add_devotee', $data);
        echo view('template/footer');
    }

    public function view($id) {
        $data['data'] = $this->db->table('devotee_management')->where('id', $id)->get()->getRowArray();
        $data['rasi'] = $this->db->table('rasi')->get()->getResultArray();
        $data['natchathram'] = $this->db->table('natchathram')->get()->getResultArray();
        $data['phone_codes'] = $this->db->table("phone_code")->orderBy('dailing_code', 'ASC')->get()->getResultArray();
        $data['view'] = true;

        echo view('template/header');
        echo view('template/sidebar');
        echo view('devotee/add_devotee', $data);
        echo view('template/footer');
    }

    public function edit($id) {
        $data['data'] = $this->db->table('devotee_management')->where('id', $id)->get()->getRowArray();
        $data['rasi'] = $this->db->table('rasi')->get()->getResultArray();
        $data['natchathram'] = $this->db->table('natchathram')->get()->getResultArray();
        $data['phone_codes'] = $this->db->table("phone_code")->orderBy('dailing_code', 'ASC')->get()->getResultArray();
        $data['edit'] = true;

        echo view('template/header');
        echo view('template/sidebar');
        echo view('devotee/add_devotee', $data);
        echo view('template/footer');
    }

    public function save() {
        if (empty($_POST['phone_code']) || empty($_POST['phone_number']) || empty($_POST['name']) || empty($_POST['dob']) || empty($_POST['email']) || empty($_POST['address']) || empty($_POST['rasi_id']) || empty($_POST['natchathra_id'])) {
            $this->session->setFlashdata('fail', 'Please fill all required fields');
            $this->session->setFlashdata('data', $_POST);
            return redirect()->to(base_url() . "/devotee_management/add");
        }

        if (empty($_POST['id'])) {
            $devotee = $this->db->table('devotee_management')->where('phone_code', $_POST['phone_code'])->where('phone_number', $_POST['phone_number'])->get()->getNumRows();
            if ($devotee > 0) {
                $this->session->setFlashdata('fail', 'Phone number already exists');
                $this->session->setFlashdata('data', $_POST);
                return redirect()->to(base_url() . "/devotee_management/add");
            }
        }
        try{
            $id = $_POST['id'];
            $data['name'] = $_POST['name'];
            $data['dob'] = $_POST['dob'];
            $data['phone_code'] = $_POST['phone_code'];
            $data['phone_number'] = $_POST['phone_number'];
            $data['email'] = $_POST['email'];
            $data['ic_no'] = $_POST['ic_no'];
            $data['address'] = $_POST['address'];
            $data['rasi_id'] = $_POST['rasi_id'];
            $data['natchathra_id'] = $_POST['natchathra_id'];
            $data['user_module_tag'] = 0;
            $data['consent_for_reminders'] = $_POST['reminder_consent'];
            $data['consent_for_birthday_wishes'] = $_POST['birthday_consent'];
            $data['is_member'] = $_POST['is_member'];
            $data['added_through'] = 'ADMIN';
            $data['member_id'] = !empty($_POST['member_id']) ? $_POST['member_id'] : null;

            if (empty($id)) {
                $data['created_by'] = $this->session->get('log_id');
                $data['created_at'] = date('Y-m-d H:i:s');
                $builder = $this->db->table('devotee_management')->insert($data);
            } else {
                $data['updated_by'] = $this->session->get('log_id');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $builder = $this->db->table('devotee_management')->where('id', $id)->update($data);
            }

            if ($builder) {
                $message = empty($id) ? 'Devotee Added Successfully' : 'Devotee Updated Successfully';
                $this->session->setFlashdata('succ', $message);
                return redirect()->to(base_url() . "/devotee_management");
            } else {
                $this->session->setFlashdata('fail', 'Please Try Again');
                $this->session->setFlashdata('data', $_POST);
                return redirect()->to(base_url() . "/devotee_management/add");
            }

        } catch(Exception $e){
            $this->session->setFlashdata('fail', 'Please Try Again');
            return redirect()->to(base_url()."/devotee_management");
        }
    }

    public function get_natchathram() {
        $rasi_id = $this->request->getPost('rasi_id');
        $res = $this->db->table('rasi')->where('id', $rasi_id)->get()->getRowArray();

        $natchathram_ids = [];

        if (!empty($res['natchathra_id'])) {
            $natchathram_ids = explode(',', $res['natchathra_id']);
        } else {
            $natchathram_ids = array_column($this->db->table('natchathram')->select('id')->get()->getResultArray(), 'id');
        }

        $natchathrams = $this->db->table('natchathram')
            ->whereIn('id', $natchathram_ids)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'rasi_id' => $res['rasi_id'] ?? null,
            'natchathrams' => $natchathrams
        ]);
    }

    public function check_phone_number() {
        $phoneCode = $this->request->getPost('phone_code');
        $phoneNumber = $this->request->getPost('phone_number');

        $result = $this->db->table('devotee_management')
            ->where('phone_code', $phoneCode)
            ->where('phone_number', $phoneNumber)
            ->get()
            ->getRowArray();

        if ($result) {
            echo json_encode(['exists' => true]);
        } else {
            echo json_encode(['exists' => false]);
        }
    }

    public function check_member_exists() {
        $phoneCode = $this->request->getPost('phone_code');
        $phoneNumber = $this->request->getPost('phone_number');
        $mobile = $phoneCode . $phoneNumber;

        $member = $this->db->table('member')->where('mobile', $mobile)->get()->getRowArray();
        if ($member) {
            $data['id'] = $member['id'];
            $data['name'] = $member['name'];
            $data['dob'] = $member['dob'];
            $data['member_no'] = $member['member_no'];
            $data['email'] = $member['email_address'];
            $data['ic_no'] = $member['ic_no'];
            $data['address'] = $member['address'];
        }
        echo json_encode($data);
    }

}