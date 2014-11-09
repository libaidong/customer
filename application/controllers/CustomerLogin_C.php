<?php
class CustomerLogin_C extends CI_Controller {
	//function __construct() {
	//	parent::__construct();
	//	$this->load->model('User');
	//	$this->JoystModel('User');
	//}

	function index() {
		$page['title'] = 'Login';
		$this->site->Header();
		$this->site->View('customer/login', array(
			'loginError' => '',
			'stitle' => 'Login'
		));
		$this->site->Footer();
	}
	
	function logout() {
		$_SESSION["user_info"] = '';
		$this->site->Header();
		$this->site->View('customer/login', array(
			'loginError' => '',
			'stitle' => 'Login'
		));
		$this->site->Footer();
	}
	
	function indexMain() {
		$user = $_SESSION["user_info"];
		$this->site->Header();
		$this->site->View('customer/main', array(
			'user' => $user,
			'stitle' => 'Dashboard'
		));
		$this->site->Footer();
	}
	
	function passwordUpdate() {
		$messages = '';
	    $this->load->model('CustomerLogin_M');
		if ( $_POST && isset($_POST['username']) && isset($_POST['oldpassword']) && isset($_POST['newpassword']) && isset($_POST['passwordConfirmation'])) {
			if ($user = $this->CustomerLogin_M->GetByLogin($_POST['username'], $_POST['oldpassword'])) {
			    $tempArray = "";
				$tempArray[] = array(
					'password' => $_POST['newpassword']
				);
				$re = $this->CustomerLogin_M->updatePasswordInfo($tempArray, $user[0]['userid']);
				if ($re == 1) {
					$messages = 'Password Update Success!';
				} else {
					$messages = 'Password Update Failure!';
				}
				$this->site->View('fitpalogin/passwordUpdateResult', array(
					'messages' => $messages
				));
			} else {
				$messages = "Invalid password for user '{$_POST['username']}'";
				$this->site->View('fitpalogin/passwordUpdateResult', array(
					'messages' => $messages
				));
			}
		}
	}
	
	/**
	* Preform a user login
	* This can be either a standard email/password combination or via the Facebook OAuth API
	* @param string $skin Alternate views/login-<?> file to use to display the page
	*/
	function login() {
		$messages = '';
	    $this->load->model('CustomerLogin_M');
		if ( $_POST && isset($_POST['username']) && isset($_POST['password']) ) { // STANDARD ONSITE LOGIN
			if (!$_POST['username'])
				$messages = 'You must specify a valid user name';
			if (!$_POST['password'])
				$messages = 'You must specify a valid password';
			if (!$this->site->HasErrs()) {
				if ($user = $this->CustomerLogin_M->GetByLogin($_POST['username'], $_POST['password'])) { // Valid login
					$this->site->Header();
					$this->site->View('customer/main', array(
						'user' => $user,
						'stitle' => 'Dashboard'
					));
					$this->site->Footer();
				} else {
				    $messages = "Invalid password for user '{$_POST['username']}'";
					$this->site->Header();
					$this->site->View('customer/login', array(
						'loginError' => $messages,
						'stitle' => 'Login'
					));
					$this->site->Footer();
				}
			}
		}
	}
}
?>
