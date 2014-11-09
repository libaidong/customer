<?php
class User extends Joyst_Model {
	function DefineSchema() {
		$this->On('getall', function(&$where) {
			if (!isset($where['status'])) // If not specified imply only active items
				$where['status'] = 'active';
		});
		$this->On('create', function(&$row) {
			$row['created'] = time();
			if (isset($row['email']) && empty($row['username']))
				$row['username'] = $row['email'];
			if (isset($row['password'])) {
				$row['passhash'] = $this->HashSet($row['password']);
				unset($row['password']);
			}
		});
		$this->On('save', function($id, &$row) {
			if(isset($row['tags'])){
				//Since the entire array will be given on save, remove the existing ones and provide the full fresh array
				$this->load->model('Tag2User');

				$this->Tag2User->removeTagsFromUser($id);
				foreach($row['tags'] as $t) {
					$this->Tag2User->upsert($id, $t);
				}
			}
			$row['edited'] = time();

			if (isset($_SESSION['user']) && $_SESSION['user']['userid'] == $id) // Changing self
				$_SESSION['user'] = array_merge($_SESSION['user'], $this->Get($id, TRUE));
		});
		return array(
			'_model' => 'User',
			'_table' => 'users',
			'_id' => 'userid',
			'userid' => array(
				'type' => 'pk',
			),
			'username' => array(
				'type' => 'varchar',
				'length' => 50,
			),
			'fname' => array(
				'type' => 'varchar',
				'length' => 50,
			),
			'lname' => array(
				'type' => 'varchar',
				'length' => 50,
			),
			'passhash' => array(
				'type' => 'char',
				'length' => 40,
				'hide' => true,
			),
			'passhash2' => array(
				'type' => 'char',
				'length' => 40,
				'hide' => true,
			),
			'email' => array(
				'type' => 'email',
			),
			'phone' => array(
				'type' => 'varchar',
				'length' => 50,
			),
			'role' => array(
				'type' => 'enum',
				'options' => array(
					'user' => 'Regular user',
					'admin' => 'Administrator',
					'root' => 'Root user',
					'integrator' => 'Integrator',
					'manager' => 'Manager'
				),
				'default' => 'user',
			),
			'created' => array(
				'type' => 'epoc',
			),
			'edited' => array(
				'type' => 'epoc',
			),
			'lastlogin' => array(
				'type' => 'epoc',
				'readonly' => true,
			),
			'passhash2_created' => array(
				'type' => 'epoc',
				'readonly' => true,
				'hide' => true,
			),
			'status' => array(
				'type' => 'enum',
				'options' => array('active', 'deleted'),
				'default' => 'active',
			),
		);
	}

	/**
	* Retruns the active user or retrives a single value from his data
	* @param string $option Either null for the entire user object or the single item to return the value of
	* @param bool $real Force the use of 'realrole' instead of the cached one.
	*/
	function GetActive($property = null, $real = FALSE) {
		if (!isset($_SESSION['user']))
			return FALSE;

		if ($real && $property == 'role')
			return isset($_SESSION['user']['realrole']) ? $_SESSION['user']['realrole'] : FALSE;

		if ($property == 'locationid') // Shortcut function to return this location
			return $_SESSION['location']['locationid'];

		if ($property)
			return isset($_SESSION['user'][$property]) ? $_SESSION['user'][$property] : FALSE;

		return $_SESSION['user'];
	}

	var $cachednames;
	/**
	* Fast access function to simply return a user name
	* This function uses caching and ONLY returns one item of data
	* @param int|array $userid Either the user row or the ID to retrieve
	* @return string The user name of the requested user
	*/
	function GetName($user = null) {
		if (is_int($user)) {
			$user = $this->Get($user);
		} elseif (!is_array($user))
			$user = $this->GetActive();
		if (!isset($this->cachednames[$user['userid']])) {
			if (!isset($user['fname'])) {
				$this->cachednames[$user['userid']] = 'ERROR';
			} elseif ($user['fname'] && $user['lname']) {
				$this->cachednames[$user['userid']] = "{$user['fname']} {$user['lname']}";
			} elseif ($user['fname']) {
				$this->cachednames[$user['userid']] = $user['fname'];
			} elseif ($user['lname']) {
				$this->cachednames[$user['userid']] = $user['lname'];
			} else
				$this->cachednames[$user['userid']] = $user['email'];
		}
		return $this->cachednames[$user['userid']];
	}

	/**
	* Return true if the current user is an admin
	* @param bool $real Use the real role obtrained during login rather than the current role which may have been set via controllers/users/changerole
	* @return bool TRUE if the user is admin
	*/
	function IsAdmin($real = FALSE) {
		return in_array($this->GetActive('role', $real), array('admin', 'root', 'integrator'));
	}


	/**
	 * A generalised version of the role checker
	 * @param string $role The role to check the user is the specified role
	 * @return boolean True if the user is in the specified role
	 */
	public function isRole($role) {
		return in_array($this->GetActive('role'), array($role));
	}

	/**
	* Return true if the current user is root
	* @param bool $real Use the real role obtrained during login rather than the current role which may have been set via controllers/users/changerole
	* @return bool TRUE if the user is root
	*/
	function IsRoot($real = FALSE) {
		return ($this->GetActive('role', $real) == 'root');
	}


	/**
	* Binds the current session user with a specified user record
	* This function sets up the session, updates login information and all other tasks assocaietd with logging in
	* @param string $userid The UserID of the user to bind to
	* @param bool|string $redirect If true, this function is fatal and redirects to the main home page of that users account. If its a string the string is used as the redirection URI
	* @param string $method Optional descriptive string on how the user logged in (e.g. 'Facebook')
	*/
	function Login($userid, $redirect = TRUE, $method = null) {
		$this->load->model('Location');
		$_SESSION['user'] = $this->Get($userid);
		$_SESSION['locations'] = $this->Location->GetUserLocations($_SESSION['user']['userid']); // Possible locations the user can select from
		$_SESSION['location'] = current($_SESSION['locations']); // The currently active location
		unset($_SESSION['location']['secret']);//sending passwords anywhere is a bad idea
		for($i = 0; $i < count($_SESSION['locations']); $i++) {
			unset($_SESSION['locations'][$i]['secret']);
		}

		$this->Log->Add('login', "Login for user {$_SESSION['user']['username']}", $userid);

		if ($redirect)
			$this->site->Redirect(is_string($redirect) ? $redirect : '/');
	}

	function Logout() {
		$this->Log->Add('logout', "Logout for user {$_SESSION['user']['username']}", $_SESSION['user']['userid']);
		unset($_SESSION['user']);
		session_destroy();
	}

	/**
	* Attempt to retrive a user login by a plaintext email and password combo
	* @param string $username The username to test
	* @param string $password The attempted password
	* @return array The user record or null
	*/
	function GetByLogin($username, $password) {
		$this->db->from('users');
		$this->db->where('username', $username);
		$this->db->limit(1);
		if (! $user = $this->db->get()->row_array()) // Invalid user
			return FALSE;
		if ($this->HashCmp($password, $user['passhash'], $user['userid'])) { // Match primary password
			return $user;
		} elseif ($this->HashCmp($password, $user['passhash2'], $user['userid'])) { // Match secondary password
			$this->log->Add('login', 'Swapped to secondary password', null, $user['userid']);
			$this->db->where('userid', $user['userid']); // Swap secondary -> primary passwords
			$this->db->update('users', array(
				'passhash' => $user['passhash2'],
				'passhash2' => null,
				'passhash2_created' => null,
			));
			return $user;
		}
		return FALSE;
	}

	/**
	* Set a passhash field
	* Passhashes are 8x hex junk + $value as an Md5
	* @param string $value The value that will be hashed
	* @param string $salt Force a salt value (this is used by the HashCmp function)
	* @return string A salt+value string in the form <salt*8><md5>
	* @see HashCmp
	*/
	function HashSet($value, $salt = null) {
		if (!$salt) { // Force the salt value
			$junk = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
			$salt = '';
			foreach (range(1, 8) as $offset)
				$salt .= $junk[rand(0, 15)];
		}
		return $salt . md5("$salt$value");
	}

	/**
	* Compares an incomming value against a hash
	* NOTE: This password accepts both hashed passwords (v2 style) and plaintext passwords (v1 style). Anyone logging in with a plaintext password gets it converted to a v2 hash
	* @param string $value The plain text value to compare the hash against
	* @param string $hash The hashed password usually drawn from a database record
	* @param int $userid Optional userid. If provided and the above hash is a WP password it will be converted
	*/
	function HashCmp($value, $hash, $userid = null) {
		if (!$hash && $value)
			return FALSE;
		if (strlen($hash) == 40) { // Use 8/salt+32/md5
			$salt = substr($hash, 0, 8); // Figure out the salt of the incomming string
			return ($this->HashSet($value, $salt) == $hash); // ... and use it to create a new hash
		} elseif ($value == $hash) { // Try validating against previous systems plaintext passwords (blegh!)
			if ($userid) // Given a userID to save back to - now convert the pass to the new style
				$this->Save($userid, array('password' => $value));
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	* Sets the secondary password to something random
	* @param int $userid The user ID to set the secondary of
	* @return string The new password that was set
	*/
	function SetSecondaryPass($userid) {
		$password2 = pick(array('capricorn', 'aquarius', 'pisces', 'aries', 'taurus', 'gemini', 'virgo', 'libra', 'scorpio')).rand(10,99);
		$this->db->where('userid', $userid);
		$this->db->update('users', array(
			'passhash2' => $this->HashSet($password2),
			'passhash2_created' => time(),
		));
		$this->Log->Add('user', 'Setup secondary password recovery for user', $userid);
		return $password2;
	}
}
?>
