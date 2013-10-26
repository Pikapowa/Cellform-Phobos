{% include 'headers.tpl' %}
<!-- CHANGE_IMAGE.TPL START -->

<h1 class="synopsis">{{ Change_Modify }}</h1>

{% include 'change_headers.tpl' %}

<div class="cellframe changeimage">
	<form action="/media/ticket/write&mode=change&type=image&id={{ post.id }}" method="post" enctype="multipart/form-data">
		<div class="control-group">
			<input name="image" title="{{ AddPostImage_LoadImage }}" type="file" accept="image/gif,image/jpeg,image/png"><br>
			<img class="avatar-frame" src="/media/img/min/{{ post.image }}" height="150" alt="{{ post.title }}">
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

<!-- CHANGE_IMAGE.TPL END -->
{% include 'footer.tpl' %}

