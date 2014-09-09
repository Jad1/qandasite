<?php class Search extends CI_Controller{
/*
Name: Jad El-Houssami
ID: w13651455*/
	
	private $questionDetails;
	//error_log("above constructor");
	function __construct()
	{
		parent::__construct();
		$this->load->model('question');
		$this->questionDetails = array();
	}
	
	public function index()
	{
		
	}
	
	/*Search for questions given some keywords.
	  return the results by the search (if any) */
	public function questions()
	{
		$keywords = ($this->input->get('keywords'));
		$tags = ($this->input->get('tags'));
		$user = ($this->input->get('user'));
		
		error_log("Keywords: " .$keywords . "Tags: " . $tags . "Users: " . $user);
		
		/*$searchTerms = array('keywords' => explode(" ", $keywords),
		'tags' => explode(" ", $tags),'user' => $user);*/
		
		//$questionIds = $this->question->searchQuestions($searchTerms);
		/*Adding in the whitespace if no search term entered
		because when searched it will return nothing but an empty
		variable seems to return everything.*/
		$keywordsarray = explode(" ", $keywords);		
		$tagsarray = explode(" ", $tags);
		$questionIds = $this->question->searchQuestions($keywordsarray, $tagsarray, $user);
		error_log('After searchQuestions(): ' . gettype($questionIds[0]));
		/*if (strlen($keywords) == 0)
		{
			error_log("no keywords");
			$tagsarray = explode(" ", $tags);
			$questionIds = $this->question->searchTags($tagsarray);	
		}	
		else if(strlen($tags) == 0)
		{
			error_log("no tags");
			$keywordsarray = explode(" ", $keywords);
			error_log("array size: " . count($keywordsarray));
			$questionIds = $this->question->searchQuestions($keywordsarray);
		}
		else
		{
			error_log("everything here");
			$keywordsarray = explode(" ", $keywords);
			$tagssarray = explode(" ", $tags);
			$array = array_merge($keywordsarray, $tagssarray);
			$questionIds = $this->question->searchQuestions($array);
		}*/
			foreach($questionIds as $questionId)
			{
				error_log('id: ' . $questionId);
				$nextTitleValue = $this->question->getTitle($questionId);
				$nextQuestionPosterValue = $this->question->getQuestionPoster($questionId);
				$nextDateAndTimeValue = $this->question->getPostedDateAndTime($questionId);
				$nextRepliesValue = $this->question->getReplies($questionId);
				$nextTagsValue = $this->question->getTags($questionId);		
				error_log('title: ' . gettype($nextTitleValue['title']) . $nextTitleValue['title']);
				error_log('postedby: ' . gettype($nextQuestionPosterValue['postedby']) . $nextQuestionPosterValue['postedby']);
				error_log('postedon: ' . gettype($nextDateAndTimeValue['postedon']) . $nextDateAndTimeValue['postedon']);
				error_log('replies: ' . gettype($nextRepliesValue['replies']) . $nextRepliesValue['replies']);
				error_log('tags: ' . gettype($nextTagsValue['tags']) . $nextTagsValue['tags']);
				$this->questionDetails[] = array
				(
					'title' => $nextTitleValue['title'],
					'postedby' => $nextQuestionPosterValue['postedby'],
					'postedon' => $nextDateAndTimeValue['postedon'],
					'replies'  => $nextRepliesValue['replies'],
					'tags'  => $nextTagsValue['tags']
				);
			}
		error_log('ok here!');
		$jsonData = json_encode($this->questionDetails);
		error_log('data: ' . $jsonData);
		$this->output->set_content_type('text/json');
		print $jsonData;
	}
	
/* 0 if the values are equal, -1 if a < b or 1 if a > b.
	   
	 left < right for ascending
	 left > right for descending 
	 i.e. '>' yields:
	 most recent to least recent for dates.
	 biggest to smallest for numbers.
	 Z-A for words.	   
	*/
}