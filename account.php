<? session_start(); ?>
<?require_once('include/environment.inc');?>
<? redirect_if_not_logged_in("login.php"); ?>
<? include "partial/head.php"; ?>
<script type="text/javascript" src="js/account.js"></script>
<? include "partial/header.php"; ?>

<div id="main-content">
		<div class="flash_notice"></div>
		<div class="flash_error"></div>
      <h1>Account Info</h1>
      <div id="account-accordion" class="ui-accordion-container" style="padding-top: 10px; clear: both;">
        <h3><a href="javascript: void(0)" class="ui-accordion-link">Open Tickets</a></h3>
        <div id="openTicketsDiv">
          <?
            $openTickets = User::openTickets();
            if(count($openTickets) == 0){
              echo "<p>No Open Tickets</p><p><a href='index.php'>Click here to start a new one!</a></p>";
            }
            else {
              echo "<ul>";
              foreach($openTickets as $key => $t) {
                ?>
          <li id="openticket<? echo $key+1; ?>">
            <span class="li-header">Ticket Number: <? echo $t->user_id.$t->id; ?></span><br />
            <span class="li-key">Status:</span><span class="fieldlink"><a href="#" title="<?echo $t->status;?>"><? echo $t->status; ?></a></span><br />
            <span class="li-key">Shipping Label:</span><span class="fieldlink"><a href='<? echo $t->labelpath; ?>'>Download</a></span>
          </li>
                <?
              }
              echo "</ul>";
            }
          ?>
        </div>
        <h3><a href="javascript: void(0)" class="ui-accordion-link">Order History</a></h3>
        <div id="orderHistoryDiv">
          <p>Order history goes here...</p>
        </div>
        <h3><a href="javascript: void(0)" class="ui-accordion-link">Personal Info</a></h3>
        <div id="personalInfoDiv">
          <p>Personal info goes here...</p>
        </div>

      </div>

</div>
<? include "partial/footer.php" ?>
