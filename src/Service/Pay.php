<?php
namespace Wechat\Service;

use Wechat\Component;
use Wechat\Component\Order;
use Wechat\Component\OrderResult;
use Wechat\Component\PayOrder;
use Wechat\Component\PayOrderResult;
use Wechat\Component\PayResult;
use Wechat\Request;
use Wechat\Wechat;
use Wechat\WechatException;

/**
 *
 * 支付服务类
 *
 * @package Wechat\Service
 */
class Pay extends Wechat
{
    /**
     * Pay constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);
    }

    /**
     *
     * 创建订单
     * 除被扫支付场景以外，商户系统先调用该接口在微信支付服务后台生成预支付交易单，返回正确的预支付交易回话标识后再按扫码、JSAPI、APP等不同场景生成交易串调起支付。
     * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_1
     *
     * @param PayOrder $order
     * @return PayOrderResult
     */
    public function createOrder(PayOrder $order)
    {
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";

        //检测必填参数
        if(!$order->has('out_trade_no'))
        {
            $this->exception("缺少统一支付接口必填参数out_trade_no！");
        }
        else if(!$order->has('body'))
        {
            $this->exception("缺少统一支付接口必填参数body！");
        }
        else if(!$order->has('total_fee'))
        {
            $this->exception("缺少统一支付接口必填参数total_fee！");
        }
        else if(!$order->has('trade_type'))
        {
            $this->exception("缺少统一支付接口必填参数trade_type！");
        }

        //关联参数
        if($order->getTradeType() == "JSAPI" && !$order->has('openid'))
        {
            $this->exception("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！");
        }
        if($order->getTradeType() == "NATIVE" && !$order->has('product_id'))
        {
            $this->exception("统一支付接口中，缺少必填参数product_id！trade_type为JSAPI时，product_id为必填参数！");
        }

        //异步通知url未设置，则使用配置文件中的url
        if(!$order->has('notify_url'))
        {
            $order->setNotifyUrl($this->options['pay_notify_url']);//异步通知url
        }

        $order->setAppId($this->options['appid']);//公众账号ID
        $order->setMchId($this->options['mchid']);//商户号
        $order->setSpbillCreateIp($_SERVER['REMOTE_ADDR']);//终端ip
        $order->setNonceStr($this->getNonceStr());//随机字符串

        //签名
        $order->setSign($this->makeSign($order->toArray()));

        try
        {
            $res = Request::post($url, $order->toXml());
            $response = (new Component())->initFromXml($res);

            if ($response == false)
            {
                $this->exception('统一下单失败 '.$res);
            }
            if ($response['return_code'] == 'FAIL')
            {
                $this->exception('统一下单失败 '.$response['return_msg']);
            }
            if ($response['result_code'] == 'FAIL')
            {
                $this->exception('统一下单失败 '.$response['err_code_des']);
            }

            return new PayOrderResult($response);
        }
        catch (WechatException $e)
        {
            $this->exception($e->getMessage());
        }
    }

    /**
     *
     * 获取JSAPI支付的参数
     *
     * @param PayOrderResult $result
     *
     * @return array
     */
    public function getJsApiParameters(PayOrderResult $result)
    {
        $timeStamp = time();

        $values = [
            'appId' => $result->getAppid(),
            'timeStamp' => "$timeStamp",
            'nonceStr' => $this->getNonceStr(),
            'package' => "prepay_id=" . $result->getPrepayId(),
            'signType' => 'MD5',
        ];
        $values['paySign'] = $this->makeSign($values);

        return $values;
    }

    /**
     *
     * 支付异步回调
     *
     * @param \Closure $callback
     *
     * @return mixed
     */
    public function notifyProcess(\Closure $callback)
    {
        //获取通知的数据
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];

        //如果返回成功则验证签名
        try
        {
            $result = new PayResult();
            $result->initFromXml($xml);

            //检查签名是否一致
            $sign = $this->makeSign($result->toArray());
            if ($result->isOk($sign) == false)
            {
                $this->replyNotify([
                    'return_code' => 'FAIL',
                    'return_msg' => '签名不一致',
                ]);

                $this->writeDebug('签名不一致 '.json_encode($result->toArray()). ' => sign:'.$sign);
                return;
            }

            call_user_func($callback, $result->toArray());
        }
        catch (WechatException $e)
        {
            $this->exception($e->getMessage());
        }
    }

    /**
     *
     * 返回结果给微信
     *
     * @param $values
     */
    protected function replyNotify($values)
    {
        $com = new Component();
        $com->initFromArray($values);
        echo $com->toXml();
    }
}