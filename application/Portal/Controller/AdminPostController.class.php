<?php
// +----------------------------------------------------------------------
// | 简易CMS [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.simplestart.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Colber Dun [colber@simplestart.cn]
// +----------------------------------------------------------------------
namespace Portal\Controller;
use Common\Controller\AdminbaseController;
class AdminPostController extends AdminbaseController {
	
	protected $posts_model;
	protected $terms_model;
	protected $term_relationships_model;
	protected $model_model;
	function _initialize() {
		parent::_initialize();
		$this->posts_model = D("Portal/Posts");
		$this->model_model = D("Portal/Model");
		$this->terms_model = D("Portal/Terms");
		$this->term_relationships_model = D("Portal/TermRelationships");
	}
	function index(){
		$this->_lists();
		$this->_getTree();
		$this->_getTree_nav();
		$this->display();
	}
	
	function add(){
		$terms_id=empty($_REQUEST['term'])?0:intval($_REQUEST['term']);
		$model_id=$this->terms_model->where("term_id=$terms_id")->getField('model_id');
	    $data=$this->model_model->where(array("id" => $model_id))->find();
		$post=$this->model_model->where("id=$model_id")->find();
		$this->assign("post",$post);
		$this->assign("model_config",json_decode($post['model_config'],true));
		
		
		$this->_getTree_add();
		$this->display();
	}
	
	
	function add_post(){
		if (IS_POST) {
		
			if(empty($_POST['term'])){
				$this->error("请至少选择一个分类栏目！");
			}
			//缩略图
			if(!empty($_POST['thumbs_alt']) && !empty($_POST['thumbs_url'])){
				foreach ($_POST['thumbs_url'] as $key=>$url){
					$photourl=sp_asset_relative_url($url);
					$_POST['smeta']['thumbs'][]=array("url"=>$photourl,"alt"=>$_POST['thumbs_alt'][$key]);
				}
			}

			//相册
			if(!empty($_POST['photos_alt']) && !empty($_POST['photos_url'])){
				foreach ($_POST['photos_url'] as $key=>$url){
					$photourl=sp_asset_relative_url($url);
					$_POST['smeta']['photos'][]=array("url"=>$photourl,"alt"=>$_POST['photos_alt'][$key]);
				}
			}
			
			
			 //模型提交给产品
			  foreach ($_POST['term'] as $term_id){
					$term_id=$term_id;
				}
			  
		
			
			$model_id=$this->terms_model->where("term_id=$term_id")->getField('model_id');
			$postq=$this->model_model->where("id=$model_id")->find();		
			$model_a=json_decode($postq['model_config'],true);
			
		       foreach ($model_a['config'] as $key=>$value){
				    $type=$value['type'];
					$name=$value['name'];
					$field=$value['field'];
					$value=$value['value'];
					
					$_POST['model'][$field]=$_POST[$field];
				}
				
			$_POST['post']['post_date']=date("Y-m-d H:i:s",time());
			$_POST['post']['post_author']=get_current_admin_id();
			$article=I("post.post");
			$article['smeta']=json_encode($_POST['smeta']);
			$article['model']=json_encode($_POST['model']);
			$article['post_content']=htmlspecialchars_decode($article['post_content']);
			$result=$this->posts_model->add($article);
			
			if ($result) {
				foreach ($_POST['term'] as $mterm_id){
					$this->term_relationships_model->add(array("term_id"=>intval($mterm_id),"object_id"=>$result));					
				}
			
				$this->success("添加成功！");
			} else {
				$this->error("添加失败！");
			}
		}
		
	}
	
	public function edit(){
		$id=  intval(I("get.id"));
		//导出模型规则
		$term_id=$this->term_relationships_model->where("object_id=$id")->getField('term_id');	
		$model_id=$this->terms_model->where("term_id=$term_id")->getField('model_id');
		$post_model=$this->model_model->where("id=$model_id")->find();
		$model_config = json_decode($post_model['model_config'],true);

		$term_relationship = M('TermRelationships')->where(array("object_id"=>$id,"status"=>1))->getField("term_id",true);
		$this->_getTermTree($term_relationship);
		$terms=$this->terms_model->select();
		$post=$this->posts_model->where("id=$id")->find();
		$model_value = json_decode($post['model'],true);

		$this->assign("post",$post);
		$this->assign("smeta",json_decode($post['smeta'],true));
		//模型字段表
		foreach ($model_config['config'] as $key => $value) {
			foreach ($model_value as $k => $v) {
				if($value['field'] == $k){
					$model_config['config'][$key]['value'] = $v;
				}
			}
		}
		
		$this->assign("model_value",$model_value);
		$this->assign("model_config",$model_config);
		$this->assign("terms",$terms);
		$this->assign("term",$term_relationship);

		$this->display();
		
	}
	
	public function edit_post(){
		if (IS_POST) {
			if(empty($_POST['term'])){
				$this->error("请至少选择一个分类栏目！");
			}
			$post_id=intval($_POST['post']['id']);
			
			$this->term_relationships_model->where(array("object_id"=>$post_id,"term_id"=>array("not in",implode(",", $_POST['term']))))->delete();
			foreach ($_POST['term'] as $mterm_id){
				$find_term_relationship=$this->term_relationships_model->where(array("object_id"=>$post_id,"term_id"=>$mterm_id))->count();
				if(empty($find_term_relationship)){
					$this->term_relationships_model->add(array("term_id"=>intval($mterm_id),"object_id"=>$post_id));
				}else{
					$this->term_relationships_model->where(array("object_id"=>$post_id,"term_id"=>$mterm_id))->save(array("status"=>1));
				}
			}
		
			
			//模型提交给产品
			$term_id=$this->term_relationships_model->where("object_id=$post_id")->getField('term_id');	
			$model_id=$this->terms_model->where("term_id=$term_id")->getField('model_id');
			$postq=$this->model_model->where("id=$model_id")->find();		
			$model_a=json_decode($postq['model_config'],true);
			
		       foreach ($model_a['config'] as $key=>$value){
				    $type=$value['type'];
					$name=$value['name'];
					$field=$value['field'];
					$value=$value['value'];
					
					$_POST['model'][$field]=$_POST[$field];
				}

			//缩略图
			if(!empty($_POST['thumbs_alt']) && !empty($_POST['thumbs_url'])){
				foreach ($_POST['thumbs_url'] as $key=>$url){
					$photourl=sp_asset_relative_url($url);
					$_POST['smeta']['thumbs'][]=array("url"=>$photourl,"alt"=>$_POST['thumbs_alt'][$key]);
				}
			}

			//相册
			if(!empty($_POST['photos_alt']) && !empty($_POST['photos_url'])){
				foreach ($_POST['photos_url'] as $key=>$url){
					$photourl=sp_asset_relative_url($url);
					$_POST['smeta']['photos'][]=array("url"=>$photourl,"alt"=>$_POST['photos_alt'][$key]);
				}
			}
				
			unset($_POST['post']['post_author']);
			$article=I("post.post");
			$article['smeta']=json_encode($_POST['smeta']);
			$article['model']=json_encode($_POST['model']);
			$article['post_content']=htmlspecialchars_decode($article['post_content']);
			$result=$this->posts_model->save($article);
			if ($result!==false) {
				$this->success("保存成功！");
				
			} else {
				$this->error("保存失败！");
			}
		}
	}
	
	//排序
	public function listorders() {
		$status = parent::_listorders($this->term_relationships_model);
		if ($status) {
			$this->success("排序更新成功！");
		} else {
			$this->error("排序更新失败！");
		}
	}
	
	private  function _lists($status=1){
		$term_id=0;
		if(!empty($_REQUEST["term"])){
			$term_id=intval($_REQUEST["term"]);
			$term=$this->terms_model->where(array("term_id"=>"$term_id"))->find();
			$this->assign("term",$term);
			$_GET['term']=$term_id;
		}
		
		$where_ands=empty($term_id)?array("status = $status"):array("a.term_id = $term_id and a.status=$status");
		
		$fields=array(
				'start_time'=> array("field"=>"post_date","operator"=>">"),
				'end_time'  => array("field"=>"post_date","operator"=>"<"),
				'status'  => array("field"=>"post_status","operator"=>"0"),				
				'recommended'  => array("field"=>"recommended","operator"=>"1"),
				'istop'  => array("field"=>"istop","operator"=>"1"),						
				'keyword'  => array("field"=>"post_title","operator"=>"like"),
		);
		if(IS_POST){
			
			foreach ($fields as $param =>$val){
				if (isset($_POST[$param]) && !empty($_POST[$param])) {
					$operator=$val['operator'];
					$field   =$val['field'];
					$get=$_POST[$param];
					$_GET[$param]=$get;
					if($operator=="like"){
						$get="%$get%";
					}
					array_push($where_ands, "$field $operator '$get'");
				}
			}
		}else{
			foreach ($fields as $param =>$val){
				if (isset($_GET[$param]) && !empty($_GET[$param])) {
					$operator=$val['operator'];
					$field   =$val['field'];
					$get=$_GET[$param];
					if($operator=="like"){
						$get="%$get%";
					}
					array_push($where_ands, "$field $operator '$get'");
				}
			}
		}
		
		$where= join(" and ", $where_ands);
			
			
		$count=$this->term_relationships_model
		->alias("a")
		->join(C ( 'DB_PREFIX' )."posts b ON a.object_id = b.id")
		->where($where)
		->count();
			
		$page = $this->page($count, 20);
			
			
		$posts=$this->term_relationships_model
		->alias("a")
		->join(C ( 'DB_PREFIX' )."posts b ON a.object_id = b.id") //posts数据库表
		->where($where)
		->limit($page->firstRow . ',' . $page->listRows)
		->order("a.listorder ASC,b.post_modified DESC")->select();
		$users_obj = M("Users");
		$users_data=$users_obj->field("id,user_login")->where("user_status=1")->select();
		$users=array();
		foreach ($users_data as $u){
			$users[$u['id']]=$u;
		}
    	$terms = $this->terms_model->where(array("status"=>1))->order(array("term_id = $term_id"))->getField("term_id,name",true);
		$this->assign("users",$users);
    	$this->assign("terms",$terms);
		$this->assign("Page", $page->show('Admin'));
		$this->assign("current_page",$page->GetCurrentPage());
		unset($_GET[C('VAR_URL_PARAMS')]);
		$this->assign("formget",$_GET);
		$this->assign("posts",$posts);
	}
	
	private function _getTree_nav(){
		$term_id=empty($_REQUEST['term'])?0:intval($_REQUEST['term']);
		$result = $this->terms_model->where(array("status"=>1))->order(array("listorder"=>"asc"))->select();
		
		$tree = new \Tree();
		$tree->icon = array('&nbsp;&nbsp;│ ', '&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;';
		foreach ($result as $r) {
			$r['str_manage'] = '<a href="' . U("AdminTerm/add", array("parent" => $r['term_id'])) . '">添加子类</a> | <a href="' . U("AdminTerm/edit", array("id" => $r['term_id'])) . '">修改</a> | <a class="js-ajax-delete" href="' . U("AdminTerm/delete", array("id" => $r['term_id'])) . '">删除</a> ';
			$r['visit'] = "<a href='#'>访问</a>";
			$r['taxonomys_nav'] = $this->taxonomys[$r['taxonomy']];
			$r['id']=$r['term_id'];
			$r['parentid']=$r['parent'];
			$r['current']=$term_id==$r['term_id']?"current":"";
			$array[] = $r;
		}
					
					
		$tree->init($array);
	
		$str="<li  class='\$current'><a href='".__ROOT__."/index.php?g=&m=AdminPost&a=index&term=\$id'>\$spacer<i class='fa fa-file-text-o fa-fw'></i>\$name</a><a  href='".__ROOT__."/index.php?g=&m=AdminPost&a=add&term=\$id' class='plus-article'><i class='fa fa-plus-square-o'></i></a></li>";
		$taxonomys_nav = $tree->get_tree(0, $str);
		$this->assign("taxonomys_nav", $taxonomys_nav);
	}
	private function _getTree_add(){
		$term_id=empty($_REQUEST['term'])?0:intval($_REQUEST['term']);
		$result = $this->terms_model->order(array("listorder"=>"asc"))->select();
		
		$tree = new \Tree();
		$tree->icon = array('&nbsp;&nbsp;│ ', '&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;';
		foreach ($result as $r) {
			$r['str_manage'] = '<a href="' . U("AdminTerm/add", array("parent" => $r['term_id'])) . '">添加子类</a> | <a href="' . U("AdminTerm/edit", array("id" => $r['term_id'])) . '">修改</a> | <a class="js-ajax-delete" href="' . U("AdminTerm/delete", array("id" => $r['term_id'])) . '">删除</a> ';
			$r['visit'] = "<a href='#'>访问</a>";
			$r['taxonomys_add'] = $this->taxonomys[$r['taxonomy']];
			$r['id']=$r['term_id'];
			$r['parentid']=$r['parent'];
			$r['selected']=$term_id==$r['term_id']?"selected":"";
			$array[] = $r;
		}
		
		$tree->init($array);
		$str="<option value='\$id' \$selected>\$spacer\$name</option>";
		$taxonomys_add = $tree->get_tree(0, $str);
		$this->assign("taxonomys_add", $taxonomys_add);
	}
	private function _getTree(){
		$term_id=empty($_REQUEST['term'])?0:intval($_REQUEST['term']);
		$result = $this->terms_model->where(array("status"=>1))->order(array("listorder"=>"asc"))->select();
		
		$tree = new \Tree();
		$tree->icon = array('&nbsp;&nbsp;│ ', '&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;';
		foreach ($result as $r) {
			$r['str_manage'] = '<a href="' . U("AdminTerm/add", array("parent" => $r['term_id'])) . '">添加子类</a> | <a href="' . U("AdminTerm/edit", array("id" => $r['term_id'])) . '">修改</a> | <a class="js-ajax-delete" href="' . U("AdminTerm/delete", array("id" => $r['term_id'])) . '">删除</a> ';
			$r['visit'] = "<a href='#'>访问</a>";
			$r['taxonomys'] = $this->taxonomys[$r['taxonomy']];
			$r['id']=$r['term_id'];
			$r['parentid']=$r['parent'];
			$r['selected']=$term_id==$r['term_id']?"selected":"";
			$array[] = $r;
		}
		
		$tree->init($array);
		$str="<option value='\$id' \$selected>\$spacer\$name</option>";
		$taxonomys = $tree->get_tree(0, $str);
		$this->assign("taxonomys", $taxonomys);
	}
	private function _getTermTree($term=array()){
		$result = $this->terms_model->order(array("listorder"=>"asc"))->select();
		
		$tree = new \Tree();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		foreach ($result as $r) {
			$r['str_manage'] = '<a href="' . U("AdminTerm/add", array("parent" => $r['term_id'])) . '">添加子类</a> | <a href="' . U("AdminTerm/edit", array("id" => $r['term_id'])) . '">修改</a> | <a class="js-ajax-delete" href="' . U("AdminTerm/delete", array("id" => $r['term_id'])) . '">删除</a> ';
			$r['visit'] = "<a href='#'>访问</a>";
			$r['taxonomys'] = $this->taxonomys[$r['taxonomy']];
			$r['id']=$r['term_id'];
			$r['parentid']=$r['parent'];
			$r['selected']=in_array($r['term_id'], $term)?"selected":"";
			$r['checked'] =in_array($r['term_id'], $term)?"checked":"";
			$array[] = $r;
		}
		
		$tree->init($array);
		$str="<option value='\$id' \$selected>\$spacer\$name</option>";
		$taxonomys = $tree->get_tree(0, $str);
		$this->assign("taxonomys", $taxonomys);
	}
	
	
	
	
	function delete(){
		if(isset($_GET['tid'])){
			$tid = intval(I("get.tid"));
			$data['status']=0;
			if ($this->term_relationships_model->where("tid=$tid")->save($data)) {
				
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
		if(isset($_POST['ids'])){
			$tids=join(",",$_POST['ids']);
			$data['status']=0;
			if ($this->term_relationships_model->where("tid in ($tids)")->save($data)) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}
	
	function check(){
		if(isset($_POST['ids']) && $_GET["check"]){
			$data["post_status"]=1;
			
			$tids=join(",",$_POST['ids']);
			$objectids=$this->term_relationships_model->field("object_id")->where("tid in ($tids)")->select();
			$ids=array();
			foreach ($objectids as $id){
				$ids[]=$id["object_id"];
			}
			$ids=join(",", $ids);
			if ( $this->posts_model->where("id in ($ids)")->save($data)!==false) {
				$this->success("审核成功！");
			} else {
				$this->error("审核失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["uncheck"]){
			
			$data["post_status"]=0;
			$tids=join(",",$_POST['ids']);
			$objectids=$this->term_relationships_model->field("object_id")->where("tid in ($tids)")->select();
			$ids=array();
			foreach ($objectids as $id){
				$ids[]=$id["object_id"];
			}
			$ids=join(",", $ids);
			if ( $this->posts_model->where("id in ($ids)")->save($data)) {
				$this->success("取消审核成功！");
			} else {
				$this->error("取消审核失败！");
			}
		}
	}
	
	function top(){
		if(isset($_POST['ids']) && $_GET["top"]){
			$data["istop"]=1;
				
			$tids=join(",",$_POST['ids']);
			$objectids=$this->term_relationships_model->field("object_id")->where("tid in ($tids)")->select();
			$ids=array();
			foreach ($objectids as $id){
				$ids[]=$id["object_id"];
			}
			$ids=join(",", $ids);
			if ( $this->posts_model->where("id in ($ids)")->save($data)!==false) {
				$this->success("置顶成功！");
			} else {
				$this->error("置顶失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["untop"]){
				
			$data["istop"]=0;
			$tids=join(",",$_POST['ids']);
			$objectids=$this->term_relationships_model->field("object_id")->where("tid in ($tids)")->select();
			$ids=array();
			foreach ($objectids as $id){
				$ids[]=$id["object_id"];
			}
			$ids=join(",", $ids);
			if ( $this->posts_model->where("id in ($ids)")->save($data)) {
				$this->success("取消置顶成功！");
			} else {
				$this->error("取消置顶失败！");
			}
		}
	}
	
	function recommend(){
		if(isset($_POST['ids']) && $_GET["recommend"]){
			$data["recommended"]=1;
	
			$tids=join(",",$_POST['ids']);
			$objectids=$this->term_relationships_model->field("object_id")->where("tid in ($tids)")->select();
			$ids=array();
			foreach ($objectids as $id){
				$ids[]=$id["object_id"];
			}
			$ids=join(",", $ids);
			if ( $this->posts_model->where("id in ($ids)")->save($data)!==false) {
				$this->success("推荐成功！");
			} else {
				$this->error("推荐失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["unrecommend"]){
	
			$data["recommended"]=0;
			$tids=join(",",$_POST['ids']);
			$objectids=$this->term_relationships_model->field("object_id")->where("tid in ($tids)")->select();
			$ids=array();
			foreach ($objectids as $id){
				$ids[]=$id["object_id"];
			}
			$ids=join(",", $ids);
			if ( $this->posts_model->where("id in ($ids)")->save($data)) {
				$this->success("取消推荐成功！");
			} else {
				$this->error("取消推荐失败！");
			}
		}
	}
	
	function move(){
		if(IS_POST){
			if(isset($_GET['ids']) && isset($_POST['term_id'])){
				$tids=$_GET['ids'];
				if ( $this->term_relationships_model->where("tid in ($tids)")->save($_POST) !== false) {
					$this->success("移动成功！");
				} else {
					$this->error("移动失败！");
				}
			}
		}else{
			$parentid = intval(I("get.parent"));
			
			$tree = new \Tree();
			$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
			$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
			$terms = $this->terms_model->order(array("path"=>"asc"))->select();
			$new_terms=array();
			foreach ($terms as $r) {
				$r['id']=$r['term_id'];
				$r['parentid']=$r['parent'];
				$new_terms[] = $r;
			}
			$tree->init($new_terms);
			$tree_tpl="<option value='\$id'>\$spacer\$name</option>";
			$tree=$tree->get_tree(0,$tree_tpl);
			 
			$this->assign("terms_tree",$tree);
			$this->display();
		}
	}
	
	function recyclebin(){
		$this->_lists(0);
		$this->_getTree();
		$this->display();
	}
	
	function clean(){
		if(isset($_POST['ids'])){
			$ids = implode(",", $_POST['ids']);
			$tids= implode(",", array_keys($_POST['ids']));
			$data=array("post_status"=>"0");
			$status=$this->term_relationships_model->where("tid in ($tids)")->delete();
			//删除模型表对应文章的数据
			$this->model_relationships_model->where(array("model_object"=>$ids))->delete();
			
				
			
			
			if($status!==false){
				foreach ($_POST['ids'] as $post_id){
					$post_id=intval($post_id);
					$count=$this->term_relationships_model->where(array("object_id"=>$post_id))->count();
					if(empty($count)){
						$status=$this->posts_model->where(array("id"=>$post_id))->delete();
						
					
					}
				}
				
			}
			
			if ($status!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}else{
			if(isset($_GET['id'])){
				$id = intval(I("get.id"));
				$tid = intval(I("get.tid"));
				$status=$this->term_relationships_model->where("tid = $tid")->delete();
				//删除模型表对应文章的数据
				$this->model_relationships_model->where(array("model_object"=>$id))->delete();
				if($status!==false){
					$count=$this->term_relationships_model->where(array("object_id"=>$id))->count();
					if(empty($count)){
						$status=$this->posts_model->where("id=$id")->delete();
					}
					
				}
				if ($status!==false) {
					$this->success("删除成功！");
				} else {
					$this->error("删除失败！");
				}
			}
		}
	}
	
	function restore(){
		if(isset($_GET['id'])){
			$id = intval(I("get.id"));
			$data=array("tid"=>$id,"status"=>"1");
			if ($this->term_relationships_model->save($data)) {
				$this->success("还原成功！");
			} else {
				$this->error("还原失败！");
			}
		}
	}
	
}