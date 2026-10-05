<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shops create</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Shop create</li>
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
                            <h3 class="card-title">Shops create</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                </div>
                                <div class="col-md-6">
                                    <form action="<?php echo base_url('super_admin/shops_create_action'); ?>" method="post">
                                        <div class="form-group">
                                            <label for="varchar">Name </label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Name" required/>
                                        </div>
                                        <div class="form-group">
                                            <label for="int">Email </label>
                                            <input type="text" class="form-control" name="email" id="email" placeholder="Email" required/>
                                        </div>
                                        <div class="form-group">
                                            <label for="longtext">Password </label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="longtext">Confirm Password </label>
                                            <input type="password" class="form-control" name="con_password" id="password" placeholder="Confirm Password"  required />
                                        </div>

                                        <div class="form-group">
                                            <label for="enum">Status </label>
                                            <select class="form-control" name="status" id="status"  >
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Create</button>
                                        <a href="<?php echo site_url('super_admin/shops') ?>" class="btn btn-danger">Cancel</a>
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