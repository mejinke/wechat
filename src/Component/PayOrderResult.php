<?php
namespace Wechat\Component;
use Wechat\Component;

/**
 *
 * 下单结果类
 *
 * @package Wechat\Component
 */
class PayOrderResult extends Component
{
    /**
     *
     * 调用接口提交的公众账号ID
     *
     * @var
     */
    private $appid;

    /**
     *
     * 调用接口提交的商户号
     *
     * @var
     */
    private $mchId;

    /**
     *
     * 自定义参数，可以为请求支付的终端设备号等
     *
     * @var
     */
    private $deviceInfo;

    /**
     *
     * 串微信返回的签名值，详见签名算法
     * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_3
     * @var
     */
    private $sign;

    /**
     *
     * 微信返回的随机字符
     *
     * @var
     */
    private $nonceStr;

    /**
     *
     * 交易类型，取值为：JSAPI，NATIVE，APP等，说明详见参数规定
     * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_2
     * @var
     */
    private $tradeType;

    /**
     *
     * 微信生成的预支付会话标识，用于后续接口调用中使用，该值有效期为2小时
     *
     * @var
     */
    private $prepayId;

    /**
     *
     * rade_type为NATIVE时有返回，用于生成二维码，展示给用户进行扫码支付
     *
     * @var
     */
    private $codeUrl;

    /**
     * OrderResult constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        foreach ($values as $k => $v)
        {
            $name = preg_replace_callback('/([-_]+([a-z]{1}))/i',function($matches){
                return strtoupper($matches[2]);
            },$k);

            if (property_exists($this, $name))
            {
                $this->$name = $v;
            }
        }
    }

    /**
     *
     * ${CARET}
     *
     * @return mixed
     */
    public function getAppid()
    {
        return $this->appid;
    }

    /**
     *
     * ${CARET}
     *
     * @return mixed
     */
    public function getMchId()
    {
        return $this->mchId;
    }

    /**
     *
     * ${CARET}
     *
     * @return mixed
     */
    public function getDeviceInfo()
    {
        return $this->deviceInfo;
    }

    /**
     *
     * ${CARET}
     *
     * @return mixed
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     *
     * ${CARET}
     *
     * @return mixed
     */
    public function getNonceStr()
    {
        return $this->nonceStr;
    }

    /**
     *
     * ${CARET}
     *
     * @return mixed
     */
    public function getTradeType()
    {
        return $this->tradeType;
    }

    /**
     *
     * ${CARET}
     *
     * @return mixed
     */
    public function getPrepayId()
    {
        return $this->prepayId;
    }

    /**
     *
     * ${CARET}
     *
     * @return mixed
     */
    public function getCodeUrl()
    {
        return $this->codeUrl;
    }
}