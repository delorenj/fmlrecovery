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
      if(count($openTickets) == 0) {
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
        <span class="li-key" style="margin-top:5px;">Ticket Inquiry: </span>
        <span class="fieldlink" style="text-decoration:underline;"><a href="#" onclick="toggleCommentBox(<?echo $key+1;?>); return false;">Ask a question</a></span>
        <div class="clearfix"></div>
        <div id="openticketcomments<?echo $key+1;?>">
              <?
              if($t->ticket_comments != NULL) {
                foreach($t->ticket_comments as $c) {
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
        <button class="epc-button ui-state-default ui-corner-all" onClick="cancelTicket(<?echo $key+1;?>, <?echo $t->id;?>); return false;">Cancel Ticket</button>
        <button class="epc-button ui-state-default ui-corner-all" onClick="window.location='<? echo $t->labelpath; ?>.pdf';">Download Shipping Label</button>
      </li>
          <?
        }
        echo "</ul>";
      }
      ?>
    </div>
    <h3><a href="javascript: void(0)" class="ui-accordion-link">Personal Info</a></h3>
    <div id="personalInfoDiv">
      <? $address = Address::first(array('conditions' => array('user_id = ?', User::current_user()->id))); ?>
      <div class="lcolumn">
        <div class="formfield" style="overflow:hidden;">
          <div class="clearfix">
            <label for="firstname">First Name </label>
            <input type="text" size="20" id="first_name" value="<? echo User::current_user()->first_name; ?>" class="epc-textfield float_left" />
          </div>
        </div>
        <div class="formfield">
          <div class="clearfix">
            <label for="lastname">Last Name </label>
            <input type="text" size="20" id="last_name" value="<? echo User::current_user()->last_name; ?>" class="epc-textfield float_left" />
          </div>
        </div>
        <div class="formfield">
          <div class="clearfix">
            <label for="email">Email </label>
            <input type="text" size="20" id="email" value="<? echo User::current_user()->email; ?>" class="epc-textfield float_left" />
          </div>
        </div>
      </div>
      <div class="rcolumn">
        <div class="formfield" style="overflow:hidden;">
          <div class="clearfix">
            <label for="street">Street</label>
            <input type="text" size="20" id="street" value="<? echo $address->streetlines; ?>" class="epc-textfield float_left" />
          </div>
        </div>
        <div class="formfield" style="overflow:hidden;">
          <div class="clearfix">
            <label for="city">City</label>
            <input type="text" size="20" id="city" value="<? echo $address->city; ?>" class="epc-textfield float_left" />
          </div>
        </div>
        <div class="formfield" style="overflow:hidden;">
          <div class="clearfix">
            <label for="state">State </label>
            <input type="text" size="20" id="state" value="<? echo $address->stateorprovincecode; ?>" class="epc-textfield float_left" />
          </div>
        </div>
        <div class="formfield" style="overflow:hidden;">
          <div class="clearfix">
            <label for="zip">Zip</label>
            <input type="text" size="20" id="zip" value="<? echo $address->postalcode; ?>" class="epc-textfield float_left" />
          </div>
        </div>
        <div class="formfield" style="overflow:hidden;">
          <div class="clearfix">
            <label for="phone">Phone</label>
            <input type="text" size="20" id="phone" value="<? echo $address->phonenumber; ?>" class="epc-textfield float_left" />
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="accordion-control">
        <button class="epc-button ui-state-default ui-corner-all" onClick="updateAccount(<?echo User::current_user()->id;?>); return false;">Update</button>
      </div>
    </div>
  </div>
</div>
<? include "partial/footer.php" ?>
