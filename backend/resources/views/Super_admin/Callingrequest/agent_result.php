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
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
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
                        <div class="card-header">
                            <h3 class="card-title">Calling request list</h3>
                            <a href="<?= base_url('super_admin/calling_request'); ?>" class="btn btn-xs btn-danger w-25 float-right">Back</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12" id="message">
                                <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-4 ">
                                    <form action="<?= base_url('super_admin/calling_search_agent')?>" method="post">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label for="varchar">Agent</label>
                                                <select class="form-control" name="agent_id">
                                                    <option value="">Please Select</option>
                                                    <?php foreach ($agent as $row){ ?>
                                                    <option value="<?= $row->agent_id;?>" <?= ($agent_id == $row->agent_id)?'selected':'';?> ><?= $row->agent_name;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="varchar">Status</label>
                                                <select class="form-control" name="status">
                                                    <option value="">Please Select</option>
                                                    <option value="Open" <?= ($status == 'Open')?'selected':'';?>>Open</option>
                                                    <option value="Processing" <?= ($status == 'Processing')?'selected':'';?>>Processing</option>
                                                    <option value="Complete" <?= ($status == 'Complete')?'selected':'';?>>Complete</option>
                                                    <option value="Cancel" <?= ($status == 'Cancel')?'selected':'';?>>Cancel</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary" style="margin-top: 30px;">
                                                    Search
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

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
                                <?php  foreach ($calling as $val){ $img = (!empty($val->image))?$val->image:'noImage.jpg'; ?>
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
                                        <td><a href='<?php echo  base_url('uploads/calling_request/').$img; ?>' target='_blank' ><?php echo image_view('uploads/calling_request', '', $val->image, 'noImage.jpg', 'img-25', ''); ?></a></td>
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
                                <tfoot>
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