<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Global Address update</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('super_admin/shops')?>">Home</a></li>
                        <li class="breadcrumb-item active">Global Address update</li>
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
                            <h3 class="card-title">Global Address update</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php echo  isset(newSession()->message) ? newSession()->message :''; ?>
                                </div>
                                <div class="col-md-6">
                                    <form action="<?php echo base_url('super_admin/global_address_update_action'); ?>" method="post">

                                        <div class="form-group">
                                            <label for="varchar">Division </label>
                                            <select class="form-control" name="division" onchange="viewdistrict(this.value)" required >
                                                <option value="">Please Select</option>
                                                <?php echo divisionView($address->division) ; ?>
                                            </select>


                                        </div>

                                        <div class="form-group">
                                            <label for="varchar">District </label>
                                            <select class="form-control" name="district" onchange="viewupazila(this.value)" id="district" required>
                                                <option value="">Please Select</option>
                                                <?php echo districtselect($address->zila,$address->division) ; ?>
                                            </select>


                                        </div>

                                        <div class="form-group">
                                            <label for="varchar">Upazila </label>
                                            <select class="form-control" name="upazila" id="upazila" onchange="checkCity(this.value)"  required>
                                                <option value="">Please Select</option>
                                                <?php echo upazilaselect($address->upazila,$address->zila) ; ?>
                                            </select>
                                        </div>

                                        <div class="form-group" id="pourashava">
                                            <label for="varchar">Pourashava/Union</label>
                                            <select class="form-control" name="pourashava" id="reuq" >
                                                <option value="">Please Select</option>
                                                <?php echo pourashavaUnion($address->pourashava) ; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="varchar">Ward</label>
                                            <select class="form-control" name="ward" required>
                                                <option value="">Please Select</option>
                                                <?php echo wardView($address->ward) ; ?>
                                            </select>
                                        </div>

                                        <input type="hidden" name="global_address_id" value="<?php echo $address->global_address_id; ?>" >
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="<?php echo site_url('super_admin/global_address') ?>" class="btn btn-danger">Cancel</a>
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