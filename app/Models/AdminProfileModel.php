<?php

// App/Models/AdminProfileModel.php
namespace App\Models;

use CodeIgniter\Model;

class AdminProfileModel extends Model
{
    protected $table = 'admin_profile';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'name_tamil',
        'address1',
        'address2',
        'city',
        'postcode',
        'telephone',
        'regno',
        'mobile',
        'email',
        'gstno',
        'daily_closing_phone',
        'fax_no',
        'website',
        'bankid',
        'hall_remind',
        'ubayam_courtesy_grace_amount',
        'donation_courtesy_grace_amount',
        'booking_range_year',
        'image',
        'ar_image',
        'since_eng',
        'since_tamil',
        'city_tamil',
        'iso'
    ];

    protected $useTimestamps = false; // This table doesn't have created_at/updated_at

    /**
     * Get company profile settings
     */
    public function getCompanyProfile()
    {
        return $this->first();
    }

    /**
     * Update company profile
     */
    public function updateProfile($data)
    {
        $profile = $this->first();
        if ($profile) {
            return $this->update($profile['id'], $data);
        } else {
            return $this->insert($data);
        }
    }

    /**
     * Get formatted company address
     */
    public function getFormattedAddress()
    {
        $profile = $this->first();
        if (!$profile) return '';

        $address = [];
        if (!empty($profile['address1'])) $address[] = $profile['address1'];
        if (!empty($profile['address2'])) $address[] = $profile['address2'];
        if (!empty($profile['city'])) $address[] = $profile['city'];
        if (!empty($profile['postcode'])) $address[] = $profile['postcode'];

        return implode(', ', $address);
    }
}