<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {
	/**
	* Display the global front page
	*/
	function index() {
		if (!$this->User->GetActive())
			$this->site->Redirect('/login');
		if ($_SESSION['user']['role'] == 'integrator')
			$this->site->Redirect('/resources');

		$this->load->model('Event');
		$this->load->model('Device');

		$this->site->Header('Dashboard');
		$this->site->View('pages/dashboard');
		$this->site->Footer();
	}

	/**
	* Show a generic page that lives in the views/pages folder
	* @param string $page,... The page name (including optional folders) to show
	*/
	function Show($page = null) {
		$args = func_get_args();
		$code = $args ? implode('/', $args) : ltrim($_SERVER['REQUEST_URI'], '/');

		$titles = array( // Special case titles
		);
		$title = isset($titles[$code]) ? $titles[$code] : ucfirst($code);

		if (file_exists("application/views/pages/$code.php")) {
			$this->site->Header(isset($titles[$code]) ? $titles[$code] : ucfirst($code));
			$this->site->View('pages/' . $code);
			$this->site->Footer();
		} else { // Give up
			$this->Error404();
			return;
		}
	}

	/**
	* Include a specific page without any theming
	* @param string $page,... The page name (including optional folders) to show
	*/
	function Includes($page = null) {
		$args = func_get_args();
		$code = $args ? implode('/', $args) : ltrim($_SERVER['REQUEST_URI'], '/');

		$titles = array( // Special case titles
		);
		$title = isset($titles[$code]) ? $titles[$code] : ucfirst($code);

		if (file_exists("application/views/pages/include/$code.php")) {
			$this->site->View('pages/include/' . $code);
		} else { // Give up
			$this->Error404();
			return;
		}
	}

	function Error404() {
		$heading = "404 Page not Found";
		$message = "The page you requested cannot be found";
		require('application/errors/error_404.php');
	}

	/**
	* Display an under construction page
	*/
	function Construction() {
		$this->site->Header('Under construction');
		$this->site->View('pages/construction');
		$this->site->Footer();
	}
}
?>
