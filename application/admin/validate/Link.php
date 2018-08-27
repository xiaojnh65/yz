<?php
namespace app\admin\validate;
use think\Validate;
class Link extends Validate
{
	protected $rule = [
	'title' => 'require|unique:link|max:25',
	'url' => 'url|require|unique:link|max:60',
	'descr' => 'require',
	];

	protected $message = [
	'title.require' => '标题不能为空',
	'title.unique' => '标题不能重复',
	'title.max' => '标题最多不能超过25个字符',
	'url.url' => '链接地址格式不正确',
	'url.require' => '链接地址不能为空',
	'url.unique' => '链接地址不能重复',
	'url.max' => '链接地址最多不能超过60个字符',
	'descr.require' => '描述不能为空',
	];

	//指定场景
	protected $scene = [
	// 'add' => ['title'=>'require|url','url','descr'],
	'add' => ['title','url','descr'],
	'edit' => ['title','url'],
	];

}
