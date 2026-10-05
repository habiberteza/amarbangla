<?= $this->extend('Agent/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Calling request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('agent/dashboard')?>">Home</a></li>
                        <li class="breadcrumb-item active">Calling request</li>
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
                        <div class="card-header row">
                            <div class="col-md-12">
                                <h3 class="card-title">Calling request list</h3>
                            </div>


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
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Description</th>
                                    <th>Address</th>
                                    <th>Image</th>
                                    <th>Created Time</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  foreach ($result as $val){ $img = (!empty($val->image))?$val->image:'noImage.jpg'; ?>
                                    <tr>
                                        <td><?php echo $val->calling_id;?></td>
                                        <td><?php echo get_data_by_id('customer_name','customers','customer_id',$val->customer_id);  ?></td>
                                        <td><?php echo get_data_by_id('mobile','customers','customer_id',$val->customer_id); ?></td>
                                        <td><?php echo $val->description ?></td>
                                        <td>
                                            <?php
                                            $glAd = get_data_by_id('global_address_id','customers','customer_id',$val->customer_id);
                                            if (!empty($glAd)){
                                                $address = global_address_id_by_address($glAd);?>
                                                <b>District:</b> <?php echo districtname($address->zila); ?>
                                                <br><b>Upazila:</b> <?php echo upazilaname($address->upazila); ?>
                                                <br><b>Pourashava/Union:</b> <?php echo pourashavaUnionName($address->pourashava); ?>
                                                <br><b>Ward:</b> <?php echo getwardname($address->ward); ?>
                                                <br><b>Home Address:</b> <?php echo get_data_by_id('address','customers','customer_id',$val->customer_id); ?><br>
                                            <?php } else{ echo 'Address not found';} ?>
                                        </td>
                                        <td><a href='<?php echo  base_url('uploads/calling_request/').$img; ?>' target='_blank' ><?php echo image_view('uploads/calling_request', '', $val->image, 'noImage.jpg', 'w-75', ''); ?></a></td>
                                        <td><?php echo invoiceDateFormat($val->createdDtm); ?></td>
                                        <td width="180">
                                            <select name="status" onchange="request_status(this.value,'<?php echo $val->calling_id;?>')" >
                                                <option value="" >Please select</option>
                                                <option value="Open" <?php echo ($val->status == 'Open')?'selected':''; ?> >Open</option>
                                                <option value="Processing" <?php echo ($val->status == 'Processing')?'selected':''; ?> >Processing</option>
                                                <option value="Complete" <?php echo ($val->status == 'Complete')?'selected':''; ?> >Complete</option>
                                                <option value="Cancel" <?php echo ($val->status == 'Cancel')?'selected':''; ?>  >Cancel</option>
                                            </select>

                                        </td>
                                    </tr>
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
<!-- /.content-wrapper -->
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
<script>
    function request_status(status, id) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('agent/calling_status_update') ?>",
            dataType: "text",
            data: {
                status: status,
                calling_id: id
            },
            success: function(msg) {
                $('#message').html(msg);
            }
        });
    }
</script>
<?= $this->endSection() ?>
