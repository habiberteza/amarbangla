<?php

namespace App\Controllers\Shop_admin;

use App\Controllers\BaseController;
use App\Models\AdpostModel;
use App\Models\BankModel;
use App\Libraries\Permission;
use App\Models\CommentModel;
use App\Models\LikepostModel;

class GroupPost extends BaseController
{
    protected $session;
    protected $validation;
    protected $permission;
    protected $adpostModel;
    protected $likepostModel;
    protected $commentModel;
    private $module_name = 'Group_post';
    public function __construct()
    {
        $this->adpostModel = new AdpostModel();
        $this->likepostModel = new LikepostModel();
        $this->commentModel = new CommentModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->permission = new permission();
    }

    public function index(){


        $data['post'] = $this->adpostModel->orderBy('ad_post_id','DESC')->findAll();

        $permi = $this->permission->module_permission(Auth()->role_id, $this->module_name);
        if (isset($permi['mod_access']) and $permi['mod_access'] == 1) {
            echo view('Shop_admin/Group_post/index', $data);
        }else{
            echo view('Shop_admin/no_permission');
        }


    }

    public function like_submit()
    {
        $shopId = Auth()->sch_id;
        $post_id = $this->request->getPost('post_id');


        $check = $this->likepostModel->where('sch_id', $shopId)->where('ad_post_id', $post_id)->countAllResults();
        if (empty($check)) {
            $data['ad_post_id'] = $post_id;
            $data['sch_id'] = $shopId;
            $data['createdDtm'] = date('Y-m-d h:i:s');
            $this->likepostModel->insert($data);

            $oldLike = get_data_by_id('total_like', 'ad_post', 'ad_post_id', $post_id);
            $newLike = $oldLike + 1;

            $dataLike['ad_post_id'] = $post_id;
            $dataLike['total_like'] = $newLike;
            $this->adpostModel->update($dataLike['ad_post_id'], $dataLike);
        }
    }
    public function show_comment()
    {
        $post_id = $this->request->getPost('post_id');

        $data['post'] = $this->adpostModel->where('ad_post_id', $post_id)->first();
        $data['comment'] = $this->commentModel->where('ad_post_id', $post_id)->findAll();

        echo view('Shop_admin/Group_post/comment', $data);


    }
    public function comment_action(){
        $shopId = Auth()->sch_id;
        $comment = $this->request->getPost('comment');
        $ad_post_id = $this->request->getPost('ad_post_id');
        $reload = $this->request->getPost('reload-val');
        if (!empty($comment)) {
            $data['sch_id'] = $shopId;
            $data['comment'] = $comment;
            $data['ad_post_id'] = $ad_post_id;
            $data['createdDtm'] = date('Y-m-d h:i:s');
            $this->commentModel->insert($data);
        }
        print $reload;
    }









}