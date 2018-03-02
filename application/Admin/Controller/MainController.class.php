<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class MainController extends AdminbaseController {
	
    public function index(){
    	
    	$mysql= M()->query("select VERSION() as version");
    	$mysql=$mysql[0]['version'];
    	$mysql=empty($mysql)?L('UNKNOWN'):$mysql;
    	
    	//server infomaions
    	$info = array(
    			L('OPERATING_SYSTEM') => PHP_OS,
    			L('OPERATING_ENVIRONMENT') => $_SERVER["SERVER_SOFTWARE"],
    			L('PHP_RUN_MODE') => php_sapi_name(),
    			L('MYSQL_VERSION') =>$mysql,
    			L('PROGRAM_VERSION') => SIMPLE_CMS_VERSION . "&nbsp;&nbsp;&nbsp; [<a href='http://www.simplestart.cn' target='_blank'>简易CMS</a>]",
    			L('UPLOAD_MAX_FILESIZE') => ini_get('upload_max_filesize'),
    			L('MAX_EXECUTION_TIME') => ini_get('max_execution_time') . "s",
    			L('DISK_FREE_SPACE') => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
    	);
    	$this->assign('server_info', $info);
		
		
		
		//总文章数
		$posts=M('posts');
		$posts_count=$posts->count();
		$this->assign('posts_count',$posts_count);

		$today=date('Y-m-d 00:00:00');//今天开始日期
		$todata['post_date'] = array('egt',$today);
		
		//今日发表文章数
		$tonews_count=$posts->where($todata)->count();
		$this->assign('tonews_count',$tonews_count);
		$ztday=date("Y-m-d 00:00:00",time())-60*60*24;//昨天开始日期
		$ztdata['post_date'] = array('between',array($ztday,$today));
		//昨日文章数
		$ztnews_count=$posts->where($ztdata)->count();
		$this->assign('ztnews_count',$ztnews_count);
		//今日提升比
		$difday=($ztnews_count>0)?($tonews_count-$ztnews_count)/$ztnews_count*100:0;		
		$this->assign('difday',$difday);
		
		
		
		
		
		//总用户数
		$users=M('users');
		$users_count=$users->count();
		$this->assign('users_count',$users_count);

		$today=date('Y-m-d 00:00:00');//今天开始日期
		$todata['create_time'] = array('egt',$today);
		
		//今日用户章数
		$touser_count=$users->where($todata)->count();
		$this->assign('touser_count',$touser_count);
		$ztday=date("Y-m-d 00:00:00",time())-60*60*24;//昨天开始日期
		$ztdata['create_time'] = array('between',array($ztday,$today));
		//昨日用户数
		$ztuser_count=$users->where($ztdata)->count();
		$this->assign('ztuser_count',$ztuser_count);
		//今日提升比
		$difday=($ztuser_count>0)?($touser_count-$ztuser_count)/$ztuser_count*100:0;		
		$this->assign('difday_u',$difday_u);
		
		
		//总评论数
		$comments=M('comments');
		$comment_count=$comments->count();
		$this->assign('comment_count',$comment_count);

		$today=date('Y-m-d 00:00:00');//今天开始日期
		$todata['createtime'] = array('egt',$today);
		
		//今日评论章数
		$tocomment_count=$comments->where($todata)->count();
		$this->assign('tocomment_count',$tocomment_count);
		$ztday=date("Y-m-d 00:00:00",time())-60*60*24;//昨天开始日期
		$ztdata['createtime'] = array('between',array($ztday,$today));
		//昨日评论数
		$ztcomment_count=$comments->where($ztdata)->count();
		$this->assign('ztcomment_count',$ztcomment_count);
		//今日提升比
		$difday=($ztcomment_count>0)?($tocomment_count-$ztcomment_count)/$ztcomment_count*100:0;		
		$this->assign('difday_c',$difday_c);
		
		
		
		//总页面数
		$pages=M('pages');
		$pages_count=$pages->count();
		$this->assign('pages_count',$pages_count);
		
		
		
		//总留言数
        $sugs_count=M('guestbook')->count();
        $this->assign('sugs_count',$sugs_count);
		//今日留言
        $tosugs_count=M('guestbook')->where(array('createtime'=>array('egt',$today)))->count();
        $this->assign('tosugs_count',$tosugs_count);
		//昨日留言
        $ztsugs_count=M('guestbook')->where(array('createtime'=>array('between',array($ztday,$today))))->count();
        $this->assign('ztsugs_count',$ztsugs_count);
		//今日提升比
        $difday_s=($ztsugs_count>0)?($tosugs_count-$ztsugs_count)/$ztsugs_count*100:0;
        $this->assign('difday_s',$difday_s);
		
		
		
		
		
		
		
		
    	$this->display();
    }
}