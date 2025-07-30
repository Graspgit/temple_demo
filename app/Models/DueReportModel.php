<?php

namespace App\Models;

use CodeIgniter\Model;

class DueReportModel extends Model
{
    protected $table = 'invoice';
    protected $primaryKey = 'id';
    protected $allowedFields = [];

    public function getDueReport($filters = [])
    {
        $builder = $this->db->table('invoice i');
        
        $builder->select("
            CASE 
                WHEN i.invoice_type = 1 THEN 'Customer' 
                WHEN i.invoice_type = 2 THEN 'Supplier' 
            END as entity_type,
            CASE 
                WHEN i.invoice_type = 1 THEN c.customer_name 
                WHEN i.invoice_type = 2 THEN s.supplier_name 
            END as entity_name,
            CASE 
                WHEN i.invoice_type = 1 THEN c.customer_code 
                WHEN i.invoice_type = 2 THEN s.supplier_code 
            END as entity_code,
            i.invoice_no,
            i.date,
            i.grand_total,
            i.paid_amount,
            i.due_amount,
            DATEDIFF(CURDATE(), i.date) as days_overdue,
            i.invoice_type,
            i.customer_supplier_id,
            i.id as invoice_id
        ", false);
        
        $builder->join('customer c', 'c.id = i.customer_supplier_id AND i.invoice_type = 1', 'left');
        $builder->join('supplier s', 's.id = i.customer_supplier_id AND i.invoice_type = 2', 'left');
        
        // Filter by report type
        if (empty($filters['report_type']) || $filters['report_type'] == 'outstanding') {
            $builder->where('i.due_amount >', 0);
        } elseif ($filters['report_type'] == 'paid') {
            $builder->where('i.due_amount', 0);
        }
        
        // Filter by entity type
        if (!empty($filters['entity_type']) && $filters['entity_type'] != 'all') {
            if ($filters['entity_type'] == 'customer') {
                $builder->where('i.invoice_type', 1);
            } elseif ($filters['entity_type'] == 'supplier') {
                $builder->where('i.invoice_type', 2);
            }
        }
        
        // Filter by specific entity
        if (!empty($filters['entity_id'])) {
            $builder->where('i.customer_supplier_id', $filters['entity_id']);
        }
        
        // Date range filter
        if (!empty($filters['from_date'])) {
            $builder->where('i.date >=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $builder->where('i.date <=', $filters['to_date']);
        }
        
        $builder->orderBy('i.date', 'DESC');
        $builder->orderBy('entity_name', 'ASC');
        
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getDueSummary($filters = [])
    {
        // Customer dues summary
        $customerBuilder = $this->db->table('invoice i');
        $customerBuilder->select("
            'Customer' as entity_type,
            COUNT(*) as total_invoices,
            SUM(i.grand_total) as total_amount,
            SUM(i.paid_amount) as total_paid,
            SUM(i.due_amount) as total_due
        ", false);
        $customerBuilder->where('i.invoice_type', 1);
        
        // Apply filters for customer
        $this->applyFilters($customerBuilder, $filters, 'customer');
        
        $customerSummary = $customerBuilder->get()->getRowArray();
        
        // Supplier dues summary
        $supplierBuilder = $this->db->table('invoice i');
        $supplierBuilder->select("
            'Supplier' as entity_type,
            COUNT(*) as total_invoices,
            SUM(i.grand_total) as total_amount,
            SUM(i.paid_amount) as total_paid,
            SUM(i.due_amount) as total_due
        ", false);
        $supplierBuilder->where('i.invoice_type', 2);
        
        // Apply filters for supplier
        $this->applyFilters($supplierBuilder, $filters, 'supplier');
        
        $supplierSummary = $supplierBuilder->get()->getRowArray();
        
        return [
            'customer' => $customerSummary,
            'supplier' => $supplierSummary
        ];
    }

    private function applyFilters($builder, $filters, $entityType = null)
    {
        // Filter by report type
        if (!empty($filters['report_type'])) {
            if ($filters['report_type'] == 'outstanding') {
                $builder->where('i.due_amount >', 0);
            } elseif ($filters['report_type'] == 'paid') {
                $builder->where('i.due_amount', 0);
            }
        }
        
        // Filter by specific entity
        if (!empty($filters['entity_id'])) {
            if (($entityType == 'customer' && $filters['entity_type'] == 'customer') ||
                ($entityType == 'supplier' && $filters['entity_type'] == 'supplier') ||
                empty($entityType)) {
                $builder->where('i.customer_supplier_id', $filters['entity_id']);
            }
        }
        
        // Date range filter
        if (!empty($filters['from_date'])) {
            $builder->where('i.date >=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $builder->where('i.date <=', $filters['to_date']);
        }
    }

    public function getAgingReport($filters = [])
    {
        $builder = $this->db->table('invoice i');
        
        $builder->select("
            CASE 
                WHEN i.invoice_type = 1 THEN 'Customer'
                WHEN i.invoice_type = 2 THEN 'Supplier'
            END as entity_type,
            CASE 
                WHEN i.invoice_type = 1 THEN c.customer_name
                WHEN i.invoice_type = 2 THEN s.supplier_name
            END as entity_name,
            SUM(CASE WHEN DATEDIFF(CURDATE(), i.date) <= 30 THEN i.due_amount ELSE 0 END) as due_0_30,
            SUM(CASE WHEN DATEDIFF(CURDATE(), i.date) BETWEEN 31 AND 60 THEN i.due_amount ELSE 0 END) as due_31_60,
            SUM(CASE WHEN DATEDIFF(CURDATE(), i.date) BETWEEN 61 AND 90 THEN i.due_amount ELSE 0 END) as due_61_90,
            SUM(CASE WHEN DATEDIFF(CURDATE(), i.date) > 90 THEN i.due_amount ELSE 0 END) as due_over_90,
            SUM(i.due_amount) as total_due
        ", false);
        
        $builder->join('customer c', 'c.id = i.customer_supplier_id AND i.invoice_type = 1', 'left');
        $builder->join('supplier s', 's.id = i.customer_supplier_id AND i.invoice_type = 2', 'left');
        $builder->where('i.due_amount >', 0);
        
        // Apply filters
        if (!empty($filters['entity_type']) && $filters['entity_type'] != 'all') {
            if ($filters['entity_type'] == 'customer') {
                $builder->where('i.invoice_type', 1);
            } elseif ($filters['entity_type'] == 'supplier') {
                $builder->where('i.invoice_type', 2);
            }
        }
        
        $builder->groupBy(['i.invoice_type', 'i.customer_supplier_id']);
        $builder->having('total_due >', 0);
        $builder->orderBy('entity_type', 'ASC');
        $builder->orderBy('entity_name', 'ASC');
        
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getCustomerDues()
    {
        $builder = $this->db->table('invoice i');
        $builder->select('c.customer_name, SUM(i.due_amount) as total_due');
        $builder->join('customer c', 'c.id = i.customer_supplier_id');
        $builder->where('i.invoice_type', 1);
        $builder->where('i.due_amount >', 0);
        $builder->groupBy('i.customer_supplier_id');
        $builder->orderBy('total_due', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getSupplierDues()
    {
        $builder = $this->db->table('invoice i');
        $builder->select('s.supplier_name, SUM(i.due_amount) as total_due');
        $builder->join('supplier s', 's.id = i.customer_supplier_id');
        $builder->where('i.invoice_type', 2);
        $builder->where('i.due_amount >', 0);
        $builder->groupBy('i.customer_supplier_id');
        $builder->orderBy('total_due', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getReceiptPaymentHistory($invoiceId)
    {
        // First get invoice details to determine type
        $invoiceBuilder = $this->db->table('invoice');
        $invoiceBuilder->select('invoice_type');
        $invoiceBuilder->where('id', $invoiceId);
        $invoiceData = $invoiceBuilder->get()->getRowArray();
        
        if (!$invoiceData) {
            return [];
        }
        
        $builder = $this->db->table('entries e');
        $builder->select("
            e.id as entry_id,
            e.number as voucher_number,
            e.date as entry_date,
            e.narration,
            e.payment as payment_mode,
            e.paid_to,
            e.cheque_no,
            e.cheque_date,
            e.dr_total,
            e.cr_total,
            CASE 
                WHEN e.entrytype_id = 1 THEN 'Receipt'
                WHEN e.entrytype_id = 2 THEN 'Payment'
                WHEN e.entrytype_id = 3 THEN 'Contra'
                WHEN e.entrytype_id = 4 THEN 'Journal'
            END as entry_type
        ", false);
        
        // Remove the join with entryitems to avoid duplicates
        $builder->where('e.inv_id', $invoiceId);
        $builder->where('e.type IN (18, 19)'); // Only Sales Invoice (18) and Purchase Invoice (19)
        
        // Filter by entry type based on invoice type
        if ($invoiceData['invoice_type'] == 1) {
            // Sales Invoice - show only Receipt entries
            $builder->where('e.entrytype_id', 1);
        } else {
            // Purchase Invoice - show only Payment entries  
            $builder->where('e.entrytype_id', 2);
        }
        
        $builder->orderBy('e.date', 'DESC');
        $builder->orderBy('e.id', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getInvoiceDetails($invoiceId)
    {
        $builder = $this->db->table('invoice i');
        $builder->select("
            i.*,
            CASE 
                WHEN i.invoice_type = 1 THEN c.customer_name
                WHEN i.invoice_type = 2 THEN s.supplier_name
            END as entity_name,
            CASE 
                WHEN i.invoice_type = 1 THEN c.customer_code
                WHEN i.invoice_type = 2 THEN s.supplier_code
            END as entity_code,
            CASE 
                WHEN i.invoice_type = 1 THEN 'Customer'
                WHEN i.invoice_type = 2 THEN 'Supplier'
            END as entity_type
        ", false);
        
        $builder->join('customer c', 'c.id = i.customer_supplier_id AND i.invoice_type = 1', 'left');
        $builder->join('supplier s', 's.id = i.customer_supplier_id AND i.invoice_type = 2', 'left');
        $builder->where('i.id', $invoiceId);
        
        return $builder->get()->getRowArray();
    }
}