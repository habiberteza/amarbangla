<?php

namespace App\Models;

use CodeIgniter\Model;

class LedgerModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'ledger';
    protected $primaryKey = 'ledg_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["ledg_id", "sch_id", "customer_id", "invoice_id", "trans_id",	"package_id",	"rtn_sale_id", "chaque_id", "particulars", "trangaction_type",	"amount", "rest_balance", "createdDtm",	"createdBy", "updatedDtm", "updatedBy", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 