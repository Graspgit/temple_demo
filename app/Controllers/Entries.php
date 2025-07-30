<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;
use App\Models\DeletedEntriesModel;
use App\Models\DeletionReasonsModel;
use App\Models\EntryTypesModel;
use App\Models\LoginModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class Entries extends BaseController
{
    function __construct(){
        parent:: __construct();
        helper('url');
		helper("common");
        $this->model = new PermissionModel();
        if( ($this->session->get('login') ) == false && $this->session->get('role') != 1){
            $data['dn_msg'] = 'Please Login';
            return redirect()->to('/login');
		}
    }	

	public function add_entries(){
		if(!$this->model->permission_validate('entries_accounts','create_p')){
			return redirect()->to(base_url().'/dashboard');}
	    $uid = $this->request->uri->getSegment(3);
		$group = $this->db->table("groups");
				if($uid == 4)
				{
					$group = $group->whereNotIn('id', array("4","7"));
				}
		$group = $group->get()->getResultArray();
        foreach($group as $row){
            $ledger[] = '<optgroup label="'.$row['name'].'">';
            $res = $this->db->table("ledgers")->where('group_id', $row['id'])->get()->getResultArray();
            foreach($res as $r){
                $id = $r['id'];
				$ledgername = get_ledger_name($id);
                $ledger[] .= '<option value="'.$id.'">'.$ledgername.'</option>';
            }
            $ledger[] .='</optgroup>';
        }
		
        $number = $this->db->table('entries')->select('number')->where('entrytype_id', $uid)->orderBy('id','desc')->get()->getRowArray(); 
		if(empty($number)) {
			$data['ent_num'] = 1;
		} else {
			$data['ent_num'] = $number['number'] + 1;
		}
        $yr=date('Y');
		$mon=date('m');
		$query   = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='". $yr ."' and entrytype_id = $uid and month (date)='". $mon ."')")->getRowArray();
        if($uid == 1) $bill_no = 'REC' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 2) $bill_no = 'PAY' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 3) $bill_no = 'CON' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 4) $bill_no = 'JOR' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        $data['entry_code'] = $bill_no;
		$data['en_id'] = $uid;
		if($uid == 1) $data['sub_title'] = "Receipt Entry";
		else if($uid == 2) $data['sub_title'] = "Payment Entry";
		else if($uid == 3) $data['sub_title'] = "Contra Entry";
		else if($uid == 4) $data['sub_title'] = "Journal Entry";
        $data['ledger'] =$ledger;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/add', $data);
		echo view('template/footer');
	}

	public function save_entries(){
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		
		//echo '<pre>'; 
		if($_POST['entries']){
            
            foreach($_POST['entries'] as $row0){
				
				if (empty($row0['ledger']) || ( empty(trim($row0['d_amt'])) && empty(trim($row0['c_amt']))) )
				{
					//print_r($row0);
					//die;
					// if(empty(trim($row['d_amt'])) && empty(trim($row['c_amt'])))
					// {
					$msg_data['err'] = 'Please check Empty Ledger / Dr Amount / Cr Amount. Please Try Again';
					echo json_encode($msg_data);
					exit();
					//}
				}
            }            
        }
		//exit;
		if ($_POST['total_debit']!=$_POST['total_credit']){			
		$msg_data['err'] = 'Credit and Debit Total Mismatched';
		echo json_encode($msg_data);
		exit();
		}
		
		
        $id = $this->request->uri->getSegment(3);
		
		$date = explode('-', $_POST['date']);
		$yr = $date[0];
  		$mon = $date[1];
		$query   = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='". $yr ."' and entrytype_id = $id and month (date)='". $mon ."')")->getRowArray();
        if($id == 1) $bill_no = 'REC' .date('y',strtotime($_POST['date'])).$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($id == 2) $bill_no = 'PAY' .date('y',strtotime($_POST['date'])).$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($id == 3) $bill_no = 'CON' .date('y',strtotime($_POST['date'])).$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($id == 4) $bill_no = 'JOR' .date('y',strtotime($_POST['date'])).$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        $data['entry_code'] = $bill_no;
		
		$number = $this->db->table('entries')->select('number')->where('entrytype_id', $id)->orderBy('id','desc')->get()->getRowArray(); 
		if(empty($number)) {
			$data['number'] = 1;
		} else {
			$data['number'] = $number['number'] + 1;
		}
		
        $data['entrytype_id']   = $id;
        $data['number']         = $_POST['number']; 
        $data['date']           = $_POST['date']; 
        $data['payment']        = $_POST['payment']; 
        $data['paid_to']        = $_POST['paid_to']; 
        $data['narration']      = $_POST['narration'];
        //$data['entry_code']     = $_POST['entry_code'];
        if($_POST['payment'] != 'cash'){
            $data['status']         = $_POST['status'];
            $data['cheque_no']      = $_POST['cheque_no'];
            $data['cheque_date']    = $_POST['cheque_date'];
            $data['return_date']    = $_POST['return_date'];
            $data['extra_charge']   = $_POST['extra_charge'];
            $data['collection_date']= $_POST['collection_date'];
        }
		if($_POST['entries']){
            $tot_d = 0; $tot_c = 0;
            foreach($_POST['entries'] as $row){
                if(trim($row['d_amt']) && $row['ledger']) $tot_d += $row['d_amt'];
                if(trim($row['c_amt']) && $row['ledger']) $tot_c += $row['c_amt'];
            }
            if($_POST['payment'] == "cheque" && $_POST['status'] == "returned")
			{
				if($_POST['extra_charge'] != "")
				{
					$data['dr_total']   = $tot_d + $_POST['extra_charge'];
					$data['cr_total']   = $tot_c + $_POST['extra_charge'];
				}
				else
				{
					$data['dr_total']   = $tot_d;
					$data['cr_total']   = $tot_c;
				}
			}
			else
			{
				$data['dr_total']   = $tot_d;
				$data['cr_total']   = $tot_c;
			}
        }
        $this->db->table("entries")->insert($data);
        $insid = $this->db->insertID();
        if(!empty($insid)){
            if($_POST['entries']){
                $tot_d = 0; $tot_c = 0;
                $i = 1;
                foreach($_POST['entries'] as $row)
				{
                    if(!empty($row['ledger']))
					{
						if($i == 1)
						{
							if($_POST['payment'] == "cheque" && $_POST['status'] == "returned")
							{
								if($_POST['extra_charge'] != "")
								{
									// EXTRA CHARGE SECTION C
									$extra_charge_nor = $this->db->table("ledgers")->where(["group_id"=>"15", "name"=>"Extra Charge"])->countAllResults();
									if($extra_charge_nor > 0)
									{
										$extra_charge_row = $this->db->table("ledgers")->where(["group_id"=>"15", "name"=>"Extra Charge"])->get()->getRowArray();
										$ledger_id = $extra_charge_row['id'];
										//var_dump($extra_charge_row);
										//exit;
									}
									else
									{
										$inser_ledger = array("group_id"=>15, "name"=>"Extra Charge", "op_balance"=>0, "op_balance_dc"=>"D");
										$this->db->table("ledgers")->insert($inser_ledger);
										$ledger_id = $this->db->insertID();
									}
									
									if($id == 1)
									{
										$dc_val = "C";
										if(trim($row['d_amt'])) $ent_firstdata['amount'] = $row['d_amt'] + $_POST['extra_charge'];
										$ent_firstdata['entry_id'] = $insid;
										$ent_firstdata['ledger_id'] = $row['ledger'];
										$ent_firstdata['dc'] = "D";
										$ent_firstdata['details'] = $row['details'];
										$this->db->table("entryitems")->insert($ent_firstdata);
										
									}
									else if($id == 2)
									{
										$dc_val = "D";
										if(trim($row['c_amt'])) $ent_firstdata['amount'] = $row['c_amt'] + $_POST['extra_charge'];
										$ent_firstdata['entry_id'] = $insid;
										$ent_firstdata['ledger_id'] = $row['ledger'];
										$ent_firstdata['dc'] = "C";
										$ent_firstdata['details'] = $row['details'];
										$this->db->table("entryitems")->insert($ent_firstdata);
									}
									else
									{
										$dc_val = "D";
										if(trim($row['c_amt'])) $ent_firstdata['amount'] = $row['c_amt'] + $_POST['extra_charge'];
										$ent_firstdata['entry_id'] = $insid;
										$ent_firstdata['ledger_id'] = $row['ledger'];
										$ent_firstdata['dc'] = "C";
										$ent_firstdata['details'] = $row['details'];
										$this->db->table("entryitems")->insert($ent_firstdata);
									}
									
									$ent_c['entry_id'] = $insid;
									$ent_c['ledger_id'] = $ledger_id;
									$ent_c['dc'] = $dc_val;
									$ent_c['details'] = "Extra Charge";
									$ent_c['amount'] = $_POST['extra_charge'];
									$this->db->table("entryitems")->insert($ent_c);
								}
							}
							else
							{
								if(trim($row['d_amt'])) $ent_firstdata['amount'] = $row['d_amt'];
								if(trim($row['c_amt'])) $ent_firstdata['amount'] = $row['c_amt'];
								$ent_firstdata['entry_id'] = $insid;
								$ent_firstdata['ledger_id'] = $row['ledger'];
								$ent_firstdata['dc'] = $row['dc'];
								$ent_firstdata['details'] = $row['details'];
								$this->db->table("entryitems")->insert($ent_firstdata);
							}
						}
						else
						{
							$ent['entry_id'] = $insid;
							$ent['ledger_id'] = $row['ledger'];
							$ent['dc'] = $row['dc'];
							$ent['details'] = $row['details'];
							if(trim($row['d_amt'])) $ent['amount'] = $row['d_amt'];
							if(trim($row['c_amt'])) $ent['amount'] = $row['c_amt'];
							$this->db->table("entryitems")->insert($ent);
						}
                    }
					$i++;
                }
            }//die;
				$msg_data['succ'] = 'Entries Added Successflly';
				$msg_data['id'] = $insid;
            
        }else{
            $msg_data['err'] = 'Please Try Again';
        }
		
		echo json_encode($msg_data);
		exit();
	}

    public function getbillno(){
        $uid = $_POST['eid'];
        $yr= date('Y',strtotime($_POST['dt'])) ;
        $mon= date('m',strtotime($_POST['dt'])) ;
        $query   = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='". $yr ."' and entrytype_id = $uid and month (date)='". $mon ."')")->getRowArray();
        if($uid == 1) $bill_no = 'REC' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 2) $bill_no = 'PAY' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 3) $bill_no = 'CON' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 4) $bill_no = 'JOR' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));

        echo $bill_no;      
    }

	public function view(){
	    if(!$this->model->permission_validate('entries_accounts','view')){
			return redirect()->to(base_url().'/dashboard');}
	    $id=  $this->request->uri->getSegment(3);
	    
		$data['results'] = $this->db->table("entries")->where('id', $id)->get()->getRowArray();
		$data['results2'] = $this->db->table("entryitems")->where('entry_id', $id)->get()->getResultArray();
			
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/view', $data);
		echo view('template/footer');
	}

    public function edit(){
        if(!$this->model->permission_validate('entries_accounts','edit')){
			return redirect()->to(base_url().'/dashboard');}
        $id=  $this->request->uri->getSegment(3);
        $data['results'] = $this->db->table("entries")->where('id', $id)->get()->getRowArray();
		$data['results2'] = $this->db->table("entryitems")->where('entry_id', $id)->get()->getResultArray();
        foreach($data['results2'] as $row){
            $res = $this->db->table('ledgers')->where('id', $row['ledger_id'])->get()->getRowArray();
            $led_name[] = $res['name'];
        }
		$group = $this->db->table("groups");
				if($id == 4)
				{
					$group = $group->whereNotIn('id', array("4","7"));
				}
		$group = $group->get()->getResultArray();
        foreach($group as $row){
            $ledger[] = '<optgroup label="'.$row['name'].'">';
            $res = $this->db->table("ledgers")->where('group_id', $row['id'])->get()->getResultArray();
            foreach($res as $r){
                $ids = $r['id'];
                $ledger[] .= '<option value="'.$ids.'">'.$r['name'].'</option>';
            }
            $ledger[] .='</optgroup>';
        }
        $data['led_name'] = $led_name;
        $data['ledger'] = $ledger;
        $data['en_id'] = $id;
	    echo view('template/header');
		echo view('template/sidebar');
        echo view('entries/edit', $data);
		echo view('template/footer');
    }

    public function update_entries(){
        // echo '<pre>'; print_r($_POST);//die;
		// echo '<pre>'; print_r($id);die;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		if($_POST['entries']){
            
            foreach($_POST['entries'] as $row0){
				
				if (empty($row0['ledger']) || (empty(trim($row0['d_amt'])) && empty(trim($row0['c_amt']))))
				{
					
					$msg_data['err'] = 'Please check Empty Ledger / Dr Amount / Cr Amount. Please Try Again';
					echo json_encode($msg_data);
					exit();					
				}				
            }            
        }		
		if ($_POST['total_debit']!=$_POST['total_credit']){	
		$msg_data['err'] = 'Credit and Debit Total Mismatched';
		echo json_encode($msg_data);
		exit();
		}
		
		
        $id = $this->request->uri->getSegment(3);
        $data['payment']        = $_POST['payment']; 
        $data['paid_to']        = $_POST['paid_to']; 
        $data['narration']      = $_POST['narration'];
        $data['entry_code']     = $_POST['entry_code'];
        if($_POST['payment'] == 'cash') $data['status'] = NULL;
        else $data['status'] = $_POST['status'];
        $data['cheque_no']      = $_POST['cheque_no'];
        $data['cheque_date']    = $_POST['cheque_date'];

        if($_POST['return_date'] == '') $data['return_date'] = NULL;
        else $data['return_date']    = $_POST['return_date'];

        if($_POST['extra_charge'] == '') $data['extra_charge'] = NULL;
        else $data['extra_charge']   = $_POST['extra_charge'];

        if($_POST['collection_date'] == '') $data['collection_date'] = NULL;
        else $data['collection_date']= $_POST['collection_date'];
		if($_POST['entries']){
            $tot_d = $_POST['sdr_total']; $tot_c = $_POST['scr_total'];
            foreach($_POST['entries'] as $row){
				
				//ledger
				
                if(trim($row['d_amt']) && $row['ledger']) $tot_d += $row['d_amt'];
                if(trim($row['c_amt']) && $row['ledger']) $tot_c += $row['c_amt'];
            }
            if($_POST['payment'] == "cheque" && $_POST['status'] == "returned")
			{
				if($_POST['extra_charge'] != "")
				{
					$data['dr_total']   = $tot_d + $_POST['extra_charge'];
					$data['cr_total']   = $tot_c + $_POST['extra_charge'];
				}
				else
				{
					$data['dr_total']   = $tot_d;
					$data['cr_total']   = $tot_c;
				}
			}
			else
			{
				$data['dr_total']   = $tot_d;
				$data['cr_total']   = $tot_c;
			}
        }
        $res = $this->db->table("entries")->where('id', $id)->update($data);
        $entry_type = $this->db->table("entries")->where('id', $id)->get()->getRowArray();
        //echo $this->db->getLastQuery();
        if($res){
            //print_r($data);die;
            if($_POST['entries']){
                $tot_d = 0; $tot_c = 0;
                $i = 1;
                foreach($_POST['entries'] as $row)
				{
                    if(!empty($row['ledger']))
					{
						if($i == 1)
						{
							if($_POST['payment'] == "cheque" && $_POST['status'] == "returned")
							{
								if($_POST['extra_charge'] != "")
								{
									// EXTRA CHARGE SECTION C
									$extra_charge_nor = $this->db->table("ledgers")->where(["group_id"=>"15", "name"=>"Extra Charge"])->countAllResults();
									if($extra_charge_nor > 0)
									{
										$extra_charge_row = $this->db->table("ledgers")->where(["group_id"=>"15", "name"=>"Extra Charge"])->get()->getRowArray();
										$ledger_id = $extra_charge_row['id'];
										//var_dump($extra_charge_row);
										//exit;
									}
									else
									{
										$inser_ledger = array("group_id"=>15, "name"=>"Extra Charge", "op_balance"=>0, "op_balance_dc"=>"D");
										$this->db->table("ledgers")->insert($inser_ledger);
										$ledger_id = $this->db->insertID();
									}
									
									if($entry_type['entrytype_id'] == 1)
									{
										$dc_val = "C";
										if(trim($row['d_amt'])) $ent_firstdata['amount'] = $row['d_amt'] + $_POST['extra_charge'];
										$ent_firstdata['entry_id'] = $id;
										$ent_firstdata['ledger_id'] = $row['ledger'];
										$ent_firstdata['dc'] = "D";
										$ent_firstdata['details'] = $row['details'];
										$this->db->table("entryitems")->insert($ent_firstdata);
										
									}
									else if($entry_type['entrytype_id'] == 2)
									{
										$dc_val = "D";
										if(trim($row['c_amt'])) $ent_firstdata['amount'] = $row['c_amt'] + $_POST['extra_charge'];
										$ent_firstdata['entry_id'] = $id;
										$ent_firstdata['ledger_id'] = $row['ledger'];
										$ent_firstdata['dc'] = "C";
										$ent_firstdata['details'] = $row['details'];
										$this->db->table("entryitems")->insert($ent_firstdata);
									}
									else
									{
										$dc_val = "D";
										if(trim($row['c_amt'])) $ent_firstdata['amount'] = $row['c_amt'] + $_POST['extra_charge'];
										$ent_firstdata['entry_id'] = $id;
										$ent_firstdata['ledger_id'] = $row['ledger'];
										$ent_firstdata['dc'] = "C";
										$ent_firstdata['details'] = $row['details'];
										$this->db->table("entryitems")->insert($ent_firstdata);
									}
									
									$ent_c['entry_id'] = $id;
									$ent_c['ledger_id'] = $ledger_id;
									$ent_c['dc'] = $dc_val;
									$ent_c['details'] = "Extra Charge";
									$ent_c['amount'] = $_POST['extra_charge'];
									$this->db->table("entryitems")->insert($ent_c);
								}
							}
							else
							{
								if(trim($row['d_amt'])) $ent_firstdata['amount'] = $row['d_amt'];
								if(trim($row['c_amt'])) $ent_firstdata['amount'] = $row['c_amt'];
								$ent_firstdata['entry_id'] = $id;
								$ent_firstdata['ledger_id'] = $row['ledger'];
								$ent_firstdata['dc'] = $row['dc'];
								$ent_firstdata['details'] = $row['details'];
								$this->db->table("entryitems")->insert($ent_firstdata);
							}
						}
						else
						{
							$ent['entry_id'] = $id;
							$ent['ledger_id'] = $row['ledger'];
							$ent['dc'] = $row['dc'];
							$ent['details'] = $row['details'];
							if(trim($row['d_amt'])) $ent['amount'] = $row['d_amt'];
							if(trim($row['c_amt'])) $ent['amount'] = $row['c_amt'];
							$this->db->table("entryitems")->insert($ent);
						}
                    }
					$i++;
                }
            }//die;
				$msg_data['succ'] = 'Entries Updated Successflly';
				$msg_data['id'] = $id;
        }else{
            $msg_data['err'] = 'Please Try Again';
        }
		
		echo json_encode($msg_data);
		exit();
    }

    public function delete(){
        if(!$this->model->permission_validate('entries_accounts','delete_p')){
			return redirect()->to(base_url().'/dashboard');}
        $id = $this->request->uri->getSegment(3);
        if($id){
            $res = $this->db->table('entryitems')->delete(['entry_id' => $id]);
            if($res){
                $res1 = $this->db->table('entries')->delete(['id' => $id]);
                if($res1){
                    $this->session->setFlashdata('succ', 'Entries Delete Successfully');
			        return redirect()->to(base_url()."/account/entries");}else{
                    $this->session->setFlashdata('fail', 'Please Try Again');
			        return redirect()->to(base_url()."/account/entries");}
            }else{
                $this->session->setFlashdata('fail', 'Please Try Again');
			    return redirect()->to(base_url()."/account/entries");}
        }else{
            $this->session->setFlashdata('fail', 'Please Try Again');
			return redirect()->to(base_url()."/account/entries");}
    }


	public function AmountInWords()
	{
		$amount = (float) $_POST['number'];
		$amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;

		// Function to convert number to words
		function convertNumberToWords($number)
		{
			$words = [
				0 => 'zero',
				1 => 'one',
				2 => 'two',
				3 => 'three',
				4 => 'four',
				5 => 'five',
				6 => 'six',
				7 => 'seven',
				8 => 'eight',
				9 => 'nine',
				10 => 'ten',
				11 => 'eleven',
				12 => 'twelve',
				13 => 'thirteen',
				14 => 'fourteen',
				15 => 'fifteen',
				16 => 'sixteen',
				17 => 'seventeen',
				18 => 'eighteen',
				19 => 'nineteen',
				20 => 'twenty',
				30 => 'thirty',
				40 => 'forty',
				50 => 'fifty',
				60 => 'sixty',
				70 => 'seventy',
				80 => 'eighty',
				90 => 'ninety'
			];

			$suffixes = ['', 'Thousand', 'Million', 'Billion', 'Trillion'];

			if ($number == 0) {
				return $words[0];
			}

			$output = '';
			$groupIndex = 0;

			// Split number into groups of 3 digits from right to left
			while ($number > 0) {
				$group = $number % 1000;
				if ($group > 0) {
					$groupWords = convertThreeDigitNumberToWords($group, $words);
					$output = $groupWords . ' ' . $suffixes[$groupIndex] . ' ' . $output;
				}
				$number = (int) ($number / 1000);
				$groupIndex++;
			}

			return trim($output);
		}

		// Function to convert 3-digit number to words
		function convertThreeDigitNumberToWords($number, $words)
		{
			$hundreds = (int) ($number / 100);
			$remainder = $number % 100;

			$result = '';

			if ($hundreds > 0) {
				$result .= $words[$hundreds] . ' hundred';
				if ($remainder > 0) {
					$result .= ' and ';
				}
			}

			if ($remainder > 0) {
				if ($remainder < 20) {
					$result .= $words[$remainder];
				} else {
					$tens = (int) ($remainder / 10) * 10;
					$units = $remainder % 10;
					$result .= $words[$tens];
					if ($units > 0) {
						$result .= '-' . $words[$units];
					}
				}
			}

			return $result;
		}

		// Get the amount in words (Ringgit)
		$amount_in_words = convertNumberToWords(floor($amount));

		// Get the amount after decimal (paise/cents) in words
		$get_paise = ($amount_after_decimal > 0) ? " and Cents " . trim(convertNumberToWords($amount_after_decimal)) : '';

		// Return the result
		return 'Ringgit ' . trim($amount_in_words) . $get_paise . ' Only';
	}
	public function AmountInWords_old(){
        $amount = (float)$_POST['number'];
        $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
		// // return ($num).'';
		// // exit;
        // Check if there is any number after decimal
		function NumToWords($num){
			$num=floor($num);
			$amt_hundred = null;
			$count_length = strlen($num);
			$x = 0;
			$string = array();
			$change_words = array(0 => '', 1 => 'One', 2 => 'Two',
				3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
				7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
				10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
				13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
				16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
				19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
				40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
				70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
				$here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
				while( $x < $count_length ) {
					$get_divider = ($x == 2) ? 10 : 100;
					$amount = floor($num % $get_divider);
					$num = floor($num / $get_divider);
					$x += $get_divider == 10 ? 1 : 2;
					if ($amount) {
						$add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
						$amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
						$string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
					}else $string[] = null;
			}
			//$implode_to_Rupees = implode('', array_reverse($string));
			return(implode('', array_reverse($string)));
		}
        
        /*$get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
        " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';*/
        //$get_paise = ($amount_after_decimal > 0) ? " and Cents ".(trim($change_words[$amount_after_decimal])): '';
        
		$get_paise = ($amount_after_decimal > 0) ? " and Cents ". trim(NumToWords($amount_after_decimal)):'';
        //return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
        return (NumToWords($amount) ? 'Ringgit '.trim(NumToWords($amount)).'' : ''). $get_paise. ' Only';
        
		//return ($implode_to_paise).'';
		//echo json_encode($amt_words);
    }
	
    public function AmountInWords2(){
		
		// after decimal if 99 is nine nine
        
        $amount = (float)$_POST['number'];
        $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
		
        // Check if there is any number after decimal
        $amt_hundred = null;
        $count_length = strlen($num);
        $x = 0;
        $string = array();
        $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
            $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
            while( $x < $count_length ) {
                $get_divider = ($x == 2) ? 10 : 100;
                $amount = floor($num % $get_divider);
                $num = floor($num / $get_divider);
                $x += $get_divider == 10 ? 1 : 2;
                if ($amount) {
                    $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
                    $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
                    $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
                }else $string[] = null;
        }
        $implode_to_Rupees = implode('', array_reverse($string));
        /*$get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
        " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';*/
        $get_paise = ($amount_after_decimal > 0) ? " and Cents ".(trim($change_words[$amount_after_decimal /10]).' '. trim($change_words[$amount_after_decimal % 10]))  : '';
        
		//$get_paise = ($amount_after_decimal > 0) ? " and Cents ".(trim($change_words[$amount_after_decimal])):'';
        //return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
        return ($implode_to_Rupees ? 'Ringgit '.trim($implode_to_Rupees).'' : ''). $get_paise. ' Only';
        
		//return ($amount_after_decimal).'';
		//echo json_encode($amt_words);
    }
	
	public function print_page(){
		
		$id = $this->request->uri->getSegment(3);
			
		$data['results'] = $this->db->table("entries")->where('id', $id)->get()->getRowArray();
		$tmpid = $this->session->get('profile_id');
		$data['temple_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		//print_r($data['results']['entrytype_id']); die;
		if ($data['results']['entrytype_id'] !=4)
		
			echo view('entries/entries_print', $data);
		else
			echo view('entries/entries_print_journal', $data);
	 }

	public function list(){
		if(!$this->model->list_validate('entries_accounts')){
			return redirect()->to(base_url().'/dashboard');}
		$data['permission'] = $this->model->get_permission('entries_accounts');  
		$entries = $this->db->query("SELECT * FROM entries where type is NULL or type = '' ORDER BY entries.date DESC")->getResultArray();
		//$data['data'] = $this->db->table('entries')->orderBy('id', 'desc')->get()->getResultArray();//->where('inv_id', null)->where('type', null)
		$data['data'] = $entries;

		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/list', $data);
		echo view('template/footer');
	}
	public function view_page($id1){
		if(!$this->model->list_validate('entries_accounts')){
			return redirect()->to(base_url().'/dashboard');}
		$data['permission'] = $this->model->get_permission('entries_accounts');
		$entries = $this->db->query("SELECT * FROM entries WHERE id = $id1")->getRowArray();
		$data['entries'] = $entries;
		$entry_id = $entries['id'];
		$data['en_id'] = $entries['entrytype_id'];
		$data['entry_code'] = $entries['entry_code'];
		$data['bank_ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->where('type', 1)->get()->getResultArray();
		$data['view'] = true;
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		if($entries['entrytype_id'] == "1")
		{
			$entrie_items = $this->db->query("SELECT * FROM entryitems WHERE entry_id = $entry_id and dc = 'C' ")->getResultArray();
			$data['entrie_items'] = $entrie_items;
			$data['credit_ledger'] = $this->db->query("SELECT ledger_id,details FROM entryitems WHERE entry_id = $entry_id and dc = 'D' ")->getRowArray();
			echo view('entries/receipt_add',$data);
		}
		if($entries['entrytype_id'] == "2")
		{
			$entrie_items = $this->db->query("SELECT * FROM entryitems WHERE entry_id = $entry_id and dc = 'D' ")->getResultArray();
			$data['entrie_items'] = $entrie_items;
			$data['credit_ledger'] = $this->db->query("SELECT ledger_id,details FROM entryitems WHERE entry_id = $entry_id and dc = 'C' ")->getRowArray();
			echo view('entries/payment_add',$data);
		}
		if($entries['entrytype_id'] == "4")
		{
			$entrie_items = $this->db->query("SELECT * FROM entryitems WHERE entry_id = $entry_id ")->getResultArray();
			$data['entrie_items'] = $entrie_items;
			$data['credit_ledger'] = $this->db->query("SELECT ledger_id,details FROM entryitems WHERE entry_id = $entry_id ")->getRowArray();
			echo view('entries/journal_add',$data);
		}
		echo view('template/footer');
	}
	public function edit_page($id1){
		if(!$this->model->list_validate('entries_accounts')){
			return redirect()->to(base_url().'/dashboard');}
		$data['permission'] = $this->model->get_permission('entries_accounts');  
		$entries = $this->db->query("SELECT * FROM entries WHERE id = $id1")->getRowArray();
		$data['entries'] = $entries;
		$entry_id = $entries['id'];
		$data['en_id'] = $entries['entrytype_id'];
		$data['entry_code'] = $entries['entry_code'];
		$data['ledgers'] = $this->db->table("ledgers")->select('id,name,code')->get()->getResultArray();
		$data['view'] = true;
		echo view('template/header');
		echo view('template/sidebar');
		if($entries['entrytype_id'] == "1")
		{
			$entrie_items = $this->db->query("SELECT * FROM entryitems WHERE entry_id = $entry_id and dc = 'C' ")->getResultArray();
			$data['entrie_items'] = $entrie_items;
			if(count($entrie_items) > 0){
				$data['entrie_items_count'] = count($entrie_items) + 1;
			}
			else{
				$data['entrie_items_count'] = 1;
			}
			$data['credit_ledger'] = $this->db->query("SELECT ledger_id,details FROM entryitems WHERE entry_id = $entry_id and dc = 'D' ")->getRowArray();
			echo view('entries/receipt_add',$data);
		}
		if($entries['entrytype_id'] == "2")
		{
			echo view('entries/payment_add',$data);
		}
		if($entries['entrytype_id'] == "4")
		{
			echo view('entries/journal_add',$data);
		}
		echo view('template/footer');
	}
	public function delete_page(){
        if(!$this->model->permission_validate('entries_accounts','delete_p')){
			return redirect()->to(base_url().'/dashboard');}
        $id = $this->request->uri->getSegment(3);
        if($id){
            $res = $this->db->table('entryitems')->delete(['entry_id' => $id]);
            if($res){
                $res1 = $this->db->table('entries')->delete(['id' => $id]);
                if($res1){
                    $this->session->setFlashdata('succ', 'Entries Delete Successfully');
			        return redirect()->to(base_url()."/entries/list");}else{
                    $this->session->setFlashdata('fail', 'Please Try Again');
			        return redirect()->to(base_url()."/entries/list");}
            }else{
                $this->session->setFlashdata('fail', 'Please Try Again');
			    return redirect()->to(base_url()."/entries/list");}
        }else{
            $this->session->setFlashdata('fail', 'Please Try Again');
			return redirect()->to(base_url()."/entries/list");}
    }
	public function generateReceiptEntryCodeByDate()
	{
		helper('date'); // Load the date helper if needed

		// Get the date from POST request
		$date = $this->request->getPost('date');
		if (!$date) {
			return $this->response->setJSON(['error' => 'No date provided']);
		}

		$yr = date('Y', strtotime($date));
		$mon = date('m', strtotime($date));

		// Query to get the last entry code for the given date
		$uid = 1; // Example user ID or type ID, adjust as needed
		$query = $this->db->query("SELECT entry_code FROM entries WHERE id = (SELECT max(id) FROM entries WHERE YEAR(date) = '$yr' AND entrytype_id = $uid AND MONTH(date) = '$mon')")->getRowArray();

		$next_number = isset($query['entry_code']) ? ((float) substr($query['entry_code'], -5)) + 1 : 1;

		// $bill_no = 'PAY' . date('y', strtotime($date)) . $mon . sprintf("%05d", $next_number);

		// 
		if ($uid == 1)
			$bill_no = 'REC' . date('y', strtotime($date)) . $mon . sprintf("%05d", $next_number);
		if ($uid == 2)
			$bill_no = 'PAY' . date('y', strtotime($date)) . $mon . sprintf("%05d", $next_number);
		if ($uid == 3)
			$bill_no = 'CON' . date('y', strtotime($date)) . $mon . sprintf("%05d", $next_number);
		if ($uid == 4)
			$bill_no = 'JOR' . date('y', strtotime($date)) . $mon . sprintf("%05d", $next_number);
		return $this->response->setJSON(['entryCode' => $bill_no]);
	}
    public function receipt_add($id = 0){
		$uid = 1;
        $yr=date('Y');
		$mon=date('m');
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$start_date = $ac_year['from_year_month'] . '-01';
		$end_date = date('Y-m-t', strtotime($ac_year['to_year_month'] . '-01'));
		$data['financial_year']['start_date'] = $start_date;
		$data['financial_year']['end_date'] = $end_date;
		$query   = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='". $yr ."' and entrytype_id = $uid and month (date)='". $mon ."')")->getRowArray();
        if($uid == 1) $bill_no = 'REC' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 2) $bill_no = 'PAY' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 3) $bill_no = 'CON' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 4) $bill_no = 'JOR' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        $data['entry_code'] = $bill_no;
		$data['ledgers'] = $this->db->query("SELECT * FROM `ledgers` where group_id not in (SELECT id FROM `groups` WHERE code in (5000, 6000, 9000) or parent_id in (SELECT id FROM `groups` WHERE code in (5000, 6000, 9000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (5000, 6000, 9000))))")->getResultArray();
		$data['bank_ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->where('type', 1)->get()->getResultArray();
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		$data['payment_mode'] = $this->db->table('payment_mode')->where("paid_through", "DIRECT")->where("expenses", 1)->where('status', 1)->get()->getResultArray();
		$data['en_id'] = $uid;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/receipt_add',$data);
		echo view('template/footer');
	}
	public function receipt_edit($id){
		$uid = 1;
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$start_date = $ac_year['from_year_month'] . '-01';
		$end_date = date('Y-m-t', strtotime($ac_year['to_year_month'] . '-01'));
		$data['financial_year']['start_date'] = $start_date;
		$data['financial_year']['end_date'] = $end_date;
        $entries = $this->db->query("SELECT * FROM entries WHERE id = $id")->getRowArray();
		$data['entries'] = $entries;
		$entry_id = $entries['id'];
		$data['en_id'] = $entries['entrytype_id'];
		$data['entry_code'] = $entries['entry_code'];
		$entrie_items = $this->db->query("SELECT * FROM entryitems WHERE entry_id = $entry_id and dc = 'C' ")->getResultArray();
		$data['entrie_items'] = $entrie_items;
		$data['credit_ledger'] = $this->db->query("SELECT ledger_id,details FROM entryitems WHERE entry_id = $entry_id and dc = 'D' ")->getRowArray();
		$data['ledgers'] = $this->db->query("SELECT * FROM `ledgers` where group_id not in (SELECT id FROM `groups` WHERE code in (5000, 6000, 9000) or parent_id in (SELECT id FROM `groups` WHERE code in (5000, 6000, 9000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (5000, 6000, 9000))))")->getResultArray();
		$data['bank_ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->where('type', 1)->get()->getResultArray();
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		$data['payment_mode'] = $this->db->table('payment_mode')->where("paid_through", "DIRECT")->where("expenses", 1)->where('status', 1)->get()->getResultArray();
		$data['en_id'] = $uid;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/receipt_edit',$data);
		echo view('template/footer');
	}
	public function receipt_debit_ac()
	{
		$rtdebit_ac = $_POST['rtdebit_ac'];
		$reslt_data = $this->db->query("SELECT * FROM ledgers where id !=$rtdebit_ac and group_id not in (SELECT id FROM `groups` WHERE code in (5000, 6000, 9000) or parent_id in (SELECT id FROM `groups` WHERE code in (5000, 6000, 9000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (5000, 6000, 9000))))")->getRowArray();
		$html = "<option value='0'>Select debit a/c</option>";
		foreach($reslt_data as $res)
		{
			$html .= "<option value=".$res["id"].">" . $res["left_code"] . '/' . $res["right_code"] . " - ".$res["name"]."</option>";
		}
		echo $html;
	}
	public function save_receipt_entries(){
		//var_dump($_POST['entries']);
		//exit;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$entry_id = $_POST['entry_id'];
		$entry_type_id = $_POST['entry_type_id'];
		$receipt_date = $_POST['receipt_date'];
		$receipt_credit_ac = $_POST['receipt_credit_ac'];
		$receipt_entrycode = $_POST['receipt_entrycode'];
		$receipt_receivedfrom = !empty($_POST['receipt_receivedfrom']) ? $_POST['receipt_receivedfrom'] : '';
		$fund_id = $_POST['fund_id'];
		$pay_method = $_POST['receipt_paymode'];

		if($pay_method == "cheque")
		{
			$receipt_chequeno = !empty($_POST['receipt_chequeno']) ? $_POST['receipt_chequeno'] : '';
			$receipt_chequedate = !empty($_POST['receipt_chequedate']) ? $_POST['receipt_chequedate'] : '';
		}
		else if($pay_method == "online")
		{
			$receipt_chequeno = !empty($_POST['receipt_refno']) ? $_POST['receipt_refno'] : '';
			$receipt_chequedate = !empty($_POST['receipt_transactiondate']) ? $_POST['receipt_transactiondate'] : '';
		}
		else
		{
			$receipt_chequeno = NULL;
			$receipt_chequedate = NULL;
		}
		$receipt_particulars = $_POST['receipt_particulars'];
		$tot_amt_input = $_POST['tot_amt_input'];
		
		if( !empty($tot_amt_input) && !empty($pay_method) && !empty($_POST['entries']) && !empty($receipt_entrycode) && !empty($fund_id) && !empty($receipt_particulars) ) {
			$data['entrytype_id']   = $entry_type_id;
			$data['payment']   = $pay_method;
			$data['date']           = $receipt_date; 
			$data['dr_total']        = $tot_amt_input; 
			$data['cr_total']        = $tot_amt_input; 
			$data['narration']      = $receipt_particulars;
			$data['entry_code']      = $receipt_entrycode;
			$data['fund_id']      = $fund_id;
			$data['paid_to']      = $receipt_receivedfrom; 
			$data['cheque_no']      = $receipt_chequeno; 
			$data['cheque_date']      = $receipt_chequedate;

			if(!empty($entry_id)){
				$this->db->table("entries")->where('id', $entry_id)->update($data);
				$insid = $entry_id;
			}
			else{
				$number = $this->db->table('entries')->select('number')->where('entrytype_id', $entry_type_id)->orderBy('id','desc')->get()->getRowArray(); 
				if(empty($number)) {
					$data['number'] = 1;
				} else {
					$data['number'] = $number['number'] + 1;
				}
				$this->db->table("entries")->insert($data);
        		$insid = $this->db->insertID();
				// CREDIT as of now DEBIT
				if(!empty($receipt_credit_ac))
				{
					$entryitems_c['entry_id']  = $insid;
					$entryitems_c['ledger_id'] = $receipt_credit_ac;
					$entryitems_c['details'] = $receipt_particulars;
					$entryitems_c['amount'] = $tot_amt_input;
					$entryitems_c['dc'] = "D";
					$this->db->table('entryitems')->insert($entryitems_c);
				}
			}
			if(!empty($insid)){
				if(!empty($_POST['entries'])){
					foreach($_POST['entries'] as $row)
					{
						if(empty($row['entryitemid']))
						{
							$entryitems_d['entry_id']  = $insid;
							$entryitems_d['ledger_id'] = $row['ledgerid'];
							$entryitems_d['details'] = $receipt_particulars;
							$entryitems_d['amount'] = $row['amount'];
							$entryitems_d['dc'] = "C";
							$this->db->table('entryitems')->insert($entryitems_d);
						}
					}
				}
				$msg_data['succ'] = 'Receipt Entry Added Successfully';
              	$msg_data['id'] = $insid;
			}
			else{
            	$msg_data['err'] = 'Please Try Again';
        	}
		}
		else
		{
			$msg_data['err'] = 'Please Fill All Required Field';
		}
		echo json_encode($msg_data);
      	exit();
	}
	public function update_receipt_entries(){
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$entry_id = $_POST['entry_id'];
		$entry_type_id = $_POST['entry_type_id'];
		$receipt_date = $_POST['receipt_date'];
		$receipt_credit_ac = $_POST['receipt_credit_ac'];
		$receipt_entrycode = $_POST['receipt_entrycode'];
		$receipt_receivedfrom = !empty($_POST['receipt_receivedfrom']) ? $_POST['receipt_receivedfrom'] : '';
		$fund_id = $_POST['fund_id'];
		$pay_method = $_POST['receipt_paymode'];

		if($pay_method == "cheque")
		{
			$receipt_chequeno = !empty($_POST['receipt_chequeno']) ? $_POST['receipt_chequeno'] : '';
			$receipt_chequedate = !empty($_POST['receipt_chequedate']) ? $_POST['receipt_chequedate'] : '';
		}
		else if($pay_method == "online")
		{
			$receipt_chequeno = !empty($_POST['receipt_refno']) ? $_POST['receipt_refno'] : '';
			$receipt_chequedate = !empty($_POST['receipt_transactiondate']) ? $_POST['receipt_transactiondate'] : '';
		}
		else
		{
			$receipt_chequeno = NULL;
			$receipt_chequedate = NULL;
		}
		$receipt_particulars = $_POST['receipt_particulars'];
		$tot_amt_input = $_POST['tot_amt_input'];
		
		if( !empty($tot_amt_input) && !empty($pay_method) && !empty($_POST['entries']) && !empty($receipt_entrycode) && !empty($fund_id) && !empty($receipt_particulars) ) {
			$data['entrytype_id']   = $entry_type_id;
			$data['payment']   = $pay_method;
			$data['date']           = $receipt_date; 
			$data['dr_total']        = $tot_amt_input; 
			$data['cr_total']        = $tot_amt_input; 
			$data['narration']      = $receipt_particulars;
			$data['entry_code']      = $receipt_entrycode;
			$data['fund_id']      = $fund_id;
			$data['paid_to']      = $receipt_receivedfrom; 
			$data['cheque_no']      = $receipt_chequeno; 
			$data['cheque_date']      = $receipt_chequedate;

			if(!empty($entry_id)){
				$this->db->table("entries")->where('id', $entry_id)->update($data);
				$insid = $entry_id;
				/* $entryitems_c  = array();
				$entryitems_c['details'] = $receipt_receivedfrom;
				$entryitems_c['amount'] = $tot_amt_input;
				$entryitems_c['clearancemode'] = 'FLOAT';
				$entryitems_c['reconciliation_date'] = NULL;
				$entryitems_c['flag_end'] = 0;
				$this->db->table('entryitems')->where('entry_id', $entry_id)->where('dc', 'D')->update($entryitems_c); */
			}
			else{
				$number = $this->db->table('entries')->select('number')->where('entrytype_id', $entry_type_id)->orderBy('id','desc')->get()->getRowArray(); 
				if(empty($number)) {
					$data['number'] = 1;
				} else {
					$data['number'] = $number['number'] + 1;
				}
				$this->db->table("entries")->insert($data);
        		$insid = $this->db->insertID();
				// CREDIT as of now DEBIT
				/* if(!empty($receipt_credit_ac))
				{
					$entryitems_c  = array();
					$entryitems_c['entry_id']  = $insid;
					$entryitems_c['ledger_id'] = $receipt_credit_ac;
					$entryitems_c['details'] = $receipt_receivedfrom;
					$entryitems_c['amount'] = $tot_amt_input;
					$entryitems_c['dc'] = "D";
					$this->db->table('entryitems')->insert($entryitems_c);
				} */
			}
			$this->db->table('entryitems')->delete(['entry_id' => $insid]);
			if(!empty($insid)){
				if(!empty($_POST['entries'])){
					if(!empty($receipt_credit_ac)){
						$entryitems_c  = array();
						$entryitems_c['entry_id']  = $insid;
						$entryitems_c['ledger_id'] = $receipt_credit_ac;
						$entryitems_c['details'] = $receipt_particulars;
						$entryitems_c['amount'] = $tot_amt_input;
						$entryitems_c['dc'] = "D";
						$this->db->table('entryitems')->insert($entryitems_c);
					}
					foreach($_POST['entries'] as $row)
					{
						$entryitems_d = array();
						$entryitems_d['entry_id']  = $insid;
						$entryitems_d['ledger_id'] = $row['ledgerid'];
						$entryitems_d['details'] = $receipt_particulars;
						$entryitems_d['amount'] = $row['amount'];
						$entryitems_d['dc'] = "C";
						/* if(empty($row['entryitemid']))
						{
							$this->db->table('entryitems')->insert($entryitems_d);
						}else{
							$this->db->table("entryitems")->where('id', $row['entryitemid'])->update($entryitems_d);
						} */
						$this->db->table('entryitems')->insert($entryitems_d);
					}
				}
				$msg_data['succ'] = 'Receipt Entry Modified Successfully';
              	$msg_data['id'] = $insid;
			}
			else{
            	$msg_data['err'] = 'Please Try Again';
        	}
		}
		else
		{
			$msg_data['err'] = 'Please Fill All Required Field';
		}
		echo json_encode($msg_data);
      	exit();
	}
	public function generatePaymentEntryCodeByDate()
	{
		helper('date'); // Load the date helper if needed

		// Get the date from POST request
		$date = $this->request->getPost('date');
		if (!$date) {
			return $this->response->setJSON(['error' => 'No date provided']);
		}

		$yr = date('Y', strtotime($date));
		$mon = date('m', strtotime($date));

		// Query to get the last entry code for the given date
		$uid = 2; // Example user ID or type ID, adjust as needed
		$query = $this->db->query("SELECT entry_code FROM entries WHERE id = (SELECT max(id) FROM entries WHERE YEAR(date) = '$yr' AND entrytype_id = $uid AND MONTH(date) = '$mon')")->getRowArray();

		$next_number = isset($query['entry_code']) ? ((float) substr($query['entry_code'], -5)) + 1 : 1;

		// $bill_no = 'PAY' . date('y', strtotime($date)) . $mon . sprintf("%05d", $next_number);

		// 
		if ($uid == 1)
			$bill_no = 'REC' . date('y', strtotime($date)) . $mon . sprintf("%05d", $next_number);
		if ($uid == 2)
			$bill_no = 'PAY' . date('y', strtotime($date)) . $mon . sprintf("%05d", $next_number);
		if ($uid == 3)
			$bill_no = 'CON' . date('y', strtotime($date)) . $mon . sprintf("%05d", $next_number);
		if ($uid == 4)
			$bill_no = 'JOR' . date('y', strtotime($date)) . $mon . sprintf("%05d", $next_number);
		return $this->response->setJSON(['entryCode' => $bill_no]);
	}

	public function payment_add(){
		$uid = 2;
        $yr=date('Y');
		$mon=date('m');
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$start_date = $ac_year['from_year_month'] . '-01';
		$end_date = date('Y-m-t', strtotime($ac_year['to_year_month'] . '-01'));
		$data['financial_year']['start_date'] = $start_date;
		$data['financial_year']['end_date'] = $end_date;
		$query   = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='". $yr ."' and entrytype_id = $uid and month (date)='". $mon ."')")->getRowArray();
        if($uid == 1) $bill_no = 'REC' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 2) $bill_no = 'PAY' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 3) $bill_no = 'CON' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 4) $bill_no = 'JOR' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        $data['entry_code'] = $bill_no;
		$data['ledgers'] = $this->db->query("SELECT * FROM `ledgers` where group_id not in (SELECT id FROM `groups` WHERE code in (4000, 8000) or parent_id in (SELECT id FROM `groups` WHERE code in (4000, 8000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (4000, 8000))))")->getResultArray();
		$data['bank_ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->where('type', 1)->get()->getResultArray();
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		$data['payment_mode'] = $this->db->table('payment_mode')->where("paid_through", "DIRECT")->where("expenses", 1)->where('status', 1)->get()->getResultArray();
		$data['en_id'] = $uid;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/payment_add',$data);
		echo view('template/footer');
	}
	public function payment_edit($id){
		$uid = 2;
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$start_date = $ac_year['from_year_month'] . '-01';
		$end_date = date('Y-m-t', strtotime($ac_year['to_year_month'] . '-01'));
		$data['financial_year']['start_date'] = $start_date;
		$data['financial_year']['end_date'] = $end_date;
        $entries = $this->db->query("SELECT * FROM entries WHERE id = $id")->getRowArray();
		$data['entries'] = $entries;
		$entry_id = $entries['id'];
		$data['en_id'] = $entries['entrytype_id'];
		$data['entry_code'] = $entries['entry_code'];
		$entrie_items = $this->db->query("SELECT * FROM entryitems WHERE entry_id = $entry_id and dc = 'D' ")->getResultArray();
		$data['entrie_items'] = $entrie_items;
		$data['credit_ledger'] = $this->db->query("SELECT ledger_id,details FROM entryitems WHERE entry_id = $entry_id and dc = 'C' ")->getRowArray();
		$data['ledgers'] = $this->db->query("SELECT * FROM `ledgers` where group_id not in (SELECT id FROM `groups` WHERE code in (4000, 8000) or parent_id in (SELECT id FROM `groups` WHERE code in (4000, 8000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (4000, 8000))))")->getResultArray();
		$data['bank_ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->where('type', 1)->get()->getResultArray();
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		$data['payment_mode'] = $this->db->table('payment_mode')->where("paid_through", "DIRECT")->where("expenses", 1)->where('status', 1)->get()->getResultArray();
		$data['en_id'] = $uid;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/payment_edit',$data);
		echo view('template/footer');
	}
	public function save_payment_entries(){
		//var_dump($_POST['entries']);
		//exit;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$entry_type_id = $_POST['entry_type_id'];
		$payment_date = $_POST['payment_date'];
		$payment_paymode = $_POST['payment_paymode'];
		$payment_debit_ac = $_POST['payment_debit_ac'];
		$payment_entrycode = $_POST['payment_entrycode'];
		$payment_receivedfrom = !empty($_POST['payment_receivedfrom']) ? $_POST['payment_receivedfrom'] : '';
		$fund_id = $_POST['fund_id'];

		$pay_method = $_POST['payment_paymode'];

		if($pay_method == "cheque")
		{
			$payment_chequeno = !empty($_POST['payment_chequeno']) ? $_POST['payment_chequeno'] : '';
			$payment_chequedate = !empty($_POST['payment_chequedate']) ? $_POST['payment_chequedate'] : '';
		}
		else if($pay_method == "online")
		{
			$payment_chequeno = !empty($_POST['payment_refno']) ? $_POST['payment_refno'] : '';
			$payment_chequedate = !empty($_POST['payment_transactiondate']) ? $_POST['payment_transactiondate'] : '';
		}
		else
		{
			$payment_chequeno = NULL;
			$payment_chequedate = NULL;
		}
		$payment_particulars = $_POST['payment_particulars'];
		$tot_amt_input = $_POST['tot_amt_input'];
		
		if( !empty($tot_amt_input) && !empty($pay_method) && !empty($payment_debit_ac) && !empty($_POST['entries']) && !empty($payment_entrycode) && !empty($fund_id) && !empty($payment_particulars) ) {
			$number = $this->db->table('entries')->select('number')->where('entrytype_id', $entry_type_id)->orderBy('id','desc')->get()->getRowArray(); 
			if(empty($number)) {
				$data['number'] = 1;
			} else {
				$data['number'] = $number['number'] + 1;
			}
			$data['entrytype_id']   = $entry_type_id;
			$data['payment']   = $pay_method;
			$data['date']           = $payment_date; 
			$data['dr_total']        = $tot_amt_input; 
			$data['cr_total']        = $tot_amt_input; 
			$data['narration']      = $payment_particulars;
			$data['entry_code']      = $payment_entrycode;
			$data['fund_id']      = $fund_id;
			$data['paid_to']      = $payment_receivedfrom; 
			$data['cheque_no']      = $payment_chequeno; 
			$data['cheque_date']      = $payment_chequedate;
			$this->db->table("entries")->insert($data);
        	$insid = $this->db->insertID();
			if(!empty($insid)){
				// DEBIT as of Now CREDIT
				if(!empty($payment_debit_ac))
				{
					$entryitems_c  = array();
					$entryitems_c['entry_id']  = $insid;
					$entryitems_c['ledger_id'] = $payment_debit_ac;
					$entryitems_c['details'] = $payment_particulars;
					$entryitems_c['amount'] = $tot_amt_input;
					$entryitems_c['dc'] = "C";
					$this->db->table('entryitems')->insert($entryitems_c);
				}
				if(!empty($_POST['entries'])){
					foreach($_POST['entries'] as $row)
					{
						$entryitems_d  = array();
						$entryitems_d['entry_id']  = $insid;
                    	$entryitems_d['ledger_id'] = $row['ledgerid'];
                    	$entryitems_d['details'] = $payment_particulars;
                    	$entryitems_d['amount'] = $row['amount'];
                    	$entryitems_d['dc'] = "D";
						$this->db->table('entryitems')->insert($entryitems_d);
					}
				}
				$msg_data['succ'] = 'Payment Entry Added Successfully';
              	$msg_data['id'] = $insid;
			}
			else{
            	$msg_data['err'] = 'Please Try Again';
        	}
		}
		else
		{
			$msg_data['err'] = 'Please Fill All Required Field';
		}
		echo json_encode($msg_data);
      	exit();
	}
	public function update_payment_entries(){
		// print_r($_POST);
		// exit;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$entry_id = $_POST['entry_id'];
		$entry_type_id = $_POST['entry_type_id'];
		$payment_date = $_POST['payment_date'];
		$pay_method = $_POST['payment_paymode'];
		$payment_debit_ac = $_POST['payment_debit_ac'];
		$payment_entrycode = $_POST['payment_entrycode'];
		$payment_receivedfrom = !empty($_POST['payment_receivedfrom']) ? $_POST['payment_receivedfrom'] : '';
		$fund_id = $_POST['fund_id'];

		if($pay_method == "cheque")
		{
			$payment_chequeno = !empty($_POST['payment_chequeno']) ? $_POST['payment_chequeno'] : '';
			$payment_chequedate = !empty($_POST['payment_chequedate']) ? $_POST['payment_chequedate'] : '';
		}
		else if($pay_method == "online")
		{
			$payment_chequeno = !empty($_POST['payment_refno']) ? $_POST['payment_refno'] : '';
			$payment_chequedate = !empty($_POST['payment_transactiondate']) ? $_POST['payment_transactiondate'] : '';
		}
		else
		{
			$payment_chequeno = NULL;
			$payment_chequedate = NULL;
		}
		$payment_particulars = $_POST['payment_particulars'];
		$tot_amt_input = $_POST['tot_amt_input'];
		
		if( !empty($tot_amt_input) && !empty($pay_method) && !empty($payment_debit_ac) && !empty($_POST['entries']) && !empty($payment_entrycode) && !empty($payment_particulars) && !empty($fund_id) ) {
			$data['entrytype_id']   = $entry_type_id;
			$data['payment']   = $pay_method;
			$data['date']           = $payment_date; 
			$data['dr_total']        = $tot_amt_input; 
			$data['cr_total']        = $tot_amt_input; 
			$data['narration']      = $payment_particulars;
			$data['entry_code']      = $payment_entrycode;
			$data['fund_id']      = $fund_id;
			$data['paid_to']      = $payment_receivedfrom; 
			$data['cheque_no']      = $payment_chequeno; 
			$data['cheque_date']      = $payment_chequedate;
			if(!empty($entry_id)){
				$this->db->table("entries")->where('id', $entry_id)->update($data);
				$insid = $entry_id;
				/* $entryitems_c  = array();
				$entryitems_c['details'] = $receipt_receivedfrom;
				$entryitems_c['amount'] = $tot_amt_input;
				$entryitems_c['clearancemode'] = 'FLOAT';
				$entryitems_c['reconciliation_date'] = NULL;
				$entryitems_c['flag_end'] = 0;
				$this->db->table('entryitems')->where('entry_id', $entry_id)->where('dc', 'C')->update($entryitems_c); */
			}
			else{
				$number = $this->db->table('entries')->select('number')->where('entrytype_id', $entry_type_id)->orderBy('id','desc')->get()->getRowArray(); 
				if(empty($number)) {
					$data['number'] = 1;
				} else {
					$data['number'] = $number['number'] + 1;
				}
				$this->db->table("entries")->insert($data);
        		$insid = $this->db->insertID();
			}
			$this->db->table('entryitems')->delete(['entry_id' => $insid]);
			if(!empty($insid)){
				// DEBIT as of Now CREDIT
				if(!empty($_POST['entries'])){
					if(!empty($payment_debit_ac)){
						$entryitems_c  = array();
						$entryitems_c['entry_id']  = $insid;
						$entryitems_c['ledger_id'] = $payment_debit_ac;
						$entryitems_c['details'] = $payment_particulars;
						$entryitems_c['amount'] = $tot_amt_input;
						$entryitems_c['dc'] = "C";
						$this->db->table('entryitems')->insert($entryitems_c);
					}
					foreach($_POST['entries'] as $row)
					{
						$entryitems_d  = array();
                    	$entryitems_d['details'] = $payment_particulars;
                    	$entryitems_d['amount'] = $row['amount'];
						/* if(empty($row['entryitemid']))
						{
							$entryitems_d['entry_id']  = $insid;
							$entryitems_d['ledger_id'] = $row['ledgerid'];
							$entryitems_d['dc'] = "D";
							$this->db->table('entryitems')->insert($entryitems_d);
						}else{
							$this->db->table("entryitems")->where('id', $row['entryitemid'])->update($entryitems_d);
						} */
						$entryitems_d['entry_id']  = $insid;
						$entryitems_d['ledger_id'] = $row['ledgerid'];
						$entryitems_d['dc'] = "D";
						$this->db->table('entryitems')->insert($entryitems_d);
					}
				}
				$msg_data['succ'] = 'Payment Entry Modified Successfully';
              	$msg_data['id'] = $insid;
			}
			else{
            	$msg_data['err'] = 'Please Try Again';
        	}
		}
		else
		{
			$msg_data['err'] = 'Please Fill All Required Field';
		}
		echo json_encode($msg_data);
      	exit();
	}
	public function payment_debit_ac()
	{
		$rtdebit_ac = $_POST['rtdebit_ac'];
		$reslt_data = $this->db->query("SELECT * FROM ledgers where id !=$rtdebit_ac and group_id not in (SELECT id FROM `groups` WHERE code in (4000, 8000) or parent_id in (SELECT id FROM `groups` WHERE code in (4000, 8000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (4000, 8000))))")->getResultArray();
		$html = "<option value='0'>Select credit a/c</option>";
		foreach($reslt_data as $res)
		{
			$html .= "<option value=".$res["id"].">" . $res["left_code"] . '/' . $res["right_code"] . " - ".$res["name"]."</option>";
		}
		echo $html;
	}

	public function journal_add(){
		$uid = 4;
        $yr=date('Y');
		$mon=date('m');
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$start_date = $ac_year['from_year_month'] . '-01';
		$end_date = date('Y-m-t', strtotime($ac_year['to_year_month'] . '-01'));
		$data['financial_year']['start_date'] = $start_date;
		$data['financial_year']['end_date'] = $end_date;
		$query   = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='". $yr ."' and entrytype_id = $uid and month (date)='". $mon ."')")->getRowArray();
        if($uid == 1) $bill_no = 'REC' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 2) $bill_no = 'PAY' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 3) $bill_no = 'CON' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 4) $bill_no = 'JOR' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        $data['entry_code'] = $bill_no;
		$data['ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->orWhere('type', '')->orWhere('type', NULL)->get()->getResultArray();
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		$data['en_id'] = $uid;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/journal_add',$data);
		echo view('template/footer');
	}
	public function journal_edit($id){
		$uid = 4;
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$start_date = $ac_year['from_year_month'] . '-01';
		$end_date = date('Y-m-t', strtotime($ac_year['to_year_month'] . '-01'));
		$data['financial_year']['start_date'] = $start_date;
		$data['financial_year']['end_date'] = $end_date;
        $entries = $this->db->query("SELECT * FROM entries WHERE id = $id")->getRowArray();
		$data['entries'] = $entries;
		$entry_id = $entries['id'];
		$data['en_id'] = $entries['entrytype_id'];
		$data['entry_code'] = $entries['entry_code'];
		$entrie_items = $this->db->query("SELECT * FROM entryitems WHERE entry_id = $entry_id")->getResultArray();
		$data['entrie_items'] = $entrie_items;
		$data['ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->orWhere('type', '')->orWhere('type', NULL)->get()->getResultArray();
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		$data['en_id'] = $uid;
		//print_r($entries);
		//die("r");
		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/journal_edit',$data);
		echo view('template/footer');
	}
	public function save_journal_entries(){
		//var_dump($_POST['entries']);
		//exit;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$entry_type_id = $_POST['entry_type_id'];
		$journal_date = $_POST['journal_date'];
		$journal_entrycode = $_POST['journal_entrycode'];
		$fund_id = $_POST['fund_id'];
		$input_total_debit_balance = $_POST['input_total_debit_balance'];
		$input_total_credit_balance = $_POST['input_total_credit_balance'];
		$entries = !empty($_POST['entries']) ? $_POST['entries'] : array();
		if(!empty($journal_date) && !empty($fund_id)){
			if(count($entries) > 0){
				if($input_total_debit_balance > 0 && $input_total_credit_balance > 0 && $input_total_debit_balance == $input_total_credit_balance) {
					$number = $this->db->table('entries')->select('number')->where('entrytype_id', $entry_type_id)->orderBy('id','desc')->get()->getRowArray(); 
					if(empty($number)) {
						$data['number'] = 1;
					} else {
						$data['number'] = $number['number'] + 1;
					}
					if(!empty($_POST['journal_particulars'])) {
						$data['narration']   = $_POST['journal_particulars'];
					}
					$data['entrytype_id']   = $entry_type_id;
					$data['date']           = $journal_date; 
					$data['dr_total']        = $input_total_debit_balance; 
					$data['cr_total']        = $input_total_credit_balance; 
					$data['entry_code']      = $journal_entrycode;
					$data['fund_id']      = $fund_id;
					$this->db->table("entries")->insert($data);
					$insid = $this->db->insertID();
					if(!empty($insid)){
						if(!empty($_POST['entries'])){
							foreach($_POST['entries'] as $row)
							{
								$entryitems_d['entry_id']  = $insid;
								$entryitems_d['ledger_id'] = $row['ledgerid'];
								if(!empty($data['narration'])) $entryitems_d['details'] = $data['narration'];
								if($row['dramt'] > 0)
								{
									$entryitems_d['amount'] = $row['dramt'];
									$entryitems_d['dc'] = "D";
								}
								if($row['cramt'] > 0)
								{
									$entryitems_d['amount'] = $row['cramt'];
									$entryitems_d['dc'] = "C";
								}
								$this->db->table('entryitems')->insert($entryitems_d);
							}
						}
						$msg_data['succ'] = 'Joural Entry Added Successfully';
						$msg_data['id'] = $insid;
					}
					else{
						$msg_data['err'] = 'Plese try again';
					}
				}
				else
				{
					$msg_data['err'] = 'Transaction not balanced';
				}
			}else{
				$msg_data['err'] = 'Add Ledgers (Credit/Debit) first';
			}
		}else{
			$msg_data['err'] = 'Please Fill All Required Field';
		}
		echo json_encode($msg_data);
      	exit();
	}
	public function update_journal_entries(){
		//var_dump($_POST['entries']);
		//exit;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$entry_id = $_POST['entry_id'];
		$entry_type_id = $_POST['entry_type_id'];
		$journal_date = $_POST['journal_date'];
		$journal_entrycode = $_POST['journal_entrycode'];
		$fund_id = $_POST['fund_id'];
		$input_total_debit_balance = $_POST['input_total_debit_balance'];
		$input_total_credit_balance = $_POST['input_total_credit_balance'];
		$entries = !empty($_POST['entries']) ? $_POST['entries'] : array();
		if(!empty($journal_date) && !empty($fund_id)){
			if(count($entries) > 0){
				if($input_total_debit_balance > 0 && $input_total_credit_balance > 0 && $input_total_debit_balance == $input_total_credit_balance) {
					$data['entrytype_id']   = $entry_type_id;
					$data['date']           = $journal_date; 
					$data['dr_total']        = $input_total_debit_balance; 
					$data['cr_total']        = $input_total_credit_balance; 
					$data['entry_code']      = $journal_entrycode;
					$data['fund_id']      = $fund_id;
					if(!empty($_POST['journal_particulars'])) {
						$data['narration']   = $_POST['journal_particulars'];
					}
					if(!empty($entry_id)){
						$this->db->table("entries")->where('id', $entry_id)->update($data);
						$insid = $entry_id;
						/* $entryitems_c  = array();
						$entryitems_c['details'] = $receipt_receivedfrom;
						$entryitems_c['amount'] = $tot_amt_input;
						$entryitems_c['clearancemode'] = 'FLOAT';
						$entryitems_c['reconciliation_date'] = NULL;
						$entryitems_c['flag_end'] = 0;
						$this->db->table('entryitems')->where('entry_id', $entry_id)->where('dc', 'C')->update($entryitems_c); */
					}
					else{
						$number = $this->db->table('entries')->select('number')->where('entrytype_id', $entry_type_id)->orderBy('id','desc')->get()->getRowArray(); 
						if(empty($number)) {
							$data['number'] = 1;
						} else {
							$data['number'] = $number['number'] + 1;
						}
						$this->db->table("entries")->insert($data);
						$insid = $this->db->insertID();
					}
					$this->db->table('entryitems')->delete(['entry_id' => $insid]);
					if(!empty($insid)){
						foreach($entries as $row)
						{
							$entryitems_d['entry_id']  = $insid;
							$entryitems_d['ledger_id'] = $row['ledgerid'];
							if(!empty($data['narration'])) $entryitems_d['details'] = $data['narration'];
							if($row['dramt'] > 0)
							{
								$entryitems_d['amount'] = $row['dramt'];
								$entryitems_d['dc'] = "D";
							}
							if($row['cramt'] > 0)
							{
								$entryitems_d['amount'] = $row['cramt'];
								$entryitems_d['dc'] = "C";
							}
							$this->db->table('entryitems')->insert($entryitems_d);
						}
						$msg_data['succ'] = 'Joural Entry Modified Successfully';
						$msg_data['id'] = $insid;
					}
					else{
						$msg_data['err'] = 'Please Try Again';
					}
				}
				else
				{
					$msg_data['err'] = 'Transaction not balanced';
				}
			}else{
				$msg_data['err'] = 'Add Ledgers (Credit/Debit) first';
			}
		}else{
			$msg_data['err'] = 'Please Fill All Required Field';
		}
		echo json_encode($msg_data);
      	exit();
	}
	public function getladgerName()
	{
		$ledger_id = $_POST['ledger_id'];
		$row_data = $this->db->table('ledgers')->where('id', $ledger_id)->get()->getRowArray();
		echo '(' . $row_data['left_code'] . '/' . $row_data['right_code'] . ") - ".$row_data['name'];
	}

	public function receipt_copy_edit($id){
		$uid = 1;
		$yr=date('Y');
		$mon=date('m');
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$start_date = $ac_year['from_year_month'] . '-01';
		$end_date = date('Y-m-t', strtotime($ac_year['to_year_month'] . '-01'));
		$data['financial_year']['start_date'] = $start_date;
		$data['financial_year']['end_date'] = $end_date;
        $entries = $this->db->query("SELECT * FROM entries WHERE id = $id")->getRowArray();
		$data['entries'] = $entries;
		$entry_id = $entries['id'];
		$entrie_items = $this->db->query("SELECT * FROM entryitems WHERE entry_id = $entry_id and dc = 'C' ")->getResultArray();
		$data['entrie_items'] = $entrie_items;
		$data['credit_ledger'] = $this->db->query("SELECT ledger_id,details FROM entryitems WHERE entry_id = $entry_id and dc = 'D' ")->getRowArray();
		$data['ledgers'] = $this->db->query("SELECT * FROM `ledgers` where group_id not in (SELECT id FROM `groups` WHERE code in (5000, 6000, 9000) or parent_id in (SELECT id FROM `groups` WHERE code in (5000, 6000, 9000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (5000, 6000, 9000))))")->getResultArray();
		$data['bank_ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->where('type', 1)->get()->getResultArray();
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		$query   = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='". $yr ."' and entrytype_id = $uid and month (date)='". $mon ."')")->getRowArray();
        if($uid == 1) $bill_no = 'REC' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 2) $bill_no = 'PAY' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 3) $bill_no = 'CON' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 4) $bill_no = 'JOR' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        $data['entry_code'] = $bill_no;
		$data['en_id'] = $uid;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/receipt_copy_edit',$data);
		echo view('template/footer');
	}
	public function save_receipt_copy_entries(){
		//var_dump($_POST['entries']);
		//exit;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$entry_id = $_POST['entry_id'];
		$entry_type_id = $_POST['entry_type_id'];
		$receipt_date = $_POST['receipt_date'];
		$receipt_paymode = $_POST['receipt_paymode'];
		$receipt_credit_ac = $_POST['receipt_credit_ac'];
		$receipt_entrycode = $_POST['receipt_entrycode'];
		$receipt_receivedfrom = !empty($_POST['receipt_receivedfrom']) ? $_POST['receipt_receivedfrom'] : '';
		$fund_id = $_POST['fund_id'];
		if($receipt_paymode == "cheque")
		{
			$receipt_chequeno = !empty($_POST['receipt_chequeno']) ? $_POST['receipt_chequeno'] : '';
			$receipt_chequedate = !empty($_POST['receipt_chequedate']) ? $_POST['receipt_chequedate'] : '';
		}
		else if($receipt_paymode == "online")
		{
			$receipt_chequeno = !empty($_POST['receipt_refno']) ? $_POST['receipt_refno'] : '';
			$receipt_chequedate = !empty($_POST['receipt_transactiondate']) ? $_POST['receipt_transactiondate'] : '';
		}
		else
		{
			$receipt_chequeno = NULL;
			$receipt_chequedate = NULL;
		}
		$receipt_particulars = $_POST['receipt_particulars'];
		$tot_amt_input = $_POST['tot_amt_input'];
		
		if( !empty($tot_amt_input) && !empty($receipt_paymode) && !empty($_POST['entries']) && !empty($receipt_entrycode) && !empty($fund_id) && !empty($receipt_particulars) ) {
			$data['entrytype_id']   = $entry_type_id;
			$data['payment']   = $receipt_paymode;
			$data['date']           = $receipt_date; 
			$data['dr_total']        = $tot_amt_input; 
			$data['cr_total']        = $tot_amt_input; 
			$data['narration']      = $receipt_particulars;
			$data['entry_code']      = $receipt_entrycode;
			$data['fund_id']      = $fund_id;
			$data['paid_to']      = $receipt_receivedfrom; 
			$data['cheque_no']      = $receipt_chequeno; 
			$data['cheque_date']      = $receipt_chequedate;

			if(!empty($entry_id)){
				$this->db->table("entries")->where('id', $entry_id)->update($data);
				$insid = $entry_id;
			}
			else{
				$number = $this->db->table('entries')->select('number')->where('entrytype_id', $entry_type_id)->orderBy('id','desc')->get()->getRowArray(); 
				if(empty($number)) {
					$data['number'] = 1;
				} else {
					$data['number'] = $number['number'] + 1;
				}
				$this->db->table("entries")->insert($data);
        		$insid = $this->db->insertID();
				// CREDIT as of now DEBIT
				if(!empty($receipt_credit_ac))
				{
					$entryitems_c['entry_id']  = $insid;
					$entryitems_c['ledger_id'] = $receipt_credit_ac;
					$entryitems_c['details'] = $receipt_particulars;
					$entryitems_c['amount'] = $tot_amt_input;
					$entryitems_c['dc'] = "D";
					$this->db->table('entryitems')->insert($entryitems_c);
				}
			}
			if(!empty($insid)){
				if(!empty($_POST['entries'])){
					foreach($_POST['entries'] as $row)
					{
						if(empty($row['entryitemid']))
						{
							$entryitems_d['entry_id']  = $insid;
							$entryitems_d['ledger_id'] = $row['ledgerid'];
							$entryitems_d['details'] = $receipt_particulars;
							$entryitems_d['amount'] = $row['amount'];
							$entryitems_d['dc'] = "C";
							$this->db->table('entryitems')->insert($entryitems_d);
						}
					}
				}
				$msg_data['succ'] = 'Receipt Entry Added Successfully';
              	$msg_data['id'] = $insid;
			}
			else{
            	$msg_data['err'] = 'Please Try Again';
        	}
		}
		else
		{
			$msg_data['err'] = 'Please Fill All Required Field';
		}
		echo json_encode($msg_data);
      	exit();
	}
	public function payment_copy_edit($id){
		$uid = 2;
		$yr=date('Y');
		$mon=date('m');
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$start_date = $ac_year['from_year_month'] . '-01';
		$end_date = date('Y-m-t', strtotime($ac_year['to_year_month'] . '-01'));
		$data['financial_year']['start_date'] = $start_date;
		$data['financial_year']['end_date'] = $end_date;
        $entries = $this->db->query("SELECT * FROM entries WHERE id = $id")->getRowArray();
		$data['entries'] = $entries;
		$entry_id = $entries['id'];
		$entrie_items = $this->db->query("SELECT * FROM entryitems WHERE entry_id = $entry_id and dc = 'D' ")->getResultArray();
		$data['entrie_items'] = $entrie_items;
		$data['credit_ledger'] = $this->db->query("SELECT ledger_id,details FROM entryitems WHERE entry_id = $entry_id and dc = 'C' ")->getRowArray();
		$data['ledgers'] = $this->db->query("SELECT * FROM `ledgers` where group_id not in (SELECT id FROM `groups` WHERE code in (4000, 8000) or parent_id in (SELECT id FROM `groups` WHERE code in (4000, 8000)) or parent_id in (select id from `groups` where parent_id in (SELECT id FROM `groups` WHERE code in (4000, 8000))))")->getResultArray();
		$data['bank_ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->where('type', 1)->get()->getResultArray();
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		$query   = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='". $yr ."' and entrytype_id = $uid and month (date)='". $mon ."')")->getRowArray();
        if($uid == 1) $bill_no = 'REC' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 2) $bill_no = 'PAY' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 3) $bill_no = 'CON' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 4) $bill_no = 'JOR' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        $data['entry_code'] = $bill_no;
		$data['en_id'] = $uid;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/payment_copy_edit',$data);
		echo view('template/footer');
	}
	public function save_payment_copy_entries(){
		//var_dump($_POST['entries']);
		//exit;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$entry_type_id = $_POST['entry_type_id'];
		$payment_date = $_POST['payment_date'];
		$payment_paymode = $_POST['payment_paymode'];
		$payment_debit_ac = $_POST['payment_debit_ac'];
		$payment_entrycode = $_POST['payment_entrycode'];
		$payment_receivedfrom = !empty($_POST['payment_receivedfrom']) ? $_POST['payment_receivedfrom'] : '';
		$fund_id = $_POST['fund_id'];
		if($payment_paymode == "cheque")
		{
			$payment_chequeno = !empty($_POST['payment_chequeno']) ? $_POST['payment_chequeno'] : '';
			$payment_chequedate = !empty($_POST['payment_chequedate']) ? $_POST['payment_chequedate'] : '';
		}
		else if($payment_paymode == "online")
		{
			$payment_chequeno = !empty($_POST['payment_refno']) ? $_POST['payment_refno'] : '';
			$payment_chequedate = !empty($_POST['payment_transactiondate']) ? $_POST['payment_transactiondate'] : '';
		}
		else
		{
			$payment_chequeno = NULL;
			$payment_chequedate = NULL;
		}
		$payment_particulars = $_POST['payment_particulars'];
		$tot_amt_input = $_POST['tot_amt_input'];
		
		if( !empty($tot_amt_input) && !empty($payment_paymode) && !empty($payment_debit_ac) && !empty($_POST['entries']) && !empty($payment_entrycode) && !empty($fund_id) && !empty($payment_particulars) ) {
			$number = $this->db->table('entries')->select('number')->where('entrytype_id', $entry_type_id)->orderBy('id','desc')->get()->getRowArray(); 
			if(empty($number)) {
				$data['number'] = 1;
			} else {
				$data['number'] = $number['number'] + 1;
			}
			$data['entrytype_id']   = $entry_type_id;
			$data['payment']   = $payment_paymode;
			$data['date']           = $payment_date; 
			$data['dr_total']        = $tot_amt_input; 
			$data['cr_total']        = $tot_amt_input; 
			$data['narration']      = $payment_particulars;
			$data['entry_code']      = $payment_entrycode;
			$data['fund_id']      = $fund_id;
			$data['paid_to']      = $payment_receivedfrom; 
			$data['cheque_no']      = $payment_chequeno; 
			$data['cheque_date']      = $payment_chequedate;
			$this->db->table("entries")->insert($data);
        	$insid = $this->db->insertID();
			if(!empty($insid)){
				// DEBIT as of Now CREDIT
				if(!empty($payment_debit_ac))
				{
					$entryitems_c  = array();
					$entryitems_c['entry_id']  = $insid;
					$entryitems_c['ledger_id'] = $payment_debit_ac;
					$entryitems_c['details'] = $payment_particulars;
					$entryitems_c['amount'] = $tot_amt_input;
					$entryitems_c['dc'] = "C";
					$this->db->table('entryitems')->insert($entryitems_c);
				}
				if(!empty($_POST['entries'])){
					foreach($_POST['entries'] as $row)
					{
						$entryitems_d  = array();
						$entryitems_d['entry_id']  = $insid;
                    	$entryitems_d['ledger_id'] = $row['ledgerid'];
                    	$entryitems_d['details'] = $payment_particulars;
                    	$entryitems_d['amount'] = $row['amount'];
                    	$entryitems_d['dc'] = "D";
						$this->db->table('entryitems')->insert($entryitems_d);
					}
				}
				$msg_data['succ'] = 'Payment Entry Added Successfully';
              	$msg_data['id'] = $insid;
			}
			else{
            	$msg_data['err'] = 'Please Try Again';
        	}
		}
		else
		{
			$msg_data['err'] = 'Please Fill All Required Field';
		}
		echo json_encode($msg_data);
      	exit();
	}
	public function journal_copy_edit($id){
		$uid = 4;
		$yr=date('Y');
		$mon=date('m');
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$start_date = $ac_year['from_year_month'] . '-01';
		$end_date = date('Y-m-t', strtotime($ac_year['to_year_month'] . '-01'));
		$data['financial_year']['start_date'] = $start_date;
		$data['financial_year']['end_date'] = $end_date;
        $entries = $this->db->query("SELECT * FROM entries WHERE id = $id")->getRowArray();
		$data['entries'] = $entries;
		$entry_id = $entries['id'];
		$entrie_items = $this->db->query("SELECT * FROM entryitems WHERE entry_id = $entry_id")->getResultArray();
		$data['entrie_items'] = $entrie_items;
		$data['ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->orWhere('type', '')->orWhere('type', NULL)->get()->getResultArray();
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		$data['en_id'] = $uid;
		$query   = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='". $yr ."' and entrytype_id = $uid and month (date)='". $mon ."')")->getRowArray();
        if($uid == 1) $bill_no = 'REC' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 2) $bill_no = 'PAY' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 3) $bill_no = 'CON' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 4) $bill_no = 'JOR' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        $data['entry_code'] = $bill_no;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/journal_copy_edit',$data);
		echo view('template/footer');
	}
	public function save_journal_copy_entries(){
		//var_dump($_POST['entries']);
		//exit;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$entry_type_id = $_POST['entry_type_id'];
		$journal_date = $_POST['journal_date'];
		$journal_entrycode = $_POST['journal_entrycode'];
		$fund_id = $_POST['fund_id'];
		$input_total_debit_balance = $_POST['input_total_debit_balance'];
		$input_total_credit_balance = $_POST['input_total_credit_balance'];
		$entries = !empty($_POST['entries']) ? $_POST['entries'] : array();
		if(!empty($journal_date) && !empty($fund_id)){
			if(count($entries) > 0){
				if($input_total_debit_balance > 0 && $input_total_credit_balance > 0 && $input_total_debit_balance == $input_total_credit_balance) {
					$number = $this->db->table('entries')->select('number')->where('entrytype_id', $entry_type_id)->orderBy('id','desc')->get()->getRowArray(); 
					if(empty($number)) {
						$data['number'] = 1;
					} else {
						$data['number'] = $number['number'] + 1;
					}
					if(!empty($_POST['journal_particulars'])) {
						$data['narration']   = $_POST['journal_particulars'];
					}
					$data['entrytype_id']   = $entry_type_id;
					$data['date']           = $journal_date; 
					$data['dr_total']        = $input_total_debit_balance; 
					$data['cr_total']        = $input_total_credit_balance; 
					$data['entry_code']      = $journal_entrycode;
					$data['fund_id']      = $fund_id;
					$this->db->table("entries")->insert($data);
					$insid = $this->db->insertID();
					if(!empty($insid)){
						if(!empty($_POST['entries'])){
							foreach($_POST['entries'] as $row)
							{
								$entryitems_d['entry_id']  = $insid;
								$entryitems_d['ledger_id'] = $row['ledgerid'];
								if(!empty($data['narration'])) $entryitems_d['details'] = $data['narration'];
								if($row['dramt'] > 0)
								{
									$entryitems_d['amount'] = $row['dramt'];
									$entryitems_d['dc'] = "D";
								}
								if($row['cramt'] > 0)
								{
									$entryitems_d['amount'] = $row['cramt'];
									$entryitems_d['dc'] = "C";
								}
								$this->db->table('entryitems')->insert($entryitems_d);
							}
						}
						$msg_data['succ'] = 'Joural Entry Added Successfully';
						$msg_data['id'] = $insid;
					}
					else{
						$msg_data['err'] = 'Plese try again';
					}
				}
				else
				{
					$msg_data['err'] = 'Transaction not balanced';
				}
			}else{
				$msg_data['err'] = 'Add Ledgers (Credit/Debit) first';
			}
		}else{
			$msg_data['err'] = 'Please Fill All Required Field';
		}
		echo json_encode($msg_data);
      	exit();
	}

	public function credit_note_add(){
		$uid = 5;
        $yr=date('Y');
		$mon=date('m');
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$start_date = $ac_year['from_year_month'] . '-01';
		$end_date = date('Y-m-t', strtotime($ac_year['to_year_month'] . '-01'));
		$data['financial_year']['start_date'] = $start_date;
		$data['financial_year']['end_date'] = $end_date;
		$query   = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='". $yr ."' and entrytype_id = $uid and month (date)='". $mon ."')")->getRowArray();
        if($uid == 1) $bill_no = 'REC' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 2) $bill_no = 'PAY' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 3) $bill_no = 'CON' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 4) $bill_no = 'JOR' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        if($uid == 5) $bill_no = 'CRN' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['entry_code'],-5))+1)));
        $data['entry_code'] = $bill_no;
		$data['ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->get()->getResultArray();
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		$data['en_id'] = $uid;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/credit_note_add',$data);
		echo view('template/footer');
	}
	public function credit_note_edit($id){
		$uid = 5;
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$start_date = $ac_year['from_year_month'] . '-01';
		$end_date = date('Y-m-t', strtotime($ac_year['to_year_month'] . '-01'));
		$data['financial_year']['start_date'] = $start_date;
		$data['financial_year']['end_date'] = $end_date;
        $entries = $this->db->query("SELECT * FROM entries WHERE id = $id")->getRowArray();
		$data['entries'] = $entries;
		$entry_id = $entries['id'];
		$data['entry_code'] = $entries['entry_code'];
		$entrie_items = $this->db->query("SELECT * FROM entryitems WHERE entry_id = $entry_id")->getResultArray();
		$data['entrie_items'] = $entrie_items;
		$data['ledgers'] = $this->db->table("ledgers")->select('id,name,code,left_code,right_code')->get()->getResultArray();
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		$data['en_id'] = $uid;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/credit_note_edit',$data);
		echo view('template/footer');
	}
	public function save_credit_note_entries(){
		//var_dump($_POST['entries']);
		//exit;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$entry_type_id = $_POST['entry_type_id'];
		$credit_note_date = $_POST['credit_note_date'];
		$credit_note_entrycode = $_POST['credit_note_entrycode'];
		$fund_id = $_POST['fund_id'];
		$input_total_debit_balance = $_POST['input_total_debit_balance'];
		$input_total_credit_balance = $_POST['input_total_credit_balance'];
		$entries = !empty($_POST['entries']) ? $_POST['entries'] : array();
		if(!empty($credit_note_date) && !empty($fund_id)){
			if(count($entries) > 0){
				if($input_total_debit_balance > 0 && $input_total_credit_balance > 0 && $input_total_debit_balance == $input_total_credit_balance) {
					$number = $this->db->table('entries')->select('number')->where('entrytype_id', $entry_type_id)->orderBy('id','desc')->get()->getRowArray(); 
					if(empty($number)) {
						$data['number'] = 1;
					} else {
						$data['number'] = $number['number'] + 1;
					}
					if(!empty($_POST['credit_note_particulars'])) {
						$data['narration']   = $_POST['credit_note_particulars'];
					}
					$data['entrytype_id']   = $entry_type_id;
					$data['date']           = $credit_note_date; 
					$data['dr_total']        = $input_total_debit_balance; 
					$data['cr_total']        = $input_total_credit_balance; 
					$data['entry_code']      = $credit_note_entrycode;
					$data['fund_id']      = $fund_id;

					$this->db->table("entries")->insert($data);
					$insid = $this->db->insertID();
					if(!empty($insid)){
						if(!empty($_POST['entries'])){
							foreach($_POST['entries'] as $row)
							{
								$entryitems_d['entry_id']  = $insid;
								$entryitems_d['ledger_id'] = $row['ledgerid'];
								if(!empty($data['narration'])) $entryitems_d['details'] = $data['narration'];
								if($row['dramt'] > 0)
								{
									$entryitems_d['amount'] = $row['dramt'];
									$entryitems_d['dc'] = "D";
								}
								if($row['cramt'] > 0)
								{
									$entryitems_d['amount'] = $row['cramt'];
									$entryitems_d['dc'] = "C";
								}
								$this->db->table('entryitems')->insert($entryitems_d);
							}
						}
						$msg_data['succ'] = 'Joural Entry Added Successfully';
						$msg_data['id'] = $insid;
					}
					else{
						$msg_data['err'] = 'Plese try again';
					}
				}
				else
				{
					$msg_data['err'] = 'Transaction not balanced';
				}
			}else{
				$msg_data['err'] = 'Add Ledgers (Credit/Debit) first';
			}
		}else{
			$msg_data['err'] = 'Please Fill All Required Field';
		}
		echo json_encode($msg_data);
      	exit();
	}
	public function update_credit_note_entries(){
		//var_dump($_POST['entries']);
		//exit;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		$entry_id = $_POST['entry_id'];
		$entry_type_id = $_POST['entry_type_id'];
		$credit_note_date = $_POST['credit_note_date'];
		$credit_note_entrycode = $_POST['credit_note_entrycode'];
		$fund_id = $_POST['fund_id'];
		$input_total_debit_balance = $_POST['input_total_debit_balance'];
		$input_total_credit_balance = $_POST['input_total_credit_balance'];
		$entries = !empty($_POST['entries']) ? $_POST['entries'] : array();
		if(!empty($credit_note_date) && !empty($fund_id)){
			if(count($entries) > 0){
				if($input_total_debit_balance > 0 && $input_total_credit_balance > 0 && $input_total_debit_balance == $input_total_credit_balance) {
					$data['entrytype_id']   = $entry_type_id;
					$data['date']           = $credit_note_date; 
					$data['dr_total']        = $input_total_debit_balance; 
					$data['cr_total']        = $input_total_credit_balance; 
					$data['entry_code']      = $credit_note_entrycode;
					$data['fund_id']      = $fund_id;
					if(!empty($_POST['credit_note_particulars'])) {
						$data['narration']   = $_POST['credit_note_particulars'];
					}
					if(!empty($entry_id)){
						$this->db->table("entries")->where('id', $entry_id)->update($data);
						$insid = $entry_id;
						/* $entryitems_c  = array();
						$entryitems_c['details'] = $receipt_receivedfrom;
						$entryitems_c['amount'] = $tot_amt_input;
						$entryitems_c['clearancemode'] = 'FLOAT';
						$entryitems_c['reconciliation_date'] = NULL;
						$entryitems_c['flag_end'] = 0;
						$this->db->table('entryitems')->where('entry_id', $entry_id)->where('dc', 'C')->update($entryitems_c); */
					}
					else{
						$number = $this->db->table('entries')->select('number')->where('entrytype_id', $entry_type_id)->orderBy('id','desc')->get()->getRowArray(); 
						if(empty($number)) {
							$data['number'] = 1;
						} else {
							$data['number'] = $number['number'] + 1;
						}
						$this->db->table("entries")->insert($data);
						$insid = $this->db->insertID();
					}
					$this->db->table('entryitems')->delete(['entry_id' => $insid]);
					if(!empty($insid)){
						foreach($entries as $row)
						{
							$entryitems_d['entry_id']  = $insid;
							$entryitems_d['ledger_id'] = $row['ledgerid'];
							if(!empty($data['narration'])) $entryitems_d['details'] = $data['narration'];
							if($row['dramt'] > 0)
							{
								$entryitems_d['amount'] = $row['dramt'];
								$entryitems_d['dc'] = "D";
							}
							if($row['cramt'] > 0)
							{
								$entryitems_d['amount'] = $row['cramt'];
								$entryitems_d['dc'] = "C";
							}
							$this->db->table('entryitems')->insert($entryitems_d);
						}
						$msg_data['succ'] = 'Joural Entry Modified Successfully';
						$msg_data['id'] = $insid;
					}
					else{
						$msg_data['err'] = 'Please Try Again';
					}
				}
				else
				{
					$msg_data['err'] = 'Transaction not balanced';
				}
			}else{
				$msg_data['err'] = 'Add Ledgers (Credit/Debit) first';
			}
		}else{
			$msg_data['err'] = 'Please Fill All Required Field';
		}
		echo json_encode($msg_data);
      	exit();
	}

	public function delete_page_enhanced($id = null)
	{
		if (!$this->model->permission_validate('entries_accounts', 'delete_p')) {
			return redirect()->to(base_url('/dashboard'));
		}

		if (!$id) {
			$id = $this->request->getUri()->getSegment(3);
		}

		$reason = $this->request->getPost('delete_reason') ?? '';

		if ($id) {
			// Get client information
			$ip_address = $this->request->getIPAddress();
			$user_agent = (string)$this->request->getUserAgent();
			$session_id = session_id();
			$deleted_by = $this->session->get('log_id');

			// Fetch complete entry data before deletion
			$entry_data = $this->db->table('entries')->where('id', $id)->get()->getRowArray();
			$entry_items_data = $this->db->table('entryitems')->where('entry_id', $id)->get()->getResultArray();

			if ($entry_data) {
				// Start transaction
				$this->db->transStart();

				try {
					// Store in deleted_entries table
					$deleted_entry_data = [
						'original_entry_id' => $id,
						'entry_data' => json_encode($entry_data),
						'entry_items_data' => json_encode($entry_items_data),
						'entry_type_id' => $entry_data['entrytype_id'],
						'entry_code' => $entry_data['entry_code'],
						'entry_date' => $entry_data['date'],
						'dr_total' => $entry_data['dr_total'],
						'cr_total' => $entry_data['cr_total'],
						'narration' => $entry_data['narration'],
						'payment_mode' => $entry_data['payment'] ?? null,
						'deleted_by' => $deleted_by,
						'deleted_reason' => $reason,
						'ip_address' => $ip_address,
						'user_agent' => $user_agent,
						'session_id' => $session_id,
						'deleted_at' => date('Y-m-d H:i:s'),
						'created_at' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s')
					];

					$this->db->table('deleted_entries')->insert($deleted_entry_data);

					// Delete from original tables (hard delete)
					$this->db->table('entryitems')->delete(['entry_id' => $id]);
					$this->db->table('entries')->delete(['id' => $id]);

					$this->db->transComplete();

					if ($this->db->transStatus() === false) {
						session()->setFlashdata('fail', 'Error occurred while deleting entry');
					} else {
						session()->setFlashdata('succ', 'Entry deleted successfully and archived');
					}
				} catch (Exception $e) {
					$this->db->transRollback();
					session()->setFlashdata('fail', 'Error: ' . $e->getMessage());
				}
			} else {
				session()->setFlashdata('fail', 'Entry not found');
			}
		} else {
			session()->setFlashdata('fail', 'Invalid entry ID');
		}

		return redirect()->to(base_url('/entries/list'));
	}

	/**
	 * Get deletion reasons for dropdown
	 */
	public function get_deletion_reasons()
	{
		$reasonsModel = new DeletionReasonsModel();
		$reasons = $reasonsModel->getActiveReasons();
		
		return $this->response->setJSON($reasons);
	}

	/**
	 * Deleted entries report
	 */
	public function deleted_entries_report()
	{
		if (!$this->model->list_validate('entries_accounts')) {
			return redirect()->to(base_url('/dashboard'));
		}

		$data['permission'] = $this->model->get_permission('entries_accounts');

		// Get filter parameters
		$filters = [
			'start_date' => $this->request->getGet('start_date'),
			'end_date' => $this->request->getGet('end_date'),
			'entry_type' => $this->request->getGet('entry_type'),
			'deleted_by' => $this->request->getGet('deleted_by')
		];

		// Get deleted entries with details
		$deletedEntriesModel = new DeletedEntriesModel();
		$data['deleted_entries'] = $deletedEntriesModel->getDeletedEntriesWithDetails($filters);

		// Get entry types for filter dropdown
		$entryTypesModel = new EntryTypesModel();
		$data['entry_types'] = $entryTypesModel->getActiveTypes();

		// Get users for filter dropdown (from login table)
		$loginModel = new LoginModel();
		$data['users'] = $loginModel->getUsersForDropdown();

		// Get deletion reasons
		$reasonsModel = new DeletionReasonsModel();
		$data['deletion_reasons'] = $reasonsModel->getActiveReasons();

		// Pass request object to view for filter values
		$data['request'] = $this->request;

		return view('template/header', $data)
			. view('template/sidebar', $data)
			. view('entries/deleted_entries_report', $data)
			. view('template/footer', $data);
	}

	/**
	 * Export deleted entries report
	 */
	public function export_deleted_entries()
	{
		if (!$this->model->list_validate('entries_accounts')) {
			return redirect()->to(base_url('/dashboard'));
		}

		// Get filter parameters
		$filters = [
			'start_date' => $this->request->getGet('start_date'),
			'end_date' => $this->request->getGet('end_date'),
			'entry_type' => $this->request->getGet('entry_type'),
			'deleted_by' => $this->request->getGet('deleted_by')
		];

		// Get deleted entries with details
		$deletedEntriesModel = new DeletedEntriesModel();
		$data['deleted_entries'] = $deletedEntriesModel->getDeletedEntriesWithDetails($filters);

		$this->response->setHeader('Content-Type', 'application/vnd.ms-excel');
		$this->response->setHeader('Content-Disposition', 'attachment; filename="deleted_entries_report_' . date('Y-m-d') . '.xls"');

		return view('entries/deleted_entries_export', $data);
	}

	/**
	 * View deleted entry details
	 */
	public function view_deleted_entry($id = null)
	{
		if (!$this->model->list_validate('entries_accounts')) {
			return redirect()->to(base_url('/dashboard'));
		}

		if (!$id) {
			$id = $this->request->getUri()->getSegment(3);
		}

		$builder = $this->db->table('deleted_entries de');
		$deleted_entry = $builder
			->select('de.*, et.name as entry_type_name, l.name as deleted_by_name, l.username as deleted_by_username')
			->join('entry_types et', 'et.id = de.entry_type_id', 'left')
			->join('login l', 'l.id = de.deleted_by', 'left') // Changed from users to login
			->where('de.id', $id)
			->get()
			->getRowArray();

		if (!$deleted_entry) {
			session()->setFlashdata('fail', 'Deleted entry not found');
			return redirect()->to(base_url('/entries/deleted_entries_report'));
		}

		// Decode JSON data
		$deleted_entry['entry_data_decoded'] = json_decode($deleted_entry['entry_data'], true);
		$deleted_entry['entry_items_data_decoded'] = json_decode($deleted_entry['entry_items_data'], true);

		$data['deleted_entry'] = $deleted_entry;

		return view('template/header', $data)
			. view('template/sidebar', $data)
			. view('entries/view_deleted_entry', $data)
			. view('template/footer', $data);
	}

	/**
	 * Print deleted entry
	 */
	public function print_deleted_entry($id = null)
	{
		if (!$id) {
			$id = $this->request->getUri()->getSegment(3);
		}

		$builder = $this->db->table('deleted_entries de');
		$deleted_entry = $builder
			->select('de.*, et.name as entry_type_name, l.name as deleted_by_name, l.username as deleted_by_username')
			->join('entry_types et', 'et.id = de.entry_type_id', 'left')
			->join('login l', 'l.id = de.deleted_by', 'left') // Changed from users to login
			->where('de.id', $id)
			->get()
			->getRowArray();

		if (!$deleted_entry) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$deleted_entry['entry_data_decoded'] = json_decode($deleted_entry['entry_data'], true);
		$deleted_entry['entry_items_data_decoded'] = json_decode($deleted_entry['entry_items_data'], true);

		$data['deleted_entry'] = $deleted_entry;

		// Get company/temple details
		$data['temple_details'] = $this->db->table('admin_profile')
										->where('id', session()->get('profile_id'))
										->get()
										->getRowArray();

		return view('entries/print_deleted_entry', $data);
	}

	/**
	 * Restore deleted entry (for soft delete)
	 */
	public function restore_entry($deleted_entry_id = null)
	{
		if (!$this->model->permission_validate('entries_accounts', 'create_p')) {
			return redirect()->to(base_url('/dashboard'));
		}

		if (!$deleted_entry_id) {
			$deleted_entry_id = $this->request->getUri()->getSegment(3);
		}

		if ($deleted_entry_id) {
			$deleted_entry = $this->db->table('deleted_entries')
									->where('id', $deleted_entry_id)
									->where('status', 'deleted')
									->get()
									->getRowArray();

			if ($deleted_entry) {
				$this->db->transStart();

				try {
					// Restore entry (if using soft delete)
					$this->db->table('entries')
							->where('id', $deleted_entry['original_entry_id'])
							->update([
								'is_deleted' => 0,
								'deleted_at' => null,
								'deleted_by' => null
							]);

					// Update deleted_entries status
					$this->db->table('deleted_entries')
							->where('id', $deleted_entry_id)
							->update([
								'status' => 'restored',
								'restored_by' => session()->get('id'), // Using login table ID
								'restored_at' => date('Y-m-d H:i:s'),
								'updated_at' => date('Y-m-d H:i:s')
							]);

					$this->db->transComplete();

					if ($this->db->transStatus() === false) {
						session()->setFlashdata('fail', 'Error occurred while restoring entry');
					} else {
						session()->setFlashdata('succ', 'Entry restored successfully');
					}
				} catch (Exception $e) {
					$this->db->transRollback();
					session()->setFlashdata('fail', 'Error: ' . $e->getMessage());
				}
			} else {
				session()->setFlashdata('fail', 'Deleted entry not found');
			}
		}

		return redirect()->to(base_url('/entries/deleted_entries_report'));
	}
	
}
