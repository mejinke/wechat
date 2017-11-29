<?php
namespace Wechat;

/**
 *
 * 基础请求类
 *
 * @package Wechat
 */
class Request
{

    /**
     *
     * Get请求
     *
     * @param $url
     * @param int $second
     *
     * @return mixed
     */
    public static function get($url, $second = 30)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     *
     * Post请求
     *
     * @param string $url
     * @param array/string $data
     * @param null $cert 证书，数组；cert 与 key 分别属于两个.pem文件路径
     * @param int $second
     *
     * @return mixed
     * @throws WechatException
     */
    public static function post($url, $data, $cert = null, $second = 30)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, 1);

        //设置证书
        if(is_array($cert))
        {
            //使用证书：cert 与 key 分别属于两个.pem文件
            if (!isset($cert['ssl_cert']) || !isset($cert['ssl_key']) || !is_file($cert['ssl_cert']) || !is_file($cert['ssl_key']))
            {
                throw new WechatException('证书路径配置不正确');
            }
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, $cert['ssl_cert']);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, $cert['ssl_key']);
        }

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}