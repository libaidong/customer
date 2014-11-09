<?php
class Commandqueue extends Joyst_Model {
	function DefineSchema() {
		$this->On('create', function(&$row) {
			$row['created'] = time();
		});
		$this->on('created', function($cqid, $row) {
			$this->Log->Add('commandqueue', "Command '{$row['command']}' created as #$cqid", null, array('dcuid' => $row['dcuid']));
		});
		return array(
			'_model' => 'CommandQueue',
			'_table' => 'commandqueue',
			'_id' => 'commandqueueid',
			'commandqueueid' => array(
				'type' => 'pk',
				'readonly' => true,
			),
			'dcuid' => array(
				'type' => 'fk',
			),
			'command' => array(
				'type' => 'varchar',
				'length' => 100,
			),
			'payload' => array(
				'type' => 'text',
			),
			'transactionid' => array(
				'type' => 'varchar',
				'length' => 100,
			),
			'issued' => array(
				'type' => 'epoc',
			),
			'created' => array(
				'type' => 'epoc',
				'readonly' => 1,
			),
		);
	}

	function IsQueued($dcuid, $command, $params = null) {
		if ($params) { // Explode JSON and count
			$matches = FALSE;
			foreach ($this->GetAll(array(
				'dcuid' => $dcuid,
				'command' => $command,
			)) as $row) {
				$row['payload'] = json_decode($row['payload'], TRUE);
				foreach ($params as $key => $val)
					if (
						isset($row['payload']) &&
						isset($row['payload']['parameters']) &&
						isset($row['payload']['parameters'][$key]) &&
						$row['payload']['parameters'][$key] == $params[$key]
					) {
						$matches = TRUE;
						break;
					}
			}
			return $matches;
		} else { // Quick count
			return $this->Count(array(
				'dcuid' => $dcuid,
				'command' => $command,
			)) > 0;
		}
	}
}
?>
