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
                    <span class="li-header li-header-left">Ticket Number: <? echo $t->id; ?></span>
                    <span class="li-header li-header-right fieldlink"><a href="#" title="<?echo $t->status;?>"><? echo $t->status; ?></a></span>
                    <div class="clearfix"></div>
                    <span class="li-key">Created On: </span><span><? echo date_format($t->created_at,"m/d/Y"); ?></span>
                    <div class="clearfix"></div>
                    <span class="li-key">Service: </span><span><? echo $t->megabytes."MB ".$t->media.", ".$t->service->name; ?></span>
                    <?if($t->etc != NULL) {?>
                      <? $etcDate = date_format($t->etc,"m/d/Y"); ?>
                      <div class="clearfix"></div>
                      <span class="li-key">Estimated Completion Date: </span><span><? echo $etcDate; ?></span>
                    <? } ?>
                    <?if($t->service_fee != NULL) {?>
                      <div class="clearfix"></div>
                      <span class="li-key">Service Fee: </span><span><? echo "$".$t->service_fee; ?></span>
                    <? } ?>

                    <div class="clearfix"></div>
                    <span class="li-key" style="margin-top:5px;">Questions or Comments:</span>
                    <span class="fieldlink"><a href="#" onclick="toggleCommentBox(<?echo $key+1;?>); return false;">Comment</a></span>
                    <div class="clearfix"></div>
                    <div id="openticketcomments<?echo $key+1;?>"></div>
                    <div class="clearfix"></div>
                    <div id="openticketcommentinput<?echo $key+1;?>" style="display:none; width:60%;">
                      <textarea rows="4" cols="50"></textarea>
                      <div class="clearfix"></div>
                      <button class="epc-button ui-state-default ui-corner-all" onClick="addComment(<?echo $key+1;?>); return false;">Comment</button>
                    </div>
                    <div class="clearfix" style="margin-top:25px;"></div>
                    <button class="epc-button ui-state-default ui-corner-all" onClick="cancelTicket(<?echo $key+1;?>); return false;">Cancel Ticket</button>
                    <button class="epc-button ui-state-default ui-corner-all" onClick="window.location='<? echo $t->labelpath; ?>';">Download Shipping Label</button>
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
