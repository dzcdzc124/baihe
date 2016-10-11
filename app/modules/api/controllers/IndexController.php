<?php

namespace App\Modules\Api\Controllers;

use App\Models\Product;
use App\Models\Users;
use App\Models\Order;
use App\Models\Product;
use App\Helpers\Wxpay as WxpayHelper;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        exitmsg('Access Denied.');
    }

    public function orderAction(){
        if( !$this->user || empty($this->user->id) ){
            $this->serveJson('请先登录~');
        }

        $order = Order::findByNewestOrderByUserId($this->user->id);

        if(!$order || empty($order->order_id) ){
            $this->serveJson('请先完成测试~');    
        }


        if( !empty($order->prepay_id) && $order->status == 0 && $order->expire_at > TIMESTAMP){
            $this->serveJson('Ok', 0, ['prepay_id'=>$order->prepay_id]);   
        } elseif ( $order->status == 1 ){
            $this->serveJson('已完成支付~', 1);    
        } elseif ( $order->expire_at < TIMESTAMP ){
            if( $order->status == 0){
                $order->status = 2;
                $order->save();
            }

            $newOrder = new Order;
            $newOrder->user_id = $order->user_id;
            $newOrder->product_id = $order->product_id;
            $newOrder->order_id = Order::createOrderId();
            $newOrder->total_fee = $order->total_fee;
            $newOrder->status = 0;
            $newOrder->data = $order->data;
            $newOrder->created = TIMESTAMP;
            $newOrder->updated = TIMESTAMP;

            if($newOrder->save()){
                $order = $newOrder;
            }else{
                $this->serveJson('创建新订单出错~');   
            }
        }

        $product = Product::findById($order->product_id);
        if(!$product){
            $this->serveJson('没有找打产品~');   
        }

        $res = WxpayHelper::createOrder($order, $product);
        $order->data = json_encode($res);
        if($res['errcode'] == 0){
            $order->prepay_id = $res['prepay_id'];
            $order->save();
            $this->serveJson('ok', 0, ['prepay_id'=>$res['prepay_id']]);
        }else{
            $this->serveJson($res['errmsg'], -1, $res);
        }

        return $prepay_id;
    }
}