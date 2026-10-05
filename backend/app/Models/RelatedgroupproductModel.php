<?php

namespace App\Models;

use CodeIgniter\Model;

class RelatedgroupproductModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'related_group_product';
    protected $primaryKey = 'related_group_product_id';
    protected $returnType = 'object';    
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["related_group_product_id", "prod_id",	"group_code", "color_family_id", "size"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 