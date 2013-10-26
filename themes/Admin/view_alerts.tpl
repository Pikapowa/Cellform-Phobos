{% include 'headers.tpl' %}
<!-- DELALERTS.TPL START -->

		<div class="span9">
			<div class="hero-unit">
				<fieldset>
				<legend>{{ Ticket_Alerts }}</legend>

				<div class="cellframe delalerts">
					<a class="btn btn-warning" href="#" onclick="cell.DelAlerts();">{{ Ticket_AlertsDelete }}</a>
					<a class="btn btn-warning" href="#" onclick="cell.DelTickets();">{{ Ticket_Delete }}</a>

					<hr>
					<table class="table table-hover">
					<thead>
					<tr>
						<th>[X]</th>
						<th>{{ Ticket_Title }}</th>
						<th>{{ Ticket_Author }}</th>
						<th>{{ Ticket_AlertAuthor }}</th>
						<th>{{ General_Date }}</th>
					</tr>
					</thead>
					<tbody>
						{% for alert in alerts %}
						<tr id="nodes_{{ alert.id }}">
							<td><input type="checkbox" name="nodes[]" value="{{ alert.id }}"></td>
							<td><a href="/media/ticket/view&id={{ alert.id }}">{{ alert.title }}</a></td>
							<td><a href="/media/users/profil&user={{ alert.user }}">{{ alert.user }}</a></td>
							<td><a href="/media/users/profil&user={{ alert.user_alert }}">{{ alert.user_alert }}</a></td>
							<td>{{ alert.date|date("d/m/Y H:i") }}</td>
						</tr>
						{% endfor %}
					</tbody>
					</table>
				</div>
				</fieldset>
			</div>
		</div><!--/span-->

<!-- DELALERTS.TPL END -->
{% include 'footer.tpl' %}