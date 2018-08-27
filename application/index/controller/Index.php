<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $cate_list = Db('categorys')->select();
        $this->assign('cate_list',$cate_list); 

        $new_products = Db('goods')->paginate(5);
        $this->assign('new_products',$new_products); 
        $this->assign('page_title','妍挈美容商城,美容用品,脸部护理,身体护理,纹绣漂唇');	
    	return view();
    }

   
}
