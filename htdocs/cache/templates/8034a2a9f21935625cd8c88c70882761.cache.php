<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>
<script type="text/javascript">
$(function() {
	SwapTab(0);
	$(".table_form tr>th").attr("width", 200);
    // 错误提示
	<?php if ($error) { ?>
	art.dialog.tips('<font color=red><?php echo $error['msg']; ?></font>', 3);
	<?php } ?>
    // 结果显示
	<?php if ($result) { ?>
	art.dialog.tips('<?php echo $result; ?>', 3, 1);
	<?php } ?>
    // 生成静态文件
	<?php if ($create) { ?>
	$.post('<?php echo $create; ?>&rand='+Math.random(),{}, function(){});
	$.post('<?php echo SITE_URL;  echo APP_DIR; ?>/index.php?c=home&m=create_list_html&id=<?php echo $catid; ?>&rand='+Math.random(),{}, function(){});
	<?php } ?>
    //每隔5秒保存表单数据
    <?php if (!$data['id'] && !$did) { ?>
    setInterval("dr_save_add_data()", 5000);
    <?php } ?>
    // 加载草稿
    $("#dr_cgbox").mouseover(function(){
        $(".dr_cgbox").show();
    }).mouseout(function(){
        $(".dr_cgbox").hide();
    });
    // 删除草稿数据
    $(".dr_cgbox_delete").click(function(){
        var did = $(this).attr("did");
        var num = parseInt($("#dr_cg_nums").html());
        $.ajax({
            type: "POST",
            dataType:"json",
            url: memberpath+'index.php?c=api&m=ajax_delete_draft&sid=<?php echo SITE_ID; ?>&dir=<?php echo APP_DIR; ?>&did='+did,
            success: function(data) {
                if (data == did) {
                    $("#dr_cgbox_"+did).remove();
                    $("#dr_cg_nums").html(num - 1);
                } else {
                    dr_tips(data);
                }
            },
            error: function(HttpRequest, ajaxOptions, thrownError) { }
        });
    });
    $(".dr_cgbox_select").click(function(){
       var did = $(this).attr("did");
       window.location.href = '<?php echo $draft_url; ?>&did='+did;
    });
    // 自动保存草稿
    <?php if ($did) { ?>
    setInterval("dr_save_draft_data()", 5000);
    <?php } ?>
});
// 修改栏目时的页面跳转
function show_category_field(catid) {
    <?php if ($is_category_field) { ?>
    window.location.href = '<?php echo dr_url(APP_DIR."/home/".$ci->router->method, array("id"=>$data['id'])); ?>&catid='+catid;
    <?php } ?>
}
// 动态保存草稿数据
function dr_save_draft_data() {
    var catid = $('#dr_catid').val();
    $.ajax({
        type: "POST",
        dataType:"json",
        url: memberpath+'index.php?c=api&m=ajax_save_draft&sid=<?php echo SITE_ID; ?>&dir=<?php echo APP_DIR; ?>&did=<?php echo $did; ?>&catid='+catid,
        data: $("#myform").serialize(),
        success: function(data) { },
        error: function(HttpRequest, ajaxOptions, thrownError) { }
    });
}
// 动态保存表单数据
function dr_save_add_data() {
    $.ajax({
        type: "POST",
        dataType:"json",
        url: memberpath+'index.php?c=api&m=ajax_save_add&dir=<?php echo APP_DIR; ?>',
        data: $("#myform").serialize(),
        success: function(data) { },
        error: function(HttpRequest, ajaxOptions, thrownError) { }
    });
}
</script>
<form action="" method="post" name="myform" id="myform">
<input name="page" id="page" type="hidden" value="<?php echo $page; ?>" />
<input name="action" id="dr_action" type="hidden" value="back" />
<input name="dr_id" id="dr_id" type="hidden" value="<?php echo $data['id']; ?>" />
<input name="dr_module" id="dr_module" type="hidden" value="<?php echo APP_DIR; ?>" />
<div class="subnav">
	<div class="content-menu ib-a blue line-x">
		<?php echo $menu; ?>
	</div>
	<div class="bk10"></div>
    <div class="table-list col-tab">
        <ul class="tabBut2 cu-li">
            <li class="on li"><?php echo lang('246'); ?></li>
            <?php if ($draft_list) { ?>
            <li class="cg li" id="dr_cgbox">
                <?php echo lang('html-790'); ?>（<span id="dr_cg_nums"><?php echo count($draft_list); ?></span>）
                <fieldset class="dr_cgbox" style="display:none">
                    <div class="picList">
                        <ul class="cg_items" style="max-height: 400px;overflow-y: auto;">
                            <?php if (is_array($draft_list)) { $count=count($draft_list);foreach ($draft_list as $t) { ?>
                            <li id="dr_cgbox_<?php echo $t['id']; ?>">
                                <a href="javascript:;" class="dr_cgbox_delete" did="<?php echo $t['id']; ?>" title="<?php echo lang('del'); ?>"><img align="absmiddle" src="<?php echo SITE_URL; ?>dayrui/statics/images/b_drop.png"></a>
                                <span><?php echo dr_date($t['inputtime']); ?></span>
                                <a href="javascript:;" class="dr_cgbox_select" did="<?php echo $t['id']; ?>"><?php echo $t['title']; ?></a>
                            </li>
                            <?php } } ?>
                        </ul>
                    </div>
                </fieldset>
            </li>
            <?php } ?>
        </ul>

        <div class="contentList pad-10 dr_table">
        <table width="100%" class="table_form">
        <tr>
            <th width="200"><font color="red">*</font>&nbsp;<?php echo lang('cat-00'); ?>： </th>
            <td><?php echo $select; ?></td>
        </tr>
        <?php echo $myfield;  if ($flag) { ?>
        <tr>
            <th width="200"><?php echo lang('html-174'); ?>： </th>
            <td>
			<?php if (is_array($flag)) { $count=count($flag);foreach ($flag as $i=>$t) {  if ($t['name']) { ?><input name="flag[]" type="checkbox" <?php if (@in_array($i, $myflag)) { ?>checked="checked" <?php } ?>value="<?php echo $i; ?>" />&nbsp;<label><?php echo $t['name']; ?></label>&nbsp;&nbsp;&nbsp;<?php }  } } ?>
            </td>
        </tr>
		<?php }  if (!$data['id']) { ?>
        <tr>
            <th width="200"><?php echo lang('m-113'); ?>： </th>
            <td>
			<input name="qq_share" type="checkbox" checked="checked" value="1" />
			<label>腾讯微博</label>
            <?php if (!$member['oauth']['qq']) { ?><label style="color:#FF0000">（请进入会员中心-账户-快捷登录，绑定QQ账户）</label><?php } ?>
			&nbsp;&nbsp;
			<input name="sina_share" type="checkbox" checked="checked" value="1" />
			<label>新浪微博</label>
            <?php if (!$member['oauth']['sina']) { ?><label style="color:#FF0000">（请进入会员中心-账户-快捷登录，绑定新浪账户）</label><?php } ?>
            </td>
        </tr>
        <?php } ?>
        </table>
        </div>
    </div>
</div>
<div class="fixed-bottom">
    <div class="fixed-but text-c">
        <div class="button"><input value="<?php echo lang('html-789'); ?>" type="submit" name="submit" class="cu" onclick="$('#dr_action').val('draft')" style="width:100px;" /></div>
        <div class="button"><input value="<?php echo lang('html-362'); ?>" type="submit" name="submit" class="cu" onclick="$('#dr_action').val('back')" style="width:100px;" /></div>
        <?php if (!$data['id']) { ?>
        <div class="button"><input value="<?php echo lang('html-363'); ?>" type="submit" name="submit" class="cu" onclick="$('#dr_action').val('continue')" style="width:100px;" /></div>
        <?php } ?>
    </div>
</div>
</form>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>