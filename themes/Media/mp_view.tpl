{% include 'headers.tpl' %}
<!-- MP_VIEW.TPL START -->

<h1 class="synopsis">{{ Mail }}</h1>
<div class="cellframe">
	<a class="btn" href="/media/mp/inbox">{{ Mail_Inbox }}</button></a>
	<a class="btn" href="/media/mp/send">{{ Profil_SendPm }}</button></a>
	<a class="btn" href="/media/mp/send&user_d={{ mp.username }}&subject={{ mp.subject }}">{{ MailBody_PmReply }}</a>
	<hr>

	<div class="container-fluid">
		<div class="row-fluid">
			<img class="avatar-frame" src="/media/avatars/{{ mp.avatar }}" height="100" border="0" alt="{{ mp.username }}">
			<div class="mp-info">
				<span class="badge badge-info">{{ General_Author }} : {{ mp.username }}</span>
				<span class="badge badge-info">{{ General_Title }} : {{ mp.subject }}</span>
				<p><span class="badge badge-success">{{ Posts_PostedAt }} : {{ mp.date }}</span></p>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12 well">
				<span>{{ mp.message|raw }}</span>
			</div>
		</div>
	</div>
</div>

<!-- MP_VIEW.TPL END -->
{% include 'footer.tpl' %}