<?php

namespace App\Models;

use CodeIgniter\Model;

class BrandModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'brand';
    protected $primaryKey = 'brand_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["brand_id",	"sch_id" ,"name","image","createdDtm","createdBy","updatedBy","updatedDtm","deleted","deletedRole"	
];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}