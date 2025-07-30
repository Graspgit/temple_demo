<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\RequestModel;

class Prasadam_online extends BaseController
{
  function __construct() {
    parent::__construct();
    helper('url');
    helper('common');
    $request = service('request');
    $this->model = new PermissionModel();
    if (($this->session->get('log_id_frend')) == false) {
        if ($request->isAJAX()) {
                echo json_encode([
                    "session_expired"=>true,
                    "status"=>200
                    ]);
                exit;
            }
            
      $data['dn_msg'] = 'Please Login';
      return redirect()->to('/member_login');
    }
  }
  
  public function index() {
    
    $login_id = $_SESSION['log_id_frend'];
    $default_group = $this->db->query("SELECT * FROM prasadam_group order by id asc limit 1")->getRowArray();
    $data['default'] = str_replace(' ', '_', strtolower($default_group['name']));
    $data['payment_mode'] = $this->db->table('payment_mode')->where("paid_through", "COUNTER")->where("prasadam", 1)->where('status', 1)->get()->getResultArray();
    $data['phone_codes'] = $this->db->table("phone_code")->orderBy('dailing_code', 'ASC')->get()->getResultArray();
    $data['prasadam_settings'] = $this->db->query("SELECT * FROM prasadam_setting WHERE ledger_id != '' AND ledger_id IS NOT NULL order by name_eng asc")->getResultArray();
    $data['reprintlists'] = $this->db->query("SELECT id,customer_name,total_amount,name,date FROM prasadam WHERE added_by = '" . $login_id . "' and paid_through = 'COUNTER' AND payment_status = 2 ORDER BY id DESC LIMIT 3")->getResultArray();

    $pras_sett = $this->db->table('prasadam_setting')->where("shortcode is not null and shortcode != ''")->get()->getResultArray();
    foreach ($pras_sett as $row) {
      $group_ids = explode(',', $row['group_id']);
      foreach ($group_ids as $grp) {
        $group = $this->db->table('prasadam_group')->where('id', $grp)->get()->getRowArray();
        if ($group) {
          if (!isset($data['sett_data'][$group['name']])) {
            $data['sett_data'][$group['name']] = [];
          }
          $row['group_id'] = $grp;
          $data['sett_data'][$group['name']][] = $row;
        }
      }
    }
    $group_order = [];
    $sorted_data = [];
    if(isset($data['sett_data']))
    {
        foreach ($data['sett_data'] as $group_name => $settings) {
            $group_details = $this->db->table('prasadam_group')->where('name', $group_name)->get()->getRowArray();
            if ($group_details) {
                $group_order[$group_name] = $group_details['order_no'];
            }
        }
        asort($group_order);
        
        foreach ($group_order as $group_name => $order_no) {
            $sorted_data[$group_name] = $data['sett_data'][$group_name];
        }
    }
    $data['sett_data'] = $sorted_data;
    
    $data['dieties'] = $this->db->table('archanai_diety')->get()->getResultArray();

    $settings = $this->db->table('settings')->where('type', 3)->get()->getResultArray();
		$setting_array = array();
		if(count($settings) > 0){
			foreach ($settings as $item) {
				$setting_array[$item['setting_name']] = $item['setting_value'];
			}
		}
		$data['setting'] = $setting_array;
    // echo '<pre>';
    // print_r($data['sett_data']);
    // exit;
    echo view('frontend/layout/header');
    echo view('frontend/prasadam/index', $data);
  }

  public function save() {
    // echo '<pre>';
    // print_r( $_POST); 
    // exit;
    $msg_data = array();
    $msg_data['err'] = '';
    $msg_data['succ'] = '';
    $this->db->transStart();
    try {
    
      $pay_id = $_POST['pay_method'];
      $payment_mode = $this->db->table('payment_mode')->where("id", $pay_id)->get()->getRowArray();
      $pay_method = $payment_mode['name'];
      $pay_code = !empty($payment_mode['shortcode']) ? substr($payment_mode['shortcode'], 0, 2) : 'PY';
      $data['date'] = $date = $_POST['date'];
	
	    $yr = Date("Y");
      $mon = Date("m");
      $archanai_code = 'PR';
      $counter_code = 'CT';
      $ref_no_code = $archanai_code . $counter_code . $pay_code;
		
      $query = $this->db->query("SELECT ref_no FROM prasadam where id=(select max(id) from prasadam where year (date)='" . $yr . "' and month (date)='" . $mon . "')")->getRowArray();
      //$data['ref_no'] = 'PR' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
      if(!empty($query['ref_no'])) $data['ref_no'] = $ref_no_code . $yr . $mon . (sprintf("%06d", (((float) substr($query['ref_no'], -6)) + 1)));
		  else $data['ref_no'] = $ref_no_code . $yr . $mon . sprintf("%06d", 1);
	  
	    $data["ref_code"] = $ref_no_code;
      $tot_amt = !empty($_POST['tot_amt']) ? (float) $_POST['tot_amt'] : 0;
      $data['date'] = date('Y-m-d');
      $data['customer_name'] = $_POST['name'];
      $data['diety_id'] = isset($_POST['diety_id']) ? $_POST['diety_id'] : null;

      $data['email_id'] = $_POST['email_id'];
      $data['ic_no'] = $_POST['ic_number'];
      $data['address'] = $_POST['address'];
      $data['desciption'] = $_POST['description'];
      $sub_total = $tot_amt;
      if (!empty($_POST['discount_amount'])) {
        $data['discount_amount'] = $_POST['discount_amount'];
        $sub_total += $_POST['discount_amount'];
      }
      $data['sub_total'] = $sub_total;
      $data['total_amount'] = $tot_amt;
      $data['session'] = $_POST['time'];
      $data['collection_date'] = $_POST['collection_date'];
      $time_session = ($data['session'] == 'Breakfast') ? "AM" : "PM";
      $data['serve_time'] = $_POST['hour'] . ':' . $_POST['minute'] . ' ' . $time_session;
      $data['phone_code'] = $mble_phonecode = !empty($_POST['phonecode']) ? $_POST['phonecode'] : "";
      $data['phone_number'] = $mble_number = !empty($_POST['mobile']) ? $_POST['mobile'] : "";
      $data['mobile_no'] = $mble_phonecode . $mble_number;

      $data['added_by'] = $this->session->get('log_id_frend');
      $data['sep_print'] = (!empty($_REQUEST['sep_print']) ? $_REQUEST['sep_print'] : 0);
      $data['paid_through'] = "COUNTER";
      $data['payment_type'] = !empty($_POST['payment_type']) ? $_POST['payment_type'] : 'full';
      $data['payment_mode'] = $pay_id = $_POST['pay_method'];
      $payment_mode = $this->db->table('payment_mode')->where("id", $pay_id)->get()->getRowArray();
		  $pay_method = $payment_mode['name'];

      if (!empty($pay_method)) {
        if ($data['payment_type'] == 'full')
          $data['payment_status'] = 2;
        else
          $data['payment_status'] = 1;
      } else {
        $data['payment_status'] = 0;
      }

      $data['created_at'] = date('Y-m-d H:i:s');
      $data['updated_at'] = date('Y-m-d H:i:s');

      $ip = 'unknown';
      $this->requestmodel = new RequestModel();
      $ip = $this->requestmodel->getIpAddress();
      if ($ip != 'unknown') {
        $ip_details = $this->requestmodel->getLocation($ip);
        $data['ip'] = $ip;
        $data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
        $data['ip_details'] = json_encode($ip_details);
      }
      $paid_amount = !empty($_POST['paid_amount']) ? (float) $_POST['paid_amount'] : 0;
      if (!empty($data['customer_name']) && !empty($data['mobile_no']) && !empty($_POST['prasadam'])) {
        if (($data['payment_type'] == 'partial' && !empty($paid_amount)) || $data['payment_type'] == 'full') {
          if ($data['payment_type'] == 'full')
            $paid_amount = $tot_amt;
          if ($paid_amount <= $tot_amt) {
            $data['paid_amount'] = $paid_amount;
            try {
              $res = $this->db->table('prasadam')->insert($data);
              if ($res) {
                $ins_id = $this->db->insertID();
                if (!empty($ins_id)) {
                  foreach ($_POST['prasadam'] as $prasadam) {
                    $data_prdm_book['prasadam_booking_id'] = $ins_id;
                    $data_prdm_book['group_id'] = $prasadam['group_id'];
                    $data_prdm_book['prasadam_id'] = $prasadam['id'];
                    $data_prdm_book['quantity'] = $prasadam['qty'];
                    $data_prdm_book['created'] = date('Y-m-d H:i:s');
                    $prsm_set = $this->db->table('prasadam_setting')->where('id', $prasadam['id'])->get()->getRowArray();
                    $data_prdm_book['amount'] = $prsm_set['amount'];
                    $amt = $prasadam['qty'] * $prsm_set['amount'];
                    $data_prdm_book['total_amount'] = $amt;
                    
                    $whr_qr = " ref_code = '".$ref_no_code."' and prasadam_id = " . $data_prdm_book['prasadam_id'];
                    $query2 = $this->db->query("SELECT coalesce(max(sep_pras_sl_no_to), 0) + 1 as new_no FROM `prasadam_booking_details` join prasadam on prasadam.id=prasadam_booking_details.prasadam_booking_id where $whr_qr")->getRowArray();
                    $data_prdm_book['sep_pras_sl_no'] = $query2['new_no'];
                    $data_prdm_book['sep_pras_sl_no_to'] = $query2['new_no'] + ($data_prdm_book['quantity'] - 1);
              
                    $res_2 = $this->db->table('prasadam_booking_details')->insert($data_prdm_book);

                    $settings = $this->db->table('settings')->where('type', 3)->where('setting_name', 'enable_madapalli')->get()->getRowArray();
                    if ($settings['setting_value'] == 1) {
                      $madapalli_details['date'] = $_POST['collection_date'];
                      $madapalli_details['type'] = 1;
                      $madapalli_details['booking_id'] = $ins_id;
                      $madapalli_details['product_id'] = $prasadam['id'];
                      $madapalli_details['quantity'] = $prasadam['qty'];
                      $madapalli_details['amount'] = $amt;
                      $madapalli_details['session'] = $data['session'];
                      $madapalli_details['serve_time'] = $data['serve_time'];
                      $madapalli_details['customer_name'] = $_POST['name'];
                      $madapalli_details['customer_mobile'] = $mble_phonecode . $mble_number;
                      $madapalli_details['pro_name_eng'] = $prsm_set['name_eng'];
                      $madapalli_details['status'] = 0;
                      $madapalli_details['created_by'] = $this->session->get('log_id_frend');
                      $madapalli_details['created_at'] = date('Y-m-d H:i:s');
                      $madapalli_details['updated_at'] = date('Y-m-d H:i:s');
                      $res_m1 = $this->db->table('madapalli_booking_details')->insert($madapalli_details);

                      if ($res_m1){
                        $preparation_details = $this->db->table('madapalli_preparation_details')->where('date', $_POST['collection_date'])->where('type', 1)->get()->getResultArray();
                        $product_found = false;

                        foreach ($preparation_details as $detail) {
                          if ($detail['product_id'] == $madapalli_details['product_id'] && $detail['session'] == $madapalli_details['session']) {
                            $new_quantity = $detail['quantity'] + $madapalli_details['quantity'];
                            $update_data = [
                              'quantity' => $new_quantity,
                              'updated_at' => date('Y-m-d H:i:s')
                            ];
                            $this->db->table('madapalli_preparation_details')->where('id', $detail['id'])->update($update_data);
                            $product_found = true;
                            break;
                          }
                        }

                        if (!$product_found) {
                          $insert_data = [
                            'date' => $_POST['collection_date'],
                            'type' => 1,
                            'session' => $data['session'],
                            'product_id' => $madapalli_details['product_id'],
                            'pro_name_eng' => $prsm_set['name_eng'],
                            'pro_name_tamil' => $prsm_set['name_tamil'],
                            'quantity' => $madapalli_details['quantity'],
                            'status' => 0,
                            'created_by' => $this->session->get('log_id_frend'),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                          ];
                          $this->db->table('madapalli_preparation_details')->insert($insert_data);
                        }
                      }
                    }
                  }
                  
                  $payment_gateway_data = array();
                  $payment_gateway_data['prasadam_id'] = $ins_id;
                  $payment_gateway_data['pay_method'] = $pay_method;
                  $this->db->table('prasadam_payment_gateway_datas')->insert($payment_gateway_data);
                  $prasadam_payment_gateway_id = $this->db->insertID();

                  $pay_details = array();
                  $payment_mode_details = $this->db->table("payment_mode")->where('id', $data['payment_mode'])->get()->getRowArray();
                  $pay_details['prasadam_id'] = $ins_id;
                  $pay_details['payment_mode_id'] = $data['payment_mode'];
                  $pay_details['is_repayment'] = 0;
                  $pay_details['paid_through'] = 'COUNTER';
                  $pay_details['pay_status'] = 2;
                  $pay_details['payment_mode_title'] = $payment_mode_details['name'];
                  $pay_details['booking_ref_no'] = $data['ref_no'];
                  $pay_details['amount'] = $paid_amount;
                  if (empty($pay_details['amount'])) {
                    $this->db->transRollback();
                    $msg_data['err'] = 'Invalid Amount';
                    echo json_encode($msg_data);
                    exit;
                  }
                  $pay_details['paid_date'] = date('Y-m-d');
                  $this->requestmodel = new RequestModel();
                  $ip = $this->requestmodel->getIpAddress();
                  $pay_details['ip'] = $ip;
                  if ($ip != 'unknown') {
                    $ip_details = $this->requestmodel->getLocation($ip);
                    $pay_details['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
                    $pay_details['ip_details'] = json_encode($ip_details);
                  }
                  $res_3 = $this->db->table('prasadam_booked_pay_details')->insert($pay_details);

                  $booking_ref_data = array();
                  $booking_ref_data['paid_amount'] = $pay_details['amount'];
                  $booking_ref_data['booking_status'] = 1;
                  $this->db->table("prasadam")->where('id', $ins_id)->update($booking_ref_data);

                  // $devotee_id = $this->devotee_save($data);
                  // if (!empty($devotee_id)){
                  //   $activity_details = json_encode([
                  //     'type' => 'Prasadam Booked',
                  //     'booking_id' => $ins_id
                  //   ]);
                  //   $this->save_activity_log($devotee_id, 4, $activity_details);

                  //   if ($res_3) {
                  //       $this->devotee_payment_details($devotee_id, $pay_details);
                  //   }
                  // }
                }

                if ($data['payment_status'] == 2 || $data['payment_status'] == 1) {
                  $this->account_migration($ins_id);
                  // $this->send_whatsapp_msg($ins_id);
                  // $this->send_mail_to_customer($ins_id);
                }
                if ($res_2) {
                  $this->session->setFlashdata('succ', 'Prasadam Added Successflly');
                  $msg_data['succ'] = 'Prasadam Added Successflly';
                  $msg_data['id'] = $ins_id;
                } else {
                  $this->db->transRollback();
                  $this->session->setFlashdata('fail', 'Please Try Again');
                  $msg_data['err'] = 'Please Try Again';
                }
              }else{
                $this->db->transRollback();
                $msg_data['err'] = 'Please Try Again';
              }
            } catch(Exception $ex) {
                print_r($ex);
            }
          } else {
            $this->db->transRollback();
            $msg_data['err'] = 'Payment amount not greater than total amount';
          }
        } else {
          $this->db->transRollback();
          $msg_data['err'] = 'Invalid Paid Amount.';
        }
      } else {
        $this->db->transRollback();
        //$this->session->setFlashdata('fail', 'Please Try Again');
        $msg_data['err'] = 'Please Try Again. required user details.';
      }
      $this->db->transComplete();
    } catch (Exception $e) {
      $this->db->transRollback();
      $msg_data['err'] = $e->getMessage();
    }
    echo json_encode($msg_data);
    exit();
  }

  public function devotee_payment_details($devotee_id, $pay_details) {
      if (!empty($devotee_id) && !empty($pay_details)) {

          $devotee_pay['devotee_id'] = $devotee_id;
          $devotee_pay['module_type'] = 3;
          $devotee_pay['booking_id'] = $pay_details['prasadam_id'];
          $devotee_pay['ref_no'] = $pay_details['booking_ref_no'];
          $devotee_pay['paid_date'] = $pay_details['paid_date'];
          $devotee_pay['is_repayment'] = $pay_details['is_repayment'];
          $devotee_pay['amount'] = $pay_details['amount'];
          $devotee_pay['payment_mode_id'] = $pay_details['payment_mode_id'];
          $devotee_pay['payment_mode_title'] = $pay_details['payment_mode_title'];
          $devotee_pay['pay_status'] = $pay_details['pay_status'];
          $devotee_pay['paid_through'] = 'COUNTER';
          $devotee_pay['created_by'] = $this->session->get('log_id_frend');
          $devotee_pay['created_at'] = date('Y-m-d H:i:s');

          $this->db->table('devotee_payment_details')->insert($devotee_pay);      
      }
  }

  public function devotee_save($data) {
    if (!empty($data['customer_name']) && !empty($data['phone_code']) && !empty($data['phone_number'])) {
        $existing_devotee = $this->db->table('devotee_management')
                                    ->where('phone_code', $data['phone_code'])
                                    ->where('phone_number', $data['phone_number'])
                                    ->get()
                                    ->getRowArray();

        if ($existing_devotee) {
            $update_data = [];

            if (empty($existing_devotee['dob']) && !empty($data['dob']) ) {
                $update_data['dob'] = $data['dob'];
            }
            if (empty($existing_devotee['email']) && !empty($data['email_id'])) {
                $update_data['email'] = $data['email_id'];
            }
            if (empty($existing_devotee['ic_no']) && !empty($data['ic_no'])) {
                $update_data['ic_no'] = $data['ic_no'];
            }
            if (empty($existing_devotee['address']) && !empty($data['address'])) {
                $update_data['address'] = $data['address'];
            }
            if (empty($existing_devotee['rasi_id']) && !empty($data['rasi_id'])) {
                $update_data['rasi_id'] = $data['rasi_id'];
            }
            if (empty($existing_devotee['natchathra_id']) && !empty($data['natchathra_id'])) {
                $update_data['natchathra_id'] = $data['natchathra_id'];
            }

            if ($existing_devotee['is_member'] == 0) {
              $mobile = $data['phone_code'].$data['phone_number'];
              $member = $this->db->table('member')->where('mobile', $mobile)->get()->getRowArray();
              if ($member) {
                $update_data = [
                  'is_member' => 1,
                  'member_id' => $member['id']
                ];
              }
            }

            if (!empty($update_data)) {
                $update_data['updated_by'] = $data['added_by'];
                $update_data['updated_at'] = date('Y-m-d H:i:s');
                $dvt_update = $this->db->table('devotee_management')->where('id', $existing_devotee['id'])->update($update_data);

                if ($dvt_update) {
                    $updated_fields = [];

                    if (isset($update_data['dob'])) {
                        $updated_fields['dob'] = $data['dob'];
                    }
                    if (isset($update_data['email'])) {
                        $updated_fields['email'] = $data['email_id'];
                    }
                    if (isset($update_data['address'])) {
                        $updated_fields['address'] = $data['address'];
                    }
                    if (isset($update_data['ic_no'])) {
                        $updated_fields['ic_no'] = $data['ic_no'];
                    }

                    $activity_details = json_encode([
                        'type' => 'Devotee updated',
                        'updated_fields' => $updated_fields 
                    ]);

                    $this->save_activity_log($existing_devotee['id'], 2, $activity_details);
                }
            }
            return $existing_devotee['id'];

        } else {
            $new_devotee = [
                'name' => !empty($data['customer_name']) ? $data['customer_name'] : null,
                'dob' => !empty($data['dob']) ? $data['dob'] : null,
                'phone_code' => !empty($data['phone_code']) ? $data['phone_code'] : null,
                'phone_number' => !empty($data['phone_number']) ? $data['phone_number'] : null,
                'email' => !empty($data['email']) ? $data['email'] : null,
                'ic_no' => !empty($data['ic_no']) ? $data['ic_no'] : null,
                'address' => !empty($data['address']) ? $data['address'] : null,
                'state' => !empty($data['state']) ? $data['state'] : null,
                'pincode' => !empty($data['pincode']) ? $data['pincode'] : null,
                'rasi_id' => !empty($data['rasi_id']) ? $data['rasi_id'] : null,
                'natchathra_id' => !empty($data['natchathra_id']) ? $data['natchathra_id'] : null,
                'user_module_tag' => 3,
                'added_through' => 'COUNTER', 
                'created_by' => $this->session->get('log_id_frend'), 
                'created_at' => date('Y-m-d H:i:s'),
                'ip' => $data['ip'], 
                'ip_location' => $data['ip_location'], 
                'ip_details' => $data['ip_details'], 
            ];

            $mobile = $new_devotee['phone_code'].$new_devotee['phone_number'];
            $member = $this->db->table('member')->where('mobile', $mobile)->get()->getRowArray();
            if ($member) {
              $new_devotee = [
                'is_member' => 1,
                'member_id' => $member['id']
              ];
            }
            
            $mgm_save = $this->db->table('devotee_management')->insert($new_devotee);
            $devotee_id = $this->db->insertID();

            if ($mgm_save) {
                $activity_details = json_encode([
                    'type' => 'Devotee added',
                    'name' => $new_devotee['name'],
                    'phone' => $new_devotee['phone_code'] . $new_devotee['phone_number'],
                    'dob' => !empty($new_devotee['dob']) ? $new_devotee['dob'] : null,
                    'email' => !empty($new_devotee['email']) ? $new_devotee['email'] : null,
                    'address' => !empty($new_devotee['address']) ? $new_devotee['address'] : null,
                ]);
                $this->save_activity_log($devotee_id, 1, $activity_details);
            }
            return $devotee_id;
        }
    }
  }

  private function save_activity_log($devotee_id, $activity_type, $activity_fields = null) {
      $activity = array();
      $activity['devotee_id'] = $devotee_id;
      $activity['date'] = date('Y-m-d');
      $activity['time'] = date('H:i:s');  
      $activity['module_type'] = 3; 
      $activity['activity_type'] = $activity_type; 
      $activity['details'] = $activity_fields;
      $activity['added_through'] = 'COUNTER';
      $activity['created_by'] = $this->session->get('log_id_frend');
      $activity['created_at'] = date('Y-m-d H:i:s');

      $this->db->table('devotee_activity')->insert($activity);
  }

  public function get_devotee_details() {
    $phone_code = $_POST['code'];
    $phone_number = $_POST['number'];
		$dev_data = $this->db->table('devotee_management')->where('phone_code', $phone_code)->where('phone_number', $phone_number)->get()->getRowArray();
		
		  $msg_data['name'] = $dev_data['name'];
		  $msg_data['ic_no'] = $dev_data['ic_no'];
		  $msg_data['email'] = $dev_data['email'];
		  $msg_data['address'] = $dev_data['address'];
		echo json_encode($msg_data);
		exit();
	}

  public function gtpaymentdata() {
    $id = $_POST['id'];
    $res = $this->db->table("prasadam")->where("id", $id)->get()->getRowArray();
    $amt = $res['amount'];
    $data['amt'] = $amt;
    $res1 = $this->db->table("prasadam_booked_pay_details")->selectSum('amount')->where("prasadam_id", $id)->get()->getRowArray();
    $paid_amount = $res1['amount'];
    $data['paid_amount'] = $paid_amount;
    $data['bal_amount'] = $amt - $paid_amount;

    echo json_encode($data);
  }

  public function save_repayment() {
    if (!empty($_POST['payment_mode']) && !empty($_POST['pay_amount']) && !empty($_POST['booking_id'])) {
      $date = $_POST['date'];
      $pay_amount = $_POST['pay_amount'];
      $payment_mode = $_POST['payment_mode'];
      $booking_id = $_POST['booking_id'];
      $count = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getNumRows();
      if ($count > 0) {
        $payment_mode_details = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getRowArray();
        $annathanam_details = $this->db->table("prasadam")->where('id', $booking_id)->get()->getRowArray();
        if ($annathanam_details['amount'] >= ($annathanam_details['paid_amount'] + $pay_amount)) {
          $booking_payment_ins_data = array();
          $booking_payment_ins_data['prasadam_id'] = $booking_id;
          $booking_payment_ins_data['booking_ref_no'] = $annathanam_details['ref_no'];
          $booking_payment_ins_data['is_repayment'] = 1;
          $booking_payment_ins_data['payment_mode_id'] = $payment_mode;
          $booking_payment_ins_data['paid_date'] = !empty($date) ? $date : date('Y-m-d');
          $booking_payment_ins_data['amount'] = $pay_amount;
          $booking_payment_ins_data['payment_mode_title'] = $payment_mode_details['name'];
          $paid_through = 'COUNTER';
          if ($paid_through != 'ADMIN' && $paid_through != 'COUNTER')
            $booking_payment_ins_data['payment_ref_no'] = $ubayam_details['ref_no'];
          $booking_payment_ins_data['paid_through'] = $paid_through;
          $booking_payment_ins_data['pay_status'] = ($paid_through == 'ADMIN' || $paid_through == 'COUNTER') ? 2 : 1;
          $this->requestmodel = new RequestModel();
          $ip = $this->requestmodel->getIpAddress();
          $booking_payment_ins_data['ip'] = $ip;
          if ($ip != 'unknown') {
            $ip_details = $this->requestmodel->getLocation($ip);
            $booking_payment_ins_data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
            $booking_payment_ins_data['ip_details'] = json_encode($ip_details);
          }
          // $this->paid_amount += $booking_payment_ins_data['amount'];
          $res = $this->db->table("prasadam_booked_pay_details")->insert($booking_payment_ins_data);
          $booked_pay_id = $this->db->insertID();

          if ($res) {
              $this->devotee_payment_details($devotee_id, $booking_payment_ins_data);
          }

          $this->db->query("UPDATE prasadam SET paid_amount = paid_amount + ? WHERE id = ?", [$pay_amount, $booking_id]);
          $query = $this->db->table('prasadam')->where('id', $booking_id)->get()->getRowArray();
          if ($query['amount'] == $query['paid_amount']) {
            $this->db->query("UPDATE prasadam SET payment_status = 2 WHERE id = ?", [$booking_id]);
          }
          $this->partial_account_migration($booked_pay_id);

          echo json_encode(['status' => true, 'message' => 'Repayment saved successfully.']);
        } else {
          echo json_encode(['status' => false, 'message' => 'Payment amount not exceed Total.']);
        }
      } else {
        echo json_encode(['status' => false, 'message' => 'Failed to save repayment.']);
      }
    } else {
      echo json_encode(['status' => false, 'message' => 'Failed to save repayment.']);
    }
    exit;
  }

  public function partial_account_migration($booked_pay_id) {
    $succ = true;
    $yr = date('Y');
    $mon = date('m');
    $booked_pay_details_cnt = $this->db->table("prasadam_booked_pay_details")->where("id", $booked_pay_id)->get()->getNumRows();
    if ($booked_pay_details_cnt > 0) {
      $booked_pay_details = $this->db->table("prasadam_booked_pay_details")->where("id", $booked_pay_id)->get()->getResultArray();
      $td_ledger = $this->db->table('ledgers')->where('name', 'TRADE RECEIVABLE')->where('group_id', 3)->where('left_code', '1200')->get()->getRowArray();
      if (!empty($td_ledger)) {
        $cr_id1 = $td_ledger['id'];
      } else {
        $cled1['group_id'] = 3;
        $cled1['name'] = 'TRADE RECEIVABLE';
        $cled1['code'] = '1200/005';
        $cled1['op_balance'] = '0';
        $cled1['op_balance_dc'] = 'D';
        $cled1['left_code'] = '1200';
        $cled1['right_code'] = '005';
        $this->db->table('ledgers')->insert($cled1);
        $cr_id1 = $this->db->insertID();
      }
      $booking_id = $booked_pay_details[0]['prasadam_id'];
      $prasadam = $this->db->table("prasadam")->where("id", $booking_id)->get()->getRowArray();
      foreach ($booked_pay_details as $row) {
        $paymentmode = $this->db->table('payment_mode')->where('id', $row['payment_mode_id'])->get()->getRowArray();
        if (!empty($paymentmode['ledger_id'])) {
          $number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
          if (empty($number))
            $num = 1;
          else
            $num = $number['number'] + 1;
          // Get Entry Code
          $qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();

          $entries['entry_code'] = 'REC' . date('y', strtotime($row['paid_date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
          $entries['entrytype_id'] = '1';
          $entries['number'] = $num;
          $entries['date'] = $row['paid_date'];
          $entries['dr_total'] = $row['amount'];
          $entries['cr_total'] = $row['amount'];
          $entries['narration'] = 'Prasadam(' . $prasadam['ref_no'] . ')' . "\n" . 'name:' . $prasadam['customer_name'] . "\n" . 'NRIC:' . $prasadam['ic_number'] . "\n" . 'email:' . $prasadam['email'] . "\n";
          $entries['inv_id'] = $booking_id;
          $entries['type'] = 8;
          //Insert Entries
          $ent = $this->db->table('entries')->insert($entries);
          $en_id = $this->db->insertID();
          if (!empty($en_id)) {
            // Trade Debtors => Credit
            $eitems_hall_book['entry_id'] = $en_id;
            $eitems_hall_book['ledger_id'] = $cr_id1;
            $eitems_hall_book['amount'] = $row['amount'];
            $eitems_hall_book['dc'] = 'C';
            $eitems_hall_book['details'] = 'Prasadam Amount';
            $this->db->table('entryitems')->insert($eitems_hall_book);
            // PETTY CASH => Debit 
            $eitems_cash_led['entry_id'] = $en_id;
            $eitems_cash_led['ledger_id'] = $paymentmode['ledger_id'];
            $eitems_cash_led['amount'] = $row['amount'];
            $eitems_cash_led['dc'] = 'D';
            $eitems_cash_led['details'] = 'Prasadam Amount';
            $this->db->table('entryitems')->insert($eitems_cash_led);
          }
        } else {
          $succ = false;
          return $succ;
        }
      }
    } else {
      $succ = false;
      return $succ;
    }
  }

  public function send_mail_to_customer($id) {
    $prasadam = $this->db->table("prasadam")->where("id", $id)->get()->getRowArray();
    if (!empty($prasadam['email_id'])) {
      $prasadam_booking_details = $this->db->table('prasadam_booking_details')->select("prasadam_booking_details.*, prasadam_setting.name_eng as prasadam_eng, prasadam_setting.name_tamil as prasadam_tamil")->join('prasadam_setting', 'prasadam_booking_details.prasadam_id = prasadam_setting.id', 'left')->where('prasadam_booking_id', $id)->get()->getResultArray();
      $tmpid = 1;
      $temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
      $temple_title = "Temple " . $temple_details['name'];
      $qr_url = base_url() . "/prasadam/reg/";
      $mail_data['qr_image'] = qrcode_generation($id, $qr_url);
      $mail_data['don_id'] = $id;
      $mail_data['prasadam'] = $prasadam;
      $mail_data['prasadam_booking_details'] = $prasadam_booking_details;
      $mail_data['temple_details'] = $temple_details;
      $message = view('prasadam/mail_template', $mail_data);
      $to = $prasadam['email_id'];
      $subject = $temple_details['name'] . " Prasadam";
      $to_mail = array("prithivitest@gmail.com", $to);
      send_mail_with_content($to_mail, $message, $subject, $temple_title);
    }
  }

  public function payment_process($prsm_id) {
    $prasadam_booking = $this->db->table('prasadam')->where('id', $prsm_id)->get()->getRowArray();
    $prasadam_payment_gateway_datas = $this->db->table('prasadam_payment_gateway_datas')->where('prasadam_id', $prsm_id)->get()->getResultArray();
    if (count($prasadam_payment_gateway_datas) > 0) {
      if ($prasadam_payment_gateway_datas[0]['pay_method'] == 'adyen') {
        if (!empty($prasadam_payment_gateway_datas[0]['request_data'])) {
          $request_data = $prasadam_payment_gateway_datas[0]['request_data'];
          $response = json_decode($request_data, true);
        } else {
          $tmpid = 1;
          $temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
          $result = $this->initiatePayment($prasadam_booking['amount'], $prsm_id, $temple_details['address1'] . $temple_details['address2'], $temple_details['city'], $temple_details['email']);
          $response = json_decode($result, true);
          $payment_gateway_up_data = array();
          $payment_gateway_up_data['request_data'] = $result;
          $payment_gateway_up_data['reference_id'] = $response['id'];
          $this->db->table('prasadam_payment_gateway_datas')->where('id', $prasadam_payment_gateway_datas[0]['id'])->update($payment_gateway_up_data);
        }
        if (!empty($response['url']) && !empty($response['id'])) {
          return redirect()->to($response['url']);
        }
      } elseif ($prasadam_payment_gateway_datas[0]['pay_method'] == 'ipay_merch_qr') {
        //$view_file = 'frontend/ipay88/ipay_merch_qr';
        $view_file = 'frontend/ipay88/ipay_merch_qr_camera';
        $data['prsm_id'] = $prsm_id;
        $data['list'] = $this->db->table('payment_option')->where('status', 1)->get()->getResultArray();
        $data['submit_url'] = '/prasadam_online/initiate_ipay_merch_qr/' . $prsm_id;
        echo view($view_file, $data);
      } elseif ($prasadam_payment_gateway_datas[0]['pay_method'] == 'ipay_merch_online') {
        $view_file = 'frontend/ipay88/ipay_merch_online';
        $data['id'] = $prsm_id;
        $data['controller'] = 'prasadam_online';
        echo view($view_file, $data);
      } else {
        // $redirect_url = base_url() . '/prasadam_online/print_booking/' . $prsm_id;
        $redirect_url = base_url() . '/prasadam_online/print_booking_report_a5/' . $prsm_id;
        return redirect()->to($redirect_url);
      }
    } else {
      $tmpid = 1;
      $temple_details = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
      $result = $this->initiatePayment($prasadam_booking['amount'], $prsm_id, $temple_details['address1'] . $temple_details['address2'], $temple_details['city'], $temple_details['email']);
      $response = json_decode($result, true);
      if (!empty($response['url']) && !empty($response['id'])) {
        $payment_gateway_data = array();
        $payment_gateway_data['prasadam_id'] = $prsm_id;
        $payment_gateway_data['pay_method'] = 'adyen';
        $payment_gateway_data['request_data'] = $result;
        $payment_gateway_data['reference_id'] = $response['id'];
        $this->db->table('prasadam_payment_gateway_datas')->insert($payment_gateway_data);
        $prasadam_payment_gateway_id = $this->db->insertID();
        if (!empty($prasadam_payment_gateway_id)) {
          return redirect()->to($response['url']);
        }
      }
    }
  }

  public function initiatePayment($amount, $orderid, $address, $city, $email) {
    if (file_get_contents('php://input') != '') {
      $request = json_decode(file_get_contents('php://input'), true);
    } else {
      $request = array();
    }
    $apikey = "AQExhmfuXNWTK0Qc+iSGm3I5puqPTYhFHpxGTXFfyXa4nWlGJfnh+XuzwV6dTmmMJv6GnBDBXVsNvuR83LVYjEgiTGAH-09p02SzaBtpvbU0D3ZRFu8cWY44ivj4mqeMXogk0Ogk=-@e*vZIt9AWvaNN:.";
    $merchantAccount = "VivaantechsolutionscomECOM";
    $url = "https://checkout-test.adyen.com/v70/paymentLinks";
    $final_amt = $amount * 100;
    $data = [
      'amount' => [
        'currency' => 'MYR',
        'value' => $final_amt
      ],
      "reference" => $orderid,
      'countryCode' => "MY",
      'shopperReference' => "order_" . $orderid,
      'shopperEmail' => $email,
      'shopperLocale' => "en-US",
      "billingAddress" => [
        "street" => $address,
        "postalCode" => "46000",
        "city" => $city,
        "houseNumberOrName" => "1/23",
        "country" => "MY",
        "stateOrProvince" => "KL"
      ],
      "deliveryAddress" => [
        "street" => $address,
        "postalCode" => "46000",
        "city" => $city,
        "houseNumberOrName" => "1/23",
        "country" => "MY",
        "stateOrProvince" => "KL"
      ],
      'returnUrl' => base_url() . '/prasadam_online/print_booking/' . $orderid,
      'merchantAccount' => $merchantAccount
    ];
    $json_data = json_encode($data);
    $curlAPICall = curl_init();
    curl_setopt($curlAPICall, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curlAPICall, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlAPICall, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($curlAPICall, CURLOPT_URL, $url);
    curl_setopt(
      $curlAPICall,
      CURLOPT_HTTPHEADER,
      array(
        "x-api-key: " . $apikey,
        "Content-Type: application/json",
        "Content-Length: " . strlen($json_data)
      )
    );
    $result = curl_exec($curlAPICall);
    if ($result === false) {
      throw new Exception(curl_error($curlAPICall), curl_errno($curlAPICall));
    }
    curl_close($curlAPICall);
    return $result;
  }

  public function initiatePayment_response($pay_id) {
    if (file_get_contents('php://input') != '') {
      $request = json_decode(file_get_contents('php://input'), true);
    } else {
      $request = array();
    }
    $apikey = "AQExhmfuXNWTK0Qc+iSGm3I5puqPTYhFHpxGTXFfyXa4nWlGJfnh+XuzwV6dTmmMJv6GnBDBXVsNvuR83LVYjEgiTGAH-09p02SzaBtpvbU0D3ZRFu8cWY44ivj4mqeMXogk0Ogk=-@e*vZIt9AWvaNN:.";
    $merchantAccount = "VivaantechsolutionscomECOM";
    $url = "https://checkout-test.adyen.com/v70/paymentLinks/" . $pay_id;
    $curlAPICall = curl_init();
    curl_setopt($curlAPICall, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curlAPICall, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlAPICall, CURLOPT_URL, $url);
    // Api key
    curl_setopt(
      $curlAPICall,
      CURLOPT_HTTPHEADER,
      array(
        "x-api-key: " . $apikey
      )
    );
    $result = curl_exec($curlAPICall);
    if ($result === false) {
      throw new Exception(curl_error($curlAPICall), curl_errno($curlAPICall));
    }
    curl_close($curlAPICall);
    return $result;
  }

  public function ipay88_online_response($prasadam_id) {
    include_once FCPATH . 'app/Libraries/ipay88-master/IPay88.class.php';
    $MerchantCode = 'M01236';
    $MerchantKey = 'HQgUUZLVzg';
    $ipay88 = new \IPay88($MerchantCode);
    $ipay88->setMerchantKey($MerchantKey);
    $response = $ipay88->getResponse();
    //print_r($response);
    if ($response['status']) {
      $prasadam_up_data = array();
      $prasadam_up_data['payment_status'] = 2;
      $this->db->table('prasadam')->where('id', $prasadam_id)->update($prasadam_up_data);
      $this->account_migration($prasadam_id);
      $this->session->setFlashdata('succ', 'Prasadam Successfully Completed');
      $redirect_url = base_url() . '/prasadam_online/print_booking/' . $prasadam_id;
      return redirect()->to($redirect_url);
    } else {
      $this->session->setFlashdata('fail', 'Payment Failed');
      echo '<script>
    window.onunload = refreshParent;
    window.close();
      function refreshParent() {
          window.opener.location.reload();
      }
    </script>';
        }
  }

  public function initiate_ipay_merch_online($prasadam_id) {
    include_once FCPATH . 'app/Libraries/ipay88-master/IPay88.class.php';
    $payment_id = !empty($_REQUEST['payment_id']) ? $_REQUEST['payment_id'] : '';
    $prasadam_booking = $this->db->table('prasadam')->where('id', $prasadam_id)->get()->getRowArray();
    $email = !empty($prasadam_booking['email']) ? $prasadam_booking['email'] : 'dd@ipay88.com.my';
    $name = !empty($prasadam_booking['customer_name']) ? $prasadam_booking['customer_name'] : 'Prithivi';
    $mobile_no = !empty($prasadam_booking['mobile_no']) ? $prasadam_booking['mobile_no'] : '9856734562';
    $description = 'Prasadam';
    $final_amt = $prasadam_booking['amount'];
    $final_amount = number_format($final_amt, '2', '.', '');
    $final_amt_str = (string) ($final_amt * 1000);
    $MerchantCode = 'M01236';
    $MerchantKey = 'HQgUUZLVzg';
    $ref_no = 'PRAS_' . $prasadam_id;
    $refno_pay = $ref_no;
    $module = 'archanai';
    // $final_amount = '1.00';
    // $final_amt_str = '1000';
    $ipay88 = new \IPay88($MerchantCode);
    $ipay88->setMerchantKey($MerchantKey);
    $ipay88->setField('PaymentId', 16);
    $ipay88->setField('RefNo', $refno_pay);
    $ipay88->setField('Amount', $final_amount);
    $ipay88->setField('Currency', 'MYR');
    $ipay88->setField('ProdDesc', $description);
    $ipay88->setField('UserName', $name);
    $ipay88->setField('UserEmail', $email);
    $ipay88->setField('UserContact', (string) $mobile_no);
    $ipay88->setField('Remark', $description);
    $ipay88->setField('Lang', 'utf-8');
    $ipay88->setField('ResponseURL', base_url() . '/prasadam_online/ipay88_online_response/' . $prasadam_id);
    $ipay88->setField('BackendURL', base_url() . '/prasadam_online/ipay88_online_response/' . $prasadam_id);
    $ipay88->generateSignature();
    $ipay88_fields = $ipay88->getFields();
    $data['ipay88_fields'] = $ipay88_fields;
    $data['epayment_url'] = \Ipay88::$epayment_url;
    $view_file = 'frontend/ipay88/ipay_merch_online_process';
    echo view($view_file, $data);
  }

  public function account_migration($ins_id) {
    $yr = date('Y');
    $mon = date('m');
    $data = $this->db->table('prasadam')->where('id', $ins_id)->get()->getRowArray();
    $booking_settings = $this->db->table('booking_setting')->get()->getResultArray();
    $setting = array();
    if (count($booking_settings) > 0) {
      foreach ($booking_settings as $bs) {
        $setting[$bs['meta_key']] = $bs['meta_value'];
      }
    }
    $payment_mode_details = $this->db->table('payment_mode')->where('id', $data['payment_mode'])->get()->getRowArray();
    $sales_group = $this->db->table('groups')->where('code', '4000')->get()->getRowArray();

    if (!empty($sales_group)) {
      $sls_id = $sales_group['id'];
    } else {
      $sls1['parent_id'] = 0;
      $sls1['name'] = 'Sales';
      $sls1['code'] = '4000';
      $sls1['added_by'] = $this->session->get('log_id');
      $this->db->table('groups')->insert($sls1);
      $sls_id = $this->db->insertID();
    }

    $td_ledger = $this->db->table('ledgers')->where('name', 'TRADE RECEIVABLE')->where('group_id', 3)->where('left_code', '1200')->get()->getRowArray();
    if (!empty($td_ledger)) {
      $trade_receivable_id = $td_ledger['id'];
    } else {
      $cled1['group_id'] = 3;
      $cled1['name'] = 'TRADE RECEIVABLE';
      $cled1['code'] = '1200/005';
      $cled1['op_balance'] = '0';
      $cled1['op_balance_dc'] = 'D';
      $cled1['left_code'] = '1200';
      $cled1['right_code'] = '005';
      $this->db->table('ledgers')->insert($cled1);
      $trade_receivable_id = $this->db->insertID();
    }
    $number = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
    if (empty($number) && empty($number1))
      $num = 1;
    else
      $num = $number['number'] + 1;

    $qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();
    $entries['entry_code'] = 'JOR' . date('y', strtotime($data['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
    $entries['date'] = date("Y-m-d", strtotime($data['date']));
    $entries['number'] = $num;
    $entries['entrytype_id'] = '4';
    $entries['dr_total'] = $data['sub_total']; // Assuming 'total_amount' is the field for total booking amount
    $entries['cr_total'] = $data['sub_total'];
    $entries['narration'] = 'Prasadam(' . $data['ref_no'] . ')' . "\n" . 'name:' . $data['customer_name'] . "\n" . 'NRIC:' . $data['ic_no'] . "\n" . 'email:' . $data['email_id'] . "\n";
    $entries['inv_id'] = $ins_id;
    $entries['type'] = '10';
    $ent = $this->db->table('entries')->insert($entries);
    $en_id1 = $this->db->insertID();

    $prasadam_booking_details = $this->db->table('prasadam_booking_details')->where('prasadam_booking_id', $ins_id)->get()->getResultArray();
    foreach ($prasadam_booking_details as $pbd) {
      $prasadam_details = $this->db->table('prasadam_setting')->where('id', $pbd['prasadam_id'])->get()->getRowArray();

      if (!empty($prasadam_details['ledger_id'])) {
        $dr_id = $prasadam_details['ledger_id'];
      } else {
        $ledger1 = $this->db->table('ledgers')->where('name', 'All Sales')->where('group_id', $sls_id)->get()->getRowArray();
        if (!empty($ledger1)) {
          $dr_id = $ledger1['id'];
        } else {
          $right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '4913')->orderBy('right_code', 'desc')->get()->getRowArray();
          $set_right_code = (int) $right_code['right_code'] + 1;
          $set_right_code = sprintf("%04d", $set_right_code);
          $led1['group_id'] = $sls_id;
          $led1['name'] = 'All Sales';
          $led1['left_code'] = '4913';
          $led1['right_code'] = $set_right_code;
          $led1['op_balance'] = '0';
          $led1['op_balance_dc'] = 'D';
          $led_ins1 = $this->db->table('ledgers')->insert($led1);
          $dr_id = $this->db->insertID();
        }
      }
      // Debit the Product's Ledger (dr_id)
      $eitems_d['entry_id'] = $en_id1;
      $eitems_d['ledger_id'] = $dr_id;
      $eitems_d['amount'] = $pbd['total_amount'];
      $eitems_d['details'] = 'Prasadam(' . $data['ref_no'] . ')';
      $eitems_d['dc'] = 'C';
      $cr_res = $this->db->table('entryitems')->insert($eitems_d);
      $debtor_amount += $pbd['total_amount'];
    }

    // Credit Trade Receivable (trade_receivable_id)
    $eitems_c['entry_id'] = $en_id1;
    $eitems_c['ledger_id'] = $trade_receivable_id;
    $eitems_c['amount'] = $debtor_amount;
    $eitems_c['details'] = 'Prasadam(' . $data['ref_no'] . ')';
    $eitems_c['dc'] = 'D';
    $deb_res = $this->db->table('entryitems')->insert($eitems_c);

    $paid_amount = $pbd['total_amount'];
    if (!empty($data['discount_amount'])) {
      $number = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
      if (empty($number) && empty($number1))
        $num = 1;
      else
        $num = $number['number'] + 1;

      $qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();
      $entries['entry_code'] = 'JOR' . date('y', strtotime($data['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
      $entries['date'] = date("Y-m-d", strtotime($data['date']));
      $entries['number'] = $num;
      $entries['entrytype_id'] = '4';
      $entries['dr_total'] = $data['discount_amount']; // Assuming 'total_amount' is the field for total booking amount
      $entries['cr_total'] = $data['discount_amount'];
      $entries['narration'] = 'Prasadam(' . $data['ref_no'] . ')' . "\n" . 'name:' . $data['customer_name'] . "\n" . 'NRIC:' . $data['ic_no'] . "\n" . 'email:' . $data['email_id'] . "\n";
      $entries['inv_id'] = $ins_id;
      $entries['type'] = '10';
      $ent = $this->db->table('entries')->insert($entries);
      $en_id2 = $this->db->insertID();

      $eitems_c = array();
      $eitems_c['entry_id'] = $en_id2;
      $eitems_c['ledger_id'] = $trade_receivable_id;
      $eitems_c['amount'] = $data['discount_amount'];
      $eitems_c['details'] = 'Discount for Prasadam(' . $data['ref_no'] . ')';
      $eitems_c['dc'] = 'C';
      $deb_res = $this->db->table('entryitems')->insert($eitems_c);

      $eitems_disc_ent = array();
      $discount_ledger_id = !empty($setting['discount_prasadam_ledger_id']) ? $setting['discount_prasadam_ledger_id'] : 447;
      $eitems_disc_ent['entry_id'] = $en_id2;
      $eitems_disc_ent['ledger_id'] = $discount_ledger_id;
      $eitems_disc_ent['amount'] = $data['discount_amount'];
      $eitems_disc_ent['is_discount'] = 1;
      $eitems_disc_ent['dc'] = 'D';
      $eitems_disc_ent['details'] = 'Discount for Prasadam(' . $data['ref_no'] . ')';
      $this->db->table('entryitems')->insert($eitems_disc_ent);
      // $tot_amount += $prasadam['discount_amount'];
      $debtor_amount -= $data['discount_amount'];
    }

    $prasadam_booked_count = $this->db->table('prasadam_booked_pay_details')->where('prasadam_id', $ins_id)->get()->getNumRows();
    if ($prasadam_booked_count > 0) {
      $prasadam_booked_detail = $this->db->table('prasadam_booked_pay_details')->where('prasadam_id', $ins_id)->get()->getRowArray();

      $cr_id = $payment_mode_details['ledger_id'];
      $number = $this->db->table('entries')->select('number')->where('entrytype_id', 1)->orderBy('id', 'desc')->get()->getRowArray();
      if (empty($number) && empty($number1))
        $num = 1;
      else
        $num = $number['number'] + 1;

      $qry = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =1 and month (date)='" . $mon . "')")->getRowArray();
      $entries['entry_code'] = 'REC' . date('y', strtotime($data['date'])) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
      $entries['date'] = date("Y-m-d", strtotime($data['date']));
      $entries['number'] = $num;
      $entries['entrytype_id'] = '1';
      $entries['dr_total'] = $prasadam_booked_detail['amount']; // Assuming 'total_amount' is the field for total booking amount
      $entries['cr_total'] = $prasadam_booked_detail['amount'];
      $entries['narration'] = 'Prasadam(' . $data['ref_no'] . ')' . "\n" . 'name:' . $data['customer_name'] . "\n" . 'NRIC:' . $data['ic_no'] . "\n" . 'email:' . $data['email_id'] . "\n";
      $entries['inv_id'] = $ins_id;
      $entries['type'] = '10';
      $ent = $this->db->table('entries')->insert($entries);
      $en_id2 = $this->db->insertID();

      $eitems_d['entry_id'] = $en_id2;
      $eitems_d['ledger_id'] = $trade_receivable_id;
      $eitems_d['amount'] = $prasadam_booked_detail['amount'];
      $eitems_d['details'] = 'Prasadam(' . $data['ref_no'] . ')';
      $eitems_d['dc'] = 'C';
      $cr_res = $this->db->table('entryitems')->insert($eitems_d);

      // Credit Payment Mode (cr_id)
      $eitems_c['entry_id'] = $en_id2;
      $eitems_c['ledger_id'] = $cr_id;
      $eitems_c['amount'] = $prasadam_booked_detail['amount'];
      $eitems_c['details'] = 'Prasadam(' . $data['ref_no'] . ')';
      $eitems_c['dc'] = 'D';
      $deb_res = $this->db->table('entryitems')->insert($eitems_c);

    }
  }

  public function print_booking_report($prsm_id) {

    $id = $prsm_id;
    $data['data'] = $this->db->table('prasadam')->select('prasadam.*')->where('prasadam.id', $id)->get()->getRowArray();
    $tmpid = $this->session->get('profile_id_frend');
    $data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
    $data['booking_details'] = $this->db->table('prasadam_booking_details')
      ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
      ->join('prasadam_group', 'prasadam_group.id = prasadam_booking_details.group_id')
      ->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil, prasadam_group.name as groupname')
      ->where('prasadam_booking_details.prasadam_booking_id', $id)
      ->get()->getResultArray();
    $data['pay_details'] = $this->db->table("prasadam_booked_pay_details")->where("prasadam_id", $id)->get()->getResultArray();
    $data['prasadambooked'] = $this->db->table('prasadam_booking_details')
      ->select('total_amount')
      ->where('id', $id)
      ->get()
      ->getRowArray();
      // echo '<pre>';
      // print_r($data['booking_details']);
      // exit;
    echo view('frontend/prasadam/print_page_a4', $data);
  }

  public function print_booking_report_a5($prsm_id) {

    $id = $prsm_id;
    $data['data'] = $this->db->table('prasadam')->select('prasadam.*')->where('prasadam.id', $id)->get()->getRowArray();
    $tmpid = $this->session->get('profile_id_frend');
    $data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
    $data['booking_details'] = $this->db->table('prasadam_booking_details')
      ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
      ->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil')
      ->where('prasadam_booking_details.prasadam_booking_id', $id)
      ->get()->getResultArray();
    $data['pay_details'] = $this->db->table("prasadam_booked_pay_details")->where("prasadam_id", $id)->get()->getResultArray();
    $data['prasadambooked'] = $this->db->table('prasadam_booking_details')
      ->select('total_amount')
      ->where('id', $id)
      ->get()
      ->getRowArray();
    $diety = $this->db->table('archanai_diety')->where('id', $data['data']['diety_id'])->get()->getRowArray();
    $data['data']['diety_name'] = $diety ? $diety['name'] : '';

    echo view('frontend/prasadam/print_page_a5', $data);
  }

  public function print_booking($prsm_id) {
    $id = $this->request->uri->getSegment(3);
    
    $data['qry1'] = $this->db->table('prasadam')
    ->select('prasadam.*, archanai_diety.name as diety_name_eng, archanai_diety.name_tamil as diety_name_tamil, payment_mode_table.name as payment_mode_name')
    ->join('archanai_diety', 'archanai_diety.id = prasadam.diety_id', 'left')
    ->join('payment_mode as payment_mode_table', 'payment_mode_table.id = prasadam.payment_mode', 'left')
  
      ->where('prasadam.id', $id)
      ->get()
      ->getRowArray();
    $data['qry1_payfor'] = $this->db->table('prasadam_booking_details')
      ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
      ->join('prasadam_group', 'prasadam_group.id = prasadam_booking_details.group_id')
      
      ->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil, prasadam_group.name as groupname')
      ->where('prasadam_booking_details.prasadam_booking_id', $id)
      ->get()->getResultArray();
    $data['dieties'] = $this->db->table('archanai_diety')->get()->getResultArray();
    // $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
    // $data['qrcdoee'] = qrcode_generation($id, $url, 95, 95);
    if ($prasadam['sep_print'] == 1)
      $view_file = 'frontend/prasadam/print_sep';
    else
      // $view_file = 'frontend/prasadam/print_page';
      $view_file = 'frontend/prasadam/print_imin';
    // if ($prasadam['paid_through'] == 'COUNTER') {
    //   if ($prasadam['payment_status'] == '2') {
    //     $tmpid = $this->session->get('profile_id');
    //     $data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
    //     $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
    //     echo view($view_file, $data);
    //   } elseif ($prasadam['payment_status'] == '1') {
    //     $prasadam_payment_gateway_datas = $this->db->table('prasadam_payment_gateway_datas')->where('prasadam_id', $prsm_id)->get()->getRowArray();
    //     if (!empty($prasadam_payment_gateway_datas['reference_id'])) {
    //       $reference_id = $prasadam_payment_gateway_datas['reference_id'];
    //       $result_data = $this->initiatePayment_response($reference_id);
    //       $response_data = json_decode($result_data, true);
    //       $payment_gateway_up_data = array();
    //       $payment_gateway_up_data['response_data'] = $result_data;
    //       $this->db->table('prasadam_payment_gateway_datas')->where('id', $prasadam_payment_gateway_datas['id'])->update($payment_gateway_up_data);
    //       if (!empty($response_data['status'])) {
    //         if ($response_data['status'] == 'completed') {
    //           $prasadam_up_data = array();
    //           $prasadam_up_data['payment_status'] = 2;
    //           $this->db->table('prasadam')->where('id', $id)->update($prasadam_up_data);
    //           $this->account_migration($id);
    //           $tmpid = $this->session->get('profile_id');
    //           $data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
    //           $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
    //           echo view($view_file, $data);
    //         } else {
    //           $prasadam_up_data = array();
    //           $prasadam_up_data['payment_status'] = 3;
    //           $this->db->table('prasadam')->where('id', $id)->update($prasadam_up_data);
    //           redirect()->to("/cancelled_booking");
    //           exit;
    //         }
    //       }
    //     } else {
    //       redirect()->to("/cancelled_booking");
    //       exit;
    //     }
    //   }
    // } else {
    $tmpid = 1;
    $data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
    $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
    echo view($view_file, $data);
    //}
  }

  public function print_booking_sep($prsm_id) {
    $id = $this->request->uri->getSegment(3);
    $data['qry1'] = $this->db->table('prasadam')
      ->select('prasadam.*')
      ->where('prasadam.id', $id)
      ->get()->getRowArray();
    $tmpid = $this->session->get('profile_id');
    $data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
    $data['qry1_payfor'] = $this->db->table('prasadam_booking_details')
      ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
      ->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil')
      ->where('prasadam_booking_details.prasadam_booking_id', $id)
      ->get()->getResultArray();
    // $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
    // $data['qrcdoee'] = qrcode_generation($id, $url, 95, 95);
    echo view('frontend/prasadam/print_sep', $data);
  }

  public function cancelled_booking() {
    echo view('frontend/layout/header');
    echo view('frontend/prasadam/cancelled_booking');
    echo view('frontend/layout/footer');
  }

  public function reprint_booking($id) {
    $data['qry1'] = $prasadam = $this->db->table('prasadam')
      ->select('prasadam.*, payment_mode.name as payment_mode_name')
      ->join('payment_mode', 'payment_mode.id = prasadam.payment_mode', 'left')
      ->where('prasadam.id', $id)
      ->get()
      ->getRowArray();
    $data['qry1_payfor'] = $this->db->table('prasadam_booking_details')
      ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
      ->join('prasadam_group', 'prasadam_group.id = prasadam_booking_details.group_id')

      ->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil,prasadam_group.name as groupname')
      ->where('prasadam_booking_details.prasadam_booking_id', $id)
      ->get()->getResultArray();
    // $view_file = 'frontend/prasadam/print_page';
    $view_file = 'frontend/prasadam/print_imin';
    $tmpid = $this->session->get('profile_id');
    $data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
    $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
    // $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
    // $data['qrcdoee'] = qrcode_generation($id, $url, 95, 95);
    echo view($view_file, $data);
  }

  public function send_whatsapp_msg($id) {
    $data['qry1'] = $prasadam = $this->db->table('prasadam')
      ->select('prasadam.*')
      ->where('prasadam.id', $id)
      ->get()->getRowArray();
    $tmpid = 1;
    $data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
    $data['qry1_payfor'] = $this->db->table('prasadam_booking_details')
      ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
      ->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil')
      ->where('prasadam_booking_details.prasadam_booking_id', $id)
      ->get()->getResultArray();
    // $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
    // $data['qrcdoee'] = qrcode_generation($id, $url, 95, 95);
    $tmpid = 1;
    $data['temple_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
    if (!empty($prasadam['mobile_no'])) {
      $html = view('prasadam/pdf', $data);
      $options = new Options();
      $options->set('isHtml5ParserEnabled', true);
      $options->set(array('isRemoteEnabled' => true));
      $options->set('isPhpEnabled', true);
      // echo $html;
      $dompdf = new Dompdf($options);
      $dompdf->loadHtml($html);
      $dompdf->setPaper('A4', 'portrait');
      $dompdf->render();
      $filePath = FCPATH . 'uploads/documents/invoice_prasadam_' . $id . '.pdf';

      file_put_contents($filePath, $dompdf->output());
      $message_params = array();
      $message_params[] = date('d M, Y', strtotime($prasadam['date']));
      $message_params[] = date('d M, Y', strtotime($prasadam['collection_date']));
      $message_params[] = date('h:i A', strtotime($prasadam['collection_date'] . ' ' . $prasadam['start_time']));
      $message_params[] = $prasadam['amount'];
      $media['url'] = base_url() . '/uploads/documents/invoice_prasadam_' . $id . '.pdf';
      $media['filename'] = 'prasadam_invoice.pdf';
      $mobile_number = $prasadam['mobile_no'];
      //$mobile_number = '+919092615446';
      // print_r($mobile_number);
      // print_r($message_params);
      // print_r($media);
      // die; 
      $whatsapp_resp = whatsapp_aisensy($mobile_number, $message_params, 'prasadam_live', $media);
      //print_r($whatsapp_resp);
    }
  }

  public function print_imin($prsm_id) {
    $id = $this->request->uri->getSegment(3);
    $data['qry1'] = $prasadam = $this->db->table('prasadam')
      ->select('prasadam.*')
      ->where('prasadam.id', $id)
      ->get()->getRowArray();
    $data['qry1_payfor'] = $this->db->table('prasadam_booking_details')
      ->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
      ->select('prasadam_booking_details.*,prasadam_setting.name_eng,prasadam_setting.name_tamil')
      ->where('prasadam_booking_details.prasadam_booking_id', $id)
      ->get()->getResultArray();
    // $url = "https://maps.app.goo.gl/SyWKRkVEzrTDa1BB8";
    // $data['qrcdoee'] = qrcode_generation($id, $url, 95, 95);
    if ($prasadam['sep_print'] == 1)
      $view_file = 'frontend/prasadam/print_imin_sep';
    else
      $view_file = 'frontend/prasadam/print_imin';
    if ($prasadam['paid_through'] == 'COUNTER') {
      if ($prasadam['payment_status'] == '2') {
        $tmpid = $this->session->get('profile_id');
        $data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
        $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
        echo view($view_file, $data);
      } elseif ($prasadam['payment_status'] == '1') {
        $prasadam_payment_gateway_datas = $this->db->table('prasadam_payment_gateway_datas')->where('prasadam_id', $prsm_id)->get()->getRowArray();
        if (!empty($prasadam_payment_gateway_datas['reference_id'])) {
          $reference_id = $prasadam_payment_gateway_datas['reference_id'];
          $result_data = $this->initiatePayment_response($reference_id);
          $response_data = json_decode($result_data, true);
          $payment_gateway_up_data = array();
          $payment_gateway_up_data['response_data'] = $result_data;
          $this->db->table('prasadam_payment_gateway_datas')->where('id', $prasadam_payment_gateway_datas['id'])->update($payment_gateway_up_data);
          if (!empty($response_data['status'])) {
            if ($response_data['status'] == 'completed') {
              $prasadam_up_data = array();
              $prasadam_up_data['payment_status'] = 2;
              $this->db->table('prasadam')->where('id', $id)->update($prasadam_up_data);
              $this->account_migration($id);
              $tmpid = $this->session->get('profile_id');
              $data['temp_details'] = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();
              $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
              echo view($view_file, $data);
            } else {
              $prasadam_up_data = array();
              $prasadam_up_data['payment_status'] = 3;
              $this->db->table('prasadam')->where('id', $id)->update($prasadam_up_data);
              redirect()->to("/cancelled_booking");
              exit;
            }
          }
        } else {
          redirect()->to("/cancelled_booking");
          exit;
        }
      }
    } else {
      $tmpid = 1;
      $data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
      $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
      echo view($view_file, $data);
    }
  }

}
