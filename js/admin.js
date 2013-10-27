/*	MEDIA JAVASCRIPT CENTER	*/
var cell;

$LAB.setGlobalDefaults({AlwaysPreserveOrder:true});

$LAB
.script("/js/jquery/jquery-1.10.2.min.js")
.script("/js/jquery/jquery-ui.js")

.script("/js/bootstrap/bootstrap.js")
.script("/js/bootstrap/bootstrap-fileinput.js")

.script("/js/cellform/cellform.js")
.script("/js/cellform/cellform-api.js")

.wait(function()
{
	$(document).ready(function ()
	{
		var api  = 'cellform-api.js';
		cell = new Cellform();

		cell.init({
			api : api,
			logging : false
		});

		// MEMBER SEARCH
		$(document).on('click', '#membersearch', function ()
		{
			cell.api('userlist').then(function(res)
			{
				$('#membersearch').autocomplete(
				{
					minLength: 1,
					scrollHeight: 220, 
					source: res
				});
			});
		});
	});
});

