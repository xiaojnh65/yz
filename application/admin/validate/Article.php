<?php
namespace app\admin\validate;
use think\Validate;
class Article extends Validate
{
	protected $rule = [
	'title' => 'require|unique:article|max:25',
	'content' => 'require',
	];

	protected $message = [
	'title.require' => '文章标题不能为空',
	'title.unique' => '文章标题不能重复',
	'title.max' => '文章标题最多不能超过25个字符',
	'content.require' => '文章内容不能为空',
	];

	//指定场景
	protected $scene = [
	'add' => ['title','content'],
	'edit' => ['title'],
	];

}
