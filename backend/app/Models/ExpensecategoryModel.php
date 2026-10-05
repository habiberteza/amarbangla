<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpensecategoryModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'expense_category';
    protected $primaryKey = 'expense_category_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["expense_category_id", "sch_id", "name", "createdDtm", "createdBy", "updatedBy", "updatedDtm", "deleted","deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 