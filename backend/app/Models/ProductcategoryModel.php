<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductcategoryModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'product_category';
    protected $primaryKey = 'prod_cat_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["prod_cat_id", "sch_id", "parent_pro_cat", "product_category", "image", "status", "createdDtm", "createdBy", "updatedDtm", "updatedBy", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 