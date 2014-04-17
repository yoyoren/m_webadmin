<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<h2><a>页面缓存管理</a></h2>
	<?php 
		define('IN_ECS', true);
		require ('../includes/init.php');
		$page_cache = $REDIS_CLIENT -> keys('goods_page*');
		$html = '<table><tr><td>页面id</td><td>操作1</td><td>操作2</td></tr>';
		for($i=0;$i<count($page_cache);$i++){
		  $redis_id = $page_cache[$i];
		  $html.='<tr><td>'. $redis_id.'</td>
		  <td><button class="page_detail" data-id="'. $redis_id.'">查看</button></td>
		  <td><button class="clear_cache" data-id="'. $redis_id.'">清理缓存</button></td>
		  </tr>';
		}
		 $html.='</table>';
		 echo $html;
		 function a($b){
		    echo $b();
		 }
		  function c() {return 'aaaaa';}
		 a('c');
	?>
	<h2>管理首页轮播</h2>
	<h2>商品管理</h2>
</body>

<script src="http://www.mescake.com/script/jquery-1.10.min.js"></script>
<script>
$('.page_detail').click(function(){
	var id = $(this).data('id').replace('goods_page','');
	location.href = 'api.php?action=show_goods_page&id='+id;
});

$('.clear_cache').click(function(){
	var id = $(this).data('id').replace('goods_page','');
	$.post('api.php?action=del_goods_page_cache',{
		id:id
	},function(d){
		alert('清除成功!');	
	},'json');
});
</script>
</html>