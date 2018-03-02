<?php
// +----------------------------------------------------------------------
// | 简易CMS [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.simplestart.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Colber Dun [colber@simplestart.cn]
// +----------------------------------------------------------------------
namespace plugins\BackgroundMusic;
use Common\Lib\Plugin;

/**
 * BackgroundMusic
 */
class BackgroundMusicPlugin extends Plugin{

        public $info = array(
            'name'=>'BackgroundMusic',
            'title'=>'背景音乐',
            'description'=>'网站页面背景音乐',
			'ico'=>'music',
            'status'=>1,
            'author'=>'简易CMS',
            'version'=>'1.0'
        );
        
        public $has_admin=1;//插件是否有后台管理界面

        public function install(){//安装方法必须实现
            return true;//安装成功返回true，失败false
        }

        public function uninstall(){//卸载方法必须实现
            return true;//卸载成功返回true，失败false
        }
        
        //实现的footer钩子方法
        public function footer($param){
        	$config=$this->getConfig();
        	$this->assign($config);
			
			
			$where = array('name'=>'BackgroundMusic');
    	$plugin = M('Plugins')->where($where)->find();
    	if($plugin){
    		$plugins = json_decode($plugin['config'], true);
    	  
    	}
		 
        	$this->display($plugins['musictheme']);
			
			
        }
		

    }