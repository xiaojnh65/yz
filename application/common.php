<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**递归处理数据库查询出来的数组
	 *@param array $data 数据库结果集处理成的数组
	 *@param int $pid 分类的父id
	 *@param int $count 表示分类的级别
	 *@return array $rec 处理之后的数组
	 */
	function getCate ($data, $pid = 0, $count = 0) {
		//声明一个新数组，用来保存处理之后的数组
		static $rec = array();
		//遍历数组
		foreach ($data as $v) {
			//判断该条分类的记录的父id，是否我们传进来的$pid的值
			if ($v['pid'] == $pid) {
				//为每个分类添加缩进的级别
				$v['count'] = $count;
				//var_dump($v);
				$rec[] = $v;
				//递归：数组，该条记录的id
				getCate($data, $v['id'], $count +1);
			}
		}
		return $rec;
	}

	 /**
	 *查询某个分类的所有子分类的id
	 *@param array $data 所有分类的数组
	 *
	 */
	function getChild ($data, $pid = 0) {
		//声明一个新数组，用来保存处理之后的数组
		static $rec = array();
		//遍历数组
		foreach ($data as $v) {
			//判断该条分类的记录的父id，是否我们传进来的$pid的值
			if ($v['pid'] == $pid) {
				$rec[] = $v['id'];
				//递归：数组，该条记录的id
				getChild($data, $v['id']);
			}
		}
		return $rec;
	}

	 /**
	 *查询某个分类的所有上级分类的id
	 *@param array $data 所有分类的数组
	 *
	 */
	function getParent ($data, $id) {
		//声明一个新数组，用来保存处理之后的数组
		static $rec = array();
		//遍历数组
		foreach ($data as $v) {
			//判断该条分类的记录的父id，是否我们传进来的$id的值
			if ($v['id'] == $id) {
				$rec[] = $v['id'];
				//递归：数组，该条记录的pid
				getParent($data, $v['pid']);
			}
		}
		return $rec;
	}

	  //查找父类的函数,用于面包屑;$cid只是参数而已
    function getFather($data, $cid){
    	//声明变量保存查出来的数据
    	static $rec = array();
    	foreach($data as $v){
    		//判断当前传递的cid是否是分类表中数据id
    		if($cid == $v['id']){
    			$rec[] = $v;
	    		getfather($data, $v['pid']);
    		}
    	}
    	//按键倒序:引用传递,改变原数组结构
    	krsort($rec);
    	return $rec;
    }

