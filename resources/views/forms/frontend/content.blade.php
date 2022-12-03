{!! $content !!}

@include('layouts.core._script_vars')

<script>
    // before
    document.addEventListener("DOMContentLoaded", function(event) {
        // load css
        FormPopupContent.loadCss('{{ url('core/css/form_popup_content.css') }}');

        // load js
        FormPopupContent.loadJs([
            '{{ URL::asset('core/js/jquery-3.6.0.min.js') }}',
            '{{ URL::asset('core/validate/jquery.validate.min.js') }}',
            '{{ action('Controller@jquery_validate_locale') }}',
            '{{ URL::asset('core/datetime/anytime.min.js') }}',
            '{{ URL::asset('core/datetime/moment.min.js') }}',
            '{{ URL::asset('core/datetime/pickadate/picker.js') }}',
            '{{ URL::asset('core/datetime/pickadate/picker.date.js') }}',
            '{{ URL::asset('core/js/functions.js') }}',
        ], function() {
            FormPopupContent.init();

            // after loaded popup
            parent.postMessage({
                loaded: true
            }, '*');
        });
    });

    var FormPopupContent = {
        loadCss: function(url) {
            if (this.css == null) {
                var head  = document.getElementsByTagName('head')[0];
                var link  = document.createElement('link');
                link.rel  = 'stylesheet';
                link.type = 'text/css';
                link.href = url;
                link.media = 'all';
                head.appendChild(link);

                this.css = link;
            }
        },

        loadJs: function(jss, callback) {
            var _this = this;

            if (jss.length > 0) {
                var s = document.createElement("script");
                var url = jss.shift();

                console.log("loading: " + url);

                s.type = "text/javascript";
                s.src = url;
                s.setAttribute('builder-helper', 'true')
                s.onload = function () {
                    //
                    _this.loadJs(jss, callback);
                };
                window.document.head.appendChild(s);
            } else {
                if (typeof(callback) !== 'undefined') {
                    callback();
                }
            }
        },

        autoHeight: function() {
            var content_height = this.getForm().height();
            var content_width = this.getForm().width();
            parent.postMessage({
                frameSize: {
                    height: content_height,
                    width: content_width,
                }
            }, '*');
        },

        // ---- jQuery loaded ------

        formId: 'PopupForm',
        form: null,
        formUrl: '{{ action('FormController@frontendSave', [
            'uid' => $form->uid,
        ]) }}',

        init: function() {
            var _this = this;
            
            // wrap content with <form>
            _this.wrapForm();

            // validate
            _this.getForm().validate();

            // pick a date
            if ($(".date-control").length) {
                $('.date-control').pickadate({
                    format: 'yyyy-mm-dd'
                });
            }

            // datetime picker
            if ($(".datetime-control").length) {
                $(".datetime-control").each(function() {
                    var id = '_' + Math.random().toString(36).substr(2, 9);
                    $(this).attr('id', id);

                    $('#' + id).AnyTime_picker({
                        format: LANG_ANY_DATETIME_FORMAT
                    });
                });
            }

            // submit
            _this.getForm().on('submit', function(e) {
                e.preventDefault();

                _this.save();
            });

            // auto height
            _this.autoHeight();
            $(':input').on('keyup change keydown', function() {
                _this.autoHeight();
            });
            $( window ).resize(function() {
                _this.autoHeight();
            });
        },

        showErrorBox: function(errors) {
            var box = $('<div class="alert alert-error"></div>');
            box.append('<a>ssdfsfda</a>');

            parent.postMessage({
                alert: {
                    message: Object.values(errors)[0],
                }
            }, '*');
        },

        getForm: function() {
            return $('#' + this.formId);
        },

        wrapForm: function() {
            $( "[builder-wrapper]" ).wrap( '<form style="padding:0;margin:0" id="'+this.formId+'" action="'+this.formUrl+'"></form>' );
            this.getForm().prepend('{{ csrf_field() }}');
        },

        showSuccessMessage: function() {
            $('form-section').hide();
            $('message-section').show();
            this.autoHeight();
        },

        save: function() {
            var _this = this;

            if (!_this.getForm().valid()) {
                _this.autoHeight();
                return;
            }

            addMaskLoading();
            
            $.ajax({
                url: _this.formUrl,
                method: 'POST',
                data: _this.getForm().serialize(),
                globalError: false
            })
            .done(function(res) {
                _this.showSuccessMessage();
                removeMaskLoading();
            })
            .fail(function(res) {
                switch (res.status) {
                    case 400:
                        var errors = JSON.parse(res.responseText);
                        _this.showErrorBox(errors);
                        break;
                    default:
                        alert(res.responseText);
                }
                removeMaskLoading();
            });
        }
    }
</script>