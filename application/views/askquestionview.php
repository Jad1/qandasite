<html>
<!--
Name: Jad El-Houssami
ID: w13651455
-->
	<head>
		<title>Ask Question</title>
		<link rel='stylesheet' type='text/css' href='/qandasite/qandasite/css/styles.css' />
		<script src= 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>
	</head>
	<body>
		<table class='centre_table'>
		    <tr>
				<td class='large_cell centre_align'>
					<span class='left_align'><a href='/qandasite/qandasite'>Home</a>&gt;Ask question</span>
				</td>
				<td class='large_cell centre_align'>
					<span class='centre_align'><a href='/qandasite/qandasite/index.php//askquestioncontroller/logout'>Logout</a></span>
				</td>
				<td class='large_cell centre_align'>
					<span class='right_align'>Welcome <a href='/qandasite/qandasite/index.php/changepasswordcontroller/profile'>
					<?php if (isset($username)){print $username;}?></a></span>
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td>
					<h1>Ask a Question</h1>
				</td>
				<!--Ask question form-->
				<form action= '/qandasite/qandasite/index.php/askquestioncontroller/addquestion' method='post'>
					<table>
						<tr>
							<td class='top_align'>All fields must be filled in</td>
						</tr>
						<tr>
							<td colspan='2' class='centre_align'>
								<span class='error_message'>
									<?php 
										if (isset($error)){print $error;}
										if (isset($other)){print $other;}
									?>
								</span>
							</td>
						</tr>						
						<tr>
							<td class='centre_align'>
								<select name='categories'>
									<option value=''>Select category</option>
									<option value='HTML'>HTML</option>
									<option value='JavaScript'>JavaScript</option>
									<option value='CSS'>CSS</option>
									<option value='PHP'>PHP</option>
									<option value='XML'>XML</option>
									<option value='Web Security'>Web Security</option>
								</select>
							</td>									
						</tr>
						<tr>
							<td class='centre_align'><input type='text' name='title'  class='large_input_field' placeholder='Question title (Between 20 and 500 characters)' /></td>
						</tr>
						<tr>
							<td class='centre_align'><input type='text' name='charsleft' id='charsleft' readonly='readonly' size='5' value='10000'/> Characters left</td></td>
						</tr>
						<tr>
							<td class='centre_align'><textarea name='question' id='textlength' rows='20' cols='50' placeholder='Question (Between 50 and 10000 characters)'></textarea></td>								
						</tr>
						<tr>
							<td class='centre_align'>
								Tags must be separated with commas.<br />
								<input type='text' name='tags' class='large_input_field' placeholder='Add tags (Min one tag max five, min 5 characters max 75 characters.)' />
							</td>
						</tr>		
						<tr>
							<td class='centre_align'>
								<input type='submit' value='Ask Question' />
							</td>
						</tr>
					</table>
				</form>		
			</tr>
		</table>	
		<script language='javascript'>
		$(function()
		{
			var textLengthTextArea = $('#textlength');
			var charsLeft = $('#charsleft');
			var chars = 10000 - (textLengthTextArea.val().length);
			
			textLengthTextArea.on("keydown", function(e)
			{ 
				$.ajax
				({
					url: "/qandasite/qandasite/index.php/profilecontroller/changecharsleft",
					type: "post",
					data: {chars : chars, keycode : e.keyCode},  //See References.txt (13)
					success: function(data)
					{
						charsLeft.val(data);
						chars = data;
					}
				});			
			});
		});
		</script>
	</body>
</html>