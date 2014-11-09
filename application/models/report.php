<?php
class Report extends Joyst_Model {
	function DefineSchema() {
		$this->On('getall', function(&$where) {
			if (!isset($where['status'])) // If not specified imply only active items
				$where['status'] = 'active';
		});
		$this->On('create', function(&$row) {
			$row['creatorid'] = $this->User->GetActive('userid');
			$row['created'] = time();
		});

		return array(
			'_model' => 'Report',
			'_table' => 'reports',
			'_id' => 'reportid',
			'reportid' => array(
				'type' => 'pk',
				'readonly' => true,
			),
			'tagid' => array(
				'type' => 'fk',
			),
			'start' => array(
				'type' => 'epoch',
			),
			'end' => array(
				'type' => 'epoch',
			),
			'type' => array(
				'type' => 'enum',
				'options' => array('billing', 'runtime'),
				'default' => 'billing',
			),
			'description' => array(
				'type' => 'varchar',
				'length' => 200,
			),
			'creatorid' => array(
				'type' => 'fk',
				'readonly' => true,
			),
			'created' => array(
				'type' => 'epoc',
				'readonly' => true,
			),
			'status' => array(
				'type' => 'enum',
				'options' => array('active', 'deleted'),
				'default' => 'active',
			),
		);
	}

	function Get($reportid) {
		$this->db->from('reports');
		$this->db->where('reportid', $reportid);
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}

	function GetAll($where = null, $orderby = null, $limit = 100, $offset = 0) {
		$this->db->select('reports.*');
		$this->db->from('reports');
		if ($where)
			$this->db->where($where);
		if ($orderby)
			$this->db->order_by($orderby);
		$this->db->join('tags', 'tags.tagid = reports.tagid');
		$this->db->where('tags.locationid', $this->User->GetActive('locationid'));
		$this->db->limit($limit,$offset);
		return $this->db->get()->result_array();
	}
}
