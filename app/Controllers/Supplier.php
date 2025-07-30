<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Supplier extends BaseController
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
    
    public function index() {
        $suppliers = $this->db->table('supplier s')
            ->select('s.*, l.name as ledger_name')
            ->join('ledgers l', 'l.id = s.ledger_id', 'left')
            ->orderBy('s.created_at', 'DESC')
            ->get()->getResultArray();
            
        $data['suppliers'] = $suppliers;
        echo view('template/header');
        echo view('template/sidebar');
        echo view('supplier/list', $data);
        echo view('template/footer');
    }
    
    public function add() {
        $data['ac_years'] = $this->get_active_ac_year();
        echo view('template/header');
        echo view('template/sidebar');
        echo view('supplier/add', $data);
        echo view('template/footer');
    }
    
    public function edit($id) {
        $supplier = $this->db->table('supplier')->where("id", $id)->get()->getRowArray();
        
        // Get opening balance if exists
        $opening_balance = $this->db->table('ac_year_ledger_balance ayl')
            ->select('ayl.*, ay.from_year_month, ay.to_year_month')
            ->join('ac_year ay', 'ay.id = ayl.ac_year_id')
            ->where('ayl.ledger_id', $supplier['ledger_id'])
            ->where('ay.status', 1)
            ->get()->getRowArray();
            
        $data['supplier'] = $supplier;
        $data['opening_balance'] = $opening_balance;
        $data['ac_years'] = $this->get_active_ac_year();
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('supplier/add', $data);
        echo view('template/footer');
    }
    
    public function view($id) {
        $supplier = $this->db->table('supplier')->where("id", $id)->get()->getRowArray();
        
        // Get opening balance if exists
        $opening_balance = $this->db->table('ac_year_ledger_balance ayl')
            ->select('ayl.*, ay.from_year_month, ay.to_year_month')
            ->join('ac_year ay', 'ay.id = ayl.ac_year_id')
            ->where('ayl.ledger_id', $supplier['ledger_id'])
            ->where('ay.status', 1)
            ->get()->getRowArray();
            
        $data['supplier'] = $supplier;
        $data['opening_balance'] = $opening_balance;
        $data['view'] = true;
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('supplier/add', $data);
        echo view('template/footer');
    }
    
    private function get_active_ac_year() {
        return $this->db->table('ac_year')
            ->where('status', 1)
            ->get()->getRowArray();
    }
    
    public function create_ledger($typ, $ccode)
    {
        // TRADE CREDITORS
        $searcharr = ["payable","payables","trade payable","trade payables",
        "creditor","creditors","trade creditor","trade creditors"];
        $searchstr = "";
        foreach($searcharr as $iter)
        {
            $searchstr .= "'".$iter."',";
        }
        $searchstr = rtrim($searchstr,",");
        
        $hadgroupdata = $this->db->table("groups")->where("LOWER(name) in (".$searchstr.")")->get()->getRowArray(); 
        
        $left_code = "2110";
        if(!isset($hadgroupdata["name"])) 
        {
            $right_code = "0001";
            $data = [];
            $data["parent_id"] = 14;
            $data["name"] = "TRADE CREDITORS";
            $data["code"] = $left_code;
            $data["fixed"] = 1;
            $data["added_by"] = "";
            $data["created"] = Date("Y-m-d H:i:s");
            
            $this->db->table("groups")->insert($data);
            $group_id = $this->db->insertID();
        }
        else 
        {
            $group_id = $hadgroupdata["id"];
            $ledger_data = $this->db->table("ledgers")->select("(IFNULL(MAX(`right_code`), 0) + 1) as right_code")->where("group_id",$group_id)->get()->getRowArray();
            $codarr = ["000","00","0",""];
            $right_code = $codarr[strlen($ledger_data["right_code"])-1].$ledger_data["right_code"];
        }
        
        // Create ledger
        $inp = [];
        $inp["group_id"] = $group_id;
        $inp["name"] = $_POST["supplier_name"]."(".$ccode.")";
        $inp["left_code"] = $left_code;
        $inp["right_code"] = $right_code;
        $this->db->table("ledgers")->insert($inp);
        return $this->db->insertID();
    }
    
    private function save_opening_balance($ledger_id, $ac_year_id, $amount, $balance_type) {
        // Delete existing opening balance for this ledger and year
        $this->db->table('ac_year_ledger_balance')
            ->where('ledger_id', $ledger_id)
            ->where('ac_year_id', $ac_year_id)
            ->delete();
            
        if($amount > 0) {
            $balance_data = [
                'ac_year_id' => $ac_year_id,
                'ledger_id' => $ledger_id,
                'dr_amount' => $balance_type == 'dr' ? $amount : 0,
                'cr_amount' => $balance_type == 'cr' ? $amount : 0,
                'quantity' => 0,
                'unit_price' => 0.00
            ];
            
            return $this->db->table('ac_year_ledger_balance')->insert($balance_data);
        }
        return true;
    }
    
    public function store() {
        $id = $_POST['id'];
        $errmsgarr = [];
        
        // Validation
        if($_POST['supplier_name']=="" || strlen($_POST['supplier_name']) > 200) {
           $errmsgarr[] = "Supplier name"; 
        }
        if(strlen($_POST['mobile_no']) > 20) {
           $errmsgarr[] = "mobile no"; 
        }
        if(strlen($_POST['email_id']) > 200) {
           $errmsgarr[] = "email id"; 
        }
        if(strlen($_POST['vat_no']) > 100) {
           $errmsgarr[] = "tin no"; 
        }
        if(strlen($_POST['phone']) > 20) {
           $errmsgarr[] = "phone"; 
        }
        if(strlen($_POST['fax']) > 100) {
           $errmsgarr[] = "fax"; 
        }
        if(strlen($_POST['city']) > 100) {
           $errmsgarr[] = "city"; 
        }
        if(strlen($_POST['state']) > 100) {
           $errmsgarr[] = "state"; 
        }
        if(strlen($_POST['zipcode']) > 20) {
           $errmsgarr[] = "zipcode"; 
        }
        if(strlen($_POST['country']) > 100) {
           $errmsgarr[] = "country"; 
        }
        if(!empty($id) && intval($id) == 0) {
            $errmsgarr[] = "id"; 
        }
        
        // Validate opening balance
        if(!empty($_POST['opening_balance']) && !is_numeric($_POST['opening_balance'])) {
            $errmsgarr[] = "Opening balance must be numeric";
        }
        
        if(empty($errmsgarr)) {
            $data['supplier_name'] = $_POST['supplier_name'];
            $data['mobile_no'] = $_POST['mobile_no'];
            $data['vat_no'] = $_POST['vat_no'];
            $data['address1'] = $_POST['address1'];
            $data['address2'] = $_POST['address2'];
            $data['city'] = $_POST['city'];
            $data['state'] = $_POST['state'];
            $data['country'] = $_POST['country'];
            $data['phone'] = $_POST['phoneno'];
            $data['contact'] = $_POST['contact'];
            $data['email_id'] = $_POST['email'];
            $data['fax'] = $_POST['fax'];
            $data['zipcode'] = $_POST['pincode'];
            
            if(empty($id)) {
                // Create new supplier
                $data['created_at'] = Date("Y-m-d H:i:s");
                $this->db->transBegin();
                
                try {
                    $builder = $this->db->table('supplier')->insert($data);
                    if($builder) {
                        $id = $this->db->insertID();
                        $codarr = ["0000","000","00","0",""];
                        $scode = "SU".$codarr[strlen($id)-1].$id;
                        $ledger_id = $this->create_ledger(1, $scode);
                        
                        if($ledger_id == 0) {
                            $this->db->transRollback();
                            $this->session->setFlashdata('fail', 'Please Try Again');
                            return redirect()->to("/supplier");
                        }
                        
                        $this->db->table('supplier')->where('id', $id)->update([
                            "supplier_code" => $scode,
                            "ledger_id" => $ledger_id
                        ]);
                        
                        // Save opening balance
                        $ac_year = $this->get_active_ac_year();
                        if($ac_year && !empty($_POST['opening_balance']) && $_POST['opening_balance'] > 0) {
                            $balance_type = $_POST['balance_type'] ?? 'cr'; // Default to credit for suppliers
                            $this->save_opening_balance($ledger_id, $ac_year['id'], $_POST['opening_balance'], $balance_type);
                        }
                        
                        $this->db->transCommit();
                        $this->session->setFlashdata('succ', 'Supplier Added Successfully');
                        return redirect()->to("/supplier");
                    } else {
                        $this->db->transRollback();
                        $this->session->setFlashdata('fail', 'Please Try Again');
                        return redirect()->to("/supplier");
                    }
                } catch(Exception $ex) {
                    $this->db->transRollback();
                    $this->session->setFlashdata('fail', 'Please Try Again');
                    return redirect()->to("/supplier");
                }
            } else {
                // Update existing supplier
                $this->db->transBegin();
                try {
                    $data['updated_at'] = Date("Y-m-d H:i:s");
                    $new_name = $_POST['supplier_name'];
                    $olddata = $this->db->table('supplier')->where('id', $id)->get()->getRowArray();
                    $builder = $this->db->table('supplier')->where('id', $id)->update($data);
                    
                    if($builder) {
                        if($olddata["supplier_name"] != $new_name) {
                            $this->db->table('ledgers')->where('id', $olddata["ledger_id"])->update([
                                "name" => $new_name."(".$olddata["supplier_code"].")"
                            ]);
                        }
                        
                        // Update opening balance
                        $ac_year = $this->get_active_ac_year();
                        if($ac_year && isset($_POST['opening_balance'])) {
                            $balance_type = $_POST['balance_type'] ?? 'cr';
                            $this->save_opening_balance($olddata["ledger_id"], $ac_year['id'], $_POST['opening_balance'], $balance_type);
                        }
                        
                        $this->db->transCommit();
                        $this->session->setFlashdata('succ', 'Supplier Updated Successfully');
                        return redirect()->to("/supplier");
                    } else {
                        $this->db->transRollback();
                        $this->session->setFlashdata('fail', 'Please Try Again');
                        return redirect()->to("/supplier");
                    }
                } catch(Exception $exception) {
                    $this->db->transRollback();
                    $this->session->setFlashdata('fail', 'Please Try Again');
                    return redirect()->to("/supplier");
                }
            }
        } else {
            $this->session->setFlashdata('fail', implode(",",$errmsgarr)." are invalid or empty");
            return redirect()->to("/supplier");
        }
    }
}