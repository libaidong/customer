<?php
class ConsumptionData extends Joyst_Model {
	function __construct() {
		parent::construct();
	}

	function DefineSchema() {
		return array(
			'_model' => 'ConsumptionData',
			'_table' => 'consumptiondata',
			'_id' => 'deviceid',
			'deviceid' => array(
				'type' => 'fk',
				'readonly' => true,
			),
			'created' => array(
				'type' => 'epoc',
				'readonly' => true,
			),
			'quantity' => array(
				'type' => 'int',
			),
		);
	}

	function GetBetween($deviceid = null, $start, $end) {
		$this->db->select("MAX(quantity) AS quantity");
		$this->db->from('consumptiondata');
		$this->db->where('deviceid', $deviceid);
		$this->db->where('created >=', $start);
		$this->db->where('created <=', $end);
		$row = $this->db->get()->row_array();
		return $row['quantity'];
	}

}
