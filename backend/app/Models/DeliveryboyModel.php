<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryboyModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'delivery_boy';
    protected $primaryKey = 'delivery_boy_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['delivery_boy_id','agent_id','name','father_name','mother_name','age','mobile','password','pass','pic','nid','role_id','global_address_id','balance','status','createdDtm','createdBy','updatedDtm','updatedBy','deleted','deletedRole'];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 