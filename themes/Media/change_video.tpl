{% include 'headers.tpl' %}
<!-- CHANGE_VIDEO.TPL START -->

<h1 class="synopsis">{{ Change_Modify }}</h1>

{% include 'change_headers.tpl' %}

<div class="cellframe changevideo">
	<img class="avatar-frame" src="{{ post.image }}" height="150" border="0" alt="{{ post.title }}">

	<form action="/media/ticket/write&mode=change&type=video&id={{ post.id }}" method="post" enctype="multipart/form-data">
		<div class="control-group">
			<label for="url_video">{{ AddPostVideo_Url }}</label>
			<input name="media" maxlength="125" type="text"><a href="#" onclick="cell.DialogBox('{{ AddPostVideo_MoreUrl }}');"> [?]</a>
		</div>

		<div class="control-group">
			<label for="title">{{ AddPost_Title }}</label>
			<input name="title" maxlength="30" value="{{ post.title }}" type="text">
			<label for="description">{{ AddPost_Description }}</label>
			<textarea class="ckeditor field span9" name="description">{{ post.description }}</textarea>
			<input type="hidden" name="csrf" value="{{ csrf }}"/>
		</div>

		<input class="btn btn-primary" name="send" value="{{ General_Modify }}" type="submit">
	</form>
</div>

<!-- CHANGE_VIDEO.TPL END -->
{% include 'footer.tpl' %}