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

require_once FCPATH.'dayrui/core/D_Common.php';

class D_Module extends D_Common {

    public $dir; // 模块目录
    public $flag; // 可用推荐位
    public $link; // 当前模块数据库对象
    public $catid; // 当前会员可管理的栏目（id数组）
    public $search_model; // 搜索模型类
    public $is_category; // 是否开启栏目功能
    public $syn_content; // 同步内容到其他站点

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        if (!defined('DR_IS_SO')) {
            $this->_m_init();
        }
    }

    // 初始化模块
    protected function _m_init() {

        // 定义模块目录
        if (!$this->dir) {
            $this->dir = APP_DIR;
        }

        // 检查模块
        $module = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
        if (!$module) {
            // 模块缓存不存在时
            $data = $this->db->where('dirname', $this->dir)->get('module')->row_array();
            if ($data) {
                // 模块被禁用
                if ($data['disabled']) {
                    $this->admin_msg(lang('m-241'));
                }
                // 尝试生成缓存数据
                $this->load->model('module_model');
                $this->module_model->cache($this->dir);
                $module = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
                if (!$module) {
                    $this->admin_msg(lang('m-148'));
                }
            } else {
                // 模块没有安装
                $this->admin_msg(lang('m-321'));
            }
            unset($data);
        }

        // 模块常量
        define('MODULE_URL', $this->mobile ? SITE_URL.$this->dir.'/' : $module['url']);
        define('MODULE_HTML', $module['html']);
        define('MODULE_NAME', $module['name']);
        define('MODULE_TITLE', $module['setting']['seo']['module_title']);
        define('MODULE_THEME_PATH', MODULE_URL.'statics/'.$module['theme'].'/');

        // 数据库对象
        $this->link = $this->site[SITE_ID];
        // 设置模块模板
        $this->template->module($module['template']);

        // 模块语言文件
        $this->lang->load('module');
        $this->lang->load('category');
        // 模型加载
        $this->load->model('content_model');
        $this->load->model('category_model');

        // 初始化会员中心部分
        if (IS_MEMBER) {
            $this->_init_member($module);
        }
    }

    /**
     * 栏目权限验证
     *
     * @param	intval	$catid	栏目id
     * @param	string	$option	权限选项
     * @return	bool
     */
    public function is_category_auth($catid, $option) {

        if ($this->admin['adminid'] == 1 || !$catid || !$option) {
            return TRUE;
        }

        return $this->get_cache('module-'.SITE_ID.'-'.$this->dir, 'category', $catid, 'setting', 'admin', $this->admin['adminid'], $option);
    }

    /**
     * 栏目选择
     *
     * @param array			$data		栏目数据
     * @param intval/array	$id			被选中的ID，多选是可以是数组
     * @param string		$str		属性
     * @param string		$default	默认选项
     * @param intval		$onlysub	只可选择子栏目
     * @param intval		$is_push	是否验证权限
     * @param intval		$is_first	是否返回第一个可用栏目id
     * @return string
     */
    public function select_category($data, $id = 0, $str = '', $default = ' -- ', $onlysub = 0, $is_push = 0, $is_first = 0) {

        $cache = md5(dr_array2string($data).dr_array2string($id).$str.$default.$onlysub.$is_push.$is_first.$this->member['uid']);
        if ($cache_data = $this->get_cache_data($cache)) {
            return $cache_data;
        }

        $tree = array();
        $first = 0; // 第一个可用栏目
        $string = '<select '.$str.'>';

        if ($default) {
            $string.= "<option value='0'>$default</option>";
        }

        if (is_array($data)) {
            foreach($data as $t) {
                // 外部链接不显示
                if (isset($t['setting']['linkurl']) && $t['setting']['linkurl']) {
                    continue;
                }
                // 验证权限
                if ($is_push && $t['child'] == 0) {
                    if (IS_MEMBER && !$this->module_rule[$t['id']]['add']) {
                        continue;
                    } elseif (IS_ADMIN && !$this->is_category_auth($t['id'], 'add')
                        && !$this->is_category_auth($t['id'], 'edit')) {
                        continue;
                    }
                }
                // 选中操作
                $t['selected'] = '';
                if (is_array($id)) {
                    $t['selected'] = in_array($t['id'], $id) ? 'selected' : '';
                } elseif(is_numeric($id)) {
                    $t['selected'] = $id == $t['id'] ? 'selected' : '';
                }
                // 是否可选子栏目
                $t['html_disabled'] = !empty($onlysub) && $t['child'] != 0 ? 1 : 0;
                // 第一个可用子栏目
                if ($first == 0 && $t['child'] == 0) {
                    $first = $t['id'];
                }
                unset($t['permission'], $t['setting'], $t['catids'], $t['url']);
                $tree[$t['id']] = $t;
            }
        }

        $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $str2 = "<optgroup label='\$spacer \$name'></optgroup>";

        $this->load->library('dtree');
        $this->dtree->init($tree);

        $string.= $this->dtree->get_tree_category(0, $str, $str2);
        $string.= '</select>';

        $data = $is_first ? array($string, $first) : $string;
        if ($tree) {
            $this->set_cache_data($cache, $data, 7200);
        }

        return $data;
    }

    /**
     * 通过之后的审核状态值
     *
     * @param	intval	$uid	会员uid
     * @param	intval	$catid	栏目id
     * @param	intval	$status	原状态
     * @return	intval	新状态
     */
    protected function _get_verify_status($uid, $catid, $status) {

        $member = $this->member_model->get_base_member($uid);
        $verify = $this->get_cache('module-'.SITE_ID.'-'.$this->dir, 'category', $catid, 'permission', $member['markrule'], 'verify');
        if (!$verify) {
            return 9;
        }

        $data = $this->dcache->get('verify');

        return !isset($data[$verify]) || count($data[$verify]['verify']) <= $status ? 9 : $status + 1;
    }

    /**
     * 会员中心初始化
     */
    protected function _init_member($module) {

        $this->load->library('Dfield', array($this->dir));
        $this->field = $this->module['field'];

        // 当前会员组可用的推荐位
        $data = $module['setting']['flag'];
        if ($data) {
            foreach ($data as $i => $t) {
                if (isset($t[$this->member['mark']])
                    && $t[$this->member['mark']]
                    && $t['name']) {
                    $this->flag[$i] = $t;
                }
            }
        }

    }

    /**
     * 栏目下级或者同级栏目
     */
    private function _related_cat($mod, $catid) {

        if (!$mod) {
            return array(NULL, NULL);
        }

        $cat = $mod['category'][$catid];
        $related = $parent = array();

        if ($cat['child']) {
            $parent = $cat;
            foreach ($mod['category'] as $t) {
                if ($t['pid'] == $cat['id']) {
                    $related[] = $t;
                }
            }
        } elseif ($cat['pid']) {
            foreach ($mod['category'] as $t) {
                if ($t['pid'] == $cat['pid']) {
                    $related[] = $t;
                    if ($cat['child']) {
                        $parent = $cat;
                    } else {
                        $parent = $mod['category'][$t['pid']];
                    }
                }
            }
        } else {
            if (!$mod['category']) {
                return array(NULL, NULL);
            }
            $parent = $cat;
            foreach ($mod['category'] as $t) {
                if ($t['pid'] == 0) {
                    $related[] = $t;
                }
            }
        }

        return array($parent, $related);
    }

    ///////////////////////////////////////////////////////////////////

    /**
     * 模块内容购买页
     */
    protected function _show_buy() {

        $id = (int)$this->input->get('id');
        $name = (SITE_MOBILE === TRUE ? 'm' : '').'show'.$this->dir.SITE_ID.$id;
        $data = $this->get_cache_data($name);

        if (!$data) {
            $this->load->model('content_model');
            $data = $this->content_model->get($id);
            if (!$data) {
                exit('');
            }
            $mod = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
            if (!$mod) {
                exit('');
            }
        } else {
            $mod = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
        }

        $cat = $mod['category'][$data['catid']];

        // 格式化输出自定义字段
        $fields = $mod['field'];
        $fields = $cat['field'] ? array_merge($fields, $cat['field']) : $fields;
        $fields['inputtime'] = array('fieldtype' => 'Date');
        $fields['updatetime'] = array('fieldtype' => 'Date');
        $data = $this->field_format_value($fields, $data, $page);
        $table = SITE_ID.'_'.$this->dir.'_buy_'.(int)$this->member['tableid'];

        // 查找收费有收费字段
        $fees = '';
        foreach ($fields as $t) {
            if ($t['fieldtype'] == 'Fees') {
                $fees = $t['fieldname'];
                break;
            }
        }
        if (!$fees || !isset($data[$fees])) {
            exit('document.write("此模块内容没有收费字段");');
        } // 无收费字段

        if ($this->uid) {
            $is_buy = $this->link
                           ->where('cid', $id)
                           ->where('uid', (int)$this->uid)
                           ->count_all_results($table);
            $data['score'] = abs((int)$data[$fees][$this->markrule]);
            $data['is_buy'] = $data['score'] ? $is_buy : 1;
        } else {
            $data['is_buy'] = 0;
        }

        if (!$data['is_buy']
            && $this->input->get('action') == 'confirm') {
            // 会员未登录
            if (!$this->member) {
                $this->msg(lang('m-039'));
            }
            // 虚拟币检查
            if (-$data['score'] + $this->member['score'] < 0) {
                $this->msg(dr_lang('m-103', $data['score'], $this->member['score']));
            }
            // 扣减虚拟币
            $this->member_model->update_score(1, $this->uid, -$data['score'], '', 'lang,m-106');
            // 记录购买历史
            $this->link->insert($table, array(
                'cid' => $id,
                'uid' => $this->uid,
                'url' => $data['url'],
                'title' => $data['title'],
                'thumb' => $data['thumb'],
                'score' => $data['score'],
                'inputtime' => SYS_TIME
            ));
            $this->msg(lang('m-104'), $data['url'], 1);
        } else {
            $this->template->assign($data);
            ob_start();
            $this->template->display('show_buy.html');
            $html = ob_get_contents();
            ob_clean();
            $html = addslashes(str_replace(array("\r", "\n", "\t", chr(13)), array('', '', '', ''), $html));
            exit('document.write("'.$html.'");');
        }
    }

    /**
     * 模块首页
     */
    protected function _index() {

        $mod = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
        $file = FCPATH.'cache/index/'.$this->dir.'-'.SITE_ID.'.html';
        $path = $this->mobile ? '/mobiles/'.$mod['template'].'/' : '/templates/'.$mod['template'].'/';
        $name = is_file(FCPATH.$this->dir.$path.'index.html') ? 'index.html' : (is_file(FCPATH.'dayrui'.$path.'module.html') ? 'module.html' : 'index.html');

        // 系统开启静态首页、非手机端访问、静态文件不存在时，才生成文件
        if (SITE_MODULE_INDEX
            && !$this->template->mobile
            && !is_file($file)) {
            ob_start();
            $this->template->assign(dr_module_seo($mod));
            $this->template->assign('indexm', 1);
            $this->template->display($name);
            $html = ob_get_clean();
            file_put_contents($file, $html, LOCK_EX);
            echo $html;exit;
        } else {
            $this->template->assign(dr_module_seo($mod));
            $this->template->assign('indexm', 1);
            $this->template->display($name);
        }
    }

    /**
     * 模块栏目列表
     */
    protected function _category($id = 0, $dir = NULL, $page = 1) {

        $mod = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);

        if ($id) {
            $cat = $mod['category'][$id];
            if (!$cat) {
                $this->goto_404_page(dr_lang('cat-23', $id));
            }
        } elseif ($dir) {
            $id = $mod['category_dir'][$dir];
            $cat = $mod['category'][$id];
            if (!$cat) {
                // 无法通过目录找到栏目时，尝试多及目录
                foreach ($mod['category'] as $t) {
                    if ($t['setting']['urlrule']) {
                        $rule = $this->get_cache('urlrule', $t['setting']['urlrule']);
                        if ($rule['value']['catjoin']
                            && strpos($dir, $rule['value']['catjoin'])) {
                            $dir = trim(strchr($dir, $rule['value']['catjoin']), $rule['value']['catjoin']);
                            if (isset($mod['category_dir'][$dir])) {
                                $id = $mod['category_dir'][$dir];
                                $cat = $mod['category'][$id];
                                break;
                            }
                        }
                    }
                }
                // 返回无法找到栏目
                if (!$cat) {
                    $this->goto_404_page(dr_lang('cat-23', $dir));
                }
            }
        } else {
            $this->goto_404_page(lang('cat-23'));
        }

        list($parent, $related) = $this->_related_cat($mod, $id);

        $this->template->assign(dr_category_seo($mod, $cat, max(1, (int)$this->input->get('page'))));
        $this->template->assign(array(
            'cat' => $cat,
            'page' => $page,
            'catid' => $id,
            'params' => array('catid' => $id),
            'parent' => $parent,
            'related' => $related,
            'urlrule' => $this->mobile ? dr_mobile_category_url($this->dir, $catid, '{page}') : dr_category_url($catid, '{page}'),
        ));
        $this->template->display($cat['child']? $cat['setting']['template']['category'] : $cat['setting']['template']['list']);
    }

    /**
     * 模块内容页
     */
    protected function _show($id = NULL, $page = 1, $return = FALSE) {

        // 缓存查询结果
        $name = (SITE_MOBILE === TRUE ? 'm' : '').'show'.$this->dir.SITE_ID.$id;
        $data = $this->get_cache_data($name);

        if (!$data) {

            $this->load->model('content_model');
            $data = $this->content_model->get($id);
            if (!$data) {
                if ($return) {
                    return NULL;
                }
                $this->goto_404_page(dr_lang('mod-30', $id));
            }

            $mod = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
            if (!$mod) {
                if ($return) {
                    return NULL;
                }
                $this->msg(lang('m-148'));
            }

            // 检测转向字段
            $redirect = 0;
            foreach ($mod['field'] as $t) {
                if ($t['fieldtype'] == 'Redirect'
                    && $data[$t['fieldname']]) {
                    $this->link
                        ->where('id', $id)
                        ->set('hits', 'hits+1', FALSE)
                        ->update(SITE_ID.'_'.$this->dir);
                    if (MODULE_HTML) {
                        $redirect = 1;
                        $data['goto_url'] = $data[$t['fieldname']];
                        break;
                    } else {
                        redirect($data[$t['fieldname']], 'location', 301);
                        exit;
                    }
                }
            }

            $cat = $mod['category'][$data['catid']];

            // 处理关键字标签
            $data['tag'] = $data['keywords'];
            if ($data['keywords']) {
                $array = explode(',', $data['keywords']);
                $data['keywords'] = array();
                foreach ($array as $t) {
                    $data['keywords'][$t] = dr_tag_url($mod, $t);
                }
            }

            // 上一篇文章
            $data['prev_page'] = $this->link
                                      ->where('catid', $data['catid'])
                                      ->where('id<', $id)
                                      ->where('status', 9)
                                      ->order_by('id desc')
                                      ->limit(1)
                                      ->get($this->content_model->prefix)
                                      ->row_array();
            // 下一篇文章
            $data['next_page'] = $this->link
                                      ->where('catid', $data['catid'])
                                      ->where('id>', $id)
                                      ->where('status', 9)
                                      ->order_by('id asc')
                                      ->limit(1)
                                      ->get($this->content_model->prefix)
                                      ->row_array();
            // 缓存数据
            if ($data['uid'] != $this->uid) {
                $data = $this->set_cache_data($name, $data, $mod['setting']['show_cache']);
            }
            $this->set_cache_data('hits'.$this->dir.SITE_ID.$id, $data['hits'], $mod['setting']['show_cache']);

        } else {

            $mod = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
            $cat = $mod['category'][$data['catid']];

        }

        // 拒绝访问判断
        if (isset($cat['permission'][$this->markrule]['show'])
            && $cat['permission'][$this->markrule]['show']) {
            if ($return) {
                return NULL;
            }
            $this->msg(lang('m-338'));
        }

        // 格式化输出自定义字段
        $fields = $mod['field'];
        $fields = $cat['field'] ? array_merge($fields, $cat['field']) : $fields;
        $fields['inputtime'] = array('fieldtype' => 'Date');
        $fields['updatetime'] = array('fieldtype' => 'Date');
        $data = $this->field_format_value($fields, $data, $page);

        // 栏目下级或者同级栏目
        list($parent, $related) = $this->_related_cat($mod, $data['catid']);

        $this->template->assign($data);
        $this->template->assign(dr_show_seo($mod, $data, $page));
        $this->template->assign(array(
            'cat' => $cat,
            'page' => $page,
            'params' => array('catid' => $data['catid']),
            'parent' => $parent,
            'related' => $related,
            'urlrule' => $this->mobile ? dr_mobile_show_url($this->dir, $id, '{page}') : dr_show_url($mod, $data, '{page}'),
        ));

        $tpl = $cat['setting']['template']['show'];
        $tpl = $tpl ? $tpl : 'show.html';
        $tpl = isset($data['template']) && strpos($data['template'], '.html') !== FALSE ? $data['template'] : $tpl;

        if (!$return) {
            $this->template->display($tpl);
        }

        // 存在转向字段时处理方式
        return array($data, $redirect ? 'go' : $tpl);
    }

    /**
     * 模块扩展内容页
     */
    protected function _extend($id = NULL, $return = FALSE) {

        // 缓存查询结果
        $name = (SITE_MOBILE === TRUE ? 'm' : '').'extend'.$this->dir.SITE_ID.$id;
        $data = $this->get_cache_data($name);

        if (!$data) {

            $mod = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
            if (!$mod) {
                if ($return) {
                    return NULL;
                }
                $this->msg(lang('m-148'));
            }

            $this->load->model('content_model');
            $data = $this->content_model->get_extend($id);
            if (!$data) {
                if ($return) {
                    return NULL;
                }
                $this->goto_404_page(dr_lang('mod-45', $id));
            }

            $content = $this->get_cache_data('show'.$this->dir.SITE_ID.$data['cid']);
            if (!$content) {
                $content = $this->get_cache_data('extend-show'.$this->dir.SITE_ID.$data['cid']);
            }
            if (!$content) {
                $content = $this->content_model->get($data['cid']);
                $this->set_cache_data('extend-show'.$this->dir.SITE_ID.$data['cid'], $content, $mod['setting']['show_cache']);
            }
            if (!$content) {
                if ($return) {
                    return NULL;
                }
                $this->goto_404_page(dr_lang('mod-30', $data['cid']));
            }

            $data = $data + $content;
            $data['curl'] = $content['url'];

            // 检测转向字段
            $redirect = 0;
            foreach ($mod['extend'] as $t) {
                if ($t['fieldtype'] == 'Redirect'
                    && $data[$t['fieldname']]) {
                    if (MODULE_HTML) {
                        $redirect = 1;
                        $data['goto_url'] = $data[$t['fieldname']];
                        break;
                    } else {
                        redirect($data[$t['fieldname']], 'location', 301);
                        exit;
                    }
                }
            }

            $cat = $mod['category'][$data['catid']];

            // 上一篇
            $data['prev_page'] = $this->link
                                      ->where('cid', (int)$data['cid'])
                                      ->where('id<', $id)
                                      ->order_by('displayorder desc,id desc')
                                      ->limit(1)
                                      ->get($this->content_model->prefix.'_extend')
                                      ->row_array();
            // 下一篇
            $data['next_page'] = $this->link
                                      ->where('cid', (int)$data['cid'])
                                      ->where('id>', $id)
                                      ->order_by('displayorder desc,id asc')
                                      ->limit(1)
                                      ->get($this->content_model->prefix.'_extend')
                                      ->row_array();
            // 缓存数据
            if ($data['uid'] != $this->uid) {
                $data = $this->set_cache_data($name, $data, $mod['setting']['show_cache']);
            }

        } else {

            $mod = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
            $cat = $mod['category'][$data['catid']];

        }

        // 拒绝访问判断
        if ($cat['permission'][$this->markrule]['show']) {
            $this->msg(lang('m-338'));
        }

        // 格式化输出自定义字段
        $fields = $mod['field'];
        $fields = $cat['field'] ? array_merge($fields, $cat['field']) : $fields;
        $fields = $fields + $mod['extend'];
        $fields['inputtime'] = array('fieldtype' => 'Date');
        $data = $this->field_format_value($fields, $data, 1);

        // 栏目下级或者同级栏目
        list($parent, $related) = $this->_related_cat($mod, $data['catid']);

        $this->template->assign($data);
        $this->template->assign(dr_extend_seo($mod, $data));
        $this->template->assign(array(
            'cat' => $cat,
            'params' => array('catid' => $data['catid']),
            'parent' => $parent,
            'related' => $related,
            'urlrule' => $this->mobile ? dr_mobile_extend_url($this->dir, $id, '{page}') : dr_extend_url($mod, $data, '{page}'),
        ));

        $tpl = $cat['setting']['template']['extend'];
        $tpl = $tpl ? $tpl : 'extend.html';
        if (!$return) {
            $this->template->display($tpl);
        }

        // 存在转向字段时处理方式
        return array($data, $redirect ? 'go' : $tpl);
    }

    /**
     * 模块内容搜索页
     */
    protected function _search($call = '') {

        // 对指定模块搜索
        if ($call) {
            $this->dir = $call;
        }

        $mod = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
        if (isset($mod['setting']['search']['close'])
            && $mod['setting']['search']['close']) {
            if ($call) {
                return NULL;
            } else {
                $this->msg(lang('m-125'));
            }
        }

        // 加载搜索模型
        if (is_file(FCPATH.$this->dir.'models/Search_model.php')) {
            require_once FCPATH.$this->dir.'models/Search_model.php';
        } else {
            require_once FCPATH.'dayrui/models/Search_model.php';
        }
        $this->search_model = new Search_model();

        // 清除过期缓存
        $this->search_model->clear($mod['setting']['search']['cache']);

        // 搜索参数
        $get = $this->input->get(NULL, TRUE);
        $get = isset($get['rewrite']) ? dr_rewrite_decode($get['rewrite']) : $get;
        $id = $get['id'];
        $catid = (int)$get['catid'];
        $_GET['page'] = $get['page'];
        $get['keyword'] = str_replace(array('%', ' '), array('', '%'), $get['keyword']);
        unset($get['c'], $get['m'], $get['id'], $get['page']);

        // 关键字个数判断
        if ($get['keyword']
            && strlen($get['keyword']) < (int)$mod['setting']['search']['length']) {
            if ($call) {
                return NULL;
            } else {
                $this->msg(lang('mod-31'));
            }
        }

        if ($id) {
            // 读缓存数据
            $data = $this->search_model->get($id);
            $catid = $data['catid'];
            $data['get'] = $data['params'];
            if (!$data) {
                if ($call) {
                    return NULL;
                } else {
                    $this->msg(lang('mod-32'));
                }
            }
        } else {
            // 实时组合搜索条件
            $data = $this->search_model->set($get);
        }

        list($parent, $related) = $this->_related_cat($mod, $catid);

        $seoinfo = dr_category_seo($mod, $mod['category'][$catid], max(1, (int)$this->input->get('page')));

        if ($call) {
            $urlrule = $mod['setting']['search']['rewrite'] ? SITE_URL.'so-module-'.$this->dir.'-id-{id}-page-{page}.html' : SITE_URL.'index.php?c=so&module='.$this->dir.'&id={id}&page={page}';
            return array(
                'cat' => $mod['category'][$catid],
                'data' => $data,
                'caitd' => $catid,
                'parent' => $parent,
                'seoinfo' => $seoinfo,
                'keyword' => $get['keyword'],
                'urlrule' => str_replace('{id}', $data['id'], $urlrule),
                'sototal' => $data['contentid'] ? substr_count($data['contentid'], ',') + 1 : 0,
                'searchid' => $data['id'],
            );
        } else {
            $urlrule = $mod['setting']['search']['rewrite'] ? 'search-id-{id}-page-{page}.html' : 'index.php?c=search&id={id}&page={page}';
            $this->template->assign($seoinfo);
            $this->template->assign(array(
                'cat' => $mod['category'][$catid],
                'caitd' => $catid,
                'parent' => $parent,
                'related' => $related,
                'keyword' => $get['keyword'],
                'urlrule' => str_replace('{id}', $data['id'], $urlrule),
                'sototal' => $data['contentid'] ? substr_count($data['contentid'], ',') + 1 : 0,
                'searchid' => $data['id'],
            ));
            $this->template->assign($data);
            $this->template->display('search.html');
        }
    }

    /**
     * 顶级可用栏目
     */
    public function show_select_category() {

        $data = array();
        $category = $this->get_cache('module-'.SITE_ID.'-'.$this->dir, 'category');

        foreach ($category as $t) {
            if (!$t['child'] && $t['permission'][$this->member['mark']]['add']) {
                $pids = explode(',', $t['pids']);
                $pid = (int)$pids[1];
                if (isset($category[$pid])) {
                    $category[$pid]['mark'] = 1;
                    $data[$pid] = $category[$pid];
                }
            }
        }

        $this->template->assign(array(
            'id' => 2,
            'list' => $data
        ));
        $this->template->display('category_select.html');
    }

    /**
     * 可用子栏目
     */
    public function show_select_child() {

        $id = (int)$this->input->post('id');
        $data = array();
        $catid = (int)$this->input->post('catid');
        $category = $this->get_cache('module-'.SITE_ID.'-'.$this->dir, 'category');

        foreach ($category as $t) {
            if ($catid == $t['pid']) {
                $t['mark'] = $t['child'] ? 1 : $t['permission'][$this->member['mark']]['add'];
                $data[] = $t;
            }
        }

        $this->template->assign(array(
            'id' => $id + 1,
            'list' => $data
        ));
        $this->template->display('category_select.html');
    }

    //////////////////////////////////////////////////////////

    /**
     * 创建内容html文件
     */
    protected function _create_show_file($id, $member = TRUE) {

        if (!MODULE_HTML || !$id) {
            return NULL;
        }

        $this->clear_cache('show'.$this->dir.SITE_ID.$id);
        $mod = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
        list($data, $tpl) = $this->index($id, 1, TRUE);
        if (!$data) {
            return NULL;
        }

        $file = str_replace($mod['url'], '', $data['url']);
        if (strpos($file, 'index.php?c=show') !== FALSE) {
            return NULL;
        }

        $filepath = array();

        ob_start();
        $this->template->display($tpl);
        $dir = dirname($file);
        $file = basename($file);
        $html = ob_get_clean();
        if (SITE_ID > 1) {
            $dir = trim('html/'.SITE_ID.'/'.trim($dir, '.'), '/');
        }
        if ($dir != '.' && !file_exists(APPPATH.$dir)) {
            dr_mkdirs(APPPATH.$dir, TRUE);
        }
        // 判断是否为目录形式
        if (strpos($file, '.html') === FALSE
            && strpos($file, '.htm') === FALSE
            && strpos($file, '.shtml') === FALSE) {
            dr_mkdirs(APPPATH.$dir.'/'.$file, TRUE);
        }
        // 如果是目录就生成一个index.html
        if (is_dir(APPPATH.$dir.'/'.$file)) {
            $dir.= '/'.$file;
            $file = 'index.html';
        }
        if (!file_put_contents(APPPATH.$dir.'/'.$file, $html, LOCK_EX)) {
            return NULL;
        }

        $filepath[] = APPPATH.$dir.'/'.$file;
        // 表示存在内容分页
        if (isset($data['content_page'])
            && $data['content_page']) {
            foreach ($data['content_page'] as $i => $p) {
                $url = dr_show_url($mod, $data, $i);
                $file = str_replace($mod['url'], '', $url);
                list($cdata, $tpl) = $this->index($id, $i, TRUE);
                if ($cdata) {
                    ob_start();
                    $this->template->display($tpl);
                    $dir = dirname($file);
                    $file = basename($file);
                    $html = ob_get_clean();
                    if (SITE_ID > 1) {
                        $dir = trim('html/'.SITE_ID.'/'.trim($dir, '.'), '/');
                    }
                    if ($dir != '.' && !file_exists(APPPATH.$dir)) {
                        dr_mkdirs(APPPATH.$dir, TRUE);
                    }
                    // 判断是否为目录形式
                    if (strpos($file, '.html') === FALSE
                        && strpos($file, '.htm') === FALSE
                        && strpos($file, '.shtml') === FALSE) {
                        dr_mkdirs(APPPATH.$dir.'/'.$file, TRUE);
                    }
                    // 如果是目录就生成一个index.html
                    if (is_dir(APPPATH.$dir.'/'.$file)) {
                        $dir.= '/'.$file;
                        $file = 'index.html';
                    }
                    @file_put_contents(APPPATH.$dir.'/'.$file, $html, LOCK_EX);
                    $filepath[] = APPPATH.$dir.'/'.$file;
                }
            }
        }

        // 保存文件记录
        $this->content_model->set_html(1, $data['uid'], 0, $id, $data['catid'], $filepath);

        if ($mod['extend']) {
            $list = $this->link
                         ->select('id')
                         ->where('cid', (int)$id)
                         ->get(SITE_ID.'_'.$this->dir.'_extend')
                         ->result_array();
            if ($list) {
                $this->clear_cache('show-extend'.$this->dir.SITE_ID.$id);
                foreach ($list as $t) {
                    list($edata, $tpl) = $this->_extend($t['id'], TRUE);
                    if (!$edata) {
                        continue;
                    }
                    $file = str_replace($mod['url'], '', $edata['url']);
                    if (strpos($file, 'index.php?c=extend') !== FALSE) {
                        continue;
                    }
                    ob_start();
                    $this->template->display($tpl);
                    $dir = dirname($file);
                    $file = basename($file);
                    $html = ob_get_clean();
                    if (SITE_ID > 1) {
                        $dir = trim('html/'.SITE_ID.'/'.trim($dir, '.'), '/');
                    }
                    if ($dir != '.' && !file_exists(APPPATH.$dir)) {
                        dr_mkdirs(APPPATH.$dir, TRUE);
                    }
                    // 判断是否为目录形式
                    if (strpos($file, '.html') === FALSE
                        && strpos($file, '.htm') === FALSE
                        && strpos($file, '.shtml') === FALSE) {
                        dr_mkdirs(APPPATH.$dir.'/'.$file, TRUE);
                    }
                    // 如果是目录就生成一个index.html
                    if (is_dir(APPPATH.$dir.'/'.$file)) {
                        $dir.= '/'.$file;
                        $file = 'index.html';
                    }
                    if (!file_put_contents(APPPATH.$dir.'/'.$file, $html, LOCK_EX)) {
                        continue;
                    }
                    $filepath = array(APPPATH.$dir.'/'.$file);
                    // 保存文件记录
                    $this->content_model->set_html(2, $data['uid'], $data['id'], $t['id'], $data['catid'], $filepath);
                }
            }
        }

        return TRUE;
    }

    /**
     * 内容页生成静态
     */
    protected function _show_html() {

        if (!$this->member['adminid']) {
            $this->mini_msg('请在前台会员中心登录管理员号（若此模块绑定了域名，请在会员中心重新登录）');
        }
        if (!MODULE_HTML) {
            $this->mini_msg('当前模块没有开启生成功能');
        }

        $url = SITE_URL.$this->dir.'/index.php?c=show&m=html';
        $end = (int)$this->input->get('end');
        $page = (int)$this->input->get('p');
        $start = (int)$this->input->get('start');
        $catid = (int)$this->input->get('catid');
        $total = (int)$this->input->get('total');

        if (IS_POST) {
            $data = $this->input->post('data');
            $end = $data['end'];
            $catid = $data['catid'];
            $start = $data['start'];
        }

        if (!$page) $this->mini_msg('正在统计数据...', $url.'&p=1&catid='.$catid.'&start='.$start.'&end='.$end, 1);
        if ($page == 1 && !$total) {
            if ($catid && $cat = $this->get_cache('module-'.SITE_ID.'-'.$this->dir, 'category', $catid, 'childids')) {
                $this->link->where_in('catid', explode(',', $cat));
            }
            if ($start) {
                $end = $end ? $end : SYS_TIME;
                $this->link->where('`updatetime` between '.$start.' and '.$end);
            }
            $total = $this->link->count_all_results(SITE_ID.'_'.$this->dir);
            if (!$total) $this->mini_msg('没有找到相关数据');
            $this->mini_msg('需要生成 '.$total.' 条数据...', $url.'&p=1&total='.$total.'&catid='.$catid.'&start='.$start.'&end='.$end, 1);
        }

        $pagesize = 50;// 每次生成数量
        $count = ceil($total/$pagesize); // 计算总页数
        if ($page > $count) {
            $this->mini_msg('生成完成', '', 1);
        }

        if ($catid && $cat = $this->get_cache('module-'.SITE_ID.'-'.$this->dir, 'category', $catid, 'childids')) {
            $this->link->where_in('catid', explode(',', $cat));
        }
        if ($start) {
            $end = $end ? $end : SYS_TIME;
            $this->link->where('`updatetime` between '.$start.' and '.$end);
        }
        $list = $this->link
                     ->select('id')
                     ->limit($pagesize, $pagesize * ($page - 1))
                     ->get(SITE_ID.'_'.$this->dir)
                     ->result_array();
        $i = 0;
        if ($list) {
            foreach ($list as $t) {
                if ($this->_create_show_file($t['id'])) {
                    $i++;
                }
            }
        }

        $next = $page + 1;

        $this->mini_msg("共{$total}条数据，共生成{$count}页，每页生成{$pagesize}条，本次成功生成{$i}条，正在生成第{$next}页...", $url.'&p='.$next.'&total='.$total.'&catid='.$catid.'&start='.$start.'&end='.$end, 2, 0);

    }

    /**
     * 栏目页生成静态
     */
    protected function _category_html() {

        if (!$this->member['adminid']) {
            $this->mini_msg('请在前台会员中心登录管理员号（若此模块绑定了域名，请在会员中心重新登录）');
        }

        if (!MODULE_HTML) {
            $this->mini_msg('当前模块没有开启生成功能');
        }

        $url = SITE_URL.$this->dir.'/index.php?c=category&m=html';
        $key = (int)$this->input->get('key');
        $page = (int)$this->input->get('p');
        $name = 'category_html_'.$this->uid.$this->input->ip_address();
        $total = (int)$this->input->get('total');
        $category = $this->get_cache('module-'.SITE_ID.'-'.$this->dir, 'category');

        if (IS_POST) {
            $cat = array();
            $data = $this->input->post('data');
            foreach ($category as $t) {
                if ($data['catid']) {
                    if ($t['id'] == $data['catid']) {
                        $cat = explode(',', $t['childids']);
                        break;
                    }
                } else {
                    $cat[] = $t['id'];
                }
            }
            // 生成栏目缓存
            $this->cache->file->save($name, $cat, 99999);
        }

        $cat = $this->cache->file->get($name);
        if (!$cat) {
            $this->mini_msg('临时缓存数据不存在，请重新生成栏目');
        }

        $catid = (int)$cat[$key];
        if (!$catid) {
            $this->mini_msg('生成完毕', '', 1);
        }

        $this->_create_category_file($catid);

        if (!$total) {
            if (!$category[$catid]['child'] ||
                ($category[$catid]['child'] && $category[$catid]['setting']['template']['list'] == $category[$catid]['setting']['template']['category'])) {
                // 生成栏目的列表分页
                if ($category[$catid]['child']) {
                    $total = $this->link->where_in('catid', @implode(',', $category[$catid]['childids']))->count_all_results(SITE_ID.'_'.$this->dir);
                } else {
                    $total = $this->link->where('catid', $catid)->count_all_results(SITE_ID.'_'.$this->dir);
                }
                if (!$total) {
                    $this->mini_msg('栏目【'.$category[$catid]['name'].'】列表无数据，正在生成下一栏目...', $url.'&p=1&total=0&key='.($key+1), 2, 0);
                }
            } else {
                // 生成一个栏目的首页
                $this->mini_msg('栏目【'.$category[$catid]['name'].'】首页生成成功，正在生成下一栏目...', $url.'&p=1&total=0&key='.($key+1), 2, 0);
            }
        }

        $pagesize = (int)$category[$catid]['setting']['template']['pagesize'];// 每页数量
        $count = ceil($total/$pagesize); // 计算总页数
        if ($page > $count) {
            $this->mini_msg('栏目【'.$category[$catid]['name'].'】列表生成完毕，正在生成下一栏目...', $url.'&p=1&total=0&key='.($key+1), 2, 0);
        }

        $ok = $this->_create_category_file($catid, $page);

        $next = $page + 1;

        $this->mini_msg("栏目【{$category[$catid]['name']}】共{$total}条数据，共生成{$count}页，每页生成{$pagesize}条".($ok ? '' : "，本次生成失败")."，正在生成第{$next}页...", $url.'&p='.$next.'&total='.$total.'&key='.$key, 2, 0);

    }

    /**
     * 创建栏目的html文件
     */
    protected function _create_category_file($catid, $page = 0) {

        if (!MODULE_HTML || !$catid) {
            return NULL;
        }

        $mod = $this->get_cache('module-'.SITE_ID.'-'.$this->dir);
        $cat = $mod['category'][$catid];
        // 当此栏目是外链时，不生成！
        if ($cat['setting']['linkurl']) {
            return NULL;
        }
        $url = $page > 1 ? dr_category_url($mod, $cat, $page) : $cat['url'];
        if (!$url) {
            return NULL;
        }

        $file = str_replace($mod['url'], '', $url);
        $_GET['page'] = $page;
        if (strpos($file, 'index.php?c=category') !== FALSE) {
            return NULL;
        }

        ob_start();
        $this->_category($catid, NULL, $page);
        $dir = dirname($file);
        $file = basename($file);
        $html = ob_get_clean();
        if (SITE_ID > 1) {
            $dir = trim('html/'.SITE_ID.'/'.trim($dir, '.'), '/');
        }
        if ($dir != '.' && !file_exists(APPPATH.$dir)) {
            dr_mkdirs(APPPATH.$dir, TRUE);
        }
        // 判断是否为目录形式
        if (strpos($file, '.html') === FALSE
            && strpos($file, '.htm') === FALSE
            && strpos($file, '.shtml') === FALSE) {
            dr_mkdirs(APPPATH.$dir.'/'.$file, TRUE);
        }
        // 如果是目录就生成一个index.html
        if (is_dir(APPPATH.$dir.'/'.$file)) {
            $dir.= '/'.$file;
            $file = 'index.html';
        }
        if (!file_put_contents(APPPATH.$dir.'/'.$file, $html, LOCK_EX)) {
            return NULL;
        }
        // 保存文件记录
        $this->content_model->set_html(3, 0, 0, $catid, $catid, array(APPPATH.$dir.'/'.$file));
        // 生成栏目的第一页
        if ($page <= 1) {
            $purl = dr_category_url($mod, $cat, '{page}');
            $pfile = basename(str_replace(array($mod['url'], '{page}'), array('', 1), $purl));
            // 判断是否为目录形式
            if (strpos($pfile, '.html') === FALSE
                && strpos($pfile, '.htm') === FALSE
                && strpos($pfile, '.shtml') === FALSE) {
                dr_mkdirs(APPPATH.$dir.'/'.$pfile, TRUE);
            }
            // 如果是目录就生成一个index.html
            if (is_dir(APPPATH.$dir.'/'.$pfile)) {
                $dir.= '/'.$pfile;
                $pfile = 'index.html';
            }
            file_put_contents(APPPATH.$dir.'/'.$pfile, $html, LOCK_EX);
            $this->content_model->set_html(3, 0, 0, $catid, $catid, array(APPPATH.$dir.'/'.$pfile));
        }

        return TRUE;
    }


    /**
     * 创建栏目html方法
     */
    public function create_list_html() {
        $this->_create_category_file((int)$this->input->get('id'), 1);
    }

    // 会员中心获取可用字段
    protected function _get_member_field($catid) {

        // 主字段
        $myfield = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'field');

        // 指定栏目字段
        $category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid, 'field');
        if ($category) {
            $content = $myfield['content'];
            unset($myfield['content']);
            $myfield = array_merge($myfield, $category);
            if ($content) {
                $myfield['content'] = $content;
            }
            unset($content, $field);
        }

        return $myfield;
    }
}