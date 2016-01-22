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
 
class Api extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 会员登录信息JS调用
     */
    public function userinfo() {
        ob_start();
        $this->template->display('api.html');
        $html = ob_get_contents();
        ob_clean();
        $html = addslashes(str_replace(array("\r", "\n", "\t", chr(13)), array('', '', '', ''), $html));
        echo 'document.write("'.$html.'");';
    }

    /**
     * 自定义信息JS调用
     */
    public function template() {
        $this->api_template();
    }

    /**
	 * 更新浏览数
	 */
	public function hits() {
	
	    $id = (int)$this->input->get('id');
	    $dir = $this->input->get('module', TRUE);
		$name = 'hits'.$dir.SITE_ID.$id;
		$hits = (int)$this->get_cache_data($name);
        if (!is_file(FCPATH.'cache/data/module-'.SITE_ID.'-'.$dir.'.cache.php')) {
            exit;
        }
		
		if (!$hits) {
			$data = $this->site[SITE_ID]
						 ->where('id', $id)
						 ->select('hits')
						 ->limit(1)
						 ->get($this->db->dbprefix(SITE_ID.'_'.$dir))
						 ->row_array();
			$hits = (int)$data['hits'];
		}
		
		$hits++;
		$this->set_cache_data($name, $hits, (int)$this->get_cache('module-'.SITE_ID.'-'.$dir, 'setting', 'show_cache'));
		
		$this->site[SITE_ID]
			 ->where('id', $id)
			 ->update($this->db->dbprefix(SITE_ID.'_'.$dir), array('hits' => $hits));

		exit("document.write('$hits');");
	}
	
	/**
	 * 发送桌面快捷方式
	 */
	public function desktop() {
		
		$site = (int)$this->input->get('site');
		$module = $this->input->get('module');
		
		if ($site && !$module) {
			$url = $this->SITE[$site]['SITE_URL'];
			$name = $this->SITE[$site]['SITE_NAME'].'.url';
		} elseif ($site && $module) {
			$mod = $this->get_cache('module-'.$site.'-'.$module);
			$url = $mod['url'];
			$name = $mod['name'].'.url';
		}  else {
			$url = $this->SITE[SITE_ID]['SITE_URL'];
			$name = $this->SITE[SITE_ID]['SITE_NAME'].'.url';
		}
		
		$data = "
		[InternetShortcut]
		URL={$url}
		IconFile={$url}favicon.ico
		Prop3=19,2
		IconIndex=1
		";
		$mime = 'application/octet-stream';
		
		header('Content-Type: "' . $mime . '"');
		header('Content-Disposition: attachment; filename="' . $name . '"');
		header("Content-Transfer-Encoding: binary");
		header('Expires: 0');
		header('Pragma: no-cache');
		header("Content-Length: " . strlen($data));
		echo $data;
	}
	
	/**
	 * 伪静态测试
	 */
	public function test() {
		header('Content-Type: text/html; charset=utf-8');
		echo '服务器支持伪静态';
	}
	
	/**
	 * 自定义数据调用（老版本）
	 */
	public function data() {
	
		// 安全认证码
		$auth = $this->input->get('auth');
		if ($auth != SYS_KEY) {
			// 安全认证码不正确
			$data = array('error' => '安全认证码不正确');
		} else {
			// 解析数据
			$data = $this->template->list_tag($this->input->get('param'));
		}
		
		// 接收参数
		$format = $this->input->get('format');
		$callback = $this->input->get('callback');
		
		// 页面输出
		if ($format == 'xml') {
			header('Content-Type: text/xml');
			echo dr_array2xml($data, FALSE);
		} else {
			echo json_encode($data);
		}
	}
	
	/**
	 * 自定义数据调用（新版本）
	 */
	public function data2() {
	
		// 安全认证码
		$auth = $this->input->get('auth');
		if ($auth != md5(SYS_KEY)) {
			// 安全认证码不正确
			$data = array('error' => '安全认证码不正确');
		} else {
			// 解析数据
            $param = $this->input->get('param');
            if ($param == 'login') {
                // 登录认证
                $code = $this->member_model->login($this->input->get('username'), $this->input->get('password'), 0, 1);
                if (is_array($code)) {
                    $data = $this->member_model->get_member($code['uid']);
                } elseif ($code == -1) {
                    $data = array('error' => lang('m-003'));
                } elseif ($code == -2) {
                    $data = array('error' => lang('m-004'));
                } elseif ($code == -3) {
                    $data = array('error' => lang('m-005'));
                } elseif ($code == -4) {
                    $data = array('error' => lang('m-006'));
                }
            } else {
                // list数据查询
                $data = $this->template->list_tag($param);
            }
		}
		
		// 接收参数
		$format = $this->input->get('format');
		
		// 页面输出
		if ($format == 'xml') {
			header('Content-Type: text/xml');
			echo dr_array2xml($data, FALSE);
		} elseif ($format == 'jsonp') {
			echo $this->input->get('callback').'('.json_encode($data).')';
		} else {
			echo json_encode($data);
		}
	}
}
