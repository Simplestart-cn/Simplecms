<?php
// +----------------------------------------------------------------------
// | 简易CMS [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.simplestart.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Colber Dun [colber@simplestart.cn]
// +----------------------------------------------------------------------
namespace plugins\Templet\Controller; 
use Api\Controller\PluginController;

class AdminIndexController extends PluginController{
	
	 function index()
	 
    {
		
        $dir = $_GET['file'];//获取当前页数
        $page_index = true;
        if (!$dir) {

             // 'SP_TMPL_PATH'前台模板文件根目录
           // 'SP_DEFAULT_THEME'	模板文件目录
            $dir = './'.C("SP_TMPL_PATH").C("SP_DEFAULT_THEME"); //查看的根目录
            $page_index = false;
	
        }
		
        //调用处理函数

        $list = scandir($dir);
        $file_dir = '';
        $file_img = '';
        foreach ($list as $file) {
            $location_dir = $dir . '/' . $file;

            if (!(is_dir($location_dir) && '.' != $file && '..' != $file)) {
                list($filesname, $kzm) = explode(".", $file);//获取扩展名
                if ($kzm == "css" or $kzm == "html" or $kzm == "js") { //文件过滤
                    $url = U('execute');
                    $p = '_plugin=Templet&_controller=AdminIndex&_action=delete&address=' . $dir . "/" . $file;
                    if (strpos($url, '?')) {
                        $url = $url . '&' . $p;
                    } else {
                        $url = $url . '?' . $p;
                    }
					
					
					if ($kzm == "css") { //文件过滤
                   		 $file_img = $file_img . "<div class='show_img'>
					<a class='img' target=\"_blank\" href=" . $dir . "/" . $file . " style='background: #F853EB;' download=". $file .">
                    <i class='fa'>CSS</i><span>" . $file . "</span></a>
                    <a class='delete js-ajax-delete' href=" . $url . ">✕</a></div>";
                    } elseif ($kzm == "js") { //文件过滤
                   		 $file_img = $file_img . "<div class='show_img'>
					<a class='img' target=\"_blank\" href=" . $dir . "/" . $file . " style='background: #FF2F3F;' download=". $file .">
                    <i class='fa'>JS</i><span>" . $file . "</span></a>
                    <a class='delete js-ajax-delete' href=" . $url . ">✕</a></div>";
                    }else {
						
						
                   		 $file_img = $file_img . "<div class='show_img'>
					<a class='img' target=\"_blank\" href=" . $dir . "/" . $file . " style='background: #00944D;' download=". $file .">
                    <i class='fa fa-html5'></i><span>" . $file . "</span></a>
                    <a class='delete js-ajax-delete' href=" . $url . ">✕</a></div>";						
                       
                    }
					
                   
                }
				
            } else {

                $url = U('execute');
                $p = '_plugin=Templet&_controller=AdminIndex&_action=index&file=' . $dir . "/" . $file;
                if (strpos($url, '?')) {
                    $url = $url . '&' . $p;
                } else {
                    $url = $url . '?' . $p;
                }
                $file_dir = $file_dir . "<div class='show_img'>
                <a class='img' href=" . $url . ">
                <i class='fa fa-folder-open-o'></i><span>" . $file . "</span></a></div>";
            }
        }
        $this->assign("file", $file_dir . $file_img);
        $this->assign("page_index", $page_index);
        $this->display(":admin_index");

    }

    /**
     *  删除
     */
    function delete()
    {
        $address = $_GET['address'];//获取当前页数
        $result = @unlink($address);
        if ($result == false) {
			$this->error("删除失败！");
            
        } else {
            $this->success("删除成功！");
        }
		
    }

    function loopFun($dir)
    {
        //取出文件或者文件夹
        $list = scandir($dir);

        foreach ($list as $file) {

            $location_dir = $dir . '/' . $file;
            list($filesname, $kzm) = explode(".", $file);//获取扩展名

            if ($kzm == "gif" or $kzm == "jpg" or $kzm == "JPG") { //文件过滤
                if (!is_dir('./' . $file)) { //文件夹过滤
                    echo '<br />';
                    echo $dir . '/' . $file;
                    echo "<a href=" . $dir . "/" . $file . "><img src=" . $dir . "/" . $file . "></a><br>";
                    $array[] = $file;//把符合条件的文件名存入数组
                    $i++;//记录总数

                }

            }
            //判断是否是文件夹 是就调用自身函数再去进行处理
            if (is_dir($location_dir) && '.' != $file && '..' != $file) {
                $this->loopFun($location_dir);
				
            }

        }
		
    }

    function load_image($dir,$menuid){
        $list = scandir($dir);

        foreach ($list as $file) {
            $location_dir = $dir . '/' . $file;
            if (!(is_dir($location_dir) && '.' != $file && '..' != $file)) {
                list($filesname, $kzm) = explode(".", $file);//获取扩展名
                if ($kzm == "gif" or $kzm == "jpg" or $kzm == "JPG") { //文件过滤
                    echo '<br />';
                    echo $dir . '/' . $file;
                    echo "<a href=' . $dir . '/' . $file . '><img src=' . $dir . '/' . $file . '></a><br>";
                }
            } else {
                echo "<a href=" . U('index') . '&file=' . $dir . "/" . $file . ">" . $file . "</a><br>";
            }
        }
	
    }


}
