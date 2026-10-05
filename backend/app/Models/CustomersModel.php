<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomersModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["customer_id","customer_name","father_name","mother_name","age","mobile","password","pass",	"pic","nid","cus_type_id","balance","mac_address","address","global_address_id","createdDtm","createdBy","updatedDtm","updatedBy", "deleted","deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}