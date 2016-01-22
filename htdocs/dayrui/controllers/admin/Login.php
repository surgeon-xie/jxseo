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
	
class Login extends M_Controller {
    
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        $this->output->enable_profiler(FALSE);
    }
    
    public function index() {

        $id = 0;
        $error = '';

		if (IS_POST) {
			if (get_cookie('admin_login')) {
                $error = lang('167');
            } elseif (SITE_ADMIN_CODE && !$this->check_captcha('code')) {
                $error = lang('168');
            } else {
                $uid = $this->member_model->admin_login(
                    $this->input->post('username', TRUE),
                    $this->input->post('password', TRUE)
                );
                if ($uid > 0) {
                    $url = $this->input->get('backurl') ? urldecode($this->input->get('backurl')) : dr_url('home');
                    $url = pathinfo($url);
                    $url = $url['basename'] ? $url['basename'] : dr_url('home/index');
                    $this->admin_msg(lang('042'), $url, 1);
                }
                if ($uid == -1) {
                    $id = 1;
                    $error = lang('043');
                } elseif ($uid == -2) {
                    $error = lang('044');
                } elseif ($uid == -3) {
                    $error = lang('045');
                } elseif ($uid == -4) {
                    $error = lang('046');
                } else {
                    $error = lang('047');
                }
            }
		}
		
		$this->template->assign('id', $id);
		$this->template->assign('error', $error);
		$this->template->assign('username', $this->member['username']);
		$this->template->display('login.html');
    }
	
	public function logout() {
		$this->session->unset_userdata('admin');
		$this->session->unset_userdata('siteid');
		$this->admin_msg(lang('048'), dr_url(''), 1);
	}
}