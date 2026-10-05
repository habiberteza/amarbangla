<?php

namespace App\Models;

use CodeIgniter\Model;

class ColorfamilyModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'color_family';
    protected $primaryKey = 'color_family_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["color_family_id","code","color_name","createdDtm","createdBy","updatedBy","updatedDtm","deleted","deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}