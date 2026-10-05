<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'employee';
    protected $primaryKey = 'employee_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["employee_id","sch_id","name","salary","age","balance","createdDtm","createdBy","updatedDtm","updatedBy","deleted","deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 