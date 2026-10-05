<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Suppliers</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Suppliers transaction</li>
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
                <h3 class="card-title">Supplier transaction List</h3>
                <span class="float-right">
                    <h4>Name: <?php echo  get_data_by_id('name','suppliers','supplier_id', $supplierId); ?>//Phone: <?php echo  showWithPhoneNummberCountryCode(get_data_by_id('phone','suppliers','supplier_id', $supplierId)); ?></h4>
			        <h4> Balance: <?php echo  showWithCurrencySymbol(get_data_by_id('balance','suppliers','supplier_id', $supplierId)); ?></h4>
                </span>
            </div>
            <div class="card-body p-3">
                <table id="example1" class="table table-striped projects">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Date</th>
                        <th>Memo</th>
                        <th>Purchase Id</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($suppLedger as $row){ $purchaseId =($row->purchase_id == NULL)? '<a href="#">TRNS_'.$row->trans_id.'</a>' : '<a href="'.site_url('shop_admin/purchase_view/'.$row->purchase_id).'" >PURS_'.$row->purchase_id.'</a>'; ?>
                        <tr>
                            <td><?= $row->ledg_sup_id ;?></td>
                            <td><?= $row->createdDtm ;?></td>
                            <td><?= ($row->particulars == NULL)? "Purchase": $row->particulars ;?></td>
                            <td><?= $purchaseId;?></td>
                            <td><?= ($row->trangaction_type != 'Dr.')?"---":showWithCurrencySymbol($row->amount) ;?> </td>
                            <td><?= ($row->trangaction_type != 'Cr.')?"---":showWithCurrencySymbol($row->amount) ;?> </td>
                            <td><?= showWithCurrencySymbol($row->rest_balance) ;?></td>
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
