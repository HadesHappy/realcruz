// auto fill url input
class UrlAutoFill {
    constructor(data) {
        var _this = this;
        _this.keyup = null;
        _this.data = data;
        _this.values = [];   
        _this.isLink = function() {
            var link = $('.tox-form .tox-selectfield').length;

            if (link) {
                $('.tox-form__group:first-child input').eq(0).prop('readonly', false);
            } else {
                $('.tox-form__group:first-child input').eq(0).prop('readonly', true);
            }

            return link;
        }

        if (!_this.keyup) {
            _this.keyup = $(document).on('focus', '.tox-form__group:first-child input', function(event) {
                if (!_this.isLink()) {
                    return;
                }

                if (!$('.urlautofill-input').length) {
                    var input = $('<input type="text" class="urlautofill-input">');
                    $(this).after(input);
                    input.focus();
                    input.val($(this).val());

                    _this.input = input;
                }
            });

            $(document).on('input', '.tox-form__group:first-child input', function(event) {
                if (!_this.isLink()) {
                    return;
                }
                console.log('input');
            });

            $(document).on('keyup change', '.urlautofill-input', function(event) {
                if (!_this.isLink()) {
                    return;
                }
                if(event.keyCode !== 13 && event.keyCode !== 38 && event.keyCode !== 40 ) {
                    _this.updateValues();
                    _this.showDropdown();
                }
                $('.tox-form__group:first-child input').eq(0).val(_this.input.val());
            })

            $(document).on('keydown', '.urlautofill-input', function(event) {
                if (!_this.isLink()) {
                    return;
                }
                if(event.keyCode == 38) {
                    _this.moveUp();
                }
                if(event.keyCode == 40) {
                    _this.moveDown();
                }
                if(event.keyCode == 13) {
                    if (_this.current) {
                        _this.select(_this.current);
                    }
                }
            });

            _this.keyword = function() {
                return $('.urlautofill-input').val();
            }

            $(document).on('mousedown', '.urlfill-dropdown li', function() {
                if (!_this.isLink()) {
                    return;
                }
                _this.select($(this));
            })

            $(document).on('focus click', '.urlautofill-input', function(event) {
                if (!_this.isLink()) {
                    return;
                }
                _this.input = $(this);
                _this.updateValues();
                setTimeout(function() {
                    _this.showDropdown();
                    _this.input.val($('.tox-form__group:first-child input').eq(0).val());

                    _this.input.css('opacity', '1');
                }, 100);
            });

            $(document).on('focusout', '.urlautofill-input', function(event) {
                if (!_this.isLink()) {
                    return;
                }
                setTimeout(function() {
                    _this.hideDropdown();
                    _this.input.val($('.tox-form__group:first-child input').eq(0).val());
                }, 100);

                _this.input.css('opacity', '0');
            });
            
            $(document).on('mousedown', '.tox-browse-url', function(event) {
                if (!_this.isLink()) {
                    return;
                }
                $('.tox-form__group:first-child input').eq(0).focus();
            });

            $(document).on('change', '.tox-form__group:first-child input', function(event) {
                if (!_this.isLink()) {
                    return;
                }
                setTimeout(function() {
                    _this.input.val($('.tox-form__group:first-child input').eq(0).val());
                }, 1000);
            });

            $(document).on('mouseover', '.tox-form__group:first-child input', function(event) {
                if (!_this.isLink()) {
                    return;
                }
                if ($('.tox-form__group:first-child input').eq(0).val() != _this.input.val()) {
                    _this.input.val($('.tox-form__group:first-child input').eq(0).val());
                }
            });
        }                    
    }

    moveUp() {
        var _this = this;
        if (_this.dropdown) {
            if (!_this.current || !_this.current.prev().length) {
                _this.setCurrent(_this.dropdown.find('li').last());
            } else {
                _this.setCurrent(_this.current.prev());
            }
        }
    }

    moveDown() {
        var _this = this;
        if (_this.dropdown) {
            if (!_this.current || !_this.current.next().length) {
                _this.setCurrent(_this.dropdown.find('li').first());
            } else {
                _this.setCurrent(_this.current.next());
            }
        }
    }

    setCurrent(li) {
        var _this = this;
        _this.current = li;

        _this.dropdown.find('li').removeClass('current');
        _this.current.addClass('current');

        _this.current.focus();
    }

    updateValues() {
        var _this = this;
        _this.values = [];

        var matches = [];
        _this.data.forEach(function(item) {
            if (_this.keyword() != '' && item.value.toLowerCase().indexOf(_this.keyword().toLowerCase()) === 0) {
                matches.push(item.value);
                _this.values.push(item);
            }
        });

        if (_this.keyword() == '') {
            _this.data.forEach(function(item) {
                if (!matches.includes(item.value)) {
                    _this.values.push(item);
                }
            });
        }
    }

    showDropdown() {
        var _this = this;
        
        // remove current dropdown
        _this.hideDropdown();

        if (_this.values.length) {
            _this.dropdown = $('<ul class="urlfill-dropdown">');

            _this.values.forEach(function(item) {
                _this.dropdown.append($('<li data="'+item.text+'">').html(item.value));
            });

            _this.input.after(_this.dropdown);
            // select first
            _this.setCurrent(_this.dropdown.find('li').first());
        }
    }

    hideDropdown() {
        var _this = this;
        
        // remove current dropdown
        if (_this.dropdown) {
            _this.dropdown.remove();
            _this.dropdown = null;
        }
    }

    select(item) {
        var _this = this;
        var value = item.html();

        _this.input.val(value);
        _this.input.focusout();

        if (_this.input.closest('.tox-form__group').next().find('input').val() == '' && _this.isLink()) {
            _this.input.closest('.tox-form__group').next().find('input').val(item.attr('data'));
        }

        $('.tox-form__group:first-child input').eq(0).val(_this.input.val());
    }
}        