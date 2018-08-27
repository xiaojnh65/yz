<?php
namespace app\admin\controller;
use think\Db; 
use app\admin\model\AuthGroup as AuthGroupModel;
use app\admin\model\AuthRule as AuthRuleModel;
class AuthGroup extends Common
{
   public function lst(){
        $list = AuthGroupModel::paginate(5);
        $this->assign('list',$list);
        return view(); 
   } 

   public function add(){
        if(request()->isPost()){
            $data = input('post.');
            if($data['rules']){
              $data['rules'] = implode(',', $data['rules']);
            }
            
            $result = Db('auth_group')->insert($data);
            if($result){
                $this->success('添加用户组成功',url('lst'));
            }else{
                $this->error('添加用户组失败');
            }
        }
        /*
        $authrule_list = Db('auth_rule')->order('sort desc')->select();
        $newAuthRule = getCate($authrule_list, 0, 0);
                */                              
        $authrule = new AuthRuleModel();
        $newAuthRule = $authrule->authRuleTree();
        return view();
   }

   public function del(){
        $result = Db('auth_group')->delete(input('id'));
        if($result){
          $this->success('删除用户组成功',url('lst'));
        }else{
          $this->error('删除用户组失败');
        }
   }

   public function edit(){
        if(request()->isPost()){
            $data = input('post.');  
             if($data['rules']){
              $data['rules'] = implode(',', $data['rules']);
            }

            //从输入的数据里取键组成新的数组
            $_data = array();
            foreach($data as $k=>$v){
              $_data[] = $k;
            }
            //数组里是否有status
            if(! in_array('status', $_data)){
              $data['status'] = 0;
            }
            // dump($_data);dump($data);exit;

           /* 这个测试也成功
           if(! isset($data['status'])){
              $data['status'] = 0;
            } 
                  */

            $result = Db('auth_group')->update($data);
            if($result !== false){
              $this->success('修改用户组成功',url('lst'));
            }else{
              $this->error('修改用户组失败');
            }
        }
        $info = Db('auth_group')->find(input('id'));
        $this->assign('info',$info);
        /* 
        $authrule_list = Db('auth_rule')->order('sort desc')->select();
        $newAuthRule = getCate($authrule_list, 0, 0); 
                */
        $authrule = new AuthRuleModel();
        $newAuthRule = $authrule->authRuleTree();
        $this->assign('newAuthRule',$newAuthRule);
        return view();
   }
       
}