<?php

namespace App\Models;

use CodeIgniter\Model;

class BankdepositModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'bank_deposit';
    protected $primaryKey = 'dep_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["dep_id","sch_id","bank_id","amount","commont","createdDtm","createdBy","updatedBy","updatedDtm","deleted","deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}