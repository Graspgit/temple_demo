<?php

namespace App\Models;

use CodeIgniter\Model;

class UniversityModel extends Model
{
    protected $table = 'universities';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'university_code',
        'university_name',
        'short_name',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'phone',
        'email',
        'website',
        'contact_person',
        'contact_person_phone',
        'contact_person_email',
        'established_year',
        'university_type',
        'affiliation_number',
        'is_active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation Rules
    protected $validationRules = [
        'university_code' => [
            'label' => 'University Code',
            'rules' => 'required|min_length[3]|max_length[20]|alpha_numeric|is_unique[universities.university_code,id,{id}]'
        ],
        'university_name' => [
            'label' => 'University Name',
            'rules' => 'required|min_length[5]|max_length[200]'
        ],
        'short_name' => [
            'label' => 'Short Name',
            'rules' => 'required|min_length[2]|max_length[50]'
        ],
        'email' => [
            'label' => 'Email',
            'rules' => 'permit_empty|valid_email'
        ],
        'phone' => [
            'label' => 'Phone',
            'rules' => 'permit_empty|regex_match[/^[0-9+\-\s()]+$/]|max_length[20]'
        ],
        'pincode' => [
            'label' => 'Pincode',
            'rules' => 'permit_empty|numeric|exact_length[6]'
        ],
        'website' => [
            'label' => 'Website',
            'rules' => 'permit_empty|valid_url'
        ],
        'contact_person_email' => [
            'label' => 'Contact Person Email',
            'rules' => 'permit_empty|valid_email'
        ],
        'established_year' => [
            'label' => 'Established Year',
            'rules' => 'permit_empty|numeric|exact_length[4]|greater_than[1800]|less_than_equal_to[' . date('Y') . ']'
        ]
    ];
    
    protected $validationMessages = [
        'university_code' => [
            'is_unique' => 'This University Code already exists.'
        ]
    ];
    
    /**
     * Get active universities
     */
    public function getActive()
    {
        return $this->where('is_active', 1)->findAll();
    }
    
    /**
     * Get universities for dropdown
     */
    public function getDropdown()
    {
        $universities = $this->select('id, university_name, short_name')
                            ->where('is_active', 1)
                            ->orderBy('university_name', 'ASC')
                            ->findAll();
        
        $options = [];
        foreach ($universities as $university) {
            $options[$university['id']] = $university['university_name'] . ' (' . $university['short_name'] . ')';
        }
        
        return $options;
    }
    
    /**
     * Check if university has associated records
     */
    public function hasAssociations($id)
    {
        $db = \Config\Database::connect();
        
        // Check for courses
        $courses = $db->table('courses')
                     ->where('university_id', $id)
                     ->countAllResults();
        
        // Check for students
        $students = $db->table('students')
                      ->where('university_id', $id)
                      ->countAllResults();
        
        return ($courses > 0 || $students > 0);
    }
    
    /**
     * Get university statistics
     */
    public function getStatistics($id)
    {
        $db = \Config\Database::connect();
        
        $stats = [
            'total_courses' => $db->table('courses')
                                 ->where('university_id', $id)
                                 ->countAllResults(),
            
            'total_students' => $db->table('students')
                                  ->where('university_id', $id)
                                  ->countAllResults(),
            
            'active_students' => $db->table('students')
                                   ->where('university_id', $id)
                                   ->where('status', 'active')
                                   ->countAllResults(),
            
            'total_batches' => $db->table('batches')
                                 ->join('courses', 'courses.id = batches.course_id')
                                 ->where('courses.university_id', $id)
                                 ->countAllResults()
        ];
        
        return $stats;
    }
    
    /**
     * Search universities
     */
    public function search($term)
    {
        return $this->groupStart()
                    ->like('university_code', $term)
                    ->orLike('university_name', $term)
                    ->orLike('short_name', $term)
                    ->orLike('city', $term)
                    ->groupEnd()
                    ->limit(10)
                    ->findAll();
    }
}