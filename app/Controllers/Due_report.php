<?php

namespace App\Controllers;

use App\Models\DueReportModel;
use App\Models\CustomerModel;
use App\Models\SupplierModel;
use App\Models\PermissionModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Due_report extends BaseController
{
    protected $dueReportModel;
    protected $customerModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->dueReportModel = new DueReportModel();
        $this->customerModel = new CustomerModel();
        $this->supplierModel = new SupplierModel();
        $this->model = new PermissionModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Due Report',
            'customers' => $this->customerModel->findAll(),
            'suppliers' => $this->supplierModel->findAll()
        ];

        $data['permission'] = $this->model->get_permission('archanai_setting');
        // Get filter parameters
        $data['report_type'] = $this->request->getGet('report_type') ?? 'all';
        $data['entity_type'] = $this->request->getGet('entity_type') ?? 'all';
        $data['entity_id'] = $this->request->getGet('entity_id') ?? '';
        $data['from_date'] = $this->request->getGet('from_date') ?? '';
        $data['to_date'] = $this->request->getGet('to_date') ?? '';

        // Initialize empty data
        $data['due_data'] = [];
        $data['summary'] = [];

        // Generate report if requested
        if ($this->request->getGet('generate_report')) {
            $filters = [
                'report_type' => $data['report_type'],
                'entity_type' => $data['entity_type'],
                'entity_id' => $data['entity_id'],
                'from_date' => $data['from_date'],
                'to_date' => $data['to_date']
            ];

            $data['due_data'] = $this->dueReportModel->getDueReport($filters);
            $data['summary'] = $this->dueReportModel->getDueSummary($filters);
        }

        echo view('template/header');
        echo view('template/sidebar');
        echo view('due_report/due_report', $data);
        echo view('template/footer');
    }

    public function exportExcel()
    {
        $filters = [
            'report_type' => $this->request->getGet('report_type'),
            'entity_type' => $this->request->getGet('entity_type'),
            'entity_id' => $this->request->getGet('entity_id'),
            'from_date' => $this->request->getGet('from_date'),
            'to_date' => $this->request->getGet('to_date')
        ];

        $dueData = $this->dueReportModel->getDueReport($filters);
        $summary = $this->dueReportModel->getDueSummary($filters);

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator("Grasp Software")
            ->setLastModifiedBy("Grasp Software")
            ->setTitle("Due Report")
            ->setSubject("Due Report")
            ->setDescription("Due amounts report for customers and suppliers");

        $currentRow = 1;

        // Company Header
        $sheet->setCellValue('A1', 'GRASP SOFTWARE');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $currentRow++;

        // Report Title
        $reportTitle = 'DUE REPORT';
        if ($filters['entity_type'] != 'all') {
            $reportTitle = strtoupper($filters['entity_type']) . ' DUE REPORT';
        }
        $sheet->setCellValue('A2', $reportTitle);
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $currentRow++;

        // Filter Information
        $currentRow++;
        $sheet->setCellValue('A' . $currentRow, 'Report Filters:');
        $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
        $currentRow++;

        $sheet->setCellValue('A' . $currentRow, 'Report Type:');
        $sheet->setCellValue('B' . $currentRow, ucfirst($filters['report_type']));
        $sheet->setCellValue('D' . $currentRow, 'Entity Type:');
        $sheet->setCellValue('E' . $currentRow, ucfirst($filters['entity_type']));
        $currentRow++;

        $fromDate = !empty($filters['from_date']) ? date('d-m-Y', strtotime($filters['from_date'])) : 'All';
        $toDate = !empty($filters['to_date']) ? date('d-m-Y', strtotime($filters['to_date'])) : 'All';
        $sheet->setCellValue('A' . $currentRow, 'From Date:');
        $sheet->setCellValue('B' . $currentRow, $fromDate);
        $sheet->setCellValue('D' . $currentRow, 'To Date:');
        $sheet->setCellValue('E' . $currentRow, $toDate);
        $currentRow++;

        // Generated Date
        $sheet->setCellValue('A' . $currentRow, 'Generated On:');
        $sheet->setCellValue('B' . $currentRow, date('d-m-Y H:i:s'));
        $currentRow += 2;

        // Summary Section
        $sheet->setCellValue('A' . $currentRow, 'SUMMARY:');
        $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
        $currentRow++;

        // Summary Headers
        $summaryHeaders = ['Entity Type', 'Total Invoices', 'Total Amount', 'Total Paid', 'Total Due'];
        $col = 'A';
        foreach ($summaryHeaders as $header) {
            $sheet->setCellValue($col . $currentRow, $header);
            $sheet->getStyle($col . $currentRow)->getFont()->setBold(true);
            $col++;
        }
        $currentRow++;

        // Summary Data
        if ($filters['entity_type'] == 'all' || $filters['entity_type'] == 'customer') {
            $sheet->setCellValue('A' . $currentRow, 'Customer');
            $sheet->setCellValue('B' . $currentRow, number_format($summary['customer']['total_invoices'] ?? 0));
            $sheet->setCellValue('C' . $currentRow, number_format($summary['customer']['total_amount'] ?? 0, 2));
            $sheet->setCellValue('D' . $currentRow, number_format($summary['customer']['total_paid'] ?? 0, 2));
            $sheet->setCellValue('E' . $currentRow, number_format($summary['customer']['total_due'] ?? 0, 2));
            $currentRow++;
        }

        if ($filters['entity_type'] == 'all' || $filters['entity_type'] == 'supplier') {
            $sheet->setCellValue('A' . $currentRow, 'Supplier');
            $sheet->setCellValue('B' . $currentRow, number_format($summary['supplier']['total_invoices'] ?? 0));
            $sheet->setCellValue('C' . $currentRow, number_format($summary['supplier']['total_amount'] ?? 0, 2));
            $sheet->setCellValue('D' . $currentRow, number_format($summary['supplier']['total_paid'] ?? 0, 2));
            $sheet->setCellValue('E' . $currentRow, number_format($summary['supplier']['total_due'] ?? 0, 2));
            $currentRow++;
        }

        $currentRow += 2;

        // Detailed Report Section
        $sheet->setCellValue('A' . $currentRow, 'DETAILED REPORT:');
        $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
        $currentRow++;

        // Data table headers
        $headers = [
            'A' => 'Entity Type',
            'B' => 'Name',
            'C' => 'Invoice No',
            'D' => 'Invoice Date',
            'E' => 'Grand Total',
            'F' => 'Paid Amount',
            'G' => 'Due Amount',
            'H' => 'Days Overdue'
        ];

        foreach ($headers as $col => $header) {
            $sheet->setCellValue($col . $currentRow, $header);
        }

        // Style data headers
        $sheet->getStyle('A' . $currentRow . ':H' . $currentRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $currentRow . ':H' . $currentRow)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCCCCCC');
        
        $currentRow++;

        // Add detailed data
        $totalGrandTotal = 0;
        $totalPaidAmount = 0;
        $totalDueAmount = 0;

        foreach ($dueData as $item) {
            $sheet->setCellValue('A' . $currentRow, $item['entity_type']);
            $sheet->setCellValue('B' . $currentRow, $item['entity_name'] . ' (' . $item['entity_code'] . ')');
            $sheet->setCellValue('C' . $currentRow, $item['invoice_no']);
            $sheet->setCellValue('D' . $currentRow, date('d-m-Y', strtotime($item['date'])));
            $sheet->setCellValue('E' . $currentRow, $item['grand_total']);
            $sheet->setCellValue('F' . $currentRow, $item['paid_amount']);
            $sheet->setCellValue('G' . $currentRow, $item['due_amount']);
            $sheet->setCellValue('H' . $currentRow, $item['days_overdue'] > 0 ? $item['days_overdue'] . ' days' : '-');

            $totalGrandTotal += $item['grand_total'];
            $totalPaidAmount += $item['paid_amount'];
            $totalDueAmount += $item['due_amount'];

            $currentRow++;
        }

        // Add totals row
        if (!empty($dueData)) {
            $sheet->setCellValue('A' . $currentRow, 'TOTAL:');
            $sheet->mergeCells('A' . $currentRow . ':D' . $currentRow);
            $sheet->setCellValue('E' . $currentRow, $totalGrandTotal);
            $sheet->setCellValue('F' . $currentRow, $totalPaidAmount);
            $sheet->setCellValue('G' . $currentRow, $totalDueAmount);
            
            // Style totals row
            $sheet->getStyle('A' . $currentRow . ':H' . $currentRow)->getFont()->setBold(true);
            $sheet->getStyle('A' . $currentRow . ':H' . $currentRow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFF0F0F0');
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(12);

        // Set number format for amount columns
        $lastRow = $currentRow;
        $sheet->getStyle('E1:G' . $lastRow)->getNumberFormat()
            ->setFormatCode('#,##0.00');

        // Set worksheet title
        $sheet->setTitle('Due Report');

        // Create writer and save
        $writer = new Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Due_Report_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function printReport()
    {
        $filters = [
            'report_type' => $this->request->getGet('report_type'),
            'entity_type' => $this->request->getGet('entity_type'),
            'entity_id' => $this->request->getGet('entity_id'),
            'from_date' => $this->request->getGet('from_date'),
            'to_date' => $this->request->getGet('to_date')
        ];

        $data = [
            'due_data' => $this->dueReportModel->getDueReport($filters),
            'summary' => $this->dueReportModel->getDueSummary($filters),
            'filters' => $filters,
            'title' => 'Due Report'
        ];

        echo view('due_report/due_report_print', $data);
    }

    public function getEntitiesByType()
    {
        $entityType = $this->request->getPost('entity_type');
        $entities = [];

        if ($entityType === 'customer') {
            $customers = $this->customerModel->findAll();
            foreach ($customers as $customer) {
                $entities[] = [
                    'id' => $customer['id'],
                    'name' => $customer['customer_name']
                ];
            }
        } elseif ($entityType === 'supplier') {
            $suppliers = $this->supplierModel->findAll();
            foreach ($suppliers as $supplier) {
                $entities[] = [
                    'id' => $supplier['id'],
                    'name' => $supplier['supplier_name']
                ];
            }
        }

        return $this->response->setJSON($entities);
    }

    public function getReceiptPaymentHistory($invoiceId)
    {
        $receiptPaymentHistory = $this->dueReportModel->getReceiptPaymentHistory($invoiceId);
        $invoiceData = $this->dueReportModel->getInvoiceDetails($invoiceId);
        
        $data = [
            'receipt_payment_history' => $receiptPaymentHistory,
            'invoice_data' => $invoiceData,
            'title' => ($invoiceData['invoice_type'] == 1) ? 'Receipt History' : 'Payment History'
        ];

        echo view('template/header');
        echo view('template/sidebar');
        echo view('due_report/receipt_payment_history', $data);
        echo view('template/footer');
    }

    public function getReceiptPaymentHistoryAjax($invoiceId)
    {
        $receiptPaymentHistory = $this->dueReportModel->getReceiptPaymentHistory($invoiceId);
        $invoiceData = $this->dueReportModel->getInvoiceDetails($invoiceId);
        
        $data = [
            'receipt_payment_history' => $receiptPaymentHistory,
            'invoice_data' => $invoiceData
        ];

        return $this->response->setJSON($data);
    }

    public function printReceiptPaymentHistory($invoiceId)
    {
        $receiptPaymentHistory = $this->dueReportModel->getReceiptPaymentHistory($invoiceId);
        $invoiceData = $this->dueReportModel->getInvoiceDetails($invoiceId);
        
        $data = [
            'receipt_payment_history' => $receiptPaymentHistory,
            'invoice_data' => $invoiceData,
            'title' => ($invoiceData['invoice_type'] == 1) ? 'Receipt History - Print' : 'Payment History - Print'
        ];

        echo view('due_report/receipt_payment_history_print', $data);
    }
}