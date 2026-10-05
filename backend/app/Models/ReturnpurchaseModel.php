<?php

namespace App\Models;

use CodeIgniter\Model;

class ReturnpurchaseModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'return_purchase';
    protected $primaryKey = 'rtn_purchase_id';
    protected $returnType = 'object';    
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["rtn_purchase_id", "sch_id", "supplier_id", "pymnt_type_id", "customer_name", "amount", "rtn_profit", "nagad_paid", "bank_paid", "bank_id", "creation_timestamp", "payment_timestamp", "payment_method", "payment_details", "status", "timestamp", "year", "createdDtm", "createdBy", "updatedBy", "updatedDtm", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 