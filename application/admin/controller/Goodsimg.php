<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Image;
use think\Validate;

class Goodsimg extends Common
{
		
	private $filename; //该数组存放upload处理后的资源中SaveName，Filename

	public function lst(){
        $list = Db('goodsimg')->field('a.*,b.goodsname')->alias('a')->join('yz_goods b','a.gid=b.id')->paginate(5);

		// $list = Db('goodsimg')->paginate(5);
		$this->assign('list',$list);		
		return view();
	}

	//添加
	public function add(){
		if(request()->isPost()){
			$data = input('post.');

			//判断是否添加有图片
            if($_FILES['pic']['error'] == 4){$this->error('请添加一张图片');}
			// 上传图片
			$this->filename = $this->upload();
			//注意:必须拼接成下面这种格式才能删除
			$pic = './uploads/'.$this->filename['dirname'].'/'.$this->filename['filename'];
			
		//缩放图片并删除原图
			$smallpic = $this->simg();	
			$data['smallpic'] = $smallpic;
			unlink($pic);

			$result = Db('goodsimg')->insert($data);
			if($result){
				$this->success('添加商品相册成功',url('lst'));
			}else{
				$this->error('添加商品相册失败');
			}
			return;

		}else{
			$gid = input('gid');
			$this->assign('gid',$gid);
			return view();
		}
	}	
		

	//修改
	 public function edit($id){
			return view();
       		 
    }    

      //删除
    public function del($id){
       
       
    }

	/*-----------------------------辅助方法---------------------*/
	//上传图片
	public function upload(){
		// 获取表单上传文件
		$file = request()->file('pic');
		// 上传到框架应用根目录/public/uploads/ 目录下
		$info = $file->validate(['size'=>356780,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
		if($info){
		// 成功上传后 获取上传信息
			 $filename['dirname'] = dirname($info->getSaveName()); 
			 $filename['filename'] = $info->getFilename();
			 return $filename;
		}else{
			// 上传失败获取错误信息
			echo $file->getError();
		}
	}
			
	//缩略图
	public function simg(){
		// $image = \think\Image::open($this->path);
		$image = \think\Image::open(request()->file('pic'));
		$simage = './uploads/'.$this->filename['dirname'].'/'.'small'.$this->filename['filename'];

		$res = $image->thumb(150, 150)->save($simage);
		 if($res){
		 	$simage = 'http://127.0.0.1/yz/public/uploads/'.$this->filename['dirname'].'/'.'small'.$this->filename['filename'];
		 	return $simage;
		 }
	}	

}
