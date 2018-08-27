<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Cart as CartModel;
use app\index\model\Order as OrderModel;
class Cart extends Controller
{	
    public function add(){
    	$data = input('post.');
    	$cart = new CartModel;
		$cart->addToCart(input('post.id'), input('post.num'));
		// redirect(url('lst')); 该写法无法跳转
        $this->success('ok', 'lst');	
    }	

    public function lst()
    {
    	$cart = new CartModel;
		$list = $cart->cartList();
        // dump($list);exit;

		$this->assign('list', $list);
    	return view();
    }

    public function ajaxUpdateData()
    {
        $gid = input('get.gid');
        $gn = input('get.gn');
        $cart = new CartModel;
        $data = $cart->updateData($gid, $gn);
    }

    public function order()
    {
        $mid = session('id');
        if(!$mid)
        {
            // 把当前这个页面的地址存到SESSION中，这样登录成功之后就跳回来了
            $url = 'Index/Cart/order'; 
            session('returnUrl', $url);
            $this->error('您未登录,请登录!',url('Index/Webusers/login'));
        }
       
        if(request()->isPost())
        {
            $data = input('post.');
            //验证收货人信息等的填写是否符合规则
            $validate = \think\Loader::validate('Order');
            if(!$validate->scene('add')->check($data)){
            $this->error($validate->getError());
            }

            $order = new OrderModel();
            $result = $order->save($data);
            if($result){
                $this->success('添加订单成功','Cart/tips'); 
            }else{
                // $this->error('添加订单失败'); 
                $this->error($order->getError());
            }
            return;
        } 

        $cart = new CartModel;
        $list = $cart->cartList();
        $this->assign('list', $list);
        return view();
    }

    public function tips()
    {
        echo '111';
        return view();
    }
}
