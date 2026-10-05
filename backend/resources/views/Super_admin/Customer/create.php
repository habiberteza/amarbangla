<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Customer create</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Customer create</li>
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
                            <h3 class="card-title">Customer create</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                </div>
                                <div class="col-md-6">
                                    <form action="<?php echo base_url('super_admin/customer_action'); ?>" method="post">
                                        <div class="form-group">
                                            <label for="varchar">Customer Name </label>
                                            <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Customer Name" >
                                        </div>
                                        <div class="form-group">
                                            <label for="int">Mobile </label>
                                            <input type="number" class="form-control" name="mobile" id="mobile" placeholder="Mobile" >
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label for="int">Password </label>
                                            <input type="password" class="form-control" placeholder="Password" name="password" required  >
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label for="int">Confirm Password </label>
                                            <input type="password" class="form-control" placeholder="Conform Password" name="con_password" required  >
                                        </div>
                                        <div class="form-group">
                                            <label for="int">CusTypeID</label>

                                            <select class="form-control" name="cus_type_id" >
                                                <option value="">Please Select</option>

                                                <?php foreach ($customers_type as $row) {?>
                                                    <option value="<?php echo $row->cus_type_id ?>"><?php echo $row->type_name ?> </option>
                                                <?php }?>

                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Create</button>
                                        <a href="<?php echo site_url('super_admin/customer') ?>" class="btn btn-danger">Cancel</a>
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