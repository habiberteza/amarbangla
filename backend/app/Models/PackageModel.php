<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'package';
    protected $primaryKey = 'package_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["package_id", "invoice_id",	"sch_id", "price", "delivery_charge", "profit",	"nagad_paid", "bank_paid", "bank_id", "chaque_paid", "chaque_id", "delivery_status", "status", "createdDtm",	"createdBy", "updatedBy", "updatedDtm",	"deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 