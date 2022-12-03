<div class="form-group control-text">
    <div class="sub_section">
        <h3 class="text-semibold text-primary">{{ trans('messages.profile_photo') }}</h3>
        <div class="media profile-image d-flex">
            <div class="preview me-4 position-relative" id="{{$dragId}}">
                <a href="#" class="upload-media-container radius-0 pre-upload-photo">
                    <img preview-for="image" empty-src="{{ URL::asset('images/placeholder.jpg') }}" src="{{ $src }}" class="rounded-circle" alt="">

                </a>
                <span onclick="$('input[name=image]').trigger('click')"
                    style="position:absolute;top:39px"
                    class="edit-photo text-center">
                    <span class="material-symbols-rounded">
edit
</span></span>
                <input type="file" name="image" id="{{$preview}}" accept="image/*" class="file-styled previewable hide">
                <input type="hidden" name="_remove_image" value='' />
            </div>
            <div class="col-md-10 padding-l0">
                {{ trans('messages.photo_at_least', ["size" => "300px x 300px"]) }}
                <div class="mb-2">
                    <a href="#remove" class=" remove-profile-image"> {{ trans('messages.remove_current_photo') }}</a>
                </div>
                <a href="#upload" onclick="$('input[name=image]').trigger('click')" class="btn btn-secondary">{{ trans('messages.upload_photo') }}</a>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        var element = document.getElementById("{{$dragId}}");
        var image = document.getElementById("{{$preview}}");
        element.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            element.style="background: #c7dade"

        });

        element.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            element.style="background: white"

        });
        element.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            element.style="background: white";
            var imageType = /image.*/;
            if (e.dataTransfer.files[0].type.match(imageType)) {
                image.files = e.dataTransfer.files
            }

        });
    })
</script>
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