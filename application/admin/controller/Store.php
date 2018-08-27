<?php
namespace app\admin\controller;
use think\Db; 
use app\admin\model\Store as StoreModel;
class Store extends Common
{
    //列表
    public function lst(){
        $list = Db('store')->field('a.*,b.goodsname,b.smallpic')->alias('a')->join('yz_goods b','a.goods_id=b.id')->paginate(5);
         
        
        $this->assign('list',$list); 
        return view(); 
    }

    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Store');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }

            // 判断是否有库存,有就不添加,没有则添加
            $store = new StoreModel();
            $info = $store->where('goods_id',input('goods_id'))->find();
            if(! $info){
                $result = $store->save($data);
                if($result){
                    $this->success('添加商品库存成功',url('lst'));
                }else{
                    $this->error('添加商品库存失败');
                }
            }else{
                $this->error('该商品已添加过库存',url('lst'));
            }
           
            return;  
        }
        $goods_id = input('goods_id');
        $info = Db('goods')->where('id',$goods_id)->find();
        $smallpic = $info['smallpic'];

        $this->assign('goods_id',$goods_id);
        $this->assign('smallpic',$smallpic);
        return view(); 
    }
    
    public function edit(){
        if(request()->isPost()){
            $data = input('post.');
            $goods_id = $data['goods_id'];

            $validate = \think\Loader::validate('Store');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            $store = new StoreModel();
            $result = $store->save($data,['goods_id'=>$goods_id]);

            if($result !== false){
                $this->success('修改库存成功',url('lst'));
            }else{
                $this->error('修改库存失败');
            }
            return;
        }

        $goods_id = input('goods_id');
        $info = Db('goods')->where('id',$goods_id)->find();
        $smallpic = $info['smallpic'];

        $this->assign('goods_id',$goods_id);
        $this->assign('smallpic',$smallpic);
        return view(); 
    }
       
}