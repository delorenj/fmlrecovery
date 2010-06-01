
<body>
	  	<div id="wrapper">
				<div id="header">
					<div class="midHeader">
						<div class="superHeader">
              <!--<span><a href="#" onclick="testMail(); return 0;">Test Mail</a></span><br />-->
              <? if(User::logged_in()){
                echo "<span id='login-message'>Welcome ".User::current_user()->first_name."</span><br />";
								echo "<a href='#' onClick='logout()'>Log out</a>";
              } else {
                echo "<span id='login-message'>Welcome Guest</span><br />";
              }?>
						</div>
					</div>
					<div class="subHeader">
						<? if(User::logged_in()){
//							echo "<a href='tickets.php?userid=".User::current_user()->id."'>My Tickets</a>";
//							echo "<a href='account.php?userid=".User::current_user()->id."'>Account Info</a>";
                    }

                    echo '<a href="index.php">Home</a>';
                    echo '<a href="account.php">My Account</a>';
                    echo '<a href="contact.php">Contact Us</a>';
                    echo '<a href="about.php">About</a>';
                  ?>
					</div>
				</div>


