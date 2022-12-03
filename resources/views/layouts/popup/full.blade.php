<div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
        <div class="modal-header">
            <a href="javascript:;" class="material-symbols-rounded back">keyboard_backspace</a>
            <h5 class="modal-title text-center" style="width:100%">
                @yield('title')
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body @yield('class')">
            <!-- display flash message -->
            @include('common.errors')

            <!-- main inner content -->
            @yield('content')
        </div>
    </div>
</div>
