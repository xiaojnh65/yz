<?php
namespace app\admin\controller;
use think\Db; 
use app\admin\model\Article as ArticleModel;
class Article extends Common
{
   public function lst(){
        $list = Db('article')->field('a.*,b.coluname')->alias('a')->join('yz_colu b','a.coluid=b.id')->paginate(5);
        $this->assign('list',$list);
        return view();
   } 

   public function add(){
        if(request()->isPost()){
          $data = input('post.');
          $article = new ArticleModel;

          /*
          if($_FILES['pic']['tmp_name']){
            $file = request()->file('pic');
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
              // $pic = ROOT_PATH . 'public'. DS . 'uploads'.'/'.$info->getSaveName();
              $pic = 'http://127.0.0.1/yz/' . 'public'. DS . 'uploads'.'/'.$info->getSaveName();
              $data['pic'] = $pic;
            }
          }
               */ 

           $validate = \think\Loader::validate('Article');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            $result = $article->save($data);
            if($result !== false){
                $this->success('添加文章成功',url('lst'));      
            }else{
                $this->error('添加文章失败');
            }

              return;
        }
        $colulist = Db('colu')->select();
        $newColu = getCate($colulist, 0, 0);
        $this->assign('newColu',$newColu); 
        return view();
   } 

   public function edit($id){
        if(request()->isPost()){
            $data = input('post.');
            $id = $data['id'];
            $validate = \think\Loader::validate('Article');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }

            $article = new ArticleModel;
            $result = $article->update($data, ['id'=>$id]);

            if($result){
              $this->success('修改文章成功','lst');
            }else{
              $this->error('修改文章失败');
            }
            return;
      }
        $id = input('id');
        $info =  Db('article')->find($id);

        $colulist = Db('colu')->select();
        $newColu = getCate($colulist, 0, 0);

        $this->assign('newColu',$newColu); 
        $this->assign('info',$info);
        return view();
   } 

   public function del(){
       $id = input('id'); 
       $result = ArticleModel::destroy($id);
       if($result){
         $this->success('删除文章成功','lst'); 
       }else{
         $this->error('删除文章失败'); 
       }
   }

}