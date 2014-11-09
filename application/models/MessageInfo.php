<?php
class MessageInfo extends CI_Model {
	/**
	* Attempt to retrive a user login by a plaintext email and password combo
	* @param string $username The username to test
	* @param string $password The attempted password
	* @return array The user record or null
	*/
	function selectMessage($username) {
		$sql = 'SELECT sendname, content, senddate FROM message WHERE username = ? order by senddate DESC';
		$query = $this->db->query($sql, array($username));
		if (count($query->result_array()) == 0) {
			return false;
		} else {
			return $query->result_array();
		}
	}
	
	function insertInfo($tempArray) {
		$result = $this->db->insert('message', $tempArray[0]);
		return $result;
	}
}
?>
