<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Ledger Vat</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Ledger Vat</li>
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
                <h3 class="card-title">Ledger Vat List</h3>
            </div>
            <div class="card-body p-3">
                <table id="example1" class="table table-striped projects">
                    <thead>
                    <tr>
                        <th> # </th>
                        <th>Date</th>
                        <th>Particulars</th>
                        <th>Memo </th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Rest Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($ledgerVat as $row){
                        $amountCr = ($row->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($row->amount);
                        $amountDr =($row->trangaction_type != "Dr.")?"---":showWithCurrencySymbol($row->amount);
                        ?>
                        <tr>
                            <td width="20"> <?= $i++;?> </td>
                            <td><?php echo $row->createdDtm;  ?></td>
                            <td><?php echo $row->particulars ?></td>
                            <td><?php echo $row->ledg_vat_id  ?></td>
                            <td><?php echo $amountDr ?></td>
                            <td><?php echo $amountCr ?></td>
                            <td><?php echo showWithCurrencySymbol($row->rest_balance) ?></td>
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
