<?php
namespace app\admin\controller;

use think\Controller;

class Base extends Controller{
    public function _initialize(){
        $uid = session('uid');
        if($uid == null){
            $this->rediect('Login/login','请先登录后操作');
        }
    }
}