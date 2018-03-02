<?php
return array (
  'app' => 'Admin',
  'model' => 'Setting',
  'action' => 'default',
  'data' => '',
  'type' => '0',
  'status' => '1',
  'name' => '系统设置',
  'icon' => 'cogs',
  'remark' => '',
  'listorder' => '5',
  'children' => 
  array (
    array (
      'app' => 'Admin',
      'model' => 'Setting',
      'action' => 'site',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '网站信息',
      'icon' => 'tv',
      'remark' => '',
      'listorder' => '0',
      'children' => 
      array (
        array (
          'app' => 'Admin',
          'model' => 'Setting',
          'action' => 'site_post',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '提交修改',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
      ),
    ),
    array (
      'app' => 'Admin',
      'model' => 'Setting',
      'action' => 'userdefault',
      'data' => '',
      'type' => '0',
      'status' => '1',
      'name' => '个人信息',
      'icon' => 'user',
      'remark' => '',
      'listorder' => '1',
      'children' => 
      array (
        array (
          'app' => 'Admin',
          'model' => 'User',
          'action' => 'userinfo',
          'data' => '',
          'type' => '1',
          'status' => '1',
          'name' => '修改信息',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'User',
              'action' => 'userinfo_post',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '修改信息提交',
              'icon' => '',
              'remark' => '',
              'listorder' => '0',
            ),
          ),
        ),
        array (
          'app' => 'Admin',
          'model' => 'Setting',
          'action' => 'password',
          'data' => '',
          'type' => '1',
          'status' => '1',
          'name' => '修改密码',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Setting',
              'action' => 'password_post',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '提交修改',
              'icon' => '',
              'remark' => '',
              'listorder' => '0',
            ),
          ),
        ),
      ),
    ),
    array (
      'app' => 'Admin',
      'model' => 'Mailer',
      'action' => 'default',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '邮箱配置',
      'icon' => 'envelope-o',
      'remark' => '',
      'listorder' => '2',
      'children' => 
      array (
        array (
          'app' => 'Admin',
          'model' => 'Mailer',
          'action' => 'index',
          'data' => '',
          'type' => '1',
          'status' => '1',
          'name' => 'SMTP配置',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Mailer',
              'action' => 'index_post',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '提交配置',
              'icon' => '',
              'remark' => '',
              'listorder' => '0',
            ),
          ),
        ),
        array (
          'app' => 'Admin',
          'model' => 'Mailer',
          'action' => 'active',
          'data' => '',
          'type' => '1',
          'status' => '1',
          'name' => '邮件模板',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Mailer',
              'action' => 'active_post',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '提交模板',
              'icon' => '',
              'remark' => '',
              'listorder' => '0',
            ),
          ),
        ),
      ),
    ),
    array (
      'app' => 'Admin',
      'model' => 'Backup',
      'action' => 'default',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '备份管理',
      'icon' => 'database',
      'remark' => '',
      'listorder' => '3',
      'children' => 
      array (
        array (
          'app' => 'Admin',
          'model' => 'Backup',
          'action' => 'restore',
          'data' => '',
          'type' => '1',
          'status' => '1',
          'name' => '数据还原',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Backup',
          'action' => 'index',
          'data' => '',
          'type' => '1',
          'status' => '1',
          'name' => '数据备份',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Backup',
              'action' => 'index_post',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '提交数据备份',
              'icon' => '',
              'remark' => '',
              'listorder' => '0',
            ),
          ),
        ),
        array (
          'app' => 'Admin',
          'model' => 'Backup',
          'action' => 'download',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '下载备份',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Backup',
          'action' => 'del_backup',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '删除备份',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Backup',
          'action' => 'import',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '数据备份导入',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
        ),
      ),
    ),
    array (
      'app' => 'Admin',
      'model' => 'Menu',
      'action' => 'index',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '后台菜单',
      'icon' => '',
      'remark' => '',
      'listorder' => '4',
      'children' => 
      array (
        array (
          'app' => 'Admin',
          'model' => 'Menu',
          'action' => 'add',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '添加菜单',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Menu',
              'action' => 'add_post',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '提交添加',
              'icon' => '',
              'remark' => '',
              'listorder' => '0',
            ),
          ),
        ),
        array (
          'app' => 'Admin',
          'model' => 'Menu',
          'action' => 'listorders',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '后台菜单排序',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Menu',
          'action' => 'export_menu',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '菜单备份',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Menu',
          'action' => 'edit',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '编辑菜单',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Menu',
              'action' => 'edit_post',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '提交编辑',
              'icon' => '',
              'remark' => '',
              'listorder' => '0',
            ),
          ),
        ),
        array (
          'app' => 'Admin',
          'model' => 'Menu',
          'action' => 'delete',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '删除菜单',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Menu',
          'action' => 'lists',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '所有菜单',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
        ),
      ),
    ),
    array (
      'app' => 'Admin',
      'model' => 'Route',
      'action' => 'index',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => 'URL美化',
      'icon' => 'magic',
      'remark' => '',
      'listorder' => '5',
      'children' => 
      array (
        array (
          'app' => 'Admin',
          'model' => 'Route',
          'action' => 'add',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '路由添加',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Route',
              'action' => 'add_post',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '路由添加提交',
              'icon' => '',
              'remark' => '',
              'listorder' => '0',
            ),
          ),
        ),
        array (
          'app' => 'Admin',
          'model' => 'Route',
          'action' => 'edit',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '路由编辑',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Route',
              'action' => 'edit_post',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '路由编辑提交',
              'icon' => '',
              'remark' => '',
              'listorder' => '0',
            ),
          ),
        ),
        array (
          'app' => 'Admin',
          'model' => 'Route',
          'action' => 'delete',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '路由删除',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Route',
          'action' => 'ban',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '路由禁止',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Route',
          'action' => 'open',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '路由启用',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Route',
          'action' => 'listorders',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '路由排序',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
      ),
    ),
    array (
      'app' => 'Admin',
      'model' => 'Storage',
      'action' => 'index',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '文件存储',
      'icon' => 'cloud',
      'remark' => '',
      'listorder' => '6',
      'children' => 
      array (
        array (
          'app' => 'Admin',
          'model' => 'Storage',
          'action' => 'setting_post',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '文件存储设置提交',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
      ),
    ),
    array (
      'app' => 'Api',
      'model' => 'Oauthadmin',
      'action' => 'setting',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '第三方登陆',
      'icon' => 'leaf',
      'remark' => '',
      'listorder' => '7',
      'children' => 
      array (
        array (
          'app' => 'Api',
          'model' => 'Oauthadmin',
          'action' => 'setting_post',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '提交设置',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
      ),
    ),
    array (
      'app' => 'Admin',
      'model' => 'Setting',
      'action' => 'clearcache',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '清除缓存',
      'icon' => 'trash-o',
      'remark' => '',
      'listorder' => '8',
    ),
  ),
);