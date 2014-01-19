<?php require_once $_SERVER['DOCUMENT_ROOT'].'/config.php' ?>

<!DOCTYPE html>

<html>
	<head>
    	<?php require_once $_SERVER['DOCUMENT_ROOT'].'/html/std_head.php' ?>
        <title>SNHS</title>
        <link rel="stylesheet" href="/css/home.css" />
        
        <script>
			function saveContent(form) {
				requestData = $(form).serialize();
				request("/services/?service=pagecontent&action=edit",requestData,saveContent_succeeded,saveContent_failed,true,true);
			}
			function saveContent_succeeded(ret) {
				alert("Saved");
			}
			function saveContent_failed(ret) {
				alert("Not Saved");
			}
		</script>
    </head>
    
    <body>
    	<div id="main">
			<style>
				div#divMenu li.home { background-color:orange }
			</style>
			<?php include $_SERVER['DOCUMENT_ROOT'].'/html/header.php' ?>
            
            <div id="content">
            
            	<?php
					$sections = array('p_notes','p_info');
					$hasperm = Permissions::hasPerm(Permissions::OFFICER);
					foreach($sections as $s) {
						$content = new PageContent();
						$content->find('name',$s,true);
						if($content->numresults>0) {
							$content = $content->results[0];
							$name = $s.'_title';
							$$name = $content->title;
							$name = $s.'_data';
							$$name = $content->data;
						} else {
							$name = $s.'_title';
							$$name = "Error";
							$name = $s.'_data';
							$$name = "Could not read database.";
						}
					}
				?>
                
                <div id="p_notes" class="half left larger-text">
                	<h2><?php echo $p_notes_title ?></h2>
                    <?php
                    if($hasperm) {
						echo '<form method="post" onSubmit="saveContent(this); return false;">';
						echo '<input type="hidden" name="name" value="p_notes" />';
                    	echo '<textarea name="data" class="homeedit half">';
						echo str_replace("<br>","\r\n",$p_notes_data);
					} else {
						echo $p_notes_data;
					}
					if($hasperm) {
						echo '</textarea>';
                    	echo '<br>';
                    	echo '<input type="submit" value="Save">';
                    	echo '</form>';
                    }
					?>
                </div>
                
                <div id="p_schedule" class="half right">
                	<!-- FROM GOOGLE CALENDAR -->
                    <iframe src="https://www.google.com/calendar/embed?title=Schedule&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;height=350&amp;wkst=1&amp;bgcolor=%23FF9900&amp;src=snhs%40eldosnhs.com&amp;color=orange&amp;ctz=America%2FDenver" style=" border-width:0 " width="450" height="350" frameborder="0" scrolling="no"></iframe>
                    <!-- END OF GOOGLE CALENDAR -->
                    <?php
						if($hasperm) {
							echo '<a href="http://calendar.google.com/a/eldosnhs.com" target="_blank">Edit this calendar</a>';
						}
					?>
                </div>
                
                <div id="p_info" class="full larger-text">
                <h2><?php echo $p_info_title ?></h2>
                    <?php
                    if($hasperm) {
						echo '<form method="post" onSubmit="saveContent(this); return false;">';
						echo '<input type="hidden" name="name" value="p_info" />';
                    	echo '<textarea name="data" class="homeedit full">';
					} 
					echo $p_info_data;
					if($hasperm) {
						echo '</textarea>';
                    	echo '<br>';
                    	echo '<input type="submit" value="Save">';
                    	echo '</form>';
                    }
					?>
                </div>
            </div>
            
            <?php include $_SERVER['DOCUMENT_ROOT'].'/html/footer.php' ?>
        </div>
    </body>
</html>