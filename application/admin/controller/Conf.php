<?php
namespace app\admin\controller;
use think\Db; 
use app\admin\model\Conf as ConfModel;
class Conf extends Common
{
    //配置列表
    public function lst(){
        if(request()->isPost()){
            $conf = new ConfModel();
            $sorts = input('post.');
            foreach($sorts as $k=>$v){
                $conf->update(['id'=>$k,'sort' => $v]);
            }
            $this->success('更新排序成功',url('lst'));
            return;
        }
        $list = ConfModel::order('sort desc')->paginate(10);
        $this->assign('list',$list);
        return view(); 
    }

    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Conf');
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }
            if($data['values']){
                $data['values'] = str_replace('，',',',$data['values']);
            }
            $conf = new ConfModel();
            $result = $conf->save($data);

            if($result){
               $this->success('添加配置成功',url('lst'));
            }else{
               $this->error('添加配置失败');
           }
            return;
        } 
        return view(); 
    }

    public function del(){
        $id = input('id'); 
        $result = ConfModel::destroy($id);
        if($result){
            $this->success('删除配置项成功',url('lst'));
        }else{
            $this->error('删除配置项失败');
        }
    }
    
    public function edit(){
        if(request()->isPost()){
            $data = input('post.');
            $id = $data['id'];

            $validate = \think\Loader::validate('Conf');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            if($data['values']){
                $data['values'] = str_replace('，',',',$data['values']);
            }

            $conf = new ConfModel();
            $result =$conf->save($data,['id'=>$id]);

            if($result !== false){
                $this->success('修改配置成功',url('lst'));
            }else{
                $this->error('修改配置失败');
            }
            return;
        }

        $info = ConfModel::find(input('id'));
        $this->assign('info',$info);
        return view();
    }

    //显示/修改配置项
    public function conf(){
        if(request()->isPost()){
            $data = input('post.');
            // dump($data);exit;
            // 表单数组$formarr元素：sitename,keywords,descr,close等
            $formarr = array();
            foreach($data as $k=>$v){
                $formarr[] = $k;
            }

            // $_confarr为数据表中字段enname组成的数组
            $_confarr = Db('conf')->field('enname')->select();
            $confarr = array();
            foreach($_confarr as $k=>$v){
                    $confarr[] = $v['enname'];
            }

            //一个个比对数据表中字段是否在表单数组中
            foreach($confarr as $k=>$v){
                if(! in_array($v, $formarr)){
                    // $checkboxarr = $v;
                    $info = Db('conf')->where('enname',$v)->select();
                    // unset($info[0]['value']);
                }
            }

            if($data){
                foreach($data as $k=>$v){
                    ConfModel::where('enname',$k)->update(['value'=>$v]);
                }
                $this->success('修改配置成功');
            }
            return;
        } 

        $list = ConfModel::order('sort desc')->select();
        $this->assign('list',$list);
        return view();
    }
       
}