<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Seller Commission</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('agent/dashboards')?>">Home</a></li>
                        <li class="breadcrumb-item active">Seller Commission</li>
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
                            <h3 class="card-title">Seller Commission</h3>
                            <a href="<?= base_url('agent/sellers'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="commTotalDetail">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Invoice Id</th>
                                    <th>Commission</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody id="commDetail">
                                    <?php $j = 1;foreach ($commission as $row) { ?>
                                    <tr>
                                        <td><?php echo $j++?></td>
                                        <td><?php echo invoiceDateFormat($row->createdDtm)?> </td>
                                        <td><?php echo $row->invoice_id ?></td>
                                        <td><?php echo showWithCurrencySymbol($row->commission) ?></td>
                                        <td><?php if ($row->com_status == 0) { ?>
                                                <?php echo '<span class="label bg-warning p-1">pending</span>'; ?>
                                            <?php }else{ ?>
                                                <?php echo '<span class="label bg-success p-1">paid</span>'; ?>
                                            <?php } ?></td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
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