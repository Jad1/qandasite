<?php class user extends CI_Model{
/*
Name: Jad El-Houssami
ID: w13651455
*/

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}
		
	public function saltPassword($password)
	{
		$uniqueSalt = "£%^:@*";
		$password = sha1($password);
		$password = sha1($password . $uniqueSalt);
		return $password;
	}	
	
	public function setSessionUserdata($username, $flag, $userId, $reputation)
	{
		$newSessionData = array('username' => $username, 'flag' => $flag, 'userid' => $userId, 'reputation' => $reputation);
		$this->session->set_userdata($newSessionData);	
	}
	
	/*Checks if the username exists on the database.
	  return true if the username exists or false if it does not.*/
	public function checkIfUsernameExists($username)
	{
		$query = $this->db->get_where('user', array('username' => $username));
		if ($query->num_rows() != 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function addUser($newUsername, $newPassword, $emailAddress, $language, $browser, $currentProjects)
	{
		//Check username doesn't already exist.
		//If the query returns a row back then the username exists!
		
		$userDetails = array('username' => $newUsername, 'password' => $newPassword,
		'emailaddress' => $emailAddress, 'favwebdevlanguage' => $language,
		'favbrowser' => $browser, 'currentprojects' => $currentProjects, 
		'reputation' => 10, 'flag' => 1);
		
		if ($this->checkIfUsernameExists($userDetails['username']))
		{
			return "Username $newUername already exists.";
		}
		//Hash and salt password and add the user
		else
		{
			$userDetails['password'] = $this->saltPassword($userDetails['password']);
			$this->db->insert('user', $userDetails);
			return "Sucessfully signed up $newUsername";			
		}
	}
	
	public function getUserId($username)
	{
		$this->db->select('userid')->from('user')->where(array('username' => $username));
		$query = $this->db->get();
	//	error_log($this->db->last_query());
		$query = $query->row(); //Only expecting one result (usernames should be unique) so a single object is OK.
		return $query->userid; //Return property of object.					
	}
	
	public function getUsername($username)
	{
		$this->db->select('username')->from('user')->where(array('username' => $username));
		$query = $this->db->get();
	//	error_log($this->db->last_query());
		$query = $query->row(); //Only expecting one result (usernames should be unique) so a single object is OK.
		return $query->username; //Return property of object.				
	}
	
	public function getPassword()
	{
		
	}
	
	public function getEmailAddress($username)
	{
		$this->db->select('emailaddress')->from('user')->where(array('username' => $username));
		$query = $this->db->get();
	//	error_log($this->db->last_query());
		$query = $query->row(); //Only expecting one result (usernames should be unique) so a single object is OK.
		return $query->emailaddress; //Return property of object.		
	}	
	
	public function getFavWebDevLanguage($username)
	{
		$this->db->select('favwebdevlanguage')->from('user')->where(array('username' => $username));
		$query = $this->db->get();
	//	error_log($this->db->last_query());
		$query = $query->row(); //Only expecting one result (usernames should be unique) so a single object is OK.
		return $query->favwebdevlanguage; //Return property of object.		
	}
	
	public function getFavBrowser($username)
	{
		$this->db->select('favbrowser')->from('user')->where(array('username' => $username));
		$query = $this->db->get();
		$query = $query->row(); //Only expecting one result (usernames should be unique) so a single object is OK.
		return $query->favbrowser; //Return property of object.	
	}
	
	public function getCurrentProjects($username)
	{
		$this->db->select('currentprojects')->from('user')->where(array('username' => $username));
		$query = $this->db->get();
	//	error_log($this->db->last_query());
		$query = $query->row(); //Only expecting one result (usernames should be unique) so a single object is OK.
		return $query->currentprojects; //Return property of object.
	}
	
	public function getReputation($username)
	{
		$this->db->select('reputation')->from('user')->where(array('username' => $username));
		$query = $this->db->get();
	//	error_log($this->db->last_query());
		$query = $query->row(); //Only expecting one result (usernames should be unique) so a single object is OK.
		return $query->reputation; //Return property of object.	
	}
	
	public function getFlag($username)
	{
		$this->db->select('flag')->from('user')->where(array('username' => $username));
		$query = $this->db->get();
		$query = $query->row(); //Only expecting one result (usernames should be unique) so a single object is OK.
		return $query->flag; //Return property of object.
		
	}
	
	/*Checks that the given username and password match to a user in the database.
	  @param $username the entered username.
	  @param $password the entered password.
	  return a true if a match is found or false if a matahc is not found. (may remove success message)
	*/
	public function checkCredentials($username, $password)
	{
		//Must salt the password before performing the check.
		$password = $this->saltPassword($password);
		$credentials = array('username' => $username, 'password' => $password);
		
		$query = $this->db->get_where('user', $credentials);
		if ($query->num_rows() != 1)
		{
			return false;
		}
		else
		{
			//Add new data to the session array: user id, username and flag.
			$userId = $this->getUserId($username);
			$flag = $this->getFlag($username);
			$reputation = $this->getReputation($username);
			$this->setSessionUserdata($username, $flag, $userId, $reputation);
			return true;
		}
	}
	
	/*Change the user's password
	  @param $oldPassword the old password of the user.
	  @param $newPassword the new password of the user.
	  Return a message depending on whether the change was successful or not.
	  */
	public function changePassword($oldPassword, $newPassword)
	{
		$username= $this->session->userdata('username');
		$whereClause = array('username' => $username, 'password' => $oldPassword);
			
		//Hash old password and check that user 
		//wrote the right old password (should be a single matching row).
		$whereClause['password'] = $this->saltPassword($whereClause['password']);
		$query = $this->db->get_where('user', $whereClause);

		if ($query->num_rows() != 1)
		{
			return 'Old password is not correct';
		}
		else
		{
			//Hash and salt new password and update the user's password.
			$newPassword = $this->saltPassword($newPassword);
			$this->db->where(array('username'=> $username));
			$this->db->update('user', array('password' => $newPassword));
			return 'Password has been changed.';
		}
	}
	
	public function updateProfile($username, $emailAddress, $reputation, $flag, $language, $browser, $currentProjects, $oldusername, $visitingUserId)
	{
		$userDetails = array('username' => $username,'emailaddress' => $emailAddress, 
		'favwebdevlanguage' => $language,'favbrowser' => $browser, 
		'currentprojects' => $currentProjects, 'reputation' => $reputation, 'flag' => $flag);
		/*If the new username isn't equal to the old username 
		then check the new username hasn't already been used.
		(The change may mean that the new username conflicts with an existing one)*/
		if(!($userDetails['username'] == $oldusername))
		{
			if ($this->checkIfUsernameExists($userDetails['username']))
			{
				return "Username $username already exists.";
			}
		}
		//else
	//	{	
			$this->db->where(array('username' => $oldusername));
			$this->db->update('user', $userDetails);
			$userId = $this->user->getUserId($username);
			$reputation = $this->getReputation($username);
			//Only update the session if  the profile was updated by the profile owner.
			if ($userId == $visitingUserId)
			{
				$this->setSessionUserdata($username, $flag, $userId, $reputation);
			}
			error_log($this->db->last_query());
			return "Profile has been updated.";
	//	}
	}
	
	/*Add the user to the login database. This will be used when accessing something 
	  restricted only to certain people.
	  Note: I may have to add a new 'flag' column to this table if the flag is needed (not sure yet)*/
	  
	  //REMOVE THESE METHODS AND THE LOGIN TABLE ONCE PERMISSIONS HAVE BEEN IMPLEMENTED.
	public function createLogin($sessionId, $username)
	{
		if ($this->isLoggedIn($username))
		{
			$loginData = array('sessionid' => $sessionId, 'username' => $username);
			$this->db->insert('login', $loginData);
		}
		else
		{
			
		}
		
	}
	
	public function deleteLogin()
	{
		
	}
	public function isLoggedIn($username)
	{
		$query = $this->db->get_where('login', array('username' => $username));

		if ($query->num_rows() != 1)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
}