<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Calendar extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->Security->EnsureLogin();
		$this->load->library('rest');
	}

	/** 
	 * Simply returns a list of timezones as a JSON array
	 */
	function timezones(){
		echo json_encode($this->db->get('tz')->result_array());
	}

	/**
	* Fancy filtering system to display calendars
	*/
	function Dashboard() {
		$this->load->Model('Tag');
		$this->load->Model('Device');
		$this->load->Model('Event');

		$this->site->Header('Schedules');
		$this->site->View('calendar/dashboard');
		$this->site->Footer();
	}

	//
	function index() {
		if ($this->site->Want('html')) {
			$this->EpocTools = new EpocTools();
			$this->site->header('Calendar');
			$this->load->view('calendar/index');
			$this->site->footer();
		} else {
			$this->load->model('Event');
			$this->load->model('Device');

			if($this->rest->get()) {
				$start = time();
				$end = strtotime('+2 days');

				$this->URLopts = new URLopts();
				$this->URLopts->Post();
				$params = $this->URLopts->Get();
				if (isset($params['after']) && $params['after'])
					$start = strtotime($params['after']);
				if (isset($params['before']) && $params['before'])
					$end = strtotime($params['before']);

				$events = $this->Event->GetBetween($start, $end);

				if (isset($params['array'])) // Output wants an array - sort (which also breaks key/val hash)
					usort($events, function($a, $b) {
						if ($a['start'] == $b['start'])
							return 0;
						return ($a['start'] < $b['start']) ? -1 : 1;
					});

				$this->site->JSON($events);
			}
			elseif ($post = $this->rest->postJSON()) {

				print_R($post);
			}
		}
	}

	function Get($id = null) {
		return $this->Edit($id);
	}

	function Edit($eventid = null) {
		$this->load->model('Event');
		$this->load->model('Format');

		if (!$event = $this->Event->Get($eventid) && $eventid)
			$this->site->Error('Event not found');
		if($this->site->Want('html')) {
			$this->site->Header('Edit Schedule');
			$this->site->View('calendar/event');
			$this->site->Footer();
		} 
		elseif ($this->site->Want('post-json')) {
			$this->Event->Save($eventid, $_POST);
			$this->site->JSON($this->Event->Get($event['eventid']));
		} 
		elseif ($this->site->Want('put-json')) {
			$this->Event->Save($eventid, $_POST);
			$this->site->JSON($this->Event->Get($event['eventid']));
		} 
		elseif ($this->site->Want('json')) {
			$this->site->JSON($event);
		}
	}

	function Meta() {
		$this->load->Model('Event');
		if (!$this->site->Want('json'))
			$this->site->Error('I only respond to JSON requests');
		$this->site->JSON($this->Event->GetSchema());
	}


	// JSON API {{{
	/**
	 * Events API
	 * @param int $eventid The event ID, used for GET requests and on saving to an existing event. 
	 */
	function events($eventid = null)  {
		$this->load->model('Event');
		$this->load->model('Device');

		if($this->rest->get() && $eventid) {
			echo json_encode($this->Event->get($eventid));
		}
		elseif($this->rest->get() && !$eventid) {
			$start = time();
			$end = strtotime('+2 days');

			$this->URLopts = new URLopts();
			$this->URLopts->Post();

			$params = $this->URLopts->Get();

			if (isset($params['after']) && $params['after']){
				$start = strtotime($params['after']);
				unset($params['after']);
			}
			if (isset($params['before']) && $params['before']){
				$end = strtotime($params['before']);
				unset($params['before']);
			}

			//Allow timestamps directly as well
			if (isset($params['afterTS']) && $params['afterTS']){
				$start = $params['afterTS'];
				unset($params['afterTS']);
			}
			if (isset($params['beforeTS']) && $params['beforeTS']){
				$end = $params['beforeTS'];
				unset($params['beforeTS']);
			}
			if (isset($params['array'])){ // Output wants an array - sort (which also breaks key/val hash)
				$sort = true;
				unset($params['array']);
			}
			else $sort = false;
			
			if (isset($params['getBetween'])){ //Need to use more simple time calculation with the getBetween method, not the complicated getEventsActive() 
				$simple = true;
				unset($params['getBetween']);
			}
			else $simple = false; 

			// Restrict results for user roles
			if ($this->User->GetActive('role') == 'user')
				$params['userid'] = $this->User->GetActive('userid');

			// Retrict results depending on location
			$params['locationid'] = $_SESSION['location']['locationid'];

			if($simple)
				$events = $this->Event->getBetween($start, $end, $params, $sort);
			else 
				$events = $this->Event->GetEventsActive($start, $end, $params, $sort);
			$this->site->JSON($events);
		}
		elseif (($post = $this->rest->postJSON()) && $eventid) { //Saving to an existing record
			echo $this->Event->save($eventid, $post);
		}
		elseif (($post = $this->rest->postJSON()) && !$eventid) { //Saving new record
			echo $this->Event->create($post);
		}
		elseif($this->rest->delete() && $eventid) {
			echo $this->Event->delete($eventid);
		}
	}
	/**
	* Retrieve all allocations in the calendar
	* @param $_POST['start'] The timestamp at the start of the period to return (if none, the start of this week is used)
	* @param $_POST['end'] The timestamp at the end of the period to return (if none, start + 2 months is used)
	* @param string $_POST['device'] OPTIONAL ID of the device to filter by
	*/
	function JSONlist() {
		$this->load->model('User');
		$this->load->model('Device');
		$this->load->model('Event');

		// URLopts config {{{
		$this->URLopts = new URLopts();
		$this->URLopts->Post();
		$params = $this->URLopts->Get(null, 4);
		$filters = array();
		if (isset($params['tagid']) && $params['tagid'])
			$filters['tagid'] = $params['tagid'];
		if (isset($params['deviceid']) && $params['deviceid'])
			$filters['events.deviceid'] = $params['deviceid'];
		// }}}

		if (!isset($_POST['start']))
			$_POST['start'] = strtotime('this monday');

		if (!isset($_POST['end']))
			$_POST['end'] = strtotime('+2 months');

		$out = array();
		foreach ($this->Event->GetEventsActive($_POST['start'], $_POST['end'], $filters) as $event) {
			$device = $this->Device->Get($event['deviceid']);
			$out[] = array(
				'id' => 'ev-' . $event['eventid'],
				'title' => isset($device['name']) ? $device['name'] : $event['description'],
				'start' => $event['start'],
				'color' => $event['eventtype'] == 'calendar event' ? '#000000' : '#0022DD',
				'end' => $event['end']
			);
		}
		$this->site->JSON($out);
	}

	/**
	* Add a calendar event to the system
	* @param string $_POST['type'] The type of allocation to make. Enum of ('device')
	* @param string $_POST['device'] OPTIONAL ID of the device to add (if $_POST['type'] == 'device')
	* @param string $_POST['date'] The date onto which the allocation should be made
	* @param string $_POST['comment'] OPTIONAL Comment to add to the event
	*/
	function JSONAdd() {
		$this->load->library('Time');
		$this->load->model('User');
		$this->load->model('Device');
		$this->load->model('Event');
		$this->Security->EnsureLogin();
		if (!isset($_POST['type'])) {
			echo $this->site->JSONError('Type must be specified');
		} elseif (!in_array($_POST['type'], qw('device task comment unavailable'))) {
			echo $this->site->JSONError('Invalid type');
		} elseif (!isset($_POST['device'])) {
			echo $this->site->JSONError('Device must be specified');
		} elseif (!$device = $this->Device->Get($_POST['device'])) {
			echo $this->site->JSONError('Invalid device');
		} elseif (!isset($_POST['date'])) {
			echo $this->site->JSONError('Date must be specified');
		} elseif (!$_POST['date'] > 0) {
			echo $this->site->JSONError('Invalid date');
		} else { // All is well
			$this->EpocTools = new EpocTools();

			$data = array(
				'type' => $_POST['type'],
				'deviceid' => $device['deviceid'],
				'userid' => $this->User->GetActive('userid'),
				'start' => $this->EpocTools->Date($_POST['date']),
				'end' => $this->EpocTools->DateEnd($_POST['date']),
			);
			if ($this->Event->GetBetween($data['start'], $data['end'] + (60*60*24), array('deviceid' => $data['deviceid'], 'userid' => $data['userid']))) {
				echo $this->site->JSONError('That event already exists');
			} else { // No existing
				foreach (qw('comment') as $field) // Optional fields
					if (isset($_POST[$field]))
						$data[$field] = $_POST[$field];
				$this->Event->Create($data);
				echo $this->site->JSONInfo('Event created');
			}
		}
	}

	/**
	* Move an event within a calendar (usually by drag/dropping)
	* @param string $_POST['event'] The event ID to move. Note that this could have an optional 'ev-' prefix for namespacing reasons - this is removed automaticly.
	* @param int $_POST['days'] OPTIONAL number of days to move (this could be negative)
	* @param int $_POST['minutes'] OPTIONAL number of minutes to move (this could be negative)
	*/
	function JSONMove() {
		$this->load->model('Event');
		$this->Security->EnsureLogin();
		if (!isset($_POST['event'])) {
			echo $this->site->JSONError('Event must be specified');
		} else { // All is well
			if (substr($_POST['event'], 0, 3) == 'ev-')
				$_POST['event'] = substr($_POST['event'], 3);
			if (!$event = $this->Event->Get($_POST['event']))
				echo $this->site->JSONError('Invalid event');

			$newdate['start'] = $event['start'];
		
			$status = '';
			if (isset($_POST['days']) && $_POST['days']) {
				$newdate['start'] = strtotime(($_POST['days']>0?'+':'') . "{$_POST['days']} days", $newdate['start']);
				$status .= "Event moved " . ($_POST['days']>0?"{$_POST['days']} days":"back by " . abs($_POST['days']) . " days");
			}
			if (isset($_POST['minutes']) && $_POST['minutes']) {
				$newdate['start'] = strtotime(($_POST['minutes']>0?'+':'') . "{$_POST['minutes']} minutes", $newdate['start']);
				if (!$status)
					$status .= "Event moved " . ($_POST['minutes']>0?"{$_POST['days']} minutes":"back by " . abs($_POST['days']) ." minutes");
			}
			if ($event['start'] != $newdate['start']) // Time has been adjusted - adjust ending too
				$newdate['end'] = $newdate['start'] + ($event['end'] - $event['start']);

			$this->Event->Save($event['eventid'], $newdate);

			echo $this->site->JSONInfo($status);
		}
	}

	/**
	* Remove a calendar event from the system
	* @param string $_POST['event'] The event ID to remove. Note that this could have an optional 'ev-' prefix for namespacing reasons - this is removed automaticly.
	* @param string $_POST['device'] OPTIONAL ID of the device to add (if $_POST['type'] == 'device')
	* @param string $_POST['date'] The date onto which the allocation should be made
	* @param string $_POST['comment'] OPTIONAL Comment to add to the event
	*/
	function JSONRemove() {
		$this->load->model('Event');
		$this->Security->EnsureLogin();
		if (!isset($_POST['event'])) {
			echo $this->site->JSONError('Event must be specified');
		} else { // All is well
			if (substr($_POST['event'], 0, 3) == 'ev-')
				$_POST['event'] = substr($_POST['event'], 3);
			if (!$event = $this->Event->Get($_POST['event']))
				echo $this->site->JSONError('Invalid event');
			$this->Event->Delete($event['eventid']);
			echo $this->site->JSONInfo('Event removed');
		}
	}
	// }}}
}
