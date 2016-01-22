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

class D_Admin_Extend extends M_Controller {

	public $catid; // 栏目参数id
	public $catrule; // 栏目权限规则
	public $content; // 内容数据
	protected $field; // 自定义字段+含系统字段
	protected $sysfield; // 系统字段
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$cid = (int)$this->input->get('cid');
		$catid = (int)$this->input->get('catid');
		$this->content = $this->content_model->get($cid);
		if (!$this->content) {
			$this->admin_msg(lang('019'));
		}
		// 判断管理组是否具有此栏目的管理权限
		$this->catrule = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR,'category',$this->content['catid'],'setting','admin',$this->admin['adminid']);
		if ($this->admin['adminid'] > 1 && !$this->catrule['show']) {
			$this->admin_msg(lang('257'));
		} else {
			$this->catrule['show'] = $this->catrule['add'] = $this->catrule['edit'] = $this->catrule['del'] = 1;
		}
		$this->load->library('Dfield', array(APP_DIR));
		$this->content['type'] = dr_string2array($this->content['type']);
		$this->sysfield = array(
			'inputtime' => array(
				'name' => lang('104'),
				'ismain' => 1,
				'fieldtype' => 'Date',
				'fieldname' => 'inputtime',
				'setting' => array(
					'option' => array(
						'width' => 140
					),
					'validate' => array(
						'formattr' => '',
					)
				)
			)
		);
		$field = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'extend');
		$this->field = $field ? array_merge($field, $this->sysfield) : $this->sysfield;
		$this->template->assign(array(
			'cid' => $cid,
            'menu' => $this->get_menu(array(
				lang('mod-29') => APP_DIR.'/admin/extend/index/cid/'.$cid.($catid ? '/catid/'.$catid : ''),
				'<font color=red><b>'.lang('mod-37').'</b></font>' => APP_DIR.'/admin/extend/add/cid/'.$cid.'/catid/'.$catid,
				lang('mod-36') => APP_DIR.'/admin/home/index/catid/'.$catid,
			)),
			'catid' => $catid,
			'catrule' => $this->catrule,
			'content' => $this->content,
		));
        $this->catid = $catid;
	}

    /**
     * 管理
     */
    public function index() {
		
		if (IS_POST && !$this->input->post('search')) {
		
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) {
                exit(dr_json(0, lang('013')));
            }

			switch($this->input->post('action')) {
		        // 删除
				case 'del':
					if ($this->catrule['del']) {
						$this->content_model->delete_extend_for_ids($ids);
					} else {
						exit(dr_json(0, lang('160')));
					}
					exit(dr_json(1, lang('000')));
					break;
				// 排序
				case 'order':
					if (!$this->catrule['edit']) {
                        exit(dr_json(0, lang('160')));
                    }
					$_data = $this->input->post('data');
					foreach ($ids as $id) {
						$this->link
							 ->where('id', $id)
							 ->update($this->content_model->prefix.'_extend', $_data[$id]);
					}
					exit(dr_json(1, lang('000')));
					break;
				// 移动
				case 'move':
					$type = $this->input->post('type');
					if (!$type) {
                        exit(dr_json(0, lang('160')));
                    }
					if (!$this->catrule['edit']) {
                        exit(dr_json(0, lang('160')));
                    }
					$this->content_model->extend_move($ids, $type);
					exit(dr_json(1, lang('000')));
					break;
				// 未定义
				default :
					exit(dr_json(0, lang('000')));
					break;
			}
		}
		
		// 根据参数筛选结果
		$param = array();
		if ($this->input->get('search')) {
            $param['search'] = 1;
        }
		if ($this->input->get('type')) {
            $param['type'] = $this->input->get('type');
        }
		
		// 数据库中分页查询
		list($list, $param)	= $this->content_model->extend_limit_page($this->content['id'], $param);
		
		// 搜索参数
		if ($this->input->get('search')) {
			$_param = $this->cache->file->get($this->content_model->cache_file);
		} else {
			$_param = $this->input->post('data');
		}
		$_param = $_param ? $param + $_param : $param;
		$param['cid'] = $this->content['id'];
		$param['catid'] = $this->content['catid'];
		
		$this->template->assign(array(
			'app' => $app,
			'list' => $list,
			'param'	=> $_param,
			'pages'	=> $this->get_pagination(dr_url(APP_DIR.'/extend/index', $param), $param['total']),
		));
		$this->template->display('content_extend_index.html');
    }
    
	/**
     * 添加
     */
    public function add() {
		
		if (!$this->catrule['add']) {
            $this->admin_msg(lang('160'));
        }

        $did = (int)$this->input->get('did');
		$type = (int)$this->input->get('type');

		$error = $data = array();
		$result = '';
		
		if (IS_POST) {
			$type = (int)$this->input->post('type');
			$_POST['data']['cid'] = $this->content['id'];
			$_POST['data']['uid'] = $this->content['uid'];
			$data = $this->validate_filter($this->field);
			if (isset($data['error'])) {
				$error = $data;
				$data = $this->input->post('data');
			} else {
				$data[1]['cid'] = $this->content['id'];
				$data[1]['uid'] = $this->content['uid'];
                $data[1]['catid'] = $this->content['catid'];
                $data[1]['status'] = 9;
                $data[1]['author'] = $this->content['author'];
                // 保存为草稿
                if ($this->input->post('action') == 'draft') {
                    $this->clear_cache('save_'.APP_DIR.'_extend_'.$this->uid);
                    $id = $this->content_model->save_draft($did, $data, 1);
                    $this->attachment_handle($this->uid, $this->content_model->prefix.'_draft-'.$id, $this->field);
                    $this->admin_msg(lang('m-229'), dr_url(APP_DIR.'/home/draft/'), 1);
                    exit;
                }
                // 数据来至草稿时更新时间
                if ($did) {
                    $data[1]['inputtime'] = SYS_TIME;
                }
				if ($id = $this->content_model->add_extend($data)) {
                    // 发布草稿时删除草稿数据
                    if ($did && $this->content_model->delete_draft($did, 'cid='.$this->content['id'].' and eid=-1')) {
                        $this->attachment_replace_draft($did, $this->content['id'], $id, $this->content_model->prefix);
                    } else {
                        $this->clear_cache('save_'.APP_DIR.'_extend_'.$this->uid);
                    }
					$mark = $this->content_model->prefix.'-'.$this->content['id'].'-'.$id;
					$member = $this->member_model->get_base_member($this->content['uid']);
					$markrule = $member['markrule'];
					$category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $this->content['catid']);
					$rule = $category['permission'][$markrule];
					// 积分处理
					if ($rule['extend_experience'] + $member['experience'] >= 0) {
						$this->member_model->update_score(0, $this->content['uid'], $rule['extend_experience'], $mark, "lang,m-343,{$category['name']}", 1);
					}
					// 虚拟币处理
					if ($rule['extend_score'] + $member['score'] >= 0) {
						$this->member_model->update_score(1, $this->content['uid'], $rule['extend_score'], $mark, "lang,m-343,{$category['name']}", 1);
					}
					// 操作成功处理附件
					$this->attachment_handle($this->content['uid'], $mark, $this->field);
					$create = MODULE_HTML ? dr_module_create_show_file($this->content['id'], 1) : '';
					if ($this->input->post('action') == 'back') {
						$this->admin_msg(
                            lang('000').
                            ($create ? "<script src='".$create."'></script>".dr_module_create_list_file($this->content['catid'])  : ''),
                            dr_url(APP_DIR.'/extend/index', array(
                                'cid' => $this->content['id'],
                                'catid' => (int)$_GET['catid'],
                                'type' => (int)$_GET['type'])
                            ),
                            1,
                            0
                        );
					} else {
						$type = $data[1]['mytype'];
						unset($data);
						$data['mytype'] = (int)$type;
						$result = lang('000');
					}
				} else {
					$error = array('error' => 'error');
				}
			}
		} else {
            if ($did) {
                $temp = $this->content_model->get_draft($did);
                if ($temp['draft']['cid'] == $this->content['id'] && $temp['draft']['eid'] == -1) {
                    $data = $temp;
                }
            } else {
                $data = $this->get_cache_data('save_'.APP_DIR.'_extend_'.$this->uid);
            }
        }
		
		$this->template->assign(array(
            'did' => $did,
			'data' => $data,
			'error' => $error,
			'result' => $result,
			'create' => $create,
            'draft_url' => SITE_URL.dr_url(APP_DIR.'/extend/add', array('cid' => $this->content['id'], 'catid' => $this->catid)),
            'draft_list' => $this->content_model->get_draft_list('cid='.$this->content['id'].' and eid=-1'),
			'myfield' => $this->field_input($this->field, $data, TRUE),
		));
		$this->template->display('content_extend_add.html');
	}
	
	/**
     * 修改
     */
    public function edit() {
	
		if (!$this->catrule['edit']) {
            $this->admin_msg(lang('160'));
        }
		
		$id = (int)$this->input->get('id');
        $did = (int)$this->input->get('did');

		$data = $this->content_model->get_extend($id);
		if (!$data) {
            $this->admin_msg(lang('019'));
        }
		
		$error = array();
		$result = '';
		
		if (IS_POST) {
			$_data = $data;
			$type = (int)$this->input->post('type');
			$_POST['data']['cid'] = $this->content['id'];
			$_POST['data']['uid'] = $this->content['uid'];
			$data = $this->validate_filter($this->field, $_data);
			if (isset($data['error'])) {
				$error = $data;
				$data = $this->input->post('data', TRUE);
			} else {
				$data[1]['cid'] = $this->content['id'];
				$data[1]['uid'] = $this->content['uid'];
				$data[1]['catid'] = $this->content['catid'];
				$data[1]['status'] = $_data['status'];
                $data[1]['author'] = $this->content['author'];
                // 保存为草稿
                if ($this->input->post('action') == 'draft') {
                    $data[1]['id'] = $data[0]['id'] = $id;
                    $id = $this->content_model->save_draft($did, $data, 1);
                    $this->attachment_handle($this->uid, $this->content_model->prefix.'_draft-'.$id, $this->field);
                    $this->admin_msg(lang('m-229'), dr_url(APP_DIR.'/home/draft/'), 1);
                    exit;
                }
                // 正常保存
				if ($id = $this->content_model->edit_extend($_data, $data)) {
                    // 发布草稿时删除草稿数据
                    if ($did && $this->content_model->delete_draft($did, 'cid='.$this->content['id'].' and eid='.$id)) {
                        $this->attachment_replace_draft($did, $this->content['id'], $id, $this->content_model->prefix);
                    }
					$mark = $this->content_model->prefix.'-'.$this->content['id'].'-'.$id;
					// 操作成功处理附件
					$this->attachment_handle($this->content['uid'], $mark, $this->field, $_data);
					$this->admin_msg(
                        lang('000').
                        (MODULE_HTML ? dr_module_create_show_file($this->content['id']).dr_module_create_list_file($this->content['catid']) : ''),
                        dr_url(APP_DIR.'/extend/index', array(
                            'cid' => $this->content['id'],
                            'catid' => (int)$_GET['catid'],
                            'type' => (int)$_GET['type'])
                        ),
                        1,
                        0
                    );
				} else {
					$error = array('error' => 'error');
				}
			}
		} else {
            if ($did) {
                $temp = $this->content_model->get_draft($did);
                if ($temp['draft']['cid'] == $this->content['id'] && $temp['draft']['eid'] == $id) {
                    $data = $temp;
                }
            }
        }
        $data['inputtime'] = SYS_TIME;
		
		$this->template->assign(array(
            'did' => $did,
			'data' => $data,
			'error' => $error,
			'result' => $result,
            'draft_url' => SITE_URL.dr_url(APP_DIR.'/extend/edit', array('cid' => $this->content['id'], 'catid' => $this->catid, 'id' => $id)),
            'draft_list' => $this->content_model->get_draft_list('cid='.$this->content['id'].' and eid='.$id),
			'myfield' => $this->field_input($this->field, $data, TRUE),
		));
		$this->template->display('content_extend_add.html');
    }
   
}