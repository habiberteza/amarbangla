<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>License update</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">License update</li>
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
                            <h3 class="card-title">License update</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                </div>
                                <div class="col-md-6">
                                    <form action="<?php echo base_url('super_admin/license_update_action'); ?>" method="post">
                                        <div class="form-group">
                                            <label for="varchar">Shops</label>
                                            <select class="form-control" name="sch_id" required >
                                                <option value="<?php echo $license->sch_id;?>"><?php echo get_data_by_id('name','shops','sch_id',$license->sch_id);?></option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="varchar">Start Date</label>
                                            <input type="date" class="form-control" name="start_date" id="start_date" value="<?php echo $license->start_date;?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="varchar">End Date</label>
                                            <input type="date" class="form-control" name="end_date" id="end_date" value="<?php echo $license->end_date;?>" required />
                                        </div>


                                        <input type="hidden"  name="lic_id" value="<?php echo $license->lic_id;?>" required />
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="<?php echo site_url('super_admin/license') ?>" class="btn btn-danger">Cancel</a>
                                    </form>

                                </div>
                            </div>
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