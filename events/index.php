<?php require_once $_SERVER['DOCUMENT_ROOT'].'/config.php' ?>

<!DOCTYPE html>

<html>
	<head>
    	<?php require_once $_SERVER['DOCUMENT_ROOT'].'/html/std_head.php' ?>
        <title>SNHS Events</title>
        <link rel="stylesheet" href="/css/events.css" />
        <script src="/js/table_builder.js"></script>
        
        <script>
			function showLoading() {
				loadingImg = '<img src="/img/ajax-loader-1.gif">';
				document.getElementById('eventlist').innerHTML = loadingImg;
			}
			
			function checkFields(form) {
				f = form;
				if(f.name.value=="") {
					alert("Name is empty.");
					return false;
				}
				if(f.date.value=="") {
					alert("Date is empty.");
					return false;
				}
				if(f.pointvalue.value=="") {
					alert("Point Value is empty.");
					return false;
				}
				
				return true;
			}
			
			function addEvent(form) {
				if(checkFields(form)==false) return false;
				requestData = $(form).serialize();
				request("/services/?service=events&action=add",requestData,addEvent_succeeded,addEvent_failed);
			}
			function addEvent_succeeded(ret) {
				f = $('#addEventForm')[0];
				f.name.value = "";
				f.date.value = "";
				f.details.value = "";
				f.pointtype.value = "";
				f.pointvalue.value = "";
				f.def.value = "";
				showEvents();
			}
			function addEvent_failed(ret) {
				if(ret.data=="DUPLICATE") {
					message = "The event already exists.";
				} else {
					message = ret.data;
				}
				alert("Could not add event.\n\nError:\n"+message);
			}
			
			function editEvent(id) {
				w = 250;
				h = 300;
				left = (screen.width/2)-(w/2);
				top = (screen.height/2)-(h/2);
				win = window.open("/events/editevent.php?id="+id,"editevent","location=0,menubar=0,toolbar=0,width="+w+",height="+h+",left="+left+",top="+top);
			}
			function editEvent_succeeded(ret) {
				showEvents();
			}
			function focusWin() {
				win.focus();
			}
			
			function deleteEvent(id,lname,fname) {
				ans = confirm("Delete event: "+lname+", "+fname+"?");
				if(ans) {
					showLoading();
					requestData = 'id='+id;
					request("/services/?service=events&action=delete",requestData,deleteEvent_succeeded,deleteEvent_failed);
				}
			}
			function deleteEvent_succeeded(ret) {
				showEvents();
			}
			function deleteEvent_failed(ret) {
				alert("Could not delete event.");
			}
			
			function showEvents() {
				showLoading();
				request("/services/?service=events&action=view&strict=0",null,showEvents_succeeded,showEvents_failed);
			}
			function showEvents_succeeded(ret) {
				rowTemplate = [
					{name:"Options", string:'<input type="button" value="Delete" class="left" onClick="deleteEvent(#id#,\'#name#\',\'#date#\')" /><input type="button" value="Edit" class="right" onClick="editEvent(#id#)" />'},
					{name:"Name", key:"name"},
					{name:"Date", key:"date"},
					{name:"Details", key:"details"},
					{name:"Point Type", key:"pointtype"},
					{name:"Point Value", key:"pointvalue"},
					{name:"Point Default", key:"def"}
				];
				table = build_table(rowTemplate, ret.data);
				document.getElementById('eventlist').innerHTML = table;
			}
			function showEvents_failed(ret) {
				alert("Could not access events.");
			}
		</script>
    </head>
    
    <body onLoad="showEvents()">
    	<div id="main">
			<style>
				div#divMenu li.events { background-color:orange }
			</style>
			<?php include $_SERVER['DOCUMENT_ROOT'].'/html/header.php' ?>
            
            <div id="content">
            
            	<form method="post" onSubmit="addEvent(this); return false" id="addEventForm">
                    <table>
                        <tr>
                            <td>Name:</td><td><input type="text" name="name" /></td>
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
                            <td>Point Value:</td><td><input type="text" name="pointvalue" /></td>
                            <td>Point Default:</td><td><input type="text" name="def" /></td>
                        </tr>
                        <tr>
                            <td><input type="submit" value="Add Event" /></td>
                        </tr>
                    </table>
                </form>
                
                <div id="eventlist">
                
                </div>
            
            </div>
            
            <?php include $_SERVER['DOCUMENT_ROOT'].'/html/footer.php' ?>
        </div>
    </body>
</html>