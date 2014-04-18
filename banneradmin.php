<html>
<head>
<?php include('inc/header.php');?>
</head>
<body>
	<?php require_once('api.php');?>
    <?php include('inc/menu.php');?>
	<div class="display-zone">
		<div class="title">
			<span class="title-con">轮播图管理</span>
			<span class="action-bar">
				<button class="button" id="review">效果预览</button>
				<button class="button" id="add_new_banner">添加新banner</button>
			</span>
		</div>
		<table id="exsit_list" cellpadding="0" cellspacing="0">
			<tr>
				<th>顺序</th>
				<th>图片</th>
				<th>描述<br>(desc)</th>
				<th>标题<br>(title)</th>
				<th>操作</th>
			</tr>
		</table>
	</div>
	<div style="display:none" id="add_dialog" title="添加banner">
		<table cellpadding="0" cellspacing="0" style="border-top:0 none;">
			<tr>
				<td>位置</td>
				<td><select id="banner_pos">
					<option value="1">第一张</option>
					<option value="2">最后一张</option>
				</select></td>	
			</tr>
			<tr>
				<td>上传图片</td>
				<td>
					 <span id="banner_image_upload_tip"></span>
					 <input type="hidden" id="banner_thumb">
					 <form id="banner_image_file_form">
						 <input type="file" id="banner_image_file" name="images">
					 </form>
				</td>	
			</tr>
			<tr>
				<td>描述</td>
				<td><input id="desc"  class="enter-item"/></td>	
			</tr>
			<tr>
				<td>标题</td>
				<td><input id="title"  class="enter-item" placeholder="例如:一些放在图片上的文字"/></td>	
			</tr>
			<tr>
				<td>链接</td>
				<td><input id="link"  class="enter-item" placeholder="例如:cake/32" /></td>	
			</tr>
			<tr>
				<td>操作</td>
				<td><button class="button" id="add_new_banner_button">添加</button></td>	
			</tr>
		</table>
	</div>
</body>
<script>
$.get('api.php?action=get_banner_list',function(d){
	var d = d.data;
	var html='';

	for(var i=0;i<d.length;i++){
		var index=i+1;
		html+='<tr><td>'+index+'</td>';
		html+='<td><img src="../'+d[i].img+'" width="200"></td>';
		html+='<td>'+d[i].desc+'</td>';
		html+='<td>'+d[i].title+'</td>';
		html+='<td><button class="button del_banner" data-index="'+i+'">删除(下线)</button></td></tr>';
	}
	$('#exsit_list').append(html);
	$(".button").button();
},'json');

$('#add_new_banner').click(function(){
	$('#add_dialog').dialog({
		modal:true,
		width:400
	});
});

$('#add_new_banner_button').click(function(){
	var pos = $('#banner_pos').val();
	var desc = $('#desc').val();
	var title = $('#title').val();
	var link = $('#link').val();
	var banner_thumb = $('#banner_thumb').val();

	$.post('api.php?action=add_banner',{
		pos:pos,
		desc:desc,
		title:title,
		link:link,
		banner_thumb:banner_thumb
	},function(d){
		if(d == 'success'){
			alert('添加成功');
			location.reload();
		}	
	});
});

$(document).delegate('.del_banner','click',function(){
	var index = $(this).data('index');
	$.post('api.php?action=del_banner',{
		index:index
	},function(d){
		if(d == 'success'){
			alert('添加成功');
			location.reload();
		}
	});
});
$(".button").button();



var Upload = function(opt){
		this.opt = {
			formId:opt.formId,
			inputId:opt.inputId,
			iframeId:opt.iframeId||'mes_upload',
			url:opt.url,
			callback:opt.callback||function(){},
			upload:opt.upload||function(){}
		}

		this.jqForm = $('#'+this.opt.formId);
		this.jqInput = $('#'
      +
      this.opt.inputId);
      this.init();

      }

      Upload.prototype = {
        init : function() {
          var me = this;
          var iframeId = this.opt.iframeId;
          var jqForm = this.jqForm;
          jqForm.attr('target', iframeId);
          jqForm.attr('action', this.opt.url);
          jqForm.attr('enctype', 'multipart/form-data');
          jqForm.attr('method', 'post');
          jqForm.after('<iframe name="' + iframeId + '" id="' + iframeId + '" width="0" height="0"></iframe>');
          this.jqInput.change(function() {
            me.opt.upload();
            jqForm.submit();
          });
          setTimeout(function() {
            var iframe = $('#'+iframeId)[0];
            iframe.onload = iframe.onreadystatechange = function() {
              var ret = iframe.contentWindow.ret;
              me.opt.callback(ret);
            }
          }, 0);
        }
      }

	  //上传产品图片
      new Upload({
        inputId : 'banner_image_file',
        formId : 'banner_image_file_form',
        iframeId : 'banner_image_file_iframe',
        url : 'api.php?action=uploadgoodsimage',
        upload : function(d) {
			$('#banner_image_upload_tip').html('正在上传...')
        },
        callback : function(d) {
			if(d&&d.code==0){
				$('#banner_image_upload_tip').html('上传成功');
				$('#banner_thumb').val(d.url);
				$('#banner_image_upload_tip').append('<img src="/'+d.url+'" width="150"/>');
			}
        }
      });
	  $('#review').click(function(){
		location.href="/route.php?mod=page&action=index";
	  });
</script>
</html>