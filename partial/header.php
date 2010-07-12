
<body>
  <div id="wrapper">
    <div id="header">
      <div class="midHeader">
        <div class="superHeader">
          <? if(User::logged_in()){
            echo "<span id='login-message'>Welcome ".User::current_user()->first_name."</span><br />";
            echo "<a style='font-size:smaller;' href='#' onClick='logout()'>Log out</a>";
          } else {
            echo "<a style='font-size:smaller;' href='#' onClick='window.location=\"account.php\"'>Log in</a>";
          }?>
        </div>
      </div>
      <div class="subHeader">
                <a href="index.php">Home</a>
                <a href="account.php">My Account</a>
                <a href="contact.php">Contact Us</a>
                <a href="about.php">About</a>
                <? if(User::is_admin()) {
                  echo '<a href="admin.php">Admin</a>';
                }?>
      </div>
    </div>


