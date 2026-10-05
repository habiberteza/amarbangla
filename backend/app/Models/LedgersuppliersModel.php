<?php

namespace App\Models;

use CodeIgniter\Model;

class LedgersuppliersModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'ledger_suppliers';
    protected $primaryKey = 'ledg_sup_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["ledg_sup_id", "sch_id", "supplier_id", "money_receipt_id", "purchase_id", "trans_id", "rtn_purchase_id", "chaque_id", "particulars", "trangaction_type", "amount", "rest_balance", "createdDtm", "createdBy", "updateDtm", "updatedBy", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 