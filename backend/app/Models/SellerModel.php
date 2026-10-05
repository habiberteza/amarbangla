<?php

namespace App\Models;

use CodeIgniter\Model;

class SellerModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'seller';
    protected $primaryKey = 'seller_id';
    protected $returnType = 'object';    
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["seller_id", "agent_id",'role_id', "name", "father_name", "mother_name", "age", "mobile", "password","pass","pic","nid","global_address_id","balance",'createdDtm', 'createdBy', 'updateDtm', 'updatedBy', 'deleted', 'deletedRole'];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 