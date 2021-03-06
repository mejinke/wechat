#一、安装

```
composer require mejinke/wechat
```

#二、快速使用

```
require __DIR__.'/../vendor/autoload.php';

//配置
$options = [
	'appid' => 'xx',
	'appsecret' => 'xx',
	'mchid' => 'xx',
	'key' => 'xx'
];
//例实化
$wechat = new \Wechat\Wechat($options);
```
### 配置项：


|   Name         | Default    | Desc         | 
| -------------- | ---------- | ------------------------------------------|
| appid          | -          | 微信appid                                 |
| appsecret      | -          | app secret                                |
| mchid          | -          | 商户ID                                    |
| key            | -          | 商户key                                   |
| ssl_cert       | -          | 证书绝对路径                               |
| ssl_key        | -          | 证书key绝对路径                            |
| pay_notify_url | -          | 支付成功后回调地址                          |
| cache_file     | -          | 存储微信accessToken相关重要票据信息的文件地址 |
| log_dir        | -          | 日志文件存放绝对路径                        |

##1、发送消息
```
$tp = new \Wechat\Component\MessageTemplate();
$tp->setId('xx'); //模板ID
$tp->setTouser('xxx'); //接收人openid
$tp->setData([
	'first' => ['value' => '测试标题'],
	'keyword1' => ['value' => '测试'],
	'remark' => ['value' => '马上开始吧.']
]);
$tp->setUrl('xxx');//消息跳转的URL

//发送
$wechat->service('Message')->send($tp);

```

##2、事件处理
```
$event = $wechat->service('Event');

//关注事件
$event->subscribe(function(\Wechat\Component\Event $e){
    //...
});

//取消关注事件
$event->unsubscribe(function(\Wechat\Component\Event $e){
    //..
});
//扫描带参数二维码事件
$event->scan(function(\Wechat\Component\Event $e){
    //..
});
//上报地理位置事件
$event->location(function(\Wechat\Component\Event $e){
    //..
});

//执行事件派发
$event->dispatch();
```

##3、支付

```
//下单
$order = new \Wechat\Component\PayOrder();
$order->setBody("xxxxx"); //标题
$order->setAttach("xxxx"); //附件值
$order->setOutTradeNo("xxxxx"); //订单号
$order->setTotalFee(10 * 100); //总金额
$order->setTimeStart(date("YmdHis")); //时间戳
$order->setTimeExpire(date("YmdHis", time() + 600)); //过期时间
$order->setTradeType('JSAPI'); //支付方式
$order->setOpenid("xxxxx"); //用户openid

$pay = $wechat->service('Pay');
//提交订单
$result = $pay->createOrder($order);

//获取JSAPI参数
$pay->getJsApiParameters($result);

//获取回调
$pay->notifyProcess(function($data){
	//....
});

```

##4、退款

```
//创建退款组件
$refund = new \Wechat\Component\PayRefund();
$refund->setTransactionId('4200000074201802089227802768'); //微信生成的订单号，在支付通知中有返回
//$refund->setsetOutTradeNo('1217752501201407033233368018'); //商户系统内部订单号,可选；和transactionId 二选一
$refund->setOutRefundNo(date("YmdHis")); //商户唯一退款单号
$refund->setTotalFee(10); //订单金额：分
$refund->setRefundFee(10); //退款金额：分
//发起退款
$wechat->service('Pay')->refund($re);

```

##5、自定义菜单
```
$menu = new \Wechat\Component\Menu();

//添加一个View Button
$id = $menu->addViewButton('官网', 'http://www.xxxx.com');
//添加子菜单
$menu->addViewButton('主页', 'http://www.xxxx.com', $id);

//添加一个Click Button
$menu->addClickButton('最新', 'news');

//提交创建
$wechat->service('Menu')->create($menu);
```

##6、多媒体
```
//下载多媒体资源
$wechat->service('Media')->download('xxxxxxx-media_id', '保存路径');
//下载speex格式高质量语音文件
$wechat->service('Media')->downloadSpeex('xxxxxxx-media_id', '保存路径');
```
