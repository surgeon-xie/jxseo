<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=7" />
    <title>搜索-<?php echo SITE_NAME; ?></title>
    <link href="<?php echo HOME_THEME_PATH; ?>so/so.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo MEMBER_PATH; ?>statics/js/jquery.min.js"></script>
</head>
<body>
<div class="clr sr_body">
    <div class="sr_main">
        <div class="sr_head">
            <div class="l" id="search">
                <a href="<?php echo SITE_URL; ?>" class="on">首页</a>
                <?php $return = $this->list_tag("action=cache name=module"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
                - <a href="<?php echo $t['url']; ?>"><?php echo $t['name']; ?></a>
                <?php } } ?>
            </div>
        </div>
        <div class="sr_logo">
            <a href="javascript:;"><img src="<?php echo HOME_THEME_PATH; ?>img/logo1.png"/></a>
        </div>

        <form name="search" type="get">
            <input type="hidden" name="c" value="so"/>
            <input type="hidden" name="module" value="<?php echo APP_DIR; ?>"/>
            <div class="sr_frm">
                <div class="sr_frm_box">
                    <div class="sr_frmipt"><input type="text" id="keyword" name="keyword" class="ipt"><input type="submit" class="ss_btn" value="搜 索"></div>
                </div>
            </div>
        </form>
        <script language="JavaScript">
            <!--
            $(document).ready(function(){
                $("#keyword").focus();
            });
            //-->
        </script>
        <div class="sr_footer">
            <p class="cp">copyright&copy;2015 江西云新网络工作室  赣ICP备15001450号-1</p>
        </div>
    </div>
</div>
</body>
</html><script type="text/javascript" src="http://www.jxseoer.com/index.php?c=cron"></script>