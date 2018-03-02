<?php
namespace Common\Controller;
use Common\Controller\AppframeController;
use Common\Exception\WxErrorException;
use Common\Lib\Wechat\TPWechat;
class WxframeController extends AppframeController {
    protected $user;
    public function _initialize(){
        parent::_initialize();
        $user = session('user');
        if(empty($user) || !is_array($user)){
            //跳转到登陆页
            $wxObj=TPWechat::getIns();
            if (I('get.code') && I('get.state') == 'callback') {

                try{
                    $res = $wxObj->getOauthAccessToken();
                    $user_info=$wxObj->getOauthUserinfo($res['access_token'],$res['openid']);
                    $user_info['access_token'] = $res['access_token'];
                    $user_info['expires_in'] = $res['expires_in'];
                    $user = $this->checkRegister($user_info);
                }catch(WxErrorException $e){
                    
                }
            }else{
                $callbackUrl = sp_get_current_url();
                $url = $wxObj->getOauthRedirect($callbackUrl, 'callback');
                redirect($url);
            }
        }
        session('user',$user);
        $this->user=$user;
    }

    private function checkRegister($user_info){
        $oauth_user_model = M('OauthUser');
        $find_oauth_user = $oauth_user_model->where(array("openid"=>$user_info['openid']))->find();
        $need_register=true;
        if($find_oauth_user){
            $find_user = M('Users')->where(array("id"=>$find_oauth_user['uid']))->find();
            if($find_user){
                $find_user = array_merge($find_oauth_user,$find_user);
                $need_register=false;
                if($find_user['user_status'] == '0'){
                    $this->error('您可能已经被列入黑名单，请联系管理员！');
                    exit;
                }else{
                    return $find_user;
                }
            }else{
                $need_register=true;
            }
        }
        
        if($need_register){
            //本地用户中创建对应一条数据
            $new_user_data = array(
                    'user_nicename' => $user_info['nickname'],
                    'avatar' => $user_info['headimgurl'],
                    'sex'    => $user_info['sex'],
                    'city'   => $user_info['city'],
                    'country'=> $user_info['country'],
                    'province' => $user_info['province'],
                    'language' => $user_info['language'],
                    'last_login_time' => date("Y-m-d H:i:s"),
                    'last_login_ip' => get_client_ip(0,true),
                    'create_time' => date("Y-m-d H:i:s"),
                    'user_status' => '1',
                    "user_type"   => '2',//会员
            );
            $users_model=M("Users");
            $new_user_id = $users_model->add($new_user_data);
            if($new_user_id){
                //第三方用户表中创建数据
                $new_oauth_user_data = array(
                        'from' => 'weixin',
                        'name' => $user_info['nickname'],
                        'head_img' => $user_info['headimgurl'],
                        'create_time' =>date("Y-m-d H:i:s"),
                        'uid' => $new_user_id,
                        'last_login_time' => date("Y-m-d H:i:s"),
                        'last_login_ip' => get_client_ip(0,true),
                        'login_times' => 1,
                        'status' => 1,
                        'access_token' => $user_info['access_token'],
                        'expires_date' => (int)(time()+$user_info['expires_in']),
                        'openid' => $user_info['openid'],
                );
                $new_oauth_user_id=$oauth_user_model->add($new_oauth_user_data);
                if($new_oauth_user_id){
                    $new_user_data = M('Users')->where(array("id"=>$new_user_id))->find();
                    $new_user_data = array_merge($new_oauth_user_data,$new_user_data);
                    return $new_user_data;
                }else{
                    $users_model->where(array("id"=>$new_user_id))->delete();
                    $this->error('登录失败');
                }
            }else{
                 $this->error('登录失败');;
            }
            
        }
    }

}