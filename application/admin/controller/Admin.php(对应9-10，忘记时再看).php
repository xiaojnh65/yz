<?php
/*
一些查询练习
    1. $list = Db('admin')->where('id','gt','5')->where('username','like','高%')->select();
    2. $list = Db('admin')->where('id','gt','5')->field('id,username,pass,addtime')->select();
  用模型实现查询
    1. $admin = new AdminModel();$list = $admin->select();//这里select可以用all代替
    2. $list = AdminModel::all('1,2,3'); //不用实例化
    3. $list = AdminModel::where('username','高洛峰')->find();//find和get一样
         foreach($list as $key=>$val){
                echo $val->username;
                echo '<br>';
            }
 */   

namespace app\admin\controller;
use think\Controller;
use app\admin\model\Admin as AdminModel;
// use think\Db; //第三种方法
class Admin extends Controller
{
    public function lst(){
        // $list = Db('admin')->select();
        $admin = new AdminModel();
        $list = $admin->getadmin();
       
        $this->assign('list',$list);
        return view();
    }

    public function add(){
      if(request()->isPost()){
        // $result = Db('admin')->insert($_POST); //第一种方法
        // $result = \think\Db::name('admin')->insert($_POST); //第二种方法 
        // $result = Db::name('admin')->insert($_POST); //第三种方法
        // $result = \think\Db::table('yz_admin')->insert($_POST); //第四种方法

        $data = input('post.');
        $admin = new AdminModel();
        $result = $admin->addadmin($data);

        if($result){
            $this->success('添加管理员成功',url('lst')); 
        }else{
            $this->error('添加管理员失败'); 
        }
        return;
      } 

        return view();
    }

    public function edit($id){
        $info = Db('admin')->find($id);
        if(request()->isPost()){
            $data = input('post.');
            $id = $data['id'];

            $result = Db('admin')->where('id',$id)->update(['username'=>$data['username'],'pass'=>md5($data['pass'])]);
            
            if($result){
                $this->success('修改管理员成功',url('lst'));
            }else{
                $this->error('修改管理员失败');
            }
            return;
        }else{
            $id = input('id');
            $this->assign('info',$info);
            return view();
        }
    }

    public function del($id){
        $id = input('id');
        $result = Db('admin')->delete($id);
        if($result){
            $this->success('删除管理员成功',url('lst')); 
        }else{
            $this->error('删除管理员失败'); 
        }
    }

}
