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

require FCPATH.'dayrui/core/D_Common.php';

class M_Controller extends D_Common {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }

    // 系统体检选项
    protected function _get_step() {
        return array(
            1  => '_cookie_code',
            2  => '_admin_file',
            3  => '_dir_write',
            4  => '_template_theme',
            5  => '_url_fopen',
            6  => '_curl_init',
            7  => '_fsockopen',
            8  => '_php',
            9  => '_mysql',
            10 => '_email',
            11 => '_memcache',
            12 => '_mcryp',
            13 => '_tableinfo',
            14 => '_unzip',
            15 => '_category',
            16 => '_openssl_open',
            17 => '_gzinflate',
            18 => '_ini_get',
            19 => '_template',
            20 => '_upload',
            21 => '_domain',
            98 => '_version',
            99 => '_result'
        );
    }

    public function aaa() {
        echo 'This is FineCMS';
    }
}