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
     *
     * 除被扫支付场景以外，商户系统先调用该接口在微信支付服务后台生成预支付交易单，
     * 返回正确的预支付交易回话标识后再按扫码、JSAPI、APP等不同场景生成交易串调起支付。
     *
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
     * 申请退款
     *
     * 当交易发生之后一段时间内，由于买家或者卖家的原因需要退款时，卖家可以通过退款接口将支付款退还给买家，
     * 微信支付将在收到退款请求并且验证成功之后，按照退款规则将支付款按原路退到买家帐号上。
     * 注意：
     * 1、交易时间超过一年的订单无法提交退款
     * 2、微信支付退款支持单笔交易分多次退款，多次退款需要提交原支付订单的商户订单号和设置不同的退款单号。申请退款总金额不能超过订单金额。
     * 一笔退款失败后重新提交，请不要更换退款单号，请使用原商户退款单号
     * 3、请求频率限制：150qps，即每秒钟正常的申请退款请求次数不超过150次
     * 错误或无效请求频率限制：6qps，即每秒钟异常或错误的退款申请请求不超过6次
     * 4、每个支付订单的部分退款次数不能超过50次
     *
     * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_4
     *
     * @param Component\PayRefund $refund
     *
     * @return bool
     */
    public function refund(Component\PayRefund $refund)
    {
        $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";

        //检测必填参数
        if(!$refund->has('transaction_id') && $refund->has('out_trade_no'))
        {
            $this->exception("微信订单号(transaction_id)、商户订单号(out_trade_no)不能都为空");
        }
        else if(!$refund->has('out_refund_no'))
        {
            $this->exception("商户退款单号(out_refund_no)为必填项！");
        }
        else if(!$refund->has('total_fee'))
        {
            $this->exception("订单金额(total_fee)为必填项");
        }
        else if(!$refund->has('refund_fee'))
        {
            $this->exception("退款金额(refund_fee)为必填项");
        }

        $refund->setAppId($this->options['appid']);//公众账号ID
        $refund->setMchId($this->options['mchid']);//商户号
        $refund->setNonceStr($this->getNonceStr());//随机字符串

        //签名
        $refund->setSign($this->makeSign($refund->toArray()));

        try
        {
            $res = Request::post($url, $refund->toXml(), ['ssl_cert' => $this->options['ssl_cert'], 'ssl_key' => $this->options['ssl_key']]);
            $response = (new Component())->initFromXml($res);

            if ($response == false)
            {
                $this->exception('申请退款 '.$res);
            }
            if ($response['return_code'] == 'FAIL')
            {
                $this->exception('申请退款 '.$response['return_msg']);
            }
            if ($response['result_code'] == 'FAIL')
            {
                $this->exception('申请退款 '.$response['err_code_des']);
            }

            return true;
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