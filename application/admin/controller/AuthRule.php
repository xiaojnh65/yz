<?php
namespace app\admin\controller;
use think\Db; 
use app\admin\model\AuthRule as AuthRuleModel;
class AuthRule extends Common
{
    public function lst(){
        if(request()->isPost()){
            $sorts = input('post.');
            foreach($sorts as $k=>$v){
                Db('auth_rule')->where('id',$k)->update(['sort' => $v]);
            }
            $this->success('更新排序成功',url('lst'));
            return;
        }
        $authrule_list = Db('auth_rule')->order('sort desc')->select();
        $newAuthRule = getCate($authrule_list, 0, 0);
        $this->assign('newAuthRule',$newAuthRule);
        return view();
    }    
       
   public function add(){
        if(request()->isPost()){
            $data = input('post.');
            //获取上级权限的level，不是顶级权限则level加1,否则level为0
            $arr = Db('auth_rule')->where('id',input('pid'))->field('level')->find();
            if($arr){
                $data['level'] = $arr['level'] + 1;    

            }else{
                $data['level'] = 0;
            }

            $result = Db('auth_rule')->insert($data);
            if($result){
                $this->success('添加权限成功',url('lst'));
            }else{
                $this->error('添加权限失败');
            }
        }

        $authrule_list = Db('auth_rule')->order('sort desc')->select();
        $newAuthRule = getCate($authrule_list, 0, 0);
        $this->assign('newAuthRule',$newAuthRule);
        return view();
    }    

    public function del(){
        // $result = Db('auth_rule')->delete(input('id'));
        $authrule = new AuthRuleModel();
        $authrule->getparentid(input('id'));
        if($result){
            $this->success('删除权限成功',url('lst'));
        }else{
            $this->error('删除权限失败');
        }
    }

    public function edit(){
        if(request()->isPost()){
            $data = input('post.');
            //获取上级权限的level，不是顶级权限则level加1,否则level为0
            $arr = Db('auth_rule')->where('id',input('pid'))->field('level')->find();
            if($arr){
                $data['level'] = $arr['level'] + 1;    

            }else{
                $data['level'] = 0;
            }

            $result = Db('auth_rule')->where('id',input('id'))->update($data);
            if($result !== false){
                $this->success('修改权限成功',url('lst'));
            }else{
                $this->error('修改权限失败');
            }
            return;
        }
        $info = Db('auth_rule')->find(input('id'));
        $this->assign('info',$info);

        $authrule_list = Db('auth_rule')->select();
        $newAuthRule = getCate($authrule_list, 0, 0);
        $this->assign('newAuthRule',$newAuthRule);
        return view();
    }

    /************************辅助方法********************/
    //查出要删除的权限下，是否有子权限，有则删除
    public function delChild(){
        
        $id = input('id'); //要删除的当前权限的id
        $data = Db('auth_rule')->select();
        $info = getChild($data, $id);
        
        /*
        $id = input('id');
        $data = Db('auth_rule')->select();
        $info = getParent($data, $id);
        dump($info);exit; */

        //如果不判断,要删除的权限下面没有子权限时，将会出错
        if($info){
            Db('auth_rule')->delete($info);
        }
    }

}