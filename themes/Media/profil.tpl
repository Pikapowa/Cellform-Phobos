{% include 'headers.tpl' with {'permalink': 'true'} %}
<!-- PROFIL.TPL START -->

<h1 class="synopsis">
{{ Profil_Of }} {{ user.username }}
{% if user.sex == 'male' %}
	[&#9794;]
{% else %}
	[&#9792;]
{% endif %}
</h1>

<div class="cellframe profil">
	<div class="row-fluid show-grid">
		<div class="span6">
			<img class="avatar-frame big" src="/media/avatars/{{ user.avatar }}" alt="{{ user.username }}">
		</div>
		<div class="span6">
			<div class="cellframe info-profil">
				{% if user.username != userbase.username %}
				<a class="btn" href="/media/mp/send&user_d={{ user.username }}">{{ Profil_SendPm }}</a>
				{% if user.friend %}
				{% else %}
				<a class="btn" id="addfriend" href="javascript:cell.AddFriend('{{ user.username }}');">{{ Profil_AddFriends }}</a>
				<input type="hidden" name="csrf_friends_ajax" id="csrf_friends_ajax" value="{{ csrf_token }}"/>
				{% endif %}
				<a class="btn" href="/media/notifications/all&user={{ user.username }}&type=votes&top=10">{{ Notifications }}</a>
				{% endif %}
				<ul>
					<li>{{ General_Score }} : {{ user.score }}</li>
					<li>{{ Profil_NbLike }} : {{ user.likes }}</li>
					<li>{{ Profil_NbComments }} : {{ user.nbcomments }}</li>
					<li>{{ Profil_NbTickets }} : {{ user.nbtickets }}</li>
					<li>{{ Profil_Rank }} : 
						{% if (user.score > 0) and (user.score < 1000) %} {{ Rank_Noob }}
							{% elseif (user.score > 990) and (user.score < 2800) %} {{ Rank_AsPaladin }}
							{% elseif (user.score > 2790) and (user.score < 4800) %} {{ Rank_Paladin }}
							{% elseif (user.score > 4790) and (user.score < 6800) %} {{ Rank_Master }}
							{% elseif (user.score > 6790) and (user.score < 10000) %} {{ Rank_GrandMaster }}
							{% elseif (user.score > 9990) and (user.score < 20000) %} {{ Rank_Priest }}
							{% elseif (user.score > 19990) and (user.score < 30000) %} {{ Rank_GrandPriest }}
							{% elseif (user.score > 30000) %} {{ Rank_As }}
						{% endif %}
					</li>
					<li>{{ Profil_RegisterDate }} : {{ user.regdate }}</li>
					<li>{{ Profil_SigninDate }} : {{ user.lastvisit }}</li>
				</ul>
			</div>
		</div>
	</div>
</div>

{% include 'posts.tpl' %}

<!-- PROFIL.TPL END-->
{% include 'footer.tpl' %}