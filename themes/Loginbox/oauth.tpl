{% include 'headers.tpl' %}
<!-- BODY -->
		<!-- AFFICHAGE DU FORMULAIRE DE CONNEXION -->
			<div id="tenant-forms-center" class="tab-content">
				<img src="/images/loginbox/sns-connect.png">

				<form action="/home/oauth?service={{ service }}" method="post" class="tab-pane active">

					{% include '/../Public/errnos.tpl' %}

					<div class="control-group">
						<label class="control-label">{{ Signup_Username }}</label>
						<div class="controls">
							<input name="username" placeholder="{{ Signup_EnterUsername }}" maxlength="12" type="text">
						</div>
					</div>

					<div class="form-bigbutton">
						<button class="btn btn-primary submit-btn" name="send" type="submit">{{ OAuth_Signup }}</button>
					</div>
				</form>

				<br>
			</div>
		</div>
	{% include 'footer.tpl' %}
<!-- FIN DE LA PAGE -->	