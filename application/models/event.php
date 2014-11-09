<?
class Event extends Joyst_Model {
	function __construct() {
		parent::__construct();
		$this->load->Model('Device');
	}

	function DefineSchema() {
		$this->On('row', function(&$row) {
			$row['isActive'] = $this->IsActive($row);
			$row['startText'] = date('d/m g:ia', $row['start']);
			$row['endText'] = date('d/m g:ia', $row['end']);
			$row['device'] = $this->Device->Get($row['deviceid']);
			$row['repeatText'] = $this->Event->TranslateRepeats($row);
		});
		$this->On('getall', function(&$where) {
			if (!isset($where['status']))
				$where['status'] = 'active';
		});
		$this->On('create', function(&$row) {
			$row['created'] = time();
			$row['creatorid'] = $this->User->GetActive('userid');
		});
		$this->on('created', function($id, $row) {
			$this->Log->Add('event', "Event #$id created", null);
		});
		$this->On('save', function($id, &$row) {
			$row['edited'] = time();
			$row['editedid'] = $this->User->GetActive('userid');
		});
		return array(
			'_model' => 'Event',
			'_table' => 'events',
			'_id' => 'eventid',
			'eventid' => array(
				'type' => 'pk',
				'readonly' => true,
			),
			'eventtype' => array(
				'type' => 'enum',
				'options' => array('device schedule', 'calendar event'),
				'default' => 'device schedule',
			),
			'deviceid' => array(
				'type' => 'fk',
			),
			'start' => array(
				'type' => 'epoch',
			),
			'end' => array(
				'type' => 'epoch',
			),
			'description' => array(
				'type' => 'varchar',
				'length' => 255,
			),
			'command_start' => array(
				'type' => 'text',
			),
			'command_end' => array(
				'type' => 'text',
			),
			'basehours' => array(
				'type' => 'text',
				//by ordinality, ie the first entry is for Monday, Tuesday etc. 
				'default' => '[
					{start: 32400, end: 61200}, 
					{start: 32400, end: 61200},
					{start: 32400, end: 61200},
					{start: 32400, end: 61200},
					{start: 32400, end: 61200},
					{start: 32400, end: 61200},
					{start: 32400, end: 61200}
				]',
			),
			'repeat_start' => array(
				'type' => 'epoc',
			),
			'repeat_end' => array(
				'type' => 'epoc',
			),
			'repeat_type' => array(
				'type' => 'enum',
				'options' => array(
					'none' => 'Once only',
					'daily' => 'Daily',
					'weekly' => 'Weekly',
					'monthly' => 'Monthly',
					'yearly' => 'Yearly',
				),
				'default' => 'none',
			),
			'repeat_internal' => array(
				'type' => 'int',
			),
			'repeat_params' => array(
				'type' => 'varchar',
				'length' => 100,
			),
			'created' => array(
				'type' => 'epoc',
				'readonly' => true,
			),
			'creatorid' => array(
				'type' => 'fk',
				'readonly' => true,
			),
			'edited' => array(
				'type' => 'int',
				'readonly' => true,
			),
			'editedid' => array(
				'type' => 'fk',
				'readonly' => true,
			),
			'status' => array(
				'type' => 'enum',
				'options' => array('active', 'deleted'),
				'default' => 'active',
			),
		);
	}

	/**
	* Get all calendar events between a set of dates
	* This function also computes all repeating events if requested
	* @param int $start The start epoc to filter by
	* @param int $end The end epoc to filter by
	* @param array $where Additional WHERE conditions to filter by
	* @param bool $computeRepeating Also compute all repeating events
	*/
	function GetBetween($start, $end, $where = null, $computeRepeating = true) {
		$this->db->select('events.*');
		$this->db->from('events');
		$this->db->where("((start >= $start AND end <= $end) OR ((repeat_end >= $start OR isNull(repeat_end)) AND events.repeat_type != 'none') ) "); // Get both repeating events AND single events
		$this->db->where('events.status', 'active');

		if ($where) {
			if (isset($where['tagid'])) { // Meta value to filter by tagids
				$this->db->join('tags2devices', 'tags2devices.deviceid = events.eventid');
				$this->db->where('tags2devices.tagid', $where['tagid']);
				unset($where['tagid']);
			}
			if (isset($where['locationid'])) { // Meta value to filter by locations
				$this->db->join('devices AS location_devices', 'location_devices.deviceid = events.deviceid');
				$this->db->join('dcus AS location_dcus', 'location_dcus.dcuid = location_devices.dcuid');
				$this->db->where('location_dcus.locationid', $where['locationid']);
				unset($where['locationid']);
			}
			if (isset($where['dcuid'])) { // Meta value to filter by dcus
				$this->db->join('devices AS dcus_devices', 'dcus_devices.deviceid = events.deviceid');
				$this->db->where('dcus_devices.dcuid', $where['dcuid']);
				unset($where['dcuid']);
			}
			if (isset($where['userid'])) { // Meta value to restrict results based on tags associated with given userid
				$this->db->join('tags2devices AS users_tags_devices', 'users_tags_devices.deviceid = events.eventid');
				$this->db->join('tags2users AS users_tags', 'users_tags.tagid = users_tags_devices.tagid');
				$this->db->where('users_tags.userid', $where['userid']);
				unset($where['userid']);
			}
			if ($where) // Anything left? Throw at SQL
				$this->db->where($where);
		}
		$this->db->order_by('start');

		if (!$computeRepeating) {
			$rows = $this->db->get()->result_array();
			$this->ApplyRows($rows);
			return $rows;
		}
//die($this->db->_compile_select());
		$events = array();
		foreach ($all = $this->db->get()->result_array() as $event) {
			if ($repeats = $this->CalcRepeating($event, $start, $end)) // Merge repeats in with this array
				$events = array_merge($events, $repeats);
		}
		$this->ApplyRows($events);
		
		return $events;
	}

	/**
	* Compute all repeating events for a given event parent
	* @param array $event The event row to compute repeating events for
	* @param int $start Optional starting point if $event['repeat_start'] isn't to be used
	* @param int $end Optional ending point if $event['repeat_end'] isn't to be used
	*/
	function CalcRepeating($event, $start = null, $end = null) {
		//Mark the original instance as a non-repeating event 	
		$event['initialInstance'] = true;

		$events = array();
		if ($event['repeat_type'] == 'none') {
			$events["ev-{$event['eventid']}"] = $event;
		} else {
			switch ($event['repeat_type']) {
				case 'daily':
					$repeatString = 'day';
					break;
				case 'weekly':
					$repeatString = 'week';
					break;
				case 'monthly':
					$repeatString = 'month';
					break;
				case 'yearly':
					$repeatString = 'year';
					break;
			}

			// Populate repeating events
			$clone = $event;

			$cloneStart = $start ? max($event['repeat_start'], $start) : $event['repeat_start'];

			//If there's a listed expiry on the event, use it, else don't and just keep within the $end window
			if(isset($event['repeat_end']))
				$cloneEnd = $end ? min($event['repeat_end'], $end) : $event['repeat_end'];
			else 
				$cloneEnd = $end; 

			$clone['start'] = $cloneStart;
			

			//put the first event in
			$events[] = $event;
			$event['initialInstance'] = false; //don't mark future cloned iterations as the initial instance

			//Keep track of the time where this iteration is up to, becuase it'll need to advance, even if there is no items cloned
			$currentStartTime = $event['start'];
			$iterator = 0; //This is keeps track of how many iterations are we ahead from the original item. If units are 'days' and this is 1, then we're 1 day ahead. 
			
			//Increment the start and the end times. 
			$currentStartTime  = strtotime("+ $iterator $repeatString", $event['start']);
			$currentEndTime = strtotime("+ $iterator $repeatString", $event['end']);

			while ($currentStartTime <= $cloneEnd) {
				

				//first check if this iteration is within the window we're after. If not, there's no point in going and calculating things
				if($currentStartTime >= $start && $iterator > 0) {//also remember the initial event will be there, so don't add on the first iteration
					
					//Create a new clone of the item
					$newClone = $event;

					$newClone['start'] = $currentStartTime; 
					$newClone['end'] = $currentEndTime; 
					
					$events[] = $newClone;
				}
				else {
					//noop, just continue. We've not iterated sufficiently into the window of interest
				}

				$iterator++;
				
				//Increment the start and the end times. 
				$currentStartTime  = strtotime("+ $iterator $repeatString", $event['start']);
				$currentEndTime = strtotime("+ $iterator $repeatString", $event['end']);
			}
		}
		return $events;
	}

	/**
	 * Shows calendar events, but filters out those removed for public holidays and similar scheduled downtime. Meant to be a simple filter layer over the getEvents() call.
	 */
	public function getEventsActive($start, $end, $where = null, $sort = false) {
		$scheduledEvents = $this->getBetween($start, $end, $where);

		$output = array();
		foreach($scheduledEvents as $s) {

			//Only check if dealing with device schedules, there's no point checking calendar events
			if($s['eventtype'] == 'device schedule') {

				//Now, gather any calendar events for each and see if there's a conflict. 
				if($calendarEvents = $this->getBetween(strtotime('midnight', $s['start']), strtotime('midnight tomorrow', $s['end']), array('eventtype' => 'calendar event'))) {

					$ok = true;

					foreach($calendarEvents as $c) {
						if($s['eventid'] == $c['eventid']){ 
							//don't allow self-conflict
						}
						else {

							if (
								($c['start'] >= $s['start'] && $c['start'] <= $s['end'])  //if the secheduled event starts before a calendar event finished
								|| ($c['start'] <= $s['start'] && $c['end'] >= $s['start']) //if a calendar event starts before a scheduled one is finished
								|| ($c['start'] <= $s['start'] && $c['end'] >= $s['end'])
							) {
								/*
								 * Allow the first instance in any repeating event, and thereby also one-off events 
								 * As being allowed to override this subtractive behaviour.
								 */
								if(!$s['initialInstance'])
									$ok = false; //not ok, they're colliding
							}

						}
					}

					if($ok)
						$output[] = $s;
				}
				else { //no conflict, add as per usual
					$output[] = $s;
				}
			}
			else { //no conflict, add as per usual
				$output[] = $s;
			}
			
		}

		if($sort) {
			usort($output, function($a, $b) {
				if ($a['start'] == $b['start'])
					return 0;
				return ($a['start'] < $b['start']) ? -1 : 1;
			});
		}

		return $output; 
	}

	/**
	* Returns if an event is active
	* FIXME: This function should really read the status of an item from the DB rather than calculating it
	* @param int|array Either the ID of the element to retrieve or the event row
	*/
	function IsActive($event) {
		if (is_int($event))
			$event = $this->Get($event);
		$now = time();
		return ($now >= $event['start'] && $now <= $event['end']);
	}

	/**
	* Return a human readable string about the repeat data
	* @param int|array $event Either the id or the row of the event to translate
	* @return string The human readble repeat data e.g. 'every 2 days'
	*/
	function TranslateRepeats($event) {
		if (is_int($event))
			$event = $this->Get($event);
		switch ($event['repeat_type']) {
			case 'daily':
				if ($event['repeat_interval'] > 1) {
					return "Every {$event['repeat_interval']} days";
				} else
					return 'Daily';
			case 'weekly':
				if ($event['repeat_interval'] > 1) {
					return "Every {$event['repeat_interval']} weeks";
				} else
					return 'Weekly';
			case 'monthly':
				if ($event['repeat_interval'] > 1) {
					return "Every {$event['repeat_interval']} months";
				} else
					return 'Monthly';
			case 'yearly':
				if ($event['repeat_interval'] > 1) {
					return "Every {$event['repeat_interval']} years";
				} else
					return 'Yearly';
			default: 
				return 'Unknown';
		}
	}

	/**
	* Get hash of last computed schedule
	* FIXME: This function is just a stub
	* @param int $dcuid The ID of the DCU to compute the hash of
	*/
	function GetHash($dcuid) {
		return md5(time());
	}

	/**
	* Translate a shorthand command into JSON for use in iCal on the DCDs
	* @param string $command The internal command to translate
	* @param array $event The existing event row to apply the command to
	* @return array The object to be converted to JSON
	*/
	function CommandHash($command, $event) {
		if (!preg_match('/(.+?)\((.*)\)/', $command, $matches))
			return;
		if (count($matches) < 3)
			return;
		list($raw, $command, $params) = $matches;

		$command = strtolower($command);
		$device = $this->Device->Get($event['deviceid']);

		$out = array();

		foreach (array('start', 'end') as $side) {
			switch ($command) {
				case 'turnon':
					$out['methods' . ucfirst($side)] = array(
						'method' => 'setState',
						'params' => array(
							'deviceId' => $device['ref'],
							'state' => 'on',
						),
					);
					break;
				case 'turnoff':
					$out['methods' . ucfirst($side)] = array(
						'method' => 'setState',
						'params' => array(
							'deviceId' => $device['ref'],
							'state' => 'off',
						),
					);
					break;
			}
		}
		return $out;
	}
}
?>
