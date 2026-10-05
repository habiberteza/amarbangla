<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymenttypeModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'payment_type';
    protected $primaryKey = 'pymnt_type_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["pymnt_type_id", "sch_id", "type_name", "createdBy", "createdDtm", "updatedBy", "updatedDtm", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 