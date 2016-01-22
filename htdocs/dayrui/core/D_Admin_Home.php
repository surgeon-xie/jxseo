<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since	    version 2.3.7
 * @author	    Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
class D_Admin_Home extends M_Controller {

    protected $verify; // 审核流程
    protected $sysfield; // 系统字段

    /**
     * 构造函数
     */

    public function __construct() {
        parent::__construct();
        $this->load->library('Dfield', array(APP_DIR));
        $this->sysfield = array(
            'hits' => array(
                'name' => lang('244'),
                'ismain' => 1,
                'fieldname' => 'hits',
                'fieldtype' => 'Text',
                'setting' => array(
                    'option' => array(
                        'value' => 0,
                        'width' => 157,
                    )
                )
            ),
            'author' => array(
                'name' => lang('101'),
                'ismain' => 1,
                'fieldtype' => 'Text',
                'fieldname' => 'author',
                'setting' => array(
                    'option' => array(
                        'width' => 157,
                        'value' => $this->admin['username']
                    ),
                    'validate' => array(
                        'tips' => lang('102'),
                        'check' => '_check_member',
                        'required' => 1,
                        'formattr' => ' /><input type="button" class="button" value="'.lang('103').'" onclick="dr_dialog_member(\'author\')" name="user"',
                    )
                )
            ),
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
                        'required' => 1,
                        'formattr' => '',
                    )
                )
            ),
            'updatetime' => array(
                'name' => lang('105'),
                'ismain' => 1,
                'fieldtype' => 'Date',
                'fieldname' => 'updatetime',
                'setting' => array(
                    'option' => array(
                        'width' => 140
                    ),
                    'validate' => array(
                        'required' => 1,
                        'formattr' => '',
                    )
                )
            ),
            'inputip' => array(
                'name' => lang('106'),
                'ismain' => 1,
                'fieldtype' => 'Text',
                'fieldname' => 'inputip',
                'setting' => array(
                    'option' => array(
                        'width' => 157,
                        'value' => $this->input->ip_address()
                    ),
                    'validate' => array(
                        'formattr' => ' /><input type="button" class="button" value="'.lang('107').'" onclick="dr_dialog_ip(\'inputip\')" name="ip"',
                    )
                )
            )
        );
        if ($this->admin['adminid'] > 1) {
            $this->verify = $this->_get_verify();
        }
    }

    // 获取可用字段
    protected function _get_field($catid = 0) {

        // 主字段
        $field = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'field');

        // 指定栏目字段
        $category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid, 'field');
        if ($category) {
            $content = $field['content'];
            unset($field['content']);
            $myfield = array_merge($field, $category);
            if ($content) {
                $myfield['content'] = $content;
            }
            $myfield = array_merge($myfield, $this->sysfield);
            unset($content, $field);
        } else {
            $myfield = array_merge($field, $this->sysfield);
        }

        return $myfield;
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
            switch ($this->input->post('action')) {
                case 'del':
                    $ok = $no = 0;
                    foreach ($ids as $id) {
                        $data = $this->link
                                     ->where('id', (int) $id)
                                     ->select('id,catid,tableid')
                                     ->limit(1)
                                     ->get($this->content_model->prefix)
                                     ->row_array();
                        if ($data) {
                            if (!$this->is_category_auth($data['catid'], 'del')) {
                                $no++;
                            } else {
                                $ok++;
                                $this->content_model->delete_for_id((int)$data['id'], (int)$data['tableid']);
                            }
                        }
                    }
                    exit(dr_json($no ? 0 : 1, $no ? dr_lang('033', $ok, $no) : lang('000')));
                    break;
                case 'order':
                    $_data = $this->input->post('data');
                    foreach ($ids as $id) {
                        $this->link
                             ->where('id', $id)
                             ->update($this->content_model->prefix, $_data[$id]);
                    }
                    exit(dr_json(1, lang('000')));
                    break;
                case 'move':
                    $catid = $this->input->post('catid');
                    if (!$catid) {
                        exit(dr_json(0, lang('cat-20')));
                    }
                    if (!$this->is_auth(APP_DIR.'/admin/home/edit')
                        || !$this->is_category_auth($catid, 'edit')) {
                        exit(dr_json(0, lang('160')));
                    }
                    $this->content_model->move($ids, $catid);
                    exit(dr_json(1, lang('000')));
                    break;
                case 'flag':
                    if (!$this->is_auth(APP_DIR.'/admin/home/edit')) {
                        exit(dr_json(0, lang('160')));
                    }
                    $flag = $this->input->post('flagid');
                    $this->content_model->flag($ids, $flag);
                    exit(dr_json(1, lang('000')));
                    break;
                default :
                    exit(dr_json(0, lang('000')));
                    break;
            }
        }

        // 重置页数和统计
        if (IS_POST) {
            $_GET['page'] = $_GET['total'] = 0;
        }

        // 筛选结果
        $param = array();
        if ($this->input->get('flag')) {
            $param['flag'] = (int) $this->input->get('flag');
        }
        if ($this->input->get('catid')) {
            $catid = $param['catid'] = (int) $this->input->get('catid');
        }
        if ($this->input->get('search')) {
            $param['search'] = 1;
        }

        // 数据库中分页查询
        list($list, $param) = $this->content_model->limit_page($param, max((int)$_GET['page'], 1), (int)$_GET['total']);

        // 统计推荐位数量
        $_menu[lang('mod-01')] = $catid ? APP_DIR.'/admin/home/index/catid/'.$catid : APP_DIR.'/admin/home/index';
        $flag = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'setting', 'flag');
        if ($flag) {
            foreach ($flag as $id => $t) {
                if ($t['name'] && $id) {
                    $_menu["{$t['name']}(".$this->content_model->flag_total($id, $catid).")"] = $catid ?
                            APP_DIR.'/admin/home/index/flag/'.$id.'/catid/'.$catid :
                            APP_DIR.'/admin/home/index/flag/'.$id;
                }
            }
        }

        // 模块应用嵌入
        $app = array();
        $data = $this->get_cache('app');
        if ($data) {
            foreach ($data as $dir) {
                $a = $this->get_cache('app-'.$dir);
                if (isset($a['module'][APP_DIR]) && isset($a['related']) && $a['related']) {
                    $app[] = array(
                        'url' => dr_url($dir.'/content/index'),
                        'name' => $a['name'],
                        'field' => $a['related'],
                    );
                }
            }
        }

        // 模块表单嵌入
        $form = array();
        $data = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'form');
        if ($data) {
            foreach ($data as $t) {
                $form[] = array(
                    'url' => dr_url(APP_DIR.'/form_'.$t['table'].'/index'),
                    'name' => $t['name'],
                );
            }
        }

        // 搜索参数
        if ($this->input->get('search')) {
            $_param = $this->cache->file->get($this->content_model->cache_file);
        } else {
            $_param = $this->input->post('data');
        }
        $_menu["<font color=red><b>".lang('mod-02')."</b></font>"] = $catid ? APP_DIR.'/admin/home/add/catid/'.$catid : APP_DIR.'/admin/home/add';
        isset($_param['catid']) && $catid = $param['catid'] = $_param['catid'];
        $_param = $_param ? $param + $_param : $param;

        // 按字段的搜索
        $field = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'field');
        $field['author'] = array('name' => lang('101'), 'ismain' => 1, 'fieldname' => 'author');

        $this->template->assign(array(
            'app' => $app,
            'form' => $form,
            'list' => $list,
            'menu' => $this->get_menu($_menu),
            'flag' => isset($param['flag']) ? $param['flag'] : '',
            'flags' => $flag,
            'field' => $field,
            'param' => $_param,
            'pages' => $this->get_pagination(dr_url(APP_DIR.'/home/index', $param), $param['total']),
            'extend' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'extend'),
            'select' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'), 0, 'id=\'move_id\' name=\'catid\'', ' --- ', 1, 1),
            'select2' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'), $catid, 'name=\'data[catid]\'', ' --- ', 0, 1),
        ));
        $this->template->display('content_index.html');
    }

    /**
     * 审核
     */
    public function verify() {

        if ($this->admin['adminid'] > 1 && !$this->verify) {
            $this->admin_msg(lang('337'));
        }

        if (IS_POST && $this->input->post('action') != 'search') {
            $ids = $this->input->post('ids', TRUE);
            if (!$ids) {
                exit(dr_json(0, lang('013')));
            }
            if ($this->admin['adminid'] > 1) {
                // 非管理员角色只能操作自己审核的
                $status = array();
                foreach ($this->verify as $t) {
                    $status+=$t['status'];
                }
                $where = '`status` IN ('.implode(',', $status).')';
            } else {
                $where = '';
            }
            switch ($this->input->post('action')) {
                case 'del': // 删除
                    $this->load->model('attachment_model');
                    foreach ($ids as $id) {
                        $data = $this->link // 主表状态
                                     ->where($where ? $where.' AND `id`='.(int)$id : '`id`='.(int)$id)
                                     ->select('uid,catid')
                                     ->limit(1)
                                     ->get($this->content_model->prefix.'_index')
                                     ->row_array();
                        if ($data) {
                            // 删除数据
                            $this->content_model->del_verify($id);
                            // 删除表对应的附件
                            $this->attachment_model->delete_for_table($this->content_model->prefix.'_verify-'.$id);
                        }
                    }
                    exit(dr_json(1, lang('000')));
                    break;
                case 'flag': // 标记
                    $js = $error = array();
                    if (!$this->input->post('flagid')) {
                        exit(dr_json(0, lang('013')));
                    }
                    foreach ($ids as $id) {
                        $result = $this->_verify($id, NULL, $where ? $where.' AND `id`='.(int)$id : '`id`='.(int)$id);
                        if (is_array($result)) {
                            if (MODULE_HTML) {
                                $js[] = dr_module_create_show_file($result['id'], 1);
                                $js[] = dr_module_create_list_file($result['catid'], 1);
                            }
                        } elseif ($result) {
                            $error[] = str_replace('<br>', '', $result);
                        }
                    }
                    if ($error) {
                        exit(dr_json(1, $error, $js));
                    } else {
                        exit(dr_json(2, lang('000'), $js));
                    }
                    break;
                default:
                    exit(dr_json(0, lang('047')));
                    break;
            }
        }
        $param = array();
        $param['status'] = (int)$this->input->get('status');
        if ($this->admin['adminid'] == 1) {
            // 管理员角色列出所有审核流程
            $goto = '';
            $where = '`status`='.$param['status'];
            for ($i = 0; $i < 9; $i++) {
                $total = (int)$this->db->where('status', $i)->count_all_results($this->content_model->prefix.'_verify');
                $key_name = lang('05'.$i).' ('.$total.')';
                if ($total && !$goto) {
                    if ($param['status'] == $i) {
                        $goto = 1;
                    } else {
                        $goto = $key_name;
                    }
                }
                $_menu[$key_name] = APP_DIR.'/admin/home/verify'.(isset($_GET['status']) || $i ? '/status/'.$i : '');
            }
            // 跳转到对应的状态
            if (strlen($goto) > 1 && isset($_menu[$goto])) {
                redirect(SITE_URL.$this->duri->uri2url($_menu[$goto]), 'refresh');
                exit;
            }
        } else {
            // 非管理员角色列出自己审核的
            $status = array();
            foreach ($this->verify as $t) {
                $status+=$t['status'];
            }
            if ($param['status']) {
                $where = '`status` IN ('.implode(',', $status).')';
            } else {
                $where = '`status`=0 AND `backuid`='.$this->uid;
            }
        }
        // 栏目筛选
        if ($this->input->get('catid')) {
            $param['catid'] = (int) $this->input->get('catid');
            $where.= ' AND `catid` = '.$param['catid'];
        }
        // 获取总数量
        $param['total'] = $total = $this->input->get('total') ? $this->input->get('total') : $this->link->where($where)->count_all_results($this->content_model->prefix.'_verify');
        $page = max(1, (int) $this->input->get('page'));
        $data = $this->link
                     ->select('id,catid,author,content,inputtime,status')
                     ->where($where)
                     ->limit(SITE_ADMIN_PAGESIZE, SITE_ADMIN_PAGESIZE * ($page - 1))
                     ->order_by('inputtime DESC, id DESC')
                     ->get($this->content_model->prefix . '_verify')
                     ->result_array();

        if ($this->admin['adminid'] > 1) {
            // 被退回
            $_total = $this->link
                           ->where('`status`=0 AND `backuid`='.$this->uid)
                           ->count_all_results($this->content_model->prefix.'_verify');
            $_menu[lang('050').' ('.$_total.')'] = APP_DIR.'/admin/home/verify';
            // 我的审核
            $_total = $this->link
                           ->where_in('status', $status)
                           ->count_all_results($this->content_model->prefix.'_verify');
            $_menu[lang('120').' ('.$_total.')'] = APP_DIR.'/admin/home/verify/status/1';
        }

        $this->template->assign(array(
            'list' => $data,
            'menu' => $this->get_menu($_menu),
            'param' => $param,
            'pages' => $this->get_pagination(dr_url(APP_DIR.'/home/verify', $param), $param['total'])
        ));
        $this->template->display('content_verify.html');
    }

    /**
     * 添加
     */
    public function add() {

        $did = (int)$this->input->get('did');
        $catid = (int)$this->input->get('catid');

        $error = $data = array();
        $result = $select = '';
        $category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category');

        // 可用字段
        $myfield = $this->_get_field($catid);

        // 提交保存操作------
        if (IS_POST) {
            $cid = (int)$this->input->post('catid');
            // 判断栏目权限
            if ($cid != $catid && !$this->is_category_auth($catid, 'add')) {
                $this->admin_msg(lang('160'));
            }
            $catid = $cid;
            $category = $cid != $catid ? $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid) : $category;
            unset($cid);
            // 设置uid便于校验处理
            $uid = $this->input->post('data[author]') ? get_member_id($this->input->post('data[author]')) : 0;
            $_POST['data']['id'] = $id;
            $_POST['data']['uid'] = $uid;
            $data = $this->validate_filter($myfield);
            // 返回错误
            if (isset($data['error'])) {
                $error = $data;
                $data = $this->input->post('data', TRUE);
            } elseif (!$catid) {
                $data = $this->input->post('data', TRUE);
                $error = array('error' => 'catid', 'msg' => lang('cat-22'));
            } else {
                $data[1]['uid'] = $uid;
                $data[1]['catid'] = $catid;
                $data[1]['status'] = 9;
                // 保存为草稿
                if ($this->input->post('action') == 'draft') {
                    $this->clear_cache('save_'.APP_DIR.'_'.$this->uid);
                    $id = $this->content_model->save_draft($did, $data, 0);
                    $this->attachment_handle($this->uid, $this->content_model->prefix.'_draft-'.$id, $myfield);
                    $this->admin_msg(lang('m-229'), dr_url(APP_DIR.'/home/draft/'), 1);
                    exit;
                }
                // 数据来至草稿时更新时间
                if ($did) {
                    $data[1]['updatetime'] = $data[1]['inputtime'] = SYS_TIME;
                }
                // 正常发布
                if (($id = $this->content_model->add($data)) != FALSE) {
                    // 发布草稿时删除草稿数据
                    if ($did && $this->content_model->delete_draft($did, 'cid=0 and eid=0')) {
                        $this->attachment_replace_draft($did, $id, 0, $this->content_model->prefix);
                    } else {
                        $this->clear_cache('save_'.APP_DIR.'_'.$this->uid);
                    }
                    $mark = $this->content_model->prefix.'-'.$id;
                    $member = $this->member_model->get_base_member($uid);
                    $rule = $category['permission'][$member['markrule']];
                    // 积分处理
                    if ($rule['experience'] + $member['experience'] >= 0) {
                        $this->member_model->update_score(0, $uid, $rule['experience'], $mark, "lang,m-151,{$category['name']}", 1);
                    }
                    // 虚拟币处理
                    if ($rule['score'] + $member['score'] >= 0) {
                        $this->member_model->update_score(1, $uid, $rule['score'], $mark, "lang,m-151,{$category['name']}", 1);
                    }
                    // 操作成功处理附件
                    $this->attachment_handle($data[1]['uid'], $mark, $myfield);
                    // 处理推荐位
                    $update = $this->input->post('flag');
                    if ($update) {
                        foreach ($update as $i) {
                            $this->link->insert(SITE_ID.'_'.APP_DIR.'_flag', array(
                                'id' => $id,
                                'uid' => $uid,
                                'flag' => $i,
                                'catid' => $catid
                            ));
                        }
                    }
                    // 创建静态页面链接
                    $create = MODULE_HTML ? dr_module_create_show_file($id, 1) : '';
                    if ($this->input->post('action') == 'back') {
                        $this->admin_msg(
                            lang('000').
                            ($create ? "<script src='".$create."'></script>".dr_module_create_list_file($catid) : ''),
                            dr_url(APP_DIR.'/home/index/', array('catid'=>$catid)),
                            1,
                            1
                        );
                    } else {
                        unset($data);
                        $result = lang('000');
                    }
                }
            }
            $select = $this->select_category($category, $catid, 'id=\'dr_catid\' name=\'catid\' onChange="show_category_field(this.value)"', '', 1, 1);
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
                list($select, $catid) = $this->select_category($category, 0, 'id=\'dr_catid\' name=\'catid\' onChange="show_category_field(this.value)"', '', 1, 1, 1);
            } else {
                $select = $this->select_category($category, $catid, 'id=\'dr_catid\' name=\'catid\' onChange="show_category_field(this.value)"', '', 1, 1);
            }
            // 判断栏目权限
            if (!$this->is_category_auth($catid, 'add')) {
                $this->admin_msg(lang('160'));
            }
        }

        $this->template->assign(array(
            'did' => $did,
            'data' => $data,
            'flag' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'setting', 'flag'),
            'menu' => $this->get_menu(array(
                lang('back') => APP_DIR.'/admin/home/index/catid/'.$catid,
                lang('mod-02') => APP_DIR.'/admin/home/add'
            )),
            'catid' => $catid,
            'error' => $error,
            'result' => $result,
            'create' => $create,
            'myflag' => $this->input->post('flag'),
            'select' => $select,
            'myfield' => $this->new_field_input($myfield, $data, TRUE),
            'draft_url' => SITE_URL.dr_url(APP_DIR.'/home/add'),
            'draft_list' => $this->content_model->get_draft_list('cid=0 and eid=0'),
            'is_category_field' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category_field'),
        ));
        $this->template->display('content_add.html');
    }

    /**
     * 修改
     */
    public function edit() {

        $id = (int)$this->input->get('id');
        $did = (int)$this->input->get('did');
        $cid = (int)$this->input->get('catid');

        $data = $this->content_model->get($id);
        $catid = $cid ? $cid : $data['catid'];

        $error = $myflag = array();
        $result = $select = '';
        unset($cid);

        // 数据判断
        if (!$data) {
            $this->admin_msg(lang('019'));
        }

        // 栏目缓存
        $category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category');

        // 可用字段
        $myfield = $this->_get_field($catid);

        if ($flag = $this->link->where('id', $id)->get(SITE_ID.'_'.APP_DIR.'_flag')->result_array()) {
            foreach ($flag as $t) {
                $myflag[] = $t['flag'];
            }
        }
        unset($flag);

        if (IS_POST) {
            $cid = (int)$this->input->post('catid');
            // 判断栏目权限
            if ($cid != $catid && !$this->is_category_auth($catid, 'add')) {
                $this->admin_msg(lang('160'));
            }
            $catid = $cid;
            $category = $cid != $catid ? $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid) : $category;
            unset($cid);
            // 设置uid便于校验处理
            $uid = $this->input->post('data[author]') ? get_member_id($this->input->post('data[author]')) : 0;
            $_POST['data']['id'] = $id;
            $_POST['data']['uid'] = $uid;
            $post = $this->validate_filter($myfield, $data);
            if (isset($post['error'])) {
                $error = $post;
            } elseif (!$catid) {
                $error = array('error' => 'catid', 'msg' => lang('cat-22'));
            } else {
                $post[1]['uid'] = $uid;
                $post[1]['catid'] = $catid;
                $post[1]['status'] = 9;
                $post[1]['updatetime'] = $this->input->post('no_time') ? $data['updatetime'] : $post[1]['updatetime'];
                // 保存为草稿
                if ($this->input->post('action') == 'draft') {
                    $post[1]['id'] = $post[0]['id'] = $id;
                    $id = $this->content_model->save_draft($did, $post, 0);
                    $this->attachment_handle($this->uid, $this->content_model->prefix.'_draft-'.$id, $myfield);
                    $this->admin_msg(lang('m-229'), dr_url(APP_DIR.'/home/draft/'), 1);
                    exit;
                }
                // 正常保存
                $this->content_model->edit($data, $post);
                // 发布草稿时删除草稿数据
                if ($did && $this->content_model->delete_draft($did, 'cid='.$id.' and eid=0')) {
                    $this->attachment_replace_draft($did, $id, 0, $this->content_model->prefix);
                }
                // 操作成功处理附件
                $this->attachment_handle($post[1]['uid'], $this->content_model->prefix.'-'.$id, $myfield, $data);
                // 处理推荐位
                $update = $this->input->post('flag');
                if ($update !== $myflag) {
                    // 删除旧的
                    if ($myflag) {
                        $this->link
                                ->where('id', $id)
                                ->where_in('flag', $myflag)
                                ->delete(SITE_ID.'_'.APP_DIR.'_flag');
                    }
                    // 增加新的
                    if ($update) {
                        foreach ($update as $i) {
                            $this->link->insert(SITE_ID.'_'.APP_DIR.'_flag', array(
                                'id' => $id,
                                'uid' => $uid,
                                'flag' => $i,
                                'catid' => $catid
                            ));
                        }
                    }
                }
                //exit;
                $this->admin_msg(
                    lang('000') .
                    (MODULE_HTML ? dr_module_create_show_file($id).dr_module_create_list_file($catid) : ''),
                    dr_url(APP_DIR.'/home/index/', array('catid'=>$catid)),
                    1,
                    1
                );
            }
            $data = $post;
            $myflag = $this->input->post('flag');
        } else {
            if ($did) {
                $temp = $this->content_model->get_draft($did);
                if ($temp['draft']['cid'] == $data['id'] && $temp['draft']['eid'] == 0) {
                    $temp['id'] = $id;
                    $data = $temp;
                    $catid = $temp['catid'] ? $temp['catid'] : $catid;
                }
            }
            // 判断栏目权限
            if (!$this->is_category_auth($catid, 'add')) {
                $this->admin_msg(lang('160'));
            }
        }

        $data['updatetime'] = SYS_TIME;
        $this->template->assign(array(
            'did' => $did,
            'data' => $data,
            'flag' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'setting', 'flag'),
            'menu' => $this->get_menu(array(
                lang('back') => APP_DIR.'/admin/home/index/catid/'.$catid,
                lang('mod-02') => APP_DIR.'/admin/home/add/catid/'.$catid
            )),
            'catid' => $catid,
            'error' => $error,
            'result' => $result,
            'myflag' => $myflag,
            'select' => $this->select_category($category, $catid, 'id=\'dr_catid\' name=\'catid\' onChange="show_category_field(this.value)"', '', 1, 1),
            'myfield' => $this->new_field_input($myfield, $data, TRUE),
            'draft_url' => SITE_URL.dr_url(APP_DIR.'/home/edit', array('id' => $id)),
            'draft_list' => $this->content_model->get_draft_list('cid='.$id.' and eid=0'),
            'is_category_field' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category_field'),
        ));
        $this->template->display('content_add.html');
    }

    /**
     * 修改审核文档
     */
    public function verifyedit() {

        $id = (int)$this->input->get('id');
        $cid = (int)$this->input->get('catid');
        $data = $this->content_model->get_verify($id);
        $catid = $cid ? $cid : $data['catid'];
        $error = array();

        // 数据验证
        if (!$data) {
            $this->admin_msg(lang('019'));
        }

        $category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid, 'field');
        // 可用字段
        $myfield = $this->_get_field($catid);

        if (IS_POST) {
            $cid = (int)$this->input->post('catid');
            // 判断栏目权限
            if ($cid != $catid && !$this->is_category_auth($catid, 'add')) {
                $this->admin_msg(lang('160'));
            }
            $catid = $cid;
            $category = $cid != $catid ? $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid) : $category;
            unset($cid);
            // 设置uid便于校验处理
            $uid = $this->input->post('data[author]') ? get_member_id($this->input->post('data[author]')) : 0;
            $_POST['data']['id'] = $id;
            $_POST['data']['uid'] = $uid;
            $post = $this->validate_filter($myfield, $data);
            if (isset($post['error'])) {
                $error = $post;
                $data = $this->input->post('data', TRUE);
            } elseif (!$catid) {
                $data = $this->input->post('data', TRUE);
                $error = array('error' => 'catid', 'msg' => lang('cat-22'));
            } elseif (!$this->input->post('flagid')) {
                $data = $this->input->post('data', TRUE);
                $error = array('error' => 'flagid', 'msg' => lang('161'));
            } else {
                $post[1]['uid'] = $uid;
                $post[1]['catid'] = $catid;
                $result = $this->_verify($id, $post, '`id`='.$id);
                if (is_array($result)) {
                    $this->admin_msg(
                        lang('000').
                        (MODULE_HTML ? dr_module_create_show_file($id).dr_module_create_list_file($catid) : ''),
                        $this->input->post('backurl'),
                        1,
                        1
                    );
                } elseif ($result) {
                    $this->admin_msg($result);
                }
                $this->admin_msg(lang('000'), $this->input->post('backurl'), 1);
            }
        }

        if ($data['status'] == 0) { // 退回
            $backuri = APP_DIR.'/admin/home/verify/status/0';
        } elseif ($data['status'] > 0 && $data['status'] < 9) {
            $backuri = APP_DIR.'/admin/home/verify/status/'.$data['status'];
        } else {
            $backuri = APP_DIR.'/admin/home/verify/';
        }

        $this->template->assign(array(
            'data' => $data,
            'menu' => $this->get_menu(array(
                lang('back') => $backuri,
                lang('edit') => APP_DIR.'/admin/home/verifyedit/id/'.$data['id']
            )),
            'catid' => $catid,
            'error' => $error,
            'select' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'), $catid, 'id=\'dr_catid\' name=\'catid\' onChange="show_category_field(this.value)"', '', 1),
            'backurl' => $_SERVER['HTTP_REFERER'],
            'myfield' => $this->new_field_input($myfield, $data, TRUE),
            'is_category_field' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category_field'),
        ));
        $this->template->display('content_edit.html');
    }

    // 审核内容
    public function _verify($id, $data, $_where) {

        // 获得审核数据
        $verify = $this->content_model->get_verify($id);
        if (!$verify) {
            return;
        }

        // 通过审核
        if ($this->input->post('flagid') > 0) {
            // 查询当前的审核状态id
            $status = $this->_get_verify_status($verify['uid'], $verify['catid'], $verify['status']);
            // 权限验证
            if ($status == 9) {
                $member = $this->member_model->get_base_member($verify['uid']);
                $category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $verify['catid']);
                // 标示
                $rule = $category['permission'][$member['markrule']];
                $mark = $this->content_model->prefix.'-'.$id;
                // 积分处理
                if ($rule['experience']) {
                    $this->member_model->update_score(0, $verify['uid'], $rule['experience'], $mark, "lang,m-151,{$category['name']}", 1);
                }
                // 虚拟币处理
                if ($rule['score']) {
                    // 虚拟币判断重复
                    if (!$this->db
                              ->where('type', 1)
                              ->where('mark', $mark)
                              ->count_all_results('member_scorelog_'.(int)substr((string)$verify['uid'], -1, 1))) {
                        if ($rule['score'] + $member['score'] < 0) {
                            // 数量不足提示
                            return dr_lang('m-118', $verify['title'],  $member['username'], SITE_SCORE, abs($rule['score']));
                        }
                        $this->member_model->update_score(1, $verify['uid'], $rule['score'], $mark, "lang,m-151,{$category['name']}", 1);
                    }
                }
            }
            // 筛选字段
            if (!$data) {
                $data = array();
                $catid = $data['catid'] ? $data['catid'] : (int)$verify['catid'];
                $myfield = $this->_get_field($catid);
                foreach ($myfield as $field) {
                    if ($field['fieldtype'] == 'Group') {
                        continue;
                    }
                    if ($field['fieldtype'] == 'Baidumap') {
                        $data[$field['ismain']][$field['fieldname'].'_lng'] = (double)$verify[$field['fieldname'].'_lng'];
                        $data[$field['ismain']][$field['fieldname'].'_lat'] = (double)$verify[$field['fieldname'].'_lat'];
                    } else {
                        $value = $verify[$field['fieldname']];
                        if (strpos($field['setting']['option']['fieldtype'], 'INT') !== FALSE) {
                            $value = (int)$value;
                        } elseif ($field['setting']['option']['fieldtype'] == 'DECIMAL'
                            || $field['setting']['option']['fieldtype'] == 'FLOAT') {
                            $value = (double)$value;
                        }
                        $data[$field['ismain']][$field['fieldname']] = $value;
                    }
                }
                $data[1]['id'] = $data[0]['id'] = $id;
                $data[1]['uid'] = (int)$verify['uid'];
                $data[1]['catid'] = $catid;
                $data[1]['author'] = $verify['author'];
            } else {
                $myfield = $this->_get_field($verify['catid']);
            }
            $data[1]['status'] = $status;
            // 保存内容
            $this->content_model->edit($verify, $data);
            // 审核通过
            if ($status == 9) {
                $mark = $this->content_model->prefix.'-'.$id;
                // 操作成功处理附件
                $this->attachment_handle($data[1]['uid'], $mark, $myfield, $data);
                $this->member_model->add_notice(
                    $data[1]['uid'],
                    3,
                    dr_lang('m-084', $verify['title'])
                );
                return array('id' => $id, 'catid' => $data[1]['catid']);
            }
        } else {
            // 拒绝审核
            // 更改主表状态
            $this->link->where($_where)->update($this->content_model->prefix, array('status' => 0));
            // 更改索引表状态
            $this->link->where($_where)->update($this->content_model->prefix.'_index', array('status' => 0));
            // 更改审核表状态
            $this->link->where($_where)->update($this->content_model->prefix.'_verify', array(
                    'status' => 0,
                    'backuid' => (int)$this->uid,
                    'backinfo' => dr_array2string(array(
                        'uid' => $this->uid,
                        'author' => $this->admin['username'],
                        'rolename' => $this->admin['role']['name'],
                        'optiontime' => SYS_TIME,
                        'backcontent' => $this->input->post('backcontent')
                    ))
                )
            );
            $this->member_model->add_notice(
                $verify['uid'],
                3,
                dr_lang('m-124', $verify['title'], MEMBER_URL.'index.php?s='.APP_DIR.'&c=back&m=edit&id='.$id)
            );
        }
    }

    /**
     * 生成静态
     */
    public function html() {

        $mod = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR);
        if (!$mod['html']) {
            $html = 1;
        } elseif (SITE_ID > 1 && !$mod['domain']) {
            $html = 2;
        } else {
            $rule = FALSE;
            foreach ($mod['category'] as $t) {
                if ($t['setting']['urlrule']) {
                    $rule = TRUE;
                    break;
                }
            }
            $html = $rule ? 0 : 3;
        }

        set_cookie('mobile', -1, -1);

        $this->template->assign(array(
            'html' => $html,
            'menu' => $this->get_menu(array(
                lang('html-621') => APP_DIR.'/admin/home/html',
            )),
            'extend' => $mod['extend'] ? 1 : 0,
            'select' => $this->select_category($mod['category'], 0, 'name=\'data[catid]\'', '全部'),
        ));
        $this->template->display('content_html.html');
    }

    /**
     * 清除静态文件
     */
    public function clear() {

        $type = (int) $this->input->get('type');
        $page = (int) $this->input->get('page');
        $total = (int) $this->input->get('total');

        if ($page == 0 && !$total) {
            if ($type == 1) {
                $this->link->where('type', 3);
            } else {
                $this->link->where('type <>', 3);
            }
            $total = $this->link->count_all_results($this->content_model->prefix.'_html');
            $this->mini_msg('正在统计静态文件数量...', dr_url(APP_DIR.'/home/clear', array('type' => $type, 'page' => 1, 'total' => $total)));
        }
        $pagesize = 100; // 每次清除数量
        $count = ceil($total / $pagesize); // 计算总页数
        if ($page > $count) {
            $this->mini_msg('全部清除完成');
        }
        if ($type == 1) {
            $this->link->where('type', 3);
        } else {
            $this->link->where('type <>', 3);
        }
        $data = $this->link
                     ->select('filepath,id')
                     ->limit($pagesize, $pagesize * ($page - 1))
                     ->get($this->content_model->prefix.'_html')
                     ->result_array();
        $this->content_model->delete_html_file($data);
        $next = $page + 1;
        $this->mini_msg("共{$total}个文件，共需清理{$count}次，每次删除{$pagesize}个，正在进行第{$next}次...", dr_url(APP_DIR . '/home/clear', array('type' => $type, 'page' => $next, 'total' => $total)), 2, 0);
    }

    // 复制文章
    public function copy() {

        $id = (int)$this->input->get('id');
        $row = $this->content_model->get($id);
        if (!$row) {
            exit(dr_json(0, lang('019')));
        }

        // 格式化字段
        $data = array();
        $fields = $this->_get_field($row['catid']);
        foreach ($fields as $field) {
            if ($field['fieldtype'] == 'Group') {
                continue;
            }
            if ($field['fieldtype'] == 'Baidumap') {
                $data[$field['ismain']][$field['fieldname'].'_lng'] = (double)$row[$field['fieldname'].'_lng'];
                $data[$field['ismain']][$field['fieldname'].'_lat'] = (double)$row[$field['fieldname'].'_lat'];
            } else {
                $value = $row[$field['fieldname']];
                if (strpos($field['setting']['option']['fieldtype'], 'INT') !== FALSE) {
                    $value = (int)$value;
                } elseif ($field['setting']['option']['fieldtype'] == 'DECIMAL'
                    || $field['setting']['option']['fieldtype'] == 'FLOAT') {
                    $value = (double)$value;
                }
                $data[$field['ismain']][$field['fieldname']] = $value;
            }
        }
        $data[1]['uid'] = (int)$row['uid'];
        $data[1]['catid'] = (int)$row['catid'];
        $data[1]['author'] = $row['author'];
        $data[1]['status'] = 9;
        $data[1]['inputtime'] = $data[1]['updatetime'] = SYS_TIME;

        // 入库
        if (($new_id = $this->content_model->add($data)) != FALSE) {
            $extend = $this->get_cache('module-'.SITE_ID.'-'.$this->dir, 'extend');
            if ($extend) {
                $ext = $this->link
                            ->where('cid', $id)
                            ->where('status', 9)
                            ->get(SITE_ID.'_'.$this->dir.'_extend_index')
                            ->result_array();
                if ($ext) {
                    foreach ($ext as $t) {
                        $row = $this->content_model->get_extend($t['id']);
                        if ($row) {
                            unset($row['id']);
                            $data = array();
                            foreach ($extend as $field) {
                                if ($field['fieldtype'] == 'Group') {
                                    continue;
                                }
                                if ($field['fieldtype'] == 'Baidumap') {
                                    $data[$field['ismain']][$field['fieldname'].'_lng'] = (double)$row[$field['fieldname'].'_lng'];
                                    $data[$field['ismain']][$field['fieldname'].'_lat'] = (double)$row[$field['fieldname'].'_lat'];
                                } else {
                                    $value = $row[$field['fieldname']];
                                    if (strpos($field['setting']['option']['fieldtype'], 'INT') !== FALSE) {
                                        $value = (int)$value;
                                    } elseif ($field['setting']['option']['fieldtype'] == 'DECIMAL'
                                        || $field['setting']['option']['fieldtype'] == 'FLOAT') {
                                        $value = (double)$value;
                                    }
                                    $data[$field['ismain']][$field['fieldname']] = $value;
                                }
                            }
                            $data[1]['cid'] = $new_id;
                            $data[1]['uid'] = $row['uid'];
                            $data[1]['catid'] = $row['catid'];
                            $data[1]['status'] = 9;
                            $data[1]['author'] = $row['author'];
                            $data[1]['inputtime'] = SYS_TIME;
                            $this->content_model->add_extend($data);
                        }
                    }
                }
            }
            exit(dr_json(1, lang('000')));
        } else {
            exit(dr_json(0, lang('357')));
        }

    }

    /**
     * 草稿箱管理
     */
    public function draft() {


        $table = $this->content_model->prefix.'_draft';

        if (IS_POST) {
            $ids = $this->input->post('ids', TRUE);
            if (!$ids) {
                exit(dr_json(0, lang('013')));
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
            break;
        }

        $page = max(1, (int) $this->input->get('page'));
        $total = $_GET['total'] ? intval($_GET['total']) : $this->link->where('uid', $this->uid)->count_all_results($table);
        if ($total) {
            $result = $this->link
                           ->where('uid', $this->uid)
                           ->limit(SITE_ADMIN_PAGESIZE, SITE_ADMIN_PAGESIZE * ($page - 1))
                           ->order_by('inputtime DESC, id DESC')
                           ->get($table)
                           ->result_array();
        } else {
            $result = array();
        }

        $this->template->assign(array(
            'menu' => $this->get_menu(array(
                lang('334') =>  APP_DIR.'/admin/home/draft',
                lang('mod-02') =>  APP_DIR.'/admin/home/add',
            )),
            'list' => $result,
            'total' => $total,
            'pages' => $this->get_pagination(dr_url(APP_DIR.'/home/draft'), $total)
        ));
        $this->template->display('content_draft.html');
    }

    ////////////////////内容维护部分/////////////////////////

    // 内容维护功能菜单
    private function _get_content_menu() {

        return array(
            lang('366') => APP_DIR.'/admin/home/content',
            lang('136') => APP_DIR.'/admin/home/url',
        );
    }

    // 提取缩略图
    public function content() {

        $cfile = SITE_ID.APP_DIR.$this->uid.$this->input->ip_address().'_content_thumb';

        if (IS_POST) {
            $catid = $this->input->post('catid');
            $thumb = $this->input->post('thumb');
            $query = $this->link;
            if (count($catid) > 1 || $catid[0]) {
                $query->where_in('catid', $catid);
                if (count($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category')) == count($catid)) {
                    $catid = 0;
                }
            } else {
                $catid = 0;
            }
            // 统计数量
            if ($thumb) {
                $total = $query->where('thumb=""')->count_all_results($this->content_model->prefix);
            } else {
                $total = $query->count_all_results($this->content_model->prefix.'_index');
            }
            $this->cache->file->save($cfile, array('thumb' => $thumb, 'catid' => $catid, 'total' => $total), 10000);
            if ($total) {
                $this->mini_msg(dr_lang('132', $total), dr_url(APP_DIR.'/home/content', array('todo' => 1)), 2);
            } else {
                $this->mini_msg(lang('133'));
            }
        }

        // 处理url
        if ($this->input->get('todo')) {
            $page = max(1, (int)$this->input->get('page'));
            $psize = 100; // 每页处理的数量
            $cache = $this->cache->file->get($cfile);
            $table = $this->content_model->prefix;
            if ($cache) {
                $total = $cache['total'];
                $catid = $cache['catid'];
                $thumb = $cache['thumb'];
            } else {
                $catid = 0;
                $thumb = 1;
                $total = $this->link->where('thumb=""')->count_all_results($table);
            }
            $tpage = ceil($total / $psize); // 总页数
            if ($page > $tpage) {
                // 更新完成删除缓存
                $this->cache->file->delete($cfile);
                $this->mini_msg(lang('360'), NULL, 1);
            }
            $module = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR);
            if ($catid) {
                $this->link->where_in('catid', $catid);
            }
            if ($thumb) {
                $this->link->where('thumb=""');
            }
            $data = $this->link
                         ->select('id,tableid')
                         ->limit($psize, $psize * ($page - 1))
                         ->order_by('id DESC')
                         ->get($table)
                         ->result_array();
            foreach ($data as $t) {
                $row = $this->link
                            ->select('content')
                            ->where('id', $t['id'])
                            ->get($table.'_data_'.$t['tableid'])
                            ->row_array();
                if ($row) {
                    $thumb = 0;
                    if (preg_match("/index\.php\?c=api&m=thumb&id=([0-9]+)&/U", $row['content'], $m)) {
                        $thumb = intval($m[1]);
                    } elseif (preg_match('/id=\"finecms_img_([0-9]+)\"/iU', $row['content'], $m)) {
                        $thumb = intval($m[1]);
                    } elseif (preg_match("/(src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|png))\\2/i", $row['content'], $m)) {
                        $thumb = $m[3];
                    }
                    if ($thumb) {
                        $this->link->where('id', $t['id'])->update($table, array('thumb' => $thumb));
                    }
                }
            }
            $this->mini_msg(dr_lang('135', "$tpage/$page"), dr_url(APP_DIR.'/home/content', array('todo' => 1, 'page' => $page + 1)), 2, 0);
        } else {
            $this->template->assign(array(
                'menu' => $this->get_menu($this->_get_content_menu()),
                'select' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'), 0, 'id="dr_synid" name=\'catid[]\' multiple style="width:200px;height:250px;"', ''),
            ));
            $this->template->display('content_thumb.html');
        }

    }

    /**
     * 更新URL
     */
    public function url() {

        $cfile = SITE_ID.APP_DIR.$this->uid.$this->input->ip_address().'_content_url';

        if (IS_POST) {
            $catid = $this->input->post('catid');
            $query = $this->link;
            if (count($catid) > 1 || $catid[0]) {
                $query->where_in('catid', $catid);
                if (count($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category')) == count($catid)) {
                    $catid = 0;
                }
            } else {
                $catid = 0;
            }
            // 统计数量
            $total = $query->count_all_results($this->content_model->prefix.'_index');
            $this->cache->file->save($cfile, array('catid' => $catid, 'total' => $total), 10000);
            if ($total) {
                $this->mini_msg(dr_lang('132', $total), dr_url(APP_DIR.'/home/url', array('todo' => 1)), 2);
            } else {
                $this->mini_msg(lang('133'));
            }
        }

        // 处理url
        if ($this->input->get('todo')) {
            $page = max(1, (int)$this->input->get('page'));
            $psize = 100; // 每页处理的数量
            $cache = $this->cache->file->get($cfile);
            if ($cache) {
                $total = $cache['total'];
                $catid = $cache['catid'];
            } else {
                $catid = 0;
                $total = $this->link->count_all_results($this->content_model->prefix);
            }
            $tpage = ceil($total / $psize); // 总页数
            if ($page > $tpage) {
                // 更新完成删除缓存
                $this->cache->file->delete($cfile);
                $this->mini_msg(lang('360'), NULL, 1);
            }
            $module = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR);
            $table = $this->content_model->prefix;
            if ($catid) {
                $this->link->where_in('catid', $catid);
            }
            $data = $this->link
                         ->limit($psize, $psize * ($page - 1))
                         ->order_by('id DESC')
                         ->get($table)
                         ->result_array();
            foreach ($data as $t) {
                $url = dr_show_url($module, $t);
                $this->link->update($table, array('url' => $url), 'id='.$t['id']);
                if ($module['extend']) {
                    $extend = $this->link
                                   ->where('cid', (int)$t['id'])
                                   ->order_by('id DESC')
                                   ->get($table.'_extend')
                                   ->result_array();
                    if ($extend) {
                        foreach ($extend as $e) {
                            $this->link
                                 ->where('id=',(int)$e['id'])
                                 ->update($table.'_extend', array('url' => dr_extend_url($module, $e)));
                        }
                    }
                }
            }
            $this->mini_msg(dr_lang('135', "$tpage/$page"), dr_url(APP_DIR.'/home/url', array('todo' => 1, 'page' => $page + 1)), 2, 0);
        } else {
            $this->template->assign(array(
                'menu' => $this->get_menu($this->_get_content_menu()),
                'select' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'), 0, 'id="dr_synid" name=\'catid[]\' multiple style="width:200px;height:250px;"', ''),
            ));
            $this->template->display('content_url.html');
        }
    }

}
