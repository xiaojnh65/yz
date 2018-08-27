<?php
namespace app\admin\controller;
use think\Db; 
use app\admin\model\Colu as ColuModel;
use app\admin\model\Article as ArticleModel;
class Colu extends Common
{
    //如果删除该分类,先查出下面的子分类再删除
    protected $beforeActionList = [
    'delChild' => ['only'=>'del'],
    ];

    //栏目列表
    public function lst(){
        if(request()->isPost()){
            $sorts = input('post.');
            foreach($sorts as $k=>$v){
                Db('colu')->where('id',$k)->update(['sort' => $v]);
            }
            $this->success('更新排序成功',url('lst'));
            return;
        }
       
        $colulist = Db('colu')->order('sort desc')->select();
        $newColu = getCate($colulist);
        $this->assign('newColu',$newColu); 
        return view();
    }

    public function add(){
        $colu = new ColuModel();
        
        if(request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Colu');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            $result = $colu->save($data);

            if($result){
                $this->success('添加栏目成功',url('lst'));
            }else{
                $this->error('添加栏目失败');
            }
            return;

        }else{
            $colulist = Db('colu')->select();
            $newColu = getCate($colulist);
            $this->assign('newColu',$newColu); 
            return view();
        }
    }

    public function del($id){
        $id = input('id');
        $result = Db('colu')->delete($id);
        if($result){
        $this->success('删除栏目成功',url('lst'));
        }else{
            $this->error('删除栏目失败');
        }   
    }
    
    public function edit($id){
        if(request()->isPost()){
            $data = input('post.');
            $id = $data['id'];
            $validate = \think\Loader::validate('Colu');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }

            // $result = Db('colu')->where('id',$id)->update(['pid'=>$data['pid'], 'coluname'=>$data['coluname'],'type'=>$data['type']]);
            $colu = new ColuModel();
            $result = $colu->save($data,['id'=>$id]);

            if($result){
                $this->success('修改栏目成功',url('lst'));
            }else{
                $this->error('修改栏目失败');
            }
            return;

        }else{
            $id = input('id');
            $info = Db('colu')->find($id);
            $colulist = Db('colu')->select();
            $newColu = getCate($colulist, 0, 0);

            $this->assign('info',$info);
            $this->assign('newColu',$newColu);
            return view();
        }
    }                        
  /************************辅助方法********************/
    //查出要删除的栏目下，是否有子栏目，有则删除
    public function delChild(){
        $id = input('id'); //要删除的当前栏目的id
        $data = Db('colu')->select();
        //获取子栏目ID
        $childIds = getChild($data, $id);
        $allColuId = $childIds;
        //把本栏目也加入数组,这样数组$allColuId包括本栏目及它下面的子栏目
        $allColuId[] = $id;

        //为什么不能删除文章下的图片???
        foreach($allColuId as $k => $v){
            $article = new ArticleModel;
            $article->where('coluid',$v)->delete();
        }

        //如果不判断,要删除的栏目下面没有子栏目时，将会出错
        if($childIds){
            Db('colu')->delete($childIds);
        }
    }


}