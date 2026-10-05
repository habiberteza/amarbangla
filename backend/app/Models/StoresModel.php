<?php

namespace App\Models;

use CodeIgniter\Model;

class StoresModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'stores';
    protected $primaryKey = 'store_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['store_id','sch_id','name','description','is_default','createdDtm', 'createdBy', 'updateDtm', 'updatedBy', 'deleted', 'deletedRole'];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}