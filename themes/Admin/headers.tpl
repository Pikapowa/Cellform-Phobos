<!DOCTYPE html>
<!-- ADMIN HEADERS -->
<html lang="{{ General_Language_Locale }}">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<title>{{ TITLE }} - {{ General_Admin }}</title>
<!-- Ref -->
<meta name="description" content="{{ DESC }}">

<link rel="shortcut icon" href="/images/icon_default.gif">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/images/icon_default.gif">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/images/icon_default.gif">
<link rel="apple-touch-icon-precomposed" href="/images/icon_default.gif">

<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Iceland">
<link rel="stylesheet" type="text/css" href="/themes/css/jquery/jquery-ui.min.css?{{ ID }}">
<link rel="stylesheet" type="text/css" href="/themes/css/bootstrap/bootstrap-2.css?{{ ID }}">
<link rel="stylesheet" type="text/css" href="/themes/css/cellform/community.css?{{ ID }}">

<noscript><meta http-equiv="refresh" content="0; URL=/home/nojs"></noscript>
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<link rel="stylesheet" type="text/css" href="/themes/css/bootstrap/bootstrap-responsive-2.css?{{ ID }}">

<style>
body {
	margin-top: 60px;
}
</style>

</head>
<body>
<div id="content">
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="brand" href="#">
				<img src="/images/icon_default.gif">
			</a>
			<a class="brand" href="/admin">{{ SITENAME }} - {{ General_Admin }}</a>
		</div>
	</div>
</div>

	<div class="container-fluid">
	<div class="row-fluid">
		<div class="span3">
			<div class="well sidebar-nav sidebar-nav-fixed">
				<ul class="nav nav-list">
				<li class="nav-header">{{ Site_Panel }}</li>
				<li><a href="/admin">{{ Site_ConfigMain }}</a></li>
				<li><a href="/admin/oauth">{{ Site_ConfigOAuth }}</a></li>
				<li class="nav-header">{{ User_Panel }}</li>
				<li><a href="/admin/deluser">{{ User_Delete }}</a></li>
				<li class="nav-header">{{ Ticket_Panel }}</li>
				<li><a href="/admin/viewalerts">{{ Ticket_Alerts }}</a></li>
				<li class="nav-header">{{ Admin_Panel }}</li>
				<li><a href="/media">{{ Admin_Quit }}</a></li>
				</ul>
			</div><!--/.well -->
		</div><!--/span-->