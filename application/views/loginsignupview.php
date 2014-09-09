<html>
<!--
Name: Jad El-Houssami
ID: w13651455 
-->
	<head>
		<title>Log In/Sign Up</title>
		<link rel='stylesheet' type='text/css' href='/qandasite/qandasite/css/styles.css' />
		<script src= "http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	</head>
	<body>
		<table>
			<tr>
				<td colspan='3' >
					<a href='/qandasite/qandasite/'>Home</a>&gt;Log in/Sign up
				</td>
			</tr>
			<tr>
				<td class='top_align'>* field is required</td>
				<td>
					<h1>Create Account</h1>
					<!--Signup form-->
					<form action= '/qandasite/qandasite/index.php/loginsignupcontroller/addUser' method='post'>
						<fieldset>
							<legend>Account Details</legend>
							<table>
								<tr>
									<td>Username (screen name) *</td>
									<td><input type='text' name='newusername' size='35' /></td>
								</tr>
								<tr>
									<td>Password *</td>
									<td><input type='password' name='newpassword' size='35' /></td>
								</tr>
								<tr>
									<td>E-mail address *</td>
									<td><input type='text' name='emailaddress' size='35' /></td>
								</tr>
								<tr>
									<td colspan='2'>
										<span class="error_message">
											<?php 
												if (isset($signuperror)){print $signuperror;}
												if (isset($signupother)){print $signupother;}
											?>
										</span>
									</td>
								</tr>
							</table>
						</fieldset>
						<fieldset>
							<legend>Technical Interests</legend>
							<table>
								<tr>
									<td>Favourite web dev language</td>
									<td>
										<select name='languages'>
											<option value= ''>Select an option...</option>
											<option value='HTML'>HTML</option>
											<option value='JavaScript'>JavaScript</option>
											<option value='CSS'>CSS</option>
											<option value='PHP'>PHP</option>
											<option value='AJAX'>AJAX</option>
											<option value='jQuery'>jQuery</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Favourite browser</td>
									<td>
										<select name='browsers'>
											<option value= ''>Select an option...</option>
											<option value='Internet Explorer'>Internet Explorer</option>
											<option value='Firefox'>Firefox</option>
											<option value='Google Chrome'>Google Chrome</option>
											<option value='Safari'>Safari</option>
											<option value='Opera'>Opera</option>
										</select>								
									</td>
								</tr>
								<tr>
									<td>Current projects (500 characters max)<br />
									<input type='text' name='counter' id='charsleft' readonly='readonly' size='5' value='500' /> Characters left</td>
									<td><textarea name='currentprojects' id='textlength' rows='5' cols='20'></textarea></td>
								</tr>
								<tr>
									<td colspan='2' class='centre_align'><input type='submit' name='createaccount' value="Create Account" /></td>
								</tr>
							</table>
						</fieldset>	
					</form>
				</td>
				<td class='top_align'>
					<h1>Log In</h1>
					<!--Login form-->
					<form action='/qandasite/qandasite/index.php/loginsignupcontroller/checkCredentials' method='post'>
						<table>
							<tr>
								<td>Username</td>
								<td><input type='text' name='loginusername' size='35' /></td>
							</tr>
							<tr>
								<td>Password</td>
								<td><input type='password' name='loginpassword' size='35' /></td>
							</tr>
							<tr>
								<td colspan='2' class='centre_align'><input type='submit' name='login' value="Log in" /></td>
							</tr>
							<tr>
								<td colspan='2'>
									<span class="error_message">
										<?php 
											if (isset($loginerror)){print $loginerror;}
											if (isset($loginother)){print $loginother;}
										?>
									</span>
								</td>
							</tr>							
						</table>		
					</form>
				</td>
			</tr>
		</table>
		<script language='javascript'>
		$(function()
		{
			var textLengthTextArea = $("#textlength");
			var charsLeft = $('#charsleft');

			textLengthTextArea.on("keydown", function(e)
			{ 
				var chars = 500 - (textLengthTextArea.val().length);
				$.ajax
				({
					url: "/qandasite/qandasite/index.php/loginsignupcontroller/changecharsleft",
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