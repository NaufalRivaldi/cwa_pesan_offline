<!doctype html>
<html lang="en">
<head>
<base href="<?=base_url()?>">
<meta charset="UTF-8">
<title>Back-End Admin</title>
<link rel="stylesheet" href="css/admin.bootstrap.min.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet/less" href="css/admin.less">
</head>
<body id="login">

<form action="backend/home/login" method="post" class="loginbox">
	<h1><span class="fa fa-key"></span> Admin Log In</h1>
	<?=$this->def->msghandling()?>
	<div>
		<input type="text" name="username" id="username" placeholder="Username">
	</div>
	<div>
		<input type="password" name="password" id="password" placeholder="Password">
	</div>
	<div align="center">
		<button><span class="fa fa-sign-in"></span> Log In</button>
	</div>
</form>


<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/less-1.3.3.min.js"></script>
<script>
	
</script>
</body>
</html>