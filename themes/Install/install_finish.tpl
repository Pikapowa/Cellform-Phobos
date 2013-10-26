{% include 'headers.tpl' %}

		<div class="span9">
			<form action="/" class="form-horizontal">
			<fieldset>
				<div class="hero-unit">
					<legend>{{ General_InstallFinish }}</legend>

					<h1>{{ InstallFinish_Congratulation }}</h1>
					<hr>
					<br>
					<span class="red">{{ InstallFinish_Warning}}</span>
					<hr>

					<button class="btn btn-primary btn-large" type="submit">{{ InstallFinish_Connect }}</button>
				</div>
			</fieldset>
			</form>
		</div><!--/span-->

		<script>
			$('#btn-8').addClass('active');
		</script>

{% include 'footer.tpl' %}