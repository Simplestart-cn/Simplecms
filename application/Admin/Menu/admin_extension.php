<?php
return array (
  'app' => 'Admin',
  'model' => 'Extension',
  'action' => 'default',
  'data' => '',
  'type' => '0',
  'status' => '1',
  'name' => '扩展配置',
  'icon' => 'sitemap',
  'remark' => '',
  'listorder' => '4',
  'children' => 
  array (
    array (
      'app' => 'Admin',
      'model' => 'Navcat',
      'action' => 'default1',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '前台菜单',
      'icon' => 'laptop',
      'remark' => '',
      'listorder' => '0',
      'children' => 
      array (
        array (
          'app' => 'Admin',
          'model' => 'Nav',
          'action' => 'index',
          'data' => '',
          'type' => '1',
          'status' => '1',
          'name' => '菜单管理',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Nav',
              'action' => 'listorders',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '前台导航排序',
              'icon' => '',
              'remark' => '',
              'listorder' => '0',
            ),
            array (
              'app' => 'Admin',
              'model' => 'Nav',
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
              'model' => 'Nav',
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
                  'model' => 'Nav',
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
              'model' => 'Nav',
              'action' => 'add',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '添加菜单',
              'icon' => '',
              'remark' => '',
              'listorder' => '1000',
              'children' => 
              array (
                array (
                  'app' => 'Admin',
                  'model' => 'Nav',
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
          ),
        ),
        array (
          'app' => 'Admin',
          'model' => 'Navcat',
          'action' => 'index',
          'data' => '',
          'type' => '1',
          'status' => '1',
          'name' => '菜单分类',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Navcat',
              'action' => 'delete',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '删除分类',
              'icon' => '',
              'remark' => '',
              'listorder' => '1000',
            ),
            array (
              'app' => 'Admin',
              'model' => 'Navcat',
              'action' => 'edit',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '编辑分类',
              'icon' => '',
              'remark' => '',
              'listorder' => '1000',
              'children' => 
              array (
                array (
                  'app' => 'Admin',
                  'model' => 'Navcat',
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
              'model' => 'Navcat',
              'action' => 'add',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '添加分类',
              'icon' => '',
              'remark' => '',
              'listorder' => '1000',
              'children' => 
              array (
                array (
                  'app' => 'Admin',
                  'model' => 'Navcat',
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
          ),
        ),
      ),
    ),
    array (
      'app' => 'Portal',
      'model' => 'AdminTerm',
      'action' => 'index',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '分类配置',
      'icon' => 'sitemap',
      'remark' => '',
      'listorder' => '1',
      'children' => 
      array (
        array (
          'app' => 'Portal',
          'model' => 'AdminTerm',
          'action' => 'listorders',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '分类排序',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
        array (
          'app' => 'Portal',
          'model' => 'AdminTerm',
          'action' => 'delete',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '删除分类',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
        ),
        array (
          'app' => 'Portal',
          'model' => 'AdminTerm',
          'action' => 'edit',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '编辑分类',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
          'children' => 
          array (
            array (
              'app' => 'Portal',
              'model' => 'AdminTerm',
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
          'app' => 'Portal',
          'model' => 'AdminTerm',
          'action' => 'add',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '添加分类',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
          'children' => 
          array (
            array (
              'app' => 'Portal',
              'model' => 'AdminTerm',
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
      ),
    ),
    array (
      'app' => 'Portal',
      'model' => 'AdminModel',
      'action' => 'index',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '模型配置',
      'icon' => 'cubes',
      'remark' => '',
      'listorder' => '2',
      'children' => 
      array (
        array (
          'app' => 'Portal',
          'model' => 'AdminModel',
          'action' => 'add',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '添加模型',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
          'children' => 
          array (
            array (
              'app' => 'Portal',
              'model' => 'AdminModel',
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
          'app' => 'Portal',
          'model' => 'AdminModel',
          'action' => 'edit',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '编辑模型',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
          'children' => 
          array (
            array (
              'app' => 'Portal',
              'model' => 'AdminModel',
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
          'app' => 'Portal',
          'model' => 'AdminModel',
          'action' => 'delete',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '删除模型',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
      ),
    ),
    array (
      'app' => 'Portal',
      'model' => 'AdminPage',
      'action' => 'index',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '页面配置',
      'icon' => 'file-o',
      'remark' => '',
      'listorder' => '3',
      'children' => 
      array (
        array (
          'app' => 'Portal',
          'model' => 'AdminPage',
          'action' => 'listorders',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '页面排序',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
        array (
          'app' => 'Portal',
          'model' => 'AdminPage',
          'action' => 'delete',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '删除页面',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
        ),
        array (
          'app' => 'Portal',
          'model' => 'AdminPage',
          'action' => 'edit',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '编辑页面',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
          'children' => 
          array (
            array (
              'app' => 'Portal',
              'model' => 'AdminPage',
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
          'app' => 'Portal',
          'model' => 'AdminPage',
          'action' => 'add',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '添加页面',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
          'children' => 
          array (
            array (
              'app' => 'Portal',
              'model' => 'AdminPage',
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
      ),
    ),
    array (
      'app' => 'Admin',
      'model' => 'Plugin',
      'action' => 'index',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '插件配置',
      'icon' => 'plug',
      'remark' => '',
      'listorder' => '4',
      'children' => 
      array (
        array (
          'app' => 'Admin',
          'model' => 'Plugin',
          'action' => 'toggle',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '插件启用切换',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Plugin',
          'action' => 'setting',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '插件设置',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Plugin',
              'action' => 'setting_post',
              'data' => '',
              'type' => '1',
              'status' => '0',
              'name' => '插件设置提交',
              'icon' => '',
              'remark' => '',
              'listorder' => '0',
            ),
          ),
        ),
        array (
          'app' => 'Admin',
          'model' => 'Plugin',
          'action' => 'install',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '插件安装',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Plugin',
          'action' => 'uninstall',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '插件卸载',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Plugin',
          'action' => 'update',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '插件更新',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
      ),
    ),
    array (
      'app' => 'Admin',
      'model' => 'Slidecat',
      'action' => 'index',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '幻灯配置',
      'icon' => '',
      'remark' => '',
      'listorder' => '5',
      'children' => 
      array (
        array (
          'app' => 'Admin',
          'model' => 'Slidecat',
          'action' => 'delete',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '删除分类',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Slidecat',
          'action' => 'edit',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '编辑分类',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Slidecat',
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
          'model' => 'Slidecat',
          'action' => 'add',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '添加分类',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Slidecat',
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
      ),
    ),
    array (
      'app' => 'Admin',
      'model' => 'Ad',
      'action' => 'index',
      'data' => '',
      'type' => '1',
      'status' => '1',
      'name' => '网站广告',
      'icon' => 'bullhorn',
      'remark' => '',
      'listorder' => '7',
      'children' => 
      array (
        array (
          'app' => 'Admin',
          'model' => 'Ad',
          'action' => 'toggle',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '广告显示切换',
          'icon' => '',
          'remark' => '',
          'listorder' => '0',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Ad',
          'action' => 'delete',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '删除广告',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
        ),
        array (
          'app' => 'Admin',
          'model' => 'Ad',
          'action' => 'edit',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '编辑广告',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Ad',
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
          'model' => 'Ad',
          'action' => 'add',
          'data' => '',
          'type' => '1',
          'status' => '0',
          'name' => '添加广告',
          'icon' => '',
          'remark' => '',
          'listorder' => '1000',
          'children' => 
          array (
            array (
              'app' => 'Admin',
              'model' => 'Ad',
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
      ),
    ),
  ),
);