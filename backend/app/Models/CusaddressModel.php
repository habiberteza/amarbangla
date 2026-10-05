<?php

namespace App\Models;

use CodeIgniter\Model;

class CusaddressModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'cus_address';
    protected $primaryKey = 'address_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["address_id","customer_id","division","zila","upazila","pourashava","ward","address","createdDtm","createdBy","updatedDtm","updatedBy"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 