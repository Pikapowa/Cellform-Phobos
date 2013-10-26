/*******************************************************************************
 * API JS CELLFORM													           *
 *******************************************************************************/


(function()
{
	var WEB_ROOT = '/';
	var PIC_ROOT = WEB_ROOT + 'media/img/';

	var LEVEL_USER  = 1,
		LEVEL_ADMIN = 2;

	var is_loading = false;

	var ckeditor_isloaded = false;

	var masonry_opt = {
		columnWidth: 300,
		itemSelector: '.container-posts',
		gutter: 20,
		isFitWidth: true,
		hiddenStyle: { opacity: 0 }, /* for IE < 9 */
		visibleStyle: { opacity: 1 } /* for IE < 9 */
	},
		$container = $('#posts-list'),
		page = 1,
		offset_gotop = 800;

	var $loading = $('.loading');
	var $content = $('#content');

	if (typeof CKEDITOR != 'undefined')
	{
		CKEDITOR.on('instanceReady', function()
		{
			ckeditor_isloaded = true;
		});
	}

   /*!
	* Return web root path
	*
	* \fn GetWebRoot()
	* \memberof Cellform
	* \return string WEB_ROOT
	*/
	Cellform.prototype.GetWebRoot = function()
	{
		return WEB_ROOT;
	}

   /*!
	* Return web root pictures path
	*
	* \fn GetPicRoot()
	* \memberof Cellform
	* \return string PIC_ROOT
	*/
	Cellform.prototype.GetPicRoot = function()
	{
		return PIC_ROOT;
	}

   /**
	*----------------------------------------------------------------
	*	UNTILS FUNCTIONS FOR GLOBAL USED
	*----------------------------------------------------------------
	*/

   /* PRIVATE METHOD */

   /*!
	* Return array of filtered parameters
	*
	* \fn CleanParams()
	* \param string params
	* \return array
	*/
	function CleanParams(params)
	{
		var ret = {};
		for (k in params)
		{
			if (params.hasOwnProperty(k) && (params[k] !== undefined && params[k] !== null))
			{
				ret[k] = params[k];
			}
		}
		return ret;
	}

	/*!
	* Micro template engine (not used in this code version)
	* Usage : #{var}
	*
	* \fn Template()
	* \param string template
	* \param array values
	* \return string
	*/
	function Template(template, values)
	{
		var replace = function(str, match)
		{
			return typeof values[match] === "string" || typeof values[match] === 'number' ? values[match] : str;
		};

		return template.replace(/#\{([^{}]*)}/g, replace);
	};

   /* PUBLIC METHOD */

   /*!
	* Return true if client-mobile
	*
	* \fn IsMobile()
	* \memberof Cellform
	* \return bool
	*/
	Cellform.prototype.IsMobile = function()
	{
		return ($.browser.mobile || $(document).width() < 767) ? 1 : 0;
	}

   /*!
	* Delete selected <tr> line.
	*
	* \fn DelNodes()
	* \memberof Cellform
	* \return Array object
	*/
	Cellform.prototype.DelNodes = function()
	{
		var nodes = new Array();
		$("input:checked").each(function ()
		{
			nodes.push($(this).val());
			$('tr[id^="nodes_' + $(this).val() + '"]').remove();
		});

		return nodes;
	}

   /*!
	* Return form values as JSON format
	*
	* \fn PrepareForm()
	* \memberof Cellform
	* \param string form
	* \return object
	*/
	Cellform.prototype.PrepareForm = function(form)
	{
		var array = $(form).serializeArray();
		var json = {};

		$.each(array, function()
		{
			json[this.name] = this.value || '';
		});

		return json;
	}

	/*!
	* When scrolled the go-top buttons is displayed [MASONRY]
	* It's a part of masonry service
	*
	* \fn GoTop()
	* \memberof Cellform
	*/
	Cellform.prototype.GoTop = function()
	{
		$(window).scroll(function()
		{
			if($(this).scrollTop() > offset_gotop)
			{
				$('#go-top').show();	
			}
			else
			{
				$('#go-top').hide();
			}
		});
	}

	/*!
	* Set loading as true or false (set is_loading with boolean cast)
	*
	* \fn SetLoading(loading_state)
	* \memberof Cellform
	* \param bool loading_state
	*/
	Cellform.prototype.SetLoading = function(loading_state)
	{
		is_loading = Boolean(loading_state);

		if (is_loading)
		{
			$loading.show();
		}
		else
		{
			$loading.hide();
		}
	}

	/*!
	* When scrolled all the way to the bottom, add more posts. [MASONRY]
	*
	* \fn OnScroll()
	* \memberof Cellform
	* \param string event
	*/
	Cellform.prototype.OnScroll = function(event)
	{
		if (!is_loading)
		{
			var area = ($(window).scrollTop() + $(window).height() > $(document).height() - 100);
			if (area)
			{
				Cellform.prototype.LoadPosts();
			}
		}
	};

	/*!
	* Loads post-list from the API. [MASONRY]
	*
	* \fn LoadPosts()
	* \memberof Cellform
	*/
	Cellform.prototype.LoadPosts = function()
	{
		Cellform.prototype.SetLoading(true);

		$.ajax({
			url: masonry_url,
			dataType: 'json',
			data: {page: page},
			success: Cellform.prototype.OnLoadPosts
		}).fail(function()
		{
			Cellform.prototype.SetLoading(false);
		});
	};

	/*!
	* Receives data from the API, creates HTML for ticket and updates the layout [MASONRY]
	*
	* \fn OnLoadPosts()
	* \memberof Cellform
	* \param array data
	*/
	Cellform.prototype.OnLoadPosts = function(data)
	{
		// Increment page.
		page++;

		var html = '',
			icon,
			image,
			toolbar,
			vote_up,
			vote_down;

		var i = 0,
			length = data.length,
			post;

		for (; i<length; i++)
		{
			post = data[i];

			icon 		= '';
			image 		= '';
			toolbar		= '';
			vote_up		= '';
			vote_down	= '';

			switch(post.type)
			{
				case 'image':
					image += '<img src="' + PIC_ROOT + 'min/' + post.image + '" width="' + masonry_opt.columnWidth + '">';
					break;

				case 'video':
					icon  += '<div class="play"></div>';
					image += '<img src="' + post.image + '" width="' + masonry_opt.columnWidth + '">';
					break;

				case 'link':
					icon += '<i class="icon-link icon-3x"></i>';
					break;
			}

			if (post.favoris)
			{
				toolbar += '<a href="#?" onclick="cell.DelFavorites(' + post.id + ');"><i class="icon-heart-empty icon-2x favoris"></i></a>';
			}

			if (post.user == translations.Username)
			{
				toolbar += '<a href="/media/ticket/write&mode=change&type=' + post.type + '&id=' + post.id + '"><i class="icon-edit icon-2x change"></i></a>';
			}

			if (translations.Level == LEVEL_ADMIN)
			{
				toolbar += '<a href="#?" onclick="cell.DelTickets(' + post.id + ');"><i class="icon-ban-circle icon-2x delete"></i></a>';
			}

			if (post.vote == 'up')
			{
				vote_up = 'voted';
			}

			if (post.vote == 'down')
			{
				vote_down = 'voted';
			}

			// Create HTML postlist (cellframe).
			html += '<div class="container-posts clearfix" id="' + post.id + '">';
			html += '<div class="cellframe-posts">';
			html += '<span class="title">'+ post.title +'</span>';
			html += '<a href="#&id=' + post.id + '" rel="' + post.id + '" class="poplight">';
			html += '<div class="wrapper">';
			html += icon;
			html += image;
			html += '</a>';
			html += '</div>';

			html += '<br>';

			html += '<div class="minitoolbar">';
			html += toolbar;
			html += '</div>';

			html += '<div class="info">';
			html += '<span>' + translations.PostedAt + ' : ' +
					'<span>' + post.date + '</span><br>' +
					'<span>' + translations.NbComments + ' : ' +
					'<span class="nbcomments">' + post.nbcomments + '</span>' +
					'</div>' +
					'<span class="score">' + translations.Score + ' : </span>' +
					'<span class="score val">' + post.score + '</span>';
			html += '</div>'; // <!-- cellframe-posts -->

			html += '<div class="cellframe-posts footer">';
			html += '<a href="/media/users/profil&user=' + post.user + '" title="' + post.user + '">' +
					'<img class="avatar-frame mini" src="/media/avatars/' + post.avatar + '" alt="' + post.user + '">' +
					'<span class="user">' + post.user + '</span>' +
					'</a>' +
					'<div class="pull-right">' +
					'<a href="#&vote=up?id=' + post.id + '" class="btn btn-mini postsvote vote-up ' + vote_up + '" title="up"><i class="icon-thumbs-up-alt"></i></a>' +
					'<a href="#&vote=down?id=' + post.id + '" class="btn btn-mini postsvote vote-down ' + vote_down + '" title="down"><i class="icon-thumbs-down-alt"></i></a>' +
					'</div>';

			html += '</div>'; // <!-- footer -->
			html += '</div>'; // <!-- container-posts -->
		}

		var $html = $('<div/>').html(html).contents();

		// Add HTML template to the page & masonry.
		$container.append($html);
		$html.hide();

		// Apply layout.
		$container.imagesLoaded(function()
		{
			Cellform.prototype.SetLoading(false);

			$html.show();
			$container.masonry('appended', $html);
		});
	};

	/* START SERVICES */
   /*!
	* Masonry Initializer [SERVICE] 
	*
	* \fn Service_Masonry()
	* \memberof Cellform
	*/
	Cellform.prototype.Service_Masonry = function()
	{
	 	$container.masonry(masonry_opt);

		$(window).bind('scroll', this.OnScroll);

		Cellform.prototype.LoadPosts();

		// Active Gotop button
		Cellform.prototype.GoTop();
	}

   /*!
	* Permalink-post popup calling [SERVICE]
	*
	* \fn Service_Permalink()
	* \memberof Cellform
	*/
	Cellform.prototype.Service_Permalink = function()
	{
		var _self = this;

		var data = {},
			params = {},
			scroll_y,
			post_id;

		// INITIALIZE SELECTOR
		var $permalink_post 	= $('#permalink-post'),
			$permalink_body		= $('.modal-body'),
			$permalink_left 	= $('.permalink-left'),
			$permalink_right 	= $('.permalink-right'),
			$permalink_coms		= $('.comments'),
			$messagebox			= $('#message');

		// PERMALINK-POST OPEN-POPUP
		$(document).on('click', 'a.poplight[href^=#]', function()
		{
			// INITIALIZE PERMALINK FOR LOADING
			_self.SetLoading(true);

			$permalink_post.show();
			$permalink_body.hide();
			$permalink_left.empty();
			$permalink_coms.empty();

			$('.permalink-right .vote-up, .permalink-right .vote-down').removeClass('voted');

			scroll_y 	= $(document).scrollTop();
			post_id 	= $(this).attr('rel');
			params 		= {
				'id' : post_id,
			};

			// LOAD JSON DATA FROM POSTINFO
			_self.api('postinfo', params).then(function(res)
			{
				data = res.post[0];
				data.avatar = '/media/avatars/' + data.avatar;

				if (data.type == 'image')
				{
					data.file = data.image;
					data.image = _self.GetPicRoot() + data.image;

					$permalink_left.append('<img src="' + data.image + '">');
					$('.toolbar-left').show();
				}

				if (data.type == 'video')
				{
					if (_self.IsMobile())
					{
						var media = data.phone;
						$permalink_left.append('<iframe width=100% height=100% src="' + media + '" frameborder="0" allowfullscreen></iframe>');
					}
					else
					{
						var media = data.media;
						$permalink_left.append('<object width="100%" height="100%"><param name="movie" value="' + media + '"></param><param name="allowFullScreen" value="true"></param><param name="wmode" value="opaque"></param><embed src="' + media + '" type="application/x-shockwave-flash" wmode="opaque" allowfullscreen="true" width="100%" height="100%"></embed></object>');
					}
				}

				if (data.type == 'link' && !_self.IsMobile())
				{
					var media = data.media;

					$permalink_left.append('<img style="display:none">'); // for loadjson issues
					$permalink_left.append('<iframe width=100% height=100% src="' + media + '" frameborder="0"></iframe>');
				}

				$permalink_post.loadJSON(data); // INJECT JSON

				// VOTE-BLOCK
				if (data.vote == 'up')
				{
					$('.permalink-right .vote-up').addClass('voted');
				}
				if (data.vote == 'down')
				{
					$('.permalink-right .vote-down').addClass('voted');
				}

				// LOAD COMMENTS
				_self.api('getcomments', params).then(function(res)
				{
					_self.InsertComments(res.data, $permalink_coms, 'append');

					// PRINT MODAL-BODY & DISABLE LOADER WHEN IMAGE IS LOADED
					$permalink_right.scrollTop(0);

				    $permalink_body.imagesLoaded(function()
				    {
				    	_self.SetLoading(false);
				    	$permalink_body.show();

						if (!_self.IsMobile())
						{
							$permalink_right.scrollTop(10);
						}
					});
				});
			});

			/**
			 * START EVENTS
			 */

			// WAIT FOR FAVORITE ADDING
			$('.subheaders .favorite').unbind('click').bind('click', function()
			{
				_self.api('addfavorites', params).then(function(res)
				{
					if (_self.StreamErrno(res))
					{
						$('#' + post_id + ' .minitoolbar').prepend('<a href="#?" onclick="cell.DelFavorites(' + post_id + ');"><i class="icon-heart-empty icon-2x favoris"></i></a>');
					}
				});
			});

			// WAIT FOR ALERT SENDING
			$('.subheaders .alerts').unbind('click').bind('click', function()
			{
				_self.api('addalert', params).then(function(res)
				{
					_self.StreamErrno(res);
				});
			});

			// WAIT FOR COMMENT SEND
			$('.comment-frame .send').unbind('click').bind('click', function()
			{
				if (ckeditor_isloaded)
				{
					var message = CKEDITOR.instances.message.getData();
				}
				else
				{
					var message = $messagebox.val();
				}

				params = {
					'id' : post_id,
					'message' : message,
				};

				_self.api('addcomment', params).then(function(res)
				{
					if (_self.StreamErrno(res))
					{
						if (ckeditor_isloaded)
						{
							CKEDITOR.instances.message.setData('');
						}
						else
						{
							$messagebox.text('');
						}

						_self.InsertComments(res.data, $permalink_coms, 'prepend');

						$nbcomments = $('#' + post_id + ' .nbcomments');
						nbcomments  = parseInt($nbcomments.text(), 10) + 1;

						$nbcomments.text(nbcomments);
					}
				});
			});

			// PERMALINK-POST FULLSCREEN
			$('.btn-fullscreen').on('click', function()
			{
				$('.permalink-post-container').hide();
				$('.permalink-fullscreen').show();
			});

			$('.permalink-fullscreen').on('click', function()
			{
				$('.permalink-post-container').show();
				$('.permalink-fullscreen').hide();
			});

			/**
			 * END EVENTS
			 */

			// BLOCK INFINITE-SCROLL WHILE PERMALINK IS OPENNED
			$(window).unbind('scroll', Cellform.prototype.OnScroll);

			if (_self.IsMobile())
			{
				window.scrollTo(0,0);
				$content.hide();
			}
			else
			{
				$('#overlay').show();
			}

			// PERMALINK-POST CLOSE
			$('.permalink-close, #overlay').on('click', function()
			{
				$('#permalink-post, .toolbar-left, #overlay').hide(0, function()
				{
					$content.show();
					$container.masonry();
					window.scrollTo(0, scroll_y);
				});

				// RELAUNCH INFINITE SCROLL
				$(window).bind('scroll', Cellform.prototype.OnScroll);

				return false;
			});

			return false;
		});
	}

   /*!
	* Add a vote on a post & inject new data in the DOM [SERVICE]
	*
	* \fn Service_PostVote()
	* \memberof Cellform
	*/
	Cellform.prototype.Service_PostsVote = function()
	{
		var _self = this;

		$(document).on('click', 'a.postsvote[href^=#]', function()
		{
			var popURL	= $(this).attr('href'),
				query	= popURL.split('&'),
				dim 	= query[1].split('?'),
				vote	= dim[0].split('=')[1],
				id		= dim[1].split('=')[1];

			var params = {
				'id' : id,
				'vote' : vote,
			};

			_self.api('postsvote', params).then(function(res)
			{
				if (res.vote != 'fail')
				{
					var score = $('#' + id + ' .score.val').text();
					score = parseInt(score, 10);
					score += res.score;

					$('#' + id + ' .score.val, .permalink-right .score').text(score);

					if (res.vote == 'up')
					{
						$('#' + id + ' .vote-up, .subheaders .vote-up').addClass('voted');
						$('#' + id + ' .vote-down, .subheaders .vote-down').removeClass('voted');
					}
					else
					{
						$('#' + id + ' .vote-down, .subheaders .vote-down').addClass('voted');
						$('#' + id + ' .vote-up, .subheaders .vote-up').removeClass('voted');
					}
				}
			});

			return false;
		});
	}

   /*!
	* Add a vote on a comment & inject new data in the DOM [SERVICE]
	*
	* \fn Service_ComsVote()
	* \memberof Cellform
	*/
	Cellform.prototype.Service_ComsVote = function()
	{
		var _self = this;

		$(document).on('click', 'a.comsvote[href^=#]', function()
		{
			var popURL	= $(this).attr('href'),
				query	= popURL.split('&'),
				dim 	= query[1].split('?'),
				vote	= dim[0].split('=')[1],
				id		= dim[1].split('=')[1];

			var params = {
				'id' : id,
				'vote' : vote,
			};

			_self.api('comsvote', params).then(function(res)
			{
				if (res.vote != 'fail')
				{
					var score = $('#com_' + id + ' .com-score').text();
					score = parseInt(score, 10);
					score += res.score;

					$('#com_' + id + ' .com-score').text(score);

					if (res.vote == 'up')
					{
						$('#com_' + id + ' .vote-up').addClass('voted');
						$('#com_' + id + ' .vote-down').removeClass('voted');
					}
					else
					{
						$('#com_' + id + ' .vote-down').addClass('voted');
						$('#com_' + id + ' .vote-up').removeClass('voted');
					}
				}
			});

			return false;
		});
	}
	/* END SERVICES */

   /*!
	* Insert comment(s) in the DOM
	*
	* \fn InsertComment(data, $div, mode)
	* \memberof Cellform
	* \param array data
	* \param object div
	* \param string mode
	*/
	Cellform.prototype.InsertComments = function(data, $div, mode)
	{
		var comments = '',
			vote_up,
			vote_down,
			icon;

		$.each(data, function(key, value)
		{
			vote_up 	= '';
			vote_down 	= '';
			icon 		= '';

			if (value.vote == 'up')
			{
				vote_up = 'voted';
			}

			if (value.vote == 'down')
			{
				vote_down = 'voted';
			}

			if (translations.Level == LEVEL_ADMIN)
			{
				icon = '<a class="com-del" href="#" onclick="cell.DelComment(' + value.id + ');"><span>[×]</span></a>';
			}

			comments += '<div id="com_' + value.id + '">' +
				'<img class="avatar-frame mini" src="/media/avatars/' + value.avatar + '">' +
				'<a class="user" href="/media/users/profil&user=' + value.user + '"><span>' + value.user + '</span></a>' +
				'<span>' + translations.PostedAt + ' ' + value.date + '</span>' +
				'<span><a href="#&vote=up?id=' + value.id + '" class="btn btn-mini comsvote vote-up ' + vote_up + '" title="up"><i class="icon-thumbs-up-alt"></i></a>' +
				'<a href="#&vote=down?id=' + value.id + '" class="btn btn-mini comsvote vote-down ' + vote_down + '" title="down"><i class="icon-thumbs-down-alt"></i></a></span>' +
				'<span class="com-score">' + value.score + '</span>+' +
				icon +
				'<div class="message">' + value.msg + '</div>' +
				'</div>' +
				'<hr>';
		});

		if (mode == 'prepend')
		{
			$div.prepend(comments);
		}
		else
		{
			$div.append(comments);
		}
	}

   /*!
	* Add a friend to user.
	*
	* \fn AddFriend()
	* \memberof Cellform
	* \param string user
	*/
	Cellform.prototype.AddFriend = function(user)
	{
		var _self = this;
		var params = {
			'user' : user,
		};

		_self.api('addfriend', params).then(function(res)
		{
			if (_self.StreamErrno(res))
			{
				$('#addfriend').remove();
			}
		});
	}

   /*!
	* Delete a friend
	*
	* \fn DelFriend()
	* \memberof Cellform
	*/
	Cellform.prototype.DelFriend = function()
	{
		var _self = this,
			nodes = new Array();

		nodes = _self.DelNodes();

		var params = {
			'nodes' : nodes,
		};

		_self.api('delfriend', params).then(function(res)
		{
			_self.StreamErrno(res);
		});
	}

   /*!
	* Delete a specific private message
	*
	* \fn DelMp()
	* \memberof Cellform
	*/
	Cellform.prototype.DelMp = function()
	{
		var _self = this,
		nodes = new Array();

		nodes = _self.DelNodes();

		var params = {
			'nodes' : nodes,
		};

		_self.api('delmp', params).then(function(res)
		{
			_self.StreamErrno(res);
		});
	}

   /*!
	* Delete a ticket in favorites list
	*
	* \fn DelFavorites()
	* \memberof Cellform
	*/
	Cellform.prototype.DelFavorites = function(id)
	{
		var _self = this;
		var params = {
			'id' : id,
		};

		_self.DialogBox(translations.Delete + ' ?', {confirm: true}, function()
		{
			_self.api('delfavorites', params).then(function(res)
			{
				if (res == 'success')
				{
					$('#' + id + ' .minitoolbar .favoris').remove();
				}
			});
		});
	}

   /*!
	* Retrieves the last 10 people who commented on your ticket & inject html data
	*
	* \fn GetNotifsComments()
	* \memberof Cellform
	*/
	Cellform.prototype.GetNotifsComments = function()
	{
		var _self = this;
		var html = '';

		_self.api('getnotifs_comments').then(function(res)
		{
			$('#notifs-comments-list').html(html);

			$.each(res, function(key, value)
			{
				html += '<li>';
				html += '<a href="/media/ticket/view&id=' + value.id + '">';
				html += '<span class="msg-body">';
				html += '<span class="msg-title">';
				html += '<span class="red">' + value.user + '</span> ' + translations.Commented + ' <span class="red">[' + value.title + ']</span>';
				html += '</span>';
				html += '<span class="msg-time">';
				html += '<i class="icon-time"></i>';
				html += '<span>' + value.date + '</span>';
				html += '</span>';
				html += '</span>';
				html += '</a>';
				html += '</li>';
				html += '<li class="divider"></li>';
			});

			html += '<li>';
			html += '<a href="/media/notifications/all&user=' + translations.Username + '&top=20&type=coms">';
			html += translations.AllComments;
			html += '</a>';
			html += '</li>';

			$('#notifs-comments-list').html(html);
		});
	}

   /*!
	*  Retrieves the last 10 people who voted on your ticket & inject html data
	*
	* \fn GetNotifsVotes()
	* \memberof Cellform
	*/
	Cellform.prototype.GetNotifsVotes = function()
	{
		var _self = this;
		var html = '';

		_self.api('getnotifs_votes').then(function(res)
		{
			$('#notifs-votes-list').html(html);

			$.each(res, function(key, value)
			{
				html += '<li>';
				html += '<a href="/media/ticket/view&id=' + value.id + '">';
				html += '<span class="msg-body">';
				html += '<span class="msg-title">';
				html +=  '<span class="red">' + value.uservoted + '</span> ' + translations.Voted + ' <i class="icon-thumbs-' + value.vote + '-alt"></i><span class="red"> [' + value.title + ']</span>';
				html += '</span>';
				html += '</span>';
				html += '</a>';
				html += '</li>';
				html += '<li class="divider"></li>';
			});

			html += '<li>';
			html += '<a href="/media/notifications/all&user=' + translations.Username + '&top=20&type=votes">';
			html += translations.AllVotes;
			html += '</a>';
			html += '</li>';

			$('#notifs-votes-list').html(html);
		});
	}

   /*!
	* Print errors or notification in the dialog box.
	* First argument required received data from ajax request 
	*
	* \fn StreamErrno(data)
	* \memberof Cellform
	* \param array data
	* \return bool
	*/
	Cellform.prototype.StreamErrno = function(data)
	{
		var _self = this;
		var listerrors = '';

		if (data.notifs)
		{
			_self.DialogBox('<font class="notifications">' + data.notifs + '</font>');
		}

		if (data.errors)
		{
			$.each(data.errors, function()
			{
				listerrors += '<li>' + this + '</br>';
			});

			_self.DialogBox('<font class="errors">' + listerrors + '</font>');

			return false;
		}
		else
		{
			return true;
		}
	}

   /*!
	* This function call a dialog box
	* First argument is the string to print
	* Second argument is an configuration array
	* Third argument is an callback function
	*
	* \fn DialogBox(string, args, callback)
	* \memberof Cellform
	* \param string string
	* \param array args
	* \param function callback
	*/
	Cellform.prototype.DialogBox = function(string, args, callback)
	{
		var default_args =
		{
			'confirm' : false,
			'verify' : false,
			'input' : false,
			'animate' :	false,
			'textOk' : 'Ok',
			'textCancel' : 'Cancel',
			'textYes' : 'Yes',
			'textNo' : 'No',
		}

		if (args)
		{
			for(var index in default_args) 
			{
				if (typeof args[index] == 'undefined')
				{
					args[index] = default_args[index];
				}
			}
		}

		var aHeight = $(document).height();
		var aWidth = $(document).width();

		$('body').append('<div class="DialogOverlay" id="aOverlay"></div>');
		$('.DialogOverlay').css('height', aHeight).css('width', aWidth).fadeIn(100);
		$('body').append('<div class="DialogOuter"></div>');
		$('.DialogOuter').append('<div class="DialogInner"></div>');
		$('.DialogInner').append(string);
		$('.DialogOuter').css("left", ( $(window).width() - $('.DialogOuter').width() ) / 2+$(window).scrollLeft() + "px");

		if (args)
		{
			if (args['animate'])
			{
				var aniSpeed = args['animate'];
				if (isNaN(aniSpeed))
				{
					aniSpeed = 400;
				}
				$('.DialogOuter').css('top', '-200px').show().animate({top:"100px"}, aniSpeed);
			}
			else
			{
				$('.DialogOuter').css('top', '100px').fadeIn(200);
			}
		}
		else
		{
			$('.DialogOuter').css('top', '100px').fadeIn(200);
		}
	    
		if (args)
	   	{
			if (args['input'])
	   		{
				if (typeof(args['input']) == 'string')
				{
					$('.DialogInner').append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" value="' + args['input'] + '" /></div>');
				}
				else
	    		{
					$('.DialogInner').append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" /></div>');
				}

				$('.aTextbox').focus();
			}
		}

		$('.DialogInner').append('<div class="aButtons"></div>');

		if (args)
	   	{
			if (args['confirm'] || args['input'])
			{ 
				$('.aButtons').append('<button value="ok">' + args['textOk'] + '</button>');
				$('.aButtons').append('<button value="cancel">' + args['textCancel'] + '</button>'); 
			}
			else if(args['verify'])
			{
				$('.aButtons').append('<button value="ok">' + args['textYes'] + '</button>');
				$('.aButtons').append('<button value="cancel">' + args['textNo'] + '</button>');
			}
			else
			{
				$('.aButtons').append('<button value="ok">' + args['textOk'] + '</button>');
			}
		}
		else
    	{
			$('.aButtons').append('<button value="ok">Ok</button>');
		}
		
		$(document).keydown(function(e) 
		{
			if ($('.DialogOverlay').is(':visible'))
			{
				if (e.keyCode == 13) 
				{
					$('.aButtons > button[value="ok"]').click();
				}
				if (e.keyCode == 27) 
				{
					$('.aButtons > button[value="cancel"]').click();
				}
			}
		});

		var aText = $('.aTextbox').val();

		if (!aText)
		{
			aText = false;
		}

		$('.aTextbox').keyup(function()
	    {
			aText = $(this).val();
		});

	    $('.aButtons > button, .DialogOverlay').click(function()
	    {
	    	$('.DialogOverlay').remove();
			$('.DialogOuter').remove();
	    	if (callback)
	    	{
				var wButton = $(this).attr('value');

				if (wButton == 'ok')
				{
					if (args)
					{
						if (args['input'])
						{
							callback(aText);
						}
						else
						{
							callback(true);
						}
					}
					else
					{
						callback(true);
					}
				}
				else if (wButton == 'cancel')
				{
					//callback(false);
				}
			}
		});
	}

	/*	ADMIN FUNCTIONS	*/

	/*!
	* Delete alerts. It's a admin privilege.
	*
	* \fn DelAlerts()
	* \memberof Cellform
	*/
	Cellform.prototype.DelAlerts = function()
	{
		var _self = this,
		nodes = new Array();

		_self.DialogBox(translations.Delete + ' ?', {confirm: true}, function()
		{
			nodes = _self.DelNodes();

			var params = {
				'nodes' : nodes,
			};

			_self.api('delalerts', params);
		});
	}

	/*!
	* Delete tickets. It's a admin privilege.
	*
	* \fn DelAlerts()
	* \memberof Cellform
	*/
	Cellform.prototype.DelTickets = function(id)
	{
		var _self = this,
		nodes = new Array();

		_self.DialogBox(translations.Delete + ' ?', {confirm: true}, function()
		{
			if (typeof(id) == 'undefined')
			{
				nodes = _self.DelNodes();
			}
			else
			{
				nodes[0] = id;
				$('#' + id).remove();
			}

			var params = {
				'nodes' : nodes,
			};

			_self.api('delalerts', params);
			_self.api('deltickets', params);
		});
	}

	/*!
	* Delete an specific comment. It's a admin privilege.
	*
	* \fn DelComment()
	* \memberof Cellform
	*/
	Cellform.prototype.DelComment = function(id)
	{
		var _self = this,
		nodes = new Array();

		_self.DialogBox(translations.Delete + ' ?', {confirm: true}, function()
		{
			var params = {
				'id' : id,
			};

			_self.api('delcomment', params).then(function(res)
			{
				if (res == 'success')
				{
					$('#com_' + id).remove();
				}
			});
		});
	}

	/**
	*----------------------------------------------------------------
	*	FUNCTIONS FOR ALL CELLFORM COMPONENTS
	*----------------------------------------------------------------
	*/
   /*!
	* Literal object API
	*
	* \memberof Cellform
	*/
	Cellform.prototype.API =
	{
	   /* LOGINBOX */
	   /*!
		* SIGN-IN
		*
		* \fn login()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'login' : function(params)
		{
			return $.ajax({
				url: WEB_ROOT + 'home/signin',
				type: 'POST',
				data: CleanParams({
					login: params.login,
					password: params.password
				}),
				dataType : 'json',
			});
		},
	   /*!
		* SIGN-UP
		*
		* \fn signup()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'signup' : function(_self)
		{
			return $.ajax({
				url: WEB_ROOT + 'home/signup',
				type: 'POST',
				data: CleanParams({
					email: params.email,
					password: params.password,
					password_confirm: params.password_confirm,
					username: params.username,
					sex: params.sex,
					captcha: params.captcha
				}),
				dataType: 'json',
			});
		},
	   /*!
		* REQUEST-RECOVERY
		*
		* \fn recovery()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'recovery' : function(_self)
		{
			return $.ajax({
				url : WEB_ROOT + 'home/requestrecovery',
				type: 'POST',
				data : CleanParams({
					email: params.email
				}),
				dataType : 'json',
			});
		},
	   /*!
		* CAPTCHA
		*
		* \fn captcha()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'captcha' : function(_self)
		{
			return $.ajax({
				url : WEB_ROOT + 'captcha/defaults/captchajson',
				type: 'GET',
				dataType : 'json',
			});
		},
		/* MEDIA */
	   /*!
		* POSTINFO
		*
		* \fn postinfo()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'postinfo' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'media/ticket/postinfo',
				type: 'POST',
				data: CleanParams({
					id: params.id,
				}),
				dataType : 'json',
			});
		},
	   /*!
		* GET-COMMENTS
		*
		* \fn getcomments()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'getcomments' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'media/comment/getcomments',
				type: 'POST',
				data: CleanParams({
					id: params.id,
				}),
				dataType : 'json',
			});
		},
	   /*!
		* ADD-COMMENT
		*
		* \fn addcomment()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'addcomment' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'media/comment/addcomment',
				type: 'POST',
				data: CleanParams({
					id: params.id,
					message: params.message,
				}),
				dataType : 'json',
			});
		},
	   /*!
		* ADD-VOTE [POSTS]
		*
		* \fn postsvote()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'postsvote' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'media/vote/postsvote',
				type: 'GET',
				data: CleanParams({
					id: params.id,
					vote: params.vote,
				}),
				dataType : 'json',
			});
		},
	   /*!
		* ADD-VOTE [COMS]
		*
		* \fn comsvote()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'comsvote' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'media/vote/comsvote',
				type: 'GET',
				data: CleanParams({
					id: params.id,
					vote: params.vote,
				}),
				dataType : 'json',
			});
		},
	   /*!
		* AUTOCOMPLETE USER LIST
		*
		* \fn userlist()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'userlist' : function()
		{
			return $.ajax({
				url : WEB_ROOT + 'media/users/userlist',
				type: 'GET',
				dataType : 'json',
			});
		},
	   /*!
		* AUTOCOMPLETE POST LIST
		*
		* \fn postlist()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'postlist' : function()
		{
			return $.ajax({
				url : WEB_ROOT + 'media/ticket/postlist',
				type: 'GET',
				dataType : 'json',
			});
		},
	   /*!
		* ADD USER TO FRIENDS LIST 
		*
		* \fn addfriend()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'addfriend' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'media/friends/add',
				type: 'GET',
				data: CleanParams({
					user: params.user,
				}),
				dataType : 'json',
			});
		},
	   /*!
		* DEL USER TO FRIENDS LIST 
		*
		* \fn delfriend()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'delfriend' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'media/friends/del',
				type: 'POST',
				data: CleanParams({
					nodes: params.nodes,
				}),
				dataType : 'json',
			});
		},
	   /*!
		* DEL MP TO INBOX
		*
		* \fn delmp()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'delmp' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'media/mp/del',
				type: 'POST',
				data: CleanParams({
					nodes: params.nodes,
				}),
				dataType : 'json',
			});
		},
	   /*!
		* ADD POST IN FAVORITE LIST
		*
		* \fn addfavorites()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'addfavorites' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'media/favorites/add',
				type: 'POST',
				data: CleanParams({
					id: params.id,
				}),
				dataType : 'json',
			});
		},
	   /*!
		* DELETE POST IN FAVORITE LIST
		*
		* \fn delfavorites()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'delfavorites' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'media/favorites/del',
				type: 'POST',
				data: CleanParams({
					id: params.id,
				}),
				dataType : 'json',
			});
		},
	   /*!
		* GET 10 LAST COMMENTS NOTIFICATIONS
		*
		* \fn getnotifs_comments()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'getnotifs_comments' : function()
		{
			return $.ajax({
				url : WEB_ROOT + 'media/notifications/overviewcomments',
				type: 'GET',
				dataType : 'json',
			});
		},
	   /*!
		* GET 10 LAST VOTES NOTIFICATIONS
		*
		* \fn getnotifs_votes()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'getnotifs_votes' : function()
		{
			return $.ajax({
				url : WEB_ROOT + 'media/notifications/overviewvotes',
				type: 'GET',
				dataType : 'json',
			});
		},
	   /*!
		* ADD ALERT
		*
		* \fn addalert()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'addalert' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'media/ticket/alert',
				type: 'POST',
				data: CleanParams({
					id: params.id,
				}),
				dataType : 'json',
			});
		},
		/* ADMIN */
	   /*!
		* DEL ALERTS
		*
		* \fn delalerts()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'delalerts' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'admin/defaults/delalerts',
				type: 'POST',
				data: CleanParams({
					nodes: params.nodes,
				}),
				dataType : 'json',
			});
		},
	   /*!
		* DEL TICKETS
		*
		* \fn deltickets()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'deltickets' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'admin/defaults/deltickets',
				type: 'POST',
				data: CleanParams({
					nodes: params.nodes,
				}),
				dataType : 'json',
			});
		},
	   /*!
		* DEL COMMENT
		*
		* \fn delcomment()
		* \memberof Cellform.API
		* \return jqXHR
		*/
		'delcomment' : function(params)
		{
			return $.ajax({
				url : WEB_ROOT + 'admin/defaults/delcomment',
				type: 'POST',
				data: CleanParams({
					id: params.id,
				}),
				dataType : 'json',
			});
		},
	};

})();
