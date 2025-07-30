<?php
namespace App\Models;

use CodeIgniter\Model;
// 8. Marriage Registration Payments Model
class MarriageRegistrationPaymentsModel extends Model
{
    protected $table = 'marriage_registration_payments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'registration_id', 'payment_date', 'amount', 'payment_method',
        'reference_number', 'payment_details', 'received_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    public function getPaymentsByRegistration($registration_id)
    {
        $builder = $this->db->table($this->table . ' mrp');
        $builder->select('mrp.*, u.username as received_by_name');
        $builder->join('users u', 'mrp.received_by = u.id', 'left');
        $builder->where('mrp.registration_id', $registration_id);
        $builder->orderBy('mrp.payment_date', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getTotalPayments($registration_id)
    {
        return $this->selectSum('amount')
                   ->where('registration_id', $registration_id)
                   ->get()
                   ->getRow()
                   ->amount ?? 0;
    }

    public function getPaymentStats($start_date, $end_date)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            COUNT(*) as total_payments,
            SUM(amount) as total_amount,
            payment_method,
            COUNT(*) as method_count
        ');
        $builder->where('DATE(payment_date) >=', $start_date);
        $builder->where('DATE(payment_date) <=', $end_date);
        $builder->groupBy('payment_method');
        
        return $builder->get()->getResultArray();
    }
}
