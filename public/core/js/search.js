class SearchSection {
    constructor(options) {
        this.id = '_' + Math.random().toString(36).substr(2, 9);

        for (const [key, value] of Object.entries(options)) {
            this[key] = value;
        }
    }

    getContainer() {
        // check exist
        if (!$('#' + this.id).length) {
            TopSearchBar.getResultsBox().append(`<div class="search-section" id="`+this.id+`"></div>`);
        }

        return $('#' + this.id);
    }

    load(options) {
        var _this = this;

        if (typeof(options) !== 'undefined') {
            for (const [key, value] of Object.entries(options)) {
                this[key] = value;
            }
        }

        // loading spinner
        if (typeof(_this.before) != 'undefined') {
            _this.before();
        }

        if(this.xhr != null && this.xhr.readyState != 4){
            this.xhr.abort();
        }
        this.xhr = $.ajax({
            url: _this.url,
            type: 'GET',
            data: {
                keyword: TopSearchBar.getKeyword()
            }
        }).done(function(response) {
            _this.getContainer().html(response);

            if (typeof(_this.callback) != 'undefined') {
                _this.callback();
            }
        });
    }
}

var TopSearchBar = {
    getSearchBox: function() {
        return $('.app_search_box');
    },

    getSearchInput: function() {
        return $('.app_search_input');
    },

    getSearchControl: function() {
        return $('.search-control');
    },

    getResultsBox: function() {
        return $('.search-results');
    },

    getResults: function() {
        return $('.search-result');
    },

    getSelected: function() {
        return $('.search-result.selected');
    },

    openSearch: function() {
        $('body').addClass('search-open');
        TopSearchBar.getSearchInput().trigger('focus');
        TopSearchBar.getSearchInput()[0].setSelectionRange(0, TopSearchBar.getSearchInput()[0].value.length)
    },

    closeSearch: function() {
        TopSearchBar.getSearchInput().trigger('blur');
        $('body').removeClass('search-open');
    },

    isSearchOpen: function() {
        return $('body').hasClass('search-open');
    },

    getKeyword: function() {
        return TopSearchBar.getSearchInput().val().trim();
    },



    unselect: function() {
        if (TopSearchBar.getSelected().length) {
            TopSearchBar.getSelected().removeClass('selected');
        }
    },

    go: function() {
        if (!TopSearchBar.getResults().length) {
            return;
        }

        if (!TopSearchBar.getSelected().length) {
            TopSearchBar.select(TopSearchBar.getResults().first());
        }

        window.location = TopSearchBar.getSelected().attr('href');
    },

    select: function(result) {
        if (result == null) {
            return;
        }

        TopSearchBar.unselect();
        result.addClass('selected');
        result.focus();
    },

    findPrev: function() {
        // current section
        if (TopSearchBar.getSelected().prevAll('.search-result').length) {
            return TopSearchBar.getSelected().prevAll('.search-result').first();
        }

        // next section
        if (TopSearchBar.getSelected().closest('.search-section').prevAll('.search-section').length) {
            if (TopSearchBar.getSelected().closest('.search-section').prevAll('.search-section').find('.search-result').length) {
                return TopSearchBar.getSelected().closest('.search-section').prevAll('.search-section').find('.search-result').last();
            }
        }

        return null;
    },

    moveUp: function() {
        if (!TopSearchBar.getResults().length) {
            return;
        }

        if (!TopSearchBar.getSelected().length) {
            TopSearchBar.select(TopSearchBar.getResults().first());
            return;
        }

        if (!TopSearchBar.findPrev() == null) {
            return;
        }

        TopSearchBar.select(TopSearchBar.findPrev());
    },

    scrollUp: function() {
        TopSearchBar.getResultsBox().animate({
            scrollTop: TopSearchBar.getResultsBox().scrollTop() + TopSearchBar.getSelected().height()*4
        }, 100);
    },

    scrollDown: function() {
        TopSearchBar.getResultsBox().animate({
            scrollTop: TopSearchBar.getResultsBox().scrollTop() - TopSearchBar.getSelected().height()*4
        }, 100);
    },

    findNext: function() {
        // current section
        if (TopSearchBar.getSelected().nextAll('.search-result').length) {
            return TopSearchBar.getSelected().nextAll('.search-result').first();
        }

        // next section
        if (TopSearchBar.getSelected().closest('.search-section').nextAll('.search-section').length) {
            if (TopSearchBar.getSelected().closest('.search-section').nextAll('.search-section').find('.search-result').length) {
                return TopSearchBar.getSelected().closest('.search-section').nextAll('.search-section').find('.search-result').first();
            }
        }

        return null;
    },

    moveDown: function() {
        if (!TopSearchBar.getResults().length) {
            return;
        }

        if (!TopSearchBar.getSelected().length) {
            TopSearchBar.select(TopSearchBar.getResults().first());
            return;
        }

        if (!TopSearchBar.findNext() == null) {
            return;
        }

        TopSearchBar.select(TopSearchBar.findNext());
    },

    showNoKeyword: function() {
        TopSearchBar.getResultsBox().html(`
            <div class="no-keyword-alert px-5">
                <div class="py-4 text-center">
                    <div class="mb-2">
                        <svg style="width:65px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 432.2 411"><defs><clipPath id="clip-path"><circle cx="158" cy="157" r="144" style="fill:none"/></clipPath></defs><g style="isolation:isolate"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M430,135.9v42.4c0,7.6-8.4,13.8-18.7,13.8H302V122H411.3C421.6,122,430,128.2,430,135.9Z" style="fill:#fff;opacity:0.13"/><path d="M302,192.1H410.7c10.3,0,18.6-6.2,18.6-13.8V135.8c0-7.6-8.3-13.8-18.6-13.8H302" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:5.769283168228909px"/><g style="clip-path:url(#clip-path)"><rect x="81.7" y="110.1" width="296.3" height="93.89" rx="18.6" style="fill:#fff;opacity:0.13"/><path d="M194.2,110.1H358.8a18.5,18.5,0,0,1,18.5,18.6v56.8A18.5,18.5,0,0,1,358.8,204H194.2" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:6.659193055820083px"/><path d="M168.2,204H99.5A18.5,18.5,0,0,1,81,185.5V128.7a18.5,18.5,0,0,1,18.5-18.6h68.7" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:6.659193055820083px"/><path d="M162.6,227.3h.1a17.9,17.9,0,0,0,17.9-17.8V104.4a17.9,17.9,0,0,0-17.9-17.9h-.1" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:5.983098520979932px"/><path d="M198.5,86.5h-.1a17.9,17.9,0,0,0-17.8,17.9V209.5a17.8,17.8,0,0,0,17.8,17.8h.1" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:5.983098520979932px"/><path d="M146.3,175l-.6-4.9h-.2a14.6,14.6,0,0,1-11.9,5.8c-7.8,0-11.8-5.6-11.8-11.2,0-9.3,8.3-14.4,23.3-14.3v-.8c0-3.2-.9-9-8.8-9a19,19,0,0,0-10.1,2.9l-1.6-4.6a24,24,0,0,1,12.7-3.5c11.9,0,14.7,8.1,14.7,15.8v14.5a52.6,52.6,0,0,0,.7,9.3Zm-1.1-19.8c-7.6-.1-16.3,1.2-16.3,8.7,0,4.6,3,6.8,6.6,6.8a9.6,9.6,0,0,0,9.3-6.5,5.9,5.9,0,0,0,.4-2.3Z" style="fill:gray"/></g><g style="opacity:0.13"><circle cx="158" cy="157" r="149" style="fill:#0071bc"/></g><image width="329" height="329" xlink:href="data:image/png;base64,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" style="opacity:0.25;mix-blend-mode:multiply"/><path d="M157.5,18A139.6,139.6,0,0,1,297,157.5,139.6,139.6,0,0,1,157.5,297,139.6,139.6,0,0,1,18,157.5,139.6,139.6,0,0,1,157.5,18m0-10A149.5,149.5,0,1,0,307,157.5,149.5,149.5,0,0,0,157.5,8Z" style="fill:#0071bc"/><image width="152" height="152" transform="translate(240 259)" xlink:href="data:image/png;base64,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" style="opacity:0.25;mix-blend-mode:multiply"/><line x1="253" y1="272" x2="364.5" y2="383.5" style="fill:none;stroke:#0071bc;stroke-linecap:round;stroke-linejoin:round;stroke-width:10px"/><path d="M43,164v-1A122.1,122.1,0,0,1,117,50.8" style="fill:none;stroke:#0071bc;stroke-linecap:round;stroke-linejoin:round;stroke-width:10px"/></g></g></g></svg>
                    </div>
                    <p class="line-1 px-4 mb-0" style="font-size: 92%">
                        ` + TopSearchBar.lang.no_keyword + `
                    </p>
                </div>
            </div>
        `);
    },

    hideNoKeyword: function() {
        if (this.getResults().length) {
            TopSearchBar.getResultsBox().find('.no-keyword-alert').remove();
        }
    },

    hideEmptyResult: function() {
        TopSearchBar.getResultsBox().find('.empty-result-alert').remove();
    },

    showEmptyResult: function() {
        TopSearchBar.getResultsBox().html(`
            <div class="empty-result-alert">
                <div class="py-3 text-center">
                    <span class="line-1 text-muted2 px-4">
                        <span class="material-icons-outlined mb-2 me-2">
                            feedback
                        </span> ` + TopSearchBar.lang.empty_result + `
                    </span>
                </div>
            </div>
        `);
    },
    
    abortAll: function() {
        this.sections.forEach(function(section) {
            if(section.xhr != null && section.xhr.readyState != 4){
                section.xhr.abort();
            }
        });
    },

    beforeSearch: function() {
        this.getSearchBox().addClass('searching');

        // no keyword
        if (!this.getKeyword() && !this.getResults().length) {
            this.showNoKeyword();
        }
    },

    afterSearch: function() {
        this.getSearchBox().removeClass('searching');

        // no keyword
        if (!this.getKeyword()) {
            this.showNoKeyword();
        } else {
            this.hideNoKeyword();

            // result check
            if (!this.getResults().length) {
                this.showEmptyResult();
            } else {
                this.hideEmptyResult();
            }
        }
    },

    search: function() {
        this.abortAll();

        // start search
        this.beforeSearch();

        // no keyword
        if (!this.getKeyword()) {
            this.afterSearch();
            return;
        }

        // do sections search
        this.current = 0;
        if (typeof(this.sections[TopSearchBar.current]) != 'undefined') {
            this.runSection(this.sections[TopSearchBar.current]);
        }
    },

    runSection: function(section) {
        // after search
        if (typeof(section) == 'undefined') {
            this.afterSearch();
            return;
        }

        section.load({
            callback: function() {
                TopSearchBar.current += 1;
                TopSearchBar.runSection(TopSearchBar.sections[TopSearchBar.current]);

                if (TopSearchBar.getKeyword() && TopSearchBar.getResults().length) {
                    TopSearchBar.hideNoKeyword();
                    TopSearchBar.hideEmptyResult();
                }
            }
        });
    },

    init: function(options) {
        // search sections
        TopSearchBar.sections = options.sections;
        TopSearchBar.lang = options.lang;
        TopSearchBar.container = options.container;

        // html structure
        TopSearchBar.container.html(`
            <div class="d-flex">
                <div class="app_search_box py-2 ms-auto">
                    <div class="d-flex align-items-center control-line">
                        <a href="javascript:;" class="search-close-button me-2">
                            <span class="material-icons-round fs-4">close</span>
                        </a>
                        <div class="search-control d-flex justify-content-center">
                            <input class="app_search_input form-control" type="text" placeholder="Type to search" />
                            <span class="material-symbols-rounded search-icon xtooltip"  title="`+TopSearchBar.lang.tooltip+`">
                                search
                            </span>
                        </div>
                    </div>
                    <div class="search-results shadow border">
                    </div>
                </div>
            </div>
        `);

        initJs(TopSearchBar.container);

        $('.search-icon').on('click', function() {
            TopSearchBar.openSearch();
            TopSearchBar.search();
        });

        TopSearchBar.getSearchInput().on('keyup', function(e) {
            if (e.which == 40 || e.which == 38 || e.which == 13 || e.which == 37 || e.which == 39) {
                return;
            }
            TopSearchBar.search();
        });

        $('.search-close-button').on('click', function() {
            TopSearchBar.closeSearch();
        });

        // clid ouside hide search box
        $(document).on('mousedown', function(e) 
        {
            var container = TopSearchBar.getSearchBox();
            var other = $('.top-search-container');

            // if the target of the click isn't the container nor a descendant of the container
            if (
                (!container.is(e.target) && container.has(e.target).length === 0)
                // && (!other.is(e.target) && other.has(e.target).length === 0)
            )
            {
                TopSearchBar.closeSearch();
            }
        });

        // biding arrows key
        $(document).on('keydown', function(e) {
            if (!TopSearchBar.isSearchOpen()) {
                return;
            }
            switch(e.which) {
                case 13: // enter
                    TopSearchBar.go();
                    e.preventDefault();
                break;

                case 37: // left
                break;

                case 38: // up
                    TopSearchBar.moveUp();
                    e.preventDefault();
                break;

                case 39: // right
                break;

                case 40: // down
                    TopSearchBar.moveDown();
                    e.preventDefault();
                break;

                default: return; // exit this handler for other keys
            }
            
        });

        // keyboard shorcut
        $(document).on('keyup', function(e) {
            if (e.ctrlKey && e.key === 'd') {
                TopSearchBar.openSearch();
                TopSearchBar.search();
            }
        });

        $(document).on('keyup', function(e) {
            if (e.key === 'Escape') {
                TopSearchBar.closeSearch();
            }
        });
    }
};