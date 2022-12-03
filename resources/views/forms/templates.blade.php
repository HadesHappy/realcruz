@if ($templates->count() > 0)
    <div id="layout" class="row template-boxes mt-4" style="
        margin-left: -20px;
        margin-right: -20px;
    ">
        @foreach ($templates as $key => $template)
            <div class="col-xl-3 col-md-3 col-sm-4 col-xm-6">
                <a href="javascript:;" class="select-template-layout mb-4 d-block">
                    <input type="radio" name="template_uid" value="{{ $template->uid }}" style="display:none" />
                    <div class="">
                        <div class="">
                            <div class="">
                                <img class="rounded border shadow-sm" width="100%" src="{{ $template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                            </div>
                            <label class="mt-1 text-center">{{ $template->name }}</label>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
        
    <div style="clear:both" class="mt-4">
        @include('elements/_per_page_select', ["items" => $templates])
    </div>

    <script>
        var FormsTemplates = {
            manager: null,
            getManager: function() {
                var _this = this;

                if (this.manager == null) {
                    this.manager = new GroupManager();

                    // add all templates
                    $('.select-template-layout').each(function() {
                        _this.manager.add({
                            element: $(this),
                            uid: $(this).attr('data-uid'),
                            radio: $(this).find('[name=template_uid]')
                        });
                    });
                }

                this.manager.bind(function(group, others) {
                    group.element.on('click', function() {
                        others.forEach(function(other) {
                            other.element.removeClass('selected');
                        });

                        group.element.addClass('selected');
                        group.radio.prop('checked', true);
                    });
                });

                return this.manager;
            },
            select: function(element) {
                var template = element.attr('data-uid');
            }
        }

        $(function() {
            FormsTemplates.getManager();
        });
        // $(document).ready(function() {
        //     $(document).on('click', '.select-template-layout', function() {
		// 		var template = $(this).attr('data-template');
                
        //         // unselect all layouts
        //         $('.select-template-layout').removeClass('selected');
                
        //         // select this
        //         $(this).addClass('selected');

		// 		// unselect all
		// 		$('[name=template]').val('');
				
		// 		// update template value
		// 		if (typeof(template) !== 'undefined') {
		// 			$('[name=template]').val(template);
		// 		}
        //     });
            
        //     $('.select-template-layout').eq(0).click();
        // });
    </script>
    
@elseif (!empty(request()->keyword))
    <div class="empty-list">
        <span class="material-symbols-rounded">
auto_awesome_mosaic
</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-symbols-rounded">
auto_awesome_mosaic
</span>
        <span class="line-1">
            {{ trans('messages.template_empty_line_1') }}
        </span>
    </div>
@endif
