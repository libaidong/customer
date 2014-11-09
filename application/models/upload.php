<?
class Upload extends CI_Model {


	function Get($uploadid) {
		$this->db->from('uploads');
		$this->db->where('uploadid', $uploadid);
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}

	function GetAll($parenttable, $parentid, $orderby = 'file') {
		$this->db->select('uploads.*, users.fname, users.lname');
		$this->db->from('uploads');
		$this->db->where('parenttable', $parenttable);
		$this->db->where('parentid', $parentid);
		$this->db->order_by($orderby);
		$this->db->join('users', 'uploads.creatorid = users.userid');
		$result = $this->db->get()->result_array();

		for($i = 0; $i < count($result); $i++) {

			//Figure out if the file is an image or a file
			if(preg_match('/(jpg$)|(png$)/', $result[$i]['file']))
				$result[$i]['type'] = 'image';
			else 
				$result[$i]['type'] = 'file';

			//Also give it a sensible file-name
			$match = array();
			preg_match('#[0-9a-zA-Z._-]*$#', $result[$i]['file'], $match);
			$result[$i]['filename'] = $match[0];
		}

		return $result;
	}

	/**
	* Returns the <img src="icon.jpg"> wrapping for a file if it matches the database of known file extensions
	* @param string $file The file name to query
	* @param bool $allow_thumb Search (and create) thumbnails for supported types
	* @return string HTML of an <img> element
	*/
	function GetIcon($file, $allow_thumb = TRUE) {
		$ext = pathinfo($file, PATHINFO_EXTENSION);

		if ($allow_thumb && in_array($ext, explode(',', THUMBNAIL_ABLE))) { // Thumbnails allowed and its a supported type
			if (!file_exists($thumbpath = $this->GetThumbPath($file))) // Make one
				$this->CreateThumbnail(DATA_DIR . "/$file", DATA_DIR . "/$thumbpath");
			return '<img src="' . DATA_DIR_WWW . "/$thumbpath" . "\"/>";
		}

		if (file_exists($f = "img/filetypes/$ext.png"))
			return "<img src=\"/$f\"/>";
		return "<img src=\"/img/filetypes/default.png\"/>";
	}

	function HasThumbnail($file) {
		return !preg_match('!img/filetypes/!', $this->GetIcon($file)); // Return true if the type is not a file type icon
	}

	function GetThumbPath($file) {
		return dirname($file) . '/' . pathinfo($file, PATHINFO_FILENAME) . '_tn.' . pathinfo($file, PATHINFO_EXTENSION);
	}

	function CreateThumbnail($file, $dst) {
		$this->load->library('image_lib');
		$this->image_lib->initialize(array(
			'image_library' => 'gd2',
			'source_image' => $file,
			'new_image' => $dst,
			'maintain_ratio' => TRUE,
			'width' => THUMBNAIL_WIDTH,
			'height' => THUMBNAIL_HEIGHT,
		));
		$this->image_lib->resize();
	}

	/**
	* Place a file or blob into the directory calculated by the table and id
	* @param string $tmpname Either the temporary file name to move or the basename if $data is set
	* @param string $name The uploaded file name
	* @param array $data An array containing parenttable,parentid to determine where the file should be stored
	* @param bool $isblob If TRUE $tmpname is assumed to be a binary blob of data to store instead of the temporary file name
	*/
	function Create($tmpname, $name, $data, $isblob = FALSE) {
		$fields = array(
			'creatorid' => $this->User->GetActive('userid'),
			'created' => time(),
		);
		foreach (qw('parenttable parentid description') as $field)
			if (isset($data[$field]) && $data[$field])
				$fields[$field] = $data[$field];

		if (!isset($fields['parenttable']) || !isset($fields['parentid']))
			die('Parenttable and Parentid must both be set to accept an upload');

		$dir = DATA_DIR . "/{$data['parenttable']}/{$data['parentid']}";
		$basename = preg_replace('/[^a-z0-9_\.-]+/', '-', strtolower(basename($name)));
		$fields['file'] = "$dir/$basename";
		if (file_exists($fields['file'])) { // File already exists - append '-2' to the end
			$base = pathinfo($fields['file'], PATHINFO_FILENAME);
			$ext = pathinfo($fields['file'], PATHINFO_EXTENSION);
			$base .= '-2';
			while (file_exists("$dir/$base.$ext"))
				$base++;
			$basename = "$base.$ext";
			$fields['file'] = "$dir/$basename";
		}

		//Do this silently so as to not cause problems with headers 
		@$this->rmkdir($dir, 0777);

		if ($isblob) { // Accept data from blob
			file_put_contents($fields['file'], $tmpname);
		} else { // Accept data from tmp file

		//move the user, check to see if it's successful. if not, throw an error
		@$attempt = move_uploaded_file($tmpname, $fields['file']);
			
		if(!$attempt)
			throw new Exception('Unable to complete file upload'); 
		}

		$fields['file'] = substr($fields['file'], strlen(DATA_DIR) + 1); // Clip off DATA_DIR prefix

		$this->db->insert('uploads', $fields);

		$this->Log->Add('upload', "Uploaded file '$basename' for {$data['parenttable']} #{$data['parentid']}: " . $this->Log->NiceArray($fields));
	}

	function Delete($uploadid) {
		$file = $this->Get($uploadid);
		unlink($file['file']);
		$this->db->where('uploadid', $uploadid);
		$this->db->delete('uploads');
		$this->Log->Add('file', "Deleted upload #$uploadid ({$file['file']})");
	}

	/**
	* Recursive mkdir()
	* This is used instead of the PHP mkdir() builtin as PHP's one doesnt carry the $mode parameter to any new nested dirs
	* @param string $path The path to create
	* @param int $mode The octal persmission set to create
	*/
	function rmkdir($path, $mode = 0777) {
		$dirs = explode(DIRECTORY_SEPARATOR , $path);
		$count = count($dirs);
		$path = '';
		for ($i = 1; $i < $count; ++$i) {
			$path .= DIRECTORY_SEPARATOR . $dirs[$i];
			if (!is_dir($path) && !mkdir($path))
				return false;
		}
		return true;
	}
}
?>
