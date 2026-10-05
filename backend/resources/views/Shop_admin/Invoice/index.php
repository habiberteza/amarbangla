<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Invoice</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Invoice</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Invoice List</h3>
            </div>
            <div class="card-body p-3">
                <table id="example1" class="table table-striped projects">
                    <thead>
                    <tr>
                        <th>Invoice Id </th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th>Delivery Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i=1; foreach ($package as $pac){
                        $amount = $pac->delivery_charge + $pac->price;
                        $customerId = get_data_by_id('customer_id', 'invoice', 'invoice_id', $pac->invoice_id);
                        $invStatus = get_data_by_id('status', 'package', 'package_id', $pac->package_id);
                    ?>
                        <tr>
                            <td><?= $pac->invoice_id;?></td>
                            <td><?= get_data_by_id('customer_name', 'customers', 'customer_id', $customerId);?></td>
                            <td><?= showWithCurrencySymbol($amount);?></td>
                            <td><?= showWithCurrencySymbol($amount);?></td>
                            <td>
                                <?php
                                    if ($pac->status == 0) {
                                        echo '<span class="bg-warning p-1">Unpaid</span>';
                                    } elseif ($pac->status == 1) {
                                        echo '<span class="bg-success p-1">Paid</span>';
                                    } else {
                                        echo '<span class="bg-danger p-1">Cancel</span>';
                                    }
                                ?>
                            </td>
                            <td><?= deliverystatus2($pac->package_id); ?></td>
                            <td width="120">
                                <a href="<?= base_url('shop_admin/invoice_view/' . $pac->package_id); ?>" class="btn btn-xs btn-info ">View</a>
                                <?php if ($pac->status == 0) { ?>
                                <a href="<?= base_url('shop_admin/invoice_package_action/' . $pac->package_id); ?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-warning ">Paid</a>
                                <?php } ?>
                                <a href="<?= base_url('shop_admin/invoice_cancel/' . $pac->package_id); ?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-danger">Cancel</a>
                            </td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
<script>

</script>
<?= $this->endSection() ?>
