<?php require_once $_SERVER['DOCUMENT_ROOT'].'/config.php' ?>

<?php
	$student = false;
	if(! Permissions::hasPerm(Permissions::OFFICER) && Permissions::hasPerm(Permissions::STUDENT)) {
		$student = true;
	}
?>

<!DOCTYPE html>

<html>
	<head>
    	<?php require_once $_SERVER['DOCUMENT_ROOT'].'/html/std_head.php' ?>
        <title>SNHS Points</title>
        <script src="/js/table_builder.js"></script>
        
        <script>
			$(document).ready(function(e) {
                showPoints(1);
            });
			
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
				
				/*$(".points").parent().on("focusout",null,null,function() {
					if($(this).parent().children().length > 1) {
						par = $(this);
						cancelSavePoints(par);
					}
				});*/
				
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
			function showPoints(s,page) {
				page = (page!=null) ? page : 0;
				showLoading();
				if(s) {
					r1 = request("/services/?service=points&action=view",null,null,null);
					requestData = { findby:"student", student:1 };
					r2 = request("/services/?service=users&action=view&strict=0",requestData,null,null);
					r3 = request("/services/?service=events&action=view&strict=0",null,null,null);
					$.when(r1,r2,r3).done(function(ret1,ret2,ret3) {
						points = JSON.parse(ret1[0]).data;
						users = JSON.parse(ret2[0]).data;
						events = JSON.parse(ret3[0]).data;
						showPage(page);
					});
				} else {
					r1 = request("/services/?service=points&action=view",null,null,null);
					$.when(r1).done(function(ret1) {
						points = JSON.parse(ret1).data;
						showPage(page);
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
			
			function showPage(i) {
				ea = i*7;
				eb = ea+7;
				doShowPoints(ea,eb,i);
			}
			function doShowPoints(ea,eb,page) {
				for(i=0; i<users.length; i++) {
					uevents = new Object();
					ueventsc = 0;
					for(j=0; j<events.length; j++) {
						found = false;
						for(k=0; k<points.length; k++) {
							if(points[k]['uid']==users[i].id && points[k]['eid']==events[j]['id']) {
								uevents[j] = new Object();
								uevents[j]['event'] = events[j];
								uevents[j]['points'] = points[k]['points'];
								uevents[j]['pid'] = points[k]['id'];
								found = true;
							}
						}
						if(! found) {
							uevents[j] = new Object();
							uevents[j]['event'] = events[j];
							uevents[j]['points'] = 0;
						}
					}
					users[i].events = uevents;
				}
				
				table = "Page: ";
				numpages = Math.ceil(events.length / 7);
				for(i=0; i<numpages; i++) {
					if(i==page) {
						l = '<input type="button" style="width:30px; height:30px; background-color:yellow;" onclick="showPoints(0,'+i+')" value="'+(i+1)+'" />';
					} else {
						l = '<input type="button" style="width:30px; height:30px; background-color:;" onclick="showPoints(0,'+i+')" value="'+(i+1)+'" />';
					}
					table += l;
				}
				
				table += "<table>";
				table += "<tr>";
				table += "<th>Student</th>";
				for(i=ea; i<eb; i++) {
					if(i<events.length) {
						table += "<th>"+events[i].name+'<br>'+events[i].date+"</th>";
					} else {
						table += "<th></th>";
					}
				}
				table += "<th>Total</th>";
				table += "</tr>";
				elid = 0;
				for(i=0; i<users.length; i++) {
					table += "<tr>";
					table += "<td>"+users[i].lname+", "+users[i].fname+"</td>";
					users[i].pointtotal = 0;
					for(ii=0; ii<events.length; ii++) {
						if(events[ii] != undefined) {
							users[i].pointtotal += parseInt(users[i].events[ii].points);
						}
					}
					for(j=ea; j<eb; j++) {
						if(events[j] != undefined) {
							uid = users[i]['id'];
							eid = events[j]['id'];
							id = users[i].events[j]['pid'];
							if(users[i].events[j] != undefined) {
								ps = users[i].events[j].points;
							} else {
								ps = 0;
							}
							<?php if(!$student) { ?>
							td = '<td><input type="text" id="'+elid+'" class="points" data-uid="'+uid+'" data-eid="'+eid+'" name="'+id+'" value="'+ps+'" style="width:50px; text-align:center;" /></td>';
							<?php } else { ?>
							td = '<td>'+ps+'</td>';
							<?php } ?>
							table += td;
							elid++;
						} else {
							table += '<td></td>';
						}						
					}
					table += "<td>"+users[i].pointtotal+"</td>";
					table += "</tr>";
				}
				table += "</table>";
				
				document.getElementById('pointslist').innerHTML = table;
				
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
		</script>
        <style>
			div#divMenu li.points { background-color:orange }
		</style>
    </head>
    
    <body onLoad="">
    	<div id="main">
			<style>
				div#divMenu li.points { background-color:orange }
			</style>
			<style>
				table {
					width:100%;
				}
				td {
					text-align:center;
				}
			</style>
			<?php include $_SERVER['DOCUMENT_ROOT'].'/html/header.php' ?>
            
            <div id="content">
            
            	
                	<form id="pointslistform" method="post" onSubmit="return false">
                    	<div id="pointslist">
                        
                        </div>
                    </form>
                
            
            </div>
            
            <?php include $_SERVER['DOCUMENT_ROOT'].'/html/footer.php' ?>
        </div>
    </body>
</html>