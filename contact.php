<? session_start(); ?>
<?require_once('include/environment.inc');?>
<? include "partial/head.php"; ?>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Reenie+Beanie" />
<script type="text/javascript" src="js/contact.js"></script>
<? include "partial/header.php"; ?>

<div id="main-content">
		<div class="flash_notice"></div>
		<div class="flash_error"></div>
      <h1>Contact Us</h1>
      <div class="contact-block" style="float:left;">
        <div class="formfield" style="overflow:hidden;">
          <div class="clearfix">
            <label for="name">Name </label>
            <input type="text" id="name" class="epc-textfield float_left" />
          </div>
        </div>
        <div class="formfield" style="overflow:hidden;">
          <div class="clearfix">
            <label for="name">Email </label>
            <input type="text" id="email" class="epc-textfield float_left" />
          </div>
        </div>
        <div class="formfield" style="overflow:hidden;">
          <div class="clearfix">
            <label for="message">Message </label>
            <textarea rows="4" cols="30" id="message" class="epc-textarea float_left" ></textarea>
          </div>
        </div>
        <div class="formfield" style="overflow:hidden;">
          <div class="clearfix">
            <button class="epc-button ui-state-default ui-corner-all" onClick="sendMessage(); return false;">Send</button>
          </div>
        </div>
      </div>
      <div style="float:right; width:50%; margin-top:-40px;">
        <div id="myPhone"><p>973.440.8809</p></div>
        <div id="myPhone" style="font-family:'Josefin Sans Std Light'; font-size:2em; font-weight:600; font-style:italic;"><p>Hello@fmlrecovery.com</p></div>
        <div id="myLocation" style="position:relative; z-index: 100;width:100%;height:400px; text-align:right; border:2px solid black;"></div>
      </div>
      <div class="clearfix"></div>
</div>
<? include "partial/footer.php" ?>
