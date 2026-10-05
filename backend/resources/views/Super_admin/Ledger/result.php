<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ledger</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Ledger</li>
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
                            <h3 class="card-title">Ledger list</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12">
                                <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                            </div>
                            <form action="<?= base_url('super_admin/ledger_filter')?>" method="post">
                                <div class="row mb-3 " >
                                    <div class="col-md-3"  >
                                        <label>Shops</label>
                                        <select name="shop" class="form-control select2">
                                            <option value="">Please select</option>
                                            <?php foreach ($shops as $row){ ?>
                                                <option value="<?= $row->sch_id;?>" <?= ($sch_id == $row->sch_id)?'selected':'';?> ><?= $row->name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3"  >
                                        <label>Start Date</label>
                                        <input type="date" class="form-control" name="st_date" id="st_date" value="<?= $st_date;?>" >
                                    </div>
                                    <div class="col-md-3"  >
                                        <label>End Date</label>
                                        <input type="date" class="form-control" name="en_date" id="en_date" value="<?= $en_date;?>" >
                                    </div>
                                    <div class="col-md-3" style="padding: 4px;">
                                        <button style="margin-top: 28px;"  class="btn btn-primary " type="submit">Filter</button>
                                    </div>
                                </div>
                            </form>

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Shop</th>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  foreach ($ledger as $val){
                                    $particulars = ($val->particulars == NULL) ? "Payment" : $val->particulars;
                                    $amountCr = ($val->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($val->amount);
                                    $amountDr =($val->trangaction_type != "Dr.")?"---":showWithCurrencySymbol($val->amount);
                                ?>
                                    <tr>
                                        <td><?php echo $val->sup_ledg_id;?></td>
                                        <td><?php echo get_data_by_id('name','shops','sch_id',$val->sch_id);?></td>
                                        <td><?php echo $val->createdDtm;?></td>
                                        <td><?php echo $particulars ?></td>
                                        <td><?php echo $amountDr ?></td>
                                        <td><?php echo $amountCr ?></td>
                                        <td><?php echo showWithCurrencySymbol($val->rest_balance) ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Shop</th>
                                    <th>Date</th>
                                    <th>Particulars</th>
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