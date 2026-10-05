<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Campaign</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Campaign Create</li>
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
                <h3 class="card-title">Campaign Create</h3>
                <a href="<?= base_url('shop_admin/campaign'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">

                <div class="row">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>
                    <div class="col-md-6">
                        <form action="<?= base_url('shop_admin/campaign_create_action'); ?>" method="post" enctype="multipart/form-data" >
                            <div class="form-group">
                                <label for="varchar">Title  </label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="title" required/>
                            </div>

                            <div class="form-group">
                                <label for="varchar">Product </label>
                                <select class="form-control select2 " style=" width: 100%;"  name="prod_id" required>
                                    <option value="">Please Select</option>
                                    <?php foreach ($product as $row) { ?>
                                        <option value="<?php echo $row->prod_id; ?>"><?php echo $row->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="varchar">Start date  </label>
                                <input type="date" class="form-control" name="start_date" id="start_date" required/>
                            </div>

                            <div class="form-group">
                                <label for="varchar">End date </label>
                                <input type="date" class="form-control" name="end_date" id="end_date" required/>
                            </div>
                            <div class="form-group">
                                <label for="varchar">Description </label>
                                <textarea class="form-control" rows="5" name="description"  placeholder="Description" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="varchar">Image </label>
                                <input type="file" class="form-control" name="image" id="imageFile" onchange="Upload()" required/>
                                <b>Size: 390x100</b><br>
                                <span id="validImg"></span>
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
