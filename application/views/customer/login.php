<body class="gray-bg">
<div class="middle-box text-center loginscreen  animated fadeInDown">
	<div>
		<div>
            <h1 class="logo-name">FITPA</h1>
        </div>
		<h3>Welcome to FITPA</h3>
		<p>Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
			<!--Continually expanded and constantly improved Inspinia Admin Them (IN+)-->
		</p>
		<p>Login in. To see it in action.</p>
		<?php if ($loginError !== ''){?>
		<div class="alert alert-warning"><?php echo $loginError?></div>
		<?php }?>
		<form class="m-t" role="form" id="nextfrm" action="/mainLogin" method="POST">
			<div class="form-group">
				<input name="username" type="text" class="form-control" placeholder="Username" required="">
			</div>
			<div class="form-group">
				<input name="password" type="password" class="form-control" placeholder="Password" required="">
			</div>
			<button type="submit" class="btn btn-primary block full-width m-b">Login</button>
			<p class="text-muted text-center"><small>Do not have an account?</small></p>
			<a class="btn btn-sm btn-white btn-block" href="/registrationIndex">Create an account</a>
		</form>
		
		<p class="m-t"> <small>FITPA we app framework base on Bootstrap 3 &copy; 2014</small> </p>
	</div>
</div>
</body>
