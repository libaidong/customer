<?

class Tag2User extends CI_Model{
	
	function __construct(){
		parent::__construct();	
		$this->load->model('Tag');
	}

	/**
	 * Retrieve tags associated with given userid.
	 * @param int $userid The user ID
	 * @return array the resulting array of tags
	 */
	public function getByUser($userid) {
		$this->db->where('userid', $userid);
		$this->db->join('tags', 'tags2users.tagid = tags.tagid');
		return $this->db->get('tags2users')->result_array();
	}
	
	/**
	 * Gets a set of users by tagid
	 */
	public function getByTag($tagid) {
		$this->db->wherE('tagid', $tagid);
		$this->db->join('users', 'tags2users.userid = users.userid');
		return $this->db->get('tags2users')->result_array();
	}
	
	/**
	 * Clears the associated tags from a user
	 */
	public function removeTagsFromUser($userid) {
		$this->db->where('userid', $userid);
		$this->db->delete('tags2users');
	}

	/**
	 * A fire-and-forget insert function which allows you to associate a tag with a user without checking if it's preexising. 
	 * @param int $userid The user ID
	 * @param string $tag The tag string which may or may not be present in the tags database
	 * @param int $locationid the associated locationid for the tag, defaults to the current one
	 * @return int The TagID
	 */
	public function upsert($userid, $tagString, $locationid = null) {

		if(!$locationid) {
			$locationid = $_SESSION['location']['locationid'];
		}

		$tag = $this->Tag->GetByName($tagString);

		if(!$tag){
			$tag = $this->Tag->create(array('name' => $tagString, 'locationid' => $locationid));
		}

		//after creating the tag, now add the tag2user entry
		if($tag && $locationid && $userid) {
			$this->db->insert('tags2users', array('userid' => $userid, 'tagid' => $tag));
			return $tag;
		}
		else {
			throw new Exception("Error: data missing - $userid - $locationid - {$tag}");
		}


	}

}
