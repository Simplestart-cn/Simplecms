<?php

/**
 * 会员中心
 */
namespace User\Controller;
use Common\Controller\HomebaseController;
class CenterController extends HomebaseController {
	
	protected $users_model;
	function _initialize(){
		parent::_initialize();
		$this->users_model=D("Common/Users");
	} 

    //会员中心首页
	public function index() {
		$this->check_login();
        $this->check_user();
		$userid=sp_get_current_userid();
		$user=$this->users_model->where(array("id"=>$userid))->find();
		$user['user_cate'] = M('user_cate')->where(array('id'=>$user['user_cate']))->getField('name');
		$this->assign('user',$user);
    	$this->display(':index');
    }

    /**
     * 登录相关##########
     */
    //退出
    public function logout(){
    	$ucenter_syn=C("UCENTER_ENABLED");
    	$login_success=false;
    	if($ucenter_syn){
    		include UC_CLIENT_ROOT."client.php";
    		echo uc_user_synlogout();
    	}
    	session("user",null);//只有前台用户退出
    	redirect(__ROOT__."/");
    }

    public function login(){
    	if(sp_is_user_login()){ //已经登录时直接跳到首页
	        redirect(__ROOT__."/");
	    }else{
	        $this->display(":login");
	    }
    }

    //登录验证
    function doLogin(){

    	if(!sp_check_verify_code()){
    		$this->error(L("CAPTCHA_NOT_RIGHT"));
    	}
    	
    	$users_model=M("Users");
    	$rules = array(
    			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
    			array('username', 'require', '手机号/邮箱/用户名不能为空！', 1 ),
    			array('password','require','密码不能为空！',1),
    	
    	);
    	if($users_model->validate($rules)->create()===false){
    		$this->error($users_model->getError());
    	}
    	
    	$username=$_POST['username'];
    	
    	if(preg_match('/^\d+$/', $username)){//手机号登录
    	    $this->_do_mobile_login();
    	}else{
    	    $this->_do_email_login(); // 用户名或者邮箱登录
    	}
  
    }
	
    private function _do_mobile_login(){
        $users_model=M('Users');
        $where['mobile']=$_POST['username'];
        $password=$_POST['password'];
        $result = $users_model->where($where)->find();
        if(empty($result)){
            $map['user_login'] = $_POST['username'];
            $result = $users_model->where($map)->find();
        }
        if(!empty($result)){
            if(sp_compare_password($password, $result['user_pass'])){
                $_SESSION["user"]=$result;
                //写入此次登录信息
                $data = array(
                    'last_login_time' => date("Y-m-d H:i:s"),
                    'last_login_ip' => get_client_ip(0,true),
                );
                $users_model->where(array('id'=>$result["id"]))->save($data);
                $redirect=empty($_SESSION['login_referer'])?__ROOT__."/":$_SESSION['login_referer'];
                $_SESSION['login_referer']="";
                $this->success(L('LOGIN_SUCCESS'), $redirect);
            }else{
                $this->error(L("PASSWORD_NOT_RIGHT"));
            }
        }else{
            $this->error(L('USERNAME_NOT_EXIST'));
        }
    }
    
    private function _do_email_login(){

        $username=$_POST['username'];
        $password=$_POST['password'];
        
        if(strpos($username,"@")>0){//邮箱登陆
            $where['user_email']=$username;
        }else{
            $where['user_login']=$username;
        }
        $users_model=M('Users');
        $result = $users_model->where($where)->find();
        if($result['user_type'] == 1){
            $this->error('管理员请前往后台地址登录~');
        }
        $ucenter_syn=C("UCENTER_ENABLED");
        
        $ucenter_old_user_login=false;
         
        $ucenter_login_ok=false;
        if($ucenter_syn){
            setcookie("sp_auth","");
            include UC_CLIENT_ROOT."client.php";
            list($uc_uid, $username, $password, $email)=uc_user_login($username, $password);
             
            if($uc_uid>0){
                if(!$result){
                    $data=array(
                        'user_login' => $username,
                        'user_email' => $email,
                        'user_pass' => sp_password($password),
                        'last_login_ip' => get_client_ip(0,true),
                        'create_time' => time(),
                        'last_login_time' => time(),
                        'user_status' => '1',
                        'user_type'=>2,
                    );
                    $id= $users_model->add($data);
                    $data['id']=$id;
                    $result=$data;
                }
        
            }else{
                 
                switch ($uc_uid){
                    case "-1"://用户不存在，或者被删除
                        if($result){//本应用已经有这个用户
                            if(sp_compare_password($password, $result['user_pass'])){//本应用已经有这个用户,且密码正确，同步用户
                                $uc_uid2=uc_user_register($username, $password, $result['user_email']);
                                if($uc_uid2<0){
                                    $uc_register_errors=array(
                                        "-1"=>"用户名不合法",
                                        "-2"=>"包含不允许注册的词语",
                                        "-3"=>"用户名已经存在",
                                        "-4"=>"Email格式有误",
                                        "-5"=>"Email不允许注册",
                                        "-6"=>"该Email已经被注册",
                                    );
                                    $this->error("同步用户失败--".$uc_register_errors[$uc_uid2]);
                                     
                                     
                                }
                                $uc_uid=$uc_uid2;
                            }else{
                                $this->error("密码错误！");
                            }
                        }
        
                        break;
                    case -2://密码错
                        if($result){//本应用已经有这个用户
                            if(sp_compare_password($password, $result['user_pass'])){//本应用已经有这个用户,且密码正确，同步用户
                                $uc_user_edit_status=uc_user_edit($username,"",$password,"",1);
                                if($uc_user_edit_status<=0){
                                    $this->error("登陆错误！");
                                }
                                list($uc_uid2)=uc_get_user($username);
                                $uc_uid=$uc_uid2;
                                $ucenter_old_user_login=true;
                            }else{
                                $this->error("密码错误！");
                            }
                        }else{
                            $this->error("密码错误！");
                        }
                         
                        break;
                         
                }
            }
            $ucenter_login_ok=true;
            echo uc_user_synlogin($uc_uid);
        }

        if(!empty($result)){
            if(sp_compare_password($password, $result['user_pass'])|| $ucenter_login_ok){
                $_SESSION["user"]=$result;
                //写入此次登录信息
                $data = array(
                    'last_login_time' => date("Y-m-d H:i:s"),
                    'last_login_ip' => get_client_ip(0,true),
                );
                $users_model->where("id=".$result["id"])->save($data);
                $redirect=empty($_SESSION['login_referer'])?__ROOT__."/":$_SESSION['login_referer'];
                $_SESSION['login_referer']="";
                $ucenter_old_user_login_msg="";
        
                if($ucenter_old_user_login){
                    //$ucenter_old_user_login_msg="老用户请在跳转后，再次登陆";
                }
                $redirect = '/page/index/id/8';
                $this->success("登录验证成功！正在跳转...", $redirect);
            }else{
                $this->error("密码错误！");
            }
        }else{
            $this->error("用户名不存在！");
        }
    }


    /**
     * 用户注册
     * @return [type] [description]
     */
    function register(){
	    if(sp_is_user_login()){ //已经登录时直接跳到首页
	        redirect(__ROOT__."/");
	    }else{
	    	$user_cate = M('user_cate')->where(array('status'=>1))->order('listorder asc')->select();
	    	$this->assign('user_cate',$user_cate);
	    	if(isset($_GET["email"])){
	    		$this->display(':register_email');
	    	}else{
	    		$this->display(":register");
	    	}
	    }
	}
	
	function doRegister(){
    	
    	if(isset($_POST['email'])){
    	    
    	    //邮箱注册
    	    $this->_do_email_register();
    	    
    	}elseif(isset($_POST['mobile'])){
    	    
    	    //手机号注册
    	    $this->_do_mobile_register();
    	    
    	}else{
    	    $this->error("注册方式不存在！");
    	}
	}
	
	private function _do_mobile_register(){
	    $password=$_POST['password'];
	    $mobile=$_POST['mobile'];
	    $code = $mobile.$_POST['mobile_verify'];
	    if(!sp_check_mobile_verify($code)){
	            $this->error("手机验证码错误！");
	            return false;
        }
        // if(!sp_check_verify_code()){
        //     $this->error("验证码错误！");
        // }
        $rules = array(
            //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
            array('user_cate', 'require', '请选择所属行业！', 1 ),
            array('mobile', 'require', '手机号不能为空！', 1 ),
            array('password','require','密码不能为空！',1),
            array('repassword', 'require', '重复密码不能为空！', 1 ),
            array('repassword','password','确认密码不正确',0,'confirm'),
        );
        	
	    $users_model=M("Users");
	     
	    if($users_model->validate($rules)->create()===false){
	        $this->error($users_model->getError());
	    }
	    
	    if(strlen($password) < 6 || strlen($password) > 12){
	        $this->error("密码长度至少6位，最多12位！");
	    }
	    $users_model=M("Users");

	    $map['user_login'] = $_POST['username'];
	    $where['mobile']=$mobile;
	    $res = $users_model->where($map)->count();
	    if($res){
	        $this->error("用户名已被注册！");
	        return false;
	    }
	    
	    $result = $users_model->where($where)->count();
	    if($result){
	        $this->error("手机号已被注册！");
	        return false;
	    }

        $data=array(
            'user_login' => $_POST['username'],
            'user_email' => '',
            'mobile' =>$_POST['mobile'],
            'user_nicename' =>$_POST['username'],
            'user_cate' => $_POST['user_cate'],
            'user_pass' => sp_password($password),
            'last_login_ip' => get_client_ip(0,true),
            'create_time' => date("Y-m-d H:i:s"),
            'last_login_time' => date("Y-m-d H:i:s"),
            'user_status' => 2,
            "user_type"=>2,//会员
        );
        $rst = $users_model->add($data);
        if($rst){
            //登入成功页面跳转
            $data['id']=$rst;
            $_SESSION['user']=$data;
            $this->success("注册成功！",__ROOT__."/");
        
        }else{
            $this->error("注册失败！",U("User/center/register"));
        }
	}
	
	private function _do_email_register(){
	   
        if(!sp_check_verify_code()){
            $this->error("验证码错误！");
        }
        
        $rules = array(
            //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
            array('email', 'require', '邮箱不能为空！', 1 ),
            array('user_cate', 'require', '请选择所属行业！', 1 ),
            array('password','require','密码不能为空！',1),
            array('repassword', 'require', '重复密码不能为空！', 1 ),
            array('repassword','password','确认密码不正确',0,'confirm'),
            array('email','email','邮箱格式不正确！',1), // 验证email字段格式是否正确
            	
        );
	    
	     
	    $users_model=M("Users");
	     
	    if($users_model->validate($rules)->create()===false){
	        $this->error($users_model->getError());
	    }
	     
	    $user_cate = $_POST['user_cate'];
	    $password=$_POST['password'];
	    $email=$_POST['email'];
	    $username=str_replace(array(".","@"), "_",$email);
	    // $username=$_POST['username'];
	    //用户名需过滤的字符的正则
	    $stripChar = '?<*.>\'"';
	    if(preg_match('/['.$stripChar.']/is', $username)==1){
	        $this->error('用户名中包含'.$stripChar.'等非法字符！');
	    }
	     
// 	    $banned_usernames=explode(",", sp_get_user_options("banned_usernames"));
	     
// 	    if(in_array($username, $banned_usernames)){
// 	        $this->error("此用户名禁止使用！");
// 	    }
	     
	    if(strlen($password) < 6 || strlen($password) > 12){
	        $this->error("密码长度至少6位，最多12位！");
	    }
	    
	    $where['user_login']=$username;
	    $where['user_email']=$email;
	    $where['_logic'] = 'OR';
	    
	    $ucenter_syn=C("UCENTER_ENABLED");
	    $uc_checkemail=1;
	    $uc_checkusername=1;
	    if($ucenter_syn){
	        include UC_CLIENT_ROOT."client.php";
	        $uc_checkemail=uc_user_checkemail($email);
	        $uc_checkusername=uc_user_checkname($username);
	    }
	     
	    $users_model=M("Users");
	    $result = $users_model->where($where)->count();
	    if($result || $uc_checkemail<0 || $uc_checkusername<0){
	        $this->error("用户名或者该邮箱已经存在！");
	    }else{
	        $uc_register=true;
	        if($ucenter_syn){
	            $uc_uid=uc_user_register($username,$password,$email);
	            //exit($uc_uid);
	            if($uc_uid<0){
	                $uc_register=false;
	            }
	        }
	        if($uc_register){
	            $need_email_active=C("SP_MEMBER_EMAIL_ACTIVE");
	            $data=array(
	                'user_login' => $username,
	                'user_email' => $email,
	                'user_nicename' =>$username,
	                'user_cate' => $user_cate,
	                'user_pass' => sp_password($password),
	                'last_login_ip' => get_client_ip(0,true),
	                'create_time' => date("Y-m-d H:i:s"),
	                'last_login_time' => date("Y-m-d H:i:s"),
	                'user_status' => $need_email_active?2:1,
	                "user_type"=>2,//会员
	            );
	            $rst = $users_model->add($data);
	            if($rst){
	                //登入成功页面跳转
	                $data['id']=$rst;
	                $_SESSION['user']=$data;
	                	
	                //发送激活邮件
	                if($need_email_active){
	                    $this->_send_to_active();
	                    unset($_SESSION['user']);
	                    $this->success("注册成功，激活后才能使用！",U("user/center/index"));
	                }else {
	                    $this->success("注册成功！",__ROOT__."/");
	                }
	                	
	            }else{
	                $this->error("注册失败！",U("user/center/register"));
	            }
	             
	        }else{
	            $this->error("注册失败！",U("user/center/register"));
	        }
	         
	    }
	}

	
    /**
     * 邮箱激活
     * @return [type] [description]
     */
    function active(){
		$this->check_login();
		$this->display(":active");
	}
	
	function doActive(){
		$this->check_login();
		$current_user=session('user');
		if($current_user['user_status']==2){
		    $this->_send_to_active();
		    $this->success('激活邮件发送成功，激活请重新登录！',U("user/index/logout"));
		}else if($current_user['user_status']==1){
		    $this->error('您的账号已经激活，无需再次激活！');
		}else{
		    $this->error('您的账号无法发送激活邮件！');
		}
	}
	
	function forgotPassword(){
		$this->display(':forgot_password');
	}
	

	function password_reset(){
	    $users_model=M("Users");
	    $hash=I("get.hash");
	    $find_user=$users_model->where(array("user_activation_key"=>$hash))->find();
	    if (empty($find_user)){
	        $this->error('重置码无效！',__ROOT__."/");
	    }else{
	        $this->display(":password_reset");
	    }
	}
	
	function do_password_reset(){
		if(isset($_POST['email'])){ 
            $this->do_email_password_reset();
        }elseif(isset($_POST['mobile'])){
            $this->do_mobile_password_reset();
        }else{
            $this->error("验证方式不存在！");
        }
	}

    /**
     * 通过手机号重设密码
     * @return [type] [description]
     */
    private function do_mobile_password_reset(){
        if(IS_POST){
            $password=$_POST['password'];
            $mobile=$_POST['mobile'];
            $code = $mobile.$_POST['mobile_verify'];
            if(!sp_check_mobile_verify($code)){
                    $this->error("手机验证码错误！");
                    return false;
            }
            if(strlen($password) < 6 || strlen($password) > 12){
                $this->error("密码长度至少6位，最多12位！");
            }else{
                $users_model=M("Users");
                $rules = array(
                        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
                        array('mobile', 'require', '手机号不能为空！',1),
                        array('password','require','密码不能为空！',1),
                        array('repassword','password','确认密码不正确',0,'confirm'),
                );
                if($users_model->validate($rules)->create()===false){
                    $this->error($users_model->getError());
                }else{
                    $find_user=$users_model->where(array("mobile"=>$mobile))->find();
                    if($find_user){
                        $res = $users_model->where(array("mobile"=>$mobile))->save(array('user_pass' => sp_password($password)));
                        if($res != false){
                            $this->success("密码已重设，页面正在跳转...",__ROOT__."/");
                        }else{
                            $this->error('密码重设失败！');
                        }
                    }else {
                        $this->error("账号不存在！");
                    }
                    
                }
                
            }
            
        }
    }

    /**
     * 通过邮箱重设密码
     * @return [type] [description]
     */
    private function do_email_password_reset(){
        if(IS_POST){
            if(!sp_check_verify_code()){
				$this->error("验证码错误！");
			}else{
				$users_model=M("Users");
				$rules = array(
						//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
						array('email', 'require', '邮箱不能为空！', 1 ),
						array('email','email','邮箱格式不正确！',1), // 验证email字段格式是否正确
						
				);
				if($users_model->validate($rules)->create()===false){
					$this->error($users_model->getError());
				}else{
					$email=I("post.email");
					$find_user=$users_model->where(array("user_email"=>$email))->find();
					if($find_user){
						$this->_send_to_resetpass($find_user);
						$this->success("密码重置邮件发送成功！",__ROOT__."/");
					}else {
						$this->error("账号不存在！");
					}
				}
			}
        }
    }    

	protected  function _send_to_resetpass($user){
		$options=get_site_options();
		//邮件标题
		$title = $options['site_name']."密码重置";
		$uid=$user['id'];
		$username=$user['user_login'];
	
		$activekey=md5($uid.time().uniqid());
		$users_model=M("Users");
	
		$result=$users_model->where(array("id"=>$uid))->save(array("user_activation_key"=>$activekey));
		if(!$result){
			$this->error('密码重置激活码生成失败！');
		}
		//生成激活链接
		$url = U('user/center/password_reset',array("hash"=>$activekey), "", true);
		//邮件内容
		$template =<<<hello
		#username#，你好！<br>
		请点击或复制下面链接进行密码重置：<br>
		<a href="http://#link#">http://#link#</a>
hello;
		$content = str_replace(array('http://#link#','#username#'), array($url,$username),$template);
	
		$send_result=sp_send_email($user['user_email'], $title, $content);
	
		if($send_result['error']){
			$this->error('密码重置邮件发送失败！');
		}
	}


	/**
	 * 获取手机验证码
	 * @return [type] [description]
	 */
	public function get_mobile_verify(){
        if(IS_POST){
            $mobile = $_POST['mobile'];
            $action = $_POST['action'];
            if(!$mobile){
                $this->ajaxReturn(array('status'=>0,'info'=>'请填写手机号码'));
            }
            switch ($action) {
                case 'register':
                    # 注册验证码
                    $tpl_code = 'SMS_55655095'; //阿里大鱼短信模板ID
                    break;
                case 'resetpass':
                    # 重设密码
                    $map['mobile'] = $mobile;
                    $users_model=M("Users");
				    $result = $users_model->where($map)->count();
				    if(!$result){
				        $this->error("手机号未注册！");
				        return false;
				    }
                    $tpl_code = 'SMS_55655093';
                    break;
                case 'resetinfo':
                    # 变更重要信息
                    $tpl_code = 'SMS_55655092';
                    break;
                default:
                    $tpl_code = 'SMS_55655099';
                    break;
            }
            /**
             * 发送验证码
             * @var $mobile 手机号码
             * @var $tpl_code 阿里大鱼短信模板ID
             */
            $res = sp_send_mobile_verify($mobile,$tpl_code);
            if($res){
                $this->ajaxReturn(array('status'=>1,'info'=>'已发送'));
            }else{
                $this->ajaxReturn(array('status'=>0,'info'=>'获取失败'));
            }
        }else{
            redirect(U('User/Index/index'));
        }
        
    }

}
