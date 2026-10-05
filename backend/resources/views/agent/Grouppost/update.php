<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Group Post update</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('agent/dashboards')?>">Home</a></li>
                        <li class="breadcrumb-item active">Group Post update</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Group Post update</h3>
                            <a href="<?= base_url('agent/group_post'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="<?php echo base_url('agent/group_post_update_action'); ?>" method="post" enctype="multipart/form-data" >
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="varchar">Title </label>
                                            <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?php echo $post->title ?>" required/>
                                        </div>

                                        <div class="form-group">
                                            <?php echo image_view('uploads/post', '', $post->banner_1, 'no_image.jpg', 'w-25', '');?>
                                        </div>
                                        <div class="form-group">
                                            <label for="varchar">Banner one </label>
                                            <input type="file" class="form-control" name="banner_1" />
                                        </div>
                                        <div class="form-group">
                                            <?php echo image_view('uploads/post', '', $post->banner_2, 'no_image.jpg', 'w-25', '');?>
                                        </div>
                                        <div class="form-group">
                                            <label for="varchar">Banner two </label>
                                            <input type="file" class="form-control" name="banner_2" id="banner_2" />
                                        </div>

                                        <input type="hidden"  name="ad_post_id"  value="<?php echo $post->ad_post_id;?>" required>
                                        <button type="submit" class="btn btn-primary">Update</button>

                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="varchar">Youtube video </label>
                                            <input type="text" class="form-control" name="youtube_video" id="youtube_video" value="<?php echo $post->youtube_video ?>" />
                                        </div>

                                        <div class="form-group">
                                            <label for="varchar">Facebook video </label>
                                            <input type="text" class="form-control" name="facebook_video" id="facebook_video" accept-charset="utf-8" value="<?php print htmlspecialchars($post->facebook_video) ?>" />
                                        </div>

                                        <div class="form-group">
                                            <label for="varchar">Description </label>
                                            <textarea id="editor" rows="6" name="description" style="width: 100%;" ><?php echo $post->description ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
    <script>

    </script>
<?= $this->endSection() ?>