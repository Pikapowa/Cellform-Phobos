<!DOCTYPE html>
<!-- LOGINBOX HEADERS -->
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
<link rel="stylesheet" type="text/css" href="/themes/css/bootstrap/bootstrap-2.css?{{ ID }}">
<link rel="stylesheet" type="text/css" href="/themes/css/cellform/community.css?{{ ID }}">
<link rel="stylesheet" type="text/css" href="/themes/css/cellform/login.css?{{ ID }}">

<noscript><meta http-equiv="refresh" content="0; URL=/home/nojs"></noscript>
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<link rel="stylesheet" type="text/css" href="/themes/css/bootstrap/bootstrap-responsive-2.css?{{ ID }}">

</head>
<body class="ua-mozilla modal-open" id="login">

<div id="bg">
	<img alt="background" src="/images/loginbox/bg.jpg?{{ ID }}">
</div>

<div style="top: 50%; margin-top: -341px; margin-left: -240px; margin-bottom: 15px; display: block;" class="modal hide in" id="modal-login">
	<div class="modal-header">
		<div id="modal-title">
			<h3>
				<span>{{ TITLE }}</span>
			</h3>
			<span>{{ DESC }}</span>
		</div>

		<div class="tenant-image">
			<img alt="banniere" src="/images/loginbox/present.jpg?{{ ID }}">
		</div>
		<div class="description">{{ General_Welcome }} {{ SITENAME }}.<br>{{ General_Nologin }}</div>
	</div>

	<div class="modal-body">
		<div class="links center">
			<a href="/">{{ General_Home}}</a>
			<a href="/media/">{{ General_Community }}</a>
			<a href="/home/subscribe">{{ General_Subscribe }}</a>
			<a href="#" onclick="cell.DialogBox('{{ General_LongPowerBy }}');">{{ General_Apropos }}</a>
		</div>
	<!-- FIN DE L'ENTËTE -->
<!-- DEBUT DU CORPS -->
