<?php

namespace App\Models;

use CodeIgniter\Model;

class LikepostModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'like_post';
    protected $primaryKey = 'like_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["like_id", "ad_post_id", "sch_id", "customer_id", "createdDtm", "updatedBy", "updatedDtm", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 