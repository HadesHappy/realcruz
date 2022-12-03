@extends('layouts.core.frontend')

@section('title', trans('messages.campaigns'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("WebsiteController@index") }}">{{ trans('messages.websites') }}</a></li>
        </ul>
    </div>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-5">
                <div class="me-4">
                    <svg width="100px" height="100px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" width="6.72in" height="6.72in" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 6720 6720">
                        <defs>
                        <style type="text/css">
                        <![CDATA[
                        .fil1 {fill:none}
                        .fil0 {fill:rgb(110, 110, 110);fill-rule:nonzero}
                        ]]>
                        </style>
                        </defs>
                        <g id="Layer_x0020_1">
                        <metadata id="CorelCorpID_0Corel-Layer"/>
                        <path class="fil0" d="M3360 1124c485,0 935,155 1302,419 376,271 665,655 816,1104l0 0c12,37 -7,76 -44,88 -8,3 -16,4 -24,4l-4101 0c-39,0 -70,-31 -70,-70 0,-10 2,-19 6,-28 151,-446 439,-829 814,-1098 367,-264 816,-419 1302,-419zm1220 532c-343,-247 -765,-392 -1220,-392 -456,0 -877,145 -1220,392 -327,235 -583,562 -732,943l3904 0c-148,-381 -405,-708 -732,-943zm895 2422c-151,446 -439,829 -814,1098 -367,264 -816,419 -1302,419 -485,0 -935,-155 -1302,-419 -376,-271 -665,-655 -816,-1104l0 0c-12,-37 7,-76 44,-88 8,-3 16,-4 24,-4l4101 0c39,0 70,31 70,70 0,10 -2,19 -6,28zm-895 985c327,-235 583,-562 732,-943l-3904 0c148,381 405,708 732,943 343,247 765,392 1220,392 456,0 877,-145 1220,-392z"/>
                        <path class="fil0" d="M5086 4778c-208,254 -471,461 -770,603 -290,138 -614,215 -956,215 -342,0 -666,-77 -956,-215 -301,-143 -565,-352 -774,-607l0 0c-24,-30 -20,-74 10,-98 7,-6 16,-10 24,-13 266,-108 543,-191 828,-247 282,-55 572,-84 868,-84 296,0 587,29 868,84 288,56 567,141 834,250l0 0c36,15 53,55 38,91 -3,9 -8,16 -15,22zm-830 477c253,-120 479,-290 663,-497 -231,-88 -471,-158 -719,-206 -272,-53 -554,-81 -841,-81 -288,0 -569,28 -841,81 -248,49 -488,118 -719,206 185,207 410,376 663,497 272,129 576,201 897,201 321,0 625,-72 897,-201z"/>
                        <path class="fil0" d="M4920 1962c-185,-207 -410,-376 -663,-497 -272,-129 -576,-201 -897,-201 -321,0 -625,72 -897,201 -253,120 -479,290 -663,497 231,88 471,158 719,206 272,53 554,81 841,81 288,0 569,-28 841,-81 248,-49 488,-118 719,-206zm-604 -623c299,142 562,349 770,603 6,6 11,14 15,22 15,36 -3,76 -38,91l0 0c-268,109 -547,194 -834,250 -282,55 -572,84 -868,84 -296,0 -587,-29 -868,-84 -285,-56 -562,-139 -828,-247 -9,-3 -17,-7 -24,-13 -30,-24 -34,-68 -10,-98l0 0c209,-256 473,-464 774,-607 290,-138 614,-215 956,-215 342,0 666,77 956,215z"/>
                        <path class="fil0" d="M3360 1124c272,0 520,161 721,434 198,271 350,654 429,1099l0 0c7,38 -19,74 -57,81 -4,1 -9,1 -13,1l-2161 0c-39,0 -70,-31 -70,-70 0,-5 1,-10 2,-15 79,-444 231,-825 428,-1095 200,-273 449,-434 721,-434zm608 517c-174,-237 -384,-377 -608,-377 -224,0 -434,140 -608,377 -174,237 -310,570 -388,959l1993 0c-78,-389 -215,-721 -388,-959zm541 2425c-79,443 -231,825 -428,1095 -200,273 -449,434 -721,434 -272,0 -520,-161 -721,-434 -198,-271 -350,-654 -429,-1099l0 0c-7,-38 19,-74 57,-81 4,-1 9,-1 13,-1l2161 0c39,0 70,31 70,70 0,5 -1,10 -2,15zm-541 1013c174,-237 310,-570 388,-959l-1993 0c78,389 215,721 388,959 174,237 384,377 608,377 224,0 434,-140 608,-377z"/>
                        <path class="fil0" d="M870 2599l4980 0 70 0 0 70 0 1381 0 70 -70 0 -4980 0 -70 0 0 -70 0 -1381 0 -70 70 0zm4911 140l-4841 0 0 1242 4841 0 0 -1242z"/>
                        <path class="fil0" d="M1484 3766l-249 -812 142 0 129 469 48 174c2,-9 16,-65 42,-168l129 -476 142 0 122 471 41 155 46 -157 139 -470 134 0 -254 812 -143 0 -129 -487 -32 -139 -164 625 -144 0zm1567 0l-249 -812 142 0 129 469 48 174c2,-9 16,-65 42,-168l129 -476 142 0 122 471 41 155 46 -157 139 -470 134 0 -254 812 -143 0 -129 -487 -32 -139 -164 625 -144 0zm1567 0l-249 -812 142 0 129 469 48 174c2,-9 16,-65 42,-168l129 -476 142 0 122 471 41 155 46 -157 139 -470 134 0 -254 812 -143 0 -129 -487 -32 -139 -164 625 -144 0z"/>
                        </g>
                        <rect class="fil1" width="6720" height="6720"/>
                    </svg>
                </div>
                <div>
                    <h3 class="mb-1">{{ trans('messages.website.connect_to_your_website') }}</h3>
                    <p class="text-muted mb-0">{!! trans('messages.website.connect_to_your_website.desc') !!}</p>
                </div>
                <div class="ms-auto">
                    <a href="{{ action('WebsiteController@index') }}" class="btn btn-default ms-2 fw-600 px-4 py-2">
                        {{ trans('messages.manage_your_sites') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <h5 class="text-muted mb-3 fs-6 fst-italic">{{ trans('messages.website.get_custom_website_code') }}</h5>
    <form id="WebsiteCreate" action="{{ action('WebsiteController@create') }}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-6">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => '',
                    'name' => 'url',
                    'value' => $website->url,
                    'label' => trans('messages.website.enter_url'),
                    'help_class' => 'template',
                    'rules' => ['name' => 'required']
                ])

                <div class="mt-4">
                    <button type="submit" class="btn btn-secondary">
                        {{ trans('messages.website.get_code') }}
                    </button>
                    <a href="{{ action('WebsiteController@index') }}" class="btn btn-link ms-2">
                        {{ trans('messages.cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function() {
            $('#WebsiteCreate').on('submit', function() {
                addMaskLoading();
            });
        });
    </script>
@endsection