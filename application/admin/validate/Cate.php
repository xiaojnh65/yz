<?php
namespace app\admin\validate;
use think\Validate;
class Cate extends Validate
{
	protected $rule = [
	'name' => 'require|unique:categorys|max:32|min:1',
	];

	protected $message = [
	'name.require' => '分类名称不能为空',
	'name.unique' => '分类名称不能重复',
	'name.max' => '分类名称最多不能超过32个字符',
	'name.max' => '分类名称最少不能少于1个字符',
	];

	//指定场景
	protected $scene = [
	'add' => ['name'],
	'edit' => ['name'],
	];

}
