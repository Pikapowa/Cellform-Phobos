{% include 'headers.tpl' %}
<!-- MEMBERS.TPL START -->

<h1 class="synopsis">{{ Members }}</h1>
<div class="cellframe members">

<form action="/media/users/profil" method="get" class="navbar-form pull-right" autocomplete="off">
	<div class="control-group">
		<label class="control-label">{{ Members_Search }} :</label>
		<div class="controls">
			<input name="user" id="membersearch" type="text">
		</div>
	</div>
</form>

<table class="table table-hover">
	<thead>
		<tr>
			<th>{{ General_Profil }}</th>
			<th>{{ General_Pseudo }}</th>
			<th>{{ General_Score }}</th>
		</tr>
	</thead>
	<tbody>
{% for user in users %}
<tr>
	<td><a href="/media/users/profil/&user={{ user.username }}"><img class="avatar-frame mini" src="/media/avatars/{{ user.avatar }}" border="0" alt="{{ user.username }}"></a></td>
	<td><a href="/media/users/profil&user={{ user.username }}">{{ user.username }}</a></td>
	<td>{{ user.score }}</td>
</tr>
{% endfor %}
</tbody>
</table>
</div>

<!-- MEMBERS.TPL END -->
{% include 'footer.tpl' %}