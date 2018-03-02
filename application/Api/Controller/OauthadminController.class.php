<?php
// +----------------------------------------------------------------------
// | 简易CMS [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.simplestart.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Colber Dun [colber@simplestart.cn]
// +----------------------------------------------------------------------
/**
 * 参    数：
 * 作    者：lht
 * 功    能：OAth2.0协议下第三方登录数据报表
 * 修改日期：2013-12-13
 */
namespace Api\Controller;
use Common\Controller\AdminbaseController;
class OauthadminController extends AdminbaseController {
	
	//设置
	function setting(){
		$host=sp_get_host();
		$callback_uri_root = $host.__ROOT__.'/index.php?g=api&m=oauth&a=callback&type=';
		$this->assign("callback_uri_root",$callback_uri_root);
		$this->display();
	}
	
	//设置
	function setting_post(){
		if($_POST){

			$host=sp_get_host();
			$call_back = $host.__ROOT__.'/index.php?g=api&m=oauth&a=callback&type=';
			$data = array(
				'SP_SDK_QQ' => array(
						'APP_KEY'    => $_POST['qq_key'],
						'APP_SECRET' => $_POST['qq_sec'],
						'CALLBACK'   => $call_back . 'qq',
				),
				'SP_SDK_SINA' => array(
						'APP_KEY'    => $_POST['sina_key'],
						'APP_SECRET' => $_POST['sina_sec'],
						'CALLBACK'   => $call_back . 'sina',
				),
				'SP_SDK_WECHAT' => array(
						'APP_KEY'    => $_POST['wechat_key'],
						'APP_SECRET' => $_POST['wechat_sec'],
						'CALLBACK'   => $call_back . 'wechat',
				),
				'SP_SDK_WXAPPLET' => array(
						'APP_ID'    => $_POST['wxapplet_id'],
						'APP_SECRET' => $_POST['wxapplet_sec'],
						'CALLBACK'   => $call_back . 'wxapplet',
				),
				'SP_SDK_ALISMS' => array(
						'APP_KEY'    => $_POST['alisms_key'],
						'APP_SECRET' => $_POST['alisms_sec'],
						'SIGN_NAME'  => $_POST['alisms_name']
				)

			);
			
			$result=sp_set_dynamic_config($data);
			
			if($result){
				$this->success("更新成功！");
			}else{
				$this->error("更新失败！");
			}
		}
	}
}