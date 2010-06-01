<? session_start(); ?>
<?require_once('include/environment.inc');?>
<? include "partial/head.php"; ?>
<script type="text/javascript" src="js/account.js"></script>
<? include "partial/header.php"; ?>

<div id="main-content">
		<div class="flash_notice"></div>
		<div class="flash_error"></div>
      <?
        if(!User::logged_in()){
          header("location:login.php");
        }
      ?>
      <h1>Account Info</h1>
</div>
<? include "partial/footer.php" ?>
