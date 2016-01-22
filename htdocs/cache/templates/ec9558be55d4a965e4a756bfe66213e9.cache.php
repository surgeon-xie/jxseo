<?php if ($fn_include = $this->_include("header.html", "/")) include($fn_include); ?>
    <div class="jumbotron">
         <div class="container">
			  <h2 style="text-align:center;">SEO搜索引擎优化</h2>
			  <p style="text-indent:2em;">SEO由英文Search Engine Optimization缩写而来， 中文意译为“搜索引擎优化”。SEO是指从自然搜索结果获得网站流量的技术和过程，是在了解搜索引擎自然排名机制的基础上， 对网站进行内部及外部的调整优化， 改进网站在搜索引擎中的关键词自然排名， 获得更多流量， 从而达成网站销售及品牌建设的目标。</p>
			  <p><a class="btn btn-danger btn-md" href="#" role="button">了解更多</a></p>
	    </div>
    </div>
    <section >
		    <div class="container">
		    	<div class="row">
		    		<main class="col-lg-8 col-xs-12 col-md-8">
            <p><a href="<?php echo SITE_URL; ?>" class="content-link">首页</a> > <?php echo dr_catpos($catid, ' > '); ?> ><?php echo $title; ?></p>
		    			<article class="article-content">
		    				<div class="post-head">
		    					<h3 class="post-title"><?php echo $title; ?></h3>
		    				</div>
		    				<div class="post-meta">
		    					<span class="author">作者：<?php echo $author; ?></span>
		    					.  <time class="post-data"><?php echo $updatetime; ?></time>
		    				</div>
		    			<div class="post-content">
		    				<?php echo $content; ?>
		    			</div>
              <?php if (is_array($content_page)) { $count=count($content_page);foreach ($content_page as $i=>$t) {  if ($page==$i) { ?>
                <span><?php echo $i; ?></span>
                <?php } else { ?>
                <a href="<?php echo dr_content_page_url($urlrule, $i); ?>" title="<?php echo $t['title']; ?>"><?php echo $i; ?></a>
                <?php }  } } ?>
		    		    </article>
                <nav>
                  <ul class="pager">
                    <li class="previous"><?php if ($prev_page) { ?><a href="<?php echo $prev_page['url']; ?>"><span aria-hidden="true">&larr;</span><?php echo $prev_page['title']; ?></a><?php } else { ?><a>没有了</a><?php } ?></li>
                    <li class="next"><?php if ($next_page) { ?><a href="<?php echo $next_page['url']; ?>"><?php echo $next_page['title']; ?><span aria-hidden="true">&rarr;</span></a><?php } else { ?><a>没有了</a><?php } ?></li>
                  </ul>
                </nav>
		    		</main>
		    		<aside class="col-lg-4 col-md-4 col-xs-12">
		    			<div class="list-group">
						  <a href="#" class="list-group-item active" role="button" >
						    SEO服务
						  </a>
						  <a href="<?php echo SITE_URL; ?>seofuwu/seozz/" class="list-group-item">SEO整站优化</a>
						  <a href="<?php echo SITE_URL; ?>seofuwu/seoguwen/" class="list-group-item">SEO顾问服务</a>
						  <a href="<?php echo SITE_URL; ?>seofuwu/yidongsearch/" class="list-group-item">移动搜索化</a>
						  <a href="<?php echo SITE_URL; ?>seofuwu/koubei/" class="list-group-item">口碑网络营销</a>
						  <a href="<?php echo SITE_URL; ?>seofuwu/seojc/" class="list-group-item">SEO工具监测</a>
						</div>
						<div class="list-group">
						  <a href="<?php echo SITE_URL; ?>news/pinpaianli/" class="list-group-item active" role="button" >
						    最新案例
						  </a>
						   <?php $return = $this->list_tag("action=module  module=news catid=35 field=title,url order=updatetime num=5"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
						  <a href="<?php echo $t['url']; ?>" class="list-group-item"><?php echo $t['title']; ?></a>
                           <?php } } ?>
						</div>
						<div class="list-group">
						  <a href="<?php echo MODULE_URL; ?>" class="list-group-item active" role="button" >
						    热门文章
						  </a>
						  <?php $return = $this->list_tag("action=module module=news flag=5  field=title,url,updatetime order=updatetime num=5"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
						  <a href="<?php echo $t['url']; ?>" class="list-group-item"><?php echo $t['title']; ?></a>
                          <?php } } ?>
		    		</aside>
		    	</div>
		    </div>
    </section>
<?php if ($fn_include = $this->_include("footer.html", "/")) include($fn_include); ?><script type="text/javascript" src="http://www.jxseoer.com/index.php?c=cron"></script>