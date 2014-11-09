## REST handling with Codeigniter

My experience has been that when dealing with front end frameworks, Codeigniter's handling of the POST data they send is awful. 
This is an extraordinarily simple Codeigniter library which cleans up a little of the mess. 

#### Setup:

 * Add the library file to /application/libraries
 * Load as required:

 	$this->load->library('rest');
	
#### Usage example

	//put request - string
	if($put = $this->rest->put()){
		echo 'put ' . $put; // should return string
	}

	//put request - JSON
	if($put = $this->rest->putJSON()) {
		echo "Put JSON \n";
		print_r($put); //should return array
	}

	//POST request - JSON
	if($post = $this->rest->postJSON()) {
		echo "Post JSON \n";
		print_r($post); //should return array
	}

	//POST request - string 
	elseif($post = $this->rest->post()){
		echo 'post ' . $post; //should return string
	}

	//Delete request
	elseif($this->rest->delete()){
		echo 'delete';
	}
	
	//Get request - just returns boolean if true
	elseif($this->rest->get()) {
		echo 'get';
	}
	
