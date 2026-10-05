<?= $this->extend('Shop_admin/layout') ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper overflow-hidden">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Group Post</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('shop_admin/dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Group Post</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <?php $i=1; $j=1; $m=1; $k=1; foreach ($post as $val) { ?>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <a href="javascript:void(0)" onclick="openModal('<?php echo $val->ad_post_id;?>')">
                    <div class="card">
                        <div class="card-title">
                            <div class="col-md-12 text-capitalize" style="color: #000000;padding-left: 20px;padding-top: 20px; ">
                                <h3 class="box-title"><?php echo $val->title ?></h3>
                                <small>Author: <?php echo get_data_by_id('name', 'shops', 'sch_id', $val->sch_id) ?>
                                    (Admin) </small>
                                <br><small>Date: <?php echo globalDateTimeFormat($val->createdDtm) ?> </small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if (!empty($val->youtube_video)) { ?>
                                    <div class="col-md-12">
                                        <iframe width="100%" height="350px" src="<?php echo $val->youtube_video; ?>"  frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                <?php } ?>
                                 <?php if (!empty($val->facebook_video)) { ?>
                                    <div class="col-md-12" style="text-align: center;">
                                        <iframe <?php print $val->facebook_video; ?> style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($val->banner_1)) { ?>
                                    <div class="col-md-6">
                                        <?= image_view('uploads/post', '', $val->banner_1, 'no_image.jpg', '', ''); ?>
                                    </div>
                                <?php } ?>

                                <?php if (!empty($val->banner_2)) { ?>
                                    <div class="col-md-6">
                                        <?= image_view('uploads/post', '', $val->banner_2, 'no_image.jpg', '', ''); ?>
                                    </div>
                                <?php } ?>

                                <div class="col-md-12">
                                    <p>Description:</p>
                                    <?php echo substr($val->description, 0, 200) ?> <small> >>>More</small>
                                </div>
                            </div>
                </a>
                            <div class="col-md-12 like-row row" id="rel-like-sec_<?php echo $i++?>">
                                <div class="col-md-6" >
                                    <?php $checkLike = check_like_by_id('like_post','ad_post_id',$val->ad_post_id); ?>
                                    (<?php echo count_alldata_by_id('like_post','ad_post_id',$val->ad_post_id )?>) <a href="javascript:void(0)" class="<?php echo (!empty($checkLike))?'selected_like':'';?>" onclick="likeSubmit('<?php echo $val->ad_post_id;?>','rel-like-sec_<?php echo $j++?>')" > <i class="fa fa-thumbs-o-up"></i> Like</a>
                                </div>
                                <div class="col-md-6">
                                    (<?php echo count_alldata_by_id('comment','ad_post_id',$val->ad_post_id )?>) <a href="javascript:void(0)"   onclick="openModal('<?php echo $val->ad_post_id;?>')" > <i class="fa fa-comment-o"></i> Comments </a>
                                </div>
                            </div>
                            <div class="col-md-12 comment-box mt-4" style="border-top: 1px solid #d2d6de;">
                                <ul class="list-group mt-4" id="comm-data_<?php echo $m++;?>">
                                    <?php $comment = get_alldata_by_id('comment', 'ad_post_id', $val->ad_post_id, '5'); ?>
                                    <?php foreach ($comment as $item) { ?>
                                        <li class="list-group-item" >
                                            <div class="row">
                                                <div class="col-md-2" style="text-align: right;">
                                                    <span class="icon-coment-read"><i class="fa fa-user"></i></span>
                                                </div>
                                                <div class="col-md-10">
                                                    <b><?php echo $item->comment; ?></b>
                                                    <?php if (!empty($item->sch_id)) { ?>
                                                        <br>
                                                        <small>Admin: <?php echo get_data_by_id('name', 'shops', 'sch_id', $item->sch_id) ?> .</small> <small>(<?php echo globalDateTimeFormat($item->createdDtm) ?> )</small>
                                                    <?php } ?>
                                                    <?php if (!empty($item->customer_id)) { ?>
                                                        <br>
                                                        <small>Customer: <?php echo get_data_by_id('customer_name', 'customers', 'customer_id', $item->customer_id) ?>.</small> <small>(<?php echo globalDateTimeFormat($item->createdDtm) ?> )</small>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="col-md-12 mt-4 comment-row">
                                <form id="comment-form" action="<?php echo base_url('shop_admin/group_post_comment_action') ?>" method="post">
                                    <div class="row">
                                        <div class="col-md-2" >
                                            <span class="icon-coment-read-com" style="font-size: 25px; float: right;"><i class="fa fa-user"></i></span>
                                        </div>
                                        <div class="col-md-8">
                                            <textarea name="comment" class="form-control" id="" rows="1" required></textarea>
                                            <input type="hidden" name="ad_post_id" value="<?php echo $val->ad_post_id ?>">
                                            <input type="hidden" name="reload-val" value="comm-data_<?php echo $k++;?>">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary">Post</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                       </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </section>
    <!-- /.content -->
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
        <div class="modal-content " id="data-post">
        </div>
    </div>
</div>
<!-- /.content-wrapper -->
<?= $this->endSection() ?>


<?= $this->section('java_script') ?>
<script>
    function likeSubmit(id,reload){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('shop_admin/group_post_like_submit') ?>",
            dataType: "text",
            data: {post_id:id},
            success: function (data) {
                $('#'+reload).load(document.URL +  ' #'+reload);
            }
        });
    }

  function openModal(id){
      $('#exampleModalCenter').modal('show');

      $.ajax({
          type: "POST",
          url: "<?php echo base_url('shop_admin/group_post_show_comment')?>",
          dataType: "text",
          data: {post_id:id},
          success: function (data) {
              $('#data-post').html(data);
          }
      });
  }

  $(document).on('submit', '#comment-form', function (e) {
      e.preventDefault();

      var geniusform = $(this).serialize();
      $('button.btn-post').prop('disabled', true);
      $.ajax({
          type: "POST",
          url: $(this).prop('action'),
          dataType: "text",
          data: geniusform,
          success: function (data) {
              $('#'+data).load(document.URL +  ' #'+data);
              $('#comment-form')[0].reset();
              $('button.btn-post').prop('disabled', false);
          }
      });
  });

</script>
<?= $this->endSection() ?>
