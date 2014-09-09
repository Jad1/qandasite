<html>
<!--
Name: Jad El-Houssami
ID: w13651455
-->
	<head>
		<title>Home</title>
		<link rel='stylesheet' type='text/css' href='/qandasite/qandasite/css/styles.css' />
		<script src= "http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>	
	</head>
	<body>
		<table class='centre_table'>
		    <tr>
				<td class='large_cell centre_align'>
					<span class='left_align'>Home</span>
				</td>
				<td class='large_cell centre_align'>
					<span class='centre_align'>
						<?php
							if  (!isset($session['username']))
							{
								print "<a href='/qandasite/qandasite/index.php/loginsignupcontroller' id='loginsignup'>Log in/Sign up</a>";
							}
							else
							{
								print"<a href='/qandasite/qandasite/index.php/homecontroller/logout' id='logout'>Logout</a>";
							}
						?>
					</span>
				</td>
				<td class='large_cell centre_align'>
					<span class='right_align' id='welcomemessage'>Welcome 
						<a href='/qandasite/qandasite/index.php/profilecontroller'>
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
				<td class= 'top_align'>
						<h1>Search</h1><br />
						<input type='text' id='searchbykeywords' size='35' placeholder='Search by keyword(s)...' /><br /> <br />
						<input type='text' id='searchbytags' size='35' placeholder='Search tag(s)...' /> <br /> <br />
						<input type='text' id='searchbyuser' size='35' placeholder='Search by user...' />
				</td>	
				<td class='centre_align'>
				<h1 id='heading'>Today's Asked Questions</h1>
				<?php
					if(isset($session['username']))
					{
						print "<a href='/qandasite/qandasite/index.php/askquestioncontroller'>Ask Question</a><br /><br />";
					}
				?>
				<select id='sortcriterion'>
					<option value=''>Sort questions by...</option>
					<option value='recentlyposted'>Most recent</option>
					<option value='notrecentlyposted'>Least recent</option>
					<option value='mostreplies'>Most replies</option>
					<option value='leastreplies'>Least replies</option>
					<option value='A-Z'>Alphabetical (A-Z)</option>
					<option value='Z-A'>Alphabetical (Z-A)</option>
				</select> <br /></br />
				<div id="questiondisplay">
					<?php
						//Print all questions posted today (with a link to the question itself)
						if (isset ($questions))
						{
							for($i = 0; $i < count($questions); $i++)
							{
								print "<table class='home_dynamic_table'>";
									print"<tr> <td>";
										print "<b><a href='#'><h2>" . $questions[$i]['title'] . "</h2></a>";
										print "Posted by " . "<a href='#' class='profilename'>" . $questions[$i]['postedby'] . "</a>" . ", on " . $questions[$i]['postedon'] . "<br />";
										print "Replies: " . $questions[$i]['replies'] .	"&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Tags: "
										. $questions[$i]['tags'] . "</b>";
									print"</td> </tr>";
								print "</table>";
								print "<br />";
							}
						}
					?>
				</div>	
				</td>
				<td class='centre_align top_align'>
					<div id='categories'>
						<h1>Browse Categories</h1><br />
						<a href=''>HTML</a><br />
						<a href=''>CSS</a><br />
						<a href=''>JavaScript</a><br />
						<a href=''>PHP</a><br />
						<a href=''>XML</a><br />
						<a href=''>Web Security</a>
					</div>
				</td>
			</tr>
		</table>
		<script language='javascript'>
		$(function()
		{
			var minimumSearchLength = 2;
			var minimumResultSize = 1;
			var searchByKeywords = $('#searchbykeywords');
			var searchByTags = $('#searchbytags');
			var searchByUser = $('#searchbyuser');
			var questionDisplay = $('#questiondisplay');
			var sortCriterion = $('#sortcriterion');
			var heading = $('#heading');
			var welcomeMessage = $('#welcomemessage');
			var categories = $('#categories');
			var table, tr, td, a, b, h2, br, nbsp; //These correspond to HTML tags or a non-breaking space.
			var username;
			var keywords;
			var tags;
			var user;
			var searchHandler = searchByKeywords.add(searchByTags).add(searchByUser); //Use this to call the same keyup handler.
			var questions = <?php print json_encode($questions);?>; //See References.txt (6)
			var category
			var t;
			
			//console.log((questionDisplay.find('span')).html());
			searchHandler.on("keyup", function()
			{ 
				keywords = searchByKeywords.val();
				tags = searchByTags.val();
				user = searchByUser.val();
				
				if(keywords.length == 0)
				{
					keywords = "ii";
				}
				
				if(tags.length == 0)
				{
					tags = "ii";
				}
				
				if(user.length == 0)
				{
					user = "ii";
				}
				
				console.log(keywords.length + " " + keywords);
				console.log(tags.length+ " " + tags);
				console.log(user.length + " " + user);
				$.ajax
				({
					url: "/qandasite/qandasite/index.php/search/questions",
					type: "get",
					data: {keywords : keywords, tags : tags, user : user}, 
					dataType: "json",
					success: function(data)
					{
						console.log(JSON.stringify(data));
						console.log(data.length);
						//Empty div and re-fill it with new search.
						if (data.length >= minimumResultSize)
						{
							displaySearchResults(data);
						}	
					},
					error: function(jqXHR, status, error)
					{
						console.log(" Status :" + status + " error: " + error); 
					}			
				});	
			});
		
			sortCriterion.on("change", function()
			{
				for(var i = 0; i < questions.length; i++)
				{
					questions[i].replies = parseInt(questions[i].replies);
				}

				switch(sortCriterion.val())
				{
					//For my benefit, most recent posts require descending order sort. Least recent ascending order posts.
					//See References.txt (9)
					case 'recentlyposted':
						questions.sort(function(a, b)
						{
							if (a.postedon < b.postedon)
							{
								return 1;
							}
							if (a.postedon > b.postedon)
							{
								return -1;
							}	
							// a must be equal to b
							return 0;
						});	
						displaySearchResults(questions);
						break;
					case 'notrecentlyposted':
						questions.sort(function(a, b)
						{
							if (a.postedon > b.postedon)
							{
								console.log(a.postedon + " " + b.postedon);
								return 1;
							}
							if (a.postedon < b.postedon)
							{
								console.log(a.postedon + " " + b.postedon);
								return -1;
							}	
							// a must be equal to b
							console.log(a.postedon + " " + b.postedon);
							return 0;
						});	
						displaySearchResults(questions);
						break;		
					case 'mostreplies':
						questions.sort(function(a, b)
						{
							return b.replies - a.replies;
						});							
						displaySearchResults(questions);
						break;			
					case 'leastreplies':
						questions.sort(function(a, b)
						{
							return a.replies - b.replies;
						});						
						displaySearchResults(questions);						
						break;
					case 'A-Z':
						questions.sort(function (a, b) {
							if (a.title > b.title)
							{
								console.log(a.title + " " + b.title);
								return 1;
							}
							if (a.title < b.title)
							{
								console.log(a.title + " " + b.title);
								return -1;
							}	
							// a must be equal to b
							console.log(a.title + " " + b.title);
							return 0;
						});
						displaySearchResults(questions);
						break;
					case 'Z-A':
						isAplhabeticSort = true;
						isAscendingSort = false;	
							questions.sort(function (a, b) {
							if (a.title < b.title)
							{
								console.log(a.title + " " + b.title);
								return 1;
							}
							if (a.title > b.title)
							{
								console.log(a.title + " " + b.title);
								return -1;
							}	
							// a must be equal to b
							console.log(a.title + " " + b.title);
							return 0;
						});
						displaySearchResults(questions);						
						break;												
					default:
						break;
				}
			});	
			
			/*The following event handlers have three parameters because I only want the 
			callback function to be invoked when a link within the div outside it is clicked.
			  See References.txt (5)*/
			  
			questionDisplay.on("click", "a", function(e)
			{
				t = $(this);
				/*Prevent default action of link.
				  See references.txt (11)*/
				e.preventDefault();
				//Check if the clicked link has a given class.
				if (!(t.hasClass('profilename')))
				{
					var questiontitle = t.text().trim();

					$.ajax
					({
						url: "/qandasite/qandasite/index.php/questionanswercontroller",
						data: {questiontitle : questiontitle},
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
				else
				{
					displayUserProfile(e, t);
				}
				
			});
			
			welcomeMessage.on("click", "a", function(e)
			{
				displayUserProfile(e, $(this));
			});
			
			categories.on("click", "a", function(e)
			{
				/*Prevent default action of link.
				  See references.txt (11)*/
				e.preventDefault();
			    category = $(this).text().trim();
				$.ajax
				({
					url: "/qandasite/qandasite/index.php/questionlistcontroller",
					data: {category : category},
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
				
			function displaySearchResults(results)
			{
				questions = results; //This means that a future sort only takes into account the most recent search (i.e. the one being displayed now)
				heading.empty();
				questionDisplay.empty();

				heading.append("Results");
				var x;
				//See References.txt (4)
				//Create the elements to go inside the div and add the JSON data.
				for(var i = 0; i < results.length; i++)
				{
					table = $("<table />",
					{
						"class": "home_dynamic_table"
					});
					 x = questionDisplay.append(table);
					tr = $("<tr />");
					table.append(tr);
					td = $("<td />");
					tr.append(td);										
					a = $("<a />",
					{
						"href": "#",
					});
					td.append(a);
					
					h2 = $("<h2 />",
					{
						"text" : results[i].title
					});
					a.append(h2);
					
					
					b = $("<b />",
					{
						"text" : "Posted by "
					});
					td.append(b);
					
					a = $("<a />",
					{
						"href" : "/qandasite/qandasite/index.php/",
						"class" : "profilename"
					});
					b.append(a);
					a.append(results[i].postedby);
					b.append(", on " + results[i].postedon);
					
					br = $("<br />");
					b.append(br);
					
					b = $("<b />",
					{
						"text": "Replies: " + results[i].replies
					});
					td.append(b);

					//See References.txt (2)
					nbsp = "&nbsp; ";
					for(var j  = 0; j < 6; j++)
					{
						b.append(nbsp);
					}
					b = $("<b />",
					{
						"text": "Tags: " + results[i].tags
					});
					td.append(b);	
					
					br = $("<br />");
					table.after(br);
					console.log(x.html());
				}
			}
			
			function displayUserProfile(e, t)
			{
			/*Prevent default action of link.
				  See references.txt (11)*/
				e.preventDefault();
			    username = t.text().trim();

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
			}
		});	
		</script>
	</body>
</html>