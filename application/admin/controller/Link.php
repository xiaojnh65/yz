<?php
namespace app\admin\controller;
use think\Db; 
use app\admin\model\Link as LinkModel;
class Link extends Common
{
    //栏目列表
    public function lst(){
        if(request()->isPost()){
            $link = new LinkModel();
            $sorts = input('post.');
            foreach($sorts as $k=>$v){
                $link->where('id',$k)->update(['sort' => $v]);
            }
            $this->success('更新排序成功',url('lst'));
            return;
        }
        $list = Db('link')->order('sort desc')->paginate(5);
        $this->assign('list',$list);
        return view(); 
    }

    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            // $result =   Db('link') ->insert($data);

            $validate = \think\Loader::validate('Link');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            $link = new LinkModel();
            $result = $link->save($data);

            if($result){
                $this->success('添加链接成功',url('lst'));
            }else{
                $this->error('添加链接失败');

            }
            return;  
        }
        return view(); 
    }

    public function del(){
        $id = input('id'); 
        $result = linkModel::destroy($id);
        if($result){
            $this->success('删除链接成功',url('lst'));
        }else{
            $this->error('删除链接失败');
        }
    }
    
    public function edit($id){
        if(request()->isPost()){
            $data = input('post.');
            $id = $data['id'];

            $validate = \think\Loader::validate('Link');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            $link = new LinkModel();
            $result = $link->save($data,['id'=>$id]);

            if($result !== false){
                $this->success('修改链接成功',url('lst'));
            }else{
                $this->error('修改链接失败');
            }
            return;
        }

        $id = input('id');
        $link = new LinkModel;
        $info = LinkModel::where('id',$id)->find();
        $this->assign('info',$info);
        return view();
    }
       
}