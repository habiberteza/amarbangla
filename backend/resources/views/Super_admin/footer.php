<footer class="main-footer">
    <strong>Copyright &copy; 2024 <a href="https://dnationsoft.com/">DNationSoft</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.1.0
    </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo base_url()?>assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url()?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url()?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo base_url()?>assets/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo base_url()?>assets/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?php echo base_url()?>assets/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url()?>assets/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url()?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo base_url()?>assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?php echo base_url()?>assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo base_url()?>assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url()?>assets/dist/js/pages/dashboard.js"></script>

<!-- DataTables  & Plugins -->
<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="<?php print base_url(); ?>assets/dist/js/spartan-multi-image-picker.js"></script>
<script src="<?php print base_url(); ?>assets/dist/js/ckeditor.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url() ?>assets/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(function () {
        $('.select2').select2();

        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "order": [
                [0, "desc"]
            ],
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');


        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

        $("#example3").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "order": [
                [0, "desc"]
            ],
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );
    $(function(){

        $("#coba").spartanMultiImagePicker({
            fieldName:        'userfile[]',
            directUpload : {
                status: true,
                loaderIcon: '<i class="fas fa-sync fa-spin"></i>',
                url: '../c.php',
                additionalParam : {
                    name : 'My Name'
                },
                success : function(data, textStatus, jqXHR){
                },
                error : function(jqXHR, textStatus, errorThrown){
                }
            }
        });
    });

    $(function() {

        $("#coba2").spartanMultiImagePicker({
            fieldName: 'userfile2',
            maxCount: 1,
            directUpload: {
                status: true,
                loaderIcon: '<i class="fas fa-sync fa-spin"></i>',
                url: '../c.php',
                additionalParam: {
                    name: 'My Name'
                },
                success: function(data, textStatus, jqXHR) {},
                error: function(jqXHR, textStatus, errorThrown) {}
            }
        });
    });

    $(function() {

        $("#coba3").spartanMultiImagePicker({
            fieldName: 'userfile2',
            maxCount: 1,
            directUpload: {
                status: true,
                loaderIcon: '<i class="fas fa-sync fa-spin"></i>',
                url: '../c.php',
                additionalParam: {
                    name: 'My Name'
                },
                success: function(data, textStatus, jqXHR) {},
                error: function(jqXHR, textStatus, errorThrown) {}
            }
        });
    });

    $(function() {

        $("#coba4").spartanMultiImagePicker({
            fieldName: 'userfile2',
            maxCount: 1,
            directUpload: {
                status: true,
                loaderIcon: '<i class="fas fa-sync fa-spin"></i>',
                url: '../c.php',
                additionalParam: {
                    name: 'My Name'
                },
                success: function(data, textStatus, jqXHR) {},
                error: function(jqXHR, textStatus, errorThrown) {}
            }
        });
    });

    $(function() {

        $("#coba5").spartanMultiImagePicker({
            fieldName: 'userfile2',
            maxCount: 1,
            directUpload: {
                status: true,
                loaderIcon: '<i class="fas fa-sync fa-spin"></i>',
                url: '../c.php',
                additionalParam: {
                    name: 'My Name'
                },
                success: function(data, textStatus, jqXHR) {},
                error: function(jqXHR, textStatus, errorThrown) {}
            }
        });
    });

    function shopSubCategory(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/shops_sub_category') ?>",
            dataType: "text",
            data: {
                catId: id
            },
            success: function(data) {
                $('#subCat').html(data);
            }
        });
    }

    function viewdistrict(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/search_district') ?>",
            dataType: "text",
            data: {
                divisionsId: id
            },

            beforeSend: function() {
                $('#district').html(
                    '<img src="<?php print base_url(); ?>/assets/loading.gif" width="20" alt="loading"/> Progressing...'
                );
                $('#district2').html(
                    '<img src="<?php print base_url(); ?>/assets/loading.gif" width="20" alt="loading"/> Progressing...'
                );
            },
            success: function(msg) {
                $('#district').html(msg);
                $('#district2').html(msg);
            }

        });
    }

    function viewupazila(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/search_upazila') ?>",
            dataType: "text",
            data: {
                districtId: id
            },

            beforeSend: function() {
                $('#upazila').html(
                    '<img src="<?php print base_url(); ?>/assets/loading.gif" width="20" alt="loading"/> Progressing...'
                );
                $('#upazila2').html(
                    '<img src="<?php print base_url(); ?>/assets/loading.gif" width="20" alt="loading"/> Progressing...'
                );
            },
            success: function(msg) {
                $('#upazila').html(msg);
                $('#upazila2').html(msg);
            }

        });
    }

    function eventCheckBox() {
        let checkboxs = document.getElementsByTagName("input");
        for (let i = 1; i < checkboxs.length; i++) {
            checkboxs[i].checked = !checkboxs[i].checked;
        }
    }

    function printDiv(){
        window.print();
    }

    function request_status(status, id) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/calling_status_update') ?>",
            dataType: "text",
            data: {
                status: status,
                calling_id: id
            },
            beforeSend: function() {
                //$('#message').html(
                //    '<img src="<?php //print base_url(); ?>///assets/loading.gif" width="20" alt="loading"/> Progressing...'
                //);
            },
            success: function(msg) {
                $('#message').html(msg);
            }
        });
    }

    function shopdata(id, sellerId) {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/commission_data') ?>",
            dataType: "text",
            data: {
                sch_id: id,
                seller_id: sellerId
            },
            beforeSend: function() {
                //$('#commDetail').html(
                //    '<img src="<?php //print base_url(); ?>///assets/loading.gif" width="20" alt="loading"/> Progressing...'
                //);
            },
            success: function(data) {

                $('#commDetail').html(data);

            }

        });
    }

    function shoptotaldata(id, sellerId) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/commission_data_total') ?>",
            dataType: "text",
            data: {
                sch_id: id,
                seller_id: sellerId
            },
            beforeSend: function() {
                //$('#commTotalDetail').html(
                //    '<img src="<?php //print base_url(); ?>///assets/loading.gif" width="20" alt="loading"/> Progressing...'
                //);
            },
            success: function(data) {
                $('#commTotalDetail').html(data);
            }

        });
    }

    function allshopcom(sellerId) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/commission_all') ?>",
            dataType: "text",
            data: {
                seller_id: sellerId
            },
            beforeSend: function() {
                //$('#commTotalDetail').html(
                //    '<img src="<?php //print base_url(); ?>///assets/loading.gif" width="20" alt="loading"/> Progressing...'
                //);
            },
            success: function(data) {
                $('#commTotalDetail').html(data);
            }

        });
    }

    function shopbyorder(schId, sellerId) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/sellers_order_data') ?>",
            dataType: "text",
            data: {
                sch_id: schId,
                seller_id: sellerId
            },
            beforeSend: function() {
                //$('#shopOrder').html(
                //    '<img src="<?php //print base_url(); ?>///assets/loading.gif" width="20" alt="loading"/> Progressing...'
                //);
            },
            success: function(data) {
                $('#shopOrder').html(data);
            }

        });
    }

    function shopdatadeliveryBoy(id, deliveryBoyId) {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/delivery_boy_commission_data') ?>",
            dataType: "text",
            data: {
                sch_id: id,
                delivery_boy_id: deliveryBoyId
            },
            beforeSend: function() {
                //$('#commDetail').html(
                //    '<img src="<?php //print base_url(); ?>///assets/loading.gif" width="20" alt="loading"/> Progressing...'
                //);
            },
            success: function(data) {
                $('#commDetail').html(data);
            }

        });
    }

    function shoptotaldaliverydata(id, deliveryBoyId) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/delivery_boy_commission_data_total') ?>",
            dataType: "text",
            data: {
                sch_id: id,
                delivery_boy_id: deliveryBoyId
            },
            beforeSend: function() {
                //$('#commTotalDetail').html(
                //    '<img src="<?php //print base_url(); ?>///assets/loading.gif" width="20" alt="loading"/> Progressing...'
                //);
            },
            success: function(data) {
                $('#commTotalDetail').html(data);
            }

        });
    }

    function allshopcomdeliveryBoy(deliveryBoyId) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/delivery_boy_commission_all') ?>",
            dataType: "text",
            data: {
                delivery_boy_id: deliveryBoyId
            },
            beforeSend: function() {
                //$('#commTotalDetail').html(
                //    '<img src="<?php //print base_url(); ?>///assets/loading.gif" width="20" alt="loading"/> Progressing...'
                //);
            },
            success: function(data) {
                $('#commTotalDetail').html(data);
            }

        });
    }

    function showSubCategory(id, url) {
        $.ajax({
            type: "POST",
            url: url,
            dataType: "text",
            data: {
                cat_id: id
            },
            beforeSend: function() {
                //$('#subCat').html(
                //    '<img src="<?php //print base_url(); ?>///assets/loading.gif" width="20" alt="loading"/> Progressing...'
                //);
                // $('.preloader').show();
            },
            success: function(msg) {
                $('#subCat').html(msg);
            }
        });
    }

    function addCart() {
        var category = $('[name=category]').val();
        var subCatId = $('[name=sub_category]').val();
        var name = $('[name=name]').val();
        var unit = $('[name=unit]').val();
        var Qty = $('[name=qty]').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/demo_product_add_cart') ?>",
            dataType: "text",
            data: {
                subCatId: subCatId,
                name: name,
                category: category,
                unit: unit,
                qty: Qty
            },
            success: function(msg) {
                $('#message').html(msg);
                $('#table-reload').load(document.URL + ' #example2');
                $('[name=category]').val('');
                $('[name=sub_category]').val('');
                $('[name=name]').val('');
            }
        });
    }

    function remove_cart_data(val) {
        // var Id = $(Id).attr("id");
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/demo_product_remove_cart') ?>",
            dataType: "text",
            data: {
                id: val
            },
            success: function(msg) {
                $('#message').html(msg);
                $('#table-reload').load(document.URL + ' #example2');
            }
        });
    }

    function clearCart() {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/demo_product_clearCart') ?>",
            dataType: "text",
            success: function(msg) {
                $('#message').html(msg);
                $('#table-reload').load(document.URL + ' #example2');
            }
        });
    }

    function _productNameSearch(key) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/demo_product_search_by_key') ?>",
            dataType: "text",
            data: {
                keyword: key
            },
            success: function(msg) {
                $('#subCat').html(msg);
                $('#example2_info').hide();
                $('#example2_paginate').hide();
                // alert(msg);
            }
        });
    }

    function _prodSearch(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/demo_product_search_by_cat') ?>",
            dataType: "text",
            data: {
                catId: id
            },
            success: function(msg) {
                $('#subCat').html(msg);
                $('#example2_info').hide();
                $('#example2_paginate').hide();
            }
        });
    }

    function statusActive(status, id) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/campaign_status_update') ?>",
            dataType: "text",
            data: {
                campaign_id: id,
                status: status
            },
            beforeSend: function() {
                $('#loding').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(msg) {
                $('#message').html(msg);
            }
        });
    }

    function Upload() {
        //Get reference of FileUpload.
        var fileUpload = document.getElementById("imageFile");
        //Check whether the file is valid Image.
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png|.gif)$");
        if (regex.test(fileUpload.value.toLowerCase())) {
            //Check whether HTML5 is supported.
            if (typeof(fileUpload.files) != "undefined") {
                //Initiate the FileReader object.
                var reader = new FileReader();
                //Read the contents of Image File.
                reader.readAsDataURL(fileUpload.files[0]);
                reader.onload = function(e) {
                    //Initiate the JavaScript Image object.
                    var image = new Image();
                    //Set the Base64 string return from FileReader as source.
                    image.src = e.target.result;
                    //Validate the File Height and Width.
                    image.onload = function() {
                        var height = this.height;
                        var width = this.width;
                        if (height > 100 || width > 390) {
                            $('#validImg').html('<b style="color: red;">Image size must be greater than 390x100</b>');
                            $('#imageFile').val('');
                        } else {
                            $('#validImg').html('<b style="color: green;">Image is valid</b>');
                        }
                    };
                }
            } else {
                $('#validImg').html('<b style="color: red;">Image size must be greater than 390x100</b>');
                $('#imageFile').val('');
            }
        } else {
            $('#validImg').html('<b style="color: red;">Please select a valid Image file.</b>');
            $('#imageFile').val('');
        }
    }

    function statusChange(status, sms_request_id) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/update_status') ?>",
            dataType: "text",
            data: {
                status: status,
                sms_request_id: sms_request_id
            },
            success: function(msg) {
                $('#message').html(msg);
            }
        });
    }

    function allshoporder(schId) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('super_admin/order_filter_shop') ?>",
            dataType: "text",
            data:{ sch_id: schId },
            success: function(data) {
                $('#shopOrder').html(data);
                $('#example1_info').hide();
                $('#example1_paginate').hide();
            }

        });
    }

    function filter_invoice() {
        let division = $('[name=division]').val();
        let district = $('[name=district]').val();
        let upazila = $('[name=upazila]').val();
        let pourashava = $('[name=pourashava]').val();
        let ward = $('[name=ward]').val();
        let status = $('[name=status]').val();

        if (division == '') {
            $('[name=division]').css('border-color', '#ff0000');
        } else {
            $('[name=division]').css('border-color', '#008000');
        }

        if (division != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('super_admin/order_filter_invoice') ?>",
                dataType: "text",
                data: {
                    division: division,
                    district: district,
                    upazila: upazila,
                    pourashava: pourashava,
                    ward: ward,
                    status: status,
                },
                success: function(msg) {
                    $('#shopOrder').html(msg);
                    $('#example1_info').hide();
                    $('#example1_paginate').hide();
                }
            });
        }
    }

    function purchase_price_update(id){
        alert(id);
    }

    function updateFunction(proId, input, value, viewId, formName,updateRow) {
        var formID = "'" + formName + "'"
        var data = '<form id="' + formName +
            '" action="<?= base_url('super_admin/demo_product_price_update') ?>" data-row="'+updateRow+'" method="post"><input type="text" name="' +
            input +
            '" class="form-control mb-2" value="' + value +
            '" ><input type="hidden" name="product_id" class="form-control mb-2" value="' + proId +
            '" ><button type="button" onclick="submitFormBulk(' + formID +
            ')" class="btn btn-xs btn-primary mr-2">Update</button><a href="javascript:void(0)" onclick="hideInput(this)" class="btn btn-xs btn-danger">Cancel</button> </form>';

        $('#' + viewId).html(data);
    }

    function submitFormBulk(formID) {
        var form = document.getElementById(formID);
        var upRow = $(form).attr('data-row');

        var done = false;
        $.ajax({
            url: $(form).prop('action'),
            type: "POST",
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                // $("#message").html(data);
                $("#mess").show();
                var div = $("#"+upRow).html(data);
                div.animate({opacity: '0.5'});
                div.animate({opacity: '1'});

            }
        });
    }

    function hideInput(data) {
        $(data).parent().remove();
    }

</script>
</body>
</html>