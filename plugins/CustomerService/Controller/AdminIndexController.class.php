<?php
// +----------------------------------------------------------------------
// | 简易CMS [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.simplestart.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Colber Dun [colber@simplestart.cn]
// +----------------------------------------------------------------------
namespace plugins\CustomerService\Controller; 
use Api\Controller\PluginController;

class AdminIndexController extends PluginController{
	protected $plugins_model;	
	function _initialize(){		
		$this->plugins_model = D("Common/Plugins");
	}
	
	function index(){
		
		$where = array('name'=>'CustomerService');
    	$plugin = M('Plugins')->where($where)->find();
    	if($plugin){
    		$plugins = json_decode($plugin['config'], true);
    	    $this->assign('plugins', $plugins);
			$id = json_decode($plugin['id'], true);
			$this->assign('id', $id);
			$this->display(":admin_index");
			
    	}	
		
	}
	
	
	function setting_post(){
		if(IS_POST){
			$id     =   intval(I('post.id'));
			$config =   I('post.config');
			$result = $this->plugins_model->where("id=$id")->setField('config',json_encode($config));
			if($result !== false){
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
		}
	}
	


}
