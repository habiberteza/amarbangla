<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.
     */
    protected $table = 'invoice';
    protected $primaryKey = 'invoice_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ["invoice_id", "customer_id", "pymnt_type_id", "customer_name", "amount","entire_sale_discount", "vat", "delivery_charge", "final_amount", "profit", "nagad_paid", "bank_paid",	"bank_id", "chaque_paid", "chaque_id", "seller_id",	"due", "global_address_id",	"address", "delivery_type",	"creation_timestamp", "payment_timestamp", "payment_method", "payment_details", "status", "timestamp",	"year",	"createdDtm", "createdBy", "updatedBy", "updatedDtm", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 