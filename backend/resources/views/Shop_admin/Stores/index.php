<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Stores</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Stores</li>
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
                <h3 class="card-title">Stores List</h3>
                <a href="<?= base_url('shop_admin/stores_create'); ?>" class="btn btn-xs btn-primary w-25 float-right">Create</a>
            </div>
            <div class="card-body p-3">
                <div class="col-md-12">
                    <?= isset(newSession()->message) ? newSession()->message :''; ?>
                </div>
                <table id="example1" class="table table-striped projects">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach ($store as $val){ ?>
                        <tr>
                            <td width="20"> <?= $i++;?> </td>
                            <td><?= $val->name ?></td>
                            <td><?= $val->description ?></td>
                            <td width="120">
                                <a href="<?= base_url('shop_admin/stores_update/' . $val->store_id); ?>" class="btn btn-xs btn-warning ">Update</a>
                                <?php if (is_default($val->store_id,'store_id', 'stores') != 1) { ?>
                                <a href="<?= base_url('shop_admin/stores_delete/' . $val->store_id); ?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-danger">Delete</a>
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
