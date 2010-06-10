		<form id="create-user-form">
			<fieldset>
				<legend>Create an Account</legend>
				<input type="hidden" name="action" value="create"/>
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

