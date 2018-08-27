<?php
namespace app\index\controller;
use think\Controller;
class Goods extends Controller
{	
	//一个分类的商品列表
    public function goods_list(){
    	$id = input('cid');
   		$goods_list = Db('goods')->where('cid',$id)->select();
   		$this->assign('goods_list',$goods_list);

   		$cate_list = Db('categorys')->select();
      $this->assign('cate_list',$cate_list); 
     	return view(); 

  	} 

  	//一个商品的详细信息
  	public function goods(){
  		$id = input('id');
  		$goods = Db('goods')->find($id);
      $this->assign('goods',$goods);

      $gid = $id;
      $goodsimg = Db('goodsimg')->where('gid',$gid)->select();
      // dump($goodsimg);exit;
      $this->assign('goodsimg',$goodsimg);
      $cate_list = Db('categorys')->select();
      $this->assign('cate_list',$cate_list); 

      $breadcrumb = getFather($cate_list, $goods['cid']);
      $this->assign('breadcrumb',$breadcrumb); 
      
      return view();
      // return 'aaa';
  	}
  
  public function addcart(){

    $data = input('post.');
    return $str = json_encode($data);
    $arr = json_decode($str);
    // return 'bbb';
  }    
}

