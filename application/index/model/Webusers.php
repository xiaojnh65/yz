<?php
namespace app\index\model;
use think\Model;
use app\index\model\Cart as CartModel;
use think\Controller;
use think\Db;
class Webusers extends Model
{
	public function add_webusers($data){
		if(empty($data) || !is_array($data)){
			return false;
		}
		if(!empty($data['pass'])){
			$data['pass'] = md5($data['pass']);
		}
		$webusersData = array();
		$webusersData['username'] = $data['username'];
		$webusersData['pass'] = $data['pass'];
		if($this->save($webusersData)){
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

	}

	public function login($data){
		$result =  Webusers::getByusername($data['username']);
		if(!$result){
			return 1; //用户名不存在
			
		}else{
			if($result['pass'] !== md5($data['pass'])){
				return 2; //用户名和密码不匹配
			}else{
				session('id', $result['id']);
				session('username', $result['username']);   
				// 把购物车中的数据从COOKIE移动到数据库
            	$cart = new CartModel();
                $cart->moveDataToDb();
				return 3; //登录成功
			}
		}
	}

	

}