<?php
class Trackprogress_m extends CI_Model {

	function queryList() {
		$sql = 'SELECT trackprogressid, customerid, username, status, comments FROM trackprogress';  
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	function queryInfo($trackprogressid) {
		$sql = 'SELECT trackprogressid, customerid, username, status, comments FROM trackprogress WHERE trackprogressid = ?';  
		$query = $this->db->query($sql, array($trackprogressid));
		return $query->result_array();
	}
	
	function updateInfo($trackprogressid) {

		$where = "trackprogressid = ".$trackprogressid;
		$result = $this->db->update('trackprogress', $tempArray[0], $where);
		return $result;
	}
}	
?>