<?php
namespace app\admin\model;
use think\Model;
class Article extends Model
{
	protected static function init()	{
		Article::event('before_insert', function ($data) {
			if($_FILES['pic']['tmp_name']){
	            $file = request()->file('pic');
	            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	            if($info){
	              // $pic = ROOT_PATH . 'public'. DS . 'uploads'.'/'.$info->getSaveName();
	              $pic = '/yz/' . 'public'. DS . 'uploads'.'/'.$info->getSaveName();
	              $data['pic'] = $pic;
	            }
          	}
		});

		Article::event('before_update', function ($data) {
			$id = $data['id'];
			if($_FILES['pic']['tmp_name']){
				$article = new Article;
	            $info = Article::find($id);
				$picpath = $_SERVER['DOCUMENT_ROOT'] . $info['pic'];
				// echo $picpath;exit;
				if($info['pic']) unlink($picpath);

				$file = request()->file('pic');
	            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	            if($info){
	              // $pic = ROOT_PATH . 'public'. DS . 'uploads'.'/'.$info->getSaveName();
	              $pic = '/yz/' . 'public'. DS . 'uploads'.'/'.$info->getSaveName();
	              $data['pic'] = $pic;
	            }  
          	}
		});

		Article::event('before_delete', function ($data) {
				$id = $data['id'];
				$article = new Article;
	            $info = Article::find($id);
				$picpath = $_SERVER['DOCUMENT_ROOT'] . $info['pic'];
				if($info['pic']) unlink($picpath);
		});

	}
}