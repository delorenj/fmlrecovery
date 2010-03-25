
<body>
	  	<div id="wrapper">
				<div id="header">
					<div class="midHeader">
						<div class="superHeader">
              <? if(User::logged_in()){
                echo "<span id='login-message'>Welcome ".User::current_user()->first_name."</span><br />";
								echo "<a href='#' onClick='logout()'>Log out</a>";
              } else {
                echo "<span id='login-message'>Welcome Guest</span><br />";
              }?>
						</div>
					</div>
					<div class="subHeader">
<!--
						<% if user_logged_in? %>
  	          <%= link_to 'My Tickets','tickets'  %>
    	        <%= link_to 'Account Info',:controller=>'users', :action=>'show', :id=>current_user.id  %>
						<% else %>
					 	<%= link_to 'Login', login_url  %>
						<% end %> 
				  	<%= link_to 'Contact Us', 'contact'  %>
-->
						<a href="#">Login</a>
						<a href="#">Contact Us</a>
					</div>
				</div>


