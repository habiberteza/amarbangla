<?php

namespace App\Models;

use CodeIgniter\Model;

class SmsrequestModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'sms_request';
    protected $primaryKey = 'sms_request_id';
    protected $returnType = 'object';    
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["sms_request_id",	"sch_id", "sms_qty", "status", "createdBy", "createdDtm",	"updatedBy", "updatedDtm", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 