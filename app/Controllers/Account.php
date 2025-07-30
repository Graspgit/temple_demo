<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Account extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		helper("common");
		$this->model = new PermissionModel();
		if (($this->session->get('login')) == false && $this->session->get('role') != 1) {
			$data['dn_msg'] = 'Please Login';
			return redirect()->to('/login');
		}
	}

	public function index()
	{
		if (!$this->model->list_validate('ac_creation_accounts')) {
			return redirect()->to('/dashboard');
		}
		
		$data['permission'] = $this->model->get_permission('ac_creation_accounts');
		$data['add_group'] = $this->model->get_permission('group');
		$data['add_ledger'] = $this->model->get_permission('ledger');

		// Get financial year
		$financial_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		if ($financial_year) {
			$ac_year_id = $financial_year['id'];
			$sdate = $financial_year['from_year_month'] . "-01";
			$tdate = $financial_year['to_year_month'] . "-31";
			
			// Calculate opening balance difference
			$ac_start_balance = $this->db->query("SELECT COALESCE(sum(if(dr_amount != '', dr_amount, 0)), 0) as dr_total, COALESCE(sum(if(cr_amount != '', cr_amount, 0)), 0) as cr_total FROM `ac_year_ledger_balance` where ac_year_id = $ac_year_id")->getRowArray();
			$data['ac_op_diff'] = $ac_start_balance['dr_total'] - $ac_start_balance['cr_total'];
		} else {
			$data['ac_op_diff'] = 0;
		}
		
		// Prepare tree data for initial load (only top-level groups)
		$data['coa_tree_data'] = $this->getCoATreeData(true); // true = initial load only
		
		$data['check_financial_year'] = $this->db->table("ac_year")->where('status', 1)->get()->getResultArray();
		
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/index', $data);
		echo view('template/footer');
	}

	// New method to get COA data in tree structure
	private function getCoATreeData($initialLoad = false)
	{
		$financial_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		if (!$financial_year) return [];
		
		$ac_year_id = $financial_year['id'];
		$sdate = $financial_year['from_year_month'] . "-01";
		$tdate = $financial_year['to_year_month'] . "-31";
		
		// Get all groups
		$groups = $this->db->table("groups")->orderBy('code', 'asc')->get()->getResultArray();
		$this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		// Get all ledgers with their balances in one query
		$ledgers = $this->db->query("
			SELECT 
				l.*,
				COALESCE(aylb.dr_amount, 0) - COALESCE(aylb.cr_amount, 0) as op_balance,
				COALESCE(SUM(CASE WHEN ei.dc = 'D' THEN ei.amount ELSE 0 END), 0) - 
				COALESCE(SUM(CASE WHEN ei.dc = 'C' THEN ei.amount ELSE 0 END), 0) as period_balance
			FROM ledgers l
			LEFT JOIN ac_year_ledger_balance aylb ON l.id = aylb.ledger_id AND aylb.ac_year_id = ?
			LEFT JOIN entryitems ei ON l.id = ei.ledger_id
			LEFT JOIN entries e ON ei.entry_id = e.id AND e.date >= ? AND e.date <= ?
			GROUP BY l.id
			ORDER BY l.left_code, l.right_code
		", [$ac_year_id, $sdate, $tdate])->getResultArray();
		
		// Build tree structure
		$tree = $this->buildCoATree($groups, $ledgers, $initialLoad);
		
		return $tree;
	}

	// Build hierarchical tree structure
	private function buildCoATree($groups, $ledgers, $initialLoad = false)
	{
		$groupsById = [];
		$tree = [];
		
		// Index groups by ID
		foreach ($groups as $group) {
			$groupsById[$group['id']] = [
				'id' => $group['id'],
				'parent_id' => $group['parent_id'],
				'name' => $group['name'],
				'code' => $group['code'],
				'type' => 'group',
				'fixed' => $group['fixed'],
				'children' => []
			];
		}
		
		// Build group hierarchy
		foreach ($groupsById as $id => &$group) {
			if (empty($group['parent_id']) || $group['parent_id'] == '0') {
				$tree[] = &$group;
			} else {
				if (isset($groupsById[$group['parent_id']])) {
					$groupsById[$group['parent_id']]['children'][] = &$group;
				}
			}
		}
		
		// Add ledgers to their groups (only for non-initial load or for visible groups)
		if (!$initialLoad) {
			foreach ($ledgers as $ledger) {
				$cl_balance = $ledger['op_balance'] + $ledger['period_balance'];
				
				$ledgerNode = [
					'id' => $ledger['id'],
					'name' => $ledger['name'],
					'code' => $ledger['left_code'] . '/' . $ledger['right_code'],
					'type' => 'ledger',
					'op_balance' => $ledger['op_balance'],
					'cl_balance' => $cl_balance,
					'url' => base_url() . '/accountreport/ledger_statement/' . $ledger['id'],
					'show_op_bal' => $this->shouldShowOpBalance($ledger['group_id']),
					'children' => []
				];
				
				if (isset($groupsById[$ledger['group_id']])) {
					$groupsById[$ledger['group_id']]['children'][] = $ledgerNode;
				}
			}
		}
		
		return $tree;
	}

	// Check if opening balance button should be shown
	private function shouldShowOpBalance($groupId)
	{
		$group = $this->db->table("groups")->where('id', $groupId)->get()->getRowArray();
		if (!$group) return false;
		
		// Get root parent code
		$rootCode = $this->getRootGroupCode($group);
		$in_ex_group = array(4000, 5000, 6000, 8000, 9000);
		
		return !in_array($rootCode, $in_ex_group);
	}

	// Get root group code
	private function getRootGroupCode($group)
	{
		if (empty($group['parent_id']) || $group['parent_id'] == '0') {
			return $group['code'];
		}
		
		$parent = $this->db->table("groups")->where('id', $group['parent_id'])->get()->getRowArray();
		if ($parent) {
			return $this->getRootGroupCode($parent);
		}
		
		return $group['code'];
	}

	// New AJAX method to get COA data
	public function get_coa_data()
	{
		if (!$this->model->list_validate('ac_creation_accounts')) {
			return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
		}
		
		$data = $this->getCoATreeData(false); // false = load all data
		
		return $this->response->setJSON([
			'success' => true,
			'data' => $data
		]);
	}

	// New AJAX method to get specific node children
	public function get_node_children()
	{
		if (!$this->model->list_validate('ac_creation_accounts')) {
			return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
		}
		
		$nodeId = $this->request->getPost('node_id');
		$nodeType = $this->request->getPost('node_type');
		
		if ($nodeType === 'group') {
			$children = $this->getGroupChildren($nodeId);
		} else {
			$children = [];
		}
		
		return $this->response->setJSON([
			'success' => true,
			'children' => $children
		]);
	}

	// Get children of a specific group
	private function getGroupChildren($groupId)
	{
		$financial_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		if (!$financial_year) return [];
		
		$ac_year_id = $financial_year['id'];
		$sdate = $financial_year['from_year_month'] . "-01";
		$tdate = $financial_year['to_year_month'] . "-31";
		
		$children = [];
		
		// Get child groups
		$childGroups = $this->db->table("groups")
			->where('parent_id', $groupId)
			->orderBy('code', 'asc')
			->get()
			->getResultArray();
		
		foreach ($childGroups as $group) {
			$children[] = [
				'id' => $group['id'],
				'name' => $group['name'],
				'code' => $group['code'],
				'type' => 'group',
				'fixed' => $group['fixed'],
				'children' => [] // Will be loaded on demand
			];
		}
		
		// Get ledgers
		$ledgers = $this->db->query("
			SELECT 
				l.*,
				COALESCE(aylb.dr_amount, 0) - COALESCE(aylb.cr_amount, 0) as op_balance,
				COALESCE(SUM(CASE WHEN ei.dc = 'D' THEN ei.amount ELSE 0 END), 0) - 
				COALESCE(SUM(CASE WHEN ei.dc = 'C' THEN ei.amount ELSE 0 END), 0) as period_balance
			FROM ledgers l
			LEFT JOIN ac_year_ledger_balance aylb ON l.id = aylb.ledger_id AND aylb.ac_year_id = ?
			LEFT JOIN entryitems ei ON l.id = ei.ledger_id
			LEFT JOIN entries e ON ei.entry_id = e.id AND e.date >= ? AND e.date <= ?
			WHERE l.group_id = ?
			GROUP BY l.id
			ORDER BY l.left_code, l.right_code
		", [$ac_year_id, $sdate, $tdate, $groupId])->getResultArray();
		
		foreach ($ledgers as $ledger) {
			$cl_balance = $ledger['op_balance'] + $ledger['period_balance'];
			
			$children[] = [
				'id' => $ledger['id'],
				'name' => $ledger['name'],
				'code' => $ledger['left_code'] . '/' . $ledger['right_code'],
				'type' => 'ledger',
				'op_balance' => $ledger['op_balance'],
				'cl_balance' => $cl_balance,
				'url' => base_url() . '/accountreport/ledger_statement/' . $ledger['id'],
				'show_op_bal' => $this->shouldShowOpBalance($ledger['group_id']),
				'children' => []
			];
		}
		
		return $children;
	}
	public function index_old()
	{
		if (!$this->model->list_validate('ac_creation_accounts')) {
			return redirect()->to(base_url() . '/dashboard');}
		$data['permission'] = $this->model->get_permission('ac_creation_accounts');
		$data['add_group'] = $this->model->get_permission('group');
		$data['add_ledger'] = $this->model->get_permission('ledger');

		if (!empty($_POST['ledger']))
			$ledger_id = $_POST['ledger'];
		else
			$ledger_id = "";

		$financial_year = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$ac_year_id = $financial_year['id'];
		
		$ac_start_balance = $this->db->query("SELECT COALESCE(sum(if(dr_amount != '', dr_amount, 0)), 0) as dr_total, COALESCE(sum(if(cr_amount != '', cr_amount, 0)), 0) as cr_total FROM `ac_year_ledger_balance` where ac_year_id = $ac_year_id")->getRowArray();
		$data['ac_op_diff']  = $ac_start_balance['dr_total'] - $ac_start_balance['cr_total'];
		
		$group = $this->db->table("groups")->orderBy('code', 'asc')->get()->getResultArray();
		$ledger[] = '<option value="">--Select Ledger--</option>';
		foreach ($group as $row) {
			$res = $this->db->table("ledgers")->where('group_id', $row['id'])->orderBy('left_code', 'asc')->orderBy('right_code', 'asc')->get()->getResultArray();
			foreach ($res as $r) {
				$id = $r['id'];
				$ledgername = $r['left_code'] . '/' . $r['right_code'] . ' - ' . $r['name'];
				if ($ledger_id == $id)
					$selected = 'selected';
				else
					$selected = '';
				$ledger[] .= '<option ' . $selected . ' value="' . $id . '">' . $ledgername . '</option>';
			}
		}
		$data['ledger'] = $ledger;
		$data['group'] = $group;
		$ac_id = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$sdate = $ac_id['from_year_month'] . "-01";
		$tdate = $ac_id['to_year_month'] . "-31";
		//var_dump($sdate);
		//var_dump($tdate);
		//var_dump($ac_id);
		//exit;
		$datas = array();
		$group = $this->model->get_permission('group');
		if ($group['edit'] == 1 || $group['delete_p'] == 1)
			$group_p = 1;
		else
			$group_p = 0;
		if ($group['edit'] == 1)
			$group_e = 1;
		else
			$group_e = 0;
		if ($group['delete_p'] == 1)
			$group_d = 1;
		else
			$group_d = 0;
		$ledgerp = $this->model->get_permission('ledger');
		if ($ledgerp['edit'] == 1 || $ledgerp['delete_p'] == 1)
			$ledger_p = 1;
		else
			$ledger_p = 0;
		if ($ledgerp['edit'] == 1)
			$ledgere = 1;
		else
			$ledgere = 0;
		if ($ledgerp['delete_p'] == 1)
			$ledgerd = 1;
		else
			$ledgerd = 0;
		//Parent Group
		$datas = array();
		$parent = $this->db->query("select * from `groups` where parent_id is NULL or parent_id ='' or parent_id = 0 order by code asc")->getResultArray();
		foreach ($parent as $row) {
			$lid = $row['id'] . ',1';
			$nid = $row['id'] . '_1';
			$in_ex_group = array(4000,5000,6000,8000,9000);
			if (in_array($row['code'], $in_ex_group)) $op_dis = false;
			else $op_dis = true;
			//print_r($row);
			$datas[] = '<tr>
					   		<td><span id="name_' . $nid . '">(' . $row['code'] . ') ' . $row['name'] . '</span></td>
							<td>Group</td>
							<td>-</td>
							<td>-</td>';
			if ($group_p == 1) {
				$datas[] = '	<td>';
				if ($group_e == 1) {
					if(empty($row['fixed'])){
						$datas[] = '<a style="color: #fff;" href="' . base_url() . '/account/edit_group/' . $row['id'] . '">
                                            <button class="btn btn-primary  btn-rad"><i class="material-icons">&#xE3C9;</i></button>
                                        </a>';
					}

				}
				if($group_d == 1){	
					if(empty($row['fixed'])){
						$datas[] = '<a style="color: #fff;" href="#">	<button class="btn btn-danger btn-rad" onclick="confirm_modal('.$lid.')"><i class="material-icons">&#xE872;</i></button></a>';
					}
				}

				$datas[] = '	</td>';
			}
			$datas[] = '<tr>';
			$id = $row['id'];
			if (!empty($_POST['ledger'])) {
				$ledger_id = $_POST['ledger'];
				$res = $this->db->query("select * from ledgers where group_id = '" . $id . "' and id = '" . $ledger_id . "' order by left_code,right_code asc")->getResultArray();
			} else {
				$res = $this->db->query("select * from `ledgers` where group_id = '" . $id . "' order by left_code,right_code asc")->getResultArray();
			}
			//$res = $this->db->query("select * from ledgers where group_id = '".$id."' ")->getResultArray();
			if (count($res) > 0) {
				foreach ($res as $dd) {
					$lid = $dd['id'] . ',2';
					$nid = $dd['id'] . '_2';
					$led_id = $dd['id'];
					$ledgername = get_ledger_name($led_id);
					$debitamt = 0;
					$creditamt = 0;
					$debitamt = $this->db->query("select sum(entryitems.amount) as amount 
								from entryitems 
								inner join entries on entries.id = entryitems. entry_id
								where entryitems.dc = 'D' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
					$creditamt = $this->db->query("select sum(entryitems.amount) as amount 
								from entryitems 
								inner join entries on entries.id = entryitems. entry_id
								where entryitems.dc = 'C' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
					if ($debitamt['amount'] == '')
						$debitamt['amount'] = 0;
					if ($creditamt['amount'] == '')
						$creditamt['amount'] = 0;
					$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $dd['id'])->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
					$op_balance_amt = 0;
					if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
						$op_balance_amt -= $op_balance['cr_amount'];
					} else {
						$op_balance_amt += $op_balance['dr_amount'];
					}
					//echo $creditamt['amount'];
					//exit;
					$clbal = ($op_balance_amt - $creditamt['amount']) + $debitamt['amount'];
					
					if($op_balance_amt < 0){
						$op_balance_amt_amount = '(' . number_format(abs($op_balance_amt), "2", ".", ",") . ')';
					}else{
						$op_balance_amt_amount = number_format(abs($op_balance_amt), "2", ".", ",");
					}
					if($clbal < 0){
						$clbal_amount = '(' . number_format(abs($clbal), "2", ".", ",") . ')';
					}else{
						$clbal_amount = number_format(abs($clbal), "2", ".", ",");
					}
					//echo $clbal.'<br>'; 
					$datas[] = '<tr>
                                    <td><a style="margin-left: 5%;" href="' . base_url() . '/accountreport/ledger_statement/' . $dd['id'] . '" id="name_' . $nid . '">' . $ledgername . '</a></td>
                                    <td>Ledger</td>
                                    <td>' . $op_balance_amt_amount . '</td>
                                    <td>' . $clbal_amount . '</td>';
					if ($ledger_p == 1) {
						$datas[] = '	<td>';
						if ($ledgere == 1) {
							$action = '<a style="color: #fff;" href="' . base_url() . '/account/edit_ledger/' . $dd['id'] . '">
                                                <button class="btn btn-primary  btn-rad" style="color: #fff;"><i class="material-icons">&#xE3C9;</i></button>
                                            </a>';
							if($op_dis) $action .= '<a style="color: #fff;" href="' . base_url() . '/account/edit_opbal/' . $dd['id'] . '">
                                                <button class="btn btn-success  btn-rad"><i class="material-icons">attach_money</i></button>
                                            </a>';
							$datas[] = $action;
						}
						if ($ledgerd == 1) {
							$datas[] = '
                                        <a style="color: #fff;" href="#">
                                            <button class="btn btn-danger  btn-rad" onclick="confirm_modal(' . $lid . ')" style="color: #fff;"><i class="material-icons">&#xE872;</i></button>
                                        </a>';
						}
						$datas[] = '
                                    </td>';
					}
					$datas[] = '<tr>';
				}
			}
			// Child Group
			$cgroup = $this->db->query("select * from `groups` where parent_id = $id order by code asc")->getResultArray();
			foreach ($cgroup as $crow) {
				$lid = $crow['id'] . ',1';
				$nid = $crow['id'] . '_1';
				//print_r($row);
				$datas[] = '<tr>
                                <td>&emsp;<span id="name_' . $nid . '">(' . $crow['code'] . ') ' . $crow['name'] . '</span></td>
                                <td>Group</td>
                                <td>-</td>
                                <td>-</td>';
				if ($group_p == 1) {
					$datas[] = '	<td>';
					if ($group_e == 1) {
						if(empty($crow['fixed'])){
							$datas[] = '<a style="color: #fff;" href="' . base_url() . '/account/edit_group/' . $crow['id'] . '">
                                                <button class="btn btn-primary  btn-rad"><i class="material-icons">&#xE3C9;</i></button>
                                            </a>';
						}

					}
					if($group_d == 1) {
						if(empty($crow['fixed'])){
							$datas[] = '<a style="color: #fff;" href="#"><button class="btn btn-danger btn-rad" onclick="confirm_modal('.$lid.')"><i class="material-icons">&#xE872;</i></button></a>';
						}
						
					}
					$datas[] = '</td>';
				}
				$datas[] = '<tr>';
				$id = $crow['id'];
				if (!empty($_POST['ledger'])) {
					$ledger_id = $_POST['ledger'];
					$res = $this->db->query("select * from ledgers where group_id = '" . $id . "' and id = '" . $ledger_id . "' order by left_code, right_code asc")->getResultArray();
				} else {
					$res = $this->db->query("select * from `ledgers` where group_id = '" . $id . "' order by left_code, right_code asc")->getResultArray();
				}
				if (count($res) > 0) {
					foreach ($res as $dd) {
						$lid = $dd['id'] . ',2';
						$nid = $dd['id'] . '_2';
						$led_id = $dd['id'];
						$ledgername = $dd['name'];
						$debitamt = 0;
						$creditamt = 0;
						$debitamt = $this->db->query("select sum(entryitems.amount) as amount 
								from entryitems 
								inner join entries on entries.id = entryitems. entry_id
								where entryitems.dc = 'D' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
						$creditamt = $this->db->query("select sum(entryitems.amount) as amount 
								from entryitems 
								inner join entries on entries.id = entryitems. entry_id
								where entryitems.dc = 'C' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
						if ($debitamt['amount'] == '')
							$debitamt['amount'] = 0;
						if ($creditamt['amount'] == '')
							$creditamt['amount'] = 0;
						$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $dd['id'])->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
						$op_balance_amt = 0;
						if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
							$op_balance_amt -= $op_balance['cr_amount'];
						} else {
							$op_balance_amt += $op_balance['dr_amount'];
						}
						$clbal = ($op_balance_amt - $creditamt['amount']) + $debitamt['amount'];
						if($op_balance_amt < 0){
							$op_balance_amt_amount = '(' . number_format(abs($op_balance_amt), "2", ".", ",") . ')';
						}else{
							$op_balance_amt_amount = number_format(abs($op_balance_amt), "2", ".", ",");
						}
						if($clbal < 0){
							$clbal_amount = '(' . number_format(abs($clbal), "2", ".", ",") . ')';
						}else{
							$clbal_amount = number_format(abs($clbal), "2", ".", ",");
						}
						$datas[] = '<tr>
                                        <td>&emsp;&emsp;<a style="margin-left: 5%;" href="' . base_url() . '/accountreport/ledger_statement/' . $dd['id'] . '" id="name_' . $nid . '">('.$dd['left_code'] .'/'. $dd['right_code'] .') ' . $ledgername . '</a></td>
                                        <td>Ledger</td>
                                        <td>' . $op_balance_amt_amount . '</td>
                                        <td>' . $clbal_amount . '</td>';
						if ($ledger_p == 1) {
							$datas[] = '	<td>';
							if ($ledgere == 1) {
								$action = '
                                                <a style="color: #fff;" href="' . base_url() . '/account/edit_ledger/' . $dd['id'] . '">
                                                    <button class="btn btn-primary  btn-rad" style="color: #fff;"><i class="material-icons">&#xE3C9;</i></button>
                                                </a>';
												
                                if($op_dis) $action .= '<a style="color: #fff;" href="' . base_url() . '/account/edit_opbal/' . $dd['id'] . '">
                                                    <button class="btn btn-success  btn-rad"><i class="material-icons">attach_money</i></button>
                                                </a>';
								$datas[] = $action;
							}
							if ($ledgerd == 1) {
								$datas[] = '
                                            <a style="color: #fff;" href="#">
                                                <button class="btn btn-danger  btn-rad" onclick="confirm_modal(' . $lid . ')" style="color: #fff;"><i class="material-icons">&#xE872;</i></button>
                                            </a>';
							}
							$datas[] = '
                                        </td>';
						}
						$datas[] = '<tr>';
					}
				}
				// 2nd child
				$mcgroup = $this->db->query("select * from `groups` where parent_id = $id")->getResultArray();
				foreach ($mcgroup as $mcrow) {
					$lid = $mcrow['id'] . ',1';
					$nid = $mcrow['id'] . '_1';
					//print_r($row);
					$datas[] = '<tr>
                                    <td>&emsp;&emsp;&emsp;<span id="name_' . $nid . '">(' . $mcrow['code'] . ') ' . $mcrow['name'] . '</span></td>
                                    <td>Group</td>
                                    <td>-</td>
                                    <td>-</td>';
					if ($group_p == 1) {
						$datas[] = '	<td>';
						if ($group_e == 1) {
							if(empty($mcrow['fixed'])){
								$datas[] = '<a style="color: #fff;" href="' . base_url() . '/account/edit_group/' . $mcrow['id'] . '">
                                                    <button class="btn btn-primary  btn-rad"><i class="material-icons">&#xE3C9;</i></button>
                                                </a>';
							}

						}
						if($group_d == 1) {
							if(empty($mcrow['fixed'])){
								$datas[] = '<a style="color: #fff;" href="#"><button class="btn btn-danger btn-rad" onclick="confirm_modal('.$lid.')"><i class="material-icons">&#xE872;</i></button></a>';
							}
						}
						$datas[] = '	</td>';
					}
					$datas[] = '<tr>';
					$id = $mcrow['id'];
					if (!empty($_POST['ledger'])) {
						$ledger_id = $_POST['ledger'];
						$res = $this->db->query("select * from ledgers where group_id = '" . $id . "' and id = '" . $ledger_id . "'  order by left_code, right_code asc")->getResultArray();
					} else {
						$res = $this->db->query("select * from `ledgers` where group_id = '" . $id . "' order by left_code, right_code asc")->getResultArray();
					}
					if (count($res) > 0) {
						foreach ($res as $dd) {
							$led_id = $dd['id'];
							$ledgername = $dd['name'];
							$lid = $dd['id'] . ',2';
							$nid = $dd['id'] . '_2';
							$debitamt = 0;
							$creditamt = 0;
							$debitamt = $this->db->query("select sum(entryitems.amount) as amount 
								from entryitems 
								inner join entries on entries.id = entryitems. entry_id
								where entryitems.dc = 'D' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
							$creditamt = $this->db->query("select sum(entryitems.amount) as amount 
									from entryitems 
									inner join entries on entries.id = entryitems. entry_id
									where entryitems.dc = 'C' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
							if ($debitamt['amount'] == '')
								$debitamt['amount'] = 0;
							if ($creditamt['amount'] == '')
								$creditamt['amount'] = 0;
							$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $dd['id'])->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
							$op_balance_amt = 0;
							if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
								$op_balance_amt -= $op_balance['cr_amount'];
							} else {
								$op_balance_amt += $op_balance['dr_amount'];
							}
							$clbal = ($op_balance_amt - $creditamt['amount']) + $debitamt['amount'];
							if($op_balance_amt < 0){
								$op_balance_amt_amount = '(' . number_format(abs($op_balance_amt), "2", ".", ",") . ')';
							}else{
								$op_balance_amt_amount = number_format(abs($op_balance_amt), "2", ".", ",");
							}
							if($clbal < 0){
								$clbal_amount = '(' . number_format(abs($clbal), "2", ".", ",") . ')';
							}else{
								$clbal_amount = number_format(abs($clbal), "2", ".", ",");
							}
							$datas[] = '<tr>
                                            <td>&emsp;&emsp;&emsp;&emsp;<a style="margin-left: 5%;" href="' . base_url() . '/accountreport/ledger_statement/' . $dd['id'] . '" id="name_' . $nid . '">('.$dd['left_code'] .'/'. $dd['right_code'] .') ' . $ledgername . '</a></td>
                                            <td>Ledger</td>
                                            <td>' . $op_balance_amt_amount . '</td>
                                            <td>' . $clbal_amount . '</td>';
							if ($ledger_p == 1) {
								$datas[] = '	<td>';
								if ($ledgere == 1) {
									$action = '
                                                    <a style="color: #fff;" href="' . base_url() . '/account/edit_ledger/' . $dd['id'] . '">
                                                        <button class="btn btn-primary  btn-rad" style="color: #fff;"><i class="material-icons">&#xE3C9;</i></button>
                                                    </a>';
														
                                    if($op_dis) $action .= '<a style="color: #fff;" href="' . base_url() . '/account/edit_opbal/' . $dd['id'] . '">
                                                        <button class="btn btn-success  btn-rad"><i class="material-icons">attach_money</i></button>
                                                    </a>';
									$datas[] = $action;
								}
								if ($ledgerd == 1) {
									$datas[] = '
                                                <a style="color: #fff;" href="#">
                                                    <button class="btn btn-danger  btn-rad" onclick="confirm_modal(' . $lid . ')" style="color: #fff;"><i class="material-icons">&#xE872;</i></button>
                                                </a>';
								}
								$datas[] = '
                                            </td>';
							}
							$datas[] = '<tr>';
						}
					}
				}
			}
		}
		$data['list'] = $datas;
		$data['check_financial_year'] = $this->db->table("ac_year")->where('status', 1)->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/index', $data);
		echo view('template/footer');
	}
	
	
	
	public function index_new()
	{
	
		if (!$this->model->list_validate('ac_creation_accounts')) {
			return redirect()->to('/dashboard');
		}
		$data['permission'] = $this->model->get_permission('ac_creation_accounts');
		$data['add_group'] = $this->model->get_permission('group');
		$data['add_ledger'] = $this->model->get_permission('ledger');

		if (!empty($_POST['ledger']))
			$ledger_id = $_POST['ledger'];
		else
			$ledger_id = "";

		$group = $this->db->table("groups")->orderBy('code', 'asc')->get()->getResultArray();
		$ledger[] = '<option value="">--Select Ledger--</option>';
		foreach ($group as $row) {
			$res = $this->db->table("ledgers")->where('group_id', $row['id'])->orderBy('code', 'asc')->get()->getResultArray();
			foreach ($res as $r) {
				$id = $r['id'];
				$ledgername = $r['left_code'] . '/' . $r['right_code'] . ' - ' . $r['name'];
				if ($ledger_id == $id)
					$selected = 'selected';
				else
					$selected = '';
				$ledger[] .= '<option ' . $selected . ' value="' . $id . '">' . $ledgername . '</option>';
			}
		}
		$data['ledger'] = $ledger;
		$data['group'] = $group;
		$ac_id = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$sdate = $ac_id['from_year_month'] . "-01";
		$tdate = $ac_id['to_year_month'] . "-31";
		//var_dump($sdate);
		//var_dump($tdate);
		//var_dump($ac_id);
		//exit;
		$datas = array();
		$group = $this->model->get_permission('group');
		if ($group['edit'] == 1 || $group['delete_p'] == 1)
			$group_p = 1;
		else
			$group_p = 0;
		if ($group['edit'] == 1)
			$group_e = 1;
		else
			$group_e = 0;
		if ($group['delete_p'] == 1)
			$group_d = 1;
		else
			$group_d = 0;
		$ledgerp = $this->model->get_permission('ledger');
		if ($ledgerp['edit'] == 1 || $ledgerp['delete_p'] == 1)
			$ledger_p = 1;
		else
			$ledger_p = 0;
		if ($ledgerp['edit'] == 1)
			$ledgere = 1;
		else
			$ledgere = 0;
		if ($ledgerp['delete_p'] == 1)
			$ledgerd = 1;
		else
			$ledgerd = 0;
		//Parent Group
		$datas = array();
		$parent = $this->db->query("select * from `groups` where parent_id is NULL or parent_id ='' or parent_id = 0 order by code asc")->getResultArray();
		foreach ($parent as $row) {
			$lid = $row['id'] . ',1';
			$nid = $row['id'] . '_1';
			//print_r($row);
			$datas[] = '<tr class="parent_1">
					   		<td><a><span id="name_' . $nid . '">(' . $row['code'] . ') ' . $row['name'] . '</span></a> </td>
							<td>Group</td>
							<td>-</td>
							<td>-</td>';
			if ($group_p == 1) {
				$datas[] = '	<td>';
				if ($group_e == 1) {
					$datas[] = '		<a style="color: #fff;" href="' . base_url() . '/account/edit_group/' . $row['id'] . '">
                                            <button class="btn btn-primary  btn-rad"><i class="material-icons">&#xE3C9;</i></button>
                                        </a>';

				}
				/*if($group_d == 1) {				
																																																																																																													$datas[] = '		<a style="color: #fff;" href="#">
																																																																																																																		<button class="btn btn-danger btn-rad" onclick="confirm_modal('.$lid.')"><i class="material-icons">&#xE872;</i></button>
																																																																																																																	</a>';
																																																																																																													}*/

				$datas[] = '	</td>';
			}
			$datas[] = '</tr>';
			$id = $row['id'];
			if (!empty($_POST['ledger'])) {
				$ledger_id = $_POST['ledger'];
				$res = $this->db->query("select * from ledgers where group_id = '" . $id . "' and id = '" . $ledger_id . "' ")->getResultArray();
			} else {
				$res = $this->db->query("select * from `ledgers` where group_id = '" . $id . "' ")->getResultArray();
			}
			//$res = $this->db->query("select * from ledgers where group_id = '".$id."' ")->getResultArray();
			if (count($res) > 0) {
				foreach ($res as $dd) {
					$lid = $dd['id'] . ',2';
					$nid = $dd['id'] . '_2';
					$led_id = $dd['id'];
					$ledgername = get_ledger_name($led_id);
					$debitamt = 0;
					$creditamt = 0;
					$debitamt = $this->db->query("select sum(entryitems.amount) as amount 
								from entryitems 
								inner join entries on entries.id = entryitems. entry_id
								where entryitems.dc = 'D' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
					$creditamt = $this->db->query("select sum(entryitems.amount) as amount 
								from entryitems 
								inner join entries on entries.id = entryitems. entry_id
								where entryitems.dc = 'C' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
					if ($debitamt['amount'] == '')
						$debitamt['amount'] = 0;
					if ($creditamt['amount'] == '')
						$creditamt['amount'] = 0;
					$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $dd['id'])->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
					if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
						$op_balance_amt = $op_balance['cr_amount'];
					} else {
						$op_balance_amt = $op_balance['dr_amount'];
					}
					//echo $creditamt['amount'];
					//exit;
					$clbal = ($op_balance_amt - $creditamt['amount']) + $debitamt['amount'];
					//echo $clbal.'<br>'; 
					$datas[] = '<tr class="child">
                                    <td><a style="margin-left: 5%;" href="' . base_url() . '/accountreport/ledger_statement/' . $dd['id'] . '" id="name_' . $nid . '">' . $ledgername . '</a></td>
                                    <td>Ledger</td>
                                    <td>' . number_format($op_balance_amt, "2", ".", ",") . '</td>
                                    <td>' . number_format(abs($clbal), "2", ".", ",") . '</td>';
					if ($ledger_p == 1) {
						$datas[] = '	<td>';
						if ($ledgere == 1) {
							$datas[] = '
                                            <a style="color: #fff;" href="' . base_url() . '/account/edit_ledger/' . $dd['id'] . '">
                                                <button class="btn btn-primary  btn-rad" style="color: #fff;"><i class="material-icons">&#xE3C9;</i></button>
                                            </a> 
                                            <a style="color: #fff;" href="' . base_url() . '/account/edit_opbal/' . $dd['id'] . '">
                                                <button class="btn btn-success  btn-rad"><i class="material-icons">attach_money</i></button>
                                            </a>';
						}
						if ($ledgerd == 1) {
							$datas[] = '
                                        <a style="color: #fff;" href="#">
                                            <button class="btn btn-danger  btn-rad" onclick="confirm_modal(' . $lid . ')" style="color: #fff;"><i class="material-icons">&#xE872;</i></button>
                                        </a>';
						}
						$datas[] = '
                                    </td>';
					}
					$datas[] = '</tr>';
				}
			}
			// Child Group
			$cgroup = $this->db->query("select * from `groups` where parent_id = $id order by code asc")->getResultArray();
			foreach ($cgroup as $crow) {
				$lid = $crow['id'] . ',1';
				$nid = $crow['id'] . '_1';
				//print_r($row);
				$datas[] = '<tr class="parent">
                                <td>&emsp; <a><span id="name_' . $nid . '">(' . $crow['code'] . ') ' . $crow['name'] . '</span></a></td>
                                <td>Group</td>
                                <td>-</td>
                                <td>-</td>';
				if ($group_p == 1) {
					$datas[] = '	<td>';
					if ($group_e == 1) {
						$datas[] = '		<a style="color: #fff;" href="' . base_url() . '/account/edit_group/' . $crow['id'] . '">
                                                <button class="btn btn-primary  btn-rad"><i class="material-icons">&#xE3C9;</i></button>
                                            </a>';

					}
					/*if($group_d == 1) {				
																																																																																																																																								 $datas[] = '		<a style="color: #fff;" href="#">
																																																																																																																																													 <button class="btn btn-danger btn-rad" onclick="confirm_modal('.$lid.')"><i class="material-icons">&#xE872;</i></button>
																																																																																																																																												 </a>';
																																																																																																																																								 }	*/

					$datas[] = '	</td>';
				}
				$datas[] = '</tr>';
				$id = $crow['id'];
				if (!empty($_POST['ledger'])) {
					$ledger_id = $_POST['ledger'];
					$res = $this->db->query("select * from ledgers where group_id = '" . $id . "' and id = '" . $ledger_id . "' order by left_code asc")->getResultArray();
				} else {
					$res = $this->db->query("select * from `ledgers` where group_id = '" . $id . "' order by left_code asc")->getResultArray();
				}
				if (count($res) > 0) {
					foreach ($res as $dd) {
						$lid = $dd['id'] . ',2';
						$nid = $dd['id'] . '_2';
						$led_id = $dd['id'];
						$ledgername = $dd['name'];
						$debitamt = 0;
						$creditamt = 0;
						$debitamt = $this->db->query("select sum(entryitems.amount) as amount 
								from entryitems 
								inner join entries on entries.id = entryitems. entry_id
								where entryitems.dc = 'D' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
						$creditamt = $this->db->query("select sum(entryitems.amount) as amount 
								from entryitems 
								inner join entries on entries.id = entryitems. entry_id
								where entryitems.dc = 'C' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
						if ($debitamt['amount'] == '')
							$debitamt['amount'] = 0;
						if ($creditamt['amount'] == '')
							$creditamt['amount'] = 0;
						$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $dd['id'])->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
						if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
							$op_balance_amt = $op_balance['cr_amount'];
						} else {
							$op_balance_amt = $op_balance['dr_amount'];
						}
						$clbal = ($op_balance_amt - $creditamt['amount']) + $debitamt['amount'];
						$datas[] = '<tr class="child">
                                        <td>&emsp;&emsp;<a style="margin-left: 5%;" href="' . base_url() . '/accountreport/ledger_statement/' . $dd['id'] . '" id="name_' . $nid . '">('.$dd['left_code'] .'/'. $dd['right_code'] .') ' . $ledgername . '</a></td>
                                        <td>Ledger</td>
                                        <td>' . number_format($op_balance_amt, "2", ".", ",") . '</td>
                                        <td>' . number_format(abs($clbal), "2", ".", ",") . '</td>';
						if ($ledger_p == 1) {
							$datas[] = '	<td>';
							if ($ledgere == 1) {
								$datas[] = '
                                                <a style="color: #fff;" href="' . base_url() . '/account/edit_ledger/' . $dd['id'] . '">
                                                    <button class="btn btn-primary  btn-rad" style="color: #fff;"><i class="material-icons">&#xE3C9;</i></button>
                                                </a> 
                                                <a style="color: #fff;" href="' . base_url() . '/account/edit_opbal/' . $dd['id'] . '">
                                                    <button class="btn btn-success  btn-rad"><i class="material-icons">attach_money</i></button>
                                                </a>';
							}
							if ($ledgerd == 1) {
								$datas[] = '
                                            <a style="color: #fff;" href="#">
                                                <button class="btn btn-danger  btn-rad" onclick="confirm_modal(' . $lid . ')" style="color: #fff;"><i class="material-icons">&#xE872;</i></button>
                                            </a>';
							}
							$datas[] = '
                                        </td>';
						}
						$datas[] = '</tr>';
					}
				}
				// 2nd child
				$mcgroup = $this->db->query("select * from `groups` where parent_id = $id")->getResultArray();
				foreach ($mcgroup as $mcrow) {
					$lid = $mcrow['id'] . ',1';
					$nid = $mcrow['id'] . '_1';
					//print_r($row);
					$datas[] = '<tr class="parent">
                                    <td>&emsp;&emsp;&emsp; <a><span id="name_' . $nid . '">(' . $mcrow['code'] . ') ' . $mcrow['name'] . '</span></a></td>
                                    <td>Group</td>
                                    <td>-</td>
                                    <td>-</td>';
					if ($group_p == 1) {
						$datas[] = '	<td>';
						if ($group_e == 1) {
							$datas[] = '		<a style="color: #fff;" href="' . base_url() . '/account/edit_group/' . $mcrow['id'] . '">
                                                    <button class="btn btn-primary  btn-rad"><i class="material-icons">&#xE3C9;</i></button>
                                                </a>';

						}
						/* if($group_d == 1) {				
																																																																																																																																																																			   $datas[] = '		<a style="color: #fff;" href="#">
																																																																																																																																																																								   <button class="btn btn-danger btn-rad" onclick="confirm_modal('.$lid.')"><i class="material-icons">&#xE872;</i></button>
																																																																																																																																																																							   </a>';
																																																																																																																																																																			   }	*/

						$datas[] = '	</td>';
					}
					$datas[] = '</tr>';
					$id = $mcrow['id'];
					if (!empty($_POST['ledger'])) {
						$ledger_id = $_POST['ledger'];
						$res = $this->db->query("select * from ledgers where group_id = '" . $id . "' and id = '" . $ledger_id . "' ")->getResultArray();
					} else {
						$res = $this->db->query("select * from `ledgers` where group_id = '" . $id . "' ")->getResultArray();
					}
					if (count($res) > 0) {
						foreach ($res as $dd) {
							$led_id = $dd['id'];
							$ledgername = $dd['name'];
							$lid = $dd['id'] . ',2';
							$nid = $dd['id'] . '_2';
							$debitamt = 0;
							$creditamt = 0;
							$debitamt = $this->db->query("select sum(entryitems.amount) as amount 
								from entryitems 
								inner join entries on entries.id = entryitems. entry_id
								where entryitems.dc = 'D' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
							$creditamt = $this->db->query("select sum(entryitems.amount) as amount 
									from entryitems 
									inner join entries on entries.id = entryitems. entry_id
									where entryitems.dc = 'C' and entryitems.ledger_id = $led_id and entries.date >= '$sdate' and entries.date <= '$tdate'")->getRowArray();
							if ($debitamt['amount'] == '')
								$debitamt['amount'] = 0;
							if ($creditamt['amount'] == '')
								$creditamt['amount'] = 0;
							$op_balance = $this->db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $dd['id'])->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
							if ($op_balance['dr_amount'] == "0.00" || $op_balance['dr_amount'] == "") {
								$op_balance_amt = $op_balance['cr_amount'];
							} else {
								$op_balance_amt = $op_balance['dr_amount'];
							}
							$clbal = ($op_balance_amt - $creditamt['amount']) + $debitamt['amount'];
							$datas[] = '<tr class="child">
                                            <td>&emsp;&emsp;&emsp;&emsp;<a style="margin-left: 5%;" href="' . base_url() . '/accountreport/ledger_statement/' . $dd['id'] . '" id="name_' . $nid . '">('.$dd['left_code'] .'/'. $dd['right_code'] .') ' . $ledgername . '</a></td>
                                            <td>Ledger</td>
                                            <td>' . number_format($op_balance_amt, "2", ".", ",") . '</td>
                                            <td>' . number_format(abs($clbal), "2", ".", ",") . '</td>';
							if ($ledger_p == 1) {
								$datas[] = '	<td>';
								if ($ledgere == 1) {
									$datas[] = '
                                                    <a style="color: #fff;" href="' . base_url() . '/account/edit_ledger/' . $dd['id'] . '">
                                                        <button class="btn btn-primary  btn-rad" style="color: #fff;"><i class="material-icons">&#xE3C9;</i></button>
                                                    </a> 
                                                    <a style="color: #fff;" href="' . base_url() . '/account/edit_opbal/' . $dd['id'] . '">
                                                        <button class="btn btn-success  btn-rad"><i class="material-icons">attach_money</i></button>
                                                    </a>';
								}
								if ($ledgerd == 1) {
									$datas[] = '
                                                <a style="color: #fff;" href="#">
                                                    <button class="btn btn-danger  btn-rad" onclick="confirm_modal(' . $lid . ')" style="color: #fff;"><i class="material-icons">&#xE872;</i></button>
                                                </a>';
								}
								$datas[] = '
                                            </td>';
							}
							$datas[] = '<tr>';
						}
					}
				}
			}
		}
		$data['list'] = $datas;
		$data['check_financial_year'] = $this->db->table("ac_year")->where('status', 1)->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/index_new', $data);
		echo view('template/footer');
	
	}

	public function add_group()
	{

		if (!$this->model->permission_validate('group', 'create_p')) {
			return redirect()->to('/dashboard');
		}
		$data['group'] = $this->db->table('groups')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/add_group', $data);
		echo view('template/footer');
	}

	public function edit_group()
	{
		if (!$this->model->permission_validate('group', 'edit')) {
			return redirect()->to('/dashboard');
		}
		$id = $this->request->uri->getSegment(3);
		$data['data'] = $this->db->table('groups')->where("id", $id)->get()->getRowArray();
		$data['group'] = $this->db->table('groups')->get()->getResultArray();
		//print_r($data);die;
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/edit_group', $data);
		echo view('template/footer');
	}

	public function save_add_group()
	{
		$id = $_POST['id'];

		if(!empty($_POST['pgroup'])) $data['parent_id'] = $_POST['pgroup'];
		$data['name'] = $_POST['gname'];
		$data['code'] = $_POST['gcode'];
		$data['added_by'] = $this->session->get('log_id');

		if (empty($id)) {
			$data['created'] = date('Y-m-d H:i:s');
			$data['modified'] = date('Y-m-d H:i:s');
			$builder = $this->db->table('groups')->insert($data);
			if ($builder) {
				$this->session->setFlashdata('succ', 'Groups Added Successfully');
				return redirect()->to(base_url() . "/account");
			} else {
				$this->session->setFlashdata('fail', 'Please Try Again');
				return redirect()->to(base_url() . "/account");
			}
		} else {
			$data['modified'] = date('Y-m-d H:i:s');
			$builder = $this->db->table('groups')->where('id', $id)->update($data);
			if ($builder) {
				$this->session->setFlashdata('succ', 'Groups Update Successfully');
				return redirect()->to(base_url() . "/account");
			} else {
				$this->session->setFlashdata('fail', 'Please Try Again');
				return redirect()->to(base_url() . "/account");
			}
		}
	}

	public function add_ledger()
	{
		if (!$this->model->permission_validate('ledger', 'create_p')) {
			return redirect()->to('/dashboard');
		}
		$data['group'] = $this->db->table('groups')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/add_ledger', $data);
		echo view('template/footer');
	}

	public function edit_ledger()
	{
		if (!$this->model->permission_validate('ledger', 'edit')) {
			return redirect()->to('/dashboard');
		}
		$id = $this->request->uri->getSegment(3);
		$data['data'] = $this->db->table('ledgers')->where("id", $id)->get()->getRowArray();
		$data['group'] = $this->db->table('groups')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/edit_ledger', $data);
		echo view('template/footer');
	}

	public function entries()
	{
		if (!$this->model->list_validate('entries_accounts')) {
			return redirect()->to('/dashboard');
		}
		$data['permission'] = $this->model->get_permission('entries_accounts');
		$data['data'] = $this->db->table('entries')->where('inv_id', null)->where('type', null)->orderBy('id', 'desc')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('entries/index', $data);
		echo view('template/footer');
	}

	public function save_add_ledger()
	{
		$id = $_POST['id'];
		if(!empty($_POST['ledger_subgroup'])){
			$data['group_id'] = $_POST['ledger_subgroup'];
		}else{
			$data['group_id'] = $_POST['lgroup'];
		}
		$data['name'] = $_POST['lname'];
		$data['code'] = $_POST['lcode'];
		$data['op_balance'] = $_POST['op_bal'];
		$data['op_balance_dc'] = $_POST['op_dc'];
		$data['right_code'] = $_POST['right_code'];
		$data['reconciliation'] = !empty($_POST['reconciliation']) ? 1 : 0;
		$data['type'] = !empty($_POST['type']) ? 1 : 0;
		$data['pa'] = !empty($_POST['profit_accuulation']) ? 1 : 0;
		$data['aging'] = !empty($_POST['aging']) ? 1 : 0;
		//echo '<pre>';
		//print_r($data);die;
		$ac_id = $this->db->table("ac_year")->where('status', 1)->get()->getRowArray();
		if(!empty($data['name']) && !empty($data['right_code']) && !empty($data['group_id']) ){
			$group_name_left_code = $this->db->table("groups")->where('id', $data['group_id'])->get()->getRowArray();
			$data['left_code'] = $group_name_left_code['code'];
			if (empty($id)) {
				$res = $this->db->table('ledgers')->insert($data);
				if ($res) {
					$ledger_id = $this->db->insertID();
					if ($_POST['op_dc'] == "D") {
						$acdata['dr_amount'] = $_POST['op_bal'];
						$acdata['cr_amount'] = "0.00";
					} else {
						$acdata['cr_amount'] = $_POST['op_bal'];
						$acdata['dr_amount'] = "0.00";
					}
					$acdata['ledger_id'] = $ledger_id;
					$acdata['ac_year_id'] = $ac_id['id'];
					$data_exists = $this->db->table('ac_year_ledger_balance')->where('ledger_id', $ledger_id)->where('ac_year_id', $ac_id['id'])->get()->getNumRows();
					if ($data_exists > 0) {
						$res = $this->db->table('ac_year_ledger_balance')->where('ledger_id', $ledger_id)->where('ac_year_id', $ac_id['id'])->update($acdata);
					} else {
						$res = $this->db->table('ac_year_ledger_balance')->insert($acdata);
					}
	
					$this->session->setFlashdata('succ', 'Ledger Add Successfully');
					return redirect()->to(base_url() . "/account");
				} else {
					$this->session->setFlashdata('fail', 'Please Try Again');
					return redirect()->to(base_url() . "/account");
				}
			} else {
				$res = $this->db->table('ledgers')->where('id', $id)->update($data);
				if ($res) {
					$this->session->setFlashdata('succ', 'Ledger Update Successfully');
					return redirect()->to(base_url() . "/account");
				} else {
					$this->session->setFlashdata('fail', 'Please Try Again');
					return redirect()->to(base_url() . "/account");
				}
			}
		}
		else {
			$this->session->setFlashdata('fail', 'Please enter required fields');
			return redirect()->to(base_url() . "/account");
		}
	}
	public function get_group_code(){
		$ledger_subgroup_id = $_POST['ledger_subgroup_id'];
		if(!empty($ledger_subgroup_id)){
			$group = $ledger_subgroup_id;
		}
		else{
			$group = $_POST['ledger_group_id'];
		}
		$group_name_left_code = $this->db->table("groups")->where('id', $group)->get()->getRowArray();
		echo $group_name_left_code['code'];
	}
	public function check_group()
	{
		$id = $this->request->uri->getSegment(3);
		$ent = $this->db->table('entryitems')->where('ledger_id', $id)->get()->getNumRows();
		$led = $this->db->table('ledgers')->where('group_id', $id)->get()->getNumRows();
		$grp = $this->db->table('groups')->where('parent_id', $id)->get()->getNumRows();
		if ($ent == 0 && $led == 0 && $grp == 0)
			$res = true;
		else
			$res = false;
		echo json_encode($res);
	}
	public function check_ledger()
	{
		$id = $this->request->uri->getSegment(3);
		$ent = $this->db->table('entryitems')->where('ledger_id', $id)->get()->getNumRows();
		if ($ent == 0)
			$res = true;
		else
			$res = false;
		echo json_encode($res);
	}

	public function delete_group($id)
	{
		// $id = $this->request->uri->getSegment(3);
		$num_rows = $this->db->table('ledgers')->where("group_id", $id)->get()->getNumRows();
		if($num_rows < 1){
			$res = $this->db->table('groups')->delete(['id' => $id]);
			if ($res) {
				$this->session->setFlashdata('succ', 'Group Delete Successfully');
				return redirect()->to(base_url() . "/account");} else {
				$this->session->setFlashdata('fail', 'Please Try Again...');
				return redirect()->to(base_url() . "/account");}
		}else{
			$this->session->setFlashdata('fail', 'Ledger found under the group. You can\'t delete the group.');
			return redirect()->to(base_url() . "/account");}
		exit;
	}

	public function delete_ledger($id)
	{
		// $id = $this->request->uri->getSegment(3);
		$num_rows = $this->db->table('entryitems')->where("ledger_id", $id)->get()->getNumRows();
		if($num_rows < 1){
			$res = $this->db->table('ledgers')->delete(['id' => $id]);
			if ($res) {
				$this->session->setFlashdata('succ', 'Ledger Delete Successfully');
				return redirect()->to(base_url() . "/account");} else {
				$this->session->setFlashdata('fail', 'Please Try Again...');
				return redirect()->to(base_url() . "/account");}
		}else{
			$this->session->setFlashdata('fail', 'Entries found. You can\'t delete the ledger.');
			return redirect()->to(base_url() . "/account");}
		exit;
	}

	public function edit_opbal()
	{
		if (!$this->model->permission_validate('ledger', 'edit')) {
			return redirect()->to('/dashboard');
		}
		$id = $this->request->uri->getSegment(3);
		$data['id'] = $id;
		//$data['data1'] = $this->db->table('ledgers')->where("id", $id)->get()->getRowArray();
		$data['data'] = $this->db->table('ac_year_ledger_balance')->where("ledger_id", $id)->get()->getRowArray();
		$data['year'] = $this->db->table('ac_year')->get()->getResultArray();
		$data['funds'] = $this->db->table('funds')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/edit_opbal', $data);
		echo view('template/footer');
	}

	public function save_add_ledger_opbal()
	{
		$year = $_POST['fyear'];
		$id = $_POST['id'];
		$data['ac_year_id'] = $year;
		$data['ledger_id'] = $id;
		$selval = $_POST['op_dc'];
		if ($selval == "D") {
			$data['dr_amount'] = $_POST['op_bal'];
			$data['cr_amount'] = "0.00";
		} else if ($selval == "C") {
			$data['dr_amount'] = "0.00";
			$data['cr_amount'] = $_POST['op_bal'];
		}
		$data_exists = $this->db->table('ac_year_ledger_balance')->where('ledger_id', $id)->where('ac_year_id', $year)->get()->getNumRows();
		/* echo '<pre>';
		print_r($data_exists);die; */
		if ($data_exists > 0) {
			$res = $this->db->table('ac_year_ledger_balance')->where('ledger_id', $id)->where('ac_year_id', $year)->update($data);
			if ($res) {
				$this->session->setFlashdata('succ', 'Opening Balance Updated Successfully');
				return redirect()->to(base_url() . "/account");} else {
				$this->session->setFlashdata('fail', 'Please Try Again');
				return redirect()->to(base_url() . "/account");}
		} else {
			$res = $this->db->table('ac_year_ledger_balance')->insert($data);
			if ($res) {
				$this->session->setFlashdata('succ', 'Opening Balance Added Successfully');
				return redirect()->to(base_url() . "/account");} else {
				$this->session->setFlashdata('fail', 'Please Try Again');
				return redirect()->to(base_url() . "/account");}
		}
		exit;
	}

	public function get_amt()
	{
		$id = $_POST['id'];
		$yr = $_POST['yr'];
		$builder = $this->db->table("ac_year_ledger_balance")->where("ledger_id", $id)->where("ac_year_id", $yr);
		if(!empty($_POST['fund_id'])) $builder->where("fund_id", $_POST['fund_id']);
		$res = $builder->get()->getRowArray();
		if ($res['cr_amount'] == "0.00") {
			$data['select'] = "D";
			$data['amt'] = $res['dr_amount'];
		} else if ($res['dr_amount'] == "0.00") {
			$data['select'] = "C";
			$data['amt'] = $res['cr_amount'];
		} else {
			$data['select'] = "D";
			$data['amt'] = "0.00";
		}
		echo json_encode($data);
	}

	public function funds()
	{


		$data['list'] = $this->db->table('funds')->get()->getResultArray();

		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/funds', $data);
		echo view('template/footer');
	}
	public function save_donation_category(){
        

		$id = $_POST['id'];
		$data['name']	 =	trim($_POST['name']);
		$data['description']	 =	trim($_POST['description']);
		$data['code'] = trim($_POST['code']);
	
		
		
		if(empty($id)){
		    $builder = $this->db->table('donation_category')->insert($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Donation Category Added Successfully');
    		    return redirect()->to(base_url()."/master/donation_category");}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to(base_url()."/master/donation_category");}
		}else{
            
            $builder = $this->db->table('donation_category')->where('id', $id)->update($data);
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Donation Category Update Successfully');
    		    return redirect()->to(base_url()."/master/donation_category");}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to(base_url()."/master/donation_category");}
		}
		exit;
		
	}
	public function save_funds()
	{
		
		$id = $_POST['id'];
		$data['name'] = trim($_POST['name']);
		$data['description'] = trim($_POST['description']);
		$data['code'] = trim($_POST['code']);

		
		if (empty($id)) {
			$data['created'] = date('Y-m-d H:i:s');
			$data['modified'] = date('Y-m-d H:i:s');
			$builder = $this->db->table('funds')->insert($data);

			
			if($builder){
    		    $this->session->setFlashdata('succ', 'Fund Added Successfully');
    		    return redirect()->to(base_url()."/account/funds");}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to(base_url()."/account/funds");}

		} else {
			$data['modified'] = date('Y-m-d H:i:s');
			$builder = $this->db->table('funds')->where('id', $id)->update($data);
			
		    if($builder){
    		    $this->session->setFlashdata('succ', 'Fund Update Successfully');
    		    return redirect()->to(base_url()."/account/funds");}else{
    		    $this->session->setFlashdata('fail', 'Please Try Again');
    		    return redirect()->to(base_url()."/account/funds");}
			
		}
		exit;


	}

	public function funds_validation(){
		$name = trim($_POST['name']);
		
		$data = array();
		if (empty($name) ) {
		  $data['err'] = "Please Fill Required Fields";
		  $data['succ']= '';
		}else{
		  $data['succ'] = "Form validate";
		  $data['err'] ='';
		}
		echo json_encode($data);
	}
	public function del_funds_check(){
		$id = $_POST['id'];
		$res = $this->db->table("funds")->where("name", $id)->get()->getResultArray();
		echo count($res);
	}
	public function delete_funds(){
	    
		$id=  $this->request->uri->getSegment(3);
		$res = $this->db->table('funds')->delete(['id' => $id]);
		if($res){
		    $this->session->setFlashdata('succ', 'Fund Delete Successfully');
		    return redirect()->to(base_url()."/account/funds");}else{
		    $this->session->setFlashdata('fail', 'Please Try Again');
		    return redirect()->to(base_url()."/account/funds");}
		exit;
	}
	
	public function edit_funds(){
	    
		$id=  $this->request->uri->getSegment(3);
	    $data['data'] = $this->db->table('funds')->where('id', $id)->get()->getRowArray();
		
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('account/add_funds', $data);
		echo view('template/footer');
	}
	
	public function view_funds(){
	   
		$id=  $this->request->uri->getSegment(3);
		
	    $data['data'] = $this->db->table('funds')->where('id', $id)->get()->getRowArray();
	    $data['view'] = true;
	    echo view('template/header');
		echo view('template/sidebar');
		echo view('account/add_funds', $data);
		echo view('template/footer');
	}

	public function add_funds() {

		$data['funds'] = $this->db->table('funds')->get()->getResultArray();
		echo view('template/header');
		echo view('template/sidebar');
		echo view('account/add_funds', $data);
		echo view('template/footer');
    }
	public function get_sub_group($id, $json = true){
		$groups = $this->db->table('groups')->where("parent_id", $id)->get()->getResultArray();
		if($json){
			echo json_encode($groups);
			exit;
		}else return $groups;
	}
	
	public function save_group()
	{
		
		$data['parent_id'] = trim($_POST['g_id']);
		$data['name'] = trim($_POST['group']);
		$data['code'] = trim($_POST['code']);
		$data['added_by'] = $this->session->get('log_id');
		$data['created'] = date('Y-m-d H:i:s');
		$data['modified'] = date('Y-m-d H:i:s');
			
		$builder = $this->db->table('groups')->insert($data);
		$sub_groups = $this->get_sub_group($data['parent_id'], false);
		$data = array("status"=>"success","sub_groups" => $sub_groups);
		echo json_encode($data);
		exit;

	}
}