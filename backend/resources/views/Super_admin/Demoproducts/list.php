<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Product List</li>
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
                            <a href="<?php echo base_url('super_admin/demo_product'); ?>" class="btn btn-xs btn-primary float-right"><i class="fas fa-list"></i> Product add</a>
                            <h3 class="card-title">Product List</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row" >
                                <div class="col-md-12" id="message">
                                    <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <form method="get" action="<?php echo base_url('super_admin/demo_product_list'); ?>">
                                        <div class="row align-items-end">
                                            <div class="form-group col-md-4 mb-2">
                                                <label><i class="fas fa-search"></i> Product Name Search</label>
                                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name..." value="<?php echo esc($search ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-md-4 mb-2">
                                                <label><i class="fas fa-folder"></i> Category Filter</label>
                                                <select name="cat_id" class="form-control form-control-sm" style="text-transform:capitalize;">
                                                    <option value="">All Categories</option>
                                                    <?php foreach ($category as $cat) { ?>
                                                        <optgroup label="---------------------------------">
                                                            <option value="<?php echo $cat->cat_id; ?>" <?php echo (!empty($selected_cat) && $selected_cat == $cat->cat_id) ? 'selected' : ''; ?> style="font-weight: bold; "><?php echo $cat->product_category; ?></option>
                                                            <?php echo subCatDemoOption($cat->cat_id) ?>
                                                        </optgroup>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2 mb-2">
                                                <label>Per Page</label>
                                                <select name="per_page" class="form-control form-control-sm">
                                                    <option value="25" <?php echo (isset($per_page) && $per_page == 25) ? 'selected' : ''; ?>>25</option>
                                                    <option value="50" <?php echo (isset($per_page) && $per_page == 50) ? 'selected' : ''; ?>>50</option>
                                                    <option value="100" <?php echo (isset($per_page) && $per_page == 100) ? 'selected' : ''; ?>>100</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2 mb-2 d-flex">
                                                <button type="submit" class="btn btn-sm btn-primary mr-1"><i class="fas fa-filter"></i> Filter</button>
                                                <a href="<?php echo base_url('super_admin/demo_product_list'); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-undo"></i> Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="demo_prod_table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Category</th>
                                            <th>Purchase price</th>
                                            <th>Selling price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="subCat">
                                    <?php if (!empty($product)) { foreach ($product as $val) { ?>
                                        <tr id="update_row_<?= $val->id;?>">
                                            <td><?php echo $val->id ?></td>
                                            <td><strong><?php echo esc($val->name); ?></strong></td>
                                            <td><?php echo demo_singleImage_by_productId($val->id,'70','0','');?> </td>
                                            <td><?php echo get_data_by_id('product_category', 'demo_category', 'cat_id', $val->prod_cat_id) ?></td>
                                            <td>
                                                <p onclick="updateFunction('<?= $val->id ?>', 'purchase_price', '<?= $val->purchase_price ?>', 'pur_view_<?= $val->id ?>', 'pur_price_update_<?= $val->id ?>','update_row_<?= $val->id;?>')"><?php echo showWithCurrencySymbol($val->purchase_price) ?></p>
                                                <p id="pur_view_<?= $val->id;?>"></p>
                                            </td>
                                            <td>
                                                <p onclick="updateFunction('<?= $val->id ?>', 'selling_price', '<?= $val->selling_price ?>', 'sel_view_<?= $val->id ?>', 'sell_price_update_<?= $val->id ?>','update_row_<?= $val->id;?>')"><?php echo showWithCurrencySymbol($val->selling_price) ?></p>
                                                <p id="sel_view_<?= $val->id;?>"></p>
                                            </td>
                                            <td>
                                                <a href="<?php echo site_url('super_admin/demo_product_update/' . $val->id) ?>" class="btn btn-xs btn-info ">Edit</a>
                                                <a href="<?php echo site_url('super_admin/demo_product_delete/' . $val->id) ?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted"><i class="fas fa-box-open fa-2x d-block mb-2"></i> No products found.</td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                </div>
                                <?php if (!empty($pager)) { ?>
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mt-3 flex-wrap">
                                        <div class="text-muted small mb-2">
                                            Showing page <?php echo $pager->getCurrentPage(); ?> of <?php echo $pager->getPageCount(); ?> (Total <?php echo number_format($pager->getTotal()); ?> products)
                                        </div>
                                        <div>
                                            <?= $pager->links(); ?>
                                        </div>
                                    </div>
                                <?php } ?>
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