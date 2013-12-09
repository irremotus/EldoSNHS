<?php require_once $_SERVER['DOCUMENT_ROOT'].'/config.php' ?>

<!DOCTYPE html>

<html>
	<head>
    	<?php require_once $_SERVER['DOCUMENT_ROOT'].'/html/std_head.php' ?>
        <title>SNHS Reports</title>
        <script src="/js/table_builder.js"></script>
        <script src="reports.js"></script>
    </head>
    
    <body onLoad="">
    	<div id="main">
			<style>
				div#divMenu li.reports { background-color:orange }
			</style>
			<?php include $_SERVER['DOCUMENT_ROOT'].'/html/header.php' ?>
            
            <div id="content">
            
            	<form method="post" onSubmit="makeReport(this); return false" id="createReportForm">
                    <table>
                        <tr>
                        	<td><h3>Items to show on report</h3></td>
                        </tr>
                        <tr>
                            <td>
                            <input type="checkbox" name="showName" id="chkName" value="1" />
                            <label for="chkName"> Name</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <input type="checkbox" name="showEmail" id="chkEmail" value="1" />
                            <label for="chkEmail"> Email</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <input type="checkbox" name="showPoints" id="chkPoints" value="1" />
                            <label for="chkPoints"> Total Points</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <input type="checkbox" name="showGTPoints" id="chkGTPoints" value="1" />
                            <label for="chkGTPoints"></label> Only show users with greater than <input type="number" style="width:50px;" name="GTPoints" id="numGTPoints" value="100" /> points
                            </td>
                        </tr>
                        
                        <tr>
                            <td><input type="submit" value="Generate Report" /></td>
                        </tr>
                    </table>
                </form>
                
                <div id="reportbox">
                
                </div>
            
            </div>
            
            <?php include $_SERVER['DOCUMENT_ROOT'].'/html/footer.php' ?>
        </div>
    </body>
</html>