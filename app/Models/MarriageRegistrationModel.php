<?php

namespace App\Models;

use CodeIgniter\Model;

// 1. Marriage Registration Model
class MarriageRegistrationModel extends Model
{
    protected $table = 'marriage_registrations';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'registration_number', 'registration_date', 'slot_id', 'venue_id', 'category_id',
        'bride_name', 'bride_name_tamil', 'bride_name_chinese', 'bride_name_hindi',
        'bride_dob', 'bride_age', 'bride_nationality', 'bride_ic_passport',
        'bride_father_name', 'bride_mother_name', 'bride_address', 'bride_phone',
        'bride_email', 'bride_religion', 'bride_occupation', 'bride_photo',
        'groom_name', 'groom_name_tamil', 'groom_name_chinese', 'groom_name_hindi',
        'groom_dob', 'groom_age', 'groom_nationality', 'groom_ic_passport',
        'groom_father_name', 'groom_mother_name', 'groom_address', 'groom_phone',
        'groom_email', 'groom_religion', 'groom_occupation', 'groom_photo',
        'witness1_name', 'witness1_ic', 'witness1_phone',
        'witness2_name', 'witness2_ic', 'witness2_phone',
        'special_requirements', 'guest_count', 'remarks',
        'total_amount', 'paid_amount', 'payment_status', 'registration_status',
        'created_by', 'updated_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getRegistrationsWithDetails($limit = null, $offset = 0)
    {
        $builder = $this->db->table($this->table . ' mr');
        $builder->select('mr.*, ms.slot_name, ms.start_time, ms.end_time,
                         mv.venue_name, mc.category_name,
                         CONCAT(mr.bride_name, " & ", mr.groom_name) as couple_names');
        $builder->join('marriage_slots ms', 'mr.slot_id = ms.id', 'left');
        $builder->join('marriage_venues mv', 'mr.venue_id = mv.id', 'left');
        $builder->join('marriage_categories mc', 'mr.category_id = mc.id', 'left');
        $builder->orderBy('mr.registration_date', 'DESC');
        
        if ($limit) {
            $builder->limit($limit, $offset);
        }
        //$a = $builder->get();
        //echo $this->db->getLastQuery();
        //die("r");
        return $builder->get()->getResultArray();
    }
    
    public function getRegistrationsWithDetailsTotCnts($fromDate,$toDate,$status)
    {
        
        //get previous and current month total
        $builder = $this->db->table($this->table . ' mr');
        $builder->select('DATE_FORMAT(registration_date, "%Y-%m") as date,mr.registration_status as registration_status, sum(total_amount) as total_amount');
        $builder->join('marriage_slots ms', 'mr.slot_id = ms.id', 'left');
        $builder->join('marriage_venues mv', 'mr.venue_id = mv.id', 'left');
        $builder->join('marriage_categories mc', 'mr.category_id = mc.id', 'left');
        $builder->where("registration_date >= DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y-%m-01'
  AND registration_date < DATE_FORMAT(CURDATE() + INTERVAL 1 MONTH, '%Y-%m-01')
GROUP BY DATE_FORMAT(registration_date, '%Y-%m')")
        ->groupBy("registration_status,registration_date")
        ->get()->getResultArray();
        
        $curmon = Date("Y-m");
        $arr = [];
        $perc = 0;
        foreach($builder as $iter)
        {
            
            $arr[($curmon == $iter["date"])?"cur_mon":"prev_mon"][$iter["registration_status"]] = $iter["total_amount"]  ;
        }
        
        $arr1 = [];
        foreach($arr as $key=>$iter)
        {
            foreach($iter as $stat=>$iter1)
            {
                if($key == "prev_mon")
                {
                    if(isset($arr["cur_mon"][$stat]))
                        $cur_stat_amt = floatval($arr["cur_mon"][$stat]);
                    else
                        $cur_stat_amt = 0;
                    
                    $percentage_change = ((($cur_stat_amt - $iter1) / $iter1) * 100);
                    if($percentage_change>0)$typ = "pos";
                    else $typ = "neg";
                    
                    $arr1[$stat] = ["perc"=>$percentage_change,"typ"=>$typ];
                }
            }
        }
        return [$arr,$arr1];
        
    }
    

    public function getRegistrationWithDetails($id)
    {
        $builder = $this->db->table($this->table . ' mr');
        $builder->select('mr.*, ms.slot_name, ms.start_time, ms.end_time,
                         mv.venue_name, mv.venue_description, mv.base_price, mv.additional_charges,
                         mc.category_name, mc.base_fee');
        $builder->join('marriage_slots ms', 'mr.slot_id = ms.id', 'left');
        $builder->join('marriage_venues mv', 'mr.venue_id = mv.id', 'left');
        $builder->join('marriage_categories mc', 'mr.category_id = mc.id', 'left');
        $builder->where('mr.id', $id);
        
        return $builder->get()->getRowArray();
    }

    public function getBookingCountForSlot($date, $slot_id)
    {
        return $this->where('registration_date', $date)
                   ->where('slot_id', $slot_id)
                   ->where('registration_status !=', 'cancelled')
                   ->countAllResults();
    }

    public function getCalendarData($month, $year)
    {
        $builder = $this->db->table($this->table . ' mr');
        $builder->select('mr.registration_date, mr.slot_id, ms.slot_name, 
                         COUNT(*) as booking_count, ms.max_bookings');
        $builder->join('marriage_slots ms', 'mr.slot_id = ms.id', 'left');
        $builder->where('MONTH(mr.registration_date)', $month);
        $builder->where('YEAR(mr.registration_date)', $year);
        $builder->where('mr.registration_status !=', 'cancelled');
        $builder->groupBy('mr.registration_date, mr.slot_id');
        
        return $builder->get()->getResultArray();
    }

    public function getRegistrationsByDateRange($start_date, $end_date)
    {
        return $this->where('registration_date >=', $start_date)
                   ->where('registration_date <=', $end_date)
                   ->orderBy('registration_date', 'ASC')
                   ->findAll();
    }

    public function getMonthlyStats($month, $year)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            COUNT(*) as total_registrations,
            SUM(CASE WHEN registration_status = "completed" THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN registration_status = "pending" THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN registration_status = "cancelled" THEN 1 ELSE 0 END) as cancelled,
            SUM(total_amount) as total_revenue,
            SUM(paid_amount) as collected_amount
        ');
        $builder->where('MONTH(registration_date)', $month);
        $builder->where('YEAR(registration_date)', $year);
        
        return $builder->get()->getRowArray();
    }
}

