<?php
define('IN_ECS', true);
require_once ('../includes/init.php');
require_once ('../lib/safe.php');


Predis\Autoloader::register();
$db_addr = '127.0.0.1';
$db_username = 'root';
$db_password = '';
$db_name = 'mescake';

//$con = mysql_connect("localhost",$db_username,$db_password,$db_name);
//开启一个全局的redis
$REDIS_CLIENT = new Predis\Client($redis_config);
$action = ANTI_SPAM($_GET['action'],array('empty'=>true));
function check_login(){
	global $db;
	if(!$_COOKIE['admin_token']||!$_COOKIE['uuid']){
		return false;
	}
	$uuid = $_COOKIE['uuid'];
	$sql = "select * from mes_admin where id='{$uuid}'";
	$user = $db->getAll($sql);
	
	if(count($user)>0){
		$user = $user[0];
	
		$token = md5($user['username'].$user['password']);
		if($_COOKIE['admin_token'] == $token){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

if($action!='admin_login'){
	if(!check_login()){
		echo '<script>location.href="login.php"</script>';
		echo json_encode(array('code'=>'100001','msg'=>'login_frist'));
		die;
	}
}
switch($action){
	 //管理员登陆
   case 'admin_login':
		$password =  ANTI_SPAM($_POST['password']);
		$username =  ANTI_SPAM($_POST['username']);
		$password = md5($password.'_mescake');
		$password = strtoupper($password);

	    $sql = "select * from mes_admin where username='{$username}' and password='{$password}'";
		$user = $db->getAll($sql);

		if(count($user)>0){
			setcookie('admin_token', md5($username.$password),time()+3600);
			setcookie('uuid', $user[0]['id'],time()+3600);
			echo 'success';
		}else{
			echo 'fail';
		}
		break;
   case 'del_goods_page_cache':
	    $id = ANTI_SPAM($_POST['id']);
        $REDIS_CLIENT->del('goods_page'.$id);
		echo 'success';
		break;
   
   case 'show_goods_page':
		$id = ANTI_SPAM($_GET['id']);
        $content = $REDIS_CLIENT->get('goods_page'.$id);
		echo $content;
		break;
   
   case 'get_goods_list':
	   $data = $db->getAll('select * from ecs_goods');
		echo json_encode(array('data'=>$data));
		break;
   
   case 'get_goods_attr_by_id':
	   $goods_id = ANTI_SPAM($_POST['id']);
	   $data = $db->getAll('select * from ecs_goods_attr where goods_id='.$goods_id);
	   echo json_encode(array('data'=>$data));
	   break;

   case 'del_attr':
	   $id = ANTI_SPAM($_POST['id']);
	   $sql = "delete from ecs_goods_attr where goods_attr_id='{$id}'";
	   $data = $db->query($sql);
	   echo json_encode(array(
		    'code'=>0,
			'data'=>$data
		));
	   break;
   case 'add_attr':
		$goods_id = ANTI_SPAM($_POST['goods_id']);
		$attr_type = ANTI_SPAM($_POST['attr_type']);
		$attr_price = ANTI_SPAM($_POST['attr_price']);
		$attr_value = ANTI_SPAM($_POST['attr_value']);
		$sql = "INSERT INTO ecs_goods_attr (goods_id,attr_id,attr_value,attr_price) values('{$goods_id}','{$attr_type}','{$attr_value}','{$attr_price}');";
		$data = $db->query($sql);
		
		//添加商品到客服系统
		$sql_call = "INSERT INTO call_goods_attr (goods_id,attr_id,attr_value,attr_price) values('{$goods_id}','{$attr_type}','{$attr_value}','{$attr_price}');";
		$res = $db->query($sql_call);

	   echo json_encode(array(
		    'code'=>0,
			'data'=>$data,
			'res'=>$res, 
		));
	   break;
   case 'online_goods':
	    $goods_id = ANTI_SPAM($_POST['id']);
	    $sql = "update ecs_goods set is_on_sale='1' where goods_id=".$goods_id;
		$res = $db->query($sql);
		echo json_encode(array(
		    'code'=>0,
			'res'=>$res, 
		));
	   break;
   case 'offline_goods':
	   $goods_id = ANTI_SPAM($_POST['id']);
		$sql = "update ecs_goods set is_on_sale='0' where goods_id=".$goods_id;
		$res = $db->query($sql);
		echo json_encode(array(
		    'code'=>0,
			'res'=>$res, 
		));
	   break;
   case 'add_goods':
	   $cat_id = ANTI_SPAM($_POST['cat_id']);
	   $goods_sn = ANTI_SPAM($_POST['goods_sn']);
	   $goods_type = ANTI_SPAM($_POST['goods_type']);
	   $goods_name = ANTI_SPAM($_POST['goods_name']);
	   $goods_desc = ANTI_SPAM($_POST['goods_desc']);
	   //$goods_cato = $_POST['goods_cato'];

	   $market_price = ANTI_SPAM($_POST['market_price']);
	   $is_on_sale = ANTI_SPAM($_POST['is_on_sale']);
	   $is_alone_sale = ANTI_SPAM($_POST['is_alone_sale']);
	   $keywords  = ANTI_SPAM($_POST['keywords']);
       
	   $add_time = time();
	   $sql = "INSERT INTO ecs_goods (cat_id,goods_sn,goods_name,goods_name_style,click_count,brand_id,provider_name,goods_number,goods_weight,market_price,shop_price,promote_price,promote_start_date,promote_end_date,warn_number,keywords,goods_brief,goods_desc,goods_thumb,goods_img,original_img,is_real,extension_code,is_on_sale,is_alone_sale,is_shipping,integral,add_time,sort_order,is_delete,is_best,is_new,is_hot,is_promote,bonus_type_id,last_update,goods_type,seller_note,give_integral,rank_integral,suppliers_id,is_check,is_sugar) values('{$cat_id}','{$goods_sn}','{$goods_name}','+',0,4,'',0,'0.000','{$market_price}','0.00','0.00',0,0,0,'{$keywords}','','{$goods_desc}','','','',1,'',{$is_on_sale},{$is_alone_sale},0,0,{$add_time},100,0,0,0,0,0,0,{$add_time},{$goods_type},'',-1,-1,0,NULL,1)";
	   
	   $data = $db->query($sql);
	   
	   echo json_encode(array(
		   'data'=>$data,
		   'code'=>0,
	   ));
   break;
 }
?>