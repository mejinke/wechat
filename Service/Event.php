<?php
namespace Wechat\Service;

use Wechat\Component\MessageTemplate;
use Wechat\Request;
use Wechat\Wechat;
use Wechat\WechatException;

/**
 *
 * 微信事件服务类
 * 在微信用户和公众号产生交互的过程中，用户的某些操作会使得微信服务器通过事件推送的形式通知到开发者在开发者中心处设置的服务器地址，从而开发者可以获取到该信息
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140454
 *
 * @package Wechat\Service
 */
class Event extends Wechat
{

    /**
     *
     * Event
     *
     * @var \Wechat\Component\Event
     */
    private $event;

    /**
     *
     * 事件回调列表
     *
     * @var array
     */
    private $eventClosures = [];

    public function __construct(array $options)
    {
        parent::__construct($options);

        try
        {
            $this->event = new \Wechat\Component\Event();
            $this->event->initFromXml($GLOBALS['HTTP_RAW_POST_DATA']);
        }
        catch (WechatException $e)
        {
            $this->exception($e->getMessage());
        }
    }

    /**
     * 事件分派
     */
    public function dispatch()
    {
        $this->writeDebug("Event => ".$this->event->getEvent(). ' OpenID => '.$this->event->getFromUserName());

        $e = strtolower($this->event->getEvent());
        if (isset($this->eventClosures[$e]))
        {
            $this->eventClosures[$e]($this->event);
        }
        else
        {
            $this->writeDebug("Event(NotFound) => ".$this->event->getEvent());
        }
    }

    /**
     *
     * 用户订阅时触发
     *
     * @param \Closure $cb
     */
    public function subscribe(\Closure $cb)
    {
        $this->eventClosures['subscribe'] = $cb;
    }

    /**
     *
     * 用户取消订阅时触发
     *
     * @param \Closure $cb
     */
    public function unsubscribe(\Closure $cb)
    {
        $this->eventClosures['unsubscribe'] = $cb;
    }

    /**
     *
     * 扫描带参数二维码事件
     *
     * @param \Closure $cb
     */
    public function scan(\Closure $cb)
    {
        $this->eventClosures['scan'] = $cb;
    }

    /**
     *
     * 上报地理位置事件
     *
     * @param \Closure $cb
     */
    public function location(\Closure $cb)
    {
        $this->eventClosures['location'] = $cb;
    }
}