<?php
namespace App\Models;

use CodeIgniter\Model;
// 4. Marriage Categories Model
class MarriageCategoriesModel extends Model
{
    protected $table = 'marriage_categories';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'category_name', 'category_name_tamil', 'category_name_chinese', 'category_name_hindi',
        'base_fee', 'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActiveCategories()
    {
        return $this->where('is_active', 1)->orderBy('category_name', 'ASC')->findAll();
    }

    public function getCategoriesByLanguage($language = 'english')
    {
        $categories = $this->getActiveCategories();
        
        foreach ($categories as &$category) {
            switch ($language) {
                case 'tamil':
                    $category['display_name'] = $category['category_name_tamil'] ?: $category['category_name'];
                    break;
                case 'chinese':
                    $category['display_name'] = $category['category_name_chinese'] ?: $category['category_name'];
                    break;
                case 'hindi':
                    $category['display_name'] = $category['category_name_hindi'] ?: $category['category_name'];
                    break;
                default:
                    $category['display_name'] = $category['category_name'];
            }
        }
        
        return $categories;
    }
}
