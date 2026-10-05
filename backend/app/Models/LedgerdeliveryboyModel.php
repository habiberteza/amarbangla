<?php

namespace App\Models;

use CodeIgniter\Model;

class LedgerdeliveryboyModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'ledger_delivery_boy';
    protected $primaryKey = 'ledg_delivery_boy_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["ledg_delivery_boy_id","sch_id","delivery_boy_id","commission_id","invoice_id","package_id","trans_id","particulars","trangaction_type","amount","rest_balance","createdDtm","createdBy","updateDtm","updatedBy","deleted","deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 