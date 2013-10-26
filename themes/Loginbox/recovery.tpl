{% include 'headers.tpl' %}
	<!-- BODY -->
		<!-- AFFICHAGE DU FORMULAIRE DE DEMANDE DE MOT DE PASSE -->
		<div id="tenant-forms-center" class="tab-content">
			<form id="cellform-recovery">
				<h3>{{ Login_ForgotPassword}}</h3>
				<div class="control-group">
					<label class="control-label">{{ General_Email }}</label>
					<div class="controls">
						<input name="email" placeholder="email@example.com" maxlength="30" type="email"><a href="#" onclick="cell.DialogBox('{{ Recovery_EmailConfirm }}');">[?]</a>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<input class="btn btn-primary submit-btn" value="{{ Recovery_Reinit }}">
					</div>
				</div>

				<div class="form-nav">
					<a href="#" onclick="history.back();">-- {{ General_Return }} --</a>
				</div>
			</form>
		</div>
	</div>
	{% include 'footer.tpl' %}
<!-- FIN DE LA PAGE -->