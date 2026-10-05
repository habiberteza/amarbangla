<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'commission';
    protected $primaryKey = 'commission_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["commission_id","invoice_id","package_id","seller_id","delivery_boy_id","commission","com_status","createdDtm","createdBy","updatedDtm","updatedBy","deleted","deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}