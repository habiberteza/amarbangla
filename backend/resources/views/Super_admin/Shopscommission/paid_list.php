<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shops Commission</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Shops Commission list</li>
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
                            <h3 class="card-title">Shops Commission list</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>InvoiceId</th>
                                    <th>Percent</th>
                                    <th>Commission</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  foreach ($shopsIn as $val){ ?>
                                    <tr>
                                        <td><?php echo $val->sup_commi_inv_id;?></td>
                                        <td><?php echo $val->invoice_id;?></td>
                                        <td><?php echo $val->percent; ?> %</td>
                                        <td><?php echo showWithCurrencySymbol($val->commission); ?></td>
                                        <td><?php
                                            if ($val->status == 0) {echo '<span class="label label-warning">Unpaid</span>';}
                                            if ($val->status == 1) {echo '<span class="label label-success">Paid</span>';}
                                            if ($val->status == 2) {echo '<span class="label label-info">Pending</span>';}
                                            ?>
                                        </td>

                                    </tr>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>InvoiceId</th>
                                    <th>Percent</th>
                                    <th>Commission</th>
                                    <th>Status</th>
                                </tr>
                                </tfoot>
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