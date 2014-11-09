<?
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Generic email functionality model
* NOTE: See config/constands#EMAIL_OVERRIDE
*/
class Email extends CI_Model {
	/**
	* Send a pre-packaged email to a user
	* This function scoops the template from the pages table (e.g. pages.code = 'emails/user/signup')
	* @param string $code The lookup code to query the pages table for to retrieve the content
	* @param int $userid The user id to send the mail to. Use '0' for admin
	* @param array $vars Local vars to replace in the template
	*/
	function Send($code, $userid, $vars) {
		$this->load->model('Page');
		$this->load->model('User');
		if ((!$page = $this->Page->GetByCode($code)) && $page != 'core/panic') {
			$this->site->Panic("Cant find non-existant page code '$code' to send via email to user id #$userid");
			return;
		}
		$users = ($userid == 0) ? $this->User->GetAll(array('role' => 'admin')) : array($this->User->Get($userid)); // Get a list of who to send to

		foreach ($users as $user) {
			$vars['user.name'] = $this->User->GetName($user['userid']);
			$vars['user.email'] = $user['email'];

			$vars['link.site'] = SITE_URL;
			$vars['link.login'] = SITE_URL . '/login';

			$vars['site.title'] = SITE_TITLE;
			$vars['site.name'] = SITE_TITLE;

			$title = $this->Page->Replace($page['title'], $vars);
			$body = $this->Page->Replace($page['text'], $vars);
			$this->Dispatch($page['type'], $user['userid'], $title, $body);
			$this->Log->Add('email', "Email $code dispatched to {$user['email']}", $user['userid']);
		}
		return TRUE;
	}

	/**
	* Send the actual HTML email to a user
	* @param string $format The format of the mail to send ('html' or 'text')
	* @param array $to The email address to send to
	* @param string $subject The email subject to use
	* @param string $html The HTML blob to send
	*/
	function Dispatch($format, $to, $subject, $body) {
		$headers = '';
		if ($format == 'html')
			$headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\n";
		if (EMAIL_OVERRIDE) {
			$headers .= "To: " . EMAIL_OVERRIDE_NAME . " <" . EMAIL_OVERRIDE . ">\r\n";
		} else
			$headers .= "To: " . $to . "\r\n";
		$headers .= 'From: ' . EMAIL_FROM_NAME . ' <' . EMAIL_FROM . ">\r\n";
		mail($to, $subject, $body, $headers);
	}
}
?>
