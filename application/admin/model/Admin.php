<?php
namespace app\admin\model;
use think\Model;
use think\Controller;
use think\Db;
class Admin extends Model
{
	public function addadmin($data){
		if(empty($data) || !is_array($data)){
			return false;
		}
		if(!empty($data['pass'])){
			$data['pass'] = md5($data['pass']);
		}
		$adminData = array();
		$adminData['username'] = $data['username'];
		$adminData['pass'] = $data['pass'];
		if($this->save($adminData)){
			$groupAccess['uid'] = $this->id;
			$groupAccess['group_id'] = $data['group_id'];
			Db('auth_group_access')->insert($groupAccess);
			return true;
		}else{
			return false;
		}
	}

	//查询出所有数据并分页
	public function getadmin(){
		return $this::paginate(5);
	}

	//处理修改管理员及密码
	public function saveadmin($data,$info,$id){
		//名称不能为空,密码为空则使用原密码,修改密码则加密
            if(! $data['username']){
                return 2; //管理员名称不能为空');
            }
            if(! $data['pass']){
                $data['pass'] = $info['pass'];
            }
            else{
                $data['pass'] = md5($data['pass']);
            }
			Db('auth_group_access')->where('uid', $id)->update(['group_id'=>$data['group_id']]);
            return $this->where('id',$id)->update(['username'=>$data['username'],'pass'=>$data['pass']]);
            // return Db('admin')->where('id',$id)->update(['username'=>$data['username'],'pass'=>$data['pass']]);

	}

	public function login($data){
		// $result = Db('admin')->where('username',$data['username'])->find();
		$result =  Admin::getByusername($data['username']);
		if(!$result){
			return 1; //用户名不存在
			
		}else{
			if($result['pass'] !== md5($data['pass'])){
				return 2; //用户名和密码不匹配
			}else{
				session('id', $result['id']);
				session('username', $result['username']);
				return 3; //登录成功
			}
		}
	}

	

}