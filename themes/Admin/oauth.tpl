{% include 'headers.tpl' %}
<!-- OAUTH.TPL START -->
		<div class="span9">
			<form action="/admin/oauth" method="post" enctype="multipart/form-data" class="form-horizontal">
			<div class="hero-unit">
				<fieldset>
					<legend>{{ Site_ConfigOAuth }}</legend>

					{% include '/../Public/errnos.tpl' %}

					<legend>{{ Site_OAuthFacebook }}</legend>

					<div class="control-group">
						<label class="control-label">{{ Site_OAuthFacebook }}</label>
						<div class="controls">
							<label class="radio inline"><input name="facebook_enabled" value="true"  type="radio" {% if FACEBOOK_ENABLED == 'true' %}  checked {% endif %}>Activé</label>
							<label class="radio inline"><input name="facebook_enabled" value="false" type="radio" {% if FACEBOOK_ENABLED == 'false' %} checked {% endif %}>Désactivé</label>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ Site_OAuthAppID }}</label>
						<div class="controls">
							<input name="facebook_appid" value="{{ FACEBOOK_APPID }}" type="text" class="input-xlarge"><a href="#" onclick="cell.DialogBox('{{ Site_OAuthAppIDMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ Site_OAuthSecret }}</label>
						<div class="controls">
							<input name="facebook_secret" value="{{ FACEBOOK_SECRET }}" type="text" class="input-xlarge"><a href="#" onclick="cell.DialogBox('{{ Site_OAuthSecretMore }}');">[?]</a>
						</div>
					</div>

					<legend>{{ Site_OAuthGoogle }}</legend>

					<div class="control-group">
						<label class="control-label">{{ Site_OAuthGoogle }}</label>
						<div class="controls">
							<label class="radio inline"><input name="google_enabled" value="true"  type="radio" {% if GOOGLE_ENABLED == 'true' %}  checked {% endif %}>Activé</label>
							<label class="radio inline"><input name="google_enabled" value="false" type="radio" {% if GOOGLE_ENABLED == 'false' %} checked {% endif %}>Désactivé</label>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ Site_OAuthAppID }}</label>
						<div class="controls">
							<input name="google_appid" value="{{ GOOGLE_APPID }}" type="text" class="input-xlarge"><a href="#" onclick="cell.DialogBox('{{ Site_OAuthAppIDMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ Site_OAuthSecret }}</label>
						<div class="controls">
							<input name="google_secret" value="{{ GOOGLE_SECRET }}" type="text" class="input-xlarge"><a href="#" onclick="cell.DialogBox('{{ Site_OAuthSecretMore }}');">[?]</a>
						</div>
					</div>

					<div class="form-actions">
						<input type="hidden" name="csrf" value="{{ csrf }}"/>
						<button class="btn btn-primary" name="send" type="submit">{{ General_Modify }}</button>
					</div>
				</fieldset>
			</div>
			</form>
		</div><!--/span-->

<!-- OAUTH.TPL END -->
{% include 'footer.tpl' %}