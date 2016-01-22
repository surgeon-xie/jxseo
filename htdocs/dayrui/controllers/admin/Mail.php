<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.5
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Mail extends M_Controller {

    private $cache_file;

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->template->assign('menu', $this->get_menu(array(
		    lang('190') => 'admin/mail/index',
		    lang('206') => 'admin/mail/add_js',
		    lang('325') => 'admin/mail/send',
		    lang('112') => 'admin/mail/log',
		)));
        // 缓存文件名称
        $this->cache_file = md5('sendmail' . $this->uid . $this->input->ip_address() . $this->input->user_agent());
    }
	
	/**
     * 管理
     */
    public function index() {
	
		if (IS_POST) {
		
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) {
                exit(dr_json(0, lang('013')));
            }

            if ($this->input->post('action') == 'del') {
                // 删除邮件配置
                if (!$this->is_auth('admin/mail/del')) {
                    exit(dr_json(0, lang('160')));
                }
                $this->db
                     ->where_in('id', $ids)
                     ->delete('mail_smtp');
            } else {
                // 更新排序号
                if (!$this->is_auth('admin/mail/edit')) {
                    exit(dr_json(0, lang('160')));
                }
                $data = $this->input->post('data');
                foreach ($ids as $id) {
                    $this->db
                         ->where('id', $id)
                         ->update('mail_smtp', array('displayorder' => (int)$data[$id]));
                }
            }

			$this->cache(1);
			exit(dr_json(1, lang('014')));
		}
		
		$this->template->assign(array(
			'list' => $this->db
                           ->order_by('displayorder asc')
						   ->get('mail_smtp')
						   ->result_array(),
		));
		$this->template->display('mail_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
	
		if (IS_POST) {
			
			$data = $this->input->post('data', TRUE);
			$data['port'] = (int)$data['port'];
			$data['displayorder'] = 0;

			$this->db->insert('mail_smtp', $data);
			$this->cache(1);
			
			exit(dr_json(1, lang('014'), ''));
		}
		
		$this->template->display('mail_add.html');
    }

	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get('mail_smtp')
					 ->row_array();
		if (!$data) {
            exit(lang('019'));
        }
		
		if (IS_POST) {
		
			$data = $this->input->post('data', TRUE);
			$data['port'] = (int)$data['port'];
			if ($data['pass'] == '******') {
                unset($data['pass']);
            }
			
			$this->db
				 ->where('id', $id)
				 ->update('mail_smtp', $data);
			$this->cache(1);
			
			exit(dr_json(1, lang('014'), ''));
		}
		
		$this->template->assign(array(
			'data' => $data,
        ));
		$this->template->display('mail_add.html');
    }
	
	/**
     * 发送
     */
    public function send() {
	
		$this->template->display('mail_send.html');
    }
	
	/**
     * 发送请求
     */
    public function ajaxsend() {

        $i = $j = 0;
		$all = $this->input->post('is_all');
		$data = $this->input->post('data', true);
		$mail = $data['mail'];

        switch ($all) {

            case 1:
                if ($data['mails']) {
                    $mail = str_replace(array(PHP_EOL, chr(13), chr(10)), ',', $data['mails']);
                    $mail = str_replace(',,', ',', $mail);
                    $mail = trim($mail, ',');
                }
                if (!$mail) {
                    exit(dr_json(0, lang('328')));
                }
                $mail = @explode(',', $mail);
                if (!$data['title'] || !$data['message']) {
                    exit(dr_json(0, lang('329')));
                }
                foreach ($mail as $tomail) {
                    if ($this->member_model->sendmail($tomail, $data['title'], $data['message'])) {
                        $i ++;
                    } else {
                        $j ++;
                    }
                }
                exit(dr_json(1, dr_lang('327', $i, $j)));
                break;

            case 2:
                if (!$data['title'] || !$data['message']) {
                    $this->admin_msg(lang('329'), dr_url('mail/send'));
                }
                $data['total'] = $data['groupid'] ?
                    $this->db->where('groupid', $data['groupid'])->count_all_results('member') :
                    $this->db->count_all_results('member');
                if (!$data['total']) {
                    $this->admin_msg(lang('347'), dr_url('mail/send'));
                }
                // 保存缓存文件
                $this->cache->file->save($this->cache_file, $data, 36000);
                $this->admin_msg(dr_lang('348', $data['total'], '...'), dr_url('mail/member'), 2);
                break;

            default:
                if (!$data['mail']) {
                    exit(dr_json(0, lang('328')));
                }
                if (!$data['title'] || !$data['message']) {
                    exit(dr_json(0, lang('329')));
                }
                if ($this->member_model->sendmail($data['mail'], $data['title'], $data['message'])) {
                    $i ++;
                } else {
                    $j ++;
                }
                exit(dr_json(1, dr_lang('327', $i, $j)));
                break;
        }
    }

    /**
     * 发送给会员
     */
    public function member() {

        $data = $this->cache->file->get($this->cache_file);
        if (!$data) {
            $this->admin_msg(lang('134'));
        }

        $page = max((int)$this->input->get('page'), 1);
        $psize = 5;
        $tpage = ceil($data['total']/$psize);
        if ($data['groupid']) {
            $this->db->where('groupid', $data['groupid']);
        }
        $member = $this->db
                       ->select('email')
                       ->order_by('uid desc')
                       ->limit($psize, $psize * ($page - 1))
                       ->get('member')
                       ->result_array();
        if ($member) {
            foreach ($member as $t) {
                $this->member_model->sendmail($t['email'], $data['title'], $data['message']);
            }
            $this->admin_msg(
                dr_lang('348', $data['total'], $tpage.'/'.$page),
                dr_url('mail/member', array('page' => $page + 1)),
                2,1
            );
        } else {
            $this->cache->file->delete($this->cache_file);
            $this->admin_msg(lang('000'), dr_url('mail/send'), 1);
        }

    }
	
	/**
     * 日志
     */
    public function log() {
	
		if (IS_POST) {
			@unlink(FCPATH.'cache/mail_error.log');
			exit(dr_json(1, lang('000')));
		}
		
		$data = $list = array();
		$file = @file_get_contents(FCPATH.'cache/mail_error.log');
		if ($file) {
			$data = explode(PHP_EOL, $file);
			$data = $data ? array_reverse($data) : array();
			unset($data[0]);
			$page = max(1, (int)$this->input->get('page'));
			$limit = ($page - 1) * SITE_ADMIN_PAGESIZE;
			$i = $j = 0;
			foreach ($data as $v) {
				if ($i >= $limit && $j < SITE_ADMIN_PAGESIZE) {
					$list[] = $v;
					$j ++;
				}
				$i ++;
			}
		}
		
		$total = count($data);
		$this->template->assign(array(
			'list' => $list,
			'total' => $total,
			'pages'	=> $this->get_pagination(dr_url('mail/log'), $total)
		));
		$this->template->display('mail_log.html');
    }
	
	/**
     * test
     */
    public function test() {
	
		$id = (int)$this->input->get('id');
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get('mail_smtp')
					 ->row_array();
		if (!$data) {
            exit(lang('019'));
        }
		
		$this->load->library('Dmail');
		$this->dmail->set(array(
			'host' => $data['host'],
			'user' => $data['user'],
			'pass' => $data['pass'],
			'port' => $data['port'],
			'from' => $data['user']
		));
		
		if ($this->dmail->send(SYS_EMAIL, 'test', 'test for '.SITE_NAME)) {
			echo 'ok';
		} else {
			echo 'Error: '.$this->dmail->error();
		}
	}
    
    /**
     * 缓存
     */
    public function cache($update = 0) {
	    $this->system_model->email();
		((int)$_GET['admin'] || $update) or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
	}
}