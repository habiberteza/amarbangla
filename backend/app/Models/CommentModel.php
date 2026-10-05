<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'comment';
    protected $primaryKey = 'comment_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["comment_id","ad_post_id","comment","sch_id","customer_id","createdDtm","createdBy","updatedBy","updatedDtm","deleted",	"deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}