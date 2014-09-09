<?php class Question extends CI_Model{
/*
Name: Jad El-Houssami
ID: w13651455
*/

	private $currentTime;
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->currentTime = date('o-m-d H:i:s');
	}
	
	/*Adds the new question to the questions table.
	@param $category the category of the question.
	@param $title the title of the question.
	@param $content the content of the question.
	@param $postedBy the user who posted the question.
	@param $tags the tags associated with the question.
	*/
	public function addQuestion($category, $title, $content, $postedBy, $tags)
	{
		$questionDetails = array('category' => $category, 'title' => $title, 
		'content' => $content, 'postedby' => $postedBy, 'postedon' => $this->currentTime, 
		'lasteditedby' => '', 'lasteditedon' => '', 'tags' => $tags, 'replies' => 0);
		if ($this->checkIfTitleExists($questionDetails['title']))
		{
			return "A question with the title \"$title\" already exists.";
		}
		$this->db->insert('question', $questionDetails);
	}
	
	public function checkIfTitleExists($title)
	{
		$query = $this->db->get_where('question', array('title' => $title));
		if ($query->num_rows() != 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function getCategory($questionId)
	{
		//Selects the title value that corresponds to the $questionId variable.
		$this->db->where('questionid', $questionId);
		$this->db->select('category');
		$query = $this->db->get('question');
		$resultSet = $query->result_array(); 
		return $resultSet[0]; //$resultSet is an array of associative arrays so return just the single inner array.		
	}	
		
	/*Select all question ids from the question table where
	a given column contains a string which contains a given pattern.
	@param $value the value to look for.
	return an array of question ids or null if nothing is found.*/
	public function getQuestionIds($column, $value)
	{
		$this->db->select('questionid')->from('question')->like(array($column => $value));
		$query = $this->db->get();
		return $query->result_array();  //Return the whole array of associative arrays.
	}
	
	/*Gets the title that corresponds to a given question ID.
	@param $questionId the question ID that the title should correspond to.
	return the title.*/
	public function getTitle($questionId)
	{
		//Selects the title value that corresponds to the $questionId variable.
		$this->db->where('questionid', $questionId);
		$this->db->select('title');
		$query = $this->db->get('question');
		$resultSet = $query->result_array(); 
		return $resultSet[0]; //$resultSet is an array of associative arrays so return just the single inner array.
	}
	
	/*Gets the question poster's username that corresponds to a given question ID.
	@param $questionId the question ID that the title should correspond to.
	return the username.*/	
	public function getQuestionPoster($questionId)
	{
		//Selects the postedby value that corresponds to the $questionId variable.
		$this->db->where('questionid', $questionId);
		$this->db->select('postedby');
		$query = $this->db->get('question');
		$resultSet = $query->result_array(); 
		return $resultSet[0]; //$resultSet is an array of associative arrays so return just the single inner array.
	}

	/*Gets the date and time that correspond to a given question ID.
	@param $questionId the question ID that the title should correspond to.
	return the date and time.*/		
	public function getPostedDateAndTime($questionId)
	{
		//Selects the postedon value that corresponds to the $questionId variable.
		$this->db->where('questionid', $questionId);
		$this->db->select('postedon');
		$query = $this->db->get('question');
		$resultSet = $query->result_array(); 
		return $resultSet[0]; //$resultSet is an array of associative arrays so return just the single inner array.	
	}

	public function getLastEditor()
	{
		
	}

	public function getLastEditedTime()
	{
		
	}

	/*Gets the question tags that corresponds to a given question ID.
	@param $questionId the question ID that the title should correspond to.
	return the tags.*/	
	public function getTags($questionId)
	{
		//Selects the tags value that corresponds to the $questionId variable.
		$this->db->where('questionid', $questionId);
		$this->db->select('tags');
		$query = $this->db->get('question');
		$resultSet = $query->result_array(); 
		return $resultSet[0]; //$resultSet is an array of arrays so return just the single inner array.	
	}

	/*Gets the question replies value that corresponds to a given question ID.
	@param $questionId the question ID that the title should correspond to.
	return the replies value.*/		
	public function getReplies($questionId)
	{
		//Selects the replies value that corresponds to the $questionId variable.
		$this->db->where('questionid', $questionId);
		$this->db->select('replies');
		$query = $this->db->get('question');
		$resultSet = $query->result_array(); 
		return $resultSet[0]; //$resultSet is an array of arrays so return just the single inner array.
	}		
	
	public function getQuestionContent($questionId)
	{
		//Selects the content that corresponds to the $questionId variable.
		$this->db->where('questionid', $questionId);
		$this->db->select('content');
		$query = $this->db->get('question');
		$resultSet = $query->result_array(); 
		return $resultSet[0]; //$resultSet is an array of arrays so return just the single inner array.		
	}
	
	/*Searches table for questions based on a keyword or tag query.
	  return the question IDs that correspond to the search.*/
	public function searchQuestions($keywordsarray, $tagsarray, $user)
	{
		
		/*$keywords = $searchTerms['keywords'];
		$tags = $searchTerms['tags'];
		$users = $searchTerms['user'];*/
		
		if(count($keywordsarray) > 0)
		{
			if (strlen($keywordsarray[0]) >= 2)
			{
				$this->db->select('questionid');
				$this->db->from('question');
				$this->db->like('title', $keywordsarray[0]);
				for($i = 1; $i < count($keywordsarray); $i++)
				{
					if (strlen($keywordsarray[$i]) >= 2)
					{
						$this->db->or_like('title', $keywordsarray[$i]);
					}
				}
				
				$keywordsquery = $this->db->get();
				error_log($this->db->last_query());
				$keywordsquery = $keywordsquery->result_array();	
				$idsFromKeywords = array();
				for($i = 0; $i < count($keywordsquery); $i++)
				{
					$idsFromKeywords[] = $keywordsquery[$i]['questionid'];
					error_log('hi keywords, ' . $keywordsquery[$i]['questionid']);
				}
			}	
		}
		
		if (count($tagsarray) > 0)
		{
			if (strlen($tagsarray[0]) >= 2)
			{
				$this->db->select('questionid');
				$this->db->from('question');
				$this->db->like('tags', $tagsarray[0]);
				for($i = 1; $i < count($tagsarray); $i++)
				{
					if (strlen($tagsarray[$i]) >= 2)
					{
						$this->db->or_like('title', $tagsarray[$i]);
					}
				}
				$tagsquery = $this->db->get();
				error_log($this->db->last_query());
				$tagsquery = $tagsquery->result_array();	
				$idsFromTags = array();
				for($i = 0; $i < count($tagsquery); $i++)
				{
					$idsFromTags[] = $tagsquery[$i]['questionid'];
					error_log('hi tags , ' . $tagsquery[$i]['questionid']);
				}				
			}
		}
		
		if (strlen($user) >= 2)
		{
			$this->db->where('postedby', $user);
			$this->db->select('questionid');
			$userquery = $this->db->get('question');
			error_log($this->db->last_query());
			$userquery = $userquery->result_array();
			$idsFromUser = array();
			for($i = 0; $i < count($userquery); $i++)
			{
				$idsFromUser[] = $userquery[$i]['questionid'];
				error_log('hi user, ' . $userquery[$i]['questionid']);
			}						
		}	
		
		/*Intersect arrays (I didn't use array_intersect because I didn't want key values
		from the other arrays to be preserved.*/
		error_log("Sizes: " . count($idsFromKeywords) . count($idsFromTags) . count($idsFromUser));
		//Don't want to include an array in an intersect if it is empty.
		if ((count($idsFromKeywords) == 0) && (count($idsFromTags) == 0) && (count($idsFromUser) == 0))
		{
			error_log('1');
			return null;
		}
		else if ((count($idsFromKeywords) == 0) && (count($idsFromTags) == 0) && (count($idsFromUser) != 0))
		{
			error_log('2');
			return $idsFromUser;
		}
		else if ((count($idsFromKeywords) == 0) && (count($idsFromTags) != 0) && (count($idsFromUser) == 0))
		{
			error_log('3');
			return $idsFromTags;
		}
		else if ((count($idsFromKeywords) != 0) && (count($idsFromTags) == 0) && (count($idsFromUser) == 0))
		{
			error_log('4');
			return $idsFromKeywords;
		}	
		else if ((count($idsFromKeywords) == 0) && (count($idsFromTags) != 0) && (count($idsFromUser) != 0))
		{
		    error_log('5');
			$finalIntersect = $this->intersectIds($idsFromTags, $idsFromUser);
			return $finalIntersect;
		}	
		else if ((count($idsFromKeywords) != 0) && (count($idsFromTags) == 0) && (count($idsFromUser) != 0))
		{
			error_log('6');
			$finalIntersect = $this->intersectIds($idsFromKeywords, $idsFromUser);
			return $finalIntersect;
		}
		else if ((count($idsFromKeywords) != 0) && (count($idsFromTags) != 0) && (count($idsFromUser) == 0))
		{
			error_log('7');
			$finalIntersect = $this->intersectIds($idsFromKeywords, $idsFromTags);
			return $finalIntersect;
		}
		else
		{
			error_log('8');
			$firstIntersect = intersectIds($idsFromKeywords, $idsFromTags);
			$secondIntersect = intersectIds($firstIntersect, $idsFromUser);
			return $secondIntersect;
		}
		
		
	}
	
	public function intersectIds($arrayToCheck, $arrayToCompare)
	{
		$intersect = array();
		for($i = 0; $i < count($arrayToCheck);$i++)
		{
			for($j = 0; $j < count($arrayToCompare); $j++)
			{
				if($arrayToCheck[$i] == $arrayToCompare[$j])
				{
					$intersect[] = $arrayToCheck[$i];
					error_log('intersect: ' . $intersect[$i]);
				}
			}
		}
		return $intersect;
	}

	/*public function searchTags($searchTerms)
	{
		if (strlen($searchTerms[0]) >= 2)
		{
			$this->db->select('questionid');
			$this->db->from('question');
			$this->db->like('tags', $searchTerms[0]);
			for($i = 1; $i < count($searchTerms); $i++)
			{
				if (strlen($searchTerms[$i]) >= 2)
				{
					$this->db->or_like('tags', $searchTerms[$i]);
				}
			}
			$query = $this->db->get();
			return $query->result_array();	
		}
		return null;		
	}*/
	public function deleteQuestion($questionId)
	{
		$tables = array('question', 'answer');
		$this->db->delete($tables, array('questionid' => $questionId));
	}

	public function updateQuestion($questionId, $title, $oldtitle, $content, $tags, $editedby)
	{
		$questionDetails = array('title' => $title, 'content' => $content, 'lasteditedby' => $editedby,
		'lasteditedon' => $this->currentTime, 'tags' => $tags);
		/*If the new title isn't equal to the old title 
		then check the new title hasn't already been used.
		(The change may mean that the new title conflicts with an existing one)*/
		if(!($questionDetails['title'] == $oldtitle))
		{
			if ($this->checkIfTitleExists($questionDetails['title']))
			{
				return "This title is already in use.";
			}
		}
			$this->db->where(array('questionid' => $questionId));
			$this->db->update('question', $questionDetails);
			return "Question has been updated.";	
	}	
}