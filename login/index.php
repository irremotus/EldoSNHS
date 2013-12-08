<?php require_once $_SERVER['DOCUMENT_ROOT'].'/config.php' ?>

<!DOCTYPE html>

<html>
	<head>
    	<?php require_once $_SERVER['DOCUMENT_ROOT'].'/html/std_head.php' ?>
        <title>SNHS Login</title>
        <script src="/js/table_builder.js"></script>
        
        <script>
			function login(form) {
				requestData = $(form).serialize();
				request("/services/?service=login&action=login",requestData,login_succeeded,login_failed);
			}
			function login_succeeded(ret) {
				location = "/";
			}
			function login_failed(ret) {
				alert("Wrong username or password.");
			}
			
			function setFocus() {
				document.getElementById('username').focus();
			}
		</script>
    </head>
    
    <body onLoad="setFocus()">
    	<div id="main">
			<style>
				div#divMenu li.login { background-color:orange }
			</style>
			<?php include $_SERVER['DOCUMENT_ROOT'].'/html/header.php' ?>
            
            <div id="content">
            
                <form onSubmit="login(this); return false;">
                    <table>                   
                        <tr>
                            <td>Username:</td><td><input type="text" name="username" id="username" /></td>
                        </tr>
                        <tr>
                            <td>Password:</td><td><input type="password" name="pass" /></td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="submit" value="Login" /></td>
                        </tr>
                    </table>
                    
                    <input type="hidden" name="redir" value="" />
                </form>
            
            </div>
            
            <?php include $_SERVER['DOCUMENT_ROOT'].'/html/footer.php' ?>
        </div>
    </body>
</html>