<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermissionModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Archanaibooking extends BaseController
{
    function __construct(){
        parent:: __construct();
        helper('url');
        helper('common_helper');
		$this->model = new PermissionModel();
        if( ($this->session->get('login') ) == false && $this->session->get('role') != 1){
            $data['dn_msg'] = 'Please Login';
            return redirect()->to('/login');
		}
    }
    
    public function index(){
	  //echo '<pre>';
      if(!$this->model->list_validate('archanai_ticket')){
			return redirect()->to(base_url().'/dashboard');}
	  $data['permission'] = $this->model->get_permission('archanai_ticket');
	  $data['staff'] = $this->db->table('staff')->where('is_admin',0)->get()->getResultArray();
	  $data['rasi'] = $this->db->table('rasi')->get()->getResultArray();
	  //echo"<pre>"; print_r($nat); die();
	  $data['nat'] = $this->db->table('natchathram')->get()->getResultArray();
	  $group = $this->db->query("SELECT * FROM archanai_group order by name asc")->getResultArray();
	  $data['archanai'][''] = $this->db->table('archanai')->where('groupname', '')->where('view_archanai', 1)->orderBy('order_no', 'ASC')->get()->getResultArray();
	  foreach($group as $row){ 
			$data['archanai'][$row['name']] = $this->db->table('archanai')->where('groupname', $row['name'])->where('view_archanai', 1)->orderBy('order_no', 'ASC')->get()->getResultArray();
	  }
      //$data['data'] = $this->db->table('archanai')->get()->getResultArray();
		$yr=date('Y');
		$mon=date('m');
		$query   = $this->db->query("SELECT ref_no FROM archanai_booking where id=(select max(id) from archanai_booking where year (date)='". $yr ."' and month (date)='". $mon ."')")->getRowArray();

      $data['bill_no']= 'AR' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['ref_no'],-5))+1)));
	  $data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->where('paid_through', 'DIRECT')->get()->getResultArray();
      echo view('template/header');
      echo view('template/sidebar');
      echo view('archanaibooking/index', $data);
      echo view('template/footer');
    }
	
	
    public function getbillno(){

		//echo strtotime($_POST['dt']);
      $yr= date('Y',strtotime($_POST['dt'])) ;
      $mon= date('m',strtotime($_POST['dt'])) ;
		  $query   = $this->db->query("SELECT ref_no FROM archanai_booking where id=(select max(id) from archanai_booking where year (date)='". $yr ."' and month (date)='". $mon ."')")->getRowArray();

      echo 'AR' .date('y',strtotime($_POST['dt'])).$mon. (sprintf("%05d",(((float)  substr($query['ref_no'],-5))+1)));      
    }
	
	 
    public function show_product(){
		$group = $this->db->query("SELECT * FROM archanai_group order by name asc")->getResultArray();
		$archanai[''] = $this->db->query("SELECT * FROM archanai WHERE groupname = '' and view_archanai = 1 and name_eng LIKE '%". $_POST['prod'] ."%' order by order_no asc")->getResultArray();
		foreach($group as $row){
				$name = $row['name']; 
				$archanai[$row['name']] = $this->db->query("SELECT * FROM archanai WHERE groupname = '$name' and view_archanai = 1 and name_eng LIKE '%". $_POST['prod'] ."%' order by order_no asc")->getResultArray();
		}
		//echo '<pre>'; print_r($archanai);
		/* $group = $this->db->query("SELECT groupname, COUNT(groupname) as cnt FROM archanai GROUP BY groupname HAVING COUNT(groupname) > 0")->getResultArray();
		foreach($group as $row){ 
			$name = $row['groupname'];
			$archanai[$row['groupname']] = $this->db->query("SELECT * FROM archanai WHERE groupname = '$name' and name_eng LIKE '%". $_POST['prod'] ."%' order by order_no asc")->getResultArray();
	  	}*/
		  foreach($archanai as $key => $value){
			if(!empty($value)){
				if(!empty($key)) { $val = $key; } else{  $val = '';}
				$tr_row[] = '<div class="col-md-12"><h4>'.$val.'</h4></div>';
				foreach($value as $row) {
					$tr_row[] .= '<div class="col-md-3" style="padding-left: 0px;">
									<div class="prod" id="prod'.$row['id'].'" data-id="prod'.$row['id'].'" onclick="addtocart('.$row['id'].')"><img src="'.base_url().'/uploads/archanai/'.$row['image'].'" width="200" height="80" alt="image" />
										<!--<div class="vl"></div>-->
										<div class="detail">
										<h5 id="nm_'.$row['id'].'" data-id="'.$row['id'].'"> '.$row['name_tamil'].' <br>'.$row['name_eng'].'</h5><h4 id="amt_'.$row['id'].'" data-id="'.($row['amount'] ).'" >RM '.number_format((float)($row['amount'] ), 2).'</h4>
										</div>
									</div>
								</div>';
				}
			}
		  }
      
      $data['row'] = $tr_row;
      // echo '<pre>';
      // print_r($tr_row);
      // die();
      echo json_encode($data);
      //echo $_POST['prod'];

    }
    public function save(){
		// echo '<pre>';
		// print_r( $_POST); exit;
		$cnt = $_POST['cnt'];
		$comission_to = $_POST['comission_to'];
		$rasi_id = $_POST['rasi_id'];
		$natchathra_id = $_POST['natchathra_id'];
		$name = $_POST['ar_name'];
		$tot_amt = $_POST['tot_amt'];
		$entered_amount = $_POST['entered_amount'];
		$payment_mode = $_POST['paymentmode'];
		//echo $comission_to; exit;
		$msg_data = array();
		$msg_data['err'] = '';
		$msg_data['succ'] = '';
		/*
		if(floatval($tot_amt) > floatval($entered_amount)){
			$this->session->setFlashdata('fail', 'Please enter equal or greater than total amount');
			$msg_data['err'] =  'Please enter equal or greater than total amount';
		}
		*/
		if($cnt==0 || $cnt == ''){
			$this->session->setFlashdata('fail', 'Please add atlease on item');
			$msg_data['err'] =  'Please add atlease on item';
			/* return redirect()->to(base_url()."/archanaibooking"); */
		}
		else if ($payment_mode==0 || $payment_mode == ''){
			$this->session->setFlashdata('fail', 'Please choose payment mode');
			$msg_data['err'] =  'Please choose payment mode';
		}
		else{
			// $yr=date('Y');
			// $mon=date('m');
			$yr= date('Y',strtotime($_POST['dt'])) ;
			$mon= date('m',strtotime($_POST['dt'])) ;
			$query   = $this->db->query("SELECT ref_no FROM archanai_booking where id=(select max(id) from archanai_booking where year (date)='". $yr ."' and month (date)='". $mon ."')")->getRowArray();

			$data['ref_no']= 'AR' .date('y',strtotime($_POST['dt'])).$mon. (sprintf("%05d",(((float)  substr($query['ref_no'],-5))+1)));
			
			//$data['date']		    =	date('Y-m-d');
			$data['date']			= date('Y-m-d',strtotime($_POST['dt']));
			
			//$data['ref_no']		=	$_POST['billno'];
			$data['entry_by']	    =	$this->session->get('log_id');
			$data['amount']       =	$_POST['tot_amt'];
			$data['created']      =	date('Y-m-d H:i:s');
			$data['rasi_id'] =   $rasi_id;
			$data['natchathra_id'] =   $natchathra_id;
			$data['comission_to'] =   $comission_to;
			$data['payment_mode'] =   $payment_mode;
				$tot_amt = 0;
				$com_amt = 0;
			$payment_mode_details = $this->db->table('payment_mode')->where('id', $data['payment_mode'])->get()->getRowArray();
			foreach ($_POST['arch'] as $arch)
			{
			$cash = $this->db->table('archanai')->where('id', $arch['id'])->get()->getRowArray();
			$data_arch_book['amount']  =	$cash['amount'];
			$data_arch_book['commision']  =	$cash['commission'];
			$amt = $arch['qty'] * $cash['amount'];
			$camt =  $arch['qty'] * $cash['commission'];
			$tot_amt  +=	$amt;
			$tot_camt  +=	$camt;
			}
			$data['amount'] = $tot_amt-$tot_camt;
			$data['comission'] = $tot_camt;
			
			$res = $this->db->table('archanai_booking')->insert($data);
			$arch_book_id=$this->db->insertID();
			if($res){
				// Debit ledger
				$sales_group = $this->db->table('groups')->where('name', 'Sales')->where('parent_id', 26)->get()->getRowArray();
				if(!empty($sales_group)){
					$sls_id = $sales_group['id'];
				}else{
					$sls1['parent_id'] = 26;
					$sls1['name'] = 'Sales';
					$sls1['code'] = '330';
					$sls1['added_by'] = $this->session->get('log_id');
					$led_ins1 = $this->db->table('groups')->insert($sls1);
					$sls_id = $this->db->insertID();
				}
				// Debit ledger
				$ledger1 = $this->db->table('ledgers')->where('name', 'ARCHANAI COLLECTION')->where('group_id', $sls_id)->get()->getRowArray();
				if(!empty($ledger1)){
					$dr_id = $ledger1['id'];
				}else{
					$led1['group_id'] = $sls_id;
					$led1['name'] = 'ARCHANAI COLLECTION';
					$led1['left_code'] = '7013';
					$led1['right_code'] = '000';
					$led1['op_balance'] = '0';
					$led1['op_balance_dc'] = 'D';
					$led_ins1 = $this->db->table('ledgers')->insert($led1);
					$dr_id = $this->db->insertID();
				}
			  $cash_amt = 0;
			  $com_amt = 0;
			 	foreach ($_POST['arch'] as $arch)
			  	{            
					$data_arch_book['archanai_booking_id']	 =	$arch_book_id;
					$data_arch_book['archanai_id']  =	$arch['id'];
					$data_arch_book['quantity']  =	$arch['qty'];
					$data_arch_book['created']  =	date('Y-m-d H:i:s');
					$cash = $this->db->table('archanai')->where('id', $arch['id'])->get()->getRowArray();
					$data_arch_book['amount']  =	$cash['amount'];
					$data_arch_book['commision']  =	$cash['commission'];
					$amt = $arch['qty'] * $cash['amount'];
					$camt =  $arch['qty'] * $cash['commission'];
					$data_arch_book['total_amount']  =	$amt-$camt;
					$data_arch_book['total_commision']  =	$camt;
					$cash_amt = $cash_amt + $amt;
					$com_amt = $com_amt + $camt;
					$res_2 = $this->db->table('archanai_booking_details')->insert($data_arch_book);
				//    $query = $this->db->query("SELECT * FROM your_table WHERE payment_mode = ?", [$payment_mode_details['name']]);
                //     echo $query;
				// 	exit;
					
					/*STOCK DEDECTION SECTION START */
					$archanai_dedection_data = $this->db->table('archanai')->where('id',$arch['id'])->get()->getRowArray();
					if(!empty($archanai_dedection_data['dedection_from_stock'])){
						if($archanai_dedection_data['dedection_from_stock'] == 1){
							$am_raw_items = $this->db->table("archanai_raw_material_items")
												->where("product_id", $arch['id'])
												->get()->getResultArray();
							if(count($am_raw_items) > 0)
							{
								$staff_row = $this->db->table('staff')->where('name','admin')->where('is_admin',1)->get()->getRowArray();
								$data_rtout['date'] = $_POST['dt'];
								$data_rtout['staff_name'] = empty($staff_row['id']) ? $staff_row['id'] : $comission_to;
								$query_out   = $this->db->query("SELECT invoice_no FROM stock_outward where id=(select max(id) from stock_outward where year (date)='". $yr ."' and month (date)='". $mon ."')")->getRowArray();
								$data_rtout['invoice_no']= 'AR' .date('y',strtotime($_POST['dt'])).$mon. (sprintf("%05d",(((float)  substr($query_out['invoice_no'],-5))+1)));
								$data_rtout['added_by'] 		= $this->session->get('log_id');
								$data_rtout['modified'] 		= date("Y-m-d H:i:s");
								$data_rtout['created'] 		= date("Y-m-d H:i:s");
								$this->db->table('stock_outward')->insert($data_rtout);
								$ins_id_rtout = $this->db->insertID(); 
								$tot_outward_list_amt = 0;
								foreach($am_raw_items as $pr_raw_item)
								{
									$tot_req_qty = $arch['qty'] * $pr_raw_item['qty'];
									$av_data_r = $this->db->table("raw_matrial_groups")->where("id", $pr_raw_item['raw_id'])->get()->getRowArray();
									$avl_stack_r['opening_stock'] = $av_data_r['opening_stock'] - $tot_req_qty;
									$this->db->table('raw_matrial_groups')->where('id', $pr_raw_item['raw_id'])->update($avl_stack_r);

									$uom_item_data = $this->db->table('raw_matrial_groups')->where('id',$pr_raw_item['raw_id'])->get()->getRowArray();
									$uom_id = $uom_item_data['uom_id'];
									$item_raw_name = $uom_item_data['name'];
									$item_raw_rate = $uom_item_data['price'];
									$item_raw_qty = $tot_req_qty;
									$item_raw_amt = $uom_item_data['price'] * $tot_req_qty;
									$data_rtout_list['stack_out_id']= $ins_id_rtout;
									$data_rtout_list['item_type'] 	= 2;
									$data_rtout_list['item_id'] 	= $pr_raw_item['raw_id'];
									$data_rtout_list['item_name'] 	= $item_raw_name;
									$data_rtout_list['uom_id'] 		= $uom_id;
									$data_rtout_list['rate'] 		= $item_raw_rate;
									$data_rtout_list['quantity'] 	= $item_raw_qty;
									$data_rtout_list['amount'] 		= $item_raw_amt;
									$data_rtout_list['dedection_from'] 	= "Archanai";
									$data_rtout_list['dedection_id'] 	= $pr_raw_item['product_id'];
									$data_rtout_list['created'] 	= date("Y-m-d H:i:s");
									$data_rtout_list['modified'] 	= date("Y-m-d H:i:s");
									$this->db->table('stock_outward_list')->insert($data_rtout_list);
									$tot_outward_list_amt = $tot_outward_list_amt + $item_raw_amt;
								}
								$this->db->table('stock_outward')->where('id', $ins_id_rtout)->update(array("total_amount"=>$tot_outward_list_amt));
							}
						}
					}
					/*STOCK DEDECTION SECTION END */
					
			  	}
			  //print_r($_POST);
			  if(!empty($_POST['rasi'])){
				  foreach ($_POST['rasi'] as $rasi) 
				  {
				  $data_arch_rasi['archanai_booking_id']	 =	$arch_book_id;
				  $data_arch_rasi['name'] =   $rasi['arc_name'];
				  $data_arch_rasi['rasi_id'] =  $rasi['rasi_ids'];
				  $data_arch_rasi['natchathram_id'] =   $rasi['natchathra_ids'];
				  //echo "<pre>"; print_r($data_arch_rasi); exit();
				  $res_3 = $this->db->table('archanai_booking_rasi')->insert($data_arch_rasi);
				  } 
			  }
			if(!empty($_POST['vehicle'])){
				foreach ($_POST['vehicle'] as $vehicle) 
				{
					$data_arch_vehicle['archanai_booking_id']	 =	$arch_book_id;
					$data_arch_vehicle['name'] =   $vehicle['vle_name'];
					$data_arch_vehicle['vehicle_no'] =  $vehicle['vle_no'];
					$this->db->table('archanai_booking_vehicle')->insert($data_arch_vehicle);
				} 
			}
			  
			  //echo "<pre>"; print_r($res_3); exit();
			  
			  $number = $this->db->table('entries')->select('number')->where('entrytype_id',1)->orderBy('id','desc')->get()->getRowArray(); 
				if(empty($number)) {
					$num = 1;
				} else {
					$num = $number['number'] + 1;
				}
				$qry   = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='". $yr ."' and entrytype_id =1 and month (date)='". $mon ."')")->getRowArray();
				$entries['entry_code'] = 'REC' .date('y',strtotime($_POST['dt'])).$mon. (sprintf("%05d",(((float)  substr($qry['entry_code'],-5))+1)));
				
				$entries['entrytype_id'] = '1';
				$entries['number'] 		 = $num;
				$entries['date'] 		 = $data['date'];
								
				$entries['dr_total'] 	 = $cash_amt;
				$entries['cr_total'] 	 = $cash_amt;	
				$entries['narration'] 	 = 'Archanai Booking(' . $data['ref_no'] . ')';
				$entries['inv_id']		 = $arch_book_id;
				$entries['type']		 = '3';
				$ent = $this->db->table('entries')->insert($entries);
				$en_id = $this->db->insertID();
			
			if(!empty($en_id) ){
				$eitems_d['entry_id'] = $en_id;
				$eitems_d['ledger_id'] = $dr_id;
				$eitems_d['amount'] = $cash_amt;
				$eitems_d['details'] = 'Archanai Booking(' . $data['ref_no'] . ')';
				$eitems_d['dc'] = 'C';
				$this->db->table('entryitems')->insert($eitems_d);

				$eitems_c['entry_id'] = $en_id;
				$eitems_c['ledger_id'] = $payment_mode_details['ledger_id'];
				$eitems_c['details'] = 'Archanai Booking(' . $data['ref_no'] . ')';
				/* if ($comission_to!=0)
					$eitems_c['amount'] = $cash_amt-$com_amt;
				else */
					$eitems_c['amount'] = $cash_amt;
				$eitems_c['dc'] = 'D';
				$this->db->table('entryitems')->insert($eitems_c);
				
				/* if ($comission_to!=0)
				{
					$com_led = $this->db->table('ledgers')->where('name', 'Commission Ledger')->where('group_id', 13)->get()->getRowArray();
					if(!empty($com_led)){
						$com_led_id = $com_led['id'];
					}else{
						$com_led_data['group_id'] = 13;
						$com_led_data['name'] = 'Commission Ledger';
						$com_led_data['op_balance'] = '0';
						$com_led_data['op_balance_dc'] = 'D';
						$com_led_ins = $this->db->table('ledgers')->insert($com_led_data);
						$com_led_id = $this->db->insertID();
					}
					
					$eitems_com['entry_id'] = $en_id;
					$eitems_com['ledger_id'] = $com_led_id;
					$eitems_com['amount'] = $com_amt;
					$eitems_com['dc'] = 'D';
					$this->db->table('entryitems')->insert($eitems_com);
					
				} */
				
			}
			
			if($res_2){
				if ($comission_to!=0)
				{
					 $this->db->query("update staff set commission_amt=commission_amt+$com_amt where id=$comission_to ");
	
				}
				$this->session->setFlashdata('succ', 'Archanai Booking Added Successfully');
				$msg_data['succ'] = 'Archanai Booking Added Successfully';
				$msg_data['id'] = $arch_book_id;
			}else{
				$this->session->setFlashdata('fail', 'Please Try Again');
				$msg_data['err'] = 'Please Try Again';
				// return redirect()->to(base_url()."/archanaibooking");
			}
		  }
		}
		echo json_encode($msg_data);
		exit();
    }
	 
	public function print_booking($arch_book_id){
		 
	 	$id = $this->request->uri->getSegment(3);

		$data['qry1'] = $this->db->table('archanai_booking')->where('id', $id)->get()->getRowArray();
		//$data['qry2'] = $this->db->table('archanai_booking_details')->where('archanai_booking_id', $id)->get()->getResultArray();
		//echo "<pre>"; print_r($id); exit();
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();

		$data['booking'] = $this->db->table('archanai_booking_details', 'archanai', 'archanai_booking_rasi', 'rasi', 'natchathram')
					->join('archanai', 'archanai.id = archanai_booking_details.archanai_id', 'left')
					->where('archanai_booking_details.archanai_booking_id', $id )
					->select('archanai.*')
					->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
					->get()
					->getResultArray();
		$data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
					->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
					->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
					->where('archanai_booking_rasi.archanai_booking_id', $id )
					->select('archanai_booking_rasi.*')
					->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
					->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
					->get()
					->getResultArray();
		$data['vehicles'] = $this->db->table('archanai_booking_vehicle')
					->where('archanai_booking_vehicle.archanai_booking_id', $id )
					->select('archanai_booking_vehicle.*')
					->get()
					->getResultArray();
					//echo $this->db->getLastQuery();
		//echo "<pre>"; print_r($data); exit();
		$url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
		$data['qrcdoee'] = qrcode_generation($id,$url, 95, 95);
		echo view('archanaibooking/print', $data);
	}
    public function print_booking_kzhanji($arch_book_id){
		 
		$id = $this->request->uri->getSegment(3);
		
	   $data['qry1'] = $this->db->table('archanai_booking')->where('id', $id)->get()->getRowArray();
	   //$data['qry2'] = $this->db->table('archanai_booking_details')->where('archanai_booking_id', $id)->get()->getResultArray();
	   //echo "<pre>"; print_r($id); exit();
	   $tmpid = $this->session->get('profile_id');
	   $data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
	   
	   $data['booking'] = $this->db->table('archanai_booking_details', 'archanai')
				   ->join('archanai', 'archanai.id = archanai_booking_details.archanai_id', 'left')
				   ->where('archanai_booking_details.archanai_booking_id', $id )
				   ->select('archanai.*')
				   ->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
				   ->get()
				   ->getResultArray();
	   echo view('archanaibooking/print_kalanjiam', $data);
	}
     public function print_booking_sep($arch_book_id){
		 
	 	$id = $this->request->uri->getSegment(3);

		$data['qry1'] = $this->db->table('archanai_booking')->where('id', $id)->get()->getRowArray();
		//$data['qry2'] = $this->db->table('archanai_booking_details')->where('archanai_booking_id', $id)->get()->getResultArray();
		//echo "<pre>"; print_r($id); exit();
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();

		$data['booking'] = $this->db->table('archanai_booking_details', 'archanai')
					->join('archanai', 'archanai.id = archanai_booking_details.archanai_id')
					->where('archanai_booking_details.archanai_booking_id', $id )
					->select('archanai.*')
					->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
					->get()
					->getResultArray();
					//echo $this->db->getLastQuery();
		$data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
					->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
					->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
					->where('archanai_booking_rasi.archanai_booking_id', $id )
					->select('archanai_booking_rasi.*')
					->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
					->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
					->get()
					->getResultArray();
		$data['vehicles'] = $this->db->table('archanai_booking_vehicle')
					->where('archanai_booking_vehicle.archanai_booking_id', $id )
					->select('archanai_booking_vehicle.*')
					->get()
					->getResultArray();
		//echo "<pre>"; print_r($data); exit();
		$url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
		$data['qrcdoee'] = qrcode_generation($id,$url, 95, 95);
		echo view('archanaibooking/print_sep', $data);
	}
	public function print_booking1($arch_book_id){
		 
	 	$id = $this->request->uri->getSegment(3);

		$data['qry1'] = $this->db->table('archanai_booking')->where('id', $id)->get()->getRowArray();
		//$data['qry2'] = $this->db->table('archanai_booking_details')->where('archanai_booking_id', $id)->get()->getResultArray();
		//echo "<pre>"; print_r($id); exit();
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();

		$data['booking'] = $this->db->table('archanai_booking_details', 'archanai', 'archanai_booking_rasi', 'rasi', 'natchathram')
					->join('archanai', 'archanai.id = archanai_booking_details.archanai_id', 'left')
					->where('archanai_booking_details.archanai_booking_id', $id )
					->select('archanai.*')
					->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
					->get()
					->getResultArray();
		$data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
					->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
					->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
					->where('archanai_booking_rasi.archanai_booking_id', $id )
					->select('archanai_booking_rasi.*')
					->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
					->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
					->get()
					->getResultArray();
					//echo $this->db->getLastQuery();
		//echo "<pre>"; print_r($data); exit();
		echo view('archanaibooking/print1', $data);
	 }
     
     public function print_booking_sep1($arch_book_id){
		 
	 	$id = $this->request->uri->getSegment(3);

		$data['qry1'] = $this->db->table('archanai_booking')->where('id', $id)->get()->getRowArray();
		//$data['qry2'] = $this->db->table('archanai_booking_details')->where('archanai_booking_id', $id)->get()->getResultArray();
		//echo "<pre>"; print_r($id); exit();
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();

		$data['booking'] = $this->db->table('archanai_booking_details', 'archanai')
					->join('archanai', 'archanai.id = archanai_booking_details.archanai_id')
					->where('archanai_booking_details.archanai_booking_id', $id )
					->select('archanai.*')
					->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
					->get()
					->getResultArray();
					//echo $this->db->getLastQuery();
		$data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
					->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
					->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
					->where('archanai_booking_rasi.archanai_booking_id', $id )
					->select('archanai_booking_rasi.*')
					->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
					->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
					->get()
					->getResultArray();
		//echo "<pre>"; print_r($data); exit();
		echo view('archanaibooking/print_sep1', $data);
	}
	
	
	public function get_natchathram() {
	    $rasi_id = $_POST['rasi_id'];
		$res = $this->db->table('rasi')->where('id',$rasi_id)->get()->getRowArray();
		if(!empty($res['natchathra_id']))
		{
			$data = array("natchathra_id"=>$res['natchathra_id'], "rasi_id"=>$res['rasi_id']);
		}
		else
		{
			$res_natchathrams =  $this->db->table('natchathram')->get()->getResultArray();
			$data_bf = array();
			foreach($res_natchathrams as $res_natchathram)
			{
				$data_bf[] = $res_natchathram['id'];
			}
			$dataip = implode(',', $data_bf);
			$data = array("natchathra_id"=>$dataip, "rasi_id"=>$res['rasi_id']);
		}
		echo json_encode($data);
		exit;
	}
	public function get_natchathram_name() {
	    $id = $_POST['id'];
	    /*if(!empty($id)) {
	        $res =  $this->db->table('natchathram')->where('id',$id)->get()->getRowArray();
	    } else {
	        $res =  $this->db->table('natchathram')->get()->getResultArray();
	    }*/
		$res =  $this->db->table('natchathram')->where('id',$id)->get()->getRowArray();
		$data = array("id"=>$res['id'], "name_eng"=>$res['name_eng']);
		echo json_encode($data);
		exit;
	}
	
	public function new_booking(){
	  $data['permission'] = $this->model->get_permission('archanai_ticket');
	  $data['staff'] = $this->db->table('staff')->get()->getResultArray();
	  $data['rasi'] = $this->db->table('rasi')->get()->getResultArray();
	  //echo"<pre>"; print_r($nat); die();
	  $data['nat'] = $this->db->table('natchathram')->get()->getResultArray();
	  $group = $this->db->query("SELECT * FROM archanai_group order by name asc")->getResultArray();
	  $data['archanai'][''] = $this->db->table('archanai')->where('groupname', '')->where('view_archanai', 1)->orderBy('order_no', 'ASC')->get()->getResultArray();
	  foreach($group as $row){ 
			$data['archanai'][$row['name']] = $this->db->table('archanai')->where('groupname', $row['name'])->where('view_archanai', 1)->orderBy('order_no', 'ASC')->get()->getResultArray();
	  }
      //$data['data'] = $this->db->table('archanai')->get()->getResultArray();
		$yr=date('Y');
		$mon=date('m');
		$query   = $this->db->query("SELECT ref_no FROM archanai_booking where id=(select max(id) from archanai_booking where year (date)='". $yr ."' and month (date)='". $mon ."')")->getRowArray();

      $data['bill_no']= 'AR' .date('y').$mon. (sprintf("%05d",(((float)  substr($query['ref_no'],-5))+1)));
	  $data['payment_modes'] = $this->db->table('payment_mode')->where('status', 1)->where('paid_through', 'DIRECT')->get()->getResultArray();
	  
	  echo view('template/header');
      echo view('template/sidebar');
      echo view('archanaibooking/new_booking', $data);
      echo view('template/footer');
    }
    
    public function show_product1(){
		$group = $this->db->query("SELECT * FROM archanai_group order by name asc")->getResultArray();
		$archanai[''] = $this->db->query("SELECT * FROM archanai WHERE groupname = '' and view_archanai = 1 and name_eng LIKE '%". $_POST['prod'] ."%' order by order_no asc")->getResultArray();
		foreach($group as $row){
				$name = $row['name']; 
				$archanai[$row['name']] = $this->db->query("SELECT * FROM archanai WHERE groupname = '$name' and view_archanai = 1 and name_eng LIKE '%". $_POST['prod'] ."%' order by order_no asc")->getResultArray();
		}
		//echo '<pre>'; print_r($archanai);
		/* $group = $this->db->query("SELECT groupname, COUNT(groupname) as cnt FROM archanai GROUP BY groupname HAVING COUNT(groupname) > 0")->getResultArray();
		foreach($group as $row){ 
			$name = $row['groupname'];
			$archanai[$row['groupname']] = $this->db->query("SELECT * FROM archanai WHERE groupname = '$name' and name_eng LIKE '%". $_POST['prod'] ."%' order by order_no asc")->getResultArray();
	  	}*/
		  foreach($archanai as $key => $value){
			if(!empty($value)){
				if(!empty($key)) { $val = $key; } else{  $val = '';}
				$tr_row[] = '<div class="col-md-12"><h4>'.$val.'</h4></div>';
				foreach($value as $row) {
					$tr_row[] .= '<div class="col-md-4 col-lg-3 col-sm-6 col-xs-12" style="padding-left: 0px;">
                                        <div class="prod" id="prod'.$row['id'].'" data-id="prod'.$row['id'].'" onclick="addtocart('.$row['id'].')"><img src="'.base_url().'/uploads/archanai/'.$row['image'].'" width="200" height="80" alt="image" />
                                            <div class="detail">
                                                <h5 id="nm_'.$row['id'].'" data-id="'.$row['id'].'"> '.$row['name_tamil'].' <br>'.$row['name_eng'].'</h5><h4 id="amt_'.$row['id'].'" data-id="'.($row['amount'] ).'" >RM '.number_format((float)($row['amount'] ), 2).'</h4>
                                            </div>
                                        </div>
                                  </div>';
				}
			}
		  }
      
      $data['row'] = $tr_row;
      // echo '<pre>';
      // print_r($tr_row);
      // die();
      echo json_encode($data);
      //echo $_POST['prod'];

    }


	public function print_qrcode()
	{
		
	}
	
	public function refund_report()
	{
		$fdate = !empty($_REQUEST['fdate']) ? $_REQUEST['fdate'] : date('Y-m-01');
		$tdate = !empty($_REQUEST['tdate']) ? $_REQUEST['tdate'] : date('Y-m-d');
		$user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : [];
	    $builder = $this->db->table('archanai_booking ab')->select('ab.*, pm.name as payment_mode_name, pm.id as payment_mode_id, abgd.reference_id')->join('archanai_payment_gateway_datas abgd', 'abgd.archanai_booking_id = ab.id', 'left')->join('payment_mode pm', 'pm.id = abgd.payment_mode', 'left')->where("date BETWEEN '$fdate' AND '$tdate'")->where('payment_status', 3)->where("is_refund",1)->orderBy('ab.date', 'DESC');
		if(!empty($user_id)){
			$builder->whereIn('ab.entry_by', $user_id);
		}
		$data['list'] = $builder->get()->getResultArray();
		$data['users'] = $this->db->table('login')->select('id,name,member_comes')->where('member_comes', 'counter')->get()->getResultArray();
		$data['fdate'] = $fdate;
		$data['tdate'] = $tdate;
		$data['user_id'] = $user_id;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('archanaibooking/archanai_refund', $data);
		echo view('template/footer');
	}
	public function refund_print($arch_book_id=0){
			global $lang;
		if (!$this->model->list_validate('archanai_report')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data = array();
		$from_date = !empty($_REQUEST['fdt']) ? $_REQUEST['fdt'] : date('Y-m-01');
		$to_date = !empty($_REQUEST['tdt']) ? $_REQUEST['tdt'] : date('Y-m-m');
		$user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : [];
	    $builder = $this->db->table('archanai_booking ab')->select('ab.*, pm.name as payment_mode_name, pm.id as payment_mode_id, abgd.reference_id')->join('archanai_payment_gateway_datas abgd', 'abgd.archanai_booking_id = ab.id', 'left')->join('payment_mode pm', 'pm.id = abgd.payment_mode', 'left')->where("date BETWEEN '$from_date' AND '$to_date'")->where('payment_status', 3)->where("is_refund",1)->orderBy('ab.date', 'DESC');
		if(!empty($user_id)){
			$builder->whereIn('ab.entry_by', $user_id);
		}
		$archanai_datas = $builder->get()->getResultArray();;
		$data['data'] = $archanai_datas;
		$data['fdate'] = $from_date;
		$data['tdate'] = $to_date;
		$i = 1;
		if ($_REQUEST['pdf_archanairefund'] == "PDF") {
			// $file_name = "Archanai_Refund_Report_" . $data['fdate'] . "_to_" . $data['tdate'];
			$file_name = "Archanai_Refund_Report";
			$dompdf = new \Dompdf\Dompdf();
			$options = $dompdf->getOptions();
			$options->set(array('isRemoteEnabled' => true));
			$dompdf->setOptions($options);
			$dompdf->loadHtml(view('archanaibooking/archanai_refund_pdf', ["pdfdata" => $data]), 'UTF-8');
			$dompdf->setPaper('LEGAL', 'portrait');
			$dompdf->render();
			$dompdf->stream($file_name);
		} elseif ($_REQUEST['excel_archanairefund'] == "EXCEL") {
			// $fileName = "Archanai_Refund_Report_" . $data['fdate'] . "_to_" . $data['tdate'];
			$fileName = "Archanai_Refund_Report";
			$spreadsheet = new Spreadsheet();

			$sheet = $spreadsheet->getActiveSheet();
			$sheet->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$style = array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				)
			);

			$sheet->getStyle("A1:F1")->applyFromArray($style);
			$sheet->mergeCells('A1:F1');
			$sheet->setCellValue('A1', $_SESSION['site_title']);
			$sheet->setCellValue('A2', $lang->sno);
			$sheet->setCellValue('B2', $lang->date);
			$sheet->setCellValue('C2', $lang->bill_no);
			$sheet->setCellValue('D2', $lang->booked . ' ' . $lang->by);
			$sheet->setCellValue('E2', $lang->payment);
			$sheet->setCellValue('F2', $lang->amount);
			$rows = 3;
			$si = 1;
			$tot = 0;
			if(count($archanai_datas) > 0){
				$ii = 0;
				foreach ($archanai_datas as $val) {
				    $tot += floatval($val['amount']);
					$sheet->setCellValue('A' . $rows, ++$ii);
					$sheet->setCellValue('B' . $rows, $val['date']);
					$sheet->setCellValue('C' . $rows, $val['ref_no']);
					$sheet->setCellValue('D' . $rows, $val['paid_through']);
					$sheet->setCellValue('E' . $rows, $val['payment_mode_name']);
					$sheet->setCellValue('F' . $rows, $val['amount']);
					$sheet->getStyle('F' . $rows)->getNumberFormat()->setFormatCode('#,##0.00');
					$rows++;
					$si++;
				}
				    $sheet->setCellValue('E' . $rows, 'Total');
					$sheet->setCellValue('F' . $rows, $tot);
				
			}
			$writer = new Xlsx($spreadsheet);
			$writer->save('uploads/excel/' . $fileName . '.xlsx');
			return $this->response->download('uploads/excel/' . $fileName . '.xlsx', null)->setFileName($fileName . '.xlsx');
		} else {
			echo view('archanaibooking/archanai_refund_print.php', $data);
		}
	   
	}
	
		public function ticket_print()
	{
		$fdate = !empty($_REQUEST['fdate']) ? $_REQUEST['fdate'] : date('Y-m-01');
		$tdate = !empty($_REQUEST['tdate']) ? $_REQUEST['tdate'] : date('Y-m-d');
		$user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : [];
		$paid_through = !empty($_REQUEST['paid_through']) ? $_REQUEST['paid_through'] : [];
	    $builder = $this->db->table('archanai_booking ab')->select('ab.*, pm.name as payment_mode_name, pm.id as payment_mode_id, abgd.reference_id')->join('archanai_payment_gateway_datas abgd', 'abgd.archanai_booking_id = ab.id', 'left')->join('payment_mode pm', 'pm.id = abgd.payment_mode', 'left')->where("date BETWEEN '$fdate' AND '$tdate'")->where('payment_status', 2)->orderBy('ab.date', 'DESC');
		if(!empty($user_id)){
			$builder->whereIn('ab.entry_by', $user_id);
		}
		if(!empty($paid_through)){
			$builder->whereIn('ab.paid_through', $paid_through);
		}
		$data['list'] = $builder->get()->getResultArray();
		$data['users'] = $this->db->table('login')->select('id,name,member_comes')->where('member_comes', 'counter')->get()->getResultArray();
		$data['fdate'] = $fdate;
		$data['tdate'] = $tdate;
		$data['user_id'] = $user_id;
		$data['paid_through'] = $paid_through;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('archanaibooking/ticket_print', $data);
		echo view('template/footer');
	}
	
	public function print_ticket($arch_book_id){
		$id = $this->request->uri->getSegment(3);
	   $data['qry1'] = $archanai_booking = $this->db->table('archanai_booking')->where('id', $id)->get()->getRowArray();
	   // $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
	   // $data['qrcdoee'] = qrcode_generation($id,$url, 95, 95);
	   // if($archanai_booking['sep_print'] == 1) $view_file = 'frontend/archanai/sep_print_imin';
	   if($archanai_booking['sep_print'] == 1) $view_file = 'frontend/archanai/print_sep';
	   // else $view_file = 'frontend/archanai/print_imin';
	   else $view_file = 'frontend/archanai/print';
	   if($archanai_booking['paid_through'] == 'COUNTER' || $archanai_booking['paid_through'] == 'KIOSK'){
		   if($archanai_booking['payment_status'] == '2'){
			   //$data['qry2'] = $this->db->table('archanai_booking_details')->where('archanai_booking_id', $id)->get()->getResultArray();
			   //echo "<pre>"; print_r($id); exit();
			   $tmpid = $this->session->get('profile_id');
			   $data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

			   $data['booking'] = $this->db->table('archanai_booking_details', 'archanai', 'archanai_booking_rasi', 'rasi', 'natchathram')
							->join('archanai', 'archanai.id = archanai_booking_details.archanai_id', 'left')
							->join('archanai_diety', 'archanai_diety.id = archanai_booking_details.deity_id', 'left')
							->where('archanai_booking_details.archanai_booking_id', $id )
							->select('archanai.*, archanai_diety.name_tamil as diety_name, archanai_diety.code as diety_code')
							->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
							->get()->getResultArray();	
							
				$data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
							->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
							->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
							->where('archanai_booking_rasi.archanai_booking_id', $id )
							->select('archanai_booking_rasi.*')
							->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
							->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
							->get()
							->getResultArray();
				$data['vehicles'] = $this->db->table('archanai_booking_vehicle')
							->where('archanai_booking_vehicle.archanai_booking_id', $id )
							->select('archanai_booking_vehicle.*')
							->get()
							->getResultArray();
				$settings_arr = $this->db->table('settings')->where('type', 1)->get()->getResultArray();
				$settings = array();
				if(count($settings_arr) > 0){
					foreach($settings_arr as $val){
						$settings[$val['setting_name']] = $val['setting_value'];
					}
				}
				$data['settings'] = $settings;   
						   //echo $this->db->getLastQuery();
			   //echo "<pre>"; print_r($data); exit();
			   echo view($view_file, $data);
		   }elseif($archanai_booking['payment_status'] == '1'){
			   $archanai_payment_gateway_datas = $this->db->table('archanai_payment_gateway_datas')->where('archanai_booking_id', $arch_book_id)->get()->getRowArray();
			   if(!empty($archanai_payment_gateway_datas['reference_id'])){
				   $reference_id = $archanai_payment_gateway_datas['reference_id'];
				   $result_data = $this->initiatePayment_response($reference_id);
				   $response_data = json_decode($result_data, true);
				   $payment_gateway_up_data = array();
				   $payment_gateway_up_data['response_data'] = $result_data;
				   $this->db->table('archanai_payment_gateway_datas')->where('id', $archanai_payment_gateway_datas['id'])->update($payment_gateway_up_data);
				   if(!empty($response_data['status'])){
					   if($response_data['status'] == 'completed'){
						   $archanai_booking_up_data = array();
						   $archanai_booking_up_data['payment_status'] = 2;
						   $this->db->table('archanai_booking')->where('id', $id)->update($archanai_booking_up_data);
						   $this->account_migration($id);
						   $tmpid = $this->session->get('profile_id');
						   $data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

						   $data['booking'] = $this->db->table('archanai_booking_details', 'archanai', 'archanai_booking_rasi', 'rasi', 'natchathram')
									   ->join('archanai', 'archanai.id = archanai_booking_details.archanai_id', 'left')
									   ->where('archanai_booking_details.archanai_booking_id', $id )
									   ->select('archanai.*')
									   ->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
									   ->get()
									   ->getResultArray();
						   $data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
									   ->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
									   ->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
									   ->where('archanai_booking_rasi.archanai_booking_id', $id )
									   ->select('archanai_booking_rasi.*')
									   ->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
									   ->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
									   ->get()
									   ->getResultArray();
						   $data['vehicles'] = $this->db->table('archanai_booking_vehicle')
									   ->where('archanai_booking_vehicle.archanai_booking_id', $id )
									   ->select('archanai_booking_vehicle.*')
									   ->get()
									   ->getResultArray();
						   echo view($view_file, $data);
					   }else{
						   $archanai_booking_up_data = array();
						   $archanai_booking_up_data['payment_status'] = 3;
						   $this->db->table('archanai_booking')->where('id', $id)->update($archanai_booking_up_data);
						   redirect()->to("/cancelled_booking");
						   exit;
					   }
				   }
			   }else{
				   redirect()->to("/cancelled_booking");
				   exit;
			   }
		   }
	   }else{
		   $tmpid = $this->session->get('profile_id');
		   $data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();

		   $data['booking'] = $this->db->table('archanai_booking_details', 'archanai', 'archanai_booking_rasi', 'rasi', 'natchathram')
					   ->join('archanai', 'archanai.id = archanai_booking_details.archanai_id', 'left')
					   ->where('archanai_booking_details.archanai_booking_id', $id )
					   ->select('archanai.*')
					   ->select('archanai_booking_details.*,(archanai_booking_details.amount+archanai_booking_details.commision) as tot')
					   ->get()
					   ->getResultArray();
		   $data['rasi'] = $this->db->table('archanai_booking_rasi', 'rasi', 'natchathram')
					   ->join('rasi', 'rasi.id = archanai_booking_rasi.rasi_id', 'left')
					   ->join('natchathram', 'natchathram.id = archanai_booking_rasi.natchathram_id', 'left')
					   ->where('archanai_booking_rasi.archanai_booking_id', $id )
					   ->select('archanai_booking_rasi.*')
					   ->select('rasi.*, rasi.name_eng as rasi_name_eng, rasi.name_tamil as rasi_name_tamil')
					   ->select('natchathram.*, natchathram.name_eng as nat_name_eng, natchathram.name_tamil as nat_name_tamil')
					   ->get()
					   ->getResultArray();
					   //echo $this->db->getLastQuery();
		   //echo "<pre>"; print_r($data); exit();
		   $data['vehicles'] = $this->db->table('archanai_booking_vehicle')
									   ->where('archanai_booking_vehicle.archanai_booking_id', $id )
									   ->select('archanai_booking_vehicle.*')
									   ->get()
									   ->getResultArray();
		   echo view($view_file, $data);
	   }
	}
	
	public function get_ar_names()
	{
		$name = $_POST['search'];
		$data = array();
		$res = $this->db->query("select name from  archanai_booking_rasi where name like '%".$name."%' group by name")->getResultArray();
		foreach($res as $row){
			$data[] = $row['name'];
		}
		//$datas = implode(',', $data);
		echo json_encode($data);
	}

	public function print_archanaiticket()
	{
		global $lang;
		if (!$this->model->list_validate('archanai_report')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data = array();
		$from_date = !empty($_REQUEST['fdt']) ? $_REQUEST['fdt'] : date('Y-m-01');
		$to_date = !empty($_REQUEST['tdt']) ? $_REQUEST['tdt'] : date('Y-m-m');
		$user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : [];
		$paid_through = !empty($_REQUEST['paid_through']) ? $_REQUEST['paid_through'] : [];
	    $builder = $this->db->table('archanai_booking ab')->select('ab.*, pm.name as payment_mode_name, pm.id as payment_mode_id, abgd.reference_id')->join('archanai_payment_gateway_datas abgd', 'abgd.archanai_booking_id = ab.id', 'left')->join('payment_mode pm', 'pm.id = abgd.payment_mode', 'left')->where("date BETWEEN '$from_date' AND '$to_date'")->where('payment_status', 2)->orderBy('ab.date', 'DESC');
		if(!empty($user_id)){
			$builder->whereIn('ab.entry_by', $user_id);
		}
		if(!empty($paid_through)){
			$builder->whereIn('ab.paid_through', $paid_through);
		}
		$archanai_datas = $builder->get()->getResultArray();
		$data['data'] = $archanai_datas;
		$data['fdate'] = $from_date;
		$data['tdate'] = $to_date;
		$i = 1;
		if ($_REQUEST['pdf_archanaiticket'] == "PDF") {
			// $file_name = "Archanai_Ticket_Report_" . $data['fdate'] . "_to_" . $data['tdate'];
			$file_name = "Archanai_Ticket_Report";
			$dompdf = new \Dompdf\Dompdf();
			$options = $dompdf->getOptions();
			$options->set(array('isRemoteEnabled' => true));
			$dompdf->setOptions($options);
			$dompdf->loadHtml(view('archanaibooking/archanai_ticket_pdf', ["pdfdata" => $data]), 'UTF-8');
			$dompdf->setPaper('LEGAL', 'portrait');
			$dompdf->render();
			$dompdf->stream($file_name);
		} elseif ($_REQUEST['excel_archanaiticket'] == "EXCEL") {
			// $fileName = "Archanai_Ticket_Report_" . $data['fdate'] . "_to_" . $data['tdate'];
			$fileName = "Archanai_Ticket_Report";
			$spreadsheet = new Spreadsheet();

			$sheet = $spreadsheet->getActiveSheet();
			$sheet->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$style = array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				)
			);

			$sheet->getStyle("A1:F1")->applyFromArray($style);
			$sheet->mergeCells('A1:F1');
			$sheet->setCellValue('A1', $_SESSION['site_title']);
			$sheet->setCellValue('B2', $lang->date);
			$sheet->setCellValue('C2', $lang->bill_no);
			$sheet->setCellValue('D2', $lang->booked . ' ' . $lang->by);
			$sheet->setCellValue('E2', $lang->payment);
			$sheet->setCellValue('F2', $lang->amount);
			$rows = 3;
			$si = 1;
			if(count($archanai_datas) > 0){
				$ii = 0;
				foreach ($archanai_datas as $val) {
					$sheet->setCellValue('A' . $rows, $val['date']);
					$sheet->setCellValue('B' . $rows, $val['ref_no']);
					$sheet->setCellValue('C' . $rows, $val['reference_id']);
					$sheet->setCellValue('D' . $rows, $val['paid_through']);
					$sheet->setCellValue('E' . $rows, $val['payment_mode_name']);
					$sheet->setCellValue('F' . $rows, $val['amount']);
					$sheet->getStyle('F' . $rows)->getNumberFormat()->setFormatCode('#,##0.00');
					$rows++;
					$si++;
				}
			}
			$writer = new Xlsx($spreadsheet);
			$writer->save('uploads/excel/' . $fileName . '.xlsx');
			return $this->response->download('uploads/excel/' . $fileName . '.xlsx', null)->setFileName($fileName . '.xlsx');
		} else {
			echo view('archanaibooking/archanai_ticket_print.php', $data);
		}
	}
	
	//item count report
	public function item_count_report()
	{
		$fdate = !empty($_REQUEST['fdate']) ? $_REQUEST['fdate'] : date('Y-m-01');
		$tdate = !empty($_REQUEST['tdate']) ? $_REQUEST['tdate'] : date('Y-m-d');
		$user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
		$product_id = !empty($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';
	    $builder = $this->db->table('archanai_booking ab')
	    ->select("name_eng as archanai_name,abd.deity_id as deity_id,ref_code,archanai_id,shortcode,min(sep_arc_sl_no) as sl_no,max(sep_arc_sl_no_to) as sl_to,sum(quantity) as quantity,sum(total_amount) as amount")
	    ->join("archanai_booking_details abd","ab.id = abd.archanai_booking_id")
	    ->join("archanai a","a.id = abd.archanai_id")
	    ->where("payment_status",2)
	    ->where("date BETWEEN '$fdate' AND '$tdate'")
	    ->groupby("archanai_id,abd.deity_id,ref_code");
		if(!empty($user_id)){
			$builder->where('ab.entry_by', $user_id);
		}
		if(!empty($product_id)){
			$builder->where('abd.archanai_id', $product_id);
		}
	    $datas = $builder->get()
	    ->getResultArray();
	    // echo $this->db->getLastQuery();
	    // die("t");
	    $paymodes = [];
	    $datapaymode = $this->db->table("payment_mode")
	    ->get()
	    ->getResultArray();
	    foreach($datapaymode as $iter)
	    {
	        $paymodes[$iter["shortcode"]] = $iter["name"];
	    }
	    
	    $deitys = [];
	    $deitydata = $this->db->table("archanai_diety")
	    ->get()
	    ->getResultArray();
	    foreach($deitydata as $iter)
	    {
	        $deitys[$iter["id"]] = $iter["name"];
	    }
	    
	    $res = [];
	    foreach($datas as $iter)
	    {
	        if(strlen($iter["ref_code"])!=6)continue;
	        
	        $typ = substr($iter["ref_code"], 2, 2);
	        if($typ != "CT" && $typ != "KI") //counter or kios
	        continue;
	        
	        $paymode = substr($iter["ref_code"], 4, 2); //paymentmode
	        $paymode_name = (isset($paymodes[$paymode])?$paymodes[$paymode]:$paymode);
	        $deity_name = (isset($deitys[$iter["deity_id"]])?$deitys[$iter["deity_id"]]:$iter["deity_id"]);
	        $res[($typ=="CT"?"Counter":"KIOSK")][$paymode_name][$deity_name][$iter["archanai_id"]] = $iter;
	        
	    }
	    //->select('ab.*, pm.name as payment_mode_name')->join('archanai_payment_gateway_datas abgd', 'abgd.archanai_booking_id = ab.id', 'left')->join('payment_mode pm', 'pm.id = abgd.payment_mode', 'left')->where("date BETWEEN '$fdate' AND '$tdate'")->where('payment_status', 3)->where("is_refund",1)->orderBy('ab.date', 'DESC');
		$data['list'] = $res;
		$data['users'] = $this->db->table('login')->select('id,name,member_comes')->where('member_comes', 'counter')->get()->getResultArray();
		$data['products'] = $this->db->table('archanai')->get()->getResultArray();
		$data['fdate'] = $fdate;
		$data['tdate'] = $tdate;
		$data['user_id'] = $user_id;
		$data['product_id'] = $product_id;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('report/item_count_report', $data);
		echo view('template/footer');
	}
	public function item_count_report_print($arch_book_id=0){
		global $lang;
		if (!$this->model->list_validate('archanai_report')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data = array();
		$from_date = !empty($_REQUEST['fdt']) ? $_REQUEST['fdt'] : date('Y-m-01');
		$to_date = !empty($_REQUEST['tdt']) ? $_REQUEST['tdt'] : date('Y-m-m');
		$user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
		$product_id = !empty($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';
	    
	    $builder = $this->db->table('archanai_booking ab')
	    ->select("name_eng as archanai_name,abd.deity_id as deity_id,ref_code,archanai_id,shortcode,min(sep_arc_sl_no) as sl_no,max(sep_arc_sl_no_to) as sl_to,sum(quantity) as quantity,sum(total_amount) as amount")
	    ->join("archanai_booking_details abd","ab.id = abd.archanai_booking_id")
	    ->join("archanai a","a.id = abd.archanai_id")
	    ->where("payment_status",2)
	    ->where("date BETWEEN '$from_date' AND '$to_date'")
	    ->groupby("archanai_id,abd.deity_id,ref_code");
		if(!empty($user_id)){
			$builder->where('ab.entry_by', $user_id);
		}
		if(!empty($product_id)){
			$builder->where('abd.archanai_id', $product_id);
		}
	    $datas = $builder->get()->getResultArray();
	    // echo $this->db->getLastQuery();
	    // die("t");
	    $paymodes = [];
	    $datapaymode = $this->db->table("payment_mode")
	    ->get()
	    ->getResultArray();
	    foreach($datapaymode as $iter)
	    {
	        $paymodes[$iter["shortcode"]] = $iter["name"];
	    }
	    
	    $deitys = [];
	    $deitydata = $this->db->table("archanai_diety")
	    ->get()
	    ->getResultArray();
	    foreach($deitydata as $iter)
	    {
	        $deitys[$iter["id"]] = $iter["name"];
	    }
	    
	    $res = [];
	    foreach($datas as $iter)
	    {
	        if(strlen($iter["ref_code"])!=6)continue;
	        
	        $typ = substr($iter["ref_code"], 2, 2);
	        if($typ != "CT" && $typ != "KI") //counter or kios
	        continue;
	        
	        $paymode = substr($iter["ref_code"], 4, 2); //paymentmode
	        $paymode_name = (isset($paymodes[$paymode])?$paymodes[$paymode]:$paymode);
	        $deity_name = (isset($deitys[$iter["deity_id"]])?$deitys[$iter["deity_id"]]:$iter["deity_id"]);
	        $res[($typ=="CT"?"Counter":"KIOSK")][$paymode_name][$deity_name][$iter["archanai_id"]] = $iter;
	        
	    }
	    
	    //print_r($res);
		$data['data'] = $res;
		$data['lang'] = $lang;
		$data['fdate'] = $from_date;
		$data['tdate'] = $to_date;
		
		$i = 1;
		if ($_REQUEST['pdf_item_count_report'] == "PDF") {
			// $file_name = "Item_Count_Report_" . $data['fdate'] . "_to_" . $data['tdate'];
			$file_name = "Item_Count_Report";
			$dompdf = new \Dompdf\Dompdf();
			$options = $dompdf->getOptions();
			$options->set(array('isRemoteEnabled' => true));
			$dompdf->setOptions($options);
			$dompdf->loadHtml(view('report/item_count_user_report_pdf', ["pdfdata" => $data]), 'UTF-8');
			$dompdf->setPaper('LEGAL', 'portrait');
			$dompdf->render();
			$dompdf->stream($file_name);
		} elseif ($_REQUEST['excel_item_count_report'] == "EXCEL") {
			// $fileName = "Item_Count_Report_" . $data['fdate'] . "_to_" . $data['tdate'];
			$fileName = "Item_Count_Report";
			$spreadsheet = new Spreadsheet();

			$sheet = $spreadsheet->getActiveSheet();
			$sheet->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$sheet->getStyle('A2')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$sheet->getStyle('A3')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$style = array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				)
			);

			$sheet->getStyle("A1:F1")->applyFromArray($style);
			$sheet->mergeCells('A1:F1');
			$sheet->getStyle("A2:F2")->applyFromArray($style);
			$sheet->mergeCells('A2:F2');
			$sheet->getStyle("A3:F3")->applyFromArray($style);
			$sheet->mergeCells('A3:F3');
			$sheet->setCellValue('A1', $_SESSION['site_title']);
			$sheet->setCellValue('A2', 'ITEM COUNT REPORT');
			$sheet->setCellValue('A3', date('d-m-Y', strtotime($from_date)) . ' - ' . date('d-m-Y', strtotime($to_date)));
			
			
			$rows = 5;
			//$si = 1;
			// print_r($data['data']);
			// die;
			if(!empty($data['data']))
			{
			    $totqtytyp = 0;
                $tottyp = 0;
                                
				foreach ($data['data'] as $typ=>$iter){
				    $sheet->setCellValue('A'.$rows++, $typ);
				    $totqtypaymode = 0;
                    $totpaymode = 0;
			        foreach($iter as $payment_mode=>$iter2)
			        {
			            $totqtydeity = 0;
                        $totdeity = 0;
			            $sheet->setCellValue('A'.$rows++, $payment_mode);
			            
    				    foreach ($iter2 as $deity_name=>$iter1){
    				        $sheet->setCellValue('A'.$rows++, $deity_name);
    				        $totqty = 0;
		        	        $tot = 0;
		        	        	$sheet->setCellValue('A'.$rows, $lang->sno);
                    			$sheet->setCellValue('B'.$rows, $lang->archanai_name);
                    			$sheet->setCellValue('C'.$rows, $lang->sl_no);
                    			$sheet->setCellValue('D'.$rows, $lang->sl_to);
                    			$sheet->setCellValue('E'.$rows, $lang->quantity);
                    			$sheet->setCellValue('F'.$rows++, $lang->amount);
		
    				         $i = 1; foreach($iter1 as $val) {
            				    $sheet->setCellValue('A' .$rows,$i);
            					$sheet->setCellValue('B' . $rows, $val['shortcode']." - ".$val['archanai_name']);
            					$sheet->setCellValue('C' . $rows, $val['sl_no']);
            					$sheet->setCellValue('D' . $rows, $val['sl_to']);
            					$sheet->setCellValue('E' . $rows, $val['quantity']);
            					$sheet->setCellValue('F' . $rows, $val['amount']);
            					$sheet->getStyle('F' . $rows)->getNumberFormat()->setFormatCode('#,##0.00');
            					$rows++;
            					$i++;
            					$totqty+=floatval($val['quantity']);
            					$tot+=floatval($val['amount']);
    				         }
    				         $sheet->setCellValue('D' . $rows, 'SUB TOTAL');
    					     $sheet->setCellValue('E' . $rows, $totqty);
    					     $sheet->setCellValue('F' . $rows, $tot);
    					     $sheet->getStyle('F' . $rows)->getNumberFormat()->setFormatCode('#,##0.00');
    					     $rows++;
    					     $totqtydeity+=floatval($totqty);
                             $totdeity+=floatval($tot);
    				    }
    				    $sheet->setCellValue('D' . $rows, 'TOTAL '.strtoupper($payment_mode));
    			        $sheet->setCellValue('E' . $rows, $totqtydeity);
    			        $sheet->setCellValue('F' . $rows, $totdeity);
                		$sheet->getStyle('F' . $rows)->getNumberFormat()->setFormatCode('#,##0.00');
    				    $totqtypaymode+=floatval($totqtydeity);
                        $totpaymode+=floatval($totdeity);
						$rows++;
			        }
			        $sheet->setCellValue('D' . $rows, 'TOTAL '.strtoupper($typ));
			        $sheet->setCellValue('E' . $rows, $totqtypaymode);
			        $sheet->setCellValue('F' . $rows, $totpaymode);
            		$sheet->getStyle('F' . $rows)->getNumberFormat()->setFormatCode('#,##0.00');
			        $totqtytyp+=floatval($totqtypaymode);
                    $tottyp+=floatval($totpaymode);
					$rows++;
				}
				$sheet->setCellValue('D' . $rows, 'TOTAL');
				$sheet->setCellValue('E' . $rows, $totqtytyp);
				$sheet->setCellValue('F' . $rows, $tottyp);
				$sheet->getStyle('F' . $rows)->getNumberFormat()->setFormatCode('#,##0.00');
				
			}
			$writer = new Xlsx($spreadsheet);
			$writer->save('uploads/excel/' . $fileName . '.xlsx');
			return $this->response->download('uploads/excel/' . $fileName . '.xlsx', null)->setFileName($fileName . '.xlsx');
		} else {
		    echo view('report/item_count_report_user_print.php', $data);
            
		}
	   
	}
	/*
	public function item_count_report_print1($arch_book_id=0){
		global $lang;
		if (!$this->model->list_validate('archanai_report')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data = array();
		$from_date = !empty($_REQUEST['fdt']) ? $_REQUEST['fdt'] : date('Y-m-01');
		$to_date = !empty($_REQUEST['tdt']) ? $_REQUEST['tdt'] : date('Y-m-m');
		$user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
	    
	    $builder = $this->db->table('archanai_booking ab')
	    ->select("name_eng as archanai_name,ref_code,archanai_id,shortcode,min(sep_arc_sl_no) as sl_no,max(sep_arc_sl_no_to) as sl_to,sum(quantity) as quantity,sum(total_amount) as amount")
	    ->join("archanai_booking_details abd","ab.id = abd.archanai_booking_id")
	    ->join("archanai a","a.id = abd.archanai_id")
	    ->where("payment_status",2)
	    ->where("date BETWEEN '$from_date' AND '$to_date'")
	    ->groupby("archanai_id,ref_code")
	    ->get()
	    ->getResultArray();
	    //echo $this->db->getLastQuery();
	    //die("t");
	    $paymodes = [];
	    $datapaymode = $this->db->table("payment_mode")
	    ->get()
	    ->getResultArray();
	    foreach($datapaymode as $iter)
	    {
	        $paymodes[$iter["shortcode"]] = $iter["name"];
	    }
	    $res = [];
	    foreach($builder as $iter)
	    {
	        if(strlen($iter["ref_code"])!=6)continue;
	        
	        $typ = substr($iter["ref_code"], 2, 2);
	        if($typ != "CT" && $typ != "KI") //counter or kios
	        continue;
	        
	        $paymode = substr($iter["ref_code"], 4, 2); //paymentmode
	        $paymode_name = (isset($paymodes[$paymode])?$paymodes[$paymode]:$paymode);
	        $res[($typ=="CT"?"Counter":"Kios")][$paymode_name][$iter["archanai_id"]] = $iter;
	        
	    }
	    
	    //print_r($res);
		$data['data'] = $res;
		$data['lang'] = $lang;
		$i = 1;
		if ($_REQUEST['pdf_item_count_report'] == "PDF") {
			// $file_name = "Item_Count_Report_" . $data['fdate'] . "_to_" . $data['tdate'];
			$file_name = "Item_Count_Report";
			$dompdf = new \Dompdf\Dompdf();
			$options = $dompdf->getOptions();
			$options->set(array('isRemoteEnabled' => true));
			$dompdf->setOptions($options);
			$dompdf->loadHtml(view('report/item_count_report_pdf', ["pdfdata" => $data]), 'UTF-8');
			$dompdf->setPaper('LEGAL', 'portrait');
			$dompdf->render();
			$dompdf->stream($file_name);
		} elseif ($_REQUEST['excel_item_count_report'] == "EXCEL") {
			// $fileName = "Item_Count_Report_" . $data['fdate'] . "_to_" . $data['tdate'];
			$fileName = "Item_Count_Report";
			$spreadsheet = new Spreadsheet();

			$sheet = $spreadsheet->getActiveSheet();
			$sheet->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$style = array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				)
			);

			$sheet->getStyle("A1:F1")->applyFromArray($style);
			$sheet->mergeCells('A1:F1');
			$sheet->setCellValue('A1', $_SESSION['site_title']);
			$sheet->setCellValue('A2', $lang->sno);
			$sheet->setCellValue('B2', $lang->archanai_name);
			$sheet->setCellValue('C2', $lang->shortcode);
			$sheet->setCellValue('D2', $lang->sl_no);
			$sheet->setCellValue('E2', $lang->sl_to);
			$sheet->setCellValue('F2', $lang->quantity);
			$sheet->setCellValue('G2', $lang->amount);
			$rows = 3;
			//$si = 1;
			if(!empty($data['data']))
			{
				foreach ($data['data'] as $typ=>$iter){
				    $sheet->setCellValue('A'.$rows++, $typ);
				    $totqty = 0;
		        	$tot = 0;
			
				    foreach ($iter as $payment_mode=>$iter1){
				        $sheet->setCellValue('A'.$rows++, $payment_mode);
				         $i = 1; foreach($iter1 as $val) {
        				    $tot += floatval($val['amount']);
        					$sheet->setCellValue('A' . $rows, $i++);
        					$sheet->setCellValue('B' . $rows, $val['archanai_name']);
        					$sheet->setCellValue('C' . $rows, $val['shortcode']);
        					$sheet->setCellValue('D' . $rows, $val['sl_no']);
        					$sheet->setCellValue('E' . $rows, $val['sl_to']);
        					$sheet->setCellValue('F' . $rows, $val['quantity']);
        					$sheet->setCellValue('G' . $rows, $val['amount']);
        					$sheet->getStyle('G' . $rows)->getNumberFormat()->setFormatCode('#,##0.00');
        					$rows++;
        					$i++;
        					$totqty+=floatval($val['quantity']);
        					$tot+=floatval($val['amount']);
				         }
				         $sheet->setCellValue('E' . $rows, 'Total');
					     $sheet->setCellValue('F' . $rows, $totqty);
					     $sheet->setCellValue('G' . $rows, $tot);
					     $rows++;
				    }
				}
				    
				
			}
			$writer = new Xlsx($spreadsheet);
			$writer->save('uploads/excel/' . $fileName . '.xlsx');
			return $this->response->download('uploads/excel/' . $fileName . '.xlsx', null)->setFileName($fileName . '.xlsx');
		} else {
		    
			echo view('report/item_count_report_print.php', $data);
		}
	   
	}
	*/
	
}
