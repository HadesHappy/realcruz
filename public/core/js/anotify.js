class ANotify {
    constructor(options) {
        var _this = this;
        _this.id = '_' + Math.random().toString(36).substr(2, 9);
        _this.options = {};
        _this.notifications = [];
        _this.container = $('#anotify');

        // options
        if (typeof(options) !== 'undefined') {
            _this.options = options;
        }

        // append container
        if (!_this.container.length) {
            $('body').append('<div id="anotify"></div>')
            _this.container = $('#anotify');
        }
    }

    add(options) {
        var _this = this;
        var id = '_' + Math.random().toString(36).substr(2, 9);
        var timeout = 5000;
        var type = 'info';

        if (typeof(options.timeout) !== 'undefined') {
            timeout = options.timeout;
        }

        if (typeof(options.type) !== 'undefined') {
            type = options.type;
        }

        if (type == 'error') {
            type = 'danger';
        }

        var titleHtml = '';
        if (typeof(options.title) !== 'undefined') {
            titleHtml = `
                <div class="fw-600">`+options.title+`</div>
            `;
        }

        var closeButton = '';
        if (typeof(options.dismissible) !== 'undefined' && options.dismissible === true) {
            closeButton = `
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
        }
        _this.container.prepend(`
            <div id="`+id+`" class="alert alert-`+type+` alert-dismissible fade show shadow-sm" style="display:none" role="alert">
                `+titleHtml+`
                <div>`+options.message+`</div>
                `+closeButton+`
            </div>
        `);

        $('#' + id).slideDown();

        if (timeout !== false) {
            setTimeout(function() {
                $('#' + id).fadeOut();
            }, timeout);
        }
    }
}