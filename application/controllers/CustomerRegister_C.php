<?php
class CustomerRegister_C extends CI_Controller {
	//function __construct() {
	//	parent::__construct();
	//	$this->load->model('User');
	//	$this->JoystModel('User');
	//}

	function index() {
		$this->site->Header();
		$this->site->View('customer/register', array(
			'userInfo' => '',
			'messages' => '',
			'stitle' => 'Register'
		));
		$this->site->Footer();
	}

	/**
	* Preform a user login
	* This can be either a standard email/password combination or via the Facebook OAuth API
	* @param string $skin Alternate views/login-<?> file to use to display the page
	*/
	function selectInfo() {
		$userInfo = $_SESSION["user_info"];
	    $this->load->model('CustomerRegister_M');
		$reuUerInfo = $this->CustomerRegister_M->selectInfo($userInfo[0]['customerid']);
		$this->site->Header();
		$this->site->View('customer/register', array(
			'userInfo' => $reuUerInfo,
			'messages' => '',
			'stitle' => 'Register'
		));
		$this->site->Footer();
	}
	
	function updateInfo() {
		$messages = '';
	    $this->load->model('CustomerRegister_M');
		if ($_POST['username'] != $_SESSION["user_info"][0]['username'] && $userInfo = $this->CustomerRegister_M->selectNameInfo($_POST['username'])) {
			$this->site->Header();
			$this->site->View('customer/register', array(
				'userInfo' => $_SESSION["user_info"],
				'messages' => 'The registered name is already in use, please re named!',
				'resultFlg' => 'renamed',
				'stitle' => 'Register'
			));
			$this->site->Footer();
		} else {
			$tempArray = "";
			if ($_POST['password1'] == null || $_POST['password1'] == '') {
				$tempArray[] = array(
					'username' => $_POST['username'],
					'firstname' => $_POST['firstname'],
					'lastname' => $_POST['lastname'],
					'title' => $_POST['title'],
					'dob' => $_POST['dob'],
					'homephonenumber' => $_POST['homephonenumber'],
					'middlename' => $_POST['middlename'],
					'email' => $_POST['email'],
					'address' => $_POST['address'],
					'gender' => 'man',
					'usertype' => 'customer'
				);
			} else {
				$tempArray[] = array(
					'username' => $_POST['username'],
					'password' => $_POST['password1'],
					'firstname' => $_POST['firstname'],
					'lastname' => $_POST['lastname'],
					'title' => $_POST['title'],
					'dob' => $_POST['dob'],
					'homephonenumber' => $_POST['homephonenumber'],
					'middlename' => $_POST['middlename'],
					'email' => $_POST['email'],
					'address' => $_POST['address'],
					'gender' => $_POST['optionsRadios'],
					'usertype' => 'customer'
				);
			}
			$re = $this->CustomerRegister_M->updateInfo($tempArray, $_SESSION["user_info"][0]['customerid']);
			$userInfo = '';
			if ($re == 1) {
				$messages = 'RegistrationInfo Update Success!';
				$userInfo = $this->CustomerRegister_M->selectInfo($_SESSION["user_info"][0]['customerid']);
				$_SESSION["user_info"] = $userInfo;
			} else {
				$messages = 'RegistrationInfo Update Failure!';
			}
			$this->site->Header();
			$this->site->View('customer/register', array(
				'userInfo' => $userInfo,
				'messages' => $messages,
				'stitle' => 'Register'
			));
			$this->site->Footer();
		}
	}
	
	/**
	* Preform a user login
	* This can be either a standard email/password combination or via the Facebook OAuth API
	* @param string $skin Alternate views/login-<?> file to use to display the page
	*/
	function insertInfo() {
		$messages = '';
		$resultFlg = '';
	    $this->load->model('CustomerRegister_M');
		
		if ($userInfo = $this->CustomerRegister_M->selectNameInfo($_POST['username'])) {
			$this->site->Header();
			$this->site->View('customer/register', array(
				'messages' => 'The registered name is already in use, please re named!',
				'resultFlg' => 'renamed',
				'stitle' => 'Register'
			));
			$this->site->Footer();
		} else {
			$tempArray = "";
			$tempArray[] = array(
				'username' => $_POST['username'],
				'password' => $_POST['password1'],
				'firstname' => $_POST['firstname'],
				'lastname' => $_POST['lastname'],
				'title' => $_POST['title'],
				'dob' => $_POST['dob'],
				'homephonenumber' => $_POST['homephonenumber'],
				'middlename' => $_POST['middlename'],
				'email' => $_POST['email'],
				'address' => $_POST['address'],
				'gender' => $_POST['optionsRadios'],
				'usertype' => 'customer'
			);
			$re = $this->CustomerRegister_M->insertInfo($tempArray);
			if ($re == 1) {
				$messages = 'RegistrationInfo Create Success!';
				$resultFlg = 'Success';
			} else {
				$messages = 'RegistrationInfo Create Failure!';
				$resultFlg = 'Failure';
			}
			$this->site->Header();
			$this->site->View('customer/register', array(
				'userInfo' => '',
				'messages' => $messages,
				'resultFlg' => $resultFlg,
				'stitle' => 'Register'
			));
			$this->site->Footer();
		}
	}
}
?>
