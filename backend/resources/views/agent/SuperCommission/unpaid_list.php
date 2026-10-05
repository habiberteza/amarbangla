<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Unpaid Commission</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('agent/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Unpaid Commission</li>
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
                <h3 class="card-title">Unpaid Commission List</h3>
                <a href="<?= base_url('agent/super_commission'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">
                <table id="example1" class="table table-striped projects">
                    <thead>
                    <tr>
                        <th> # </th>
                        <th>InvoiceId</th>
                        <th>Percent</th>
                        <th>Commission</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($commission as $row){ ?>
                        <tr>
                            <td width="20"> <?= $i++;?> </td>
                            <td><?= $row->invoice_id; ?></td>
                            <td><?= $row->percent; ?> %</td>
                            <td><?= showWithCurrencySymbol($row->commission); ?></td>
                            <td>
                                <?php
                                if ($row->status == 0) {echo '<span class="bg bg-warning">Unpaid</span>';}
                                if ($row->status == 1) {echo '<span class="bg bg-success">Paid</span>';}
                                if ($row->status == 2) {echo '<span class="bg bg-info">Pending</span>';}
                                ?>
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
