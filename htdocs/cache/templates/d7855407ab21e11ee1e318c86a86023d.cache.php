<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=7" />
    <title>搜索 - “<?php echo $keyword; ?>” - <?php echo $meta_title; ?></title>
    <link href="<?php echo HOME_THEME_PATH; ?>so/search.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/jquery.min.js"></script>
</head>
<body>
<div class="clr sr_body sr_list">
    <div class="sr_main">
        <div class="sr_head">
            <div class="l">
                <a href="<?php echo SITE_URL; ?>" class="on">首页</a>
                <?php $return = $this->list_tag("action=cache name=module"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
                - <a href="<?php echo $t['url']; ?>"><?php echo $t['name']; ?></a>
                <?php } } ?>
            </div>
        </div>

        <div class="wrap sr_logo">
            <a href="<?php echo SITE_URL; ?>index.php?c=so" class="l"><img src="<?php echo HOME_THEME_PATH; ?>img/logo1.png" /></a>
            <div class="l">
                <div class="sr_frm_box">
                    <form name="search" type="get">
                        <div class="sr_frmipt">
                            <input type="hidden" name="c" value="so"/>
                            <input type="hidden" name="module" value="<?php echo $dirname; ?>"/>
                            <input type="text" name="keyword" class="ipt" id="keyword" value="<?php echo $keyword; ?>">
                            <input type="submit" class="ss_btn" value="搜 索"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="brd1s"></div>
        <div class="wrap sr_lists">
            <div class="l">
                <div>
                    <span>网页结果</span>
                    <ul>
                        <?php if (is_array($module)) { $count=count($module);foreach ($module as $dir=>$t) { ?>
                        <li><a href="<?php echo dr_so_url($params, 'module', $dir, $urlrule); ?>" <?php if ($dir==$dirname) { ?>class="ac"<?php } ?>><?php echo $t['name']; ?>（<?php echo intval($t['search']['sototal']); ?>）</a></li>
                        <?php } } ?>
                    </ul>
                </div>
                <div class="bgno">
                    <span>按时间搜索</span>
                    <?php $time = array('不限'=>'', '一天内'=>strtotime(date('Y-m-d 00:00:00')).','.strtotime(date('Y-m-d 23:59:59')), '一周内'=>strtotime('-7 day').','.strtotime(date('Y-m-d 23:59:59')), '一月内'=>strtotime('-1 month').','.strtotime(date('Y-m-d 23:59:59')), '一年内'=>strtotime('-1 year').','.strtotime(date('Y-m-d 23:59:59'))); ?>
                    <ul>
                        <?php if (is_array($time)) { $count=count($time);foreach ($time as $i=>$t) { ?>
                        <li><a href="<?php echo dr_so_url($params, 'updatetime', $t, $urlrule); ?>" <?php if ($get['updatetime']==$t) { ?>class="ac"<?php } ?>><?php echo $i; ?></a></li>
                        <?php } } ?>
                    </ul>
                </div>
            </div>
            <div class="c wrap">
                <ul class="wrap">
                    <?php if ($searchid) {  $return = $this->list_tag("action=search module=$dirname id=$searchid total=$sototal catid=$catid order=$params[order] page=1 pagesize=10 urlrule=$urlrule"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
                    <li class="wrap">
                        <div>
                            <h5><a href="<?php echo $t['url']; ?>" target="_blank"><?php echo dr_keyword_highlight($t['title'], $keyword); ?></a></h5>
                            <p><?php echo dr_keyword_highlight($t['description'], $keyword); ?></p>
                        </div>
                        <div class="adds">时间：<?php echo $t['updatetime']; ?></div>
                    </li>
                    <?php } }  if (!$total) { ?>
                    <div class="norecord">对不起，没有找到任何记录！</div>
                    <?php }  } else { ?>
                    <div style="margin-top:20px">对不起，没有找到任何记录！</div>
                    <?php } ?>
                </ul>
                <div id="pages" class="text-c mg_t20"><?php echo $pages; ?></div>
            </div>
        </div>
    </div>
    <div class="sr_footer">
        <p class="cp">copyright&copy;2015 江西云新网络工作室  赣ICP备15001450号-1</p>
    </div>
    <script language="JavaScript">
        <!--
        $(document).ready(function(){
            $("#keyword").focus();
        });
        //-->
    </script>
</div>
</div>
</body>
</html><script type="text/javascript" src="http://www.jxseoer.com/index.php?c=cron"></script>