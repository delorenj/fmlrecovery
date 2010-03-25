
<body>
	  	<div id="wrapper">
				<div id="header">
					<div class="midHeader">
						<div class="superHeader">
<!--
							<% if user_logged_in? %>
				 				<span>Welcome <%= current_user.first_name.capitalize %></span><br />
								<%= link_to 'Logout', logout_url  %> 
							<% else %>
								<span>Welcome Guest</span>
							<% end %>
-->
              <? if(isset($_SESSION['user'])){
                echo "<span id='login-message'>Welcome ".$_SESSION['user']->first_name."</span>";
              } else {
                echo "<span id='login-message'>Welcome Guest</span>";
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


