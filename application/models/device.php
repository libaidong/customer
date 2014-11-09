<?php
class Device extends Joyst_Model {
	function DefineSchema() {
		$this->On('create', function(&$row) {
			$row['created'] = time();
			$row['creatorid'] = $this->User->GetActive('userid');
		});
		$this->On('save', function($id, &$row) {
			if(isset($row['tags'])){
				//Since the entire array will be given on save, remove the existing ones and provide the full fresh array
				$this->load->model('Tag2Device');

				$this->Tag2Device->removeTagsFromDevice($id);
				foreach($row['tags'] as $t) {
					$this->Tag2Device->upsert($id, $t);
				}
			}
			$row['edited'] = time();
			if (isset($row['starred']))
				$this->SetStar($id, $row['starred']);
		});
		$this->on('row', function(&$row) {
			$row['starred'] = $this->IsStarred($row['deviceid']);

			// typeText is the human readble device type
			$row['typeText'] = $row['type'] ? $this->schema['type']['options'][$row['type']] : 'Unknown';

			// icon Will either use 'icon' or 'type' whichever one is first available
			$row['icon'] = $row['icon'] ? $row['icon'] : $row['type'];

			$row['isActive'] = $this->IsActive($row);

			if (!$this->User->IsAdmin()) { // FIXME: Is user, prefix all with company name
				$row['name'] = "Minter Elison, {$row['name']}";
			}

			// Provide fake, predictable data
			srand($row['deviceid']);
			$row['usage'] = rand(1,1000) / 10;
		});
		$this->on('getall', function(&$where) {
			//Create a psudo call for all devices which aren't meters. 
			if(isset($where['type']) && $where['type'] == 'device') {
				$this->db->where('devices.type != "meter"'); //urgh. a real hack here. 
				unset($where['type']);
			}

			$this->db->select('devices.*');
			if (isset($where['tagid'])) {
				$this->db->join('tags2devices', 'tags2devices.deviceid = devices.deviceid');
				$this->db->where('tags2devices.tagid', $where['tagid']);
				unset($where['tagid']);
			}
			if (isset($where['starred'])) {
				$this->db->join('user2device', 'user2device.deviceid = devices.deviceid');
				$this->db->where('user2device.userid', $this->User->GetActive('userid'));
				unset($where['starred']);
			}

			$this->db->join('dcus', 'dcus.dcuid = devices.dcuid');

			if (isset($where['locationid'])) {
				if ($where['locationid'] > 0)
					$this->db->where('dcus.locationid', $where['locationid']);
				unset($where['locationid']);
			} else 
				$this->db->where('dcus.locationid', $this->User->GetActive('locationid'));

			if ($where)
				$this->db->where($where);
			
			if (!$this->User->IsAdmin()) { // Is a user, restrict to devices the user has tags for
				$this->db->join('tags2devices AS usertags2devices', 'usertags2devices.deviceid = devices.deviceid');
				$this->db->where('usertags2devices.tagid IN (SELECT tagid FROM tags2users WHERE tags2users.userid = ' . $this->db->Escape($this->User->GetActive('userid')) . ')');
			}
		});
		$this->on('created', function($deviceid, $row) {
			$this->Log->Add('device', "Device #$deviceid created", null, array('deviceid' => $deviceid));
		});

		return array(
			'_model' => 'Device',
			'_table' => 'devices',
			'_id' => 'deviceid',
			'deviceid' => array(
				'type' => 'pk',
				'readonly' => true,
			),
			'dcuid' => array(
				'type' => 'fk',
			),
			'ref' => array(
				'type' => 'varchar',
				'length' => 50,
			),
			'name' => array(
				'type' => 'varchar',
				'length' => 100,
			),
			'description' => array(
				'type' => 'varchar',
				'length' => 255,
			),
			'type' => array(
				'type' => 'enum',
				'options' => array(
					'unknown' => 'Unknown',
					'meter' => 'Meter',
					'output' => 'Output',
					'input' => 'Input',
				),
				'default' => 'unknown',
			),
			'spec' => array(
				'type' => 'json',
			),
			'scale' => array(
				'type' => 'decimal',
				'length' => '17,7',
			),
			'measurement_units' => array(
				'type' => 'enum',
				'options' => array('normal', 'pulse'),
			),
			'units' => array(
				'type' => 'varchar',
				'length' => 100,
			),
			'usage_target' => array(
				'type' => 'decimal',
				'length' => '17,7',
				'default' => 100,
			),
			'initial_offset' => array(
				'type' => 'decimal',
				'length' => '17,7',
				'default' => 0,
			),
			'icon' => array(
				'type' => 'varchar',
				'length' => 20,
			),
			'created' => array(
				'type' => 'epoc',
				'readonly' => true,
			),
			'creatorid' => array(
				'type' => 'int',
				'readonly' => true,
			),
			'edited' => array(
				'type' => 'int',
				'readonly' => true,
			),
			'status' => array(
				'type' => 'enum',
				'options' => array('active', 'deleted'),
				'default' => 'active',
			),
			'offset_start' => array(
				'type' => 'int',
				'default' => 0,
			),
			'offset_end' => array(
				'type' => 'int',
				'default' => 0,
			),
			'area' => array(
				'type' => 'int',
			),
		);
	}

	function DefineSchemaNew() {
		$this->Define('deviceid')
			->PrimaryKey();
		$this->Define('dcuid')
			->FK();
		$this->Define('ref')
			->VarChar(50);
		$this->Define('name')
			->VarChar(100);
		$this->Define('description')
			->VarChar(255);
		$this->Define('type')
			->Default('unknown')
			->Enum(array(
				'unknown' => 'Unknown',
				'meter' => 'Meter',
				'output' => 'Output',
				'input' => 'Input',
			));
		$this->Define('spec')
			->Text();
		$this->Define('scale')
			->Decimal(17, 7);
		$this->Define('measurement_units')
			->Enum(array('normal', 'pulse'));
		$this->Define('units')
			->VarChar(100);
		$this->Define('usage_target')
			->Decimal(17,7)
			->Default(100);
		$this->Define('icon')
			->VarChar(20)
			->Pipe(function(&$row) {
				return $row['icon'] || $row['type']; // Fall back to using the row type unless icon is specified
			});
		$this->Define('created')
			->Epoc()
			->ReadOnly();
		$this->Define('creatorid')
			->FK()
			->ReadOnly();
		$this->Define('edited')
			->Epoc()
			->ReadOnly();
		$this->Define('status')
			->Default('active')
			->Enum(array('active', 'deleted'));
		$this->Define('starred')
			->Set(function(&$row) {
				return $this->IsStarred($row['deviceid']);
			})
			->Get(function(&$row) {
				return $this->SetStar($row['deviceid'], $row['starred']);
			});
		$this->Define('typeText')
			->Get(function(&$row) {
				return $row['type'] ? $this->schema['type']['options'][$row['type']] : 'Unknown';
			});
		$this->Define('usage', function(&$row) {
			// Provide fake, predictable data
			srand($row['deviceid']);
			return rand(1,1000) / 10;
		});
		$this->Define('isActive', function(&$row) {
			return $this->IsActive($row);
		});

		$this->On('create', function(&$row) {
			$row['created'] = time();
			$row['creatorid'] = $this->User->GetActive('userid');
		});
		$this->On('save', function($id, &$row) {
			$row['edited'] = time();
		});
		$this->on('getall', function(&$where) {
			if (!isset($where['status']))
				$where['status'] = 'active'; // Assume we always want active unless its already specified

			$this->db->select('devices.*'); // Even if we join with other tables we are only interested in this on (fixes the issue where tags or some other table would override common field names like 'name')
			if (isset($where['tagid'])) { // Complex match against tags
				$this->db->join('tags2devices', 'tags2devices.deviceid = devices.deviceid');
				$this->db->where('tags2devices.tagid', $where['tagid']);
				unset($where['tagid']);
			}
			if (isset($where['starred'])) { // Complex match against this users starred status
				$this->db->join('user2device', 'user2device.deviceid = devices.deviceid');
				$this->db->where('user2device.userid', $this->User->GetActive('userid'));
				unset($where['starred']);
			}

			// Restrict returned devices to only those where the DCU is located in this location
			$this->db->join('dcus', 'dcus.dcuid = devices.dcuid');
			$this->db->where('dcus.locationid', $this->User->GetActive('locationid'));
		});


	}

	/**
	 * Simply returns a list of the devices which don't have any tags associated with them. 
	 * @param int $locationid The associated location
	 */
	public function getOrphanedDevices($locationid){
		$this->db->select('devices.*');
		$this->db->join('tags2devices', 'devices.deviceid = tags2devices.deviceid', 'left OUTER');
		$this->db->where('tags2devices.deviceid', null);
		return $this->db->get('devices')->result_array();
	}

	
	/**
	* Returns if a device is active
	* FIXME: This function should really read the status of an item from the DB rather than calculating it
	* @param int|array Either the ID of the device to retrieve or the device row
	*/
	function IsActive($device) {
		$this->db->from('devices');
		$this->db->where('deviceid', $device['deviceid']);
		$this->db->where('dcuid', $device['dcuid']);
		$this->db->limit(1);
		$device = $this->db->get()->row_array();
		return $device['device_status'];
	}

	function IsStarred($deviceid) {
		$this->db->select('COUNT(*) AS count');
		$this->db->from('user2device');
		$this->db->where('userid', $this->User->GetActive('userid'));
		$this->db->where('deviceid', $deviceid);
		$row = $this->db->get()->row_array();
		return $row['count'] > 0;
	}

	function SetStar($deviceid, $status) {
		$this->db->from('user2device');
		$this->db->where('userid', $this->User->GetActive('userid'));
		$this->db->where('deviceid', $deviceid);
		$this->db->delete();

		if ($status)
			$this->db->insert('user2device', array(
				'userid' => $this->User->GetActive('userid'),
				'deviceid' => $deviceid,
			));
	}

	function HasTag($deviceid, $tagid) {
		$this->db->from('tags2devices');
		$this->db->select('COUNT(*) AS count');
		$this->db->where('deviceid', $deviceid);
		$this->db->where('tagid', $tagid);
		$row = $this->db->get()->row_array();
		return $row['count'] > 0;
	}

	function GetTags($deviceid) {
		$this->db->select('tags.*');
		$this->db->from('tags2devices');
		$this->db->where('deviceid', $deviceid);
		$this->db->join('tags', 'tags.tagid = tags2devices.tagid');
		$this->db->order_by('tags.name');
		return $this->db->get()->result_array();
	}

	function SetTag($deviceid, $tagid) {
		if ($this->HasTag($deviceid, $tagid))
			return;
		$this->db->insert('tags2devices', array(
			'tagid' => $tagid,
			'deviceid' => $deviceid,
			'creatorid' => $this->User->GetActive('userid'),
			'created' => time(),
		));
	}

	function GetDeviceByID($deviceid = null, $dcuid = null, $ref = true) {   //deviceid is ref
		if ($deviceid){
			$this->db->from('devices');
			if ($ref)
				$this->db->where('ref', $deviceid);
			else
				$this->db->where('deviceid', $deviceid);
			if ($dcuid)
				$this->db->where('dcuid', $dcuid);
			$this->db->where('status', 'active');
			$this->db->limit(1);
			return $this->db->get()->row_array();
		} else
			return false;
	}

	function UpdateStatus($deviceid = null, $dcuid = null, $status = null) {
		if ($deviceid && $dcuid){
			$this->db->where('ref', $deviceid);
			$this->db->where('dcuid', $dcuid);
			$this->db->update('devices', array('device_status' => $status));
		} else
			return false;
	}

	function Delete($deviceid = null) {
		if ($deviceid){
			$this->db->where('deviceid', $deviceid);
			$this->db->update('devices', array('status' => 'deleted'));
		} else
			return false;
	}
}
