<?php require_once $_SERVER['DOCUMENT_ROOT'].'/config.php' ?>

<!DOCTYPE html>

<html>
	<head>
    	<?php require_once $_SERVER['DOCUMENT_ROOT'].'/html/std_head.php' ?>
        <title>SNHS Checkin</title>
        <script>
			function checkin(form) {
				request("/services/?service=checkin&action=checkin",$(form).serialize(),checkin_succeeded,checkin_failed);
			}
			function checkin_succeeded(ret) {
				alert("Successfully Checked In");
				document.getElementById('username').value = "";
				document.getElementById('password').value = "";
				document.getElementById('username').focus();
			}
			function checkin_failed(ret) {
				alert("Checkin failed. Wrong username or password.");
			}
			
			function checkinSetup(form) {
				request("/services/?service=checkin&action=setup",$(form).serialize(),checkinSetup_succeeded,checkinSetup_failed);
			}
			function checkinSetup_succeeded(ret) {
				document.getElementById('setup').style.display = "none";
				document.getElementById('checkin').style.display = "block";
				document.getElementById('divMenu').style.display = "none";
				document.getElementById('logout').style.display = "none";
				document.getElementById('username').focus();
			}
			function checkinSetup_failed(ret) {
				alert("Setup failed");
			}
			
			function endCheckin() {
				location = "/";
			}
		</script>
    </head>
    
    <body onLoad="">
    	<div id="main">
			<style>
				div#divMenu li.checkin { background-color:orange }
			</style>
			<?php include $_SERVER['DOCUMENT_ROOT'].'/html/header.php' ?>
            
            <div id="content">
            
            	<div id="setup">
                	<h3>Checkin Setup</h3>
                    <form method="post" onSubmit="checkinSetup(this); return false;">
                    	<table>
                        	<tr>
                            	<td>Checkin event:</td>
                                <td>
                                	<select name="event" id="event">
                                    <?php
										if(Permissions::hasPerm(Permissions::STUDENT)) {
											$events = new Event();
											$events->find('id','',false);
											if($events->numresults>0) {
												foreach($events->results as $event) {
													echo '<option value="'.$event->id.'">'.$event->name.' | '.$event->date.'</option>';
												}
											}
										}
									?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="2"><input type="submit" value="Begin Checkin" /></td>
                            </tr>
                        </table>
                    </form>
                </div>
                
                <div id="checkin" style="display:none;">
                	<h3>Enter your username and password to checkin</h3>
                    <form method="post" onSubmit="checkin(this); return false;">
                    	<table>
                        	<tr>
                            	<td>Username:</td>
                                <td><input type="text" name="username" id="username" /></td>
                            </tr>
                            <tr>
                            	<td>Password:</td>
                                <td><input type="password" name="pass" id="password" /></td>
                            </tr>
                            <tr>
                            	<td colspan="2"><input type="submit" value="Checkin" /></td>
                            </tr>
                            <tr>
                            	<td><br></td>
                            </tr>
                            <tr>
                            	<td colspan="2"><input type="button" value="End Checkin" onClick="endCheckin()" /></td>
                            </tr>
                        </table>
                    </form>
                </div>
            
            </div>
            
            <?php include $_SERVER['DOCUMENT_ROOT'].'/html/footer.php' ?>
        </div>
    </body>
</html>