/*	LOGINBOX JAVASCRIPT CENTER	*/
var cell;

$LAB.setGlobalDefaults({AlwaysPreserveOrder:true});

$LAB
.script("/js/jquery/jquery-1.10.2.min.js")

.script("/js/bootstrap/bootstrap.js")

.script("/js/cellform/cellform.js")
.script("/js/cellform/cellform-api.js")

.wait(function()
{
	cell = new Cellform();

	var api = 'cellform-api.js';
	cell.init({
		api : api,
		logging : false
	});

	// SIGN-IN
	$('#cellform-login .submit-btn').click(function()
	{
		params = cell.PrepareForm('#cellform-login');

		cell.api('login', params).then(function(res)
		{
			cell.StreamErrno(res);

			if (res == 'success')
			{
				location.href = '/media/';
			}
		});
	});

	// SIGN-UP
	$('#cellform-signup .submit-btn').click(function()
	{
		params = cell.PrepareForm('#cellform-signup');

		cell.api('signup', params).then(function(res)
		{
			cell.StreamErrno(res);
		});
	});

	// RECOVERY
	$('#cellform-recovery .submit-btn').click(function()
	{
		params = cell.PrepareForm('#cellform-recovery');

		cell.api('recovery', params).then(function(res)
		{
			cell.StreamErrno(res);
		});
	});
});