<html>
<head>
	<?php include('inc/header.php');?>
</head>
<body>
   <?php require_once('api.php');?>
   <?php include('inc/menu.php');?>
  
		
	
	
	<div class="display-zone">
	    <div class="title">
			<span class="title-con">index管理</span>
		</div>
		<table id="home_list" cellpadding="0" cellspacing="0">
			<tr>
				<th>货号<br>(goods_sn)</th>
				<th>名称<br>(goods_name)</th>
				<th>图片</th>
				<th>操作</th>
			</tr>
		</table>
		
		<div class="title">
			<span class="title-con">产品管理</span>
		</div>
		<table id="exsit_list" cellpadding="0" cellspacing="0">
			<tr>
				<th>货号<br>(goods_sn)</th>
				<th>名称<br>(goods_name)</th>
				<th>图片</th>
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
						<option value="20">可切块</option>
						<option value="21">可做无糖</option>
						<option value="22">可切块数</option>
						<option value="23">无糖规格</option>
					</select>
					
				</td>	
			</tr>
			<tr id="attr_price_col">
				<td>价格(attr_price):</td>
				<td><input id="attr_price" class="enter-item" value="0"  placeholder="填写价格"/></td>	
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
					<select id="attr_cut" style="display:none">
						<option value="1">可切</option>
						<option value="0">不可切</option>
					</select>
					<select id="attr_nosugar" style="display:none">
						<option value="1">可做</option>
						<option value="0">不可做</option>
					</select>
					<select id="attr_nosugar_weight" style="display:none">
						<option value="0">选择磅重</option>
					</select>
					<select id="attr_cut_weight" style="display:none">
						<option value="0">选择磅重</option>
					</select>
					<input id="attr_cut_num" class="enter-item" style="display:none" placeholder="填写可切块数"/>
				</td>	
			</tr>
			<tr>
				<td>操作</td>
				<td><button id="add_attr_button" class="button">添加</button></td>	
			</tr>

	</table>
</div>

</div>
</body>

<script>
    $.post('api.php?action=get_weixin_home',function(d){
		var data = d;
		var html = '';
		for(var i=0;i<data.length;i++){
		  var current = data[i];
		  var onsale = '正在销售';
		  if(current.is_on_sale==0){
			onsale='已经下架';
		  }
		  html+='<tr><td>'+current.sn+'</td>'; 
		   html+='<td>'+current.name+'</td>';
		   html+='<td><img src="/themes/default/images/sgoods/'+current.sn.substring(0,3)+'.jpg" width="75" onerror="$(this).remove()"></td>';		   
		   html+='<td width="150">\
		   <button class="button del_button" data-id="'+current.id+'">从首页删除</button>\
			</tr>';
		}
		$('#home_list').append(html); 
		$(".button").button();
	},'json');
	
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
		   html+='<td width="150">\
		   <button class="button add_button" data-id="'+current.goods_id+'">添加到首页</button>\
			</tr>';
		}
		$('#exsit_list').append(html); 
		$(".button").button();
	},'json');
	
	//展开一个商品所有的属性
	$(document).delegate('.add_button','click',function(){
		var _this = $(this);
		var id=_this.data('id');
		$.post('api.php?action=add_to_weixin_home',{
			id:id
		},function(d){
			alert('添加成功');
			location.reload();
		},'json');
	}).delegate('.del_button','click',function(){
		var _this = $(this);
		var id=_this.data('id');
		$.post('api.php?action=del_from_weixin_home',{
			id:id
		},function(d){
		
			alert('删除成功');
			location.reload();
		},'json');
	});;
</script>
</html>