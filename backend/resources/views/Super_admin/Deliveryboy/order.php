<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Delivery boy order</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Delivery boy order</li>
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
                            <a href="<?php echo base_url('super_admin/delivery_boy');?>" class="btn btn-xs btn-danger float-right">Back</a>
                            <h3 class="card-title">Delivery boy order list</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i=1; foreach ($orderData as $row) {
                                    $cus_id = get_data_by_id('customer_id','invoice','invoice_id',$row->invoice_id);
                                    $glo_add = get_data_by_id('global_address_id','invoice','invoice_id',$row->invoice_id);
                                    $address = global_address_id_by_address($glo_add);
                                    ?>
                                    <tr>
                                        <td width="20px"><?php echo $i++; ?></td>
                                        <td><?php echo get_data_by_id('customer_name','customers','customer_id',$cus_id)?></td>
                                        <td><?php echo get_data_by_id('mobile','customers','customer_id',$cus_id)?></td>
                                        <td>
                                            <b>District:</b> <?php echo districtname($address->zila); ?>
                                            <b>Upazila:</b> <?php echo upazilaname($address->upazila); ?> <br>
                                            <b>Pourashava/Union:</b> <?php echo pourashavaUnionName($address->pourashava); ?>
                                            <br><b>Ward:</b> <?php echo getwardname($address->ward); ?>
                                            <b>Home Address:</b> <?php echo get_data_by_id('address','invoice','invoice_id',$row->invoice_id); ?>
                                        </td>
                                        <td><?php
                                            if ($row->status == 0) {
                                                echo '<span class="label bg-primary">Accepted</span>';
                                            }
                                            if ($row->status == 1) {
                                                echo '<span class="label bg-success">Complete</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
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