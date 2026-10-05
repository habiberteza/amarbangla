<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product Insert</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Product Insert</li>
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

                            <a href="<?php echo base_url('super_admin/demo_product_list'); ?>" class="btn btn-xs btn-warning float-right"><i class="fas fa-list"></i> Product list</a>
                            <a href="<?php echo base_url('super_admin/demo_product_bulk_upload'); ?>" class="btn btn-xs btn-info float-right mr-2"><i class="fas fa-upload"></i> Bulk Upload</a>
                            <h3 class="card-title">Product Insert</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="row" >
                                <div class="col-md-12" id="message">
                                    <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                </div>
                                <div class="col-md-12 row" >
                                    <div class="form-group col-md-3">
                                        <label for="int">Category </label>
                                        <select class="form-control"  onchange="showSubCategory(this.value,'<?php echo site_url('super_admin/demo_product_get_sub_cat') ?>')" name="category" id="category" required>
                                            <option value="">Please Select</option>
                                            <?php echo getCatListInOptionsuper(''); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="int">Sub Category </label>
                                        <select class="form-control" name="sub_category" id="subCat" required>
                                            <option value="">Please Select</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="varchar">Name </label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" required/>
                                        <input type="hidden" class="form-control" name="qty" id="qty" value="1"/>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="int">Unit</label>
                                        <select class="form-control" name="unit" required>
                                            <?php echo selectOptions('1', unitArray()); ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3 " style="margin-top: 30px;">
                                        <button onclick="addCart()" type="button" class="form-control btn btn-info btn-xs">Add Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <form action="<?php echo site_url('super_admin/demo_product_create_action') ?>" method="POST" >
                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="box" id="box_bac">
                                            <div class="box-header">
                                                <h3 class="box-title">Cart Product List</h3>
                                            </div>
                                            <div class="box-body " id="table-reload" >
                                                <table class="table table-bordered table-striped" id="example2">
                                                    <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Name</th>
                                                        <th>Unit</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $i=''; foreach (Cart()->contents() as $row) {?>
                                                        <tr>
                                                            <td width="20"><input type="hidden" class="form-control" name="prod_cat_id[]" id="prod_cat_id[]"  value="<?php echo $row['cat_id'] ;?>"  /><?php echo ++$i ;?></td>
                                                            <td><input type="hidden" class="form-control" name="name[]" id="name"  value="<?php echo $row['name'] ;?>"  /><?php echo $row['name'] ;?> </td>
                                                            <td><input type="hidden" class="form-control" name="unit[]" id="unit"  value="<?php echo $row['unit'] ;?>"  /><?php echo showUnitName($row['unit']);?> </td>
                                                            <td width="120px">
                                                                <button class="btn btn-xs btn-danger" type="button"    onclick="javasciprt: return confirm('Are You Sure ?'),remove_cart_data('<?php echo $row['rowid']?>')">Cancel</button>
                                                            </td>
                                                        </tr>
                                                    <?php }?>

                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th colspan="8"><button style="float: right; margin-right: 40px;" onclick="javasciprt: return confirm('Are You Sure ?'),clearCart()" class="btn btn-info btn-xs" type="button" >Clear All</button></th>
                                                    </tr>
                                                    </tfoot>

                                                </table>
                                                <!-- <div id="cart"></div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" >
                                        <button type="submit" class="btn btn-primary geniusSubmit-btn" style="float: right;">Insert</button>
                                    </div>
                                </div>
                            </form>


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