<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Logo</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Logo</li>
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
                            <h3 class="card-title">Logo</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="<?php echo base_url('super_admin/website_settings/logo_action'); ?>" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                    </div>

                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <?php echo image_view('uploads/website_image','',$logoData->value,'no_image.jpg','','');?>

                                        </div>

                                        <div class="form-group">
                                            <label class="control-label ">Upload Image</label>
                                            <div class="row" id="coba2"></div>

                                        </div>

                                        <button type="submit" class="btn btn-primary">Update</button>
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