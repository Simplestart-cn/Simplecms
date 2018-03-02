<?php
return array (
	'musictheme' => array (// 在后台插件配置表单中的键名 ,会是config[select]
		'title' => '主题',
		'type' => 'radio',
		'options' => array (//select 和radio,checkbox的子选项
			'widget' => '原型音乐旋转',// 值=>显示
			'widget1' => '待上线版',
			'widget2' => '待上线版',// 值=>显示
			
		),
		'value' => 'widget',
		'tip' => '' 
	),
	'position' => array (
		'title' => '位置',
		'type' => 'radio',
		'options' => array (
			'1' => '右侧',
			'2' => '左侧' 
		),
		'value' => '1',
		'tip' => '' 
	),
	'bottom' => array (
		'title' => '底部距离',
		'type' => 'text',
		'value' => '1',
		'tip' => '默认为1' 
	),
	'music' => array (
		'title' => '音乐',
		'type' => 'text',
		'value' => '/data/upload/music/music.mp3',
		'tip' => '' 
	),
	
);
					