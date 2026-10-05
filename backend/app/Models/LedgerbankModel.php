<?php

namespace App\Models;

use CodeIgniter\Model;

class LedgerbankModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'ledger_bank';
    protected $primaryKey = 'ledgBank_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["ledgBank_id", "bank_id", "sch_id", "money_receipt_id", "purchase_id", "trans_id", "rtn_purchase_id", "rtn_sale_id", "chaque_id", "invoice_id", "particulars", "trangaction_type", "amount", "rest_balance", "createdDtm", "createdBy", "updatedDtm", "updatedBy", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 