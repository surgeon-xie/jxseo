<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since	version 2.3.7
 * @author	Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
class D_Member_Home extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 管理
     */
    public function index() {

        if (IS_POST) {
            // 判断id是否为空
            $ids = $this->input->post('ids', TRUE);
            if (!$ids) {
                exit(dr_json(0, lang('019')));
            }
            if ($this->input->post('action') == 'update') {
                // 虚拟币检查
                if ($this->member_rule['update_score'] + $this->member['score'] < 0) {
                    exit(dr_json(0, dr_lang('mod-08', abs($this->member_rule['update_score']), $this->member['score'])));
                }
                // 积分检查
                $this->member_model->update_score(0, $this->uid, (int) $this->member_rule['update_experience'], '', "lang,m-150");
                // 虚拟币
                $this->member_model->update_score(1, $this->uid, (int) $this->member_rule['update_score'], '', "lang,m-150");
                // 更新文档时间
                $this->content_model->updatetime($ids);
                exit(dr_json(1, lang('mod-12')));
            } else {
                $i = (int) $this->input->post('flag');
                if (!isset($this->flag[$i])) {
                    exit(dr_json(0, lang('mod-14')));
                }
                $count = count($ids);
                $value = abs($this->flag[$i][$this->markrule] * $count);
                // 虚拟币检查
                if ($this->member['score'] - $value < 0) {
                    exit(dr_json(0, dr_lang('mod-11', $value, $this->member['score'])));
                }
                $total = $this->content_model->flag($ids, $i);
                if ($total) {
                    // 虚拟币
                    $value = abs($this->flag[$i][$this->markrule] * $total);
                    $this->member_model->update_score(1, $this->uid, -$value, '', "lang,m-181," . $total);
                    exit(dr_json(1, lang('000')));
                }
                exit(dr_json(0, lang('mod-13')));
            }
        }

        $kw = $this->input->get('kw', TRUE);
        $catid = (int) $this->input->get('catid');
        $order = dr_get_order_string(isset($_GET['order']) && strpos($_GET['order'], "undefined") !== 0 ? $this->input->get('order', TRUE) : 'updatetime desc', 'updatetime desc');
        $this->link->where('uid', $this->uid)->where_in('catid', $this->catid)->where('status', 9);

        // 模块表单嵌入
        $form = array();
        $data = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'form');
        if ($data) {
            foreach ($data as $t) {
                if (!$t['permission'][$this->markrule]['disabled']) {
                    $form[] = array(
                        'url' => dr_url(APP_DIR.'/form_'.SITE_ID.'_'.$t['table'].'/listc'),
                        'name' => $t['name'],
                    );
                }
            }
        }

        // 搜索关键字
        if ($kw) {
            $this->link->like('title', $kw);
        }
        // 搜索栏目
        if ($catid) {
            $this->link->where_in('catid', explode(',', $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid, 'childids')));
        }
        // 排序
        $this->link->order_by($order);
        if ($this->input->get('action') == 'search') {
            // ajax搜索数据
            $page = max((int) $this->input->get('page'), 1);
            $data = $this->link
                         ->limit($this->pagesize, $this->pagesize * ($page - 1))
                         ->get($this->content_model->prefix)
                         ->result_array();
            if (!$data) {
                exit('null');
            }
            $this->template->assign(array(
                'kw' => $kw,
                'form' => $form,
                'list' => $data,
            ));
            $this->template->display('content_data.html');
        } else {
            $url = 'index.php?s='.APP_DIR.'&c=home&m=index&action=search&catid='.$catid.'&order='.$order.'&kw='.$kw;
            $this->template->assign(array(
                'kw' => $kw,
                'list' => $this->link
                              ->limit($this->pagesize)
                              ->get($this->content_model->prefix)
                              ->result_array(),
                'form' => $form,
                'order' => $order,
                'extend' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'extend'),
                'select' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'), $catid, 'id=\'dr_catid\' name=\'catid\'', ' -- ', 1, 1),
                'moreurl' => $url,
                'flagdata' => $this->flag,
                'meta_name' => lang('mod-01'),
                'searchurl' => $url,
            ));
            $this->template->display('content_index.html');
        }
    }

    /**
     * 推荐位
     */
    public function flag() {

        $id = (int) $this->input->get('id');
        $flag = $this->flag;
        if ($flag && !$id) {
            foreach ($flag as $i => $t) {
                if (isset($t[$this->member['mark']])
                    && $t[$this->member['mark']] && $t['name']) {
                    if (!$id) {
                        $id = $i;
                    }
                }
            }
        }

        // 判断权限
        if (!isset($flag[$id])) {
            $this->member_msg(lang('mod-14'));
        }

       // $name = $flag[$id]['name'];
       // $score = $flag[$id][$this->member['mark']];

        if (IS_POST) {
            // 判断id是否为空
            $ids = $this->input->post('ids', TRUE);
            if (!$ids) {
                exit(dr_json(0, lang('019')));
            }
            if ($this->input->post('action') == 'update') {
                // 虚拟币检查
                if ($this->member_rule['update_score'] + $this->member['score'] < 0) {
                    exit(dr_json(0, dr_lang('mod-08', abs($this->member_rule['update_score']), $this->member['score'])));
                }
                // 积分检查
                $this->member_model->update_score(0, $this->uid, (int) $this->member_rule['update_experience'], '', "lang,m-150");
                // 虚拟币
                $this->member_model->update_score(1, $this->uid, (int) $this->member_rule['update_score'], '', "lang,m-150");
                // 更新文档时间
                $this->content_model->updatetime($ids);
                exit(dr_json(1, lang('mod-12')));
            } else {
                $this->link
                     ->where_in('id', $ids)
                     ->where('flag', $id)
                     ->where('uid', $this->uid)
                     ->delete($this->content_model->prefix.'_flag');
                exit(dr_json(1, lang('000')));
                exit;
            }
        }

        $data = $this->link
                     ->from($this->content_model->prefix.'_flag')
                     ->join($this->content_model->prefix, $this->content_model->prefix.'.id='.$this->content_model->prefix.'_flag.id', 'left')
                     ->where($this->content_model->prefix.'_flag.uid', $this->uid)
                     ->where($this->content_model->prefix.'_flag.flag', $id)
                     ->get()
                     ->result_array();

        $this->template->assign(array(
            'id' => $id,
            'flag' => $flag,
            'list' => $data,
        ));
        $this->template->display('content_flag.html');
    }

    /**
     * 发布
     */
    public function add() {

        $did = (int)$this->input->get('did');
        $catid = (int)$this->input->get('catid');

        $module = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR);
        if (!$this->_module_post_catid($module)) {
            $this->member_msg(lang('mod-15'));
        }

        // 栏目选择判断
        $isselect = isset($module['setting']['postselect']) && $module['setting']['postselect'] ? 0 : 1;
        if (!$isselect && ($catid == 0 || $module['category'][$catid]['child'])) {
            $level = $module['category_level'] + 1;
            $cpids = substr($module['category'][$catid]['pids'], 2);
            $value = !$cpids ? '' : '["'.str_replace(',', '","', $cpids).'","'.$catid.'"]';
            $select = '';
            for ($i = 1; $i <= $level; $i++) {
                $select.= '<ul iid='.($i - 1).' class="sortList_step finecms-select-category" '.($i > 1 ? 'style="display:none"' : '').'></ul>';
            }
            $select.= '
			<script type="text/javascript">
			$(function(){
				var $ld5 = $(".finecms-select-category");					  
				$ld5.ld({
					ajaxOptions:{"url":memberpath+"index.php?c=api&m=category&module='.APP_DIR.'"},
					defaultParentId:0,
					drevent:"click"
				});	 
				var ld5_api = $ld5.ld("api");
				ld5_api.selected(' . $value . ');
			})
			</script>';
            $this->template->assign(array(
                'select' => $select,
                'category' => $this->select_category($module['category'], $catid, 'id=\'dr_catid\' name=\'catid\'', '', 1, 1),
                'meta_name' => lang('cat-00')
            ));
            $this->template->display('content_select.html');
            exit;
        }

        // 可用字段
        $field = $this->_get_member_field($catid);

        // 初始化参数
        $error = $data = array();

        // 提交操作
        if (IS_POST) {
            // 栏目参数
            $catid = (int)$this->input->post('catid');
            $catid = $catid ? $catid : (int)$this->input->get('catid');
            // 发布权限判断
            if (!$this->module_rule[$catid]['add']) {
                $this->member_msg(lang('mod-15'));
            }
            // 日投稿上限检查
            if ($this->uid && $this->module_rule[$catid]['postnum']) {
                $total = $this->link
                              ->where('uid', $this->uid)
                              ->where('DATEDIFF(from_unixtime(inputtime),now())=0')
                              ->where('catid', $catid)
                              ->count_all_results($this->content_model->prefix.'_index');
                if ($total >= $this->module_rule[$catid]['postnum']) {
                    $this->member_msg(dr_lang('mod-16', $this->module_rule[$catid]['postnum']));
                }
            }
            // 投稿总数检查
            if ($this->uid && $this->module_rule[$catid]['postcount']) {
                $total = $this->link
                              ->where('uid', $this->uid)
                              ->where('catid', $catid)
                              ->count_all_results($this->content_model->prefix.'_index');
                if ($total >= $this->module_rule[$catid]['postcount']) {
                    $this->member_msg(dr_lang('mod-17', $this->module_rule[$catid]['postcount']));
                }
            }
            // 虚拟币检查
            if ($this->uid && $this->module_rule[$catid]['score'] + $this->member['score'] < 0) {
                $this->member_msg(dr_lang('mod-09', abs($this->module_rule[$catid]['score']), $this->member['score']));
            }
            // 字段验证与过滤
            $cat = $module['category'][$catid];
            $field = $cat['field'] ? array_merge($field, $cat['field']) : $field;
            // 设置uid便于校验处理
            $_POST['data']['id'] = $id;
            $_POST['data']['uid'] = $this->uid;
            $_POST['data']['author'] = $this->member['username'];
            $_POST['data']['inputtime'] = $_POST['data']['updatetime'] = SYS_TIME;
            $data = $this->validate_filter($field);

            // 验证出错信息
            if (isset($data['error'])) {
                $error = $data;
                $data = $this->input->post('data', TRUE);
            } elseif (!$catid) {
                $data = $this->input->post('data', TRUE);
                $error = array('error' => 'catid', 'msg' => lang('cat-22'));
            } elseif ($cat['child']) {
                $data = $this->input->post('data', TRUE);
                $error = array('error' => 'catid', 'msg' => lang('mod-18'));
            } else {
                // 设定文档默认值
                $data[1]['uid'] = $this->uid;
                $data[1]['hits'] = 0;
                $data[1]['catid'] = $catid;
                $data[1]['status'] = !$this->uid || $this->module_rule[$catid]['verify'] ? 1 : 9;
                $data[1]['inputip'] = $this->input->ip_address();
                $data[1]['author'] = $this->member['username'] ? $this->member['username'] : 'guest';
                $data[1]['inputtime'] = $data[1]['updatetime'] = SYS_TIME;
                // 保存为草稿
                if ($this->input->post('action') == 'draft') {
                    $this->clear_cache('save_'.APP_DIR.'_'.$this->uid);
                    $id = $this->content_model->save_draft($did, $data, 0);
                    $this->attachment_handle($this->uid, $this->content_model->prefix.'_draft-'.$id, $field);
                    if (IS_AJAX) {
                        exit(dr_json(0, lang('m-229'), dr_url(APP_DIR.'/home/draft/')));
                    }
                    $this->member_msg(lang('m-229'), dr_url(APP_DIR.'/home/draft/'), 1);
                    exit;
                }
                // 数据来至草稿时更新时间
                if ($did) {
                    $data[1]['updatetime'] = $data[1]['inputtime'] = SYS_TIME;
                }
                // 发布文档
                if (($id = $this->content_model->add($data)) != FALSE) {
                    // 发布草稿时删除草稿数据
                    if ($did && $this->content_model->delete_draft($did, 'cid=0 and eid=0')) {
                        $this->attachment_replace_draft($did, $id, 0, $this->content_model->prefix);
                    } else {
                        $this->clear_cache('save_'.APP_DIR.'_'.$this->uid);
                    }
                    // 发布文档后执行
                    $this->_post($id, $data);
                    if ($data[1]['status'] == 9) { // 审核通过
                        $mark = $this->content_model->prefix.'-'.$id;
                        // 积分处理
                        $experience = (int) $this->module_rule[$catid]['experience'];
                        if ($experience) {
                            $this->member_model->update_score(0, $this->uid, $experience, $mark, "lang,m-151,{$cat['name']}", 1);
                        }
                        // 虚拟币处理
                        $score = (int) $this->module_rule[$catid]['score'];
                        if ($score) {
                            $this->member_model->update_score(1, $this->uid, $score, $mark, "lang,m-151,{$cat['name']}", 1);
                        }
                        // 附件归档到文档
                        $this->attachment_handle($this->uid, $mark, $field);
                        $this->attachment_replace($this->uid, $id, $this->content_model->prefix);
                        if (IS_AJAX) {
                            exit(dr_json(1, lang('m-340'), dr_member_url(APP_DIR.'/home/index')));
                        }
                        $this->template->assign(array(
                            'url' => SITE_URL.APP_DIR.'/index.php?c=show&id='.$id,
                            'add' => dr_member_url(APP_DIR.'/home/add', array('catid' => $catid)),
                            'edit' => 0,
                            'html' => MODULE_HTML ? dr_module_create_show_file($id).dr_module_create_list_file($catid) : '',
                            'list' => $this->member['uid'] ? dr_member_url(APP_DIR . '/home/index') : SITE_URL.APP_DIR.'/index.php?c=category&id='.$catid,
                            'catid' => $catid,
                            'meta_name' => lang('mod-19')
                        ));
                        $this->template->display('success.html');
                    } else {
                        $this->attachment_handle($this->uid, $this->content_model->prefix.'_verify-'.$id, $field);
                        if (IS_AJAX) {
                            exit(dr_json(1, lang('m-341'), dr_member_url(APP_DIR.'/verify/index')));
                        }
                        $this->template->assign(array(
                            'url' => dr_member_url(APP_DIR.'/verify/index'),
                            'add' => dr_member_url(APP_DIR.'/home/add', array('catid' => $catid)),
                            'edit' => 0,
                            'list' => $this->member['uid'] ? dr_member_url(APP_DIR.'/home/index') : SITE_URL.APP_DIR.'/index.php?c=category&id='.$catid,
                            'catid' => $catid,
                            'meta_name' => lang('mod-19')
                        ));
                        $this->template->display('verify.html');
                    }
                    exit;
                }
            }
            if (IS_AJAX) {
                exit(dr_json(0, $error['msg'], $error['error']));
            }
            unset($data['id']);
        } else {
            if ($did) {
                $temp = $this->content_model->get_draft($did);
                if ($temp['draft']['cid'] == 0 && $temp['draft']['eid'] == 0 ) {
                    $data = $temp;
                }
            } else {
                $data = $this->get_cache_data('save_'.APP_DIR.'_'.$this->uid);
            }
            $catid = $data['catid'] ? $data['catid'] : $catid;
            // 栏目id不存在时就去第一个可用栏目为catid
            if (!$catid) {
                list($select, $catid) = $this->select_category($module['category'], 0, 'id=\'dr_catid\' name=\'catid\' onChange="show_category_field(this.value)"', '', 1, 1, 1);
            } else {
                $select = $this->select_category($module['category'], $catid, 'id=\'dr_catid\' name=\'catid\' onChange="show_category_field(this.value)"', '', 1, 1);
            }
        }

        $backurl = str_replace(MEMBER_URL, '', $_SERVER['HTTP_REFERER']);
        $this->template->assign(array(
            'did' => $did,
            'purl' => dr_url(APP_DIR.'/home/add', array('catid' => $catid)),
            'catid' => $catid,
            'error' => $error,
            'verify' => 0,
            'select' => $select,
            'myfield' => $this->new_field_input($field, $data, TRUE),
            'listurl' => $backurl ? $backurl : dr_url(APP_DIR.'/home/index'),
            'isselect' => $isselect,
            'meta_name' => lang('mod-02'),
            'draft_url' => MEMBER_URL.dr_url(APP_DIR.'/home/add', array('catid' => $catid)),
            'draft_list' => $this->content_model->get_draft_list('cid=0 and eid=0'),
            'result_error' => $error,
            'category_field_url' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category_field') ? dr_url(APP_DIR.'/home/add') : ''
        ));
        $this->template->display('content_add.html');
    }

    /**
     * 修改
     */
    public function edit() {

        // 初始化参数
        $id = (int)$this->input->get('id');
        $did = (int)$this->input->get('did');
        $cid = (int)$this->input->get('catid');

        $data = $this->content_model->get($id);
        $error = array();
        $catid = $cid ? $cid : $data['catid'];

        // 数据是否存在
        if (!$data) {
            $this->member_msg(lang('019'));
        }
        // 禁止修改他人文档
        if ($data['author'] != $this->member['username'] && $data['uid'] != $this->member['uid']) {
            $this->member_msg(lang('mod-05'));
        }
        // 修改权限判断
        if (!$this->module_rule[$catid]['edit']) {
            $this->member_msg(lang('mod-20'));
        }
        // 可用字段
        $field = $this->_get_member_field($catid);
        $isedit = (int) $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid, 'setting', 'edit');

        // 保存修数据
        if (IS_POST) {
            $_data = $data;
            // 字段验证与过滤
            $catid = $isedit ? $catid : (int) $this->input->post('catid');
            // 修改权限判断
            if (!$this->module_rule[$catid]['edit']) {
                $this->member_msg(lang('mod-20'));
            }
            $cat = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid);
            $field = $cat['field'] ? array_merge($field, $cat['field']) : $field;
            // 设置uid便于校验处理
            $_POST['data']['id'] = $id;
            $_POST['data']['uid'] = $this->uid;
            $_POST['data']['author'] = $this->member['username'];
            $_POST['data']['inputtime'] = $data['inputtime'];
            $_POST['data']['updatetime'] = SYS_TIME;
            $data = $this->validate_filter($field, $_data);
            if (isset($data['error'])) {
                $error = $data;
                $data = $this->input->post('data', TRUE);
            } elseif (!$isedit && !$catid) {
                $data = $this->input->post('data', TRUE);
                $error = array('error' => 'catid', 'msg' => lang('cat-22'));
            } else {
                // 初始化数据
                $status = isset($data['status']) && $data['status'] ? 9 : ($this->module_rule[$catid]['verify'] ? 1 : 9);
                $status = isset($this->module_rule[$catid]['edit_verify']) && $this->module_rule[$catid]['edit_verify'] ? 9 : $status;
                $data[1]['uid'] = $this->uid;
                $data[1]['author'] = $this->member['username'];
                $data[1]['catid'] = $catid;
                $data[1]['status'] = $status;
                $data[1]['updatetime'] = SYS_TIME;
                // 保存为草稿
                if ($this->input->post('action') == 'draft') {
                    $data[1]['id'] = $data[0]['id'] = $id;
                    $id = $this->content_model->save_draft($did, $data, 0);
                    $this->attachment_handle($this->uid, $this->content_model->prefix.'_draft-'.$id, $field);
                    if (IS_AJAX) {
                        exit(dr_json(0, lang('m-229'), dr_url(APP_DIR.'/home/draft/')));
                    }
                    $this->admin_msg(lang('m-229'), dr_url(APP_DIR.'/home/draft/'), 1);
                    exit;
                }
                // 修改数据
                if ($this->content_model->edit($_data, $data)) {
                    // 发布草稿时删除草稿数据
                    if ($did && $this->content_model->delete_draft($did, 'cid='.$id.' and eid=0')) {
                        $this->attachment_replace_draft($did, $id, 0, $this->content_model->prefix);
                    }
                    $this->attachment_handle($this->uid, $this->content_model->prefix.'-'.$id, $field, $_data, $data[1]['status'] == 9 ? TRUE : FALSE);
                    if ($data[1]['status'] == 9) { // 审核通过
                        if (IS_AJAX) {
                            exit(dr_json(1, lang('m-340'), dr_member_url(APP_DIR.'/home/index')));
                        }
                        $this->template->assign(array(
                            'url' => SITE_URL.APP_DIR.'/index.php?c=show&id='.$id,
                            'add' => dr_member_url(APP_DIR.'/home/add', array('catid' => $catid)),
                            'edit' => 1,
                            'list' => dr_member_url(APP_DIR.'/home/index'),
                            'html' => MODULE_HTML ? dr_module_create_show_file($id).dr_module_create_list_file($catid) : '',
                            'catid' => $catid,
                            'meta_name' => lang('mod-03')
                        ));
                        $this->template->display('success.html');
                    } else {
                        if (IS_AJAX) {
                            exit(dr_json(1, lang('m-341'), dr_member_url(APP_DIR.'/verify/index')));
                        }
                        $this->template->assign(array(
                            'url' => dr_member_url(APP_DIR.'/verify/index'),
                            'add' => dr_member_url(APP_DIR.'/home/add', array('catid' => $catid)),
                            'edit' => 1,
                            'list' => dr_member_url(APP_DIR.'/home/index'),
                            'catid' => $catid,
                            'meta_name' => lang('mod-03')
                        ));
                        $this->template->display('verify.html');
                    }
                } else {
                    $this->member_msg(lang('mod-06'));
                }
                exit;
            }
            if (IS_AJAX) {
                exit(dr_json(0, $error['msg'], $error['error']));
            }
        } else {
            if ($did) {
                $temp = $this->content_model->get_draft($did);
                if ($temp['draft']['cid'] == $data['id'] && $temp['draft']['eid'] == 0) {
                    $temp['id'] = $id;
                    $data = $temp;
                    $catid = $temp['catid'] ? $temp['catid'] : $catid;
                }
            }
        }

        $backurl = str_replace(MEMBER_URL, '', $_SERVER['HTTP_REFERER']);
        $this->template->assign(array(
            'did' => $did,
            'purl' => dr_url(APP_DIR.'/home/add', array('id' => $id)),
            'data' => $data,
            'catid' => $catid,
            'error' => $error,
            'isedit' => $isedit,
            'select' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'), $catid, 'id=\'dr_catid\' name=\'catid\' onChange="show_category_field(this.value)"', '', 1, 1),
            'backurl' => $_SERVER['HTTP_REFERER'],
            'myfield' => $this->field_input($field, $data, TRUE),
            'listurl' => $backurl ? $backurl : dr_url(APP_DIR.'/home/index'),
            'meta_name' => lang('mod-21'),
            'draft_url' => MEMBER_URL.dr_url(APP_DIR.'/home/edit', array('id' => $id)),
            'draft_list' => $this->content_model->get_draft_list('cid='.$id.' and eid=0'),
            'result_error' => $error,
            'category_field_url' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category_field') ? dr_url(APP_DIR.'/home/edit', array('id' => $id, 'did' => $did)) : ''
        ));
        $this->template->display('content_add.html');
    }

    /**
     * 删除
     */
    public function del() {

        $id = (int) $this->input->post('id');
        $this->load->model('mform_model');
        $data = $this->link
                     ->where('id', $id)
                     ->where('uid', (int)$this->uid)
                     ->select('tableid,catid')
                     ->limit(1)
                     ->get($this->content_model->prefix)
                     ->row_array();
        // 删除权限判断
        if (!$data || !$this->module_rule[$data['catid']]['del']) {
            exit(dr_json(0, lang('mod-22')));
        }

        $this->content_model->delete_for_id($id, (int)$data['tableid']);

        exit(dr_json(1, lang('000')));
    }

    /**
     * 收藏的文档
     */
    public function favorite() {

        $table = $this->link->dbprefix(SITE_ID.'_'.APP_DIR.'_favorite_'.(int)substr((string) $this->uid, -1, 1));

        if (IS_POST) {
            // 判断id是否为空
            $ids = $this->input->post('ids', TRUE);
            if (!$ids) {
                exit(dr_json(0, lang('019')));
            }
            $this->link
                 ->where_in('id', $ids)
                 ->where('uid', $this->uid)
                 ->delete($table);
            exit(dr_json(1, lang('000')));
        }

        $page = max((int) $this->input->get('page'), 1);
        $data = $this->link
                     ->where('uid', $this->uid)
                     ->order_by('inputtime desc')
                     ->limit($this->pagesize, $this->pagesize * ($page - 1))
                     ->get($table)
                     ->result_array();

        if ($page == 1) {
            $this->template->assign(array(
                'list' => $data,
                'moreurl' => 'index.php?s='.APP_DIR.'&c=home&m=favorite'
            ));
            $this->template->display('favorite_index.html');
        } else {
            if (!$data) {
                exit('null');
            }
            $this->template->assign(array(
                'list' => $data,
            ));
            $this->template->display('favorite_data.html');
        }
    }

    /**
     * 购买的文档
     */
    public function buy() {

        $table = $this->link->dbprefix(SITE_ID.'_'.APP_DIR.'_buy_'.(int)substr((string) $this->uid, -1, 1));

        if (IS_POST) {
            // 判断id是否为空
            $ids = $this->input->post('ids', TRUE);
            if (!$ids) {
                exit(dr_json(0, lang('019')));
            }
            $this->link
                 ->where_in('id', $ids)
                 ->where('uid', $this->uid)
                 ->delete($table);
            exit(dr_json(1, lang('000')));
        }

        $page = max((int) $this->input->get('page'), 1);
        $data = $this->link
                     ->where('uid', $this->uid)
                     ->order_by('inputtime desc')
                     ->limit($this->pagesize, $this->pagesize * ($page - 1))
                     ->get($table)
                     ->result_array();

        if ($page == 1) {
            $this->template->assign(array(
                'list' => $data,
                'moreurl' => 'index.php?s='.APP_DIR.'&c=home&m=buy'
            ));
            $this->template->display('buy_index.html');
        } else {
            if (!$data) {
                exit('null');
            }
            $this->template->assign(array(
                'list' => $data,
            ));
            $this->template->display('buy_data.html');
        }
    }

    /**
     * 我的草稿箱
     */
    public function draft() {

        $table = $this->content_model->prefix.'_draft';

        if (IS_POST) {
            // 判断id是否为空
            $ids = $this->input->post('ids', TRUE);
            if (!$ids) {
                exit(dr_json(0, lang('019')));
            }
            $this->load->model('attachment_model');
            foreach ($ids as $id) {
                // 删除草稿记录
                if ($this->link->where('id', $id)->where('uid', $this->uid)->get($table)->row_array()) {
                    $this->link->where('id', $id)->delete($table);
                    // 删除表对应的附件
                    $this->attachment_model->delete_for_table($table.'-'.$id);
                }
            }
            exit(dr_json(1, lang('000')));
        }

        $page = max((int) $this->input->get('page'), 1);
        $data = $this->link
                     ->where('uid', $this->uid)
                     ->order_by('inputtime desc')
                     ->limit($this->pagesize, $this->pagesize * ($page - 1))
                     ->get($table)
                     ->result_array();
        if ($page == 1) {
            $this->template->assign(array(
                'list' => $data,
                'moreurl' => 'index.php?s='.APP_DIR.'&c=home&m=draft'
            ));
            $this->template->display('draft_index.html');
        } else {
            if (!$data) {
                exit('null');
            }
            $this->template->assign(array(
                'list' => $data,
            ));
            $this->template->display('draft_data.html');
        }
    }

    /**
     * Ajax调用栏目附加字段
     *
     * @return void
     */
    public function field() {
        $data = dr_string2array($this->input->post('data'));
        $field = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', (int) $this->input->post('catid'), 'field');
        if (!$field) {
            exit('');
        }
        echo $this->field_input($field, $data);
    }

    /**
     * 发布文档后执行的动作
     *
     * @return void
     */
    protected function _post($id, $data) {
        
    }

}
