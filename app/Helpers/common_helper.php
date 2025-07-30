<?php


function get_enum_value( $table, $field ) {
	$db = db_connect();
	    $row = $db->query("SHOW COLUMNS FROM ".$table." LIKE '$field'")->getRowArray();
		$columnType = $row['Type'];
		$enumString = substr($columnType, 5, -1);
        // Split by comma and remove quotes
        $enumValues = array_map(function($value) {
            return trim($value, "'");
        }, explode(',', $enumString));
		return $enumValues;
}
function get_ledger_name($id)
{
	$db = db_connect();
	$tot_groups = $db->table('ledgers')->where('id', $id)->get()->getRowArray();
	if ($tot_groups) {
		if (!empty ($tot_groups['code']))
			$name = $tot_groups['code'] . " - " . $tot_groups['name'];
		else {
			$name = '';
			if (!empty ($tot_groups['left_code'])) {
				$name .= $tot_groups['left_code'];
				$name .= '/';
				if (!empty ($tot_groups['right_code']))
					$name .= $tot_groups['right_code'];
			}
			$name .= " - ".$tot_groups['name'];
		}
	}
	return $name;
}
function get_ledger_name_only($id)
{
	$db = db_connect();
	$tot_groups = $db->table('ledgers')->where('id', $id)->get()->getRowArray();
	if ($tot_groups) {
		if (!empty ($tot_groups['name'])) {
			$name = $tot_groups['name'];
		} else {
			$name = "";
		}
	}
	return $name;
}
function get_ledger_code_only($id)
{
	$db = db_connect();
	$tot_groups = $db->table('ledgers')->where('id', $id)->get()->getRowArray();
	if ($tot_groups) {
		$name = '';
		if (!empty ($tot_groups['left_code'])) {
			$name .= $tot_groups['left_code'];
		}
		$name .= '/';
		if (!empty ($tot_groups['right_code'])) {
			$name .= $tot_groups['right_code'];
		}
	}
	return $name;
}
function total_group_amt_new_rightcode_triplezero($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$sub_tot = 0;
	$tot_groups = $db->table('groups')->where('parent_id', $id)->get()->getResult();
	if ($tot_groups) {
		foreach ($tot_groups as $tr) {
			$sub_tot += get_group_amt_new_rightcode_triplezero($tr->id, $sdate, $tdate);
		}
	}
	return $sub_tot;
}
function get_group_amt_new_rightcode_triplezero($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	@$group_amt += get_ledgers_amt_new_rightcode_triplezero($id, $sdate, $tdate);
	$groups = $db->table('groups')->where('parent_id', $id)->get()->getResult();
	if ($groups) {
		foreach ($groups as $r) {
			$group_amt += get_ledgers_amt_new_rightcode_triplezero($r->id, $sdate, $tdate);
			get_group_amt_new_rightcode_triplezero($r->id, $sdate, $tdate);
		}
	}
	return $group_amt;
}
function total_group_amt_new_rightcode_triplezero_previousyear($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$sub_tot = 0;
	$tot_groups = $db->table('groups')->where('parent_id', $id)->get()->getResult();
	if ($tot_groups) {
		foreach ($tot_groups as $tr) {
			$sub_tot += get_group_amt_new_rightcode_triplezero_previousyear($tr->id, $sdate, $tdate);
		}
	}
	return $sub_tot;
}

function loanperiodendmonths($emi_start_month_paytype_two, $emi_type_paytype_two)
{
	$data_emi_start_month = date("Y-m-01", strtotime($emi_start_month_paytype_two));
	if(!empty($emi_type_paytype_two)){
		$emi_dedection_one_month = $emi_type_paytype_two - 1;
		$data_emi_end_month = date("Y-m-t", strtotime("+$emi_dedection_one_month months", strtotime($data_emi_start_month)));
	}else{
		$data_emi_end_month = date("Y-m-t", strtotime($emi_start_month_paytype_two));
	}
	return $data_emi_end_month;
}
function get_group_amt_new_rightcode_triplezero_previousyear($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	@$group_amt += get_ledgers_amt_new_rightcode_triplezero_previousyear($id, $sdate, $tdate);
	$groups = $db->table('groups')->where('parent_id', $id)->get()->getResult();
	if ($groups) {
		foreach ($groups as $r) {
			$group_amt += get_ledgers_amt_new_rightcode_triplezero_previousyear($r->id, $sdate, $tdate);
			get_group_amt_new_rightcode_triplezero_previousyear($r->id, $sdate, $tdate);
		}
	}
	return $group_amt;
}
function get_ledgers_amt_new_rightcode_triplezero_previousyear($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$op_balance = 0;
	$ac_id = $db->table("ac_year")->where('status', 1)->get()->getRowArray();
	$op_list = $db->table('ledgers')->where('group_id', $id)->where('right_code', '000')->get()->getResult();
	if ($op_list) {
		foreach ($op_list as $op) {
			$op_balance_new = $db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $op->id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
			if ($op_balance_new['dr_amount'] == "0.00" || $op_balance_new['dr_amount'] == "") {
				$op_balance_amt = $op_balance_new['cr_amount'];
			} else {
				$op_balance_amt = $op_balance_new['dr_amount'];
			}
			$op_balance += $op_balance_amt;
		}
	}
	return $op_balance;
}
function get_ledger_amt_new_rightcode_triplezero($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$op_balance = 0;
	// $op = $db->table('ledgers')->where('id', $id)->get()->getRow();
	// $ac_id = $db->table("ac_year")->where('status', 1)->get()->getRowArray();
	// $op_balance_new = $db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
	// if ($op_balance_new['dr_amount'] == "0.00" || $op_balance_new['dr_amount'] == "") {
		// $op_balance_amt = $op_balance_new['cr_amount'];
	// } else {
		// $op_balance_amt = $op_balance_new['dr_amount'];
	// }
	// $op_balance += $op_balance_amt;
	$op_balance -= get_ledger_cr_amt_new_rightcode_triplezero($id, $sdate, $tdate);
	$op_balance += get_ledger_dr_amt_new_rightcode_triplezero($id, $sdate, $tdate);
	$op_balance += get_ledger_op_amt_new_rightcode_triplezero($id, $sdate, $tdate);
	/* echo $op->name;
	echo '<br>';
	echo get_ledger_op_amt_new_rightcode_triplezero($id, $sdate, $tdate, $fund_id);
	echo '<br>';
	echo get_ledger_cr_amt_new_rightcode_triplezero($id, $sdate, $tdate, $fund_id);
	echo '<br>';
	echo get_ledger_dr_amt_new_rightcode_triplezero($id, $sdate, $tdate, $fund_id);
	echo '<br>'; */
	return $op_balance;
}
function get_ledger_amt_new_rightcode_triplezero_previousyear($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$op_balance = 0;
	$ac_id = $db->table("ac_year")->where('status', 1)->get()->getRowArray();
	$builder = $db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id']);
	// if (!empty ($fund_id))
		// $builder->where('fund_id', $fund_id);
	$op_balance_new = $builder->get()->getRowArray();
	if ($op_balance_new['dr_amount'] == "0.00" || $op_balance_new['dr_amount'] == "") {
		$op_balance_amt -= $op_balance_new['cr_amount'];
	} else {
		$op_balance_amt = $op_balance_new['dr_amount'];
	}
	$op_balance += $op_balance_amt;
	return $op_balance;
}
function get_ledgers_amt_new_rightcode_triplezero($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$op_balance = 0;
	$ac_id = $db->table("ac_year")->where('status', 1)->get()->getRowArray();
	$op_list = $db->table('ledgers')->where('group_id', $id)->where('right_code', '000')->get()->getResult();
	if ($op_list) {
		foreach ($op_list as $op) {
			$op_balance_new = $db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $op->id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
			if ($op_balance_new['dr_amount'] == "0.00" || $op_balance_new['dr_amount'] == "") {
				$op_balance_amt = $op_balance_new['cr_amount'];
			} else {
				$op_balance_amt = $op_balance_new['dr_amount'];
			}
			//$op_balance += $op_balance_amt;
			$op_balance -= get_ledger_cr_amt_new_rightcode_triplezero($op->id, $sdate, $tdate);
			$op_balance += get_ledger_dr_amt_new_rightcode_triplezero($op->id, $sdate, $tdate);
			$op_balance += get_ledger_op_amt_new_rightcode_triplezero($op->id, $sdate, $tdate);
			/* echo $op->name;
			echo '<br>';
			echo get_ledger_op_amt_new_rightcode_triplezero($op->id, $sdate, $tdate, $fund_id);
			echo '<br>';
			echo get_ledger_cr_amt_new_rightcode_triplezero($op->id, $sdate, $tdate, $fund_id);
			echo '<br>';
			echo get_ledger_dr_amt_new_rightcode_triplezero($op->id, $sdate, $tdate, $fund_id);
			echo '<br>'; */
		}
	}
	return $op_balance;
}
// this function not required but subtotal without zero check plus and minus included
function get_ledger_amt_new_rightcode_triplezero_subtotal($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$op_balance = 0;
	$ac_id = $db->table("ac_year")->where('status', 1)->get()->getRowArray();
	$builder = $db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id']);
	// if (!empty ($fund_id))
		// $builder->where('fund_id', $fund_id);
	$op_balance_new = $builder->get()->getRowArray();
	if ($op_balance_new['dr_amount'] == "0.00" || $op_balance_new['dr_amount'] == "") {
		$op_balance_amt = $op_balance_new['cr_amount'];
	} else {
		$op_balance_amt = $op_balance_new['dr_amount'];
	}
	$op_balance += $op_balance_amt;
	$op_balance += get_ledger_cr_amt_new_rightcode_triplezero($id, $sdate, $tdate);
	$op_balance += get_ledger_dr_amt_new_rightcode_triplezero($id, $sdate, $tdate);
	return $op_balance;
}
function get_group_amt_new_rightcode_triplezero_subtotal($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	@$group_amt += get_ledgers_amt_new_rightcode_triplezero_subtotal($id, $sdate, $tdate);
	$groups = $db->table('groups')->where('parent_id', $id)->get()->getResult();
	if ($groups) {
		foreach ($groups as $r) {
			$group_amt += get_ledgers_amt_new_rightcode_triplezero_subtotal($r->id, $sdate, $tdate);
			get_group_amt_new_rightcode_triplezero_subtotal($r->id, $sdate, $tdate);
		}
	}
	return $group_amt;
}
function get_ledgers_amt_new_rightcode_triplezero_subtotal($id, $sdate = '', $tdate = '')
{ // this function not required but subtotal without zero check plus and minus included
	$db = db_connect();
	$op_balance = 0;
	$ac_id = $db->table("ac_year")->where('status', 1)->get()->getRowArray();
	$op_list = $db->table('ledgers')->where('group_id', $id)->where('right_code', '000')->get()->getResult();
	if ($op_list) {
		foreach ($op_list as $op) {
			$op_balance_new = $db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $op->id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
			if ($op_balance_new['dr_amount'] == "0.00" || $op_balance_new['dr_amount'] == "") {
				$op_balance_amt = $op_balance_new['cr_amount'];
			} else {
				$op_balance_amt = $op_balance_new['dr_amount'];
			}
			$op_balance += $op_balance_amt;
			$op_balance += get_ledger_cr_amt_new_rightcode_triplezero($op->id, $sdate, $tdate);
			$op_balance += get_ledger_dr_amt_new_rightcode_triplezero($op->id, $sdate, $tdate);
		}
	}
	return $op_balance;
}
function get_ledger_op_amt_new_rightcode_triplezero($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$led_ids = get_ledger_ids_leftcode($id);
	$ac_id = $db->table("ac_year")->where('status', 1)->get()->getRowArray();
	$fund_where = '';
	$op_balance_amt = 0;
	if (!empty($led_ids)) {
		$led_ids_arr = explode(',', $led_ids);
		foreach ($led_ids_arr as $ld) {
			$builder = $db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id']);
			// if (!empty ($fund_id))
				// $builder->where('fund_id', $fund_id);
			$op_balance_new = $builder->get()->getRowArray();
			if ($op_balance_new['dr_amount'] == "0.00" || $op_balance_new['dr_amount'] == "") {
				$op_balance_amt -= $op_balance_new['cr_amount'];
			} else {
				$op_balance_amt += $op_balance_new['dr_amount'];
			}
		}
	} 
	return $op_balance_amt;
}
function get_ledger_cr_amt_new_rightcode_triplezero($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$led_ids = get_ledger_ids_leftcode($id);
	$fund_where = '';
	// if (!empty ($fund_id))
		// $fund_where = " and entries.fund_id = '$fund_id'";
	if (!empty ($led_ids)) {
		$c_sql = "select sum(entryitems.amount) as amount 
				from entryitems 
				inner join entries on entries.id = entryitems. entry_id
				where entryitems.dc = 'C' and entryitems.ledger_id IN($led_ids) and entries.date >= '$sdate' and entries.date <= '$tdate'$fund_where";
		$res = $db->query($c_sql)->getRowArray();
	} else {
		$res = 0;
	}
	if (!empty ($res['amount'])) {
		$cr_amount = $res['amount'];
	} else {
		$cr_amount = 0;
	}
	return $cr_amount;
}
function get_ledger_dr_amt_new_rightcode_triplezero($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$led_ids = get_ledger_ids_leftcode($id);
	$fund_where = '';
	// if (!empty ($fund_id))
		// $fund_where = " and entries.fund_id = '$fund_id'";
	if (!empty ($led_ids)) {
		$d_sql = "select sum(entryitems.amount) as amount 
					from entryitems 
					inner join entries on entries.id = entryitems. entry_id
					where entryitems.dc = 'D' and entryitems.ledger_id IN($led_ids) and entries.date >= '$sdate' and entries.date <= '$tdate'$fund_where";
		$res = $db->query($d_sql)->getRowArray();
	} else {
		$res = 0;
	}
	if (!empty ($res['amount'])) {
		$dr_amount = $res['amount'];
	} else {
		$dr_amount = 0;
	}
	return $dr_amount;
}
function get_ledger_amt_new_rightcode_triplezero_single($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$op_balance = 0;
	$ac_id = $db->table("ac_year")->where('status', 1)->get()->getRowArray();
	$builder = $db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id']);
	// if (!empty ($fund_id))
		// $builder->where('fund_id', $fund_id);
	$op_balance_new = $builder->get()->getRowArray();
	if ($op_balance_new['dr_amount'] == "0.00" || $op_balance_new['dr_amount'] == "") {
		$op_balance_amt -= $op_balance_new['cr_amount'];
	} else {
		$op_balance_amt += $op_balance_new['dr_amount'];
	}
	$op_balance += $op_balance_amt;
	$op_balance -= get_ledger_cr_amt_new_rightcode_triplezero_single($id, $sdate, $tdate);
	$op_balance += get_ledger_dr_amt_new_rightcode_triplezero_single($id, $sdate, $tdate);
	return $op_balance;
}
function get_ledger_cr_amt_new_rightcode_triplezero_single($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$fund_where = '';
	// if (!empty ($fund_id))
		// $fund_where = " and entries.fund_id = '$fund_id'";
	if (!empty ($id)) {
		$c_sql = "select sum(entryitems.amount) as amount 
				from entryitems 
				inner join entries on entries.id = entryitems. entry_id
				where entryitems.dc = 'C' and entryitems.ledger_id = '$id' and entries.date >= '$sdate' and entries.date <= '$tdate'$fund_where";
		$res = $db->query($c_sql)->getRowArray();
	} else {
		$res = 0;
	}
	if (!empty ($res['amount'])) {
		$cr_amount = $res['amount'];
	} else {
		$cr_amount = 0;
	}
	return $cr_amount;
}
function get_ledger_dr_amt_new_rightcode_triplezero_single($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$fund_where = '';
	// if (!empty ($fund_id))
		// $fund_where = " and entries.fund_id = '$fund_id'";
	if (!empty ($id)) {
		$d_sql = "select sum(entryitems.amount) as amount 
					from entryitems 
					inner join entries on entries.id = entryitems. entry_id
					where entryitems.dc = 'D' and entryitems.ledger_id = '$id' and entries.date >= '$sdate' and entries.date <= '$tdate'$fund_where";
		$res = $db->query($d_sql)->getRowArray();
	} else {
		$res = 0;
	}
	if (!empty ($res['amount'])) {
		$dr_amount = $res['amount'];
	} else {
		$dr_amount = 0;
	}
	return $dr_amount;
}
function get_ledger_ids_leftcode($id)
{
	$db = db_connect();
	$led_da = $db->table("ledgers")->where('id', $id)->get()->getRowArray();
	$left_code = $led_da['left_code'];
	if (!empty ($left_code)) {
		$ledger_ids = $db->query("select id from ledgers where id IN (select id from ledgers where left_code = $left_code)")->getResultArray();
	} else {
		$ledger_ids = array();
	}
	$array_ledgerids = array();
	if (count($ledger_ids) > 0) {
		foreach ($ledger_ids as $ledger_id) {
			$array_ledgerids[] = $ledger_id['id'];
		}
	}
	$ledger_ids_implode = implode(',', $array_ledgerids);
	return $ledger_ids_implode;
}

function get_profit_loss_subtotal($sub_val = array())
{
	$subtotal_income = 0;
	foreach ($sub_val as $sval) {
		foreach ($sval as $amt) {
			$subtotal_income += $amt;
		}
	}
	return $subtotal_income;
}
function get_consolidate_profit_loss_subtotal($sub_val = array())
{
	$subtotal_income = 0;
	foreach ($sub_val as $sval) {
		foreach ($sval as $amt) {
			$subtotal_income += array_sum($amt);
		}
	}
	return $subtotal_income;
}
function get_ledger_amt_new_rightcode_triplezero_subtotal_multiplejobcode($id, $sdate = '', $tdate = '')
{ // this function not required but subtotal without zero check plus and minus included
	$db = db_connect();
	$op_balance = 0;
	$ac_id = $db->table("ac_year")->where('status', 1)->get()->getRowArray();
	$op_balance_new = $db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
	if ($op_balance_new['dr_amount'] == "0.00" || $op_balance_new['dr_amount'] == "") {
		$op_balance_amt = $op_balance_new['cr_amount'];
	} else {
		$op_balance_amt = $op_balance_new['dr_amount'];
	}
	$op_balance += $op_balance_amt;
	$op_balance += get_ledger_cr_amt_new_rightcode_triplezero($id, $sdate, $tdate);
	$op_balance += get_ledger_cr_amt_new_rightcode_triplezero($id, $sdate, $tdate);
	return $op_balance;
}
function get_group_amt_new_rightcode_triplezero_subtotal_multiplejobcode($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	@$group_amt += get_ledgers_amt_new_rightcode_triplezero_subtotal_multiplejobcode($id, $sdate, $tdate);
	$groups = $db->table('groups')->where('parent_id', $id)->get()->getResult();
	if ($groups) {
		foreach ($groups as $r) {
			$group_amt += get_ledgers_amt_new_rightcode_triplezero_subtotal_multiplejobcode($r->id, $sdate, $tdate);
			get_group_amt_new_rightcode_triplezero_subtotal_multiplejobcode($r->id, $sdate, $tdate);
		}
	}
	return $group_amt;
}
// this function not required but subtotal without zero check plus and minus included
function get_ledgers_amt_new_rightcode_triplezero_subtotal_multiplejobcode($id, $sdate = '', $tdate = '')
{
	$db = db_connect();
	$op_balance = 0;
	$ac_id = $db->table("ac_year")->where('status', 1)->get()->getRowArray();
	$op_list = $db->table('ledgers')->where('group_id', $id)->where('right_code', '000')->get()->getResult();
	if ($op_list) {
		foreach ($op_list as $op) {
			$op_balance_new = $db->table('ac_year_ledger_balance')->select('dr_amount,cr_amount')->where('ledger_id', $op->id)->where('ac_year_id', $ac_id['id'])->get()->getRowArray();
			if ($op_balance_new['dr_amount'] == "0.00" || $op_balance_new['dr_amount'] == "") {
				$op_balance_amt = $op_balance_new['cr_amount'];
			} else {
				$op_balance_amt = $op_balance_new['dr_amount'];
			}
			$op_balance += $op_balance_amt;
			$op_balance += get_ledgers_amt_new_rightcode_triplezero($op->id, $sdate, $tdate);
			$op_balance += get_ledgers_amt_new_rightcode_triplezero($op->id, $sdate, $tdate);
		}
	}
	return $op_balance;
}
function archanai_charts()
{
	$jan = archanai_monthwise_count($month = "01");
	$feb = archanai_monthwise_count($month = "02");
	$mar = archanai_monthwise_count($month = "03");
	$apr = archanai_monthwise_count($month = "04");
	$may = archanai_monthwise_count($month = "05");
	$jun = archanai_monthwise_count($month = "06");
	$jul = archanai_monthwise_count($month = "07");
	$aug = archanai_monthwise_count($month = "08");
	$sep = archanai_monthwise_count($month = "09");
	$oct = archanai_monthwise_count($month = "10");
	$nov = archanai_monthwise_count($month = "11");
	$dec = archanai_monthwise_count($month = "12");
	$rtn_arry = array($jan, $feb, $mar, $apr, $may, $jun, $jul, $aug, $sep, $oct, $nov, $dec);
	return json_encode($rtn_arry);
}
function archanai_monthwise_count($month)
{
	$db = db_connect();
	$current_year = date('Y');
	$archanai_charts = $db->query("SELECT COUNT(id) AS count FROM archanai_booking where YEAR(date) = $current_year AND MONTH(date) = $month ")->getResultArray();
	if (count($archanai_charts) > 0) {
		$countdata = intVal($archanai_charts[0]['count']);
	} else {
		$countdata = 0;
	}
	return $countdata;
}
// function hallbooking_charts()
// {
// 	$jan = hallbooking_monthwise_count($month = "01");
// 	$feb = hallbooking_monthwise_count($month = "02");
// 	$mar = hallbooking_monthwise_count($month = "03");
// 	$apr = hallbooking_monthwise_count($month = "04");
// 	$may = hallbooking_monthwise_count($month = "05");
// 	$jun = hallbooking_monthwise_count($month = "06");
// 	$jul = hallbooking_monthwise_count($month = "07");
// 	$aug = hallbooking_monthwise_count($month = "08");
// 	$sep = hallbooking_monthwise_count($month = "09");
// 	$oct = hallbooking_monthwise_count($month = "10");
// 	$nov = hallbooking_monthwise_count($month = "11");
// 	$dec = hallbooking_monthwise_count($month = "12");
// 	$rtn_arry = array($jan, $feb, $mar, $apr, $may, $jun, $jul, $aug, $sep, $oct, $nov, $dec);
// 	return json_encode($rtn_arry);
// }


function archanai_booking_range($from_date = '', $to_date = '', $booking_type = '', $login_id = '')
{
	$db = db_connect();
	$archanai_data = array();
	$data_direct = array();
	$data_counter = array();
	$data_online = array();
	$where = '';
	if (!empty ($from_date))
		$where .= " and b.date >= '$from_date'";
	if (!empty ($to_date))
		$where .= " and b.date <= '$to_date'";
	if (!empty ($booking_type))
		$where .= " and b.paid_through = '$booking_type'";
	if (!empty ($login_id))
		$where .= " and b.entry_by in(" . implode(',', $login_id) . ")";
	$query = $db->query("select l.name as counter_name, b.date, a.archanai_id, a.archanai_booking_id, c.id as payment_gateway_data_id, 
	sum(a.quantity) as qty, sum(a.total_amount) as amt, sum(a.total_commision) as comm, 
	count(a.archanai_id) as cnt, b.paid_through, (case when b.paid_through = 'DIRECT' then b.payment_mode 
	else c.pay_method end) as payment_mode from archanai_booking_details a left join archanai_booking b on b.id = a.archanai_booking_id 
	left join archanai_payment_gateway_datas c on c.archanai_booking_id = b.id left join login l on b.entry_by = l.id 
	where (b.payment_status not in(1,3) or b.payment_status is NULL)
	$where group by payment_mode, b.paid_through, a.archanai_id having count(a.archanai_id) > 0 order by b.date asc");
	$res2 = $query->getResultArray();
	if (count($res2)) {
		foreach ($res2 as $row) {
			$paymentname = '';
			$total = $row['amt'] + $row['comm'];
			$aname = $db->table('archanai')->where('id', $row['archanai_id'])->get()->getRowArray();
			if ($row['paid_through'] == 'DIRECT') {
				$payment_mode = $db->table('payment_mode')->where('id', $row['payment_mode'])->get()->getRowArray();
				$paymentname = $payment_mode['name'];
				$payment_id = $payment_mode['id'];
			} else {
				$payment_mode = $db->table('archanai_payment_gateway_datas')->select('payment_mode.id as payment_mode_id, payment_mode.name as payment_name')->join('payment_mode', 'payment_mode.id = archanai_payment_gateway_datas.payment_mode')->where('archanai_payment_gateway_datas.id', $row['payment_gateway_data_id'])->get()->getRowArray();
				if(!empty($payment_mode['payment_name'])){
					$paymentname = $payment_mode['payment_name'];
					$payment_id = $payment_mode['payment_mode_id'];
				}else{
					$paymentname = $row['payment_mode'];
					$payment_id = 0;
				}

			}

			$archanai_data[] = array(
				"name_in_english" => $aname['name_eng'],
				"name_in_tamil" => $aname['name_tamil'],
				"paymentmode" => $paymentname,
				"payment_mode_id" => $payment_id,
				"archanai_id" => $row['archanai_id'],
				"counter_name" => $row['counter_name'],
				"paid_through" => $row['paid_through'],
				"date" => $row['date'],
				"qty" => $row['qty'],
				"amount" => $total
			);
		}
	}
	return $archanai_data;
}






// function hallbooking_monthwise_count($month)
// {
// 	$db = db_connect();
// 	$current_year = date('Y');
// 	//$hallbooking_charts = $db->query("SELECT COUNT(id) AS count FROM hall_booking where YEAR(booking_date) = $current_year AND MONTH(booking_date) = $month ")->getResultArray();
// 	if (count($hallbooking_charts) > 0) {
// 		$countdata = intVal($hallbooking_charts[0]['count']);
// 	} else {
// 		$countdata = 0;
// 	}
// 	return $countdata;
// }
function send_mail_with_content($to_mail, $message = array(), $subject, $temple_title = "")
{
	$email = \Config\Services::email();
	$to_mails = $to_mail;
	$mail_count = count($to_mails);
	for ($i = 0; $i < $mail_count; $i++) {
		$mail_id = TRIM($to_mails[$i]);
		//echo $mail_id;
		$email->setTo($mail_id);
		$email->setFrom('templetest@grasp.com.my', $temple_title);
		$email->setSubject($subject);
		$email->setMessage($message);
		$email->send();
	}
}
function qrcode_generation($qr_id, $url, $height = 190, $width = 190)
{
	if (!empty ($qr_id)) {
		$qr_url = "https://chart.googleapis.com/chart?cht=qr&chl=" . $url . "?id=" . $qr_id . "&chs=" . $width . "x" . $height . "&chld=L|0";
		return $qr_url;
	}

}
function get_checklist_availablity($date, $id)
{
	$db = db_connect();
	$res = $db->table("hall_booking")->select("id, name")->where("booking_date", $date)->where("status<>", 3)->get()->getResultArray();
	$data_time = array();
	$i = 0;  //echo '<pre>';
	foreach ($res as $r) {
		$ds = $db->table("hall_booking_service_details")->select("checklist_id")->where("hall_booking_id", $r['id'])->get()->getResultArray();
		foreach ($ds as $rr) {
			if (!empty ($rr)) {
				$data_time[] = $rr['checklist_id'];
			}
		}
	}
	$tot_checklists = $db->table('checklist')
		->join('booking_addonn_service', 'booking_addonn_service.service_id = checklist.service_id')
		->select('checklist.*')
		->where('booking_addonn_service.id', $id)
		->get()
		->getResultArray();
	$data = array("checklists" => $tot_checklists, "availabilty" => $data_time);
	return $data;
}


function daily_archanai_booking_withcurrentdate($current_date, $current_date_two, $booking_type = '', $login_id = '')
{
    
	$db = db_connect();
	$db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
	$archanai_data = array();
	$data_direct = array();
	$data_counter = array();
	$data_online = array();
	$where = '';
	if (!empty ($booking_type))
		$where .= " and b.paid_through = '$booking_type'";
	if (!empty ($login_id))
		$where .= " and b.entry_by = '$login_id'";
	$query = $db->query("select a.archanai_id, a.archanai_booking_id, sum(a.quantity) as qunty, sum(a.total_amount) as amt, sum(a.total_commision) as comm, count(a.archanai_id) as cnt, b.paid_through, (case when b.paid_through = 'DIRECT' then b.payment_mode else c.pay_method end) as payment_mode from archanai_booking_details as a left join archanai_booking as b on b.id = a.archanai_booking_id left join archanai_payment_gateway_datas as c on c.archanai_booking_id = b.id where b.date >= '$current_date' and b.date <= '$current_date_two' $where and (b.payment_status not in(1,3) or b.payment_status is NULL) group by payment_mode, b.paid_through, a.archanai_id having count(a.archanai_id) > 0");
	
	//echo "select a.archanai_id, a.archanai_booking_id, sum(a.quantity) as qunty, sum(a.total_amount) as amt, sum(a.total_commision) as comm, count(a.archanai_id) as cnt, b.paid_through, (case when b.paid_through = 'DIRECT' then b.payment_mode else c.pay_method end) as payment_mode from archanai_booking_details as a left join archanai_booking as b on b.id = a.archanai_booking_id left join archanai_payment_gateway_datas as c on c.archanai_booking_id = b.id where b.date >= '$current_date' and b.date <= '$current_date_two' $where and (b.payment_status not in(1,3) or b.payment_status is NULL) group by payment_mode, b.paid_through, a.archanai_id having count(a.archanai_id) > 0";
	//die("r");
	$res2 = $query->getResultArray();
	if (count($res2)) {
		foreach ($res2 as $row) {
			$paymentname = '';
			$total = $row['amt'] + $row['comm'];
			$aname = $db->table('archanai')->where('id', $row['archanai_id'])->get()->getRowArray();
			if ($row['paid_through'] == 'DIRECT') {
				$payment_mode = $db->table('payment_mode')->where('id', $row['payment_mode'])->get()->getRowArray();
				$paymentname = $payment_mode['name'];
			} else {
				if ($row['payment_mode'] == "ipay_merch_qr") {
					$paymentname = "QR PAYMENT";
				} elseif ($row['payment_mode'] == "ipay_merch_online") {
					$paymentname = "ONLINE PAYMENT";
				} elseif ($row['payment_mode'] == "cash") {
					$paymentname = "CASH";
				}
				$paymentname = $row['payment_mode'];
			}

			$archanai_data[] = array(
				"name_in_english" => $aname['name_eng'],
				"name_in_tamil" => $aname['name_tamil'],
				"groupname" => $aname['groupname'],
				"paymentmode" => $paymentname,
				"paid_through" => $row['paid_through'],
				"qty" => $row['qunty'],
				"amount" => $total
			);
		}
	}
	return $archanai_data;
}

function daily_group_deity_archanai_booking_withcurrentdate($current_date, $current_date_two, $booking_type = '', $login_id = '')
{
	$db = db_connect();
	$archanai_data = array();
	$data_direct = array();
	$data_counter = array();
	$data_online = array();
	$where = '';
	// if (!empty($booking_type))
	// 	$where .= " and b.paid_through = '$booking_type'";
	// if (!empty($login_id))
	// 	$where .= " and b.entry_by = '$login_id'";
	// $query = $db->query("select a.archanai_id, a.archanai_booking_id, a.deity_id, ad.name as deity_name, ag.id as group_id, aa.groupname, aa.name_eng, aa.name_tamil, sum(a.quantity) as qunty, sum(b.discount_amount) as discount_amount, sum(a.total_amount) as amt, sum(a.total_commision) as comm, count(a.archanai_id) as cnt, b.paid_through, c.pay_method as payment_mode from archanai_booking_details a left join archanai_booking b on b.id = a.archanai_booking_id left join archanai_payment_gateway_datas c on c.archanai_booking_id = b.id left join archanai aa on aa.id = a.archanai_id left join archanai_group ag on ag.name = aa.groupname left join archanai_diety ad on ad.id = a.deity_id where b.date >= '$current_date' and b.date <= '$current_date_two'$where and (b.payment_status not in(1,3) or b.payment_status is NULL) group by c.pay_method, a.deity_id, b.paid_through, a.archanai_id, ag.id having count(a.archanai_id) > 0 order by ag.order_no ASC, c.pay_method ASC");
	if (!empty($booking_type)) {
        $where .= " AND (CASE WHEN b.entry_by = 25 THEN 'KIOSK' ELSE b.paid_through END) = '$booking_type'";
    }
    if (!empty($login_id)) {
        $where .= " AND b.entry_by = '$login_id'";
    }
	$query = $db->query("
        SELECT a.archanai_id, 
               a.archanai_booking_id, 
               a.deity_id, 
               ad.name AS deity_name, 
               ag.id AS group_id, 
               aa.groupname, 
               aa.name_eng, 
               aa.name_tamil, 
               SUM(a.quantity) AS qunty, 
               SUM(b.discount_amount) AS discount_amount, 
               SUM(a.total_amount) AS amt, 
               SUM(a.total_commision) AS comm, 
               COUNT(a.archanai_id) AS cnt, 
               (CASE WHEN b.entry_by = 25 THEN 'KIOSK' ELSE b.paid_through END) AS paid_through, 
               (CASE WHEN b.paid_through = 'DIRECT' THEN b.payment_mode ELSE c.pay_method END) AS payment_mode 
        FROM archanai_booking_details a 
        LEFT JOIN archanai_booking b ON b.id = a.archanai_booking_id 
        LEFT JOIN archanai_payment_gateway_datas c ON c.archanai_booking_id = b.id 
        LEFT JOIN archanai aa ON aa.id = a.archanai_id 
        LEFT JOIN archanai_group ag ON ag.name = aa.groupname 
        LEFT JOIN archanai_diety ad ON ad.id = a.deity_id 
        WHERE b.date >= '$current_date' 
          AND b.date <= '$current_date_two' 
          AND (b.payment_status NOT IN (1,3) OR b.payment_status IS NULL) 
          $where
        GROUP BY  paid_through,c.pay_method, a.deity_id, a.archanai_id, ag.id 
        HAVING COUNT(a.archanai_id) > 0  
        ORDER BY  paid_through ASC, ag.order_no ASC, c.pay_method ASC
    ");

	$res2 = $query->getResultArray();
	if (count($res2)) {
		foreach ($res2 as $row) {
			$paymentname = '';
			$total = $row['amt'] + $row['comm'];
			if ($row['paid_through'] == 'DIRECT') {
				$payment_mode = $db->table('payment_mode')->where('id', $row['payment_mode'])->get()->getRowArray();
				$paymentname = $payment_mode['name'];
			} else {
				$paymentname = $row['payment_mode'];
			}
			// $archanai_data[$row['group_id']]['title'] = $row['groupname'];
			// $archanai_data[$row['group_id']]['deities'][$row['diety_id']] = array(
			// 	"name_in_english" => $row['name_eng'],
			// 	"name_in_tamil" => $row['name_tamil'],
			// 	"paymentmode" => $paymentname,
			// 	"paid_through" => $row['paid_through'],
			// 	"qty" => $row['qunty'],
			// 	"amount" => $total,
			// 	"discount_amount" => $row['discount_amount']
			// );

			// Group structure
			if (!isset($archanai_data[$row['group_id']])) {
				$archanai_data[$row['group_id']] = [
					'title' => $row['groupname'],
					'deities' => []
				];
			}
	
			// Deity structure
			if (!isset($archanai_data[$row['group_id']]['deities'][$row['deity_id']])) {
				$archanai_data[$row['group_id']]['deities'][$row['deity_id']] = [
					'deity_name' => $row['deity_name'],
					'products' => []
				];
			}
	
			// Product data
			$archanai_data[$row['group_id']]['deities'][$row['deity_id']]['products'][] = [
				"name_in_english" => $row['name_eng'],
				"name_in_tamil" => $row['name_tamil'],
				"paymentmode" => $paymentname,
				"paid_through" => $row['paid_through'],
				"qty" => $row['qunty'],
				"amount" => $total,
				"discount_amount" => $row['discount_amount']
			];
		}
	}
	return $archanai_data;
}
function daily_group_archanai_booking_withcurrentdate_old($current_date, $current_date_two, $booking_type = '', $login_id = '')
{
	$db = db_connect();
	$archanai_data = array();
	$data_direct = array();
	$data_counter = array();
	$data_online = array();
	$where = '';
	// if (!empty($booking_type))
	// 	$where .= " and b.paid_through = '$booking_type'";
	if (!empty($login_id))
		$where .= " and b.entry_by = '$login_id'";
	// $query = $db->query("select a.archanai_id, a.archanai_booking_id, ag.id as group_id, aa.groupname, aa.name_eng, aa.name_tamil, sum(a.quantity) as qunty, sum(a.total_amount) as amt, sum(a.total_commision) as comm, count(a.archanai_id) as cnt, b.paid_through, (case when b.paid_through = 'DIRECT' then b.payment_mode else c.pay_method end) as payment_mode from archanai_booking_details a left join archanai_booking b on b.id = a.archanai_booking_id left join archanai_payment_gateway_datas c on c.archanai_booking_id = b.id left join archanai aa on aa.id = a.archanai_id left join archanai_group ag on ag.name = aa.groupname where b.date >= '$current_date' and b.date <= '$current_date_two'$where and (b.payment_status not in(1,3) or b.payment_status is NULL) group by payment_mode, b.paid_through, a.archanai_id, ag.id having count(a.archanai_id) > 0 order by ag.order_no ASC");
	$query = $db->query("select a.archanai_id, a.archanai_booking_id, ag.id as group_id, aa.groupname, aa.name_eng, aa.name_tamil, sum(a.quantity) as qunty, sum(b.amount) as amt, sum(a.total_commision) as comm, count(a.archanai_id) as cnt, (case when b.entry_by = 25 then 'KIOSK' else b.paid_through end) as paid_through, (case when b.paid_through = 'DIRECT' then b.payment_mode else c.pay_method end) as payment_mode from archanai_booking_details a left join archanai_booking b on b.id = a.archanai_booking_id left join archanai_payment_gateway_datas c on c.archanai_booking_id = b.id left join archanai aa on aa.id = a.archanai_id left join archanai_group ag on ag.name = aa.groupname where b.date >= '$current_date' and b.date <= '$current_date_two'$where and (b.payment_status not in(1,3) or b.payment_status is NULL) group by payment_mode, b.paid_through, a.archanai_id, ag.id having count(a.archanai_id) > 0 " . (!empty($booking_type) ? "HAVING paid_through = '$booking_type'" : "") . "  order by ag.order_no ASC");

	$res2 = $query->getResultArray();
	if (count($res2)) {
		foreach ($res2 as $row) {
			$paymentname = '';
			$total = $row['amt'] + $row['comm'];
			if ($row['paid_through'] == 'DIRECT') {
				$payment_mode = $db->table('payment_mode')->where('id', $row['payment_mode'])->get()->getRowArray();
				$paymentname = $payment_mode['name'];
			} else {
				// if ($row['payment_mode'] == "qr") {
				// 	$paymentname = "QR PAYMENT";
				// } elseif ($row['payment_mode'] == "online") {
				// 	$paymentname = "ONLINE PAYMENT";
				// } elseif ($row['payment_mode'] == "cash") {
				// 	$paymentname = "CASH";
				// }
				$paymentname = $row['payment_mode'];
			}
			$archanai_data[$row['group_id']]['title'] = $row['groupname'];
			$archanai_data[$row['group_id']]['data'][] = array(
				"name_in_english" => $row['name_eng'],
				"name_in_tamil" => $row['name_tamil'],
				"paymentmode" => $paymentname,
				"paid_through" => $row['paid_through'],
				"qty" => $row['qunty'],
				"amount" => $total
			);
		}
	}
	return $archanai_data;
}

function daily_diety_archanai_booking_withcurrentdate($current_date, $current_date_two, $booking_type = '', $login_id = '')
{
	$db = db_connect();
	$archanai_data = array();
	$data_direct = array();
	$data_counter = array();
	$data_online = array();
	$where = '';
	if (!empty ($booking_type))
		$where .= " and b.paid_through = '$booking_type'";
	if (!empty ($login_id))
		$where .= " and b.entry_by = '$login_id'";
	$query = $db->query("select a.archanai_id, a.archanai_booking_id, a.diety_id, ad.name as diety_name, sum(a.quantity) as qunty, sum(a.total_amount) as amt, sum(a.total_commision) as comm, count(a.archanai_id) as cnt, b.paid_through, (case when b.paid_through = 'DIRECT' then b.payment_mode else c.pay_method end) as payment_mode from archanai_booking_details a left join archanai_booking b on b.id = a.archanai_booking_id left join archanai_payment_gateway_datas c on c.archanai_booking_id = b.id left join archanai_diety ad on ad.id = a.diety_id where b.date >= '$current_date' and b.date <= '$current_date_two' $where and (b.payment_status not in(1,3) or b.payment_status is NULL) group by payment_mode, b.paid_through, a.archanai_id, a.diety_id having count(a.archanai_id) > 0 order by a.diety_id");
	$res2 = $query->getResultArray();
	if (count($res2)) {
		foreach ($res2 as $row) {
			$paymentname = '';
			$total = $row['amt'] + $row['comm'];
			$aname = $db->table('archanai')->where('id', $row['archanai_id'])->get()->getRowArray();
			if ($row['paid_through'] == 'DIRECT') {
				$payment_mode = $db->table('payment_mode')->where('id', $row['payment_mode'])->get()->getRowArray();
				$paymentname = $payment_mode['name'];
			} else {
				if ($row['payment_mode'] == "ipay_merch_qr") {
					$paymentname = "QR PAYMENT";
				} elseif ($row['payment_mode'] == "ipay_merch_online") {
					$paymentname = "ONLINE PAYMENT";
				} elseif ($row['payment_mode'] == "cash") {
					$paymentname = "CASH";
				}

			}
			$archanai_data[$row['diety_id']]['title'] = $row['diety_name'];
			$archanai_data[$row['diety_id']]['data'][] = array(
				"name_in_english" => $aname['name_eng'],
				"name_in_tamil" => $aname['name_tamil'],
				"paymentmode" => $paymentname,
				"paid_through" => $row['paid_through'],
				"qty" => $row['qunty'],
				"amount" => $total
			);
		}
	}
	return $archanai_data;
}
function daily_kattalai_archanai_withcurrentdate($current_date, $current_date_two, $booking_type = '', $login_id = '')
{
	$db = db_connect();

	$builder = $db->table("kattalai_archanai_booking as kab")
		->join('payment_mode as pm', 'pm.id = kab.payment_mode', 'left')
		// ->join('kattalai_archanai_deity_details as kadd', 'kadd.booking_id = kab.id', 'left')
		// ->join('archanai_diety as ad', 'ad.id = kadd.deity_id', 'left')
		->join('kattalai_archanai_payment_gateway_datas as kapgd', 'kapgd.booking_id = kab.id', 'left')
		->select("(SELECT GROUP_CONCAT(ad.name SEPARATOR ', ') 
					FROM kattalai_archanai_deity_details d 
					JOIN archanai_diety ad ON ad.id = d.deity_id 
					WHERE d.booking_id = kab.id) as deity_name")
		->select("kab.id as booking_id, kab.name, kab.amount as amount, 
					(SELECT kapd.amount 
					FROM kattalai_archanai_pay_details kapd 
					WHERE kapd.booking_id = kab.id 
					ORDER BY kapd.id ASC LIMIT 1) as paidamount, 
					kab.payment_type, kab.daytype, 
					(CASE WHEN kab.booking_through = 'DIRECT' THEN pm.name ELSE kapgd.pay_method END) as paymentmode, 
					kab.booking_through")
		->where("DATE_FORMAT(kab.date, '%Y-%m-%d') >=", $current_date)
		->where("DATE_FORMAT(kab.date, '%Y-%m-%d') <=", $current_date_two)
		->groupStart()
		->where("kab.payment_status !=", 3)
		->orWhere('kab.payment_status IS NULL')
		->groupEnd();


	if (!empty($booking_type)) {
		$builder->where("kab.booking_through", $booking_type);
	}

	if (!empty($login_id)) {
		$builder->where("kab.added_by", $login_id);
	}

	$kattalai_archanai_data = $builder->get()->getResultArray();

	foreach ($kattalai_archanai_data as $key => $ubayam) {
		$detailBuilder = $db->table('kattalai_archanai_booking as kab')
			->join('kattalai_archanai as ka', 'ka.id = kab.archanai_type_id')
			->join('ledgers as l', 'l.id = ka.ledger_id', 'left')
			->select('ka.name_eng as package_name, l.name as ledger_name, concat(l.left_code, "-", l.right_code) as ledger_code, kab.no_of_days as quantity, kab.amount as unit_amount')
			->where('kab.id', $ubayam['booking_id']);

		$products = $detailBuilder->get()->getResultArray();
		$kattalai_archanai_data[$key]['products'] = $products;  // Nest the products data under each annathanam
	}

	return $kattalai_archanai_data;
}
function daily_annathanam_withcurrentdate($current_date, $current_date_two, $booking_type = '', $login_id = '') {
    $db = db_connect();
    
    $builder = $db->table("annathanam_new as an")
        ->join('payment_mode as pm', 'pm.id = an.payment_mode', 'left')
        ->join('annathanam_payment_gateway_datas as apgd', 'apgd.annathanam_booking_id = an.id', 'left')
        ->select("an.id as annathanam_id, an.amount as amount, an.slot_time as time, (SELECT abpd.amount 
					FROM annathanam_booked_pay_details abpd 
					WHERE abpd.annathanam_id = an.id 
					ORDER BY abpd.id ASC LIMIT 1) as paidamount, an.name as customer_name, an.payment_type, (case when an.booking_through = 'DIRECT' then pm.name else apgd.pay_method end) as paymentmode, an.booking_through")
		->Where('an.for_ubayam !=', 1)
        ->where("DATE_FORMAT(an.date, '%Y-%m-%d') >=", $current_date)
        ->where("DATE_FORMAT(an.date, '%Y-%m-%d') <=", $current_date_two)
		->groupBy('an.id')
        ->groupStart()
        ->where("an.payment_status !=", 3)
        ->orWhere('an.payment_status IS NULL')
        ->groupEnd();
    
    if (!empty($booking_type)) {
        $builder->where("an.booking_through", $booking_type);
    }
    if (!empty($login_id)) {
        $builder->where("an.added_by", $login_id);
    }
    
    $annathanam_data = $builder->get()->getResultArray();

    foreach ($annathanam_data as $key => $annathanam) {
        $detailBuilder = $db->table('annathanam_new as an')
            ->join('annathanam_packages as ap', 'ap.id = an.package_id')
            ->join('ledgers as l', 'l.id = ap.ledger_id', 'left')
            ->select('ap.name_eng as package_name, l.name as ledger_name, concat(l.left_code, "-", l.right_code) as ledger_code, an.no_of_pax as quantity, an.amount as amount')
            ->where('an.id', $annathanam['annathanam_id']);
        
        $products = $detailBuilder->get()->getResultArray();
        $annathanam_data[$key]['products'] = $products;  // Nest the products data under each annathanam
    }
    
    return $annathanam_data;
}


function daily_product_offering_withcurrentdate($current_date, $current_date_two, $booking_type = '', $login_id = '') {
    $db = db_connect();
    
    $builder = $db->table("product_offering_detail as pod")
        ->join('product_offering as po', 'po.id = pod.pro_off_id', 'left')
		->join('product_category as pc', 'pc.id = pod.product_id', 'left')
        ->join('offering_category as oc', 'oc.id = pod.offering_id', 'left')
        ->select("po.name as customer_name, oc.name as category_name, pc.name as product_name, pod.grams, pod.value, oc.name as paymentmode")
        ->where("DATE_FORMAT(po.date, '%Y-%m-%d') >=", $current_date)
        ->where("DATE_FORMAT(po.date, '%Y-%m-%d') <=", $current_date_two);
        
    if (!empty($booking_type)) {
        $builder->where("po.paid_through", $booking_type);
    }
    if (!empty($login_id)) {
        $builder->where("po.added_by", $login_id);
    }
    
    $prasadam_data = $builder->get()->getResultArray();

    // foreach ($prasadam_data as $key => $prasadam) {
    //     $detailBuilder = $db->table('prasadam_booking_details as pbd')
    //         ->join('prasadam_setting as ps', 'ps.id = pbd.prasadam_id')
    //         ->join('ledgers as l', 'l.id = ps.ledger_id', 'left')
    //         ->select('ps.name_eng as package_name, l.name as ledger_name, concat(l.left_code, "-", l.right_code) as ledger_code, pbd.quantity, pbd.total_amount as amount')
    //         ->where('pbd.prasadam_booking_id', $prasadam['prasadam_id']);
        
    //     $products = $detailBuilder->get()->getResultArray();
    //     $prasadam_data[$key]['products'] = $products;  // Nest the products data under each prasadam
    // }
    
    return $prasadam_data;
}



function daily_hall_booking_withcurrentdate($current_date, $current_date_two, $booking_type = '', $login_id = '') {
    $db = db_connect();
    
    $builder = $db->table("templebooking as tb")
		->join('booked_pay_details as bpd', 'bpd.booking_id = tb.id', 'left')
		->join('payment_mode as pm', 'pm.id = bpd.payment_mode_id', 'left')	
        ->select("tb.id as templebooking_id, tb.total_amount as amount, tb.booking_date as date, (SELECT bpd.amount 
				FROM booked_pay_details bpd 
				WHERE bpd.booking_id = tb.id 
				AND bpd.is_repayment = 0 
				ORDER BY bpd.id ASC LIMIT 1) as paidamount, (SELECT bdd.amount
                FROM booked_deposit_details bdd 
                WHERE bdd.booking_id = tb.id) as deposit, tb.name as customer_name, tb.payment_type, pm.name as paymentmode, tb.booking_through")
		->where('tb.booking_type', 1)
		->where('tb.booking_status', 1)
        ->where("DATE_FORMAT(tb.entry_date, '%Y-%m-%d') >=", $current_date)
        ->where("DATE_FORMAT(tb.entry_date, '%Y-%m-%d') <=", $current_date_two)
		->groupBy('tb.id')
        ->groupStart()
        ->where("tb.payment_status !=", 3)
        ->orWhere('tb.payment_status IS NULL')
        ->groupEnd();
    
    if (!empty($booking_type)) {
        $builder->where("tb.booking_through", $booking_type);
    }
    // if (!empty($login_id)) {
    //     $builder->where("an.added_by", $login_id);
    // }
    
    $ubayam_data = $builder->get()->getResultArray();

    foreach ($ubayam_data as $key => $ubayam) {
        $detailBuilder = $db->table('templebooking as tb')
            ->join('booked_packages as bp', 'bp.booking_id = tb.id')
            ->join('ledgers as l', 'l.id = bp.ledger_id', 'left')
            ->select('bp.name as package_name, l.name as ledger_name, concat(l.left_code, "-", l.right_code) as ledger_code, bp.quantity as quantity, tb.amount as amount')
            ->where('tb.id', $ubayam['templebooking_id']);
        
        $products = $detailBuilder->get()->getResultArray();
        $ubayam_data[$key]['products'] = $products;  // Nest the products data under each annathanam
    }
    
    return $ubayam_data;
}

function daily_ubayam_withcurrentdate($current_date, $current_date_two, $booking_type = '', $login_id = '') {
    $db = db_connect();
    
    $builder = $db->table("templebooking as tb")
		->join('booked_pay_details as bpd', 'bpd.booking_id = tb.id', 'left')
		->join('payment_mode as pm', 'pm.id = bpd.payment_mode_id', 'left')
        ->select("tb.id as templebooking_id, tb.amount as amount, tb.booking_date as date, (SELECT bpd.amount 
					FROM booked_pay_details bpd 
					WHERE bpd.booking_id = tb.id 
					AND bpd.is_repayment = 0
					ORDER BY bpd.id ASC LIMIT 1) as paidamount, tb.name as customer_name, tb.payment_type, pm.name as paymentmode, tb.booking_through")
		->where('tb.booking_type', 2)
		->where('tb.booking_status', 1)
        ->where("DATE_FORMAT(tb.entry_date, '%Y-%m-%d') >=", $current_date)
        ->where("DATE_FORMAT(tb.entry_date, '%Y-%m-%d') <=", $current_date_two)
        ->groupStart()
        ->where("tb.payment_status !=", 3)
        ->orWhere('tb.payment_status IS NULL')
        ->groupEnd();
    
    if (!empty($booking_type)) {
        $builder->where("tb.booking_through", $booking_type);
    }
    // if (!empty($login_id)) {
    //     $builder->where("an.added_by", $login_id);
    // }
    
    $ubayam_data = $builder->get()->getResultArray();

    foreach ($ubayam_data as $key => $ubayam) {
        $detailBuilder = $db->table('templebooking as tb')
            ->join('booked_packages as bp', 'bp.booking_id = tb.id')
            ->join('ledgers as l', 'l.id = bp.ledger_id', 'left')
            ->select('bp.name as package_name, l.name as ledger_name, concat(l.left_code, "-", l.right_code) as ledger_code, bp.quantity as quantity, tb.amount as amount')
            ->where('tb.id', $ubayam['templebooking_id']);
        
        $products = $detailBuilder->get()->getResultArray();
        $ubayam_data[$key]['products'] = $products;  // Nest the products data under each annathanam
    }
    
    return $ubayam_data;
}

function daily_donation_withcurrentdate($current_date, $current_date_two, $booking_type = '', $login_id = '')
{
	$db = db_connect();
	$builder = $db->table("donation as d")
		->join('donation_setting as ds', 'ds.id = d.pay_for')
		->join('payment_mode as pm', 'pm.id = d.payment_mode', 'left')
		->join('donation_payment_gateway_datas as dpgd', 'dpgd.donation_booking_id = d.id', 'left')
		->select("d.amount as paidamount, ds.name as package_name,d.name as person_name,(case when d.paid_through = 'DIRECT' then pm.name else dpgd.pay_method end) as paymentmode, (case when d.added_by = 25 then 'KIOSK' else d.paid_through end) as paid_through");
	if (!empty ($login_id))
		$builder->where("d.added_by", $login_id);
	// if (!empty ($booking_type))
	// 	$builder->where("d.paid_through", $booking_type);
	if (!empty($booking_type)) {
		$builder->having("paid_through", $booking_type);
	}
	$donation_data = $builder->where("DATE_FORMAT(d.date, '%Y-%m-%d') >=", $current_date)
		->where("DATE_FORMAT(d.date, '%Y-%m-%d') <=", $current_date_two)
		->groupStart()
		->whereNotIn("d.payment_status", array(1, 3))
		->Orwhere('d.payment_status IS NULL')
		->groupEnd()
		->groupBy('d.id')
		->get()
		->getResultArray();
	return $donation_data;
}

function daily_prasadam_withcurrentdate($current_date, $current_date_two, $booking_type = '', $login_id = '') {
    $db = db_connect();
    
    $builder = $db->table("prasadam as p")
        ->join('payment_mode as pm', 'pm.id = p.payment_mode', 'left')
        ->join('prasadam_payment_gateway_datas as ppgd', 'ppgd.prasadam_id = p.id', 'left')
        ->select("p.id as prasadam_id, p.total_amount as amount, (SELECT pbpd.amount 
					FROM prasadam_booked_pay_details pbpd 
					WHERE pbpd.prasadam_id = p.id 
					AND pbpd.is_repayment = 0
					ORDER BY pbpd.id ASC LIMIT 1) as paidamount, p.customer_name, p.payment_type, ppgd.pay_method as paymentmode, p.paid_through")
		->where('p.booking_type', 0)
		->where("DATE_FORMAT(p.date, '%Y-%m-%d') >=", $current_date)
        ->where("DATE_FORMAT(p.date, '%Y-%m-%d') <=", $current_date_two)
        ->groupStart()
        ->where("p.payment_status !=", 3)
        ->orWhere('p.payment_status IS NULL')
        ->groupEnd();
    
    // if (!empty($booking_type)) {
    //     $builder->where("p.paid_through", $booking_type);
    // }
	if (!empty($booking_type)) {
		$builder->having("paid_through", $booking_type);
	}
    // if (!empty($login_id)) {
    //     $builder->where("p.added_by", $login_id);
    // }
    
    $prasadam_data = $builder->get()->getResultArray();

    foreach ($prasadam_data as $key => $prasadam) {
        $detailBuilder = $db->table('prasadam_booking_details as pbd')
            ->join('prasadam_setting as ps', 'ps.id = pbd.prasadam_id')
            ->join('ledgers as l', 'l.id = ps.ledger_id', 'left')
            ->select('ps.name_eng as package_name, l.name as ledger_name, concat(l.left_code, "-", l.right_code) as ledger_code, pbd.quantity, pbd.total_amount as amount')
            ->where('pbd.prasadam_booking_id', $prasadam['prasadam_id']);
        
        $products = $detailBuilder->get()->getResultArray();
        $prasadam_data[$key]['products'] = $products;  
    }
    
    return $prasadam_data;
}

function daily_payment_voucher_withcurrentdate($current_date, $current_date_two, $booking_type = '', $login_id = '')
{
	$db = db_connect();
	$builder = $db->table("entries as e")
		->join('entryitems as ei', 'ei.entry_id = e.id')
		->select("e.dr_total as paidamount, e.id as booking_id, e.payment as paymentmode,e.paid_through, e.paid_to, e.narration,ei.details");
	if (!empty($login_id))
		$builder->where("e.entry_by", $login_id);
	if (!empty($booking_type))
		$builder->where("e.paid_through", $booking_type);
	$payment_voucher_data = $builder->where("DATE_FORMAT(e.date, '%Y-%m-%d') >=", $current_date)
		->where("DATE_FORMAT(e.date, '%Y-%m-%d') <=", $current_date_two)
		//->where('e.entrytype_id',2)
		->where('e.inv_id IS NULL')
		->groupBy('ei.entry_id')
		->get()
		->getResultArray();
		//$db->getLastQuery();
	return $payment_voucher_data;
}
function daily_repayment_data_withcurrentdate($current_date, $current_date_two, $booking_type = '', $login_id = '') {
    $db = db_connect();

    $prasadamBuilder = $db->table("prasadam as p")
        ->join('prasadam_booked_pay_details as pbpd', 'pbpd.prasadam_id = p.id', 'inner')
        ->join('payment_mode as pm', 'pm.id = p.payment_mode', 'left')
        ->join('prasadam_payment_gateway_datas as ppgd', 'ppgd.prasadam_id = p.id', 'left')
        ->select("p.id as id, p.date, p.customer_name, p.total_amount as amount, p.paid_amount as paidamount, pbpd.amount as repaid_amount, pbpd.paid_through, pbpd.payment_mode_title as paymentmode, 'Prasadam' as type")
        ->where("pbpd.is_repayment", 1)
        ->where("DATE_FORMAT(pbpd.paid_date, '%Y-%m-%d') >=", $current_date)
        ->where("DATE_FORMAT(pbpd.paid_date, '%Y-%m-%d') <=", $current_date_two);
        // ->groupStart()
        // ->where("p.payment_status !=", 3)
        // ->orWhere('p.payment_status IS NULL')
        // ->groupEnd();

    if (!empty($booking_type)) {
        $prasadamBuilder->where("pbpd.paid_through", $booking_type);
    }
    $prasadam_data = $prasadamBuilder->get()->getResultArray();

	$annathanamBuilder = $db->table("annathanam_new as an")
        ->join('annathanam_booked_pay_details as abpd', 'abpd.annathanam_id = an.id', 'inner')
        ->join('payment_mode as pm', 'pm.id = an.payment_mode', 'left')
        ->join('annathanam_payment_gateway_datas as apgd', 'apgd.annathanam_booking_id = an.id', 'left')
        ->select("an.id as id, an.date, an.name as customer_name, an.amount as amount, an.paid_amount as paidamount, abpd.amount as repaid_amount, abpd.payment_mode_title as paymentmode, abpd.paid_through, 'Annathanam' as type")
        ->where("abpd.is_repayment", 1)
		->Where('an.for_ubayam !=', 1)
        ->where("DATE_FORMAT(abpd.paid_date, '%Y-%m-%d') >=", $current_date)
        ->where("DATE_FORMAT(abpd.paid_date, '%Y-%m-%d') <=", $current_date_two);
        // ->groupStart()
        // ->where("an.payment_status !=", 3)
        // ->orWhere('an.payment_status IS NULL')
        // ->groupEnd();

    if (!empty($booking_type)) {
        $annathanamBuilder->where("an.booking_through", $booking_type);
    }
    $annathanam_data = $annathanamBuilder->get()->getResultArray();

	$ubayambuilder = $db->table("templebooking as tb")
		->join('booked_pay_details as bpd', 'bpd.booking_id = tb.id', 'left')
		->join('payment_mode as pm', 'pm.id = bpd.payment_mode_id', 'left')
        ->select("tb.id as templebooking_id, tb.amount as amount, tb.booking_date as date, tb.paid_amount as paidamount, bpd.paid_through, bpd.amount as repaid_amount, tb.name as customer_name, tb.payment_type, bpd.payment_mode_title as paymentmode, tb.booking_through, 'Ubayam' as type")
		->where("bpd.is_repayment", 1)
		->where('bpd.booking_type', 2)
		->where('bpd.pay_status', 2)
        ->where("DATE_FORMAT(bpd.paid_date, '%Y-%m-%d') >=", $current_date)
        ->where("DATE_FORMAT(bpd.paid_date, '%Y-%m-%d') <=", $current_date_two);
        // ->groupStart()
        // ->where("tb.payment_status !=", 3)
        // ->orWhere('tb.payment_status IS NULL')
        // ->groupEnd();
    
    if (!empty($booking_type)) {
        $ubayambuilder->where("bpd.paid_through", $booking_type);
    }
	$ubayam_data = $ubayambuilder->get()->getResultArray();


	$hallbuilder = $db->table("templebooking as tb")
		->join('booked_pay_details as bpd', 'bpd.booking_id = tb.id', 'left')
        ->select("tb.id as templebooking_id, tb.amount as amount, tb.booking_date as date, tb.paid_amount as paidamount, bpd.paid_through, bpd.amount as repaid_amount, tb.name as customer_name, tb.payment_type, bpd.payment_mode_title as paymentmode, tb.booking_through, 'Hall Booking' as type")
		->where("bpd.is_repayment", 1)
		->where('bpd.booking_type', 1)
		->where('bpd.pay_status', 2)
        ->where("DATE_FORMAT(bpd.paid_date, '%Y-%m-%d') >=", $current_date)
        ->where("DATE_FORMAT(bpd.paid_date, '%Y-%m-%d') <=", $current_date_two);
        // ->groupStart()
        // ->where("tb.payment_status !=", 3)
        // ->orWhere('tb.payment_status IS NULL')
        // ->groupEnd();
    
    if (!empty($booking_type)) {
        $hallbuilder->where("bpd.paid_through", $booking_type);
    }
	$hall_data = $hallbuilder->get()->getResultArray();

    $combined_data = array_merge($hall_data, $ubayam_data, $prasadam_data, $annathanam_data);

    return $combined_data;
}

function fetch_payment_info_for_products($fromDate, $toDate) {
    $db = db_connect();

    $productsQuery = $db->query("SELECT id, name_eng, ledger_id FROM archanai");
    $products = $productsQuery->getResultArray();

    $paymodesQuery = $db->query("SELECT id, name, ledger_id FROM payment_mode WHERE status = 1 GROUP BY name");
    $paymodes = $paymodesQuery->getResultArray();

    $data = [
        'archanai_details' => [],
        'archanai_pay_total' => ['payMethodTotals' => [], 'grandTotal' => 0],
        'payment_modes' => [] 
    ];

    foreach ($paymodes as $paymode) {
        $data['payment_modes'][$paymode['id']] = [
            'name' => $paymode['name'],
            'ledger_id' => $paymode['ledger_id'],
            'total_amount' => 0,
            'total_quantity' => 0,
            'products' => []
        ];
    }

    foreach ($products as $product) {
        $productId = $product['id'];
        $productName = $product['name_eng'];
        $ledgerId = $product['ledger_id'];

        // Fetch ledger details
        $ledgerQuery = $db->query("SELECT name, left_code, right_code FROM ledgers WHERE id = ?", [$ledgerId]);
        $ledgerDetails = $ledgerQuery->getRowArray();

        $sql = "SELECT apgd.payment_mode AS pay_mode_id, SUM(abd.quantity) AS quantity, SUM(abd.amount * abd.quantity) AS total_amount, pm.name AS payment_mode_name
                FROM archanai_booking_details abd
                JOIN archanai_booking ab ON abd.archanai_booking_id = ab.id
                JOIN archanai_payment_gateway_datas apgd ON ab.id = apgd.archanai_booking_id
                JOIN payment_mode pm ON pm.id = apgd.payment_mode
                WHERE abd.archanai_id = ?
                  AND ab.payment_status = 2
                  AND ab.date BETWEEN ? AND ?
                GROUP BY apgd.payment_mode";

        $query = $db->query($sql, [$productId, $fromDate, $toDate]);
        $results = $query->getResultArray();

        $productData = [
            'name_eng' => $productName,
            'ledger_id' => $ledgerId,
            'ledger_name' => $ledgerDetails['name'],
            'ledger_left_code' => $ledgerDetails['left_code'],
            'ledger_right_code' => $ledgerDetails['right_code'],
            'payment_info' => [], 
            'total_per_product' => 0,
            'total_quantity' => 0,
        ];

        foreach ($results as $row) {
            $payModeId = $row['pay_mode_id'];
            $payMethod = $row['payment_mode_name'];
            $totalAmount = (float) $row['total_amount'];
            $quantity = (int) $row['quantity'];

            $productData['payment_info'][$payMethod] = [
                'amount' => $totalAmount,
                'quantity' => $quantity,
            ];

            if (!isset($data['archanai_pay_total']['payMethodTotals'][$payMethod])) {
				$data['archanai_pay_total']['payMethodTotals'][$payMethod] = [
					'amount' => $totalAmount,
					'quantity' => $quantity
				];
			} else {
				$data['archanai_pay_total']['payMethodTotals'][$payMethod]['amount'] += $totalAmount;
				$data['archanai_pay_total']['payMethodTotals'][$payMethod]['quantity'] += $quantity;
			}

            $productData['total_per_product'] += $totalAmount;
            $productData['total_quantity'] += $quantity;

            if (isset($data['payment_modes'][$payModeId])) {
                $data['payment_modes'][$payModeId]['total_amount'] += $totalAmount;
                $data['payment_modes'][$payModeId]['total_quantity'] += $quantity;
                $data['payment_modes'][$payModeId]['products'][] = [
                    'name_eng' => $productName,
                    'amount' => $totalAmount,
                    'quantity' => $quantity,
                ];
            }
        }

        $data['archanai_details'][] = $productData;
        $data['archanai_pay_total']['grandTotal'] += $productData['total_per_product'];
    }

    return $data;
}


function fetch_payment_info_for_products1($fromDate, $toDate) {
    $db = db_connect();

    $productsQuery = $db->query("SELECT id, name_eng, ledger_id FROM archanai");
    $products = $productsQuery->getResultArray();

	$paymodesQuery = $db->query("SELECT id, name, ledger_id FROM payment_mode WHERE status = 1");
    $paymodes = $paymodesQuery->getResultArray();

    $data = [
        'archanai_details' => [],
        'archanai_pay_total' => ['payMethodTotals' => [], 'grandTotal' => 0],
    ];

    foreach ($products as $product) {
        $productId = $product['id'];
        $productName = $product['name_eng'];
        $ledgerId = $product['ledger_id'];

        // Fetch ledger details
        $ledgerQuery = $db->query("SELECT name, left_code, right_code FROM ledgers WHERE id = ?", [$ledgerId]);
        $ledgerDetails = $ledgerQuery->getRowArray();

        $sql = "SELECT apgd.pay_method, SUM(abd.quantity) AS quantity, SUM(abd.amount * abd.quantity) AS total_amount, pm.name
                FROM archanai_booking_details abd
                JOIN archanai_booking ab ON abd.archanai_booking_id = ab.id
                JOIN archanai_payment_gateway_datas apgd ON ab.id = apgd.archanai_booking_id
				JOIN payment_mode pm ON pm.id = apgd.payment_mode
                WHERE abd.archanai_id = ?
                  AND ab.payment_status = 2
                  AND ab.date BETWEEN ? AND ?
                GROUP BY pm.name";

        $query = $db->query($sql, [$productId, $fromDate, $toDate]);
        $results = $query->getResultArray();

        $productData = [
            'name_eng' => $productName,
            'ledger_id' => $ledgerId,
            'ledger_name' => $ledgerDetails['name'],
            'ledger_left_code' => $ledgerDetails['left_code'],
            'ledger_right_code' => $ledgerDetails['right_code'],
            'payment_info' => [], 
            'total_per_product' => 0,
			'total_quantity' => 0,
        ];

        foreach ($results as $row) {
            $payMethod = $row['pay_method'];
            $totalAmount = (float) $row['total_amount'];
            $quantity = (int) $row['quantity'];

            $productData['payment_info'][$payMethod] = [
                'amount' => $totalAmount,
                'quantity' => $quantity,
            ];

            if (!isset($data['archanai_pay_total']['payMethodTotals'][$payMethod])) {
                $data['archanai_pay_total']['payMethodTotals'][$payMethod] = $totalAmount;
            } else {
                $data['archanai_pay_total']['payMethodTotals'][$payMethod] += $totalAmount;
            }

            $productData['total_per_product'] += $totalAmount;
			$productData['total_quantity'] += $quantity;
        }

        $data['archanai_details'][] = $productData;
        $data['archanai_pay_total']['grandTotal'] += $productData['total_per_product'];
    }

    return $results;
}

// function fetch_payment_info_for_products($fromDate, $toDate) {
//     $db = db_connect();

//     $productsQuery = $db->query("SELECT id, name_eng, ledger_id FROM archanai");
//     $products = $productsQuery->getResultArray();

//     $data = [
//         'archanai_details' => [],
//         'archanai_pay_total' => ['payMethodTotals' => [], 'grandTotal' => 0],
//     ];

//     foreach ($products as $product) {
//         $productId = $product['id'];
//         $productName = $product['name_eng'];
//         $ledgerId = $product['ledger_id'];

//         // Fetch ledger details
//         $ledgerQuery = $db->query("SELECT name, left_code, right_code FROM ledgers WHERE id = ?", [$ledgerId]);
//         $ledgerDetails = $ledgerQuery->getRowArray();

//         $sql = "SELECT apgd.pay_method, SUM(abd.amount * abd.quantity) AS total_amount
//                 FROM archanai_booking_details abd
//                 JOIN archanai_booking ab ON abd.archanai_booking_id = ab.id
//                 JOIN archanai_payment_gateway_datas apgd ON ab.id = apgd.archanai_booking_id
//                 WHERE abd.archanai_id = ?
//                   AND ab.payment_status = 2
//                   AND ab.date BETWEEN ? AND ?
//                 GROUP BY apgd.pay_method";

//         $query = $db->query($sql, [$productId, $fromDate, $toDate]);
//         $results = $query->getResultArray();

//         $productData = [
//             'name_eng' => $productName,
//             'ledger_id' => $ledgerId,
//             'ledger_name' => $ledgerDetails['name'],
//             'ledger_left_code' => $ledgerDetails['left_code'],
//             'ledger_right_code' => $ledgerDetails['right_code'],
//             'payment_info' => [],
//             'total_per_product' => 0, 
//         ];

//         foreach ($results as $row) {
//             $payMethod = $row['pay_method'];
//             $totalAmount = (float) $row['total_amount'];

//             $productData['payment_info'][$payMethod] = $totalAmount;

//             if (!isset($data['archanai_pay_total']['payMethodTotals'][$payMethod])) {
//                 $data['archanai_pay_total']['payMethodTotals'][$payMethod] = $totalAmount;
//             } else {
//                 $data['archanai_pay_total']['payMethodTotals'][$payMethod] += $totalAmount;
//             }

//             $productData['total_per_product'] += $totalAmount;
//         }

//         $data['archanai_details'][] = $productData;
//         $data['archanai_pay_total']['grandTotal'] += $productData['total_per_product'];
//     }

//     return $data;
// }

function whatsapp_aisensy($number, $message_params, $template_name = 'hall_whatsapp_api1', $media = array())
{
	$data = array();
	/* $templateParams = [
				"Naveen",
				"Marriage Event",
				"23 Dec 2023",
				"9:00am - 12:00am",
				"2200",
				"1500",
				"700"
			 ]; */
	$templateParams = $message_params;
	// $data['apiKey'] = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjY1Njg0MGI1M2Y0NmRlMGJlMWFmYmYzNyIsIm5hbWUiOiJHUkFTUCBTT0ZUV0FSRSBTT0xVVElPTlMiLCJhcHBOYW1lIjoiQWlTZW5zeSIsImNsaWVudElkIjoiNjU2ODQwYjQzZjQ2ZGUwYmUxYWZiZjMyIiwiYWN0aXZlUGxhbiI6IkJBU0lDX01PTlRITFkiLCJpYXQiOjE3MDEzMzExMjV9.FiR2rGZ_AAlhSfSJ08evlCHddlsjg8UQuH72sCWefx0';
	/* $data['apiKey'] = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjY1ZjgwY2ZkNjYxNmY1MGI5ZjMxYWJjOCIsIm5hbWUiOiJBUlVMTUlHVSBSQUpBTUFSSUFNTUFOIERFVkFTVEhBTkFNIiwiYXBwTmFtZSI6IkFpU2Vuc3kiLCJjbGllbnRJZCI6IjY1ZjgwY2ZkNjYxNmY1MGI5ZjMxYWJjMCIsImFjdGl2ZVBsYW4iOiJCQVNJQ19NT05USExZIiwiaWF0IjoxNzExMDkwMjk5fQ.BEn8LtNGomASZhxlQ2srvnBuDuv50VVxKnf2xkPhaBs';
	$data['campaignName'] = $template_name;
	$data['destination'] = $number;
	$data['userName'] = 'wbapi@rajamariammandevasthanam.com';
	$data['templateParams'] = $templateParams;
	if (!empty ($media))
		$data['media'] = $media;
	$url = 'https://backend.aisensy.com/campaign/t1/api/v2';
	$ch = curl_init($url);
	# Setup request to send json via POST.
	$payload = json_encode($data);
	// echo $payload;
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	# Return response instead of printing.
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	# Send request.
	$result = curl_exec($ch);
	curl_close($ch);
	# Print response.
	// echo "<pre>$result</pre>";
	return json_decode($result, true); */
	return true;
}
function sync_users_all_tag($data, $tag_id = 1)
{
	$db = db_connect();
	if (!empty ($data['mobile']) && !empty ($data['country_phone_code'])) {
		$user_datas = $db->table("users_all")->where('mobile', $data['mobile'])->get()->getResultArray();
		if (count($user_datas) > 0) {
			$user_id = $user_datas[0]['id'];
		} else {
			$db->table('users_all')->insert($data);
			$user_id = $db->insertID();
		}
		$tag_data = array('user_id' => $user_id, 'tag_id' => $tag_id);
		$db->table('user_tag_relation')->delete($tag_data);
		$db->table('user_tag_relation')->insert($tag_data);
	}

}
function loadstaffsalary($staffid,$paytypeid,$ded_month)
{
	$db = db_connect();
	$staff_id = $staffid;
	$paytype_id = $paytypeid;
	$month_advance_salary = 0;
	$emi_advance_salary = 0;
	$basic_pay_data = $db->table('staff')->select("*")->where('id', $staff_id)->get()->getRowArray();
	//if($paytype_id == 1){
		$deduction_month = date('Y-m',strtotime($ded_month));
		$advance_salary_monthly_data = $db->query("select sum(amount) as amount from advancesalary where staff_id = '$staff_id' and deduction_month = '$deduction_month' and type = 1 ")->getResultArray();
		if(count($advance_salary_monthly_data) > 0){
			if($advance_salary_monthly_data[0]['amount'] > 0){
				$month_advance_salary = $advance_salary_monthly_data[0]['amount'];
			}
			else { $month_advance_salary = 0; }
		}
		else{
			$month_advance_salary = 0;
		}
	//}
	//if($paytype_id == 2){
		$deduction_emi = date('Y-m-01',strtotime($ded_month));
		$advance_salary_emi_data = $db->query("select COALESCE(sum(amount),0) as amount,COALESCE(emi_count,0) as emi_count from advancesalary where staff_id = $staff_id and emi_start_month <= '$deduction_emi' and emi_end_month >= '$deduction_emi' and type = 2 ")->getResultArray();
		if(count($advance_salary_emi_data) > 0){
			if($advance_salary_emi_data[0]['amount'] > 0){
				$emi_advance_salary = $advance_salary_emi_data[0]['amount'];
			}
			else { $emi_advance_salary = 0; }
		}else {  $emi_advance_salary = 0; }
	//}
	$advance_sal = $month_advance_salary + $emi_advance_salary;
	//return $deduction_emi;
	if(!empty($basic_pay_data['basic_pay'])){
		if($basic_pay_data['staff_type'] == 1){ // malaysian
			$epf_amount = $basic_pay_data['epf_amount'];
			$socso_amount = $basic_pay_data['socso_amount'];
			$eis_amount = $basic_pay_data['eis_amount'];
			$allowance = $basic_pay_data['allowance'];
		}
		if($basic_pay_data['staff_type'] == 2){ //Foreigner
			$epf_amount = 0;
			$socso_amount = 0;
			$eis_amount = 0;
			$allowance = $basic_pay_data['allowance'];
		}
		$earning_amt = $allowance;
		$deduction_amt = $epf_amount + $socso_amount + $eis_amount;
		$eighty_per = $basic_pay_data['basic_pay'];
		$eight_earning_deduction = ($eighty_per + $earning_amt) - $deduction_amt;
		$remaing_amt = $eight_earning_deduction - $advance_sal;
	}
	else{
		$remaing_amt = 0;
	}
	return $remaing_amt;

}
function loademiamount($provision_amount,$emi_type,$amount,$pay_type)
{
	if($pay_type == 1){
		$re_amount 	= $amount;
	}
	if($pay_type == 2){
		if(!empty($emi_type)){
			if(!empty($provision_amount)){
				$chck_bf = (float)$amount + (float)$provision_amount;
				$fbf_m_a = $chck_bf / $emi_type;
				$re_amount 	= $fbf_m_a;
			}
			else{
				$chck_bf = (float)$amount;
				$fbf_m_a = $chck_bf / $emi_type;
				$re_amount 	= $fbf_m_a;
			}
		}else{
			$re_amount 	= $amount;
		}
	}
	return $re_amount;
}
// function get_three_level_in_group($code)
// {
// 	$db = db_connect();
// 	$groups = $db->table("groups")->select('*')->whereIn('code',$code)->get()->getResultArray();
// 	$group_array = array();
// 	foreach($groups as $group){
// 		$group_array[] = $group['id'];
// 	}

// 		$subgroups = $db->table("groups")->select('*')->whereIn('parent_id', $group_array)->get()->getResultArray();
// 		$subgroup_array = array();
// 		foreach($subgroups as $subgroup){
// 			$subgroup_array[] = $subgroup['id'];
// 		}
// 		$sub_subgroups = $db->table("groups")->select('*')->whereIn('parent_id', $subgroup_array)->get()->getResultArray();
// 		$sub_subgroup_array = array();
// 		foreach($sub_subgroups as $sub_subgroup){
// 			$sub_subgroup_array[] = $sub_subgroup['id'];
// 		}
// 		$combine_array = array_merge($group_array,$subgroup_array,$sub_subgroup_array);

// 	return $combine_array;
// }

function get_three_level_in_group($code)
{
	$db = db_connect();

	// Ensure $code is an array
	if (!is_array($code)) {
		$code = [$code];
	}

	// Fetch main groups
	$groups = $db->table("groups")->select('*')->whereIn('code', $code)->get();
	if (!$groups) {
		return []; // Return empty array if query fails
	}
	$groups = $groups->getResultArray();

	$group_array = [];
	foreach ($groups as $group) {
		$group_array[] = $group['id'];
	}

	// Fetch first-level subgroups
	if (empty($group_array)) {
		return []; // If no groups found, return empty array
	}
	$subgroups = $db->table("groups")->select('*')->whereIn('parent_id', $group_array)->get();
	if (!$subgroups) {
		return $group_array;
	}
	$subgroups = $subgroups->getResultArray();

	$subgroup_array = [];
	foreach ($subgroups as $subgroup) {
		$subgroup_array[] = $subgroup['id'];
	}

	// Fetch second-level subgroups
	if (empty($subgroup_array)) {
		return $group_array; // If no subgroups found, return current groups
	}
	$sub_subgroups = $db->table("groups")->select('*')->whereIn('parent_id', $subgroup_array)->get();
	if (!$sub_subgroups) {
		return array_merge($group_array, $subgroup_array);
	}
	$sub_subgroups = $sub_subgroups->getResultArray();

	$sub_subgroup_array = [];
	foreach ($sub_subgroups as $sub_subgroup) {
		$sub_subgroup_array[] = $sub_subgroup['id'];
	}

	// Merge all IDs
	return array_merge($group_array, $subgroup_array, $sub_subgroup_array);
}
function getMonthsInRange($startDate, $endDate)
{
	$months = array();
	while (strtotime($startDate) <= strtotime($endDate)) {
		$months[]['date'] = date('Y-m', strtotime($startDate));
		// Set date to 1 so that new month is returned as the month changes.
		$startDate = date('01 M Y', strtotime($startDate . '+ 1 month'));
	}
	return $months;
}
function cal_gregorian($yearmonth)
{
	$convert_date = $yearmonth."-14";
	$year = date("Y",strtotime($convert_date));
	$month = date("m",strtotime($convert_date));
	$day = cal_days_in_month(CAL_GREGORIAN,$month,$year);
	return $day;
}
function getMonthsInCount($fromdate,$todate){

	$date1 = $fromdate;
	$date2 = $todate;

	$ts1 = strtotime($date1);
	$ts2 = strtotime($date2);

	$year1 = date('Y', $ts1);
	$year2 = date('Y', $ts2);

	$month1 = date('m', $ts1);
	$month2 = date('m', $ts2);

	$diff = (($year2 - $year1) * 12) + ($month2 - $month1);

	return $diff+1;
}

if(!function_exists('changedateFormat')){
    function changedateFormat($format = 'd-m-Y', $originalDate){
        return date($format, strtotime($originalDate));
    }
}

if(!function_exists('booking_calendar_range_year')){
    function booking_calendar_range_year($maxyear){
		$current_date = date('Y-m-d');
		$end_date = date('Y-m-d', strtotime('+'.$maxyear.' years',strtotime($current_date)));
        return $end_date;
    }
}
if(!function_exists('get_overall_temple_block_dates')){
	function get_overall_temple_block_dates()
	{
		$db = db_connect();
		$val = array();
		$result = $db->table("overall_temple_block")->select("date")->get()->getResultArray();
		foreach($result as $row)
		{
			$val[] = date("d-m-Y", strtotime($row['date']));
		}
		$response = json_encode($val);
		return $response;
	}
}
if(!function_exists('history_of_balancing')){
	function history_of_balancing(){
		$db = db_connect();
		$financial_year = $db->table("ac_year")->where('status', 1)->get()->getRowArray();
		$ac_year_id = $financial_year['id'];
		$sdate = $financial_year['from_year_month'] . '-01';
		$edate = date('Y-m-t', strtotime($financial_year['to_year_month'] . '-01'));
		$result = $db->query("select sum(dr_total) as dr_total, sum(cr_total) as cr_total from(SELECT COALESCE(sum(if(dr_amount != '', dr_amount, 0)), 0) as dr_total, COALESCE(sum(if(cr_amount != '', cr_amount, 0)), 0) as cr_total FROM `ac_year_ledger_balance` where ac_year_id = $ac_year_id UNION ALL SELECT COALESCE(sum(if(dc='D', amount, 0)), 0) as dr_total, COALESCE(sum(if(dc='C', amount, 0)), 0) as cr_total FROM `entryitems` where entry_id in (select id from entries where date BETWEEN '$sdate' and '$edate')) a")->getRowArray();
		return $result;
	}
}

if(!function_exists('loop_general_ledger_statement')){
	function loop_general_ledger_statement($ledger, $fdate, $tdate, $booking_type = 'all')
    {
        $db = db_connect();
        
        // Get active accounting year
        $ac_year = $db->table("ac_year")->where('status', 1)->get()->getRowArray();
        if(!$ac_year) {
            return ['op_bal' => 0, 'cl_bal' => 0, 'data' => []];
        }
        
        // Get opening balance
        $op_balance = $db->table('ac_year_ledger_balance')
                        ->select('dr_amount, cr_amount')
                        ->where('ledger_id', $ledger)
                        ->where('ac_year_id', $ac_year['id'])
                        ->get()
                        ->getRowArray();
        
        $op_bal = 0;
        if($op_balance){
            if(empty($op_balance['dr_amount']) || $op_balance['dr_amount'] == "0.00"){
                $op_bal = -($op_balance['cr_amount'] ?? 0);
            } else {
                $op_bal = $op_balance['dr_amount'] ?? 0;
            }
        }
        
        // Calculate adjusted opening balance if start date is provided
        if(!empty($fdate)){
            $ydate = date('Y-m-d', strtotime($fdate . ' -1 day'));
            
            // Build query for opening balance adjustment
            $opsQuery = "SELECT 
                            SUM(CASE WHEN ei.dc = 'D' THEN ei.amount ELSE 0 END) as total_debit,
                            SUM(CASE WHEN ei.dc = 'C' THEN ei.amount ELSE 0 END) as total_credit
                        FROM entryitems ei
                        JOIN entries e ON e.id = ei.entry_id
                        WHERE ei.ledger_id = ?
                        AND e.date <= ?";
            
            $params = [$ledger, $ydate];
            
            // Add booking type filter
            if($booking_type !== 'all'){
                if($booking_type === 'manual'){
                    $opsQuery .= " AND (e.type IS NULL OR e.type = '' OR e.type = 0)";
                } else {
                    $opsQuery .= " AND e.type = ?";
                    $params[] = $booking_type;
                }
            }
            
            $opsResult = $db->query($opsQuery, $params)->getRowArray();
            
            if($opsResult){
                $op_bal += ($opsResult['total_debit'] ?? 0) - ($opsResult['total_credit'] ?? 0);
            }
        }
        
        // Main query - Optimized with single query
        $mainQuery = "SELECT 
                        e.id,
                        e.date,
                        e.entry_code,
                        e.narration,
                        e.type,
                        ei.amount,
                        ei.dc,
                        ei.ledger_id
                     FROM entries e
                     JOIN entryitems ei ON ei.entry_id = e.id
                     WHERE ei.ledger_id = ?";
        
        $params = [$ledger];
        
        // Add date filters
        if(!empty($fdate)){
            $mainQuery .= " AND e.date >= ?";
            $params[] = $fdate;
        }
        if(!empty($tdate)){
            $mainQuery .= " AND e.date <= ?";
            $params[] = $tdate;
        }
        
        // Add booking type filter
        if($booking_type !== 'all'){
            if($booking_type === 'manual'){
                $mainQuery .= " AND (e.type IS NULL OR e.type = '' OR e.type = 0)";
            } else {
                $mainQuery .= " AND e.type = ?";
                $params[] = $booking_type;
            }
        }
        
        $mainQuery .= " ORDER BY e.date ASC, e.id ASC";
        
        // Execute main query
        $entries = $db->query($mainQuery, $params)->getResultArray();
        
        // Get all unique entry IDs
        $entryIds = array_unique(array_column($entries, 'id'));
        
        // Batch load all related entry items and ledger names
        $otherLedgers = [];
        if(!empty($entryIds)){
            // Get all entry items for these entries
            $otherItemsQuery = "SELECT 
                                  ei.entry_id,
                                  ei.ledger_id,
                                  ei.dc,
                                  l.name as ledger_name
                               FROM entryitems ei
                               JOIN ledgers l ON l.id = ei.ledger_id
                               WHERE ei.entry_id IN (" . implode(',', $entryIds) . ")
                               AND ei.ledger_id != ?";
            
            $otherItems = $db->query($otherItemsQuery, [$ledger])->getResultArray();
            
            // Group by entry_id
            foreach($otherItems as $item){
                $otherLedgers[$item['entry_id']][] = $item;
            }
        }
        
        // Process data
        $data = [];
        $cu_debit = 0;
        $cu_credit = 0;
        $running_balance = $op_bal;
        
        // Booking type labels
        $bookingTypes = [
            1 => 'Ubayam',
            2 => 'Donation',
            3 => 'Archanai',
            8 => 'Hall Booking',
            10 => 'Prasadam',
            11 => 'Member Registration',
            12 => 'Annadhanam',
            13 => 'Advance Salary',
            14 => 'Payslip',
            18 => 'Sales Invoice',
            19 => 'Purchase Invoice'
        ];
        
        foreach($entries as $entry){
            // Get other ledgers for this entry
            $entryLedgers = $otherLedgers[$entry['id']] ?? [];
            $ledgerNames = [];
            
            foreach($entryLedgers as $el){
                $ledgerNames[] = $el['ledger_name'];
            }
            
            $ledger_display = implode(' / ', $ledgerNames);
            
            // Format amounts
            $amount = floatval($entry['amount']);
            
            if($entry['dc'] == 'D'){
                $debit = number_format($amount, 2, '.', ',');
                $debit_amount = $amount;
                $credit = '';
                $credit_amount = 0;
                $running_balance += $amount;
                $cu_debit += $amount;
            } else {
                $debit = '';
                $debit_amount = 0;
                $credit = number_format($amount, 2, '.', ',');
                $credit_amount = $amount;
                $running_balance -= $amount;
                $cu_credit += $amount;
            }
            
            // Add booking type to narration
            $narration = $entry['narration'];
            if(!empty($entry['type']) && isset($bookingTypes[$entry['type']])){
                $narration .= ' [' . $bookingTypes[$entry['type']] . ']';
            }
            
            $data[] = [
                'entry_id' => $entry['id'],
                'date' => $entry['date'],
                'entry_code' => $entry['entry_code'],
                'ledger' => $ledger_display,
                'narration' => $narration,
                'debit' => $debit,
                'debit_amount' => $debit_amount,
                'credit' => $credit,
                'credit_amount' => $credit_amount,
                'balance' => $running_balance
            ];
        }
        
        return [
            'op_bal' => $op_bal,
            'cl_bal' => $running_balance,
            'data' => $data
        ];
    }
	
}
function archanai_ticket_booking_range($from_date = '', $to_date = '', $booking_type = '', $login_id = '')
{
	$db = db_connect();
	$archanai_data = array();
	$data_direct = array();
	$data_counter = array();
	$data_online = array();
	$where = '';
	if (!empty ($from_date))
		$where .= " and b.date >= '$from_date'";
	if (!empty ($to_date))
		$where .= " and b.date <= '$to_date'";
	if (!empty ($booking_type))
		$where .= " and b.paid_through = '$booking_type'";
	if (!empty ($login_id))
		$where .= " and b.entry_by = '$login_id'";
	$query = $db->query("select * from archanai_booking order by id desc");
	$res2 = $query->getResultArray();
	if (count($res2)) {
		foreach ($res2 as $row) {

			$archanai_data[] = array(
				
				"date" => $row['date'],
				"ref_no" => $row['ref_no'],
				"amount" => $row['amount']
			);
		}
	}
	return $archanai_data;
}
if (!function_exists('get_today_tharpanam_count')) {
	function get_today_tharpanam_count($bookid, $arch_id, $arch_qty)
	{
		$str_count = 0;
		$end_count = 0;
		$db = db_connect();
		$tot_groups = $db->table('archanai_booking')->where('id', $bookid)->get()->getResultArray();
		if (count($tot_groups) > 0) {
			//$row = $tot_groups[0];
			$archanai_tharpanam_check = $db->table('archanai')->where('id', $arch_id)->where('archanai_category', 4)->get()->getResultArray();
			if (count($archanai_tharpanam_check) > 0) {
				$re_check_archanai_booking = $db->table('archanai_booking')
					->join('archanai_booking_details', 'archanai_booking_details.archanai_booking_id = archanai_booking.id')
					->select('MAX(tharpanam_start) as tharpanam_start,MAX(tharpanam_end) as tharpanam_end')
					->where('archanai_booking.sep_print', 1)
					->where('archanai_booking.date', date('Y-m-d'))
					->where('archanai_booking.payment_status', 2)
					->get()
					->getRowArray();

				if (!empty($re_check_archanai_booking['tharpanam_start'])) {
					$pre_str_count = $re_check_archanai_booking['tharpanam_end'] + 1;
				} else {
					$pre_str_count = $re_check_archanai_booking['tharpanam_start'] + 1;
				}

				$pre_end_count = $re_check_archanai_booking['tharpanam_end'] + $arch_qty;
				$str_count = $pre_str_count;
				$end_count = $pre_end_count;

			} else {
				$str_count = 0;
				$end_count = 0;
			}

		} else {
			$str_count = 0;
			$end_count = 0;
		}
		$data = array("start" => $str_count, "end" => $end_count);
		return $data;
	}
}