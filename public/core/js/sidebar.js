class Sidebar {
    constructor(options, callback) {
        if (typeof(callback) != 'undefined') {
            options.callback = callback;
        }
        this.init(options);
    }

    init(options) {
        var _this = this;
        this.id = '_' + Math.random().toString(36).substr(2, 9);
        this.options = {};
        this.content = $('.middle-bar .content');
        this.backs = [];

        // options
        if (typeof(options) !== 'undefined') {
            this.options = options;
        }

        this.initMask();
    }

    openMiddleBar() {
        $('body').addClass('middle-bar-open');
    }

    hideMiddleBar() {
        $('body').removeClass('middle-bar-open');
    }
    
    showed() {
        return $('body').hasClass('middle-bar-open');
    }

    show() {
        this.openMiddleBar();
    }
    
    hide() {
        this.hideMiddleBar();
    }
    
    applyJs() {
        var _this = this;
        
        // init js
        initJs(_this.content);
    }

    initMask() {
        var tags = 'h1,h2,h3,h4,h5,p,.btn,span,input,select,.alert,.progress-bar,label,a,textarea,i';
        this.content.find(tags).each(function() {
            if (!$(this).parent(tags).length) {
                $(this).addClass('popup-animated-background');
            }
        });
    }

    mask() {
        this.content.addClass('sidebar-loading');
    }

    unmask() {
        this.content.removeClass('popup-loading');
        this.content.find('*').removeClass('popup-animated-background');
    }
    
    load(options) {
        var _this = this;

        if (typeof(options) == 'undefined') {
            options = {};
        }

        // update options            
        _this.options = $.extend({}, _this.options, options);
        
        // show popup
        _this.show();

        // effect
        _this.mask();

        // load from url
        $.ajax({
            url: _this.options.url,
            method: 'GET',
            dataType: 'html',
            data: _this.options.data,
        }).done(function(response) {
            _this.content.html(response);
            
            // apply js for new content
            _this.applyJs();

            // 
            _this.unmask();

            // loaded callback
            if (typeof(_this.options.callback) != 'undefined') {
                _this.options.callback();
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            // for debugging
            alert(errorThrown);
        }).always(function() {
        });
    }
    
    loadHtml(html, callback) {
        var _this = this;

        //
        if (typeof(callback) != 'undefined') {
            _this.options.callback = callback;
        }

        // show sidebar
        _this.show();
        
        _this.content.html(html);
        
        // apply js for new content
        _this.applyJs();

        //
        _this.unmask();

        // loaded callback
        if (typeof(_this.options.callback) != 'undefined') {
            _this.options.callback();
        }
    }
}