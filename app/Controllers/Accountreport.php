<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\PermissionModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Accountreport extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		helper('common');
		$this->model = new PermissionModel();
		if (($this->session->get('login')) == false && $this->session->get('role') != 1) {
			$data['dn_msg'] = 'Please Login';
			return redirect()->to('/login');
		}
	}

	public function ledger_report()
	{
		if (!$this->model->list_validate('ledger_report_accounts')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data['permission'] = $this->model->get_permission('ledger_report_accounts');

		$id = $this->request->uri->getSegment(3);
		if (!empty($id))
			$ledger_id = $id;
		else if ($_POST['ledger'])
			$ledger_id = $_POST['ledger'];
		else
			$ledger_id = 1;
		
		if ($_POST['fdate'])
			$fdate = $_POST['fdate'];
		else
			$fdate = date("Y-m-01");

		if ($_POST['tdate'])
			$tdate = $_POST['tdate'];
		else
			$tdate = date("Y-m-d");

		$led_res = $this->ledger_statement($ledger_id, $fdate, $tdate);
		$group = $this->db->table("groups")->get()->getResultArray();

		foreach ($group as $row) {
			$ledger[] = '<optgroup label="' . $row['name'] . '">';
			$res = $this->db->table("ledgers")->where('group_id', $row['id'])->get()->getResultArray();
			foreach ($res as $r) {
				$id = $r['id'];
				$ledgername = $r['left_code'] . '/' . $r['right_code'] . '-' . $r['name'];
				if ($ledger_id == $id)
					$selected = 'selected';
				else
					$selected = '';
				$ledger[] .= '<option ' . $selected . ' value="' . $id . '">' . $ledgername . '</option>';
			}
			$ledger[] .= '</optgroup>';
		}
		$data['ledger_id'] = $ledger_id;
		$data['fdate'] = $fdate;
		$data['tdate'] = $tdate;
		$data['res'] = $led_res;
		$data['ledger'] = $ledger;
		$data['check_financial_year'] = $this->db->table("ac_year")->where('status', 1)->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/ledger_report', $data);
		echo view('template/footer');
	}
	public function new_ledger_report()
	{
		// Increase memory limit temporarily for this report
		ini_set('memory_limit', '256M');
		
		if (!$this->model->list_validate('ledger_report_accounts')) {
			return redirect()->to(base_url() . '/dashboard');
		}

		$data['permission'] = $this->model->get_permission('ledger_report_accounts');

		// Get parameters
		$id = $this->request->uri->getSegment(3);
		
		// Handle ledger selection
		if (!empty($id)) {
			$ledger_id = [$id]; // Ensure it's an array
		} else if ($this->request->getPost('ledger')) {
			$ledger_id = $this->request->getPost('ledger');
		} else {
			$ledger_id = array(1);
		}

		// Limit number of ledgers to prevent memory issues
		if(count($ledger_id) > 10) {
			$ledger_id = array_slice($ledger_id, 0, 10);
			$data['warning'] = 'Maximum 10 ledgers can be selected at once for performance reasons.';
		}

		// Handle date parameters
		$fdate = $this->request->getPost('fdate') ?? date("Y-m-01");
		$tdate = $this->request->getPost('tdate') ?? date("Y-m-d");
		
		// Handle booking type filter
		$booking_type = $this->request->getPost('booking_type') ?? 'all';

		// Build ledger dropdown options - Optimized query
		$ledger = [];
		$groups = $this->db->table("groups")->select('id, name')->get()->getResultArray();
		
		foreach ($groups as $group) {
			$ledger[] = '<optgroup label="' . htmlspecialchars($group['name']) . '">';
			
			// Get only necessary fields
			$ledgers = $this->db->table("ledgers")
							   ->select('id, name, left_code, right_code')
							   ->where('group_id', $group['id'])
							   ->orderBy('name', 'ASC')
							   ->get()
							   ->getResultArray();
			
			foreach ($ledgers as $l) {
				$ledgername = $l['left_code'] . '/' . $l['right_code'] . '-' . $l['name'];
				$selected = in_array($l['id'], $ledger_id) ? 'selected' : '';
				$ledger[] = '<option value="' . $l['id'] . '" ' . $selected . '>' . htmlspecialchars($ledgername) . '</option>';
			}
			
			$ledger[] = '</optgroup>';
		}

		// Pass data to view
		$data['ledger_id'] = $ledger_id;
		$data['fdate'] = $fdate;
		$data['tdate'] = $tdate;
		$data['booking_type'] = $booking_type;
		$data['ledger'] = $ledger;
		$data['check_financial_year'] = $this->db->table("ac_year")->where('status', 1)->get()->getResultArray();

		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/new_ledger_report', $data);
		echo view('template/footer');
	}
	public function print_multiple_ledger_statement()
	{
		$ledger_id = $_POST['ledger'];
		$fdate = $_POST['fdate'];
		$tdate = $_POST['tdate'];
		$booking_type = $this->request->getPost('booking_type') ?? 'all'; // NEW
		//$led_res = $this->ledger_statement($ledger_id, $fdate, $tdate);
		//$data = $led_res;
		$data['ledger_id'] = $ledger_id;
		$data['fdate'] = $fdate;
		$data['tdate'] = $tdate;
		$data['booking_type'] = $booking_type;
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		echo view('account_report/multiple_ledger_statement', $data);
	}

	public function pdf_multiple_ledger_statement()
	{
		$ledger_id = $_POST['ledger'];
		$fdate = $_POST['fdate'];
		$tdate = $_POST['tdate'];
		$booking_type = $this->request->getPost('booking_type') ?? 'all'; // NEW
		//$led_res = $this->ledger_statement($ledger_id, $fdate, $tdate);
		//$data = $led_res;
		$data['ledger_id'] = $ledger_id;
		$data['fdate'] = $fdate;
		$data['tdate'] = $tdate;
		$data['booking_type'] = $booking_type;
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		$file_name = "General Ledger_" . $data['fdate'] . "_to_" . $data['tdate'];
		$dompdf = new \Dompdf\Dompdf();
		$options = $dompdf->getOptions();
		$options->set(array('isRemoteEnabled' => true));
		$dompdf->setOptions($options);
		$dompdf->loadHtml(view('account_report/pdf_multiple_ledger_statement', ["pdfdata" => $data]));
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
		$dompdf->stream($file_name);
	}
	public function excel_multiple_ledger_statement()
	{
		$ledger_ids = $_POST['ledger'];
		$fdate = $_POST['fdate'];
		$tdate = $_POST['tdate'];
		$booking_type = $this->request->getPost('booking_type') ?? 'all'; // NEW
		//$led_res = $this->ledger_statement($ledger_id, $fdate, $tdate);
		//$data = $led_res;
		$data['ledger_id'] = $ledger_ids;
		$data['fdate'] = $fdate;
		$data['tdate'] = $tdate;
		$data['booking_type'] = $booking_type;
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		$file_name = "General Ledger_" . $data['fdate'] . "_to_" . $data['tdate'];
		$csv_arr = array();
		foreach($ledger_ids as $ledgerid){
			$ledgername_code = get_ledger_name($ledgerid);
			$res_ledger = loop_general_ledger_statement($ledgerid,$fdate, $tdate, $booking_type);
			$csv_arr[] = array('','','',$ledgername_code,'','','','');
			$csv_arr[] = array('','','','','','','','');
			$csv_arr[] = array('Date','Ref No','Particulars','Debit','Credit','Net Activity','Balance');
			$op_bal_txt = '';
			if($res_ledger['op_bal'] < 0){
				$op_bal_txt = "( ".number_format(abs($res_ledger['op_bal']),'2','.',',')." )";
			}
			else{
				$op_bal_txt = number_format($res_ledger['op_bal'],'2','.',',');
			}
			$csv_arr[] = array('','','Opening Balance','','','',$op_bal_txt);
			$cu_credit = 0;
            $cu_debit = 0;
			 foreach($res_ledger['data'] as $row) { 
                    if(!empty($row['credit_amount'])) $cu_credit += (float) $row['credit_amount'];
                    if(!empty($row['debit_amount'])) $cu_debit += (float) $row['debit_amount'];
					$trans_amount_txt = '';
					if($row['balance'] < 0){
						$trans_amount_txt = "( ".number_format(abs($row['balance']),2)." )";
					}
					else{
						$trans_amount_txt = number_format($row['balance'],2);
					}
					$csv_arr[] = array(date('d-m-Y',strtotime($row['date'])),$row['entry_code'],$row['narration'],$row['debit'],$row['credit'],'',$trans_amount_txt);
			}
			$cl_bal_txt = '';
			if($res_ledger['cl_bal'] < 0){
				$cl_bal_txt = "( ".number_format(abs($res_ledger['cl_bal']),'2','.',',')." )";
			}
			else{
				$cl_bal_txt = number_format($res_ledger['cl_bal'],'2','.',',');
			}
			$diffrence_amt = $cu_debit - $cu_credit;
			$csv_arr[] = array('','','Closing Balance',number_format($cu_debit,'2','.',','),number_format($cu_credit,'2','.',','),number_format(abs($diffrence_amt),'2','.',','),$cl_bal_txt);
			$csv_arr[] = array('','','','','','','','');
		}
		$filename = "data_export_" . date("Y-m-d") . ".csv";
		$now = gmdate("D, d M Y H:i:s");
		header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: {$now} GMT");

		// force download  
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");

		// disposition / encoding on response body
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: binary");
		echo $this->array2csv($csv_arr);
		die();	
	}
	public function excel_multiple_ledger_statement1()
	{
		$ledger_ids = $_POST['ledger'];
		$fdate = $_POST['fdate'];
		$tdate = $_POST['tdate'];
		//$led_res = $this->ledger_statement($ledger_id, $fdate, $tdate);
		//$data = $led_res;
		$data['ledger_id'] = $ledger_ids;
		$data['fdate'] = $fdate;
		$data['tdate'] = $tdate;
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		$file_name = "General Ledger_" . $data['fdate'] . "_to_" . $data['tdate'];
		$spreadsheet = new Spreadsheet();
		$i = 0;
		foreach($ledger_ids as $ledgerid){
			$csv_arr = array();
			$ledgername_code = get_ledger_name($ledgerid);
			$ledgername = get_ledger_name_only($ledgerid);
			$res_ledger = loop_general_ledger_statement($ledgerid,$fdate, $tdate);
			if(!empty($i)) $spreadsheet->createSheet();
			$spreadsheet->setActiveSheetIndex($i);
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setTitle($ledgername);
			$sheet->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$style = array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				)
			);
			$sheet->getStyle("A1:G1")->applyFromArray($style);
			$sheet->getStyle("A4:g4")->applyFromArray($style);
			$sheet->getStyle('A1:G1')->getFont()->setBold(true);
			$sheet->getStyle('A3:G3')->getFont()->setBold(true);
			$sheet->getStyle('A4:G4')->getFont()->setBold(true);
			$sheet->mergeCells('A1:G1'); 
			$sheet->mergeCells('A4:C4');
			$sheet->setCellValue('A1', $ledgername_code);
			$sheet->setCellValue('A3', 'Date');
			$sheet->setCellValue('B3', 'Ref No');
			$sheet->setCellValue('C3', 'Particulars');
			$sheet->setCellValue('D3', 'Debit');
			$sheet->setCellValue('E3', 'Credit');
			$sheet->setCellValue('F3', 'Net Activity');
			$sheet->setCellValue('G3', 'Balance');
			$op_bal_txt = '';
			if($res_ledger['op_bal'] < 0){
				$op_bal_txt = "( ".number_format(abs($res_ledger['op_bal']),'2','.',',')." )";
			}
			else{
				$op_bal_txt = number_format($res_ledger['op_bal'],'2','.',',');
			}
			$sheet->setCellValue('A4', 'Opening Balance');
			$sheet->setCellValue('H4', $op_bal_txt);
			$rows = 5;
			$cu_credit = 0;
			$cu_debit = 0;
			 foreach($res_ledger['data'] as $row) { 
					if(!empty($row['credit_amount'])) $cu_credit += (float) $row['credit_amount'];
					if(!empty($row['debit_amount'])) $cu_debit += (float) $row['debit_amount'];
					$trans_amount_txt = '';
					if($row['balance'] < 0){
						$trans_amount_txt = "( ".number_format(abs($row['balance']),2)." )";
					}
					else{
						$trans_amount_txt = number_format($row['balance'],2);
					}
					$csv_arr[] = array(date('d-m-Y',strtotime($row['date'])),$row['entry_code'],$row['ledger'],$row['narration'],$row['debit'],$row['credit'],'',$trans_amount_txt);
					$sheet->setCellValue('A' . $rows, date('d-m-Y',strtotime($row['date'])));
					$sheet->setCellValue('B' . $rows, $row['entry_code']);
					$sheet->setCellValue('C' . $rows, $row['narration']);
					$sheet->setCellValue('D' . $rows, $row['debit']);
					$sheet->setCellValue('E' . $rows, $row['credit']);
					$sheet->setCellValue('F' . $rows, '');
					$sheet->setCellValue('G' . $rows, $trans_amount_txt);
					$rows++;
			}
			$cl_bal_txt = '';
			if($res_ledger['cl_bal'] < 0){
				$cl_bal_txt = "( ".number_format(abs($res_ledger['cl_bal']),'2','.',',')." )";
			}
			else{
				$cl_bal_txt = number_format($res_ledger['cl_bal'],'2','.',',');
			}
			$diffrence_amt = $cu_debit - $cu_credit;
			$sheet->mergeCells('A' . $rows . ':C' . $rows);
			$sheet->getStyle('A' . $rows . ':C' . $rows)->applyFromArray($style);
			$sheet->getStyle('A' . $rows . ':H' . $rows)->getFont()->setBold(true);
			$sheet->setCellValue('A' . $rows, 'Closing Balance');
			$sheet->setCellValue('D' . $rows, number_format($cu_debit,'2','.',','));
			$sheet->setCellValue('E' . $rows, number_format(abs($cu_credit),'2','.',','));
			$sheet->setCellValue('F' . $rows, number_format(abs($diffrence_amt),'2','.',','));
			$sheet->setCellValue('G' . $rows, $cl_bal_txt);
			$i++;
		}
		$fileName = "gl_statement".$fdate."_to_".$tdate;
		$writer = new Xlsx($spreadsheet);
        $writer->save('uploads/excel/'.$fileName.'.xlsx');
        return $this->response->download('uploads/excel/'.$fileName.'.xlsx', null)->setFileName($fileName.'.xlsx');
	}
	public function array2csv(array &$array){
		if (count($array) == 0) {
			return null;
		}
		ob_start();
		$df = fopen("php://output", 'w');
		//fputcsv($df, array_keys(reset($array)));
		foreach ($array as $row) {
			fputcsv($df, $row);
		}
		fclose($df);
		return ob_get_clean();
	}
	public function trail_balance()
	{
		if (!$this->model->list_validate('trial_balance_accounts')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data['permission'] = $this->model->get_permission('trial_balance_accounts');
		$ac_id = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		if ($_POST['fdate'])
			$sdate = $_POST['fdate'];
		else
			$sdate = date("Y-m-01");
		if ($_POST['tdate'])
			$tdate = $_POST['tdate'];
		else
			$tdate = date("Y-m-d");
		//echo $sdate; echo $tdate;die;
		$query = $this->db->query('select * from `groups` where parent_id = "" or parent_id is NULL or parent_id = 0 order by id asc');
		//$query = $this->db->table('groups')->where('parent_id is NULL')->get()->getResultArray();
		$parentgroup = $query->getResultArray();
		//echo '<pre>';
		//print_r($data);die;
		$datas = array();
		foreach ($parentgroup as $row) {
			//print_r($row['id']);
			/*$datas[] = '<tr>
										<td>'.$row['name'].'</td>
									 <td>Group</td>
									 <td>-</td>
									 <td>-</td>
									 <td>-</td>
									 <td>-</td>
								</tr>';*/
			$presult = $this->db->table('ledgers')->where('group_id', $row['id'])->get()->getResultArray();
			if (!empty($presult)) {
				foreach ($presult as $dd) {
					$id = $dd['id'];
					$ledgername = get_ledger_name_only($id);
					$ledgercode = get_ledger_code_only($id);
					$debitamt = 0;
					$creditamt = 0;
					$op_bal = 0;
					$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
					if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
						$op_balance_amt = $op_balance['cr_amount'];
					} else {
						$op_balance_amt = $op_balance['dr_amount'];
					}
					$op_bal = $op_balance_amt;
					$d_sql = "select sum(entryitems.amount) as amount 
                                            from entryitems 
                                            inner join entries on entries.id = entryitems. entry_id
                                            where entryitems.dc = 'D' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
					$c_sql = "select sum(entryitems.amount) as amount 
                                            from entryitems 
                                            inner join entries on entries.id = entryitems. entry_id
                                            where entryitems.dc = 'C' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
					$d_amt = $this->db->query($d_sql)->getRowArray();
					$c_amt = $this->db->query($c_sql)->getRowArray();
					$debitamt = $d_amt['amount'];
					$creditamt = $c_amt['amount'];
					$clbal = ($op_bal + $debitamt) - $creditamt;
					if (!empty($debitamt) || !empty($creditamt)) {
						$datas[] = '<tr>
                                    <td>' . $ledgercode . '</td>
                                    <td><a href="#" style="" id="' . $dd['id'] . '" onclick="ledger_report(' . $dd['id'] . ')">' . $ledgername . '</a>
                                    </td>
                                    <td align="right">' . number_format($debitamt, '2', '.', ',') . '</td>
                                    <td align="right">' . number_format($creditamt, '2', '.', ',') . '</td>
                                </tr>';
					}
					$totalopb += $op_balance_amt;
					$totaldeb += $debitamt;
					$totalcre += $creditamt;
					$totalclb += $clbal;
				}
			}
			$childgroup = $this->db->table('groups')->where('parent_id', $row['id'])->get()->getResultArray();
			if (!empty($childgroup)) {
				foreach ($childgroup as $crow) {
					/*$datas[] = '<tr>
								<td>&emsp;&emsp;'.$crow['name'].'</td>
								<td>Group</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
						</tr>';*/
					$res = $this->db->table('ledgers')->where('group_id', $crow['id'])->get()->getResultArray();
					foreach ($res as $dd) {
						$id = $dd['id'];
						$ledgername = get_ledger_name_only($id);
						$ledgercode = get_ledger_code_only($id);
						$debitamt = 0;
						$creditamt = 0;
						$op_bal = 0;
						$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
						if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
							$op_balance_amt = $op_balance['cr_amount'];
						} else {
							$op_balance_amt = $op_balance['dr_amount'];
						}
						$op_bal = $op_balance_amt;
						$d_sql = "select sum(entryitems.amount) as amount 
                                                    from entryitems 
                                                    inner join entries on entries.id = entryitems. entry_id
                                                    where entryitems.dc = 'D' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
						$c_sql = "select sum(entryitems.amount) as amount 
                                                    from entryitems 
                                                    inner join entries on entries.id = entryitems. entry_id
                                                    where entryitems.dc = 'C' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
						$d_amt = $this->db->query($d_sql)->getRowArray();
						$c_amt = $this->db->query($c_sql)->getRowArray();
						$debitamt = $d_amt['amount'];
						$creditamt = $c_amt['amount'];
						$clbal = ($op_bal + $debitamt) - $creditamt;
						if (!empty($debitamt) || !empty($creditamt)) {
							$datas[] = '<tr>
                                            <td>' . $ledgercode . '</td>
                                            <td><a href="#" style="" id="' . $dd['id'] . '" onclick="ledger_report(' . $dd['id'] . ')">' . $ledgername . '</a></td>
                                            <td align="right">' . number_format($debitamt, '2', '.', ',') . '</td>
                                            <td align="right">' . number_format($creditamt, '2', '.', ',') . '</td>
                                        </tr>';
						}
						$totalopb += $op_balance_amt;
						$totaldeb += $debitamt;
						$totalcre += $creditamt;
						$totalclb += $clbal;
					}
					$cgroup = $this->db->table('groups')->where('parent_id', $crow['id'])->get()->getResultArray();
					foreach ($cgroup as $ccg) {
						$cgchild = $this->db->table('ledgers')->where('group_id', $ccg['id'])->get()->getResultArray();
						/*$datas[] = '<tr>
								<td>&emsp;&emsp;'.$ccg['name'].'</td>
								<td>Group</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
						</tr>';*/
						foreach ($cgchild as $dd) {
							$id = $dd['id'];
							$ledgername = get_ledger_name_only($id);
							$ledgercode = get_ledger_code_only($id);
							$debitamt = 0;
							$creditamt = 0;
							$op_bal = 0;
							$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
							if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
								$op_balance_amt = $op_balance['cr_amount'];
							} else {
								$op_balance_amt = $op_balance['dr_amount'];
							}
							$op_bal = $op_balance_amt;
							$d_sql = "select sum(entryitems.amount) as amount 
                                                    from entryitems 
                                                    inner join entries on entries.id = entryitems. entry_id
                                                    where entryitems.dc = 'D' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
							$c_sql = "select sum(entryitems.amount) as amount 
                                                    from entryitems 
                                                    inner join entries on entries.id = entryitems. entry_id
                                                    where entryitems.dc = 'C' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
							$d_amt = $this->db->query($d_sql)->getRowArray();
							$c_amt = $this->db->query($c_sql)->getRowArray();
							$debitamt = $d_amt['amount'];
							$creditamt = $c_amt['amount'];
							$clbal = ($op_bal + $debitamt) - $creditamt;
							if (!empty($debitamt) || !empty($creditamt)) {
								$datas[] = '<tr>
                                            <td>' . $ledgercode . '</td>
                                            <td><a href="#" style="" id="' . $dd['id'] . '" onclick="ledger_report(' . $dd['id'] . ')">' . $ledgername . '</a></td>
                                            <td align="right">' . number_format($debitamt, '2', '.', ',') . '</td>
                                            <td align="right">' . number_format($creditamt, '2', '.', ',') . '</td>
                                        </tr>';
							}
							$totalopb += $op_balance_amt;
							$totaldeb += $debitamt;
							$totalcre += $creditamt;
							$totalclb += $clbal;
						}
					}
				}
			}
			//print_r($res);
		}//die;
		$datas[] = '<tfoot><tr style="color: black;">
					<td align="right" colspan="2"><b>Total</b></td>
					<td align="right">' . number_format($totaldeb, '2', '.', '') . '</td>
					<td align="right">' . number_format($totalcre, '2', '.', '') . '</td>
					</tr></tfoot>';
		$data['sdate'] = $sdate;
		$data['tdate'] = $tdate;
		$data['list'] = $datas;
		$data['check_financial_year'] = $this->db->table("ac_year")->where('status', 1)->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account_report/trail_balance', $data);
		echo view('template/footer');
	}

	public function balance_sheet()
	{
		if (!$this->model->list_validate('balance_sheet_accounts')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data['permission'] = $this->model->get_permission('balance_sheet_accounts');

		echo view('template/header');
		echo view('template/sidebar');
		//echo view('account/index', $data);
		echo view('template/footer');
	}
	public function getGroupSales($groupId, $fromDate, $toDate)
	{
		$sql = "SELECT ei.ledger_id, l.name as ledger_name, l.left_code, l.right_code, l.group_id, 
					   g.name as group_name, COALESCE(SUM(IF(ei.dc = 'C', amount, 0)), 0) as cr_total, 
					   COALESCE(SUM(IF(ei.dc = 'D', amount, 0)), 0) as dr_total 
				FROM ledgers l 
				LEFT JOIN `groups` g ON g.id = l.group_id 
				LEFT JOIN entryitems ei ON ei.ledger_id = l.id 
				LEFT JOIN entries e ON e.id = ei.entry_id 
				WHERE g.id = $groupId AND e.date BETWEEN '$fromDate' AND '$toDate' 
				GROUP BY ei.ledger_id 
				ORDER BY l.left_code, l.right_code ASC";
		return $this->db->query($sql)->getResultArray();
	}

	// Define a function to render the HTML table rows
	public function renderTableRows($groups, $classLevel)
	{
		$rows = array();
		foreach ($groups as $group) {
			$total = $group['cr_total'] - $group['dr_total'];
			$ledgerCode = $group['left_code'] . '/' . $group['right_code'];
			$totalAmount = ($total < 0) ? "( " . number_format(abs($total), 2) . " )" : number_format($total, 2);

			$html = '<tr><td class="' . $classLevel . '">(' . $ledgerCode . ')' . $group['ledger_name'] . '</td>';
			$html .= '<td align="right">' . $totalAmount . '</td></tr>';
			$rows[] = $html;
		}
		return $rows;
	}



	public function ledger_statement($ledger = '', $fdate = '', $tdate = '')
	{
		$id = $this->request->uri->getSegment(3);
		if (!empty($id))
			$ledger = $id;
		else {
			if ($_POST['fdate'])
				$fdate = $_POST['fdate'];
			else
				$fdate = $fdate;
			if ($_POST['tdate'])
				$tdate = $_POST['tdate'];
			else
				$tdate = $tdate;
			$tdate = $_POST['tdate'];
		}
		//echo $ledger; echo '<br>'; echo $fdate; echo '<br>'; echo $tdate; die;
		if (empty($fdate) && empty($tdate)) {
			$res = $this->db->table('entryitems', 'entries')
				->join('entries', 'entries.id = entryitems.entry_id')
				->where('entryitems.ledger_id', $ledger)
				->select('entryitems.*')
				->select('entries.*')
				->orderBy('entries.date', 'ASC')
				->get()
				->getResultArray();
		} else {
			if (empty($tdate))
				$tdate = date("Y-m-d");
			$res = $this->db->table('entryitems', 'entries')
				->join('entries', 'entries.id = entryitems.entry_id')
				->where('entries.date >=', $fdate)
				->where('entries.date <=', $tdate)
				->where('entryitems.ledger_id', $ledger)
				->select('entryitems.*')
				->select('entries.*')
				->orderBy('entries.date', 'ASC')
				->get()
				->getResultArray();
		}
		// echo $ledger.'<br>'.$fdate.'<br>'.$tdate.'<br>'.$this->db->getLastQuery().'<br>'; 
		// echo '<pre>'; print_r($res);//die;
		//Opening Balance
		// $op_bal = $this->db->table('ledgers')->where('id', $ledger)->get()->getRowArray();
		// $op_bal = $op_bal['op_balance'];
		$ac_id = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $ledger)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
		if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
			$op_bal -= $op_balance['cr_amount'];
		} else {
			$op_bal = $op_balance['dr_amount'];
		}
		if (!empty($fdate)) {
			$date = explode('-', $fdate);
			$m = $date[1];
			$de = $date[2];
			$y = $date[0];
			$ydate = date('Y-m-d', mktime(0, 0, 0, $m, ($de - 1), $y));

			$ops = $this->db->table('entryitems', 'entries')
				->join('entries', 'entries.id = entryitems.entry_id')
				->where('entries.date <=', $ydate)
				->where('entryitems.ledger_id', $ledger)
				->select('entryitems.*')
				->select('entries.*')
				->get()
				->getResultArray();

			foreach ($ops as $rd) {
				if ($rd['dc'] == 'D')
					$op_bal = $op_bal + $rd['amount'];
				else
					$op_bal = $op_bal - $rd['amount'];
			}
		}
		// echo $op_bal;
		// die;
		$data['op_bal'] = $op_bal;
		$i = 0;
		$datas = array();
		foreach ($res as $row) {
			// Ledger Name
			$getentry = $this->db->table('entryitems')->where('entry_id', $row['entry_id'])->get()->getResultArray();
			if ($getentry[0]['dc'] == 'D')
				$debit_name = $this->db->table('ledgers')->where('id', $getentry[0]['ledger_id'])->get()->getRowArray();
			else
				$debit_name = $this->db->table('ledgers')->where('id', $getentry[1]['ledger_id'])->get()->getRowArray();

			if ($getentry[0]['dc'] == 'C')
				$credit_name = $this->db->table('ledgers')->where('id', $getentry[0]['ledger_id'])->get()->getRowArray();
			else
				$credit_name = $this->db->table('ledgers')->where('id', $getentry[1]['ledger_id'])->get()->getRowArray();

			// $ledger = $debit_name['name'] . ' / Cr ' . $credit_name['name'];
			$ledger = $row['narration'];
			// if($row['type'] == 1) $table = 'ubayam';
			// else if($row['type'] == 2) $table = 'donation';
			// else if($row['type'] == 3) $table = 'archanai_booking';
			// else if($row['type'] == 4) $table = 'donation_product';
			// else if($row['type'] == 5) $table = 'stock_inward';
			// else $table = 'stock_outward';

			/* //Invoice Number Get
			 $inv_details = $this->db->table($table)->where('id', $row['inv_id'])->get()->getRowArray();
			 if($inv_details['invoice_no'] != '') $inv_no = $inv_details['invoice_no'];
			 else if($inv_details['ref_no'] != '') $inv_no = $inv_details['ref_no'];
			 else $inv_no = '-'; */

			//Credit Amount
			if (!empty($row['amount']))
				$amount = $row['amount'];
			else
				$amount = 0;
			if ($row['dc'] == 'D') {
				//$debit = 'Dr '.str_replace('-0', '0', number_format($amount, '2','.',','));
				$debit = str_replace('-0', '0', number_format($amount, '2', '.', ','));
				$debit_amount = $amount;
			} else {
				$debit = '';
				$debit_amount = 0.00;
			}

			if ($row['dc'] == 'C') {
				/* $credit = 'Cr '.str_replace('-0', '0', number_format($amount, '2','.',',')); */
				$credit = str_replace('-0', '0', number_format($amount, '2', '.', ','));
				$credit_amount = $amount;
			} else {
				$credit = '';
				$credit_amount = 0.00;
			}

			//Balance Amount
			if ($row['dc'] == 'C')
				$op_bal -= $amount;
			else
				$op_bal += $amount;

			//Report Data
			$datas[$i]['date'] = $row['date'];
			$datas[$i]['entry_code'] = $row['entry_code'];
			//$datas[$i]['inv_no'] = $inv_no;
			$datas[$i]['ledger'] = $ledger;
			$datas[$i]['debit'] = $debit;
			$datas[$i]['debit_amount'] = $debit_amount;
			$datas[$i]['credit'] = $credit;
			$datas[$i]['credit_amount'] = $credit_amount;
			$datas[$i]['balance'] = $op_bal;

			$i++;
		}
		$data['cl_bal'] = $op_bal;
		$data['data'] = $datas;
		if (empty($id)) {
			return $data;
			exit;
		} else {

			echo view('account_report/ledger_statement', $data);
		}
	}

	public function print_ledger_statement()
	{


		$ledger_id = $_POST['ledger'];
		$fdate = $_POST['fdate'];
		$tdate = $_POST['tdate'];
		$led_res = $this->ledger_statement($ledger_id, $fdate, $tdate);
		$data = $led_res;
		$tmpid = $this->session->get('profile_id');
		$data['temp_details'] = $this->db->table('admin_profile')->where('id', $tmpid)->get()->getRowArray();
		echo view('account_report/ledger_statement', $data);
	}

	public function print_trial_balance()
	{
		if (!$this->model->permission_validate('trial_balance_accounts', 'print')) {
			return redirect()->to(base_url() . '/dashboard');}
		$ac_id = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		//print_r($_POST);
		if ($_POST['fdate'])
			$sdate = $_POST['fdate'];
		else
			$sdate = date("Y-m-01");
		if ($_POST['tdate'])
			$tdate = $_POST['tdate'];
		else
			$tdate = date("Y-m-d");
		$query = $this->db->query('select * from `groups` where parent_id = "" or parent_id is NULL or parent_id = 0 order by id asc');
		//$query = $this->db->query('select * from `groups` where parent_id = "" or parent_id is NULL order by id asc');
		$parentgroup = $query->getResultArray();
		$datas = array();
		foreach ($parentgroup as $row) {
			$childgroup = $this->db->table('groups')->where('parent_id', $row['id'])->get()->getResultArray();
			if (!empty($childgroup)) {
				foreach ($childgroup as $crow) {
					$res = $this->db->table('ledgers')->where('group_id', $crow['id'])->get()->getResultArray();
					foreach ($res as $dd) {
						$id = $dd['id'];
						$ledgername = get_ledger_name_only($id);
						$ledgercode = get_ledger_code_only($id);
						$debitamt = 0;
						$creditamt = 0;
						$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
						if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
							$op_balance_amt = $op_balance['cr_amount'];
						} else {
							$op_balance_amt = $op_balance['dr_amount'];
						}
						$d_sql = "select sum(entryitems.amount) as amount 
                                            from entryitems 
                                            inner join entries on entries.id = entryitems. entry_id
                                            where entryitems.dc = 'D' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
						$c_sql = "select sum(entryitems.amount) as amount 
													from entryitems 
													inner join entries on entries.id = entryitems. entry_id
													where entryitems.dc = 'C' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
						$d_amt = $this->db->query($d_sql)->getRowArray();
						$c_amt = $this->db->query($c_sql)->getRowArray();
						$debitamt = $d_amt['amount'];
						$creditamt = $c_amt['amount'];
						$clbal = ($op_balance_amt + $debitamt) - $creditamt;
						if (!empty($debitamt) || !empty($creditamt)) {
							$datas[] = '<tr>
										<td>' . $ledgercode . '</td>
										<td>' . $ledgername . '</td>
										<td align="right">' . number_format($debitamt, '2', '.', '') . '</td>
										<td align="right">' . number_format($creditamt, '2', '.', '') . '</td>
									</tr>';
						}
						$totalopb += $op_balance_amt;
						$totaldeb += $debitamt;
						$totalcre += $creditamt;
						$totalclb += $clbal;
					}
					$cgroup = $this->db->table('groups')->where('parent_id', $crow['id'])->get()->getResultArray();
					foreach ($cgroup as $ccg) {
						$cgchild = $this->db->table('ledgers')->where('group_id', $ccg['id'])->get()->getResultArray();

						foreach ($cgchild as $dd) {
							$id = $dd['id'];
							$ledgername = get_ledger_name_only($id);
							$ledgercode = get_ledger_code_only($id);
							$debitamt = 0;
							$creditamt = 0;
							$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
							if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
								$op_balance_amt = $op_balance['cr_amount'];
							} else {
								$op_balance_amt = $op_balance['dr_amount'];
							}
							$d_sql = "select sum(entryitems.amount) as amount 
                                                    from entryitems 
                                                    inner join entries on entries.id = entryitems. entry_id
                                                    where entryitems.dc = 'D' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
							$c_sql = "select sum(entryitems.amount) as amount 
                                                    from entryitems 
                                                    inner join entries on entries.id = entryitems. entry_id
                                                    where entryitems.dc = 'C' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
							$d_amt = $this->db->query($d_sql)->getRowArray();
							$c_amt = $this->db->query($c_sql)->getRowArray();
							$debitamt = $d_amt['amount'];
							$creditamt = $c_amt['amount'];
							$clbal = ($op_balance_amt + $debitamt) - $creditamt;
							if (!empty($debitamt) || !empty($creditamt)) {
								$datas[] = '<tr>
                                            <td>' . $ledgercode . '</td>
                                            <td>' . $ledgername . '</td>
                                            <td align="right">' . number_format($debitamt, '2', '.', '') . '</td>
                                            <td align="right">' . number_format($creditamt, '2', '.', '') . '</td>
                                        </tr>';
							}
							$totalopb += $op_balance_amt;
							$totaldeb += $debitamt;
							$totalcre += $creditamt;
							$totalclb += $clbal;
						}
					}
				}
			}
			$res = $this->db->table('ledgers')->where('group_id', $row['id'])->get()->getResultArray();
			if (count($res) > 0) {
				foreach ($res as $dd) {
					$id = $dd['id'];
					$ledgername = get_ledger_name_only($id);
					$ledgercode = get_ledger_code_only($id);
					$debitamt = 0;
					$creditamt = 0;
					$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
					if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
						$op_balance_amt = $op_balance['cr_amount'];
					} else {
						$op_balance_amt = $op_balance['dr_amount'];
					}
					$d_sql = "select sum(entryitems.amount) as amount 
										from entryitems 
										inner join entries on entries.id = entryitems. entry_id
										where entryitems.dc = 'D' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
					$c_sql = "select sum(entryitems.amount) as amount 
												from entryitems 
												inner join entries on entries.id = entryitems. entry_id
												where entryitems.dc = 'C' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
					$d_amt = $this->db->query($d_sql)->getRowArray();
					$c_amt = $this->db->query($c_sql)->getRowArray();
					$debitamt = $d_amt['amount'];
					$creditamt = $c_amt['amount'];
					$clbal = ($op_balance_amt + $debitamt) - $creditamt;
					if (!empty($debitamt) || !empty($creditamt)) {
						$datas[] = '<tr>
									<td>' . $ledgercode . '</td>
									<td>' . $ledgername . '</td>
									<td align="right">' . number_format($debitamt, '2', '.', '') . '</td>
									<td align="right">' . number_format($creditamt, '2', '.', '') . '</td>
								</tr>';
					}
					$totalopb += $op_balance_amt;
					$totaldeb += $debitamt;
					$totalcre += $creditamt;
					$totalclb += $clbal;
				}
			}
			//print_r($res);
		}//die;

		$datas[] = '<tr style="color: black; border-top:1px solid black;">
					<td colspan="2"><b>Total</b></td>
					<td align="right" style="border-bottom:4px double black;">' . number_format($totaldeb, '2', '.', '') . '</td>
					<td align="right" style="border-bottom:4px double black;">' . number_format($totalcre, '2', '.', '') . '</td>
					</tr>';

		$data['list'] = $datas;
		echo view('account_report/print_trial_balance', $data);
	}



	public function print_trial_balance11()
	{
		if (!$this->model->permission_validate('trial_balance_accounts', 'print')) {
			return redirect()->to(base_url() . '/dashboard');}


		$data['permission'] = $this->model->get_permission('trial_balance_accounts');
		$ac_id = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		if ($_POST['fdate'])
			$sdate = $_POST['fdate'];
		else
			$sdate = date("Y-m-01");
		if ($_POST['tdate'])
			$tdate = $_POST['tdate'];
		else
			$tdate = date("Y-m-d");
		//echo $sdate; echo $tdate;die;
		$query = $this->db->query('select * from `groups` where parent_id = "" or parent_id is NULL or parent_id = 0 order by id asc');
		//$query = $this->db->table('groups')->where('parent_id is NULL')->get()->getResultArray();
		$parentgroup = $query->getResultArray();
		//echo '<pre>';
		//print_r($data);die;
		$datas = array();
		foreach ($parentgroup as $row) {
			//print_r($row['id']);
			/*$datas[] = '<tr>
										<td>'.$row['name'].'</td>
									 <td>Group</td>
									 <td>-</td>
									 <td>-</td>
									 <td>-</td>
									 <td>-</td>
								</tr>';*/
			$presult = $this->db->table('ledgers')->where('group_id', $row['id'])->get()->getResultArray();
			if (!empty($presult)) {
				foreach ($presult as $dd) {
					$id = $dd['id'];
					$ledgername = get_ledger_name_only($id);
					$ledgercode = get_ledger_code_only($id);
					$debitamt = 0;
					$creditamt = 0;
					$op_bal = 0;
					$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
					if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
						$op_balance_amt = $op_balance['cr_amount'];
					} else {
						$op_balance_amt = $op_balance['dr_amount'];
					}
					$op_bal = $op_balance_amt;
					$d_sql = "select sum(entryitems.amount) as amount 
                                            from entryitems 
                                            inner join entries on entries.id = entryitems. entry_id
                                            where entryitems.dc = 'D' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
					$c_sql = "select sum(entryitems.amount) as amount 
                                            from entryitems 
                                            inner join entries on entries.id = entryitems. entry_id
                                            where entryitems.dc = 'C' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
					$d_amt = $this->db->query($d_sql)->getRowArray();
					$c_amt = $this->db->query($c_sql)->getRowArray();
					$debitamt = $d_amt['amount'];
					$creditamt = $c_amt['amount'];
					$clbal = ($op_bal + $debitamt) - $creditamt;
					if (!empty($debitamt) || !empty($creditamt)) {
						$datas[] = '<tr>
                                    <td>' . $ledgercode . '</td>
                                    <td><a href="#" style="" id="' . $dd['id'] . '" onclick="ledger_report(' . $dd['id'] . ')">' . $ledgername . '</a>
                                    </td>
                                    <td align="right">' . number_format($debitamt, '2', '.', ',') . '</td>
                                    <td align="right">' . number_format($creditamt, '2', '.', ',') . '</td>
                                </tr>';
					}
					$totalopb += $op_balance_amt;
					$totaldeb += $debitamt;
					$totalcre += $creditamt;
					$totalclb += $clbal;
				}
			}
			$childgroup = $this->db->table('groups')->where('parent_id', $row['id'])->get()->getResultArray();
			if (!empty($childgroup)) {
				foreach ($childgroup as $crow) {
					/*$datas[] = '<tr>
								<td>&emsp;&emsp;'.$crow['name'].'</td>
								<td>Group</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
						</tr>';*/
					$res = $this->db->table('ledgers')->where('group_id', $crow['id'])->get()->getResultArray();
					foreach ($res as $dd) {
						$id = $dd['id'];
						$ledgername = get_ledger_name_only($id);
						$ledgercode = get_ledger_code_only($id);
						$debitamt = 0;
						$creditamt = 0;
						$op_bal = 0;
						$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
						if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
							$op_balance_amt = $op_balance['cr_amount'];
						} else {
							$op_balance_amt = $op_balance['dr_amount'];
						}
						$op_bal = $op_balance_amt;
						$d_sql = "select sum(entryitems.amount) as amount 
                                                    from entryitems 
                                                    inner join entries on entries.id = entryitems. entry_id
                                                    where entryitems.dc = 'D' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
						$c_sql = "select sum(entryitems.amount) as amount 
                                                    from entryitems 
                                                    inner join entries on entries.id = entryitems. entry_id
                                                    where entryitems.dc = 'C' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
						$d_amt = $this->db->query($d_sql)->getRowArray();
						$c_amt = $this->db->query($c_sql)->getRowArray();
						$debitamt = $d_amt['amount'];
						$creditamt = $c_amt['amount'];
						$clbal = ($op_bal + $debitamt) - $creditamt;
						if (!empty($debitamt) || !empty($creditamt)) {
							$datas[] = '<tr>
                                            <td>' . $ledgercode . '</td>
                                            <td><a href="#" style="" id="' . $dd['id'] . '" onclick="ledger_report(' . $dd['id'] . ')">' . $ledgername . '</a></td>
                                            <td align="right">' . number_format($debitamt, '2', '.', ',') . '</td>
                                            <td align="right">' . number_format($creditamt, '2', '.', ',') . '</td>
                                        </tr>';
						}
						$totalopb += $op_balance_amt;
						$totaldeb += $debitamt;
						$totalcre += $creditamt;
						$totalclb += $clbal;
					}
					$cgroup = $this->db->table('groups')->where('parent_id', $crow['id'])->get()->getResultArray();
					foreach ($cgroup as $ccg) {
						$cgchild = $this->db->table('ledgers')->where('group_id', $ccg['id'])->get()->getResultArray();
						/*$datas[] = '<tr>
								<td>&emsp;&emsp;'.$ccg['name'].'</td>
								<td>Group</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
						</tr>';*/
						foreach ($cgchild as $dd) {
							$id = $dd['id'];
							$ledgername = get_ledger_name_only($id);
							$ledgercode = get_ledger_code_only($id);
							$debitamt = 0;
							$creditamt = 0;
							$op_bal = 0;
							$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
							if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
								$op_balance_amt = $op_balance['cr_amount'];
							} else {
								$op_balance_amt = $op_balance['dr_amount'];
							}
							$op_bal = $op_balance_amt;
							$d_sql = "select sum(entryitems.amount) as amount 
                                                    from entryitems 
                                                    inner join entries on entries.id = entryitems. entry_id
                                                    where entryitems.dc = 'D' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
							$c_sql = "select sum(entryitems.amount) as amount 
                                                    from entryitems 
                                                    inner join entries on entries.id = entryitems. entry_id
                                                    where entryitems.dc = 'C' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
							$d_amt = $this->db->query($d_sql)->getRowArray();
							$c_amt = $this->db->query($c_sql)->getRowArray();
							$debitamt = $d_amt['amount'];
							$creditamt = $c_amt['amount'];
							$clbal = ($op_bal + $debitamt) - $creditamt;
							if (!empty($debitamt) || !empty($creditamt)) {
								$datas[] = '<tr>
                                            <td>' . $ledgercode . '</td>
                                            <td><a href="#" style="" id="' . $dd['id'] . '" onclick="ledger_report(' . $dd['id'] . ')">' . $ledgername . '</a></td>
                                            <td align="right">' . number_format($debitamt, '2', '.', ',') . '</td>
                                            <td align="right">' . number_format($creditamt, '2', '.', ',') . '</td>
                                        </tr>';
							}
							$totalopb += $op_balance_amt;
							$totaldeb += $debitamt;
							$totalcre += $creditamt;
							$totalclb += $clbal;
						}
					}
				}
			}
			//print_r($res);
		}//die;
		$datas[] = '<tfoot><tr style="color: black;">
					<td align="right" colspan="2"><b>Total</b></td>
					<td align="right">' . number_format($totaldeb, '2', '.', '') . '</td>
					<td align="right">' . number_format($totalcre, '2', '.', '') . '</td>
					</tr></tfoot>';
		$data['sdate'] = $sdate;
		$data['tdate'] = $tdate;
		$data['list'] = $datas;
		$data['check_financial_year'] = $this->db->table("ac_year")->where('status', 1)->get()->getResultArray();


		echo view('account_report/print_trial_balance', $data);
	}


	public function excel_trial_balance()
	{
		if (!$this->model->permission_validate('trial_balance_accounts', 'print')) {
			return redirect()->to(base_url() . '/dashboard');}
		$ac_id = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		//print_r($_POST);
		if ($_POST['fdate'])
			$sdate = $_POST['fdate'];
		else
			$sdate = date("Y-m-01");
		if ($_POST['tdate'])
			$tdate = $_POST['tdate'];
		else
			$tdate = date("Y-m-d");
		$query = $this->db->query('select * from `groups` where parent_id = "" or parent_id is NULL or parent_id = 0 order by id asc');
		//$query = $this->db->query('select * from `groups` where parent_id = "" or parent_id is NULL order by id asc');
		$parentgroup = $query->getResultArray();
		$data = array();
		foreach ($parentgroup as $row) {
			$childgroup = $this->db->table('groups')->where('parent_id', $row['id'])->get()->getResultArray();
			if (!empty($childgroup)) {
				foreach ($childgroup as $crow) {
					$res = $this->db->table('ledgers')->where('group_id', $crow['id'])->get()->getResultArray();
					foreach ($res as $dd) {
						$id = $dd['id'];
						$ledgername = get_ledger_name_only($id);
						$ledgercode = get_ledger_code_only($id);
						$debitamt = 0;
						$creditamt = 0;
						$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
						if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
							$op_balance_amt = $op_balance['cr_amount'];
						} else {
							$op_balance_amt = $op_balance['dr_amount'];
						}
						$d_sql = "select sum(entryitems.amount) as amount 
                                            from entryitems 
                                            inner join entries on entries.id = entryitems. entry_id
                                            where entryitems.dc = 'D' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
						$c_sql = "select sum(entryitems.amount) as amount 
													from entryitems 
													inner join entries on entries.id = entryitems. entry_id
													where entryitems.dc = 'C' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
						$d_amt = $this->db->query($d_sql)->getRowArray();
						$c_amt = $this->db->query($c_sql)->getRowArray();
						$debitamt = $d_amt['amount'];
						$creditamt = $c_amt['amount'];
						$clbal = ($op_balance_amt + $debitamt) - $creditamt;
						if (!empty($debitamt) || !empty($creditamt)) {
							$data[] = array(
								"ledgercode" => $ledgercode,
								"ledgername" => $ledgername,
								"debitamt" => number_format($debitamt, '2', '.', ''),
								"creditamt" => number_format($creditamt, '2', '.', '')
							);
						}
						$totalopb += $op_balance_amt;
						$totaldeb += $debitamt;
						$totalcre += $creditamt;
						$totalclb += $clbal;
					}
					$cgroup = $this->db->table('groups')->where('parent_id', $crow['id'])->get()->getResultArray();
					foreach ($cgroup as $ccg) {
						$cgchild = $this->db->table('ledgers')->where('group_id', $ccg['id'])->get()->getResultArray();

						foreach ($cgchild as $dd) {
							$id = $dd['id'];
							$ledgername = get_ledger_name_only($id);
							$ledgercode = get_ledger_code_only($id);
							$debitamt = 0;
							$creditamt = 0;
							$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
							if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
								$op_balance_amt = $op_balance['cr_amount'];
							} else {
								$op_balance_amt = $op_balance['dr_amount'];
							}
							$d_sql = "select sum(entryitems.amount) as amount 
                                                    from entryitems 
                                                    inner join entries on entries.id = entryitems. entry_id
                                                    where entryitems.dc = 'D' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
							$c_sql = "select sum(entryitems.amount) as amount 
                                                    from entryitems 
                                                    inner join entries on entries.id = entryitems. entry_id
                                                    where entryitems.dc = 'C' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
							$d_amt = $this->db->query($d_sql)->getRowArray();
							$c_amt = $this->db->query($c_sql)->getRowArray();
							$debitamt = $d_amt['amount'];
							$creditamt = $c_amt['amount'];
							$clbal = ($op_balance_amt + $debitamt) - $creditamt;
							if (!empty($debitamt) || !empty($creditamt)) {
								$data[] = array(
									"ledgercode" => $ledgercode,
									"ledgername" => $ledgername,
									"debitamt" => number_format($debitamt, '2', '.', ''),
									"creditamt" => number_format($creditamt, '2', '.', '')
								);
							}
							$totalopb += $op_balance_amt;
							$totaldeb += $debitamt;
							$totalcre += $creditamt;
							$totalclb += $clbal;
						}
					}
				}
			}
			$res = $this->db->table('ledgers')->where('group_id', $row['id'])->get()->getResultArray();
			if (count($res) > 0) {
				foreach ($res as $dd) {
					$id = $dd['id'];
					$ledgername = get_ledger_name_only($id);
					$ledgercode = get_ledger_code_only($id);
					$debitamt = 0;
					$creditamt = 0;
					$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
					if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
						$op_balance_amt = $op_balance['cr_amount'];
					} else {
						$op_balance_amt = $op_balance['dr_amount'];
					}
					$d_sql = "select sum(entryitems.amount) as amount 
										from entryitems 
										inner join entries on entries.id = entryitems. entry_id
										where entryitems.dc = 'D' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
					$c_sql = "select sum(entryitems.amount) as amount 
												from entryitems 
												inner join entries on entries.id = entryitems. entry_id
												where entryitems.dc = 'C' and entryitems.ledger_id = $id and entries.date >= '$sdate' and entries.date <= '$tdate'";
					$d_amt = $this->db->query($d_sql)->getRowArray();
					$c_amt = $this->db->query($c_sql)->getRowArray();
					$debitamt = $d_amt['amount'];
					$creditamt = $c_amt['amount'];
					$clbal = ($op_balance_amt + $debitamt) - $creditamt;
					if (!empty($debitamt) || !empty($creditamt)) {
						$data[] = array(
							"ledgercode" => $ledgercode,
							"ledgername" => $ledgername,
							"debitamt" => number_format($debitamt, '2', '.', ''),
							"creditamt" => number_format($creditamt, '2', '.', '')
						);
					}
					$totalopb += $op_balance_amt;
					$totaldeb += $debitamt;
					$totalcre += $creditamt;
					$totalclb += $clbal;
				}
			}
			//print_r($res);
		}//die;
		$datas = array(
			"Total" => "Total",
			"totaldeb" => number_format($totaldeb, '2', '.', ''),
			"totalcre" => number_format($totalcre, '2', '.', '')
		);
		$fileName = "trial_balance_" . $sdate . "_to_" . $tdate;
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
		$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
		);
		$sheet->getStyle('A2:D2')->getFont()->setBold(true);
		$sheet->getStyle("A1:D1")->applyFromArray($style);
		$sheet->mergeCells('A1:D1');
		$sheet->setCellValue('A1', "LEMBAGA WAKAF HINDU NEGERL PULAU PINANG PENANG STATE HINDU ENDOWMENTS BOARD");
		$sheet->setCellValue('A2', 'A/C No');
		$sheet->setCellValue('B2', 'Description');
		$sheet->setCellValue('C2', 'Debit');
		$sheet->setCellValue('D2', 'Credit');
		$rows = 3;
		foreach ($data as $val) {
			$sheet->setCellValue('A' . $rows, $val['ledgercode']);
			$sheet->setCellValue('B' . $rows, $val['ledgername']);
			$sheet->setCellValue('C' . $rows, $val['debitamt']);
			$sheet->setCellValue('D' . $rows, $val['creditamt']);
			$rows++;
		}
		$sheet->setCellValue('A' . $rows, "");
		$sheet->setCellValue('B' . $rows, "Total");
		$sheet->setCellValue('C' . $rows, $datas['totaldeb']);
		$sheet->setCellValue('D' . $rows, $datas['totalcre']);
		$writer = new Xlsx($spreadsheet);
		$writer->save('uploads/excel/' . $fileName . '.xlsx');
		return $this->response->download('uploads/excel/' . $fileName . '.xlsx', null)->setFileName($fileName . '.xlsx');

	}

	public function ledgers_name_list()
	{

		if (!$this->model->permission_validate('ledgers_name_list_accounts', 'print')) {
			return redirect()->to(base_url() . '/dashboard');}

		$id = 3;

		$data['results'] = $this->db->table("entries")->where('id', $id)->get()->getRowArray();

		echo view('account_report/ledgers_name_list', $data);

	}
	public function account_group_list()
	{

		if (!$this->model->permission_validate('account_group_list_accounts', 'print')) {
			return redirect()->to(base_url() . '/dashboard');}

		$id = 3;

		$data['results'] = $this->db->table("entries")->where('id', $id)->get()->getRowArray();

		echo view('account_report/account_group_list', $data);

	}
	public function manually_yearendclose()
	{
		$total_income_amount_bf_cr = 0;
		$total_income_amount_bf_dr = 0;
		$sdate = "2023-04-01";
		$tdate = "2024-03-31";
		$datas_income = array();
		$group = $this->db->table("groups")->get()->getResultArray();
		foreach ($group as $row) {
			$res = $this->db->table("ledgers")->where('group_id', $row['id'])->get()->getResultArray();
			if (count($res) > 0) {
				foreach ($res as $dd) {
					$led_id = $dd['id'];
					$op_bal = !empty($dd['op_balance']) ? $dd['op_balance'] : 0;
					$debitamt = $this->db->query("select sum(entryitems.amount) as amount 
								from entryitems 
								inner join entries on entries.id = entryitems. entry_id
								where entryitems.dc = 'D' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
					$creditamt = $this->db->query("select sum(entryitems.amount) as amount 
									from entryitems 
									inner join entries on entries.id = entryitems. entry_id
									where entryitems.dc = 'C' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
					$debitamt_tot = $debitamt['amount'];
					$creditamt_tot = $creditamt['amount'];
					if ($debitamt_tot > $creditamt_tot) {
						$datas_income[$led_id]['DR'][] = ($debitamt_tot + $op_bal) - $creditamt_tot;
					} else {
						$datas_income[$led_id]['CR'][] = ($creditamt_tot - $op_bal) - $debitamt_tot;
					}
				}
			}
		}
		//print_r($datas_income);
		foreach ($datas_income as $key => $row2) {
			if (!empty($row2["CR"])) {
				$ac_lb_data['dr_amount'] = "0.00";
				$ac_lb_data['cr_amount'] = abs($row2["CR"][0]);
			}
			if (!empty($row2["DR"])) {
				$ac_lb_data['dr_amount'] = abs($row2["DR"][0]);
				$ac_lb_data['cr_amount'] = "0.00";
			}
			$ac_lb_data['ac_year_id'] = 1;
			$ac_lb_data['ledger_id'] = $key;
			$this->db->table('ac_year_ledger_balance')->insert($ac_lb_data);
			//var_dump($ac_lb_data);
			//echo "<br>";
		}
	}

	public function trail_balance_new()
	{
		if (!$this->model->list_validate('trial_balance_accounts')) {
			return redirect()->to(base_url() . '/dashboard');
		}
		
		$data['permission'] = $this->model->get_permission('trial_balance_accounts');
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		
		// Handle date inputs
		$sdate = $this->request->getPost('fdate') ?: date("Y-m-01");
		$tdate = $this->request->getPost('tdate') ?: date("Y-m-d");
		
		$data['sdate'] = $sdate;
		$data['tdate'] = $tdate;
		$data['check_financial_year'] = $this->db->table("ac_year")->where('status', 1)->get()->getResultArray();
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account_report/trail_balance_new', $data);
		echo view('template/footer');
	}

	public function get_trial_balance_data()
	{
		if (!$this->model->list_validate('trial_balance_accounts')) {
			return $this->response->setJSON(['error' => 'Unauthorized']);
		}
		
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		if (!$ac_year) {
			return $this->response->setJSON(['error' => 'No active financial year']);
		}
		
		$sdate = $this->request->getPost('fdate') ?: date("Y-m-01");
		$tdate = $this->request->getPost('tdate') ?: date("Y-m-d");
		$fund_id = $this->request->getPost('fund_id');
		
		$fund_where = '';
		if (!empty($fund_id)) {
			$fund_where = " AND entries.fund_id = '$fund_id'";
		}
		
		// Get all groups with hierarchy
		$groups = $this->getGroupHierarchy();
		
		// Get all ledgers with their balances
		$ledgers = $this->getLedgersWithBalances($ac_year['id'], $tdate, $fund_where);
		
		// Build hierarchical data structure
		$trialBalanceData = $this->buildTrialBalanceTree($groups, $ledgers);
		
		// Calculate totals
		$totals = $this->calculateTotals($trialBalanceData);
		
		return $this->response->setJSON([
			'data' => $trialBalanceData,
			'totals' => $totals,
			'period' => [
				'from' => $sdate,
				'to' => $tdate
			]
		]);
	}

	private function getGroupHierarchy()
	{
		$query = $this->db->query("
			WITH RECURSIVE group_tree AS (
				SELECT id, parent_id, name, code, 0 as level
				FROM `groups` 
				WHERE parent_id = 0 OR parent_id IS NULL OR parent_id = ''
				
				UNION ALL
				
				SELECT g.id, g.parent_id, g.name, g.code, gt.level + 1
				FROM `groups` g
				INNER JOIN group_tree gt ON g.parent_id = gt.id
			)
			SELECT * FROM group_tree ORDER BY code
		");
		
		return $query->getResultArray();
	}

	private function getLedgersWithBalances($ac_year_id, $tdate, $fund_where)
	{
		$query = $this->db->query("
			SELECT 
				l.id,
				l.group_id,
				l.name,
				l.code,
				COALESCE(alb.dr_amount, 0) - COALESCE(alb.cr_amount, 0) as opening_balance,
				COALESCE(d.debit, 0) as debit,
				COALESCE(c.credit, 0) as credit,
				(COALESCE(alb.dr_amount, 0) - COALESCE(alb.cr_amount, 0) + COALESCE(d.debit, 0) - COALESCE(c.credit, 0)) as closing_balance
			FROM ledgers l
			LEFT JOIN ac_year_ledger_balance alb ON l.id = alb.ledger_id AND alb.ac_year_id = ?
			LEFT JOIN (
				SELECT ei.ledger_id, SUM(ei.amount) as debit
				FROM entryitems ei
				INNER JOIN entries e ON e.id = ei.entry_id
				WHERE ei.dc = 'D' AND e.date <= ? $fund_where
				GROUP BY ei.ledger_id
			) d ON l.id = d.ledger_id
			LEFT JOIN (
				SELECT ei.ledger_id, SUM(ei.amount) as credit
				FROM entryitems ei
				INNER JOIN entries e ON e.id = ei.entry_id
				WHERE ei.dc = 'C' AND e.date <= ? $fund_where
				GROUP BY ei.ledger_id
			) c ON l.id = c.ledger_id
			WHERE (COALESCE(alb.dr_amount, 0) - COALESCE(alb.cr_amount, 0) + COALESCE(d.debit, 0) - COALESCE(c.credit, 0)) != 0
			ORDER BY l.left_code, l.right_code
		", [$ac_year_id, $tdate, $tdate]);
		
		return $query->getResultArray();
	}

	private function buildTrialBalanceTree($groups, $ledgers)
	{
		$tree = [];
		$groupMap = [];
		
		// Create group map
		foreach ($groups as $group) {
			$group['ledgers'] = [];
			$group['children'] = [];
			$group['total_debit'] = 0;
			$group['total_credit'] = 0;
			$groupMap[$group['id']] = $group;
		}
		
		// Assign ledgers to groups
		foreach ($ledgers as $ledger) {
			if (isset($groupMap[$ledger['group_id']])) {
				$debit = $ledger['closing_balance'] > 0 ? $ledger['closing_balance'] : 0;
				$credit = $ledger['closing_balance'] < 0 ? abs($ledger['closing_balance']) : 0;
				
				$ledger['debit'] = $debit;
				$ledger['credit'] = $credit;
				
				$groupMap[$ledger['group_id']]['ledgers'][] = $ledger;
				
				// Update group totals
				$this->updateParentTotals($groupMap, $ledger['group_id'], $debit, $credit);
			}
		}
		
		// Build tree structure
		foreach ($groupMap as $id => $group) {
			if (empty($group['parent_id']) || $group['parent_id'] == '0') {
				$tree[] = &$groupMap[$id];
			} else if (isset($groupMap[$group['parent_id']])) {
				$groupMap[$group['parent_id']]['children'][] = &$groupMap[$id];
			}
		}
		
		return $tree;
	}

	private function updateParentTotals(&$groupMap, $groupId, $debit, $credit)
	{
		if (!isset($groupMap[$groupId])) return;
		
		$groupMap[$groupId]['total_debit'] += $debit;
		$groupMap[$groupId]['total_credit'] += $credit;
		
		if (!empty($groupMap[$groupId]['parent_id']) && $groupMap[$groupId]['parent_id'] != '0') {
			$this->updateParentTotals($groupMap, $groupMap[$groupId]['parent_id'], $debit, $credit);
		}
	}

	private function calculateTotals($tree)
	{
		$totals = ['debit' => 0, 'credit' => 0];
		
		foreach ($tree as $group) {
			$totals['debit'] += $group['total_debit'];
			$totals['credit'] += $group['total_credit'];
		}
		
		return $totals;
	}
	public function print_trial_balance_new()
	{
		if (!$this->model->list_validate('trial_balance_accounts')) {
			return redirect()->to(base_url() . '/dashboard');
		}
		
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		if (!$ac_year) {
			echo "No active financial year found";
			return;
		}
		
		$sdate = $this->request->getPost('fdate') ?: date("Y-m-01");
		$tdate = $this->request->getPost('tdate') ?: date("Y-m-d");
		$fund_id = $this->request->getPost('fund_id');
		
		$fund_where = '';
		if (!empty($fund_id)) {
			$fund_where = " AND entries.fund_id = '$fund_id'";
		}
		
		// Get all groups with hierarchy
		$groups = $this->getGroupHierarchy();
		
		// Get all ledgers with their balances
		$ledgers = $this->getLedgersWithBalances($ac_year['id'], $tdate, $fund_where);
		
		// Build hierarchical data structure
		$trialBalanceData = $this->buildTrialBalanceTree($groups, $ledgers);
		
		// Calculate totals
		$totals = $this->calculateTotals($trialBalanceData);
		
		// Prepare data for view
		$data = [
			'company_name' => $_SESSION['site_title'] ?? 'Company Name',
			'from_date' => date('d-m-Y', strtotime($sdate)),
			'to_date' => date('d-m-Y', strtotime($tdate)),
			'trial_balance_data' => $trialBalanceData,
			'totals' => $totals,
			'logo' => $_SESSION['logo_img'] ?? ''
		];
		
		// Load print view
		echo view('account_report/trial_balance_print', $data);
	}

	public function pdf_trial_balance_new()
	{
		if (!$this->model->list_validate('trial_balance_accounts')) {
			return redirect()->to(base_url() . '/dashboard');
		}
		
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		if (!$ac_year) {
			echo "No active financial year found";
			return;
		}
		
		$sdate = $this->request->getPost('fdate') ?: date("Y-m-01");
		$tdate = $this->request->getPost('tdate') ?: date("Y-m-d");
		$fund_id = $this->request->getPost('fund_id');
		
		$fund_where = '';
		if (!empty($fund_id)) {
			$fund_where = " AND entries.fund_id = '$fund_id'";
		}
		
		// Get all groups with hierarchy
		$groups = $this->getGroupHierarchy();
		
		// Get all ledgers with their balances
		$ledgers = $this->getLedgersWithBalances($ac_year['id'], $tdate, $fund_where);
		
		// Build hierarchical data structure
		$trialBalanceData = $this->buildTrialBalanceTree($groups, $ledgers);
		
		// Calculate totals
		$totals = $this->calculateTotals($trialBalanceData);
		
		// Prepare data for view
		$data = [
			'company_name' => $_SESSION['site_title'] ?? 'Company Name',
			'from_date' => date('d-m-Y', strtotime($sdate)),
			'to_date' => date('d-m-Y', strtotime($tdate)),
			'trial_balance_data' => $trialBalanceData,
			'totals' => $totals
		];
		
		// Load TCPDF or DOMPDF library
		$html = view('account_report/trial_balance_pdf', $data);
		
		// Using DOMPDF example
		$dompdf = new \Dompdf\Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream("trial_balance_" . date('Y-m-d') . ".pdf", ["Attachment" => true]);
	}

	public function excel_trial_balance_new()
	{
		if (!$this->model->list_validate('trial_balance_accounts')) {
			return redirect()->to(base_url() . '/dashboard');
		}
		
		$ac_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		if (!$ac_year) {
			echo "No active financial year found";
			return;
		}
		
		$sdate = $this->request->getPost('fdate') ?: date("Y-m-01");
		$tdate = $this->request->getPost('tdate') ?: date("Y-m-d");
		$fund_id = $this->request->getPost('fund_id');
		
		$fund_where = '';
		if (!empty($fund_id)) {
			$fund_where = " AND entries.fund_id = '$fund_id'";
		}
		
		// Get all groups with hierarchy
		$groups = $this->getGroupHierarchy();
		
		// Get all ledgers with their balances
		$ledgers = $this->getLedgersWithBalances($ac_year['id'], $tdate, $fund_where);
		
		// Build hierarchical data structure
		$trialBalanceData = $this->buildTrialBalanceTree($groups, $ledgers);
		
		// Calculate totals
		$totals = $this->calculateTotals($trialBalanceData);
		
		// Create Excel using PhpSpreadsheet
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		// Set header
		$sheet->setCellValue('A1', $_SESSION['site_title'] ?? 'Company Name');
		$sheet->mergeCells('A1:D1');
		$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
		$sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
		
		$sheet->setCellValue('A2', 'TRIAL BALANCE');
		$sheet->mergeCells('A2:D2');
		$sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
		$sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
		
		$sheet->setCellValue('A3', 'From ' . date('d-m-Y', strtotime($sdate)) . ' To ' . date('d-m-Y', strtotime($tdate)));
		$sheet->mergeCells('A3:D3');
		$sheet->getStyle('A3')->getAlignment()->setHorizontal('center');
		
		// Column headers
		$sheet->setCellValue('A5', 'A/C No');
		$sheet->setCellValue('B5', 'Description');
		$sheet->setCellValue('C5', 'Debit');
		$sheet->setCellValue('D5', 'Credit');
		
		$sheet->getStyle('A5:D5')->getFont()->setBold(true);
		$sheet->getStyle('A5:D5')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFD3D3D3');
		
		// Add data
		$row = 6;
		$this->addGroupToExcel($sheet, $trialBalanceData, $row, 0);
		
		// Add totals
		$sheet->setCellValue('A' . $row, '');
		$sheet->setCellValue('B' . $row, 'TOTAL');
		$sheet->setCellValue('C' . $row, $totals['debit']);
		$sheet->setCellValue('D' . $row, $totals['credit']);
		$sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);
		$sheet->getStyle('C' . $row . ':D' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
		
		// Auto-size columns
		foreach(range('A','D') as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}
		
		// Create writer and output
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="trial_balance_' . date('Y-m-d') . '.xlsx"');
		header('Cache-Control: max-age=0');
		
		$writer->save('php://output');
	}

	private function addGroupToExcel($sheet, $groups, &$row, $level)
	{
		foreach ($groups as $group) {
			if (($group['total_debit'] > 0 || $group['total_credit'] > 0) || 
				count($group['ledgers']) > 0 || count($group['children']) > 0) {
				
				// Add group row
				$sheet->setCellValue('A' . $row, $group['code']);
				$sheet->setCellValue('B' . $row, str_repeat('  ', $level) . $group['name']);
				$sheet->setCellValue('C' . $row, $group['total_debit']);
				$sheet->setCellValue('D' . $row, $group['total_credit']);
				
				$sheet->getStyle('B' . $row)->getFont()->setBold(true);
				$sheet->getStyle('C' . $row . ':D' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
				
				$row++;
				
				// Add child groups
				if (!empty($group['children'])) {
					$this->addGroupToExcel($sheet, $group['children'], $row, $level + 1);
				}
				
				// Add ledgers
				foreach ($group['ledgers'] as $ledger) {
					$sheet->setCellValue('A' . $row, $ledger['code']);
					$sheet->setCellValue('B' . $row, str_repeat('  ', $level + 1) . $ledger['name']);
					$sheet->setCellValue('C' . $row, $ledger['debit']);
					$sheet->setCellValue('D' . $row, $ledger['credit']);
					
					$sheet->getStyle('C' . $row . ':D' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
					
					$row++;
				}
			}
		}
	}


	public function receipt_payment()
	{
		if (!$this->model->list_validate('receipt_payment_accounts')) {
			return redirect()->to(base_url() . '/dashboard');}

		$data['permission'] = $this->model->get_permission('receipt_payment_accounts');
		if ($_POST['sdate'])
			$sdate = $_POST['sdate'];
		else
			$sdate = date('Y-m-01');
		if ($_POST['edate'])
			$edate = $_POST['edate'];
		else
			$edate = date('Y-m-d');

		$receipts = $this->db->query("SELECT l.left_code,l.right_code,ledger_id, l.name as ledger_name, COALESCE(sum(amount), 0) as amount FROM entryitems ei left join entries e on e.id = ei.entry_id left join ledgers l on l.id = ei.ledger_id where e.entrytype_id = 1 and dc = 'C' and e.date >= '$sdate' and e.date <= '$edate' group by ledger_id ")->getResultArray();

		$payments = $this->db->query("SELECT l.left_code,l.right_code,ledger_id, l.name as ledger_name, COALESCE(sum(amount), 0) as amount FROM entryitems ei left join entries e on e.id = ei.entry_id left join ledgers l on l.id = ei.ledger_id where e.entrytype_id = 2 and dc = 'D' and e.date >= '$sdate' and e.date <= '$edate' group by ledger_id ")->getResultArray();
		$table = array();
		$data = array();
		$total_receipt = 0;
		$total_payment = 0;
		$type_id = !empty($_POST['type_id']) ? $_POST['type_id'] : 3;

		$table[] = '<tr>';
		if (count($receipts) > 0 && $type_id == 1) {
			$table[] .= '<td style="font-weight: bold;font-size: medium;height: 100%;padding: 0 !important;">
							<table style="width:100%;" border="1" class="table1">';
			foreach ($receipts as $rec_row) {
				$total_receipt = $total_receipt + $rec_row['amount'];
				$table[] .= '<tr>
												<td>[' . $rec_row['left_code'] . '-' . $rec_row['right_code'] . '] ' . $rec_row['ledger_name'] . '</td>
												<td align="right">' . $rec_row['amount'] . '</td>
											</tr>';
			}
			$table[] .= '</table></td>';
		} else if (count($payments) > 0 && $type_id == 2) {
			$table[] .= '<td style="font-weight: bold;font-size: medium;padding: 0 !important;vertical-align: top;">
							<table style="width:100%;" border="1" class="table1">';
			foreach ($payments as $pay_row) {
				$total_payment = $total_payment + $pay_row['amount'];
				$table[] .= '<tr>
												<td>[' . $pay_row['left_code'] . '-' . $pay_row['right_code'] . '] ' . $pay_row['ledger_name'] . '</td>
												<td align="right">' . $pay_row['amount'] . '</td>
											</tr>';
			}
			$table[] .= '</table></td>';
		} else if (count($receipts) > 0 && count($payments) > 0 && $type_id == 3) {
			$table[] .= '<td style="font-weight: bold;font-size: medium;padding: 0 !important;height: 100%;">
							<table style="width:100%;" border="1" class="table1">';
			foreach ($receipts as $rec_row) {
				$total_receipt = $total_receipt + $rec_row['amount'];
				$table[] .= '<tr>
												<td>[' . $rec_row['left_code'] . '-' . $rec_row['right_code'] . '] ' . $rec_row['ledger_name'] . '</td>
												<td align="right">' . $rec_row['amount'] . '</td>
											</tr>';
			}
			$table[] .= '</table></td>';
			$table[] .= '<td style="font-weight: bold;font-size: medium;padding: 0 !important;vertical-align: top;">
							<table style="width:100%;" border="1" class="table1">';
			foreach ($payments as $pay_row) {
				$total_payment = $total_payment + $pay_row['amount'];
				$table[] .= '<tr>
												<td>[' . $pay_row['left_code'] . '-' . $pay_row['right_code'] . '] ' . $pay_row['ledger_name'] . '</td>
												<td align="right">' . $pay_row['amount'] . '</td>
											</tr>';
			}
			$table[] .= '</table></td>';
		} else {
			$table[] .= '<td colspan="2">No Records Found</td>';
		}
		$table[] .= '</tr>';

		$data['sdate'] = $sdate;
		$data['edate'] = $edate;
		$data['type_id'] = $type_id;
		$data['total_receipt'] = $total_receipt;
		$data['total_payment'] = $total_payment;
		$data['table'] = $table;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/receipt_payment', $data);
		echo view('template/footer');
	}

	public function print_receipt_payment()
	{
		if (!$this->model->list_validate('receipt_payment_accounts')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data['permission'] = $this->model->get_permission('receipt_payment_accounts');
		if ($_POST['fdate'])
			$sdate = $_POST['fdate'];
		else
			$sdate = date('Y-m-01');
		if ($_POST['tdate'])
			$edate = $_POST['tdate'];
		else
			$edate = date('Y-m-d');

		$receipts = $this->db->query("SELECT l.left_code,l.right_code,ledger_id, l.name as ledger_name, COALESCE(sum(amount), 0) as amount FROM entryitems ei left join entries e on e.id = ei.entry_id left join ledgers l on l.id = ei.ledger_id where e.entrytype_id = 1 and dc = 'C' and e.date >= '$sdate' and e.date <= '$edate' group by ledger_id ")->getResultArray();

		$payments = $this->db->query("SELECT l.left_code,l.right_code,ledger_id, l.name as ledger_name, COALESCE(sum(amount), 0) as amount FROM entryitems ei left join entries e on e.id = ei.entry_id left join ledgers l on l.id = ei.ledger_id where e.entrytype_id = 2 and dc = 'D' and e.date >= '$sdate' and e.date <= '$edate' group by ledger_id ")->getResultArray();
		$table = array();
		$data = array();
		$total_receipt = 0;
		$total_payment = 0;
		$type_id = !empty($_POST['ptype_id']) ? $_POST['ptype_id'] : 3;

		$table[] = '<tr>';
		if (count($receipts) > 0 && $type_id == 1) {
			$table[] .= '<td style="font-weight: bold;font-size: medium;height: 100%;padding:0;">
							<table style="width:100%;" border="1" class="table1">';
			foreach ($receipts as $rec_row) {
				$total_receipt = $total_receipt + $rec_row['amount'];
				$table[] .= '<tr>
												<td>[' . $rec_row['left_code'] . '-' . $rec_row['right_code'] . '] ' . $rec_row['ledger_name'] . '</td>
												<td align="right">' . $rec_row['amount'] . '</td>
											</tr>';
			}
			$table[] .= '</table></td>';
		} else if (count($payments) > 0 && $type_id == 2) {
			$table[] .= '<td style="font-weight: bold;font-size: medium;padding:0;">
							<table style="width:100%;" border="1" class="table1">';
			foreach ($payments as $pay_row) {
				$total_payment = $total_payment + $pay_row['amount'];
				$table[] .= '<tr>
												<td>[' . $pay_row['left_code'] . '-' . $pay_row['right_code'] . '] ' . $pay_row['ledger_name'] . '</td>
												<td align="right">' . $pay_row['amount'] . '</td>
											</tr>';
			}
			$table[] .= '</table></td>';
		} else if (count($receipts) > 0 && count($payments) > 0 && $type_id == 3) {
			$table[] .= '<td style="font-weight: bold;font-size: medium;padding:0 !important;height: 100%;vertical-align: top;">
							<table style="width:100%;" border="1" class="table1">';
			foreach ($receipts as $rec_row) {
				$total_receipt = $total_receipt + $rec_row['amount'];
				$table[] .= '<tr>
												<td>[' . $rec_row['left_code'] . '-' . $rec_row['right_code'] . '] ' . $rec_row['ledger_name'] . '</td>
												<td align="right">' . $rec_row['amount'] . '</td>
											</tr>';
			}
			$table[] .= '</table></td>';
			$table[] .= '<td style="font-weight: bold;font-size: medium;vertical-align: top;padding:0;">
							<table style="width:100%;" border="1" class="table1">';
			foreach ($payments as $pay_row) {
				$total_payment = $total_payment + $pay_row['amount'];
				$table[] .= '<tr>
												<td>[' . $pay_row['left_code'] . '-' . $pay_row['right_code'] . '] ' . $pay_row['ledger_name'] . '</td>
												<td align="right">' . $pay_row['amount'] . '</td>
											</tr>';
			}
			$table[] .= '</table></td>';
		} else {
			$table[] .= '<td colspan="2">No Records Found</td>';
		}
		$table[] .= '</tr>';

		$data['sdate'] = $sdate;
		$data['edate'] = $edate;
		$data['type_id'] = $type_id;
		$data['total_receipt'] = $total_receipt;
		$data['total_payment'] = $total_payment;
		$data['table'] = $table;
		echo view('account/print_receipt_payment', $data);
	}

	public function view_account_year_closing()
	{
	    //get current year
        $acc_year_data = $this->db->table("ac_year")->where("status",1)->get()->getRowArray();
        if(!isset($acc_year_data["from_year_month"]))
        {
             echo "No active year is found";
             return;
        }
        $from_year = intval(explode("-",$acc_year_data["from_year_month"])[0]);
        $current_closed_acc_year = $from_year;
        
        if($current_closed_acc_year == 0)
        {
             echo "Invalid Year";
             return;
        }
        if($acc_year_data["mig_comp"] == 0) //incomplete
            $year = $current_closed_acc_year - 1;
        else
            $year = $current_closed_acc_year;
            
        $acccnt=$this->db->table("ac_year")->select("count(*) as cnt,database() as db")->where("from_year_month",$year."-01")->where("mig_comp",1)->get()->getRowArray();
		$cnt = intval($acccnt["cnt"]);
        
        $data['show_bt']= 1;//($cnt > 0 ?0:1);
        $data['fy_end'] = $year;
        echo view('template/header');
		echo view('template/sidebar');
		echo view("account_report/view_account_year_closing",$data);
		echo view('template/footer');
        return;
	}
    //account year closing
    public function account_year_closing()
    {
        
      //echo $this->rollbkbkreference("2024","2025");
      //return;
        //error_reporting(E_ALL);
        //check pa is valid(profilt & loss)
        $ledger_pa_data = $this->db->table("ledgers")->select("count(*) as cnt")->where("pa",1)->get()->getRowArray();
        $ledger_cnt = (isset($ledger_pa_data["cnt"])?intval($ledger_pa_data["cnt"]):0);
        
        if($ledger_cnt < 1)
        {
            //echo json_encode(["status"=>false,"errMsg"=>"First you create profit accumulation ledger otherwise didnt close the account"]);
             return ["data"=>"false","rollback"=>"-1","prev_year"=>$prev_year,"year"=>0,"err_msg"=>"First you create profit accumulation ledger otherwise didnt close the account"];
        }
            
        if($ledger_cnt > 1)
        {
            //echo json_encode(["status"=>false,"errMsg"=>"You have mulitple profit accumulation ledger so didnt close yout=r acccount"]);
            return ["data"=>"false","rollback"=>"-1","prev_year"=>$prev_year,"year"=>0,"err_msg"=>"You have mulitple profit accumulation ledger so didnt close yout=r acccount"];
        }
            
        //get current year
        $acc_year_data = $this->db->table("ac_year")->where("status",1)->get()->getRowArray();
        if(!isset($acc_year_data["from_year_month"]))
        {
            // echo json_encode(["status"=>false,"errMsg"=>"No active year is found"]);
             return ["data"=>"false","rollback"=>"-1","prev_year"=>$prev_year,"year"=>0,"err_msg"=>"No active year is found"];
        }
        //$from_year = intval(explode("-",$acc_year_data["from_year_month"])[0]);
        //$current_closed_acc_year = $from_year;
        
		echo json_encode($this->migrate_table());
        
    }
    /***************************************************************************************************/
    //created by vijayan 17/02
	public function migrate_table()
	{
		$err=false;	
		
		//print_r($_GET);
		
		$accdata=$this->db->table("ac_year")->where("status",1)->get()->getRowArray();
	
		$year = intval(explode("-",$accdata["from_year_month"])[0]);
		$prev_year = $year - 1;
		$pref_arr = [];
		//1=>insert account year
		if($_GET['data']['Setting']['step'] == 1)
		{
		    if($accdata["mig_comp"] == 0) //migration incomplete
    		{
    		    $yval = intval(explode("-",$accdata["from_year_month"])[0]);
    		    $had_ac_year = 1;
    		}
    		else //new migration
    		{
    		    $prev_year = $yval = intval(explode("-",$accdata["from_year_month"])[0]);
    		    $year = $prev_year + 1;
    		    $had_ac_year = 0;
    		}
    		
			if($yval==1970 || $yval==0)
				return ["data"=>"false","rollback"=>"-1","prev_year"=>$prev_year,"year"=>0,"err_msg"=>"Invalid year."];
		
				$year = $prev_year + 1;	
				//return ["data"=>"false","prev_year"=>$prev_year,"year"=>$year,"err_msg"=>""];
				$acccnt=$this->db->table("ac_year")->select("count(*) as cnt,database() as db")->where("from_year_month",$year."-01")->get()->getRowArray();
				$cnt = intval($acccnt["cnt"]);
				$cur_db = $acccnt["db"];
				//print_r($cnt);echo "yy";
			
				//rem
				if($had_ac_year > 0)
					return ["data"=>"success","prev_year"=>$prev_year,"year"=>$year,"err_msg"=>""];
					//return ["data"=>"false","rollback"=>"-1","prev_year"=>$prev_year,"year"=>$year,"err_msg"=>"Already migrated"];
				
				if(isset($_SESSION["cacheLable".$year]))
				$_SESSION["cacheLable".$year]=[];
			
				$data1=[
						'from_year_month',
						'to_year_month',
						'status',
						'user_id'
					];
					
				$this->db->table("ac_year")->where("status",1)->update(["status"=>0]);
				$user_id = 0;
				$pref_arr = []; //there is no second prefix so use dummy set amesukoperasi
				$data = [];
					/******* Add to account table *******/
					foreach($data1 as $iter)
					{
						if($iter=="from_year_month" || $iter == "to_year_month")
						{
							$data[$iter] = str_ireplace($prev_year,$year,$accdata[$iter]);
						}
						else if($iter == "status")
						    $data[$iter] = 1;
						else
							$data[$iter] = $user_id;
					}
					 $data["mig_comp"] = 0; //start
					 $a = $this->db->table("ac_year")->insert($data);
					 if(!$a)
					    $err = true;
					  else //no error check migration column
					  {
					        $ref_tab = "ledgers";
					        $qr = $this->db->query("SHOW COLUMNS FROM $ref_tab LIKE 'is_migrate'")->getRowArray();
					       	//echo "ty".$qr->num_rows();
        					if(!isset($qr["Field"])) 
                            {
        					    //add is_migrate to old table
        					    $this->db->query("Alter TABLE `$ref_tab` add `is_migrate` INT NOT NULL DEFAULT '0' COMMENT '0 => not migrate new year record, 1=> migrate new record';");
                            }
					  }

			}
	
			//return ["data"=>"false","prev_year"=>$prev_year,"year"=>$year,"err_msg"=>""];
			if(!isset($_GET['data']['Setting']['step']))
			{
				//rollback if 403 error
				return ["data"=>"false","prev_year"=>$prev_year,"year"=>$year,"err_msg"=>""];
			}
			
			$err_res=["data"=>"false","prev_year"=>$prev_year,"year"=>$year,"err_msg"=>"Migrate record failed. Rollback data."];
		
			//2=>ledger data (20 20 records)
			if($_GET['data']['Setting']['step'] == 2)
			{
				//$pref = $_GET['data']['Setting']['pref'];
				return $this->ledger_data($err,$prev_year,$year,$accdata); //it had tot cnt
			
			}
			
			//rollback all data
			if($err)
			{
				return $err_res;
			}
			return ["data"=>"success","prev_year"=>$prev_year,"year"=>$year,"pref_arr" =>$pref_arr];
	}
	
	public function setCacheLable($data,$year)
	{
		$_SESSION["cacheLable".$year] = $data;
	}
	public function getAllProfitLedgeridtest()
	{
	    	$res1 = [];
			$res2 = [];
		
				$year_grp = "groups";
				$year_ledger = "ledgers";
				$sql="select id,parent_id,code from `$year_grp`";	
				$data = $this->db->query($sql)->getResultArray();
				$grp_ids = [];
				foreach($data as $iter)
				{
					$grp_ids[intval($iter["id"])] = $iter;
					
				}
				//code  #ref
				$res4 = [5000];//[4000,5000,6000,8000,9000,10000];
				$res = [];
				//group all 4000,6000,8000,9000 datas
				foreach($grp_ids as $iter=>$iter2)
				{
				    if(in_array($iter2["code"],$res4))
				    {
				        $res[] = $iter;
				    }
					else if(in_array($iter2["parent_id"],$res))
					{
						$res[] = $iter;
					}
				}
				//get all ledger
				$sql1="select id from $year_ledger where group_id in(".implode(',',$res).")";
				$data1 = $this->db->query($sql1)->getResultArray();
				foreach($data1 as $iter)
				{
					$res1[] = $iter["id"];
					//$res2[] = $iter["id"];
				}
			
			sort($res1);
			$res1 = array_unique($res1);
			$res_arr = [
			"resAll"=>$res1
			];
			
			print_r($res_arr);
	}
	//get 3 & 4 ledgerids
	public function getAllProfitLedgerid($year,$prev_year)
	{
		if(!empty($_SESSION["cacheLable".$year]))
		{
			$res_arr = $_SESSION["cacheLable".$year];
		}
		else
		{
			//$pref_label = "main";
			$res1 = [];
			$res2 = [];
		
				$year_grp = "groups";
				$year_ledger = "ledgers";
				$sql="select id,parent_id,code from `$year_grp`";	
				$data = $this->db->query($sql)->getResultArray();
				$grp_ids = [];
				foreach($data as $iter)
				{
					$grp_ids[intval($iter["id"])] = $iter;
					
				}
				//code
				$res4 = [4000,5000,6000,8000,9000];
				$res = [];
				//group all 4000,6000,8000,9000 datas
				foreach($grp_ids as $iter=>$iter2)
				{
				    if(in_array($iter2["code"],$res4))
				    {
				        $res[] = $iter;
				    }
					else if(in_array($iter2["parent_id"],$res))
					{
						$res[] = $iter;
					}
				}
				//get all ledger
				$sql1="select id from $year_ledger where group_id in(".implode(',',$res).")";
				$data1 = $this->db->query($sql1)->getResultArray();
				foreach($data1 as $iter)
				{
					$res1[] = $iter["id"];
					//$res2[] = $iter["id"];
				}
			
			sort($res1);
			$res1 = array_unique($res1);
			$res_arr = [
			"resAll"=>$res1
			];
		
			$this->setCacheLable($res_arr,$year);
		}
		return $res_arr;
	}
	public function getcr($op_balance_dc,$op_bal,$ex_typ,$expences,&$cl_typ,&$cl_bal)
	{
	    $cl_typ="D";
		/* echo '$op_balance_dc=' . $op_balance_dc;
		echo '$op_bal=' . $op_bal;
		echo '$ex_typ=' . $ex_typ;
		echo '$expences=' . $expences; */
		if($op_balance_dc == $ex_typ){
			$cl_bal = abs($op_bal) + abs($expences);
			$cl_typ=$op_balance_dc;
		}else{
			$cl_bal = abs(abs($op_bal) - abs($expences));
			$cl_typ = abs($op_bal) >= abs($expences) ? $op_balance_dc : $ex_typ;
		}
		/* echo '$cl_typ=' . $cl_typ;
		echo '$cl_bal=' . $cl_bal; */
	}
	//20 20 records
	public function ledger_data(&$err,$prev_year,$year,$accdata)
	{
	    $this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
		$ldgidarr = $this->getAllProfitLedgerid($year,$prev_year);
		$ldgid = $ldgidarr["resAll"];
		//prev year from to date
		$accfrom_date = str_ireplace($year,$prev_year,$accdata["from_year_month"])."-01";
		$accto_date = str_ireplace($year,$prev_year,$accdata["to_year_month"])."-31";//$accdata["to_year_month"]."-31";
		
		if($accdata["mig_comp"] == 1) //already migration completed
		    return ["data"=>"success","prev_year"=>$prev_year,"year"=>$year,"ledger_empty"=>$ledger_empty,"tot_cnt"=>0];
		    
		//calculate profit & loss total
		$prtotid = $ldgidarr["resAll"];
		if($prev_year > 0 && $year > 0)
		{
				
			$tables=
			[
				"ledgers"
			];
			$ledger_empty=0;
			$ds = $this->db;
			
			$ref_tab="ledgers";
			$tot_cnt = $ds->query("SELECT count(*) as cnt from $ref_tab WHERE is_migrate = 0")->getRowArray();
			
			if(!isset($tot_cnt["cnt"])) //complete
			{
					$ledger_empty = 1;
					$this->db->table("ac_year")->where("status",1)->update(["mig_comp"=>1]);
					$this->db->table("ledgers")->update(["is_migrate"=>0]);
					return ["data"=>"success","prev_year"=>$prev_year,"year"=>$year,"ledger_empty"=>$ledger_empty,"tot_cnt"=>0];
			}
		    
			$ac_year_prev_data = $this->db->table("ac_year")->where("from_year_month",$prev_year."-01")->get()->getrowArray();
			$ac_year_data = $this->db->table("ac_year")->where("from_year_month",$year."-01")->get()->getrowArray();
			if(!isset($ac_year_prev_data["id"]) || !isset($ac_year_data["id"]))
				return ["data"=>"false","prev_year"=>$prev_year,"year"=>$year,"err_msg"=>"Migrate record failed. Rollback data.","tot_cnt"=>$tot_cnt];
			
			$ac_year_prev_id = intval($ac_year_prev_data["id"]);
			$ac_year_id = intval($ac_year_data["id"]);
			$tot_cnt = ((intval($tot_cnt["cnt"])-20)>0?intval($tot_cnt["cnt"]):0);
			foreach($tables as $iter1)
			{
				 $ref_tab=$iter1;
				 $new_tab="ac_year_ledger_balance";
				 $group_tab="groups";
				 $ref_entry_tab = "entryitems";
				
				$sql_data=$this->db->query(" SELECT * from $ref_tab WHERE is_migrate = 0 limit 0,20  ")->getResultArray();
				 
				foreach($sql_data as $iter)
				{
					if($iter["pa"] == 1) //ledger ACCUMULATED PROFIT/LOSS total  4000,6000,8000,9000 & all subgroup amount in that prefix
					{
					    if(empty($prtotid))
					    {
					        $this->db->query(" update $ref_tab set is_migrate = 1,op_balance = '0', op_balance_dc='C' where id = '".$iter["id"]."'");
					        continue;
					    }
					    
					    
						$sql_entry_item_data=$this->db->query("SELECT sum(if(dc='D', amount, 0)) as dr_total, sum(if(dc='C', amount, 0)) as cr_total,sum(if(dc='D', quantity, 0)) as dr_quantity,sum(if(dc='C', quantity, 0)) as cr_quantity FROM $ref_entry_tab t join entries as e on e.id=t.entry_id WHERE ledger_id in(".implode(',',$prtotid).") and date(`date`) >= '$accfrom_date' and date(`date`) <= '$accto_date'")->getRowArray();

						$sql_ac_year_prev = $this->db->table("ac_year_ledger_balance")->where("ac_year_id",$ac_year_prev_id)->where("ledger_id",$iter["id"])->get()->getRowArray();
						// print_r($sql_entry_item_data);
					    if(isset($sql_ac_year_prev["quantity"]))
					    {
    					    //get op balance =>quantity has only one column
    					    $qtty = $sql_ac_year_prev["quantity"];
    					    $ammt = $sql_ac_year_prev["dr_amount"] - abs($sql_ac_year_prev["cr_amount"]);
    					    $fund_id = $sql_ac_year_prev["fund_id"];
					    }
					    else
					    {
					        $qtty = 0;
					        $ammt = 0;
					        $fund_id = 0;
					    }
					    
					    $op_quantity = $qtty;
					    $op_quantity_dc = ($op_quantity >= 0 ? "D":"C");
					    $op_bal = $ammt;
					    $op_balance_dc = ($ammt >= 0 ? "D":"C");
						/* echo '<br>$op_bal='. $op_bal;
						echo '<br>$op_balance_dc='. $op_balance_dc; */
					}
					else
					{
					    //echo "SELECT sum(if(dc='D', amount, 0)) as dr_total, sum(if(dc='C', amount, 0)) as cr_total,sum(if(dc='D', quantity, 0)) as dr_quantity,sum(if(dc='C', quantity, 0)) as cr_quantity FROM $ref_entry_tab t join entries as e on e.id=t.entry_id WHERE ledger_id = ".$iter["id"]." and date('date') >= '$accfrom_date' and date('date') <= '$accto_date'";
						$sql_entry_item_data=$this->db->query("SELECT sum(if(dc='D', amount, 0)) as dr_total, sum(if(dc='C', amount, 0)) as cr_total,sum(if(dc='D', quantity, 0)) as dr_quantity,sum(if(dc='C', quantity, 0)) as cr_quantity FROM $ref_entry_tab t join entries as e on e.id=t.entry_id WHERE ledger_id = ".$iter["id"]." and e.date >= '$accfrom_date' and e.date <= '$accto_date' ")->getRowArray();
						$sql_ac_year_prev = $this->db->table("ac_year_ledger_balance")->where("ac_year_id",$ac_year_prev_id)->where("ledger_id",$iter["id"])->get()->getRowArray();
						// print_r($sql_ac_year_prev);
						if(isset($sql_ac_year_prev["quantity"]))
					    {
    					    //get op balance =>quantity has only one column
    					    $qtty = $sql_ac_year_prev["quantity"];
    					    $ammt = $sql_ac_year_prev["dr_amount"] - abs($sql_ac_year_prev["cr_amount"]);
    					    $fund_id = $sql_ac_year_prev["fund_id"];
					    }
					    else
					    {
					        $qtty = 0;
					        $ammt = 0;
					        $fund_id = 0;
					    }
					    
					    $op_quantity = $qtty;
					    $op_quantity_dc = ($op_quantity >= 0 ? "D":"C");
					    $op_bal = $ammt;
					    $op_balance_dc = ($ammt >= 0 ? "D":"C");
					}
					
					if(in_array($iter["id"],$ldgid)) //profit
					{
						$cl_typ=$op_balance_dc;
						$cl_bal=0;
						$cl_qty = 0;//intval($sql_entry_item_data["cr_quantity"]) - intval($sql_entry_item_data["dr_quantity"]);
					}
					else
					{
						
						$expences = floatval($sql_entry_item_data["dr_total"]) - floatval($sql_entry_item_data["cr_total"]);
					    $expences_qty = floatval($sql_entry_item_data["dr_quantity"]) - floatval($sql_entry_item_data["cr_quantity"]);
						$ex_typ = $expences_qty_typ = "D";
						$cl_typ = $cl_qty_typ = 'D';
						$cl_bal = $cl_qty = 0;
						if($expences < 0 ) //cr
						{
							$expences=abs($expences); 
							$ex_typ = "C";
						}
						if($cl_qty < 0) //cr
						{
						   $expences_qty=abs($expences_qty); 
						   $expences_qty_typ = "C"; 
						}
						$this->getcr($op_balance_dc,$op_bal,$ex_typ,$expences,$cl_typ,$cl_bal);
						//quantity
						$this->getcr($op_quantity_dc,$op_quantity,$expences_qty_typ,$expences_qty,$cl_qty_typ,$cl_qty);
						
					}
					 
					//unit_price
					$unit_price = 0;
					if($cl_bal!=0 && $cl_qty!=0)
					{
					    $unit_price = number_format(($cl_bal/$cl_qty),2);
					}
					//ac_year_ledger_balance
					$inp = [
						"ac_year_id",
						"ledger_id",
						"fund_id",
						"dr_amount",
						"cr_amount",
						"quantity",
						"unit_price",
						//"uom_id"
					];
					
					$inp_sql = " insert into $new_tab set ";
					foreach($inp as $iter2)
					{
						if($iter2 == "unit_price")
						{
						    
							$inp_sql .= $iter2 ."='" .$unit_price."',";
						}
						else if($iter2 == "ledger_id")
						{
							$inp_sql .= $iter2 ."='" .$iter["id"]."',";
						}
						else if($iter2 == "ac_year_id")
						{
							$inp_sql .= $iter2 ."='" .$ac_year_id."',";
						}
						else if($iter2 == "quantity")
						{
							$inp_sql .= $iter2 ."='" .$cl_qty."',";
						}
						else if($iter2 == "cr_amount")
						{
						    
							$inp_sql .= $iter2 ."='" .(($cl_typ =="C")?$cl_bal:0)."',";
						}
						else if($iter2 == "dr_amount")
						{
						    
							$inp_sql .= $iter2 ."='" .(($cl_typ =="D")?$cl_bal:0)."',";
						}
						else
						{
						   
							$inp_sql .= $iter2 ."='" .$fund_id."',"; 
						}
					}
					
					// if($iter["id"] == 131){ echo $inp_sql; die;}
					try
					{
						$inp_sql=rtrim($inp_sql,",");
						//avoid duplicate entry
						$chk_ex = $this->db->table($new_tab)->where("ac_year_id",$ac_year_id)->where("ledger_id",$iter["id"])->get()->getRowArray();
						if(!isset($chk_ex))
						{
    						$this->db->query($inp_sql);
    						$this->db->query(" update $ref_tab set is_migrate = 1,op_balance = '$cl_bal', op_balance_dc='$cl_typ' where id = '".$iter["id"]."'");
						}
					}
					catch(Exception $ex)
					{
						//print_r($ex);
						$err=true;
						break;
					}
			    }
			}
			
		}
		else
			return ["data"=>"false","prev_year"=>$prev_year,"year"=>$year,"err_msg"=>"Migrate record failed. Rollback data.","tot_cnt"=>$tot_cnt];
		
		
		if($tot_cnt == 0)
		{
		    $this->db->table("ac_year")->where("status",1)->update(["mig_comp"=>1]);
		    $this->db->table("ledgers")->update(["is_migrate"=>0]);
		}
		
		return ["data"=>"success","prev_year"=>$prev_year,"year"=>$year,"ledger_empty"=>$ledger_empty,"tot_cnt"=>$tot_cnt];
	}
	
	public function rollbk()
	{
	    return false;
	//	$this->autoRender = false; // Prevent loading a view
      //  $this->response->type('json'); // Set response type to JSON
		$prev_year=intval($_GET['data']['Setting']['prev_year']);
		$year=intval($_GET['data']['Setting']['year']);
		$step=4;//intval($_GET['data']['Setting']['step']); because it run more than one pref
		
		if($prev_year > 0 && $year > 0)
		{
		    $ac_id = $this->db->table("ac_year")->where("from_year_month",$year."-01")->get()->getRowArray();
		    
			//delete acc year data
			$this->db->table("ac_year")->where("from_year_month",$year."-01")->delete();
			
			//delete acc year data
			$this->db->table("ac_year_ledger_balance")->where("ac_year_id",$ac_id)->delete();
			
			$this->db->table("ledgers")->update(["is_migrate"=>0]);
			
			return json_encode(["data"=>"success","err_msg"=>"Rollback Sucessfully."]);
		}
		return json_encode(["data"=>"false","err_msg"=>"Invalid year."]);
	}
	
	public function rollbkbkreference($prev_year,$year)
	{
	    //return false;
		//$this->autoRender = false; // Prevent loading a view
       // $this->response->type('json'); // Set response type to JSON
		$step=4;//intval($_GET['data']['Setting']['step']); because it run more than one pref
	//	$ds = $this->Entry->getDataSource();
	//	$pref = $this->getPrefixLabel($prev_year);
		
		if($prev_year > 0 && $year > 0)
		{
			
		    $ac_data = $this->db->table("ac_year")->where("from_year_month",$year."-01")->get()->getRowArray();
		    $ac_id = intval($ac_data["id"]);
			//delete acc year data
			$this->db->table("ac_year")->where("from_year_month",$year."-01")->delete();
			$this->db->table("ac_year")->where("from_year_month",$prev_year."-01")->update(["status"=>1]);
			
			//set prev year as active
			//$this->db->table("ac_year")->where("from_year_month",$prev_year."-01")
			
			//delete acc year ledger data
			if($ac_id > 0)
			$this->db->table("ac_year_ledger_balance")->where("ac_year_id",$ac_id)->delete();
			
			$this->db->table("ledgers")->update(["is_migrate"=>0]);
			return json_encode(["data"=>"success","err_msg"=>"Rollback Sucessfully."]);
		}
		return json_encode(["data"=>"false","err_msg"=>"Invalid year."]);
	}

	public function profit_loss()
	{
		if (!$this->model->list_validate('profit_and_loss_accounts')) {
			return redirect()->to(base_url() . '/dashboard');
		}
		
		$data['permission'] = $this->model->get_permission('profit_and_loss_accounts');
		
		// Get request parameters
		$request = $this->request->getPost();
		$breakdown = $request['breakdown'] ?? 'daily';
		$fund_id = $request['fund_id'] ?? '';
		
		// Set date ranges based on breakdown type
		if ($breakdown == 'monthly') {
			$smonthdate = !empty($request['smonthdate']) ? $request['smonthdate'] . "-01" : date('Y-m-01');
			$emonthdate = !empty($request['emonthdate']) ? 
				date('Y-m-t', strtotime($request['emonthdate'] . "-01")) : 
				date('Y-m-t');
			$from_date = $smonthdate;
			$to_date = $emonthdate;
			$getMonthsInRange = $this->getMonthsInRange($smonthdate, $emonthdate);
		} else {
			$from_date = $request['sdate'] ?? date('Y-m-01');
			$to_date = $request['edate'] ?? date('Y-m-d');
			$getMonthsInRange = [];
		}
		
		// Build where clause for fund filtering
		$fund_where = !empty($fund_id) ? " AND e.fund_id = $fund_id" : "";
		
		// Initialize report data structure
		$reportData = [
			'revenue' => $this->getGroupData(4000, $from_date, $to_date, $fund_where, $breakdown, $getMonthsInRange, 'credit'),
			'direct_cost' => $this->getGroupData(5000, $from_date, $to_date, $fund_where, $breakdown, $getMonthsInRange, 'debit'),
			'incomes' => $this->getGroupData(8000, $from_date, $to_date, $fund_where, $breakdown, $getMonthsInRange, 'credit'),
			'expenses' => $this->getGroupData(6000, $from_date, $to_date, $fund_where, $breakdown, $getMonthsInRange, 'debit'),
			'taxation' => $this->getGroupData(9000, $from_date, $to_date, $fund_where, $breakdown, $getMonthsInRange, 'debit')
		];
		
		// Calculate totals
		$calculations = $this->calculateTotalsPL($reportData, $breakdown, $getMonthsInRange);
		
		// Prepare view data
		$data['reportData'] = $reportData;
		$data['calculations'] = $calculations;
		$data['breakdown'] = $breakdown;
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['fund_id'] = $fund_id;
		$data['funds'] = $this->db->table("funds")->get()->getResultArray();
		$data['getMonthsInRange'] = $getMonthsInRange;
		
		// Set date parameters for forms
		if ($breakdown == 'monthly') {
			$data['smonthdate'] = date('Y-m', strtotime($smonthdate));
			$data['emonthdate'] = date('Y-m', strtotime($emonthdate));
			$data['sdate'] = date('Y-m-01');
			$data['edate'] = date('Y-m-d');
		} else {
			$data['sdate'] = $from_date;
			$data['edate'] = $to_date;
			$data['smonthdate'] = date('Y-m');
			$data['emonthdate'] = date('Y-m');
		}
		
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/profitloss_new', $data);
		echo view('template/footer');
	}

	private function getGroupData($groupCode, $fromDate, $toDate, $fundWhere, $breakdown, $monthsRange, $type)
	{
		$group = $this->db->table("groups")->where('code', $groupCode)->get()->getRowArray();
		if (!$group) return null;
		
		$data = [
			'group' => $group,
			'ledgers' => [],
			'subgroups' => [],
			'totals' => []
		];
		
		// Get direct ledgers
		$data['ledgers'] = $this->getLedgerData($group['id'], $fromDate, $toDate, $fundWhere, $breakdown, $monthsRange, $type);
		
		// Get subgroups recursively
		$data['subgroups'] = $this->getSubgroupsRecursive($group['id'], $fromDate, $toDate, $fundWhere, $breakdown, $monthsRange, $type);
		
		// Calculate totals
		$data['totals'] = $this->calculateGroupTotals($data, $breakdown, $monthsRange);
		
		return $data;
	}

	private function getSubgroupsRecursive($parentId, $fromDate, $toDate, $fundWhere, $breakdown, $monthsRange, $type, $level = 1)
	{
		$subgroups = $this->db->table("groups")
			->where('parent_id', $parentId)
			->orderBy('code', 'ASC')
			->get()
			->getResultArray();
		
		$result = [];
		
		foreach ($subgroups as $subgroup) {
			$subgroupData = [
				'group' => $subgroup,
				'level' => $level,
				'ledgers' => $this->getLedgerData($subgroup['id'], $fromDate, $toDate, $fundWhere, $breakdown, $monthsRange, $type),
				'subgroups' => []
			];
			
			// Get nested subgroups
			if ($level < 3) { // Limit recursion depth
				$subgroupData['subgroups'] = $this->getSubgroupsRecursive(
					$subgroup['id'], $fromDate, $toDate, $fundWhere, $breakdown, $monthsRange, $type, $level + 1
				);
			}
			
			// Calculate subgroup totals
			$subgroupData['totals'] = $this->calculateGroupTotals($subgroupData, $breakdown, $monthsRange);
			
			$result[] = $subgroupData;
		}
		
		return $result;
	}

	private function getLedgerData($groupId, $fromDate, $toDate, $fundWhere, $breakdown, $monthsRange, $type)
	{
		$ledgers = $this->db->table("ledgers")
			->where('group_id', $groupId)
			->orderBy('left_code', 'ASC')
			->orderBy('right_code', 'ASC')
			->get()
			->getResultArray();
		
		$result = [];
		
		foreach ($ledgers as $ledger) {
			$ledgerData = [
				'ledger' => $ledger,
				'amounts' => []
			];
			
			if ($breakdown == 'monthly' && count($monthsRange) > 0) {
				foreach ($monthsRange as $month) {
					$startDate = $month['date'] . '-01';
					$endDate = date('Y-m-t', strtotime($startDate));
					$ledgerData['amounts'][$month['date']] = $this->getLedgerAmount(
						$ledger['id'], $startDate, $endDate, $fundWhere, $type
					);
				}
			}
			
			// Get total for the entire period
			$ledgerData['total'] = $this->getLedgerAmount($ledger['id'], $fromDate, $toDate, $fundWhere, $type);
			
			if ($ledgerData['total'] != 0) { // Only include ledgers with transactions
				$result[] = $ledgerData;
			}
		}
		
		return $result;
	}

	private function getLedgerAmount($ledgerId, $startDate, $endDate, $fundWhere, $type)
	{
		$query = "SELECT 
			COALESCE(SUM(IF(ei.dc = 'C', amount, 0)), 0) as cr_total,
			COALESCE(SUM(IF(ei.dc = 'D', amount, 0)), 0) as dr_total
			FROM entryitems ei
			LEFT JOIN entries e ON e.id = ei.entry_id
			WHERE e.date BETWEEN ? AND ?
			AND ei.ledger_id = ? $fundWhere";
		
		$result = $this->db->query($query, [$startDate, $endDate, $ledgerId])->getRowArray();
		
		if ($type == 'credit') {
			return $result['cr_total'] - $result['dr_total'];
		} else {
			return $result['dr_total'] - $result['cr_total'];
		}
	}

	private function calculateGroupTotals($groupData, $breakdown, $monthsRange)
	{
		$totals = [];
		
		if ($breakdown == 'monthly' && count($monthsRange) > 0) {
			foreach ($monthsRange as $month) {
				$totals[$month['date']] = 0;
				
				// Add ledger amounts
				foreach ($groupData['ledgers'] as $ledgerData) {
					if (isset($ledgerData['amounts'][$month['date']])) {
						$totals[$month['date']] += $ledgerData['amounts'][$month['date']];
					}
				}
				
				// Add subgroup amounts
				if (!empty($groupData['subgroups'])) {
					foreach ($groupData['subgroups'] as $subgroup) {
						if (isset($subgroup['totals'][$month['date']])) {
							$totals[$month['date']] += $subgroup['totals'][$month['date']];
						}
					}
				}
			}
		}
		
		// Calculate period total
		$totals['total'] = 0;
		foreach ($groupData['ledgers'] as $ledgerData) {
			$totals['total'] += $ledgerData['total'];
		}
		
		if (!empty($groupData['subgroups'])) {
			foreach ($groupData['subgroups'] as $subgroup) {
				$totals['total'] += $subgroup['totals']['total'];
			}
		}
		
		return $totals;
	}

	private function calculateTotalsPL($reportData, $breakdown, $monthsRange)
	{
		$calculations = [];
		
		if ($breakdown == 'monthly') {
			foreach ($monthsRange as $month) {
				$monthKey = $month['date'];
				
				$revenue = $reportData['revenue']['totals'][$monthKey] ?? 0;
				$directCost = $reportData['direct_cost']['totals'][$monthKey] ?? 0;
				$incomes = $reportData['incomes']['totals'][$monthKey] ?? 0;
				$expenses = $reportData['expenses']['totals'][$monthKey] ?? 0;
				$taxation = $reportData['taxation']['totals'][$monthKey] ?? 0;
				
				$calculations[$monthKey] = [
					'gross_profit' => $revenue - $directCost,
					'net_profit' => $revenue - $directCost + $incomes - $expenses,
					'final_profit' => $revenue - $directCost + $incomes - $expenses - $taxation
				];
			}
		}
		
		// Calculate totals
		$revenue = $reportData['revenue']['totals']['total'] ?? 0;
		$directCost = $reportData['direct_cost']['totals']['total'] ?? 0;
		$incomes = $reportData['incomes']['totals']['total'] ?? 0;
		$expenses = $reportData['expenses']['totals']['total'] ?? 0;
		$taxation = $reportData['taxation']['totals']['total'] ?? 0;
		
		$calculations['total'] = [
			'gross_profit' => $revenue - $directCost,
			'net_profit' => $revenue - $directCost + $incomes - $expenses,
			'final_profit' => $revenue - $directCost + $incomes - $expenses - $taxation
		];
		
		return $calculations;
	}

	private function getMonthsInRange($start, $end)
	{
		$start = new \DateTime($start);
		$end = new \DateTime($end);
		$interval = new \DateInterval('P1M');
		$period = new \DatePeriod($start, $interval, $end->modify('+1 day'));
		
		$months = [];
		foreach ($period as $date) {
			$months[] = ['date' => $date->format('Y-m')];
		}
		
		return $months;
	}

}