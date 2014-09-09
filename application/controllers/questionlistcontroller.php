<?php class QuestionListController extends CI_Controller{
/*
Name: Jad El-Houssami
ID: w13651455

*/

	private $questionDetails;
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('question');
		$this->questionDetails = array();
	}
	
	//Add the category to the session variable.
	public function index()
	{	
		$category = $this->input->get('category');
		$this->session->set_userdata(array('category' => $category));
	}
	
	/*Use the category to get the relevant question details.*/
	public function display()
	{
		$questionIds = $this->question->getQuestionIds('category', $this->session->userdata('category'));
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
		$this->load->view('questionlistview', array('questions' => $this->questionDetails, 'session' => $this->session->userdata));		
	}
}