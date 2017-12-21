<?php
namespace Wechat\Component;
use Wechat\Component;
use Wechat\WechatException;

/**
 *
 * 微信事件推送组件类
 *
 * @package Wechat\Component
 */
class Event extends Component
{

    /**
     *
     * 开发者微信号
     *
     * @return string
     */
    public function getToUserName()
    {
        return $this->get('ToUserName');
    }

    /**
     *
     * 发送方帐号（一个OpenID）
     *
     * @return string
     */
    public function getFromUserName()
    {
        return $this->get('FromUserName');
    }

    /**
     *
     * 消息创建时间 （整型）
     *
     * @return int
     */
    public function getCreateTime()
    {
        return $this->get('CreateTime');
    }

    /**
     * 消息类型，event
     */
    public function getMsgType()
    {
        return $this->get('MsgType');
    }

    /**
     *
     * 事件类型，subscribe(订阅)、unsubscribe(取消订阅)、SCAN(扫码关注)、LOCATION、
     *
     * @return string/null
     */
    public function getEvent()
    {
        return $this->get('Event');
    }

    /**
     *
     * 扫描带参数二维码事件
     * 事件KEY值，qrscene_为前缀，后面为二维码的参数值
     *
     * @return string/null
     */
    public function getEventKey()
    {
        return $this->get('EventKey');
    }

    /**
     *
     * 扫描带参数二维码事件
     * 二维码的ticket，可用来换取二维码图片
     *
     * @return string/null
     */
    public function getTicket()
    {
        return $this->get('Ticket');
    }


    /**
     *
     * 地理位置纬度
     *
     * @return string/null
     */
    public function getLatitude()
    {
        return $this->get('Latitude');
    }

    /**
     *
     * 地理位置经度
     *
     * @return string/null
     */
    public function getLongitude()
    {
        return $this->get('Longitude');
    }

    /**
     *
     * 地理位置精度
     *
     * @return string/null
     */
    public function getPrecision()
    {
        return $this->get('Precision');
    }
}