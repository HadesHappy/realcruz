class IframeModal {
    constructor() {
        this.modal = $('.iframe-modal');
        if (!this.modal.length) {
            var modal = $('<div>').html('<div class="iframe-modal"><iframe src=""></iframe></div>');
            $('body').append(modal);
            
            this.modal = modal;            
        }
        this.iframe = this.modal.find('.iframe-modal');
        this.modal.css('display', 'none');
    }
    
    show() {
        this.modal.fadeIn();
        $('html').css('overflow', 'hidden');
    }
    
    hide() {
        this.modal.fadeOut();
        $('html').css('overflow', 'auto');
    }
    
    static hide() {
        $('.iframe-modal').parent().fadeOut();
        $('html').css('overflow', 'auto');
    }
    
    load(src) {
        var _this = this;
        this.src = src;
        this.show();
        //this.iframe.attr('src', this.src);
        //
        //console.log(this.modal);
        
        $.ajax({
            url: src,
            type: 'GET',
            dataType: 'html',
        }).always(function(response) {
            _this.iframe.html(response);

            initJs(_this.iframe);
        });
    }
}
var GlobalIframeModal = IframeModal;