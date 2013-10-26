{% include 'headers.tpl' %}
<!-- ADD_LINK.TPL START -->

<h1 class="synopsis">{{ AddPost_ShareLink }}</h1>

{% include 'add_headers.tpl' %}

<div class="cellframe addlink">
	<form action="/media/ticket/write&mode=add&type=link" method="post">
		<div class="control-group">
			<label for="title">{{ AddPostLink_Url }}</label>
			<input name="media" maxlength="125" type="text" title="{{ AddPost_ShareLink }}"><br>
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

<!-- ADD_LINK.TPL END -->
{% include 'footer.tpl' %}