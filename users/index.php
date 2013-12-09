<?php require_once $_SERVER['DOCUMENT_ROOT'].'/config.php' ?>

<!DOCTYPE html>

<html>
	<head>
    	<?php require_once $_SERVER['DOCUMENT_ROOT'].'/html/std_head.php' ?>
        <title>SNHS Users</title>
        <script src="/js/table_builder.js"></script>
        
        <script>
			viewtype = "students";
			function showLoading() {
				loadingImg = '<img src="/img/ajax-loader-1.gif">';
				document.getElementById('userlist').innerHTML = loadingImg;
			}
			
			function checkFields(form) {
				f = form;
				if(f.fname.value=="") {
					alert("First Name is empty.");
					return false;
				}
				if(f.lname.value=="") {
					alert("Last Name is empty.");
					return false;
				}
				if(f.pass.value=="") {
					alert("Password is empty.");
					return false;
				}
				
				return true;
			}
			
			function addUser(form) {
				if(checkFields(form)==false) return false;
				requestData = $(form).serialize();
				request("/services/?service=users&action=add",requestData,addUser_succeeded,addUser_failed);
			}
			function addUser_succeeded(ret) {
				f = $('#addUserForm')[0];
				f.fname.value = "";
				f.lname.value = "";
				f.email.value = "";
				f.phone.value = "";
				f.pass.value = "";
				//$('#addUserForm').student.value = "";
				//$('#addUserForm').fname.value = "";
				showUsers();
			}
			function addUser_failed(ret) {
				if(ret.data=="DUPLICATE") {
					message = "The user already exists.";
				} else {
					message = ret.data;
				}
				alert("Could not add user.\n\nError:\n"+message);
			}
			
			function editUser(id) {
				popw = 250;
				poph = 300;
				
				w = document.body.clientWidth;
				h = document.body.clientHeight;
				x = window.screenX;
				y = window.screenY;
				
				left = ((w-popw)/2)+y;				
				top = ((h-poph)/2)+x;
				window.open("/users/edituser.php?id="+id,"edituser","location=0,menubar=0,toolbar=0,width="+popw+",height="+poph+",left="+left+",top="+top);
			}
			function editUser_succeeded(ret) {
				showUsers();
			}
			
			function deleteUser(id,lname,fname) {
				ans = confirm("Delete user: "+lname+", "+fname+"?");
				if(ans) {
					showLoading();
					requestData = 'id='+id;
					request("/services/?service=users&action=delete",requestData,deleteUser_succeeded,deleteUser_failed);
				}
			}
			function deleteUser_succeeded(ret) {
				showUsers();
			}
			function deleteUser_failed(ret) {
				alert("Could not delete user.");
			}
			
			function showUsers() {
				showLoading();
				if(viewtype=="admin") {
					showAdmin();
				} else {
					showStudents();
				}
				//request("/services/?service=users&action=view&strict=0",null,showUsers_succeeded,showUsers_failed);
			}
			function showStudents() {
				viewtype = "students";
				showLoading();
				requestData = { findby:'student', student:1 };
				request("/services/?service=users&action=view&strict=0",requestData,showUsers_succeeded,showUsers_failed);
				$('#btnShowStudents').addClass('selected');
				$('#btnShowAdmin').removeClass('selected');
			}
			function showAdmin() {
				viewtype = "admin";
				showLoading();
				requestData = { findby:'student', student:0 };
				request("/services/?service=users&action=view&strict=0",requestData,showUsers_succeeded,showUsers_failed);
				$('#btnShowStudents').removeClass('selected');
				$('#btnShowAdmin').addClass('selected');
			}
			function showUsers_succeeded(ret) {
				rowTemplate = [
					{name:"Options", string:'<input type="button" value="Delete" class="left" onClick="deleteUser(#id#,\'#lname#\',\'#fname#\')" /><input type="button" value="Edit" class="right" onClick="editUser(#id#)" />'},
					{name:"Username", key:"username"},
					{name:"Last Name", key:"lname"},
					{name:"First Name", key:"fname"},
					{name:"Email", key:"email"},
					{name:"Grade", key:"grade"},
					{name:"Password", key:"pass"}
				];
				table = build_table(rowTemplate, ret.data);
				document.getElementById('userlist').innerHTML = table;
			}
			function showUsers_failed(ret) {
				alert("Could not access users.");
			}
			
			function generatePassword() {
				maxnum = 9999;
				minnum = 1000;
				document.getElementById('pass').value = Math.floor(Math.random()*(maxnum-minnum+1))+minnum;
			}
		</script>
    </head>
    
    <body onLoad="showStudents()">
    	<div id="main">
			<style>
				div#divMenu li.users { background-color:orange }
			</style>
			<?php include $_SERVER['DOCUMENT_ROOT'].'/html/header.php' ?>
            
            <div id="content">
            
            	<form method="post" onSubmit="addUser(this); return false" id="addUserForm">
                    <table>
                        <tr>
                            <td>First Name:</td><td><input type="text" name="fname" /></td>
                            <td>Last Name:</td><td><input type="text" name="lname" /></td>
                            <td>Email:</td><td><input type="email" name="email" /></td>
                        </tr>
                        <tr>
                            <td>Phone Number:</td><td><input type="tel" name="phone" /></td>
                            <td>Student:</td>
                            <td>
                            	<select name="student">
                                	<option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </td>
                            <td>Perms:</td>
                            <td>
                            	<select name="perms">
                                	<option value="1">Student</option>
                                    <option value="2">Officer</option>
                                    <option value="3">Admin</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                        	<td>Password:</td><td><input type="text" name="pass" id="pass" /><input type="button" value="Generate password" onClick="generatePassword()" /></td>
                            <td>Grade:</td>
                            <td>
                            	<select name="grade">
                                	<option value="9">Freshman</option>
                                    <option value="10">Sophomore</option>
                                    <option value="11">Junior</option>
                                    <option value="12">Senior</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="submit" value="Add User" /></td>
                        </tr>
                    </table>
                </form>
                
                <div>
                	<h3 style="display:inline;">View: </h3><input id="btnShowStudents" type="button" value="Students" onClick="showStudents()" /><input id="btnShowAdmin" type="button" value="Admin" onClick="showAdmin()" />
                </div>
                
                <div id="userlist">
                
                </div>
            
            </div>
            
            <?php include $_SERVER['DOCUMENT_ROOT'].'/html/footer.php' ?>
        </div>
    </body>
</html>