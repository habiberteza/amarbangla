<?php

namespace App\Models;

use CodeIgniter\Model;

class CallingrequestModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'calling_request';
    protected $primaryKey = 'calling_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["calling_id", "customer_id", "agent_id", "status","description","image","createdBy","createdDtm","updatedBy","updatedDtm","deleted","deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}