<?php class AskQuestionController extends CI_Controller{
/*
Name: Jad El-Houssami
ID: w13651455

REFERENCES:*/

	private $doAddQuestion;
	private $x;
	function __construct()
	{
		$this->doAddQuestion = true;
		parent::__construct();
		$this->load->model('question');
		$this->load->library('session');
		$this->load->helper('url');
		$this->x = $this->session->userdata('username');
	}
	
	public function index()
	{
		//Redirect user to home page if they aren't logged in.
		if($this->x == "")
		{
			redirect('/');
		}
		else
		{
			$this->load->view('askquestionview');
		}
	}
	
	//Set $doAddQuestion to false. If it is false, the question won't be added to the table
	//in the database.
	public function stopQuery()
	{
		$this->doAddQuestion = false;
	}
	
	/*Recieves question data from the form and checks for length issues. 
	  If all is OK, add question to database.
	  Return error message if something goes wrong, otherwise return nothing.*/
	public function addQuestion()
	{
		/*Trim variables which have a minimum character requirement so
		  users don't cheat by putting lots of whitespace before/after text.*/
		$category = $this->input->post('categories');
		$title = trim($this->input->post('title'));
		$content = trim($this->input->post('question'));
		$tags = trim($this->input->post('tags'));
		$message = array('error' => '', 'other' => '');
		
		
		if ($category == "")
		{
			$message['error'] = $message['error'] . "Please select a category. <br />";
			$this->stopQuery();			
		}
		if (strlen($title) < 20)
		{
			$message['error'] = $message['error'] . "Title must be at least 20 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}
		else if (strlen($title) > 500)
		{
			$message['error'] = $message['error'] . "Title must not exceed 500 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}
		
		if (strlen($content) < 50)
		{
			$message['error'] = $message['error'] . "Question content must be at least 50 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}
		else if (strlen($content) > 10000)
		{
			$message['error'] = $message['error'] . "Question content must not exceed 10000 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}	
		
		if (strlen($tags) < 5)
		{
			$message['error'] = $message['error'] . "Tags must be at least 5 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}
		else if (strlen($tags) > 75)
		{
			$message['error'] = $message['error'] . "Tags must not exceed 75 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}		
		
		//Commas used as tag separators, therefore they are searched when counting 
		//number of tags.
		if ((preg_match_all("/[,]/", $tags, $matches) > 4))
		{
			$message['error'] = $message['error'] . "Maximum five tags.";
			$this->stopQuery();
		}
		
		if($this->x == "")
		{
			redirect('/');
		}
		
		if ($this->doAddQuestion)
		{
			$message['other'] = $this->question->addQuestion($category, $title, $content, $this->session->userdata['username'], $tags);
		}
		
		$this->load->view('askquestionview', $message);
			
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
		else if(($keyCode == 8) && ($charsLeft != 10000))
		{
			$charsLeft++;
		}
		print $charsLeft;
	}
	
	public function home()
	{
		redirect('/');
	}
	
	public function profile()
	{
		
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		$this->home();
	}
}