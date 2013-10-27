/*******************************************************************************
 * API JS CELLFORM CONTROLLER										           *
 *******************************************************************************/

function Cellform() {
	// Initizalize cellform object
}

$.isDeferred = function(obj)
{
	return obj && $.isFunction(obj.always);
};

(function(base)
{
	$.ajaxSetup({
		global: true,
		cache: false,
		timeout: 30000,
		data:
		{
			ajax: 'true',
			csrf: translations.Csrf
		},
		beforeSend: function(xhr, settings)
		{
			if ('onLine' in navigator && !navigator.onLine)
			{
				alert('Internet is offline. Please check your network.');
				return false;
			}
		},
		error: function(xhr, stat, statusText)
		{
			'console' in window && console.error('ajaxError', stat, xhr);

			switch (stat)
			{
			case 'abort':
				if (xhr.state() == 'pending' && xhr.resolve)
				{
					xhr.resolve('aborted');
				}
				return;
			case 'success':
				break;
			case 'timeout':
				alert('Network timed out. Please check your network.');
				break;
			case 'parseerror' :
				alert('Some server error occurred. Please contact support');
				break;
			case 'error':
				switch (xhr.status) {
				case 401:
					xhr.resolve && xhr.resolve('unauthorized');
					return;
					break;
				case 403: // forbidden bad request
				case 404: // not found
				case 405: // bad method
					break;
				case 500: // internal server error
					alert('Server error occurred. Please wait for maintainance. Response : ' + xhr.responseText);
					break;
				case 503: // service unavailable
					alert('Server busy. Please wait a while...');
					break;
				default:
					alert('Server error occurred(' + xhr.status + '). Please wait for maintainance. Response : ' + xhr.responseText);
					break;
				}
				'console' in window && console.error('ajaxError', stat, xhr);
			}
		},
		success: function()
		{
		}
	});

	var default_options = {
		debug : false,
		api : '/',
		logging : false
	};

	base.prototype =
	{
		_log : function()
		{
			// ('console' in window) && console.log.apply(console, arguments);
		},
		_error : function(ex)
		{
			// ('console' in window) && console.error.apply(console, [ ex, ex.stack ]);
		},
		init : function(options)
		{
			var _self = this;
			_self.options = $.extend(default_options, options);

			if (_self.options.logging)
			{
				_self._log('Cellform : logging start');
			}
			else
			{
				_self._log = function()
				{
				};
				_self._error = function()
				{
				};
			}

			_self._api_d = $.Deferred(function(_d)
			{
				if (!Cellform.prototype.API)
				{
					$.getScript(_self.options.api).then(function()
					{
						_d.resolve();
					}).fail(function(xhr, stat, text)
					{
						_d.reject();
						alert('cannot load api js (' + stat + '): ' + _self.options.api);
					});
				}
				else
				{
					_d.resolve();
				}
			}).promise();
		},

		api : function(path, params)
		{
			var _self = this;
			if (_self.options.logging)
			{
				'console' in window && console.log('Cellform.api(): ' + path, 'params:', params);
			}

			var _d = $.Deferred();
			var api_deferred = null;
			_self._api_d.then(function()
			{
				try
				{
					params = $.extend({}, params);
					var api = _self.API[path];
					if (!api)
					{
						('console' in window) && console.error('invalid api path', path);
						alert('invalid api path:' + path);
						return;
					}
					if ($.isFunction(api))
					{
						api = api.call(_self, params, _self);
						if ($.isDeferred(api))
						{
							if (_self.options.logging)
							{
								'console' in window && console.log('Cellform.api():deferred : ', path, 'params:', params);
							}

							api_deferred = api;
							api.then(function(res)
							{
								if (_self.options.logging)
								{
									'console' in window && console.log('Cellform.api():resolved deferred', path, 'params:', params, 'res:', res);
								}

								_d.resolve(res);
							}).fail(_self, function()
							{
								if (_self.options.logging)
								{
									'console' in window && console.log('Cellform.api():failed deferred', path, 'params:', params, 'arguments:', arguments);
								}

								_d.reject.apply(_d, arguments);
								if (arguments[0].ajaxerror)
								{
									alert('HTTP Error ' + arguments[0].ajaxerror.status);
								}
							});
						}
						else
						{
							setTimeout(function()
							{
								if (_self.options.logging)
								{
									'console' in window && console.log('Cellform.api():resolved function', path, 'params:', params, 'res:', api);
								}

								_d.resolve(api);
							}, 100);
						}
					}
					else
					{
						setTimeout(function()
						{
							if (_self.options.logging)
							{
								'console' in window && console.log('Cellform.api():resolved static', path, 'params:', params, 'res:', api);
							}

							_d.resolve(api);
						}, 100);
					}
				}
				catch (ex)
				{
					_d.reject({
						'exception' : ex
					});
					alert(ex);
					throw ex;
				}

			}).fail(function()
			{
				'console' in window && console.log('Cellform.api():failed', path, params, arguments);
				_d.reject({
					'failed' : arguments
				});
			});
			_d.abort = function()
			{
				console.error('Cellform.api():aborted');
				if (api_deferred && $.isFunction(api_deferred.abort))
				{
					api_deferred.abort();
				}
				_d.resolve('aborted');
			};
			return _d;
		}
	};

})(Cellform);