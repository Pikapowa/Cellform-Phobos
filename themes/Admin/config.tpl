{% include 'headers.tpl' %}
<!-- MAIN.TPL START -->
		<div class="span9">
			<form action="/admin" method="post" enctype="multipart/form-data" class="form-horizontal">
			<div class="hero-unit">
				<fieldset>
					<legend>{{ Site_ConfigMain }}</legend>

					{% include '/../Public/errnos.tpl' %}

					<div class="control-group">
						<label class="control-label">{{ Site_Name }}</label>
						<div class="controls">
							<input name="sitename" value="{{ SITENAME }}" maxlength="20" type="text" class="input-xlarge"><a href="#" onclick="cell.DialogBox('{{ Site_NameMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ Site_Title }}</label>
						<div class="controls">
							<input name="sitetitle" value="{{ TITLE }}" maxlength="64" type="text" class="input-xlarge"><a href="#" onclick="cell.DialogBox('{{ Site_TitleMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ Site_Desc }}</label>
						<div class="controls">
							<input name="description" value="{{ DESC }}" maxlength="128" type="text" class="input-xlarge"><a href="#" onclick="cell.DialogBox('{{ Site_DescMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ Site_Mail }}</label>
						<div class="controls">
							<input name="email" value="{{ EMAIL }}" maxlength="30" type="email" class="input-xlarge"><a href="#" onclick="cell.DialogBox('{{ Site_MailMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ Site_Id }}</label>
						<div class="controls">
							<input name="id" value="{{ ID }}" maxlength="10" type="text"><a href="#" onclick="cell.DialogBox('{{ Site_IdMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ Site_Lang }} (fr, en)</label>
						<div class="controls">
							<input name="lang" value="{{ DEFAULTS_LANG }}" maxlength="2" type="text"><a href="#" onclick="cell.DialogBox('{{ Site_LangMore }}');">[?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ Site_PicturePresent }}</label>
						<div class="controls">
							<input name="present" type="file" title="{{ Site_LoadImagePresent }}" accept="image/jpeg"><a href="#" onclick="cell.DialogBox('{{ Site_PicturePresentMore }}');"> [?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ Site_PictureBg }}</label>
						<div class="controls">
							<input name="bg" type="file" title="{{ Site_LoadImageBg }}" accept="image/jpeg"><a href="#" onclick="cell.DialogBox('{{ Site_PictureBgMore }}');"> [?]</a>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ Site_PictureBanner }}</label>
						<div class="controls">
							<input name="banner" type="file" title="{{ Site_LoadImageBanner }}" accept="image/png"><a href="#" onclick="cell.DialogBox('{{ Site_PictureBannerMore }}');"> [?]</a>
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

<!-- MAIN.TPL END -->
{% include 'footer.tpl' %}