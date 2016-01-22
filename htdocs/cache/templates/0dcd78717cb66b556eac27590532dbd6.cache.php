<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
    <div class="jumbotron">
         <div class="container">
        <h2 style="text-align:center;">客户案例</h2>
        <p style="text-indent:2em;">无论是整体框架，还是局部，我们都力求在每一个细节中做到完美。SEO是指从自然搜索结果获得网站流量的技术和过程，是在了解搜索引擎自然排名机制的基础上， 对网站进行内部及外部的调整优化， 改进网站在搜索引擎中的关键词自然排名， 获得更多流量， 从而达成网站销售及品牌建设的目标。</p>
        <p><a class="btn btn-danger btn-md" href="#" role="button">了解更多</a></p>
      </div>
    </div>
    <!-- 这是幕布 -->
        <div class="container">
          <div class="row">
          <?php $return = $this->list_tag("action=module module=news catid=$catid field=thumb,title,url order=thumb,updatetime num=8"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
              <div class="col-xs-6 col-md-3 col-lg-3">
                <div class="thumbnail">
                  <a href="<?php echo $t['url']; ?>"><img class="img-responsive" src="<?php echo dr_thumb($t['thumb'],253,194); ?>" alt="<?php echo $t['title']; ?>" title="<?php echo $t['title']; ?>" width="253" height="90"></a>
                  <div class="caption">
                  <a href="<?php echo $t['url']; ?>" class="case_link"><?php echo $t['title']; ?></a>
                  </div>
                </div>
              </div>
            <?php } } ?>
            </div>
          </div>
         <nav style="text-align:center;">
              <ul class="pagination" >
               <?php $return = $this->list_tag("action=module module=news catid=$catid page=1 pagesize=1"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
                <li><?php echo $pages; ?></li>
                <?php } } ?>
              </ul>
            </nav>
        <!-- 这是主要内容 -->
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?><script type="text/javascript" src="http://www.jxseoer.com/index.php?c=cron"></script>