<footer class="min_footer">
      <div class="container">
        <div class="row">
          <div class="col-xs-12 col-md-4 col-lg-4">
            <ul class="footerliebiao">
              <li>
                <h4>SEO优化服务</h4>
                <div class="progress">
                   <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
                   <span class="sr-only">60% Complete</span>
                   </div>
                </div>
              </li>
              <li><a rel="nofollow" href="<?php echo SITE_URL; ?>seofuwu/seozz/" class="footerlist">SEO整站优化服务</a></li>
              <li><a rel="nofollow" href="<?php echo SITE_URL; ?>seofuwu/seoguwen/" class="footerlist">SEO顾问服务</a></li>
              <li><a rel="nofollow" href="<?php echo SITE_URL; ?>seofuwu/yidongsearch/" class="footerlist">移动搜索优化服务</a></li>
              <li><a rel="nofollow" href="<?php echo SITE_URL; ?>seofuwu/koubei/" class="footerlist">口碑网络营销服务</a></li>
              <li><a rel="nofollow" href="<?php echo SITE_URL; ?>seofuwu/seojc/" class="footerlist">SEO监测服务</a></li>
            </ul>
          </div>
          <div class="col-xs-12 col-md-4 col-lg-4">
            <ul class="footerliebiao">
              <li>
                <h4>SEO知识</h4>
                <div class="progress">
                   <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
                   <span class="sr-only">60% Complete</span>
                   </div>
                </div>
              </li>
                  <li ><a href="<?php echo SITE_URL; ?>news/wangzhanyouhua/" class="footerlist">网站优化</a></li>
                  <li ><a href="<?php echo SITE_URL; ?>news/wangluotuiguang/" class="footerlist">网络推广</a></li>
                  <li ><a href="<?php echo SITE_URL; ?>news/taobaoyouhua/" class="footerlist">淘宝优化</a></li>
                  <li ><a href="<?php echo SITE_URL; ?>news/waimaoyouhua/" class="footerlist">外贸优化</a></li>
                  <li ><a href="<?php echo SITE_URL; ?>news/weixinyingxiao/" class="footerlist">微信营销</a></li>
            </ul>
          </div>
          <div class="col-xs-12 col-md-4 col-lg-4">
            <ul class="footerliebiao">
              <li>
                <h4>关于我们</h4>
                <div class="progress">
                   <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
                   <span class="sr-only">60% Complete</span>
                   </div>
                </div>
              </li>
              <li><a rel="nofollow" href="<?php echo SITE_URL; ?>news/pinpaianli/" class="footerlist">品牌案例</a></li>
              <li><a rel="nofollow" href="<?php echo SITE_URL; ?>contactus/" class="footerlist">联系我们</a></li>
              <li><a rel="nofollow" href="<?php echo SITE_URL; ?>guanyuwomen/tuanduijianjie/" class="footerlist">团队简介</a></li>
              <li><a rel="nofollow" href="<?php echo SITE_URL; ?>guanyuwomen/rencaizhaopin/" class="footerlist">人才招聘</a></li>
              <li><a rel="nofollow" href="<?php echo SITE_URL; ?>guanyuwomen/rencailinian/" class="footerlist">人才理念</a></li>
            </ul>
          </div>
          <div class="col-xs-12 col-md-12 col-lg-12">
            <h4>友情链接</h4>
            <div class="progress">
                 <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%;">
                <span class="sr-only">60% Complete</span>
                </div>
            </div>
            <p>
            <?php $return = $this->list_tag("action=navigator type=4"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
            <a class="lianjie" href="<?php echo $t['url']; ?>" title="<?php echo $t['title']; ?>" <?php if ($t['target']) { ?>target="_blank"<?php } ?>><?php echo $t['name']; ?></a>
             <?php } } ?>
            </p>
          </div>
        </div>
      </div>
      <div class="dibu">
             <p class="dibu_tittle">copyright&copy;2015 江西云新网络工作室  赣ICP备15001450号-1
             <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1255874274'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/z_stat.php%3Fid%3D1255874274%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script>
             <script>
              var _hmt = _hmt || [];
              (function() {
                var hm = document.createElement("script");
                hm.src = "//hm.baidu.com/hm.js?5afef68c5a7db8b6fb607d73b996c65e";
                var s = document.getElementsByTagName("script")[0]; 
                s.parentNode.insertBefore(hm, s);
              })();
              </script>
             </p>
          </div>
          <a href="#0" class="cd-top">Top</a>
    </footer>
    <!-- 这是底部 -->
    <script src="<?php echo HOME_THEME_PATH; ?>js/jquery.min.js"></script>
    <script src="<?php echo HOME_THEME_PATH; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo HOME_THEME_PATH; ?>js/lrtk.js"></script> 
  </body>
</html>