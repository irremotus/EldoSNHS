function pointsDoneLoading() {
	$(".points").parent().on("focusin",null,null,function() {
		if($(this).children().length == 1) {
			buttons = '<input type="button" value="Save" onclick="savePoints(this.parentNode.children[0])" /><input type="button" value="Cancel" onclick="cancelSavePoints(this.parentNode)" />';
			$(this).children().eq(0).after(buttons);
		}
	});

	highlightcolor = "#F33";
	centercolor = "yellow";
	$(".points").parent().hover(function(e) {
		uid = $(this).children().eq(0).attr("data-uid");
		eid = $(this).children().eq(0).attr("data-eid");
		$("[data-uid="+uid+"]").css("background-color",highlightcolor);
		$("[data-eid="+eid+"]").css("background-color",highlightcolor);
		//$("[data-uid="+uid+"]").parent().css("background-color",highlightcolor);
		//$("[data-eid="+eid+"]").parent().css("background-color",highlightcolor);
		$(this).children().eq(0).css("background-color",centercolor);
	}, function(e) {
		uid = $(this).children().eq(0).attr("data-uid");
		eid = $(this).children().eq(0).attr("data-eid");
		$("[data-uid="+uid+"]").css("background-color","");
		$("[data-eid="+eid+"]").css("background-color","");
		//$("[data-uid="+uid+"]").parent().css("background-color","");
		//$("[data-eid="+eid+"]").parent().css("background-color","");
		$(this).css("background-color","");
	});
}

function savePoints(inp) {
	par = $(inp).parent();
	id = inp.name;
	elid = inp.id;
	uid = inp.getAttribute("data-uid");
	eid = inp.getAttribute("data-eid");
	ps = inp.value;
	requestData = "id="+id+"&uid="+uid+"&eid="+eid+"&points="+ps+"&elid="+elid;
	request("/services/?service=points&action=edit&strict=1",requestData,savePoints_succeeded,savePoints_failed,true,true);
	loadingImg = '<img id="loadingImg" src="/img/ajax-loader-2.gif">';
	el = document.getElementById(elid);
	$(el).parent().children().eq(2).remove();
	$(el).parent().children().eq(1).replaceWith(loadingImg);
}

function savePoints_succeeded(ret) {
	savedImg = '<img id="savedImg" style="position:relative; left:-15px;" src="/img/tick_64.png" height="15" width="15">';
	elid = ret.data.elid;
	el = document.getElementById(elid);
	el.name = ret.data.id;
	//$(el).parent().children().eq(1).replaceWith(savedImg);
	$(el).parent().children().eq(1).remove();
	//$(el).after(savedImg).show(2000).remove();
}
function savePoints_failed(ret) {
	alert("Could not save points.");
}
function cancelSavePoints(par) {
	$(par).children().eq(2).remove();
	$(par).children().eq(1).remove();
}

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
	
	table += "<table>";
	table += "<tr>";
	if (showName)
		table += "<th>Student</th>";
	if (showPoints)
		table += "<th>Total Points</th>";
	if (showEmail)
		table += "<th>Email</th>";
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
		table += "<tr>";
		if (showName)
			table += "<td>"+users[i].lname+", "+users[i].fname+"</td>";
		if (showPoints)
			table += "<td>"+users[i].pointtotal+"</td>";
		if (showEmail)
			table += "<td>"+users[i].email+"</td>";
		table += "</tr>";
	}
	table += "</table>";
	
	//document.getElementById('reportbox').innerHTML = table;
	w = window.open("", "SNHS Report", "width=500, height=800");
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
		if (showGTPoints)	
			w.document.write("<h3>Only showing students who have more than "+GTPoints+" points</h3>");
		w.document.write(table);
		w.document.write(buttons);
	}
	
	pointsDoneLoading();
	
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