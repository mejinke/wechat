<?php
namespace Wechat\Service;

use Wechat\Component\MessageTemplate;
use Wechat\Request;
use Wechat\Wechat;
use Wechat\WechatException;

/**
 *
 * 微信自定义菜单服务类
 * 自定义菜单能够帮助公众号丰富界面，让用户更好更快地理解公众号的功能
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141013
 *
 * @package Wechat\Service
 */
class Menu extends Wechat
{

    public function __construct(array $options)
    {
        parent::__construct($options);
    }

    /**
     *
     * 创建自定义菜单
     *
     * @param \Wechat\Component\Menu $menu
     *
     * @return bool
     */
    public function create(\Wechat\Component\Menu $menu)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='. $this->getAccessToken();
        return $this->parseResult('menu_create', Request::post($url, $menu->toArray()));
    }

    /**
     *
     * 删除自定义菜单
     *
     * @return bool
     */
    public function delete()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='. $this->getAccessToken();
        return $this->parseResult('menu_delete', Request::get($url));
    }

    /**
     *
     * 解析结果
     *
     * @param $type
     * @param $result
     *
     * @return bool
     */
    protected function parseResult($type, $result)
    {
        $json = json_decode($result, true);

        if ($json == false)
        {
            $this->exception($type. ' 解析结果失败');
        }

        if ($json['errcode'] != 0)
        {
            $this->exception($type.' '.$json['errmsg']);
        }

        return true;
    }
}