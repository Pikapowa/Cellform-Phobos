{% include 'headers.tpl' %}
<!-- DELUSER.TPL START -->

		<div class="span9">
			<form action="/admin/deluser" method="post" class="form-horizontal">
			<div class="hero-unit">
				<fieldset>
					<legend>{{ User_Delete }}</legend>

					{% include '/../Public/errnos.tpl' %}

					<div class="control-group">
						<label class="control-label">{{ User_Delete }}</label>
						<div class="controls">
							<input name="username" id="membersearch" type="text">
						</div>
					</div>

					<div class="form-actions">
						<input type="hidden" name="csrf" value="{{ csrf }}"/>
						<button class="btn btn-warning" name="send" type="submit">{{ General_Delete }}</button>
					</div>
				</fieldset>
			</div>
			</form>
		</div><!--/span-->

<!-- DELUSER.TPL END -->
{% include 'footer.tpl' %}