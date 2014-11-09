<body class="gray-bg">
<div class="middle-box text-center loginscreen   animated fadeInDown">
	<div>
		<div>
			<h1 class="logo-name">FITPA</h1>
		</div>
		<h3>Register to FITPA</h3>
		<p>Create account to see it in action.</p>
		<p><?php echo $messages?></p>
		<?php if ($userInfo == '' || count($userInfo) == 0){?>
			<form id="registerForm" name="registerForm" class="m-t" role="form" action="/registrationInsertInfo" method="POST">
				<div class="form-group">
					<input name="username" type="text" class="form-control" placeholder="Name For Login" required="required">
				</div>
				<div class="form-group">
					<input name="password1" type="password" class="form-control" placeholder="Password" required="" id="password">
				</div>
				<div class="alert alert-warning" style="display:none" id="cfrPwdMsg">confirm password is different from password!</div>
				<div class="form-group">
					<input name="password2" type="password" class="form-control" placeholder="Password Confirmation" required="" id="confirmPwd">
				</div>
				<div class="form-group">
					<input name="firstname" type="firstName" class="form-control" placeholder="First Name" required="">
				</div>
				<div class="form-group">
					<input name="middlename" type="middlename" class="form-control" placeholder="Middle Name">
				</div>
				<div class="form-group">
					<input name="lastname" type="lastName" class="form-control" placeholder="Last Name" required="">
				</div>
				 <div class="form-group"><label class="col-sm-2 control-label">Gender:<br/>
					<div class="col-sm-10">
						<div class="radio"><label> <input type="radio" checked="" value="man" id="optionsRadios1" name="optionsRadios">Male</label></div>
						<div class="radio"><label> <input type="radio" value="woman" id="optionsRadios2" name="optionsRadios">Female</label></div>
					</div>
				 </div>
				<div class="form-group">
					<input name="title" type="title" class="form-control" placeholder="Title">
				</div><div class="form-group">
					<input name="dob" type="dob" class="form-control" placeholder="DOB">
				</div>
				<div class="form-group">
					<input name="homephonenumber" type="homephonenumber" class="form-control" placeholder="Home Phone" required="">
				</div>
				
				<div class="form-group">
					<input name="email" type="email" class="form-control" placeholder="Email" required="">
				</div>
				<div class="form-group">
					<input name="address" type="address" class="form-control" placeholder="Address">
				</div>
				<div class="form-group">
					<div class="checkbox i-checks"><label> <input type="checkbox"><i></i> Agree the terms and policy </label></div>
				</div>
				<button type="submit" class="btn btn-primary block full-width m-b" id="register">Register</button>

				<p class="text-muted text-center"><small>Already have an account?</small></p>
				<a class="btn btn-sm btn-white btn-block" href="/login">Login</a>
			</form>
		<?php } else { ?>
			<form id="nextfrm" class="m-t" role="form" action="/registrationUpdateInfo" method="POST" ng-controller="userEdit">
				<div class="form-group">
					<input name="username" type="text" value="<?php echo $userInfo[0]['username']?>" class="form-control" placeholder="Name For Login" ng-model="userInfo[0].username">
				</div>
				<div class="form-group">
					<input name="password1" type="password" class="form-control" placeholder="Password"  ng-model="userInfo[0].password">
				</div>
				<div class="form-group">
					<input name="firstname" value="<?php echo $userInfo[0]['firstname']?>" type="firstName" class="form-control" placeholder="First Name" ng-model="userInfo[0].firstname" >
				</div>
				<div class="form-group">
					<input name="middlename" type="middlename" value="<?php echo $userInfo[0]['middlename']?>" class="form-control" placeholder="Middle Name" ng-model="userInfo[0].middlename">
				</div>
				<div class="form-group">
					<input name="lastname" value="<?php echo $userInfo[0]['lastname']?>" type="lastName" class="form-control" placeholder="Last Name"  ng-model="userInfo[0].lastname">
				</div>
				 <div class="form-group"><label class="col-sm-2 control-label">Gender:<br/>
					<div class="col-sm-10">
						<?php if ($userInfo[0]['gender'] == 'man'){?>
							<div class="radio"><label> <input type="radio" checked="" value="man" id="optionsRadios1" name="optionsRadios">Male</label></div>
							<div class="radio"><label> <input type="radio" value="woman" id="optionsRadios2" name="optionsRadios">Female</label></div>
						<?php } else {?>
							<div class="radio"><label> <input type="radio" value="man" id="optionsRadios1" name="optionsRadios">Male</label></div>
							<div class="radio"><label> <input type="radio" checked="" value="woman" id="optionsRadios2" name="optionsRadios">Female</label></div>
						<?php }?>
					</div>
				 </div>
				<div class="form-group">
					<input name="title" type="title" value="<?php echo $userInfo[0]['title']?>" class="form-control" placeholder="Title"   ng-model="userInfo[0].title">
				</div><div class="form-group">
					<input name="dob" type="dob" value="<?php echo $userInfo[0]['dob']?>" class="form-control" placeholder="DOB" ng-model="userInfo[0].dob">
				</div>
				<div class="form-group">
					<input name="homephonenumber" type="homephonenumber" value="<?php echo $userInfo[0]['homephonenumber']?>" class="form-control" placeholder="Home Phone" ng-model="userInfo[0].homephonenumber">
				</div>
				
				<div class="form-group">
					<input name="email" type="email" value="<?php echo $userInfo[0]['email']?>" class="form-control" placeholder="Email" ng-model="userInfo[0].email">
				</div>
				<div class="form-group">
					<input name="address" type="address" value="<?php echo $userInfo[0]['address']?>" class="form-control" placeholder="Address" ng-model="userInfo[0].address">
				</div>
				<button type="submit" class="btn btn-primary block full-width m-b" id="save">Save</button>
				<a class="btn btn-sm btn-white btn-block" href="/indexMain">Cancel</a>
			</form>
		<?php }?>
		<p class="m-t"> <small>FITPA we app framework base on Bootstrap 3 &copy; 2014</small> </p>
	</div>
</div>

<!-- Mainly scripts -->
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/fitpa.js"></script>
<!-- iCheck -->
<script src="js/plugins/iCheck/icheck.min.js"></script>
<script>
	$(document).ready(function(){
		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
		});
	});
</script>
</body>
