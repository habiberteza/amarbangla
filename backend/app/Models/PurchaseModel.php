<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'purchase';
    protected $primaryKey = 'purchase_id';
    protected $returnType = 'object';    
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["purchase_id", "sch_id", "supplier_id", "amount", "nagad_paid", "bank_paid","bank_id",	"due", "createdDtm", "createdBy", "updateDtm",	"updatedBy", "deleted",	"deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 