<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Campaign</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('agent/dashboard')?>">Home</a></li>
                        <li class="breadcrumb-item active">Campaign</li>
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
                            <h3 class="card-title">Campaign list</h3>
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
                                    <th>Image</th>
                                    <th>Start date</th>
                                    <th>End date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1; $j=1; foreach ($campaign as $val){ ?>
                                    <tr>
                                        <td width="20"><?php echo $val->campaign_id;?></td>
                                        <td><?php echo $val->title ?></td>
                                        <td><?php echo $val->description ?></td>
                                        <td width="200">
                                            <?php echo image_view('uploads/campaign', '', $val->image, 'no_image.jpg', 'w-100', '') ?>
                                        </td>
                                        <td width="80"><?php echo $val->start_date ?></td>
                                        <td width="80"><?php echo $val->end_date ?></td>
                                        <td>
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" <?php echo ($val->status == '1')?'checked':'';?> name="status" class="custom-control-input" onclick="statusActive(this.value,'<?php echo $val->campaign_id;?>')" value="<?php echo ($val->status == '1')?'0':'1';;?>" id="customSwitch_<?php echo $i++;?>">
                                                    <label class="custom-control-label" for="customSwitch_<?php echo $j++;?>"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td width="40px">
                                            <a href="<?php echo base_url('agent/campaign_update/'.$val->campaign_id);?>" class="btn btn-xs btn-warning ">Update</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
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
    function statusActive(status, id) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('agent/campaign_status_update') ?>",
            dataType: "text",
            data: {
                campaign_id: id,
                status: status
            },
            success: function(msg) {
                $('#message').html(msg);
            }
        });
    }
</script>
<?= $this->endSection() ?>