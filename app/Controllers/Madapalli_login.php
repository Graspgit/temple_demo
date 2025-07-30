<?php
namespace App\Controllers;
use App\Controllers\BaseController;

class Madapalli_login extends BaseController
{
    function __construct(){
        parent:: __construct();
        helper('url');
    }
    public function index(){
		$data['data'] = $this->db->table('admin_profile')->get()->getRowArray();
		echo view('madapalli/login', $data);
		
    }
    
    public function validation(){
        // print_r($_post);
        // exit;
        $data = array();
        $username = $_POST['username'];
        $password = $_POST['password'];
        if(trim($username) != '' && trim($password != '') ){
            
            $builder = $this->db->table('login')
						->where("username", $username)
						->where("password", $password)
						->where("member_comes", 'madapalli');

            $datas = $builder->get();
            $details = $datas->getRowArray();
            if($datas->resultID->num_rows > 0){
                $builder = $this->db->table('login')
                        ->join('admin_profile', 'login.profile_id = admin_profile.id')
                        ->select('login.*, login.id as log_id, login.name as log_name')
                        ->select('admin_profile.*')
                        ->where("login.profile_id", $details['profile_id'])
                        ->where("login.id", $details['id']);

                $datas = $builder->get();
                $details = $datas->getRowArray();
                $session = array(
                            'username_madap' => $details['username'],
                            'role' => $details['role'],
                            'log_id_madap' => $details['log_id'],
                            'log_name_madap' => $details['log_name'],
                            'profile_id_madap' => $details['profile_id'],
                            'logo_img_madap' => $details['image'],
                            'booking_range_year_madap' => $details['booking_range_year'],
                            'login_mapap' => true
                        );
                $val = $this->session->get('site_title');
                
                $this->session->set($session);
                $this->session->setFlashdata('succ', 'Login Successfully!...');

                return redirect()->to('/madapalli');
                
            }else{
                $this->session->setFlashdata('fail', 'Wrong Username And Password');
                return redirect()->to('/madapalli_login');
            }
        }else{
            $this->session->setFlashdata('fail', 'Please Fill Out Username And Password');
			return redirect()->to('/madapalli_login');
            //echo view('/member_login');
        }
    }
	public function log_check(){
		$json_row = array();
		$json_row['login'] = false;
		if($this->session->get('log_id_madap')) $json_row['login'] = true;
		echo json_encode($json_row);
		exit;
	}
    public function logout(){
        $this->session->destroy();
		return redirect()->to('/madapalli_login');
    }
}
