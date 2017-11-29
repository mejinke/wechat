<?php
namespace Wechat;

/**
 *
 * 日志记录
 *
 * @package Wechat
 */
class Log
{
    /**
     *
     * 写入内容
     *
     * @param $filename
     * @param $content
     */
    public static function write($filename, $content)
    {
        $content = "[".date('Y-m-d H:i:s')."]   -   ".$content."\n";
        file_put_contents($filename, $content, FILE_APPEND);
    }
}