function initJs(container)
{
    // tooltip
    if (container.find('.xtooltip:not([title=""]), [data-popup=tooltip]:not([title=""]), .leftbar-navbar .leftbar-tooltip:not([title=""])').tooltipster) {
        container.find('.xtooltip:not([title=""]), [data-popup=tooltip]:not([title=""]), .leftbar-navbar .leftbar-tooltip:not([title=""])').tooltipster({
            theme: 'tooltipster-light'
        });
    }

    // select2
    if (container.find('.select').select2) {
        container.find('.select').select2({
            dropdownAutoWidth: true,
            minimumResultsForSearch: 30,
            escapeMarkup: function(markup) {
                return markup;
            }
        });
    }

    // pick a date
    if (container.find(".pickadate-control").length) {
        pickadateMask2(container.find('.pickadate-control'));
        container.find('.pickadate-control').pickadate({
            format: 'yyyy-mm-dd'
        });
    }

    // datetime picker
    if (container.find(".pickadatetime").length) {
        container.find(".pickadatetime").each(function() {
            var id = '_' + Math.random().toString(36).substr(2, 9);
            $(this).attr('id', id);

            $('#' + id).AnyTime_picker({
                format: LANG_ANY_DATETIME_FORMAT
            });
        });
    }

    // numeric
    if (container.find(".numeric").numeric) {
        container.find(".numeric").numeric();
    }

    // pick a time
    if (container.find(".pickatime, .time-selector").length) {
        container.find(".pickatime, .time-selector").each(function() {
            var id = '_' + Math.random().toString(36).substr(2, 9);
            $(this).attr('id', id);

            $('#' + id).AnyTime_picker({
                format: "%H:%i"
            });
        });
    }

    // custom validation
    if (typeof(customValidate) != 'undefined') {
        customValidate(container.find(".form-validate-jquery"));
    }

    // link method
    applyLinkListener(container.find('a[link-method], a[link-confirm]'));

    // Select2 tags
    if (container.find('.select-tag').select2) {
        container.find('.select-tag').select2({
            minimumResultsForSearch: 20,
            templateResult: formatSelect2TextOption,
            templateSelection: formatSelect2TextSelected,
            placeholder: function(){
                $(this).attr('data-placeholder');
            },
            dropdownAutoWidth : true,
            width: 'auto',
            tags: true,
        });
    }

    // Select2 ajax
    container.find(".select2-ajax").each(function() {
        initSelect2Ajax($(this));
    });

    // @legacy: radio box
    container.find(".control-radio .radio_box .main-control").on('click', function() {
        var radio_control = $(this).parents('.control-radio');
        var radio_box = $(this).parents('.radio_box');
        var radio = $(this).find('input');

        radio_control.find('.radio_more_box').hide();
        if (radio.is(":checked")) {
            radio_box.find('.radio_more_box').show();
        } else {
            radio_box.find('.radio_more_box').hide();
        }
    });
    container.find(".control-radio .radio_box .main-control input:checked").parents('.main-control').trigger('click');

    // @legacy: button password eye
    container.find('.btn-view-password').on('click', function() {
        var btn = $(this);
        var input = btn.closest('div').find('input');
        
        if (btn.hasClass('open')) {
            btn.removeClass('open');
            input.attr('type', 'password');
            btn.html('visibility');
        } else {
            btn.addClass('open');
            input.attr('type', 'text');
            btn.html('visibility_off');
        }
        
        input.focus();
    });

    // styled checkbox
    container.find('.styled, .switchery, [name=color_scheme]').after('<span class="check-symbol"></span>');

    // showAjaxDetailBox
    showAjaxDetailBox(container.find('.ajax-detail-box'));
}

function openMiddleBar() {
    $('body').addClass('middle-bar-open');
}

function hideMiddleBar() {
    $('body').removeClass('middle-bar-open');
}

function middleBarShowed() {
    return $('body').hasClass('middle-bar-open');
}

function placeholderLoading(container) {
    tags = 'h1,h2,h3,h4,h5,p,.btn,span,input,select,.alert,.progress-bar,label,a,textarea,i';
    container.find(tags).each(function() {
        if (!$(this).parent(tags).length) {
            $(this).addClass('animated-background');
        }
    });
}

function removePlaceholderLoading(container) {
    container.find('*').removeClass('animated-background');
}

function initSelect2Ajax(select) {
    var url = select.attr("data-url");
    var placeholder = select.attr("placeholder");
    if(typeof(placeholder) == 'undefined') {
        placeholder = "";
    }
    select.select2({
        placeholder: placeholder,
        allowClear: true,
        dropdownParent: select.parent(),
        ajax: {
            url: url,
            dataType: 'json',
            delay: 250,

            data: function (params) {
                return {
                q: params.term, // search term
                page: params.page
                };
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 0,
        templateResult: formatSelect2TextOption,
        templateSelection: formatSelect2TextSelected,
    });
}

// Preview upload image
function previewImageBrowse(input, img) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            img.attr('src', e.target.result);

            // calculate crop part
            var box_width = img.parent().width();
            var box_height = img.parent().height();
            var width = img[0].naturalWidth;
            var height = img[0].naturalHeight;
            var cal_width, cal_height;

            if(width/height < box_width/box_height) {
                cal_height = box_height;
                cal_width = box_height*(height/width);
            } else {
                cal_width = box_width;
                cal_height = box_width*(width/height);
            }

            img.width(cal_height);
            img.height(cal_width);

            var mleft = -Math.abs(cal_width - box_width)/2;
            var mtop = -Math.abs(cal_height - box_height)/2;
            img.css("margin-left", mtop+"px");
            img.css("margin-top", mleft+"px");
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function htmlDecode(input){
    var e = document.createElement('textarea');
    e.innerHTML = input;
    // handle case of empty input
    return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
}

function copyToClipboard(text, container) {
    var $temp = $("<input>");
    if (typeof(container) !== 'undefined') {
        container.append($temp);
    } else {
        $("body").append($temp);
    }
    
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();
}

function formatSelect2TextSelected(d) {
    var text = d.text;
    var parts = text.split('|||');

    return parts[0];
}

function formatSelect2TextOption(d) {
    var text = d.text;
    var parts = text.split('|||');

    if (parts.length == 1) {
        return parts[0];
    } else {
        return '<div class="select2_title">' + parts[0] + '</div>' + '<div class="select2_sub_line">' + parts[1] + '</div>';
    }
}

function initMainMenu()
{
    // check menu active
    menuActiveCheck();

    // leftbar scroll behaviour
    $('.leftbar .navbar-main').on( 'DOMMouseScroll mousewheel', function ( event ) {
        if ($('.leftbar').hasClass('leftbar-closed')) {
            if ($(window).width() < 1200) {
                return;
            }
            if ($(this).parent('.dropdown-menu').length) {
                console.log('dddd');
                return;
            }

            if( event.originalEvent.detail > 0 || event.originalEvent.wheelDelta < 0 ) { //alternative options for wheelData: wheelDeltaX & wheelDeltaY
            var isBottom = -(-$(this).css('margin-top').replace('px', '') - $(this).height()) - 10 < $(window).height();
            var adjust;
            
            if (!isBottom) {
                adjust = ($(this).css('margin-top').replace('px', '') - 10) + 'px';
            } else {
                adjust = (-$(this).height() + $(window).height()) + 'px';
            }

            $(this).css('margin-top', adjust);
            } else {
                var isTop = parseInt($(this).css('margin-top').replace('px', '')) + 10 < 0;
            
                if (isTop) {
                    adjust = (parseInt($(this).css('margin-top').replace('px', '')) + 10) + 'px';
                } else {
                    adjust = '0px';
                }
                $(this).css('margin-top', adjust);
            }
            //prevent page fom scrolling
            return false;
        }
    });

    $('.leftbar-hide-menu').on('click', function() {
        $('.leftbar .navbar-main').css('margin-top', '0px');
    });

    $('.leftbar .main-menu .nav-link').on('hide.bs.dropdown', function (e) {
        if (!$('.leftbar').hasClass('leftbar-closed') && e.clickEvent && e.clickEvent.target.className!="nav-link") {
            e.preventDefault();
        }
    });

    var leftbarAdjust = function() {
        $('.leftbar .navbar-main').css('margin-top', '0px');

        if ($(window).width() < 1500) {
            $('.leftbar').removeClass('leftbar-open');
            $('.leftbar').addClass('leftbar-closed');
        }

        if ($(window).width() >= 1500 && ($('body').hasClass('state-open') || $('body').hasClass('state-'))) {
            $('.leftbar').addClass('leftbar-open');
            $('.leftbar').removeClass('leftbar-closed');
        }
    };
    leftbarAdjust();
    $( window ).on('resize', function() {
        leftbarAdjust();
    });
}

function menuActiveCheck() {
    // menu active
    for (i=0; i < 10; i++) {
        $("li[rel"+i+"='"+CONTROLLER+"']").addClass("active");
        $("li[rel"+i+"='"+CONTROLLER+"']").find('.nav-link').eq(0).addClass("active");
        $("li[rel"+i+"='"+CONTROLLER+"']").closest('.nav-link').addClass("active");
        $("li[rel"+i+"='"+CONTROLLER+"']").parents('.nav-item').addClass("active");
        $("li[rel"+i+"='"+CONTROLLER+"']").parents('.nav-item').find('.nav-link').eq(0).addClass("active");
    }
    for (i=0; i < 10; i++) {
        $("li[rel"+i+"='"+CONTROLLER+"/"+ACTION+"']").addClass("active");
        $("li[rel"+i+"='"+CONTROLLER+"/"+ACTION+"']").find('.nav-link').eq(0).addClass("active");
        $("li[rel"+i+"='"+CONTROLLER+"/"+ACTION+"']").closest('.nav-link').addClass("active");
        $("li[rel"+i+"='"+CONTROLLER+"/"+ACTION+"']").parents('.nav-item').addClass("active");
        $("li[rel"+i+"='"+CONTROLLER+"/"+ACTION+"']").parents('.nav-item').find('.nav-link').eq(0).addClass("active");
    }

    $('.dropdown-item.active').closest('.nav-item').addClass("active");
    setTimeout(function() {
        $('.leftbar:not(.leftbar-closed) .main-menu .nav-link.dropdown-toggle.active').dropdown('toggle');
    }, 100);
}

function applyLinkListener(links, options) {
    links.on('click', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        var confirm = $(this).attr('link-confirm');
        var method = $(this).attr('link-method');
        var type = 'link';

        new Link({
            type: 'link',
            url: url,
            confirm: confirm,
            method: method
        });
    });
}

function pickadateMask(selector) {
    $(document).on('change', selector, function() {
        updatePickadateDateMask($(this));
    });
    $(selector).each(function() {
        updatePickadateDateMask($(this));
    });
    $(document).on('focusout', selector, function() {
        var value = $(this).parent().find('.date-mask-control').html();
        var date = moment(value, LANG_DATE_FORMAT.toUpperCase()); //Get the current date
        $(this).val(date.format('yyyy-mm-dd'.toUpperCase()));
    });
}

function pickadateMask2(selector) {
    selector.change(function() {
        updatePickadateDateMask($(this));
    });
    selector.each(function() {
        updatePickadateDateMask($(this));
    });
    selector.focusout(function() {
        var value = $(this).parent().find('.date-mask-control').html();
        var date = moment(value, LANG_DATE_FORMAT.toUpperCase()); //Get the current date
        $(this).val(date.format('yyyy-mm-dd'.toUpperCase()));
    });
}

function updatePickadateDateMask(control) {
    control.each(function() {
        var mask = $(this).parent().find('.date-mask-control');
        var value = $(this).val();

        if(value !== '') {
            var date = moment(value); //Get the current date
            mask.html(date.format(LANG_DATE_FORMAT.toUpperCase()));
        }
    });
}

function popupwindow(url, title, w, h) {
    var left = (screen.width/2)-(w/2);
    var top = 0;
    var height = screen.height;

    if (typeof(h) !== 'undefined') {
        height = h;
        top = (screen.height/2)-(height/2);
    }

    return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+height+', top='+top+', left='+left);
}

function removeMaskLoading() {
    $('.mask-loading-effect').remove();
}

function addMaskLoading(text, callback, options) {
    removeMaskLoading();
    var wait = 400;
    
    if (typeof(text) === 'undefined') {
        text = '';
    }

    if (typeof(options) === 'undefined') {
        options = {};
    }

    if (typeof(options.wait) !== 'undefined') {
        wait = options.wait;
    }
    
    var div = $('<div>').html(`<div class="mask-loading-effect"><div class="content">
        <div class="mask-loading mb-3"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>
        `+text+`</div><div>`
    );
    
    $('body').append(div);
    
    div.fadeIn(400, function() {
        if (typeof(callback) !== 'undefined') {
            setTimeout(function() { callback(); }, wait);
        }
    });
}

function addButtonMask(button) {
    button.addClass('btn-loading');
}

function removeButtonMask(button) {
    button.removeClass('btn-loading');
}

function notify(optionsOrType, title, message) {
    var options = optionsOrType;

    if (typeof(message) != 'undefined') {
        options = {
            type: optionsOrType,
            title: title,
            message: message
        };
    }

    new ANotify().add(options);
}

function makeList(options) {

    if (typeof(options.container) == 'undefined') {
        options.container = options.content;
    }

    if (typeof(options.data) == 'undefined') {
        options.data = function() {
            return options.container.find('.filter-box :input, [name=per_page], [name="uids[]"]').serializeArray();
        };
    }

    var _list = new List({
        url: options.url,
        content: options.content,
        data: options.data,
        per_page: options.per_page,
        method: options.method,
        doAction: function() {

        },
        loaded: function() {
            checkPageChange();;

            this.content.find('[name=per_page]').on('change', function() {
                _list.load();
                checkPageChange();
            });

            this.content.find('pagination a').on('click', function(e) {
                e.preventDefault();

                _list.url = $(this).attr('href');
                _list.load();
            });

            // each row input clicked
            this.content.find('[name="uids[]"]').on('change', function() {
                checkPageChange();
            });

            // check page input clicked
            this.content.find('input[name=page_checked]').on('change', function() {
                checkPage($(this).is(':checked'));
                checkPageChange();
            });

            // actions
            this.content.find('a.list-action-single').off('click').on('click', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');
                var confirm = $(this).attr('link-confirm');
                var confirmUrl = $(this).attr('link-confirm-url');
                var method = $(this).attr('link-method');

                if (typeof(confirm) !== 'undefined') {
                    confirm = confirm.replace(':number', 1);
                }

                new Link({
                    url: url,
                    confirm: confirm,
                    confirmUrl: confirmUrl,
                    method: method,
                    data: {
                        _token: CSRF_TOKEN,
                    },
                    before: function() {
                        _list.masking();
                    },
                    done: function(response) {
                        if (typeof(response) == 'string') {
                            response = {
                                status: 'success',
                                message: response
                            };
                        }

                        notify({
                            type: response.status,
                            message: response.message,
                        });
    
                        _list.load();
                    },
                    fail: function(response) {
                        new Dialog('alert', {
                            type: 'error',
                            title: LANG_ERROR,
                            message: JSON.parse(response.responseText).message
                        });
    
                        _list.load();
                    }
                });
            });
        }
    });

    var getCheckCount = function() {
        return options.container.find('[name="uids[]"]:checked').length;
    };

    var totalItems = function() {
        return options.container.find('[total-items-count]').attr('total-items-count');
    };

    var hasSelectTool = function() {
        return options.container.find('.select_tool').length;
    };

    var allItemsChecked = function() {
        return hasSelectTool() && options.container.find('.select_tool').val() == 'all_items';
    };

    var resetPage = function() {
        _list.url = _list.url.replace(/\?page=./g, '').replace(/&page=./g, '');
    }

    // check if page checked half or all
    var checkPageChange = function() {
        var checkCount = getCheckCount();
        var notCheckCount = options.container.find('[name="uids[]"]').length;

        if (checkCount == notCheckCount && checkCount > 0) {
            options.container.find('input[name=page_checked]').prop('checked', true);
            options.container.find('input[name=page_checked]').removeClass('half_checked');

            // select tool
            if (hasSelectTool() && !allItemsChecked()) {
                options.container.find('.select_tool').val('whole_page').trigger('change.select2');
            }
        } else if (checkCount == 0) {
            options.container.find('input[name=page_checked]').prop('checked', false);
            options.container.find('input[name=page_checked]').removeClass('half_checked');

            // select tool
            if (hasSelectTool()) {
                options.container.find('.select_tool').val('').trigger('change.select2');
            }
        } else {
            options.container.find('input[name=page_checked]').prop('checked', false);
            options.container.find('input[name=page_checked]').addClass('half_checked');

            // select tool
            if (hasSelectTool()) {
                options.container.find('.select_tool').val('').trigger('change.select2');
            }
        }

        // show list actions
        if (checkCount > 0) {
            options.container.find('.list_actions').fadeIn();
        } else {
            options.container.find('.list_actions').hide();
        }

        // update count number
        options.container.find('.list_actions .number').html(checkCount);

        // select all page
        if (allItemsChecked()) {
            options.container.find('.list_actions .number').html(totalItems());
        }
    };

    // check all|none items in page
    var checkPage = function(checked) {
        if (checked) {
            options.container.find('[name="uids[]"]').prop('checked', true);
        } else {
            options.container.find('[name="uids[]"]').prop('checked', false);
        }
    };

    // all filter in box change
    options.container.find('.filter-box select:not(.select_tool), .filter-box input[type=checkbox]:not(.check_all)').on('change', function() {
        resetPage();
        _list.load();
    });

    // keyword input change
    options.container.find('.filter-box input[type=text]').on('keyup', function() {
        resetPage();
        _list.load();
    });

    // sort direction click
    options.container.find('.filter-box .sort-direction').on('click', function() {
        var direction = $('[name=sort_direction]').val();

        if (direction == 'desc') {
            $('[name=sort_direction]').val('asc');
        } else {
            $('[name=sort_direction]').val('desc');
        }
        resetPage();
        _list.load();
    });

    // check page input clicked
    options.container.find('input[name=page_checked]').on('change', function() {
        checkPage($(this).is(':checked'));
        checkPageChange();
    });

    // check page input clicked
    options.container.find('.select_tool').on('change', function() {
        console.log($(this).val());
        checkPage($(this).val() !== '');
        checkPageChange();
    });
    
    // list actions
    options.container.find('.a.list-action-multi, .list_actions a.dropdown-item').off('click').on('click', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        var confirm = $(this).attr('link-confirm');
        var confirmUrl = $(this).attr('link-confirm-url');
        var method = $(this).attr('link-method');
        var data = _list.data();

        if (typeof(confirm) !== 'undefined') {
            // check all items
            if (allItemsChecked()) {
                confirm = confirm.replace(':number', totalItems());
            } else {
                confirm = confirm.replace(':number', getCheckCount());
            }
        }

        data.push({name: '_token', value: CSRF_TOKEN});

        new Link({
            url: url,
            confirm: confirm,
            confirmUrl: confirmUrl,
            method: method,
            data: data,
            before: function() {
                _list.masking();
            },
            done: function(response) {
                if (typeof(response) == 'string') {
                    response = {
                        status: 'success',
                        message: response
                    };
                }

                notify({
                    type: response.status,
                    message: response.message,
                });

                _list.load();
            },
            fail: function(response) {
                new Dialog('alert', {
                    type: 'error',
                    title: LANG_ERROR,
                    message: JSON.parse(response.responseText).message
                });

                _list.load();
            }
        });
    });

    return _list;
}

function openBuilder(url) {
    var div = $('<div class="full-iframe-popup">').html(`
        <div class="loading classic-loader frame-classic-loader"><div class="text-center inner"><div class="box-loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div></div></div>
        <iframe scrolling="no" class="builder d-none" src="`+url+`"></iframe>
    `);
    
    $(".full-iframe-popup").remove();
    $('body').append(div);

    // open builder effects
    addMaskLoading();
    $('.builder').on("load", function() {
        removeMaskLoading();

        $(this).removeClass("d-none");

        $('.full-iframe-popup .frame-classic-loader').remove();
    });
    $('body').addClass('overflow-hidden');
}

function openBuilderClassic(url) {
    var div = $('<div class="full-iframe-popup">').html(`
        <div class="loading classic-loader frame-classic-loader"><div class="text-center inner"><div class="box-loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div></div></div>
        <iframe scrolling="no" class="builder d-none" src="`+url+`"></iframe>
    `);
    
    $(".full-iframe-popup").remove();
    $('body').append(div);

    // open builder effects
    addMaskLoading();
    $('.builder').on("load", function() {
        removeMaskLoading();

        $(this).removeClass("d-none");

        $('.full-iframe-popup .frame-classic-loader').remove();
    });
    $('body').addClass('overflow-hidden');
}

function isDarkMode() {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
}

function autoDetechDarkMode(url) {
    if (isDarkMode()) {
        $('body').addClass('mode-dark');
    }

    $.ajax({
        url: url,
        method: 'GET',
        data: {
            theme_mode: isDarkMode() ? 'dark' : '',
        },
    });

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        const newColorScheme = e.matches ? "dark" : "light";

        if(newColorScheme == 'dark') {
            $('body').addClass('mode-dark');
        } else {
            $('body').removeClass('mode-dark');
        }

        $.ajax({
            url: url,
            method: 'GET',
            data: {
                theme_mode: newColorScheme,
            },
        });
    });
}

function showAjaxDetailBox(items) {
    items.each(function() {
        var container = $(this);
        var form_class = container.attr('data-form');
        var form = $(form_class);
        var url = container.attr('data-url');
        var method = form.attr('method');
        var hook_class = container.attr('hook');
        var loading_message = container.attr('loading-message');

        if (typeof(hook_class) === 'undefined') {
            hook_class = 'hook';
        }

        if(typeof(method) === 'undefined') {
            method = 'POST';
        }

        $(document).on('change', form_class + ' .' + hook_class, function() {
            data = form.serialize();

            if (typeof(loading_message) !== 'undefined') {
                container.html(loading_message);
            }

            $.ajax({
                method: 'GET',
                url: url,
                data: data
            })
            .done(function(msg) {
                container.html(msg);
                
                initJs(container);
            });
        });
    });
}

function changeThemeMod(mode) {
    $("body").removeClass (function (index, className) {
        return (className.match (/(^|\s)mode-\S+/g) || []).join(' ');
    });

    $("body").addClass('mode-' + mode);

    // auto mode
    if (mode == 'auto') {
        autoDetechDarkMode();
    }
}

function insertAtCursor(myField, myValue) {
    //IE support
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    }
    //MOZILLA and others
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
            + myValue
            + myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
}