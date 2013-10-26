<!-- SOCIAL NETWORK SERVICE -->
	<div id="login-with-sns" class="form-bigbutton">
		{% if FACEBOOK_ENABLED == 'true' %}
		<a href="/home/oauth?service=Facebook" class="btn"><img src="/images/loginbox/sns/facebook.png"></a>
		{% endif %}

		{% if GOOGLE_ENABLED == 'true' %}
		<a href="/home/oauth?service=Google" class="btn"><img src="/images/loginbox/sns/google.png"></a>
		{% endif %}
	</div>
<!-- AND SOCIAL NETWORK SERVICE -->