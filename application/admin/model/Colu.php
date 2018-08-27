<?php
namespace app\admin\model;
use think\Model;
use think\Controller;
class Colu extends Model
{
	public function colutree(){
		$colulist = $this->select();
		return $this->sort($colulist);
	}	

	public function sort($data, $pid=0, $level=0){
		static $arr = array();
		foreach($data as $v){
			if($v['pid'] == $pid){
				$v['level'] = $level;
				$arr[] = $v;
			
				$this->sort($data, $v['id'], $level+1);
			}
		}
		return $arr;
	}

}