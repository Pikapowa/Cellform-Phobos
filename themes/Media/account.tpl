{% include 'headers.tpl' %}
<!-- ACCOUNT.TPL START -->

<h1 class="synopsis">{{ Account_Welcome }} {{ userbase.username }}</h1>

<div class="cellframe account">
	<img class="avatar-frame big" src="/media/avatars/{{ userbase.avatar }}" alt="{{ userbase.username }}">

    <ul class="nav nav-tabs" style="margin-top:20px">
    <li class="active">
    <a href="#">{{ Account_MyAccount }}</a>
    </li>
    </ul>

	{% include '/../Public/errnos.tpl' %}

	<form action="/media/users/account" method="post" enctype="multipart/form-data">
		<div class="control-group">
			<label class="control-label">{{ Account_Email }}</label>
			<div class="controls">
				<input name="email" maxlength="30" value="{{ userbase.email }}" type="email"><a href="#" onclick="cell.DialogBox('{{ Account_MoreEmail }}');"> [?]</a>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">{{ Account_Password }}</label>
			<div class="controls">
				<input name="password" maxlength="30" placeholder="{{ Account_EnterPassword }}" type="password"><a href="/media/users/changepasswd"> [{{ Account_ChangePassword }}]</a>
			</div>
			<label class="control-label">{{ Account_PasswordConfirm }}</label>
			<div class="controls">
				<input name="password_confirm" maxlength="30" placeholder="{{ Account_EnterPasswordConfirm }}" type="password">
			</div>
		</div>

		<div class="control-group">
			<input name="avatar" type="file" title="{{ Account_LoadAvatar }}" accept="image/jpeg"><a href="#" onclick="cell.DialogBox('{{ Account_MoreLoadAvatar }}');"> [?]</a>
			<input type="hidden" name="csrf" id="csrf" value="{{ csrf_token }}"/>
		</div>

		<div class="control-group">
			<div class="form-bigbutton">
				<input type="hidden" name="csrf" value="{{ csrf }}"/>
				<input class="btn btn-primary" name="send" value="{{ General_Modify }}" type="submit">
			</div>
		</div>
	</form>
</div>

<!-- ACCOUNT.TPL END -->
{% include 'footer.tpl' %}