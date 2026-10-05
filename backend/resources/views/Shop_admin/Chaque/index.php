<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Chaque</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Chaque</li>
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
                <h3 class="card-title">Chaque List</h3>
            </div>
            <div class="card-body p-3">
                <table id="example1" class="table table-striped projects">
                    <thead>
                    <tr>
                        <th width="20"> # </th>
                        <th>Date</th>
                        <th>Chaque Number</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($chaque as $val){ ?>
                        <tr>
                            <td> <?= $i++;?> </td>
                            <td><?= invoiceDateFormat($val->createdDtm) ?></td>
                            <td><?= $val->chaque_number ?></td>
                            <td><?= $val->amount ?></td>
                            <td><?= $val->status ?></td>
                            <td>
                                <?php  if ($val->status != 'Approved') {?>
                                    <a href="<?= base_url('shop_admin/chaque_paid/' . $val->chaque_id); ?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-warning">Approved</a>
                                <?php }else{ ?>
                                    <button class="btn btn-xs btn-success">Paid</button>
                                <?php } ?>
                            </td>
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
