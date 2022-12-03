<div class="modal-dialog shadow modal-default">
    <div class="modal-content">
        <div class="modal-header">
            <a href="javascript:;" class="material-symbols-rounded back">keyboard_backspace</a>
            <h5 class="modal-title text-center" style="width:100%">
                @yield('bar-title')
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body @yield('class')">
            <h4 class="fw-600 mb-3 pb-1">@yield('title')</h4>

            <!-- display flash message -->
            @include('common.errors')

            <!-- main inner content -->
            @yield('content')
        </div>
    </div>
</div>