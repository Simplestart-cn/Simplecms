<?php
// +----------------------------------------------------------------------
// | 简易CMS [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.simplestart.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Colber.Dun
// +----------------------------------------------------------------------
/**
 * 文章内页
 */
namespace Portal\Controller;
use Common\Controller\HomebaseController;
class ArticleController extends HomebaseController {

    protected $terms_model;
    protected $term_relationships_model;
    protected $model_model;
    function _initialize() {
        parent::_initialize();
        $this->model_model = D("Portal/Model");
        $this->terms_model = D("Portal/Terms");
        $this->term_relationships_model = D("Portal/TermRelationships");
    }
    
    
    //文章内页
    public function index() {
        $id=intval($_GET['id']);
        $article=sp_sql_post($id,'');
        if(empty($article)){
            header('HTTP/1.1 404 Not Found');
            header('Status:404 Not Found');
            if(sp_template_file_exists(MODULE_NAME."/404")){
                $this->display(":404");
            }
            return ;
        }
        
        
        $termid=$article['term_id'];
        $term_obj= M("Terms");
        $term=$term_obj->where("term_id='$termid'")->find();
        
        $article_id=$article['object_id'];
        
        $posts_model=M("Posts");
        $posts_model->save(array("id"=>$article_id,"post_hits"=>array("exp","post_hits+1")));
        
        $article_date=$article['post_modified'];
        
        $join = "".C('DB_PREFIX').'posts as b on a.object_id =b.id';
        $join2= "".C('DB_PREFIX').'users as c on b.post_author = c.id';
        $rs= M("TermRelationships");
        
        $next=$rs->alias("a")->join($join)->join($join2)->where(array("post_modified"=>array("egt",$article_date), "object_id"=>array('neq',$id), "status"=>1,'term_id'=>$termid))->order("post_modified asc")->find();
        $prev=$rs->alias("a")->join($join)->join($join2)->where(array("post_modified"=>array("elt",$article_date), "object_id"=>array('neq',$id), "status"=>1,'term_id'=>$termid))->order("post_modified desc")->find();
        $similars =  $rs->alias("a")->join($join)->join($join2)->where(array("object_id"=>array('neq',$id), "status"=>1,'term_id'=>$termid))->order("post_hits desc")->limit(3)->select();
        foreach ($similars as $key => $value) {
            $similars[$key]['smeta'] = json_decode($value['smeta'],true);
        }
         
        $this->assign("next",$next);
        $this->assign("prev",$prev);
        $this->assign('similars',$similars);
        
        $smeta=json_decode($article['smeta'],true);
        $model=json_decode($article['model'],true);
        $content_data=sp_content_page($article['post_content']);
        $article['post_content']=$content_data['content'];
        $this->assign("page",$content_data['page']);
        $this->assign($article);
        $this->assign("smeta",$smeta);
        $this->assign("model",$model);
        
        $this->assign("term",$term);
        $this->assign("article_id",$article_id);
        
        $tplname=$term["one_tpl"];
        $tplname=sp_get_apphome_tpl($tplname, "article");
        $this->display(":$tplname");
    }
    
    
    
    
    public function do_like(){
        $this->check_login();
        
        $id=intval($_GET['id']);//posts表中id
        
        $posts_model=M("Posts");
        
        $can_like=sp_check_user_action("posts$id",1);
        
        if($can_like){
            $posts_model->save(array("id"=>$id,"post_like"=>array("exp","post_like+1")));
            $this->success("赞好啦！");
        }else{
            $this->error("您已赞过啦！");
        }
        
    }
}
