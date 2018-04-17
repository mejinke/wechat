<?php
namespace Wechat\Component;
use Wechat\Component;

/**
 *
 * 企业付款结果
 * @see https://pay.weixin.qq.com/wiki/doc/api/tools/mch_pay.php?chapter=14_2
 *
 * @package Wechat\Component
 */
class TransferResult extends Component
{

    /**
     *
     * 申请商户号的appid或商户号绑定的appid
     *
     * @var string
     */
    private $mchAppid;

    /**
     *
     * 微信支付分配的商户号
     *
     * @var string
     */
    private $mchid;

    /**
     *
     * 微信支付分配的终端设备号，
     *
     * @var string
     */
    private $deviceInfo;

    /**
     *
     * 随机字符串，不长于32位
     *
     * @var string
     */
    private $nonceStr;

    /**
     *
     * 业务结果 SUCCESS/FAIL
     * 注意：当状态为FAIL时，存在业务结果未明确的情况，所以如果状态FAIL，请务必再请求一次查询接口[请务必关注错误代码（err_code字段）
     * 通过查询查询接口确认此次付款的结果。]，以确认此次付款的结果。
     *
     * @var string
     */
    private $resultCode;

    /**
     *
     * 错误代码
     * 注意：出现未明确的错误码时（SYSTEMERROR等）[出现系统错误的错误码时（SYSTEMERROR），请务必用原商户订单号重试，或通过查询接口确认此次付款的结果。]
     * 请务必再请求一次查询接口，以确认此次付款的结果。
     *
     * @var string
     */
    private $errCode;

    /**
     *
     * 错误代码描述
     *
     * @var string
     */
    private $errCodeDes;

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
     * @return string
     */
    public function getMchAppid()
    {
        return $this->mchAppid;
    }

    /**
     *
     * ${CARET}
     *
     * @return string
     */
    public function getMchid()
    {
        return $this->mchid;
    }

    /**
     *
     * ${CARET}
     *
     * @return string
     */
    public function getDeviceInfo()
    {
        return $this->deviceInfo;
    }

    /**
     *
     * ${CARET}
     *
     * @return string
     */
    public function getNonceStr()
    {
        return $this->nonceStr;
    }

    /**
     *
     * ${CARET}
     *
     * @return string
     */
    public function getResultCode()
    {
        return $this->resultCode;
    }

    /**
     *
     * ${CARET}
     *
     * @return string
     */
    public function getErrCode()
    {
        return $this->errCode;
    }

    /**
     *
     * ${CARET}
     *
     * @return string
     */
    public function getErrCodeDes()
    {
        return $this->errCodeDes;
    }
}