<html>
<!--
Name: Jad El-Houssami
ID: w13651455
-->
	<head>
		<title>Change Password</title>
		<link rel='stylesheet' type='text/css' href='/qandasite/qandasite/css/styles.css' />
		<script src= "http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	</head>
	<body>
		<table class='centre_table'>
		    <tr>
				<td class='large_cell centre_align'>
					<span class='left_align'>
						<a href='/coursework2'>Home</a>&gt;
						<span id='profileusername'><a href='/qandasite/qandasite/index.php/profilecontroller'>
							<?php
								if (isset($session['username'])) {print $session['username'];}
							?>
						</span>	
					</a>&gt; Change Password</span>
				</td>
				<td class='large_cell centre_align'>
					<span class='centre_align'><a href='/qandasite/qandasite/index.php/changepasswordcontroller/logout'>Logout</a></span>
				</td>
				<td class='large_cell centre_align'>
					<span class='right_align'>Welcome</span>
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td class='top_align'>
					<h1>Change Password</h1>
					<!--Change password form-->
					<form action='/qandasite/qandasite/index.php/changepasswordcontroller/changepassword' method='post'>
						<table class='centre_table'>
						<tr>
							<td colspan='2' class='centre_align'>All fields must be filled in.</td>
						</tr>
							<tr>
								<td class="left_align">Old Password</td>
								<td class="left_align"><input type='password' name='oldpassword' size='35' /></td>
							</tr>
							<tr>
								<td class="left_align">New Password</td>
								<td class="left_align"><input type='password' name='newpassword' size='35' /></td>
							</tr>
								<td class="left_align">Confirm Password</td>
								<td class="left_align"><input type='password' name='confirmpassword' size='35' /></td>
							<tr>
							<tr>
								<td colspan='2' class='centre_align'><input type='submit' name='changepassword' value="Change Password" /></td>
							</tr>
							<tr>
								<td colspan='2'>
									<span class="error_message">
										<?php 
											if (isset($error)){print $error;}
											if (isset($other)){print $other;}
										?>
									</span>
								</td>
							</tr>							
						</table>		
					</form>
				</td>			
			</tr>
		</table>	
	</body>
	<script language='javascript'>
	$(function()
	{		
		var profileUsername = $('#profileusername');
		var username;

		profileUsername.on("click", "a", function(e)
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
	});
	</script>
</html>