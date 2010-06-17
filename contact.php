<? session_start(); ?>
<?require_once('include/environment.inc');?>
<? include "partial/head.php"; ?>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Reenie+Beanie" />
<script type="text/javascript" src="js/contact.js"></script>
<? include "partial/header.php"; ?>

<div id="main-content">
  <div class="flash_notice"></div>
  <div class="flash_error"></div>
  <div>
    <div class="lcolumn">
      <div id="contactUsHeader"><p>contact us</p></div>
      <div id="socialMedia">
        <a href="#" onclick="comingSoon();"><img src="images/twitter-icon.png" /></a>
        <a href="#" onclick="comingSoon();"><img src="images/facebook-icon.png" /></a>
        <a href="#" onclick="comingSoon();"><img src="images/linkedin-icon.png" /></a>
        <span style="font-family:'Reenie Beanie'; font-size: 2.5em; color:#051e5d; position:relative; top:-15px;"> <-- and follow us too!</span>
      </div>
    </div>
    <div class="rcolumn">
      <div id="myPhone">
        <p>973.440.8809</p>
      </div>
      <div id="myEmail">
        <a href="mailtop:Hello@fmlrecovery.com">Hello@fmlrecovery.com</a>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
  <div>
    <div class="lcolumn">
      <div id="contactForm">
        <div class="formfield">
          <div class="clearfix">
            <label for="name">Name </label>
            <input type="text" id="name" class="epc-textfield" style="width:100%;"/>
          </div>
        </div>
        <div class="formfield">
          <div class="clearfix">
            <label for="name">Email </label>
            <input type="text" id="email" class="epc-textfield" style="width:100%;"/>
          </div>
        </div>
        <div class="formfield">
          <div class="clearfix">
            <label for="message">Message </label>
            <textarea rows="4" id="message" class="epc-textarea" style="width:100%;"></textarea>
          </div>
        </div>
        <div class="formfield">
          <div class="clearfix">
            <button class="epc-button ui-state-default ui-corner-all" onClick="sendMessage(); return false;">Send</button>
          </div>
        </div>
      </div>
      <!--
      <div class="contactBlock">
        <p>FML Recovery &copy</p>
        <p style="font-size: smaller;">9 Morris Rd.</p>
        <p style="font-size: smaller;">Stanhope, NJ 07874</p>
        <p style="font-size: smaller;">1-973-440-8809</p>
      </div>
      -->
      <p><object type="application/x-shockwave-flash" data="https://clients4.google.com/voice/embed/webCallButton" width="230" height="85"><param name="movie" value="https://clients4.google.com/voice/embed/webCallButton" /><param name="wmode" value="transparent" /><param name="FlashVars" value="id=6d140f190b9719697367cdd2d271cdc3e2369e4e&style=0" /></object></p>

    </div>
    <div class="rcolumn">
      <div id="myLocation"></div>
    </div>
  </div>
  <div class="clearfix"></div>
</div>
<? include "partial/footer.php" ?>
