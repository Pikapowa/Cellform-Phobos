{% include 'headers.tpl' %}

		<div class="span9">
			<form action="/install/index.php?mode=licence" method="post" class="form-horizontal">
			<fieldset>
				<div class="hero-unit">
					<legend>{{ General_Intro }}</legend>
					<p>{{ Intro_Content }}</p>
					<a href="/docs/man.htm">{{ ViewDoc }}</a>
					<hr>
					<button class="btn btn-primary btn-large" name="next" type="submit">{{ General_Next }}</button>
				</div>
			</fieldset>
			</form>
		</div>

		<script>$('#btn-1').addClass('active');</script>

{% include 'footer.tpl' %}
