<?php
namespace app\admin\validate;
use think\Validate;
class Store extends Validate
{
	protected $rule = [
	'goods_store' => 'require|number|between:0,200',
	];

	protected $message = [
	'goods_store.require' => '商品库存不能为空',
	'goods_store.number' => '商品库存必须为数字',
	'goods_store.between' => '商品库存只能在0-200之间',
	];	

	//指定场景
	protected $scene = [
	'add' => ['goods_store'],
	'edit' => ['goods_store'],
	];

}
