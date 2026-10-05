<?php

namespace App\Models;

use CodeIgniter\Model;

class ShopsModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'shops';
    protected $primaryKey = 'sch_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['sch_id','agent_id','email', 'name','cash','del_balance','reserve_cash','sup_comm','address','global_address_id','mobile','comment','logo','image','banner','is_default','shop_cat_id','priority','sms','home_feature','type','status','opening_status','createdDtm', 'createdBy', 'updateDtm', 'updatedBy', 'deleted', 'deletedRole'];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}