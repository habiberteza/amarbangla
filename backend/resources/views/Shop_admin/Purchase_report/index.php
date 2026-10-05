<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Purchase Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Purchase Report</li>
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
                <h3 class="card-title">Purchase Report List</h3>
            </div>
            <div class="card-body p-3">
                <div class="row mb-3 " >
                    <div class="col-md-8">
                        <div class="shadow p-3 mb-5 bg-body rounded">

                            <form action="<?php echo site_url('shop_admin/purchase_report_search'); ?>" method="post">
                                <div class="input-group col-xs-12" style="padding-bottom: 20px;">
                                    <div class="col-md-5">
                                        <label>Start Date</label>
                                        <input type="date" class="form-control" name="st_date" id="date"  required>
                                    </div>
                                    <div class="col-md-5">
                                        <label>End Date</label>
                                        <input type="date" class="form-control" name="en_date" id="date"  required>
                                    </div>
                                    <div class="col-xs-2" style="margin-top: 30px">
                                        <button  class="btn btn-primary " type="submit">Filter</button>
                                    </div>
                                </div>
                            </form>

                            <table class="table table-bordered table-striped "   >
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Purchase Price</th>
                                </tr>
                                </thead>
                                <tbody><?php $i=''; foreach ($purchaseItem as $value) { foreach ($value as $row){ ?>
                                    <tr>
                                        <td width="10px"><?= ++$i ?></td>
                                        <td><?= get_data_by_id('name','products','prod_id',$row->prod_id) ?></td>
                                        <td><?= $row->quantity ?></td>
                                        <td><?= $row->purchase_price ?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>

                            </table>
                        </div>


                    </div>
                    <div class="col-md-4">
                        <div class="shadow p-3 mb-5 bg-body rounded">
                            <table class="table table-bordered table-striped" >
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Suppliers All Purchase Amount</th>
                                </tr>
                                </thead>
                                <tbody><?php $start='';  foreach ($suppliers as $suppliers) { ?>
                                    <tr>
                                        <td width="80px"><?= ++$start ?></td>
                                        <td><?= $suppliers->name ?></td>
                                        <td><?= suppliersTotalPurchaseAmount($suppliers->supplier_id) ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </div>

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
