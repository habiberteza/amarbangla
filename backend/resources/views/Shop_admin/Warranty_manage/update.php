<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Warranty Manage</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Warranty Manage Update</li>
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
                <h3 class="card-title">Warranty Manage Update</h3>
                <a href="<?= base_url('shop_admin/warranty_manage'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">
                <form action="<?= base_url('shop_admin/warranty_manage_update_action'); ?>" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <?= isset(newSession()->message) ? newSession()->message :''; ?>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="varchar">Product Name </label>
                                <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Product Name" value="<?= $warranty->product_name;?>" required/>
                            </div>
                            <div class="form-group">
                                <label for="datetime">Receive Date </label>
                                <input type="date" class="form-control" name="receive_date" id="receive_date" placeholder="Receive Date" value="<?= $warranty->receive_date;?>" required />
                            </div>
                            <div class="form-group">
                                <label for="datetime">Delivery Date </label>
                                <input type="date" class="form-control" name="delivery_date" id="delivery_date" placeholder="Delivery Date" value="<?= $warranty->delivery_date;?>" required />
                            </div>

                            <input type="hidden"  name="warranty_id"   value="<?= $warranty->warranty_id;?>" required/>

                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="varchar">Customer Address </label>
                                <input type="text" class="form-control" name="customer_address" id="customer_address" placeholder="Customer Address" value="<?= $warranty->customer_address;?>" required />
                            </div>
                            <div class="form-group">
                                <label for="varchar">Customer Name  </label>
                                <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Customer Name" value="<?= $warranty->customer_name;?>" required />
                            </div>
                            <div class="form-group">
                                <label for="int">Mobile  </label>
                                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" value="<?= $warranty->mobile;?>" required/>
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
