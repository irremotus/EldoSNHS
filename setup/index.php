<?php require_once $_SERVER['DOCUMENT_ROOT'].'/config.php' ?>

<!DOCTYPE html>

<html>
	<head>
    	<?php require_once $_SERVER['DOCUMENT_ROOT'].'/html/std_head.php' ?>
        <title>SNHS Setup</title>
        
        <script>
			function showLoading() {
				loadingImg = '<img src="/img/ajax-loader-1.gif">';
				document.getElementById('userlist').innerHTML = loadingImg;
			}
			
			
		</script>
    </head>
    
    <body onLoad="">
    	<div id="main">
			<style>
				div#divMenu li.setup { background-color:orange }
			</style>
			<?php include $_SERVER['DOCUMENT_ROOT'].'/html/header.php' ?>
            
            <div id="content">
            
            	<div id="users">
                	<h3>Import users from file</h3>
                    <h4>Import file must contain 2 or 3 columns, in the order: Last Name, First Name, Email.</h4>
                    <h4>Last Name and First Name are required, Email is optional.</h4>
                    <form action="/services/?service=setup&action=importusers" method="post" enctype="multipart/form-data">
                    	<table>
                        	<tr>
                            	<td>File: </td>
                                <td><input type="file" name="userfile" /></td>
                            </tr>
                            <tr>
                            	<td colspan="2">
                                	<input type="submit" value="Import Users" />
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                
                
            
            </div>
            
            <?php include $_SERVER['DOCUMENT_ROOT'].'/html/footer.php' ?>
        </div>
    </body>
</html>