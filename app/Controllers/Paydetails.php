<?php
namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Paydetails extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		helper('common');
		$this->model = new PermissionModel();
		if (($this->session->get('log_id')) == false && $this->session->get('role') != 1) {
			$data['dn_msg'] = 'Please Login';
			return redirect()->to('/login');
		}
		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
	}
	public function index()
	{
		if (!empty($_POST['paydetails_from_date']))
			$paydetails_from_date = $_POST['paydetails_from_date'];
		else
			$paydetails_from_date = date("Y-m-d");
		if (!empty($_POST['paydetails_to_date']))
			$paydetails_to_date = $_POST['paydetails_to_date'];
		else
			$paydetails_to_date = date("Y-m-d");

		$data['paydetails_from_date'] = $paydetails_from_date;
    	$data['paydetails_to_date'] = $paydetails_to_date;
		$data['payment_mode'] = $this->db->table('payment_mode')->select('name, MIN(id) as id, ledger_id')->where('status', 1)->groupBy('name')->orderby('menu_order', 'ASC')->get()->getResultArray();
		$data['archanai'] = fetch_payment_info_for_products($paydetails_from_date, $paydetails_to_date);

		echo view('template/header');
		echo view('template/sidebar');
		echo view('daily_closing/pay_details', $data);
		echo view('template/footer');
	}

	public function excel_paydetails_old() 
	{
		$paydetails_from_date = $this->request->getGet('paydetails_from_date');
    	$paydetails_to_date = $this->request->getGet('paydetails_to_date');
		$temp_details = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

		$payment_mode = $this->db->table('payment_mode')
			->select('name, MIN(id) as id, ledger_id')
			->where('status', 1)
			->groupBy('name')
			->orderBy('menu_order', 'ASC')
			->get()
			->getResultArray();
	
		$archanai = fetch_payment_info_for_products($paydetails_from_date, $paydetails_to_date);
	
		$fileName = "PayDetails_Report_" . $paydetails_from_date . "_to_" . $paydetails_to_date . ".xlsx";
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
	
		$headerStyle = [
			'font' => ['bold' => true],
			'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
		];
	
		$sheet->setCellValue('A1', $temp_details['name']);
		$sheet->mergeCells('A1:G1');
		$sheet->getStyle('A1:G1')->applyFromArray($headerStyle);
	
		// Column Headers
		$sheet->setCellValue('A2', 'S.No');
		$sheet->setCellValue('B2', 'Products');
		$colIndex = 3;
		foreach ($payment_mode as $mode) {
			$sheet->setCellValueByColumnAndRow($colIndex, 2, $mode['name'] . ' Quantity');
			$sheet->setCellValueByColumnAndRow($colIndex + 1, 2, $mode['name'] . ' Amount');
			$colIndex += 2;
		}
		$sheet->setCellValueByColumnAndRow($colIndex, 2, 'Total Quantity');
		$sheet->setCellValueByColumnAndRow($colIndex + 1, 2, 'Total Amount');
		$sheet->getStyle('A2:' . $sheet->getHighestColumn() . '2')->applyFromArray($headerStyle);
	
		// Populate Data
		$rows = 3;
		$s_no = 1;
		foreach ($archanai['archanai_details'] as $product) {
			$sheet->setCellValue('A' . $rows, $s_no++);
			$sheet->setCellValue('B' . $rows, $product['name_eng']);
	
			$colIndex = 3;
			foreach ($payment_mode as $mode) {
				$quantity = $product['payment_info'][$mode['name']]['quantity'] ?? 0;
				$amount = $product['payment_info'][$mode['name']]['amount'] ?? 0;
				$sheet->setCellValueByColumnAndRow($colIndex, $rows, $quantity);
				$sheet->setCellValueByColumnAndRow($colIndex + 1, $rows, $amount);
				$colIndex += 2;
			}
	
			$sheet->setCellValueByColumnAndRow($colIndex, $rows, $product['total_quantity']);
			$sheet->setCellValueByColumnAndRow($colIndex + 1, $rows, $product['total_per_product']);
			$rows++;
		}
	
		// Grand Total Row
		$sheet->setCellValue('A' . $rows, 'Grand Total');
		$sheet->mergeCells('A' . $rows . ':B' . $rows);
		$colIndex = 3;
		foreach ($payment_mode as $mode) {
			$quantityTotal = $archanai['archanai_pay_total']['payMethodTotals'][$mode['name']]['quantity'] ?? 0;
			$amountTotal = $archanai['archanai_pay_total']['payMethodTotals'][$mode['name']]['amount'] ?? 0;
			$sheet->setCellValueByColumnAndRow($colIndex, $rows, $quantityTotal);
			$sheet->setCellValueByColumnAndRow($colIndex + 1, $rows, $amountTotal);
			$colIndex += 2;
		}
		$sheet->setCellValueByColumnAndRow($colIndex, $rows, array_sum(array_column($archanai['archanai_details'], 'total_quantity')));
		$sheet->setCellValueByColumnAndRow($colIndex + 1, $rows, array_sum(array_column($archanai['archanai_details'], 'total_per_product')));
	
		// Save and Download
		$writer = new Xlsx($spreadsheet);
		$filePath = 'uploads/excel/' . $fileName;
		$writer->save($filePath);
	
		return $this->response->download($filePath, null)->setFileName($fileName);
	}
	public function excel_paydetails() 
	{
		$paydetails_from_date = $this->request->getGet('paydetails_from_date');
		$paydetails_to_date = $this->request->getGet('paydetails_to_date');
		$temp_details = $this->db->table('admin_profile')->where('id', 1)->get()->getRowArray();

		$payment_mode = $this->db->table('payment_mode')
			->select('name, MIN(id) as id, ledger_id')
			->where('status', 1)
			->groupBy('name')
			->orderBy('menu_order', 'ASC')
			->get()
			->getResultArray();

		$archanai = fetch_payment_info_for_products($paydetails_from_date, $paydetails_to_date);

		$fileName = "PayDetails_Report_" . $paydetails_from_date . "_to_" . $paydetails_to_date . ".xlsx";
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$headerStyle = [
			'font' => ['bold' => true],
			'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
		];

		$sheet->setCellValue('A1', $temp_details['name']);
		$sheet->mergeCells('A1:H1');
		$sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

		$dateText = "Date: " . date("d-m-Y", strtotime($paydetails_from_date)) . " - " . date("d-m-Y", strtotime($paydetails_to_date));
		$sheet->setCellValue('A2', $dateText);
		$sheet->mergeCells('A2:H2');
		$sheet->getStyle('A2:H2')->applyFromArray($headerStyle);

		$sheet->setCellValue('A3', 'S.No');
		$sheet->setCellValue('B3', 'Products');

		$colIndex = 3;
		foreach ($payment_mode as $mode) {
			$sheet->setCellValueByColumnAndRow($colIndex, 3, $mode['name'] . ' Quantity');
			$sheet->setCellValueByColumnAndRow($colIndex + 1, 3, $mode['name'] . ' Amount');
			$colIndex += 2;
		}

		$sheet->setCellValueByColumnAndRow($colIndex, 3, 'Total Quantity');
		$sheet->setCellValueByColumnAndRow($colIndex + 1, 3, 'Total Amount');
		$sheet->getStyle('A3:' . $sheet->getHighestColumn() . '3')->applyFromArray($headerStyle);

		$rows = 4;
		$s_no = 1;
		foreach ($archanai['archanai_details'] as $product) {
			$sheet->setCellValue('A' . $rows, $s_no++);
			$sheet->setCellValue('B' . $rows, $product['name_eng']);

			$colIndex = 3;
			foreach ($payment_mode as $mode) {
				$quantity = $product['payment_info'][$mode['name']]['quantity'] ?? 0;
				$amount = $product['payment_info'][$mode['name']]['amount'] ?? 0;
				$sheet->setCellValueByColumnAndRow($colIndex, $rows, $quantity);
				$sheet->setCellValueByColumnAndRow($colIndex + 1, $rows, number_format($amount, 2, '.', ','));
				$colIndex += 2;
			}

			$sheet->setCellValueByColumnAndRow($colIndex, $rows, $product['total_quantity'] ?? 0);
			$sheet->setCellValueByColumnAndRow($colIndex + 1, $rows, number_format($product['total_per_product'] ?? 0, 2, '.', ','));
			$rows++;
		}

		$sheet->setCellValue('A' . $rows, 'Grand Total');
		$sheet->mergeCells('A' . $rows . ':B' . $rows);
		$colIndex = 3;
		foreach ($payment_mode as $mode) {
			$quantityTotal = $archanai['archanai_pay_total']['payMethodTotals'][$mode['name']]['quantity'] ?? 0;
			$amountTotal = $archanai['archanai_pay_total']['payMethodTotals'][$mode['name']]['amount'] ?? 0;
			$sheet->setCellValueByColumnAndRow($colIndex, $rows, $quantityTotal);
			$sheet->setCellValueByColumnAndRow($colIndex + 1, $rows, number_format($amountTotal, 2, '.', ','));
			$colIndex += 2;
		}

		$totalQuantity = array_sum(array_column($archanai['archanai_details'], 'total_quantity')) ?? 0;
		$totalAmount = array_sum(array_column($archanai['archanai_details'], 'total_per_product')) ?? 0;
		$sheet->setCellValueByColumnAndRow($colIndex, $rows, $totalQuantity);
		$sheet->setCellValueByColumnAndRow($colIndex + 1, $rows, number_format($totalAmount, 2, '.', ','));

		$writer = new Xlsx($spreadsheet);
		$filePath = 'uploads/excel/' . $fileName;
		$writer->save($filePath);

		return $this->response->download($filePath, null)->setFileName($fileName);
	}
	
}
