<!DOCTYPE html>
<!-- HEAD MEDIA -->
<html lang="{{ General_Language_Locale }}">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<title>{{ TITLE }}</title>
<!-- Ref -->
<meta name="description" content="{{ DESC }}">

<link rel="shortcut icon" href="/images/icon_default.gif">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/images/icon_default.gif">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/images/icon_default.gif">
<link rel="apple-touch-icon-precomposed" href="/images/icon_default.gif">

<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Iceland">
<link rel="stylesheet" type="text/css" href="/themes/css/jquery/jquery-ui.min.css?{{ ID }}">
<link rel="stylesheet" type="text/css" href="/themes/css/bootstrap/bootstrap-2.css?{{ ID }}">
<link rel="stylesheet" type="text/css" href="/themes/css/fonts/font-awesome/css/font-awesome.css?{{ ID }}">

<link rel="stylesheet" type="text/css" href="/themes/css/bootstrap/bootstrap-responsive-2.css?{{ ID }}">
<link rel="stylesheet" type="text/css" href="/themes/css/cellform/community.css?{{ ID }}">

<noscript><meta http-equiv="refresh" content="0; URL=/home/nojs"></noscript>
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!--[if IE 7]>
  <link rel="stylesheet" href="/themes/css/fonts/font-awesome/css/font-awesome-ie7.min.css?{{ ID }}">
<![endif]-->

</head>

<body>

<!-- START BODY -->

{% if permalink == 'true' %}
	{% include 'permalink-post.tpl' %}
{% endif %}

<div id="overlay"></div>
<!-- START CONTENT -->
<div id="content">

<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<ul class="nav pull-left">
				<li class="purple">
					<a href="/media/ticket/write"><i class="icon-pencil"></i></a>
				</li>

				<li class="dropdown pull-left">
					<a class="dropdown-toggle" id="btn-notifs-comments" role="button" data-toggle="dropdown" href="#"><i class="icon-comments"></i><b class="caret"></b></a>
					<ul id="notifs-comments-list" class="pull-left dropdown-navbar dropdown-menu dropdown-caret dropdown-closer">
					</ul>
				</li>

				<li class="dropdown pull-left">
					<a class="dropdown-toggle" id="btn-notifs-votes" role="button" data-toggle="dropdown" href="#"><i class="icon-flag"></i><b class="caret"></b></a>
					<ul id="notifs-votes-list" class="pull-left dropdown-navbar dropdown-menu dropdown-caret dropdown-closer">
					</ul>
				</li>
			</ul>

			<div class="nav-collapse collapse">
				<ul class="nav">
					<li><a href="/media/">{{ General_Home }}</a></li>
					<li><a href="/media/ticket/top">{{ General_Ranking }}</a></li>
					<li><a href="/media/users/members">{{ General_Members }}</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ userbase.username }}<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/media/users/account">{{ General_MyAccount }}</a></li>
							<li class="divider"></li>
							<li><a href="/media/favorites/view">{{ General_MyFavorites }}</a></li>
							<li><a href="/media/mp/inbox">{{ General_MyMailBox }}</a></li>
							<li><a href="/media/friends/lists">{{ General_MyFriends }}</a></li>
							<li class="divider"></li>
							<li><a href="/media/users/profil&user={{ userbase.username }}">{{ General_MyTickets }}</a></li>
							<li><a href="/media/notifications/all&user={{ userbase.username }}&top=10&type=votes">{{ General_MyNotifications }}</a></li>
							<li class="divider"></li>
							{% if userbase.level == '2' %}
							<li><a href="/admin">{{ General_Administration }}</a></li>
							{% endif %}
						</ul>
					</li>
					<li><a href="/media/users/logout">{{ General_Logout }}</a></li>
				</ul>

				<div class="ui-widget">
					<form action="/media/ticket/search" method="get" class="navbar-form pull-right" autocomplete="off">
						<div class="input-group">
							<input name="keyword" id="postsearch" class="form-control" type="text" placeholder="{{ General_SearchTicket }}">
							<span class="input-group-btn">
								<button name="send" type="submit" class="btn btn-search">{{ General_Search }}</button>
							</span>
						</div>
					</form>
				</div>
			</div><!--/.nav-collapse -->
		</div>
	</div>
</div>

<header class="header-ban">
	<div class="container">
		<h1>{{ SITENAME }}</h1>
		<img class="avatar-frame" src="/media/avatars/{{ userbase.avatar }}" alt="{{ userbase.username }}">
		<br><a href="/media/mp/inbox"><i class="icon-envelope"></i> - {{ userbase.mp }} {{ General_NewMp }}</a>
	</div>
</header>

<div id="main" class="main-container">