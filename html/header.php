<div id="header">
	<img id="banner" src="/img/banner.gif" />
    
    <?php $officer = Permissions::hasPerm(Permissions::OFFICER); ?>
    
    <div id="divMenu">
        <ul id="ulMenu">
            <a href="/"><li class="home">Home</li></a>
            <?php if(! Control::getLoginStatus()) { ?>
                <a href="/login"><li class="login">Login</li></a>
            <?php } else { ?>
                <?php if($officer) { ?><a href="/users"><li class="users">Users</li></a><?php } ?>
            	<?php if($officer) { ?><a href="/events"><li class="events">Events</li></a><?php } ?>
                <?php //<a href="/account/view"><li class="account">Account</li></a> ?>
                <?php if(Permissions::hasPerm(Permissions::STUDENT)) { ?><a href="/points"><li class="points">Points</li></a><?php } ?>
                <?php if($officer) { ?><a href="/checkin"><li class="checkin">Check-in</li></a><?php } ?>
                <?php //if($officer) { <a href="/notifications"><li class="sms">SMS Notifications</li></a>} ?>
                <?php if($officer) { ?><a href="/setup"><li class="setup">Setup</li></a><?php } ?>
            <?php } ?>
        </ul>
    </div>
	
    <div style="clear:both;"></div>
    
    <?php if(Control::getLoginStatus()) { 
			$user = new User();
			$user->find("id", Control::getCurrentUser(), true);
			if($user->numresults > 0) {
				$user = $user->results[0];
			}
	?>
    <div style="background-color:orange; width:100%; padding:5px; border-bottom:1px dotted grey;" id="logout">
    	Hola, <?php echo $user->fname ?> <a href="#" onclick="logout()">Click here to logout</a>
    </div>
    <?php } ?>
</div>
