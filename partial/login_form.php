		<form id="login-form" action='post'>
			<fieldset>
				<legend>Log In</legend>
				<input type="hidden" name="action" value="login" />
				<div class="formfield clearfix">
					<label for="email">Email</label>
					<input name="email" id="email" type="text" class="epc-textfield"/><br />
				</div>
				<div class="formfield clearfix">
					<label for="password">Password</label>
					<input name="password" id="password" type="password" class="epc-textfield"/><br />
				</div>
				<button href="#" onClick="validateLoginForm(); return false;" class="epc-button ui-state-default ui-corner-all">Submit</button><div id="login-form-loading">&nbsp;</div>
			</fieldset>
		</form>

