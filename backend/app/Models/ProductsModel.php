<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductsModel extends Model
{
    /**
     * Called during initialization. Appends
     * our custom field to the module's model.a
     */
    protected $table = 'products';
    protected $primaryKey = 'prod_id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields =  ["prod_id", "sch_id", "store_id", "name", "size", "quantity", "unit", "product_code", "color_family_id", "purchase_price", "selling_price", "purchase_date", "purchase_type", "supplier_id", "serial_number", "brand_id", "demo_id", "show_global_price", "picture", "warranty", "barcode", "prod_cat_id", "seller_commission", "description", "meta_title", "meta_keyword", "meta_description", "status", "createdDtm", "createdBy", "updateDtm", "updatedBy", "deleted", "deletedRole"];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; 
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;
} 