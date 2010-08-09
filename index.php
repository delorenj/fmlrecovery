<? session_start(); ?>
<? require_once('include/environment.inc'); ?>
<? include "partial/head.php"; ?>
<script type="text/javascript" src="js/fedexmap.js"></script>
<script type="text/javascript" src="js/tickets.js"></script>
<? include "partial/header.php"; ?>

<div id="main-content">
		<div class="flash_notice"></div>
      <div class="flash_error"></div>
		<?include 'partial/ticket_form.php';?>
</div>
<? include "partial/footer.php" ?>
