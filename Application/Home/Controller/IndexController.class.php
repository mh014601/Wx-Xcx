<?php
// YII 3 + 4
// tp5 ci
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){

    }

    public function xcx_add(){
        $r = file_get_contents("php://input");
        var_dump(json_decode($r,true));die;
        $data['goods_name'] = I('post.goods_name');
        $data['goods_price'] = I('post.goods_price');
        $bool = M('goods_xcx')->add($data);

        if($bool){
            $da['s'] = 1;
        }else{
            $da['s'] = 2;
        }

        return $this->ajaxReturn($da);
    }
    public function xcx_goods_search(){
        $goods_name = I('post.goods_name');
        $where['goods_name'] = ['like',"%$goods_name%"];
        $rows = M('goods')->where($where)->select();


        return $this->ajaxReturn($rows);
    }

    public function lunbo(){
        $map['is_del'] = 1;
        $rows = M('goods')->field('id,goods_pic')->where($map)->select();
        return $this->ajaxReturn($rows);
    }


    public function goodsList(){

        $page = I('get.page');
        $start = ($page-1)*2;
        $rows = M('goods')->limit($start,2)->select();
        return $this->ajaxReturn($rows);
    }

    public function login(){

        $where['admin_name'] = I('get.uname');
        $where['admin_pass'] = md5(md5(I('get.upass')));

        $rows = M('manager')->where($where)->find();
        return $this->ajaxReturn($rows);
    }
}