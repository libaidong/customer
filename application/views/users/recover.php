<div class="pull-center pad-bottom-large">
	<img src="/img/logo-login.png" alt="<?=SITE_TITLE?>"/>
</div>
<? $this->load->view('site/messages') ?>
<form class="well" action="/recover/password" method="POST">
	<div class="row">
		<p>Please enter your email in the box below to be sent a new login password.</p>
	</div>
	<div class="row">
		<input name="email" type="email" class="form-control input-block-level" required autofocus placeholder="someone@somewhere.com">
	</div>
	<div class="row pad-top-small">
		<div class="pull-left">
			<a href="/login" class="btn btn-default btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back to login</a>
		</div>
		<div class="pull-right pad-bottom-tiny">
			<button type="submit" class="btn btn-success btn-sm pull-right">Generate new password</button>
		</div>
	</div>
</form>
