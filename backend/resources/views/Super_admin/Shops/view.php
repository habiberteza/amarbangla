<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shops view</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Shop view</li>
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
                            <a href="<?php echo base_url('super_admin/shops_create');?>" class="btn btn-xs btn-primary w-25 float-right">Create</a>
                            <h3 class="card-title">Shops view</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td>Name</td>
                                    <td><?php echo $shops->name;?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><?php echo $shops->email;?></td>
                                </tr>
                                <tr>
                                    <td>Balance</td>
                                    <td><?php echo showWithCurrencySymbol($shops->cash);?></td>
                                </tr>
                                <tr>
                                    <td>Mobile</td>
                                    <td><?php echo $shops->mobile;?></td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td><?php echo $shops->address;?></td>
                                </tr>
                                <tr>
                                    <td>Password</td>
                                    <td><?php echo get_data_by_id('pass','users','sch_id',$shops->sch_id) ;?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><a href="<?php echo site_url('super_admin/shops') ?>" class="btn btn-danger">Cancel</a></td>
                                </tr>
                            </table>
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