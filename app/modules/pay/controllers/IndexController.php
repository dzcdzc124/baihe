<?php

namespace App\Modules\Pay\Controllers;

use App\Models\Order;
use App\Helpers\Wxpay as WxpayHelper;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        exitmsg('Access Denied.');
    }

    public function payCallbackAction(){
        $return = [
            "return_code" => 'SUCCESS',
            "return_msg" => 'Ok'
        ];


        $xmldata = file_get_contents("php://input");
        $data = (array)simplexml_load_string($xmldata, 'SimpleXMLElement', LIBXML_NOCDATA);
        
        if($data['return_code'] == "SUCCESS"){

            $checkSign = WxpayHelper::checkSign($data);
            if($checkSign){
                $order_id = $data['out_trade_no'];

                $order = Order::findByOrderId($order_id);
                if( $order && ($order->transaction_id == "" || $order->status != 1) ) {
                    $order->transaction_id = $data['transaction_id'];
                    $order->status = 1;
                    $order->type = 'wxpay';
                    $order->updated = TIMESTAMP;
                    $order->save();
                }
            }else{
                $return["return_code"] = "FAIL";
                $return["return_msg"] = "签名校验失败";
            }
        }

        $this->output( $this->dataToXML($return) );
    }

   

    public function dataToXML($data) {
        $xml = '<xml>';
        foreach ($data as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<$key>". $val . "</$key>";
            } else {
                $xml .= "<$key>". $this->XMLSafeStr($val) ."</$key>";
            }
        }
        $xml .= '</xml>';
        return $xml;
    }

    public function XMLSafeStr($str) {
        return '<![CDATA[' . preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $str) . ']]>';   
    }
}