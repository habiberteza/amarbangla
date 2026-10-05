<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product bulk upload</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Product bulk upload</li>
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
                            <a href="<?php echo base_url('super_admin/demo_product'); ?>" class="btn btn-xs btn-danger float-right"> Back</a>
                            <h3 class="card-title">Product Update</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row" >
                                <div class="col-md-12" id="message">
                                    <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                </div>


                                <div class="col-md-6">
                                    <form action="<?php echo base_url('super_admin/demo_product_bulk_upload_action'); ?>" method="post" enctype="multipart/form-data" >
                                        <div class="form-group">
                                            <label for="varchar">File </label>
                                            <input type="file" name="file" class="form-control" accept=".csv">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <a href="<?= base_url('uploads/product.csv')?>"  class="btn btn-info mt-4 float-right" >Example CSV file</a>
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