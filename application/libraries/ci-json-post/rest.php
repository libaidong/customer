<? if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * JSON and REST handing
 */
class REST {

	public function __construct() {
	}

	/**
	 * decode JSON POST and return it as a pure multidimensional PHP array. 
	 * @return array
	 */
	public function postJSON() {
		if($this->method() == 'POST')
			return $this->recurseTypecast(json_decode(file_get_contents('php://input')));
	}

	/**
	 * Decodes a JSON PUT request and returns it as as multidimensional PHP array. 
	 * @return array
	 */
	public function putJSON() {
		if($this->method() == 'PUT')
			return $this->recurseTypecast(json_decode(file_get_contents('php://input')));
	}

	/**
	 * A convenience function that simply returns boolean true if it finds that a GET request was set
	 */
	public function get() {
		return $this->method() =='GET';
	}

	/**
	 * Return a raw string sent in POST
	 * @return string
	 */
	public function post() {
		if($this->method() == 'POST')
			return file_get_contents('php://input');
	}

	/**
	 * Returns true if recieved a DELETE request
	 * @return true if the case 
	 */
	public function delete() {
		if($this->method() == 'DELETE')
			return true;
	}

	/**
	 * Handles a raw PUT request
	 * @return string
	 */
	public function put() {
		if($this->method() == 'put') {
			return file_get_contents('php://input');
		}
	}

	/**
	 * Replies with a string representing the HTTP method employed
	 * @return String such as "GET", "POST", "Delete" etc. 
	 */
	public function method() {
		if(isset($_SERVER['REQUEST_METHOD'])) {
			return $_SERVER['REQUEST_METHOD'];
		}
		else return 'GET';
	}

	/**
	 * Retursively iterates through and typecasts a given object to an array;
	 */
	private function recurseTypecast($object) {

		if(!is_array($object) && !is_object($object)) {
			return $object;
		}

		//If is an object, attempt to typecast
		if(is_object($object)) {
			$object = (array)$object; 
		}

		//if array, recurse further
		if(is_array($object)) {
			foreach($object as &$o) {
				if(is_object($o)) {
					$o = $this->recurseTypecast($o);
				}
			}
		}
		return $object; 
	}


}
