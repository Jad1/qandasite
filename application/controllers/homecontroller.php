<?php class HomeController extends CI_Controller{
/*
Name: Jad El-Houssami
ID: w13651455

*/

	private $todaysDate;
	private $questionDetails;
	function __construct()
	{
		parent::__construct();
		$this->load->model('question');
		$this->load->library('session');
		$this->load->helper('url');
		$this->todaysDate = date('o-m-d'); //Construct today's date. (See References.txt (1))
		$this->questionDetails = array();
	}
	
	/*First gets all the required data to display today's posted question.
	Then displays the view, passing the question data for it to be displayed.
	*/
	public function index()
	{
		$this->session->unset_userdata('category');
		$this->session->unset_userdata('profile');
		$this->session->unset_userdata('questiontitle');
		$questionIds = $this->question->getQuestionIds('postedon', $this->todaysDate);
		
		for($i = 0; $i < count($questionIds); $i++)
		{
			foreach($questionIds[$i] as $questionId)
			{
				$nextTitleValue = $this->question->getTitle($questionId);
				$nextQuestionPosterValue = $this->question->getQuestionPoster($questionId);
				$nextDateAndTimeValue = $this->question->getPostedDateAndTime($questionId);
				$nextRepliesValue = $this->question->getReplies($questionId);
				$nextTagsValue = $this->question->getTags($questionId);		
				$this->questionDetails[] = array
				(
					'title' => $nextTitleValue['title'],
					'postedby' => $nextQuestionPosterValue['postedby'],
					'postedon' => $nextDateAndTimeValue['postedon'],
					'replies'  => $nextRepliesValue['replies'],
					'tags'  => $nextTagsValue['tags']
				);
			}
		}
		//print "<pre>"; print_r($this->session->userdata); print "</pre>";
		$this->load->view('homeview', array('questions' => $this->questionDetails, 'session' => $this->session->userdata));
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/');
	}
}