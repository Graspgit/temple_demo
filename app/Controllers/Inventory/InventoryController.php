<?php

namespace App\Controllers\Inventory;

use App\Controllers\BaseController;
use App\Models\PermissionModel;

class InventoryController extends BaseController
{
	function __construct(){
        parent:: __construct();
        helper('url');
		helper("common_helper");
		$this->model = new PermissionModel();
        if( ($this->session->get('login') ) == false && $this->session->get('role') != 1){
            $data['dn_msg'] = 'Please Login';
            return redirect()->to('/login');
		}
    }
	public function index()
    {
		$data['permission'] = $this->model->get_permission('archanai_setting');
        echo view('template/header');
		echo view('template/sidebar');
		echo view('inventory/dashboard', $data);
		echo view('template/footer');
    }

    public function dashboardStats()
    {
        
        
        // Get product statistics
        $totalProducts = $this->db->table('inv_products')
            ->where('deleted_at', null)
            ->countAllResults();
            
        $activeProducts = $this->db->table('inv_products')
            ->where('deleted_at', null)
            ->where('is_active', 1)
            ->countAllResults();
        
        // Get low stock items count
        $lowStockQuery = "SELECT COUNT(DISTINCT p.id) as count
                         FROM inv_products p
                         LEFT JOIN inv_stock s ON s.product_id = p.id
                         WHERE p.deleted_at IS NULL 
                         AND p.is_stockable = 1
                         AND p.reorder_level IS NOT NULL
                         AND (s.quantity IS NULL OR s.quantity <= p.reorder_level)";
        
        $lowStockResult = $this->db->query($lowStockQuery)->getRow();
        $lowStockItems = $lowStockResult ? $lowStockResult->count : 0;
        
        // Get out of stock count
        $outOfStockQuery = "SELECT COUNT(DISTINCT p.id) as count
                           FROM inv_products p
                           LEFT JOIN inv_stock s ON s.product_id = p.id
                           WHERE p.deleted_at IS NULL 
                           AND p.is_stockable = 1
                           AND (s.quantity IS NULL OR s.quantity = 0)";
        
        $outOfStockResult = $this->db->query($outOfStockQuery)->getRow();
        $outOfStock = $outOfStockResult ? $outOfStockResult->count : 0;
        
        // Get expiring soon count (next 7 days)
        $expiringSoonQuery = "SELECT COUNT(*) as count
                             FROM inv_stock_batches
                             WHERE expiry_date IS NOT NULL
                             AND expiry_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                             AND expiry_date >= CURDATE()
                             AND quantity > 0";
        
        $expiringSoonResult = $this->db->query($expiringSoonQuery)->getRow();
        $expiringSoon = $expiringSoonResult ? $expiringSoonResult->count : 0;
        
        // Get active warehouses
        $activeWarehouses = $this->db->table('inv_warehouses')
            ->where('deleted_at', null)
            ->where('is_active', 1)
            ->countAllResults();
        
        return $this->response->setJSON([
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'low_stock_items' => $lowStockItems,
            'out_of_stock' => $outOfStock,
            'expiring_soon' => $expiringSoon,
            'active_warehouses' => $activeWarehouses
        ]);
    }
    
    public function lowStock()
    {
        
        
        $query = "SELECT p.id as product_id, p.name as product_name, 
                        c.name as category, u.abbreviation as unit,
                        COALESCE(SUM(s.quantity), 0) as current_stock,
                        p.reorder_level
                 FROM inv_products p
                 JOIN inv_product_categories c ON c.id = p.category_id
                 JOIN inv_unit_of_measure u ON u.id = p.unit_of_measure_id
                 LEFT JOIN inv_stock s ON s.product_id = p.id
                 WHERE p.deleted_at IS NULL 
                 AND p.is_stockable = 1
                 AND p.reorder_level IS NOT NULL
                 GROUP BY p.id
                 HAVING current_stock <= p.reorder_level
                 ORDER BY current_stock ASC
                 LIMIT 10";
        
        $result = $this->db->query($query)->getResultArray();
        
        return $this->response->setJSON(['items' => $result]);
    }
    
    public function expiringItems()
    {
        
        
        $query = "SELECT b.*, p.name as product_name, u.abbreviation as unit,
                        DATEDIFF(b.expiry_date, CURDATE()) as days_left
                 FROM inv_stock_batches b
                 JOIN inv_products p ON p.id = b.product_id
                 JOIN inv_unit_of_measure u ON u.id = p.unit_of_measure_id
                 WHERE b.expiry_date IS NOT NULL
                 AND b.expiry_date >= CURDATE()
                 AND b.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                 AND b.quantity > 0
                 ORDER BY b.expiry_date ASC
                 LIMIT 10";
        
        $result = $this->db->query($query)->getResultArray();
        
        return $this->response->setJSON(['items' => $result]);
    }
    
    public function recentTransactions()
    {
        
        
        $query = "SELECT t.*, p.name as product_name, u.abbreviation as unit,
                        w.name as warehouse, 
                        CONCAT(usr.first_name, ' ', usr.last_name) as created_by
                 FROM inv_stock_transactions t
                 JOIN inv_stock_transaction_details d ON d.transaction_id = t.id
                 JOIN inv_products p ON p.id = d.product_id
                 JOIN inv_unit_of_measure u ON u.id = p.unit_of_measure_id
                 LEFT JOIN inv_warehouses w ON w.id = 
                    CASE 
                        WHEN t.transaction_type = 'IN' THEN t.to_warehouse_id
                        WHEN t.transaction_type = 'OUT' THEN t.from_warehouse_id
                        ELSE t.from_warehouse_id
                    END
                 LEFT JOIN users usr ON usr.id = t.created_by
                 WHERE t.deleted_at IS NULL
                 ORDER BY t.created_at DESC
                 LIMIT 20";
        
        $result = $this->db->query($query)->getResultArray();
        
        // Transform the data for display
        $transactions = [];
        foreach ($result as $row) {
            $transactions[] = [
                'date' => $row['transaction_date'],
                'transaction_number' => $row['transaction_number'],
                'type' => $row['transaction_type'],
                'product_name' => $row['product_name'],
                'quantity' => $row['quantity'],
                'unit' => $row['unit'],
                'warehouse' => $row['warehouse'] ?? 'N/A',
                'created_by' => $row['created_by'] ?? 'System'
            ];
        }
        
        return $this->response->setJSON(['transactions' => $transactions]);
    }
}