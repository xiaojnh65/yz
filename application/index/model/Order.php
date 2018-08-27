<?php
namespace app\index\model;
use think\Model;
use think\Db; 
use app\index\model\Cart as CartModel;
use app\admin\model\Store as StoreModel;
use app\index\model\Order_detail as Order_detailModel;
class Order extends Model 
{
	protected static function init()	
	{
		Order::event('before_insert', function ($data) {
    		$cart = new CartModel;
    		$cartData = $cart->cartlist();
    		if(count($cartData) == 0){
				$this->error = '必须先购买商品才能下单';
				return FALSE;
			}

    		// 循环购物车中每件商品检查库存量够不够，并且计算总价
			// 加锁-> 高并发下单时，库存量会出现混乱的问题，加锁来解决
    		$order = new Order();
			$order->fp = fopen('./order.lock', 'r');
			flock($order->fp, LOCK_EX);
			$totalPrice = 0; // 总价
			$store = new StoreModel();
			// 循环购物车中所有的商品
			foreach ($cartData as $k => $v)
			{
				
				// 取出这件商品的库存量
				$goods_store[] = $store->field('goods_store')->where('goods_id',$v['goods_id'])->find();
				if($goods_store < $v['goods_number'])
				{
					$this->error = '商品库存量不足无法下单';
					return FALSE;
				}
				// 计算总价
				$totalPrice += $v['price'] * $v['goods_number'];
			}
			// 下单前把定单的其他信息补就即可
			$data['member_id'] = session('id');
			$data['addtime'] = time();
			$data['total_price'] = $totalPrice;
			// 启用事务
			Db::startTrans();
		});


		Order_detail::event('after_insert', function ($data) {
		// 再处理定单商品表
		// 把购物车中的数据存到定单商品表即可
		$cart = new CartModel;
		$cartData = $cart->cartlist();
		// 循环购物车中的商品，1：减少库存量 2：插入到定单商品表
		$store = new StoreModel();// 库存量的模型
		$order_detail = new Order_detailModel(); //订单详情的模型
		foreach ($cartData as $k => $v)
		{
			
			// 减少库存量
			$result = $store->where('goods_id',$v['goods_id'])->setDec('goods_store', $v['goods_number']);
			if($result === FALSE)
			{
				Db::rollback();
				return FALSE;
			}
			// 插入到定单商品表
			$rs = $order_detail->add(array(
				'order_id' => $data['id'],
				'member_id' => session('id'),
				'goods_id' => $v['goods_id'],
				'goods_price' => $v['price'],
				'goods_number' => $v['goods_number'],
			));
			if($rs === FALSE)
			{
				Db::rollback();
				return FALSE;
			}
		}
		Db::commit(); // 提交事务
		// 释放锁
		flock($order->fp, LOCK_UN);
		fclose($order->fp);
		// 清空购物车中所选择的商品
		$cart->clearDb();
							
		});


	}
}

