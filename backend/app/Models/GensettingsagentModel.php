<?php

namespace App\Models;

use CodeIgniter\Model;

class GensettingsagentModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'gen_settings_agent';
    protected $primaryKey = 'settings_id_agent';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['settings_id_agent','agent_id','label','value','createdDtm', 'createdBy', 'updateDtm', 'updatedBy', 'deleted', 'deletedRole'];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}