<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>登录</title>
<link href="css/login.css" rel="stylesheet">
</head>
<body >
  <div class="ng-form-area show-place" id="form-area">
    <h4 class="title">MES商品管理系统</h4>
    <form method="post" id="miniLogin">
      <div class="shake-area" id="shake_area">
        <p class="error-tip" id="login_error" style="display:none">用户名或密码错误!</p>
        <div class="enter-area">
          <input type="text" class="enter-item first-enter-item"  autocomplete="off" placeholder="用户名" id="username" />
        </div>
        <div class="enter-area">
          <input type="password" class="enter-item last-enter-item"  autocomplete="off" placeholder="密码"  id="password"/>
        </div>
      </div>

      <input class="button orange" type="button" value="立即登录" id="do_login"/>
      <div class="ng-foot clearfix">
        <div class="ng-link-area">
          <span id="custom_display_64" style="display:none">
            <a href="#" target="_blank">忘记密码?</a>
          </span>
        </div>
      </div>
      <span id="custom_display_128" style="display:none">
        <a href="#" class="button">注册管理帐户</a>
      </span>
    </form>
  </div>
  <script src="js/jquery-1.10.2.js"></script>
  <script>
	$('#do_login').click(function(){
		var username = $('#username').val();
		var password = $('#password').val();
		$.post('api.php?action=admin_login',{
			username:username,
			password:password
		},function(d){
			if(d == 'success'){
				location.href = 'index.php';
			}else{
				$('#login_error').show();
			}
		});
	});
  </script>
</body>
</html>


