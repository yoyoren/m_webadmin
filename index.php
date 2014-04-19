<html>
<head>
	<?php include('inc/header.php');?>
</head>
<body>
   <?php require_once('api.php');?>
   <?php include('inc/menu.php');?>
	<div class="display-zone">
		<div class="title">
			<span class="title-con">产品管理</span>
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
				<th>价格</th>
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
						<option value="0">请选择</option>
						<option value="6">产品规格</option>
						<option value="3">产品用途</option>
						<option value="7">是否含糖</option>
						<option value="8">是否含酒</option>
						<option value="9">原料</option>
						<option value="11">产品描述</option>
					</select>
					
				</td>	
			</tr>
			<tr id="attr_price_col">
				<td>价格(attr_price):</td>
				<td><input id="attr_price" class="enter-item" value="0"/></td>	
			</tr>
			<tr>
				<td>描述(attr_value):</td>
				<td>
					<input id="attr_value" class="enter-item" />
					<select id="attr_weight" style="display:none">
						<option>1.0磅</option>
						<option>2.0磅</option>
						<option>3.0磅</option>
						<option>5.0磅</option>
						<option>10.0磅</option>
						<option>15.0磅</option>
						<option>20.0磅</option>
						<option>25.0磅</option>
						<option>30.0磅</option>
					</select>
					<select id="attr_size" style="display:none">
						<option value="">无准确规格</option>
						<option>13cm*13cm</option>
						<option>14cm*14cm</option>
						<option>15cm*15cm</option>
						<option>16cm*16cm</option>
						<option>17cm*17cm</option>
						<option>23cm*23cm</option>
						<option>30cm*30cm</option>
						<option>36cm*36cm</option>
						<option>50cm*50cm</option>
						<option>56cm*56cm</option>
						<option>62cm*62cm</option>
						<option>70cm*70cm</option>
					</select>
					<select id="attr_people" style="display:none">
						<option>适合3-4人食用</option>
						<option>适合4-5人食用</option>
						<option>适合5-6人食用</option>
						<option>适合7-8人食用</option>
						<option>适合11-12人食用</option>
						<option>适合15-20人食用</option>
						<option>适合30-40人食用</option>
						<option>适合40-50人食用</option>
						<option>适合50-60人食用</option>
						<option>适合60-70人食用</option>
						<option>适合70-80人食用</option>
						<option>适合80-100人食用</option>
						<option>适合100-120人食用</option>
					</select>
					<select id="attr_wine" style="display:none">
						<option>含酒</option>
						<option>不含酒</option>
					</select>
					<select id="attr_sugar" style="display:none">
						<option>含糖</option>
						<option>不含糖</option>
					</select>
				</td>	
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
			<td><input id="goods_sn" class="enter-item" placeholder="货号前3位不能重复"/></td>	
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
			<td><input id="goods_name"  class="enter-item" placeholder="最多20个字"/></td>	
		</tr>
		<tr>
			<td>分类<br>(cat_id)</td>
			<td>
				<select id="cat_id">
				<option value="0">请选择（配件可以不选）</option>
				<?php
					$contents = json_decode(file_get_contents("../goodsconfig.json"),true);
					foreach($contents as $key=>$val){
						$str = '<option value="'.$key.'">'.$val['name'].'</option>';
						echo $str;
					}
				?>
				</select>
				<select id="child_cat_id" style="display:none">
					<option value="0">请选择</option>
				</select>
			</td>	
		</tr>
		<tr style="display:none">
			<td>品牌<br>(brand_id)</td>
			<td><input id="brand_id" class="enter-item"/></td>	
		</tr>
		<tr>
			<td>市场价格<br>(market_price,蛋糕填写1磅价格)</td>
			<td><input id="market_price" value="0.00" class="enter-item" placeholder="蛋糕填写1磅价格"/></td>	
		</tr>
		<tr>
			<td>关键字<br>(keywords)</td>
			<td><input id="keywords" class="enter-item"  placeholder="用于搜索功能，可以不填"/></td>	
		</tr>
		<tr>
			<td>商品描述<br>(goods_desc)</td>
			<td><input id="goods_desc" class="enter-item" /></td>	
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
					<option value="0">下架</option>
					<option value="1">直接上架(请谨慎使用)</option>
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
			<td>产品封面图片<br>(goods_thumb)</td>
			<td>
			 <span id="goods_image_upload_tip"></span>
			 <input type="hidden" id="goods_thumb">
			 <form id="goods_image_file_form">
				 <input type="file" id="goods_image_file" name="images">
			 </form>
			</td>	
		</tr>
		<tr>
			<td>产品页面<br>（请联系猪爷索要）</td>
			<td>
			  <span id="goods_tmpl_upload_tip"></span>
			  <input type="hidden" id="goods_tmpl">
			  <form id="goods_tmpl_file_form">
				 <input type="file" id="goods_tmpl_file" name="page">
			 </form>
			</td>	
		</tr>
		<tr>
			<td>产品详情图片<br>(请联系猪爷索要)</td>
			<td>
			 <span id="goodsdetail_image_upload_tip"></span>
			 <input type="hidden" id="goodsdetail_thumb">
			 <form id="goodsdetail_image_file_form">
				 <input type="file" id="goodsdetail_image_file" name="images">
			 </form>
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
		var attr_weight = $('#attr_weight').val();
		var attr_people = $('#attr_people').val();
		var attr_size = $('#attr_size').val();
		if(attr_type==6){
			attr_value = attr_weight+':'+attr_size+attr_people;
		}else if(attr_type==7){
			attr_value = $('#attr_sugar').val();
		}else if(attr_type==8){
			attr_value = $('#attr_wine').val();
		}

		$.post('api.php?action=add_attr',{
			goods_id:goods_id,
			attr_type:attr_type,
			attr_price:attr_price,
			attr_value:attr_value,
			attr_weight:attr_weight,
			attr_people:attr_people
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
		var goods_cato = $('#cat_id').val();
		var child_cato = $('#child_cat_id').val();
		var goods_desc = $('#goods_desc').val();
		var market_price = $('#market_price').val();
		var keywords = $('#keywords').val();
		var is_on_sale = $('#is_on_sale').val();
		var is_alone_sale = $('#is_alone_sale').val();
		var goods_tmpl = $('#goods_tmpl').val();
		var goods_thumb = $('#goods_thumb').val();
		$.post('api.php?action=add_goods',{
			cat_id:cat_id,
			goods_sn:goods_sn,
			goods_type:goods_type,
			goods_name:goods_name,
			goods_cato:goods_cato,
			child_cato:child_cato,
			goods_desc:goods_desc,
			goods_thumb:goods_thumb,
			goods_tmpl:goods_tmpl,
			market_price:market_price,
			keywords:keywords,
			is_on_sale:is_on_sale,
			is_alone_sale:is_alone_sale
		},function(d){
			alert('添加成功');
			location.reload();
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
		   html+='<td width="150">'+current.keywords+'</td>';
		   html+='<td width="150">'+$(current.goods_desc).text()+'</td>';
		   
		   html+='<td>'+onsale+'</td>';
		   //html+='<td>'+current.is_alone_sale+'</td>';
		    html+='<td>'+current.market_price+'</td>';
		   html+='<td width="150">\
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

	$('#cat_id').change(function(){
		var cato = $(this).val();
		var childCato = $('#child_cat_id');
		if(cato!=0){
			$.get('api.php?action=get_child_cato',{
				cato:cato
			},function(d){
				var d=d.data;
				var html='';
				for(var i=0;i<d.length;i++){
					html+='<option value="'+d[i].name+'">'+d[i].title+'</option>';
				}
				childCato.html(html).show();
			},'json');
		}else{
			childCato.hide();
			childCato.html('<option value="0">请选择</option>');
		}
	});
	
	$('#attr_type').change(function(){
		var type = $(this).val();
		$('#attr_weight').hide();
		$('#attr_people').hide();
		$('#attr_price_col').hide();
		$('#attr_wine').hide();
		$('#attr_sugar').hide();
		$('#attr_size').hide();
		$('#attr_value').show();
		switch(type){
			case '3':break;
			case '6':
			$('#attr_weight').show();
			$('#attr_people').show();
			$('#attr_size').show();
			$('#attr_price_col').show();

			$('#attr_value').hide();
			break; 
			case '7':
			$('#attr_sugar').show();
			$('#attr_value').hide();
			break; 
			case '8':
			$('#attr_wine').show();
			$('#attr_value').hide();
			break; 
			case '9':break; 
			case '11':break; 
		}
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
        inputId : 'goods_image_file',
        formId : 'goods_image_file_form',
        iframeId : 'goods_image_file_iframe',
        url : 'api.php?action=uploadgoodsimage',
        upload : function(d) {
			$('#goods_image_upload_tip').html('正在上传...')
        },
        callback : function(d) {
			if(d&&d.code==0){
				$('#goods_image_upload_tip').html('上传成功');
				$('#goods_thumb').val(d.url);
				$('#goods_image_upload_tip').append('<img src="/'+d.url+'" width="70"/>');
			}
        }
      });


	   new Upload({
        inputId : 'goodsdetail_image_file',
        formId : 'goodsdetail_image_file_form',
        iframeId : 'goodsdetail_image_file_iframe',
        url : 'api.php?action=uploadgoodsdetailimage',
        upload : function(d) {
			$('#goodsdetail_image_upload_tip').html('正在上传...')
        },
        callback : function(d) {
			if(d&&d.code==0){
				$('#goodsdetail_image_upload_tip').html('上传成功');
				$('#goodsdetail_image_upload_tip').append('<img src="/'+d.url+'" width="70"/>');
			}
        }
      });

	  //上传产品页面
	  new Upload({
        inputId : 'goods_tmpl_file',
        formId : 'goods_tmpl_file_form',
        iframeId : 'goods_tmpl_file_iframe',
        url : 'api.php?action=uploadgoodstmpl',
        upload : function(d) {
			$('#goods_tmpl_upload_tip').html('正在上传...')
        },
        callback : function(d) {
			if(d&&d.code==0){
				$('#goods_tmpl_upload_tip').html('上传成功');
				$('#goods_tmpl').val(d.url);
			}
        }
      });
</script>
</html>