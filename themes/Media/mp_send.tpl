{% include 'headers.tpl' %}
<!-- MP_SEND.TPL START -->

<h1 class="synopsis">{{ Mail }}</h1>
<div class="cellframe mpsend">
	<a class="btn" href="/media/mp/inbox">{{ Mail_Inbox }}</a>
	<hr>

	{% include '/../Public/errnos.tpl' %}

	<form action="/media/mp/send&user_d={{ request.user_d }}" method="post">
		<div class="control-group">
			<label for="title">{{ MailSend_Receiver }}</label>
			<input name="username_d" id="membersearch" value="{{ request.user_d }}" maxlength="30" type="text" id="title">
			<label for="title">{{ MailSend_Subject }}</label>
			<input name="subject" maxlength="30" type="text" id="subject" value="{{ request.subject }}">
			<label for="message">{{ MailSend_Message }}</label>
			<textarea class="ckeditor field span9" name="message"></textarea>
			<input type="hidden" name="csrf" id="csrf" value="{{ csrf_token }}"/>
		</div>
		<input type="hidden" name="csrf" value="{{ csrf }}"/>
		<input class="btn btn-primary" name="send" value="{{ General_Send }}" type="submit">
	</form>
</div>

<!-- MP_SEND.TPL END -->
{% include 'footer.tpl' %}