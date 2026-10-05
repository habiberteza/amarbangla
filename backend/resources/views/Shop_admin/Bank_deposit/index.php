<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bank</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Bank Deposit</li>
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
                <h3 class="card-title">Bank Deposit List</h3>
                <a href="<?= base_url('shop_admin/bank_deposit_create'); ?>" class="btn btn-xs btn-primary w-25 float-right">Deposit</a>
            </div>
            <div class="card-body p-3">
                <table id="example1" class="table table-striped projects">
                    <thead>
                    <tr>
                        <th width="20"> # </th>
                        <th>Bank Name</th>
                        <th>Amount</th>
                        <th>Comment</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($bankDeposit as $val){ ?>
                        <tr>
                            <td> <?= $i++;?> </td>
                            <td><?= get_data_by_id('name', 'bank', 'bank_id',$val->bank_id);?></td>
                            <td><?= showWithCurrencySymbol($val->amount);?></td>
                            <td><?= $val->commont;?></td>
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
