<script type="text/javascript">

	var masonry_url = '{{ masonry_url }}';

	var services = {
		'masonry':		{% if masonry_run == 'true' %} true {% else %} false {% endif %},
	};

    var translations = {
		'Username':		'{{ userbase.username }}',
		'Level':		'{{ userbase.level }}',
		'Csrf':			'{{ csrf }}',
        'PostedAt':		'{{ Posts_PostedAt }}',
		'NbComments':	'{{ Posts_Comments }}',
		'Score':		'{{ General_Score }}',

		'Delete':		'{{ General_Delete }}',

		'Commented':	'{{ Notifications_Commented }}',
		'Voted':		'{{ Notifications_Voted }}',
		'AllVotes':		'{{ Notifications_AllVotes }}',
		'AllComments':	'{{ Notifications_AllComments }}',
    };
</script>