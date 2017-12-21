<?php
namespace Wechat\Component;

use Wechat\Component;

/**
 *
 * 消息模板基础组件
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
 * @package Wechat\Component
 */
class MessageTemplate extends Component
{
    /**
     *
     * 模板ID
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->get('template_id');
    }

    /**
     *
     * 模板ID
     *
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->values['template_id'] = $id;
    }

    /**
     *
     * 接收者openid
     *
     * @return mixed
     */
    public function getTouser()
    {
        return $this->get('touser');
    }

    /**
     *
     * 接收者openid
     *
     * @param mixed $touser
     */
    public function setTouser($touser)
    {
        $this->values['touser'] = $touser;
    }

    /**
     *
     * 模板跳转链接
     *
     * @return mixed
     */
    public function getUrl()
    {
        return $this->get('url');
    }

    /**
     *
     * 模板跳转链接
     *
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->values['url'] = $url;
    }

    /**
     *
     * 模板数据
     *
     * @return array
     */
    public function getData()
    {
        return $this->get('data');
    }

    /**
     *
     * 模板数据
     *
     * @param array $data
     */
    public function setData($data)
    {
        $this->values['data'] = $data;
    }
}