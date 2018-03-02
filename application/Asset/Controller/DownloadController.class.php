<?php
namespace Asset\Controller;
use Common\Controller\MemberbaseController;
class DownloadController extends MemberbaseController {
	//文件下载
    function index(){
    	header("Content-type:text/html;charset=utf-8");
    	$unique_id = trim($_GET['key']); //获取唯一码
        $post_id = trim($_GET['post_id']);
        if(!empty($post_id)){
            $term_ids = M('term_relationships')->where(array('object_id'=>$post_id))->getField('term_id',true);
            $allow_download = false;
            foreach ($term_ids as $key => $term_id) {
                $asset_auth = M('terms')->where(array('term_id'=>$term_id))->getField('asset_auth');
                $asset_auth = explode(',',$asset_auth);
                $userid=sp_get_current_userid();
                $user=M('users')->where(array("id"=>$userid))->find();
                if(in_array($user['user_cate'],$asset_auth)){
                  $allow_download = true;  
                }
            }
            if(!$allow_download){
                $this->error('权限不足');
            }
        }
        
    	$asset = M('Asset');
    	$line = $asset->where(array('key'=>$unique_id))->find();

    	$rel_name = $line['filename'];
    	if(!$rel_name){
    		$this->error('未知错误！');
    	}
    	$file = $_SERVER['DOCUMENT_ROOT'].$line['filepath'];
    	//用以解决中文不能显示出来的问题
    	$file=iconv("utf-8","gb2312",$file);
    	//首先要判断给定的文件存在与否
    	if(!file_exists($file)){
    		$this->error("没有该文件文件");
    	}
    	$fp=fopen($file,"r");
    	$file_size=filesize($file);
    	//下载文件需要用到的头
    	Header("Content-type: application/octet-stream");
    	Header("Accept-Ranges: bytes");
    	Header("Accept-Length:".$file_size);
    	Header("Content-Disposition: attachment; filename=".$rel_name);
    	$buffer=1024;
    	$file_count=0;
    	//向浏览器返回数据
    	while(!feof($fp) && $file_count<$file_size){
    		$file_con=fread($fp,$buffer);
    		$file_count+=$buffer;
    		echo $file_con;
    	}
    	//写入数据库
    	$asset->where("key='$unique_id'")->setInc('download_times',1);
    	fclose($fp);
    }

    function byPath(){
        header("Content-type:text/html;charset=utf-8");
        // $unique_id = trim($_GET['path']); //获取唯一码
        // $asset = M('Asset');
        // $line = $asset->where(array('asset_id'=>$unique_id))->find();
        // //print_r($line); die;
        // $rel_name = $line['filename'];
        
        // if(!$rel_name){
        //     $this->error('未知错误！');
        // }
        $path = trim($_GET['path']);
        $arr = explode('/',$path);
        $filename = end($arr); 
        $file = $_SERVER['DOCUMENT_ROOT'].$path;
        //用以解决中文不能显示出来的问题
        $file=iconv("utf-8","gb2312",$file);
        //首先要判断给定的文件存在与否
        if(!file_exists($file)){
            $this->error("没有该文件文件");
        }
        $fp=fopen($file,"r");
        $file_size=filesize($file);
        //下载文件需要用到的头
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length:".$file_size);
        Header("Content-Disposition: attachment; filename=".$filename);
        $buffer=1024;
        $file_count=0;
        //向浏览器返回数据
        while(!feof($fp) && $file_count<$file_size){
            $file_con=fread($fp,$buffer);
            $file_count+=$buffer;
            echo $file_con;
        }
        //写入数据库
        // $asset->where("asset_id='$unique_id'")->setInc('download_times',1);
        fclose($fp);
    }

    function checkAuth($key){

    }
}
