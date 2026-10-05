<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Ledger Cash</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Ledger Cash</li>
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
                <h3 class="card-title">Ledger Cash List</h3>
            </div>
            <div class="card-body p-3">
                <div class="row mb-3 " >
                    <div class="col-md-5"  >
                        <label>Start Date</label>
                        <input type="date" class="form-control" name="st_date" id="st_date" >
                    </div>
                    <div class="col-md-5"  >
                        <label>End Date</label>
                        <input type="date" class="form-control" name="en_date" id="en_date" >
                    </div>
                    <div class="col-md-2" style="padding: 4px;">
                        <button style="margin-top: 28px;" onclick="nagodLedSerc()" class="btn btn-primary " type="submit">Filter</button>
                    </div>

                </div>
                <div id="ledger_cash">
                    <table id="example1" class="table table-striped projects">
                        <thead>
                        <tr>
                            <th> # </th>
                            <th>Date</th>
                            <th>Particulars</th>
                            <th>Trangaction Id</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=1; foreach ($ledgerNagod as $row){
                            $particulars = ($row->particulars == NULL) ? "Payment" : $row->particulars;
                            $amountCr = ($row->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($row->amount);
                            $amountDr =($row->trangaction_type != "Dr.")?"---":showWithCurrencySymbol($row->amount);
                            ?>
                            <tr>
                                <td width="20"> <?= $i++;?> </td>
                                <td><?php echo $row->createdDtm ?></td>
                                <td><?php echo $particulars ?></td>
                                <td><?php echo $row->trans_id ?></td>
                                <td><?php echo $amountDr ?></td>
                                <td><?php echo $amountCr ?></td>
                                <td><?php echo showWithCurrencySymbol($row->rest_balance) ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
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
    function nagodLedSerc()
    {
        var st_date = $('#st_date').val();
        var en_date = $('#en_date').val();
        var error = 0;
        $('#st_date').css("border-color", "white");
        $('#st_date').css("background-color", "#ccc");
        $('#en_date').css("border-color", "white");
        $('#en_date').css("background-color", "#ccc");


        if (st_date == '') {
            $('#st_date').css("border-color", "red");
            $('#st_date').css("background-color", "#ff9393");
            error = 1;
        }
        if (en_date == '') {
            $('#en_date').css("border-color", "red");
            $('#en_date').css("background-color", "#ff9393");
            error = 1;
        }

        if (error == 0){

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('shop_admin/ledger_nagodan_search') ?>",
                data: {st_date:st_date,en_date:en_date},
                success: function(val){
                    $("#ledger_cash").html(val).show();
                    $("#example1").DataTable({
                        "responsive": true, "lengthChange": true, "autoWidth": false,
                        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                }
            });
        }
    }
</script>
<?= $this->endSection() ?>
