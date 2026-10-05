<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daily Book</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Daily Book</li>
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

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h3 class="card-title">Daily Book</h3>
                    </div>
                    <div class="col-md-6 mb-3">
                        <form action="<?php echo site_url('shop_admin/daily_book_search'); ?>" method="post">
                            <div class="input-group col-xs-4 pull-right">
                                <span class="input-group-addon " style="background-color:#367FA9; padding: 7px 15px; "><i class="fa fa-fw fa-filter" style="color: white;"></i></span>
                                <input type="date" class="form-control " name="date" id="date" value="<?= isset($date)?$date:'';?>"  required>
                                <span class="input-group-btn"><button  class="btn btn-primary " type="submit">Filter</button></span>
                            </div>
                        </form>
                    </div>

            </div>
            <div class="card-body p-3">
                <div class="row">

                    <div class="col-md-6">
                        <h3 class="box-title">Cash Ledger</h3>
                        <p class="pull-right mb-3">Last Balance <?php  echo showWithCurrencySymbol($cashrest_balance) ;?></p>

                        <table id="example2" class="table table-striped projects ">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Particulars</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Balance</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i=1; foreach ($cashLedger as $row){
                                $particulars = ($row->particulars == NULL) ? "Payment" : $row->particulars;
                                $amountCr = ($row->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($row->amount);
                                $amountDr =($row->trangaction_type != "Dr.")?"---":showWithCurrencySymbol($row->amount);

                                ?>
                                <tr>
                                    <td><?= bdDateFormat($row->createdDtm) ?></td>
                                    <td><?= $particulars ?></td>
                                    <td><?= $amountDr ?></td>
                                    <td><?= $amountCr ?></td>
                                    <td><?= showWithCurrencySymbol($row->rest_balance) ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="box-title">Bank Ledger</h3>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control select2 " onchange="bankLedgdaily(this.value)"   >
                                    <option selected="selected"  value="">Please Select</option>
                                    <?php echo getTwoValueInOption('','bank_id','name','account_no','bank'); ?>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <span id="bankdaileyLedg"></span>
                            </div>
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
    function bankLedgdaily(id){
        var date = $("#date").val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('shop_admin/daily_book_search_bank_ledger') ?>",
            data: {id:id, date:date},
            success: function(html){
                $("#bankdaileyLedg").html(html);
                $('#example3').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>
