<!-- ERRNOS.TPL START -->

<div class="errnos">
	{% for error in errnos.errors %}
	<div class="alert alert-error">
		<a class="close" data-dismiss="alert" href="#">×</a>{{ error }}
	</div>
	{% endfor %}

	{% for notification in errnos.notifs %}
	<div class="alert alert-success">
		<a class="close" data-dismiss="alert" href="#">×</a>{{ notification }}
	</div>
	{% endfor %}
</div>

<!-- ERRNOS.TPL END -->