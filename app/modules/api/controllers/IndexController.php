<?php

namespace App\Modules\Api\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Code;
use App\Helpers\Wxpay as WxpayHelper;
use App\Wechat\Wxpay;


class IndexController extends ControllerBase
{
    public function indexAction()
    {
        exitmsg('Access Denied.');
    }

    public function submitAction(){
        if( !$this->user || empty($this->user->id) ){
            $this->serveJson('请先登录~');
        }

        $answer = $this->request->get('result');
        $sex = (int) $this->request->get('sex');

        if( !isset($answer) ){
            $this->serveJson('请先答题~');
        }

        $totalAvoid = 0;
        $totalAnxious = 0;

        $answerArray = json_decode($answer);
        //计分规则：奇数项为回避量表，偶数项为焦虑量表。3,15,19,25,29,31,33,35反向计分。22反向计分。
        for ($i = 0; $i < count($answerArray); $i++) {
            // 奇数题，回避分数:
            if ($i % 2 == 0) {
                if ($i == 2 || $i == 14 || $i == 18 || $i == 24 || $i == 28 || $i == 32 || $i == 34) {
                    $actualValue = 8 - $answerArray[$i];
                    $totalAvoid += $actualValue;
                } else {
                    $totalAvoid += $answerArray[$i];
                }
                // 偶数题，焦虑分数:
            } elseif ($i % 2 == 1) {
                if ($i == 21) {
                    $actualValue = 8 - $answerArray[$i];
                    $totalAnxious += $actualValue;
                } else {
                    $totalAnxious += $answerArray[$i];
                }
            }
        }

        $zAvoid = $zAnxious = 0;
        // 计算标准分:
        if ($sex == 1) { // 男性
            $zAvoid = ($totalAvoid / 18 - 3.4) / 0.6;
            $zAnxious = ($totalAnxious / 18 - 3.7) / 0.9;
        } elseif ($sex == 0) { // 女性
            $zAvoid = ($totalAvoid / 18 - 3.2) / 0.8;
            $zAnxious = ($totalAnxious / 18 - 3.7) / 0.8;
        }

        $zAvoid = round( $zAvoid , 2 );
        $zAnxious = round( $zAnxious , 2 );

        $result = $this->getResult($zAvoid, $zAnxious);

        $product = Product::findByModule("pdq");

        if($product){
            //$order = Order::findByNewestOrderByUserId($this->user->id);

            //覆盖多余订单
            //if(!$order || $order->status == 1 || $order->status == 3){
            //}

            $order = new Order;
            $order->user_id = $this->user->id;
            $order->product_id = $product->id;
            $order->order_id = Order::createOrderId();
            $order->prepay_id = '';
            $order->transaction_id = '';
            $order->total_fee = $product->total_fee;
            $order->status = 0;
            $order->type = '';
            $order->data = $result['type'];
            $order->sex = $sex;
            $order->avoid = $zAvoid;
            $order->anxious = $zAnxious;
            $order->response = '';
            $order->expire_at = 0;
            $order->created = TIMESTAMP;
            $order->updated = TIMESTAMP;
            $order->save();

            $this->user->result = $result['type'];
            $this->user->updated = TIMESTAMP;
            $this->user->save();

            $result['order_id'] = $order->order_id;
            $result['sex'] = $sex;
            $this->serveJson('OK', 0, $result);
        }else{
            $this->serveJson('找不到产品设置~');
        }
    }

    //微信下单
    public function orderAction(){
        if( !$this->user || empty($this->user->id) ){
            $this->serveJson('请先登录~');
        }

        $order_id = $this->request->get('order_id');

        $order = Order::findByOrderId($order_id);
        if(!$order || empty($order->order_id) ){
            $this->serveJson('请先完成测试~');    
        }
        
        if( $order->user_id != $this->user->id ){
            $this->serveJson('抱歉，你无权查看该测试记录~');      
        }

        if( $order->status == 3 ){
            $this->serveJson('无效测试记录~');      
        }


        if ( $order->status == 1 ){
            //直接返回结果
            //$this->serveJson('已完成支付~', 1);    
            $this->resultAction($order);
        }

        if( empty($order->prepay_id) || $order->expire_at < TIMESTAMP){
            //更新订单
            $order->status = 0;
            $order->prepay_id = '';
            $order->expire_at = 0;
            $order->created = TIMESTAMP;
            $order->updated = TIMESTAMP;
            $order->save();
        
            $product = Product::findById($order->product_id);
            if(!$product){
                $this->serveJson('没有找产品设置~');   
            }
            $res = WxpayHelper::createOrder($this->user->openId, $order, $product);
            $order->response = json_encode($res);
            
            if($res['errcode'] == 0){
                $order->prepay_id = $res['prepay_id'];
                $order->expire_at = TIMESTAMP+580;
                $order->updated = TIMESTAMP;
                $order->save();
            }else{
                //出错订单
                $order->status = 3;
                $order->response = $res['res']['xml'];
                $order->updated = TIMESTAMP;
                $order->save();
                $this->serveJson($res['errmsg'], -1, $res);
            }
        }

        $data = [
            "appId" => $this->config->wechat->appId,     //公众号名称，由商户传入     
            "timeStamp" => TIMESTAMP,         //时间戳，自1970年以来的秒数     
            "nonceStr" => Wxpay::createNonceStr(), //随机串
            "package" => "prepay_id=".$order->prepay_id,     
            "signType" => "MD5"         //微信签名方式：     
        ];
        $data["paySign"] = Wxpay::getSign($data, $this->config->wechat->mchKey);     //微信签名 

        $this->serveJson('ok', 0, $data);
    }

    public function exchangeAction(){
        if( !$this->user || empty($this->user->id) ){
            $this->serveJson('请先登录~');
        }

        $code = strtolower( trim( $this->request->get('code') ) );
        $record = Code::findByCode($code);
        if(!$record){
            $this->serveJson('无效的兑换码~');
        }elseif($record->status == 1){
            $this->serveJson('兑换码已被使用~');
        }

        $order_id = $this->request->get('order_id');

        $order = Order::findByOrderId($order_id);
        if(!$order || empty($order->order_id) ){
            $this->serveJson('请先完成测试~');    
        }
        
        if( $order->user_id != $this->user->id ){
            $this->serveJson('抱歉，你无权查看该测试记录~');      
        }

        if( $order->status == 3 ){
            $this->serveJson('无效测试记录~');      
        }


        if ( $order->status == 1 ){
            //直接返回结果
            //$this->serveJson('已完成支付或兑换~', 1);    
            $this->resultAction($order);
        }

        if( $order->status == 3){
            $newOrder = new Order;
            $newOrder->user_id = $order->user_id;
            $newOrder->product_id = $order->product_id;
            $newOrder->order_id = Order::createOrderId();
            $newOrder->total_fee = $order->total_fee;
            $newOrder->status = 0;
            $newOrder->data = $order->data;
            $newOrder->created = TIMESTAMP;
            $newOrder->updated = TIMESTAMP;
            $newOrder->save();

            $order = $newOrder;
        }else{
            //订单失效,覆盖
            $order->status = 0;
            $order->prepay_id = '';
            $order->expire_at = 0;
            $order->created = TIMESTAMP;
            $order->updated = TIMESTAMP;
            $order->save();
        }

        $order->status = 1;
        $order->type = 'code';
        $order->updated = TIMESTAMP;
        $order->save();

        $record->status = 1;
        $record->user_id = $this->user->id;
        $record->order_id = $order->order_id;
        $record->updated = TIMESTAMP;
        $record->save();

        $this->resultAction($order);
    }

    public function resultAction($order = NULL){
        if( !isset($order) ){
            if( !$this->user || empty($this->user->id) ){
                $this->serveJson('请先登录~');
            }

            $order_id = $this->request->get('order_id');

            $order = Order::findByOrderId($order_id);
            if(!$order || empty($order->order_id) ){
                $this->serveJson('找不到该测试记录~');   
            }
            
            if( $order->user_id != $this->user->id ){
                $this->serveJson('抱歉，你无权查看该测试记录~');      
            }

            if( $order->status == 3 ){
                $this->serveJson('无效测试记录~');      
            }

            if( $order->status != 1 ){
                $this->serveJson('请先完成支付或兑换~');
            }
        }

        $result = $this->getResult($order->avoid, $order->anxious, true);
        $result['sex'] = $order->sex;
        $this->serveJson('ok', 0, $result);
    }

    public function infoAction(){
        if( !$this->user || empty($this->user->id) ){
            $this->serveJson('请先登录~');
        }

        $orders = Order::findByUserId($this->user->id);

        $result = [];
        foreach($orders as $order){
            if( $order->status == 3){
                continue;
            }

            $result[] = [
                'created' => date('m月d日', $order->created),
                'data' => $order->data,
                'type' => $order->type,
                'order_id' => $order->order_id
            ];
        }

        $this->serveJson('ok', 0 , ['list' => $result]);
    }

    public function getOrderAction(){
        if( !$this->user || empty($this->user->id) ){
            $this->serveJson('请先登录~');
        }

        $order_id = $this->request->get('order_id');

        $order = Order::findByOrderId($order_id);
        if(!$order){
            $this->serveJson('找不到该测试记录~');   
        }
        if( $order->user_id != $this->user->id ){
            $this->serveJson('抱歉，你无权查看该测试记录~');      
        }

        if( $order->status == 3 ){
            $this->serveJson('无效测试记录~');      
        }

        if( $order->status == 0 || $order->status == 2 ){
            $result = $this->getResult($order->avoid, $order->anxious);
            $result['order_id'] = $order_id;
            $result['sex'] = $order->sex;
            $this->serveJson('请先完成支付或兑换', 1, $result);
        }

        if( $order->status == 1 ){
            $this->resultAction($order);
        }
        
        $this->serveJson('未知错误');
    }

    public function getResult($zAvoid, $zAnxious, $isDesc = false){
        // 四个成人依恋的类型: 安全型、倾注型、轻视型、害怕型。
        // 0.安全型依恋的人认为自己是有价值的, 并且期望他人是有情感效用性和反应性的;
        // 1.倾注型依恋觉得自己是没有多大价值的, 但对他人有积极的评价;
        // 2.轻视型依恋的人认为自己是有价值的, 而他人是不值得信赖的;
        // 3.害怕型依恋的人则觉得自己是无价值的, 而且他人也不值得信赖。
        // 判断所属类型:
        $result = [];
        $attachmentType;
        if ($zAvoid > 0) {
            if ($zAnxious > 0) {
                $attachmentType = '害怕型';
            } else {
                $attachmentType = '轻视型';
            }
        } else {
            if ($zAnxious > 0) {
                $attachmentType = '倾注型';
            } else {
                $attachmentType = '安全型';
            }
        }
        $result['desc'] = $this->getResultDetail($attachmentType, $zAvoid, $zAnxious, $isDesc);

        if ($zAvoid < -0.5 && $zAnxious < -0.5) {
            $attachmentType .= '(典型)';
        } else if ($zAvoid <= 0.5 && $zAnxious <= 0.5) {
            $attachmentType .= '(轻度)';
        } else if ($zAvoid <= 1.5 && $zAnxious <= 1.5) {
            $attachmentType .= '(典型)';
        } else {
            $attachmentType .= '(重度)';
        }

        $result['type'] = $attachmentType;
        $result['avoid'] = $zAvoid;
        $result['anxious'] = $zAnxious;

        return $result;
    }

    public function getResultDetail($type, $zAvoid, $zAnxious, $isDesc){
        $desc;
        if($isDesc){
            switch ($type) {
                case '安全型':
                    $desc = [
                        [
                            'title' => '类型综述',
                            'intro' => '安全型的人本能地都能信任恋人，相信恋人爱他们，关怀他们，而且从来都不忧心忡忡、害怕失去恋情。他们享受亲密感，有非凡的沟通能力，善于满足恋人的需求。安全型对负面信息没有那么敏感，所以比较容易保持镇静。善于缓解冲突、态度温和、善于交流、享受亲密感，不设界限，相信自己能够改善恋情。安全型应该发扬的优点：支持恋人、不干涉、鼓励恋人。安全型的人会积极地表达自己的真实需要。他们懂得抚慰和关怀恋人。他们也会袒露自己的想法和感受看恋人如何回应。安全型的人能够帮助不安全的伴侣获得安全型的行为习惯，从而改变不安全伴侣的依恋风格。安全型的缺点就是显得普通，甚至有点无聊。'
                        ],
                        [
                            'title' => '吸引力',
                            'intro' => '让人感觉容易亲近'
                        ],
                        [
                            'title' => '对伴侣的态度',
                            'intro' => '在面对亲密关系中的事件时倾向于积极地理解伴侣的行为。这种宽容地理解能够帮助改善与伴侣的亲密关系。此类型的人会信赖自己的伴侣，认为伴侣会给自己足够的支持，对亲密关系的展望更乐观，更容易记起过去的积极事件，容易对他人做出正面评价，但不会轻率地给人下结论。'
                        ],
                        [
                            'title' => '沟通模式',
                            'intro' => '比较热情，富有表达性，会勇敢地自我暴露，也能诚实表达自己的情感，对伴侣比较开放。'
                        ],
                        [
                            'title' => '相互依赖',
                            'intro' => '在共有情形下舒适自在，只要伴侣需要，他们就会提供关心和支持，他们乐于接纳伴侣对自己的依赖。轻松接纳伴侣的依赖反而让伴侣更容易独立自主。'
                        ],
                        [
                            'title' => '喜欢的支持类型',
                            'intro' => '情感类支持'
                        ],
                        [
                            'title' => '恋爱危机的应对方式',
                            'intro' => '不容易嫉妒，能够恰当地表达自己的忧虑并尝试修复关系，也更容易宽容。'
                        ]
                    ];
                    break;
                case '倾注型':
                    $desc = [
                        [
                            'title' => '类型综述',
                            'intro' => '对情绪的反应敏感。比一般人更快速地感知一个人的情绪变化。但与此同时，容易过快地下结论，造成误解他人的情绪感受。当依恋系统被启动后，倾注型的人会无法集中精力做别的事。不断回忆对方的好处。不安和焦虑，只要在与对方取得联系后才能缓解。倾注型的人容易察觉到对方情绪的波动，并热衷于猜测对方言行背后的态度。如果不能被及时地安抚，他们会不断放大自己的想象到自己难以承受的状况。在这个过程中，倾注型的人会采取不同的防御行为，包括所有试图吸引恋人注意，与其重建联系的行为。只要是利用周围状况，使对方不得不注意自己的行为都是防御行为：拼命联系恋人、假装不理对方、比较双方的付出、表示反感和敌意、提出分手威胁，故意让恋人吃醋。'
                        ],
                        [
                            'title' => '吸引力',
                            'intro' => '容易在约会开始给对方留下糟糕的印象：忧心忡忡、紧张兮兮、没有主见、沉默寡言。'
                        ],
                        [
                            'title' => '对伴侣的态度',
                            'intro' => '在面对亲密关系中的事件时容易责备伴侣的不当行为。对伴侣没有足够的信任，对亲密关系的展望偏悲观，担心伴侣的离开。容易记起伴侣的体贴行为，但是也容易对伴侣的不当行为过度反应。容易轻率地下结论。'
                        ],
                        [
                            'title' => '沟通模式',
                            'intro' => '热情，富有表达性，不敢自我暴露，不敢诚实表达自己的情感，对伴侣比较容易妥协。'
                        ],
                        [
                            'title' => '相互依赖',
                            'intro' => '过度担心伴侣的离开，整天提心吊胆防止出现冲突和其它代价过高的关系。倾向于以共有规范慷慨地对待未来的伴侣，但一旦别人同样待他们就会变得焦虑。亲密相处会让他们感到焦虑。向伴侣提供的帮助太冒失而有控制性'
                        ],
                        [
                            'title' => '喜欢的支持类型',
                            'intro' => '情感类支持'
                        ],
                        [
                            'title' => '恋爱危机的应对方式',
                            'intro' => '认为自己得到的社会支持不够。更容易体验到孤独的感觉，这种孤独感容易引起自我价值感的降低。如果亲密关系变得没有以前那么好，那么受到这种关系贬值的影响比其他类型的人更大，同时也容易放大他们感受到的伤害。此类型人希望接近伴侣，又担心伴侣不会回报足够的爱恋。而且很容易引起嫉妒。对于恋爱中问题的应对，他们会表达自己的忧虑并尝试修复关系。对于伴侣的不当行为，他们会更容易感到受伤，同时希望这种受伤的感觉能引起伴侣的内疚，从而对亲密关系进行修复。而且，这种类型在遇到亲密关系的冲突时更容易妥协。'
                        ]
                    ];
                    break;
                case '轻视型':
                    $desc = [
                        [
                            'title' => '类型综述',
                            'intro' => '轻视型的人对待感情的态度并不直截了当。他们倾向于压抑真实的感受不表达出来。只有在当忙于应付其它问题的时候，真实的情感才能流露出来。轻视型的人常常在面对现实生活中比较大的压力或者难以轻松处理的困境的时候就会希望能够有个可以依恋的对象，而当现实生活中的问题被解决之后，他们就会认为自己不需要依恋的对象。轻视的人会把自我依靠当成独立，认为所有人都应该依靠自己而不应该依赖他人。甚至认为情感依赖也是软弱的表现。他们会更多地注意到伴侣的缺点。认为伴侣应该这样或那样地提升自我。而且认为伴侣的问题的改进应该是伴侣的责任而不是自己也应该投身其中。轻视型的人不容易察觉到伴侣的感受。他们强调自我依靠，也避免去关心伴侣的心理感受。因此，作为轻视型的伴侣常常感觉得不到情感支持以及恋爱的亲密和满足。轻视型的人对于伴侣的情绪变化不会主动沟通，常常会自我防御地揣测伴侣情绪变化的理由。'
                        ],
                        [
                            'title' => '吸引力',
                            'intro' => '对倾注型的人有吸引力。'
                        ],
                        [
                            'title' => '对伴侣的态度',
                            'intro' => '在面对亲密关系中的事件时不容易发现伴侣的体贴行为。并不在意伴侣的信任，对亲密关系的渴求也不强烈。不易对伴侣的不当行为做出反应，但也不容易对对方做出正面的评价。认为恋爱关系中的问题多是对方自己的问题，而与自己无关。'
                        ],
                        [
                            'title' => '沟通模式',
                            'intro' => '随着关系的渐进逐渐变得冷淡，较多工具性的表达而不是情感性的表达。甚少自我暴露，也不愿表达自己的情感，对于伴侣的要求较高。'
                        ],
                        [
                            'title' => '相互依赖',
                            'intro' => '更关注他们的替代选择，更容易被新结识的人吸引。珍视自己的自足和独立，接近动机较弱，并不期待将来的伴侣给予帮助，因为他们也没打算做任何报答。不愿意向伴侣提供帮助'
                        ],
                        [
                            'title' => '喜欢的支持类型',
                            'intro' => '工具性的建议或意见'
                        ],
                        [
                            'title' => '恋爱危机的应对方式',
                            'intro' => '享受自己独处的时光，也不容易受到感情上的伤害。不容易产生嫉妒的感觉，对于亲密关系中的冲突会假装一切都好或者装作一点不在乎，以逃避问题或者否认自己的苦恼。在遇到冲突的时候常常容易愤怒，对冲突也不容易妥协，容易做出被动，破坏性的反应。通常是不采取行动，坐视亲密关系的恶化。'
                        ]
                    ];
                    break;
                case '害怕型':
                    $desc = [
                        [
                            'title' => '类型综述',
                            'intro' => '害怕型的人会选择性地表现倾注和轻视型的特点。当与倾注型的人在一起时，会表现出回避的特点，需要更多的空间，会挑剔对方的缺点，不愿意沟通，会去揣测对方的情绪变化的理由，并进行自我防御，选择用疏离来对抗。但当与轻视型的人在一起时则会表现出焦虑，担心亲密关系受到威胁，对于对方的疏离会感到不安。一方面很希望有人可以依赖，另一方面又害怕太接近。害怕型的人难以找到一个恰当的方式和恋人相处。因为类型的冲突，常常会陷入两难的困境。同时，害怕型的人常常会因为回避思维的启动而难以进入亲密关系，虽然可能会有喜欢的人，但是往往不会主动进行接触和表示。如果是被别人追求的情况下，也可能是以一种不是很情愿的情况进入亲密关系，在亲密关系中就容易挑剔和显得疏离，但一旦亲密关系面临风险，又会觉得伤心难过。'
                        ],
                        [
                            'title' => '吸引力',
                            'intro' => '会吸引倾注型的人。'
                        ],
                        [
                            'title' => '对伴侣的态度',
                            'intro' => '在面对亲密关系中的事件既不容易发现伴侣的体贴行为，也容易责备伴侣的不当行为。表现特征会在倾注型和轻视型之间摇摆，渴求亲密关系，对于伴侣既不是很放心，但又不会有强烈的反应，很多情况都会憋在自己心里不表达出来。既容易对伴侣的不当行为过度反应，又容易认为问题多是对方自己的问题，需要对方自行解决。'
                        ],
                        [
                            'title' => '沟通模式',
                            'intro' => '自我表达不多，不愿自我暴露，也不愿表达自己的情感。对于伴侣有不满，但一旦进入亲密关系又不愿放弃。'
                        ],
                        [
                            'title' => '相互依赖',
                            'intro' => '既担心伴侣的离开，也容易被新结识的人吸引。在亲密关系中总是处于一个摇摆的状态，一旦觉得亲密关系变得不稳，就会感到焦虑，担心亲密关系的破裂。一旦对方表现得比较亲近，又会有想逃避的感觉。在亲密关系中，既容易表现出对于亲密关系的控制性，又不会主动给予伴侣帮助。'
                        ],
                        [
                            'title' => '喜欢的支持类型',
                            'intro' => '工具性的建议或意见'
                        ],
                        [
                            'title' => '恋爱危机的应对方式',
                            'intro' => '感觉不到足够的社会支持，但又不会表现出对于孤独感的痛苦。表面上在亲密关系中，该类型显得一切都好，但会把感受到的焦虑不安都放在心理。对于亲密关系中的冲突感受到伤害很大，但又不容易对冲突进行妥协，更多地认为是对方的过错而不采取修复关系的行动。因此在恋爱危机出现的时候会进退失据，往往导致更严重的后果。'
                        ]
                    ];
                    break;
                default:
                    break;
            }
        }else{
            switch ($type) {
                case '安全型':
                    $desc = '安全型的人本能地信任恋人，相信恋人爱他们，关怀他们，认为伴侣会给自己足够的支持，对亲密关系的展望更乐观。<br><br>在共有情形下舒适自在，只要伴侣需要，他们就会提供关心和支持。';
                    break;
                case '倾注型':
                    $desc = '倾注型的人对情绪的反应敏感。<br><br>容易察觉到对方情绪的波动，并热衷于猜测对方言行背后的态度。倾向于以共有规范慷慨地对待未来的伴侣，但一旦别人同样待他们就会变得焦虑。';
                    break;
                case '轻视型':
                    $desc = '轻视型的人对待感情的态度并不直截了当。<br><br>他们只有当忙于应付其它问题的时候，真实的情感才能流露出来。并不在意伴侣的信任，对亲密关系的渴求也不强烈。';
                    break;
                case '害怕型':
                    $desc = '害怕型的人会选择性地表现倾注和轻视型的特点。<br><br>表现特征会在倾注型和轻视型之间摇摆，渴求亲密关系，对于伴侣既不是很放心，但又不会有强烈的反应，很多情况都会憋在自己心里不表达出来。';
                    break;
                default:
                    break;
            }
        }

        return $desc;
    }

    public function getSign($data, $key)
    {
        ksort($data, SORT_STRING);

        $signData = urldecode(http_build_query($data, null, '&', PHP_QUERY_RFC3986));
        $signData .= '&key=' . $key;
        $signStr = strtoupper(md5($signData));
        return $signStr;
    }

    public function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}