<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.2
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class Update extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        $this->db->db_debug = TRUE;
    }

    /**
     * 2.3.7 更新程序
     */
    public function index() {


        //$this->admin_msg('升级完成，请更新全站缓存在刷新页面', '', 1);
        if (DR_VERSION_ID != 16) {
            //$this->admin_msg('升级完成，请更新全站缓存在刷新页面', '', 1);
        }
        //
        $page = (int)$this->input->get('page');
        if (!$page) {
            $this->admin_msg('正在升级数据...', dr_url('update/index', array('page' => $page + 1)), 2);
        }

        switch($page) {
            case 1:
                // 升级模块内容状态字段长度
                $data = $this->db->get('module')->result_array();
                if ($data) {
                    foreach ($data as $t) {
                        $dir = $t['dirname'];
                        $site = dr_string2array($t['site']);
                        if ($site) {
                            foreach ($site as $sid => $c) {
                                $db = $this->site[$sid];
                                if (!$db) {
                                    continue;
                                }
                                $pre = $this->db->dbprefix($sid.'_'.$dir);
                                $db->query('ALTER TABLE `'.$pre.'` CHANGE `status` `status` TINYINT(2) NOT NULL COMMENT "状态";');
                                $db->query('ALTER TABLE `'.$pre.'_index` CHANGE `status` `status` TINYINT(2) NOT NULL COMMENT "状态";');
                                if ($t['extend']) {
                                    $pre = $pre.'_extend';
                                    $db->query('ALTER TABLE `'.$pre.'` CHANGE `status` `status` TINYINT(2) NOT NULL COMMENT "状态";');
                                    $db->query('ALTER TABLE `'.$pre.'_index` CHANGE `status` `status` TINYINT(2) NOT NULL COMMENT "状态";');
                                }
                                // 创建草稿箱表
                                $db->query(trim("CREATE TABLE IF NOT EXISTS `".$pre."_draft` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `cid` int(10) unsigned NOT NULL COMMENT '内容id',
			  `eid` int(10) DEFAULT NULL COMMENT '扩展id',
			  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
			  `catid` tinyint(3) unsigned NOT NULL COMMENT '栏目id',
			  `content` mediumtext NOT NULL COMMENT '具体内容',
			  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
			  PRIMARY KEY `id` (`id`),
			  KEY `eid` (`eid`),
			  KEY `uid` (`uid`),
			  KEY `cid` (`cid`),
			  KEY `catid` (`catid`),
			  KEY `inputtime` (`inputtime`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='内容草稿表';"));
                            }
                        }
                    }
                }
                $this->admin_msg('正在升级模块草稿与回收站功能...', dr_url('update/index', array('page' => $page + 1)), 2);
                break;
            case 2:
                // 网站表单升级
                $data = $this->db->get('site')->result_array();
                foreach ($data as $t) {
                    $table = $t['id'].'_form';
                    $form = $this->db->get($table)->result_array();
                    if ($form) {
                        foreach ($form as $f) {
                            $name = 'Form_'.$f['table'];
                            $file = FCPATH.'dayrui/controllers/admin/'.$name.'.php';
                            if (!file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
                                .'require FCPATH.\'dayrui/core/D_Form.php\';'.PHP_EOL.PHP_EOL
                                .'class '.$name.' extends D_Form {'.PHP_EOL.PHP_EOL
                                .'	public function __construct() {'.PHP_EOL
                                .'		parent::__construct();'.PHP_EOL
                                .'	}'.PHP_EOL.PHP_EOL
                                .'	public function add() {'.PHP_EOL
                                .'		$this->_addc();'.PHP_EOL
                                .'	}'.PHP_EOL.PHP_EOL
                                .'	public function edit() {'.PHP_EOL
                                .'		$this->_editc();'.PHP_EOL
                                .'	}'.PHP_EOL.PHP_EOL
                                .'	public function index() {'.PHP_EOL
                                .'		$this->_listc();'.PHP_EOL
                                .'	}'.PHP_EOL.PHP_EOL
                                .'}')) {
                                echo '<font color=red>'.$file.'创建失败</font></br>';
                            }

                            $file = FCPATH.'dayrui/controllers/'.$name.'.php';
                            if (!file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
                                .'require FCPATH.\'dayrui/core/D_Form.php\';'.PHP_EOL.PHP_EOL
                                .'class '.$name.' extends D_Form {'.PHP_EOL.PHP_EOL
                                .'	public function __construct() {'.PHP_EOL
                                .'		parent::__construct();'.PHP_EOL
                                .'	}'.PHP_EOL.PHP_EOL
                                .'	public function index() {'.PHP_EOL
                                .'		$this->_post();'.PHP_EOL
                                .'	}'.PHP_EOL.PHP_EOL
                                .'}')) {
                                echo '<font color=red>'.$file.'创建失败</font></br>';
                            }
                        }
                    }
                }
                $this->admin_msg('正在升级网站表单文件...', dr_url('update/index', array('page' => $page + 1)), 2, 3);
                break;
            case 3:
                // 升级模块表单结构
                $data = $this->db->get('module')->result_array();
                if ($data) {
                    // 查询模块
                    $i = 0;
                    foreach ($data as $t) {
                        $dir = $t['dirname'];
                        $site = dr_string2array($t['site']);
                        if ($site) {
                            foreach ($site as $sid => $c) {
                                // 查询是否存在模块表单
                                $table = $this->db->dbprefix($sid.'_'.$dir.'_form');
                                if (!$this->site[$sid]) {
                                    continue;
                                }
                                if (!$this->site[$sid]->query("SHOW TABLES LIKE '".$table."'")->row_array()) {
                                    continue;
                                }
                                $form = $this->db->get($table)->result_array();
                                if ($form) {
                                    // 备份当前站点下的表单内容表
                                    $table = $this->db->dbprefix($sid.'_'.$dir.'_form_');
                                    foreach ($form as $v) {
                                        $j = $v['id'];
                                        if ($this->site[$sid]->query("SHOW TABLES LIKE 'back_".$table.$j."'")->row_array()) {
                                            echo $table.$j.'备份已成功<br>';
                                            continue;
                                        }
                                        if ($this->site[$sid]->query("SHOW TABLES LIKE '".$table.$j."'")->row_array()) {
                                            $this->site[$sid]->query('RENAME TABLE `'.$table.$j.'` TO `back_'.$table.$j.'`;'); //执行更新语句
                                            echo ''.$table.$j.'备份成功</br>';
                                        } else {
                                            echo '<font color=red>'.$table.$j.'不存在，备份失败！！</font></br>';
                                        }
                                    }
                                    // 处理表单
                                    foreach ($form as $v) {
                                        $i++;
                                        // 重新生成表单id
                                        $this->db->replace('module_form', array(
                                            'id' => $i,
                                            'module' => $dir,
                                            'name' => $v['name'],
                                            'disabled' => $v['disabled'],
                                            'table' => $i,
                                            'permission' => $v['permission'],
                                            'setting' => 'setting',
                                        ));
                                        // 转移自定义字段
                                        $field = $this->db->where('relatedid', $v['id'])->where('relatedname', 'mform-'.$dir.'-'.$sid)->get('field')->result_array();
                                        //var_dump($field);
                                        if ($field) {
                                            foreach ($field as $f) {
                                                if (!$this->db->where('relatedname', 'mform-'.$dir)->where('relatedid', $i)->where('fieldname', $f['fieldname'])->count_all_results('field')) {
                                                    $this->db->insert('field', array(
                                                        'name' => $f['name'],
                                                        'fieldname' => $f['fieldname'],
                                                        'fieldtype' => $f['fieldtype'],
                                                        'relatedid' => $i,
                                                        'relatedname' => 'mform-'.$dir,
                                                        'isedit' => $f['isedit'],
                                                        'ismain' => $f['ismain'],
                                                        'issystem' => $f['issystem'],
                                                        'ismember' => $f['ismember'],
                                                        'issearch' => $f['issearch'],
                                                        'disabled' => $f['disabled'],
                                                        'setting' => $f['setting'],
                                                        'displayorder' => $f['displayorder'],
                                                    ));
                                                }
                                            }
                                        }
                                        // 重命名表名称
                                        $table = 'back_'.$this->db->dbprefix($sid.'_'.$dir.'_form_'.$v['id']);
                                        if ($this->site[$sid]->query("SHOW TABLES LIKE '".$table."'")->row_array()) {
                                            $new_table = $this->db->dbprefix($sid.'_'.$dir.'_form_'.$i);
                                            if (!$t=$this->site[$sid]->query("SHOW TABLES LIKE '".$new_table."'")->row_array()) {
                                                $this->site[$sid]->query('RENAME TABLE `'.$table.'` TO `'.$new_table.'`;');
                                                echo $new_table.'更新成功<br>';
                                            } else {
                                                echo '<font color=red>'.$new_table.'已经存在！</font><br>';
                                            }
                                        } else {
                                            echo '<font color=red>'.$table.'不存在！</font><br>';
                                        }
                                        // 表单控制器名称
                                        $name = 'Form_'.$i;
                                        // 管理控制器
                                        $file = FCPATH.$dir.'/controllers/admin/'.$name.'.php';
                                        if (!file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
                                            .'require FCPATH.\'dayrui/core/D_Admin_Form.php\';'.PHP_EOL.PHP_EOL
                                            .'class '.$name.' extends D_Admin_Form {'.PHP_EOL.PHP_EOL
                                            .'	public function __construct() {'.PHP_EOL
                                            .'		parent::__construct();'.PHP_EOL
                                            .'	}'.PHP_EOL
                                            .'}')) {
                                            echo '<font color=red>'.$dir.'/controllers/admin/'.$name.'.php 生成失败</font><br>';
                                        } else {
                                            echo $dir.'/controllers/admin/'.$name.'.php 生成成功<br>';
                                        }
                                        // 会员控制器
                                        $file = FCPATH.$dir.'/controllers/member/'.$name.'.php';
                                        if (!file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
                                            .'require FCPATH.\'dayrui/core/D_Member_Form.php\';'.PHP_EOL.PHP_EOL
                                            .'class '.$name.' extends D_Member_Form {'.PHP_EOL.PHP_EOL
                                            .'	public function __construct() {'.PHP_EOL
                                            .'		parent::__construct();'.PHP_EOL
                                            .'	}'.PHP_EOL
                                            .'}')) {
                                            echo '<font color=red>'.$dir.'/controllers/member/'.$name.'.php 生成失败</font><br>';
                                        } else {
                                            echo $dir.'/controllers/member/'.$name.'.php 生成成功<br>';
                                        }
                                        // 前端发布控制器
                                        $file = FCPATH.$dir.'/controllers/'.$name.'.php';
                                        if (!file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
                                            .'require FCPATH.\'dayrui/core/D_Home_Form.php\';'.PHP_EOL.PHP_EOL
                                            .'class '.$name.' extends D_Home_Form {'.PHP_EOL.PHP_EOL
                                            .'	public function __construct() {'.PHP_EOL
                                            .'		parent::__construct();'.PHP_EOL
                                            .'	}'.PHP_EOL
                                            .'}')) {
                                            echo '<font color=red>'.$dir.'/controllers/'.$name.'.php 生成失败</font><br>';
                                        } else {
                                            echo $dir.'/controllers/'.$name.'.php 生成成功<br>';
                                        }
                                        // 替换后台表单菜单
                                        // 查询后台模块的菜单
                                        $menu = $this->db
                                            ->where('pid<>0')
                                            ->where('uri', '')
                                            ->where('mark', 'module-'.$dir)
                                            ->order_by('displayorder ASC,id ASC')
                                            ->get('admin_menu')
                                            ->row_array();
                                        if ($menu) {
                                            // 将此表单放在模块菜单中
                                            $this->db->insert('admin_menu', array(
                                                'uri' => $this->dir.'/admin/'.strtolower($name).'/index',
                                                'url' => '',
                                                'pid' => $menu['id'],
                                                'name' => $data['name'].'管理',
                                                'mark' => 'module-'.$dir.'-'.$id,
                                                'hidden' => 0,
                                                'displayorder' => 0,
                                            ));

                                            echo $dir.'后台菜单 生成成功<br>';
                                        } else {
                                            echo '<font color=red>'.$dir.'后台菜单 生成失败</font><br>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                echo "<hr>如果以上屏幕上没有红色字体，表示升级成功； 如果出现了红色字体，请不要关闭此页面，截图发送给 在线客服QQ";
                //$this->admin_msg('正在升级模块搜索表结构...', dr_url('update/index', array('page' => $page + 1)), 2);
                break;
            default:
                //$this->admin_msg('升级完成，请更新全站缓存在刷新页面', '', 1);
                break;
        }
    }
}