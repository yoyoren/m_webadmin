<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/ui-lightness/jquery-ui-1.10.4.css" rel="stylesheet">
<link href="css/index.css" rel="stylesheet">
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.10.4.js"></script>
<style>
.button{font-size:12px}
table{font-size:12px;}
table td {border-top:solid #000 1px;padding:10px;margin:0;}
table th{font-weight:bold}
#add_attr_dialog input,#add_dialog input{width:200px}
#add_attr_dialog select,#add_dialog select{width:200px}
</style>
</head>
<body>
    <?php 
		require_once('api.php');
	?>
	<div class="display-zone">
		<div class="title">
			<span class="title-con">已有商品</span>
			<span class="action-bar">
				<button class="button" id="add_new_button">添加新商品</button>
			</span>
		</div>
		<table id="exsit_list" cellpadding="0" cellspacing="0">
			<tr>
				<th>货号<br>(goods_sn)</th>
				<th>名称<br>(goods_name)</th>
				<th>图片</th>
				<th>关键字<br>(keywords)</th>
				<th>商品描述<br>(goods_desc)</th>
				<th>是否上架<br>(is_on_sale)</th>
				<th>价格<br>(market_price)</th>
				<th>操作</th>
			</tr>
		</table>
	</div>
<div style="display:none" id="detail_dialog" title="商品属性详情">
		<table id="detail_list" cellpadding="0" cellspacing="0"></table>
</div>
<div style="display:none" id="add_attr_dialog" title="添加商品属性">
	<h3 id="attr_staff_name"><h3>
	<table cellpadding="0" cellspacing="0" style="border-top:0 none; margin-top:10px;">
			<tr>
				<td>类型(attr_type):</td>
				<td>
					<select id="attr_type">
						<option value="6">产品规格</option>
						<option value="3">产品用途</option>
						<option value="7">是否含糖</option>
						<option value="8">是否含酒</option>
						<option value="9">原料</option>
						<option value="11">产品描述</option>
					</select>
				</td>	
			</tr>
			<tr>
				<td>价格(attr_price):</td>
				<td><input id="attr_price" class="enter-item" value="0"/></td>	
			</tr>
			<tr>
				<td>描述(attr_value):</td>
				<td><input id="attr_value" class="enter-item" /></td>	
			</tr>
			<tr>
				<td>操作</td>
				<td><button id="add_attr_button" class="button">添加</button></td>	
			</tr>

	</table>
</div>
<div style="display:none" id="add_dialog" title="添加商品">
	<table cellpadding="0" cellspacing="0" style="border-top:0 none;">
		<tr>
			<td>SN<br>(goods_sn):</td>
			<td><input id="goods_sn" class="enter-item"/></td>	
		</tr>
		<tr>
			<td>商品类型<br>(goods_type):</td>
			<td>
				<select id="goods_type">
					<option value="1">蛋糕</option>
					<option value="2">附属配件（例如餐具，蜡烛）</option>
					<option value="3">鲜花</option>
				</select>
			</td>	
		</tr>
		<tr>
			<td>名称<br>(goods_name)</td>
			<td><input id="goods_name"  class="enter-item" class="enter-item"/></td>	
		</tr>
		<tr>
			<td>分类<br>(cat_id)</td>
			<td>
				<select id="cat_id">
				<?php
					$contents = json_decode(file_get_contents("../goodsconfig.json"),true);
					foreach($contents as $key=>$val){
						$str = '<option value="'.$key.'">'.$val['name'].'</option>';
						echo $str;
					}
				?>
				</select>
			</td>	
		</tr>
		<tr style="display:none">
			<td>品牌<br>(brand_id)</td>
			<td><input id="brand_id" class="enter-item"/></td>	
		</tr>
		<tr>
			<td>市场价格<br>(market_price,蛋糕填写1磅价格)</td>
			<td><input id="market_price" value="0.00" class="enter-item"/></td>	
		</tr>
		<tr>
			<td>关键字<br>(keywords)</td>
			<td><input id="keywords" class="enter-item"/></td>	
		</tr>
		<tr>
			<td>商品描述<br>(goods_desc)</td>
			<td><input id="goods_desc" class="enter-item"/></td>	
		</tr>
		<tr style="display:none">
			<td>是否含酒精<br>(is_wine)</td>
			<td>
				<select id="is_wine">
					<option value="0">无</option>
					<option value="1">有</option>
				</select>
			</td>	
		</tr>
		<tr style="display:none">
			<td>是否含坚果<br>(is_nut)</td>
			<td>
				<select id="is_nut">
					<option value="0">无</option>
					<option value="1">有</option>
				</select>
			</td>	
		</tr>
		<tr style="display:none">
			<td>是否含糖<br>(is_sugar)</td>
			<td>
				<select id="is_sugar">
					<option value="0">无</option>
					<option value="1">有</option>
				</select>
			</td>	
		</tr>
		<tr style="display:none">
			<td>是否含巧克力<br>(is_qkl)</td>
			<td>
				<select id="is_qkl">
					<option value="0">无</option>
					<option value="1">有</option>
				</select>
			</td>	
		</tr>
		<tr>
			<td>是否上架<br>(is_on_sale)</td>
			<td>
				<select id="is_on_sale">
					<option value="1">上架</option>
					<option value="0">下架</option>
				</select>
			</td>	
		</tr>
		<tr>
			<td>是否单独销售<br>(is_alone_sale)</td>
			<td>
				<select id="is_alone_sale">
					<option value="1">是</option>
					<option value="0">否</option>
				</select>
			</td>	
		</tr>
		<tr>
			<td>产品图片<br>(goods_thumb)</td>
			<td>
			 <input type="file" id="goods_image_file">
			 <input type="hidden" id="goods_thumb">
			 
			</td>	
		</tr>
		<tr>
			<td>操作</td>
			<td>
				<button class="button" id="add_goods_button">确认添加</button>
			</td>	
		</tr>
	</table>
</div>
</body>

<script>
	var CURRENT_GOODS_ID;
	//确认添加一个新的属性
	$('#add_attr_button').click(function(){
		var goods_id= CURRENT_GOODS_ID;
		var attr_type = $('#attr_type').val();
		var attr_price= $('#attr_price').val();
		var attr_value= $('#attr_value').val();
		$.post('api.php?action=add_attr',{
			goods_id:goods_id,
			attr_type:attr_type,
			attr_price:attr_price,
			attr_value:attr_value
		},function(d){
			alert('添加成功');
		},'json');
	});

	//添加一个全新的商品
	$('#add_goods_button').click(function(){
		var cat_id = $('#cat_id').val();
		var goods_sn = $('#goods_sn').val();
		var goods_type = $('#goods_type').val();
		var goods_name = $('#goods_name').val();
		var goods_cato = $('#goods_cato').val();
		var goods_desc = $('#goods_desc').val();
		var market_price = $('#market_price').val();
		var keywords = $('#keywords').val();
		var is_on_sale = $('#is_on_sale').val();
		var is_alone_sale = $('#is_alone_sale').val();
		$.post('api.php?action=add_goods',{
			cat_id:cat_id,
			goods_sn:goods_sn,
			goods_type:goods_type,
			goods_name:goods_name,
			goods_cato:goods_cato,
			goods_desc:goods_desc,
			market_price:market_price,
			keywords:keywords,
			is_on_sale:is_on_sale,
			is_alone_sale:is_alone_sale
		},function(d){
			alert('添加成功');
		},'json');
	});

	$('#add_new_button').click(function(){
		$( "#add_dialog" ).dialog({
			modal:true,
			width:450,
			position:{ my: "top", at: "top", of: window }
		});
	});
	
	$.post('api.php?action=get_goods_list',function(d){
		var data = d.data;
		var html = '';
		for(var i=0;i<data.length;i++){
		  var current = data[i];
		  var onsale = '正在销售';
		  if(current.is_on_sale==0){
			onsale='已经下架';
		  }
		  html+='<tr><td>'+current.goods_sn+'</td>'; 
		   html+='<td>'+current.goods_name+'</td>';
		   html+='<td><img src="/themes/default/images/sgoods/'+current.goods_sn.substring(0,3)+'.jpg" width="75" onerror="$(this).remove()"></td>';
		   html+='<td>'+current.keywords+'</td>';
		   html+='<td>'+$(current.goods_desc).text()+'</td>';
		   
		   html+='<td>'+onsale+'</td>';
		   //html+='<td>'+current.is_alone_sale+'</td>';
		    html+='<td>'+current.market_price+'</td>';
		   html+='<td>\
		   <button class="button expand_button" data-id="'+current.goods_id+'">详细</button>\
		   <button class="button offline_button" data-id="'+current.goods_id+'">下架</button>\
		   <button class="button online_button" data-id="'+current.goods_id+'">上架</button>\
		   <button class="button add_attr_button" data-id="'+current.goods_id+'" data-name="'+current.goods_name+'">添加</button>\
			</tr>';
		}
		$('#exsit_list').append(html);
		$(".button").button();
	},'json');
	
	//展开一个商品所有的属性
	$(document).delegate('.expand_button','click',function(){
		var id = $(this).data('id');
		$.post('api.php?action=get_goods_attr_by_id',{id:id},function(d){
			var data = d.data;
			var html = '<tr>\
				<th width="20%">类型</th>\
				<th width="10%">价格</th>\
				<th>描述</th>\
				<th width="15%">操作</th>\
			</tr>';
			for(var i=0;i<data.length;i++){
				var d = data[i];
				var type =d.attr_id;
				if(d.attr_id == 6){
					type = '产品规格'
				}else if(d.attr_id == 7){
					type= '是否含糖'
				}else if(d.attr_id == 8){
					type= '是否含酒'
				}else if(d.attr_id == 9){
					type= '原料'
				}else if(d.attr_id == 11){
					type= '产品描述'
				}else if(d.attr_id == 3){
					type= '产品用途'
				}
				html+='<tr id="attr_col_'+d.goods_attr_id+'"><td>'+type+'</td>';
				html+='<td>'+(d.attr_price||'0')+'</td>';
				html+='<td>'+d.attr_value+'</td>';
				html+='<td><button class="button del_attr" data-id="'+d.goods_attr_id+'">删除</button></td></tr>';
			}
			$('#detail_list').html(html);
			$( "#detail_dialog" ).dialog({
				width:600,
				modal:true,
				position:{ my: "top", at: "top", of: window }
			});
		},'json');
	}).delegate('.offline_button','click',function(){
		var _this = $(this);
		var id=_this.data('id');
		$.post('api.php?action=offline_goods',{
			id:id
		},function(d){
			alert('下架成功');
			location.reload();
		},'json')
	}).delegate('.online_button','click',function(){
		var _this = $(this);
		var id=_this.data('id');
		$.post('api.php?action=online_goods',{
			id:id
		},function(d){
			alert('上架成功');
			location.reload();
		},'json')
	}).delegate('.del_attr','click',function(){
		var _this = $(this);
		var id=_this.data('id');
		$.post('api.php?action=del_attr',{
			id:id
		},function(d){
			alert('删除成功');
		},'json');
	}).delegate('.add_attr_button','click',function(){
		var _this = $(this);
		var id=_this.data('id');
		var name=_this.data('name');
		CURRENT_GOODS_ID = id;
		$('#attr_staff_name').html(name);
		$('#attr_staff_name').html();
		$("#add_attr_dialog").dialog({
			modal:true,
			width:450,
			position:{ my: "top", at: "top", of: window }
		});
	});



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
		this.jqInput = $('#'+this.opt.inputId);
		this.init();
		
	}
	
	Upload.prototype = {
		init:function(){
			var me = this;
			var iframeId = this.opt.iframeId;
			var jqForm = this.jqForm;
			jqForm.attr('target',iframeId);
			jqForm.attr('action',this.opt.url);
			jqForm.attr('enctype','multipart/form-data');
			jqForm.attr('method','post');
			jqForm.after('<iframe name="'+iframeId+'" id="'+iframeId+'" width="0" height="0"></iframe>');
			this.jqInput.change(function(){
				me.opt.upload();
				jqForm.submit();
			});
			setTimeout(function(){
				var iframe = $('#'+iframeId)[0];
				iframe.onload = iframe.onreadystatechange = function(){
					var ret = iframe.contentWindow.ret;
					me.opt.callback(ret);
				}
			},0);
		}
	}
							new Upload({
									inputId:'mes_input',
									formId:'mes_form',
									url:'api.php?action=uploadgoodsimage',
									upload:function(){
										$('#upload_tip').html('鍥剧墖姝ｅ湪涓婁紶涓�...');
									},
									callback:function(d){

									}
								});
														
</script>
</html>