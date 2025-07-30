<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;
use App\Models\RequestModel;

class Madapalli extends BaseController
{
    function __construct(){
        parent:: __construct();
        helper('url');
		helper('common_helper');
        $this->model = new PermissionModel();
        if( ($this->session->get('log_id_madap') ) == false ){
            $data['dn_msg'] = 'Please Login';
            return redirect()->to('/madapalli_login');
		}
    }

	public function index()
	{
		$login_id = $_SESSION['log_id_madap'];
		echo view('madapalli/layout/header');
		echo view('madapalli/index');
	}
    
}
