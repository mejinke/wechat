<?php
namespace Wechat;

/**
 *
 * 组件基础类，提供简单的初始化及转换功能
 *
 * @package Wechat
 */
class Component
{
    protected $values;

    public function initFromArray(array $data)
    {
        foreach ($data as $k => $v)
        {
            if (!is_array($v))
            {
                $this->values[$k] = $v;
            }
        }
    }

    public function initFromXml($xmlString)
    {
        if(empty($xmlString))
        {
            throw new WechatException("xml数据异常!");
        }

        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->values;
    }

    /**
     *
     * 检查是否存在某个参数
     *
     * @param $value
     *
     * @return bool
     */
    public function has($value)
    {
        return isset($this->values[$value]) && $this->values[$value] !== '';
    }

    /**
     *
     * 获取某个参数值
     *
     * @param $name
     *
     * @return null
     */
    public function get($name)
    {
        return isset($this->values[$name]) ? $this->values[$name] : null;
    }

    /**
     *
     * 返回数组格式内容
     *
     * @return mixed
     */
    public function toArray()
    {
        return $this->values;
    }


    /**
     *
     * 输出xml字符
     *
     * @throws WechatException
     */
    public function toXml()
    {
        if(!is_array($this->values) || count($this->values) <= 0)
        {
            throw new WechatException("数组数据异常！");
        }

        $xml = '<xml>';
        foreach ($this->values as $key => $val)
        {
            if (is_numeric($val))
            {
                $xml.="<".$key.">".$val."</".$key.">";
            }
            else
            {
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.= '</xml>';
        return $xml;
    }
}
