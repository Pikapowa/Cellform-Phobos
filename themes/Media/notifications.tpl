{% include 'headers.tpl' %}
<!-- NOTIFICATIONS.TPL START -->

<h1 class="synopsis">{{ Notifications }}</h1>
<div class="cellframe notifications">
	<div class="btn-toolbar" style="margin: 0;">
		<div class="btn-group">
			<button class="btn dropdown-toggle" data-toggle="dropdown">{{ Notifications_Votes }} <span class="caret"></span></button>
			<ul class="dropdown-menu">
				<li><a href="/media/notifications/all&user={{ request.user }}&type=votes&top=10">{{ Notifications_Top10Votes }}</a></li>
				<li><a href="/media/notifications/all&user={{ request.user }}&type=votes&top=20">{{ Notifications_Top20Votes }}</a></li>
				<li><a href="/media/notifications/all&user={{ request.user }}&type=votes">{{ Notifications_AllVotes }}</a></li>
			</ul>
		</div>
		<div class="btn-group">
			<button class="btn dropdown-toggle" data-toggle="dropdown">{{ General_Comments }} <span class="caret"></span></button>
			<ul class="dropdown-menu">
				<li><a href="/media/notifications/all&user={{ request.user }}&type=coms&top=10">{{ Notifications_Top10Comments }}</a></li>
				<li><a href="/media/notifications/all&user={{ request.user }}&type=coms&top=20">{{ Notifications_Top20Comments }}</a></li>
				<li><a href="/media/notifications/all&user={{ request.user }}&type=coms">{{ Notifications_AllComments }}</a></li>
			</ul>
		</div>
	</div>
	<hr>

	<ul>
	{% for notif in notifs %}
	<li>
		{% if request.type == 'coms' %}
		<a href="/media/users/profil&user={{ notif.user }}">{{ notif.user }}</a>
			{{ Notifications_Commented }}
			{% if request.user == userbase.username %}
			{% else %}
				{{ Notifications_CommentOf }} <a href="/media/users/profil&user={{ request.user }}">{{ request.user }}</a>
			{% endif %}
		{{ General_On }} <a href="/media/ticket/view&id={{ notif.id }}">[{{ notif.title }}]</a>
		{% endif %}

		{% if request.type == 'votes' %}
		<a href="/media/users/profil&user={{ notif.uservoted }}">{{ notif.uservoted }}</a>
			{{ Notifications_Voted }}

			{% if notif.vote == 'up' %}
				{{ Notifications_Voteup }}
			{% else %}
				{{ Notifications_Votedown }}
			{% endif %}

			{% if request.user == userbase.username %}
			{% else %}
			<a href="/media/users/profil&user={{ request.user }}">{{ request.user }}</a>
			{% endif %}

		{{ General_On }} <a href="/media/ticket/view&id={{ notif.id }}">[{{ notif.title }}]</a>
		{% endif %}
	</li>
	{% endfor %}
	</ul>
</div>

<!-- NOTIFICATIONS.TPL END-->
{% include 'footer.tpl' %}