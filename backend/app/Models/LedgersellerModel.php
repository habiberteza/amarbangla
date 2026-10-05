<?php

namespace App\Models;

use CodeIgniter\Model;

class LedgersellerModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'ledger_seller';
    protected $primaryKey = 'ledg_seller_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["ledg_seller_id", "sch_id", "seller_id", "invoice_id", "package_id", "trans_id", "commission_id", "particulars", "trangaction_type", "amount", "rest_balance", "createdDtm", "createdBy", "updateDtm", "updatedBy", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 