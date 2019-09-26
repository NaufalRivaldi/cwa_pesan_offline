<!doctype html>
<html lang="en">
<head>
	<base href="<?=base_url()?>">
	<meta charset="UTF-8">
	<title>Login - CWA Mail</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet/less" href="css/style.less">
</head>
<body>

<div class="login">
	<form action="home/login" method="post">
	<div class="login-box">
		<div class="h2">Log In</div>
		<?=$this->def->msghandling();?>
		<div class="ipt">
			<input type="text" name="username" placeholder="Username">
			<span class="fa fa-user"></span>
		</div>
		<div class="ipt">
			<input type="password" name="password" placeholder="Password">
			<span class="fa fa-key"></span>
		</div>
		<button><span class="fa fa-sign-in fa-fw"></span> Log In</button>
	</div>
	</form>
</div>

<script src="js/less-1.3.3.min.js"></script>
</body>
</html>
