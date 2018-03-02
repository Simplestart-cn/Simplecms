<?php
// +----------------------------------------------------------------------
// | 简易CMS [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.simplestart.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Colber Dun [colber@simplestart.cn]
// +----------------------------------------------------------------------
namespace Portal\Controller;
use Common\Controller\HomebaseController;
/**
 * 文章列表
*/
class ListController extends HomebaseController {
    
	protected $terms_model;

	function _initialize() {
			parent::_initialize();			
			$this->terms_model = D("Portal/Terms");
			
	}
	//文章内页
	public function index() {
		$term=sp_get_term($_GET['id']);
		if(empty($term)){
		    header('HTTP/1.1 404 Not Found');
		    header('Status:404 Not Found');
		    if(sp_template_file_exists(MODULE_NAME."/404")){
		        $this->display(":404");
		    }
		    return ;
		}
		$term_nav = sp_get_child_terms($_GET['id']);
		$parent_id = $term['term_id'];
		if(empty($term_nav)){
			$parent_id = sp_get_parent_term($_GET['id']);
			$term_nav = sp_get_child_terms($parent_id);
		}
	
		$tplname=$term["list_tpl"];
    	$tplname=sp_get_apphome_tpl($tplname, "list");				
    	$this->assign($term);
    	$this->assign('parent_id',$parent_id);
    	$this->assign('term_nav',$term_nav);
    	$this->assign('cat_id', intval($_GET['id']));
		$this->_lists();
		
    	$this->display(":$tplname");
		
		
		
	}
	
	public function nav_index(){
		$navcatname="文章分类";
		$datas=sp_get_terms("field:term_id,name");
		$navrule=array(
				"action"=>"List/index",
				"param"=>array(
						"id"=>"term_id"
				),
				"label"=>"name");
		exit(sp_get_nav4admin($navcatname,$datas,$navrule));
		
	}
	
	
	private  function _lists($status=1){
		$term_id=0;		
		if(!empty($_REQUEST["term"])){
			$term_id=intval($_REQUEST["term"]);
			$term=$this->terms_model->where(array("term_id"=>$term_id,"taxonomy"=>"article"))->find();
			$this->assign("term",$term);
			$_GET['term']=$term_id;
		}
				
		$users_obj = M("Users");//field(文章对应的userID,用户表的字段)->where("验证用户是否通过审核")
		$users_a=$users_obj->field("id,user_nicename")->where("user_status=1")->select();
		$users_b=$users_obj->field("id,avatar")->where("user_status=1")->select();
		$users_name=array();
		$users_avatar=array();
		foreach ($users_a as $u){
			$user_name[$u['id']]=$u;
		}
    	
    	foreach ($users_b as $u){
			$user_avatar[$u['id']]=$u;
		}
						
		$terms = $this->terms_model->order(array("term_id = $term_id"))->getField("term_id,name",true);
		$this->assign("terms",$terms);
		
		$this->assign("user_name",$user_name);
    	$this->assign("user_avatar",$user_avatar);
				
		
	}
	
	
	
	
	
	
}
