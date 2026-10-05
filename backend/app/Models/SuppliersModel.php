<?php

namespace App\Models;

use CodeIgniter\Model;

class SuppliersModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';
    protected $returnType = 'object';    
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["supplier_id", "sch_id", "name", "balance", "address", "phone", "createdDtm", "createdBy", "updateDtm", "updatedBy", "deleted", "deletedRole"	];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 