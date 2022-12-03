class List {
    constructor(options) {
        var _this = this;
        _this.id = '_' + Math.random().toString(36).substr(2, 9);
        _this.options = {};

        // options
        if (typeof(options) !== 'undefined') {
            _this.options = options;
        }
        
        // url
        if (typeof(options.url) !== 'undefined') {
            _this.url = options.url;
        } else {
            alert('List url is required!');
            return;
        }

        // content
        if (typeof(options.content) !== 'undefined') {
            _this.content = options.content;
        } else {
            alert('List content is required!');
            return;
        }
        
        // data
        if (typeof(options.data) !== 'undefined') {
            _this.data = options.data;
        } else {
            _this.data = function() { return {}; };
        }

        // method
        if (typeof(options.method) !== 'undefined') {
            _this.method = options.method
        } else {
            _this.method = 'GET';
        }

        _this.content.html(`
            <table class="init-loading table table-box pml-table mt-2 pre-list-content" current-page="1"><tbody>
            <tr><td width="1%"><div class="text-nowrap"><div class="checkbox inline"><label class="animated-background"><input type="checkbox" class="node styled" name="uids[]" value="60fbcde0d8691"></label></div></div></td><td><h5 class="text-bold mb-2 animated-background"><a class="kq_search" href="#">asdasda asdas dasd asd asd asd as dasd asd as  as</a></h5><div class="mb-2"><span class="text-semibold tooltipstered animated-background" data-popup="tooltip">500 Recipients</span></div><span class="text-muted2 animated-background">Updated at: Jul 24th, 2021 08:22</span></td><td></td><td></td><td></td><td width="15%" class="text-center"><span class="text-muted2 list-status pull-left animated-background" title="" data-popup="tooltip"><span class="label label-flat bg-new">New a</span></span><pre style="display:none"></pre></td><td class="text-end text-nowrap"><div class="d-flex align-items-center text-nowrap justify-content-end"><a href="#/edit" role="button" class="btn btn-secondary btn-icon ms-1 animated-background"> <span class="material-icons-outlined">edit</span> Edit</a><div class="btn-group ms-1" role="group"><button id="btnGroupDrop1" role="button" class="btn btn-light animated-background" data-bs-toggle="dropdown">AC</button></div></div></td></tr>
            <tr><td width="1%"><div class="text-nowrap"><div class="checkbox inline"><label class="animated-background"><input type="checkbox" class="node styled" name="uids[]" value="60fbcde0d8691"></label></div></div></td><td><h5 class="text-bold mb-2 animated-background"><a class="kq_search" href="#">asdasda asdas dasd asd asd asd as dasd asd as  as</a></h5><div class="mb-2"><span class="text-semibold tooltipstered animated-background" data-popup="tooltip">500 Recipients</span></div><span class="text-muted2 animated-background">Updated at: Jul 24th, 2021 08:22</span></td><td></td><td></td><td></td><td width="15%" class="text-center"><span class="text-muted2 list-status pull-left animated-background" title="" data-popup="tooltip"><span class="label label-flat bg-new">New a</span></span><pre style="display:none"></pre></td><td class="text-end text-nowrap"><div class="d-flex align-items-center text-nowrap justify-content-end"><a href="#/edit" role="button" class="btn btn-secondary btn-icon ms-1 animated-background"> <span class="material-icons-outlined">edit</span> Edit</a><div class="btn-group ms-1" role="group"><button id="btnGroupDrop1" role="button" class="btn btn-light animated-background" data-bs-toggle="dropdown">AC</button></div></div></td></tr>
            <tr><td width="1%"><div class="text-nowrap"><div class="checkbox inline"><label class="animated-background"><input type="checkbox" class="node styled" name="uids[]" value="60fbcde0d8691"></label></div></div></td><td><h5 class="text-bold mb-2 animated-background"><a class="kq_search" href="#">asdasda asdas dasd asd asd asd as dasd asd as  as</a></h5><div class="mb-2"><span class="text-semibold tooltipstered animated-background" data-popup="tooltip">500 Recipients</span></div><span class="text-muted2 animated-background">Updated at: Jul 24th, 2021 08:22</span></td><td></td><td></td><td></td><td width="15%" class="text-center"><span class="text-muted2 list-status pull-left animated-background" title="" data-popup="tooltip"><span class="label label-flat bg-new">New a</span></span><pre style="display:none"></pre></td><td class="text-end text-nowrap"><div class="d-flex align-items-center text-nowrap justify-content-end"><a href="#/edit" role="button" class="btn btn-secondary btn-icon ms-1 animated-background"> <span class="material-icons-outlined">edit</span> Edit</a><div class="btn-group ms-1" role="group"><button id="btnGroupDrop1" role="button" class="btn btn-light animated-background" data-bs-toggle="dropdown">AC</button></div></div></td></tr>
            </tbody></table>
        `);

        _this.initLoading();
    }

    initLoading() {
        var _this = this;

        _this.content.addClass('list-loading');
        _this.content.addClass('list-loading-init');
        _this.content.append('<span class="list-loading-msg">'+LOADING_WAIT+'</span>');

        placeholderLoading(_this.content);
    }

    masking() {
        var _this = this;

        _this.content.addClass('list-loading');

        if (_this.content.find('.init-loading').length) {
            _this.initLoading();
        }
    }

    unmasking() {
        var _this = this;
        
        _this.content.removeClass('list-loading');
        _this.content.removeClass('list-loading-init');
        _this.content.find('.list-loading-msg').remove();

        removePlaceholderLoading(_this.content);
    }

    load() {
        var _this = this;

        if(_this.xhr && _this.xhr.readyState != 4){
            _this.xhr.abort();
        }

        _this.masking();

        _this.xhr = $.ajax({
            url: _this.url,
            method: _this.method,
            data: _this.data(),
        }).done(function(response) {
            _this.content.html(response); 
            initJs(_this.content);

            // method
            if (typeof(_this.options.loaded) !== 'undefined') {
                _this.options.loaded();
            }

            _this.unmasking();
        }).fail(function(response) {
            // alert(response.responseText);

            // _this.unmasking();
        });
    }
}