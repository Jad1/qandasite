<?php class Answer extends CI_Model{
/*
Name: Jad El-Houssami
ID: w13651455
*/

	private $currentTime;
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->currentTime = date('o-m-d H:i:s'); //See References.txt(1)
	}
	
	public function addAnswer($answer, $questionId, $postedBy, $replies)
	{
		$replies++;
		$answerDetails = array('questionid' => $questionId, 'content' => $answer, 'postedby' => $postedBy, 'postedon' => $this->currentTime, 'lasteditedby' => '', 'lasteditedon' => '','answerrating' => 0);
		
		$this->db->insert('answer', $answerDetails);
		
		//Update corresponding qurstion record to increment replies value by one.
		$this->db->where('questionid', $questionId);
		$this->db->update('question', array('replies' => $replies));
	}
	
	public function getAnswerIds($questionId)
	{
		
		$this->db->select('answerid')->from('answer')->where(array('questionid' => $questionId));
		$query = $this->db->get();
		return $query->result_array();  //Return the whole array of associative arrays.	
	}
	
	public function getAnswerRating($answerId)
	{
		$this->db->where('answerid', $answerId);
		$this->db->select('answerrating');
		$query = $this->db->get('answer');
		$resultSet = $query->result_array(); 
		return $resultSet[0]; //$resultSet is an array of associative arrays so return just the single inner array.		
	}
	
	public function getPostedDateAndTime($answerId)
	{
		$this->db->where('answerid', $answerId);
		$this->db->select('postedon');
		$query = $this->db->get('answer');
		$resultSet = $query->result_array(); 
		return $resultSet[0]; //$resultSet is an array of associative arrays so return just the single inner array.	
	}
	
	public function getAnswerPoster($answerId)
	{
		$this->db->where('answerid', $answerId);
		$this->db->select('postedby');
		$query = $this->db->get('answer');
		$resultSet = $query->result_array(); 
		return $resultSet[0]; //$resultSet is an array of associative arrays so return just the single inner array.	
	}
	
	public function getLastEditor()
	{
		
	}
	
	public function getLastEditedTime()
	{
		
	}
	
	public function getAnswerContent($answerId)
	{
		$this->db->where('answerid', $answerId);
		$this->db->select('content');
		$query = $this->db->get('answer');
		$resultSet = $query->result_array(); 
		return $resultSet[0]; //$resultSet is an array of associative arrays so return just the single inner array.	
	}
	
	public function updateAnswer($answerId, $content, $editedby)
	{
		$answerDetails = array('content' => $content, 'lasteditedby' => $editedby, 'lasteditedon' => $this->currentTime);
		$this->db->where('answerid', $answerId);
		$this->db->update('answer', $answerDetails);
		return "Answer has been updated.";
	}
	
	public function deleteAnswer($questionId, $answerId, $replies)
	{
		/*Delete answer, then decrement replies value for corresponding 
		question by one.*/
		$replies-=1;
		$this->db->delete('answer', array('answerid' => $answerId));
		
		$this->db->where('questionid', $questionId);
		$this->db->update('question', array('replies' => $replies));
	}
	
}