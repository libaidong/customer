<?php

class Security extends CI_model {
	/**
	* Checks that the user is logged in.
	* If not the user is directed to the login page
	* @param string $redirect The URL to redirect to if the user is not logged in
	* @return void This function is fatal if the user is not logged in
	*/
	function EnsureLogin($redirect = '/login') {

		if (!isset($_SESSION['user'])) {// Not logged in

			//Check if there's a server redirect url in place, if so, pass it to the user/login method
			if($redirect =='/login' && isset($_SERVER['REDIRECT_URL']))
				$this->site->redirect('/users/login/' . urlencode(base64_encode($_SERVER['REDIRECT_URL'])));
			else 
				$this->site->redirect('/login');
		}
	}

	/**
	* Ensure that the user is an admin
	* @return void This function is fatal if the user is not logged in or is not an admin
	*/
	function EnsureAdmin() {
		if (!$this->User->IsAdmin())
			$this->site->Error('Sorry but you do not have the requisite user level to preform that operation');
	}

	/**
	* Similar to EnsureAdmin() but checks that the user is actually the root user
	* @return void This function is fatal if the user is not logged in or is not root
	*/
	function EnsureRoot() {
		if (!$this->User->IsRoot())
			$this->site->Error('Sorry but you must be the root user to perform this operation');
	}

	/**
	* Takes the input from a dcu and checks that it is legitimately the right key
	* @return bool True if valid, False if not;
	*/
	function ValidateDCU($dcuid, $secret) {
		if (!$dcuid)
			return;
		$secret = preg_replace('/[^a-zA-Z0-9]/', '', $secret); //Strip non alpha-numeric characters to prevent injection attacks

		$this->db->select('secret');
		$this->db->from('locations');
		$this->db->join('dcus', 'dcus.locationid = locations.locationid');
		$this->db->where('dcuid', $dcuid);
		$this->db->limit(1);
		$location = $this->db->get()->row_array();
		return $location && md5($secret) == $location['secret'];
	}
}
?>
