<?php
// +----------------------------------------------------------------------
// | 简易CMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.simplestart.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Colber Dun [colber@simplestart.cn]
// +----------------------------------------------------------------------
if (ini_get('magic_quotes_gpc')) {
	function stripslashesRecursive(array $array){
		foreach ($array as $k => $v) {
			if (is_string($v)){
				$array[$k] = stripslashes($v);
			} else if (is_array($v)){
				$array[$k] = stripslashesRecursive($v);
			}
		}
		return $array;
	}
	$_GET = stripslashesRecursive($_GET);
	$_POST = stripslashesRecursive($_POST);
}
/**
 * 系统调试设置
 * 项目正式部署后请设置为false关闭
 */
define("APP_DEBUG", false);
//网站当前路径
define('SITE_PATH', dirname(__FILE__)."/");
/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
 */
define('APP_PATH', SITE_PATH . 'application/');
/**
 * 第三方框架目录
 * 框架目录，不可更改
 */
define('SPAPP_PATH',   SITE_PATH.'ThinkPHP/');
//
define('SPAPP',   './application/');
/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define("RUNTIME_PATH", SITE_PATH . "data/runtime/");
/**
 * 静态缓存目录
 * 此目录必须可写，建议移动到非WEB目录
 */
define("HTML_PATH", SITE_PATH . "data/runtime/Html/");
//系统版本号
define("SIMPLE_CMS_VERSION", '1.1');

define("SIMPLE_CORE_TAGLIBS", 'cx,Common\Lib\Taglib\TagLibSpadmin,Common\Lib\Taglib\TagLibHome');
//安装判断
if(function_exists('saeAutoLoader') || isset($_SERVER['HTTP_BAE_ENV_APPID'])){
	//sea
}else{
	if(!file_exists("data/install.lock")){
		if(strtolower($_GET['g'])!="install"){
		    header("Location:./index.php?g=install");
		    exit();
		}
	}
}
//uc client root
define("UC_CLIENT_ROOT", './api/uc_client/');

if(file_exists(UC_CLIENT_ROOT."config.inc.php")){
	include UC_CLIENT_ROOT."config.inc.php";
}
/**
 * 引入核心入口
 * ThinkPHP亦可移动到WEB以外的目录
 */
require './ThinkPHP/ThinkPHP.php';

