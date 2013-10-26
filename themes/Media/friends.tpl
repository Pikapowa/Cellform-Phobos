{% include 'headers.tpl' %}
<!-- FRIENDS.TPL START -->

<h1 class="synopsis">{{ General_MyFriends }}</h1>
<div class="cellframe friends-list">
	<a class="btn" href="#" onclick="cell.DelFriend();">{{ General_Delete }}</a>
	<input type="hidden" name="csrf_post_ajax" id="csrf_post_ajax" value="{{ csrf_token }}"/>
	<hr>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th>{{ General_Select }}</th>
				<th>{{ General_Profil }}</th>
				<th>{{ General_Pseudo }}</th>
				<th>{{ General_Score }}</th>
			</tr>
		</thead>
		<tbody>
		{% for friend in friends %}
			<tr id="nodes_{{ friend.id }}">
				<td><input type="checkbox" name="nodes[]" value="{{ friend.id }}"></td>
				<td><a href="/media/users/profil/&user={{ friend.friends }}"><img class="avatar-frame mini" src="/media/avatars/{{ friend.avatar }}" border="0" alt="{{ friend.friends }}"></a></td>
				<td><a href="/media/users/profil/&user={{ friend.friends }}">{{ friend.friends }}</a></td>
				<td>{{ friend.score }}</td>
			</tr>
		{% endfor %}
		</tbody>
	</table>
</div>

<!-- FRIENDS.TPL END -->
{% include 'footer.tpl' %}