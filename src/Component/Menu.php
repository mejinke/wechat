<?php
namespace Wechat\Component;
use Wechat\Component;
use Wechat\WechatException;

/**
 *
 * 自定义菜单组件类
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141013
 * @package Wechat\Component
 */
class Menu extends Component
{

    /**
     *
     * 添加一个Click Button
     *
     * @param $name
     * @param $key
     * @param int $topButtonIndex 一级button索引
     *
     * @return int
     */
    public function addClickButton($name, $key, $topButtonIndex = -1)
    {
        return $this->addButton($name, 'click', $key, $topButtonIndex);
    }


    /**
     *
     * 添加一个View Button
     *
     * @param $name
     * @param $url
     * @param int $topButtonIndex 一级button索引
     *
     * @return int
     */
    public function addViewButton($name, $url, $topButtonIndex = -1)
    {
        return $this->addButton($name, 'view', $url, $topButtonIndex);
    }

    /**
     *
     * 添加Button
     *
     * @param $name 名称
     * @param $type 类型 click 、view
     * @param $other key或URL
     * @param int $topButtonIndex 上级button索引
     *
     * @return int 当前一级button索引
     */
    protected function addButton($name, $type, $other, $topButtonIndex)
    {
        $button = $this->getButton($topButtonIndex);

        $nowButton = [
            'type' => $type,
            'name' => $name
        ];

        switch ($type)
        {
            case 'click':
                $nowButton['key'] = $other;
                break;
            case 'view':
                $nowButton['url'] = $other;
        }

        if ($button == null)
        {
            $nowButton['sub_button'] = [];
            $this->values[] = $nowButton;
        }
        else
        {
            $button['sub_button'][] = $nowButton;
            $this->values[$topButtonIndex] = $button;
        }

        return count($this->values) - 1;
    }

    /**
     *
     * 获取Button
     *
     * @param $index button索引位置
     *
     * @return null
     */
    protected function getButton($index)
    {
        return isset($this->values[$index]) ? $this->values[$index] : null;
    }


    public function toArray()
    {
        return [
            'button' => $this->values
        ];
    }
}