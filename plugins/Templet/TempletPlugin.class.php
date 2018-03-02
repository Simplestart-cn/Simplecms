<?php
// +----------------------------------------------------------------------
// | 简易CMS [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.simplestart.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Colber Dun [colber@simplestart.cn]
// +----------------------------------------------------------------------
namespace plugins\Templet;
use Common\Lib\Plugin;

/**
 * Templet
 */
class TempletPlugin extends Plugin{

        public $info = array(
            'name'=>'Templet',
            'title'=>'模板管理',
            'description'=>'模板管理',
			'ico'=>'html5',
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
        
        

    }