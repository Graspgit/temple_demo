<?php
namespace App\Controllers;
use App\Controllers\BaseController;

class Session extends BaseController
{
    function __construct(){
        parent:: __construct();
        helper('url');
        if( ($this->session->get('login') ) == false ){
            $data['dn_msg'] = 'Please Login';
            return redirect()->to('/login');
		}
    }

    public function index() {
        $res['data']= $this->db->table('admin_profile')->get()->getRowArray();
        $this->load->view('template/header', $res);
    }
    
}