<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Image;
use think\Validate;

class Goods extends Common
{
	private $filename; //该数组存放upload处理后的资源中SaveName，Filename

	//列表
	public function lst(){
		//双表联查,通过商品表中分类ID查出分类表中对应ID的名称
        $list = Db('goods')->field('a.*,b.name')->alias('a')->join('yz_categorys b','a.cid=b.id')->paginate(5);

		$this->assign('list',$list);
		return view();
	}

	//添加
	public function add(){
		if(request()->isPost()){
			$data['goodsname'] = input('goodsname');
			$data['cid'] = input('cid');
			$data['market_price'] = input('market_price');
			$data['price'] = input('price');
			$data['state'] = input('state');
			$data['descr'] = input('descr');
			$data['addtime'] = time();


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

			$result = Db('goods')->insert($data);
			if($result){
				$this->success('添加商品成功',url('lst'));
			}else{
				$this->error('添加商品失败');
			}
			return;

		}else{
			$catelist = Db('categorys')->select();
			$newCate = getCate($catelist, 0, 0);

			$this->assign('newCate',$newCate);
			return view();
		}
	}

	//修改
	 public function edit($id){
        if(request()->isPost()){
        	$data['goodsname'] = input('goodsname');
			$data['cid'] = input('cid');
			$data['market_price'] = input('market_price');
			$data['price'] = input('price');
			$data['state'] = input('state');
			$data['descr'] = input('descr');
            $data = input('post.');
            $id = $data['id'];

            //判断是否更换图片
            if($_FILES['pic']['error'] == 4){
            	$result = Db('goods')->where('id',$id)->update(['cid'=>$data['cid'], 'goodsname'=>$data['goodsname'],'market_price'=>$data['market_price'],'price'=>$data['price'],'state'=>$data['state'],'descr'=>$data['descr']]);
	            
			}else{
				//找出原缩略图并删除
				$info = Db('goods')->find($id);
				$smallpic = "." .  substr($info['smallpic'],26);
				if($smallpic){unlink($smallpic);}

				//重新上传图片
				$this->filename = $this->upload();
				$pic = './uploads/'.$this->filename['dirname'].'/'.$this->filename['filename'];
				
				// 生成新的缩略图并删除刚上传的原图
				$smallpic = $this->simg();	
				$data['smallpic'] = $smallpic;	
				unlink($pic);
				// dump( $this->smallpic);exit;
            	$result = Db('goods')->where('id',$id)->update(['cid'=>$data['cid'], 'goodsname'=>$data['goodsname'],'price'=>$data['price'],'state'=>$data['state'],'descr'=>$data['descr'],'smallpic'=>$data['smallpic']]);
			}
            
			//有或没有更换图片，更新数据库后判断
            if($result !== false){
                $this->success('修改商品成功',url('lst'));
				unlink($pic);
            }else{
                $this->error('修改商品失败');
            	unlink($pic);
				unlink($smallpic);
            }
            return;

        }else{
            $id = input('id');
            $info = Db('goods')->find($id);
            $catelist = Db('categorys')->select();
            $newCate = getCate($catelist, 0, 0);

            $this->assign('info',$info);
            $this->assign('newCate',$newCate);
            return view();
        }
    }    

      //删除
    public function del($id){
        $id = input('id');
        //找出数据库里对应缩略图并删除
		$info = Db('goods')->find($id);
		$smallpic = "." .  substr($info['smallpic'],26);

		if($smallpic){
			$res = unlink($smallpic);
			if(! $res){
				$this->error('删除图片不成功');

			}else{
				$result = Db('goods')->delete($id);
		        if($result){
		            $this->success('删除商品成功',url('lst')); 
		        }else{
		            $this->error('删除商品失败'); 
		        }
			}
		}
       
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
