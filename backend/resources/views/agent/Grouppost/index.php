<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Group Post</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('agent/dashboard')?>">Home</a></li>
                        <li class="breadcrumb-item active">Group Post</li>
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
                            <h3 class="card-title">Group Post list</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12" id="message">
                                <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                            </div>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Banner one</th>
                                    <th>Banner two</th>
                                    <th>Total like</th>
                                    <th>Total reach</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  foreach ($post as $row){ ?>
                                    <tr>
                                        <td width="20"><?php echo $row->ad_post_id;?></td>
                                        <td><?php echo $row->title ?></td>
                                        <td width="300"><?php echo $row->description ?></td>
                                        <td width="200">
                                            <?php echo image_view('uploads/post', '', $row->banner_1, 'no_image.jpg', 'w-100', '');?>
                                        </td>
                                        <td width="200">
                                            <?php echo image_view('uploads/post', '', $row->banner_2, 'no_image.jpg', 'w-100', '');?>
                                         </td>
                                        <td><?php echo $row->total_like ?></td>
                                        <td><?php echo $row->total_reach ?></td>
                                        <td width="120px">
                                            <a href="<?php echo base_url('agent/group_post_update/'.$row->ad_post_id);?>" class="btn btn-xs btn-warning ">Update</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Banner one</th>
                                    <th>Banner two</th>
                                    <th>Total like</th>
                                    <th>Total reach</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                            </table>
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