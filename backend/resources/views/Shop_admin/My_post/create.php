<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">My Post</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">My Post Create</li>
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
                <h3 class="card-title">My Post Create</h3>
                <a href="<?= base_url('shop_admin/my_post'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
            </div>
            <div class="card-body p-3">

                <div class="row">
                    <div class="col-md-12">
                        <?= isset(newSession()->message) ? newSession()->message :''; ?>
                    </div>
                    <div class="col-md-6">
                        <form action="<?= base_url('shop_admin/my_post_create_action'); ?>" method="post" enctype="multipart/form-data" >
                            <div class="form-group">
                                <label for="varchar">Title </label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Title" required/>
                            </div>

                            <div class="form-group">
                                <label for="varchar">Banner one </label>
                                <input type="file" class="form-control" name="banner_1"  />
                            </div>

                            <div class="form-group">
                                <label for="varchar">Banner two </label>
                                <input type="file" class="form-control" name="banner_2" id="banner_2"  />
                            </div>

                            <div class="form-group">
                                <label for="varchar">Youtube video embed </label><br>
                                <small>https://www.youtube.com/embed/00000</small>
                                <input type="text" class="form-control" name="youtube_video" id="youtube_video"  />
                            </div>

                            <div class="form-group">
                                <label for="varchar">Facebook video embed </label>
                                <input type="text" class="form-control" name="facebook_video" id="facebook_video"  />
                            </div>

                            <div class="form-group">
                                <label for="varchar">Description </label>
                                <textarea id="editor" rows="6" name="description" style="width: 100%;" ></textarea>
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
