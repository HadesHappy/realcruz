<script>
	var DATATABLE_TRANSLATE_URL = '{{ action('Controller@datatable_locale') }}';
	var JVALIDATE_TRANSLATE_URL = '{{ action('Controller@jquery_validate_locale') }}';
	var APP_URL = '{{ url('/') }}';
	var LANG_OK = '{{ trans('messages.ok') }}';
	var LANG_CONFIRM = '{{ trans('messages.confirm') }}';
	var LANG_YES = '{{ trans('messages.yes') }}';
	var LANG_NO = '{{ trans('messages.no') }}';
	var LANG_ARE_YOU_SURE = '{{ trans('messages.are_you_sure') }}';
	var LANG_CANCEL = '{{ trans('messages.cancel') }}';
	var LANG_DELETE_VALIDATE = '{{ trans('messages.delete_validate') }}';
	var LANG_DATE_FORMAT = 'yyyy-mm-dd';
	var LANG_ANY_DATETIME_FORMAT = '%Z-%m-%d, %H:%i';
	var LANG_J_DATE_FORMAT = '{{ trans('messages.j_date_format') }}';
	var CSRF_TOKEN = "{{ csrf_token() }}";
	var LANG_SUCCESS = '{{ trans('messages.notify.success') }}';
	var LANG_ALERT = '{{ trans('messages.notify.alert') }}';
	var LANG_ERROR = '{{ trans('messages.notify.error') }}';
	var LANG_CONFIRMATION = '{{ trans('messages.notify.confirmation') }}';
	var LANG_NOTIFY = {
		'success': '{{ trans('messages.notify.success') }}',
		'error': '{{ trans('messages.notify.error') }}',
		'notice': '{{ trans('messages.notify.notice') }}'
	};
	var LOADING_WAIT = '{{ trans('messages.loading_wait') }}';

	// current controller action
	var CONTROLLER = '{{ $controller }}';
	var ACTION = '{{ $action }}';
</script>