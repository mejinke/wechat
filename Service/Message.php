<?php
namespace Wechat\Service;

use Wechat\Component\MessageTemplate;
use Wechat\Request;
use Wechat\Wechat;
use Wechat\WechatException;

/**
 *
 * 模板消息发送服务
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
 * @package Wechat\Service
 */
class Message extends Wechat
{

    public function __construct(array $options)
    {
        parent::__construct($options);
    }

    /**
     *
     * 发送模板消息
     *
     * @param MessageTemplate $template
     *
     * @return bool
     */
    public function send(MessageTemplate $template)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->getAccessToken();

        $data = $template->toArray();

        $result = Request::post($url, $data);

        $json = json_decode($result, true);
        if ($json == false)
        {
            $this->exception('发送微信消息失败');
        }
        if ($json['errcode'] != '0')
        {
            $this->exception('发送微信息消息失败 '.$json['errmsg'].' => '.json_encode($data));
        }

        return true;
    }
}