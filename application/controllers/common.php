<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Common extends CI_Controller {

	function calendar() {
		$this->site->Header('');
		$this->site->View('customer/calendar', array(
			'stitle' => 'calendar'
		));
		$this->site->Footer();
	}
	
	function mailcompose() {
		$this->site->Header('');
		$this->site->View('customer/mail_compose', array(
			'stitle' => 'taskEdit'
		));
		$this->site->Footer();
	}
	
	function maildetail() {
		$this->site->Header('');
		$this->site->View('customer/mail_detail', array(
			'stitle' => 'taskEdit'
		));
		$this->site->Footer();
	}
	
	function mailbox() {
		$this->site->Header('');
		$this->site->View('customer/mailbox', array(
			'stitle' => 'taskEdit'
		));
		$this->site->Footer();
	}
	
}
?>
