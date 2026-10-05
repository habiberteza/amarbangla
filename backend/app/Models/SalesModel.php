<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'sales';
    protected $primaryKey = 'sales_id';
    protected $returnType = 'object';    
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["sales_id", "sch_id", "package_id", "invoice_id", "createdDtm", "createdBy", "updateDtm", "updatedBy", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 