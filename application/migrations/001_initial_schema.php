<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Initial_Schema extends CI_Migration {
	public function up() {
		/**
		 * Devices
		 */
		$this->dbforge->add_field(array(
			'deviceid' => array(
				'type' => 'int',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'locationid' => array(
				'type' => 'int'
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => '100'
			),
			'type' => array(
				'type' => 'ENUM',
				'constraint' => "'unknown', 'ac', 'light', 'hotwater'",
				'default' => 'unknown'
			),
			'spec' => array(
				'type' => 'TEXT'
			),
			'created' => array(
				'type' => 'INT'
			),
			'status' => array(
				'type' => 'ENUM',
				'constraint' => "'active', 'deleted'",
				'default' => 'active'
			)
		));

		$this->dbforge->add_key('deviceid', TRUE);

		$this->dbforge->create_table('devices');

		$this->db->query('ALTER TABLE `devices` ADD INDEX `devices_locationid` (`locationid`)');
		$this->db->query('ALTER TABLE `devices` ADD INDEX `devices_type` (`type`)');
		$this->db->query('ALTER TABLE `devices` ADD INDEX `devices_status` (`status`)');

		$fields = array(
			array(
				'locationid' => 1,
				'name' => 'Air Conditioning 1',
				'type' => 'ac',
				'spec' => '{ "deviceid": 1, "type": "HVAC", "name": "Acme A/C Unit version 1", "availableParams": [ "setTemp", "getTemp", "On", "Off", "status" ] }',
				'created' => 1392596998,
				'status' => 'active'
			),
			array(
				'locationid' => 1,
				'name' => 'Air Conditioning 2',
				'type' => 'ac',
				'spec' => '{ "deviceid": 2, "type": "HVAC", "name": "Acme A/C Unit version 1", "availableParams": [ "setTemp", "getTemp", "On", "Off", "status" ] }',
				'created' => 1392596998,
				'status' => 'active'
			),
			array(
				'locationid' => 1,
				'name' => 'Air Conditioning 3',
				'type' => 'ac',
				'spec' => '{ "deviceid": 3, "type": "HVAC", "name": "Acme A/C Unit version 1", "availableParams": [ "setTemp", "getTemp", "On", "Off", "status" ] }',
				'created' => 1392596998,
				'status' => 'active'
			),
			array(
				'locationid' => 1,
				'name' => 'Hot Water Unit',
				'type' => 'hotwater',
				'spec' => '{ "deviceid": 4, "type": "HWU", "name": "Acme Water Heater", "availableParams": [ "On", "Off" ] }',
				'created' => 1392596998,
				'status' => 'active'
			),
			array(
				'locationid' => 1,
				'name' => 'Warehouse Lights',
				'type' => 'light',
				'spec' => '{ "deviceid": 5, "type": "Lights", "name": "Acme Light Unit", "availableParams": [ "On", "Off" ] }',
				'created' => 1392596998,
				'status' => 'active'
			),
			array(
				'locationid' => 1,
				'name' => 'Warehouse Security Lights',
				'type' => 'light',
				'spec' => '{ "deviceid": 6, "type": "Lights", "name": "Acme Floodlights", "availableParams": [ "On", "Off" ] }',
				'created' => 1392596998,
				'status' => 'active'
			),
		);

		foreach($fields as $field) {
			$this->db->insert('devices', $field);
		}

		$this->dbforge->add_field(array(
			'eventid' => array(
				'type' => 'int',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'deviceid' => array(
				'type' => 'int'
			),
			'command' => array(
				'type' => 'TEXT'
			),
			'start' => array(
				'type' => 'INT',
			),
			'end' => array(
				'type' => 'INT'
			),
			'repeat_start' => array(
				'type' => 'INT',
			),
			'repeat_end' => array(
				'type' => 'INT',
			),
			'repeat_type' => array(
				'type' => 'ENUM',
				'constraint' => "'none', 'daily', 'weekly', 'monthly', 'yearly'",
				'default' => 'none'
			),
			'repeat_interval' => array(
				'type' => 'INT'
			),
			'creatorid' => array(
				'type' => 'INT'
			),
			'status' => array(
				'type' => 'ENUM',
				'constraint' => "'active', 'deleted'",
				'default' => 'active'
			)
		));

		$this->dbforge->add_key('eventid', TRUE);

		$this->dbforge->create_table('events');

		$this->db->query('ALTER TABLE `events` ADD INDEX `events_deviceid` (`deviceid`)');
		$this->db->query('ALTER TABLE `events` ADD INDEX `events_repeat` (`repeat_start`, `repeat_end`)');
		$this->db->query('ALTER TABLE `events` ADD INDEX `events_status` (`status`)');

		/**
		 * locations
		 */
		$this->dbforge->add_field(array(
			'locationid' => array(
				'type' => 'int',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 200
			),
			'status' => array(
				'type' => 'ENUM',
				'constraint' => "'active', 'deleted'",
				'default' => 'active'
			),
			'created' => array(
				'type' => 'INT'
			)
		));

		$this->dbforge->add_key('locationid', TRUE);

		$this->dbforge->create_table('locations');

		$this->db->query('ALTER TABLE `locations` ADD INDEX `locations_status` (`status`)');

		$fields = array(
			array(
				'name' => 'Warehouse One',
				'status' => 'active',
				'created' => 1392593325
			),
			array(
				'name' => 'Warehouse Two',
				'status' => 'active',
				'created' => 1392593325
			),
			array(
				'name' => 'Office One',
				'status' => 'active',
				'created' => 1392593325
			),
			array(
				'name' => 'Office Two',
				'status' => 'active',
				'created' => 1392593325
			)
		);

		foreach($fields as $field) {
			$this->db->insert('locations', $field);
		}


		/**
		 * locations
		 */
		$this->dbforge->add_field(array(
			'userid' => array(
				'type' => 'INT'
			),
			'deviceid' => array(
				'type' => 'INT'
			),
			'type' => array(
				'type' => 'VARCHAR',
				'constraint' => '50'
			),
			'text' => array(
				'type' => 'TEXT'
			),
			'data' => array(
				'type' => 'TEXT'
			),
			'created' => array(
				'type' => 'INT'
			)
		));

		$this->dbforge->create_table('logs');

		$this->db->query('ALTER TABLE `logs` ADD INDEX `logs_type` (`type`)');
		$this->db->query('ALTER TABLE `logs` ADD INDEX `logs_userid` (`userid`)');
		$this->db->query('ALTER TABLE `logs` ADD INDEX `logs_deviceid` (`deviceid`)');

		/**
		 * User Schema
		 */
		$this->dbforge->add_field(array(
			"userid" => array(
				'type' => 'int',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			"username" => array(
				'type' => 'VARCHAR',
				'constraint' => '50'
			),
			"fname" => array(
				'type' => 'VARCHAR',
				'constraint' => '50'
			),
			"lname" => array(
				'type' => 'VARCHAR',
				'constraint' => '50'
			),
			"passhash" => array(
				'type' => 'CHAR',
				'constraint' => '40'
			),
			"passhash2" => array(
				'type' => 'CHAR',
				'constraint' => '40',
				'null' => TRUE
			),
			"email" => array(
				'type' => 'VARCHAR',
				'constraint' => '100'
			),
			"status" => array(
				'type' => 'ENUM',
				'constraint' => '"active", "deleted"',
				'default' => 'active'
			),
			"role" => array(
				'type' => 'ENUM',
				'constraint' => '"user", "admin", "root"',
				'default' => 'user'
			),
			"created" => array(
				'type' => 'INT',
				'null' => TRUE,
			),
			"edited" => array(
				'type' => 'INT',
				'null' => TRUE
			),
			"lastlogin" => array(
				'type' => 'INT',
				'null' => TRUE
			),
			"passhash2_created" => array(
				'type' => 'INT',
				'null' => TRUE
			)
		));

		$this->dbforge->add_key('userid', TRUE);
		$this->dbforge->create_table('users', TRUE);

		$this->db->query('ALTER TABLE `users` ADD INDEX `users_username` (`username`)');
		$this->db->query('ALTER TABLE `users` ADD INDEX `users_status` (`status`)');
		$this->db->query('ALTER TABLE `users` ADD INDEX `users_role` (`role`)');

		$fields = array(
			array(
				'userid' => null,
				'username' => 'mc',
				'fname' => 'Matt',
				'lname' => 'Carter',
				'passhash' => 'qwaszx',
				'passhash2' => null,
				'email' => 'matt@mfdc.biz',
				'status' => 'active',
				'role' => 'root',
				'created' => 1364342313,
				'edited' => 1364342313,
				'lastlogin' => 1364342313,
				'passhash2_created' => null
			),
			array(
				'userid' => null,
				'username' => 'dp',
				'fname' => 'David',
				'lname' => 'Porter',
				'passhash' => 'qwaszx',
				'passhash2' => null,
				'email' => 'david@mfdc.biz',
				'status' => 'active',
				'role' => 'root',
				'created' => null,
				'edited' => null,
				'lastlogin' => null,
				'passhash2_created' => null
			),
			array(
				'userid' => null,
				'username' => 'nc',
				'fname' => 'Nathan',
				'lname' => 'Collins',
				'passhash' => 'qwaszx',
				'passhash2' => null,
				'email' => 'nathan@mfdc.biz',
				'status' => 'active',
				'role' => 'root',
				'created' => null,
				'edited' => null,
				'lastlogin' => null,
				'passhash2_created' => null
			),
			array(
				'userid' => null,
				'username' => 'jj',
				'fname' => 'Jason',
				'lname' => 'Jones',
				'passhash' => 'qwaszx',
				'passhash2' => null,
				'email' => 'jason@mfdc.biz',
				'status' => 'active',
				'role' => 'root',
				'created' => null,
				'edited' => null,
				'lastlogin' => null,
				'passhash2_created' => null
			),
			array(
				'userid' => null,
				'username' => 'jj',
				'fname' => 'Jay',
				'lname' => 'Gualtieri',
				'passhash' => 'qwaszx',
				'passhash2' => null,
				'email' => 'jay@ausnviro.com.au',
				'status' => 'active',
				'role' => 'root',
				'created' => null,
				'edited' => null,
				'lastlogin' => null,
				'passhash2_created' => null
			),
			array(
				'userid' => null,
				'username' => 'lp',
				'fname' => 'Luke',
				'lname' => 'Padley',
				'passhash' => 'qwaszx',
				'passhash2' => null,
				'email' => 'lpadley@googlemail.com',
				'status' => 'active',
				'role' => 'root',
				'created' => null,
				'edited' => null,
				'lastlogin' => null,
				'passhash2_created' => null
			)
		);

		foreach($fields as $field) {
			$this->db->insert('users', $field);
		}

		/**
		 * user2location
		 */
		$this->dbforge->add_field(array(
			'userid' => array(
				'type' => 'INT'
			),
			'locationid' => array(
				'type' => 'INT'
			),
		));

		$this->dbforge->add_key('userid', TRUE);
		$this->dbforge->add_key('locationid', TRUE);

		$this->dbforge->create_table('user2location', TRUE);

		$fields = array(
			array(
				'userid' => 1,
				'locationid' => 1
			),
			array(
				'userid' => 1,
				'locationid' => 2
			),
			array(
				'userid' => 1,
				'locationid' => 3
			),
			array(
				'userid' => 1,
				'locationid' => 4
			),
			array(
				'userid' => 2,
				'locationid' => 1
			),
			array(
				'userid' => 2,
				'locationid' => 2
			),
			array(
				'userid' => 2,
				'locationid' => 3
			),
			array(
				'userid' => 2,
				'locationid' => 4
			),
			array(
				'userid' => 3,
				'locationid' => 1
			),
			array(
				'userid' => 3,
				'locationid' => 2
			),
			array(
				'userid' => 3,
				'locationid' => 3
			),
			array(
				'userid' => 3,
				'locationid' => 4
			),
			array(
				'userid' => 4,
				'locationid' => 1
			),
			array(
				'userid' => 4,
				'locationid' => 2
			),
			array(
				'userid' => 4,
				'locationid' => 3
			),
			array(
				'userid' => 4,
				'locationid' => 4
			),
			array(
				'userid' => 5,
				'locationid' => 1
			),
			array(
				'userid' => 5,
				'locationid' => 2
			),
			array(
				'userid' => 5,
				'locationid' => 3
			),
			array(
				'userid' => 5,
				'locationid' => 4
			),
			array(
				'userid' => 6,
				'locationid' => 1
			),
			array(
				'userid' => 6,
				'locationid' => 2
			),
			array(
				'userid' => 6,
				'locationid' => 3
			),
			array(
				'userid' => 6,
				'locationid' => 4
			),
		);

		foreach($fields as $field) {
			$this->db->insert('user2location', $field);
		}
	}
}