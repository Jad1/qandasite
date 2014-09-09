<html>
<!--
Name: Jad El-Houssami
ID: w13651455
-->
	<head>
	<title><?php if (isset($data[0]['title'])) print $data[0]['title'];?></title>
	<link rel='stylesheet' type='text/css' href='/qandasite/qandasite/css/styles.css' />
	<script src= "http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	</head>
	<body>
		<table class='centre_table'>
		    <tr>
				<td class='large_cell centre_align'>
					<span class='left_align'>
						<a href='/qandasite/qandasite/'>Home</a> &gt; 
						<span id='category'>
							<a href='/qandasite/qandasite/index.php/questionlistcontroller'><?php if (isset($data[0]['category'])) print $data[0]['category'];?></a>
						</span>
						&gt;<?php if (isset($data[0]['title'])) print $data[0]['title'];?>
					</span>
				</td>
				<td class='large_cell centre_align'>
					<span class='centre_align'>				
						<?php
							$username;
							if  (!isset($session['username']))
							{
								$username = "";
								print "<a href='/qandasite/qandasite/index.php/loginsignupcontroller' id='loginsignup'>Log in/Sign up</a>";
							}
							else
							{
								$username = $session['username'];
								print"<a href='/qandasite/qandasite/index.php/homecontroller/logout' id='logout'>Logout</a>";
							}
						?>
					</span>
				</td>
				<td class='large_cell centre_align'>
					<span class='right_align' id='welcomemessage'>Welcome 
						<a href='/qandasite/qandasite/index.php/changepasswordcontroller/profile'>
							<?php 
								if (isset($session['username'])){print $session['username'];}
							?>
						</a>
					</span>
				</td>
			</tr>
		</table>	
		<table>
			<tr>
				<td>
					<h1><?php if (isset($data[0]['title'])) print $data[0]['title']; ?></h1>
					<span class="error_message">
						<?php if(isset($message['error'])){print $message['error'];} ?>
					</span>		
					<div id='questiondisplay'>
						<?php
							print "<h3>Question</h3>";
							print "<table class='home_dynamic_table'>";
								print "<tr> <td>";
									print "<b><a href='/qandasite/qandasite/index.php/profilecontroller'>" . 
									$data[0]['postedby'] . "</a> &nbsp;"; 
									print "Reputation: " . $data[0]['reputation'] . "</b><br />";
									print $data[0]['content'] . "<br />";
									print "<b>Tags: " . $data[0]['tags'] . "</b> <br />";
									print "<span class='editdeletequestion'>";
										print "<a href='#' class='editquestion'>Edit Question</a> &nbsp; &nbsp;";
										print "<a href='#' class='deletequestion'>Delete Question</a>";
									print"</span>";
									print "<input type='hidden' class='questionid' value='" . $data[0]['questionid'] . "' />"; 
									print "<input type='hidden' class='questionposter' value='" . $data[0]['postedby'] . "' />";
								print "</td> </tr>"; 
								print "<tr> <td>";
									print "<span class='editablequestion' style='display : none'>";
											print "<input type='text' class='updatedtitle' size='50' placeholder='Question title (Between 20 and 500 characters)' value='" . $data[0]['title'] . "' /> <br />";
											print "<textarea class='updatedcontent' rows='10' cols='20' placeholder='Question (Between 50 and 10000 characters)'>" . $data[0]['content'] . "</textarea> <br />";
											print "<input type='text' class='updatedtags' size='50' placeholder='Add tags (Min one tag max five, min 5 characters max 75 characters.)' value='" . $data[0]['tags'] . "' /> <br />";		
											print "<a href='/qandasite/qandasite/index.php/questionanswercontroller/updatequestion'>Update</a> &nbsp; &nbsp;";
											print "<a href='#'>Cancel</a>";
									print "</span>";	
								print "</td> </tr>";
							print "</table>";
							print "<br />";
							print "<h3>Answers</h3>";
							for($i = 0; $i < count($data['answers']); $i++)
							{
								print "<table class='home_dynamic_table'>";
									print "<tr> <td>";
										print "<b><a href='/qandasite/qandasite/index.php/profilecontroller'>" 
										. $data['answers'][$i]['postedby'] . "</a> &nbsp; "; 
										print "Reputation: <span class='reputation'>" . $data['answers'][$i]['reputation'] . "</span> &nbsp; &nbsp;";
										print "<span class='changepostrating'>";
											print "<input type='button' class='upvote' value='+'/>" . "&nbsp; &nbsp;";
											print "<input type='button' class='downvote' value='-'/>";
										print"</span>" . "&nbsp; &nbsp;";
										print "Answer rating: " . "<span class='rating'>" . $data['answers'][$i]['rating'] . "</span>" . "</b><br />";
										print $data['answers'][$i]['content'] . "<br />";
										print "<span class='editdeleteanswer'>";
											print "<a href='#' class='editanswer'>Edit Answer</a>" . " &nbsp; &nbsp;";
											print "<a href='#' class='deleteanswer'>Delete Answer</a>";
										print "</span> ". "<br />";
										print "<input type='hidden' class='answerposter' value='" . $data['answers'][$i]['postedby'] . "' />";
										print "<input type='hidden' class='answerid' value='" . $data['answers'][$i]['answerid'] . "' />"; 
									print "</td> </tr>";
									print "<tr> <td>";
										print "<span class='editableanswer' style='display : none'>";
											print "<textarea class='updatedanswer' rows='10' cols='20' placeholder='Answer (Between 50 and 10000 characters)'>" . $data['answers'][$i]['content'] . "</textarea> <br />";
											print "<a href='/qandasite/qandasite/index.php/questionanswercontroller/updateanswer'>Update</a> &nbsp; &nbsp;";
											print "<a href='#'>Cancel</a>";
										print "</span>";
									print "</td></tr>";	
								print "</table>";
								print "<br />";							
							}		
						?>
					</div>
					<div id='answerentry'>
						<form action='/qandasite/qandasite/index.php/questionanswercontroller/addanswer' method='post'>
							<h4>Add Answer</h4> &nbsp; &nbsp; &nbsp;
							<input type='text' name='counter' id='charsleft' readonly='readonly' size='5' value='10000' /> Characters left <br />
							<textarea name='answer' id='textlength' rows='20' cols='50' placeholder='Answer (Between 50 and 10000 characters)'></textarea>
							<!--Some PHP values that need to be posted in when the answer is submitted.-->
							<input type='hidden' name='questionid' value='<?php print $data[0]['questionid']?>' />
							<input type='hidden' name='title' value=' <?php print $data[0]['title'];?>' />
							<input type='hidden' name='postedby' value='<?php if (isset($session['username'])){print $session['username'];} ?>' />
							<input type='hidden' name='replies' value='<?php print $data[0]['replies'];?>' />
							<!--<input type='text' name='answerrating' value='' />-->
							<input type='submit' value='Add Answer' /><br />
						</form>	
					</div>
				</td>
			</tr>
		</table>
	<script language='javascript'>
		$(function()
		{
			var textLengthTextArea = $("#textlength");
			var welcomeMessage = $('#welcomemessage');
			var questionDisplay = $('#questiondisplay');
			var category = $('#category');
			var charsLeft = $('#charsleft');
			var answerEntry = $('#answerentry');
			var changePostRating = $('.changepostrating');
			var upVote = $('.upvote');
			var downVote = $('.downvote');
			var rating = $('.rating');
			var editDeleteAnswer = $('.editdeleteanswer');
			var editAnswer = $('.editanswer');
			var deleteAnswer = $('.deleteanswer');
			var editDeleteQuestion = $('.editdeletequestion');
			var editQuestion = $('.editquestion');
			var deleteQuestion = $('.deletequestion');
			var questionPoster = $('.questionposter').eq(0).val();
			var answerPosters = $('.answerposter');
			var questionId = $('.questionid');
			var answerIds = $('.answerid');
			var editableQuestion = $('.editablequestion');
			var editableAnswer = $('.editableanswer');
			var reputation = $('.reputation');
			//SORT OUT REPUTATION.
			var visitorUsername =  "<?php if(isset($session['username'])) {print "$session[username]";} else {print "";} ?>";
			var visitorFlagValue = <?php if(isset($session['flag'])) {print $session['flag'];} else {print 0;} ?>; //Flag of visitor to page (zero if not logged in)
			
			var categoryName;
			var username;
			var t;
			
			textLengthTextArea.on("keypress", function(e)
			{
				var chars = 10000 - (textLengthTextArea.val().length);
				$.ajax
				({
					url: "/qandasite/qandasite/index.php/questionanswercontroller/changecharsleft",
					type: "post",
					data: {chars : chars, keycode : e.keyCode},  //See References.txt (13)
					success: function(data)
					{
						charsLeft.val(data);
						chars = data;
					}
				});			
			});
			
			/*The following event handler has three parameters because I only want the 
			callback function to be invoked when a link within the div outside it is clicked.
			  See References.txt (5)*/
			questionDisplay.on("click", "a", function(e)
			{
				/*Prevent default action of link.
				  See references.txt (11)*/
				e.preventDefault();
				username = $(this).text().trim();

				$.ajax
				({
					url: "/qandasite/qandasite/index.php/profilecontroller",
					data: {name : username},
					type: "get",
					async: "false",
					success: function(data)
					{
						/*Redirect to new page
						  See References.txt(10)*/
						window.location.replace("/qandasite/qandasite/index.php/profilecontroller/display"); 
					},
					error: function(jqXHR, status, error)
					{
						console.log("Status: " + status + ". Error: " + error);
					}
				});
			});			

			category.on("click", "a", function(e)
			{
				/*Prevent default action of link.
				  See references.txt (11)*/
				e.preventDefault();
				categoryName = $(this).text().trim();

				$.ajax
				({
					url: "/qandasite/qandasite/index.php/questionlistcontroller",
					data: {category : categoryName},
					type: "get",
					async: "false",
					success: function(data)
					{
						/*Redirect to new page
						  See References.txt(10)*/
						window.location.replace("/qandasite/qandasite/index.php/questionlistcontroller/display"); 
					},
					error: function(jqXHR, status, error)
					{
						console.log("Status: " + status + ". Error: " + error);
					}
				});
			});	

			//Determine which upvote/downvote button was clicked and update the relevant rating value.
			//See References.txt(12)
			questionDisplay.on('click', 'input[type="button"]', function()
				{
					t = $(this);
					//Find the rating class within the span tag that this button
					//is contained in.
					var whichRating = t.parentsUntil('table').find(rating);
					
					//Check the className property of the button to see which button was clicked.
					if(t.hasClass('upvote')) 
					{
						$(whichRating).html(parseInt($(whichRating).text()) + 1);
					}
					else
					{
						$(whichRating).html(parseInt($(whichRating).text()) - 1);
					}
				});
				
				editDeleteQuestion.on('click', 'a', function(e)
				{
					t = $(this);
					/*Prevent default action of link.
				      See references.txt (11)*/
					e.preventDefault();
					e.stopPropagation(); //Stop click event from "bubbling up" to other links. See References.txt (15)
					var whichLink = t.text().trim();
					if (whichLink == "Edit Question")
					{
						//Prevent user from Editing question if they are not logged in or if they are a regular
						//user and did not post the question themselves.
						if ((visitorUsername == "") || ((visitorFlagValue == 1) && (visitorUsername != questionPoster)))
						{
							window.location.replace("/qandasite/qandasite");
						}
						else
						{
							//Hide/display edit question area depending on whether it's currently visible or not.
							if (editableQuestion.css('display') == 'none')
							{
								editableQuestion.css('display', 'inline');
							}
							else if (editableQuestion.css('display') == 'inline')
							{
								editableQuestion.css('display', 'none');
							}
						}
						
					}
					else if (whichLink == "Delete Question")
					{

						//Get the question ID, answer IDs and pass to deleteAnswer().
						var questionToDelete = t.parents('table').find(questionId).val();
						
						//Prevent user from deleting question if they are not logged in or if they are a regular
						//user and did not post the question themselves.
						if ((visitorUsername == "") || ((visitorFlagValue == 1) && (visitorUsername != questionPoster)))
						{
							//Redirect to home page automatically because user is not logged in.
							window.location.replace('/qandasite/qandasite');
						}
						else
						{
							$.ajax
							({
								url: "/qandasite/qandasite/index.php/questionanswercontroller/deletequestion",
								data: {questionid : questionToDelete},
								type: "get",
								async: "false",
								success: function(data)
								{
									/*Redirect to new page
									  See References.txt(10)*/
									window.location.replace("/qandasite/qandasite");
								},
								error: function(jqXHR, status, error)
								{
									console.log("Status: " + status + ". Error: " + error);
								}
							});							
						}
					}
				});				
				
				editableQuestion.on('click', 'a', function(e)
				{
					t = $(this);
					/*Prevent default action of link.
				      See references.txt (11)*/
					e.preventDefault();
					e.stopPropagation(); //Stop click event from "bubbling up" to other links. See References.txt (15)
					var whichLink = t.text().trim();
					if (whichLink == "Cancel")
					{
						editableQuestion.css('display', 'none');
					}
					else if (whichLink == "Update")
					{
						//Prevent user from updating question if they are not logged in or if they are a regular
						//user and did not post the question themselves.
						if ((visitorUsername == "") || ((visitorFlagValue == 1) && (visitorUsername != questionPoster)))
						{
							//Redirect to home page automatically because user is not logged in.
							window.location.replace('/qandasite/qandasite');
						}
						else
						{
							//Collect the data about the question to be updated.
							var id = t.parents('table').find(questionId).val();
							var oldtitle = "<?php print $data[0]['title']?>"; 
							var title = t.parents('span').find('.updatedtitle').val();
							var content = t.parents('span').find('.updatedcontent').val();
							var tags = t.parents('span').find('.updatedtags').val();
							
							$.ajax
							({
								url: "/qandasite/qandasite/index.php/questionanswercontroller/updatequestion",
								data: {questionid : id, title : title, oldtitle: oldtitle, content : content, tags: tags, editedby : visitorUsername},
								type: "get",
								async: "false",
								success: function(data)
								{
									/*Redirect to new page
									  See References.txt(10)*/
									window.location.replace("/qandasite/qandasite/index.php/questionanswercontroller/display");
								},
								error: function(jqXHR, status, error)
								{
									console.log("Status: " + status + ". Error: " + error);
								}
							});
						}
					}
				});
				
				editDeleteAnswer.on('click', 'a', function(e)
				{
					t = $(this);
					
					/*Prevent default action of link.
				      See references.txt (11)*/
					e.preventDefault();
					e.stopPropagation(); //Stop click event from "bubbling up" to other links. See References.txt (15)
					var whichLink = t.text().trim();
					var answerPoster = t.parentsUntil('table').find('.answerposter').val();
					console.log(answerPoster);
					var whichAnswer = t.parentsUntil('table').find('.editableanswer');
					console.log(whichAnswer);
					if (whichLink == "Edit Answer")
					{
						//Prevent user from editing answer if they are not logged in or if they are a regular
						//user and did not post the answer themselves.
						if ((visitorUsername == "") || ((visitorFlagValue == 1) && (visitorUsername != answerPoster)))
						{
							window.location.replace("/qandasite/qandasite");
						}
						else
						{
							//Hide/display edit question area depending on whether it's currently visible or not.
							if (whichAnswer.css('display') == 'none')
							{
								whichAnswer.css('display', 'inline');
							}
							else if (whichAnswer.css('display') == 'inline')
							{
								whichAnswer.css('display', 'none');
							}
						}
					}
					else
					{
						//Get the answer ID and pass to deleteAnswer().
						var answerToDelete = t.parentsUntil('table').find('.answerid').val();

						//Prevent user from deleting answer if they are not logged in or if they are a regular
						//user and did not post the answer themselves.
						if ((visitorUsername == "") || ((visitorFlagValue == 1) && (visitorUsername != answerPoster)))
						{
							//Redirect to home page automatically because user is not logged in.
							window.location.replace('/qandasite/qandasite');
						}
						else
						{
							$.ajax
							({
								url: "/qandasite/qandasite/index.php/questionanswercontroller/deleteanswer",
								data: {questionid : questionId.val(), replies : answerPosters.length, answerid : answerToDelete},
								type: "get",
								async: "false",
								success: function(data)
								{
									/*Redirect to new page
									  See References.txt(10)*/
									window.location.replace("/qandasite/qandasite/index.php/questionanswercontroller/display"); //Am concerned about this.
								},
								error: function(jqXHR, status, error)
								{
									console.log("Status: " + status + ". Error: " + error);
								}
							});							
						}
					}
				});
				
				editableAnswer.on('click', 'a', function(e)
				{
					t = $(this);
					/*Prevent default action of link.
				      See references.txt (11)*/
					e.preventDefault();
					e.stopPropagation(); //Stop click event from "bubbling up" to other links. See References.txt (15)
					var whichLink = t.text().trim();
					var answerPoster = t.parentsUntil('table').find('.answerposter').val();
					if (whichLink == "Cancel")
					{
						editableAnswer.css('display', 'none');
					}
					else if (whichLink == "Update")
					{
						//Prevent user from updating question if they are not logged in or if they are a regular
						//user and did not post the question themselves.
						if ((visitorUsername == "") || ((visitorFlagValue == 1) && (visitorUsername != answerPoster)))
						{
							//Redirect to home page automatically because user is not logged in.
							window.location.replace('/qandasite/qandasite');
						}
						else
						{
							//Collect the data about the answer to be updated.
							var id = t.parentsUntil('table').find('.answerid').val();
							var content = t.parents('span').find('.updatedanswer').val();
							
							$.ajax
							({
								url: "/qandasite/qandasite/index.php/questionanswercontroller/updateanswer",
								data: {answerid : id, content : content, editedby : visitorUsername},
								type: "get",
								async: "false",
								success: function(data)
								{
									/*Redirect to new page
									  See References.txt(10)*/
									window.location.replace("/qandasite/qandasite/index.php/questionanswercontroller/display");
								},
								error: function(jqXHR, status, error)
								{
									console.log("Status: " + status + ". Error: " + error);
								}
							});
						}
					}
				});				
				
				
			//PERMISSIONS
			//Based on the flag/reputation of the page visitor, hide certain elements.
			switch(visitorFlagValue)
			{
			case 0:
				hideClickableElements();
				break;
			case 1:
				setUserRestrictions();
				break;
			case 2:
			case 3:
				for(var i = 0; i < answerPosters.length; i++)
				{
					if(visitorUsername == answerPosters.eq(i).val())
					{
						hideOwnRatingButtons(i);
					}
				}
				
				break;
			}
			
			function hideClickableElements()
			{
				changePostRating.css('display', 'none');
				editDeleteAnswer.css('display', 'none');
				editDeleteQuestion.css('display', 'none');
				answerEntry.css('display', 'none');
			}
			
			function setUserRestrictions()
			{
				//Check if the question was posted by the person viewing the page.
				if ((visitorUsername != questionPoster))
				{
					editDeleteQuestion.css('display', 'none');
				}
				
				/*For each answer, hide edit/delete answer links if the user didn't post that answer.
				  Or hide rating links for user's own posts.*/
				for(var i = 0; i < answerPosters.length; i++)
				{
					if(visitorUsername != answerPosters.eq(i).val())
					{
						answerPosters.eq(i).siblings('.editdeleteanswer').css('display','none');
					}
					else
					{
						hideOwnRatingButtons(i);
					}
				}
			}
			
			/*No user (regardless of flag) should be able to rate their
			  own post.*/
			function hideOwnRatingButtons(i)
			{
				//See References.txt(12)
				answerPosters.eq(i).siblings('b').find('.changepostrating').eq(i).css('display','none');
			}
		});		
	</script>
	</body>
</html>