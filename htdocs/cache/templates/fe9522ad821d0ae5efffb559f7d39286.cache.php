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
		    		 <?php $return = $this->list_tag("action=module catid=$catid field=title,url,updatetime,author,description order=updatetime page=1"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
		    			<article class="article-content">
		    				<div class="post-head">
		    					<h3 class="post-title"><a href="<?php echo $t['url']; ?>"><?php echo $t['title']; ?></a></h3>
		    				</div>
		    				<div class="post-meta">
		    					<span class="author">作者：<?php echo $t['author']; ?></span>
		    					.  <time class="post-data"><?php echo $t['updatetime']; ?></time>
		    				</div>
		    			<div class="post-content">
		    				<?php echo $t['description']; ?>
		    			</div>
		    			<div class="post-link">
		    				<a href="<?php echo $t['url']; ?>" role="button" class="btn btn-danger">阅读全文</a>
		    			</div>
		    		    </article>
		    		   <?php } }  echo $error; ?>
		    		    <nav style="text-align:center;">
						  <ul class="pagination" >
						    <?php $return = $this->list_tag("action=module module=news catid=$catid page=1 pagesize=1"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
						    <li><?php echo $pages; ?></li>
	                         <?php } } ?>
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
						  <?php $return = $this->list_tag("action=module module=news flag=5  field=title,url order=updatetime num=5"); if ($return) extract($return); $count=count($return); if (is_array($return)) { foreach ($return as $key=>$t) { ?>
						  <a href="<?php echo $t['url']; ?>" class="list-group-item"><?php echo $t['title']; ?></a>
                          <?php } } ?>
		    		</aside>
		    	</div>
		    </div>
    </section>
<?php if ($fn_include = $this->_include("footer.html", "/")) include($fn_include); ?><script type="text/javascript" src="http://www.jxseoer.com/index.php?c=cron"></script>