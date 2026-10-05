<?php

namespace App\Models;

use CodeIgniter\Model;

class LedgernagodanModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'ledger_nagodan';
    protected $primaryKey = 'ledg_nagodan_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['ledg_nagodan_id','sch_id', 'customer_id','package_id','bank_id','loan_pro_id','money_receipt_id','purchase_id','trans_id','rtn_purchase_id','rtn_sale_id','chaque_id','invoice_id','commission_id','sup_comi_trns_id','particulars','trangaction_type','amount','rest_balance','createdDtm', 'createdBy', 'updateDtm', 'updatedBy', 'deleted', 'deletedRole'];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}