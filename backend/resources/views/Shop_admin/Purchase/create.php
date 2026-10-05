<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Purchase create</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Purchase create</li>
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
                <h3 class="card-title">Purchase create</h3>
                <a href="<?= base_url('shop_admin/purchase'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-6 ">
                        <form action="<?= base_url('shop_admin/purchase_create_action'); ?>" method="post">
                            <div class="form-group">
                                <label for="int">Type </label>
                                <select class="form-control" name="type_id" required >
                                    <option value="" >Please select</option>
                                    <option value="1">New Item</option>
                                    <option value="2">Existing Item</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="int">Supplier </label>
                                <select class="form-control" name="supplier_id" required>
                                    <option value="">Please select</option>
                                    <?php echo getAllListInOption('','supplier_id','name','suppliers'); ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Create</button>
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
