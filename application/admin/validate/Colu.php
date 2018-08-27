<?php
namespace app\admin\validate;
use think\Validate;
class Colu extends Validate
{
	protected $rule = [
	'coluname' => 'require|unique:colu|max:30',
	];

	protected $message = [
	'coluname.require' => '栏目名称不能为空',
	'coluname.unique' => '栏目名称不能重复',
	'coluname.max' => '栏目名称最多不能超过30个字符',
	];

	//指定场景
	protected $scene = [
	'add' => ['coluname'],
	'edit' => ['coluname'],
	];

}
