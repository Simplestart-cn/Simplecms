<?php
namespace Api\Controller;
use Common\Controller\AppframeController;
class GuestbookController extends AppframeController{
	
	protected $guestbook_model;
	
	function _initialize() {
		parent::_initialize();
		$this->guestbook_model=D("Common/Guestbook");
	}
	
	function index(){
		
	}
	
	function addmsg(){
		if(!sp_check_verify_code()){
			$this->error("验证码错误！");
		}
		
		if (IS_POST) {
			if ($this->guestbook_model->create()) {
				$result=$this->guestbook_model->add();				
				
				if ($result!==false) {
					$need_email_message=C("SP_MESSAGE_EMAIL_INFO");
					if($need_email_message){
						//留言发送邮件
	                    $this->send_to_message();
	                    $this->success("提交成功OK稍后后会有工作人员与您联系！");				
		   
	                }else {
	                    $this->success("提交成功");
	                }
				} else {
					$this->error("留言失败！");
				}
			} else {
				$this->error($this->guestbook_model->getError());
			}
		}
		
	}
	
	
	/**
	 * 发送留言邮件处理的函数
	 */
	protected function send_to_message(){
		$option = M('Options')->where(array('option_name'=>'message_email_info'))->find();
		if(!$option){
			$this->error('网站未配置账号激活信息，请联系网站管理员');
		}
		$options = json_decode($option['option_value'], true);
		//邮件标题
		$title = $options['title'];	
				
		$full_name=$_POST['full_name'];
		$message_email=$_POST['email'];
		$message_title=$_POST['title'];
		$msg=$_POST['msg'];
		
		//邮件内容
		$template = $options['template'];
		$content = str_replace(array('#full_name#','#message_email#','#message_title#','#msg#'), array($full_name,$message_email,$message_title,$msg),$template);
	    $receive_email=C("SP_MAIL_TEST_ADDRESS"); 
		$send_result=sp_send_email($receive_email,$title,$content);		
		if($send_result['error']){			
			$this->error("提交失败，请重新提交！");			
		}
		
	}
}