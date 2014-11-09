<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Matts little 'site' library
* Contains useful site wide functionality
*
* This module ships with the views/site directory
*/
class Site {
	/**
	* Whether the headers have been spewed
	* @var bool
	*/
	var $_spewed_headers;

	/**
	* Which theme to use
	* Determined on the first call to ->Header
	* @type string
	*/
	var $Theme;

	/**
	* The breadcrumb path to the current page
	* @type array
	*/
	var $_breadcrumb;

	/**
	* Output JSON options
	* @type int
	*/
	var $_jsonoptions;

	/**
	* CodeIgniter reference
	* @type CodeIgniter
	*/
	var $CI;

	/**
	* The parameters given to the header/footer calls
	* @type array
	*/
	var $headerparams;

	function Site() {
		$GLOBALS['messages'] = array();
		$this->Theme = 'normal';
		$this->_breadcrumb = array();
		$this->CI =& get_instance();
	}

	/**
	* Force the local theme id
	* @param string $theme The theme id to use when rendering
	*/
	function SetTheme($theme) {
		$this->Theme = $theme;
	}

	/**
	* Reports an error to the sys admin along with full stack-trace
	* @param string $message The nature of the error
	*/
	function Panic($message) {
		$this->load->model('Email');
		$traces = debug_backtrace();

		$backtrace = '';
		foreach ($traces as $k => $v){
			if($v['function'] == "include" || $v['function'] == "include_once" || $v['function'] == "require_once" || $v['function'] == "require") {
				$backtrace .= "#".$k." ".$v['function']."(".$v['args'][0].") called at [".$v['file'].":".$v['line']."]\n";
			}else{
				$backtrace .= "#".$k." ".$v['function']."() called at [".$v['file'].":".$v['line']."]\n";
			}
		}

		$this->Email->Send('core/panic', 0, array(
			'panic.text' => $message,
			'panic.trace' => $backtrace,
		));
	}



	/**
	* Output a template header
	*
	* Optional params inside the $params array:
	*	$_REQUEST['what'] An hash (where each value is ignored) of values to put in the What box
	*	$_REQUEST['where'] Same as above, but for the Where box
	*
	* @param string $title The title of the page
	* @param array $params Other parameters to output to the header page
	*/
	function Header($title = 'Welcome', $params = array()) {
		if (isset($_POST['ajax'])) {
			$this->CI->load->view('site/messages');
			return;
		}
		header("Content-Type: text/html; charset=utf-8");

		$params['title'] = $title;
		if (isset($params['breadcrumb'])) // Deal with incomming breadcrumbs
			$this->Breadcrumb($params['breadcrumb']);

		if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] == '/')
			$params['sitearea'] = 'dashboard';
		$this->headerparams = $params;

		$this->CI->load->view('site/' . $this->Theme . '/header', $params);
		$this->_spewed_headers = TRUE;
	}

	/**
	* Set the breadcrumb path of the current page
	* @param array An associative array where each key is the path to the item, the value is the written english to display
	*/
	function Breadcrumb($path) {
		$this->_breadcrumb = $path;
	}

	/**
	* Display the footer of the page
	*/
	function Footer() {
		if (isset($_POST['ajax']))
			return;
		$this->CI->load->view('site/' . $this->Theme . '/footer', $this->headerparams);
	}

	/**
	* Shorthand function to include another view
	*/
	function View($view, $params = null) {
		$this->CI->load->view($view, $params);
	}

	/**
	* Output a simple (non-error) string to the user
	* Unlike error() this function is NOT fatal
	* @param string $text The text to display
	* @param bool $close If TRUE the footer is appended to the message
	*/
	function Text($text, $close = TRUE) {
		if ($close && !$this->_spewed_headers) {
			$this->SetTheme('minimal');
			$this->Header('Notice');
		}
		$this->CI->load->view('text', array(
			'text' => $text,
		));
		if ($this->_spewed_headers && $close)
			$this->Footer();
	}

	/**
	* Convenience wrapper to return if the client is asking for some specific type of output
	* There is a special _GET variable called 'json' which if set forces JSON mode. If set to 'nice' this can also pretty print on JSON() calls
	* @param $type A type which corresponds to a known data type e.g. 'html', 'json'
	* @return bool True if the client is asking for that given data type
	*/
	function Want($type) {
		switch ($type) {
			case 'html':
				return !$this->Want('json');
			case 'json':
				if (isset($_GET['json'])) {
					if ($_GET['json'] == 'nice' && version_compare(PHP_VERSION, '5.4.0') >= 0)
						$this->_jsonoptions = JSON_PRETTY_PRINT;
					return TRUE;
				}
				if (
					isset($_SERVER['HTTP_ACCEPT']) &&
					preg_match('!application/json!', $_SERVER['HTTP_ACCEPT'])
				)
					return TRUE;
				return FALSE;
			case 'put-json': // Being passed IN a JSON blob (also converts incomming JSON into $_POST variables
				if (!$this->Want('json')) // Not wanting JSON
					return FALSE;
				$in = file_get_contents('php://input');
				if (!$in) // Nothing in raw POST
					return FALSE;
				$json = json_decode($in, true);
				if ($json === null) // Not JSON
					return FALSE;
				$_POST = $json;
				return TRUE;
			default:
				trigger_error("Unknown want type: '$type'");
		}
	}

	/**
	* Convenience wrapper to output JSON output
	* If mappings is an array it can be used to extract infomormation from the json incomming array
	* e.g.
	* $this->JSON(array('name' => 'John', 'userid' => 10), array('name' => 'name', 'id' => 'userid')) == array(array('id' => 10, 'name' => 'John'))
	* @param array $json The associative JSON output
	*/
	function JSON($json, $mappings = null) {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
		header("Cache-Control: no-cache, must-revalidate" ); 
		header("Pragma: no-cache" );
		header('Content-type: application/json');
		if ($mappings) { // Recompute mappings
			$out = array();
			foreach ($json as $bit)
				foreach ($mappings as $key => $val)
					$out[$key] = $bit[$val];
			$json = $out;
		}
		echo json_encode($json, $this->_jsonoptions);
		exit;
	}

	/**
	* Convenience function for reporting errors
	* @param string $message The error message to display
	* @return void This function is fatal
	*/
	function Error($message) {
		if (!$this->_spewed_headers) {
			$this->SetTheme('minimal');
			$this->Header('Error');
		}
		$this->CI->load->view('error', array(
			'text' => $message,
		));
		$this->Footer();
		$this->Terminate();
	}

	/**
	* Append simple text to the output buffer
	* @param string $html HTML to append
	*/
	function Append($html) {
		#global $OUT;
		#$OUT->append_output($html);
		echo $html;
	}

	/**
	* Directly output remaining output buffer contents and exit
	*/
	function Terminate() {
		global $OUT;
		echo $OUT->get_output();
		exit;
	}

	/**
	* Fatal redirect to another address
	* If $text is set this function will do a soft redirect
	*
	* @param string $url The URL to redirect
	* @param string $text Optional text to display while redirecting
	* @return void This function is always fatal
	*/
	function Redirect($url, $text = null) {
		if (!$text && !headers_sent()) { // Hard redirect
			header("Location: $url");
			exit;
		} else {
			$this->CI->load->view('redirect', array(
				'url' => $url,
				'text' => ($text) ? $text : 'Redirecting...',
			));
		}
	}

	/**
	* Outputs a file directly to the browser
	* This funciton also correctly calculates the correct Content-type so it doesnt appear as a binary blob
	* @param string $path The file to serve to the browser
	* @param string|null $name The filename to use. If specified this forces a download, if omitted the browser handles it normally (e.g. displaying a PDF in the browser if its a PDF)
	* @return void This function is always fatal
	*/
	function OutputFile($path, $name = null) {
		// Grab a file info object to identify the file (Weird PHP Voodoo)
		$finfo = new finfo(FILEINFO_MIME);
		$type  = $finfo->file($path);

		header('Content-type: ' . $type); // Set mimetype

		if ($name) // If we are forcing a download - set a name
			header('Content-Disposition: attachment; filename="' . $name . '"');
		
		readfile($path);
		exit();
	}

	/**
	* Checks that the user is not posting the same thing twice
	* If URL is specified, a fatal call to ->Redirect is made if the post is not unique
	* If URL is not specified this function returns FALSE, otherwise TRUE
	*
	* @param string $url Redirect here if the post is not unique
	* @return bool This function is a fatal call to ->Redirect if the post is not unique and URL is set, otherwise FALSE if not unique
	*/
	function UniquePost($url = null) {
		if (!$_POST) return TRUE; // No post anyway
		$bulk = '';
		foreach ($_POST as $key => $val)
			$bulk .= "$key=$val&";
		$md5 = md5($bulk);
		if (isset($_SESSION['unique_post']) && ($_SESSION['unique_post'] == $md5) ) { // Same MD5 as last time
			if ($url)
				$this->Redirect($url);
			return FALSE;
		}
		$_SESSION['unique_post'] = $md5;
		return TRUE;
	}

	/**
	* Adds a message to the stack
	* @param string $type The type of message (enum of: info, warning, delete, create)
	* @param string|array $text The message content to add. If the text is an array it will be enclosed in <ul> tags with each element as a <li>
	*/
	function Message($type, $text) {
		if (is_array($text)) {
			$out = '<ul>';
			foreach ($text as $item)
				$out .= "<li>$item</li>";
			$text = "$out</ol>";
		}
		$GLOBALS['messages'][] = array($type, $text);
	}

	/**
	* Returns if any messages are pending
	* @return bool Any messages waiting from calls to Message()
	* @see Message
	*/
	function HasErrs() {
		return (count($GLOBALS['messages']) > 0);
	}

	/**
	* Immediately sprews a message to the output buffer.
	* This function is what renders the Message() output and is included here in case a message ever needs to be output at a specific location
	* @param string $type The type of message (enum of: info, warning, delete, create)
	* @param string|array $text The message content to add. If the text is an array it will be enclosed in <ul> tags with each element as a <li>
	* @param bool $return Return the blob rather than echo it
	* @see Message()
	*/
	function MessageHere($type, $text, $return = false) {
		$out = "<div class=\"alert alert-$type\">$text</div>";
		if ($return) {
			return $out;
		} else
			echo $out;
	}

	/**
	* Count the number of errors in the message table
	* @return int The number of messages in the queue
	*/
	function CountMessages() {
		return count($GLOBALS['messages']);
	}

	/**
	* Checks for the existance of $_POST[$name] or fatally errors out
	* @return void This funciton is fatal if $_POST[$name] does not exist
	*/
	function RequireVar($name, $description) {
		if (!isset($_POST[$name]))
			$this->Error("POST '$name' is required - $description");
	}

	/**
	* Extract an array of parameters from a function arg stack
	* This is usually called with $this->site->Params(func_get_args());
	* so /controller/method/filter1/value1/filter2/value2 => array('filter1' => 'value1', 'filter2' => 'value2')
	* @param $stack array The argument stack to process
	*/
	function Params($stack) {
		$this->_params = array();
		$iskey = 0;
		foreach ($stack as $item) {
			$iskey = !$iskey;
			if ($iskey) {
				$key = $item;
			} else
				$this->_params[$key] = $item;
		}
		return $this->_params;
	}
	
	/**
	* Return an edited version of the URL but override the specified parameter
	* e.g. <a href="<?=$this->site->EditParams('page', $page+1)?>">Next page</a>
	* @param string $param The parameter to edit
	* @param string $value The new value to use. Set to false or do not specify to remove
	*/
	function EditParams($param, $value = FALSE) {
		$replacenext = 0;
		$replaced = 0;
		$url = '/';
		foreach ($this->CI->uri->segments as $seg) {
			if ($seg == $param) {
				$replacenext = 1;
				if ($value !== FALSE)
					$url .= $seg . '/';
			} elseif ($replacenext) {
				if ($value !== FALSE)
					$url .= $value . '/';
				$replacenext = 0;
				$replaced = 1;
			} else
				$url .= $seg . '/';
		}
		if (!$replaced && $value !== FALSE) { // Not replaced - append new parameter
			$url .= "$param/$value";
		} else
			$url = rtrim($url, '/');
		return $url;
	}

	// Simple JSON returns {{{
	/**
	* Fatally exit with a JSON error message
	* @param string $message The error to raise
	*/
	function JSONError($message) {
		echo json_encode(array('header' => array('error' => $message)), $this->_jsonoptions);
		exit;
	}

	/**
	* Send a message to the user
	* @param string $message The text to trasmit
	*/
	function JSONInfo($message = null) {
		echo json_encode($message ? array('header' => array('info' => $message)) : array('header' => array()), $this->_jsonoptions);
		exit;
	}

	/**
	* Send a message to the user on success
	* @param string $message The text to trasmit
	*/
	function JSONSuccess($message = null) {
		echo json_encode($message ? array('header' => array('message' => $message)) : array('header' => array()), $this->_jsonoptions);
		exit;
	}
	// }}}

	/**
	 * Flash messages technique using PHP session variable as message buffer
	 * Outputs message as html and unsets flashMessages session variable after completion
	 */
	function FlashMessages() {
		if (isset($_SESSION['flashMessages']) && $_SESSION['flashMessages']) {
			$html = '<div class="alert alert-info alert-dismissible">';
			$html .= '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
			foreach ($_SESSION['flashMessages'] as $msg) {
				$html .= '<p>' . $msg . '</p>';
			}

			$html .= '</div>';
			// Unset flashMessages
			$_SESSION['flashMessages'] = null;
			echo $html;
		}
	}

	/**
	 * Output given data in a HTML pretty element
	 */
	function PrintData($data) {
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
}
?>
