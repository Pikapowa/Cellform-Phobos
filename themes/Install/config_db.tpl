{% include 'headers.tpl' %}

		<div class="span9">
			<form action="/install/index.php?mode=configdb" method="post" enctype="multipart/form-data" class="form-horizontal">
			<fieldset>
				<div class="hero-unit">
					<legend>{{ General_ConfigDb }}</legend>

					{% include '/../Public/errnos.tpl' %}

					<div class="control-group">
						<label class="control-label">{{ ConfigDb_Hostname }}</label>
						<div class="controls">
							<input name="hostname" maxlength="20" type="text" class="input-xlarge"><a href="#?" onclick="alert('{{ ConfigDb_HostnameMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ ConfigDb_Name }}</label>
						<div class="controls">
							<input name="name" maxlength="20" type="text" class="input-xlarge"><a href="#?" onclick="alert('{{ ConfigDb_NameMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ ConfigDb_User }}</label>
						<div class="controls">
							<input name="username" maxlength="20" type="text" class="input-xlarge"><a href="#?" onclick="alert('{{ ConfigDb_UserMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ ConfigDb_Password }}</label>
						<div class="controls">
							<input name="password" maxlength="20" type="password" class="input-xlarge"><a href="#?" onclick="alert('{{ ConfigDb_PasswordMore }}');">[?]</a>
						</div>
					</div>

					<hr>
					<button class="btn btn-primary btn-large" id="send" name="send" type="submit">{{ General_Save }}</button>
				</div>
			</fieldset>
			</form>
		</div><!--/span-->

		<script>
			$('#btn-5').addClass('active');
		</script>

{% include 'footer.tpl' %}