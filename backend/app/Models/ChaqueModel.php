<?php

namespace App\Models;

use CodeIgniter\Model;

class ChaqueModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'chaque';
    protected $primaryKey = 'chaque_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["chaque_id","sch_id","chaque_number","to_name",	"to","from_name","from",	"from_loan_provider","amount","issue_date","account_number","status","createdDtm","createdBy","updatedBy",	"updatedDtm","deleted",	"deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}