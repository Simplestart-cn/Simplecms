<?php
// +----------------------------------------------------------------------
// | 简易CMS [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.simplestart.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Colber Dun [colber@simplestart.cn]
// +----------------------------------------------------------------------
/**
 * 功    能：结合ThinkSDK完成微信小程序登陆注册
 * 修改日期：2017-09-29
 */

namespace Api\Controller;
use Api\Lib\WXErrorCode;
use Api\Lib\WXBizDataCrypt;

use Common\Controller\HomebaseController;
class AppletController extends HomebaseController {

	//默认配置
    protected $config = [
        'url' => "https://api.weixin.qq.com/sns/jscode2session", //微信获取session_key接口url
        'appid' => '', // APPId
        'secret' => '', // 秘钥
        'grant_type' => 'authorization_code', // grant_type，一般情况下固定的
    ];

    function _initialize() {
        $this->config['appid'] = C('SP_SDK_WXAPPLET.APP_ID');
        $this->config['secret'] = C('SP_SDK_WXAPPLET.APP_SECRET');
    }	
	/**
	 * 微信小程序登录
	 * @param  [type] $user_info [description]
	 * @param  [type] $from      [description]
	 * @param  [type] $token     [description]
	 * @return [type]            [description]
	 */
	
	public function login(){
        
		$from = strtolower(I('from'));
		$code = I('code');
		$encryptedData  = I('encryptedData');
		$rawData = I('rawData');
		$iv = I('iv');
		$signature = I('signature');

		$user_info = $this->checkLogin($code,$rawData,$signature,$encryptedData,$iv);
		if(empty($user_info['openId'])){
			$this->ajaxReturn(array('status'=>0,'message'=>'登录失败,无法获取授权信息'));
		}

		$session_key = $this->str_rand();
		$oauth_user_model = M('OauthUser');
		$find_oauth_user = $oauth_user_model->where(array("from"=>$from,"openid"=>$user_info['openId']))->find();
		$return = array();
		$local_username="";
		$need_register=true;
		if($find_oauth_user){
			$find_user = M('Users')->where(array("id"=>$find_oauth_user['uid']))->find();
			if($find_user){
				$find_user = array_merge($find_oauth_user,$find_user);
				$need_register=false;
				if($find_user['user_status'] == '0'){
					$this->ajaxReturn(array('status'=>0,'message'=>'您可能已经被列入黑名单，请联系管理员！'));
					exit;
				}else{
					session_id($session_key);
					$_SESSION['uid']=$find_user['id'];
					$info = array(
						'last_login_time' => date("Y-m-d H:i:s"),
						'last_login_ip' => get_client_ip(0,true),
						'login_times' => $find_oauth_user['login_times']+1
						);
                    if(time() > (int)$find_oauth_user['expires_date'] || empty($find_oauth_user['access_token'])){
                        //Token过期，更新AccessToken
                        $info['access_token'] = $this->getAccessToken();
                        $info['expires_date'] = time()+(int)$user_info['expires_in'];  //Token有效期为7200s
                    }
					M('oauth_user')->where(array('uid'=>$find_user['id']))->save($info);
					$result['status'] = 1;
					$result['session_key'] = $session_key;
					$result['user_info'] = $find_user;
					$result['message'] = '登录成功';
					$this->ajaxReturn($result);
				}
			}else{
				$need_register=true;
			}
		}
		
		if($need_register){
			//本地用户中创建对应一条数据
			$new_user_data = array(
					'user_nicename' => $user_info['nickName'],
					'avatar' => $user_info['avatarUrl'],
					'sex'    => $user_info['gender'],
					'city'   => $user_info['city'], 
					'country'=> $user_info['country'],
					'province' => $user_info['province'],
					'language' => $user_info['language'],
					'last_login_time' => date("Y-m-d H:i:s"),
					'last_login_ip' => get_client_ip(0,true),
					'create_time' => date("Y-m-d H:i:s"),
					'user_status' => '1',
					"user_type"	  => '2',//会员
			);
			$users_model=M("Users");
			$new_user_id = $users_model->add($new_user_data);
			
			if($new_user_id){
				//第三方用户表中创建数据
                $user_info['access_token'] = $this->getAccessToken();
				$new_oauth_user_data = array(
						'from' => $from,
						'name' => $user_info['nickName'],
						'head_img' => $user_info['avatarUrl'],
						'create_time' =>date("Y-m-d H:i:s"),
						'uid' => $new_user_id,
						'last_login_time' => date("Y-m-d H:i:s"),
						'last_login_ip' => get_client_ip(0,true),
						'login_times' => 1,
						'status' => 1,
						'access_token' => $user_info['access_token'],
					    'expires_date' => time()+(int)$user_info['expires_in'],
						'openid' => $user_info['openId'],
				);
				$new_oauth_user_id=$oauth_user_model->add($new_oauth_user_data);
				if($new_oauth_user_id){
					$new_user_data = M('Users')->where(array("id"=>$new_user_id))->find();
					$new_user_data = array_merge($new_oauth_user_data,$new_user_data);
					session_id($session_key);
					$_SESSION['uid'] = $new_user_id;
					$result['status'] = 1;
					$result['session_key'] = $session_key;
					$result['user_info'] = $new_user_data;
					$result['message'] = '注册成功';
					$this->ajaxReturn($result);
				}else{
					$users_model->where(array("id"=>$new_user_id))->delete();
					$this->ajaxReturn(array('status'=>0,'message'=>'登录失败'));
				}
			}else{
				$this->ajaxReturn(array('status'=>0,'message'=>'登录失败'));
			}
			
		}
	}

	public function checkLogin($code, $rawData, $signature, $encryptedData, $iv) {
        /**
         * 4.server调用微信提供的jsoncode2session接口获取openid, session_key, 调用失败应给予客户端反馈
         * , 微信侧返回错误则可判断为恶意请求, 可以不返回. 微信文档链接
         * 这是一个 HTTP 接口，开发者服务器使用登录凭证 code 获取 session_key 和 openid。其中 session_key 是对用户数据进行加密签名的密钥。
         * 为了自身应用安全，session_key 不应该在网络上传输。
         * 接口地址："https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code"
         */

        $params = [
            'appid' => $this->config['appid'],
            'secret' => $this->config['secret'],
            'js_code' => $code,
            'grant_type' => $this->config['grant_type']
        ];

        $res = $this->makeRequest($this->config['url'], $params);

        if ($res['code'] !== 200 || !isset($res['result']) || !isset($res['result'])) {
            return ['code'=>WXErrorCode::$RequestTokenFailed, 'message'=>'请求Token失败'];
        }
        $reqData = json_decode($res['result'], true);
        if (!isset($reqData['session_key'])) {
            return ['code'=>WXErrorCode::$RequestTokenFailed, 'message'=>'请求Token失败'];
        }
        $sessionKey = $reqData['session_key'];

        /**
         * 5.server计算signature, 并与小程序传入的signature比较, 校验signature的合法性, 不匹配则返回signature不匹配的错误. 不匹配的场景可判断为恶意请求, 可以不返回.
         * 通过调用接口（如 wx.getUserInfo）获取敏感数据时，接口会同时返回 rawData、signature，其中 signature = sha1( rawData + session_key )
         *
         * 将 signature、rawData、以及用户登录态发送给开发者服务器，开发者在数据库中找到该用户对应的 session-key
         * ，使用相同的算法计算出签名 signature2 ，比对 signature 与 signature2 即可校验数据的可信度。
         */

        //签名j校对
        // $signature2 = sha1($rawData . $sessionKey);
        // if ($signature2 !== $signature){
        // 	return ['code'=>WXErrorCode::$SignNotMatch, 'message'=>'签名不匹配'.$signature2.'=='.$signature];
        // } 

        /**
         *
         * 6.使用第4步返回的session_key解密encryptData, 将解得的信息与rawData中信息进行比较, 需要完全匹配,
         * 解得的信息中也包括openid, 也需要与第4步返回的openid匹配. 解密失败或不匹配应该返回客户相应错误.
         * （使用官方提供的方法即可）
         */
        
        $pc = new WXBizDataCrypt($this->config['appid'], $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );

        if ($errCode !== 0) {
            return ['code'=>WXErrorCode::$EncryptDataNotMatch, 'message'=>'解密信息错误'];
        }


        /**
         * 7.生成第三方3rd_session，用于第三方服务器和小程序之间做登录态校验。为了保证安全性，3rd_session应该满足：
         * a.长度足够长。建议有2^128种组合，即长度为16B
         * b.避免使用srand（当前时间）然后rand()的方法，而是采用操作系统提供的真正随机数机制，比如Linux下面读取/dev/urandom设备
         * c.设置一定有效时间，对于过期的3rd_session视为不合法
         *
         * 以 $session3rd 为key，sessionKey+openId为value，写入memcached
         */
        $data = json_decode($data, true);
        $data['expires_in'] = $reqData['expires_in'];

        return $data;
    }


    /**
     * 发起http请求
     * @param string $url 访问路径
     * @param strin $method 请求类型 GET/POST
     * @param array $params 参数，该数组多于1个，表示为POST
     * @param int $expire 请求超时时间
     * @param array $extend 请求伪造包头参数
     * @param string $hostIp HOST的地址
     * @return array    返回的为一个请求状态，一个内容
     */
    private function makeRequest($url, $params = array(), $method='POST', $expire = 0, $extend = array(), $hostIp = ''){
        if (empty($url)) {
            return array('code' => '100');
        }

        $_curl = curl_init();
        $_header = array(
            'Accept-Language: zh-CN',
            'Connection: Keep-Alive',
            'Cache-Control: no-cache'
        );
        // 方便直接访问要设置host的地址
        if (!empty($hostIp)) {
            $urlInfo = parse_url($url);
            if (empty($urlInfo['host'])) {
                $urlInfo['host'] = substr(DOMAIN, 7, -1);
                $url = "http://{$hostIp}{$url}";
            } else {
                $url = str_replace($urlInfo['host'], $hostIp, $url);
            }
            $_header[] = "Host: {$urlInfo['host']}";
        }

        // 只要第二个参数传了值之后，就是POST的
        if (!empty($params)) {
            curl_setopt($_curl, CURLOPT_POSTFIELDS, http_build_query($params));
        }
        if($method == 'POST'){
            curl_setopt($_curl, CURLOPT_POST, true);
        }
        if (substr($url, 0, 8) == 'https://') {
            curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($_curl, CURLOPT_URL, $url);
        curl_setopt($_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($_curl, CURLOPT_USERAGENT, 'API PHP CURL');
        curl_setopt($_curl, CURLOPT_HTTPHEADER, $_header);

        if ($expire > 0) {
            curl_setopt($_curl, CURLOPT_TIMEOUT, $expire); // 处理超时时间
            curl_setopt($_curl, CURLOPT_CONNECTTIMEOUT, $expire); // 建立连接超时时间
        }

        // 额外的配置
        if (!empty($extend)) {
            curl_setopt_array($_curl, $extend);
        }

        $result['result'] = curl_exec($_curl);
        $result['code'] = curl_getinfo($_curl, CURLINFO_HTTP_CODE);
        $result['info'] = curl_getinfo($_curl);
        if ($result['result'] === false) {
            $result['result'] = curl_error($_curl);
            $result['code'] = -curl_errno($_curl);
        }

        curl_close($_curl);
        return $result;
    }

    //获取AccessToken
    private function getAccessToken(){
        $tokenUrl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->config['appid']."&secret=".$this->config['secret'];
        $getArr=array();
        $responds = $this->makeRequest($tokenUrl,$getArr,"GET");
        $result =json_decode($responds['result']);
        return $result->access_token;
    }



    // 生成微信小程序的二维码（三种接口生成方法）：
    //接口A
    public function getwxacode(){
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token='.$access_token;
        $data['path'] = "pages/home/index";
        $data['width'] = 430;
        $data = json_encode($data);
        $return = $this->request_post($url,$data);
        //将生成的小程序码存入相应文件夹下
        $savePath = './data/qrcode/'.date('Ymd').'/';
        if(!is_dir($savePath)){
            $mk= mkdir($savePath,0777);
            if($mk == false){
                return '目录创建失败';
            }
        }
        $filename = $savePath.$this->getMillisecond().'.jpg';
        file_put_contents($filename,$return,FILE_APPEND);
        return trim($filename,'.');
    }


    //接口B：获取无限制带参数小程序码
    public function getwxacodeUnlimit(){
        $access_token = $this->getAccessToken();
        $url='https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$access_token;

        $scene='specialInvite';
        $width=430;
        $auto_color='false'; //默认值是false，自动配置线条颜色，如果颜色依然是黑色，则说明不建议配置主色调；
        $line_color='{"r":"0","g":"0","b":"0"}'; //auth_color 为 false 时生效，使用 rgb 设置颜色 例如 {"r":"xxx","g":"xxx","b":"xxx"}
        $data='{"scene":"'.$scene.'","width":'.$width.',"auto_color":'.$auto_color.',"line_color":'.$line_color.'}';

        $responds = $this->request_post($url,$data);

        //将生成的小程序码存入相应文件夹下
        $savePath = './data/qrcode/'.date('Ymd').'/';
        if(!is_dir($savePath)){
            $mk= mkdir($savePath,0777);
            if($mk == false){
                return '目录创建失败';
            }
        }
        $filename = $savePath.$this->getMillisecond().'.jpg';
        file_put_contents($filename,$responds);
        //返回二维码路径
        return trim($filename,'.');
    }

    //接口C：获取小程序二维码(方形码)
    public function getwxaqrcode(){
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token='.$access_token;
        $data['path'] = "pages/home/index";
        $data['width'] = 430;
        $data = json_encode($data);
        $return = $this->request_post($url,$data);
        //将生成的小程序码存入相应文件夹下
        $savePath = './data/qrcode/'.date('Ymd').'/';
        if(!is_dir($savePath)){
            $mk= mkdir($savePath,0777);
            if($mk == false){
                return '目录创建失败';
            }
        }
        $filename = $savePath.$this->getMillisecond().'.jpg';
        file_put_contents($filename,$return,FILE_APPEND);
        return trim($filename,'.');
    }

    //获取时间戳到毫秒级：
    private function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }

    //发送请求
    private function request_post($url,$data){
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }else{
            return $tmpInfo;
        }
    }


	/*
     * 生成随机字符串
     * @param int $length 生成随机字符串的长度
     * @param string $char 组成随机字符串的字符串
     * @return string $string 生成的随机字符串
     */
    function str_rand($length = 64, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        if(!is_int($length) || $length < 0) {
            return false;
        }
        $string = '';
        for($i = $length; $i > 0; $i--) {
            $string .= $char[mt_rand(0, strlen($char) - 1)];
        }
        return $string;
    }
}