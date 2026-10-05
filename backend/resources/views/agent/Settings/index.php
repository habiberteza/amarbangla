<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Settings</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('agent/dashboard')?>">Home</a></li>
                            <li class="breadcrumb-item active">Settings</li>
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
                                <h3 class="card-title">Settings</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <form action="<?php echo base_url('agent/settings_update_action'); ?>" method="post">
                                            <div class="form-group">
                                                <label for="varchar">Name </label>
                                                <input type="text" class="form-control" name="agent_name" id="name" placeholder="name" value="<?= Auth_agent()->agent_name; ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="varchar">Email </label>
                                                <input type="text" class="form-control" name="email" id="email" placeholder="email" value="<?= Auth_agent()->email; ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="varchar">Mobile </label>
                                                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" value="<?= Auth_agent()->mobile; ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="longtext">Password </label>
                                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="" required />
                                            </div>
                                            <div class="form-group">
                                                <label for="longtext">Confirm Password </label>
                                                <input type="password" class="form-control " name="con_password" id="password" placeholder="Confirm Password" required />
                                            </div>

                                            <div class="form-group">
                                                <label for="longtext">Order Management Numbers</label>
                                                <input type="text" class="form-control " name="order_management_numbers" value="<?= $order_management_numbers;?>" placeholder="Order Management Numbers" />
                                            </div>

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
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
    <script>

    </script>
<?= $this->endSection() ?>