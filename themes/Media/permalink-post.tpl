<!-- PERMALINK-POST START -->
	<div id="permalink-post" class="modal">
		<!-- for permalink -->

		<div class="loading">
			<div class="spinner"></div>
		</div>

		<div class="modal-body">
			<div class="permalink-fullscreen">
				<img class="image">
			</div>
			<div class="permalink-post-container container-fluid">
				<div class="permalink-left">
					<!-- CONTENT -->
				</div>

				<div class="toolbar-left">
					<a href="/media/ticket/download" class="btn btn-mini btn-download file"><i class="icon-cloud-download"></i> {{ Permalink_Download }}</a>
					<a class="btn btn-mini btn-fullscreen"><i class="icon-fullscreen"></i> {{ Permalink_Fullscreen }}</a>
				</div>

				<div class="permalink-right">
				<!-- PERMALINK-POST-RIGHT START -->
					<div class="right-container">
						<a class="permalink-close"></a>
						<div class="headers">
							<a href="/media/users/profil" class="user">
							<img class="avatar-frame avatar">
							<span class="author user"></span>
							</a>
							<br>
							<span class="date"></span>

							<div class="subheaders">
								<a class="btn btn-mini btn-action alerts" title="{{ Permalink_AlertMore }}"><i class="icon-flag"></i>{{ Permalink_Alert }}</a>
								<a class="btn btn-mini btn-action favorite" title="{{ Permalink_FavoriteMore }}"><i class="icon-heart"></i>{{ Permalink_Favorite }}</a>
								<a href="/media/ticket/view" class="btn btn-mini btn-action id" title="{{ Permalink_LinkMore }}"><i class="icon-globe"></i>{{ Permalink_Link }}</a>

								<a href="#&vote=up" class="id btn btn-mini postsvote vote-up" title="up"><i class="icon-thumbs-up-alt"></i></a>
								<a href="#&vote=down" class="id btn btn-mini postsvote vote-down" title="down"><i class="icon-thumbs-down-alt"></i></a>

								<div class="pull-right">
									<span class="score-block">{{ General_Score }} : <span class="score"></span></span>
								</div>
							</div>
							<hr>

							<div class="info">
								<p>{{ General_Title }} : <span class="title"></span></p>
								<p>{{ General_Description }} : <span class="description"></span></p>
							</div>
						</div>

						<div class="comment-frame">
							<textarea class="ckeditor" id="message"></textarea>
							<a class="btn btn-mini btn-action send"><i class="icon-comment"></i>{{ General_Send }}</a>

							<div class="comments"></div>
						</div>
					</div>
				<!-- PERMALINK-POST-RIGHT END -->
				</div>
			</div>
		</div>
	</div>
<!-- PERMALINK-POST END -->