<?php require_once $_SERVER['DOCUMENT_ROOT'].'/config.php'; ?>

<!DOCTYPE html>

<html>
	<head>
    	<?php require_once $_SERVER['DOCUMENT_ROOT'].'/html/std_head.php' ?>
        <link rel="stylesheet" href="/css/editwindow.css" />
        
        <title>Edit User</title>
        
        <script>
			function cancelEdit() {
				window.close();
			}
			
			function loadFunc() {
				showLoading();
				requestData = 'id='+<?php echo $_REQUEST['id'] ?>;
				request('/services/?service=users&action=view&strict=1',requestData,loadFunc_succeeded,loadFunc_failed);
			}
			function loadFunc_succeeded(ret) {
				data = ret.data;
				data = data[0];
				f = $('#editUserForm')[0];
				f.id.value = data.id;
				f.fname.value = data.fname;
				f.lname.value = data.lname;
				f.email.value = data.email;
				f.phone.value = data.phone;
				f.pass.value = data.pass;
				if(data.student == 1) {
					f.student.selectedIndex = 0;
				} else {
					f.student.selectedIndex = 1;
				}
				perm = 1;
				if(data.perms > 0) {
					perm = data.perms;
				}
				f.perms.selectedIndex = perm-1;
				
				hideLoading();
			}
			function loadFunc_failed(ret) {
				alert("Could not load user.");
			}
			
			function editUser(form) {
				showLoading();
				requestData = $(form).serialize();
				request('/services/?service=users&action=edit',requestData,editUser_succeeded,editUser_failed);
			}
			function editUser_succeeded(ret) {
				window.opener.editUser_succeeded();
				window.close();
			}
			function editUser_failed(ret) {
				alert("Could not edit user.");
			}
			
			function showLoading() {
				document.getElementById('content').style.display = 'none';
				loadingImg = '<img id="loadingImg" src="/img/ajax-loader-1.gif">';
				$(document.body).append(loadingImg);
			}
			function hideLoading() {
				$(document.getElementById('loadingImg')).remove();
				document.getElementById('content').style.display = 'block';
			}
			
			function generatePassword() {
				maxnum = 9999;
				minnum = 1000;
				document.getElementById('pass').value = Math.floor(Math.random()*(maxnum-minnum+1))+minnum;
			}
		</script>
    </head>
    
    <body onLoad="loadFunc()">
    	
        <div id="content">
            
            <form method="post" onSubmit="editUser(this); return false" id="editUserForm">
                <input type="hidden" name="id" value="" />
                <table>
                    <tr>
                        <td>First Name:</td><td><input type="text" name="fname" value="" /></td>
                    </tr>
                    <tr>
                        <td>Last Name:</td><td><input type="text" name="lname" value="" /></td>
                    </tr>
                    <tr>
                        <td>Email:</td><td><input type="email" name="email" value="" /></td>
                    </tr>
                    <tr>
                        <td>Phone Number:</td><td><input type="tel" name="phone" value="" /></td>
                    </tr>
                    <tr>
                        <td>Student:</td>
                        <td>
                            <select name="student">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
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
                        <td>Password:</td><td><input type="text" name="pass" id="pass" value="" />
                        <input type="button" value="Generate password" onClick="generatePassword()" /></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Save" /></td><td><input type="button" value="Cancel" onClick="cancelEdit()" /></td>
                    </tr>
                </table>
            </form>
        
        </div>
    </body>
</html>