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
                        <li class="breadcrumb-item active">Suppliers Create</li>
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
                <h3 class="card-title">Suppliers Create</h3>
                <a href="<?= base_url('shop_admin/suppliers'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">
                <form action="<?= base_url('shop_admin/suppliers_create_action'); ?>" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <?= isset(newSession()->message) ? newSession()->message :''; ?>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="varchar">Name </label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name" required />
                            </div>
                            <div class="form-group">
                                <label for="int">Phone </label>
                                <input type="number" class="form-control" name="phone" id="phone" placeholder="Phone" required />
                            </div>

                            <button type="submit" class="btn btn-primary">Add</button>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address">Address </label>
                                <textarea class="form-control" rows="4" name="address" id="address" placeholder="Address" required></textarea>
                            </div>
                        </div>
                    </div>
                </form>

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
