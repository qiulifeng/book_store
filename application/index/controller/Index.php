<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use app\index\model\User;
use app\index\model\Book;
class Index  extends \think\Controller
{
	protected $beforeActionList = [
       'gosession' =>  ['except'=>'login,logindo'],    //tp前置方法，不管执行那个方法，都要先执行gosession ， 除了login,login_all方法
    ];
    //定义前置控制器
    public function gosession()
    {   
    	//判断是否登录
        $id=Session::get('id');
    	if(!$id)
    	{
    		$this->error('请先登录','login');
    	}
    }
    //登录成功后跳到主页
    public function index()
    {
        $db = db('book');
		$data= $db->field('book_id, book_name, book_newprice ,book_img')->where('book_issepprice=1')->find();
		$this->assign('tejiabook',$data);	
		return $this->fetch('index');	
    }
     //退出登录
    public function loginout()
    {
    	session::clear();
        $this->success('退出成功','login');
    }
	//跳到登录页面
	public function login()
	{
		return $this->fetch('login');
	}
	//核对用户名和密码是否匹配
	public function logindo()
	{
		//$u=new \app\index\model\User();
		$db = db('user');
		$username=input('post.username');
		$password=md5(input('post.password'));
		//查询数据库
		 $list=$db->where(['user_name'=>$username,'user_pwd'=>$password])->find();

		if ($list) {
			//如果存在就存入session
			Session::set('name',$username);
			Session::set('id',$list['user_id']);
			Session::set('sex',$list['user_sex']);
			Session::set('email',$list['user_email']);

			$this->success('登录成功','index/index');
		}else{
			$this->error('登录失败','index/login');
		}
	}
	//跳到个人信息
	public function message(){
		$db = db('user');
		$list=$db->where('user_id',$_SESSION['think']['id'])->find();
		$this->assign('xinxi',$list);
		return $this->fetch('message');

	}
	//跳到修改信息页面
	public function editmessage(){
		$db = db('user');
		$list=$db->where('user_id',$_SESSION['think']['id'])->find();
		$this->assign('xinxi',$list);
		return $this->fetch();
	}
	//修改信息方法
	public function editmessagedo(){
		$db = db('user');
		$name = input('post.username');
		$email = input('post.email');
		$sex = input('post.gender');
		$modify = $db->where('user_id',$_SESSION['think']['id'])->update(['user_name'=>$name,'user_sex'=>$sex,'user_email'=>$email]);
		if ($modify) {
			$this->success("修改成功",'index/message');
		}else{
			$this->error("修改失败",'index/message');
		}
	}
	//跳到修改密码页面
	public function editpassword(){
		return $this->fetch();
	}
	//修改密码方法
	public function editpassworddo(){
		$db = db('user');
		$list=$db->where('user_id',$_SESSION['think']['id'])->find();
		$password = md5(input('post.password'));
		$repass = md5(input('post.repass'));
		$nrepass = md5(input('post.nrepass'));
		if ($list['user_pwd']==$password) {
			if ($repass==$nrepass) {
				$modify = $db->where('user_id',$_SESSION['think']['id'])->update(['user_pwd'=>$repass]);
				if ($modify) {
					$this->success("密码更改成功,请重新登录",'index/login');
				}else{
					$this->error("密码更改失败",'index/editpassword');
				}
			}else{
				$this->error("两次密码不同",'index/editpassword');
			}
		}else{
			$this->error("原密码不对",'index/editpassword');
		}

	}
	//跳到书店公告页面
	public function public2(){
        return $this->fetch();
    }
    //跳到联系我们页面
    public function contact(){
        return $this->fetch();
    }
    //跳到意见反馈页面
 public function idea(){
        return $this->fetch();
    }
    //跳到帮助中心页面
    public function help(){
        return $this->fetch();
    }
    //订书
     public function add(){
        return $this->fetch();
    }


}