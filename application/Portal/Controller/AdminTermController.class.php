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
class AdminTermController extends AdminbaseController {
	
	protected $terms_model;
	protected $model_model;

	
	
	function _initialize() {
		parent::_initialize();
		$this->terms_model = D("Portal/Terms");
		$this->model_model = D("Portal/Model");
	}
	function index(){
		$result = $this->terms_model->where(array('status'=>1))->order(array("listorder"=>"asc"))->select();
		
		$tree = new \Tree();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		foreach ($result as $r) {
			$r['str_manage'] = '<a href="' . U("AdminTerm/add", array("parent" => $r['term_id'])) . '" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="'.L('ADD_SUB_CATEGORY').'"><i class="fa fa-plus-square-o fa-fw"></i></a> <a href="' . U("AdminTerm/edit", array("id" => $r['term_id'])) . '" data-toggle="tooltip" data-placement="top" title="'.L('EDIT').'" class="btn btn-primary btn-xs"><i class="fa fa-edit fa-fw"></i></a> <a class="btn btn-danger btn-xs js-ajax-delete" href="' . U("AdminTerm/delete", array("id" => $r['term_id'])) . '" data-toggle="tooltip" data-placement="top" title="'.L('DELETE').'"><i class="fa fa-trash-o fa-fw"></i></a> ';
			$url=U('portal/list/index',array('id'=>$r['term_id']));
			$r['url'] = $url;
			$r['taxonomys'] = $r['taxonomy'];
			$r['id']=$r['term_id'];
			$r['parentid']=$r['parent'];
			
			
			if ($r['model_id']==0) {
			$r['model_name']= "未选择";
				} else {
			$r['model_name']=M('Model')->where(array("id" => $r['model_id']))->getField("model_name");
				}
			
			
			$array[] = $r;
		
			
		}
		
		
		$tree->init($array);
		$str = "<tr>
					<td><input name='listorders[\$id]' type='text' size='3' value='\$listorder' class='input input-order' style='height: 22px;'></td>
					<td>\$id</td>
					<td>\$spacer <a href='\$url' target='_blank'>\$name</a></td>
					
					<td>\$model_name</td>
	    			<td>\$taxonomys</td>
					<td><span style='color: #288A5C;font-weight: bold;'>\$list_tpl</span>.html</td> 
					<td><span style='color: #288A5C;font-weight: bold;'>\$one_tpl</span>.html</td>
					<td>\$str_manage</td>
				</tr>";
		$taxonomys = $tree->get_tree(0, $str);
		$this->assign("taxonomys", $taxonomys);
		$this->display();
	}	
	function add(){
	 	$parentid = intval(I("get.parent"));
	 	$tree = new \Tree();
	 	$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
	 	$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
	 	$terms = $this->terms_model->where(array("status" => 1))->order(array("path"=>"asc"))->select();
	 	
	 	$new_terms=array();
	 	foreach ($terms as $r) {
	 		$r['id']=$r['term_id'];
	 		$r['parentid']=$r['parent'];
	 		$r['selected']= (!empty($parentid) && $r['term_id']==$parentid)? "selected":"";
	 		$new_terms[] = $r;
	 	}
	 	$tree->init($new_terms);
	 	$tree_tpl="<option value='\$id' \$selected>\$spacer\$name</option>";
	 	$tree=$tree->get_tree(0,$tree_tpl);
		
	 	$this->assign("terms_tree",$tree);
		
		
		$model=$this->model_model->where(array("model_status" =>1))->order("id asc")->select();;
	
		$this->assign('model', $model);
	 	$this->assign("parent",$parentid);
	 	$this->display();
	}
	
	function add_post(){
		if (IS_POST) {
			if ($this->terms_model->create()) {
				if ($this->terms_model->add()!==false) {
				    F('all_terms',null);
					$this->success("添加成功！",U("AdminTerm/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->terms_model->getError());
			}
		}
	}
	
	function edit(){
		$id = intval(I("get.id"));
		$data=$this->terms_model->where(array("term_id" => $id))->find();
		$tree = new \Tree();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$terms = $this->terms_model->where(array("term_id" => array("NEQ",$id), "path"=>array("notlike","%-$id-%")))->order(array("path"=>"asc"))->select();
		
		$new_terms=array();
		foreach ($terms as $r) {
			$r['id']=$r['term_id'];
			$r['parentid']=$r['parent'];
			$r['selected']=$data['parent']==$r['term_id']?"selected":"";
			
			$new_terms[] = $r;
		}
		
		$tree->init($new_terms);
		$tree_tpl="<option value='\$id' \$selected>\$spacer\$name</option>";
		$tree=$tree->get_tree(0,$tree_tpl);
		
		$model=$this->model_model->where(array("model_status" =>1))->order("id asc")->select();;
	
		$this->assign('model', $model);
		$this->assign("terms_tree",$tree);
		$this->assign("data",$data);
		$this->display();
	}
	
	function edit_post(){
		if (IS_POST) {
			if ($this->terms_model->create()) {
				if ($this->terms_model->save()!==false) {
				    F('all_terms',null);
					$this->success("修改成功！");
				} else {
					$this->error("修改失败！");
				}
			} else {
				$this->error($this->terms_model->getError());
			}
		}
	}
	
	//排序
	public function listorders() {
		$status = parent::_listorders($this->terms_model);
		if ($status) {
			$this->success("排序更新成功！");
		} else {
			$this->error("排序更新失败！");
		}
	}
	
	/**
	 *  删除
	 */
	public function delete() {
		$id = intval(I("get.id"));
		$count = $this->terms_model->where(array("parent" => $id))->count();
		
		if ($count > 0) {
			$this->error("该菜单下还有子类，无法删除！");
		}
		
		if ($this->terms_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
	
	private function _select(){
		$apps=sp_scan_dir(SPAPP."*");
		$model_navs=array();
		foreach ($apps as $a){
			if(is_dir(SPAPP.$a)){
				if(!(strpos($a, ".") === 0)){
					$navfile=SPAPP.$a."/model_nav.php";
					$app=$a;
					if(file_exists($navfile)){
						$navgeturls=include $navfile;
						foreach ($navgeturls as $url){
							$nav= file_get_contents(U("$app/$url",array(),false,true));
							$nav=json_decode($nav,true);
							$model_navs[]=$nav;
						}
					}
					
				}
			}
		}
	}
	
}