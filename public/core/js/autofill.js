$.fn.autofill = function(options) {
    var box = this;
    box.id = '_' + Math.random().toString(36).substr(2, 9);
    box.input = box.find('.autofill-input');
    box.value = function() {
        return box.input.val().trim();
    };
    box.error = box.input.attr('error-message');
    box.empty = box.input.attr('empty-message');
    box.header = box.input.attr('header');

    box.url = box.input.attr('data-url');
    box.current = null;
    box.xhr = null;
    
    // Options
    if (typeof(options) == 'undefined') {
        box.options = {};
    } else {
        box.options = options;
    }
    
    // Messages
    if (typeof(box.options.messages) == 'undefined') {
        box.messages = {};
    } else {
        box.messages = box.options.messages;
    }

    // dropdown list
    box.loadingRow = '<li class="loader-box"><a class="loader" href="javascript:;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></a></li>';
    dropboxHtml = '<div class="autofill-dropbox-container autofill-dropbox-container-'+box.id+' hide"><ul class="autofill-dropbox autofill-dropbox-'+box.id+'">' +
        //'<li><a class="loading" href="javascript:;">'+loadingEffect+'</a></li>' +
        //'<li><a class="active" href="javascript:;"><label>Lawe Pham</label><span class="text-bold">L</span>awepham@gmail.com</a></li>' +
        //'<li><a class="" href="javascript:;"><label>Louis Walker</label><span class="text-bold">L</span>ouis@gmail.com</a></li>' +
        //'<li><a class="" href="javascript:;"><label>Lion Meo</label><span class="text-bold">L</span>ion@yahoo.com</a></li>' +
        //'<li><a class="" href="javascript:;"><label>****@gmail.com</label></a></li>' +
        //'<li><a class="" href="javascript:;"><label>****@abc.com</label></a></li>' +
        '</ul></div>';
    box.append(dropboxHtml);
    box.dropbox = box.find('.autofill-dropbox-'+box.id);
    box.container = box.find('.autofill-dropbox-container-'+box.id);

    if (box.header != '') {
        box.container.prepend('<h5 class="header">'+box.header+'</h5>');
    }

    //// Add loading row
    //box.dropbox.html(box.loadingRow);

    // ---- BEGIN FUNCTIONS ------------------------------
    // Init dropbox
    box.clearDropbox = function() {
        box.dropbox.html(box.loadingRow);
    };

    box.maskDropbox = function() {
        if (!box.dropbox.find('.loader-box').length) {
            box.dropbox.prepend(box.loadingRow);
        }
    };

    box.unmaskDropbox = function() {
        box.dropbox.find('.loader-box').remove();
    };

    // Show dropbox
    box.showDropbox = function() {
        //if (box.value() != '') {
            box.container.removeClass('hide');
        //}
    };

    // Hide dropbox
    box.hideDropbox = function() {
        box.container.addClass('hide');
    };

    // Toggle dropbox
    box.toggleDropbox = function() {
        if (box.value() != '') {
            box.showDropbox();
        } else {
            // hideDropbox();
        }
    };

    // Set current
    box.setCurrent = function(li) {
        box.current = li;

        box.dropbox.find('li').removeClass('current');
        box.current.addClass('current');
    };

    // Reset current
    box.resetCurrent = function() {
        if (box.dropbox.find('li.autofill-item').length) {
            box.setCurrent(box.dropbox.find('li.autofill-item').first());
        } else {
            box.current = null;
        }
    };

    // Select current
    box.selectCurrent = function() {
        if (box.current != null) {
            var value = box.current.attr('data-value');
            var subfix = box.current.attr('data-subfix');
            if (value != 'undefined') {
                box.input.val(value);
            }
            if (subfix != 'undefined') {
                box.input.val(box.value().split('@')[0]+'@'+subfix);
            }
            box.input.blur();
        }
    };

    box.updateErrorMessage = function() {
        var valid = false;

        box.dropbox.find('li.autofill-item').each(function() {
            var dataValue = $(this).attr('data-value').trim().toLowerCase();
            var dataSubfix = $(this).attr('data-subfix').trim().toLowerCase();

            // sender
            if (typeof(box.value()) != 'undefined' && box.value().trim().toLowerCase() == dataValue) {
                valid = true;
                return;
            }

            // domain
            if (box.value().split('@').length >= 2 && dataSubfix == box.value().split('@')[1].trim().toLowerCase()) { // && dataSubfix.indexOf(box.value().split('@')[1].trim().toLowerCase()) == 0) {
                valid = true;
                return;
            }
            
            // domain
            if (box.value().split('@').length < 2 || box.value()[box.value().length-1] == '@') {
                valid = true;
                return;
            }
        });

        if (!valid) {
            if (box.error != '') {
                if (!box.container.closest('.control-autofill').find('.autofill-error').length) {
                    if (box.container.closest('.control-autofill').find('.help-block').length > 0) {
                        box.container.closest('.control-autofill').find('.help-block').after('<div class="helper-block autofill-error alert alert-warning">'+box.error+'</div>');
                    } else {
                        box.container.after('<div class="helper-block autofill-error alert alert-warning">'+box.error+'</div>');
                    }
                }
            }
            
            // Change box title
            box.container.find('.header').addClass('text-warning');
            box.container.find('.header').html(box.messages.header_not_found);
        } else {
            box.container.parent().find('.autofill-error').remove();
            // Change box title
            box.container.find('.header').removeClass('text-warning');
            box.container.find('.header').html(box.messages.header_found);
        }

        // callback
        if (typeof(box.options.callback) != 'undefined') {
            box.options.callback();
        }
    };

    box.renderDropboxList = function(data) {
        box.dropbox.html('');

        if (data.length) {
            data.forEach(function(row) {
                if (typeof(row.value) != 'undefined' || typeof(row.subfix) != 'undefined') {
                    var html = '<li class="autofill-item" data-value="'+row.value+'" data-subfix="'+row.subfix+'">' +
                        '<a href="javascript:;" class="autofill-item-a">' +
                            '<label>'+row.text+'</label>';

                    if (row.desc != null) {
                        html += row.desc;
                    }

                    html += '</a>' +
                        '</li>';

                    box.dropbox.append(html);
                }

                if (typeof(row._warning) != 'undefined') {
                    var html = '<li class="">' +
                        '<span href="javascript:;" class="autofill-item-a">' +
                            '<label class="text-danger">'+row._warning+'</label>';

                    html += '</span>' +
                        '</li>';

                    box.dropbox.append(html);
                }
            });
        } else {
            var html = '<li class="">' +
                '<a class="autofill-item-empty text-center" href="javascript:;">' +
                    '<label>'+box.empty+'</label>';
                '</a>' +
            '</li>';

            box.dropbox.append(html);
        }
    };

    box.moveUp = function() {
        if (box.dropbox.find('li.autofill-item').length) {
            if (box.current == null || !box.current.prev().hasClass('autofill-item')) {
                box.setCurrent(box.dropbox.find('li.autofill-item').last());
            } else if (box.current.prev().hasClass('autofill-item')) {
                box.setCurrent(box.current.prev());
            }
        }
    };

    box.moveDown = function() {
        if (box.dropbox.find('li.autofill-item').length) {
            if (box.current == null || !box.current.next().hasClass('autofill-item')) {
                box.setCurrent(box.dropbox.find('li.autofill-item').first());
            } else if (box.current.next().hasClass('autofill-item')) {
                box.setCurrent(box.current.next());
            }
        }
    };

    // jax load dropbox content
    box.loadDropbox = function(callback) {
        box.maskDropbox();

        if(box.xhr != null && box.xhr.readyState != 4){
            box.xhr.abort();
        }
        box.xhr = $.ajax({
            method: 'GET',
            url: box.url,
            data: {
                keyword: box.value()
            }
        })
        .fail(function(res) {
            console.log(res);
        })
        .done(function(data) {
            // box.dropbox.html(html);
            box.renderDropboxList(data);

            box.resetCurrent();

            // Click to dropbox
            box.find('.autofill-item').on('click', function() {
                box.setCurrent($(this));
                box.selectCurrent();
            });

            box.unmaskDropbox();

            // callback
            if (typeof(callback) !== 'undefined') {
                callback();
            }
        });
    };

    // ==== END FUNCTIONS =============================

    // ---- BEGIN EVENTS ------------------------------
    // On focus
    box.input.on('focus', function() {
        box.showDropbox();
        box.loadDropbox();
    });

    // Out focus
    box.input.on('focusout', function() {
        setTimeout(function() {box.hideDropbox();}, 200);
        box.updateErrorMessage();
    });

    // Key up
    box.input.on('keyup', function(event) {
        if(event.keyCode !== 13 && event.keyCode !== 38 && event.keyCode !== 40 ) {
            box.toggleDropbox();
            box.loadDropbox();
        }
        if(event.keyCode == 38) {
            box.moveUp();
        }
        if(event.keyCode == 40) {
            box.moveDown();
        }
        
        box.updateErrorMessage();
    });

    // Key up
    box.input.on('blur', function(event) {
        box.updateErrorMessage();
    });

    box.input.on('change', function(event) {
        box.updateErrorMessage();
    });

    // Key down
    box.input.on('keydown', function(event) {
        if(event.keyCode == 13) {
            event.preventDefault();

            box.selectCurrent();

            return false;
        }
    });

    // ==== END EVENTS =============================

    return box;
};