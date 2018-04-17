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
 * 企业付款服务类
 *
 * @see https://pay.weixin.qq.com/wiki/doc/api/tools/mch_pay.php?chapter=14_2
 *
 * @package Wechat\Service
 */
class Transfer extends Wechat
{
    /**
     * Transfer constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);
    }

    /**
     *
     * 发起支付
     *
     * 用于企业向微信用户个人付款
     * 目前支持向指定微信用户的openid付款。（获取openid参见微信公众平台开发者文档： 网页授权获取用户基本信息）
     *
     * @see https://pay.weixin.qq.com/wiki/doc/api/tools/mch_pay.php?chapter=14_2
     *
     * @param Component\Transfer $transfer
     * @throws WechatException
     * @return Component\TransferResult
     */
    public function payment(Component\Transfer $transfer)
    {
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";

        //检测必填参数
        if(!$transfer->has('partner_trade_no'))
        {
            $this->exception("缺少商户订单号partner_trade_no");
        }
        else if(!$transfer->has('openid'))
        {
            $this->exception("缺少用户openid");
        }
        else if(!$transfer->has('check_name'))
        {
            $this->exception("缺少校验用户姓名选项check_name");
        }
        else if(!$transfer->has('amount'))
        {
            $this->exception("缺少金额amount");
        }
        else if(!$transfer->has('desc'))
        {
            $this->exception("缺少企业付款描述信息desc");
        }

        $transfer->setMchAppId($this->options['appid']);//公众账号ID
        $transfer->setMchId($this->options['mchid']);//商户号
        $transfer->setSpbillCreateIp(!isset($_SERVER['REMOTE_ADDR']) ? '127.0.0.1' : $_SERVER['REMOTE_ADDR']);//终端ip
        $transfer->setNonceStr($this->getNonceStr());//随机字符串

        //签名
        $transfer->setSign($this->makeSign($transfer->toArray()));

        try
        {
            $res = Request::post($url, $transfer->toXml(), ['ssl_cert' => $this->options['ssl_cert'], 'ssl_key' => $this->options['ssl_key']]);
            $response = (new Component())->initFromXml($res);

            if ($response == false)
            {
                $this->exception('企业付款发起支付失败 '.$res);
            }
            if ($response['return_code'] == 'FAIL')
            {
                $this->exception('企业付款发起支付失败 '.$response['return_msg']);
            }

            $res = new Component\TransferResult($response);

            if ($res->getResultCode() != 'SUCCESS')
            {
                try
                {
                    $this->exception('企业付款发起支付失败 '.$res->getErrCodeDes());
                }
                catch (WechatException $e){}
            }

            return $res;
        }
        catch (WechatException $e)
        {
            $this->exception($e->getMessage());
        }
    }
}