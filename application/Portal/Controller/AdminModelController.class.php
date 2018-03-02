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
class AdminModelController extends AdminbaseController {
	protected $model_model;
	function _initialize() {
		parent::_initialize();
		$this->model_model = D("Portal/Model");
	}
	function index(){
		$model = $this->model_model->where(array("model_status"=>"1"))->order(array("id"=>"asc"))->select();		
		$this->assign("model", $model);
		$this->display();
	}	
	function add(){
		$this->display();
	}		
	function add_post(){
		if (IS_POST) {
			if(empty($_POST['model_name'])){
				$this->error("请填写模型名称！");
			}			
		if(!empty($_POST['config_type']) && !empty($_POST['config_name'])){
				foreach ($_POST['config_type'] as $key=>$value){
					
					$_POST['model_config']['config'][]=array("type"=>$value,"name"=>$_POST['config_name'][$key],"field"=>$_POST['config_field'][$key],"value"=>$_POST['config_value'][$key]);
				}
			}
		    $_POST['post']['model_name']=$_POST['model_name'];
			$_POST['post']['model_date']=date("Y-m-d H:i:s",time());		
			$article=I("post.post");
		    $article['model_config']=json_encode($_POST['model_config']);
			
			$result=$this->model_model->add($article);
			if ($result) {
				
				$this->success("添加成功le！",U("AdminModel/index"));
			} else {
				$this->error("添加失败！");
			}
		// && !empty($_POST['config_field']) && !empty($_POST['config_value'])	 
		}
	}	
				
	public function edit(){
		$id=  intval(I("get.id"));	
		$data=$this->model_model->where(array("id" => $id))->find();
		
		$post=$this->model_model->where("id=$id")->find();
		$this->assign("post",$post);
		$this->assign("model_config",json_decode($post['model_config'],true));
		$this->assign("terms",$terms);
		
		$this->assign("data",$data);
		$this->display();
	}	
		
	function edit_post(){
		
		
		if (IS_POST) {
			if(empty($_POST['model_name'])){
				$this->error("请填写模型名称！");
			}		
		$post_id=intval($_POST['post']['id']);	
			
		if(!empty($_POST['config_type']) && !empty($_POST['config_name'])){
				foreach ($_POST['config_type'] as $key=>$value){
					
					$_POST['model_config']['config'][]=array("type"=>$value,"name"=>$_POST['config_name'][$key],"field"=>$_POST['config_field'][$key],"value"=>$_POST['config_value'][$key]);
				}
			}
		    $_POST['post']['model_name']=$_POST['model_name'];
			$_POST['post']['model_date']=date("Y-m-d H:i:s",time());		
			$article=I("post.post");
		    $article['model_config']=json_encode($_POST['model_config']);
			
			$result=$this->model_model->save($article);
			if ($result!==false) {
				$this->success("保存成功！",U("AdminModel/edit"));
				
			} else {
				$this->error("保存失败！");
			}
		}				
	}
		
	public function delete() {
		$id = intval(I("get.id"));				
		if ($this->model_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}	
	
}