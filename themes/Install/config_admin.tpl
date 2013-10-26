{% include 'headers.tpl' %}

		<div class="span9">
			<form action="/install/index.php?mode=configadmin" method="post" enctype="multipart/form-data" class="form-horizontal">
			<fieldset>
				<div class="hero-unit">
					<legend>{{ General_ConfigAdmin }}</legend>

					{% include '/../Public/errnos.tpl' %}

					<div class="control-group">
						<label class="control-label">{{ ConfigAdmin_Email }}</label>
						<div class="controls">
							<input name="email" maxlength="30" type="email" class="input-xlarge"><a href="#?" onclick="alert('{{ ConfigAdmin_EmailMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ ConfigAdmin_Username }}</label>
						<div class="controls">
							<input name="username" maxlength="12" type="text" class="input-xlarge"><a href="#?" onclick="alert('{{ ConfigAdmin_UsernameMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ ConfigAdmin_Password }}</label>
						<div class="controls">
							<input name="password" maxlength="30" type="password" class="input-xlarge"><a href="#?" onclick="alert('{{ ConfigAdmin_PasswordMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ ConfigAdmin_PasswordConfirm }}</label>
						<div class="controls">
							<input name="password_confirm" maxlength="30" type="password" class="input-xlarge"><a href="#?" onclick="alert('{{ ConfigAdmin_PasswordConfirmMore }}');">[?]</a>
						</div>
					</div>

					<hr>
					<button class="btn btn-primary btn-large" id="send" name="send" type="submit">{{ General_Save }}</button>
				</div>
			</fieldset>
			</form>
		</div><!--/span-->

		<script>
			$('#btn-7').addClass('active');
		</script>

{% include 'footer.tpl' %}