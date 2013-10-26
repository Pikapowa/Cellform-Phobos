<!DOCTYPE html>
<!-- ADMIN HEADERS -->
<html lang="{{ General_Language_Locale }}">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<!-- Bootstrap scale disable -->
<link rel="shortcut icon" href="/images/icon_default.gif">
<title>{{ General_Title }}</title>

<script type="text/javascript" src="/js/jquery/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/js/bootstrap/bootstrap.js"></script>

<link rel="stylesheet" type="text/css" href="/themes/css/bootstrap/bootstrap-2.css">
<link rel="stylesheet" type="text/css" href="/themes/css/cellform/community.css">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Iceland">

<link rel="stylesheet" type="text/css" href="/themes/css/bootstrap/bootstrap-responsive-2.css">

<style>
body {
	margin-top: 60px;
}
</style>

</head>

<body>

<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="brand" href="#">
				<img src="/images/icon_default.gif">
			</a>
			<a class="brand" href="/install/index.php">{{ General_Title }}</a>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span3">
			<div class="well sidebar-nav sidebar-nav-fixed">
				<ul class="nav nav-list">
					<li class="nav-header">{{ General_Title }}</li>
					<li id="btn-1"><a>{{ General_Intro }}</a></li>
					<li id="btn-2"><a>{{ General_Licence }}</a></li>
					<li id="btn-3"><a>{{ General_Required }}</a></li>
					<li id="btn-4"><a>{{ General_ConfigMain }}</a></li>
					<li id="btn-5"><a>{{ General_ConfigDb }}</a></li>
					<li id="btn-6"><a>{{ General_Install }}</a>
					<li id="btn-7"><a>{{ General_ConfigAdmin }}</a>
					<li id="btn-8"><a>{{ General_InstallFinish }}</a></li>
				</ul>
			</div><!--/.well -->
		</div><!--/span-->