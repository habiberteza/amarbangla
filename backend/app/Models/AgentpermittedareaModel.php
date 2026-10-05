<?php

namespace App\Models;

use CodeIgniter\Model;

class AgentpermittedareaModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'agent_permitted_area';
    protected $primaryKey = 'agent_permitted_area_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["agent_permitted_area_id","agent_id", "global_address_id", "createdDtm", "createdBy", "updatedDtm", "updatedBy","deleted","deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}