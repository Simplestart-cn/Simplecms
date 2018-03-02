<?php
namespace Common\Lib\Wechat;
class WechatMessage{
	public function send($openid, $message){
		$access_token = $this->getAccessToken($openid);
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
		$ret_json = $this->curl_grab_page($url, $message);
		$ret = json_decode($ret_json);
		return $ret;
	}

	function getAccessToken($openid) {
		$userInfo = M('oauth_user')->where(array('openid'=>$openid))->find();
		$appid = C('WX_APPID');
		$appsecret = C('WX_APPSECRET');
		$accesstoken = $userInfo['access_token'];
		$dateline = $userInfo['expires_date'];
		$time = time();
		var_dump($appid);
		var_dump($appsecret);
		if(($time - $dateline) >= 100) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
			$ret_json = $this->curl_get_contents($url);
			$ret = json_decode($ret_json);
			if($ret->access_token){
				M('oauth_user')->where(array('openid'=>$openid))->save(array('access_token'=>$ret->access_token, 'expires_date'=>$time));
				return $ret->access_token;
			}
		}elseif(empty($accesstoken)) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
			$ret_json = $this->curl_get_contents($url);
			$ret = json_decode($ret_json);
			if($ret->access_token){
				M('oauth_user')->where(array('openid'=>$openid))->save(array('access_token'=>$ret->access_token, 'expires_date'=>$time));
				return $ret->access_token;
			}
		}
		else 
		{
			return $accesstoken;
		}
	}

	function curl_get_contents($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$r = curl_exec($ch);
		curl_close($ch);
		return $r;
	}

	function curl_grab_page($url,$data,$proxy='',$proxystatus='',$ref_url='') 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if ($proxystatus == 'true') 
		{
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
		}
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		if(!empty($ref_url))
		{
			curl_setopt($ch, CURLOPT_HEADER, TRUE);
			curl_setopt($ch, CURLOPT_REFERER, $ref_url);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		ob_start();
		return curl_exec ($ch);
		ob_end_clean();
		curl_close ($ch);
		unset($ch);
	}
}