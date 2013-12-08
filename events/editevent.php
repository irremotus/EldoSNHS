<?php require_once $_SERVER['DOCUMENT_ROOT'].'/config.php'; ?>

<!DOCTYPE html>

<html>
	<head>
    	<?php require_once $_SERVER['DOCUMENT_ROOT'].'/html/std_head.php' ?>
        <link rel="stylesheet" href="/css/editwindow.css" />
        
        <title>Edit Event</title>
        
        <script>
			function cancelEdit() {
				window.close();
			}
			
			function loadFunc() {
				showLoading();
				requestData = 'id='+<?php echo $_REQUEST['id'] ?>;
				request('/services/?service=events&action=view&strict=1',requestData,loadFunc_succeeded,loadFunc_failed);
			}
			function loadFunc_succeeded(ret) {
				data = ret.data;
				data = data[0];
				f = $('#editEventForm')[0];
				f.id.value = data.id;
				f.name.value = data.name;
				f.date.value = data.date;
				f.details.innerHTML = data.details;
				f.pointvalue.value = data.pointvalue;
				f.def.value = data.def;
				if(data.pointtype == 'points') {
					f.pointtype.selectedIndex = 0;
				} else {
					f.student.selectedIndex = 1;
				}
				
				hideLoading();
			}
			function loadFunc_failed(ret) {
				alert("Could not load event.");
			}
			
			function editEvent(form) {
				showLoading();
				requestData = $(form).serialize();
				request('/services/?service=events&action=edit',requestData,editEvent_succeeded,editEvent_failed);
			}
			function editEvent_succeeded(ret) {
				window.opener.editEvent_succeeded();
				window.close();
			}
			function editEvent_failed(ret) {
				alert("Could not edit event.");
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
			
			function refocus() {
				window.blur();
				window.opener.focusWin();
			}
		</script>
    </head>
    
    <body onLoad="loadFunc()" onBlur="refocus()">
    	
        <div id="content">
            
            <form method="post" onSubmit="editEvent(this); return false" id="editEventForm">
                <input type="hidden" name="id" />
                <table>
                    <tr>
                        <td>Name:</td><td><input type="text" name="name" /></td>
                    </tr>
                    <tr>
                        <td>Date:</td><td><input type="date" name="date" /></td>
                    </tr>
                    <tr>
                        <td>Details:</td><td><textarea name="details"></textarea></td>
                    </tr>
                    <tr>
                        <td>Point Type:</td><td><select name="pointtype">
                            <option value="points">Points</option>
                            <option value="completion">Completion</option>
                        </select></td>
                    </tr>
                    <tr>
                        <td>Point Value:</td><td><input type="text" name="pointvalue" /></td>
                    </tr>
                    <tr>
                        <td>Point Default:</td><td><input type="text" name="def" /></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Save" /></td><td><input type="button" value="Cancel" onClick="cancelEdit()" /></td>
                    </tr>
                </table>
            </form>
        
        </div>
    </body>
</html>