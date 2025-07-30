namespace App\Models;
use CodeIgniter\Model;

class GoodsReceiptItemModel extends Model
{
    protected $table = 'grn_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'grn_id', 'po_item_id', 'item_type', 'item_id', 'description', 'uom_id',
        'ordered_quantity', 'received_quantity', 'accepted_quantity', 'rejected_quantity',
        'unit_price', 'tax_rate', 'tax_amount', 'discount_rate', 'discount_amount',
        'total_amount', 'batch_number', 'expiry_date', 'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}