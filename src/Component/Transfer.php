<?php
namespace Wechat\Component;
use Wechat\Component;

/**
 *
 * 企业付款
 * @see https://pay.weixin.qq.com/wiki/doc/api/tools/mch_pay.php?chapter=14_2
 *
 * @package Wechat\Component
 */
class Transfer extends Component
{

    /**
     *
     * 设置申请商户号的appid或商户号绑定的appid
     *
     * @param string $value
     */
    public function setMchAppId($value)
    {
        $this->values['mch_appid'] = $value;
    }

    /**
     *
     * 获取申请商户号的appid或商户号绑定的appid
     *
     * @return string
     */
    public function getMchAppId()
    {
        return $this->get('mch_appid');
    }

    /**
     *
     * 设置微信支付分配的商户号
     *
     * @param string $value
     */
    public function setMchId($value)
    {
        $this->values['mchid'] = $value;
    }

    /**
     *
     * 获取微信支付分配的商户号的值
     *
     * @return string
     */
    public function getMchId()
    {
        return $this->get('mchid');
    }

    /**
     *
     * 设置微信支付分配的终端设备号，商户自定义
     *
     * @param string $value
     */
    public function setDeviceInfo($value)
    {
        $this->values['device_info'] = $value;
    }

    /**
     *
     * 获取微信支付分配的终端设备号，商户自定义的值
     *
     * @return string
     */
    public function getDeviceInfo()
    {
        return $this->get('device_info');
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
     * 设置商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
     *
     * @param string $value
     */
    public function setPartnerTradeNo($value)
    {
        $this->values['partner_trade_no'] = $value;
    }

    /**
     *
     * 获取商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
     *
     * @return string
     */
    public function getPartnerTradeNo()
    {
        return $this->get('partner_trade_no');
    }

    /**
     *
     * 设置商户appid下，某用户的openid
     *
     * @param string $value
     */
    public function setOpenid($value)
    {
        $this->values['openid'] = $value;
    }

    /**
     *
     * 获取商户appid下，某用户的openid
     *
     * @return string
     */
    public function getOpenid()
    {
        return $this->get('openid');
    }

    /**
     *
     * 设置校验用户姓名选项 NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名
     *
     * @param string $value
     */
    public function setCheckName($value)
    {
        $this->values['check_name'] = $value;
    }
    /**
     *
     * 获取校验用户姓名选项 NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名
     *
     * @return string
     */
    public function getCheckName()
    {
        return $this->get('check_name');
    }

    /**
     *
     * 设置收款用户真实姓名。如果check_name设置为FORCE_CHECK，则必填用户真实姓名
     *
     * @param string $value
     */
    public function setRealUserName($value)
    {
        $this->values['re_user_name'] = $value;
    }

    /**
     *
     * 获取收款用户真实姓名。如果check_name设置为FORCE_CHECK，则必填用户真实姓名
     *
     * @return string
     */
    public function getRealUserName()
    {
        return $this->get('re_user_name');
    }

    /**
     *
     * 设置企业付款金额，单位为分
     *
     * @param string $value
     */
    public function setAmount($value)
    {
        $this->values['amount'] = $value;
    }

    /**
     *
     * 获取企业付款金额，单位为分
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->get('amount');
    }

    /**
     *
     * 设置企业付款操作说明信息。必填。
     *
     * @param string $value
     */
    public function setDesc($value)
    {
        $this->values['desc'] = $value;
    }

    /**
     *
     * 获取订单企业付款操作说明信息。必填。
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->get('desc');
    }

    /**
     *
     * 设置该IP同在商户平台设置的IP白名单中的IP没有关联，该IP可传用户端或者服务端的IP。
     *
     * @param string $value
     */
    public function setSpbillCreateIp($value)
    {
        $this->values['spbill_create_ip'] = $value;
    }

    /**
     *
     * 获取该IP同在商户平台设置的IP白名单中的IP没有关联，该IP可传用户端或者服务端的IP。
     *
     * @return string
     */
    public function getSpbillCreateIp()
    {
        return $this->get('spbill_create_ip');
    }
}