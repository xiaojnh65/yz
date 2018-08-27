<?php
namespace app\index\controller;
use app\index\model\Webusers as WebusersModel;
use think\Controller;

class Webusers extends Controller
{
    public function regist(){
      if(request()->isPost()){
        $data = input('post.');
        //验证前台会员名称和密码输入是否符合规则
        $validate = \think\Loader::validate('Webusers');

        if(!$validate->scene('add')->check($data)){
        $this->error($validate->getError());
        }

        $webusers = new WebusersModel();
        $result = $webusers->add_webusers($data);

        if($result){
            $this->success('添加会员成功,请登录',url('Index/Webusers/login')); 
        }else{
            $this->error('添加会员失败'); 
        }
        return;
      } 
      return view();
       
    }

    public function login(){
        if(request()->isPost()){
            $data = input('post.');
            $webusers = new WebusersModel();
            $result = $webusers->login($data);
            if($result == 1){
                $this->error('用户名不存在');
            }
            if($result == 2){
                $this->error('用户名和密码不匹配');
            }elseif($result == 3){
                    // 从session中取出有没有要跳回的地址
                    $returnUrl = session('returnUrl');
                    // dump($returnUrl);exit;
                    if($returnUrl)
                    {
                        // 从sessoin中删除掉，下次再登录就正常跳到首页
                        session('returnUrl', null);
                        $this->success('进入订单',url('Cart/order'));
                    }
                    else
                        $this->success('登录成功',url('Index/index'));
                   
            }
            return;

        }else{
            return view();
        }
    } 

    public function ajaxChkLogin(){
        if(session('id'))
        {
            $arr = array(
                'ok' => 1,
                'username' => session('username'),
            );
        }
        else
        {
            $arr = array('ok' => 0);
        }
        echo json_encode($arr);
    }

     public function logout(){
            session(null);
            $this->success('退出系统成功',url('Index/index'));
    }
   
}
