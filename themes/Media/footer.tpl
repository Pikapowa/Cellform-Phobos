<!-- FOOTER.TPL START -->
</div>
<!-- END MAIN -->
</div>
<!-- END CONTENT -->

<nav id="chatbox-dropdown" class="navbar navbar-fixed-bottom navbar-inverse" style="position:fixed; width:200px" role="navigation">
<div class="navbar-inner">
	<ul class="nav navbar-nav">

		<li id="friendslist" class="dropdown">
			<a href="#" id="btn-notifs-onlinefriends" class="dropdown-toggle" data-toggle="dropdown">{{ General_OnlineFriends }}<b class="caret"></b></a>
			<ul id="notifs-onlinefriends-list" class="dropdown-menu">
			</ul>
		</li>

	</ul>
</div>
</nav>

<!-- Le javascript
================================================== -->

{% include '/../Public/global_js.tpl' %}	<!-- For javascript globals variables -->

<script type="text/javascript" src="/js/LAB.min.js?{{ ID }}"></script>
<script type="text/javascript" src="/js/media.js?{{ ID }}"></script>

<!-- END BODY -->
</body>
</html>