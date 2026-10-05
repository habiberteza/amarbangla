<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Delivery boy ledger</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('agent/dashboard')?>">Home</a></li>
                        <li class="breadcrumb-item active">Delivery boy ledger</li>
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
                            <h3 class="card-title">Delivery boy ledger</h3>
                            <a href="<?= base_url('agent/delivery_boy'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Memo</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($ledger as $val) {
                                    $particulars = ($val->particulars == NULL) ? "Pay due" : $val->particulars;
                                    $amountCr = ($val->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($val->amount);
                                    $amountDr =($val->trangaction_type != "Dr.")?"---":showWithCurrencySymbol($val->amount);
                                    $transId =($val->trans_id == NULL)?"---":$val->trans_id;
                                    $purchaseId =($val->invoice_id == NULL)? $val->trans_id : $val->invoice_id;
                                    ?>
                                    <tr>
                                        <td><?php echo $val->ledg_delivery_boy_id?></td>
                                        <td><?php echo $val->createdDtm?></td>
                                        <td><?php echo $particulars?></td>
                                        <td><?php echo $purchaseId?></td>
                                        <td><?php echo $amountDr?></td>
                                        <td><?php echo $amountCr?></td>
                                        <td><?php echo showWithCurrencySymbol($val->rest_balance)?></td>
                                    </tr>
                                <?php }?>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Memo</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
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
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
    <script>

    </script>
<?= $this->endSection() ?>