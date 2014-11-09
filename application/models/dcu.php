<?php
class Dcu extends Joyst_Model {
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
		$this->on('row', function(&$row) {
			$row['commStatus'] = true;
			$row['lastSeen'] = time() - rand(1,900);
			$row['lastSeenText'] = $this->EpocTools->Age($row['lastSeen']) . 'ago';
			$row['deviceCount'] = $this->CountDevices($row['dcuid']);
			$row['uptimeText'] = $this->EpocTools->Humanize($row['uptime'], '');
		});
		return array(
			'_model' => 'Dcu',
			'_table' => 'dcus',
			'_id' => 'dcuid',
			'dcuid' => array(
				'type' => 'pk',
				'readonly' => true,
			),
			'ref' => array(
				'type' => 'varchar',
				'length' => 50,
			),
			'name' => array(
				'type' => 'varchar',
				'length' => 200,
			),
			'version' => array(
				'type' => 'int',
				'length' => 1,
			),
			'locationid' => array(
				'type' => 'fk',
			),
			'comment' => array(
				'type' => 'text',
			),
			'last_get_devices' => array(
				'type' => 'epoc',
			),
			'last_requested_get_devices' => array(
				'type' => 'epoc',
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
			'comms' => array(
				'type' => 'enum',
				'options' => array('wired', 'gsm', 'redundant'),
				'default' => 'wired',
			),
			'uptime' => array(
				'type' => 'int',
			),
			'status' => array(
				'type' => 'enum',
				'options' => array('active', 'deleted'),
				'default' => 'active',
			),
		);
	}

	function CountDevices($dcuid) {
		$this->db->select('COUNT(*) AS count');
		$this->db->from('devices');
		$this->db->where('dcuid', $dcuid);
		$row = $this->db->get()->row_array();
		return $row['count'];
	}

	function GetByRef($dcuid, $secret = null) {
		if ($secret) {
			$rows = $this->GetAll(array('ref' => $dcuid, 'secret' => $secret));
			return $rows ? $rows[0] : null;
		} else {
			$rows = $this->GetAll(array('ref' => $dcuid));
			return $rows ? $rows[0] : null;
		}
	}
}
