<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
	function __construct(){
		$this->db = \Config\Database::connect();
		$this->session = \Config\Services::session($config);
		
		$uri = service('uri');
		
		$segment1 = "";
		$segment2 = "";
		
		if ($uri->getTotalSegments() >= 0) 
            $segment1 = $uri->getSegment(1);
        if ($uri->getTotalSegments() >= 1) 
            $segment2 = $uri->getSegment(2);

		if(!isset($_SESSION["log_id"]) && !isset($_SESSION["log_id_frend"])) //check both admin and online
		{
		    if($segment1 == "member_login") //if counter
		    {
		        if($segment2 != "" && "validation" != $segment2 && "logout" != $segment2) //allow login submit
		        {
    		        $data['dn_msg'] = 'Please Login';
    		        $redirect_url = base_url()."/member_login";
                    header("Location: ".$redirect_url);
                    exit; 
		        }
		    }
		    else
		    {
		        //check if online request
		        $online_controllers = ["archanai_booking","kattalai_archanai_online","offering_online","hallbooking_online","templeubayam_online","prasadam_online","donation_online","annathanam_counter","Dailyclosing_online","payment_voucher","report_online"];
		        if($segment1 != "" && 
		        
		        $segment2 != "validation" && $segment1 != "login" && $segment1 != "logout"
		        ) //allow login submit
		        {
		            if(in_array($segment1,$online_controllers))
		                $redirect_url = base_url()."/member_login";
		            else
    		            $redirect_url = base_url();
    		            
    				header("Location: ".$redirect_url);
    				exit;
		        }
		       //return redirect()->to('/member_login'); 
		    }
		    
		}
		
		//$this->session = \Config\Services::session();
		//$this->view = \Config\Services::renderer();
        $this->session->start();
        $this->json_resp = array();
		$this->json_resp['session'] = $_SESSION;
		global $set_lang;
        $set_lang->switcher();
    }
    protected $request;
    
    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['common'];
    //protected $uri;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        //$this->uri = service('url');

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }
}
