<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>General settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">General settings</li>
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
                            <h3 class="card-title">General settingst</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12" id="message">
                                    <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                </div>

                                <div class="col-md-6 text-capitalize ">
                                    <form action="<?php echo base_url('super_admin/update_action')?>" method="post">
                                        <?php foreach ($settings as $geninfo) {
                                            ?>
                                            <div class="form-group">
                                                <label for="longtext"><?php print str_replace("_", " ", "$geninfo->label");; ?></label>
                                                <input type="hidden" name="id[]" value="<?php print $geninfo->settings_id_sup; ?>">
                                                <input type="text" class="form-control" name="value[]" id="" value="<?php print $geninfo->value; ?>"/>

                                            </div>
                                        <?php } ?>

                                        <button type="submit" class="btn btn-primary">Update</button>
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