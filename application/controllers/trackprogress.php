<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Trackprogress extends CI_Controller {

	function index() {
		$this->load->model('Trackprogress_m');
		$result = $this->Trackprogress_m->queryList();
		$this->site->Header('');
		$this->site->View('customer/task', array(
			'taskInfo' => $result,
			'messages' => '',
			'stitle' => 'task'
		));
		$this->site->Footer();
	}
	
	function edit() {
	    $trackprogressid = $_GET['trackprogressid'];
		$this->load->model('Trackprogress_m');
		$result = $this->Trackprogress_m->queryInfo($trackprogressid);
		$this->site->Header('');
		$this->site->View('customer/taskdetail', array(
			'taskInfo' => $result,
			'messages' => '',
			'stitle' => 'taskEdit'
		));
		$this->site->Footer();
	}
	
	function updateInfo() {
	    $trackprogressid = $_GET['trackprogressid'];
		$this->load->model('Trackprogress_m');
		$result = $this->Trackprogress_m->updateInfo($trackprogressid);
		$this->site->Header('');
		$result = $this->Trackprogress_m->queryList();
		$this->site->View('customer/task', array(
			'taskInfo' => $result,
			'messages' => '',
			'stitle' => 'task'
		));
		$this->site->Footer();
	}
}
?>
