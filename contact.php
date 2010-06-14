<? session_start(); ?>
<?require_once('include/environment.inc');?>
<? include "partial/head.php"; ?>
<? include "partial/header.php"; ?>

<div id="main-content">
		<div class="flash_notice"></div>
		<div class="flash_error"></div>
      <h1>Contact Us</h1>
      <img src="images/twitter.png" />
      <div class="contact-block">
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
</div>
<? include "partial/footer.php" ?>
