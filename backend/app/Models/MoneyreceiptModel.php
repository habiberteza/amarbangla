<?php

namespace App\Models;

use CodeIgniter\Model;

class MoneyreceiptModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'money_receipt';
    protected $primaryKey = 'money_receipt_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["money_receipt_id","sch_id","customer_id","name","amount","date","createdDtm","createdBy","updatedDtm","updatedBy","deleted","deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 