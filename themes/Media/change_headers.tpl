<!-- CHANGE_HEADERS.TPL START -->

<div class="cellframe headers">
	<a class="btn" href="/media/ticket/write&mode=change&type=image&id={{ request.id }}">{{ AddPost_ShareImage }}</a>
	<a class="btn" href="/media/ticket/write&mode=change&type=video&id={{ request.id }}">{{ AddPost_ShareVideo }}</a>
	<a class="btn" href="/media/ticket/write&mode=change&type=link&id={{ request.id }}">{{ AddPost_ShareLink }}</a>
	<hr>

	{% include '/../Public/errnos.tpl' %}

</div>

<!-- CHANGE_HEADERS.TPL END -->