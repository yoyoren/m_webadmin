<html>
<head>
<?php include('inc/header.php');?>
</head>
<body>
	<?php require_once('api.php');?>
    <?php include('inc/menu.php');?>
	<div class="display-zone" id="list">
		<div class="title">
			<span class="title-con">产品分类管理</span>
			<span class="action-bar">
				<button class="button" id="add_new_cato">添加产品到一个分类</button>
			</span>
		</div>
	
	</div>
    <style>
h2,h3{width: 90%; margin: 10px auto;}
h2{padding: 10px 0; text-indent: 20px; background: #f6f6f6;}
h3{text-indent: 30px; padding: 10px 0; color: #1c94c4;}
    </style>

	<div style="display:none" id="add_dialog" title="添加banner">
		<table cellpadding="0" cellspacing="0" style="border-top:0 none;">
			<tr>
				<td>选择分类</td>
				<td>
				<select id="cat_id">
					<option value="0">请选择</option>
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
			<tr>
				<td>选择蛋糕</td>
				<td>
				<select id="cake_sel">
				</select>
				</td>	
			</tr>
			<tr>
				<td>操作</td>
				<td><button class="button" id="add_new_cato_button">确认添加（添加后会直接上线）</button></td>	
			</tr>
		</table>
	</div>
</body>
<script>
	var table_begin = '<table cellpadding="0" cellspacing="0">\
			<tr>\
				<th>名称</th>\
				<th>图片</th>\
				<th>操作</th>\
			</tr>';
	var table_end = '</table>';
	
$.get('api.php?action=get_online_goods_list',function(d){
	var data = d.data;
	var html = '';
	for(var j=0;j<data.length;j++){
		html+='<option value="'+data[j].goods_id+'">'+data[j].goods_name+'</option>';
	}
  $('#cake_sel').append(html);
},'json');

$.get('api.php?action=get_goods_by_cato',function(d){
	var data = d.data;
	for(var i in data){
		var d = data[i];
		var cato = d['cato'];
		var html='<h2>'+d.name+'</h2>';
		//html+='<h3>'+cato+'</h3>'

		for(var j=0;j<cato.length;j++){
			var goods = cato[j]['detail'];
			html+= '<h3>'+cato[j].title+'</h3>';
			html+=table_begin;
			for(var m=0;m<goods.length;m++){
				html+='<tr>';
				html+='<td>'+goods[m].goods_name+'</td>';
				html+='<td><img src="/themes/default/images/sgoods/'+goods[m].goods_sn.substring(0,3)+'.jpg" width="70"/></td>';
				html+='<td><button class="button del_button" data-childcato="'+cato[j].name+'" data-cato="'+i+'" data-id="'+goods[m].goods_id+'">删除(从分类中剔除)</td>';
				html+='</tr>';
			}
			html+=table_end;
		}
		$('#list').append(html);
		$('.button').button();
	}
},'json');

$('#add_new_cato').click(function(){
	$('#add_dialog').dialog({
		modal:true,
		width:400
	});
});
$('#add_new_cato_button').click(function(){
	var cato = $('#cat_id').val();
	var child_cato = $('#child_cat_id').val();
	var id = $('#cake_sel').val();
	$.post('api.php?action=add_goods_to_cato',{
		cato:cato,
		child_cato:child_cato,
		id:id
	},function(d){
		if(d=='success'){
			alert('添加成功');
			location.reload();
		}else{
			alert('添加失败');
		}	
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

$(document).delegate('.del_button','click',function(d){
	var _this = $(this);
	var id = _this.data('id');
	var cato = _this.data('cato');
	var child_cato = _this.data('childcato');
	$.post('api.php?action=del_goods_from_cato',{
		cato:cato,
		child_cato:child_cato,
		id:id
	},function(d){
		if(d=='success'){
			alert('删除成功');
			location.reload();
		}else{
			alert('删除失败');
		}	
	});
});
</script>
</html>