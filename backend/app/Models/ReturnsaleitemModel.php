<?php

namespace App\Models;

use CodeIgniter\Model;

class ReturnsaleitemModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'return_sale_item';
    protected $primaryKey = 'rtn_sale_item_id';
    protected $returnType = 'object';    
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["rtn_sale_item_id", "rtn_sale_id", "sch_id", "prod_id", "title", "price", "quantity", "total_price", "date", "createdDtm", "createdBy", "updatedBy", "updatedDtm", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 