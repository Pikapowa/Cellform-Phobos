{% include 'headers.tpl' %}
<!-- MP.TPL START -->

<h1 class="synopsis">{{ Mail }}</h1>
<div class="cellframe mp">
	<a class="btn" href="/media/mp/inbox">{{ Mail_Inbox }}</a>
	<a class="btn" href="/media/mp/send">{{ Profil_SendPm }}</a>
	<a class="btn" href="#" onclick="cell.DelMp();">{{ Mail_Delete }}</a>
	<input type="hidden" name="csrf_post_ajax" id="csrf_post_ajax" value="{{ csrf_token }}"/>
	<hr>
	<table class="table table-hover">
		<thead>
		<tr>
			<th>{{ General_Delete }}</th>
			<th>{{ General_Title }}</th>
			<th>{{ General_Date }}</th>
			<th>{{ General_Author }}</th>
		</tr>
		</thead>
		<tbody>
		{% for mp in mps %}
		{% if mp.useread == 'no' %}
		<tr id="nodes_{{ mp.id }}" class="newmail">
		{% else %}
		<tr id="nodes_{{ mp.id }}">
		{% endif %}
			<td><input type="checkbox" name="nodes[]" value="{{ mp.id }}"></td>
			<td><a href="/media/mp/view&id={{ mp.id }}">{{ mp.subject }}</a></td>
			<td>{{ mp.date|date("d/m/Y H:i") }}</td>
			<td><a href="/media/users/profil&user={{ mp.username }}">{{ mp.username }}</a></td>
		</tr>
		{% endfor %}
		</tbody>
	</table>
</div>

<!-- MP.TPL END -->
{% include 'footer.tpl' %}