              
              
@include('helpers.form_control', ['required' => true, 'type' => 'text', 'label' => trans('messages.template_name'), 'name' => 'name', 'value' => $template->name, 'rules' => ['name' => 'required']])
@include('helpers.form_control', ['class' => 'template-editor','required' => true, 'type' => 'textarea', 'name' => 'content', 'value' => $template->getParsedContent(), 'rules' => ['content' => 'required']])

<script>
    var editor;
    $(document).ready(function() {
        editor = tinymce.init({
            language: '{{ Auth::user()->customer->getLanguageCode() }}',
            selector: '.template-editor',
            directionality: "{{ Auth::user()->customer->text_direction }}",
            height: 500,
            convert_urls: false,
            remove_script_host: false,
            forced_root_block: "",
            plugins: 'fullpage print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
            imagetools_cors_hosts: ['picsum.photos'],
            menubar: 'file edit view insert format tools table help',
            toolbar: [
                'ltr rtl | acelletags | undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify',
                'outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl'
            ],
            toolbar_location: 'top',
            menubar: true,
            statusbar: false,
            toolbar_sticky: true,
            valid_elements : '*[*],meta[*]',
            valid_children: '+h1[div],+h2[div],+h3[div],+h4[div],+h5[div],+h6[div],+a[div]',
            extended_valid_elements : "meta[*]",
            valid_children : "+body[style],+body[meta],+div[h2|span|meta|object],+object[param|embed]",
            external_filemanager_path:APP_URL.replace('/index.php','')+"/filemanager2/",
            filemanager_title:"Responsive Filemanager" ,
            external_plugins: { "filemanager" : APP_URL.replace('/index.php','')+"/filemanager2/plugin.min.js"},
            setup: function (editor) {
                
                /* Menu button that has a simple "insert date" menu item, and a submenu containing other formats. */
                /* Clicking the first menu item or one of the submenu items inserts the date in the selected format. */
                editor.ui.registry.addMenuButton('acelletags', {
                    text: '{{ trans('messages.editor.insert_tag') }}',
                    fetch: function (callback) {
                    var items = [];

                    // Unsubscribe link
                    items.push({
                        type: 'menuitem',
                        text: 'UNSUBSCRIBE_LINK',
                        onAction: function (_) {
                            editor.insertContent('<a href="{UNSUBSCRIBE_URL}">{{ trans('messages.editor.unsubscribe_text') }}</a>');
                        }
                    });                        

                    // web view url
                    items.push({
                        type: 'menuitem',
                        text: 'WEB_VIEW_LINK',
                        onAction: function (_) {
                            editor.insertContent('<a href="{WEB_VIEW_URL}">{{ trans('messages.editor.click_view_web_version') }}</a>');
                        }
                    });

                    @foreach(Acelle\Model\Template::tags() as $tag)
                        items.push({
                            type: 'menuitem',
                            text: '{{ $tag["name"] }}',
                            onAction: function (_) {
                                editor.insertContent('{{ "{".$tag["name"]."}" }}');
                            }
                        });
                    @endforeach

                    callback(items);
                    }
                });

                editor.on('init', function(e) {
                    $('.classic-loader').remove();
                });
            }
        });
    });
</script>
							
							