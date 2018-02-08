<?php
namespace Wechat\Component;
use Wechat\Component;
use Wechat\WechatException;

/**
 *
 * 微信支付退款
 *
 * 当交易发生之后一段时间内，由于买家或者卖家的原因需要退款时，卖家可以通过退款接口将支付款退还给买家，
 * 微信支付将在收到退款请求并且验证成功之后，按照退款规则将支付款按原路退到买家帐号上。
 *
 * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_4
 *
 * @package Wechat\Component
 */
class PayRefund extends Component
{

    /**
     *
     * 设置微信分配的公众账号ID
     *
     * @param string $value
     */
    public function setAppId($value)
    {
        $this->values['appid'] = $value;
    }

    /**
     *
     * 获取微信分配的公众账号ID的值
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->get('appid');
    }

    /**
     *
     * 设置微信支付分配的商户号
     *
     * @param string $value
     */
    public function setMchId($value)
    {
        $this->values['mch_id'] = $value;
    }

    /**
     *
     * 获取微信支付分配的商户号的值
     *
     * @return string
     */
    public function getMchId()
    {
        return $this->get('mch_id');
    }

    /**
     *
     * 设置随机字符串，不长于32位。推荐随机数生成算法
     *
     * @param string $value
     */
    public function setNonceStr($value)
    {
        $this->values['nonce_str'] = $value;
    }

    /**
     *
     * 获取随机字符串，不长于32位。推荐随机数生成算法的值
     *
     * @return string
     */
    public function getNonceStr()
    {
        return $this->get('nonce_str');
    }

    /**
     *
     * 设置签名
     *
     * @param $sign
     */
    public function setSign($sign)
    {
        $this->values['sign'] = $sign;
    }

    /**
     *
     * 获取签名内容
     *
     * @return mixed
     */
    public function getSign()
    {
        return $this->get('sign');
    }

    /**
     *
     * 设置微信生成的订单号，在支付通知中有返回
     *
     * @param $value
     */
    public function setTransactionId($value)
    {
        $this->values['transaction_id'] = $value;
    }

    /**
     *
     * 获取微信生成的订单号，在支付通知中有返回
     *
     * @return null
     */
    public function getTransactionId()
    {
        return $this->get('transaction_id');
    }

    /**
     *
     * 设置商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*@ ，且在同一个商户号下唯一。
     *
     * @param string $value
     */
    public function setOutTradeNo($value)
    {
        $this->values['out_trade_no'] = $value;
    }

    /**
     *
     * 获取商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*@ ，且在同一个商户号下唯一。
     *
     * @return string
     */
    public function getOutTradeNo()
    {
        return $this->get('out_trade_no');
    }

    /**
     *
     * 设置商户系统内部的退款单号，商户系统内部唯一，只能是数字、大小写字母_-|*@ ，同一退款单号多次请求只退一笔。
     *
     * @param string $value
     */
    public function setOutRefundNo($value)
    {
        $this->values['out_refund_no'] = $value;
    }

    /**
     *
     * 获取商户系统内部的退款单号，商户系统内部唯一，只能是数字、大小写字母_-|*@ ，同一退款单号多次请求只退一笔。
     *
     * @return string
     */
    public function getOutRefundNo()
    {
        return $this->get('out_refund_no');
    }

    /**
     *
     * 设置订单总金额，只能为整数，详见支付金额
     * @see  https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_2
     * @param string $value
     */
    public function setTotalFee($value)
    {
        $this->values['total_fee'] = $value;
    }

    /**
     *
     * 获取订单总金额，只能为整数，详见支付金额的值
     * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_2
     * @return string
     */
    public function getTotalFee()
    {
        return $this->get('total_fee');
    }

    /**
     *
     * 设置退款总金额，订单总金额，单位为分，只能为整数，详见支付金额
     * @see  https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_2
     * @param $value
     */
    public function setRefundFee($value)
    {
        $this->values['refund_fee'] = $value;
    }

    /**
     *
     * 获取设置退款总金额，订单总金额，单位为分，只能为整数，详见支付金额
     * @see  https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_2
     *
     * @return null
     */
    public function getRefundFee()
    {
       return $this->get('refund_fee');
    }

    /**
     *
     * 设置货币种类
     * 货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
     *
     * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_2
     *
     * @param $value
     */
    public function setRefundFeeType($value)
    {
        $this->values['refund_fee_type'] = $value;
    }

    /**
     *
     * 获取货币种类
     * 货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
     *
     * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_2
     *
     * @return null
     */
    public function getRefundFeeType()
    {
        return $this->get('refund_fee_type');
    }

    /**
     *
     * 设置退款原因
     * 若商户传入，会在下发给用户的退款消息中体现退款原因
     *
     * @param $value
     */
    public function setRefundDesc($value)
    {
        $this->values['refund_desc'] = $value;
    }

    /**
     *
     * 获取退款原因
     *
     * @return null
     */
    public function getRefundDesc()
    {
        return $this->get('refund_desc');
    }
}