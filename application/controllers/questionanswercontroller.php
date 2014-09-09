<?php class QuestionAnswerController extends CI_Controller{
/*
Name: Jad El-Houssami
ID: w13651455
*/
	private $questionAnswerDetails;
	private $answerDetails;
	private $doQuery;
	private $message = array('error' => '');
	private $x;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('question');
		$this->load->model('answer');
		$this->load->model('user');
		$this->load->library('session');
		$this->load->helper('url');
		$this->questionAnswerDetails = array();
		$this->answerDetails = array();
		$this->doQuery = true;
		$this->x = $this->session->userdata('username'); //If this key doesn't exist in the session cookie then the user visiting the page isn't authenticated.
	}
	
		//Set $doQuery to false. If it is false, the question won't be added to the table
	//in the database.
	public function stopQuery()
	{
		$this->doQuery = false;
	}
	
	public function index()
	{
		$this->session->unset_userdata('category');
		$title = $this->input->get('questiontitle');
		$this->session->set_userdata(array('questiontitle' => $title));
	}
	
	/*Use the title to get the question id and get question and answers
	  based on this question id. Remove title from session once page has loaded.*/
	public function display()
	{
		$this->getData($this->session->userdata('questiontitle'));
		//print "<pre>"; print_r(array('data' => $this->questionAnswerDetails, 'session' => $this->session->userdata, 'message' => $this->message)); print"</pre>";
		$this->load->view('questionanswerview', array('data' => $this->questionAnswerDetails, 'session' => $this->session->userdata, 'message' => $this->message));
	}
	
	public function getData($title)
	{
		//Get the question data and put in an array.
		$questionId = $this->question->getQuestionIds('title', $title);
		$questionId = $questionId[0]['questionid'];
		$category = $this->question->getCategory($questionId);
		$postedBy = $this->question->getQuestionPoster($questionId);
		$postedBy = $postedBy['postedby'];
		$postedOn = $this->question->getPostedDateAndTime($questionId);
		$reputation = $this->user->getReputation($postedBy);
		$content = $this->question->getQuestionContent($questionId);
		$tags = $this->question->getTags($questionId);
		$replies = $this->question->getReplies($questionId);
		
		$this->questionAnswerDetails[] = array
		(
			'questionid' => $questionId,
			'title' => $title,
			'category' => $category['category'],
			'postedby' => $postedBy,
			'postedon' => $postedOn['postedon'],
			'reputation' => $reputation,
			'content' => $content['content'],
			'tags' => $tags['tags'],
			'replies' => $replies['replies']
		);
		
		//Get all the answers the correspond to this question.
		$answerIds = $this->answer->getAnswerIds($questionId);
		
		for($i = 0; $i < count($answerIds); $i++)
		{
			foreach($answerIds[$i] as $answerId)
			{
				$nextAnswerPoster = $this->answer->getAnswerPoster($answerId);
				$nextAnswerPoster = $nextAnswerPoster['postedby'];
				$nextReputation = $this->user->getReputation($nextAnswerPoster);
				$nextPostedDateAndTime = $this->answer->getPostedDateAndTime($answerId);				
				$nextAnswerRating = $this->answer->getAnswerRating($answerId);
				$nextAnswerContent = $this->answer->getAnswerContent($answerId);
				
				$this->answerDetails[] = array
				(
					'answerid' => $answerId,
					'postedby' => $nextAnswerPoster,
					'reputation' => $nextReputation,
					'postedon' => $nextPostedDateAndTime['postedon'],
					'rating' => $nextAnswerRating['answerrating'],
					'content' => $nextAnswerContent['content']
				);
			}
		}
		$this->questionAnswerDetails['answers'] = $this->answerDetails;
	}
	
	public function addAnswer()
	{
		$title = trim($this->input->post('title'));
		$answer = trim($this->input->post('answer'));
		$questionId = intval($this->input->post('questionid'));
		$postedBy = $this->input->post('postedby');
		$replies = $this->input->post('replies');
		if(strlen($answer) < 50)
		{
			$this->message['error'] = "Question content must be at least 50 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}
		else if (strlen($answer) > 10000)
		{
			$this->message['error'] = "Question content must not exceed 10000 characters (no whiteapaces at start/end of text). <br />";			
			$this->stopQuery();
		}
		
		/*If this is empty it means the session variable for username doesn't exist
		(and by extension the user is not logged in).*/
		if($this->x == "")
		{
			redirect('/');
		}
		
		

		if($this->doQuery)
		{
			$this->message['error'] = $this->answer->addAnswer($answer, $questionId, $postedBy, $replies);
		}
			$this->getData($title);
			print "<pre>"; print_r(array('data' => $this->questionAnswerDetails, 'session' => $this->session->userdata, 'message' => $this->message)); print"</pre>";
			$this->load->view('questionanswerview', array('data' => $this->questionAnswerDetails, 'session' => $this->session->userdata, 'message' => $this->message));
	}
	
	
	public function getRelatedQuestions()
	{
	
	}
	
	public function deleteAnswer()
	{
		//See References.txt (14)
		$questionId = intval($this->input->get('questionid'));
		$answerId = intval($this->input->get('answerid'));
		$replies = intval($this->input->get('replies'));
		
		/*If this is empty it means the session variable for username doesn't exist
		(and by extension the user is not logged in).*/
		if($this->x == "")
		{
			redirect('/');
		}
		$this->answer->deleteAnswer($questionId, $answerId, $replies);	
	}
	
	public function updateAnswer()
	{
		$answerId = intval($this->input->get('answerid'));
		$content = trim($this->input->get('content'));
		$editedby = trim($this->input->get('editedby'));
		
		if (strlen($content) < 50)
		{
			$this->message['error'] = $this->message['error'] . "Question content must be at least 50 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}
		else if (strlen($content) > 10000)
		{
			$this->message['error'] = $this->message['error'] . "Question content must not exceed 10000 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}
		
		/*If this is empty it means the session variable for username doesn't exist
		(and by extension the user is not logged in).*/
		if($this->x == "")
		{
			redirect('/');
		}
		
		if($this->doQuery)
		{
			$this->message['error'] = $this->answer->updateAnswer($answerId, $content, $editedby);
		}
			$this->getData($title);
			$this->load->view('questionanswerview', array('data' => $this->questionAnswerDetails, 'session' => $this->session->userdata, 'message' => $this->message));
		
		
	}	

	public function deleteQuestion()
	{
		$questionId = intval($this->input->get('questionid')); //See References.txt (14)
		
		/*If this is empty it means the session variable for username doesn't exist
		(and by extension the user is not logged in).*/
		if($this->x == "")
		{
			redirect('/');
		}
		$this->question->deleteQuestion($questionId);
	}
	
	public function updateQuestion()
	{
		$questionId = intval($this->input->get('questionid'));
		$title = trim($this->input->get('title'));
		$oldtitle = trim($this->input->get('oldtitle'));
		$content = trim($this->input->get('content'));
		$tags = trim($this->input->get('tags'));
		$editedby = trim($this->input->get('editedby'));
		
		if (strlen($title) < 20)
		{
			$this->message['error'] = $this->message['error'] . "Title must be at least 20 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}
		else if (strlen($title) > 500)
		{
			$this->message['error'] = $this->message['error'] . "Title must not exceed 500 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}
		
		if (strlen($content) < 50)
		{
			$this->message['error'] = $this->message['error'] . "Question content must be at least 50 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}
		else if (strlen($content) > 10000)
		{
			$this->message['error'] = $this->message['error'] . "Question content must not exceed 10000 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}	
		
		if (strlen($tags) < 5)
		{
			$this->message['error'] = $this->message['error'] . "Tags must be at least 5 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}
		else if (strlen($tags) > 75)
		{
			$this->message['error'] = $this->message['error'] . "Tags must not exceed 75 characters (no whiteapaces at start/end of text). <br />";
			$this->stopQuery();
		}		
		
		/*If this is empty it means the session variable for username doesn't exist
		(and by extension the user is not logged in).*/
		if($this->x == "")
		{
			redirect('/');
		}
		
		//Commas used as tag separators, therefore they are searched when counting 
		//number of tags.
		if ((preg_match_all("/[,]/", $tags, $matches) > 4))
		{
			$this->message['error'] = $this->message['error'] . "Maximum five tags.";
			$this->stopQuery();
		}
		
		if($this->doQuery)
		{
			$this->message['error'] = $this->question->updateQuestion($questionId, $title, $oldtitle, $content, $tags, $editedby);
			$this->session->set_userdata(array('questiontitle' => $title)); //If the title was changed the title in the session cookie needs to be changed accordingly.
		}
			$this->getData($title);
			$this->load->view('questionanswerview', array('data' => $this->questionAnswerDetails, 'session' => $this->session->userdata, 'message' => $this->message));		
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/');
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
	
	/*Might be able to remove these nethods.
	public function categoryPage()
	{
		
	}

	public function home()
	{
		
	}

	public function loginSignUp()
	{
		
	}	
	*/
}