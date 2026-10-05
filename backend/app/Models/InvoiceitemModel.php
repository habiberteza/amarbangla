<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceitemModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'invoice_item';
    protected $primaryKey = 'inv_item';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["inv_item",	"invoice_id", "sch_id",	"prod_id", "package_id", "title", "price",	"quantity",	"total_price",	"discount",	"final_price",	"profit", "date", "createdDtm", "createdBy", "updatedBy", "updatedDtm",	"deleted",	"deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 