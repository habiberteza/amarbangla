<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sales Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Sales Report</li>
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
                <h3 class="card-title">Sales Report List</h3>
                <b class="float-right">Total Profit: <?php echo showWithCurrencySymbol($saleprofit)?></b>
            </div>
            <div class="card-body p-3">

                <form action="<?php echo site_url('shop_admin/sales_report_search'); ?>" method="post">
                    <div class="input-group col-xs-12" style="padding-bottom: 20px;">
                        <div class="col-md-5">
                            <label>Start Date</label>
                            <input type="date" class="form-control" name="st_date" id="date" value="<?= $st_date?>"  required>
                        </div>
                        <div class="col-md-5">
                            <label>End Date</label>
                            <input type="date" class="form-control" name="en_date" id="date" value="<?= $en_date?>" required>
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
                        <th width="150">Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Final Price</th>
                        <th>Profit</th>
                    </tr>
                    </thead>
                    <tbody><?php $i=''; foreach ($sale as $row) { ?>
                        <tr>
                            <td width="10px"><?= ++$i ?></td>
                            <td><?= get_data_by_id('name','products','prod_id',$row->prod_id)?></td>
                            <td><?= showWithCurrencySymbol($row->price) ?></td>
                            <td><?= $row->quantity ?></td>
                            <td><?= showWithCurrencySymbol($row->total_price) ?></td>
                            <td><?= showWithCurrencySymbol($row->final_price) ?></td>
                            <td><?= showWithCurrencySymbol($row->profit) ?></td>
                        </tr>
                        <?php
                    }
                    ?>
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
