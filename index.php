<? session_start(); ?>
<?require_once('include/environment.inc');?>
<? include "partial/head.php"; ?>
<? include "partial/header.php"; ?>

<div id="main-content">
    <!--
         <% flash.each do |key,value| %>
		<div id="flash" class="flash_<%= key %>" >
                 <span class="message"><%= value %></span>
		</div>
             <% end -%>
    <%= yield :layout %>
    -->
    <?# $user = User::find_by_first_name('quentin'); ?>
    <!--<h1>Last Name: <?# echo $user->last_name; ?></h1>-->
		<form id="create-user-form">
			<fieldset>
				<legend>Create an Account</legend>
				<input type="hidden" name="action" value="create-account"/>
				<div class="formfield clearfix">
					<label for="first_name">First Name</label>
					<input name="first_name" type="text" class="epc-textfield"/>
				</div>
				<div class="formfield clearfix">
					<label for="last_name">Last Name</label>
					<input name="last_name" type="text" class="epc-textfield"/>
				</div>
				<div class="formfield clearfix">
					<label for="email">Email</label>
					<input name="email" type="text" class="epc-textfield"/>
				</div>
				<div class="formfield clearfix">
					<label for="password">Password</label>
					<input name="password" type="password" class="epc-textfield"/>
				</div>
				<div class="formfield clearfix">
					<button href="#" class="epc-button ui-state-default ui-corner-all" onClick="validateCreateAccount(); return false;">Submit</button>
				</div>
			</fieldset>
		</form>
		<form id="ticket-form">
			<fieldset>
				<legend>Create a Ticket</legend>
				<input type="hidden" name="action" value="login" />
				<div class="formfield clearfix">
					<label for="email">Email</label>
					<input name="email" id="email" type="text" class="epc-textfield"/><br />
				</div>
				<div class="formfield clearfix">
					<label for="password">Password</label>
					<input name="password" id="password" type="password" class="epc-textfield"/><br />
				</div>
				<button href="#" onClick="validateTicketForm(); return false;" onSubmit="validateTicketForm()" class="epc-button ui-state-default ui-corner-all">Submit</button><div id="ticket-form-loading">&nbsp;</div>
			</fieldset>
		</form>
</div>
<? include "partial/footer.php" ?>
