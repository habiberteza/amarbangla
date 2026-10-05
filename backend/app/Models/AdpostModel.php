<?php

namespace App\Models;

use CodeIgniter\Model;

class AdpostModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'ad_post';
    protected $primaryKey = 'ad_post_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["ad_post_id", "sch_id",	"title", "description",	"banner_1",	"banner_2",	"youtube_video", "facebook_video", "total_like", "total_reach",	"createdDtm", "createdBy", "updatedBy",	"updatedDtm", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}