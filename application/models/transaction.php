<?php
/**
 * A means to ensure order at the transport level for communication
 */
class Transaction extends CI_Model {

	/**
	 * Send data FROM the SCS
	 * @param array $data The data being sent to the DCU
	 * @param string $id The transaction id if one already exists
	 * @return string Transactionid, either the existing one being used or a new one if none supplied
	 */
	public function send($data, $id = null){ 
		
		//transaction ID already exists, use it
		if($id) {
			$this->db->insert('transactions', array(
				'id' => $id,
				'payload' => $data,
				'direction' => 'from SCS',
				'at' => time()
			));
		}
		else {
			$id = $this->genID();

			$this->db->insert('transactions', array(
				'id' => $id,
				'payload' => $data,
				'direction' => 'from SCS',
				'at' => time()
			));
		}
		return $id; 
	}
	
	/**
	 * Receive data from a DCU
	 * @param string $id The transaction id that was sent out originally by the SCS
	 * @param array $data The data received from the DCU
	 */
	public function recieve($id, $data = null) {
		
		$this->db->insert('transactions', array(
			'id' => $id,
			'payload' => $data,
			'direction' => 'from DCU',
			'at' => time()
		));
	}


	/**
	 * generate some entropy for IDs
	 */
	private function genID() {

		$seeds = 'abcdefghijklmnopqrstuvwxyz';

		$output = time(); //start with the time

		//Add a little entropy, not too much as speed is a factor. 
		for ($i = 0; $i < 10; $i++) {
			$output .= $seeds[rand(0, strlen($seeds) -1)];
		}
		return md5($output);
	}

}
