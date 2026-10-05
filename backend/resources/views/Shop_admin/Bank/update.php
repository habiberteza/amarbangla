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
                        <li class="breadcrumb-item active">Bank Update</li>
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
                <h3 class="card-title">Bank Update</h3>
                <a href="<?= base_url('shop_admin/bank'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">

                <div class="row">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>
                    <div class="col-md-6">
                        <form action="<?= base_url('shop_admin/bank_update_action'); ?>" method="post">
                            <div class="form-group">
                                <label for="varchar">Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?= $bank->name; ?>"  required />
                            </div>
                            <div class="form-group">
                                <label for="varchar">Account No</label>
                                <input type="text" class="form-control" name="account_no" id="account_no" placeholder="Account No" value="<?= $bank->account_no; ?>" required />
                            </div>
                            <input type="hidden" name="bank_id" value="<?= $bank->bank_id; ?>" />
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>

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
