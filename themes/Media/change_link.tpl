{% include 'headers.tpl' %}
<!-- CHANGE_LINK.TPL START -->

<h1 class="synopsis">{{ Change_Modify }}</h1>

{% include 'change_headers.tpl' %}

<div class="cellframe changelink">
	<form action="/media/ticket/write&mode=change&type=link&id={{ post.id }}" method="post">
		<div class="control-group">
			<label for="title">{{ AddPostLink_Url }}</label>
			<input name="media" maxlength="125" type="text" value="{{ post.media }}" title="{{ AddPost_ShareLink }}"><br>
		</div>

		<div class="control-group">
			<label for="title">{{ AddPost_Title }}</label>
			<input name="title" maxlength="30" type="text" value="{{ post.title }}" id="titre">
			<label for="description">{{ AddPost_Description }}</label>
			<textarea class="ckeditor field span9" name="description">{{ post.description }}</textarea>
			<input type="hidden" name="csrf" value="{{ csrf }}"/>
		</div>

		<input class="btn btn-primary" name="send" value="{{ General_Send }}" type="submit">
	</form>
</div>

<!-- CHANGE_LINK.TPL END -->
{% include 'footer.tpl' %}