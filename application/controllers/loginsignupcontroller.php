<?php class LoginSignUpController extends CI_Controller{
/*
Name: Jad El-Houssami
ID: w13651455
*/
	
	private $doQuery;
	private $signUpMessage;
	private $loginMessage;
	function __construct()
	{
		parent::__construct();
		$this->load->model('user');
		$this->load->library('session');
		$this->load->helper('url');
		$this->doQuery = true;
		$this->loginMessage = array('loginerror' => '', 'loginother' => '');
		$this->signUpMessage = array('signuperror' => '', 'signupother' => '');
	}
	
	public function index()
	{
		$this->load->view('loginsignupview');
	}
	
	/* Sets the $doQuery variable to false which is used as a check for whether or not
	   the data should be sent to the database to be queried.
	*/
	public function stopQuery()
	{
		$this->doQuery = false;
	}	
	
	/*Checks that the user has supplied a username and password 
	  before passing them to the model to be queried.
	*/
	public function checkCredentials()
	{
		$username = $this->input->post('loginusername');
		$password = $this->input->post('loginpassword');
		
		if (($username == "") || ($password == ""))
		{
			$this->loginMessage['loginerror'] = "Username and/or password have not been filled in.";
			$this->stopQuery();
		}
		
		if ($this->doQuery)
		{
			if(!($this->user->checkCredentials($username, $password)))
			{
				$this->loginMessage['loginother'] = "Username and/or password is/are not valid.";
				$this->load->view('loginsignupview', $this->loginMessage);
			}
			else
			{
				redirect('/');
			}
		}
		else
		{
			$this->load->view('loginsignupview', $this->loginMessage);
		}
	}
	
	//Gets the user data from the form and passes it to the user model.
	public function addUser()
	{
		$newUsername = trim($this->input->post('newusername'));
		$newPassword = trim($this->input->post('newpassword'));
		$emailAddress = trim($this->input->post('emailaddress'));
		$language = $this->input->post('languages');
		$browser = $this->input->post('browsers');
		$currentProjects = trim($this->input->post('currentprojects'));
		
		if (($newUsername == "") || ($newPassword == "") || ($emailAddress == ""))
		{
			$this->signUpMessage['signuperror'] = $this->signUpMessage['signuperror'] . "Please fill in all required fields. <br />";
			$this->stopQuery();
		}
		
		if (strlen($newUsername) > 50)
		{
			$this->signUpMessage['signuperror'] = $this->signUpMessage['signuperror'] . "Username must not exceed 50 characters. <br />";
			$this->stopQuery();
		}			
		
		if (strlen($emailAddress) > 50)
		{
		
			$this->signUpMessage['signuperror'] = $this->signUpMessage['signuperror'] . "E-mail address must not exceed 50 characters. <br />";
			$this->stopQuery();
		}
		
		if (strlen($currentProjects) > 500)
		{
			$this->signUpMessage['signuperror'] = $this->signUpMessage['signuperror'] . "Current projects must not exceed 500 characters. <br />";
			$this->stopQuery();
		}
		
		
		/*Password must be:
		At least 8 characters, contain a mixture of 
		upper and lowercase characters (at least one of each),
		a number and a non-alphanumeric character.
		See References.txt(2), (7)
		*/
		if((strlen($newPassword) < 8) || !(preg_match("/[A-Z]/", $newPassword)) || 
		!(preg_match("/[a-z]/", $newPassword)) || !(preg_match("/[0-9]/", $newPassword)) 
		|| (ctype_alnum($newPassword)))
		{
			$this->signUpMessage['signuperror'] = $this->signUpMessage['signuperror'] .  "Password is not valid. <br />";
			$this->stopQuery();
		}
		
		//Query the database if no input errors are found.
		if ($this->doQuery)
		{
			$this->signUpMessage['signupother'] = $this->user->addUser($newUsername, $newPassword, $emailAddress, $language, $browser, $currentProjects);
		}
			
		$this->load->view('loginsignupview', $this->signUpMessage);
	}
		
		//Update character counter in view.
	public function changeCharsLeft()
	{
		$charsLeft = intval($this->input->post('chars'));
		$keyCode = intval($this->input->post('keycode')); //See References.txt (14)
		
		//Only subtract from the character counter if a character key was pressed.
		//See Referneces.txt(13)
		if ((($keyCode >= 48) && ($keyCode <= 90)) || (($keyCode >= 96) && ($keyCode <= 111)) || ($keyCode >= 188))
		{
			$charsLeft--;
		}
		
		//See Referneces.txt(13)
		if(($keyCode == 8) && ($charsLeft != 500))
		{
			$charsLeft++;
		}
		print $charsLeft;
	}
	
	public function home()
	{
	
	}
}