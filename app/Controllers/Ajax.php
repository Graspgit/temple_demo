<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\RequestModel;
use Exception;

class Ajax extends BaseController
{
	function __construct(){
		parent::__construct();
		helper('url');
		helper('common_helper');
	}
	public function save_booking(){
		// echo "<pre>";
		// print_r($_POST);
		// exit;
		$resp = array('success' => true, 'data' => array('status' => true));
		try{
			if(!empty($_REQUEST['save_booking'])) $request_all_data = $_REQUEST;
			else $request_all_data = json_decode(file_get_contents('php://input'), true);
			
			if(!empty($request_all_data['save_booking'])){
				$this->db->transStart();
				if(!empty($this->session->get('log_id')) || !empty($request_all_data['user_id'])){

					$user_id = !empty($this->session->get('log_id')) ? $this->session->get('log_id') : $request_all_data['user_id'];
					if(!empty($request_all_data['booking_slot']) && !empty($request_all_data['booking_date']) && !empty($request_all_data['booking_type']) && !empty($request_all_data['name']) && !empty($request_all_data['mobile_code']) && !empty($request_all_data['mobile_no']) && !empty($request_all_data['packages']) && !empty($request_all_data['payment_type'])){
						$payment_type = trim($request_all_data['payment_type']);
						$payment_mode = $request_all_data['payment_mode'];
						if($payment_type == 'partial'){
							if(!empty($request_all_data['payment_details'])) $payment_details = $request_all_data['payment_details'];
							else{
								$resp['success'] = true;
								$resp['data']['status'] = false;
								$resp['data']['message'] = 'Please Fill Payment details.';
								$resp['data']['message_type'] = 'error';
								header('Content-Type: application/json; charset=utf-8');
								echo json_encode($resp);
								exit;
							}
						}elseif($payment_type == 'full'){
							if(!empty($request_all_data['payment_mode'])) $payment_mode = $request_all_data['payment_mode'];
							else{
								$resp['success'] = true;
								$resp['data']['status'] = false;
								$resp['data']['message'] = 'Please Fill Payment details.';
								$resp['data']['message_type'] = 'error';
								header('Content-Type: application/json; charset=utf-8');
								echo json_encode($resp);
								exit;
							}
						}elseif($payment_type == 'only_booking'){
							if(!empty($request_all_data['total_amt'])) $payment_mode = '';
							else{
								$resp['success'] = true;
								$resp['data']['status'] = false;
								$resp['data']['message'] = 'Please Fill Amount details.';
								$resp['data']['message_type'] = 'error';
								header('Content-Type: application/json; charset=utf-8');
								echo json_encode($resp);
								exit;
							}
						}else{
							$resp['success'] = true;
							$resp['data']['status'] = false;
							$resp['data']['message'] = 'Invalid Payment Type.';
							$resp['data']['message_type'] = 'error';
							header('Content-Type: application/json; charset=utf-8');
							echo json_encode($resp);
							exit;
						}
						$booking_ins_data = array();
						$booking_ins_data['booking_type'] = $booking_type = trim($request_all_data['booking_type']);
						$booking_ins_data['booking_date'] = trim($request_all_data['booking_date']);
						$booking_ins_data['name'] = trim($request_all_data['name']);
						$booking_ins_data['mobile_code'] = trim($request_all_data['mobile_code']);
						$booking_ins_data['mobile_no'] = trim($request_all_data['mobile_no']);
						$booking_ins_data['rasi_id'] = trim($request_all_data['rasi_id']);
						$booking_ins_data['natchathiram_id'] = trim($request_all_data['natchathra_id']);
						$booking_ins_data['deposit_amount'] = $deposit_amt = !empty($request_all_data['deposit_amt']) ? (float) $request_all_data['deposit_amt'] :  0;
						$booking_ins_data['payment_type'] = $payment_type;
						$booking_ins_data['entry_date'] = !empty($request_all_data['entry_date']) ? trim($request_all_data['entry_date']) : date('Y-m-d');
						$booking_ins_data['booking_through'] = !empty($request_all_data['booking_through']) ? trim($request_all_data['booking_through']) : 'DIRECT';
						$booking_ins_data['booking_status'] = 0;
						$booking_ins_data['payment_status'] = 0;

						if (!empty($request_all_data['brideName']))
							$booking_brides_data['brideName'] = trim($request_all_data['brideName']);
						if (!empty($request_all_data['brideDOB']))
							$booking_brides_data['brideDOB'] = trim($request_all_data['brideDOB']);
						if (!empty($request_all_data['brideIC']))
							$booking_brides_data['brideIC'] = trim($request_all_data['brideIC']);
						if (!empty($request_all_data['groomName']))
							$booking_brides_data['groomName'] = trim($request_all_data['groomName']);
						if (!empty($request_all_data['groomDOB']))
							$booking_brides_data['groomDOB'] = trim($request_all_data['groomDOB']);
						if (!empty($request_all_data['groomIC']))
							$booking_brides_data['groomIC'] = trim($request_all_data['groomIC']);
						if(!empty($request_all_data['address'])) $booking_ins_data['address'] = trim($request_all_data['address']);
						if(!empty($request_all_data['email'])) $booking_ins_data['email'] = trim($request_all_data['email']);
						if(!empty($request_all_data['ic_number'])) $booking_ins_data['ic_number'] = trim($request_all_data['ic_number']);
						if(!empty($request_all_data['description'])) $booking_ins_data['description'] = trim($request_all_data['description']);
						if ($booking_type == 1) $booking_ins_data['venue'] = trim($request_all_data['venue']);
						if ($booking_type == 2)
							$booking_ins_data['deity_id'] = trim($request_all_data['deity_id']);
						if($booking_ins_data['entry_date'] <= $booking_ins_data['booking_date']){
							if(in_array($booking_ins_data['booking_through'], array('DIRECT', 'COUNTER', 'ONLINE', 'KIOSK', 'APP'))){
								$this->requestmodel = new RequestModel();
								$ip = $this->requestmodel->getIpAddress();
								$booking_ins_data['ip'] = $ip;
								if ($ip != 'unknown') {
									$ip_details = $this->requestmodel->getLocation($ip);
									$booking_ins_data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
									$booking_ins_data['ip_details'] = json_encode($ip_details);
								} 
								$booking_ins_data['created_by'] = $user_id;
								$booking_ins_data['modified_by'] = $user_id;

								$res = $this->db->table("templebooking")->insert($booking_ins_data);
								//$whatsapp_resp = whatsapp_aisensy($data['mobile_number'], [], 'success_message1');
								if ($res) {
									$booking_id = $this->db->insertID();
									$this->sub_total = 0;
									$this->total_amount = 0;
									$this->paid_amount = 0;
									$this->final_total = 0;
									if($booking_ins_data['booking_type'] == 1) $ref_no = 'HALL';
									elseif($booking_ins_data['booking_type'] == 2) $ref_no = 'UBAY';
									elseif($booking_ins_data['booking_type'] == 3) $ref_no = 'SANN';
									else $ref_no = 'TEMP';
									$ref_no .= str_pad($booking_id, 16, 0, STR_PAD_LEFT);
									$booking_ref_data = array();
									$booking_ref_data['ref_no'] = $ref_no;
									$this->db->table("templebooking")->where('id', $booking_id)->update($booking_ref_data);

									if (!empty($request_all_data['abishegam'])) {
										$abishegam_details = isset($request_all_data['abishegam']) ? $request_all_data['abishegam'] : [];
										$total_amt = 0;
										foreach ($abishegam_details as $abishegam ) {
											$deities = $this->db->table("archanai_diety")->where('id', $abishegam['deity_id'])->get()->getRowArray();
											$abishegam_data['booking_id'] = $booking_id;
											$abishegam_data['booking_type'] = $booking_type;
											$abishegam_data['deity_id'] = $abishegam['deity_id'];
											$abishegam_data['name'] = $deities['name'];
											$abishegam_data['amount'] = $abishegam['amount'];
											$this->db->table("booked_abishegam_details")->insert($abishegam_data);
											$total_amt += $abishegam['amount'];
										}
										$this->total_amount += $total_amt;
									}

									if (!empty($request_all_data['homam'])) {
										$homam_details = isset($request_all_data['homam']) ? $request_all_data['homam'] : [];
										$total_amt = 0;
										foreach ($homam_details as $homam ) {
											$deities = $this->db->table("archanai_diety")->where('id', $homam['deity_id'])->get()->getRowArray();
											$homam_data['booking_id'] = $booking_id;
											$homam_data['booking_type'] = $booking_type;
											$homam_data['deity_id'] = $homam['deity_id'];
											$homam_data['name'] = $deities['name'];
											$homam_data['amount'] = $homam['amount'];
											$this->db->table("booked_homam_details")->insert($homam_data);
											$total_amt += $homam['amount'];
										}
										$this->total_amount += $total_amt;
									}

									if (!empty($request_all_data['extra_charges'])) {
										$extra_details = isset($request_all_data['extra_charges']) ? $request_all_data['extra_charges'] : [];
										$total_amt = 0;
										foreach ($request_all_data['extra_charges'] as $charges ) {
											$extra_charge_data['booking_id'] = $booking_id;
											$extra_charge_data['booking_type'] = $booking_type;
											$extra_charge_data['description'] = $charges['desc'];
											$extra_charge_data['amount'] = $charges['amount'];
											$extra_charge_data['ledger_id'] = 223;
											$this->db->table("booked_extra_charges")->insert($extra_charge_data);
											$total_amt += $charges['amount'];
										}
										$this->total_amount += $total_amt;
									}

									if(!$this->save_booking_slot($booking_id, $request_all_data['booking_slot'])){
										$this->db->transRollback();
										$resp['success'] = true;
										$resp['data']['status'] = false;
										$resp['data']['message'] = 'Invalid Slot';
										// $resp['data']['message_type'] = 'error';
										header('Content-Type: application/json; charset=utf-8');
										echo json_encode($resp);
										exit;
									}

									if(!empty($request_all_data['add_on'])){
										if(!$this->save_booking_addon($booking_id, $request_all_data['add_on'])){
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Add on';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									}

									if (!empty($request_all_data['free_prasadam'])) {
										if (!$this->save_free_prasadam($booking_id, $request_all_data)) {
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Free Prasadam';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									}

									if (!empty($request_all_data['prasadam'])) {
										if (!$this->save_prasadam($booking_id, $request_all_data, $booking_type)) {
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Prasadam';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									}

									if(!empty($request_all_data['annathanam'])){
										if(!$this->save_annathanam($booking_id, $request_all_data, $booking_type)){
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Annathanam Details';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									}

									if(!$this->save_booking_packages($booking_id, $request_all_data['packages'], $request_all_data['pack_amount'], $booking_ins_data['booking_type'])){
										$this->db->transRollback();
										$resp['success'] = true;
										$resp['data']['status'] = false;
										$resp['data']['message'] = 'Invalid Package';
										// $resp['data']['message_type'] = 'error';
										header('Content-Type: application/json; charset=utf-8');
										echo json_encode($resp);
										exit;
									}
									$this->sub_total = $this->total_amount;

									$devotee_id = $this->devotee_save($booking_ins_data);
									if (!empty($devotee_id)){
										if ($booking_type == 1) $type = "Hall Booking";
										else $type = "Ubayam";

										$activity_details = json_encode([
											'type' => $type . 'Booked',
											'booking_id' => $booking_id
										]);
										$module_type = $booking_type == 1 ? 5 : 6;

										$this->save_activity_log($devotee_id, 4, $activity_details, $module_type, $booking_ins_data['booking_through'], $booking_ins_data['created_by']);
									}

									if (!empty($request_all_data['family'])) {
										$family_details = isset($request_all_data['family']) ? $request_all_data['family'] : [];
										
										$existing_family = $this->db->table('devotee_family_details')->where('devotee_id', $devotee_id)->get()->getResultArray();
										$existing_family_lookup = [];
										foreach ($existing_family as $member) {
											$existing_family_lookup[$member['dob'] . '-' . $member['name'] . '-' . $member['relationship']] = true;
										}

										foreach ($family_details as $family) {
											$familyData = array();
											$familyData['booking_id'] = $booking_id;
											$familyData['booking_type'] = $booking_type;
											$familyData['name'] = trim($family['name']);
											$familyData['dob'] = trim($family['dob']);
											$familyData['relationship'] = trim($family['relationship']);
											$familyData['rasi_id'] = trim($family['rasi_id']);
											$familyData['natchathram_id'] = trim($family['natchathra_id']);
											$this->db->table('booked_family_details')->insert($familyData);

											$unique_key = trim($family['dob']) . '-' . trim($family['name']) . '-' . trim($family['relationship']);
											
											if (!isset($existing_family_lookup[$unique_key])) {
												$dev_family = array();
												$dev_family['devotee_id'] = $devotee_id;
												$dev_family['name'] = trim($family['name']);
												$dev_family['dob'] = trim($family['dob']);
												$dev_family['relationship'] = trim($family['relationship']);
												$dev_family['rasi_id'] = trim($family['rasi_id']);
												$dev_family['natchathra_id'] = trim($family['natchathra_id']);
												$dev_family['added_through'] = $booking_ins_data['booking_through'];
												$dev_family['created_by'] = $user_id;
												$dev_family['created_at'] = date('Y-m-d H:i:s');
												$this->db->table('devotee_family_details')->insert($dev_family);

												$existing_family_lookup[$unique_key] = true;
											}
										}
									}

									if(!empty($request_all_data['discount_amount'])){
										$this->total_amount -= $request_all_data['discount_amount'];
									}
									if(!empty($deposit_amt)){
										if(!$this->save_deposit_payment($deposit_amt, $booking_id, $payment_type, $payment_details, $booking_ins_data['booking_type'], $ref_no, $booking_ins_data['booking_through'], $payment_mode)){
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Deposit Payment';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									}

									if($payment_type == 'partial'){
										if(!$this->save_booking_payment($deposit_amt, $booking_id, $payment_type, $payment_details, $booking_ins_data['booking_type'], $ref_no, $booking_ins_data['booking_through'], $devotee_id, $booking_ins_data['created_by'])){
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Payment, Please check the amount first';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									}elseif($payment_type == 'full'){
										if(!$this->save_booking_payment($deposit_amt, $booking_id, $payment_type, array(), $booking_ins_data['booking_type'], $ref_no, $booking_ins_data['booking_through'], $devotee_id, $booking_ins_data['created_by'], $payment_mode)){
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Invalid Payment in full';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
									}elseif($payment_type !== 'only_booking'){
										$this->db->transRollback();
										$resp['success'] = true;
										$resp['data']['status'] = false;
										$resp['data']['message'] = 'Invalid Payment';
										// $resp['data']['message_type'] = 'error';
										header('Content-Type: application/json; charset=utf-8');
										echo json_encode($resp);
										exit;
									}
									
									if(!empty($this->total_amount)){
										$booking_ref_data = array();
										if(!empty($request_all_data['discount_amount'])){
											$booking_ref_data['discount_amount'] = $request_all_data['discount_amount'];
										}else $booking_ref_data['discount_amount'] = 0;
										$this->final_total = $this->total_amount;
										$this->total_amount -= $deposit_amt;
										$booking_ref_data['amount'] = $this->total_amount;
										$booking_ref_data['paid_amount'] = !empty($this->paid_amount) ? $this->paid_amount : 0;
										$booking_ref_data['total_amount'] = $this->final_total;
										if($this->final_total < $this->paid_amount){
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Payment amount is not greater than total amount';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
										if($payment_type != 'only_booking' && empty($this->paid_amount)){
											$this->db->transRollback();
											$resp['success'] = true;
											$resp['data']['status'] = false;
											$resp['data']['message'] = 'Payment amount is not empty';
											// $resp['data']['message_type'] = 'error';
											header('Content-Type: application/json; charset=utf-8');
											echo json_encode($resp);
											exit;
										}
										if($this->final_total <= $this->paid_amount) $booking_ref_data['payment_status'] = 2;
										elseif($this->paid_amount == 0) $booking_ref_data['payment_status'] = 0;
										else $booking_ref_data['payment_status'] = 1;
										$booking_ref_data['booking_status'] = 1;
										$this->db->table("templebooking")->where('id', $booking_id)->update($booking_ref_data);
										unset($this->total_amount);
										unset($this->paid_amount);
									}else{
										$this->db->transRollback();
										$resp['success'] = true;
										$resp['data']['status'] = false;
										$resp['data']['message'] = 'Invalid Package or Payment';
										// $resp['data']['message_type'] = 'error';
										header('Content-Type: application/json; charset=utf-8');
										echo json_encode($resp);
										exit;
									}

									if(!$this->account_migration($booking_id)){
										$this->db->transRollback();
										$resp['success'] = true;
										$resp['data']['status'] = false;
										$resp['data']['message'] = 'Invalid Account Migration';
										// $resp['data']['message_type'] = 'error';
										header('Content-Type: application/json; charset=utf-8');
										echo json_encode($resp);
										exit;
									}
									$this->db->table('booked_bride_details')->delete(['booking_id' => $booking_id]);
									if (!empty($booking_brides_data['brideName']) && !empty($booking_brides_data['brideDOB']) && !empty($booking_brides_data['brideIC'])) {
										$booking_brides_ins_data = array();
										$booking_brides_ins_data['booking_id'] = $booking_id;
										$booking_brides_ins_data['booking_type'] = $booking_ins_data['booking_type'];
										$booking_brides_ins_data['bride_type'] = 'bride';
										$booking_brides_ins_data['name'] = $booking_brides_data['brideName'];
										$booking_brides_ins_data['nric'] = $booking_brides_data['brideIC'];
										$booking_brides_ins_data['dob'] = $booking_brides_data['brideDOB'];
										$this->db->table("booked_bride_details")->insert($booking_brides_ins_data);

									}
									if (!empty($booking_brides_data['groomName']) && !empty($booking_brides_data['groomDOB']) && !empty($booking_brides_data['groomIC'])) {
										$booking_brides_ins_data = array();
										$booking_brides_ins_data['booking_id'] = $booking_id;
										$booking_brides_ins_data['booking_type'] = $booking_ins_data['booking_type'];
										$booking_brides_ins_data['bride_type'] = 'groom';
										$booking_brides_ins_data['name'] = $booking_brides_data['groomName'];
										$booking_brides_ins_data['nric'] = $booking_brides_data['groomIC'];
										$booking_brides_ins_data['dob'] = $booking_brides_data['groomDOB'];
										$this->db->table("booked_bride_details")->insert($booking_brides_ins_data);
									}
									$resp['success'] = true;
									$resp['data']['status'] = true;
									$resp['data']['message'] = 'Your Booking confirmed.';
									$resp['data']['booking_id'] = $booking_id;
								}else{
									$resp['success'] = true;
									$resp['data']['status'] = false;
									$resp['data']['message'] = 'Please contact Administrator.';
									// $resp['data']['message_type'] = 'error';
									header('Content-Type: application/json; charset=utf-8');
									echo json_encode($resp);
									exit;
								}
							}else{
								$resp['success'] = true;
								$resp['data']['status'] = false;
								$resp['data']['message'] = 'Booking only allowed in DIRECT, COUNTER, KIOSK, APP';
								// $resp['data']['message_type'] = 'error';
								header('Content-Type: application/json; charset=utf-8');
								echo json_encode($resp);
								exit;
							}
						}else{
							$resp['success'] = true;
							$resp['data']['status'] = false;
							$resp['data']['message'] = 'Can\'t book the previous day';
							// $resp['data']['message_type'] = 'error';
							header('Content-Type: application/json; charset=utf-8');
							echo json_encode($resp);
							exit;
						}
					}else{
						$resp['success'] = true;
						$resp['data']['status'] = false;
						$resp['data']['message'] = 'Please Fill All Required fields(some details)';
						// $resp['data']['message_type'] = 'error';
						header('Content-Type: application/json; charset=utf-8');
						echo json_encode($resp);
						exit;
					}
				}else{
					$resp['success'] = true;
					$resp['data']['status'] = false;
					$resp['data']['message'] = 'Please Login or Pass Auth id';
					// $resp['data']['message_type'] = 'error';
					header('Content-Type: application/json; charset=utf-8');
					echo json_encode($resp);
					exit;
				}
				$this->db->transComplete();
				if($this->db->transStatus() === FALSE) {
					throw new Exception('Transaction Failed');
				}
				$resp['success'] = true;
				$resp['data']['status'] = true;
				$resp['data']['message'] = 'Your Booking confirmed.';
				$resp['data']['booking_id'] = $booking_id;
				if(!empty($request_all_data['print'])) $resp['data']['print'] = 1;
				//if(!empty($booking_id) && !empty($booking_type)) $this->send_whatsapp_msg_booking($booking_id, $booking_type);
			
			} else {
				throw new Exception("Missing save_booking parameter.");
			}
		} catch (Exception $e) {
			$this->db->transRollback(); // Rollback the transaction if an error occurs
			$resp['success'] = false;
			$resp['data']['status'] = false;
			$resp['data']['message'] = $e->getMessage();
			// $resp['data']['message_type'] = 'error';
			// log_message('error', $e->getMessage());
			//throw $e; 
		}

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($resp);
		exit;
	}

  	public function devotee_save($data) {
		if (!empty($data['name']) && !empty($data['mobile_code']) && !empty($data['mobile_no'])) {
			$existing_devotee = $this->db->table('devotee_management')
										->where('phone_code', $data['mobile_code'])
										->where('phone_number', $data['mobile_no'])
										->get()
										->getRowArray();

			if ($existing_devotee) {
				$update_data = [];

				if (empty($existing_devotee['dob']) && !empty($data['dob']) ) {
					$update_data['dob'] = $data['dob'];
				}
				if (empty($existing_devotee['email']) && !empty($data['email'])) {
					$update_data['email'] = $data['email'];
				}
				if (empty($existing_devotee['ic_no']) && !empty($data['ic_number'])) {
					$update_data['ic_no'] = $data['ic_number'];
				}
				if (empty($existing_devotee['address']) && !empty($data['address'])) {
					$update_data['address'] = $data['address'];
				}
				if (empty($existing_devotee['rasi_id']) && !empty($data['rasi_id'])) {
					$update_data['rasi_id'] = $data['rasi_id'];
				}
				if (empty($existing_devotee['natchathra_id']) && !empty($data['natchathiram_id'])) {
					$update_data['natchathra_id'] = $data['natchathiram_id'];
				}

				if ($existing_devotee['is_member'] == 0) {
					$mobile = $data['mobile_code'] . $data['mobile_no'];
					$member = $this->db->table('member')->where('mobile', $mobile)->get()->getRowArray();
					if ($member) {
						$update_data = [
						'is_member' => 1,
						'member_id' => $member['id']
						];
					}
				}

				if (!empty($update_data)) {
					$update_data['updated_by'] = $data['created_by'];
					$update_data['updated_at'] = date('Y-m-d H:i:s');
					$dvt_update = $this->db->table('devotee_management')->where('id', $existing_devotee['id'])->update($update_data);

					if ($dvt_update) {
						$updated_fields = [];

						if (isset($update_data['dob'])) {
							$updated_fields['dob'] = $data['dob'];
						}
						if (isset($update_data['email'])) {
							$updated_fields['email'] = $data['email'];
						}
						if (isset($update_data['address'])) {
							$updated_fields['address'] = $data['address'];
						}
						if (isset($update_data['ic_no'])) {
							$updated_fields['ic_no'] = $data['ic_number'];
						}
						if (isset($update_data['rasi_id'])) {
							$updated_fields['rasi_id'] = $data['rasi_id'];
						}
						if (isset($update_data['natchathra_id'])) {
							$updated_fields['natchathra_id'] = $data['natchathiram_id'];
						}

						$activity_details = json_encode([
							'type' => 'Devotee updated',
							'updated_fields' => $updated_fields 
						]);
						$module_type = $data['booking_type'] == 1 ? 5 : 6;

						$this->save_activity_log($existing_devotee['id'], 2, $activity_details, $module_type, null, $update_data['updated_by']);
					}
				}
				return $existing_devotee['id'];

			} else {
				$new_devotee = [
					'name' => !empty($data['name']) ? $data['name'] : null,
					'dob' => !empty($data['dob']) ? $data['dob'] : null,
					'phone_code' => !empty($data['mobile_code']) ? $data['mobile_code'] : null,
					'phone_number' => !empty($data['mobile_no']) ? $data['mobile_no'] : null,
					'email' => !empty($data['email']) ? $data['email'] : null,
					'ic_no' => !empty($data['ic_number']) ? $data['ic_number'] : null,
					'address' => !empty($data['address']) ? $data['address'] : null,
					'state' => !empty($data['state']) ? $data['state'] : null,
					'pincode' => !empty($data['pincode']) ? $data['pincode'] : null,
					'rasi_id' => !empty($data['rasi_id']) ? $data['rasi_id'] : null,
					'natchathra_id' => !empty($data['natchathiram_id']) ? $data['natchathiram_id'] : null,
					'user_module_tag' => $data['booking_type'] == 1 ? 5 : 6,
					'added_through' => !empty($data['booking_through']) ? $data['booking_through'] : 'COUNTER', 
					'created_by' => !empty($data['created_by']) ? $data['created_by'] : null,
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
					$this->save_activity_log($devotee_id, 1, $activity_details, $new_devotee['user_module_tag'], $new_devotee['added_through'], $new_devotee['created_by']);
				}
				return $devotee_id;
			}
		}
	}

  	private function save_activity_log($devotee_id, $activity_type, $activity_fields, $module_type, $added_through = null, $created_by = null) {
		$activity = array();
		$activity['devotee_id'] = $devotee_id;
		$activity['date'] = date('Y-m-d');
		$activity['time'] = date('H:i:s');  
		$activity['module_type'] = $module_type; 
		$activity['activity_type'] = $activity_type; 
		$activity['details'] = $activity_fields;
		$activity['added_through'] = !empty($added_through) ? $added_through : 'COUNTER';
		$activity['created_by'] = $created_by;
		$activity['created_at'] = date('Y-m-d H:i:s');

		$this->db->table('devotee_activity')->insert($activity);
	}

	public function save_prasadam($booking_id, $request_all_data, $booking_type) {
		$succ = true;

		if (!empty($request_all_data['prasadam'])) {
			$customer_name = $request_all_data['name'];
			$date = $request_all_data['dt'];
			$collection_date = $request_all_data['booking_date'];
			$payment_mode = $request_all_data['payment_mode'];

			$yr = date('Y');
			$mon = date('m');
			$query = $this->db->query("SELECT ref_no FROM prasadam WHERE id=(SELECT MAX(id) FROM prasadam WHERE YEAR(date)='" . $yr . "' AND MONTH(date)='" . $mon . "')")->getRowArray();
			$data['ref_no'] = 'PR' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
			$data['date'] = date('Y-m-d', strtotime($request_all_data['dt']));
			$data['booking_type'] = $booking_type;
			$data['booking_id'] = $booking_id;
			$data['customer_name'] = $customer_name;
			$mble_phonecode = !empty($request_all_data['mobile_code']) ? $request_all_data['mobile_code'] : "";
			$mble_number = !empty($request_all_data['mobile_no']) ? $request_all_data['mobile_no'] : "";
			$data['mobile_no'] = $mble_phonecode . $mble_number;
			$data['total_amount'] = 0;
			$data['paid_amount'] = 0;
			$data['collection_date'] = $collection_date;
			$data['booking_status'] = 1;
			$data['payment_type'] = 'full';
			$data['payment_status'] = 2;
			$data['paid_through'] = !empty($request_all_data['booking_through']) ? trim($request_all_data['booking_through']) : 'DIRECT';
			$data['added_by'] = $request_all_data['user_id'];
			$data['payment_mode'] = $payment_mode;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data['updated_at'] = date('Y-m-d H:i:s');
			$slots = $this->db->table("booked_slot")->where('booking_id', $booking_id)->get()->getRowArray();
			$time_session = ($request_all_data['time'] == 'Breakfast') ? "AM" : "PM";
			$data['session'] = $request_all_data['time'];
			$data['serve_time'] = $request_all_data['hour'] . ':' . $request_all_data['minute'] .' '. $time_session;
			//$this->db->transStart();

			$res = $this->db->table('prasadam')->insert($data);
			$ins_id = $this->db->insertID();

			if ($res) {
				if (!empty($request_all_data['prasadam'])) {
					$prasadam_details = isset($request_all_data['prasadam']) ? $request_all_data['prasadam'] : [];
					foreach ($prasadam_details as $prasadam) {
						$prsm_set = $this->db->table('prasadam_setting')->where('id', $prasadam['id'])->get()->getRowArray();
						$prasadam_tot_amt += $prasadam['total_amount'];
						$data_prdm_book = [
							'prasadam_booking_id' => $ins_id,
							'prasadam_id' => $prasadam['id'],
							'quantity' => $prasadam['quantity'],
							'created' => date('Y-m-d H:i:s'),
							'amount' => $prasadam['amount'],
							'total_amount' => $prasadam['total_amount']
						];
						$res_2 = $this->db->table('prasadam_booking_details')->insert($data_prdm_book);

						$settings = $this->db->table('settings')->where('type', 5)->where('setting_name', 'enable_madapalli')->get()->getRowArray();

						if ($settings['setting_value'] == 1) {
							$madapalli_details['date'] = $collection_date;
							$madapalli_details['type'] = 1;
							$madapalli_details['booking_id'] = $ins_id;
							$madapalli_details['product_id'] = $prasadam['id'];
							$madapalli_details['quantity'] = $prasadam['quantity'];
							$madapalli_details['amount'] = $prasadam['total_amount'];
							$madapalli_details['session'] = $data['session'];
                			$madapalli_details['serve_time'] = $data['serve_time'];
							$madapalli_details['customer_name'] = $customer_name;
							$madapalli_details['customer_mobile'] = $mble_phonecode . $mble_number;
							$madapalli_details['pro_name_eng'] = $prsm_set['name_eng'];
							$madapalli_details['status'] = 0;
							$madapalli_details['created_by'] = $request_all_data['user_id'];
							$madapalli_details['created_at'] = date('Y-m-d H:i:s');
							$madapalli_details['updated_at'] = date('Y-m-d H:i:s');
							$res_m1 = $this->db->table('madapalli_booking_details')->insert($madapalli_details);

							if ($res_m1) {
								$preparation_details = $this->db->table('madapalli_preparation_details')->where('date', $collection_date)->where('type', 1)->get()->getResultArray();
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
										'date' => $collection_date,
										'type' => 1,
										'session' => $data['session'],
										'product_id' => $madapalli_details['product_id'],
										'pro_name_eng' => $prsm_set['name_eng'],
										'pro_name_tamil' => $prsm_set['name_tamil'],
										'quantity' => $madapalli_details['quantity'],
										'status' => 0,
										'created_by' => $request_all_data['user_id'],
										'created_at' => date('Y-m-d H:i:s'),
										'updated_at' => date('Y-m-d H:i:s')
									];
									$this->db->table('madapalli_preparation_details')->insert($insert_data);
								}
							}
						}
					}
					$this->db->query("UPDATE prasadam SET total_amount = total_amount + ? WHERE id = ?", [$prasadam_tot_amt, $ins_id]);
					$this->total_amount += $prasadam_tot_amt;
				}
			} else {
				$succ = false;
			}
		} else {
			$succ = false;
		}

		return $succ;
	}

	public function save_free_prasadam($booking_id, $request_all_data) {
		$succ = true;

		if (!empty($request_all_data['free_prasadam'])) {
			$customer_name = $request_all_data['name'];
			$date = $request_all_data['dt'];
			$collection_date = $request_all_data['booking_date'];
			$payment_mode = $request_all_data['payment_mode'];

			$yr = date('Y');
			$mon = date('m');
			$query = $this->db->query("SELECT ref_no FROM prasadam WHERE id=(SELECT MAX(id) FROM prasadam WHERE YEAR(date)='" . $yr . "' AND MONTH(date)='" . $mon . "')")->getRowArray();
			$data['ref_no'] = 'PR' . date('y') . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
			$data['date'] = date('Y-m-d', strtotime($request_all_data['dt']));
			$data['booking_type'] = 2;
			$data['booking_id'] = $booking_id;
			$data['is_free'] = 1;
			$data['customer_name'] = $customer_name;
			$mble_phonecode = !empty($request_all_data['mobile_code']) ? $request_all_data['mobile_code'] : "";
			$mble_number = !empty($request_all_data['mobile_no']) ? $request_all_data['mobile_no'] : "";
			$data['mobile_no'] = $mble_phonecode . $mble_number;
			$data['total_amount'] = 0;
			$data['paid_amount'] = 0;
			$data['collection_date'] = $collection_date;
			$data['booking_status'] = 1;
			$data['payment_type'] = 'full';
			$data['payment_status'] = 2;
			$data['paid_through'] = !empty($request_all_data['booking_through']) ? trim($request_all_data['booking_through']) : 'DIRECT';
			$data['added_by'] = $request_all_data['user_id'];
			$data['payment_mode'] = $payment_mode;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data['updated_at'] = date('Y-m-d H:i:s');
			$slots = $this->db->table("booked_slot")->where('booking_id', $booking_id)->get()->getRowArray();
			$time_session = ($request_all_data['time'] == 'Breakfast') ? "AM" : "PM";
			$data['session'] = $request_all_data['time'];
			$data['serve_time'] = $request_all_data['hour'] . ':' . $request_all_data['minute'] .' '. $time_session;

			$res = $this->db->table('prasadam')->insert($data);
			$ins_id = $this->db->insertID();

			if ($res) {
				if (!empty($request_all_data['free_prasadam'])) {
					$prasadam_details = isset($request_all_data['free_prasadam']) ? $request_all_data['free_prasadam'] : [];
					foreach ($prasadam_details as $prasadam) {
						$prsm_set = $this->db->table('prasadam_setting')->where('id', $prasadam['id'])->get()->getRowArray();
						$data_prdm_book = [
							'prasadam_booking_id' => $ins_id,
							'prasadam_id' => $prasadam['id'],
							'quantity' => $prasadam['quantity'],
							'created' => date('Y-m-d H:i:s'),
							'amount' => 0,
							'total_amount' => 0
						];
						$res_2 = $this->db->table('prasadam_booking_details')->insert($data_prdm_book);

						$settings = $this->db->table('settings')->where('type', 5)->where('setting_name', 'enable_madapalli')->get()->getRowArray();

						if ($settings['setting_value'] == 1) {
							$madapalli_details['date'] = $collection_date;
							$madapalli_details['type'] = 1;
							$madapalli_details['booking_id'] = $ins_id;
							$madapalli_details['product_id'] = $prasadam['id'];
							$madapalli_details['quantity'] = $prasadam['quantity'];
							$madapalli_details['amount'] = 0;
							$madapalli_details['session'] = $data['session'];
                			$madapalli_details['serve_time'] = $data['serve_time'];
							$madapalli_details['customer_name'] = $customer_name;
							$madapalli_details['customer_mobile'] = $mble_phonecode . $mble_number;
							$madapalli_details['pro_name_eng'] = $prsm_set['name_eng'];	
							$madapalli_details['status'] = 0;
							$madapalli_details['created_by'] = $request_all_data['user_id'];
							$madapalli_details['created_at'] = date('Y-m-d H:i:s');
							$madapalli_details['updated_at'] = date('Y-m-d H:i:s');
							$res_m1 = $this->db->table('madapalli_booking_details')->insert($madapalli_details);

							if ($res_m1) {
								$preparation_details = $this->db->table('madapalli_preparation_details')->where('date', $collection_date)->where('type', 1)->get()->getResultArray();
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
										'date' => $collection_date,
										'type' => 1,
										'session' => $data['session'],
										'product_id' => $madapalli_details['product_id'],
										'pro_name_eng' => $prsm_set['name_eng'],
										'pro_name_tamil' => $prsm_set['name_tamil'],
										'quantity' => $madapalli_details['quantity'],
										'status' => 0,
										'created_by' => $request_all_data['user_id'],
										'created_at' => date('Y-m-d H:i:s'),
										'updated_at' => date('Y-m-d H:i:s')
									];
									$this->db->table('madapalli_preparation_details')->insert($insert_data);
								}
							}
						}
					}
				}
			} else {
				$succ = false;
			}
		} else {
			$succ = false;
		}

		return $succ;
	}

	public function save_annathanam($booking_id, $request_all_data, $booking_type) {
		$succ = true;
		try {
			$annathanam = $request_all_data['annathanam'];
			$data = [];
			$data['name'] = $request_all_data['name'];
			$data['booking_type'] = $booking_type;
			$data['booking_id'] = $booking_id;
			$data['phone_code'] = $request_all_data['mobile_code'];
			$data['phone_no'] = $request_all_data['mobile_no'];
			$data['package_id'] = $packag_id = $request_all_data['annathanam_package_select'];
			$data['no_of_pax'] = $annathanam[$packag_id]['quantity'];  
			$data['amount'] = $annathanam[$packag_id]['amount'];   
			$data['total_amount'] = 0;
			$data['paid_amount'] = 0;
			$data['payment_type'] = !empty($request_all_data['payment_type']) ? $request_all_data['payment_type']: '';
			$data['booking_through'] = !empty($request_all_data['booking_through']) ? trim($request_all_data['booking_through']) : 'DIRECT';
			$pay_method = (!empty($request_all_data['pay_method']) ? $request_all_data['pay_method'] : '');
			
			$yr = date('Y', strtotime($request_all_data['booking_date']));
			$mon = date('m', strtotime($request_all_data['booking_date']));
			$query = $this->db->query("SELECT ref_no FROM annathanam_new WHERE id=(SELECT max(id) FROM annathanam_new WHERE year(date)='". $yr ."' AND month(date)='". $mon ."')")->getRowArray();
			$data['ref_no'] = 'AT' . $yr . $mon . (sprintf("%05d", (((float) substr($query['ref_no'], -5)) + 1)));
			$data['date'] = $request_all_data['booking_date'];
			$data['event_date'] = date('Y-m-d', strtotime($request_all_data['ubhayam_date']));
			$time_session = ($request_all_data['time1'] == 'Breakfast') ? "AM" : "PM";
			$data['slot_time'] = $request_all_data['time1'];
			$data['serve_time'] = $request_all_data['hour1'] . ':' . $request_all_data['minute1'] .' '. $time_session;
			$data['payment_mode'] = $request_all_data['payment_mode'];
			$data['booking_status'] = 1; 
			$data['payment_status'] = 2; 
			$data['added_by'] = $request_all_data['user_id'];
			$data['created'] = date('Y-m-d H:i:s');

			$res = $this->db->table('annathanam_new')->insert($data);
			$annathanam_id = $this->db->insertID();

			if ($booking_type == 2) {
				$settings = $this->db->table('settings')->where('type', 5)->where('setting_name', 'enable_madapalli')->get()->getRowArray();	
			} else {
				$settings = $this->db->table('settings')->where('type', 6)->where('setting_name', 'enable_madapalli')->get()->getRowArray();	
			}

			
				
			if ($settings['setting_value'] == 1) {
				$anna_items = $this->db->table('annathanam_package_items')->where('package_id', $package_id)->where('add_on', 0)->get()->getResultArray();
				foreach ($anna_items as $items){
					$anna_set = $this->db->table('annathanam_items')->where('id', $items['item_id'])->get()->getRowArray();

					$madapalli_details['date'] = trim($request_all_data['ubhayam_date']);
					$madapalli_details['type'] = 2;
					$madapalli_details['booking_id'] = $annathanam_id;
					$madapalli_details['product_id'] = $items['item_id'];
					$madapalli_details['quantity'] = $annathanam[$packag_id]['quantity'];
					$madapalli_details['amount'] = 0;
					$madapalli_details['session'] = $data['slot_time'];
					$madapalli_details['serve_time'] = $data['serve_time'];
					$madapalli_details['customer_name'] = trim($request_all_data['name']);
					$madapalli_details['customer_mobile'] = $request_all_data['mobile_code'] . $request_all_data['mobile_no'];
					$madapalli_details['pro_name_eng'] = $anna_set['name_eng'];	
					$madapalli_details['status'] = 0;
					$madapalli_details['created_by'] = $request_all_data['user_id'];
					$madapalli_details['created_at'] = date('Y-m-d H:i:s');
					$madapalli_details['updated_at'] = date('Y-m-d H:i:s');				
	
					$res_m2 = $this->db->table('madapalli_booking_details')->insert($madapalli_details);

					if ($res_m2){
						$preparation_details = $this->db->table('madapalli_preparation_details')->where('date', $_POST['event_date'])->where('type', 2)->get()->getResultArray();
						$product_found2 = false;

						foreach ($preparation_details as $detail) {
							if ($detail['product_id'] == $madapalli_details['product_id'] && $detail['session'] == $madapalli_details['session']) {
								$new_quantity = $detail['quantity'] + $madapalli_details['quantity'];
								$update_data2 = [
									'quantity' => $new_quantity,
									'updated_at' => date('Y-m-d H:i:s')
								];
								$this->db->table('madapalli_preparation_details')->where('id', $detail['id'])->update($update_data2);
								$product_found2 = true;
								break;
							}
						}

						if (!$product_found2) {
							$insert_data2 = [
								'date' =>  trim($request_all_data['ubhayam_date']),
								'type' => 2,
								'session' => $data['slot_time'],
								'product_id' => $madapalli_details['product_id'],
								'pro_name_eng' => $anna_set['name_eng'],
								'pro_name_tamil' => $anna_set['name_tamil'],
								'quantity' => $madapalli_details['quantity'],
								'status' => 0,
								'created_by' => $request_all_data['user_id'],
								'created_at' => date('Y-m-d H:i:s'),
								'updated_at' => date('Y-m-d H:i:s')
							];
							$this->db->table('madapalli_preparation_details')->insert($insert_data2);
						}
					}
				}
			} 
			
			$total_amount = $annathanam[$packag_id]['amount'];
			if (!empty($request_all_data['anna_addon'])) {
				foreach ($request_all_data['anna_addon'] as $addon_item) {
					$total_amount += $addon_item['total_amount'];
					$data_addon_item = [
						'annathanam_id' => $annathanam_id,
						'package_id' => $request_all_data['annathanam_package_select'],
						'item_id' => $addon_item['id'],
						'quantity' => $addon_item['quantity'],
						'item_amount' => $addon_item['amount'],
						'item_total_amount' => $addon_item['total_amount'],
						'add_on' => 1
					];
					$res_addon = $this->db->table('annathanam_booked_addon')->insert($data_addon_item);

					if ($settings['setting_value'] == 1) {
						$anna_set = $this->db->table('annathanam_items')->where('id', $addon_item['addonId'])->get()->getRowArray();
						$madapalli_details = array(
							'date' => trim($request_all_data['ubhayam_date']),
							'type' => 2,
							'booking_id' => $annathanam_id,
							'product_id' => $addon_item['id'],
							'quantity' => $addon_item['quantity'],
							'amount' => $addon_item['total_amount'],
							'session' => $data['slot_time'],
							'serve_time' => $data['serve_time'],
							'customer_name' => trim($request_all_data['name']),
							'customer_mobile' => $request_all_data['mobile_code'] . $request_all_data['mobile_no'],
							'pro_name_eng' => $anna_set['name_eng'],
							'status' => 0,
							'created_by' => $request_all_data['user_id'],
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s')
						);

						$res_m2 = $this->db->table('madapalli_booking_details')->insert($madapalli_details);


						if ($res_m2){
							$preparation_details = $this->db->table('madapalli_preparation_details')->where('date', $_POST['event_date'])->where('type', 2)->get()->getResultArray();
							$product_found2 = false;

							foreach ($preparation_details as $detail) {
								if ($detail['product_id'] == $madapalli_details['product_id'] && $detail['session'] == $madapalli_details['session']) {
									$new_quantity = $detail['quantity'] + $madapalli_details['quantity'];
									$update_data2 = [
										'quantity' => $new_quantity,
										'updated_at' => date('Y-m-d H:i:s')
									];
									$this->db->table('madapalli_preparation_details')->where('id', $detail['id'])->update($update_data2);
									$product_found2 = true;
									break;
								}
							}

							if (!$product_found2) {
								$insert_data2 = [
									'date' => trim($request_all_data['ubhayam_date']),
									'type' => 2,
									'session' => $data['slot_time'],
									'product_id' => $madapalli_details['product_id'],
									'pro_name_eng' => $anna_set['name_eng'],
									'pro_name_tamil' => $anna_set['name_tamil'],
									'quantity' => $madapalli_details['quantity'],
									'status' => 0,
									'created_by' => $request_all_data['user_id'],
									'created_at' => date('Y-m-d H:i:s'),
									'updated_at' => date('Y-m-d H:i:s')
								];
								$this->db->table('madapalli_preparation_details')->insert($insert_data2);
							}
						}
					}
				}
			}

			$this->db->query("UPDATE annathanam_new SET total_amount = total_amount + ? WHERE id = ?", [$total_amount, $annathanam_id]);
			$this->total_amount += $total_amount;
			
		} catch (Exception $e) {
			$succ = false;
		}
		
		return $succ;
	}

	public function save_booking_slot($booking_id, $slots) {
		$succ = true;
		if(count($slots) > 0){
			$this->db->table('booked_slot')->delete(['booking_id' => $booking_id]);
			foreach($slots as $slot){
				$count = $this->db->table("booking_slot_new")->where('id', $slot)->get()->getNumRows();
				if($count > 0){
					$booking_slot_details = $this->db->table('booking_slot_new')->where('id', $slot)->get()->getRowArray();
					$booking_slot_ins_data = array();
					$booking_slot_ins_data['booking_id'] = $booking_id;
					$booking_slot_ins_data['booking_slot_id'] = $slot;
					$booking_slot_ins_data['slot_name'] = $booking_slot_details['slot_name'];
					$res = $this->db->table("booked_slot")->insert($booking_slot_ins_data);
					if(!$res){
						$succ = false;
						break;
					}
				}else{
					$succ = false;
					break;
				}
			}
		}
		return $succ;
	}
	
	public function save_booking_addon($booking_id, $addons) {
		$succ = true;
		if(count($addons) > 0){
			$this->db->table('booked_addon')->delete(['booking_id' => $booking_id]);
			foreach($addons as $addon){
				if(!empty($addon['id'])){
					$count = $this->db->table("temple_services")->where('id', $addon['id'])->get()->getNumRows();
					if($count > 0){
						$booking_addon_details = $this->db->table('temple_services')->where('id',  $addon['id'])->get()->getRowArray();
						$booking_addon_ins_data = array();
						$booking_addon_ins_data['booking_id'] = $booking_id;
						$booking_addon_ins_data['service_id'] = $addon['id'];
						$booking_addon_ins_data['name'] = $booking_addon_details['name'];
						$booking_addon_ins_data['description'] = $booking_addon_details['description'];
						$booking_addon_ins_data['service_type'] = $booking_addon_details['service_type'];
						$booking_addon_ins_data['ledger_id'] = $booking_addon_details['ledger_id'];
						$booking_addon_ins_data['quantity'] = !empty($addon['quantity']) ? $addon['quantity'] : 1;
						$booking_addon_ins_data['amount'] = $addon['amount'];
						$this->total_amount += $booking_addon_ins_data['quantity'] * $addon['amount'];
						$res = $this->db->table("booked_addon")->insert($booking_addon_ins_data);
						if(!$res){
							$succ = false;
							break;
						}
					}else{
						$succ = false;
						break;
					}
				}else{
					$succ = false;
					break;
				}
			}
		}else $succ = false;
		return $succ;
	}

	public function save_booking_packages($booking_id, $packages, $amount, $booking_type) {
		$succ = true;
		if(count($packages) > 0){
			$this->db->table('booked_packages')->delete(['booking_id' => $booking_id]);
			foreach($packages as $package){
				if(!empty($package['id'])){
					$count = $this->db->table("temple_packages")->where('id', $package['id'])->get()->getNumRows();
					$serv_count = $this->db->table("temple_package_services")->where('package_id', $package['id'])->get()->getNumRows();
					if($count > 0 && $serv_count > 0){
						$booking_package_details = $this->db->table('temple_packages')->where('id',  $package['id'])->get()->getRowArray();
						if($booking_type == $booking_package_details['package_type']){
							$booking_package_ins_data = array();
							$booking_package_ins_data['booking_id'] = $booking_id;
							$booking_package_ins_data['package_id'] = $package['id'];
							$booking_package_ins_data['booking_type'] = $booking_type;
							$booking_package_ins_data['name'] = $booking_package_details['name'];
							$booking_package_ins_data['description'] = $booking_package_details['description'];
							$booking_package_ins_data['package_type'] = $booking_package_details['package_type'];
							$booking_package_ins_data['package_mode'] = $booking_package_details['package_mode'];
							$booking_package_ins_data['pack_date'] = $booking_package_details['pack_date'];
							$booking_package_ins_data['ledger_id'] = $booking_package_details['ledger_id'];
							$booking_package_ins_data['quantity'] = !empty($package['quantity']) ? $package['quantity'] : 1;
							// $booking_package_ins_data['amount'] = $booking_package_details['amount'];
							$booking_package_ins_data['amount'] = $amount;
							// $this->total_amount += $booking_package_ins_data['quantity'] * $booking_package_ins_data['amount'];
							$this->total_amount += $booking_package_ins_data['quantity'] * $amount;
							$res = $this->db->table("booked_packages")->insert($booking_package_ins_data);
							if($res){
								$package_id = $this->db->insertID();
								$booking_pack_service_rows = $this->db->table('temple_package_services')->where('package_id',  $package['id'])->get()->getNumRows();
								if($booking_pack_service_rows > 0){
									$this->db->table('booked_services')->delete(['booking_id' => $booking_id]);
									$booking_pack_service_details = $this->db->table('temple_package_services')->where('package_id',  $package['id'])->get()->getResultArray();
									foreach($booking_pack_service_details as $booking_pack_service_detail){
										$sd_count = $this->db->table('temple_services')->where('id',  $booking_pack_service_detail['service_id'])->get()->getNumRows();
										if($sd_count > 0){
											$booking_service_details = $this->db->table('temple_services')->where('id',  $booking_pack_service_detail['service_id'])->get()->getRowArray();
											$booking_service_ins_data = array();
											$booking_service_ins_data['booking_id'] = $booking_id;
											$booking_service_ins_data['booked_package_id'] = $package_id;
											$booking_service_ins_data['service_id'] = $booking_pack_service_detail['service_id'];
											$booking_service_ins_data['quantity'] = $booking_pack_service_detail['quantity'];
											$booking_service_ins_data['amount'] = $booking_pack_service_detail['amount'];
											$booking_service_ins_data['name'] = $booking_service_details['name'];
											$booking_service_ins_data['description'] = $booking_service_details['description'];
											$booking_service_ins_data['ledger_id'] = $booking_service_details['ledger_id'];
											$booking_service_ins_data['service_type'] = $booking_service_details['service_type'];
											$res_new = $this->db->table("booked_services")->insert($booking_service_ins_data);
											if(!$res_new){
												$succ = false;
												break;
											}
											
										}else{
											$succ = false;
											break;
										}
									}
								}else{
									$succ = false;
									break;
								}
							}else{
								$succ = false;
								break;
							}
						}else{
							$succ = false;
							break;
						}
					}else{
						$succ = false;
						break;
					}
				}else{
					$succ = false;
					break;
				}
			}
		}
		return $succ;
	}

	public function save_deposit_payment($deposit_amt, $booking_id, $payment_type, $payments = array(), $booking_type, $booking_ref_no, $paid_through, $payment_mode = '') {
		$succ = true;
		$counter = $this->db->table('booked_deposit_details')->where('booking_id', $booking_id)->get()->getNumRows();
		if ($counter == 0) {
			$count = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getNumRows();
			if ($count > 0) {
				$payment_mode_details = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getRowArray();
				$booking_payment_ins_data = array(
					'booking_id' => $booking_id,
					'booking_type' => $booking_type,
					'booking_ref_no' => $booking_ref_no,
					'payment_mode_id' => $payment_mode,
					'paid_date' => date('Y-m-d'),
					'amount' => $deposit_amt,
					'deposit_status' => 1,
					'payment_mode_title' => $payment_mode_details['name'],
					'paid_through' => $paid_through,
					'pay_status' => ($paid_through == 'DIRECT' || $paid_through == 'COUNTER') ? 2 : 1,
				);
	
				$this->requestmodel = new RequestModel();
				$ip = $this->requestmodel->getIpAddress();
				$booking_payment_ins_data['ip'] = $ip;
				if ($ip != 'unknown') {
					$ip_details = $this->requestmodel->getLocation($ip);
					$booking_payment_ins_data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
					$booking_payment_ins_data['ip_details'] = json_encode($ip_details);
				}
				// $this->paid_amount += $booking_payment_ins_data['amount'];
				$this->total_amount += $booking_payment_ins_data['amount'];
				
				$res = $this->db->table("booked_deposit_details")->insert($booking_payment_ins_data);
				if (!$res) {
					$succ = false;
				}
			} else {
				$succ = false;
			}
		} else {
			$succ = false;
		}
		return $succ;
	}

	public function save_booking_payment($deposit_amt, $booking_id, $payment_type, $payments = array(), $booking_type, $booking_ref_no, $paid_through, $devotee_id, $created_by, $payment_mode = '') {
		$succ = true;
		if($payment_type == 'full'){
			if(!empty($payment_mode)){
				$this->db->table('booked_pay_details')->delete(['booking_id' => $booking_id]);
				$count = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getNumRows();
				if($count > 0){
					$payment_mode_details = $this->db->table("payment_mode")->where('id', $payment_mode)->get()->getRowArray();
					$booking_payment_ins_data = array();
					$booking_payment_ins_data['booking_id'] = $booking_id;
					$booking_payment_ins_data['booking_type'] = $booking_type;
					$booking_payment_ins_data['booking_ref_no'] = $booking_ref_no;
					$booking_payment_ins_data['payment_mode_id'] = $payment_mode;
					$booking_payment_ins_data['paid_date'] = date('Y-m-d');
					$booking_payment_ins_data['amount'] = $this->total_amount;
					$booking_payment_ins_data['payment_mode_title'] = $payment_mode_details['name'];
					if($paid_through != 'DIRECT' && $paid_through != 'COUNTER') $booking_payment_ins_data['payment_ref_no'] = $booking_ref_no;
					$booking_payment_ins_data['paid_through'] = $paid_through;
					$booking_payment_ins_data['pay_status'] = ($paid_through == 'DIRECT' || $paid_through == 'COUNTER') ? 2 : 1;
					$this->requestmodel = new RequestModel();
					$ip = $this->requestmodel->getIpAddress();
					$booking_payment_ins_data['ip'] = $ip;
					if ($ip != 'unknown') {
						$ip_details = $this->requestmodel->getLocation($ip);
						$booking_payment_ins_data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
						$booking_payment_ins_data['ip_details'] = json_encode($ip_details);
					} 
					$this->paid_amount += $booking_payment_ins_data['amount'];

					$res = $this->db->table("booked_pay_details")->insert($booking_payment_ins_data);
					if ($res) {
						$devotee_pay['devotee_id'] = $devotee_id;
						$devotee_pay['module_type'] = $booking_type == 1 ? 5 : 6;
						$devotee_pay['booking_id'] = $booking_id;
						$devotee_pay['ref_no'] = $booking_ref_no;
						$devotee_pay['paid_date'] = date('Y-m-d');
						$devotee_pay['is_repayment'] = 0;
						$devotee_pay['amount'] = $this->total_amount;
						$devotee_pay['payment_mode_id'] = $payment_mode;
						$devotee_pay['payment_mode_title'] = $payment_mode_details['name'];
						$devotee_pay['pay_status'] = ($paid_through == 'DIRECT' || $paid_through == 'COUNTER') ? 2 : 1;
						$devotee_pay['paid_through'] = $paid_through;
						$devotee_pay['created_by'] = $created_by;
						$devotee_pay['created_at'] = date('Y-m-d H:i:s');
						$devotee_pay['ip'] = $ip;
						$devotee_pay['ip_location'] = $booking_payment_ins_data['ip_location'];
						$devotee_pay['ip_details'] = $booking_payment_ins_data['ip_details'];

						$this->db->table('devotee_payment_details')->insert($devotee_pay);   

					}else{
						$succ = false;
					}
				}else{
					$succ = false;
				}
			}else{
				$succ = false;
			}
		}elseif($payment_type == 'partial'){
			if(count($payments) > 0){
				$this->db->table('booked_pay_details')->delete(['booking_id' => $booking_id]);
				foreach($payments as $payment){
					if(!empty($payment['payment_mode']) && !empty($payment['amount'])){
						$payment_amount = (float) $payment['amount'];
						if(!empty($payment_amount)){
							$count = $this->db->table("payment_mode")->where('id', $payment['payment_mode'])->get()->getNumRows();
							if($count > 0){
								$payment_mode_details = $this->db->table("payment_mode")->where('id', $payment['payment_mode'])->get()->getRowArray();
								$booking_payment_ins_data = array();
								$booking_payment_ins_data['booking_id'] = $booking_id;
								$booking_payment_ins_data['booking_type'] = $booking_type;
								$booking_payment_ins_data['booking_ref_no'] = $booking_ref_no;
								$booking_payment_ins_data['payment_mode_id'] = $payment['payment_mode'];
								$booking_payment_ins_data['paid_date'] = !empty($payment['paid_date']) ? $payment['paid_date'] : date('Y-m-d');
								$booking_payment_ins_data['amount'] = $payment_amount;
								$booking_payment_ins_data['payment_mode_title'] = $payment_mode_details['name'];
								if($paid_through != 'DIRECT' && $paid_through != 'COUNTER') $booking_payment_ins_data['payment_ref_no'] = $booking_ref_no;
								$booking_payment_ins_data['paid_through'] = $paid_through;
								$booking_payment_ins_data['pay_status'] = ($paid_through == 'DIRECT' || $paid_through == 'COUNTER') ? 2 : 1;
								$this->requestmodel = new RequestModel();
								$ip = $this->requestmodel->getIpAddress();
								$booking_payment_ins_data['ip'] = $ip;
								if ($ip != 'unknown') {
									$ip_details = $this->requestmodel->getLocation($ip);
									$booking_payment_ins_data['ip_location'] = (!empty($ip_details['country']) ? $ip_details['country'] : 'Unknown');
									$booking_payment_ins_data['ip_details'] = json_encode($ip_details);
								} 
								$this->paid_amount += $booking_payment_ins_data['amount'];
								// if($this->paid_amount > $this->total_amount){
								// 	$succ = false;
								// 	break;
								// }
								$res = $this->db->table("booked_pay_details")->insert($booking_payment_ins_data);
								if ($res) {
									$devotee_pay['devotee_id'] = $devotee_id;
									$devotee_pay['module_type'] = $booking_type == 1 ? 5 : 6;
									$devotee_pay['booking_id'] = $booking_id;
									$devotee_pay['ref_no'] = $booking_ref_no;
									$devotee_pay['paid_date'] = !empty($payment['paid_date']) ? $payment['paid_date'] : date('Y-m-d');
									$devotee_pay['is_repayment'] = 0;
									$devotee_pay['amount'] = $payment_amount;
									$devotee_pay['payment_mode_id'] = $payment['payment_mode'];
									$devotee_pay['payment_mode_title'] = $payment_mode_details['name'];
									$devotee_pay['pay_status'] = ($paid_through == 'DIRECT' || $paid_through == 'COUNTER') ? 2 : 1;
									$devotee_pay['paid_through'] = $paid_through;
									$devotee_pay['created_by'] = $created_by;
									$devotee_pay['created_at'] = date('Y-m-d H:i:s');
									$devotee_pay['ip'] = $ip;
									$devotee_pay['ip_location'] = $booking_payment_ins_data['ip_location'];
									$devotee_pay['ip_details'] = $booking_payment_ins_data['ip_details'];

									$this->db->table('devotee_payment_details')->insert($devotee_pay);   

								}else{
									$succ = false;
									break;
								}
							}else{
								$succ = false;
								break;
							}
						}else{
							$succ = false;
							break;
						}
					}else{
						$succ = false;
						break;
					}
				}
			}else{
				$succ = false;
			}
		}
		return $succ;
	}

	public function account_migration($booking_id) {
		$succ = true;
		$templeubayam = $this->db->table("templebooking")->where("id", $booking_id)->get()->getRowArray();
		$booking_type = $templeubayam['booking_type'];
		$booking_settings = $this->db->table('settings')->get()->getResultArray();
		$setting = array();
		if(count($booking_settings) > 0){
			foreach($booking_settings as $bs){
				$setting[$bs['setting_name']] = $bs['setting_value'];
			}
		}
		$entry_date = date('Y-m-d', strtotime($templeubayam['entry_date']));
		$date = explode('-', $entry_date);
		$yr = $date[0];
		$mon = $date[1];
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

		$tp_ledger = $this->db->table('ledgers')->where('name', 'TRADE PAYABLE')->where('group_id', 14)->where('left_code', '2100')->get()->getRowArray();
		if (!empty($tp_ledger)) {
		  $cr_id2 = $tp_ledger['id'];
		} else {
		  $cled1['group_id'] = 14;
		  $cled1['name'] = 'TRADE PAYABLE';
		  $cled1['code'] = '2100/2102';
		  $cled1['op_balance'] = '0';
		  $cled1['op_balance_dc'] = 'D';
		  $cled1['left_code'] = '2100';
		  $cled1['right_code'] = '2102';
		  $this->db->table('ledgers')->insert($cled1);
		  $cr_id2 = $this->db->insertID();
		}

		$incomes_group = $this->db->table('groups')->where('code', '8000')->get()->getRowArray();
		if (!empty($incomes_group)) {
			$sls_id = $incomes_group['id'];
		} else {
			$sls1['parent_id'] = 0;
			$sls1['name'] = 'Incomes';
			$sls1['code'] = '8000';
			$sls1['added_by'] = $this->session->get('log_id');
			$led_ins1 = $this->db->table('groups')->insert($sls1);
			$sls_id = $this->db->insertID();
		}

		$booked_packages_cnt = $this->db->table("booked_packages")->where("booking_id", $booking_id)->get()->getNumRows();	
		$booked_addon_cnt = $this->db->table("booked_addon")->where("booking_id", $booking_id)->get()->getNumRows();
		$booked_deposit_cnt = $this->db->table("booked_deposit_details")->where("booking_id", $booking_id)->get()->getNumRows();
		$booked_prasadam_cnt = $this->db->table("prasadam")->where('is_free', 0)->where("booking_type", $booking_type)->where("booking_id", $booking_id)->get()->getNumRows();
		$booked_abishegam_cnt = $this->db->table("booked_abishegam_details")->where("booking_id", $booking_id)->get()->getNumRows();
		$booked_homam_cnt = $this->db->table("booked_homam_details")->where("booking_id", $booking_id)->get()->getNumRows();
		$booked_extra_cnt = $this->db->table("booked_extra_charges")->where("booking_id", $booking_id)->get()->getNumRows();

		if ($booked_packages_cnt > 0) {
			$booked_packages_details = $this->db->table("booked_packages")->where("booking_id", $booking_id)->get()->getResultArray();
			$booked_addon_details = $this->db->table("booked_addon")->join('temple_services', 'temple_services.id = booked_addon.service_id')->select('temple_services.*, booked_addon.quantity')->where("booked_addon.booking_id", $booking_id)->get()->getResultArray();
			$booked_prasadam_details = $this->db->table("prasadam")->where('is_free', 0)->where("booking_type", $booking_type)->where("booking_id", $booking_id)->get()->getResultArray();
			$booked_abishegam_details = $this->db->table("booked_abishegam_details")->where("booking_id", $booking_id)->get()->getResultArray();
			$booked_homam_details = $this->db->table("booked_homam_details")->where("booking_id", $booking_id)->get()->getResultArray();
			$booked_extra_details = $this->db->table("booked_extra_charges")->where("booking_id", $booking_id)->get()->getResultArray();
			$over_all_tot_amt = 0;

			foreach ($booked_packages_details as $row) $over_all_tot_amt += (float) $row['amount'];
			if($booked_addon_cnt > 0){
				foreach ($booked_addon_details as $row) $over_all_tot_amt += (float) $row['amount'] * (int) $row['quantity'];
			}
			if ($booked_prasadam_cnt > 0) {
				foreach ($booked_prasadam_details as $row)
					$over_all_tot_amt += (float) $row['amount'];
			}
			if($booked_abishegam_cnt > 0){
				foreach ($booked_abishegam_details as $row) $over_all_tot_amt += (float) $row['amount'];
			}
			if($booked_homam_cnt > 0){
				foreach ($booked_homam_details as $row) $over_all_tot_amt += (float) $row['amount'];
			}
			if($booked_extra_cnt > 0){
				foreach ($booked_extra_details as $row) $over_all_tot_amt += (float) $row['amount'];
			}
			$debtor_amount = 0;

			$number1 = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
			if (empty($number1))
				$num1 = 1;
			else
				$num1 = $number1['number'] + 1;
			// Get Entry Code
			$qry1 = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();

			$entries1['entry_code'] = 'JOR' . date('y', strtotime($entry_date)) . $mon . (sprintf("%05d", (((float) substr($qry1['entry_code'], -5)) + 1)));
			$entries1['entrytype_id'] = '4';
			$entries1['number'] = $num1;
			$entries1['date'] = $entry_date;
			$entries1['dr_total'] = $over_all_tot_amt;
			$entries1['cr_total'] = $over_all_tot_amt;
			if ($templeubayam['booking_type'] == 2) $narration = 'Ubayam Amout';
			elseif ($templeubayam['booking_type'] == 1) $narration = 'Hall Booking Amount';
			else $narration = 'Sannathi AMount';
			$entries1['narration'] = $narration . '(' . $templeubayam['ref_no'] . ')' . "\n" . 'name:' . $templeubayam['name'] . "\n" . 'NRIC:' . "\n" . 'email:' . $templeubayam['email'] . "\n";
			$entries1['inv_id'] = $booking_id;
			if($templeubayam['booking_type'] == 2) $entries1['type'] = 1;
			else $entries1['type'] = 8;
			//Insert Entries
			$ent = $this->db->table('entries')->insert($entries1);
			$en_id1 = $this->db->insertID();
			if (!empty($en_id1)) {
				foreach ($booked_packages_details as $row) {
					if(!empty($row['ledger_id'])){
						$led_book_id = $row['ledger_id'];
					}else{
						$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
						if(!empty($ledger1)){
							$led_book_id = $ledger1['id'];
						}else{
							$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code','desc')->get()->getRowArray();
							$set_right_code = (int) $right_code['right_code'] + 1;
							$set_right_code = sprintf("%04d", $set_right_code);
							$led1['group_id'] = $sls_id;
							$led1['name'] = 'All Incomes';
							$led1['left_code'] = '8913';
							$led1['right_code'] = $set_right_code;
							$led1['op_balance'] = '0';
							$led1['op_balance_dc'] = 'D';
							$led_ins1 = $this->db->table('ledgers')->insert($led1);
							$led_book_id = $this->db->insertID();
						}
					}
					// Hall Booking => Credit
					$eitems_hall_book['entry_id'] = $en_id1;
					$eitems_hall_book['ledger_id'] = $led_book_id;
					$eitems_hall_book['amount'] = $row['amount'];
					$eitems_hall_book['dc'] = 'C';
					$eitems_hall_book['details'] = 'Amount for ' . $row['name'];
					$this->db->table('entryitems')->insert($eitems_hall_book);
					//  Trade Debtors => Debit 
					$debtor_amount += $row['amount'];
				}
			}else{
				$succ = false;
				return $succ;
			}
		}

		if($booked_addon_cnt > 0){
			$booked_addon_details = $this->db->table("booked_addon")->join('temple_services', 'temple_services.id = booked_addon.service_id')->select('temple_services.*, booked_addon.quantity, booked_addon.amount as new_amount')->where("booked_addon.booking_id", $booking_id)->get()->getResultArray();
			foreach ($booked_addon_details as $row) {
				if(!empty($row['ledger_id'])){
					$led_book_id = $row['ledger_id'];
				}else{
					$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
					if(!empty($ledger1)){
						$led_book_id = $ledger1['id'];
					}else{
						$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code','desc')->get()->getRowArray();
						$set_right_code = (int) $right_code['right_code'] + 1;
						$set_right_code = sprintf("%04d", $set_right_code);
						$led1['group_id'] = $sls_id;
						$led1['name'] = 'All Incomes';
						$led1['left_code'] = '8913';
						$led1['right_code'] = $set_right_code;
						$led1['op_balance'] = '0';
						$led1['op_balance_dc'] = 'D';
						$led_ins1 = $this->db->table('ledgers')->insert($led1);
						$led_book_id = $this->db->insertID();
					}
				}
				$amount = (float) $row['new_amount'] * $row['quantity'];
				// Hall Booking => Credit
				$eitems_hall_book['entry_id'] = $en_id1;
				$eitems_hall_book['ledger_id'] = $led_book_id;
				$eitems_hall_book['amount'] = $amount;
				$eitems_hall_book['dc'] = 'C';
				$eitems_hall_book['details'] = 'Amount for ' . $row['name'];
				$this->db->table('entryitems')->insert($eitems_hall_book);
				//  Trade Debtors => Debit 
				$debtor_amount += $amount;
			}
		}

		if($booked_abishegam_cnt > 0){
			$booked_abishegam_details = $this->db->table("booked_abishegam_details bad")->select('bad.*, ad.ledger_id, ad.name')->join('archanai_diety ad', 'ad.id = bad.deity_id', 'inner')
			->where("bad.booking_id", $booking_id)->get()->getResultArray();

			foreach ($booked_abishegam_details as $row) {
				if(!empty($row['ledger_id'])){
					$led_book_id = $row['ledger_id'];
				}else{
					$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
					if(!empty($ledger1)){
						$led_book_id = $ledger1['id'];
					}else{
						$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code','desc')->get()->getRowArray();
						$set_right_code = (int) $right_code['right_code'] + 1;
						$set_right_code = sprintf("%04d", $set_right_code);
						$led1['group_id'] = $sls_id;
						$led1['name'] = 'All Incomes';
						$led1['left_code'] = '8913';
						$led1['right_code'] = $set_right_code;
						$led1['op_balance'] = '0';
						$led1['op_balance_dc'] = 'D';
						$led_ins1 = $this->db->table('ledgers')->insert($led1);
						$led_book_id = $this->db->insertID();
					}
				}
				$amount = (float) $row['amount'];
				// Hall Booking => Credit
				$eitems_hall_book['entry_id'] = $en_id1;
				$eitems_hall_book['ledger_id'] = $led_book_id;
				$eitems_hall_book['amount'] = $amount;
				$eitems_hall_book['dc'] = 'C';
				$eitems_hall_book['details'] = 'Amount for ' . $row['name'];
				$this->db->table('entryitems')->insert($eitems_hall_book);
				$debtor_amount += $amount;
			}
		}

		if($booked_homam_cnt > 0){
			$booked_homam_details = $this->db->table("booked_homam_details bhd")->select('bhd.*, ad.ledger_id, ad.name')->join('archanai_diety ad', 'ad.id = bhd.deity_id', 'inner')
			->where("bhd.booking_id", $booking_id)->get()->getResultArray();

			foreach ($booked_homam_details as $row) {
				if(!empty($row['ledger_id'])){
					$led_book_id = $row['ledger_id'];
				}else{
					$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
					if(!empty($ledger1)){
						$led_book_id = $ledger1['id'];
					}else{
						$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code','desc')->get()->getRowArray();
						$set_right_code = (int) $right_code['right_code'] + 1;
						$set_right_code = sprintf("%04d", $set_right_code);
						$led1['group_id'] = $sls_id;
						$led1['name'] = 'All Incomes';
						$led1['left_code'] = '8913';
						$led1['right_code'] = $set_right_code;
						$led1['op_balance'] = '0';
						$led1['op_balance_dc'] = 'D';
						$led_ins1 = $this->db->table('ledgers')->insert($led1);
						$led_book_id = $this->db->insertID();
					}
				}
				$amount = (float) $row['amount'];
				// Hall Booking => Credit
				$eitems_hall_book['entry_id'] = $en_id1;
				$eitems_hall_book['ledger_id'] = $led_book_id;
				$eitems_hall_book['amount'] = $amount;
				$eitems_hall_book['dc'] = 'C';
				$eitems_hall_book['details'] = 'Amount for ' . $row['name'];
				$this->db->table('entryitems')->insert($eitems_hall_book);
				$debtor_amount += $amount;
			}
		}

		if ($booked_prasadam_cnt > 0) {
			$booked_prasadam_details = $this->db->table("prasadam p")->select('pbd.*, ps.ledger_id, ps.name_eng')->join('prasadam_booking_details pbd', 'pbd.prasadam_booking_id = p.id')->join('prasadam_setting ps', 'pbd.prasadam_id = ps.id')->where('is_free', 0)->where('booking_type', 2)->where("booking_id", $booking_id)->get()->getResultArray();

			foreach ($booked_prasadam_details as $row) {
				if (!empty($row['ledger_id'])) {
					$led_book_id = $row['ledger_id'];
				} else {
					$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
					if (!empty($ledger1)) {
						$led_book_id = $ledger1['id'];
					} else {
						$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code', 'desc')->get()->getRowArray();
						$set_right_code = (int) $right_code['right_code'] + 1;
						$set_right_code = sprintf("%04d", $set_right_code);
						$led1['group_id'] = $sls_id;
						$led1['name'] = 'All Incomes';
						$led1['left_code'] = '8913';
						$led1['right_code'] = $set_right_code;
						$led1['op_balance'] = '0';
						$led1['op_balance_dc'] = 'D';
						$led_ins1 = $this->db->table('ledgers')->insert($led1);
						$led_book_id = $this->db->insertID();
					}
				}
				$amount = (float) $row['amount'] * $row['quantity'];
				// Hall Booking => Credit
				$eitems_hall_book['entry_id'] = $en_id1;
				$eitems_hall_book['ledger_id'] = $led_book_id;
				$eitems_hall_book['amount'] = $amount;
				$eitems_hall_book['dc'] = 'C';
				$eitems_hall_book['details'] = 'Amount for ' . $row['name_eng'];
				$this->db->table('entryitems')->insert($eitems_hall_book);
				//  Trade Debtors => Debit 
				$debtor_amount += $amount;
			}
		}

		if($booked_extra_cnt > 0){
			$booked_extra_details = $this->db->table("booked_extra_charges")->where("booking_id", $booking_id)->get()->getResultArray();
				
			foreach ($booked_extra_details as $row) {
				if(!empty($row['ledger_id'])){
					$led_book_id = $row['ledger_id'];
				}else{
					$ledger1 = $this->db->table('ledgers')->where('name', 'All Incomes')->where('group_id', $sls_id)->get()->getRowArray();
					if(!empty($ledger1)){
						$led_book_id = $ledger1['id'];
					}else{
						$right_code = $this->db->table('ledgers')->select('right_code')->where('group_id', $sls_id)->where('left_code', '8913')->orderBy('right_code','desc')->get()->getRowArray();
						$set_right_code = (int) $right_code['right_code'] + 1;
						$set_right_code = sprintf("%04d", $set_right_code);
						$led1['group_id'] = $sls_id;
						$led1['name'] = 'All Incomes';
						$led1['left_code'] = '8913';
						$led1['right_code'] = $set_right_code;
						$led1['op_balance'] = '0';
						$led1['op_balance_dc'] = 'D';
						$led_ins1 = $this->db->table('ledgers')->insert($led1);
						$led_book_id = $this->db->insertID();
					}
				}
				$amount = (float) $row['amount'];
				// Hall Booking => Credit
				$eitems_hall_book['entry_id'] = $en_id1;
				$eitems_hall_book['ledger_id'] = $led_book_id;
				$eitems_hall_book['amount'] = $amount;
				$eitems_hall_book['dc'] = 'C';
				$eitems_hall_book['details'] = 'Amount for Extra Charges';
				$this->db->table('entryitems')->insert($eitems_hall_book);
				//  Trade Debtors => Debit 
				$debtor_amount += $amount;
			}
		}
		//  Trade Debtors => Debit 
		$eitems_cash_led = array();
		$eitems_cash_led['entry_id'] = $en_id1;
		$eitems_cash_led['ledger_id'] = $cr_id1;
		$eitems_cash_led['amount'] = $debtor_amount;
		$eitems_cash_led['dc'] = 'D';
		$eitems_cash_led['details'] = 'Amount for ' . $booking_type_name . '(' . $templeubayam['ref_no'] . ')';
		$this->db->table('entryitems')->insert($eitems_cash_led);

		$discount_amount = !empty($templeubayam['discount_amount']) ? (float) $templeubayam['discount_amount']: 0;
		if (!empty($discount_amount)) {
			$number1 = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
			if (empty($number1))
				$num1 = 1;
			else
				$num1 = $number1['number'] + 1;
			$qry1 = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();

			$entries1['entry_code'] = 'JOR' . date('y', strtotime($entry_date)) . $mon . (sprintf("%05d", (((float) substr($qry1['entry_code'], -5)) + 1)));
			$entries1['entrytype_id'] = '4';
			$entries1['number'] = $num1;
			$entries1['date'] = $entry_date;
			$entries1['dr_total'] = $discount_amount;
			$entries1['cr_total'] = $discount_amount;
			if ($templeubayam['booking_type'] == 2) $narration = 'Ubayam Amout';
			elseif ($templeubayam['booking_type'] == 1) $narration = 'Hall Booking Amount';
			else $narration = 'Sannathi AMount';
			$entries1['narration'] = $narration . '(' . $templeubayam['ref_no'] . ')' . "\n" . 'name:' . $templeubayam['name'] . "\n" . 'NRIC:' . "\n" . 'email:' . $templeubayam['email'] . "\n";
			$entries1['inv_id'] = $booking_id;
			if($templeubayam['booking_type'] == 2) $entries1['type'] = 1;
			else $entries1['type'] = 8;
			//Insert Entries
			$ent = $this->db->table('entries')->insert($entries1);
			$en_id2 = $this->db->insertID();
			if (!empty($en_id2)) {

				if($templeubayam['booking_type'] == 1){
					$discount_ledger_id = !empty($setting['discount_hall_ledger_id']) ? $setting['discount_hall_ledger_id'] : 1097;
					$booking_type_name = 'Hall';
					$entry_type = 8;
				}elseif($templeubayam['booking_type'] == 2){
					$discount_ledger_id = !empty($setting['discount_ubayam_ledger_id']) ? $setting['discount_ubayam_ledger_id'] : 1097;
					$booking_type_name = 'Ubayam';
					$entry_type = 1;
				}else{
					$discount_ledger_id = !empty($setting['discount_ubayam_ledger_id']) ? $setting['discount_ubayam_ledger_id'] : 1097;
					$booking_type_name = 'Sannathi';
					$entry_type = 13;
				}

				// Hall Booking => Credit
				$eitems_hall_book['entry_id'] = $en_id2;
				$eitems_hall_book['ledger_id'] = $cr_id1;
				$eitems_hall_book['amount'] = $discount_amount;
				$eitems_hall_book['dc'] = 'C';
				$eitems_hall_book['details'] = 'Discount for ' . $booking_type_name . '(' . $templeubayam['ref_no'] . ')';
				$this->db->table('entryitems')->insert($eitems_hall_book);

				//  Trade Debtors => Debit 
				$eitems_disc_ent = array();
			    $eitems_disc_ent['entry_id'] = $en_id2;
			    $eitems_disc_ent['ledger_id'] = $discount_ledger_id;
			    $eitems_disc_ent['amount'] = $discount_amount;
			    $eitems_disc_ent['is_discount'] = 1;
			    $eitems_disc_ent['dc'] = 'D';
			    $eitems_disc_ent['details'] = 'Discount for ' . $booking_type_name . '(' . $templeubayam['ref_no'] . ')';
			    $this->db->table('entryitems')->insert($eitems_disc_ent);
			    $debtor_amount -= $discount_amount;

			}else{
				$succ = false;
				return $succ;
			}
		}
		
		if($booked_deposit_cnt > 0){
			$booked_deposit_details = $this->db->table("booked_deposit_details")->where("booking_id", $booking_id)->get()->getResultArray();

			foreach ($booked_deposit_details as $row) {
				$paymentmode = $this->db->table('payment_mode')->where('id', $row['payment_mode_id'])->get()->getRowArray();
				if (!empty($paymentmode['ledger_id'])) {
					$number1 = $this->db->table('entries')->select('number')->where('entrytype_id', 4)->orderBy('id', 'desc')->get()->getRowArray();
					if (empty($number1))
						$num1 = 1;
					else
						$num1 = $number1['number'] + 1;
					$qry1 = $this->db->query("SELECT entry_code FROM entries where id=(select max(id) from entries where year (date)='" . $yr . "' and entrytype_id =4 and month (date)='" . $mon . "')")->getRowArray();

					$entries['entry_code'] = 'JOR' . date('y', strtotime($entry_date)) . $mon . (sprintf("%05d", (((float) substr($qry1['entry_code'], -5)) + 1)));
					$entries['entrytype_id'] = '4';
					$entries['number'] = $num1;
					$entries['date'] = $row['paid_date'];
					$entries['dr_total'] = $row['amount'];
					$entries['cr_total'] = $row['amount'];
					if ($templeubayam['booking_type'] == 2) $narration = 'Ubayam deposit';
					elseif ($templeubayam['booking_type'] == 1) $narration = 'Hall Booking deposit';
					else $narration = 'Sannathi deposit';
					$entries['narration'] = $narration . '(' . $templeubayam['ref_no'] . ')' . "\n" . 'name:' . $templeubayam['name'] . "\n" . 'email:' . $templeubayam['email'] . "\n";
					$entries['inv_id'] = $booking_id;
					$entries['type'] = 8;
					//Insert Entries
					$ent = $this->db->table('entries')->insert($entries);
					$en_id = $this->db->insertID();
					if (!empty($en_id)) {
						// PETTY CASH => Debit 
						$eitems_cash_led['entry_id'] = $en_id;
						$eitems_cash_led['ledger_id'] = $cr_id2;
						$eitems_cash_led['amount'] = $row['amount'];
						$eitems_cash_led['dc'] = 'C';
						// $eitems_cash_led['details'] = 'Hall Booking Amount';
						if ($templeubayam['booking_type'] == 2){$eitems_cash_led['details'] = 'Ubayam deposit';}
						elseif ($templeubayam['booking_type'] == 1){$eitems_cash_led['details'] = 'Hall Booking deposit';}
						else {$eitems_cash_led['details'] = 'Sannathi desposit';}
						$this->db->table('entryitems')->insert($eitems_cash_led);

						// Trade Debtors => Credit
						$eitems_hall_book['entry_id'] = $en_id;
						$eitems_hall_book['ledger_id'] = $cr_id1;
						$eitems_hall_book['amount'] = $row['amount'];
						$eitems_hall_book['dc'] = 'D';
						if ($templeubayam['booking_type'] == 2){$eitems_hall_book['details'] = 'Ubayam deposit';}
						elseif ($templeubayam['booking_type'] == 1){$eitems_hall_book['details'] = 'Hall Booking deposit';}
						else {$eitems_hall_book['details'] = 'Sannathi deposit';}
						$this->db->table('entryitems')->insert($eitems_hall_book);
					}
				}else{
					$succ = false;
					return $succ;
				}
			}
		}


		if($templeubayam['payment_type'] == 'full' || $templeubayam['payment_type'] == 'partial'){
			$booked_pay_details_cnt = $this->db->table("booked_pay_details")->where("booking_id", $booking_id)->get()->getNumRows();	
			// $booked_pay_details_cnt = $this->db->table("booked_pay_details")->where("booking_id", $booking_id)->where("booking_type", 2)->get()->getNumRows();	
			if ($booked_pay_details_cnt > 0) {
				$booked_pay_details = $this->db->table("booked_pay_details")->where("booking_id", $booking_id)->get()->getResultArray();
				// $booked_pay_details = $this->db->table("booked_pay_details")->where("booking_id", $booking_id)->where("booking_type", 2)->get()->getResultArray();
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
						$entries['narration'] = 'Hall Booking(' . $templeubayam['ref_no'] . ')' . "\n" . 'name:' . $templeubayam['name'] . "\n" . 'NRIC:' . $templeubayam['ic_number'] . "\n" . 'email:' . $templeubayam['email'] . "\n";
						$entries['inv_id'] = $booking_id;
						if($templeubayam['booking_type'] == 1) $entries['type'] = 8;
						else $entries['type'] = 1;
						//Insert Entries
						$ent = $this->db->table('entries')->insert($entries);
						$en_id = $this->db->insertID();
						if (!empty($en_id)) {
							// Trade Debtors => Credit
							$eitems_hall_book['entry_id'] = $en_id;
							$eitems_hall_book['ledger_id'] = $cr_id1;
							$eitems_hall_book['amount'] = $row['amount'];
							$eitems_hall_book['dc'] = 'C';
							if ($templeubayam['booking_type'] == 2){$eitems_hall_book['details'] = 'Ubayam Amount';}
							elseif ($templeubayam['booking_type'] == 1){$eitems_hall_book['details'] = 'Hall Booking Amount';}
							else {$eitems_hall_book['details'] = 'Sannathi Amount';}
							$this->db->table('entryitems')->insert($eitems_hall_book);
							// PETTY CASH => Debit 
							$eitems_cash_led['entry_id'] = $en_id;
							$eitems_cash_led['ledger_id'] = $paymentmode['ledger_id'];
							$eitems_cash_led['amount'] = $row['amount'];
							$eitems_cash_led['dc'] = 'D';
							// $eitems_cash_led['details'] = 'Hall Booking Amount';
							if ($templeubayam['booking_type'] == 2){$eitems_cash_led['details'] = 'Ubayam Amount';}
							elseif ($templeubayam['booking_type'] == 1){$eitems_cash_led['details'] = 'Hall Booking Amount';}
							else {$eitems_cash_led['details'] = 'Sannathi Amount';}
							$this->db->table('entryitems')->insert($eitems_cash_led);
						}
					}else{
						$succ = false;
						return $succ;
					}
				}
			}else{
				$succ = false;
				return $succ;
			}
		}
		return $succ;
	}

	public function get_packages_list() {
		$resp = array('success' => true, 'data' => array('status' => true));
		try {
			if (!empty($_REQUEST['booking_date']))
				$request_all_data = $_REQUEST;
			else
				$request_all_data = json_decode(file_get_contents('php://input'), true);
			if ($request_all_data['booking_date'] && $request_all_data['slot_id'] && $request_all_data['package_type']) {
				$booking_date = trim($request_all_data['booking_date']);
				$slot_id = trim($request_all_data['slot_id']);
				$deity_id = trim($request_all_data['deity_id']);
				$package_type = trim($request_all_data['package_type']);
				$query = $this->db->query("
							SELECT COUNT(*) as count FROM (
								SELECT bp.package_id 
								FROM templebooking tb
								LEFT JOIN booked_packages bp ON bp.booking_id = tb.id
								LEFT JOIN temple_packages tp ON tp.id = bp.package_id
								LEFT JOIN booked_slot bs ON bs.booking_id = tb.id
								WHERE tb.booking_date = '$booking_date'
								AND bs.booking_slot_id = $slot_id
								AND tp.package_type = $package_type
								AND tp.deity_id = $deity_id
								AND tb.booking_status NOT IN (3)
								GROUP BY bp.package_id
							) AS subquery
						");

				$result = $query->getRowArray();
				$pack_rows = isset($result->count) ? $result->count : 0;

				// echo $pack_rows;
				// exit;
				if ($pack_rows > 1) {
					$resp['data']['packages'] = array();
					$resp['data']['addons'] = array();

				} elseif ($pack_rows == 1) {
					$packages_result = $this->db->query("
										SELECT 
											MAX(bp.package_id) AS package_id, 
											MAX(tp.package_type) AS package_type, 
											MAX(tp.package_mode) AS package_mode
										FROM 
											templebooking tb
										LEFT JOIN 
											booked_packages bp ON bp.booking_id = tb.id
										LEFT JOIN 
											temple_packages tp ON tp.id = bp.package_id
										LEFT JOIN 
											booked_slot bs ON bs.booking_id = tb.id
										WHERE 
											tb.booking_date = '$booking_date'
											AND bs.booking_slot_id = $slot_id
											AND tp.package_type = $package_type
											AND tp.deity_id = $deity_id
											AND tb.booking_status NOT IN (3)
										GROUP BY 
											bp.package_id
									")->getRowArray();

					// 	echo $packages_result;
					// exit;

					if ($packages_result['package_mode'] == 2) {
						$sql = "
						SELECT COUNT(*) as count
						FROM temple_packages
						WHERE id = ? 
						AND package_type = ?
						AND status = 1
						AND deity_id = ?
					";

						$query = $this->db->query($sql, [$packages_result['package_id'], $package_type, $deity_id]);
						$result = $query->getRow();

						$act_pack_cnt = $result->count;
						if ($act_pack_cnt > 0) {
							$sql = "
									SELECT * 
									FROM temple_packages
									WHERE id = (
										SELECT package_id
										FROM temple_package_date
										WHERE package_type = ? 
										AND pack_date = ?
										LIMIT 1
									)
									AND package_type = ?
									AND status = 1
									AND deity_id = ?
								";

							$packages = $this->db->query($sql, [$package_type, $booking_date, $package_type, $deity_id])
								->getResultArray();

							$resp['data']['packages'] = $packages;
							$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id = '" . $packages_result['package_id'] . "')")->getResultArray();
						} else {
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					} else {
						// echo "packages_result";
						// exit;

						$resp['data']['packages'] = array();
						$resp['data']['addons'] = array();
					}
				} else {


					$spl_pack_query = $this->db->query("
												SELECT *
												FROM temple_packages
												WHERE package_type = ?
												AND status = 1
												AND id IN (
													SELECT package_id
													FROM temple_package_date
													WHERE package_type = ?
													AND pack_date = ?
												)
												AND id IN (
													SELECT package_id
													FROM temple_package_slots
													WHERE slot_id = ?
													AND deity_id = ?
												)
											", [$package_type, $package_type, $booking_date, $slot_id, $deity_id])->getResultArray();

					$spl_pack_cnt = count($spl_pack_query);
					// echo $spl_pack_cnt;
					// exit;
					if ($spl_pack_cnt > 0) {
						$resp['data']['packages'] = $this->db->query("
													SELECT *
													FROM temple_packages
													WHERE package_type = ?
													AND status = 1
													AND id IN (
														SELECT package_id
														FROM temple_package_date
														WHERE package_type = ?
														AND pack_date = ?
													)
													AND id IN (
														SELECT package_id
														FROM temple_package_slots
														WHERE slot_id = ?
														AND deity_id = ?
													)
												", [$package_type, $package_type, $booking_date, $slot_id, $deity_id])->getResultArray();
						$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id in(select id from temple_packages where pack_date = '$booking_date' and package_type = '$package_type' and status = 1))")->getResultArray();
					} else {
						$tot_pack_cnt = $this->db->query("
										SELECT COUNT(*) as count
										FROM temple_packages
										WHERE package_type = ?
										AND id NOT IN (
											SELECT DISTINCT package_id
											FROM temple_package_date
											WHERE package_type = ?
											AND pack_date IS NOT NULL
											AND pack_date > '1000-01-01'
										)
										AND id IN (
											SELECT package_id
											FROM temple_package_slots
											WHERE slot_id = ?
											AND deity_id = ?
										)
									", [$package_type, $package_type, $slot_id, $deity_id])->getRow()->count;
						// echo $tot_pack_cnt;
						// exit;
						if ($tot_pack_cnt > 0) {
		
							$package_query = $this->db->query("
						SELECT *
						FROM temple_packages
						WHERE package_type = ?
						AND id NOT IN (
							SELECT DISTINCT package_id
							FROM temple_package_date
							WHERE package_type = ?
							AND deity_id = ?
							AND pack_date IS NOT NULL
							AND pack_date > '1000-01-01'
						)
						AND id IN (
							SELECT package_id
							FROM temple_package_slots
							WHERE slot_id = ?
							AND deity_id = ?
						)
					", [$package_type, $package_type, $deity_id, $slot_id, $deity_id])->getResultArray();

							$resp['data']['packages'] = $package_query;
							$resp['data']['addons'] = $this->db->query("SELECT * 
																		FROM temple_services 
																		WHERE id IN (
																			SELECT service_id 
																			FROM temple_package_addons 
																			WHERE package_id IN (
																				SELECT id 
																				FROM temple_packages 
																				WHERE (pack_date IS NULL OR pack_date < '1000-01-01') 
																				AND package_type = '$package_type' 
																				AND status = 1
																			)
																		) ")->getResultArray();
						} else {
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					}
				}
			} else
				$resp['data']['packages'] = array();
		} catch (Exception $e) {
			$resp['success'] = false;
			$resp['data']['status'] = false;
			$resp['data']['message'] = $e->getMessage();
			// $resp['data']['message_type'] = 'error';
			/* log_message('error', $e->getMessage());
							  throw $e; */
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($resp);
		exit;
	}
	
	public function get_hallpackages_list_old() {
		$resp = array('success' => true, 'data' => array('status' => true));
		try{
			if(!empty($_REQUEST['booking_date'])) $request_all_data = $_REQUEST;
			else $request_all_data = json_decode(file_get_contents('php://input'), true);
			if($request_all_data['booking_date'] && $request_all_data['slot_id'] && $request_all_data['package_type']){
				$booking_date = trim($request_all_data['booking_date']);
				$slot_id = trim($request_all_data['slot_id']);
				$venue_id = trim($request_all_data['venue_id']);
				$package_type = trim($request_all_data['package_type']);
				$pack_rows = $this->db->query("SELECT max(bp.package_id) as package_id, max(tp.package_type) as package_type, max(tp.package_mode) as package_mode, max(tp.pack_date) as pack_date FROM `templebooking` tb left join booked_packages bp on bp.booking_id = tb.id left join temple_packages tp on tp.id = bp.package_id left join booked_slot bs on bs.booking_id = tb.id  WHERE `booking_date` = '$booking_date' and bs.booking_slot_id = $slot_id and tp.package_type = $package_type and tb.booking_status not in (3) GROUP by bp.package_id")->getNumRows();
				if($pack_rows > 1){
					$resp['data']['packages'] = array();
					$resp['data']['addons'] = array();
					
				}elseif($pack_rows == 1){
					$packages_result = $this->db->query("SELECT max(bp.package_id) as package_id, max(tp.package_type) as package_type, max(tp.package_mode) as package_mode, max(tp.pack_date) as pack_date FROM `templebooking` tb left join booked_packages bp on bp.booking_id = tb.id left join temple_packages tp on tp.id = bp.package_id left join booked_slot bs on bs.booking_id = tb.id  WHERE `booking_date` = '$booking_date' and bs.booking_slot_id = $slot_id and tp.package_type = $package_type and tb.booking_status not in (3) GROUP by bp.package_id")->getRowArray();
					if($packages_result['package_mode'] == 2){
						$act_pack_cnt = $this->db->table("temple_packages")->where("id", $packages_result['package_id'])->where("package_type", $package_type)->where("status", 1)->get()->getNumRows();
						if($act_pack_cnt > 0){
							$resp['data']['packages'] = $this->db->table("temple_packages")->where("id", $packages_result['package_id'])->where("package_type", $package_type)->where("status", 1)->get()->getResultArray();
							$resp['data']['addons'] =  $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id = '" . $packages_result['package_id'] . "')")->getResultArray();
						}else{
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					}else{
						$resp['data']['packages'] = array();
						$resp['data']['addons'] = array();
					}
				}else{
					$spl_pack_cnt = $this->db->table("temple_packages")->where("pack_date", $booking_date)->where("package_type", $package_type)->where("status", 1)->get()->getNumRows();
					if($spl_pack_cnt > 0){
						$resp['data']['packages'] = $this->db->table("temple_packages")->where("pack_date", $booking_date)->where("package_type", $package_type)->where("status", 1)->get()->getResultArray();
						$resp['data']['addons'] =  $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id in(select id from temple_packages where pack_date = '$booking_date' and package_type = '$package_type' and status = 1))")->getResultArray();
					}else{
						$tot_pack_cnt = $this->db->table("temple_packages")->where("package_type", $package_type)->where("(pack_date IS NULL OR pack_date = '')")->where("status", 1)->get()->getNumRows();
						if($tot_pack_cnt > 0){
							$resp['data']['packages'] = $this->db->table("temple_packages")->where("package_type", $package_type)->where("(pack_date IS NULL OR pack_date = '')")->where("status", 1)->get()->getResultArray();
							$resp['data']['addons'] =  $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id in (select id from temple_packages where (pack_date IS NULL OR pack_date = '') and package_type = '$package_type' and status = 1))")->getResultArray();
						}else{
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					}
				}
			}else $resp['data']['packages'] = array();
		} catch (Exception $e) {
			$resp['success'] = false;
			$resp['data']['status'] = false;
			$resp['data']['message'] = $e->getMessage();
			// $resp['data']['message_type'] = 'error';
			/* log_message('error', $e->getMessage());
			throw $e; */
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($resp);
		exit;
	}

	public function get_hallpackages_list() {
		$resp = array('success' => true, 'data' => array('status' => true));
		try{
			$request_all_data = !empty($_REQUEST['booking_date']) ? $_REQUEST : json_decode(file_get_contents('php://input'), true);
			if($request_all_data['booking_date'] && $request_all_data['slot_id'] && $request_all_data['package_type'] && $request_all_data['venue_id']){
				$booking_date = trim($request_all_data['booking_date']);
				$slot_id = trim($request_all_data['slot_id']);
				$venue_id = trim($request_all_data['venue_id']);
				$package_type = trim($request_all_data['package_type']);
				
				// Query to count package availability
				$query = $this->db->query("
							SELECT COUNT(*) as count FROM (
								SELECT bp.package_id 
								FROM templebooking tb
								LEFT JOIN booked_packages bp ON bp.booking_id = tb.id
								LEFT JOIN temple_packages tp ON tp.id = bp.package_id
								LEFT JOIN booked_slot bs ON bs.booking_id = tb.id
								LEFT JOIN temple_package_venues tpv ON tpv.package_id = tp.id
								WHERE tb.booking_date = '$booking_date'
								AND bs.booking_slot_id = $slot_id
								AND tp.package_type = $package_type
								AND tpv.venue_id = $venue_id
								AND tb.booking_status NOT IN (3)
								GROUP BY bp.package_id
							) AS subquery
						");
				
				$result = $query->getRowArray();
				$pack_rows = isset($result['count']) ? $result['count'] : 0;

				// echo $pack_rows;
				// exit;

				if($pack_rows > 1){
					$resp['data']['packages'] = array();
					$resp['data']['addons'] = array();
				} elseif($pack_rows == 1){
					$packages_result = $this->db->query("
										SELECT 
											MAX(bp.package_id) AS package_id, 
											MAX(tp.package_type) AS package_type, 
											MAX(tp.package_mode) AS package_mode
										FROM 
											templebooking tb
										LEFT JOIN 
											booked_packages bp ON bp.booking_id = tb.id
										LEFT JOIN 
											temple_packages tp ON tp.id = bp.package_id
										LEFT JOIN 
											booked_slot bs ON bs.booking_id = tb.id
										LEFT JOIN 
											temple_package_venues tpv ON tpv.package_id = tp.id
										WHERE 
											tb.booking_date = '$booking_date'
											AND bs.booking_slot_id = $slot_id
											AND tp.package_type = $package_type
											AND tpv.venue_id = $venue_id
											AND tb.booking_status NOT IN (3)
										GROUP BY 
											bp.package_id
									")->getRowArray();

									// echo $packages_result['package_id'];
									// exit;
	
					if($packages_result['package_mode'] == 2){

						$sql = "
							SELECT * 
							FROM temple_packages tp
							WHERE tp.id = ?
							AND tp.package_type = ?
							AND tp.status = 1
						";
	
						$packages = $this->db->query($sql, [$packages_result['package_id'], $package_type])->getResultArray();

						// echo $packages_result['package_id'];
						// exit;
	
						$resp['data']['packages'] = $packages;
						$resp['data']['addons'] = $this->db->query("select * from temple_services where id in(select service_id from temple_package_addons where package_id = '" . $packages_result['package_id'] . "')")->getResultArray();
					} else {
						$resp['data']['packages'] = array();
						$resp['data']['addons'] = array();
					}
				// Additional logic if no packages are found directly matching the criteria
				} else {
					$spl_pack_query = $this->db->query("
						SELECT tp.*
						FROM temple_packages tp
						JOIN temple_package_venues tpv ON tpv.package_id = tp.id
						JOIN temple_package_slots tps ON tps.package_id = tp.id
						WHERE tp.package_type = ?
						AND tp.status = 1
						AND tp.id IN (
							SELECT package_id
							FROM temple_package_date
							WHERE package_type = ?
							AND pack_date = ?
						)
						AND tpv.venue_id = ?
						AND tps.slot_id = ?
					", [$package_type, $package_type, $booking_date, $venue_id, $slot_id])->getResultArray();

					$spl_pack_cnt = count($spl_pack_query);

					// echo $spl_pack_cnt;
					// exit;

					if ($spl_pack_cnt > 0) {
						$resp['data']['packages'] = $spl_pack_query;
						$resp['data']['addons'] = $this->db->query("
							SELECT ts.*
							FROM temple_services ts
							WHERE ts.id IN (
								SELECT service_id
								FROM temple_package_addons
								WHERE package_id IN (
									SELECT id
									FROM temple_packages
									WHERE package_type = ?
									AND status = 1
									AND id IN (
										SELECT package_id
										FROM temple_package_date
										WHERE pack_date = ?
										AND package_type = ?
									)
								)
							)
						", [$package_type, $booking_date, $package_type])->getResultArray();
					} else {
						$tot_pack_cnt = $this->db->query("
							SELECT COUNT(*) as count
							FROM temple_packages tp
							JOIN temple_package_venues tpv ON tpv.package_id = tp.id
							JOIN temple_package_slots tps ON tps.package_id = tp.id
							WHERE tp.package_type = ?
							AND tp.id NOT IN (
								SELECT distinct package_id
								FROM temple_package_date
								WHERE package_type = ?
								AND pack_date IS NOT NULL
								AND pack_date > '1000-01-01'
							)
							AND tp.status = 1
							AND tpv.venue_id = ?
							AND tps.slot_id = ?
						", [$package_type, $package_type, $venue_id, $slot_id])->getRow()->count;

						// echo $tot_pack_cnt;
						// exit;

						if ($tot_pack_cnt > 0) {
							$resp['data']['packages'] = $this->db->query("
								SELECT tp.*
								FROM temple_packages tp
								JOIN temple_package_venues tpv ON tpv.package_id = tp.id
								JOIN temple_package_slots tps ON tps.package_id = tp.id
								WHERE tp.package_type = ?
								AND tp.status = 1
								AND tp.id NOT IN (
									SELECT distinct package_id
									FROM temple_package_date
									WHERE package_type = ?
									AND pack_date IS NOT NULL
									AND pack_date > '1000-01-01'
								)
								AND tpv.venue_id = ?
								AND tps.slot_id = ?
							", [$package_type, $package_type, $venue_id, $slot_id])->getResultArray();
							/* echo  $this->db->getLastQuery();
							die; */
							$resp['data']['addons'] = $this->db->query("
								SELECT ts.*
								FROM temple_services ts
								WHERE ts.id IN (
									SELECT service_id
									FROM temple_package_addons
									WHERE package_id IN (
										SELECT id
										FROM temple_packages
										WHERE package_type = ?
										AND status = 1
										AND id IN (
											SELECT package_id
											FROM temple_package_date
											WHERE package_type = ?
											AND (pack_date IS NULL OR pack_date < '1000-01-01')
										)
									)
								)

							", [$package_type, $package_type])->getResultArray();
						} else {
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					}
				}

			} else {
				$resp['data']['packages'] = array();
			}
		} catch (Exception $e) {
			$resp['success'] = false;
			$resp['data']['status'] = false;
			$resp['data']['message'] = $e->getMessage();
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($resp);
		exit;
	}
		
	public function get_hallpackages_list_oldddd() {
		$resp = array('success' => true, 'data' => array('status' => true));
		try {
			if(!empty($_REQUEST['booking_date'])) $request_all_data = $_REQUEST;
			else $request_all_data = json_decode(file_get_contents('php://input'), true);
			if($request_all_data['booking_date'] && $request_all_data['slot_id'] && $request_all_data['package_type'] && $request_all_data['venue_id'] && isset($request_all_data['is_weekend'])) {
				$booking_date = trim($request_all_data['booking_date']);
				$slot_id = trim($request_all_data['slot_id']);
				$package_type = trim($request_all_data['package_type']);
				$venue_id = trim($request_all_data['venue_id']);
				$is_weekend = trim($request_all_data['is_weekend']);
	
				// Modified query to include venue and conditional amount
				$pack_rows = $this->db->query("SELECT max(bp.package_id) as package_id, max(tp.package_type) as package_type, max(IF('$is_weekend' = '1', tp.weekend_amount, tp.amount)) as package_amount, max(tp.package_mode) as package_mode, max(tp.pack_date) as pack_date 
					FROM templebooking tb 
					LEFT JOIN booked_packages bp ON bp.booking_id = tb.id 
					LEFT JOIN temple_packages tp ON tp.id = bp.package_id 
					LEFT JOIN booked_slot bs ON bs.booking_id = tb.id 
					LEFT JOIN temple_package_venues tpv ON tpv.package_id = tp.id
					WHERE booking_date = '$booking_date' AND bs.booking_slot_id = $slot_id AND tp.package_type = $package_type AND tb.booking_status NOT IN (3) AND tpv.venue_id = '$venue_id' 
					GROUP BY bp.package_id")->getNumRows();
	
				if($pack_rows > 1){
					$resp['data']['packages'] = array();
					$resp['data']['addons'] = array();
				} elseif($pack_rows == 1){
					$packages_result = $this->db->query("SELECT max(bp.package_id) as package_id, max(tp.package_type) as package_type, max(IF('$is_weekend' = '1', tp.weekend_amount, tp.amount)) as package_amount, max(tp.package_mode) as package_mode, max(tp.pack_date) as pack_date 
						FROM templebooking tb 
						LEFT JOIN booked_packages bp ON bp.booking_id = tb.id 
						LEFT JOIN temple_packages tp ON tp.id = bp.package_id 
						LEFT JOIN booked_slot bs ON bs.booking_id = tb.id 
						LEFT JOIN temple_package_venues tpv ON tpv.package_id = tp.id
						WHERE booking_date = '$booking_date' AND bs.booking_slot_id = $slot_id AND tp.package_type = $package_type AND tb.booking_status NOT IN (3) AND tpv.venue_id = '$venue_id' 
						GROUP BY bp.package_id")->getRowArray();
					if($packages_result['package_mode'] == 2){
						$act_pack_cnt = $this->db->table("temple_packages")->where("id", $packages_result['package_id'])->where("package_type", $package_type)->where("status", 1)->get()->getNumRows();
						if($act_pack_cnt > 0){
							$resp['data']['packages'] = $this->db->table("temple_packages")->where("id", $packages_result['package_id'])->where("package_type", $package_type)->where("status", 1)->get()->getResultArray();
							$resp['data']['addons'] = $this->db->query("SELECT * FROM temple_services WHERE id IN (SELECT service_id FROM temple_package_addons WHERE package_id = '" . $packages_result['package_id'] . "')")->getResultArray();
						} else {
							$resp['data']['packages'] = array();
							$resp['data']['addons'] = array();
						}
					} else {
						$resp['data']['packages'] = array();
						$resp['data']['addons'] = array();
					}
				} else {
					$resp['data']['packages'] = array();
					$resp['data']['addons'] = array();
					$resp['data']['message'] = "No package available in this venue";
				}
			} else {
				$resp['data']['packages'] = array();
				$resp['data']['message'] = "Required parameters are missing";
			}
		} catch (Exception $e) {
			$resp['success'] = false;
			$resp['data']['status'] = false;
			$resp['data']['message'] = $e->getMessage();
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($resp);
		exit;
	}
	
	public function send_whatsapp_msg_booking($booking_id, $booking_type) {
		$booking_data = $this->db->table('templebooking')
			->where('id', $booking_id)
			->where('booking_type', $booking_type)
			->get()->getRowArray();
		$booked_slot = $this->db->table('booked_slot')->where('booking_id', $booking_id)->get()->getResultArray();
		$booked_slot_name = array();
		foreach($booked_slot as $bs){
			$booked_slot_name[] = $bs['slot_name'];
		}
		// $tmpid = 1;
		// $data['temple_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		// $data['payment'] = $this->db->table('ubayam_pay_details')->where('ubayam_id', $id)->get()->getResultArray();
		// $data['terms'] = $this->db->table("terms_conditions")->get()->getRowArray();
		// $data['pay_details'] = $this->db->table("ubayam_pay_details")->where("ubayam_id", $id)->get()->getResultArray();
		if (!empty($booking_data['mobile_code']) && !empty($booking_data['mobile_no'])) {
			// $html = view('ubayam/pdf', $data);
			// $options = new Options();
			// $options->set('isHtml5ParserEnabled', true);
			// $options->set(array('isRemoteEnabled' => true));
			// $options->set('isPhpEnabled', true);
			// $dompdf = new Dompdf($options);
			// $dompdf->loadHtml($html);
			// $dompdf->setPaper('A4', 'portrait');
			// $dompdf->render();
			// $filePath = FCPATH . 'uploads/documents/invoice_ubayam_' . $id . '.pdf';

			// file_put_contents($filePath, $dompdf->output());
			$mobile_code = $booking_data['mobile_code'];
			$mobile_no = $booking_data['mobile_no'];
			$mobile_number = $mobile_code . $mobile_no;
			$message_params = array();
			$media=array();
			$api_camp = 'ubayam_live';
			if($booking_type == 2){
				$message_params[] = date('d M, Y', strtotime($booking_data['booking_date']));
				$message_params[] = implode(', ', $booked_slot_name);
				// $message_params[] = number_format($booking_data['amount'], 2);
				$message_params[] = number_format($booking_data['paid_amount'], 2);
				$api_camp = 'ubayam_live';
			}elseif($booking_type == 1){
				$booked_packages = $this->db->table('booked_packages')->where('booking_id', $booking_id)->get()->getResultArray();
				$booked_pack_name = array();
				foreach($booked_packages as $bp){
					$booked_pack_name[] = $bp['name'];
				}
				$message_params[] = implode(', ', $booked_pack_name);
				$message_params[] = date('d M, Y', strtotime($booking_data['booking_date']));
				$message_params[] = implode(', ', $booked_slot_name);
				// $message_params[] = number_format($booking_data['amount'], 2);
				$message_params[] = number_format($booking_data['paid_amount'], 2);
				$api_camp = 'hall_booking';
			}
			// $message_params[] = $ubayam['balanceamount'];
			// $media['url'] = base_url() . '/uploads/documents/invoice_ubayam_' . $id . '.pdf';
			// $media['filename'] = 'ubayam_invoice.pdf';
			//$mobile_number = '+919092615446';
			// print_r($mobile_number);
			// print_r($message_params);
			// print_r($media);
			// die; 
			$whatsapp_resp = whatsapp_aisensy($mobile_number, $message_params, $api_camp, $media);
			// print_r($whatsapp_resp);
			//echo $whatsapp_resp['success'];
			// if($whatsapp_resp['success']) 
		}
	}

	public function partial_account_migration($booked_pay_id) {
		$succ = true;
		$booked_pay_details_cnt = $this->db->table("booked_pay_details")->where("id", $booked_pay_id)->get()->getNumRows();	
		if ($booked_pay_details_cnt > 0) {
			$booked_pay_details = $this->db->table("booked_pay_details")->where("id", $booked_pay_id)->get()->getResultArray();
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

					$entries['entry_code'] = 'REC' . date('y', strtotime($entry_date)) . $mon . (sprintf("%05d", (((float) substr($qry['entry_code'], -5)) + 1)));
					$entries['entrytype_id'] = '1';
					$entries['number'] = $num;
					$entries['date'] = $entry_date;
					$entries['dr_total'] = $row['amount'];
					$entries['cr_total'] = $row['amount'];
					$entries['narration'] = 'Hall Booking(' . $templeubayam['ref_no'] . ')' . "\n" . 'name:' . $templeubayam['name'] . "\n" . 'NRIC:' . $templeubayam['ic_number'] . "\n" . 'email:' . $templeubayam['email'] . "\n";
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
						$eitems_hall_book['details'] = 'Hall Booking Amount';
						$this->db->table('entryitems')->insert($eitems_hall_book);
						// PETTY CASH => Debit 
						$eitems_cash_led['entry_id'] = $en_id;
						$eitems_cash_led['ledger_id'] = $paymentmode['ledger_id'];
						$eitems_cash_led['amount'] = $row['amount'];
						$eitems_cash_led['dc'] = 'D';
						$eitems_cash_led['details'] = 'Hall Booking Amount';
						$this->db->table('entryitems')->insert($eitems_cash_led);
					}
				}else{
					$succ = false;
					return $succ;
				}
			}
		}else{
			$succ = false;
			return $succ;
		}
	}

	public function getDeitiesBySlot() {
		// Retrieve package_type and slot_id from POST request
		$package_type = $this->request->getPost('package_type');
		$slot_id = $this->request->getPost('slot_id');

		// Check if the package_type is 2 and slot_id is provided
		if ($package_type == '2' && !empty($slot_id)) {
			// Fetch distinct deity_ids from temple_package_slots based on slot_id
			$deity_ids = $this->db->table('temple_package_slots')
				->select('DISTINCT(deity_id)')
				->where('slot_id', $slot_id)
				->get()
				->getResultArray();

			// Check if deity_ids are found
			if (!empty($deity_ids)) {
				// Extract deity IDs into an array
				$deity_id_array = array_column($deity_ids, 'deity_id');

				// Fetch deity details from archanai_diety table using deity IDs
				$deities = $this->db->table('archanai_diety')
					->whereIn('id', $deity_id_array)
					->get()
					->getResultArray();

				// Return the deities as a JSON response
				return $this->response->setJSON([
					'success' => true,
					'deities' => $deities
				]);
			} else {
				// Return an empty result with a success status
				return $this->response->setJSON([
					'success' => true,
					'deities' => []
				]);
			}
		} else {
			// Return an error response if the request is invalid
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Invalid request parameters.'
			]);
		}
	}
}
