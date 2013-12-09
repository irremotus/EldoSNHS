function showLoading() {
	document.getElementById('content').style.display = 'none';
	loadingImg = '<img id="loadingImg" src="/img/ajax-loader-1.gif">';
	$(document.getElementById('content')).after(loadingImg);
}
function hideLoading() {
	$(document.getElementById('loadingImg')).remove();
	document.getElementById('content').style.display = 'block';
}

var users;
var events;
var points;

function makeReport(form) {
	showLoading();
	
	if(1) {
		r1 = request("/services/?service=points&action=view",null,null,null);
		requestData = { findby:"student", student:1 };
		r2 = request("/services/?service=users&action=view&strict=0",requestData,null,null);
		r3 = request("/services/?service=events&action=view&strict=0",null,null,null);
		$.when(r1,r2,r3).done(function(ret1,ret2,ret3) {
			points = JSON.parse(ret1[0]).data;
			users = JSON.parse(ret2[0]).data;
			events = JSON.parse(ret3[0]).data;
			showReport(form);
		});
	}
}

function getPoints_succeeded(ret) {
	points = ret.data;
}

function getPointsStudent_succeeded(ret) {

}

function getPoints_failed(ret) {
	alert("Could not access points.");
}

function showReport(form) {
	f = $(form).serializeArray();
	showName = 0;
	showPoints = 0;
	showEmail = 0;
	showGTPoints = 0;
	GTPoints = 0;
	showGrade = 0;
	showFreshmen = 0;
	showSophomores = 0;
	showJuniors = 0;
	showSeniors = 0;
	showBorders = 0;
	for (i = 0; i < f.length; i++) {
		name = f[i]['name'];
		
		if (name == "showName")
			showName = 1;
		else if (name == "showEmail")
			showEmail = 1;
		else if (name == "showPoints")
			showPoints = 1;
		else if (name == "showGTPoints")
			showGTPoints = 1;
		else if (name == "GTPoints")
			GTPoints = f[i]['value'];
		else if (name == "showGrade")
			showGrade = 1;
		else if (name == "showFreshmen")
			showFreshmen = 1;
		else if (name == "showSophomores")
			showSophomores = 1;
		else if (name == "showJuniors")
			showJuniors = 1;
		else if (name == "showSeniors")
			showSeniors = 1;
		else if (name == "showBorders")
			showBorders = 1;
	}
	
	table = "";
	for(i=0; i<users.length; i++) {
		uevents = new Object();
		ueventsc = 0;
		for(j=0; j<events.length; j++) {
			found = false;
			for(k=0; k<points.length; k++) {
				if(points[k]['uid']==users[i].id && points[k]['eid']==events[j]['id']) {
					uevents[j] = new Object();
					uevents[j]['points'] = points[k]['points'];
					found = true;
				}
			}
			if(! found) {
				uevents[j] = new Object();
				uevents[j]['points'] = 0;
			}
		}
		users[i].events = uevents;
	}
	
	style = "<style>";
	if (showBorders)
		style += 'table { border: 1px solid black; border-collapse: collapse; } td { border: 1px solid black; }';
	style += "</style>";
	table += style;
	table += "<table>";
	table += "<tr>";
	if (showName)
		table += "<th>Student</th>";
	if (showPoints)
		table += "<th>Total Points</th>";
	if (showEmail)
		table += "<th>Email</th>";
	if (showGrade)
		table += "<th>Grade</th>";
	table += "</tr>";
	elid = 0;
	for(i=0; i<users.length; i++) {
		users[i].pointtotal = 0;
		for(ii=0; ii<events.length; ii++) {
			if(events[ii] != undefined) {
				users[i].pointtotal += parseInt(users[i].events[ii].points);
			}
		}
		if (showGTPoints && users[i].pointtotal < GTPoints)
			continue;
		if (showFreshmen || showSophomores || showJuniors || showSeniors) {
			if (users[i].grade == 9 && !showFreshmen)
				continue;
			else if (users[i].grade == 10 && !showSophomores)
				continue;
			else if (users[i].grade == 11 && !showJuniors)
				continue;
			else if (users[i].grade == 12 && !showSeniors)
				continue;
			else if (users[i].grade == 0)
				continue;
		}
		table += "<tr>";
		if (showName)
			table += "<td>"+users[i].lname+", "+users[i].fname+"</td>";
		if (showPoints)
			table += "<td>"+users[i].pointtotal+"</td>";
		if (showEmail)
			table += "<td>"+users[i].email+"</td>";
		if (showGrade)
			table += "<td>"+users[i].grade+"</td>";
		table += "</tr>";
	}
	table += "</table>";
	
	//document.getElementById('reportbox').innerHTML = table;
	w = window.open("", "SNHS Report", "width=800, height=800");
	if (w == null || w.closed)
		alert("The report window was blocked by a pop-up blocker. Please allow pop-ups from www.eldosnhs.com to use the reports feature.");
	else {
		w.focus();
		buttons = "";
		w.document.write('<link rel="stylesheet" href="/css/print.css" />');
		buttons += '<input type="button" class="noprint" value="Print" onClick="window.print();" />';
		buttons += '<input type="button" class="noprint" value="Close" onClick="window.close()" />';
		w.document.write(buttons);
		w.document.write("<h2>SNHS Report</h2>");
		ftext = "<h3>Showing ";
		first = 1;
		if (showFreshmen || showSophomores || showJuniors || showSeniors) {
			if (showFreshmen) {
				 if (!first) {
				 	ftext += ", ";
				 }
				 first = 0;
				 ftext += "Freshmen";
			}
			if (showSophomores) {
				 if (!first) {
				 	ftext += ", ";
				 }
				 first = 0;
				 ftext += "Sophomores";
			}
			if (showJuniors) {
				 if (!first) {
				 	ftext += ", ";
				 }
				 first = 0;
				 ftext += "Juniors";
			}
			if (showSeniors) {
				 if (!first) {
				 	ftext += ", ";
				 }
				 first = 0;
				 ftext += "Seniors";
			}
		}
		else {
			ftext += "students";
		}
		if (showGTPoints)
			ftext += " who have at least "+GTPoints+" points";
		ftext += "</h3>";
		w.document.write(ftext);
		w.document.write(table);
		w.document.write(buttons);
	}
	
	hideLoading();
}

function getUsers_succeeded(ret) {
	users = ret.data;
}

function getUsers_failed(ret) {
	alert("Could not access users.");
}

function getEvents_succeeded(ret) {
	events = ret.data;
}

function getEvents_failed(ret) {
	alert("Could not access events.");
}