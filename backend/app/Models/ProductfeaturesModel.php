<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductfeaturesModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'product_features';
    protected $primaryKey = 'feature_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["feature_id", "prod_id", "popular", "featured", "hot"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 