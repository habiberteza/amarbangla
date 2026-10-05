<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseitemModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'purchase_item';
    protected $primaryKey = 'purchase_item_id';
    protected $returnType = 'object';    
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["purchase_item_id", "purchase_id", "prod_id", "purchase_price", "quantity", "total_price", "createdDtm", "createdBy", "updateDtm", "updatedBy", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 