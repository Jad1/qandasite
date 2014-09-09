<?php class ChangePasswordController extends CI_Controller{
/*
Name: Jad El-Houssami
ID: w13651455
*/
	
	private $doChangePassword;
	function __construct()
	{
		parent::__construct();
		$this->load->model('user');
		$this->load->library('session');
		$this->load->helper('url');
		$this->doChangePassword = true;
	}
	
	public function index()
	{	
		$x = $this->session->userdata('username');
		if($x == "")
		{
			redirect('/');
		}
		else
		{
			$this->load->view('changepasswordview', array('session' => $this->session->userdata));
		//		print "<pre>"; print_r($this->session->userdata); print"</pre>";
		}
	}
	
	public function stopQuery()
	{
		$this->doChangePassword = false;
	}
	
	/*Checks that the entered password details are correct before querying the database.
	  Return a message with contents dependant on whether change was successful or not.*/
	public function changePassword()
	{
		$oldPassword = $this->input->post('oldpassword');
		$newPassword = $this->input->post('newpassword');
		$confirmPassword = $this->input->post('confirmpassword');
		$message = array('error' => '', 'other' => '');
		
		//Thses must not be empty.
		if (($oldPassword == "") || ($newPassword== "") || ($confirmPassword == "")){
			$message['error'] = $message['error'] ."Please fill in all required fields. <br />";
			$this->stopQuery();
		}
		
		//New password and confirmed password variables must match.
		if ($newPassword != $confirmPassword){
			$message['error'] = $message['error'] . "New password and confirmed password must match. <br />";
			$this->stopQuery();
		}
		
		//New password and old password must be different.
		if ($oldPassword == $newPassword)
		{
			$message['error'] = $message['error'] . "New password and old password must be different. <br />";
			$this->stopQuery();		
		}
		
		
		/*Password must be:
		At least 8 characters,contain a mixture of 
		upper and lowercase characters (at least one of each),
		a number and a non-alphanumeric character.
		See References.txt(2)
		*/
		if((strlen($newPassword) < 8) || !(preg_match("/[A-Z]/", $newPassword)) || 
			!(preg_match("/[a-z]/", $newPassword)) || !(preg_match("/[0-9]/", $newPassword)) 
			|| (ctype_alnum($newPassword))){
				$message['error'] = $message['error'] . "Password is not valid. <br />";		
				$this->stopQuery();
		}
		
		if ($this->doChangePassword)
		{
			$message['other'] = $this->user->changePassword($oldPassword, $newPassword);
		}
		
		$this->load->view('changepasswordview', $message);
	}
	public function profile()
	{
		redirect('/profilecontroller');
	}
	
	public function home()
	{
		redirect('/');
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		$this->home();
	}
}