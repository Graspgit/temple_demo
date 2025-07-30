<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Customer extends BaseController
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
    
    /**
     * Get current active account year
     */
    private function getCurrentActiveYear() {
        return $this->db->table('ac_year')->where('status', 1)->get()->getRowArray();
    }
    
    /**
     * Get opening balance for a customer
     */
    private function getOpeningBalance($ledger_id) {
        $active_year = $this->getCurrentActiveYear();
        if (!$active_year) {
            return ['dr_amount' => 0, 'cr_amount' => 0, 'balance_type' => 'dr'];
        }
        
        $balance = $this->db->table('ac_year_ledger_balance')
            ->where('ac_year_id', $active_year['id'])
            ->where('ledger_id', $ledger_id)
            ->get()
            ->getRowArray();
            
        if ($balance) {
            $dr_amount = $balance['dr_amount'] ?? 0;
            $cr_amount = $balance['cr_amount'] ?? 0;
            
            if ($dr_amount > 0) {
                return [
                    'amount' => $dr_amount,
                    'balance_type' => 'dr'
                ];
            } elseif ($cr_amount > 0) {
                return [
                    'amount' => $cr_amount,
                    'balance_type' => 'cr'
                ];
            }
        }
        
        return ['amount' => 0, 'balance_type' => 'dr'];
    }
    
    /**
     * Save or update opening balance
     */
    private function saveOpeningBalance($ledger_id, $amount, $balance_type) {
        $active_year = $this->getCurrentActiveYear();
        if (!$active_year) {
            return false;
        }
        
        $amount = floatval($amount);
        if ($amount < 0) {
            return false;
        }
        
        // Check if balance already exists
        $existing_balance = $this->db->table('ac_year_ledger_balance')
            ->where('ac_year_id', $active_year['id'])
            ->where('ledger_id', $ledger_id)
            ->get()
            ->getRowArray();
        
        $balance_data = [
            'ac_year_id' => $active_year['id'],
            'ledger_id' => $ledger_id,
            'fund_id' => 1, // Default fund
            'dr_amount' => $balance_type == 'dr' ? $amount : 0.00,
            'cr_amount' => $balance_type == 'cr' ? $amount : 0.00,
            'quantity' => 0,
            'unit_price' => 0.00,
            'uom_id' => null
        ];
        
        if ($existing_balance) {
            // Update existing balance
            return $this->db->table('ac_year_ledger_balance')
                ->where('id', $existing_balance['id'])
                ->update($balance_data);
        } else {
            // Insert new balance record
            return $this->db->table('ac_year_ledger_balance')->insert($balance_data);
        }
    }
    
    public function index() {
        $data['suppliers'] = $this->db->table('customer')->get()->getResultArray();
        echo view('template/header');
        echo view('template/sidebar');
        echo view('customer/list', $data);
        echo view('template/footer');
    }
    
    public function add() {
        echo view('template/header');
        echo view('template/sidebar');
        echo view('customer/add');
        echo view('template/footer');
    }
    
    public function edit($id) {
        $data['supplier'] = $this->db->table('customer')->where("id", $id)->get()->getRowArray();
        
        // Get opening balance if customer has ledger_id
        if ($data['supplier'] && $data['supplier']['ledger_id']) {
            $opening_balance = $this->getOpeningBalance($data['supplier']['ledger_id']);
            $data['opening_balance_amount'] = $opening_balance['amount'];
            $data['opening_balance_dc'] = ucfirst($opening_balance['balance_type']);
        }
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('customer/add', $data);
        echo view('template/footer');
    }
    
    public function view($id) {
        $data['supplier'] = $this->db->table('customer')->where("id", $id)->get()->getRowArray();
        $data['view'] = true;
        
        // Get opening balance for view
        if ($data['supplier'] && $data['supplier']['ledger_id']) {
            $opening_balance = $this->getOpeningBalance($data['supplier']['ledger_id']);
            $data['opening_balance_amount'] = $opening_balance['amount'];
            $data['opening_balance_dc'] = ucfirst($opening_balance['balance_type']);
        }
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('customer/add', $data);
        echo view('template/footer');
    }
    
    public function create_ledger($typ, $ccode) {
        // TRADE DEBTORS
        $searcharr = ["trade debtor","trade debtors","trade receivable","trade receivables",
        "debtor","debtor","receivable","receivables"];
        
        $searchstr = "";
        foreach($searcharr as $iter) {
            $searchstr .= "'".$iter."',";
        }
        $searchstr = rtrim($searchstr,",");
        
        // Check the group for trade debtor/receivable
        $hadgroupdata = $this->db->table("groups")->where("LOWER(name) in (".$searchstr.")")->get()->getRowArray(); 
        
        $left_code = "1210";
        if(!isset($hadgroupdata["name"])) { // Create group
            $right_code = "0001";
            $data = [];
            $data["parent_id"] = 3;
            $data["name"] = "TRADE DEBTOR";
            $data["code"] = $left_code;
            $data["fixed"] = 1;
            $data["added_by"] = "";
            $data["created"] = Date("Y-m-d H:i:s");
            
            $this->db->table("groups")->insert($data);
            $group_id = $this->db->insertID();
        } else {
            $group_id = $hadgroupdata["id"];
            $ledger_data = $this->db->table("ledgers")->select("(IFNULL(MAX(`right_code`), 0) + 1) as right_code")->where("group_id",$group_id)->get()->getRowArray();
            $codarr = ["000","00","0",""];
            $right_code = $codarr[strlen($ledger_data["right_code"])-1].$ledger_data["right_code"];
        }
        
        // Create ledger
        $inp = [];
        $inp["group_id"] = $group_id;
        $inp["name"] = $_POST["customer_name"]."(".$ccode.")";
        $inp["left_code"] = $left_code;
        $inp["right_code"] = $right_code;
        $this->db->table("ledgers")->insert($inp);
        return $this->db->insertID();
    }
    
    public function store() {
        $id = $_POST['id'];
        $errmsgarr = [];
        
        // Validation
        if($_POST['customer_name']=="" || strlen($_POST['customer_name']) > 200) {
           $errmsgarr[] = "customer name"; 
        }
        if(strlen($_POST['mobile_no']) > 20) {
           $errmsgarr[] = "mobile no"; 
        }
        if(strlen($_POST['email']) > 200) {
           $errmsgarr[] = "email id"; 
        }
        if(strlen($_POST['vat_no']) > 100) {
           $errmsgarr[] = "vat no"; 
        }
        if(strlen($_POST['phoneno']) > 20) {
           $errmsgarr[] = "phone"; 
        }
        if(strlen($_POST['cr_no']) > 50) {
           $errmsgarr[] = "cr no"; 
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
        if(strlen($_POST['pincode']) > 20) {
           $errmsgarr[] = "pincode"; 
        }
        if(strlen($_POST['country']) > 100) {
           $errmsgarr[] = "country"; 
        }
        if(!empty($id) && intval($id) == 0) {
            $errmsgarr[] = "id"; 
        }
        
        // Validate opening balance
        $opening_balance = isset($_POST['opening_balance']) ? floatval($_POST['opening_balance']) : 0;
        $balance_type = isset($_POST['balance_type']) ? $_POST['balance_type'] : 'dr';
        
        if($opening_balance < 0) {
            $errmsgarr[] = "opening balance cannot be negative";
        }
        
        if(!in_array($balance_type, ['dr', 'cr'])) {
            $errmsgarr[] = "invalid balance type";
        }
        
        if(empty($errmsgarr)) {
            $data['customer_name'] = $_POST['customer_name'];
            $data['mobile_no'] = $_POST['mobile_no'];
            $data['vat_no'] = $_POST['vat_no'];
            $data['address1'] = $_POST['address1'];
            $data['address2'] = $_POST['address2'];
            $data['city'] = $_POST['city'];
            $data['state'] = $_POST['state'];
            $data['country'] = $_POST['country'];
            $data['phone'] = $_POST['phoneno'];
            $data['cr_no'] = $_POST['cr_no'];
            $data['email_id'] = $_POST['email'];
            $data['fax'] = $_POST['fax'];
            $data['zipcode'] = $_POST['pincode'];
            
            if(empty($id)) {
                // Create new customer
                $data['created_at'] = Date("Y-m-d H:i:s");
                $this->db->transBegin();
                
                try {
                    $builder = $this->db->table('customer')->insert($data);
                    if($builder) {
                        $id = $this->db->insertID();
                        $codarr = ["0000","000","00","0",""];
                        $ccode = "CU".$codarr[strlen($id)-1].$id;
                        $ledger_id = $this->create_ledger(1,$ccode);
                        
                        if($ledger_id == 0) {
                            $this->db->transRollback();
                            $this->session->setFlashdata('fail', 'Failed to create ledger. Please try again.');
                            return redirect()->to("/customer");
                        }
                        
                        // Update customer with code and ledger_id
                        $this->db->table('customer')->where('id', $id)->update([
                            "customer_code" => $ccode,
                            "ledger_id" => $ledger_id
                        ]);
                        
                        // Save opening balance if provided
                        if($opening_balance > 0) {
                            if(!$this->saveOpeningBalance($ledger_id, $opening_balance, $balance_type)) {
                                $this->db->transRollback();
                                $this->session->setFlashdata('fail', 'Failed to save opening balance. Please try again.');
                                return redirect()->to("/customer");
                            }
                        }
                        
                        $this->db->transCommit();
                        $this->session->setFlashdata('succ', 'Customer added successfully.');
                        return redirect()->to("/customer");
                    } else {
                        $this->db->transRollback();
                        $this->session->setFlashdata('fail', 'Failed to create customer. Please try again.');
                        return redirect()->to("/customer");
                    }
                } catch(Exception $exception) {
                    $this->db->transRollback();
                    $this->session->setFlashdata('fail', 'Database error: ' . $exception->getMessage());
                    return redirect()->to("/customer");
                }
            } else {
                // Update existing customer
                $this->db->transBegin();
                
                try {
                    $data['updated_at'] = Date("Y-m-d H:i:s");
                    $new_name = $_POST['customer_name'];
                    $olddata = $this->db->table('customer')->where('id', $id)->get()->getRowArray();
                    $builder = $this->db->table('customer')->where('id', $id)->update($data);
                    
                    if($builder) {
                        // Update ledger name if customer name changed
                        if($olddata["customer_name"] != $new_name) {
                            $this->db->table('ledgers')->where('id', $olddata["ledger_id"])->update([
                                "name" => $new_name."(".$olddata["customer_code"].")"
                            ]);
                        }
                        
                        // Update opening balance
                        if($olddata["ledger_id"]) {
                            if(!$this->saveOpeningBalance($olddata["ledger_id"], $opening_balance, $balance_type)) {
                                $this->db->transRollback();
                                $this->session->setFlashdata('fail', 'Failed to update opening balance. Please try again.');
                                return redirect()->to("/customer");
                            }
                        }
                        
                        $this->db->transCommit();
                        $this->session->setFlashdata('succ', 'Customer updated successfully.');
                        return redirect()->to("/customer");
                    } else {
                        $this->db->transRollback();
                        $this->session->setFlashdata('fail', 'Failed to update customer. Please try again.');
                        return redirect()->to("/customer");
                    }
                } catch(Exception $exception) {
                    $this->db->transRollback();
                    $this->session->setFlashdata('fail', 'Database error: ' . $exception->getMessage());
                    return redirect()->to("/customer");
                }
            }
        } else {
            $this->session->setFlashdata('fail', implode(", ",$errmsgarr)." are invalid or empty");
            return redirect()->to("/customer");
        }
    }
    
    /**
     * Get customer balance report
     */
    public function balanceReport() {
        $customers = $this->db->query("
            SELECT c.*, 
                   COALESCE(alb.dr_amount, 0) as dr_amount,
                   COALESCE(alb.cr_amount, 0) as cr_amount,
                   (COALESCE(alb.dr_amount, 0) - COALESCE(alb.cr_amount, 0)) as net_balance
            FROM customer c
            LEFT JOIN ac_year_ledger_balance alb ON c.ledger_id = alb.ledger_id
            LEFT JOIN ac_year ay ON ay.id = alb.ac_year_id AND ay.status = 1
            ORDER BY c.customer_name
        ")->getResultArray();
        
        $data['customers'] = $customers;
        
        echo view('template/header');
        echo view('template/sidebar');
        echo view('customer/balance_report', $data);
        echo view('template/footer');
    }
    
    /**
     * Export customers to CSV
     */
    public function exportCSV() {
        $customers = $this->db->query("
            SELECT c.customer_code, c.customer_name, c.mobile_no, c.email_id, c.cr_no,
                   c.address1, c.city, c.state, c.country, c.zipcode,
                   COALESCE(alb.dr_amount, 0) as dr_amount,
                   COALESCE(alb.cr_amount, 0) as cr_amount
            FROM customer c
            LEFT JOIN ac_year_ledger_balance alb ON c.ledger_id = alb.ledger_id
            LEFT JOIN ac_year ay ON ay.id = alb.ac_year_id AND ay.status = 1
            ORDER BY c.customer_name
        ")->getResultArray();
        
        $filename = 'customers_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV Headers
        fputcsv($output, [
            'Customer Code', 'Customer Name', 'Mobile No', 'Email', 'CR No',
            'Address', 'City', 'State', 'Country', 'Pin Code',
            'Debit Amount', 'Credit Amount', 'Net Balance'
        ]);
        
        // CSV Data
        foreach($customers as $customer) {
            $net_balance = $customer['dr_amount'] - $customer['cr_amount'];
            fputcsv($output, [
                $customer['customer_code'],
                $customer['customer_name'],
                $customer['mobile_no'],
                $customer['email_id'],
                $customer['cr_no'],
                $customer['address1'],
                $customer['city'],
                $customer['state'],
                $customer['country'],
                $customer['zipcode'],
                number_format($customer['dr_amount'], 2),
                number_format($customer['cr_amount'], 2),
                number_format($net_balance, 2)
            ]);
        }
        
        fclose($output);
        exit();
    }
}