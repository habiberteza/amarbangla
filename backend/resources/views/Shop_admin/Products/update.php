<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Products</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Products Update</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Products Update</h3>
                <a href="<?= base_url('shop_admin/products'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">

                <div class="row">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>
                    <div class="col-md-12">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'info'))?'active':'';?> <?= (empty($_GET))?'active':'';?>" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Product Add Info</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'detail'))?'active':'';?> " id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Detail</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'metaData'))?'active':'';?>" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Meta Data</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'relatedProduct'))?'active':'';?>" id="custom-tabs-four-vat-tab" data-toggle="pill" href="#custom-tabs-four-vat" role="tab" aria-controls="custom-tabs-four-vat" aria-selected="false">Related product</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'image'))?'active':'';?>" id="custom-tabs-four-address-tab" data-toggle="pill" href="#custom-tabs-four-address" role="tab" aria-controls="custom-tabs-four-address" aria-selected="false">Image</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'productFeatures'))?'active':'';?>" id="custom-tabs-four-customer-tab" data-toggle="pill" href="#custom-tabs-four-customer" role="tab" aria-controls="custom-tabs-four-customer" aria-selected="false">Product Features</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= (!empty($_GET) && ($_GET['active'] == 'productSpecial'))?'active':'';?>" id="custom-tabs-four-vat-pay-tab" data-toggle="pill" href="#custom-tabs-four-vat-pay" role="tab" aria-controls="custom-tabs-four-customer" aria-selected="false">Product Special</a>
                                </li>



                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'info'))?'active show':'';?> <?= (empty($_GET))?'active show':'';?> " id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form action="<?= base_url('shop_admin/products_update_action') ?>" method="post">
                                                <div class="form-group">
                                                    <label for="varchar">Store</label>
                                                    <select class="form-control" name="store_id" id="store_id">
                                                        <option value="">Please Select</option>
                                                        <?= getAllListInOption($product->store_id, 'store_id', 'name', 'stores'); ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="varchar">Name </label>
                                                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?= $product->name; ?>" />
                                                </div>

                                                <div class="form-group">
                                                    <label for="int">Supplier</label>

                                                    <select class="form-control" name="supplier_id" id="supplier_id">
                                                        <option>Please Select</option>
                                                        <?= getAllListInOption($product->supplier_id, 'supplier_id', 'name', 'suppliers'); ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="int">Serial Number </label>
                                                    <input type="text" class="form-control" name="serial_number" value="<?= $product->serial_number; ?>">
                                                </div>

                                                <input type="hidden" name="prod_id" value="<?= $product->prod_id; ?>" />


                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                        </div>

                                    </div>

                                </div>

                                <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'detail'))?'active show':'';?>" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                    <form action="<?= base_url('shop_admin/products_update_detail_action') ?>" method="post">
                                    <div class="row">

                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="int">Category <?= $product->prod_cat_id;?></label>
                                                <select class="form-control" onchange="showSubCategory(this.value)" name="prod_cat_id" id="prod_cat_id">
                                                    <option>Please Select</option>
                                                    <?= categoryListInOptionShop($product->prod_cat_id); ?>

                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="int">Sub Category </label>
                                                <select class="form-control" name="sub_cat_id" id="subCat">
                                                    <option value="">Please Select</option>
                                                    <?= subCatListInOption($product->prod_cat_id); ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="int">Brand</label>
                                                <select class="form-control" name="brand_id">
                                                    <option value="">Please Select</option>
                                                    <?= getListInOption($product->brand_id, 'brand_id', 'name', 'brand') ?>
                                                </select>

                                            </div>
                                            <div class="form-group">
                                                <label for="int">Selling Price </label>
                                                <input type="text" class="form-control" name="selling_price" id="selling_price" placeholder="Selling Price" value="<?= $product->selling_price; ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="int">Selling commission % </label>
                                                <input type="text" class="form-control" name="commission" id="commission" placeholder="Selling Commission %" value="<?= $product->seller_commission; ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="varchar">Size </label>
                                                <input type="text" class="form-control" name="size" id="size" placeholder="Size" value="<?= $product->size; ?>" />
                                            </div>



                                            <div class="form-group">
                                                <label for="int">Warranty </label>
                                                <input type="text" class="form-control" name="warranty" id="warranty" placeholder="Warranty " value="<?= $product->warranty; ?>" />
                                            </div>

                                            <input type="hidden" name="prod_id" value="<?= $product->prod_id; ?>" />
                                            <button type="submit" class="btn btn-primary">Update</button>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="varchar">Description </label>
                                                <textarea id="editor" name="description"  ><?= $product->description; ?></textarea>
                                            </div>
                                        </div>

                                    </div>

                                    </form>
                                </div>

                                <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'metaData'))?'active show':'';?>" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <form action="<?= base_url('shop_admin/products_update_meta_data_action') ?>" method="post">
                                                <div class="form-group">
                                                    <label for="varchar">Meta Title </label>
                                                    <input type="text" class="form-control" name="meta_title" id="meta_title" placeholder="Meta Title" value="<?= $product->meta_title; ?>" />
                                                </div>

                                                <div class="form-group">
                                                    <label for="varchar">Meta Keyword </label>
                                                    <input type="text" class="form-control" name="meta_keyword" id="meta_keyword" placeholder="Meta Keyword" value="<?= $product->meta_keyword; ?>" />
                                                </div>

                                                <div class="form-group">
                                                    <label for="varchar">Meta Description </label>
                                                    <textarea id="editor2" name="meta_description" style="width: 100%;"><?= $product->meta_description; ?></textarea>
                                                    <small class="text-muted d-block mt-1"><i class="fa fa-info-circle text-primary"></i> <strong>Auto-generated SEO Description (Preview if left blank):</strong> <em><?= esc($product->seo_description); ?></em></small>
                                                </div>

                                                <input type="hidden" name="prod_id" value="<?= $product->prod_id; ?>" />
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'relatedProduct'))?'active show':'';?>" id="custom-tabs-four-vat" role="tabpanel" aria-labelledby="custom-tabs-four-vat-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form action="<?= base_url('shop_admin/products_update_related_product_action'); ?>" method="post">

                                                <div class="form-group">
                                                    <label for="varchar">Color </label>
                                                    <select class="form-control" name="color_family_id" >
                                                        <option value="">Please select</option>
                                                        <?= getListInOption($product->color_family_id, 'color_family_id', 'color_name', 'color_family') ?>
                                                    </select>
                                                </div>


                                                <div class="form-group">
                                                    <label for="varchar">Size </label>
                                                    <input type="text" class="form-control" name="size" value="<?= $product->size ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label for="varchar">Product Code </label>
                                                    <input type="text" class="form-control" name="product_code" value="<?= $product->product_code ?>">
                                                </div>

                                                <input type="hidden" name="prod_id" value="<?= $product->prod_id; ?>" />
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">

                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade <?= (!empty($_GET) && ($_GET['active'] == 'image'))?'active show':'';?>" id="custom-tabs-four-address" role="tabpanel" aria-labelledby="custom-tabs-four-address-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form action="<?= base_url('shop_admin/products_update_image_action'); ?>" method="post" enctype="multipart/form-data" >
                                                <div class="form-group">
                                                    <?php echo multipleImage_by_productId($product->prod_id, '210'); ?>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Upload Image</label>
                                                    <div class="row" id="coba"></div>
                                                </div>

                                                <input type="hidden" name="prod_id" value="<?= $product->prod_id; ?>" />
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'productFeatures'))?'active show':'';?>" id="custom-tabs-four-customer" role="tabpanel" aria-labelledby="custom-tabs-four-customer-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form action="<?= base_url('shop_admin/products_update_product_features_action'); ?>" method="post">
                                                <?php
                                                    $popular = (!empty($features->popular)) ? $features->popular : '0';
                                                    $featured = (!empty($features->featured)) ? $features->featured : '0';
                                                    $hot = (!empty($features->hot)) ? $features->hot : '0';
                                                ?>

                                                <div class="form-group">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" name="popular" id="popular" <?php echo ($popular == 1) ? 'checked' : ''; ?> >
                                                        <label class="custom-control-label" for="popular">Popular</label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" name="featured" id="featured" <?php echo ($featured == 1) ? 'checked' : ''; ?> >
                                                        <label class="custom-control-label" for="featured">Featured</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" name="hot" id="hot" <?php echo ($hot == 1) ? 'checked' : ''; ?> >
                                                        <label class="custom-control-label" for="hot">Hot</label>
                                                    </div>
                                                </div>


                                                <input type="hidden" name="prod_id" value="<?= $product->prod_id; ?>" />
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'productSpecial'))?'active show':'';?>" id="custom-tabs-four-vat-pay" role="tabpanel" aria-labelledby="custom-tabs-four-customer-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form action="<?= base_url('shop_admin/products_update_product_special_action'); ?>" method="post">
                                                <?php
                                                    $special_price = (!empty($special->special_price)) ? $special->special_price : '';
                                                    $start_date = (!empty($special->start_date)) ? $special->start_date : '';
                                                    $end_date = (!empty($special->end_date)) ? $special->end_date : '';
                                                ?>


                                                <div class="form-group">
                                                    <label for="int">Special Price </label>
                                                    <input type="text" class="form-control" name="special_price" id="special_price" placeholder="Special Price" value="<?php echo $special_price; ?>" required >
                                                </div>
                                                <div class="form-group">
                                                    <label for="int">Start Date </label>
                                                    <input type="date" class="form-control" name="start_date" id="start_date" placeholder="Start Date" value="<?php echo $start_date; ?>"  required >
                                                </div>
                                                <div class="form-group">
                                                    <label for="int">End Date </label>
                                                    <input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date" value="<?php echo $end_date; ?>" required >
                                                </div>

                                                <input type="hidden" name="prod_id" value="<?= $product->prod_id; ?>" />
                                                <button type="submit" class="btn btn-primary">Update</button>

                                            </form>
                                        </div>

                                        <div class="col-md-6">
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- /.card-body -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
<script>
    function showSubCategory(id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/products_get_sub_category') ?>",
            dataType: "text",
            data: {category_id: id},
            success: function(data){
                $('#subCat').html(data);
            }
        });
    }
</script>
<?= $this->endSection() ?>
