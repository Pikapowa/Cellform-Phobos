/*	MEDIA JAVASCRIPT CENTER	*/
var cell;

$LAB.setGlobalDefaults({AlwaysPreserveOrder:true});

$LAB
.script("/js/jquery/jquery-1.10.2.min.js")
.script("/js/jquery/plugins/jquery.imagesloaded.js")
.script("/js/jquery/plugins/jquery.browser.mobile.js")
.script("/js/jquery/plugins/jquery.loadJSON.js")
.script("/js/jquery/jquery-ui.js")

.script("/js/masonry.pkgd.min.js")

.script("/js/ckeditor/ckeditor.js")

.script("/js/bootstrap/bootstrap.js")
.script("/js/bootstrap/bootstrap-fileinput.js")

.script("/js/cellform/cellform.js")
.script("/js/cellform/cellform-api.js")

.wait(function()
{
	$(document).ready(function ()
	{
		var api = 'cellform-api.js';

		cell = new Cellform();

		cell.init({
			api : api,
			logging : false,
		});

		if (services.masonry)
		{
			cell.Service_Masonry();

			cell.Service_PostsVote();
			cell.Service_ComsVote();

			cell.Service_Permalink();
		}

		// NOTIFICATIONS IN NAV-BAR
		$(document).on('click', '#btn-notifs-comments', function ()
		{
			cell.GetNotifsComments();
		});

		$(document).on('click', '#btn-notifs-votes', function ()
		{
			cell.GetNotifsVotes();
		});

		// MEMBER SEARCH
		$(document).on('click', '#membersearch', function ()
		{
			cell.api('userlist').then(function(res)
			{
				$('#membersearch').autocomplete(
				{
					minLength: 2,
					scrollHeight: 220, 
					source: res,
				});
			});
		});

		// POST SEARCH
		$(document).on('click', '#postsearch', function ()
		{
			cell.api('postlist').then(function(res)
			{
				$('#postsearch').autocomplete(
				{
					minLength: 2,
					scrollHeight: 220, 
					source: res,
				});
			});
		});
	});
});

