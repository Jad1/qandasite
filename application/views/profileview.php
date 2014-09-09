<html>
<!--
Name: Jad El-Houssami
ID: w13651455
-->
	<head>
		<title>Profile</title>
		<link rel='stylesheet' type='text/css' href='/qandasite/qandasite/css/styles.css' />
		<script src= "http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	</head>
	<body>
		<table class='centre_table'>
		    <tr>
				<td class='large_cell centre_align'>
					<span class='left_align'><a href='/qandasite/qandasite/index.php/profilecontroller/home'>Home</a> &gt; Profile</span>
				</td>
				<td class='large_cell centre_align'>
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
				</td>
				<td class='large_cell centre_align'>
					<span class='right_align'>Welcome </span>
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td class='top_align'>
				<!--May need to put this link inside PHP-->
				<a href='/qandasite/qandasite/index.php/changepasswordcontroller/' class='changepassword editable'>Change Password</a>
				</td>
				<td>
					<h1>User Profile</h1>
					<table>
						<form method='post' action='/qandasite/qandasite/index.php/profilecontroller/updateprofile'>
							<tr>
								<td>Username (screen name) *</td>
								<td><?php print $userdetails[0]['username']; ?></td>
								<td class='editable moderatorrestriction adminrestriction'><input type='text' name='username' value=<?php print $userdetails[0]['username']; ?> /></td>
							</tr>
							<tr>
								<td>E-mail address *</td>
								<td><?php print $userdetails[0]['emailaddress']; ?></td>
								<td class='editable'><input type='text' name='emailaddress' value=<?php print $userdetails[0]['emailaddress']; ?> /></td>
							</tr>
							<tr>
								<td>Reputation *</td>
								<td><?php print $userdetails[0]['reputation']; ?></td>
								<td class='editable userrestriction'><input type='number' name='reputation' value=<?php print $userdetails[0]['reputation']; ?> /></td>
							</tr>
							<tr>	
								<td>User level *</td>
								<td>
									<?php
										switch($userdetails[0]['flag'])
										{
											case 1:
												print "User";
												break;
											case 2:
												print "Moderator";
												break;
											case 3:
												print "Administrator";
												break;												
										}
									?>
								</td>
								<td>
									<select name='flag' class='editable moderatorrestriction' id='flag'>
										<option value='1'>User</option>
										<option value='2'>Moderator</option>
										<option value='3'>Administrator</opt
									</select>
								</td>
							</tr>	
							<tr>	
								<td>Favourite web dev language</td>
								<td>
									<?php 
										$language = $userdetails[0]['favwebdevlanguage'];
										print $language;
									?>
								</td>
								<td>
									<select name='languages' class='editable' id='languages'>
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
									<?php 
										$browser = $userdetails[0]['favbrowser']; 
										print $browser; 
									?>
								</td>
								<td>
									<select name='browsers' class='editable' id='browsers'>
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
								<td>Current projects</td>
								<td><?php print $userdetails[0]['currentprojects']?></td>
								<td>
									<input type='text' id='charsleft'  class='editable' readonly='readonly' size='5' value='500' />
									<span class='editable'>Characters left</span><br />
									<textarea name='currentprojects'  class='editable' id='textlength' rows='5' cols='20'>
										<?php print trim($userdetails[0]['currentprojects']); ?>
									</textarea>
								</td>
							</tr>
							<tr>
								<td colspan='3' class='centre_align'>
								<input type='hidden' name='visitinguserid' id='visitinguserid' value='<?php if(isset($session['userid'])){print $session['userid'];} else {print 0;} ?>' />
								<input type='hidden' name='visitingusername' id='visitingusername' value='<?php if(isset($session['username'])){print $session['username'];} else {print "";} ?>' />
								<input type='hidden' name='visitinguserflag' id='visitinguserflag' value='<?php if(isset($session['flag'])){print $session['flag'];} else {print 0;} ?>' />
								<input type='hidden' name='profileuserid' id='profileuserid' value='<?php print $userdetails[0]['userid']; ?>' />
								<input type='hidden' name='oldusername' value='<?php print $userdetails[0]['username']; ?>' />
								<input type='hidden' name='oldflag' value='<?php print $userdetails[0]['flag']; ?>' />
								<input type='hidden' name='oldreputation' value='<?php print $userdetails[0]['reputation']; ?>' />
									<input type='submit'  class='editable' value='Save' /><br />
									<span class='error_message'>
										<?php 
											if(isset($messages['updateprofileerror'])){print $messages['updateprofileerror'];}
											if(isset($messages['updateprofileother'])){print $messages['updateprofileother'];}
										?>
									</span>
								</td>
							</tr>
						</form>
					</table>
				<td>
			</tr>	
		</table>
	<script language='javascript'>
		$(function(){		
			var textLengthTextArea = $('#textlength');
			var charsLeft = $('#charsleft');
			var flag = $('#flag');
			var languages = $('#languages');
			var browsers = $('#browsers');
			var charsLeft = $('#charsleft');
			var editable = $('.editable');
			var profileFlagValue = <?php print $userdetails[0]['flag'] ?>; //Flag of user's profile.
			var visitorFlagValue = <?php if(isset($session['flag'])) {print $session['flag'];} else {print 0;} ?>; //Flag of visitor to page (zero if not logged in)
			var visitingUserId = parseInt($('#visitinguserid').val());
			var profileUserId = parseInt($('#profileuserid').val());
			var administratorRestriction = $('.adminrestriction');
			var moderatorRestriction = $('.moderatorrestriction');
			var userRestriction = $('.userrestriction');
			var changePassword = $('.changepassword');

			textLengthTextArea.on("keydown", function(e)
			{ 
				var chars = 500 - (textLengthTextArea.val().length);		
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
			
			
			/*Switch statements for setting
			  initial values for drop-down menus.
			  (NB: Must enclose string values from PHP in quotes for switch statement
			  otherwise a ReferenceError will occur)*/
			  //Flag
			switch(profileFlagValue)  //See References.txt (6)
			{
			case 1:
				flag.val(1);
				break;
			case 2:
				flag.val(2);
				break;
			case 3:
				flag.val(3);
				break;					
			}	

			//Favourite language
			switch(<?php print "\"$language\"" ?>)  //See References.txt (6)
			{
			case 'HTML':
				languages.val('HTML');
				break;
			case 'JavaScript':
				languages.val('JavaScript');
				break;
			case 'CSS':
				languages.val('CSS');
				break;	
			case 'PHP':
				languages.val('PHP');
				break;					
			case 'AJAX':
				languages.val('AJAX');
				break;			
			case 'jQuery':
				languages.val('jQuery');
				break;								
			}	
			
			//Favourite browser.
			switch(<?php print "\"$browser\"" ?>)  //See References.txt (6)
			{
			case 'Internet Explorer':
				browsers.val('Internet Explorer');
				break;
			case 'Firefox':
				browsers.val('Firefox');
				break;
			case 'Google Chrome':
				browsers.val('Google Chrome');
				break;	
			case 'Safari':
				browsers.val('Safari');
				break;			
			case 'Opera':
				browsers.val('Opera');
				break;								
			}		
		
		//PERMISSIONS
		//Based on the page visitor, hide certain elements on the page.
		switch(visitorFlagValue)
		{
		case 0:
			hideAllEditableInputs();
			break;
		case 1:
			checkIfViewingOwnProfile();
			break;
		case 2:
			if(profileFlagValue == 1) //If mod visits profile of regular user
			{
				setModeratorRestrictions();
				hidePasswordLink();
			}
			else
			{
				checkIfViewingOwnProfile();
			}
			break;
		case 3:
			if((profileFlagValue == 1) || (profileFlagValue == 2))
			{
				hidePasswordLink();
			}
			else
			{
				if(visitingUserId != profileUserId)
				{
					administratorRestriction.css('display', 'none');
					hidePasswordLink();
				}
			}
			break;
		}
		
		changePassword.on('click', function(){
			if((visitorUserId == 0) || (visitorUserId != profileUserId))
			{
				window.location.replace('/');
			}
		});
	
		/*If the visitor to the page is:
			Not logged in,
			viewing a profile which isn't their own AND whose flag is equal to or greater than their own,
			then they shouldn't see any editable fields (admins are exception to this)
			*/
		  function hideAllEditableInputs()
		  {
			editable.css('display', 'none');
		  }
		  
			//Cannot edit username, password or flag values of regular users.
		  function setModeratorRestrictions()
		  {
			moderatorRestriction.css('display', 'none');
		  }
		  
		  //In addition to mod restrictions, users can't edit reputation values.
		  function setUserRestrictions()
		  {
			userRestriction.css('display', 'none');
		  }
		  
		  function hidePasswordLink()
		  {
			changePassword.css('display', 'none');
		  }
		  
		  function checkIfViewingOwnProfile()
		  {
		  if(visitorFlagValue == profileFlagValue)
			{
				setModeratorRestrictions();
				setUserRestrictions();
			}
			else
			{
				hideAllEditableInputs();
				hidePasswordLink();
			}
		  }
		});	
		

			
		</script>
	</body>
</html>