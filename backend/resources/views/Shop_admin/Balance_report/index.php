<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Balance Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Balance Report</li>
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
                <h3 class="card-title">Balance Report</h3>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-4">
                        <table class="table table-bordered table-striped" >
                            <tbody>
                            <tr>
                                <td colspan="2" style="text-align: center;"><h4 style="font-weight: bold;">Total Assets</h4> </td>
                            </tr>
                            <tr>
                                <td>Cash</td>
                                <td><?php echo showWithCurrencySymbol($cash) ;?></td>
                            </tr>
                            <tr>
                                <td>Bank </td>
                                <td><?php echo showWithCurrencySymbol($bankCash);?></td>
                            </tr>

                            <tr>
                                <td>Accounts Receivable</td>
                                <td><?php echo showWithCurrencySymbol($totalGetCash);?></td>
                            </tr>
                            <tr>
                                <td>Other</td>
                                <td><?php echo showWithCurrencySymbol($otherSaleCash);?></td>
                            </tr>

                            <tr>
                                <td>Vat Earning</td>
                                <td><?php echo showWithCurrencySymbol($vatEarning)?></td>
                            </tr>

                            <tr>
                                <td>Delivery charge</td>
                                <td><?php echo showWithCurrencySymbol($deliCharge);?></td>
                            </tr>

                            <tr>
                                <td>Total Product Price</td>
                                <td><?php echo showWithCurrencySymbol($totalProdPrice)?></td>
                            </tr>

                            <tr>
                                <td>Total Sale Profit</td>
                                <td><?php echo showWithCurrencySymbol($invoiceCash);?></td>
                            </tr>

                            <tr>
                                <td>Total Amount</td>
                                <td><?php echo showWithCurrencySymbol($totalCash);?></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-4">
                        <table class="table table-bordered table-striped" >
                            <tbody>
                            <tr>
                                <td colspan="2" style="text-align: center;"><h4 style="font-weight: bold;">Total Liability</h4></td>
                            </tr>
                            <tr>
                                <td>Accounts Payable</td>
                                <td><?php echo showWithCurrencySymbol($totalDueCash) ?></td>
                            </tr>
                            <tr>
                                <td>Expense</td>
                                <td><?php echo showWithCurrencySymbol($expenseCash) ?></td>
                            </tr>
                            <tr>
                                <td>Salary</td>
                                <td><?php echo showWithCurrencySymbol($employeeBalan)?></td>
                            </tr>
                            <tr>
                                <td>Return Sale Profit</td>
                                <td><?php echo showWithCurrencySymbol($returnProfit);?></td>
                            </tr>
                            <tr>
                                <td>Seller commision</td>
                                <td><?php echo showWithCurrencySymbol($commisionseller)?></td>
                            </tr>
                            <tr>
                                <td>Delivery charge Pay</td>
                                <td><?php echo showWithCurrencySymbol($deliChargePay);?></td>
                            </tr>

                            <tr>
                                <td>Total Amount</td>
                                <td><?php echo showWithCurrencySymbol($totalDue);?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-4">
                        <table class="table table-bordered table-striped" >
                            <tbody>
                            <tr>
                                <td colspan="2" style="text-align: center;"><h4 style="font-weight: bold;">Net Profit and Loss</h4></td>
                            </tr>
                            <tr>
                                <td>Total Assets</td>
                                <td><?php echo showWithCurrencySymbol($totalCash);?></td>
                            </tr>
                            <tr>
                                <td>Total Liability</td>
                                <td><?php echo showWithCurrencySymbol($totalDue);?></td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td><?php echo showWithCurrencySymbol($totalBalance);?></td>
                            </tr>
                            </tbody>
                        </table>
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
