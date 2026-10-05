<?php

namespace App\Models;

use CodeIgniter\Model;

class LedgerloanModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'ledger_loan';
    protected $primaryKey = 'ledg_loan_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["ledg_loan_id", "sch_id", "loan_pro_id", "money_receipt_id", "trans_id", "chaque_id", "particulars", "trangaction_type", "amount", "rest_balance", "createdDtm", "createdBy", "updateDtm", "updatedBy", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 