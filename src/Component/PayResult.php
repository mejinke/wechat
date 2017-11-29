<?php
namespace Wechat\Component;

use Wechat\Component;

/**
 *
 * 支付结果组件
 *
 * @package Wechat\Component
 */
class PayResult extends Component
{
    /**
     *
     * 是否验证通过
     *
     * @param $sign
     *
     * @return bool
     */
    public function isOk($sign)
    {
        if ($this->get('return_code') != 'SUCCESS' || $this->get('result_code') != 'SUCCESS')
        {
            return false;
        }

        //检查签名
        if ($sign != $this->get('sign'))
        {
            return false;
        }

        return true;
    }
}