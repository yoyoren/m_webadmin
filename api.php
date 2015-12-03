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
/*
if($action!='admin_login'){
	if(!check_login()){
		echo '<script>location.href="login.php"</script>';
		echo json_encode(array('code'=>'10001','msg'=>'login_frist'));
		die;
	}
}
*/
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
   case 'get_child_cato':
	    $cato = ANTI_SPAM($_GET['cato']);
		$CAKE_CATO = file_get_contents("../goodsconfig.json");
		$CAKE_CATO = json_decode($CAKE_CATO,true);
		$CAKE_CATO = $CAKE_CATO[$cato]['cato'];
		echo json_encode(array('data'=>$CAKE_CATO));
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
   case 'get_online_goods_list':
		$data = $db->getAll('select * from ecs_goods where is_on_sale="1"');
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
		$attr_price = ANTI_SPAM($_POST['attr_price'],array('empty'=>true));
		$attr_value = ANTI_SPAM($_POST['attr_value']);
		$attr_people = $_POST['attr_people'];
		$attr_weight = $_POST['attr_weight'];
		$sql = "INSERT INTO ecs_goods_attr (goods_id,attr_id,attr_value,attr_price) values('{$goods_id}','{$attr_type}','{$attr_value}','{$attr_price}');";
		$data = $db->query($sql);
		
		//添加商品到客服系统
		if($attr_type == 6){
			$sql_call = "INSERT INTO call_goods_attr (goods_id,attr_id,attr_value,attr_price) values('{$goods_id}','{$attr_type}','{$attr_weight}','{$attr_price}');";
			$res = $db->query($sql_call);
		}
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
	   $goods_cato = ANTI_SPAM($_POST['goods_cato'],array('empty'=>true));
	   $child_cato = ANTI_SPAM($_POST['child_cato'],array('empty'=>true));

	   //商品小图地址
	   $goods_thumb = ANTI_SPAM($_POST['goods_thumb']);
	   
	   //商品模板地址
	   $goods_tmpl = ANTI_SPAM($_POST['goods_tmpl']);
	 

	   $market_price = ANTI_SPAM($_POST['market_price']);
	   $is_on_sale = ANTI_SPAM($_POST['is_on_sale'],array('empty'=>true));
	   $is_alone_sale = ANTI_SPAM($_POST['is_alone_sale'],array('empty'=>true));
	   $keywords  = ANTI_SPAM($_POST['keywords']);
       
	   $add_time = time();
	   $sql = "INSERT INTO ecs_goods (cat_id,goods_sn,goods_name,goods_name_style,click_count,brand_id,provider_name,goods_number,goods_weight,market_price,shop_price,promote_price,promote_start_date,promote_end_date,warn_number,keywords,goods_brief,goods_desc,goods_thumb,goods_img,original_img,is_real,extension_code,is_on_sale,is_alone_sale,is_shipping,integral,add_time,sort_order,is_delete,is_best,is_new,is_hot,is_promote,bonus_type_id,last_update,goods_type,seller_note,give_integral,rank_integral,suppliers_id,is_check,is_sugar) values('{$cat_id}','{$goods_sn}','{$goods_name}','+',0,4,'',0,'0.000','{$market_price}','0.00','0.00',0,0,0,'{$keywords}','','{$goods_desc}','','','',1,'',{$is_on_sale},{$is_alone_sale},0,0,{$add_time},100,0,0,0,0,0,0,{$add_time},{$goods_type},'',-1,-1,0,NULL,1)";
	   
	   $data = $db->query($sql);
	   $gen_id = $db -> insert_id();
	   $filename = substr($goods_sn,0,3);

	   //如果是增加蛋糕 就需要更新配置的JSON

	   if($goods_cato&&$child_cato){

			$CAKE_CATO = file_get_contents("../goodsconfig.json");
			$CAKE_CATO = json_decode($CAKE_CATO,true);

			
			for($i=0;$i<count($CAKE_CATO[$goods_cato]['cato']);$i++){
				if($CAKE_CATO[$goods_cato]['cato'][$i]['name']==$child_cato){
					//新加的蛋糕都放在第一顺位
					array_unshift($CAKE_CATO[$goods_cato]['cato'][$i]['data'], $gen_id);
				}
			}
	   }
	   file_put_contents("../goodsconfig.json",json_encode($CAKE_CATO));

	   copy(ROOT_PATH.$goods_thumb,ROOT_PATH.'themes/default/images/sgoods/' . $filename . '.jpg');
	   copy(ROOT_PATH.$goods_tmpl,ROOT_PATH.'tmpl/cake_'.$gen_id.'.htm');
	   echo json_encode(array(
		   'data'=>$data,
		   'code'=>0,
	   ));
	   break;
   //上传页面的模板
   case 'uploadgoodstmpl':
			$file = $_FILES['page'];
			$size = $file['size'];
			$type = $file['type'];

			if($size>1*1024*1024){
				 echo '<script>window.ret="'.json_encode(array('code'=>1,'msg'=>'文件体积过大,不能大于1MB')).'"</script>';
				 return;
			}
			
	
			$filename = date("YmdHis");
			$url = 'tmpl/' . $filename . '.htm';  
			$upfile = ROOT_PATH.$url;
			if(is_uploaded_file($file['tmp_name'])){  
			   if(!move_uploaded_file($file['tmp_name'], $upfile)){  
					 '<script>window.ret="'.json_encode(array('code'=>3,'msg'=>'server error')).'"</script>'; 
					 return;
				}
			}
			echo "<script>window.ret=".json_encode(array('code'=>0,'msg'=>'success','url'=>$url))."</script>";
	   break;
		//上传封面图片
		case 'uploadgoodsimage':
		    $file = $_FILES['images'];
			$images = $_POST['images'];
			$size = $file['size'];
			$type = $file['type'];
			if($size>1*1024*1024){
				 echo '<script>window.ret="'.json_encode(array('code'=>1,'msg'=>'文件体积过大,不能大于1MB')).'"</script>';
				 return;
			}
			
			
			if($type!='image/jpeg'&&$type!='image/jpg'&&$type!='image/png'&&$type!='image/gif'){
				 echo "<script>window.ret='".json_encode(array('code'=>2,'msg'=>'文件格式不支持'))."'</script>";
				 return;
			}
			
			$filename = date("YmdHis");
			$url = 'themes/default/images/sgoods/' . $filename . '.jpg';  
			$upfile = ROOT_PATH.$url;
			if(is_uploaded_file($file['tmp_name'])){  
			   if(!move_uploaded_file($file['tmp_name'], $upfile)){  
					 '<script>window.ret="'.json_encode(array('code'=>3,'msg'=>'server error')).'"</script>'; 
					 return;
				}
			}
			echo "<script>window.ret=".json_encode(array('code'=>0,'msg'=>'success','url'=>$url))."</script>";
		break;
	case 'uploadgoodsdetailimage':
			//$goods_sn = ANTI_SPAM($_POST['goods_sn']);
		    $file = $_FILES['images'];
			$images = $_POST['images'];
			$size = $file['size'];
			$type = $file['type'];
			if($size>1*1024*1024){
				 echo '<script>window.ret="'.json_encode(array('code'=>1,'msg'=>'文件体积过大,不能大于1MB')).'"</script>';
				 return;
			}
			
			
			if($type!='image/jpeg'&&$type!='image/jpg'&&$type!='image/png'&&$type!='image/gif'){
				 echo "<script>window.ret='".json_encode(array('code'=>2,'msg'=>'文件格式不支持'))."'</script>";
				 return;
			}
			$filename = $file['name'];

			//这个文件夹存放的是详情图片
			$url = 'img/pro/'.$filename;  
			$upfile = ROOT_PATH.$url;
			if(is_uploaded_file($file['tmp_name'])){  
			   if(!move_uploaded_file($file['tmp_name'], $upfile)){  
					 '<script>window.ret="'.json_encode(array('code'=>3,'msg'=>'server error')).'"</script>'; 
					 return;
				}
			}
			echo "<script>window.ret=".json_encode(array('code'=>0,'msg'=>'success','url'=>$url))."</script>";
		break;
	case 'get_banner_list':
		$banner = file_get_contents("../indexconfig.json");
		$banner = json_decode($banner,true);
		echo json_encode(array('data'=>$banner));
		break;
		
	 case 'get_wx_banner_list':
		$banner = file_get_contents("../index_wx_config.json");
		$banner = json_decode($banner,true);
		echo json_encode(array('data'=>$banner));
		break;
		
	 case 'get_weixin_home':
	     $orgin_data = file_get_contents("../index_wx_goods_config.json");
		 echo $orgin_data;
	     break;
		 
	 case 'del_from_weixin_home':
	     $id = $_REQUEST['id'];
		 $orgin_data = file_get_contents("../index_wx_goods_config.json");
		 $orgin_data = json_decode($orgin_data,true);
		 $temp = array();
		 
		 for($i=0;$i<count($orgin_data);$i++){
			if($orgin_data[$i]['id'] != $id){
				array_push($temp,$orgin_data[$i]);
			}
		 }
		 
		 $data = json_encode($temp);
		 file_put_contents("../index_wx_goods_config.json",$data);
		 echo 0;
	     break;
		 
	 case 'add_to_weixin_home':
		 $id = $_POST['id'];
		 $sql = "SELECT * FROM ecs_goods WHERE goods_id=".$id;
		 $goods = $db->getAll($sql)[0];
		 $save = array(
		   'name'=>$goods['goods_name'],
		   'sn'=>$goods['goods_sn'],
		   'id'=>$goods['goods_id'],
		   'img'=>'/themes/default/images/sgoods/'.substr($goods['goods_sn'],0,3).'.jpg',
		 ); 
		
		 $orgin_data = file_get_contents("../index_wx_goods_config.json");
		 $orgin_data = json_decode($orgin_data);
		 //var_dump($orgin_data);
		 if($orgin_data){
		   array_push($orgin_data,$save); 
		   $orgin_data = json_encode($orgin_data);
		   //var_dump($orgin_data);
		   file_put_contents("../index_wx_goods_config.json",$orgin_data);
		 }else{
		   $save = json_encode(array($save));
		   file_put_contents("../index_wx_goods_config.json",$save);
		 }
		 echo 0;
		 //var_dump($goods);
		 break;
	 case 'add_banner':
		 $pos = $_POST['pos'];
		 $title = $_POST['title'];
		 $desc = $_POST['desc'];
		 $link = $_POST['link'];
		 
		 $banner_thumb = $_POST['banner_thumb'];
		 $banner = file_get_contents("../indexconfig.json");
		 $banner = json_decode($banner,true);
		 $new_banner = array(
			'desc'=>$desc,
			'title'=>$title,
			'img'=>$banner_thumb,
			'link'=>$link
		 );
		 if($pos == 1){
			array_unshift($banner,$new_banner);
		 }else if($pos == 2){
			array_push($banner,$new_banner);
		 }
		 $banner = json_encode($banner);
		 if(file_put_contents("../indexconfig.json",$banner)){
			echo 'success';
		 }else{
			echo 'fail';
		 }
		 
		break;
		case 'add_wx_banner':
				 $pos = $_POST['pos'];
				 $title = $_POST['title'];
				 $desc = $_POST['desc'];
				 $link = $_POST['link'];
				 
				 $banner_thumb = $_POST['banner_thumb'];
				 $banner = file_get_contents("../index_wx_config.json");
				 $banner = json_decode($banner,true);
				 $new_banner = array(
					'desc'=>$desc,
					'title'=>$title,
					'img'=>$banner_thumb,
					'link'=>$link
				 );
				 
				 if($pos == 1){
					array_unshift($banner,$new_banner);
				 }else if($pos == 2){
					array_push($banner,$new_banner);
				 }
				 $banner = json_encode($banner);
				 //var_dump($banner);
				 if($banner == null || $banner == 'null'){
					 $banner = array($new_banner);
					 $banner = json_encode($banner);
				 }
				 //var_dump($banner);
				 if(file_put_contents("../index_wx_config.json",$banner)){
					echo 'success';
				 }else{
					echo 'fail';
				 }
				 
				break;
	 case 'del_banner':
		 $index = $_POST['index'];
		 $banner = file_get_contents("../indexconfig.json");
		 $banner = json_decode($banner,true);
		 $res = array();

		 for($i=0;$i<count($banner);$i++){
			if($i!=$index){
				array_push($res,$banner[$i]);
			}
		 }

		 $banner = json_encode($res);
		 if(file_put_contents("../indexconfig.json",$banner)){
			echo 'success';
		 }else{
			echo 'fail';
		 }
		 break;
		 
     case 'del_wx_banner':
		 $index = $_POST['index'];
		 $banner = file_get_contents("../index_wx_config.json");
		 $banner = json_decode($banner,true);
		 $res = array();

		 for($i=0;$i<count($banner);$i++){
			if($i!=$index){
				array_push($res,$banner[$i]);
			}
		 }

		 $banner = json_encode($res);
		 if(file_put_contents("../index_wx_config.json",$banner)){
			echo 'success';
		 }else{
			echo 'fail';
		 }
		 break;
	case 'get_goods_by_cato':
		 $goods = file_get_contents("../goodsconfig.json");
		 $goods = json_decode($goods,true);
		 
		 foreach($goods as $key=>$value){
			$count = count($value['cato']);
			for($i=0;$i<$count;$i++){
				$d = $value['cato'][$i]['data'];
				$goods[$key]['cato'][$i]['detail']=array();
				for($j=0;$j<count($d);$j++){
					$id = $d[$j];
					$sql = "select * from ecs_goods where goods_id=".$id;
					$goods_data = $db->getAll($sql);
					$goods_data = $goods_data[0];
					array_push($goods[$key]['cato'][$i]['detail'],$goods_data);
				}
			}

		 }
		 echo json_encode(array(data=>$goods));
		 break;
		//商品加到一个分类当中
		case 'get_goods_by_id':
			$id = $_GET['id'];
			$sql = "select * from ecs_goods where goods_id=".$id;
			$goods_data = $db->getAll($sql);
			$goods_data = $goods_data[0];
			echo json_encode(array(data=>$goods_data));
		break;
		case 'mod_goods':
			  $id = ANTI_SPAM($_POST['id']);
			  $goods_sn = ANTI_SPAM($_POST['goods_sn']);
			  $goods_name = ANTI_SPAM($_POST['goods_name']);
			  $goods_desc = ANTI_SPAM($_POST['goods_desc']);
			  $market_price = ANTI_SPAM($_POST['market_price']);
			  $keywords = ANTI_SPAM($_POST['keywords']);
			  $sql = "update ecs_goods set goods_name='".$goods_name."' where goods_id=".$id;
			  $res = $db->query($sql);
			  echo json_encode(array(
					'code'=>0,
					'res'=>$res, 
				));
		      break;
		case 'add_goods_to_cato': 
			$id = $_POST['id'];
			$cato = $_POST['cato'];
			$child_cato = $_POST['child_cato'];

			$goods = file_get_contents("../goodsconfig.json");
			$goods = json_decode($goods,true);
			$d = $goods[$cato]['cato'];
			for($i=0;$i<count($d);$i++){
				if($d[$i]['name'] == $child_cato ){
				  if(!in_array($id,$d[$i]['data'])){
					array_push($goods[$cato]['cato'][$i]['data'],$id);
					break;
				  }
				}
			}
			$goods = json_encode($goods);
			if(file_put_contents("../goodsconfig.json",$goods)){
				echo 'success';
			}else{
				echo 'fail';
			}
		break;

		//商品加到一个分类当中
		case 'del_goods_from_cato': 
			$id = $_POST['id'];
			$cato = $_POST['cato'];
			$child_cato = $_POST['child_cato'];

			$goods = file_get_contents("../goodsconfig.json");
			$goods = json_decode($goods,true);
			$d = $goods[$cato]['cato'];
			for($i=0;$i<count($d);$i++){
				if($d[$i]['name'] == $child_cato ){
				  if(in_array($id,$d[$i]['data'])){
					$res = array();
					for($m=0;$m<count($d[$i]['data']);$m++){
						if($d[$i]['data'][$m]!=$id){
							array_push($res,$d[$i]['data'][$m]);
						}
					}
					$goods[$cato]['cato'][$i]['data']=$res;
					break;
				  }
				}
			}
			$goods = json_encode($goods);
			if(file_put_contents("../goodsconfig.json",$goods)){
				echo 'success';
			}else{
				echo 'fail';
			}
		break;
	case 'get_weight_by_id':
		$id = $_GET['id'];
		$sql = "select * from ecs_goods_attr where attr_id=6 and goods_id=".$id;
		$data = $db->getAll($sql);
		echo json_encode(array('data'=>$data));
		break;
 }
?>