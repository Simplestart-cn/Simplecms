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
 * 首页
 */
class IndexController extends HomebaseController {
	
    //首页
	public function index() {
		//产品分类
		$term_list = sp_get_terms("where:taxonomy='product' and parent=0;");
		$this->assign('term_list',$term_list);
		//产品列表
		$product_list = array();
		foreach ($term_list as $key => $value) {
			$cate_list = sp_sql_posts_bycatid($value['term_id'],"field:object_id,term_id,post_title,smeta,post_hits;limit:0,8;order:post_date desc,listorder desc;where:id>0;");
			foreach ($cate_list as $k => $v) {
				$cate_list[$k]['parent_term_id'] = sp_get_parent_term($v['term_id']);
				$cate_list[$k]['smeta'] = json_decode($v['smeta']);
				array_push($product_list,$cate_list[$k]);
			}
		}
		shuffle($product_list);
		$this->assign('product_list',$product_list);


		//文章列表
		$news_list = array();
		$list1 = sp_sql_posts_bycatid('36',"cid:37,38,39;limit:8;field:object_id,term_id,post_title,post_excerpt,post_modified,post_hits,smeta;order:post_date DESC;",array("post_status"=>1));
		$list2 = sp_sql_posts_bycatid('40',"limit:8;field:object_id,term_id,post_title,post_excerpt,post_modified,post_hits,smeta;order:post_date DESC;",array("post_status"=>1));
		$news_list = array_merge($list1,$list2);
		shuffle($news_list);
		foreach ($news_list as $key => $value) {
			$news_list[$key]['post_modified'] = date('Y-m-d',strtotime($value['post_modified']));
			$news_list[$key]['post_excerpt'] = mb_substr($value['post_excerpt'],0,100,'utf-8').'...';
			$news_list[$key]['term_id'] = $value['term_id'] == '40' ? $value['term_id'] : '36';
			$smeta =  json_decode($value['smeta']);
			$news_list[$key]['thumb'] = $smeta->thumbs[0]->url;
		}
		$this->assign('news_list',$news_list);


    	$this->display(":index");
    }

}


