<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Cate extends Common
{
	 //如果删除该分类,先查出下面的子分类再删除
    protected $beforeActionList = [
    'delChild' => ['only'=>'del'],
    ];
    
	public function lst(){
		$catelist = Db('categorys')->select();
		$newCate = getCate($catelist, 0, 0);

		$this->assign('newCate',$newCate);
		return view();
	}

	public function add(){
		if(request()->isPost()){
	 	    $data = input('post.');
	 	    // dump($data);exit;
	 	    //没数据时出错，如何解决???
            $validate = \think\Loader::validate('Categorys');
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }

			$result = Db('categorys')->insert($data);
			if($result){
				$this->success('添加分类成功',url('lst'));
			}else{
				$this->error('添加分类失败');
			}
			return;

		}else{
			$catelist = Db('categorys')->select();
			$newCate = getCate($catelist, 0, 0);

			$this->assign('newCate',$newCate);
			return view();
		}
	}

	public function del($id){
		$id = input('id');
		$result = Db('categorys')->delete($id);

		if($result){
			$this->success('删除分类成功',url('lst'));
		}else{
			$this->error('删除分类失败');
		}	
	}

	public function edit($id){
        if(request()->isPost()){
            $data = input('post.');
            $id = $data['id'];
			$result = Db('categorys')->where('id',$id)->update(['pid'=>$data['pid'], 'name'=>$data['name']]);

			if($result !== false){
				$this->success('修改分类成功',url('lst'));
			}else{
				$this->error('修改分类失败');
			}
			return;

		}else{
			$id = input('id');
			$info = Db('categorys')->find($id);
			$catelist = Db('categorys')->select();
			$newCate = getCate($catelist, 0, 0);

			$this->assign('info',$info);
			$this->assign('newCate',$newCate);
			return view();
		}
	}

	 /************************辅助方法********************/
    //查出要删除的分类下，是否有子分类，有则删除
    public function delChild(){
        $id = input('id'); //要删除的当前分类的id
        $data = Db('categorys')->select();
        $info = getChild($data, $id);

        //如果不判断,要删除的分类下面没有子分类时，将会出错
        if($info){
            Db('categorys')->delete($info);
        }
    }


}
