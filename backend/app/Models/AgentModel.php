<?php

namespace App\Models;

use CodeIgniter\Model;

class AgentModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'agent';
    protected $primaryKey = 'agent_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["agent_id", "agent_name", "email", "mobile","password",	"pass", "global_address_id", "status", "createdDtm", "createdBy", "updatedDtm", "updatedBy","deleted","deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}