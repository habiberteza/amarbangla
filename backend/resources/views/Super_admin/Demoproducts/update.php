<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product Update</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Product Update</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="<?php echo base_url('super_admin/demo_product_list'); ?>" class="btn btn-xs btn-danger float-right"><i class="fas fa-list"></i> Back</a>
                            <h3 class="card-title">Product Update</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row" >
                                <div class="col-md-12" id="message">
                                    <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                </div>
                            </div>
                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link <?php echo (!empty($_GET) && ($_GET['active'] == 'general'))?'active':'';?> <?php echo(empty($_GET))?'active':'';?>" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">General Info</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link <?php echo(!empty($_GET) && ($_GET['active'] == 'photo'))?'active':'';?> " id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Photo</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade <?php echo (!empty($_GET) && ($_GET['active'] == 'general'))?'active show':'';?> <?php echo(empty($_GET))?'active show':'';?> " id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                            <form action="<?php echo base_url('super_admin/demo_product_update_action'); ?>" method="post">
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group ">
                                                            <label for="varchar">Name </label>
                                                            <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $product->name; ?>"/>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label for="int">Category </label>
                                                            <select class="form-control" onchange="showSubCategory(this.value,'<?php echo site_url('super_admin/demo_product_get_sub_cat') ?>')" name="prod_cat_id" id="prod_cat_id">
                                                                <option value="" >Please Select</option>
                                                                <?php echo categoryListInOptionsuper($product->prod_cat_id); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label for="int">Sub Category </label>
                                                            <select class="form-control" name="sub_cat_id" id="subCat">
                                                                <option value="">Please Select</option>
                                                                <?php echo subCatListInOptionsuper($product->prod_cat_id); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label for="varchar">Purchase price </label>
                                                            <input type="number" class="form-control" name="purchase_price" id="purchase_price" placeholder="Purchase price" value="<?php echo $product->purchase_price; ?>"/>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label for="varchar">Selling price </label>
                                                            <input type="number" class="form-control" name="selling_price" id="selling_price" placeholder="Selling price" value="<?php echo $product->selling_price; ?>"/>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label for="varchar">Quantity </label>
                                                            <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Quantity" value="<?php echo $product->quantity; ?>"/>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group ">
                                                            <label for="varchar">Unit  </label>
                                                            <select class="form-control" name="unit" id="unit">
                                                                <?php echo selectOptions($product->unit, unitArray()); ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label for="varchar">Size </label>
                                                            <input type="text" class="form-control" name="size" id="size" placeholder="size" value="<?php echo $product->size; ?>"/>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label for="varchar">Description </label>
                                                            <textarea id="editor" class="form-control" name="description"  rows="12" style="width: 100%;"><?php echo $product->description; ?></textarea>
                                                        </div>

                                                    </div>
                                                    <input type="hidden" name="id" value="<?php echo $product->id; ?>" >
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                    <a href="<?php echo site_url('super_admin/demo_product_list') ?>" class="btn btn-danger">Cancel</a>

                                                </div>
                                            </form>

                                        </div>
                                        <div class="tab-pane fade <?php echo(!empty($_GET) && ($_GET['active'] == 'photo'))?'active show':'';?>" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form action="<?php echo base_url('super_admin/demo_product_photo_action'); ?>" method="post" enctype="multipart/form-data">

                                                        <div class="form-group row ">
                                                            <?php echo demo_multipleImage_by_productId($product->id,'70','<div class="col-md-2">','</div>');?>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label for="varchar">Photo </label>
                                                            <div class="row" id="coba"></div>
                                                            <span class="help-block"><b>Max. file size 1024KB and (width=300px) x (height=300px)</b></span>
                                                        </div>

                                                        <input type="hidden" name="id" value="<?php echo $product->id; ?>" >
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="<?php echo site_url('super_admin/demo_product_list') ?>" class="btn btn-danger">Cancel</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->