<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * �����̨Ȩ��ѡ��
 *
 * ��ʽ��$config['auth'][] = array('name' => ѡ�����, 'auth' => array(uri => Ȩ�����, ....)) , ...
 * URI˵����ģ��/Ӧ��URI����Ҫ����ģ��/Ӧ�õ�Ŀ¼���
 *
 */

$config['auth'][] = array(
	'name' => lang('html-392'),
	'auth' => array(
		'admin/home/index' => lang('admin'),
		'admin/home/add' => lang('add'),
		'admin/home/edit' => lang('edit'),
		'admin/home/del' => lang('del'),
        'admin/home/content' => lang('365'),
		'admin/home/html' => lang('html-621'),
        'admin/home/draft' => lang('334'),
	)
);

$config['auth'][] = array(
    'name' => lang('html-392'),
    'auth' => array(
        'admin/home/verify' => lang('114'),
        'admin/verify/index' => lang('346'),
    )
);

$config['auth'][] = array(
	'name' => lang('html-576'),
	'auth' => array(
		'admin/category/index' => lang('admin'),
		'admin/category/add' => lang('add'),
		'admin/category/edit' => lang('edit'),
		'admin/category/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('152'),
	'auth' => array(
		'admin/page/index' => lang('admin'),
		'admin/page/add' => lang('add'),
		'admin/page/edit' => lang('edit'),
		'admin/page/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('173'),
	'auth' => array(
		'admin/tag/index' => lang('admin'),
		'admin/tag/add' => lang('add'),
		'admin/tag/edit' => lang('edit'),
		'admin/tag/del' => lang('del'),
	)
);

$config['auth'][] = array(
	'name' => lang('231'),
	'auth' => array(
		'admin/theme/index' => lang('admin'),
		'admin/theme/add' => lang('add'),
		'admin/theme/edit' => lang('edit'),
		'admin/theme/del' => lang('del'),
	)
);


$config['auth'][] = array(
	'name' => lang('230'),
	'auth' => array(
		'admin/tpl/index' => lang('admin'),
		'admin/tpl/add' => lang('add'),
		'admin/tpl/edit' => lang('edit'),
		'admin/tpl/del' => lang('del'),
		'admin/tpl/tag' => lang('233'),
	)
);