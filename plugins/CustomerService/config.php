<?php
return array (
	'kftheme' => array (// 在后台插件配置表单中的键名 ,会是config[select]
		'title' => '客服主题',
		'type' => 'radio',
		'options' => array (//select 和radio,checkbox的子选项
			'widget' => 'win8扁平风格',// 值=>显示
			'widget1' => '弹性可爱版',
			'widget2' => '鼠标滑过弹出',
			'widget3' => 'QQ简洁风格',
		),
		'value' => 'widget',
		'tip' => '有好的浮动客服可以推荐给站长，第一时间做出来' 
	),
	'color' => array (
		'title' => '客服颜色',
		'type' => 'radio',
		'options' => array (
			'1' => '红色',
			'2' => '蓝色',
			'3' => '绿色',
			'4' => '橙色',
			'5' => '深空灰',
			'6' => '紫蓝', 
		),
		'value' => '1',
		'tip' => '' 
	),
	'colordiy' => array (
		'title' => 'DIY客服颜色值',
		'type' => 'text',
		'value' => '',
		'tip' => '如果以上颜色满足不了你请在此处填写颜色值，（例如：#ccc），清空填写字段，默认楼上的选择颜色' 
	),
	'position' => array (
		'title' => '客服位置',
		'type' => 'radio',
		'options' => array (
			'1' => '右侧',
			'2' => '左侧' 
		),
		'value' => '1',
		'tip' => '' 
	),
	'top' => array (
		'title' => '客服顶部距离',
		'type' => 'text',
		'value' => '30',
		'tip' => '默认为30' 
	),
	'qq' => array (
		'title' => 'QQ号码',
		'type' => 'text',
		'value' => '215625271',
		'tip' => '' 
	),
	'phone' => array (
		'title' => '电话号码',
		'type' => 'text',
		'value' => '4008888888',
		'tip' => '' 
	), 
	'message' => array (
		'title' => '留言地址',
		'type' => 'text',
		'value' => '/index.php?m=page&a=index&id=3',
		'tip' => '' 
	),
	'qrcode' => array (
		'title' => '二维码',
		'type' => 'text',
		'value' => '/plugins/CustomerService/View//assets/images/headicon_50.png',
		'tip' => '' 
	),
	
);
					