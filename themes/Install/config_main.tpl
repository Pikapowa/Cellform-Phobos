{% include 'headers.tpl' %}

		<div class="span9">
			<form action="/install/index.php?mode=configmain" method="post" enctype="multipart/form-data" class="form-horizontal">
			<fieldset>
				<div class="hero-unit">
					<legend>{{ General_ConfigMain }}</legend>

					{% include '/../Public/errnos.tpl' %}

					<div class="control-group">
						<label class="control-label">{{ ConfigMain_SiteName }}</label>
						<div class="controls">
							<input name="sitename" value="{{ SITENAME }}" maxlength="20" type="text" class="input-xlarge"><a href="#?" onclick="alert('{{ ConfigMain_SiteNameMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ ConfigMain_SiteTitle }}</label>
						<div class="controls">
							<input name="sitetitle" value="{{ TITLE }}" maxlength="64" type="text" class="input-xlarge"><a href="#?" onclick="alert('{{ ConfigMain_SiteTitleMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ ConfigMain_SiteDescription }}</label>
						<div class="controls">
							<input name="description" value="{{ DESC }}" maxlength="128" type="text" class="input-xlarge"><a href="#?" onclick="alert('{{ ConfigMain_SiteDescriptionMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ ConfigMain_SiteEmail }}</label>
						<div class="controls">
							<input name="email" value="{{ EMAIL }}" maxlength="30" type="email" class="input-xlarge"><a href="#?" onclick="alert('{{ ConfigMain_SiteEmailMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ ConfigMain_SiteKey }}</label>
						<div class="controls">
							<input name="salt" maxlength="10" type="text"><a href="#?" onclick="alert('{{ ConfigMain_SiteKeyMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ ConfigMain_SiteLang }} (fr, en)</label>
						<div class="controls">
							<input name="lang" value="{{ DEFAULTS_LANG }}" maxlength="2" type="text"><a href="#?" onclick="alert('{{ ConfigMain_SiteLangMore }}');">[?]</a>
						</div>
					</div>

					<hr>
					<button class="btn btn-primary btn-large" id="send" name="send" type="submit">{{ General_Save }}</button>
				</div>
			</fieldset>
			</form>
		</div><!--/span-->

		<script>
			$('#btn-4').addClass('active');
		</script>

{% include 'footer.tpl' %}