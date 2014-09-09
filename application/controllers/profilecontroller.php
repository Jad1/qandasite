<?php class ProfileController extends CI_Controller{
/*
Name: Jad El-Houssami
ID: w13651455
*/

	private $doQuery;
	private $userDetails;
	private $updateProfileMessage;
	function __construct()
	{
		parent::__construct();
		$this->load->model('user');
		$this->load->library('session');
		$this->load->helper('url');
		$this->userDetails = array();
		$this->doQuery = true;
		$this->updateProfileMessage = array('updateprofileerror' => '', 'updateprofileother' => '');
	}
	
	/*Add the username to the session variable.*/
	public function index()
	{
		$this->session->unset_userdata('category');
		$username = $this->input->get('name');
		$this->session->set_userdata(array('profile' => $username));
	}
	
	/*Use the username to get the relevant user details.
	Once the page has loaded remove the username from the session
	because it is no longer needed.*/
	public function display()
	{
		$this->getData($this->session->userdata('profile'));
		$this->load->view('profileview', array('userdetails' => $this->userDetails, 'session' => $this->session->userdata));
	//	print "<pre>"; print_r($this->session->userdata); print "</pre>";
		
	}
	public function getData($username)
	{
		$this->userDetails[] = array
		(
			'userid' => $this->user->getUserId($username),
			'username' => $this->user->getUsername($username),
			'emailaddress' => $this->user->getEmailAddress($username),
			'reputation' => $this->user->getReputation($username),
			'flag' => $this->user->getFlag($username),
			'favwebdevlanguage' => $this->user->getFavWebDevLanguage($username),
			'favbrowser' => $this->user->getFavBrowser($username),
			'currentprojects' => $this->user->getCurrentProjects($username)
		);
		
		error_log('current projects is '. strlen($this->userDetails[0]['currentprojects']) . 'characters long');
	}
	public function stopQuery()
	{
		$this->doQuery = false;
	}
	
	
	public function updateProfile()
	{
		$username = trim($this->input->post('username'));
		$emailAddress = trim($this->input->post('emailaddress'));
		$reputation = trim($this->input->post('reputation'));
		$flag = trim($this->input->post('flag'));
		$favWebDevLanguage = trim($this->input->post('languages'));
		$favBrowser = trim($this->input->post('browsers'));
		$currentProjects = trim($this->input->post('currentprojects'));
		$visitingUserId = trim($this->input->post('visitinguserid'));
		$visitingUsername = trim($this->input->post('visitingusername'));
		$visitingUserFlag = intval($this->input->post('visitinguserflag'));
		$oldusername = trim($this->input->post('oldusername'));
		$oldflag = intval($this->input->post('oldflag'));
		$oldreputation = intval($this->input->post('oldreputation'));
		error_log('my flag: ' . $visitingUserFlag);
		error_log('visiting username: ' . $visitingUsername . 'old username: ' . $oldusername);
		//These fields must not be empty.
		if (($username == "") || ($emailAddress == "") || ($reputation == "") || ($flag == ""))
		{
			$this->updateProfileMessage['updateprofileerror'] = "A required field has not been filled in.";
			$this->stopQuery();
		}
		
		//Check the lengths of these fields.
		if (strlen($username) > 50)
		{
			$this->updateProfileMessage['updateprofileerror'] = "The 'username' field must not exceed 50 characters.";
			$this->stopQuery();			
		}
		
		if (strlen($emailAddress) > 50)
		{
			$this->updateProfileMessage['updateprofileerror'] = "The 'E-mail address' field must not exceed 50 characters.";
			$this->stopQuery();			
		}
		
		if (strlen($currentProjects) > 500)
		{
			$this->updateProfileMessage['updateprofileerror'] = "The 'current projects' field must not exceed 500 characters.";
			$this->stopQuery();
		}
		
		/*Check if submitted value of reputation field contains non-numeric characters.
		See References.txt (8)*/
		if (!(ctype_digit($reputation)))
		{
			$this->updateProfileMessage['updateprofileerror'] = "Reputation must be an integer.";
			$this->stopQuery();			
		}
		
		//PERMISSIONS
		switch($visitingUserFlag)
		{
			case 0: //If the user is not logged in they should not update the profile.
				$this->home();
				break;
			case 1: //check if the user is viewing their own profile.
				if($visitingUsername == $oldusername)
				{
					//Regular user shouldn't be able to update their own name, flag or reputation.
					if(($username != $oldusername) || ($flag != $oldflag) || ($reputation != $oldreputation))
					{
						$this->home();
						break;
					}
				}
				else //If the user isn't viewing their own profile they shouldn't be able to update anything.
				{
					$this->home();
					break;	
				}
			case 2:
				if ($oldflag == 1)
				{	//Moderator cannot change a regular user's username or flag.
					if(($username != $oldusername) || ($flag != $oldflag))
					{
						$this->home();
						break;	
					}
				}
				else
				{
					//check if the user is viewing their own profile.
					if($visitingUsername == $oldusername)
					{
						//Regular user shouldn't be able to update their own name, flag or reputation.
						if(($username != $oldusername) || ($flag != $oldflag) || ($reputation != $oldreputation))
						{
							$this->home();
							break;
						}
					}
					else //If the user isn't viewing their own profile they shouldn't be able to update anything.
					{
						$this->home();
						break;	
					}
				}
				case 3:
					//If viewing profile of other administrator the name should not be editable.
					if (($oldflag == 3) && ($visitingUsername != $oldusername))
					{
						if ($username != $oldusername)
						{
							$this->home();
							break;							
						}
					}
				default:
					break;
		}
		
		if ($this->doQuery)
			{
				$this->updateProfileMessage['updateprofileother'] = $this->user->updateProfile($username, $emailAddress, $reputation, $flag, $favWebDevLanguage, $favBrowser, $currentProjects, $oldusername, $visitingUserId);
			}
			
		$this->getData($username);
		$this->load->view('profileview', array('userdetails' => $this->userDetails, 'session' => $this->session->userdata, 'messages' => $this->updateProfileMessage));
	}
	
	/*Remove the username of the currently 
	  viewed profile from the session.*/
	public function removeSessionData()
	{
		$this->session->unset_userdata('profile');
	}
	public function home()
	{
		$this->removeSessionData();
		redirect('/');
	}	
	
	public function loginSignUp()
	{
		$this->removeSessionData();
		redriect('/loginsignupcontroller');
	}	
	
	public function logout()
	{
		$this->session->sess_destroy();
		$this->home();
	}
	
		//Update character counter in view.
	public function changeCharsLeft()
	{
		//See References.txt (14)
		$charsLeft = intval($this->input->post('chars'));
		$keyCode = intval($this->input->post('keycode')); 
		
		//Only subtract from the character counter if a character key was pressed.
		//See Referneces.txt(13)
		if ((($keyCode >= 48) && ($keyCode <= 90)) || (($keyCode >= 96) && ($keyCode <= 111)) || ($keyCode >= 188))
		{
			$charsLeft--;
		}
		
		//See Referneces.txt(13)
		if(($keyCode == 8) && ($charsLeft != 10000))
		{
			$charsLeft++;
		}
		print $charsLeft;
	}
	
}