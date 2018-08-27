<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;

class Common extends Controller
{
	
	public function _initialize(){
			if(!session('id') || !session('username')){
			$this->error('您尚未登录',url('Login/index'));
		}

			$request = Request::instance();
			$con = $request->controller();
			$action = $request->action();
			$name = $con . '/' . $action;
			$auth = new Auth();
			$noCheck = array('Index/index','Admin/lst','Admin/logout');
			if(session('id') != 1){
				if(! in_array($name, $noCheck)){
					if(! $auth->check($name, session('id'))) {
					$this->error('没有权限',url('Index/index'));
			  		}
				}
				
			}
			

	}
			
}
