<?php
namespace app\admin\controller;
use app\admin\model\Admin as AdminModel;
use think\Db; 
class Admin extends Common
{
    //列表
    public function lst(){
        // $list = Db('admin')->paginate(5);
        // $list = Db::name('admin')->paginate(5);

        $admin = new AdminModel();
        $list = $admin->getadmin();
        $auth = new Auth();
        foreach($list as $k=>$v){
          $_groupTitle = $auth->getGroups($v['id']);
          $groupTitle = $_groupTitle[0]['title'];
          $v['groupTitle'] = $groupTitle;
        }
        // dump($list);exit; 
        $this->assign('list',$list);
        return view();
    }

    //添加
    public function add(){
      if(request()->isPost()){
        // $result = Db('admin')->insert($_POST); //该方法更常用
        $data = input('post.');

        //验证管理员名称和密码输入是否符合规则
        $validate = \think\Loader::validate('Admin');

        if(!$validate->scene('add')->check($data)){
        $this->error($validate->getError());
        }

        $admin = new AdminModel();
        $result = $admin->addadmin($data);

        if($result){
            $this->success('添加管理员成功',url('lst')); 
        }else{
            $this->error('添加管理员失败'); 
        }
        return;
      } 
        $authGroupRes = Db('auth_group')->select();
        $this->assign('authGroupRes',$authGroupRes);
        return view();
    }

    //修改
    public function edit($id){
        $info = Db('admin')->find($id);
        if(request()->isPost()){
            $data = input('post.');
            $id = $data['id'];

        //验证管理员名称和密码输入是否符合规则
        $validate = \think\Loader::validate('Admin');
        
        if(!$validate->scene('edit')->check($data)){
        $this->error($validate->getError());
        }
            $admin = new AdminModel();
            $result = $admin->saveadmin($data,$info,$id);
            // $result = Db('admin')->where('id',$id)->update(['username'=>$data['username'],'pass'=>md5($data['pass'])]);

            if($result == 2){
                $this->error('管理员名称不能为空');
            }
            if($result !== false){
                $this->success('修改管理员成功',url('lst'));
            }else{
                $this->error('修改管理员失败');
            }
            return;
        }else{
            $id = input('id');
            $this->assign('info',$info);
            //得知道当前管理员所属的组id;uid正好是admin表中的id
            $access_info = Db('auth_group_access')->where('uid',$id)->find();
            $this->assign('gid',$access_info['group_id']);
            $authGroupRes = Db('auth_group')->select();
            $this->assign('authGroupRes',$authGroupRes);
            return view();
        }
    }

    //删除
    public function del($id){
        $id = input('id');
        $result = Db('admin')->delete($id);
        if($result){
            Db('auth_group_access')->where('uid',$id)->delete('uid');
            $this->success('删除管理员成功',url('lst')); 
        }else{
            $this->error('删除管理员失败'); 
        }
    }

    public function logout(){
            session(null);
            $this->success('退出系统成功',url('Login/index'));
        }


}
