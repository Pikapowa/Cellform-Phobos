{% include 'headers.tpl' %}
<!-- ADD_IMAGE.TPL START -->

<h1 class="synopsis">{{ AddPost_ShareImage }}</h1>

{% include 'add_headers.tpl' %}

<div class="cellframe addimage">
	<form action="/media/ticket/write&mode=add&type=image" method="post" enctype="multipart/form-data">
		<div class="control-group">
			<input name="image" title="{{ AddPostImage_LoadImage }}" type="file" accept="image/gif,image/jpeg,image/png"><br>
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

<!-- ADD_IMAGE.TPL END -->
{% include 'footer.tpl' %}