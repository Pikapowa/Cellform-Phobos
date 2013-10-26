{% include 'headers.tpl' %}
<!-- BODY -->
		<!-- AFFICHAGE DU FORMULAIRE DE CONNEXION -->
			<div id="tenant-forms" class="tab-content">
				{% include 'sns.tpl' %}

				<form id="cellform-login" class="tab-pane active form-horizontal">
					<div class="control-group">
						<label class="control-label">{{ Login_Username }}</label>
						<div class="controls">
							<input name="login" placeholder="email@example.com" type="email">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">{{ Login_Password }}</label>
						<div class="controls">
							<input name="password" type="password">
						</div>
					</div>

					<div class="form-bigbutton">
						<input class="btn btn-primary submit-btn" value="{{ Login_Connect }}">
					</div>
				</form>

				<br>
				<div id="modal-recovery">
					<a href="/home/forgotpassword">{{ Login_ForgotPassword }}</a>
				</div>
			</div>
		</div>
	{% include 'footer.tpl' %}
<!-- FIN DE LA PAGE -->	