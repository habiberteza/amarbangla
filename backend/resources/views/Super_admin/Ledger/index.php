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
                            <form action="<?= base_url('super_admin/ledger')?>" method="get">
                                <div class="row mb-3 align-items-end" >
                                    <div class="col-md-3 mb-2"  >
                                        <label>Shop</label>
                                        <select name="shop" class="form-control select2">
                                            <option value="">All Shops</option>
                                            <?php foreach ($shops as $row){ ?>
                                                <option value="<?= $row->sch_id;?>" <?= (!empty($selected_shop) && $selected_shop == $row->sch_id) ? 'selected' : ''; ?>><?= $row->name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2"  >
                                        <label>Start Date</label>
                                        <input type="date" class="form-control" name="st_date" id="st_date" value="<?= esc($st_date ?? ''); ?>">
                                    </div>
                                    <div class="col-md-3 mb-2"  >
                                        <label>End Date</label>
                                        <input type="date" class="form-control" name="en_date" id="en_date" value="<?= esc($en_date ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 mb-2">
                                        <label>Per Page</label>
                                        <select name="per_page" class="form-control">
                                            <option value="25" <?= (isset($per_page) && $per_page == 25) ? 'selected' : ''; ?>>25</option>
                                            <option value="50" <?= (isset($per_page) && $per_page == 50) ? 'selected' : ''; ?>>50</option>
                                            <option value="100" <?= (isset($per_page) && $per_page == 100) ? 'selected' : ''; ?>>100</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2 d-flex">
                                        <button class="btn btn-primary mr-1" type="submit"><i class="fas fa-filter"></i> Filter</button>
                                        <a href="<?= base_url('super_admin/ledger'); ?>" class="btn btn-secondary"><i class="fas fa-undo"></i> Reset</a>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                            <table id="ledger_table" class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
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
                                <?php if (!empty($ledger)) { foreach ($ledger as $val){
                                    $particulars = ($val->particulars == NULL) ? "Payment" : $val->particulars;
                                    $amountCr = ($val->trangaction_type != "Cr.") ? "---" : showWithCurrencySymbol($val->amount);
                                    $amountDr =($val->trangaction_type != "Dr.")?"---":showWithCurrencySymbol($val->amount);
                                ?>
                                    <tr>
                                        <td><?php echo $val->sup_ledg_id;?></td>
                                        <td><strong><?php echo get_data_by_id('name','shops','sch_id',$val->sch_id);?></strong></td>
                                        <td><?php echo $val->createdDtm;?></td>
                                        <td><?php echo $particulars ?></td>
                                        <td class="text-danger"><?php echo $amountDr ?></td>
                                        <td class="text-success"><?php echo $amountCr ?></td>
                                        <td><strong><?php echo showWithCurrencySymbol($val->rest_balance) ?></strong></td>
                                    </tr>
                                <?php } } else { ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted"><i class="fas fa-book fa-2x d-block mb-2"></i> No ledger entries found.</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            </div>
                            <?php if (!empty($pager)) { ?>
                                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                                    <div class="text-muted small mb-2">
                                        Showing page <?= $pager->getCurrentPage(); ?> of <?= $pager->getPageCount(); ?> (Total <?= number_format($pager->getTotal()); ?> transactions)
                                    </div>
                                    <div>
                                        <?= $pager->links(); ?>
                                    </div>
                                </div>
                            <?php } ?>
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