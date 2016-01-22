
INSERT INTO `{dbprefix}1_navigator` VALUES(1, 0, '0', 0, '新闻', '', '/news/', '', 1, 'module-news-0', 0, 0, '1', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(2, 0, '0', 0, '图片', '', '/photo/', '', 1, 'module-photo-0', 0, 0, '2', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(3, 0, '0', 0, '视频', '', '/video/', '', 1, 'module-video-0', 0, 0, '3', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(4, 0, '0', 0, '专题', '', '/special/', '', 1, 'module-special-0', 0, 0, '4', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(5, 0, '0', 0, '租房', '', '/fang/', '', 1, 'module-fang-0', 0, 0, '5', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(8, 0, '0', 0, '空间', '', '/space/', '', 1, '', 0, 0, '8', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(9, 0, '0', 0, '图书', '', '/book/', '', 1, 'module-book-0', 0, 0, '9', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(10, 0, '0', 0, '下载', '', '/down/', '', 1, 'module-down-0', 0, 0, '10', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(11, 0, '0', 0, '会员', '', '/member/', '', 1, '', 0, 0, '11', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(13, 0, '0', 2, '第一个', 'FineCMS海豚版功能介绍和视频演示', 'http://www.dayrui.com/shipin/', '', 1, '', 0, 0, '13', 1, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(14, 0, '0', 2, '安装', '系统安装视频演示', 'http://www.dayrui.com/shipin/', '', 1, '', 0, 0, '14', 1, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(15, 0, '0', 2, '模块操作', '模块的使用演示', 'http://www.dayrui.com/shipin/', '', 1, '', 0, 0, '15', 1, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(16, 0, '0', 2, '微信', '高级版微信基本操作', 'http://www.dayrui.com/shipin/', '', 1, '', 0, 0, '16', 1, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(17, 0, '0', 2, '比较', '大众版与高级版的不同之处', 'http://www.dayrui.com/bijiao/', '', 1, '', 0, 0, '17', 1, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(18, 0, '0', 1, '第一张幻灯', 'FineCMS海豚版正式发布', 'http://www.dayrui.com/cms/', 'http://www.dayrui.com/assets/p/v21.jpg', 1, '', 0, 0, '18', 1, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(19, 0, '0', 1, '第二张幻灯', '强大而灵活的自定义字段功能', 'http://www.dayrui.com/cms/', 'http://www.dayrui.com/assets/p/v23.jpg', 1, '', 0, 0, '19', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(33, 0, '0', 4, '技术支持', '', 'http://www.dayrui.net', '', 1, '', 0, 0, '33', 1, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(31, 0, '0', 4, '天睿程序设计', '', 'http://www.dayrui.com', '', 1, '', 0, 0, '31', 1, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(32, 0, '0', 4, 'FineCMS', '', 'http://www.dayrui.com/cms/', '', 1, '', 0, 0, '32', 1, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(29, 0, '0', 3, '全站搜索', '', '/index.php?c=so', '', 1, '', 0, 0, '29', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(30, 0, '0', 4, '天睿云计算', '', 'http://www.dayrui.com', '', 1, '', 0, 0, '30', 1, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(26, 0, '0', 3, '官方网站', '', 'http://www.dayrui.com', '', 1, '', 0, 0, '26', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(27, 0, '0', 3, '技术论坛', '', 'http://www.dayrui.net', '', 1, '', 0, 0, '27', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(28, 0, '0', 3, '网站地图', '', '/index.php?c=sitemap', '', 1, '', 0, 0, '28', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(25, 0, '0', 3, '关于我们', '', 'http://www.fc2.com/index.php?c=page&id=1', '', 1, 'page-1', 0, 0, '25', 0, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(34, 0, '0', 4, '主机域名', '', 'http://cloud.dayrui.com', '', 1, '', 0, 0, '34', 1, 0);
INSERT INTO `{dbprefix}1_navigator` VALUES(35, 0, '0', 4, '模板商店', '', 'http://store.dayrui.com', '', 1, '', 0, 0, '35', 1, 0);


INSERT INTO `{dbprefix}urlrule` VALUES(NULL, 0, '单页默认规则（不能用于模块）', 'a:8:{s:4:\\"page\\";s:11:\\"{pdirname}/\\";s:9:\\"page_page\\";s:22:\\"{pdirname}/{page}.html\\";s:4:\\"list\\";s:0:\\"\\";s:9:\\"list_page\\";s:0:\\"\\";s:4:\\"show\\";s:0:\\"\\";s:9:\\"show_page\\";s:0:\\"\\";s:6:\\"extend\\";s:0:\\"\\";s:7:\\"catjoin\\";s:1:\\"/\\";}');
INSERT INTO `{dbprefix}urlrule` VALUES(NULL, 0, '单页默认规则（用于模块）', 'a:8:{s:4:\\"page\\";s:16:\\"page/{pdirname}/\\";s:9:\\"page_page\\";s:27:\\"page/{pdirname}/{page}.html\\";s:4:\\"list\\";s:0:\\"\\";s:9:\\"list_page\\";s:0:\\"\\";s:4:\\"show\\";s:0:\\"\\";s:9:\\"show_page\\";s:0:\\"\\";s:6:\\"extend\\";s:0:\\"\\";s:7:\\"catjoin\\";s:1:\\"/\\";}');
INSERT INTO `{dbprefix}urlrule` VALUES(NULL, 1, '模块默认规则', 'a:8:{s:4:\\"page\\";s:0:\\"\\";s:9:\\"page_page\\";s:0:\\"\\";s:4:\\"list\\";s:11:\\"{pdirname}/\\";s:9:\\"list_page\\";s:23:\\"{pdirname}/p{page}.html\\";s:4:\\"show\\";s:28:\\"{pdirname}/{y}/{m}/{id}.html\\";s:9:\\"show_page\\";s:35:\\"{pdirname}/{y}/{m}/{id}/{page}.html\\";s:6:\\"extend\\";s:25:\\"{pdirname}/read/{id}.html\\";s:7:\\"catjoin\\";s:1:\\"/\\";}');

REPLACE INTO `{dbprefix}1_page` (`id`, `module`, `pid`, `pids`, `name`, `dirname`, `pdirname`, `child`, `childids`, `thumb`, `title`, `keywords`, `description`, `content`, `attachment`, `template`, `urlrule`, `urllink`, `getchild`, `show`, `url`, `setting`, `displayorder`) VALUES(1, '', 0, '0', '关于我们', 'guanyuwomen', '', 1, '1,2,3,4,5', '', '', '', '', '', '', 'page.html', 0, '', 1, 1, 'index.php?c=page&id=1', '', 0);
REPLACE INTO `{dbprefix}1_page` (`id`, `module`, `pid`, `pids`, `name`, `dirname`, `pdirname`, `child`, `childids`, `thumb`, `title`, `keywords`, `description`, `content`, `attachment`, `template`, `urlrule`, `urllink`, `getchild`, `show`, `url`, `setting`, `displayorder`) VALUES(2, '', 1, '0,1', 'FineCMS v1.x', 'v1', 'guanyuwomen/', 0, '2', '', 'FineCMS v1.x 轻量级网站管理系统', 'FineCMS,网站建设,内容管理系统', 'FineCMSv1（简称v1）是一款基于PHP+MySql开发的内容管理系统，采用MVC设计模式实现业务逻辑与表现层的适当分离，使网页设计师能够轻松设计出理想的模板，插件化方式开发功能易用便于扩展，支持自定义内容模型和会员...', '<p>FineCMS v1（简称v1）是一款基于PHP+MySql开发的内容管理系统，采用MVC设计模式实现业务逻辑与表现层的适当分离，使网页设计师能够轻松设计出理想的模板， 插件化方式开发功能易用便于扩展，支持自定义内容模型和会员模型，并且可以自定义字段，可面向中小型站点提供重量级网站建设解决方案。</p><p><strong>功能特点</strong><br /></p><p>1、自定义模型和字段<br />超强的自定义模型和字段功能则把系统灵活度发挥到了极致，不用编程就实现各种信息发布和检索</p><p>2、多国语言支持<br />系统自带语言有简体中文、繁体中文、英文，其他语言扩展相当方便</p><p>3、支持多站点站群管理<br />持多个站点管理及分站功能，多站只需绑定域名到根目录，使用相当方便</p><p>4、负载能力强<br />从缓存技术、数据库设计、代码优化等多个角度入手进行优化，支持百万级数据量</p><p>5、模板制作方便<br />采用MVC设计模式实现了程序与模板完全分离，灵活的模板标签能完全显示全站信息</p><p>6、支持文章内链<br />有助于提高搜索引擎对网站的爬行索引效率，支持Tag自动内链到文章</p><p>7、表单功能<br />用于拓展内容模型和会员模型，如报名、评论、询价、咨询等</p><p>8、推荐位功能<br />推荐位功能可以让编辑随时把信息推送至指定位置，操作简单实用</p><p>9、 文字块功能<br />把一些小段内容放在文字块中，支持HTML代码和图片上传</p><p>10、自定义URL规则<br />可以完全自定义URL地址规则，包括栏目、内容及自定义页</p><p>11、功能插件化<br />按照官方提供的插件为蓝本，用户可开发出属于自己的插件</p><p>12、SEO处理<br />个性化设置每个栏目的标题标签、描述标签、关键词标签，自动生成百度谷歌网站地图</p>', '', 'page.html', 0, '', 1, 1, 'index.php?c=page&id=2', '', 0);
REPLACE INTO `{dbprefix}1_page` (`id`, `module`, `pid`, `pids`, `name`, `dirname`, `pdirname`, `child`, `childids`, `thumb`, `title`, `keywords`, `description`, `content`, `attachment`, `template`, `urlrule`, `urllink`, `getchild`, `show`, `url`, `setting`, `displayorder`) VALUES(3, '', 1, '0,1', 'FineCMS v2.x', 'v2', 'guanyuwomen/', 0, '3', '', 'FineCMS v2.x 这是一套神奇的系统', 'FineCMS,网站建设,内容管理系统', 'FineCMSv2（简称v2）是一款开源的跨平台网站内容管理系统，以“实用+好用”为基本产品理念，提供从内容发布、组织、传播、互动和数据挖掘的网站一体化解决方案。系统基于CodeIgniter框架，具有良好扩展性和管理性，...', '<p>FineCMS v2（简称v2）是一款开源的跨平台网站内容管理系统，以“实用+好用”为基本产品理念，提供从内容发布、组织、传播、互动和数据挖掘的网站一体化解决方案。系统基于CodeIgniter框架，具有良好扩展性和管理性，可以帮助您在各种操作系统与运行环境中搭建各种网站模型而不需要对复杂繁琐的编程语言有太多的专业知识，系统采用UTF-8编码，采取(语言-代码-程序)两两分离的技术模式，全面使用了模板包与语言包结构，为用户的修改提供方便，网站内容的每一个角落都可以在后台予以管理，是一套非常适合用做系统建站或者进行二次开发的程序核心。<br /><br /></p><div name="{dbprefix}page_break" class="pagebreak">功能特点</div><p><br />每个站点均属独立的系统，模板与语言相互独立、权限互相独立、操作互不影响，共享会员中心，单点同步登录注册等，结合站点语言包能创建出多语言版本的网站，支持站点独立数据库存储，支持站点静态页面同步至指定服务器，可轻松实现强大的负载均衡<br /><br />各个模块相互独立，支持域名绑定，支持按栏目进行权限划分，无限分表存储让模块的负载能力更高，超强的自定义模块和字段功能则把系统灵活度发挥到了极致，可以不用编程就实现各种信息发布和检索，系统内置了文章、组图、下载等内容模块<br /><br />自定义URL一直是FineCMS系统的一个亮点，能够DIY出各种格式的URL，并支持函数与自定义运用到标签中，增强了自定义URL的灵活性。在v2中支持自动生成伪静态规则，无需用户动手写规则，v2能自动帮你把规则写好，真正的傻瓜式操作，一键生成规则<br /><br />内置腾讯QQ、新浪微博、百度、网易、360、google、搜狐等OAuth一键登录功能，发布文章、评论自动发微博分享，还集成手机短信接口API通过手机短信验证更安全，同时会员支持与Ucenter完美整合，会员空间支持域名绑定，用它可以创建标准的个人网站或者企业网站<br /></p><p><br />应用平台为站长提供一站式应用筛选、下载、安装、更新、卸载；满足不同阶段、不同规模的站长自选适合自己的应用。为第三方应用开发商提供可靠、方便、快捷、高效的发布平台；系统基于CodeIgniter框架，开发教程文档齐全，让第三方应用快速、安全的服务到千百万站长，同时也能获得高额的回报 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br /><br />简单易用的模板引擎，支持原生PHP语法，万能的系统标签，灵活的模板制作，系统内置模板标签调用向导，能生成完善的调用代码，让美工人员可独立完成模板制作及数据调用，可让程序人员和美工人员分工协作，最大可能提高团队执行力<br /><br />内置财务管理系统，支持用虚拟币进行站内交易与兑换功能，支持主流第三方交易平台如支付宝、财付通等，帮助用户最简捷、快速、安全的实现电子商务平台</p>', '', 'page.html', 0, '', 1, 1, 'index.php?c=page&id=3', '', 0);
REPLACE INTO `{dbprefix}1_page` (`id`, `module`, `pid`, `pids`, `name`, `dirname`, `pdirname`, `child`, `childids`, `thumb`, `title`, `keywords`, `description`, `content`, `attachment`, `template`, `urlrule`, `urllink`, `getchild`, `show`, `url`, `setting`, `displayorder`) VALUES(4, '', 1, '0,1', '联系我们', 'lianxiwomen', 'guanyuwomen/', 0, '4', '', '', '', '联系QQ：135977378用户交流QQ群：253068474开发者联盟QQ群：8615168', '<p><img src="http://api.map.baidu.com/staticimage?center=116.355779,39.872817&amp;zoom=16&amp;width=530&amp;height=340&amp;markers=116.357863,39.872125" height="340" width="530" /></p><p>联系QQ：<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=135977378&amp;site=qq&amp;menu=yes" target="_blank">135977378</a></p><p>用户交流QQ群：253068474</p><p>开发者联盟QQ群：8615168<br /></p>', '', 'page.html', 0, '', 1, 1, 'index.php?c=page&id=4', '', 0);
REPLACE INTO `{dbprefix}1_page` (`id`, `module`, `pid`, `pids`, `name`, `dirname`, `pdirname`, `child`, `childids`, `thumb`, `title`, `keywords`, `description`, `content`, `attachment`, `template`, `urlrule`, `urllink`, `getchild`, `show`, `url`, `setting`, `displayorder`) VALUES(5, '', 1, '0,1', '官方网站', 'guanfangwangzhan', 'guanyuwomen/', 0, '5', '', '', '', '', '', '', 'page.html', 0, 'http://www.dayrui.com', 1, 1, 'http://www.dayrui.com', '', 0);
REPLACE INTO `{dbprefix}1_page` (`id`, `module`, `pid`, `pids`, `name`, `dirname`, `pdirname`, `child`, `childids`, `thumb`, `title`, `keywords`, `description`, `content`, `attachment`, `template`, `urlrule`, `urllink`, `getchild`, `show`, `url`, `setting`, `displayorder`) VALUES(6, '', 0, '0', 'adfasdf', 'adfasdf', '', 1, '6,7', '', '', '', '', '', '', 'page.html', 0, '', 1, 1, '/index.php?c=page&id=6', '', 0);
REPLACE INTO `{dbprefix}1_page` (`id`, `module`, `pid`, `pids`, `name`, `dirname`, `pdirname`, `child`, `childids`, `thumb`, `title`, `keywords`, `description`, `content`, `attachment`, `template`, `urlrule`, `urllink`, `getchild`, `show`, `url`, `setting`, `displayorder`) VALUES(7, '', 6, '0,6', 'asdfasdf', 'asdfasdf', 'adfasdf/', 0, '7', '', '', '', '', '', '', 'page.html', 0 ,'', 1, 1, '/index.php?c=page&id=7', '', 0);

REPLACE INTO `{dbprefix}space_category` (`id`, `uid`, `pid`, `pids`, `type`, `name`, `link`, `body`, `showid`, `modelid`, `child`, `childids`, `title`, `keywords`, `description`, `displayorder`) VALUES(1, 1, 0, '0', 2, '关于我们', '', '', 3, 0, 1, '1,2,3', '', '', '', 0),(2, 1, 1, '0,1', 2, '空间简介', '', '<p>FineCMS v2（简称v2）是一款开源的跨平台网站内容管理系统，以“实用+好用”为基本产品理念，提供从内容发布、组织、传播、互动和数据挖掘的网站一体化解决方案。系统基于CodeIgniter框架，具有良好扩展性和管理性，可以帮助您在各种操作系统与运行环境中搭建各种网站模型而不需要对复杂繁琐的编程语言有太多的专业知识，系统采用UTF-8编码，采取(语言-代码-程序)两两分离的技术模式，全面使用了模板包与语言包结构，为用户的修改提供方便，网站内容的每一个角落都可以在后台予以管理，是一套非常适合用做系统建站或者进行二次开发的程序核心。<br /></p>', 3, 0, 0, '2', '', '', '', 0),(3, 1, 1, '0,1', 2, '联系我们', '', '<p><img src="http://api.map.baidu.com/staticimage?center=104.077889,30.551305&zoom=18&width=530&height=340&markers=104.076658,30.551693" height="340" width="530" /></p><p>扣扣咨询：135977378<br />电子邮箱：finecms@qq.com</p>', 3, 0, 0, '3', '', '', '', 0),(4, 1, 0, '0', 1, '新闻资讯', '', '', 3, 1, 0, '4', '', '', '', 0),(5, 1, 0, '0', 1, '我的日志', '', '', 3, 3, 0, '5', '', '', '', 0),(6, 1, 0, '0', 1, '精彩图片', '', '', 3, 4, 0, '6', '', '', '', 0),(7, 1, 0, '0', 1, '首页幻灯', '', '', 0, 5, 0, '7', '', '', '', 0),(8, 1, 0, '0', 1, '友情链接', '', '', 3, 2, 0, '8', '', '', '', 0),(9, 1, 0, '0', 0, '技术支持', 'http://www.dayrui.com', '', 3, 0, 0, '9', '', '', '0', 0);

INSERT INTO `{dbprefix}field` VALUES(NULL, '名称', 'title', 'Text', 1, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:4:{s:5:\\"width\\";s:3:\\"400\\";s:5:\\"value\\";s:0:\\"\\";s:9:\\"fieldtype\\";s:7:\\"VARCHAR\\";s:11:\\"fieldlength\\";s:3:\\"255\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"1\\";s:8:\\"required\\";s:1:\\"1\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:34:\\"onblur=\\"get_keywords(\\''keywords\\'');\\"\\";}}', 1);
INSERT INTO `{dbprefix}field` VALUES(NULL, '名称', 'title', 'Text', 2, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:4:{s:5:\\"width\\";s:3:\\"400\\";s:5:\\"value\\";s:0:\\"\\";s:9:\\"fieldtype\\";s:7:\\"VARCHAR\\";s:11:\\"fieldlength\\";s:3:\\"255\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"1\\";s:8:\\"required\\";s:1:\\"1\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:0:\\"\\";}}', 0);
INSERT INTO `{dbprefix}field` VALUES(NULL, '主题', 'title', 'Text', 3, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:4:{s:5:\\"width\\";s:3:\\"400\\";s:5:\\"value\\";s:0:\\"\\";s:9:\\"fieldtype\\";s:7:\\"VARCHAR\\";s:11:\\"fieldlength\\";s:3:\\"255\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"1\\";s:8:\\"required\\";s:1:\\"1\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:34:\\"onblur=\\"get_keywords(\\''keywords\\'');\\"\\";}}', 1);
INSERT INTO `{dbprefix}field` VALUES(NULL, '名称', 'title', 'Text', 4, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:4:{s:5:\\"width\\";s:3:\\"400\\";s:5:\\"value\\";s:0:\\"\\";s:9:\\"fieldtype\\";s:7:\\"VARCHAR\\";s:11:\\"fieldlength\\";s:3:\\"255\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"1\\";s:8:\\"required\\";s:1:\\"1\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:34:\\"onblur=\\"get_keywords(\\''keywords\\'');\\"\\";}}', 1);
INSERT INTO `{dbprefix}field` VALUES(NULL, '内容', 'content', 'Ueditor', 1, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:6:{s:5:\\"width\\";s:3:\\"90%\\";s:6:\\"height\\";s:3:\\"100\\";s:3:\\"key\\";s:0:\\"\\";s:4:\\"mode\\";s:1:\\"2\\";s:4:\\"tool\\";s:29:\\"\\''bold\\'', \\''italic\\'', \\''underline\\''\\";s:5:\\"value\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"1\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:0:\\"\\";}}', 4);
INSERT INTO `{dbprefix}field` VALUES(NULL, '链接地址', 'link', 'Redirect', 2, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:2:{s:5:\\"width\\";s:3:\\"400\\";s:5:\\"value\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:0:\\"\\";}}', 0);
INSERT INTO `{dbprefix}field` VALUES(NULL, '内容', 'content', 'Ueditor', 3, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:6:{s:5:\\"width\\";s:3:\\"90%\\";s:6:\\"height\\";s:3:\\"100\\";s:3:\\"key\\";s:0:\\"\\";s:4:\\"mode\\";s:1:\\"2\\";s:4:\\"tool\\";s:29:\\"\\''bold\\'', \\''italic\\'', \\''underline\\''\\";s:5:\\"value\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:0:\\"\\";}}', 4);
INSERT INTO `{dbprefix}field` VALUES(NULL, '图片集', 'image', 'Files', 4, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:6:{s:5:\\"width\\";s:3:\\"80%\\";s:4:\\"size\\";s:2:\\"10\\";s:5:\\"count\\";s:2:\\"50\\";s:3:\\"ext\\";s:11:\\"gif,png,jpg\\";s:10:\\"uploadpath\\";s:25:\\"{siteid}/photo/{y}{m}{d}/\\";s:3:\\"pan\\";s:1:\\"0\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:0:\\"\\";}}', 3);
INSERT INTO `{dbprefix}field` VALUES(NULL, '简介', 'content', 'Ueditor', 4, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:6:{s:5:\\"width\\";s:3:\\"90%\\";s:6:\\"height\\";s:3:\\"100\\";s:3:\\"key\\";s:0:\\"\\";s:4:\\"mode\\";s:1:\\"2\\";s:4:\\"tool\\";s:29:\\"\\''bold\\'', \\''italic\\'', \\''underline\\''\\";s:5:\\"value\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:0:\\"\\";}}', 5);
INSERT INTO `{dbprefix}field` VALUES(NULL, '主题', 'title', 'Text', 5, 'space', 1, 1, 1, 1, 1, 0, 'a:2:{s:6:\\"option\\";a:3:{s:5:\\"width\\";i:400;s:9:\\"fieldtype\\";s:7:\\"VARCHAR\\";s:11:\\"fieldlength\\";s:3:\\"255\\";}s:8:\\"validate\\";a:4:{s:3:\\"xss\\";i:1;s:8:\\"required\\";i:1;s:4:\\"tips\\";N;s:9:\\"errortips\\";N;}}', 0);
INSERT INTO `{dbprefix}field` VALUES(NULL, '图片', 'image', 'File', 5, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:4:{s:4:\\"size\\";s:2:\\"10\\";s:3:\\"ext\\";s:11:\\"gif,png,jpg\\";s:10:\\"uploadpath\\";s:0:\\"\\";s:3:\\"pan\\";s:1:\\"0\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:0:\\"\\";}}', 0);
INSERT INTO `{dbprefix}field` VALUES(NULL, '链接地址', 'link', 'Redirect', 5, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:2:{s:5:\\"width\\";s:3:\\"400\\";s:5:\\"value\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:0:\\"\\";}}', 0);
INSERT INTO `{dbprefix}field` VALUES(NULL, '关键字', 'keywords', 'Text', 1, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:4:{s:5:\\"width\\";s:3:\\"400\\";s:5:\\"value\\";s:0:\\"\\";s:9:\\"fieldtype\\";s:7:\\"VARCHAR\\";s:11:\\"fieldlength\\";s:3:\\"255\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"1\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";s:43:\\"多个关键字以小写分号“,”分隔\\";s:8:\\"formattr\\";s:0:\\"\\";}}', 2);
INSERT INTO `{dbprefix}field` VALUES(NULL, '描述', 'description', 'Textarea', 1, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:3:{s:5:\\"width\\";s:3:\\"500\\";s:6:\\"height\\";s:2:\\"60\\";s:5:\\"value\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"1\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:12:\\"dr_clearhtml\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:0:\\"\\";}}', 3);
INSERT INTO `{dbprefix}field` VALUES(NULL, '关键字', 'keywords', 'Text', 3, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:4:{s:5:\\"width\\";s:3:\\"400\\";s:5:\\"value\\";s:0:\\"\\";s:9:\\"fieldtype\\";s:7:\\"VARCHAR\\";s:11:\\"fieldlength\\";s:3:\\"255\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"1\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";s:43:\\"多个关键字以小写分号“,”分隔\\";s:8:\\"formattr\\";s:0:\\"\\";}}', 2);
INSERT INTO `{dbprefix}field` VALUES(NULL, '描述', 'description', 'Textarea', 3, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:3:{s:5:\\"width\\";s:3:\\"400\\";s:6:\\"height\\";s:2:\\"60\\";s:5:\\"value\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"1\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:12:\\"dr_clearhtml\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:0:\\"\\";}}', 3);
INSERT INTO `{dbprefix}field` VALUES(NULL, '关键字', 'keywords', 'Text', 4, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:4:{s:5:\\"width\\";s:3:\\"400\\";s:5:\\"value\\";s:0:\\"\\";s:9:\\"fieldtype\\";s:7:\\"VARCHAR\\";s:11:\\"fieldlength\\";s:3:\\"255\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"1\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";s:43:\\"多个关键字以小写分号“,”分隔\\";s:8:\\"formattr\\";s:0:\\"\\";}}', 2);
INSERT INTO `{dbprefix}field` VALUES(NULL, '描述', 'description', 'Textarea', 4, 'space', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:3:{s:5:\\"width\\";s:3:\\"400\\";s:6:\\"height\\";s:2:\\"60\\";s:5:\\"value\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"1\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";N;s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:12:\\"dr_clearhtml\\";s:4:\\"tips\\";N;s:8:\\"formattr\\";s:0:\\"\\";}}', 4);

INSERT INTO `{dbprefix}field` VALUES(NULL, '名称', 'name', 'Text', 0, 'spacetable', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:3:{s:5:\\"width\\";i:300;s:9:\\"fieldtype\\";s:7:\\"VARCHAR\\";s:11:\\"fieldlength\\";s:3:\\"255\\";}s:8:\\"validate\\";a:4:{s:3:\\"xss\\";i:1;s:8:\\"required\\";i:1;s:4:\\"tips\\";N;s:9:\\"errortips\\";N;}}', 0);
INSERT INTO `{dbprefix}field` VALUES(NULL, 'Logo', 'logo', 'File', 0, 'spacetable', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:3:{s:4:\\"size\\";s:1:\\"2\\";s:3:\\"ext\\";s:11:\\"jpg,gif,png\\";s:10:\\"uploadpath\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";s:0:\\"\\";s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";s:0:\\"\\";s:8:\\"formattr\\";s:0:\\"\\";}}', 0);
INSERT INTO `{dbprefix}field` VALUES(NULL, '空间简介', 'introduction', 'Ueditor', 0, 'spacetable', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:5:{s:5:\\"width\\";s:3:\\"90%\\";s:6:\\"height\\";s:3:\\"200\\";s:4:\\"mode\\";s:1:\\"2\\";s:4:\\"tool\\";s:29:\\"\\''bold\\'', \\''italic\\'', \\''underline\\''\\";s:5:\\"value\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";s:0:\\"\\";s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";s:0:\\"\\";s:8:\\"formattr\\";s:0:\\"\\";}}', 0);
INSERT INTO `{dbprefix}field` VALUES(NULL, 'SEO标题', 'title', 'Text', 0, 'spacetable', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:4:{s:5:\\"width\\";s:3:\\"400\\";s:5:\\"value\\";s:0:\\"\\";s:9:\\"fieldtype\\";s:0:\\"\\";s:11:\\"fieldlength\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";s:0:\\"\\";s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";s:0:\\"\\";s:8:\\"formattr\\";s:0:\\"\\";}}', 0);
INSERT INTO `{dbprefix}field` VALUES(NULL, 'SEO关键字', 'keywords', 'Text', 0, 'spacetable', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:4:{s:5:\\"width\\";s:3:\\"400\\";s:5:\\"value\\";s:0:\\"\\";s:9:\\"fieldtype\\";s:0:\\"\\";s:11:\\"fieldlength\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";s:0:\\"\\";s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";s:0:\\"\\";s:8:\\"formattr\\";s:0:\\"\\";}}', 0);
INSERT INTO `{dbprefix}field` VALUES(NULL, 'SEO描述信息', 'description', 'Textarea', 0, 'spacetable', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:3:{s:5:\\"width\\";s:3:\\"500\\";s:6:\\"height\\";s:3:\\"100\\";s:5:\\"value\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";s:0:\\"\\";s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";s:0:\\"\\";s:8:\\"formattr\\";s:0:\\"\\";}}', 0);
INSERT INTO `{dbprefix}field` VALUES(NULL, '第三方代码', 'code', 'Textarea', 0, 'spacetable', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:3:{s:5:\\"width\\";s:3:\\"500\\";s:6:\\"height\\";s:3:\\"100\\";s:5:\\"value\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";s:0:\\"\\";s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";s:0:\\"\\";s:8:\\"formattr\\";s:0:\\"\\";}}', 0);
INSERT INTO `{dbprefix}field` VALUES(NULL, '底部信息', 'footer', 'Ueditor', 0, 'spacetable', 1, 1, 1, 1, 0, 0, 'a:2:{s:6:\\"option\\";a:5:{s:5:\\"width\\";s:3:\\"90%\\";s:6:\\"height\\";s:3:\\"200\\";s:4:\\"mode\\";s:1:\\"2\\";s:4:\\"tool\\";s:29:\\"\\''bold\\'', \\''italic\\'', \\''underline\\''\\";s:5:\\"value\\";s:0:\\"\\";}s:8:\\"validate\\";a:9:{s:3:\\"xss\\";s:1:\\"0\\";s:8:\\"required\\";s:1:\\"0\\";s:7:\\"pattern\\";s:0:\\"\\";s:9:\\"errortips\\";s:0:\\"\\";s:6:\\"isedit\\";s:1:\\"0\\";s:5:\\"check\\";s:0:\\"\\";s:6:\\"filter\\";s:0:\\"\\";s:4:\\"tips\\";s:0:\\"\\";s:8:\\"formattr\\";s:0:\\"\\";}}', 0);



REPLACE INTO `{dbprefix}space_model` VALUES(1, '文章', 'news', 'a:8:{s:3:\\"3_1\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"1\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_2\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"2\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_3\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"3\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_4\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"4\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_5\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"5\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_6\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"6\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_7\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"7\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_8\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"8\\";s:5:\\"score\\";s:0:\\"\\";}}');
REPLACE INTO `{dbprefix}space_model` VALUES(2, '外链', 'link', 'a:8:{s:3:\\"3_1\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"1\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_2\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"2\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_3\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"3\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_4\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"4\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_5\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"5\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_6\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"6\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_7\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"7\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_8\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"8\\";s:5:\\"score\\";s:0:\\"\\";}}');
REPLACE INTO `{dbprefix}space_model` VALUES(3, '日志', 'log', 'a:8:{s:3:\\"3_1\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"1\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_2\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"2\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_3\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"3\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_4\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"4\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_5\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"5\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_6\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"6\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_7\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"7\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_8\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"8\\";s:5:\\"score\\";s:0:\\"\\";}}');
REPLACE INTO `{dbprefix}space_model` VALUES(4, '相册', 'photo', 'a:8:{s:3:\\"3_1\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"1\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_2\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"2\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_3\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"3\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_4\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"4\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_5\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"1\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_6\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"2\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_7\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"3\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_8\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:1:\\"4\\";s:5:\\"score\\";s:0:\\"\\";}}');
REPLACE INTO `{dbprefix}space_model` VALUES(5, '幻灯', 'slides', 'a:8:{s:3:\\"3_1\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:0:\\"\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_2\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:0:\\"\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_3\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:0:\\"\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"3_4\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:0:\\"\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_5\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:0:\\"\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_6\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:0:\\"\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_7\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:0:\\"\\";s:5:\\"score\\";s:0:\\"\\";}s:3:\\"4_8\\";a:3:{s:3:\\"use\\";s:1:\\"1\\";s:10:\\"experience\\";s:0:\\"\\";s:5:\\"score\\";s:0:\\"\\";}}');

DROP TABLE IF EXISTS `{dbprefix}space_link`;
CREATE TABLE IF NOT EXISTS `{dbprefix}space_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` mediumint(8) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `hits` int(10) unsigned NOT NULL COMMENT '点击量',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `hits` (`hits`),
  KEY `catid` (`catid`),
  KEY `status` (`status`),
  KEY `inputtime` (`inputtime`),
  KEY `updatetime` (`updatetime`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员空间外链模型表' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `{dbprefix}space_log`;
CREATE TABLE IF NOT EXISTS `{dbprefix}space_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` mediumint(8) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `description` text DEFAULT NULL COMMENT '描述',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `hits` int(10) unsigned NOT NULL COMMENT '点击量',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `content` mediumtext,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `hits` (`hits`),
  KEY `catid` (`catid`),
  KEY `status` (`status`),
  KEY `inputtime` (`inputtime`),
  KEY `updatetime` (`updatetime`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员空间日志模型表' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `{dbprefix}space_news`;
CREATE TABLE IF NOT EXISTS `{dbprefix}space_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` mediumint(8) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `description` text DEFAULT NULL COMMENT '描述',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `hits` int(10) unsigned NOT NULL COMMENT '点击量',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `content` mediumtext,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `hits` (`hits`),
  KEY `catid` (`catid`),
  KEY `status` (`status`),
  KEY `inputtime` (`inputtime`),
  KEY `updatetime` (`updatetime`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员空间单页模型表' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `{dbprefix}space_photo`;
CREATE TABLE IF NOT EXISTS `{dbprefix}space_photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` mediumint(8) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `description` text DEFAULT NULL COMMENT '描述',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `hits` int(10) unsigned NOT NULL COMMENT '点击量',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `image` text,
  `content` mediumtext,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `hits` (`hits`),
  KEY `catid` (`catid`),
  KEY `status` (`status`),
  KEY `inputtime` (`inputtime`),
  KEY `updatetime` (`updatetime`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员空间相册模型表' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `{dbprefix}space_slides`;
CREATE TABLE IF NOT EXISTS `{dbprefix}space_slides` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` mediumint(8) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者uid',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `hits` int(10) unsigned NOT NULL COMMENT '点击量',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `hits` (`hits`),
  KEY `catid` (`catid`),
  KEY `status` (`status`),
  KEY `inputtime` (`inputtime`),
  KEY `updatetime` (`updatetime`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员空间幻灯模型表' AUTO_INCREMENT=1;

REPLACE INTO `{dbprefix}space_slides` (`id`, `catid`, `title`, `uid`, `author`, `hits`, `status`, `inputtime`, `updatetime`, `displayorder`, `image`, `link`) VALUES (1, 7, '应用开放平台', 1, 'admin', 0, 1, 1377949237, 1377949237, 0, 'http://www.dayrui.com/dayrui/statics/dayrui/images/index_banner1.jpg', 'http://store.dayrui.com'),(2, 7, '群站多语言管理', 1, 'admin', 0, 1, 1377949258, 1377949258, 0, 'http://www.dayrui.com/dayrui/statics/dayrui/images/index_banner2.jpg', 'http://www.dayrui.com/cms/'),(3, 7, 'FineCMS 一套神奇的系统', 1, 'admin', 0, 1, 1377949306, 1377949306, 0, 'http://www.dayrui.com/dayrui/statics/dayrui/images/index_banner3.jpg', 'http://www.dayrui.com/');

REPLACE INTO `{dbprefix}space` (`uid`, `name`, `logo`, `style`, `title`, `keywords`, `description`, `introduction`, `code`, `footer`, `hits`, `status`, `regtime`) VALUES
(1, 'FineCMS设计室', 'http://www.dayrui.com/member/statics/default/images/logo_new.png', 'default', 'FineCMS设计室-专业技术团队', 'FineCMS,网站建设,内容管理系统', 'FineCMS v2（简称v2）是一款开源的跨平台网站内容管理系统，以“实用+好用”为基本产品理念，提供从内容发布、组织、传播、互动和数据挖掘的网站一体化解决方案', '<p>FineCMS v2（简称v2）是一款开源的跨平台网站内容管理系统，以“实用+好用”为基本产品理念，提供从内容发布、组织、传播、互动和数据挖掘的网站一体化解决方案。系统基于CodeIgniter框架，具有良好扩展性和管理性，可以帮助您在各种操作系统与运行环境中搭建各种网站模型而不需要对复杂繁琐的编程语言有太多的专业知识，系统采用UTF-8编码，采取(语言-代码-程序)两两分离的技术模式，全面使用了模板包与语言包结构，为用户的修改提供方便，网站内容的每一个角落都可以在后台予以管理，是一套非常适合用做系统建站或者进行二次开发的程序核心。</p>', '<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cdiv id=''cnzz_stat_icon_5629330''%3E%3C/div%3E%3Cscript src=''" + cnzz_protocol + "s9.cnzz.com/stat.php%3Fid%3D5629330%26show%3Dpic2'' type=''text/javascript''%3E%3C/script%3E"));</script>', '<p>扣扣咨询：135977378 电子邮箱：finecms@qq.com</p>某某某公司版权所有，ICP备案0000001<p><br /></p>', 888888, 1, 1377949585);


REPLACE INTO `{dbprefix}space_flag` (`flag`, `uid`) VALUES (1, 1), (2, 1);

REPLACE INTO `{dbprefix}space_category_init` VALUES(1, 1, 0, '0', 2, '关于我们', '', 3, 0, 1, '1,2,3');
REPLACE INTO `{dbprefix}space_category_init` VALUES(2, 1, 1, '0,1', 2, '空间简介', '', 3, 0, 0, '2');
REPLACE INTO `{dbprefix}space_category_init` VALUES(3, 1, 1, '0,1', 2, '联系我们', '', 3, 0, 0, '3');
REPLACE INTO `{dbprefix}space_category_init` VALUES(4, 1, 0, '0', 1, '新闻资讯', '', 3, 1, 0, '4');
REPLACE INTO `{dbprefix}space_category_init` VALUES(5, 1, 0, '0', 1, '我的日志', '', 3, 3, 0, '5');
REPLACE INTO `{dbprefix}space_category_init` VALUES(6, 1, 0, '0', 1, '精彩图片', '', 3, 4, 0, '6');
REPLACE INTO `{dbprefix}space_category_init` VALUES(7, 1, 0, '0', 1, '首页幻灯', '', 0, 5, 0, '7');
REPLACE INTO `{dbprefix}space_category_init` VALUES(8, 1, 0, '0', 1, '友情链接', '', 3, 2, 0, '8');
REPLACE INTO `{dbprefix}space_category_init` VALUES(9, 1, 0, '0', 0, '技术支持', 'http://www.dayrui.com', 3, 0, 0, '9');
REPLACE INTO `{dbprefix}space_category_init` VALUES(10, 2, 0, '0', 2, '关于我们', '', 3, 0, 1, '10,11,12');
REPLACE INTO `{dbprefix}space_category_init` VALUES(11, 2, 10, '0,10', 2, '空间简介', '', 3, 0, 0, '11');
REPLACE INTO `{dbprefix}space_category_init` VALUES(12, 2, 10, '0,10', 2, '联系我们', '', 3, 0, 0, '12');
REPLACE INTO `{dbprefix}space_category_init` VALUES(13, 2, 0, '0', 1, '新闻资讯', '', 3, 1, 0, '13');
REPLACE INTO `{dbprefix}space_category_init` VALUES(14, 2, 0, '0', 1, '我的日志', '', 3, 3, 0, '14');
REPLACE INTO `{dbprefix}space_category_init` VALUES(15, 2, 0, '0', 1, '精彩图片', '', 3, 4, 0, '15');
REPLACE INTO `{dbprefix}space_category_init` VALUES(16, 2, 0, '0', 1, '首页幻灯', '', 0, 5, 0, '16');
REPLACE INTO `{dbprefix}space_category_init` VALUES(17, 2, 0, '0', 1, '友情链接', '', 3, 2, 0, '17');
REPLACE INTO `{dbprefix}space_category_init` VALUES(18, 2, 0, '0', 0, '技术支持', 'http://www.dayrui.com', 3, 0, 0, '18');
REPLACE INTO `{dbprefix}space_category_init` VALUES(19, 3, 0, '0', 2, '关于我们', '', 3, 0, 1, '19,20,21');
REPLACE INTO `{dbprefix}space_category_init` VALUES(20, 3, 19, '0,19', 2, '空间简介', '', 3, 0, 0, '20');
REPLACE INTO `{dbprefix}space_category_init` VALUES(21, 3, 19, '0,19', 2, '联系我们', '', 3, 0, 0, '21');
REPLACE INTO `{dbprefix}space_category_init` VALUES(22, 3, 0, '0', 1, '新闻资讯', '', 3, 1, 0, '22');
REPLACE INTO `{dbprefix}space_category_init` VALUES(23, 3, 0, '0', 1, '我的日志', '', 3, 3, 0, '23');
REPLACE INTO `{dbprefix}space_category_init` VALUES(24, 3, 0, '0', 1, '精彩图片', '', 3, 4, 0, '24');
REPLACE INTO `{dbprefix}space_category_init` VALUES(25, 3, 0, '0', 1, '首页幻灯', '', 0, 5, 0, '25');
REPLACE INTO `{dbprefix}space_category_init` VALUES(26, 3, 0, '0', 1, '友情链接', '', 3, 2, 0, '26');
REPLACE INTO `{dbprefix}space_category_init` VALUES(27, 3, 0, '0', 0, '技术支持', 'http://www.dayrui.com', 3, 0, 0, '27');
REPLACE INTO `{dbprefix}space_category_init` VALUES(28, 4, 0, '0', 2, '关于我们', '', 3, 0, 1, '28,29,30');
REPLACE INTO `{dbprefix}space_category_init` VALUES(29, 4, 28, '0,28', 2, '空间简介', '', 3, 0, 0, '29');
REPLACE INTO `{dbprefix}space_category_init` VALUES(30, 4, 28, '0,28', 2, '联系我们', '', 3, 0, 0, '30');
REPLACE INTO `{dbprefix}space_category_init` VALUES(31, 4, 0, '0', 1, '新闻资讯', '', 3, 1, 0, '31');
REPLACE INTO `{dbprefix}space_category_init` VALUES(32, 4, 0, '0', 1, '我的日志', '', 3, 3, 0, '32');
REPLACE INTO `{dbprefix}space_category_init` VALUES(33, 4, 0, '0', 1, '精彩图片', '', 3, 4, 0, '33');
REPLACE INTO `{dbprefix}space_category_init` VALUES(34, 4, 0, '0', 1, '首页幻灯', '', 0, 5, 0, '34');
REPLACE INTO `{dbprefix}space_category_init` VALUES(35, 4, 0, '0', 1, '友情链接', '', 3, 2, 0, '35');
REPLACE INTO `{dbprefix}space_category_init` VALUES(36, 4, 0, '0', 0, '技术支持', 'http://www.dayrui.com', 3, 0, 0, '36');