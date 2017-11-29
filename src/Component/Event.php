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
        return $this->values['ToUserName'];
    }

    /**
     *
     * 发送方帐号（一个OpenID）
     *
     * @return string
     */
    public function getFromUserName()
    {
        return $this->values['FromUserName'];
    }

    /**
     *
     * 消息创建时间 （整型）
     *
     * @return int
     */
    public function getCreateTime()
    {
        return $this->values['CreateTime'];
    }

    /**
     * 消息类型，event
     */
    public function getMsgType()
    {
        return $this->values['MsgType'];
    }

    /**
     *
     * 事件类型，subscribe(订阅)、unsubscribe(取消订阅)、SCAN(扫码关注)、LOCATION、
     *
     * @var
     */
    public function getEvent()
    {
        return $this->values['Event'];
    }

    /**
     *
     * 扫描带参数二维码事件
     * 事件KEY值，qrscene_为前缀，后面为二维码的参数值
     *
     * @var
     */
    public function getEventKey()
    {
        return $this->values['EventKey'];
    }

    /**
     *
     * 扫描带参数二维码事件
     * 二维码的ticket，可用来换取二维码图片
     *
     * @var
     */
    public function getTicket()
    {
        return $this->values['Ticket'];
    }


    /**
     *
     * 地理位置纬度
     *
     * @var
     */
    public function getLatitude()
    {
        return $this->values['Latitude'];
    }

    /**
     *
     * 地理位置经度
     *
     * @var
     */
    public function getLongitude()
    {
        return $this->values['Longitude'];
    }

    /**
     *
     * 地理位置精度
     *
     * @var
     */
    public function getPrecision()
    {
        return $this->values['Precision'];
    }
}