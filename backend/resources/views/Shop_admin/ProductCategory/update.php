<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Product Category</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Product Category Update</li>
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
                <h3 class="card-title">Product Category Update</h3>
                <a href="<?= base_url('shop_admin/product_category'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">

                <div class="row">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>
                    <div class="col-md-6">
                        <form action="<?= base_url('shop_admin/product_category_update_action'); ?>" method="post">
                            <div class="form-group">
                                <label for="varchar">Category </label>
                                <input type="text" class="form-control" name="product_category" id="product_category" placeholder="Product Category" value="<?= $cat->product_category; ?>" required >
                            </div>
                            <div class="form-group">
                                <label for="varchar">Parent Category </label>
                                <select class="form-control" name="parent_pro_cat">
                                    <option value="">Please Select</option>
                                    <?= subCategoryListOption($cat->parent_pro_cat,'product_category','product_category'); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="enum">Status </label>
                                <select class="form-control" name="status" id="status">
                                    <?= globalStatus($cat->status); ?>
                                </select>
                            </div>
                            <input type="hidden" class="form-control" name="prod_cat_id" value="<?= $cat->prod_cat_id; ?>" required >

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
