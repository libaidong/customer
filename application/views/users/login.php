<div class="pull-center pad-bottom-large">
	<img src="/img/logo-login.png" alt="<?=SITE_TITLE?>"/>
</div>
<? $this->load->view('site/messages') ?>
<form class="well" action="/login" method="POST">
	<div class="row">
		<input name="username" class="form-control input-block-level" required autofocus placeholder="Username">
	</div>
	<div class="row">
		<input name="password" type="password" class="form-control input-block-level" required placeholder="Password">
	</div>
	<div class="row">
		<div class="checkbox pull-left">
			<label><input type="checkbox"/> Remember me</label>
		</div>
		<div class="pull-right">
			<button type="submit" class="btn btn-success btn-sm pull-right">Sign in</button>
		</div>
	</div>
</form>
<a class="font-tiny pull-center btn btn-link" href="/recover/password">Recover password?</a>
