{% include 'headers.tpl' %}
	<!-- BODY -->
		<!-- AFFICHAGE DE LA PAGE DE PROTECTION -->
		<div id="tenant-forms-center" class="tab-content">
			<p>
			<img alt="warning" src="/images/warning-large.png"><br>
			<b style="color:red">{{ Nologin_SecureArea }}</b><br>
			<b style="color:red">{{ Nologin_SecureAreaMore }}</b><br>
			<b style="color:red">{{ General_Nologin }}</b><br>
			</p>
			<div class="form-nav">
				<a href="#" onclick="history.back();">-- {{ General_Return }} --</a>
			</div>
		</div>
	</div>
	{% include 'footer.tpl' %}
<!-- FIN DE LA PAGE -->