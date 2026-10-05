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
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
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
                            <div class="col-md-12 mb-3">
                                <form action="<?php echo base_url('super_admin/campaign')?>" method="get">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 mb-2">
                                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search title, description, or shop..." value="<?php echo esc($search ?? ''); ?>">
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                                                <option value="25" <?php echo ($per_page ?? 25) == 25 ? 'selected' : ''; ?>>25 / page</option>
                                                <option value="50" <?php echo ($per_page ?? 25) == 50 ? 'selected' : ''; ?>>50 / page</option>
                                                <option value="100" <?php echo ($per_page ?? 25) == 100 ? 'selected' : ''; ?>>100 / page</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i> Search</button>
                                            <a href="<?php echo base_url('super_admin/campaign'); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-sync"></i> Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                            <table id="campaign_table" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Shop</th>
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
                                        <td><?php echo esc($val->shop_name ?? get_data_by_id('name','shops','sch_id',$val->sch_id)); ?></td>
                                        <td><?php echo esc($val->title); ?></td>
                                        <td><?php echo esc($val->description); ?></td>
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
                                            <a href="<?php echo base_url('super_admin/campaign_update/'.$val->campaign_id);?>" class="btn btn-xs btn-warning ">Update</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Shop</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Start date</th>
                                    <th>End date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                            </table>
                            </div>
                            <?php if (isset($pager)): ?>
                                <div class="mt-3 d-flex justify-content-center">
                                    <?= $pager->links(); ?>
                                </div>
                            <?php endif; ?>
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