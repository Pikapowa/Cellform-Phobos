{% include 'mail_headers.tpl' %}

<!-- BODY -->
<tr class="cellform_mail_body">
	<td class="w609" width="609">
		<table class="w609" width="609" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td colspan="5" height="10"></td>
				</tr>
				<tr>
					<td class="w30" width="30"></td>
					<td class="w580" align="left" width="580">
					
						<h2>{{ SITENAME }} - {{ Recovery_Title }}</h2>
						<p>
							{{ General_Hello }} {{ email }}.</br>
							{{ Recovery_Body }} : {{ newpassword }}.</br>
						</p>

					</td>
				</tr>
				<tr>
					<td colspan="5" height="10"></td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>

{% include 'mail_footer.tpl' %}