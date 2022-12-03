<div class="row">
    <div class="col-md-3">
        <div class="sub_section">
            <h2 class="text-semibold text-primary">{{ trans('messages.profile_photo') }}</h2>
            <div class="media profile-image">
                <div class="media-left">
                    <a href="#" class="upload-media-container">
                        <img preview-for="image" empty-src="{{ URL::asset('images/placeholder.jpg') }}" src="{{ $customer->user->getProfileImageUrl() }}" class="rounded-circle" alt="">
                    </a>
                    <input type="file" name="image" class="file-styled previewable hide">
                    <input type="hidden" name="_remove_image" value='' />
                </div>
                <div class="media-body text-center">
                    <h5 class="media-heading text-semibold">{{ trans('messages.upload_photo') }}</h5>
                    {{ trans('messages.photo_at_least', ["size" => "300px x 300px"]) }}
                    <br /><br />
                    <a href="#upload" onclick="$('input[name=image]').trigger('click')" class="btn btn-primary me-1"><span class="material-symbols-rounded">
file_download
</span> {{ trans('messages.upload') }}</a>
                    <a href="#remove" class="btn btn-secondary remove-profile-image"><span class="material-symbols-rounded">
delete_outline
</span> {{ trans('messages.remove') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="sub_section">
            <h2 class="text-semibold text-primary">{{ trans('messages.account') }}</h2>

            @include('helpers.form_control', ['type' => 'text', 'name' => 'email', 'value' => $customer->user->email, 'help_class' => 'profile', 'rules' => $customer->user->rules()])

            @include('helpers.form_control', ['type' => 'password', 'label'=> trans('messages.new_password'), 'name' => 'password', 'rules' => $customer->user->rules()])

            @include('helpers.form_control', ['type' => 'password', 'name' => 'password_confirmation', 'rules' => $customer->user->rules()])

        </div>
    </div>
    <div class="col-md-5">
        <div class="sub_section">
            <h2 class="text-semibold text-primary">{{ trans('messages.basic_information') }}</h2>

            <div class="row">
                <div class="col-md-6">
                    @include('helpers.form_control', ['type' => 'text', 'name' => 'first_name', 'value' => $customer->user->first_name, 'rules' => $customer->user->rules()])
                </div>
                <div class="col-md-6">
                    @include('helpers.form_control', ['type' => 'text', 'name' => 'last_name', 'value' => $customer->user->last_name, 'rules' => $customer->user->rules()])
                </div>
            </div>

            @include('helpers.form_control', ['type' => 'select', 'name' => 'timezone', 'value' => $customer->timezone, 'options' => Tool::getTimezoneSelectOptions(), 'include_blank' => trans('messages.choose'), 'rules' => $customer->user->rules()])

            @include('helpers.form_control', ['type' => 'select', 'name' => 'language_id', 'label' => trans('messages.language'), 'value' => $customer->language_id, 'options' => Acelle\Model\Language::getSelectOptions(), 'include_blank' => trans('messages.choose'), 'rules' => $customer->user->rules()])

        </div>
    </div>

</div>
<hr>
<div class="text-left">
    <button class="btn btn-secondary"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
</div>

<script>
	$(function() {
		// Preview upload image
		$("input.previewable").on('change', function() {
			var img = $("img[preview-for='" + $(this).attr("name") + "']");
			previewImageBrowse(this, img);
		});
		$(".remove-profile-image").click(function() {
			var img = $(this).parents(".profile-image").find("img");
			var imput = $(this).parents(".profile-image").find("input[name='_remove_image']");
			img.attr("src", img.attr("empty-src"));
			imput.val("true");
		});
	});
</script>