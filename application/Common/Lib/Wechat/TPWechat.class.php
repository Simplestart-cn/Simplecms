<?php

/**
 *    微信公众平台PHP-SDK, ThinkPHP实例
 * @author dodgepudding@gmail.com
 * @link https://github.com/dodgepudding/wechat-php-sdk
 * @version 1.2
 *  usage:
 *   $options = array(
 *            'token'=>'tokenaccesskey', //填写你设定的key
 *            'encodingaeskey'=>'encodingaeskey', //填写加密用的EncodingAESKey
 *            'appid'=>'wxdk1234567890', //填写高级调用功能的app id
 *            'appsecret'=>'xxxxxxxxxxxxxxxxxxx' //填写高级调用功能的密钥
 *        );
 *     $weObj = new TPWechat($options);
 *   $weObj->valid();
 *   ...
 *
 */
namespace Common\Lib\Wechat;

use Common\Exception\WxErrorException;
use Think\Log;
use Think\Model;

class TPWechat extends WechatPlus
{
    public static $_instance;
    //单例模式实例化对象
    public static function getIns()
    {
        if (FALSE == (self::$_instance instanceof self )) {
            $options = array(
                'token' => C('SP_SDK_WECHAT.APP_TOKEN'), //填写你设定的key
                // 'encodingaeskey' =>C('SP_SDK_WECHAT.APP_AESKEY'), //填写加密用的EncodingAESKey
                'appid' => trim(C('SP_SDK_WECHAT.APP_ID')), //填写高级调用功能的app id
                'appsecret' =>trim(C('SP_SDK_WECHAT.APP_SECRET')), //填写高级调用功能的密钥
                'debug' => C('WX_DEBUG')
            );
            self::$_instance = new self($options);
        }
        return self::$_instance;
    }

    public function getJsSignPackage($url) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $signPackage= $this->getJsSign($url);
        return $signPackage;
    }

    public function sendTextMessage($openid,$content){
        $data = array('touser' => $openid, 'msgtype' => 'text', 'text' => array('content' => $content));
        return $this->sendCustomMessage($data);
    }
    /*{
    "touser":"OPENID",
    "msgtype":"image",
    "image":
    {
    "media_id":"MEDIA_ID"
    }
    }*/
    public function sendImgMessage($openid,$media_id){
        $data = array('touser' => $openid, 'msgtype' => 'image', 'image' => array('media_id' => $media_id));
        return $this->sendCustomMessage($data);
    }
    /**
     * log overwrite
     * @see Wechat::log()
     */
    public function getAccessToken()
    {
        return $this->checkAuth();
    }

    public function log($log)
    {
        if ($this->debug) {
            if (function_exists($this->logcallback)) {
                if (is_array($log)) $log = print_r($log, true);
                return call_user_func($this->logcallback, $log);
            } else {
                Log::write('wechat：' . $log, Log::DEBUG);
                return true;
            }
        }
        return false;
    }

    /**
     * 重载设置缓存
     * @param string $cachename
     * @param mixed $value
     * @param int $expired
     * @return boolean
     */
    protected function setCache($cachename, $value, $expired)
    {
        return S($cachename, $value, $expired);
    }

    /**
     * 重载获取缓存
     * @param string $cachename
     * @return mixed
     */
    protected function getCache($cachename)
    {
        return S($cachename);
    }

    /**
     * 重载清除缓存
     * @param string $cachename
     * @return boolean
     */
    protected function removeCache($cachename)
    {
        return S($cachename, null);
    }

}



