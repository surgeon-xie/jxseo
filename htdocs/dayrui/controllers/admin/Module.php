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

class Module extends M_Controller {
	
	private $_menu;
	private $_to_file;
	private $_from_file;
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->_menu = array(
			lang('073') => 'admin/module/index',
			lang('086') => 'admin/module/store',
		);
		$this->template->assign(array(
			'menu' => $this->get_menu($this->_menu),
			'duri' => $this->duri
		));
		$this->load->model('module_model');
    }

    /**
     * 模块
     */
    public function index() {

        if (IS_POST) {
            $data = $this->input->post('data');
            if ($data) {
                foreach ($data as $id => $t) {
                    $this->db
                         ->where('id', $id)
                         ->update('module', array('displayorder' => (int)$t['displayorder']));
                }
            }
        }

		$store = $data = array();
		$local = @array_diff(dr_dir_map(FCPATH, 1), array('app', 'cache', 'config', 'dayrui', 'member', 'space', 'player')); // 搜索本地模块
		$module = $this->module_model->get_data(); // 库中已安装模块
		
		if ($local) {
			// 从后台菜单中获取模块名称
			$menu = $this->db
						 ->select('mark,name')
						 ->where('pid', 0)
						 ->like('mark', 'module-')
						 ->get('admin_menu')
						 ->result_array();
			$name = array();
			if ($menu) {
				foreach ($menu as $t) {
					list($a, $dir) = explode('-', $t['mark']);
					$name[$dir] = $t['name'];
				}
			}
			foreach ($local as $dir) {
				if (is_file(FCPATH.$dir.'/config/module.php')) {
					if (isset($module[$dir])) {
						$module[$dir]['url'] = $module[$dir]['site'][SITE_ID]['domain'] ? 'http://'.$module[$dir]['site'][SITE_ID]['domain'] : SITE_URL.$dir;
						$config = $module[$dir] = array_merge($module[$dir], require FCPATH.$dir.'/config/module.php');
						if (isset($name[$dir])) {
                            $module[$dir]['name'] = $name[$dir];
                        }
                        $module[$dir]['mydb'] = isset($config['mydb']) && $config['mydb'] ? 1 : 0;
						if ($config['key']) {
							$store[$dir] = array(
								'key' => $config['key'],
								'version' => $config['version'],
							);
						}
					} else {
						$data[0][$dir] = require FCPATH.$dir.'/config/module.php';
					}
				}
			}
		}
        $data[1] = $module;
		
		$this->template->assign(array(
			'list' => $data,
			'store' => dr_base64_encode(dr_array2string($store)),
		));
		$this->template->display('module_index.html');
	}
	
	/**
     * 配置
     */
    public function config() {
	
		$id = (int)$this->input->get('id');
		$all = (int)$this->input->get('all');
		$data = $this->module_model->get($id);
		$result	= 0;
		if (!$data) {
            $this->admin_msg(lang('019'));
        }
		if (IS_POST) {
			$post = $this->input->post('data');
            $name = $this->input->post('name');
            $this->module_model->edit($id, $post);
            // 更新后台菜单
            $this->db
                 ->where('pid', 0)
                 ->where('mark', 'module-'.$data['dirname'])
                 ->update('admin_menu', array('name' => $name));
            // 更新会员菜单
            $this->db
                 ->where('pid', 0)
                 ->where('mark', 'module-'.$data['dirname'])
                 ->update('member_menu', array('name' => $name));
            $this->clear_cache('module');
            $this->admin_msg(lang('014'), $all ? dr_url('module/index') : dr_url('module/config', array('id' => $id)), 1);
		} else {
            $cfg = require FCPATH.$data['dirname'].'/config/module.php';
            if (isset($cfg['mydb']) && $cfg['mydb']) {
                // 跳转到模块自身的配置页面去
                redirect(SITE_URL.dr_url($data['dirname'].'/mconfig/index'), 'refresh');
            }
        }
		
		// 模块名称
		$name = $this->db
					 ->select('name')
					 ->where('pid', 0)
					 ->where('mark', 'module-'.$data['dirname'])
					 ->limit(1)
					 ->get('admin_menu')
					 ->row_array();

		$this->_menu[lang('362')] = 'admin/module/config/id/'.$id.'/all/1/';
		
		$this->template->assign(array(
            'all' => $all,
			'data' => $data,
			'name' => $name['name'],
			'role' => $this->dcache->get('role'),
			'menu' => $this->get_menu($this->_menu),
			'result' => $result,
		));
		$this->template->display('module_config.html');
    }
	
	/**
     * 权限划分
     */
	public function role() {
	
		$id = (int)$this->input->get('id');
		$dir = $this->input->get('dir');
		
		if ($id == 1) {
            exit(lang('027'));
        }
		if (!is_file(FCPATH.$dir.'/config/auth.php')) {
            exit(dr_lang('174', '/'.$dir.'/config/auth.php'));
        }
		if (is_file(FCPATH.$dir.'/language/'.SITE_LANGUAGE.'/module_lang.php')) {
			require FCPATH.$dir.'/language/'.SITE_LANGUAGE.'/module_lang.php';
			$this->lang->language = $this->lang->language + $lang;
		}
		
		if (IS_POST) {
			$rule = NULL;
			$post = $this->input->post('data', TRUE);
			$data = $this->db
						 ->where('id', $id)
						 ->get('admin_role')
						 ->row_array();
			if ($data['module']) {
				$rule = dr_string2array($data['module']);
				if ($rule) {
					foreach ($rule as $i => $t) {
						if (strpos($t, $dir.'/admin') === 0) {
                            unset($rule[$i]);
                        }
					}
				}
			}
			if ($rule) {
                $post = array_merge($rule, $post);
            }
			$this->auth_model->update_auth($id, 'module', $post);
			exit;
		}
		
		$data = $this->auth_model->get_role($id);
		require FCPATH.$dir.'/config/auth.php';
		
		$this->template->assign(array(
			'data' => $data['module'],
			'list' => $config['auth'],
			'prefix' => $dir.'/',
		));
        $this->template->display('admin_auth.html');
	}
	
	/**
     * 禁用/可用
     */
    public function disabled() {
		if ($this->is_auth('admin/module/config')) {
			$id = (int)$this->input->get('id');
			$_data = $this->db
						  ->select('disabled')
						  ->where('id', $id)
						  ->limit(1)
						  ->get('module')
						  ->row_array();
			$this->db
				 ->where('id', $id)
				 ->update('module', array('disabled' => ($_data['disabled'] == 1 ? 0 : 1)));
            $this->clear_cache('module');
		}
		exit(dr_json(1, lang('014')));
    }
	
	/**
     * 复制
     */
    public function copy() {
		if ($this->is_auth('admin/module/config')) {
			$dir = $this->input->get('dir');
			if (IS_POST) {
				$data = $this->input->post('data');
				if (!$data['dirname'] || !preg_match('/^[a-z]+$/iU', $data['dirname'])) {
					exit(dr_json(0, lang('html-519')));
				} elseif (is_dir(FCPATH.$data['dirname'])) {
					exit(dr_json(0, lang('html-520')));
				} elseif ($data['name'] && strpos($data['name'], "'") !== FALSE) {
					exit(dr_json(0, lang('html-091')));
				}
				$this->_copy_file(FCPATH.$dir, FCPATH.$data['dirname']);
				if ($data['name']) {
					$file = FCPATH.$data['dirname'].'/config/module.php';
					$config = require $file;
					$config['name'] = $data['name'];
					$this->load->library('dconfig');
					$this->dconfig
						 ->file($file)
						 ->note('模块配置文件')
						 ->space(24)
						 ->to_require_one($config);
				}
				exit(dr_json(1, lang('html-092')));
			} else {
				$this->template->display('module_copy.html');
			}
		} else {
			exit(dr_json(1, lang('014')));
		}
    }
	
	/**
     * 导出
     */
    public function export() {
		if ($this->is_auth('admin/module/config')) {
			$dir = $this->input->get('dir');
			$name = $this->input->get('name');
			if ($this->input->get('action') == 1) {
				$this->_copy_file(FCPATH.$dir.'/config/', FCPATH.$dir.'/_config/');
				$error = $this->module_model->export($dir, $name);
				if ($error) {
					$this->admin_msg($error);
				} else {
					$this->admin_msg(lang('313'), dr_url('module/index'), 1, 10);
				}
			} else {
				$this->admin_msg(lang('html-476'), dr_url('module/export', array('dir' => $dir, 'name' => $name, 'action' => 1)), 2);
			}
		} else {
			$this->admin_msg(lang('014'));
		}
    }

    /**
     * 安装到站点
     */
    public function install_all() {

        $dir = basename($this->input->get('dir'));
        if (!preg_match('/^[a-z]+$/iU', $dir)) {
            $this->admin_msg(lang('html-519'));
        }
        if (!is_file(FCPATH.$dir.'/config/module.php')) {
            $this->admin_msg(dr_lang('089', $dir));
        }

        $cfg = require FCPATH.$dir.'/config/module.php';
        $mydb = isset($cfg['mydb']) && $cfg['mydb'] ? 1 : 0;

        // 非自定义表时
        if (!$mydb) {
            if (!is_file(FCPATH.$dir.'/config/main.table.php')) {
                $this->admin_msg(dr_lang('090', $dir));
            }
            if (!is_file(FCPATH.$dir.'/config/data.table.php')) {
                $this->admin_msg(dr_lang('091', $dir));
            }
        }

        // 入库模块表和字段
        $id = $this->module_model->add($dir, $cfg, $mydb);
        if (!$id) {
            $this->admin_msg(dr_lang('092', $dir));
        }

        // 安装当前站点的数据表
        $this->module_model->install($id, $dir, SITE_ID, $cfg, $mydb);

        // 更新站点到模块表
        $this->db->where('id', $id)->update('module', array('site' => dr_array2string(array(
            SITE_ID => array(
                'use' => 1,
                'html' => 0,
                'theme' => SITE_THEME,
                'domain' => '',
                'template' => SITE_TEMPLATE,
            )
        ))));

        // 更新后台菜单缓存
        $this->load->model('menu_model');
        $this->menu_model->cache();

        // 更新会员菜单缓存
        $this->load->model('member_model');
        $this->member_model->cache();

        if ((int)$_GET['admin']) {
            exit('ok');
        }

        $this->admin_msg(lang('093'), dr_url('module/install', array('id'=>$id)), 1);
    }
	
	/**
     * 站点的安装管理
     */
    public function install() {

        $id = (int)$this->input->get('id');
        $data = $this->module_model->get($id);
        if (!$data) {
            $this->admin_msg(lang('019'));
        }

        // 菜单
        $this->_menu = array(
            lang('073') => 'admin/module/index',
            dr_lang('363', $data['name']) => 'admin/module/install/id/'.$id,
        );

        $this->template->assign(array(
            'id' => $id,
            'dir' => $data['dirname'],
            'data' => $data,
            'menu' => $this->get_menu($this->_menu),
        ));
        $this->template->display('module_install.html');

    }

    /**
     * 站点配置
     */
    public function install3() {

        $id = (int)$this->input->get('id');
        $sid = (int)$this->input->get('sid');
        $all = (int)$this->input->get('all');
        $data = $this->module_model->get($id);
        if (!$data) {
            $this->admin_msg(lang('019'));
        }
        if (!$data['site'][$sid]) {
            $this->admin_msg(lang('359'));
        }

        if (IS_POST) {
            $post = $this->input->post('data');
            $post['use'] = 1;
            $data['site'][$sid] = $post;
            $this->db->where('id', $id)->update('module', array(
                'site' => dr_array2string($data['site']),
            ));
            $this->clear_cache('module');
            if ($all) {
                $this->admin_msg(lang('000'), dr_url('module/install3', array('id' => $id, 'sid' => $sid, 'all' => 1)), 1);
            } else {
                $this->admin_msg(lang('000'), dr_url('module/install', array('id' => $id)), 1);
            }
        }

        // 菜单
        if ($all) {
            $this->_menu = array(
                lang('362') => 'admin/module/config/id/'.$id.'/all/0',
                lang('361') => 'admin/module/install3/id/'.$id.'/sid/'.$sid.'/all/1/',
            );
        } else {
            $this->_menu = array(
                lang('073') => 'admin/module/index',
                dr_lang('363', $data['name']) => 'admin/module/install/id/'.$id,
                lang('361') => 'admin/module/install3/id/'.$id.'/sid/'.$sid,
            );
        }


        // 模块风格
        $theme = dr_dir_map(FCPATH.$data['dirname'].'/statics/', 1);

        $this->template->assign(array(
            'sid' => $sid,
            'data' => $data,
            'menu' => $this->get_menu($this->_menu),
            'theme' => $theme ? $theme : array('default'),
            'result' => $result ? $result : '',
            'template_path' => @array_diff(dr_dir_map(FCPATH.$data['dirname'].'/templates/', 1), array('admin', 'member')),
        ));
        $this->template->display('module_install3.html');

    }

    // 执行站点安装
    public function install2() {

        $id = (int)$this->input->get('id');
        $sid = (int)$this->input->get('sid');
        $data = $this->module_model->get($id);
        if (!$data) {
            $this->admin_msg(lang('019'));
        }

        $dir = $data['dirname'];
        $cfg = require FCPATH.$dir.'/config/module.php';
        $mydb = isset($cfg['mydb']) && $cfg['mydb'] ? 1 : 0;

        // 非自定义表时
        if (!$mydb) {
            if (!is_file(FCPATH.$dir.'/config/main.table.php')) {
                $this->admin_msg(dr_lang('090', $dir));
            }
            if (!is_file(FCPATH.$dir.'/config/data.table.php')) {
                $this->admin_msg(dr_lang('091', $dir));
            }
        }

        $first = 0;
        if (isset($data['site']) && $data['site']) {
            foreach ($data['site'] as $i => $t) {
                $first = $i;
            }
        }

		// 安装当前站点的数据表
        $this->module_model->install($id, $data['dirname'], $sid, $cfg, $mydb, $first);
		
		// 更新站点到模块表
        $data['site'][$sid] = array(
            'use' => 1,
            'html' => 0,
            'theme' => SITE_THEME,
            'domain' => '',
            'template' => SITE_TEMPLATE,
        );
		$this->db->where('id', $id)->update('module', array('site' => dr_array2string($data['site'])));

        $this->clear_cache('module');
        $this->admin_msg(lang('000'), dr_url('module/install3', array('id'=>$id, 'sid'=>$sid)), 1);
    }
	
	/**
     * 卸载
     */
    public function uninstall() {
		$this->module_model->del((int)$this->input->get('id'));
        $this->clear_cache('module');
		$this->admin_msg(lang('094'), dr_url('module/index'), 1);
    }
    
	/**
     * 卸载站点
     */
    public function uninstall2() {

        $id = (int)$this->input->get('id');
        $sid = (int)$this->input->get('sid');
        $data = $this->module_model->get($id);
        if (!$data) {
            $this->admin_msg(lang('019'));
        }

        // 删除站点记录
        unset($data['site'][$sid]);
        $this->db->where('id', $id)->update('module', array(
            'site' => dr_array2string($data['site']),
        ));

        // 删除站点数据
		$this->module_model->uninstall(
            $id,
            $this->input->get('dir'),
            (int)$this->input->get('sid'),
            3
        );

        $this->clear_cache('module');
		$this->admin_msg(lang('000'), dr_url('module/install', array('id'=>$id)), 1);
    }

	/**
     * 清空
     */
    public function clear() {
		$this->module_model->clear(
            $this->input->get('dir'),
            (int)$this->input->get('sid')
        );
        $this->clear_cache('module');
		$this->admin_msg(lang('000'), dr_url('module/install', array('id'=>(int)$this->input->get('id'))), 1);
    }
	
	/**
     * 删除
     */
    public function delete() {
	
		$id = (int)$this->input->get('id');
		$dir = $this->input->get('dir');
		if ($id) {
            $this->module_model->del($id);
        }
		
		$this->load->helper('file');
		delete_files(FCPATH.$dir.'/', TRUE);
		if (is_dir(FCPATH.$dir.'/')) {
            @rmdir(FCPATH.$dir.'/');
        }

        if (is_dir(FCPATH.$dir.'/')) {
            $this->admin_msg(lang('352'));
        }

        $this->clear_cache('module');
		$this->admin_msg(lang('000'), dr_url('module/index'), 1);
    }
	
	/**
     * 推荐位收费
     */
    public function flag() {
		$this->template->display('module_flag.html');
		$this->output->enable_profiler(FALSE);
    }
	
	/**
     * 模块商店
     */
    public function store() {
	
		$data = array();
		$local = @array_diff(dr_dir_map(FCPATH, 1), array('app', 'cache', 'config', 'dayrui', 'member', 'space', 'player')); // 搜索本地模块
		if ($local) {
			foreach ($local as $dir) {
				if (is_file(FCPATH.$dir.'/config/module.php')) {
					$config = require FCPATH.$dir.'/config/module.php';
					if ($config['key']) {
						$data[$config['key']] = $config['version'];
					}
				}
			}
		}
		
		$url = 'http://store.dayrui.com/index.php?c=category&id=3&action=module&param='.dr_base64_encode(dr_array2string(array(
			'site' => SITE_URL,
			'name' => SITE_NAME,
			'data' => $data,
			'admin' => SELF,
			'version' => DR_VERSION_ID,
		)));
		
		$this->template->assign(array(
			'url' => $url,
		));
		$this->template->display('online.html');
    }
	
	
	/**
     * 云端下载程序
     */
    public function down() {
	
    	$dir = $this->input->get('dir');
		if (is_dir(FCPATH.$dir.'/')) {
            $this->admin_msg('目录（/'.$dir.'/）已经存在');
        }
		
    	$data = dr_catcher_data(urldecode($this->input->get('id')));
    	if (!$data) {
            $this->admin_msg('对不起，您的服务器不支持远程下载');
        }
		
    	$save = FCPATH.'cache/down/module.zip';
    	$check = FCPATH.'cache/down/module/';
		if (!@file_put_contents($save, $data)) {
            $this->admin_msg('目录（/cache/down/）没有写入权限');
        }

        // 载入解压缩类
		$this->load->helper('file');
		$this->load->library('Pclzip');

        // 文件安全性检测
        $this->pclzip->PclFile($save);
        $content = $this->pclzip->listContent();
        if (!$content) {
            $this->admin_msg('文件下载不完整或者已经损坏！<br>请检查网站目录权限或者联系模块开发者。');
        }
        foreach ($content as $t) {
            if (strpos($t['stored_filename'], '..') !== FALSE ||
                strpos($t['stored_filename'], '/') === 0) {
                $this->admin_msg('含有非法名称的文件：'.basename($t['stored_filename']));
            }
        }
        unset($content);

        // 开始解压文件
		if ($this->pclzip->extract(PCLZIP_OPT_PATH, $check, PCLZIP_OPT_REPLACE_NEWER) == 0) {
			@unlink($save);
			delete_files(FCPATH.'cache/down/', TRUE);
			$this->admin_msg("Error : " . $this->pclzip->errorInfo(true));
		}
		
		// 检查版本文件
    	if (!is_file($check.'config/module.php') || !filesize($check.'config/module.php')) {
			delete_files(FCPATH.'cache/down/', TRUE);
    		$this->admin_msg('文件不完整，请重试');
    	}
		
    	// 覆盖至网站根目录
    	$this->pclzip->extract(PCLZIP_OPT_PATH, FCPATH.$dir.'/', PCLZIP_OPT_REPLACE_NEWER);
    	delete_files(FCPATH.'cache/down/', TRUE);
		
		$this->admin_msg('下载成功，即将为您跳转到应用中心', dr_url('module/index'), 1);
    }
	
	/**
     * 更新
     */
    public function update() {
	
		$key = 0;
		$dir = $this->input->get('id');
		if (is_file(FCPATH.$dir.'/config/module.php')) {
			$config = require FCPATH.$dir.'/config/module.php';
			$key = (int)$config['key'];
		}
		
		if (!$key) {
            $this->admin_msg('此模块无法在线更新（key不存在）');
        }
		$url = 'http://store.dayrui.com/index.php?c=down&m=update&action=module&param='.dr_base64_encode(dr_array2string(array(
			'site' => SITE_URL,
			'name' => SITE_NAME,
			'data' => array(
				'id' => $key,
				'dir' => $dir,
				'version' => $config['version']
			),
			'admin' => SELF,
			'domain' => SITE_URL,
			'version' => DR_VERSION_ID,
		)));
		
		$this->template->assign(array(
			'url' => $url,
		));
		$this->template->display('online.html');
    }
	
	/**
     * 升级程序
     */
    public function upgrade() {
	
    	$key = (int)$this->input->get('key');
    	$dir = $this->input->get('dir');
		
		if (is_file(FCPATH.$dir.'/config/module.php')) {
			$config = require FCPATH.$dir.'/config/module.php';
			if ((int)$config['key'] != $key) {
                $this->admin_msg('此应用无法在线升级，key不匹配');
            }
		} else {
			 $this->admin_msg('此模块无法在线升级，目录（/'.$dir.'/）不存在');
		}
		
    	$data = dr_catcher_data(urldecode($this->input->get('id')));
    	if (!$data) {
            $this->admin_msg('对不起，您的服务器不支持远程下载');
        }
		
    	$save = FCPATH.'cache/down/module.zip';
    	$check = FCPATH.'cache/down/module/';
		if (!@file_put_contents($save, $data)) {
            $this->admin_msg('目录/cache/down/没有写入权限');
        }
		
		// 解压缩文件
		$this->load->helper('file');
		$this->load->library('Pclzip');

        // 文件安全性检测
        $this->pclzip->PclFile($save);
        $content = $this->pclzip->listContent();
        if (!$content) {
            $this->admin_msg('文件下载不完整或者已经损坏！<br>请检查网站目录权限或者联系模块开发者。');
        }
        foreach ($content as $t) {
            if (strpos($t['stored_filename'], '..') !== FALSE ||
                strpos($t['stored_filename'], '/') === 0) {
                $this->admin_msg('含有非法名称的文件：'.basename($t['stored_filename']));
            }
        }
        unset($content);

        // 开始解压文件
		if ($this->pclzip->extract(PCLZIP_OPT_PATH, $check, PCLZIP_OPT_REPLACE_NEWER) == 0) {
			@unlink($save);
			delete_files(FCPATH.'cache/down/', TRUE);
			$this->admin_msg("Error : " . $this->pclzip->errorInfo(true));
		}
		
		// 检查版本文件
    	if (!is_file($check.'config/module.php') || !filesize($check.'config/module.php')) {
			delete_files(FCPATH.'cache/down/', TRUE);
    		$this->admin_msg('升级文件不完整，请重试');
    	}
		
    	// 覆盖至网站目录
    	$this->pclzip->extract(PCLZIP_OPT_PATH, FCPATH.$dir, PCLZIP_OPT_REPLACE_NEWER);
    	delete_files(FCPATH.'cache/down/', TRUE);
		
    	// 运行SQL语句
    	if (is_file(FCPATH.$dir.'update.sql')) {
    		$sql = file_get_contents(FCPATH.$dir.'update.sql');
			$sql = str_replace('{dbprefix}', $this->db->dbprefix, $sql);
			$this->sql_query($sql);
			@unlink(FCPATH.$dir.'update.sql');
    	}
		
    	//检查update控制器
		if (is_file(FCPATH.$dir.'/controllers/admin/Update.php')) {
            $this->admin_msg('正在升级数据，请稍候...', dr_url($dir.'/update/index'), 2);
        }
		$this->admin_msg('升级完成，请重新检测一次版本', dr_url('module/index'), 1);
    }
	
	/**
     * 缓存
	 *
	 * 模块缓存文件格式：module-站点id-模块名称 = array(模块数组);
	 * 模块数据缓存文件：module = array( 模块名称1, 模块名称2, 模块名称3);
	 *
     */
    public function cache($update = 1) {
	
		$dir = $this->input->get('dir');
		$admin = (int)$this->input->get('admin');
		
		// 更新后台菜单缓存
		$this->load->model('menu_model');
		$this->menu_model->cache();
		
		if ($dir) {
			$url = $this->input->get('url') ? $this->input->get('url') : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
			$todo = (int)$this->input->get('todo');
			if (!($admin || !$update) && !$todo) {
				$this->admin_msg(lang('004'), dr_url('module/cache', array('dir' => $dir, 'todo' => 1, 'url' => urlencode($url))), 2, 0);
			}
			$this->module_model->cache($dir, $update);
			if ($admin || !$update) {
                return '';
            }
			$this->admin_msg(lang('000'), urldecode($url), 1);
		} else {
			// 模块页面更新缓存
			$step = (int)$this->input->get('step');
			$todo = (int)$this->input->get('todo');
			$module = $this->db
						   ->where('disabled', 0)
						   ->get('module')
						   ->result_array();
			if (!$todo && $module) {
				$cache = array();
				foreach ($module as $t) {
					$site = dr_string2array($t['site']);
					foreach ($site as $_site => $url) {
						$cache[$_site][] = $t['dirname']; // 将模块归类至站点
					}
				}
				$this->dcache->set('module', $cache);
				$this->admin_msg(lang('004'), dr_url('module/cache', array('step' => 0, 'todo' => 1)), 2, 0);
			}
			if (!isset($module[$step])) {
                $this->admin_msg(lang('116'), dr_url('module/index'), 1);
            }
			$this->module_model->cache($module[$step]['dirname'], $update);
			$this->admin_msg(dr_lang('009', $module[$step]['dirname']).' ...', dr_url('module/cache', array('step' => $step + 1, 'todo' => 1)), 2, 0);
		}
	}
	
	/**
	 * $fromFile  要复制谁
	 * $toFile    复制到那
	 */
	private function _copy_file($fromFile, $toFile){
		$this->_create_folder($toFile);
		$folder1 = opendir($fromFile);
		while ($f1 = readdir($folder1)) {
			if ($f1 != "." && $f1 != "..") {
				$path2 = "{$fromFile}/{$f1}";
				if (is_file($path2)) {	
					$file = $path2;
					$newfile = "{$toFile}/{$f1}";
					@copy($file, $newfile);
				} elseif (is_dir($path2)) {
					$toFiles = $toFile.'/'.$f1;
					$this->_copy_file($path2, $toFiles);
				}
			}
		}
	}
	
	/**
	 * 递归创建文件夹
	 */
	private function _create_folder($dir, $mode = 0777){
		if (is_dir($dir) || @mkdir($dir, $mode)) {
			return true;
		}	
		if (!$this->_create_folder(dirname($dir), $mode)) {
			return false;
		}
		return @mkdir($dir, $mode);
	}
}