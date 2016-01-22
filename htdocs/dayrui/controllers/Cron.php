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
 
class Cron extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->output->enable_profiler(FALSE);
    }
	
    /**
     * 执行任务和队列
     */
    public function index() {

        // 第三方执行队列时，非命令行不执行
		if (SYS_CRON_QUEUE && !(PHP_SAPI === 'cli' || defined('STDIN'))) {
            exit();
        }

        // 自动更新模块缓存（3小时一次）
        $file = FCPATH.'cache/auto.log';
        $auto = is_file($file) ? (int)file_get_contents($file) : 0;
        if (!$auto || $auto <= SYS_TIME - 10800) {
            $this->clear_cache('module');
            file_put_contents($file, SYS_TIME);
        }

        // 在线人数清理
        if (SYS_ONLINE_NUM) {
            $this->db->query('delete from '.$this->db->dbprefix('member_session').' where session_id not in (select t.session_id from (select session_id from '.$this->db->dbprefix('member_session').' order by last_activity desc limit '.SYS_ONLINE_NUM.') as t)');
        }

        // 未到发送时间
		if (get_cookie('cron')) {
            exit();
        }

        // 一次执行的任务数量
		$pernum = defined('SYS_CRON_NUMS') && SYS_CRON_NUMS ? SYS_CRON_NUMS : 10;

        // 用户每多少秒调用本程序
		set_cookie('cron', 1, SYS_CRON_TIME);

        // 查询所有队列记录
		$queue = $this->db
					  ->order_by('status ASC,id ASC')
					  ->limit($pernum)
					  ->get('cron_queue')
					  ->result_array();
		if (!$queue) {
            // 所有任务执行完毕
			$this->db->query('TRUNCATE `'.$this->db->dbprefix('cron_queue').'`');
			exit();
		}
		
		foreach ($queue as $data) {
			$this->cron_model->execute($data);
		}

        // 本次任务执行完毕
		exit();
	}
}