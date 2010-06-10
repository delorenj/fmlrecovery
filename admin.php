<? session_start(); ?>
<?require_once('include/environment.inc');?>
<?redirect_if_not_admin("login.php");?>
<? include "partial/head.php"; ?>
<script type="text/javascript" src="js/account.js"></script>
<? include "partial/header.php"; ?>

<div id="main-content">
		<div class="flash_notice"></div>
		<div class="flash_error"></div>
      <h1>Site Administration</h1>
      <div id="account-accordion" class="ui-accordion-container" style="padding-top: 10px; clear: both;">
        <h3><a href="javascript: void(0)" class="ui-accordion-link">Open Tickets</a></h3>
        <div id="openTicketsDiv">
          <?
            $openTickets = Ticket::openTickets();
            if(count($openTickets) == 0){
              echo "<h3>No Open Tickets! Your company must suck!</h3";
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
                    <span class="li-key" style="margin-top:5px;">Ticket Inquiry: </span>
                    <span class="fieldlink" style="text-decoration:underline;"><a href="#" onclick="toggleCommentBox(<?echo $key+1;?>); return false;">Reply</a></span>
                    <div class="clearfix"></div>
                    <div id="openticketcomments<?echo $key+1;?>">
                      <?
                        if($t->ticket_comments != NULL) {
                          foreach($t->ticket_comments as $c){
                            echo "<p class='commentType".$c->admin."'>";
                            if($c->admin) {
                              echo "  A. ";
                            } else {
                              echo "Q. ";
                            }
                            echo $c->comment."</p>";
                          }
                        }
                      ?>
                    </div>
                    <div class="clearfix"></div>
                    <div id="openticketcommentinput<?echo $key+1;?>" style="display:none; width:60%;">
                      <textarea rows="4" cols="50"></textarea>
                      <div class="clearfix"></div>
                      <button class="epc-button ui-state-default ui-corner-all" onClick="addComment(<?echo $key+1;?>, <?echo $t->id;?>); return false;">Comment</button>
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
        <h3><a href="javascript: void(0)" class="ui-accordion-link">Account Management</a></h3>
        <div id="accountManagementDiv">
          <?
            $users = User::all();
            echo '<ul>';
            foreach($users as $u){
              $numOpenTix = count(Ticket::openTickets($u->id));
              ?>
                  <li id="userbox<? echo $key+1; ?>">
                    <span class="li-header li-header-left">Account Number: <? echo $u->id; ?></span>
                    <span class="li-header li-header-right fieldlink">Open Tickets: <?echo $numOpenTix;?></span>
                    <div class="clearfix"></div>
                    <span class="li-key">Name: </span><span><? echo $u->first_name." ".$u->last_name; ?></span>
                    <div class="clearfix"></div>
                    <span class="li-key">Email: </span><span><? echo $u->email; ?></span>
                    <div class="clearfix" style="margin-top:25px;"></div>
                    <button class="epc-button ui-state-default ui-corner-all" onClick="deleteAccount(<?echo $key+1;?>, <? echo $u->id;?>); return false;">Delete Account</button>
                  </li>
              <?
            }
            echo '</ul>';
          ?>
        </div>
        <h3><a href="javascript: void(0)" class="ui-accordion-link">Personal Info</a></h3>
        <div id="personalInfoDiv">
          <p>Personal info goes here...</p>
        </div>

      </div>

</div>
<? include "partial/footer.php" ?>

