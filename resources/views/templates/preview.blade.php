<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery.min.js') }}"></script>

<div class="saving" style="display:none; position: fixed;
    height: 100%;
    vertical-align: middle;
    text-align: center;
    padding: 100px 0;
    font-size: 20px;
    color: #fff;
    width: 100%;
    background: rgba(0,0,0,0.7);">{{ trans('messages.saving_screenshot') }}</div>

{!! $template->getPreviewContent() !!}
