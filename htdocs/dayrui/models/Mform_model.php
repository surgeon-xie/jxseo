<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.3.7
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Mform_model extends CI_Model {
	
	/**
	 * 模块表单模型类
	 */
    public function __construct() {
        parent::__construct();
    }
	
	/**
	 * 添加表单
	 * 
	 * @param	array	$data
	 * @return	string|TRUE
	 */
	public function add($dir, $data) {

        if (!$data['name'] || !$data['table']) {
            return lang('332');
        }

        // 判断表名称是否存在
        if ($this->db
                 ->where('module', $dir)
                 ->where('table', $data['table'])
                 ->count_all_results('module_form')
            ) {
            return lang('333');
        }

        // 插入表单数据
		$this->db->insert('module_form', array(
			'name' => $data['name'],
            'table' => $data['table'],
            'module' => $dir,
			'setting' => dr_array2string($data['setting']),
			'disabled' => 0,
			'permission' => dr_array2string($data['permission']),
		));

        // 执行成功的操作
		if ($id = $this->db->insert_id()) {

            // 表单控制器名称
			$name = 'Form_'.$data['table'];

			// 管理控制器
			$file = FCPATH.$dir.'/controllers/admin/'.$name.'.php';
			if (!file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
			.'require FCPATH.\'dayrui/core/D_Admin_Form.php\';'.PHP_EOL.PHP_EOL
			.'class '.$name.' extends D_Admin_Form {'.PHP_EOL.PHP_EOL
			.'	public function __construct() {'.PHP_EOL
			.'		parent::__construct();'.PHP_EOL
			.'	}'.PHP_EOL
			.'}')) {
				$this->db->where('id', $id)->delete('module_form');
				return dr_lang('243', FCPATH.$dir.'/controllers/admin/');
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
				$this->db->where('id', $id)->delete('module_form');
				return dr_lang('243', FCPATH.$dir.'/controllers/member/');
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
				$this->db->where('id', $id)->delete('module_form');
				return dr_lang('243', APPPATH.'controllers/');
			}

            // 按站点更新模块表数据
            $sql = "
			CREATE TABLE IF NOT EXISTS `{tablename}` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `cid` int(10) unsigned NOT NULL COMMENT '内容id',
			  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者id',
			  `author` varchar(50) NOT NULL COMMENT '作者名称',
			  `inputip` varchar(30) DEFAULT NULL COMMENT '录入者ip',
			  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
			  `title` varchar(255) DEFAULT NULL COMMENT '内容主题',
			  `url` varchar(255) DEFAULT NULL COMMENT '内容地址',
			  `subject` varchar(255) DEFAULT NULL COMMENT '表单主题',
			  PRIMARY KEY `id` (`id`),
			  KEY `cid` (`cid`),
			  KEY `uid` (`uid`),
			  KEY `author` (`author`),
			  KEY `inputtime` (`inputtime`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='".$data['name']."表单数据表';";

            // 获取所有站点的模块
            $module = $this->ci->get_cache('module');
            foreach ($module as $sid => $mod) {
                // 更新站点模块
                if (!in_array($dir, $mod)) {
                    continue;
                }
                // 主表名称
                $table = $this->db->dbprefix($sid.'_'.$dir.'_form_'.$data['table']);
                $this->site[$sid]->query("DROP TABLE IF EXISTS `".$table."`");
                $this->site[$sid]->query(str_replace('{tablename}', $table, $sql));
            }

            // 字段入库
			$this->db->insert('field', array(
				'name' => '主题',
				'fieldname' => 'subject',
				'fieldtype' => 'Text',
				'relatedid' => $id,
				'relatedname' => 'mform-'.$this->dir,
				'isedit' => 1,
				'ismain' => 1,
				'ismember' => 1,
				'issystem' => 1,
				'issearch' => 1,
				'disabled' => 0,
				'setting' => dr_array2string(array(
					'option' => array(
						'width' => 300, // 表单宽度
						'fieldtype' => 'VARCHAR', // 字段类型
						'fieldlength' => '255' // 字段长度
					),
					'validate' => array(
						'xss' => 1, // xss过滤
						'required' => 1, // 表示必填
					)
				)),
				'displayorder' => 0,
			));

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
            }
		}

		return FALSE;
	}
	
	/**
	 * 删除
	 * 
	 * @param	intval	$id
	 * @param	intval	$sid
	 */
	public function del($id, $dir) {

		if (!$id || !$dir) {
            return NULL;
        }

        $data = $this->db->where('id', $id)->get('module_form')->row_array();
        if (!$data) {
            return NULL;
        }

        $tablename = $data['table'];
        $this->load->model('attachment_model');

        // 删除字段
		$this->db->where('relatedid', (int)$id)->where('relatedname', 'mform-'.$dir)->delete('field');

        // 删除菜单
        $this->db->where('mark', 'module-'.$dir.'-'.$id)->delete('admin_menu');

        // 按站点来删除表
        $module = $this->ci->get_cache('module');
        // 获取所有站点的模块
        foreach ($module as $sid => $mod) {
            // 更新站点模块
            if (!in_array($dir, $mod)) {
                continue;
            }
            $table = $this->db->dbprefix($sid.'_'.$dirname.'_form_'.$tablename);
            // 删除表单表
            $this->site[$sid]->query('DROP TABLE IF EXISTS `'.$table.'`');
            // 删除附件
            $this->attachment_model->delete_for_table($table, TRUE);
            $this->attachment_model->delete_for_table($table.'_'.$id, TRUE);
        }

		// 删除数据记录
		$this->db->where('id', $id)->delete('module_form');

        // 删除文件
		@unlink(FCPATH.$this->dir.'/controllers/Form_'.$tablename.'.php');
		@unlink(FCPATH.$this->dir.'/controllers/admin/Form_'.$tablename.'.php');
		@unlink(FCPATH.$this->dir.'/controllers/member/Form_'.$tablename.'.php');
		
		return NULL;
	}
	
	//-------------------------------------------------------//
	
	
	/**
	 * 获取表单内容
	 *
	 * @param	intval	$id
	 * @return	intavl
	 */
	public function get($id, $fid) {
		
		if (!$fid || !$id) {
            return NULL;
        }
		
		return $this->link->where('id', $id)->get(SITE_ID.'_'.APP_DIR.'_form_'.$fid)->row_array();
	}
}