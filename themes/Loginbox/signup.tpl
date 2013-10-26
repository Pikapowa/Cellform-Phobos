{% include 'headers.tpl' %}
	<!-- BODY -->
		<!-- AFFICHAGE DU FORMULAIRE D'INSCRIPTION -->
		<div id="tenant-forms" class="tab-content">

			<form id="cellform-signup" class="tab-pane active form-horizontal">
				<h3>{{ General_Subscribe }}</h3>

				<div class="control-group">
					<label class="control-label">{{ Signup_Email }}</label>
					<div class="controls">
						<input name="email" placeholder="email@example.com" maxlength="30" type="email"><a href="#" onclick="cell.DialogBox('{{ Signup_EmailMore }}');">[?]</a>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">{{ Signup_Password }}</label>
					<div class="controls">
						<input name="password" placeholder="{{ Signup_EnterPassword}}" maxlength="30" type="password">
					</div>
					<label class="control-label">{{ Signup_PasswordConfirm }}</label>
					<div class="controls">
						<input name="password_confirm" placeholder="{{ Signup_EnterPasswordConfirm}}" maxlength="30" type="password">
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">{{ Signup_Username }}</label>
					<div class="controls">
						<input name="username" placeholder="{{ Signup_EnterUsername }}" maxlength="12" type="text"><a href="#" onclick="cell.DialogBox('{{ Signup_AcceptedCharPseudo }}');">[?]</a>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">{{ Signup_Sex }}</label>
					<div class="controls">
						<label class="radio inline"><input name="sex" value="male" type="radio">{{ Signup_Man }}</label>
						<label class="radio inline"><input name="sex" value="female" type="radio">{{ Signup_Woman }}</label>
					</div>
				</div>

				<div class="control-group">
					<label id="cellform-captcha" class="control-label">{{ CurrentCaptcha }}</label>
					<div class="controls">
						<input name="captcha" placeholder="{{ Signup_CaptchaAnswer }}" maxlength="32" type="text"><a href="#" onclick="cell.DialogBox('{{ Signup_CaptchaMore }}');">[?]</a>
					</div>
				</div>

				<div class="form-bigbutton">
					<input class="btn btn-primary submit-btn" value="{{ Signup_Save }}">
				</div>

				<div class="form-nav">
					<a href="#" onclick="history.back();">-- {{ General_Return }} --</a>
				</div>
			</form>
		</div>
	</div>
	{% include 'footer.tpl' %}
<!-- FIN DE LA PAGE -->	