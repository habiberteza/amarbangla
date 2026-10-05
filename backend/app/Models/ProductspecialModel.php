<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductspecialModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'product_special';
    protected $primaryKey = 'product_special_id';
    protected $returnType = 'object';    
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["product_special_id", "prod_id", "special_price", "start_date", "end_date", "createdDtm", "createdBy", "updatedBy", "updatedDtm"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 