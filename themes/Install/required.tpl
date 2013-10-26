{% include 'headers.tpl' %}

		<div class="span9">
			<form action="/install/index.php?mode=configmain" method="post" class="form-horizontal">
			<fieldset>
				<div class="hero-unit">
					<legend>{{ General_Required }}</legend>

					{% include 'checklist.tpl' %}

					<hr>
					<button class="btn btn-primary btn-large" id="send" name="next" type="submit">{{ General_Next }}</button>
				</div>
			</fieldset>
			</form>
		</div>

		<script>
			{% if ready == 'no' %}
			$('#send').prop('disabled', true);
			{% endif %}

			$('#btn-3').addClass('active');
		</script>

{% include 'footer.tpl' %}