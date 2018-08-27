<?php
namespace app\index\model;
use think\Model;
class Cart extends Model 
{
	// 加入购物车
	public function addToCart($goods_id, $goods_number = 1)
	{
		$mid = session('id');
		// 如果登录了就加入到数据库中，否则就加入到COOKIE中
		if($mid)
		{
			$cartModel = new Cart();
			$has = $cartModel->where(array(
				'member_id' => array('eq', $mid),
				'goods_id' => array('eq', $goods_id),
			))->find();
			// 判断是否商品已经存在
			if($has)
				$cartModel->where('id='.$has['id'])->setInc('goods_number', $goods_number);
			else 
				//写入数据库
				$cartModel->data([
					'goods_id' => $goods_id,
					'goods_number' => $goods_number,
					'member_id' => $mid
				]);
				$cartModel->save();
		}

		else 
		{
			// 先从COOKIE中取出购物车的数组
			$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			// dump($cart);

			//判断购物车是否存在新添加的商品,如果存在,叠加商品数量.不存在则把商品ID和数量写入
			$key = input('post.id');

			if(array_key_exists($key, $cart)){
				$cart[$key] += input('post.num');
				
			}else{
				$cart[$key] = input('post.num');
			}

			// dump($cart);
			// 把这个数组存回到cookie
			$aMonth = 30 * 86400;

			setcookie('cart', serialize($cart), time() + $aMonth, '/', 'yz.com');
				
		}
	}

	// 购物车列表
	public function cartList()
	{
		$mid = session('id');
		if($mid)
		{
			$cartModel = new Cart();
			$_cart = $cartModel->where('member_id', $mid)->select();
		}
		else 
		{
			$_cart_ = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			// 转化这个数组结构和从数据库中取出的数组结构一样，都是二维的
			$_cart = array();
			foreach ($_cart_ as $k => $v)
			{
				// 从下标中解析出商品ID和商品属性ID
				$_cart[] = array(
					'goods_id' => $k,
					'goods_number' => $v,
					'member_id' => 0,
				);
			}
		}

		/****************** 循环购物车中每件商品，根据ID取出商品详情页信息 *****************/
		$cart_r = array();
		// dump($_cart);exit;
		foreach ($_cart as $k => $v)
		{
			$ginfo = Db('goods')->where('id',$v['goods_id'])->select();
			$cart_r[$k]['goods_name'] = $ginfo[0]['goodsname'];
			$cart_r[$k]['smallpic'] = $ginfo[0]['smallpic'];
			$cart_r[$k]['price'] = $ginfo[0]['price'];
			$cart_r[$k]['goods_number'] = $v['goods_number'];
			$cart_r[$k]['goods_id'] = $v['goods_id'];
			
		}
		// dump($cart_r);exit;
		return $cart_r;

	}

	// 把COOKIE中的数据转移到数据库中并清空COOKIE中的数据
	public function moveDataToDb()
	{
		$mid = session('id');
		if($mid)
		{
			// 先从COOKIE中取出购物车的数据
			$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			if($cart)
			{
				// 循环每件商品加入到数据库中
				foreach ($cart as $k => $v)
				{

					$this->addToCart($k, $v);
				}
				// 清空COOKIE中的数据
				setcookie('cart', '', time()-1, '/', 'yz.com');
			}
		}
	}

	public function updateData($gid, $gn)
	{
		$mid = session('id');
		if($mid)
		{
			$cartModel = new Cart();
			if($gn == 0)
				$cartModel->where(array(
					'goods_id' => array('eq', $gid),
					'member_id' => array('eq', $mid),
				))->delete();
			else 
				$cartModel->where(array(
					'goods_id' => array('eq', $gid),
					'member_id' => array('eq', $mid),
				))->setField('goods_number', $gn);
		}
		else 
		{
			// 先从COOKIE中取出购物车的数组
			$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			$key = $gid;
			if($gn == 0)
				unset($cart[$key]);  //???unset($arr[$key]);
			else
				$cart[$key] = $gn; // ???$arr[$key] = $gn;
			// 把这个数组存回到cookie
			$aMonth = 30 * 86400;
			setcookie('cart', serialize($cart), time() + $aMonth, '/', '34.com');
		}
	}

	// 清空购物车
	public function clearDb()
	{
		$mid = session('id');
		if($mid)
		{
			$cartModel = new Cart();
			$cartModel->where('member_id', $mid)->delete();
		}
	}

}
