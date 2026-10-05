<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product color update</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Product color update</li>
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
                            <h3 class="card-title">Product color update</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                </div>
                                <div class="col-md-6">
                                    <form action="<?php echo base_url('super_admin/product_color_update_action'); ?>" method="post">
                                        <div class="form-group">
                                            <label for="varchar">Color Name  </label>
                                            <input type="text" class="form-control" name="color_name" id="color_name" value="<?php echo $color->color_name;?>"  required>
                                        </div>
                                        <div class="form-group">
                                            <label for="enum">Color Code </label>
                                            <input type="text" class="form-control" name="code" id="code" value="<?php echo $color->code;?>" required>
                                        </div>

                                        <input type="hidden"  name="color_family_id"  value="<?php echo $color->color_family_id;?>" required>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="<?php echo site_url('super_admin/product_color') ?>" class="btn btn-danger">Cancel</a>
                                    </form>

                                </div>
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