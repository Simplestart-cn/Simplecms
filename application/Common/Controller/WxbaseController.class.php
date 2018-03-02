<?php
namespace Common\Controller;
use Common\Controller\HomebaseController;
class WxbaseController extends WxframeController{
	
	public function __construct() {
		$this->set_action_success_error_tpl();
		parent::__construct();
	}
	
	function _initialize() {
		parent::_initialize();
		$site_options=get_site_options();
		$this->assign($site_options);
		if(sp_is_user_login()){
			$this->assign("user",sp_get_current_user());
		}
		
	}
	
}