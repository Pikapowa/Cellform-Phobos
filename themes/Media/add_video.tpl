{% include 'headers.tpl' %}
<!-- ADD_VIDEO.TPL START -->

<h1 class="synopsis">{{ AddPost_ShareVideo }}</h1>

{% include 'add_headers.tpl' %}

<div class="cellframe addvideo">
	<form action="/media/ticket/write&mode=add&type=video" method="post">
		<div class="control-group">
			<label for="url_video">{{ AddPostVideo_Url }}</label>
			<input name="media" maxlength="125" type="text"><a href="#" onclick="cell.DialogBox('{{ AddPostVideo_MoreUrl }}');"> [?]</a>
		</div>

		<div class="control-group">
			<label for="title">{{ AddPost_Title }}</label>
			<input name="title" maxlength="30" type="text" id="titre">
			<label for="description">{{ AddPost_Description }}</label>
			<textarea class="ckeditor field span9" name="description"></textarea>
			<input type="hidden" name="csrf" value="{{ csrf }}"/>
		</div>

		<input class="btn btn-primary" name="send" value="{{ General_Send }}" type="submit">
	</form>
</div>

<!-- ADD_VIDEO.TPL END -->
{% include 'footer.tpl' %}