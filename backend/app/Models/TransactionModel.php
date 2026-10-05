<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'transaction';
    protected $primaryKey = 'trans_id';
    protected $returnType = 'object';    
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["trans_id", "sch_id", "title", "description", "trangaction_type", "amount", "customer_id", "loan_pro_id", "bank_id", "lc_id", "supplier_id", "employee_id", "vat_id", "seller_id", "createdDtm", "createdBy", "updateDtm", "updatedBy", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 