{% include 'headers.tpl' %}
<!-- CHANGEPASSWD.TPL START -->

<h1 class="synopsis">{{ Account_ChangePassword }}</h1>
<div class="cellframe changepasswd">
	<div class="btn-group">
		<a href="/media/users/account"><button class="btn">{{ General_ReturnAccount }}</button></a>
	</div>
	<hr>

	{% include '/../Public/errnos.tpl' %}

	<form action="/media/users/changepasswd" method="post">
		<div class="control-group">
			<label class="control-label">{{ Account_Password }}</label>
			<div class="controls">
				<input name="password" maxlength="30" placeholder="{{ Account_EnterPassword }}" type="password"><a href="#" onclick="cell.DialogBox('{{ General_AcceptedChar }}');"> [?]</a>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">{{ ChangePasswd_New }}</label>
			<div class="controls">
				<input name="newpassword" maxlength="30" placeholder="{{ ChangePasswd_EnterNewPassword }}" type="password"><a href="#" onclick="cell.DialogBox('{{ ChangePasswd_MorePassword }}');"> [?]</a>
			</div>
			<label class="control-label">{{ ChangePasswd_NewConfirm }}</label>
			<div class="controls">
				<input name="newpassword_confirm" maxlength="30" placeholder="{{ ChangePasswd_NewConfirm }}" type="password">
			</div>
		</div>

		<div class="control-group">
			<div class="form-bigbutton">
				<input type="hidden" name="csrf" value="{{ csrf }}"/>
				<input class="btn btn-warning btn-large" name="send" value="{{ General_Modify }}" type="submit">
			</div>
		</div>
	</form>
</div>

<!-- CHANGEPASSWD.TPL END -->
{% include 'footer.tpl' %}