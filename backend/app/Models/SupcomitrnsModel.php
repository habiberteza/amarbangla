<?php

namespace App\Models;

use CodeIgniter\Model;

class SupcomitrnsModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'sup_comi_trns';
    protected $primaryKey = 'sup_comi_trns_id';
    protected $returnType = 'object';    
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["sup_comi_trns_id", "sch_id", "amount", "trangaction_type", "status", "createdDtm", "createdBy", "updatedDtm", "updatedBy"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 