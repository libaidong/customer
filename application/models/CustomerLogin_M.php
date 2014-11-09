<?php
class CustomerLogin_M extends CI_Model {
	/**
	* Attempt to retrive a user login by a plaintext email and password combo
	* @param string $username The username to test
	* @param string $password The attempted password
	* @return array The user record or null
	*/
	function GetByLogin($username,$password) {
	    $sql = 'SELECT customerid, username, email FROM customer WHERE username = ? AND password = ?';
		$query = $this->db->query($sql, array($username, $password));
		if (count($query->result_array()) == 0) {
			return false;
		} else {
			return $query->result_array();
		}
	}
	
	function selectNameInfo($username) {
		$sql = 'SELECT customerid, username, firstname, lastname, gender, title, dob, homephonenumber, middlename, address, usertype, email FROM customer WHERE username = ?';
		$query = $this->db->query($sql, array($username));
		if (count($query->result_array()) == 0) {
			return false;
		} else {
			return $query->result_array();
		}
	}
	
	function updatePasswordInfo($tempArray, $userid) {
		$where = "customerid = ".$userid;
		$result = $this->db->update('customer', $tempArray[0], $where);
		return $result;
	}
}
?>
