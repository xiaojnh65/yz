<?php
namespace app\index\validate;
use think\Validate;

class Webusers extends Validate
{
	protected $rule = [
		'username' => 'require|unique:admin|max:20|chsDash',
		'pass' => 'require|min:6',
	];
	protected $message = [
		'username.require' => '管理员名称必须填写',
		'username.unique' => '管理员名称不能重复',
		'username.max' => '名称长度不能多于20个字符',
		'username.chsDash' => '名称格式只能是汉字、字母、数字和下划线_及破折号-',
		'pass.require' => '管理员密码必须填写',
		'pass.min' => '密码最少不能少于6个字符',
	];
	protected $scene = [
		
	'add' => ['username','pass'],
							//密码不改则使用原来的,改就不能少于6个字符
	'edit' => ['username','pass'=>'min:6'],
	];
}
