<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bank</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Bank</li>
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
                <h3 class="card-title">Bank List</h3>
                <a href="<?= base_url('shop_admin/bank_create'); ?>" class="btn btn-xs btn-primary w-25 float-right">Create</a>
            </div>
            <div class="card-body p-3">
                <table id="example1" class="table table-striped projects">
                    <thead>
                    <tr>
                        <th> # </th>
                        <th>Name </th>
                        <th>Account No</th>
                        <th>Balance</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($bank as $val){ ?>
                        <tr>
                            <td width="20"> <?= $i++;?> </td>
                            <td><?= $val->name;?></td>
                            <td><?= $val->account_no;?></td>
                            <td><?= showWithCurrencySymbol($val->balance);?></td>
                            <td width="120">
                                <a href="<?= base_url('shop_admin/bank_update/' . $val->bank_id); ?>" class="btn btn-xs btn-warning ">Update</a>
                                <a href="<?= base_url('shop_admin/bank_delete/' . $val->bank_id); ?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-danger">Delete</a>
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
