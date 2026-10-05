<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">বাকির হিসাব</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">বাকির হিসাব</li>
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
                <h3 class="card-title">বাকির হিসাব</h3>
                <a href="<?= base_url('shop_admin/bakir_hishab_create'); ?>" class="btn btn-xs btn-primary w-25 float-right">Create</a>
            </div>
            <div class="card-body p-3">
                <table id="example1" class="table table-striped projects">
                    <thead>
                    <tr>
                        <th> # </th>
                        <th>Account Holder</th>
                        <th>Transaction Type</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($transaction_data as $row){ if ($row->loan_pro_id != NULL) {?>
                        <tr>
                            <td width="20"> <?= $i++;?> </td>
                            <td><?= get_data_by_id('name','loan_provider','loan_pro_id',$row->loan_pro_id);?></td>
                            <td><?= $row->trangaction_type;?></td>
                            <td><?= showWithCurrencySymbol($row->amount);?></td>
                        </tr>
                    <?php } } ?>

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
