<? 
/**
 * A simple model for dealing with the taging system
 */
class Tag extends Joyst_Model {
	function DefineSchema() {
		$this->on('getall', function(&$where) {
			if($this->User->IsAdmin()) {
				//no need for conditions
			}
			elseif($this->User->isRole('manager')) {
				$this->db->where('locationid', $this->User->GetActive('locationid'));
			}
			else { //is ordinary user, only allow them to see tags from their location
				$this->db->where('tags.tagid IN (SELECT tagid FROM tags2users WHERE tags2users.userid = ' . $this->db->Escape($this->User->GetActive('userid')) . ')');
			}
		});
		$this->on('row', function(&$row) {
			$row['deviceCount'] = $this->CountDevices($row['tagid']);
		});
		$this->on('rows', function(&$rows) {
			return usort($rows, function($a, $b) { // Nat sort output
				return strnatcmp($a['name'], $b['name']);
			});
		});

		return array(
			'_model' => 'Tag',
			'_table' => 'tags',
			'_id' => 'tagid',
			'tagid' => array(
				'type' => 'pk',
				'readonly' => true,
			),
			'locationid' => array(
				'type' => 'fk',
			),
			'name' => array(
				'type' => 'varchar',
				'length' => 100,
			),
			'created' => array(
				'type' => 'epoc',
				'readonly' => true,
			),
			'creatorid' => array(
				'type' => 'int',
				'readonly' => true,
			),
			'status' => array(
				'type' => 'enum',
				'options' => array('active', 'deleted'),
				'default' => 'active',
			),
		);
	}
	
	/**
	* Convenience function to return all tags beginning with a given prefix
	* @param string $prefix The prefix to search for
	* @param bool $strip Whether to remove the prefix from the output
	* @return array A filtered list of all matching tag rows
	*/
	function GetPrefixes($prefix, $strip = FALSE) {
		$out = array();
		foreach ($this->Tag->GetAll(array('status' => 'active')) as $tag) {
			if (substr($tag['name'], 0, strlen($prefix)) == $prefix) {
				if ($strip)
					$tag['name'] = substr($tag['name'], strlen($prefix));
				$this->Trigger('row', $tag);
				$out[] = $tag;
			}
		}
		return $out;
	}

	var $cachedTagsByName = array();
	/**
	* Attempts to find a tag by its name
	* This function uses caching
	* This function is the reverse of GetName()
	* @see GetName()
	* @param string $name The name to find
	* @param bool $create If the tag isn't located - create it and return that
	* @return null|int Either the ID of the tag (created or found) or null for not found
	*/
	function GetByName($name, $create = FALSE) {
		if (!isset($cachedTagsByName[$name])) {
			$this->db->from('tags');
			$this->db->where('LOWER(name)', strtolower($name));
			$this->db->where('locationid', $this->User->GetActive('locationid'));
			$this->db->limit(1);
			$tag = $this->db->get()->row_array();
			if ($tag) {
				$cachedTagsByName[$name] = $tag['tagid'];
			} elseif ($create) { // Not found but $create is specified - make it
				$cachedTagsByName[$name] = $this->Create(array(
					'locationid' => $this->User->GetActive('locationid'),
					'name' => $name,
				));
			} else {
				$cachedTagsByName[$name] = null;
			}
		}
		return $cachedTagsByName[$name];
	}

	/**
	 * Retrieve any tags that match the given search term
	 */
	function GetTagsBySearch($term = null) {
		if ($term) {
			$this->db->select('*');
			$this->db->from('tags');
			$this->db->like('name', $term);
			return $this->db->get()->result_array();
		}
	}

	var $cachedTagNames = array();
	/**
	* Shorthand function to return the name of a tag
	* This function uses caching
	* This function is the reverse of GetByName()
	* @param int $tagid The ID of the tag to retrieve
	* @param string|null $strip Optional prefix to strip from the string if it exists
	* @return string The name of the tag
	*/
	function GetName($tagid, $strip = false) {
		if (!isset($cachedTagNames[$tagid])) {
			$tag = $this->Get($tagid);
			if ($strip && substr($tag['name'], 0, strlen($strip)) == $strip)
				$tag['name'] = substr($tag['name'], strlen($strip));
			$cachedTagNames[$tagid] = $tag['name'];
		}
		return $cachedTagNames[$tagid];
	}

	function CountDevices($tagid) {
		$this->db->select('COUNT(*) AS count');
		$this->db->from('tags2devices');
		$this->db->where('tagid', $tagid);
		$row = $this->db->get()->row_array();
		return $row['count'];
	}
}
