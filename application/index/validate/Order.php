<?php
namespace app\index\validate;
use think\Validate;

class Order extends Validate
{
	protected $rule = [
		'shr_name' => 'require|max:30|chsDash',
		'shr_province' => 'require',
		'shr_city' => 'require',
		'shr_area' => 'require',
		'shr_address' => 'require',
		'shr_tel' => 'require',
		'pay_method' => 'require',
		'post_method' => 'require',
	];
	protected $message = [
		'shr_name.require' => '收货人姓名必须填写',
		'shr_name.max' => '收货人姓名长度不能多于30个字符',
		'shr_name.chsDash' => '收货人姓名格式只能是汉字、字母、数字和下划线_及破折号-',
		'shr_province.require' => '收货人所在省不能为空',
		'shr_city.require' => '收货人城市不能为空',
		'shr_area.require' => '收货人地区不能为空',
		'shr_address.require' => '收货人地址不能为空',
		'shr_tel.requirel' => '收货人电话不能为空',
		'pay_method.require' => '支付方式不能为空',
		'post_method.require' => '送货方式不能为空',
	];
	protected $scene = [
		
	'add' => ['shr_name','shr_province','shr_city','shr_area','shr_address','shr_tel','pay_method','post_method'],
	];
}
