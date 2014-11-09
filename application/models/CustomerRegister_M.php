<?php
class CustomerRegister_M extends CI_Model {
	/**
	* Attempt to retrive a user login by a plaintext email and password combo
	* @param string $username The username to test
	* @param string $password The attempted password
	* @return array The user record or null
	*/
	function selectInfo($userid) {
		$sql = 'SELECT customerid, username, firstname, lastname, gender, title, dob, homephonenumber, middlename, address, usertype, email FROM customer WHERE customerid = ?';
		$query = $this->db->query($sql, array($userid));
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
	
	function insertInfo($tempArray) {
		$result = $this->db->insert('customer', $tempArray[0]);
		return $result;
	}
	
	function updateInfo($tempArray, $userid) {
		$where = "customerid = ".$userid;
		$result = $this->db->update('customer', $tempArray[0], $where);
		return $result;
	}
}
?>
