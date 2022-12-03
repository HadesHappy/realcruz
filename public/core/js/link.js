class Link {
    constructor(options) {
        var _this = this;
        
        // options
        if (typeof(options) !== 'undefined') {
            _this.options = options;
        }

        // method
        if (typeof(_this.options.method) == 'undefined') {
            _this.options.method = 'GET';
        }

        // method
        if (typeof(_this.options.type) == 'undefined') {
            _this.options.type = 'ajax';
        }

        // data
        if (typeof(_this.options.data) == 'undefined') {
            _this.options.data = {};
        }

        //
        _this.run();
    }

    goLink() {
        var _this = this;
        
        if (typeof(_this.options.before) !== 'undefined') {
            _this.options.before();
        }

        if (_this.options.type == 'ajax') {
            $.ajax({
                url: _this.options.url,
                method: _this.options.method,
                data: _this.options.data,
                globalError: false
            }).done(function(response) {
                if (typeof(_this.options.done) !== 'undefined') {
                    _this.options.done(response);
                }
            }).fail(function(response){
                if (typeof(_this.options.fail) !== 'undefined') {
                    _this.options.fail(response);
                } else {
                    new Dialog('alert', {
                        type: 'error',
                        title: LANG_ERROR,
                        message: JSON.parse(response.responseText).message
                    });
                }
            });
        } else if (_this.options.type == 'link') {
            if (typeof(_this.options.method) == 'undefined' || _this.options.method.trim().toLowerCase() == 'get') {
                window.location = _this.options.url;
            } else {
                var newForm = jQuery('<form>', {
                    'action': _this.options.url,
                    'method': 'POST'
                });
                newForm.append(jQuery('<input>', {
                    'name': '_token',
                    'value': CSRF_TOKEN,
                    'type': 'hidden'
                }));
                newForm.append(jQuery('<input>', {
                    'name': '_method',
                    'value': _this.options.method,
                    'type': 'hidden'
                }));
                $(document.body).append(newForm);
                newForm.submit();
            }
        }
    }

    run() {
        var _this = this;
        
        if (typeof(_this.options.confirm) !== 'undefined') {
            _this.dialog = new Dialog('confirm', {
                message: _this.options.confirm,
                ok: function() {
                    _this.goLink();
                }
            })
        } else if (typeof(_this.options.confirmUrl) !== 'undefined') {
                // load confirm message
				$.ajax({
					url: _this.options.confirmUrl,
					type: 'GET',
                    data: _this.options.data
				}).done(function(response) {
                    _this.dialog = new Dialog('confirm', {
                        message: response,
                        ok: function() {
                            _this.goLink();
                        }
                    })
                }).fail(function(response) {
                    console.log(response);
                });
        } else if (typeof(_this.options.method) !== 'undefined' && _this.options.method.trim().toLowerCase() !== 'get') {
            _this.goLink();
        }
    }
}