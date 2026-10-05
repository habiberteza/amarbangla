<div class="modal-header" id="comm-data2">
    <div class="col-md-12">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        <h3><?= $post->title; ?></h3>
        <small>Author: <?= get_data_by_id('name', 'shops', 'sch_id', $post->sch_id); ?> (Admin) </small>
        <br><small>Date: <?= globalDateTimeFormat($post->createdDtm); ?></small>
    </div>
</div>

<div class="modal-body">
    <div class="row">
        <?php if (!empty($post->youtube_video)) { ?>
        <div class="col-md-12">
            <iframe width="100%" height="350px" src="<?= $post->youtube_video ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <?php } ?>

        <?php if (!empty($post->facebook_video)) { ?>
        <div class="col-md-12" style="text-align: center;">
            <iframe <?=$post->facebook_video ?> style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>
        </div>
       <?php } ?>

        <?php if (!empty($post->banner_1)) { ?>
        <div class="col-md-6">
            <?= image_view('uploads/post', '', $post->banner_1, 'no_image.jpg', '', ''); ?>
        </div>
        <?php } ?>

        <?php if (!empty($post->banner_2)) { ?>
        <div class="col-md-6">
            <?= image_view('uploads/post', '', $post->banner_2, 'no_image.jpg', '', ''); ?>
        </div>
        <?php } ?>

        <div class="col-md-12">
            <p>Description:</p>
            <?= substr($post->description, 0, 200) ?>
        </div>

    </div>
</div>
<?php
    $checkLike = check_like_by_id('like_post', 'ad_post_id', $post->ad_post_id);
    $checkcl = (!empty($checkLike)) ? 'selected_like' : '';
    $likecount = count_alldata_by_id('like_post', 'ad_post_id', $post->ad_post_id);
    $commcount = count_alldata_by_id('comment', 'ad_post_id', $post->ad_post_id);
?>
<div class="modal-footer">
    <div class="col-md-12 like-row row">
        <div class="col-md-6">
            (<?= $likecount ?>) <a href="#" class="<?= $checkcl ?>"> <i class="fa fa-thumbs-o-up"></i> Like</a>
        </div>
        <div class="col-md-6">
            (<?= $commcount ?>) <a href="javascript:void(0)" > <i class="fa fa-comment-o"></i> Comments </a>
        </div>
    </div>
    <div class="col-md-12 comment-box">
        <ul class="list-group mt-4" >
            <?php foreach ($comment as $item) { ?>
            <li class="list-group-item" >
                <div class="row">
                    <div class="col-md-2" style="text-align: right;">
                        <span class="icon-coment-read"><i class="fa fa-user"></i></span>
                    </div>
                    <div class="col-md-10" style="text-align: left;">
                        <b><?= $item->comment ?></b>
                        <?php if (!empty($item->sch_id)) { ?>
                        <br>
                        <small>Admin: <?= get_data_by_id('name', 'shops', 'sch_id', $item->sch_id)  ?></small> <small>(<?= globalDateTimeFormat($item->createdDtm) ?>)</small>
                        <?php } ?>
                        <?php if (!empty($item->customer_id)) { ?>
                        <br>
                        <small>Customer: <?= get_data_by_id('customer_name', 'customers', 'customer_id', $item->customer_id) ?></small> <small>(<?= globalDateTimeFormat($item->createdDtm) ?> )</small>
                        <?php } ?>
                    </div>
                </div>
            </li>
            <?php } ?>
        </ul>
    </div>
    <div class="col-md-12 mt-4 comment-row">
        <form id="comment-form" action="<?= base_url('shop_admin/group_post_comment_action') ?>" method="post">
            <div class="row">
                <div class="col-md-2" >
                    <span class="icon-coment-read-com" style="font-size: 25px; float: right;" ><i class="fa fa-user"></i></span>
                </div>
                <div class="col-md-8">
                    <textarea name="comment" class="form-control" id="" rows="1" required></textarea>
                    <input type="hidden" name="ad_post_id" value="<?= $post->ad_post_id ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Post</button>
                </div>
            </div>
        </form>
    </div>
</div>