<?
class Location extends Joyst_Model {
	function __construct() {
		parent::construct();
		$this->EpocTools = new EpocTools();
	}
	
	function DefineSchema() {
		$this->EpocTools = new EpocTools();
		$this->On('getall', function(&$where) {
			$where['status'] = 'active';
		});
		$this->On('create', function(&$row) {
			$row['created'] = time();
			$row['creatorid'] = $this->User->GetActive('userid');
		});
		$this->On('save', function($id, &$row) {
			$row['edited'] = time();
		});
		return array(
			'_model' => 'Location',
			'_table' => 'locations',
			'_id' => 'locationid',
			'locationid' => array(
				'type' => 'pk',
			),
			'name' => array(
				'type' => 'varchar',
				'length' => 200,
			),
			'contact' => array(
				'type' => 'varchar',
				'length' => 200,
			),
			'timezone' => array(
				'type' => 'varchar',
				'length' => 150,
			),
			'basehours' => array(
				'type' => 'text',
			),
			'address' => array(
				'type' => 'varchar',
				'length' => 200,
			),
			'suburb' => array(
				'type' => 'varchar',
				'length' => 200,
			),
			'state' => array(
				'type' => 'varchar',
				'length' => 100,
			),
			'postcode' => array(
				'type' => 'varchar',
				'length' => 10,
			),
			'status' => array(
				'type' => 'enum',
				'options' => array('active', 'deleted'),
				'default' => 'active',
			),
			'secret' => array(
				'type' => 'varchar',
				'length' => 200,
			),
			'created' => array(
				'type' => 'epoc',
			),
		);
	}
	
	function Get($id) {
		$this->db->from('locations');
		$this->db->where('locationid', $id);
		$this->db->limit(1);
		$result = $this->db->get()->row_array();

		//Provide a default if one isn't given
		if(!isset($result['basehours'])) {
			$result['basehours'] = ' [ 
							{"start": 32400, "end": 61200}, 
							{"start": 32400, "end": 61200},
							{"start": 32400, "end": 61200},
							{"start": 32400, "end": 61200},
							{"start": 32400, "end": 61200},
							{"start": 32400, "end": 61200},
							{"start": 32400, "end": 61200}
						]
				';
		}

		return $result;
	}

	function GetUserLocations($userid, $where = null, $orderby = null) {
		$this->db->from('user2location');
		$this->db->join('locations', 'locations.locationid = user2location.locationid');
		$this->db->where('user2location.userid', $userid);
		if ($where)
			$this->db->where($where);
		if ($orderby)
			$this->db->order_by($orderby);
		return $this->db->get()->result_array();
	}
}
