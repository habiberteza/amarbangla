<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Campaign update</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Campaign update</li>
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
                            <h3 class="card-title">Campaign update</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="<?php echo base_url('super_admin/campaign_update_action'); ?>" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="varchar">Title</label>
                                            <input type="text" class="form-control" name="title" id="title" placeholder="title" required value="<?php echo $campaign->title ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label for="varchar">Product </label>
                                            <select class="form-control select2 select2-hidden-accessible" style=" width: 100%;" tabindex="-1" aria-hidden="true" name="prod_id" required>
                                                <option value="">Please Select</option>
                                                <?php foreach ($product as $row) { $sel = ($row->prod_id == $campaign->prod_id)?'selected':'';  ?>
                                                    <option value="<?php echo $row->prod_id; ?>" <?php echo $sel; ?> ><?php echo $row->name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="varchar">Start date </label>
                                            <input type="date" class="form-control" name="start_date"
                                                   id="start_date" required value="<?php echo $campaign->start_date ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label for="varchar">End date </label>
                                            <input type="date" class="form-control" name="end_date" id="end_date" required value="<?php echo $campaign->end_date ?>"/>
                                        </div>

                                        <input type="hidden"  name="campaign_id"  value="<?php echo $campaign->campaign_id;?>" required>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="<?php echo site_url('super_admin/campaign') ?>" class="btn btn-danger">Cancel</a>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="varchar">Description </label>
                                            <textarea class="form-control" rows="5" name="description"  placeholder="Description" required><?php echo $campaign->description ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <?php echo image_view('uploads/campaign', '', $campaign->image, 'no_image.jpg', 'w-50', '') ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="varchar">Image </label>
                                            <input type="file" class="form-control" name="image" id="imageFile" onchange="Upload()" />
                                            <b>Size: 390x100</b><br>
                                            <span id="validImg"></span>
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