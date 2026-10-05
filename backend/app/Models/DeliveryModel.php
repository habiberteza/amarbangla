<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'delivery';
    protected $primaryKey = 'delivery_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["delivery_id","invoice_id",	"package_id","delivery_boy_id",	"accepetDtm","sch_id", "completeDtm","status","createdDtm","createdBy","updatedDtm","updatedBy","deleted",	"deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 