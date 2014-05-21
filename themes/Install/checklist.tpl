<table class="table table-hover">
	{% for key, field in required %}
	<thead>
		<tr>
			<th>{{ field.title }}</th>
			<th>{{ General_Status }}</th>
		</tr>
	</thead>
	<tbody>
	{% for fields in field %}
		{% if fields.name %}
		<tr>
			<td>{{ fields.name }}</td>
			{% if fields.status == 'missing' %}
			<td><b class="red">{{ General_Error }}</b></td>
			{% else %}
			<td><b class="green">{{ General_Ok }}</b></td>
			{% endif %}
		</tr>
		{% endif %}
	{% endfor %}
	</tbody>
	{% endfor %}
</table>