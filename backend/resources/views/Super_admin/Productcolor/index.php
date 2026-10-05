<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product color</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Product color</li>
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
                            <a href="<?php echo base_url('super_admin/product_color_create');?>" class="btn btn-xs btn-primary w-25 float-right">Create</a>
                            <h3 class="card-title">Product color list</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12">
                                <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                            </div>
                            <div class="col-md-12 mb-3">
                                <form action="<?php echo base_url('super_admin/product_color')?>" method="get">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 mb-2">
                                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search color name or code..." value="<?php echo esc($search ?? ''); ?>">
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
                                            <a href="<?php echo base_url('super_admin/product_color'); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-sync"></i> Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                            <table id="color_table" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  foreach ($color as $val){ ?>
                                    <tr>
                                        <td width="20"><?php echo $val->color_family_id;?></td>
                                        <td><?php echo $val->color_name;?></td>
                                        <td><?php echo $val->code;?></td>
                                        <td width="160px">
                                            <a href="<?php echo base_url('super_admin/product_color_update/'.$val->color_family_id);?>" class="btn btn-xs btn-warning ">Update</a>
                                            <a href="<?php echo base_url('super_admin/product_color_delete/'.$val->color_family_id);?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Code</th>
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