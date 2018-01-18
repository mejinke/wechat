<?php
namespace Wechat\Service;

use Wechat\Component\MessageTemplate;
use Wechat\Request;
use Wechat\Wechat;
use Wechat\WechatException;

/**
 *
 * 微信多媒体资料相关操作类
 *
 * @package Wechat\Service
 */
class Media extends Wechat
{

    public function __construct(array $options)
    {
        parent::__construct($options);
    }

    /**
     *
     * 下载多媒体内容
     *
     * @param $media_id 媒体ID
     * @param $path 保存路径
     *
     * @return bool
     */
    public function download($media_id, $path)
    {
        $access_token = $this->getAccessToken();
        $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;

        $result = Request::get($url);
        $header = Request::getResponseHeader();

        $path = $path.'/'.$media_id;

        switch ($header['content_type'])
        {
            //无效
            case 'text/plain':
                $this->exception(json_decode($result, true)['errmsg']);
                return false;
                break;

            //录音
            case 'audio/amr':
                file_put_contents($path.'.amr', $result);
                break;

            //图片
            case 'image/jpeg':
                file_put_contents($path.'.jpg', $result);
                break;
        }

        return true;
    }

    /**
     *
     * 下载Speex高清录音多媒体文件
     *
     * @param $media_id
     * @param $path
     *
     * @return bool
     */
    public function downloadSpeex($media_id, $path)
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get/jssdk?access_token='.$access_token.'&media_id='.$media_id;

        $result = Request::get($url);
        $header = Request::getResponseHeader();

        $path = $path.'/'.$media_id;

        if ($header['content_type'] != 'voice/speex')
        {
            $this->exception(json_decode($result, true)['errmsg']);
            return false;
        }

        file_put_contents($path.'.speex', $result);

        return true;
    }
}