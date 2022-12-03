window.beeFormLoaded = true;

class Rating {
    constructor(container) {
        var _this = this;
        _this.container = container;        

        _this.saveCurrent();

        // move to element
        _this.container.find('span').mouseenter(function() {
            _this.moveTo($(this));
        })

        // leaving stars
        _this.container.find('span').mouseleave(function() {
            _this.leave();
        });

        // set value
        _this.container.find('span').click(function() {
            var rate = $(this).attr('data-star');
            _this.setValue(rate);
        });
    }

    moveTo(element) {
        if(!element.hasClass('remove-rate')) {
            element.html('star');
        }

        var previous = element.prev();
        while(previous.length && !previous.hasClass('remove-rate')) {
            previous.html('star');
            previous = previous.prev();
        }

        var next = element.next();
        while(next.length) {
            next.html('star_outline');
            next = next.next();
        }
    }

    leave() {
        var _this = this;

        _this.container.find('span').each(function() {
            if(!$(this).hasClass('remove-rate')) {
                $(this).html($(this).attr('data-rate'));
            }
        });
    }

    saveCurrent() {
        var _this = this;

        _this.container.find('span').each(function() {
            if(!$(this).hasClass('remove-rate')) {
                $(this).attr('data-rate', $(this).html());
            }
        });
    }

    setValue(value) {
        var _this = this;

        _this.moveTo(_this.container.find('span[data-star='+value+']'));
        _this.saveCurrent();
        _this.container.find('.rating-input').val(value);
    }
}

// var rating;
// $(document).ready(function() {
//     rating = new Rating($('.rating'));
// });