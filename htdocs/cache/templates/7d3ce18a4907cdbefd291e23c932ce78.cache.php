<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $meta_title; ?></title>
    <meta name="keywords" content="<?php echo $meta_keywords; ?>"/>
    <meta name="description" content="<?php echo $meta_description; ?>"/>
    <link href="<?php echo HOME_THEME_PATH; ?>css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo HOME_THEME_PATH; ?>css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo HOME_THEME_PATH; ?>css/lrtk.css">
    <!--[if lt IE 9]>
      <script src="<?php echo HOME_THEME_PATH; ?>js/html5shiv.min.js"></script
      <script src="<?php echo HOME_THEME_PATH; ?>js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <header class="topbc">
      <div class="container">
      <div class="row">
        <div class="col-md-4 col-sm-5 col-xs-12 col-lg-6">
          <div class="top_logo">
              <a href="<?php echo SITE_URL; ?>">
                 <img src="<?php echo HOME_THEME_PATH; ?>img/logo.jpg" class="respond-image" alt="江西SEO">
              </a>
          </div>
        </div>
        <div class="col-md-8 col-sm-7 col-xs-12 col-lg-6">
          <div class="pull-right">
            <div class="topicon  hidden-xs hidden-sm">
               <a class="weixin_image center-block" alt="微信" onmouseout="weixinhide()" onmouseover="weixin()"></a>
               <div id="weixinimg" class="weixinon">
                 <img src="<?php echo HOME_THEME_PATH; ?>img/weixin_1.png" alt="微信" height="" width="">
               </div>
            </div>
            <div class="topicon  hidden-xs hidden-sm">
               <a class="weibo_image center-block" rel="nofollow" href="http://weibo.com/1747959715/profile?topnav=1&wvr=6" alt="微博" ></a>
            </div>
            <div class="topicon  hidden-xs hidden-sm">
               <a class="qq_image center-block" rel="nofollow" href="http://wpa.qq.com/msgrd?v=3&uin=534840790&site=qq&menu=yes" alt="qq"  ></a>
            </div>
            <div class="topicon  hidden-xs hidden-sm">
               <a class="telphone_image center-block" alt="电话" ></a>
            </div>
            <p class="toptelphone visible-xs visible-sm">电话：</p>
            <p class="toptelphone">18270626598</p>
          </div>
        </div>
      </div>
    </div>
</header>
<!-- 这是头部 -->
<script type="text/javascript">
 function weixin(){
  $("#weixinimg").css('display','block');
 }
 function weixinhide(){
  $("#weixinimg").css('display','none');
 } 
</script>
      <nav class="navbar navbar-inverse">
        <div class="container">
           <div class="navbar-header">
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                 <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo SITE_URL; ?>">首页</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                  <li ><a href="<?php echo SITE_URL; ?>news/wangzhanyouhua/">网站优化</a></li>
                  <li ><a href="<?php echo SITE_URL; ?>news/wangluotuiguang/">网络推广</a></li>
                  <li ><a href="<?php echo SITE_URL; ?>news/taobaoyouhua/">淘宝优化</a></li>
                  <li ><a href="<?php echo SITE_URL; ?>news/waimaoyouhua/">外贸优化</a></li>
                  <li class="dropdown">
                    <a rel="nofollow" href="<?php echo SITE_URL; ?>/seofuwu/" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">SEO服务 <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a rel="nofollow" href="<?php echo SITE_URL; ?>seofuwu/seozz/">SEO整站优化服务</a></li>
                      <li><a rel="nofollow" href="<?php echo SITE_URL; ?>seofuwu/seoguwen/">SEO顾问服务</a></li>
                      <li><a rel="nofollow" href="<?php echo SITE_URL; ?>seofuwu/yidongsearch/">移动搜索优化服务</a></li>
                      <li><a rel="nofollow" href="<?php echo SITE_URL; ?>seofuwu/koubei/">口碑网络营销服务</a></li>
                      <li><a rel="nofollow" href="<?php echo SITE_URL; ?>seofuwu/seozh/">整合营销服务</a></li>
                      <li><a rel="nofollow" href="<?php echo SITE_URL; ?>seofuwu/seojc/">SEO监测服务</a></li>
                      <li><a rel="nofollow" href="<?php echo SITE_URL; ?>seofuwu/weixin/">微信高端定制开发</a></li>
                    </ul>
                   </li>
                   <li><a rel="nofollow" href="<?php echo SITE_URL; ?>news/pinpaianli/">品牌案例</a></li>
                   <li><a rel="nofollow" href="<?php echo SITE_URL; ?>contactus/">联系我们</a></li>
              </ul>
              <form class="navbar-form navbar-right hidden-xs hidden-sm" role="search" action="<?php echo SITE_URL; ?>index.php">
                 <div class="form-group">
                        <input name="c" type="hidden" value="so">
                        <input name="module" type="hidden" value="<?php echo APP_DIR; ?>">
                         <input type="text" class="form-control" placeholder="Search" name="keyword">
                       </div>
                       <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
              </form>
        </div>
      </nav>
      <!-- 这是导航条 -->