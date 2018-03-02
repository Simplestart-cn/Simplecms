<?php
// +----------------------------------------------------------------------
// | 简易CMS [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.simplestart.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Colber Dun [colber@simplestart.cn]
// +----------------------------------------------------------------------
/**
 * 邮箱配置
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class MailerController extends AdminbaseController {

	//SMTP配置
    public function index() {
    	$this->display();
    }
    
   //SMTP配置处理
    public function index_post() {
    	$_POST = array_map('trim', I('post.'));
    	if(in_array('', $_POST)) $this->error("不能留空！");
    	$configs['SP_MAIL_ADDRESS'] = $_POST['address'];
    	$configs['SP_MAIL_SENDER'] = $_POST['sender'];
    	$configs['SP_MAIL_SMTP'] = $_POST['smtp'];
		$configs['SP_MAIL_SECURE'] = $_POST['smtpsecure'];
    	$configs['SP_MAIL_SMTP_PORT'] = $_POST['smtp_port'];
    	$configs['SP_MAIL_LOGINNAME'] = $_POST['loginname'];
    	$configs['SP_MAIL_PASSWORD'] = $_POST['password'];
		$configs['SP_MAIL_TEST_ADDRESS'] = $_POST['test_address'];
    	if($_POST['test'] && $_POST['test_address']){
			$rst=sp_send_email($_POST['test_address'],'简易CMS测试邮件','简易CMS提醒您邮寄配置正确，可以正常使用');
			if($rst['error']){
				$this->error("测试失败！");
			}else{
				$rst=sp_set_dynamic_config($configs);
				sp_clear_cache();
				if($rst){
					$this->success("测试OK！,保存成功");
				}else{
					$this->error("测试OK！,保存失败");
					}
					
				
				}
		}else{
				$rst=sp_set_dynamic_config($configs);
				sp_clear_cache();
				if ($rst) {
					$this->success("保存成功！");
				} else {
					$this->error("保存失败！");
				}
				
		}
		
		
    }
    
    //member账号激活
    public function active(){
    	$where = array('option_name'=>'member_email_active');
    	$option = M('Options')->where($where)->find();
    	if($option){
    		$options = json_decode($option['option_value'], true);
    		$this->assign('options', $options);
    	}
    	$this->display();
    }
    
    public function active_post(){
    	$configs['SP_MEMBER_EMAIL_ACTIVE'] = intval($_POST['lightup']);
    	sp_set_dynamic_config($configs);

    	$data['option_name'] = "member_email_active";
    	$stripChar = '?<*>\'\"';
    	$_POST['options']['title'] = preg_replace('/['.$stripChar.']/s','',$_POST['options']['title']);
    	$data['option_value']= json_encode($_POST['options']);
    	$posts_model= M('Options');
    	if($posts_model->where("option_name='member_email_active'")->find()){
    		$rst2 = $posts_model->where("option_name='member_email_active'")->save($data);
    	}else{
    		$rst2 = $posts_model->add($data);
    	}
    	
    	if ($rst2!==false) {
    		$this->success("保存成功！");
    	} else {
    		$this->error("保存失败！");
    	}
    }
	
	//message留言邮件
    public function message(){
    	$where = array('option_name'=>'message_email_info');
    	$option = M('Options')->where($where)->find();
    	if($option){
    		$options = json_decode($option['option_value'], true);
    		$this->assign('options', $options);
    	}
    	$this->display();
    }
	public function message_post(){
    	$configs['SP_MESSAGE_EMAIL_INFO'] = intval($_POST['lightup']);
    	sp_set_dynamic_config($configs);

    	$data['option_name'] = "message_email_info";
    	$stripChar = '?<*>\'\"';
    	$_POST['options']['title'] = preg_replace('/['.$stripChar.']/s','',$_POST['options']['title']);
    	$data['option_value']= json_encode($_POST['options']); 
    	$posts_model= M('Options');
    	if($posts_model->where("option_name='message_email_info'")->find()){
    		$rst2 = $posts_model->where("option_name='message_email_info'")->save($data);
    	}else{
    		$rst2 = $posts_model->add($data);
    	}
    	
    	if ($rst2!==false) {
    		$this->success("保存成功！");
    	} else {
    		$this->error("保存失败！");
    	}
    }
}

