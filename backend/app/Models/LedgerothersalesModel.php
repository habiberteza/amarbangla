<?php

namespace App\Models;

use CodeIgniter\Model;

class LedgerothersalesModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'ledger_other_sales';
    protected $primaryKey = 'ledg_oth_sales_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ "ledg_oth_sales_id", "sch_id", "trans_id", "chaque_id", "particulars", "trangaction_type", "amount", "createdDtm", "createdBy", "updatedDtm", "updatedBy", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 