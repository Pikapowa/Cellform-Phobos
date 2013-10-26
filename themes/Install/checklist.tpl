{% for key, field in required %}
	<b class="pull-left">{{ field.title }}</b>
	<b class="pull-right">{{ General_Status }}</b>
	<br>
	{% for fields in field %}

	{% if fields.name %}
	<span class="pull-left">{{ fields.name }}</span>
	{% if fields.status == 'missing' %}
	<b class="pull-right red">{{ General_Error }}</b>
	{% else %}
	<b class="pull-right green">{{ General_Ok }}</b>
	{% endif %}
	<br>
	{% endif %}

	{% endfor %}
	<br>
{% endfor %}