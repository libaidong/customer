<?

class Tag2Device extends CI_Model{
	
	function __construct(){
		parent::__construct();	
		$this->load->model('Tag');
	}

	/**
	 * Gets a set of tags by deviceid
	 */
	public function getByDevice($deviceid) {
		$this->db->wherE('deviceid', $deviceid);
		$this->db->join('tags', 'tags2devices.tagid = tags.tagid');
		return $this->db->get('tags2devices')->result_array();
	}
	
	/**
	 * Gets a set of devices by tagid
	 */
	public function getByTag($tagid) {
		$this->db->wherE('tagid', $tagid);
		$this->db->join('devices', 'tags2devices.deviceid = devices.deviceid');
		return $this->db->get('tags2devices')->result_array();
	}

	/**
	 * Clears the associated tags from a device
	 */
	public function removeTagsFromDevice($deviceid) {
		$this->db->where('deviceid', $deviceid);
		$this->db->delete('tags2devices');
	}

	/**
	 * A fire-and-forget insert function which allows you to associate a tag with a device without checking if it's preexising. 
	 * @param int $deviceid The device ID
	 * @param string $tag The tag string which may or may not be present in the tags database
	 * @param int $locationid the associated locationid for the tag, defaults to the current one
	 * @return int The TagID
	 */
	public function upsert($deviceid, $tagString, $locationid = null) {

		if(!$locationid) {
			$locationid = $_SESSION['location']['locationid'];
		}

		$tag = $this->Tag->GetByName($tagString);

		if(!$tag){
			$tag = $this->Tag->create(array('name' => $tagString, 'locationid' => $locationid));
		}

		//after creating the tag, now add the tag2device entry
		if($tag && $locationid && $deviceid) {
			$this->db->insert('tags2devices', array('deviceid' => $deviceid, 'tagid' => $tag));
			return $tag;
		}
		else {
			throw new Exception("Error: data missing - $deviceid - $locationid - {$tag}");
		}


	}

}
