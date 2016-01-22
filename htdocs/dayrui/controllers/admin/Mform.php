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
	
class Mform extends M_Controller {

	public $mid;
	public $dir;
	public $link;

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('mform_model');
        $this->load->model('module_model');
		$this->dir = $this->input->get('dir');
        $module = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
		if (!$module) {
            $this->admin_msg(lang('100'));
        }
        $this->mid = $module['id'];
		$this->template->assign('menu', $this->get_menu(array(
            lang('073') => 'admin/module/index',
		    dr_lang('330', $module['name']) => 'admin/mform/index/dir/'.$this->dir,
		    lang('add') => 'admin/mform/add/dir/'.$this->dir,
		)));
        $this->template->assign(array(
            'dir' => $this->dir,
            'mid' => $this->mid,
        ));
    }
	
	/**
     * 管理
     */
    public function index() {
		$this->template->assign(array(
			'list' => $this->db->where('module', $this->dir)->get('module_form')->result_array()
		));
		$this->template->display('mform_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
	
		if (IS_POST) {
            $data = $this->input->post('data');
			$result = $this->mform_model->add($this->dir, $data);
			if ($result === FALSE) {
                $this->clear_cache('module');
				$this->admin_msg(lang('000'), dr_url('mform/index', array('dir' => $this->dir)), 1);
			} else {
				$this->admin_msg($result);
			}
		}
		
		$this->template->assign(array(
			'data' => array()
		));
		$this->template->display('mform_add.html');
    }
	
	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->db
					 ->where('id', $id)
					 ->get('module_form')
					 ->row_array();
		if (!$data) {
            $this->admin_msg(lang('019'));
        }
		
		if (IS_POST) {
			$data = $this->input->post('data');
			$this->db->where('id', $id)->update('module_form', array(
				'name' => $data['name'],
				'setting' => dr_array2string($data['setting']),
				'permission' => dr_array2string($data['permission']),
			));
            $this->clear_cache('module');
            $this->admin_msg(lang('000'), dr_url('mform/index', array('dir' => $this->dir)), 1);
		}
		
		$data['setting'] = dr_string2array($data['setting']);
		$data['permission'] = dr_string2array($data['permission']);
		
		$this->template->assign(array(
			'data' => $data,
		));
		$this->template->display('mform_add.html');
    }
	
	/**
     * 禁用/可用
     */
    public function disabled() {
		if ($this->is_auth('mform/edit')) {
			$id = (int)$this->input->get('id');
			$data = $this->db
						 ->select('disabled')
						 ->where('id', $id)
						 ->get('module_form')
						 ->row_array();
			$value = $data['disabled'] == 1 ? 0 : 1;
			$this->db->where('id', $id)->update('module_form', array('disabled' => $value));
		}
        $this->clear_cache('module');
		exit(dr_json(1, lang('014')));
    }

	/**
     * 删除
     */
    public function del() {
		$this->mform_model->del((int)$this->input->get('id'), $this->dir);
        $this->clear_cache('module');
        $this->admin_msg(lang('000'), dr_url('mform/index', array('dir' => $this->dir)), 1);
	}

}