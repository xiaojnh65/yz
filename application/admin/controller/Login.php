<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Admin as AdminModel;
class Login extends Controller
{
	public function index(){
		if(request()->isPost()){
			$data = input('post.');
			$this->check_verify(input('checkcode'));
		
			$admin = new AdminModel();
			$result = $admin->login($data);
			if($result == 1){
				$this->error('用户名不存在');
			}
			if($result == 2){
				$this->error('用户名和密码不匹配');
			}else{
				$this->success('登录成功',url('admin/lst'));
			}
			return;

		}else{
			return view();
		}
	}

	/*********************辅助方法********************************/
	// 检测输入的验证码是否正确， $code为用户输入的验证码字符串
		function check_verify($code){
			if(!captcha_check($code)){
				$this->error('验证码失败');
			}else{
				return true;
			}
		}


}