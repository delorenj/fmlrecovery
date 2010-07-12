<? session_start(); ?>
<?require_once('include/environment.inc');?>
<? include "partial/head.php"; ?>
<script type="text/javascript" src="js/tickets.js"></script>
<? include "partial/header.php"; ?>

<div id="main-content">
  <div class="flash_notice"></div>
  <div class="flash_error"></div>
  <div id="login-box">
    <div class="clearfix"><img src="images/loginheader.gif" style="float:left;"/></div>
    <div class="clearfix"></div>
    <div class="lcolumn">
      <div class="formfield">
        <div class="clearfix">
          <label for="Email">Email</label>
          <input type="text" size="20" id="email" name="email" class="epc-textfield float_left" onBlur="validateEmail(this.value);"/>
          <span class="fieldOK">&nbsp;</span><span class="loading">&nbsp;</span>
        </div>
      </div>
      <div class="formfield">
        <div class="clearfix">
          <label for="name" id="passwordCaption">Password</label>
          <input type="password" size="20" id="password" name="password" class="epc-textfield float_left" />
        </div>
      </div>
      <div class="clearfix"></div>
      <button id='loginButton' class='epc-button ui-state-default ui-corner-all' href='#' onClick='validateLoginForm("account.php"); return false;'>Log in</button>
    </div>
    <div class="rcolumn">
      <div class="login-snippet" style="padding-top:20px;">
        <h3>Don't have an account?</h3>
        <span class="fieldlink" style="font-size: smaller;"><a href="index.php">Just start creating a ticket!</a></span>
      </div>
      <div class="login-snippet">
        <h3>Did you forget your password?</h3>
        <span class="fieldlink" style="font-size: smaller;"><a href="#">Recover it here.</a></span>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
<? include "partial/footer.php" ?>
