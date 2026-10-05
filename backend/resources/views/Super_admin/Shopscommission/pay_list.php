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
                            <form action="<?php echo site_url('super_admin/shops_commission_confirm') ?>" method="POST">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>
                                            <input type="checkbox" id="selectall" name="selectall" autocomplete="off"  onclick="eventCheckBox()">
                                            <span>Select All</span>
                                        </th>
                                        <th>InvoiceId</th>
                                        <th>Percent</th>
                                        <th>Commission</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1; foreach ($shopsIn as $row){ ?>
                                        <tr>
                                            <td><?php echo $i++ ?></td>
                                            <td><input class="form-check-input"  type="checkbox" value="<?php echo $row->invoice_id ?>" name="invoiceId[]" ></td>
                                            <td><?php echo $row->invoice_id; ?></td>
                                            <td><?php echo $row->percent; ?> %</td>
                                            <td><?php echo showWithCurrencySymbol($row->commission); ?></td>
                                            <td><?php
                                                if ($row->status == 0) {echo '<span class="label label-warning">Unpaid</span>';}
                                                if ($row->status == 1) {echo '<span class="label label-success">Paid</span>';}
                                                if ($row->status == 2) {echo '<span class="label label-info">Pending</span>';}
                                                ?>
                                            </td>
                                            <td><a href="<?php echo site_url('super_admin/shops_commission_cancel/'.$row->invoice_id) ?>" class="btn btn-danger btn-xs" >Cancel</a></td>

                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th> </th>
                                        <th>InvoiceId</th>
                                        <th>Percent</th>
                                        <th>Commission</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="col-md-12 mt-4">
                                    <small class="float-right" ><button type="submit" class="btn btn-primary">Confirm</button></small>
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