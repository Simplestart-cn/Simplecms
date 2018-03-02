<?php
// +----------------------------------------------------------------------
// | 简易CMS [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.simplestart.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Colber Dun [colber@simplestart.cn]
// +----------------------------------------------------------------------
return array (
	'demo_text' => array (// 在后台插件配置表单中的键名 ,会是config[text]
		'title' => '文本:', // 表单的label标题
		'type' => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
		'value' => 'hello,简易CMS!',// 表单的默认值
		'tip' => '这是文本组件的演示' //表单的帮助提示
	),
	'demo_password' => array (// 在后台插件配置表单中的键名 ,会是config[password]
		'title' => '密码:',
		'type' => 'password',
		'value' => '',
		'tip' => '这是密码组件' 
	),
	'demo_select' => array (// 在后台插件配置表单中的键名 ,会是config[select]
		'title' => '下拉列表:',
		'type' => 'select',
		'options' => array (//select 和radio,checkbox的子选项
			'1' => '简易CMS',// 值=>显示
			'2' => '简易CMS',
			'3' => '选择3',
			'4' => '选择4' 
		),
		'value' => '1',
		'tip' => '这是下拉列表组件' 
	),
	'demo_checkbox' => array (
		'title' => '多选框',
		'type' => 'checkbox',
		'options' => array (
			'1' => 'simplestart.cn',
			'2' => 'www.simplestart.cn' 
		),
		'value' => 1,
		'tip' => '这是多选框组件' 
	),
	'demo_radio' => array (
		'title' => '单选框',
		'type' => 'radio',
		'options' => array (
			'1' => '简易CMS',
			'2' => '简易CMS' 
		),
		'value' => '1',
		'tip' => '这是单选框组件' 
	),
	'demo_textarea' => array (
		'title' => '多行文本',
		'type' => 'textarea',
		'value' => '这里是你要填写的内容',
		'tip' => '这是多行文本组件' 
	) 
);
					