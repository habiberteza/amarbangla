<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Seller</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Seller</li>
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
                            <a href="<?php echo base_url('super_admin/sellers_create');?>" class="btn btn-xs btn-primary w-25 float-right">Create</a>
                            <a href="<?php echo base_url('super_admin/sellers');?>" class="btn btn-xs btn-danger w-25 mr-2 float-right">Back</a>
                            <h3 class="card-title">Seller list</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12">
                                <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                            </div>
                            <div class="col-md-12">
                                <form action="<?php echo base_url('super_admin/sellers_filter')?>" method="post">
                                    <div class="row">

                                        <div class="form-group col-md-2">
                                            <label for="varchar">Division </label>
                                            <select class="form-control" name="division" onchange="viewdistrict(this.value)">
                                                <option value="">Please Select</option>
                                                <?php echo divisionView($address->division); ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="varchar">District</label>
                                            <select class="form-control" name="district" onchange="viewupazila(this.value)" id="district" required>
                                                <option value="">Please Select</option>
                                                <?php echo districtselect($address->zila,$address->division); ?>
                                            </select>


                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="varchar">Upazila </label>
                                            <select class="form-control" name="upazila" id="upazila" required>
                                                <option value="">Please Select</option>
                                                <?php echo upazilaselect($address->upazila,$address->zila); ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="varchar">Pourashava/Union</label>
                                            <select class="form-control" name="pourashava" required>
                                                <option value="">Please Select</option>
                                                <?php echo pourashavaUnion($address->pourashava); ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="varchar">Ward</label>
                                            <select class="form-control" name="ward" required>
                                                <option value="">Please Select</option>
                                                <?php echo wardView($address->ward); ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <button type="submit" class="btn btn-primary "  style="margin-top: 30px; width:100%;">Filter </button>
                                        </div>

                                    </div>
                                </form>
                            </div>

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  foreach ($seller as $val){ ?>
                                    <tr>
                                        <td width="10"><?php echo $val->seller_id;?></td>
                                        <td><?php echo $val->name ?></td>
                                        <td><?php echo $val->mobile ?></td>
                                        <td width="300px">
                                            <a href="<?php echo base_url('super_admin/sellers_order/'.$val->seller_id);?>" class="btn btn-xs btn-info ">Order</a>
                                            <a href="<?php echo base_url('super_admin/sellers_commission/'.$val->seller_id);?>" class="btn btn-xs btn-primary ">Commission</a>
                                            <a href="<?php echo base_url('super_admin/sellers_ledger/'.$val->seller_id);?>" class="btn btn-xs btn-success ">Ledger</a>
                                            <a href="<?php echo base_url('super_admin/sellers_update/'.$val->seller_id);?>" class="btn btn-xs btn-warning ">Update</a>
                                            <a href="<?php echo base_url('super_admin/sellers_delete/'.$val->seller_id);?>" onclick="javasciprt: return confirm('Are You Sure ?')" class="btn btn-xs btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
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