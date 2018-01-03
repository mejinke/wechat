<?php
namespace Wechat;

/**
 *
 * 主入口类，所有的服务通过该类生成；同时提供基础的票据信息查询能力
 *
 * @package Wechat
 */
class Wechat
{
    /**
     *
     * 配置信息
     *
     * @var array
     */
    protected $options = [];

    /**
     *
     * 当前已注册的服务
     *
     * @var array
     */
    private $services = [];


    public function __construct(array $options)
    {
        if (empty($options['appid']) || empty($options['appsecret']))
        {
            $this->exception('缺少 appid、appsecret配置');
        }

        $this->options = $options;
    }

    /**
     *
     * 注册一个服务
     *
     * @param $name
     *
     * @return mixed
     */
    public function service($name)
    {
        if (isset($this->services[$name]))
        {
            return $this->services[$name];
        }

        $n = '\Wechat\Service\\'.$name;
        $this->services[$name] = new $n($this->options);
        return $this->services[$name];
    }

    /**
     *
     * 读取缓存文件内容
     *
     * @return string
     */
    private function readCacheFile()
    {
        if (empty($this->options['cache_file']))
        {
            $this->exception('缓存文件尚未配置');
        }

        //读取文件
        $data = file_get_contents($this->options['cache_file']);
        if (empty($data))
        {
            return [];
        }

        $r = json_decode($data, true);
        return $r == false ? [] : $r;
    }

    /**
     *
     * 写入缓存信息
     *
     * @param $filename
     * @param $data
     *
     * @return bool
     */
    private function writeCacheFile($filename, $data)
    {
        file_put_contents($filename, json_encode($data), LOCK_EX);
        return true;
    }

    /**
     * 获取公众号的全局唯一接口调用凭据
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140183
     */
    public function getAccessToken()
    {
        $dataJson = $this->readCacheFile();

        if(isset($dataJson['accessToken']) && time() - $dataJson['accessToken']['time'] < $dataJson['accessToken']['expires_in'] - 10)
        {
            return $dataJson['accessToken']['access_token'];
        }

        //重新加载
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->options['appid'].'&secret='.$this->options['appsecret'];
        $data = file_get_contents($url);
        $json = json_decode($data, true);
        $this->writeDebug('刷新accessToken => '.$data);
        if(!isset($json['access_token'])){
            $this->exception('获取access_token失败 '.$json['errmsg']);
        }
        $json['time'] = time();

        //写入内容
        $dataJson['accessToken'] = $json;
        $this->writeCacheFile($this->options['cache_file'], $dataJson);
        //file_put_contents($this->options['cache_file'], json_encode($dataJson));

        return $json['access_token'];
    }

    /**
     *
     * 获取 jsapi_ticket
     * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141115
     *
     * @return mixed
     */
    public function getJsapiTicket()
    {
        $dataJson = $this->readCacheFile();

        if (isset($dataJson['jsapiTicket']) && $dataJson['jsapiTicket']['expire_time'] - 10 > time())
        {
            return $dataJson['jsapiTicket']['jsapi_ticket'];
        }

        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$this->getAccessToken();
        $res = file_get_contents($url);
        $json = json_decode($res, true);
        if (!isset($json['ticket']))
        {
            $this->exception('获取jsapi_ticket失败 '.$json['errmsg']);
        }

        $data = [
            'jsapi_ticket' => $json['ticket'],
            'expire_time' => time() + 7200
        ];
        $dataJson = $this->readCacheFile();
        $dataJson['jsapiTicket'] = $data;
        $this->writeCacheFile($this->options['cache_file'], $dataJson);

        return $data['jsapi_ticket'];
    }

    /**
     *
     * 根据当前设置的参数生成签名
     * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_3
     * @return string
     */
    public function makeSign(array $values)
    {
        //按字典序排序参数
        ksort($values);
        //使用URL键值对的格式
        $string = $this->toUrlParams($values);
        $string = $string . "&key=".$this->options['key'];
        //进行sha1签名
        $signature = md5($string);
        //所有字符转为大写
        return strtoupper($signature);
    }

    /**
     *
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    public function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    /**
     * 格式化参数格式化成url参数
     */
    public function toUrlParams(array $values)
    {
        $buff = "";
        foreach ($values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v))
            {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     *
     * 写入日志信息
     *
     * @param $content
     */
    protected function writeDebug($content)
    {
        if (empty($this->options['log_dir']) == false)
        {
            Log::write($this->options['log_dir'], 'INFO : '.$content);
        }
    }

    /**
     *
     * 抛出异常
     *
     * @param $content
     *
     * @throws WechatException
     */
    protected function exception($content)
    {
        if (empty($this->options['log_dir']) == false)
        {
            Log::write($this->options['log_dir'], 'ERROR : '.$content);
        }

        throw new WechatException($content);
    }

    /**
     *
     * 获取参数
     *
     * @param $name
     *
     * @return mixed|string
     */
    public function __get($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : '';
    }
}