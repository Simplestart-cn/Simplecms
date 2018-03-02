<?php

/**
 * 附件上传
 */
namespace Asset\Controller;
use Common\Controller\AdminbaseController;
class UploadController extends AdminbaseController {

    private $uid = null;

    function _initialize() {
    	$adminid=sp_get_current_admin_id();
    	$userid=sp_get_current_userid();
        if(!empty($adminid)){
            $this->uid = $adminid;
        }else if(!empty($userid)){
            $this->uid = $userid;
        }else if(empty($adminid) && empty($userid)){
    		exit("非法上传！");
    	}
    }

    /**
     * swfupload 上传 
     */
    public function swfupload() {
        if (IS_POST) {
			$savepath='article/';
            $subname=date('Ymd').'/';
		
            //上传处理类
            $config=array(
            		'rootPath'   => './'.C("UPLOADPATH"), //文件根目录
            		'savePath'   => $savepath,  //文件目录
					'autoSub'    =>    true, //子目录开关
					'subName'    => $subname,//子目录
					'saveName'   =>    array('uniqid',''), //文件命名规则
            		'maxSize'    => 11048576,         		
            		'exts'       =>    array('jpg', 'gif', 'png', 'jpeg','txt','zip','mp3'),
            		
            );
			$upload = new \Think\Upload($config);// 
			$info=$upload->upload();
            //开始上传
            if ($info) {
                //上传成功
                //写入附件数据库信息
                $file=array_shift($info);
                if(!empty($file['url'])){
                	$url=$file['url'];
                }else{
                	$url=C("TMPL_PARSE_STRING.__UPLOAD__").$savepath.$subname.$file['savename'];
                }
                
				echo "1," . $url.",".'1,'.$file['name'];
				exit;
            } else {
                //上传失败，返回错误
                exit("0," . $upload->getError());
            }
        } else {
            $this->display(':swfupload');
        }
    }


    /**
     * 图片上传 
     */
    public function uploadImage() {
        if (IS_POST) {
            $savepath='images/';
            $subname=date('Ymd');
            //上传处理类
            $config=array(
                    'rootPath'   => './'.C("UPLOADPATH"), //文件根目录
                    'savePath'   => $savepath,  //文件目录
                    'autoSub'    =>    true, //子目录开关
                    'subName'    => $subname,//子目录
                    'saveName'   =>    array('uniqid',''), //文件命名规则
                    'maxSize'    => 11048576,               
                    'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
                    
            );
            $upload = new \Think\Upload($config);// 
            $info=$upload->upload();
            //开始上传
            if ($info){
                //上传成功
                //写入附件数据库信息
                $file=array_shift($info);
                $data['uid'] = $this->uid;
                $data['name'] = $_POST['name'] ? $_POST['name'] :$file['name'];
                $data['key'] = strtoupper(substr($this->uid.$file['md5'],0,16));
                $data['file_name'] = $file['savename'];
                $data['file_size'] = $file['size'];
                $data['file_path'] = C("UPLOADPATH").$file['savepath'];
                $data['file_md5'] = $file['md5'];
                $data['file_sha1'] = $file['sha1'];
                $data['add_time'] = time();
                $data['file_suffix'] = $file['ext'];
                $data['file_type'] = $file['type'];
                $data['download_enable'] = 0;
                $data['download_times'] = 0;
                $res = M('assets')->add($data);
                if($res !== false){
                    $this->ajaxReturn(array('status'=>1,'path'=>$data['file_path'].$data['file_name'],'aid'=>$res));
                }else{
                    unlink($data['file_path'].$data['file_name']);
                    $this->ajaxReturn(array('status'=>0,'message'=>'文件信息保存失败'));
                }    
                exit;
            } else {
                //上传失败，返回错误
                $this->ajaxReturn(array('status'=>0,'message'=>$upload->getError()));
                exit;
            }
        }
    }

    /**
     * 文件上传 
     */
    public function uploadFile() {
        if (IS_POST) {
            $savepath='file/';
            $subname=date('Ymd');
            //上传处理类
            $config=array(
                    'rootPath'   => './'.C("UPLOADPATH"), //文件根目录
                    'savePath'   => $savepath,  //文件目录
                    'autoSub'    =>    true, //子目录开关
                    'subName'    => $subname,//子目录
                    'saveName'   =>    array('uniqid',''), //文件命名规则
                    'maxSize'    => 11048576,               
                    'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
                    
            );
            $upload = new \Think\Upload($config);// 
            $info=$upload->upload();
            //开始上传
            if ($info) {
                //上传成功
                //写入附件数据库信息
                $file=array_shift($info);
                $data['uid'] = $this->uid;
                $data['name'] = $_POST['name'] ? $_POST['name'] :$file['name'];
                $data['key'] = strtoupper(substr($this->uid.$file['md5'],0,16));
                $data['file_name'] = $file['savename'];
                $data['file_size'] = $file['size'];
                $data['file_path'] = C("UPLOADPATH").$file['savepath'];
                $data['file_md5'] = $file['md5'];
                $data['file_sha1'] = $file['sha1'];
                $data['add_time'] = time();
                $data['file_suffix'] = $file['ext'];
                $data['file_type'] = $file['type'];
                $data['download_enable'] = 0;
                $data['download_times'] = 0;
                $res = M('assets')->add($data);
                if($res !== false){
                    $this->ajaxReturn(array('status'=>1,'path'=>$data['file_path'].$data['file_name'],'aid'=>$res));
                }else{
                    unlink($data['file_path'].$data['file_name']);
                    $this->ajaxReturn(array('status'=>0,'message'=>'文件信息保存失败'));

                }    
                exit;
            } else {
                //上传失败，返回错误
                $this->ajaxReturn(array('status'=>0,'message'=>$upload->getError()));
                exit;
            }


        }
    }


}
