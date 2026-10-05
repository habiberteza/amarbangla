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
                        <li class="breadcrumb-item active">Bank Deposit Create</li>
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
                <h3 class="card-title">Bank Deposit Create</h3>
                <a href="<?= base_url('shop_admin/bank_deposit'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">

                <div class="row">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>
                    <div class="col-md-6">
                        <form action="<?= base_url('shop_admin/bank_deposit_create_action'); ?>" method="post">
                            <div class="form-group">
                                <label for="int">Bank</label>
                                <select class="form-control select2" name="bank_id" required>
                                    <option value="">Please select</option>
                                    <?php echo getTwoValueInOption('','bank_id','name','account_no','bank'); ?>
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="int">Amount </label>
                                <input type="number" class="form-control" name="amount" id="amount" placeholder="Amount" required />
                            </div>
                            <div class="form-group">
                                <label for="int">Comment</label>
                                <input type="text" class="form-control" name="commont" id="commont" placeholder="Comment" required />
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
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
