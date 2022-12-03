@extends('layouts.core.frontend')

@section('title', trans('messages.dashboard'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/echarts/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/echarts/dark.js') }}"></script> 
@endsection

@section('content')
    <style>
        .function-box {
            height: 100px;
            display: inline-block;
            align-items: center;
            justify-content: center;
            position: relative;
            width: 320px;
            line-height: 1.5;
        }
        .function-icon {
            position: absolute;
            font-size: 42px;
            color: #fff;
            bottom: 10px;
            right: 10px;
            opacity: 0.5;
        }
        .function-box h4 {
            font-weight: 500;
            color: #fff;
            font-size: 17px;
        }
        .btn-danger {
            background-color: #f57171;
            border-color: #f57171;
        }
        .btn-info {
            background-color: #50aed3;
            border-color: #50aed3;
        }
        .bg-payment {
            background-color: #7e7a7a;
            border-color: #7e7a7a;
        }
        .btn-success {
            background-color: #58a695;
            border-color: #58a695;
        }
        .btn-warning {
            background-color: #eebc26;
            border-color: #eebc26;
        }
    </style>
    <h2 class="mt-4 pt-2">{!! trans('messages.frontend_dashboard_hello', ['name' => Auth::user()->displayName()]) !!}</h2>
    <p>Welcome back to your account dashboard.<br>Manage your Products. Generate checkout URL and get Paid!</p>

    <div class="mt-5">
        <div class="">
            <div class="function-boxes">
                <a href="{{ action('Site\ProductController@index2') }}" class="function-box btn btn-success me-4 mb-4 rounded-3">
                    <div class="d-flex align-items-center justify-content-center" style="height:100%">
                        <span class="function-icon material-symbols-rounded">
                            inventory_2
                        </span>
                        <h4 class="text-center m-0">Products</h4>
                    </div>                        
                </a>
                <a href="{{ action('Site\CategoryController@index') }}" class="function-box btn btn-warning me-4 mb-4 rounded-3">
                    <div class="d-flex align-items-center justify-content-center" style="height:100%">
                        <span class="function-icon material-symbols-rounded">
                            category
                        </span>
                        <h4 class="text-center m-0">Product<br>Categories</h4>
                    </div>                        
                </a>
                <a href="{{ action('Site\OrderController@index') }}" class="function-box btn btn-danger me-4 mb-4 rounded-3">
                    <div class="d-flex align-items-center justify-content-center" style="height:100%">
                        <span class="function-icon material-symbols-rounded">
                            grading
                        </span>
                        <h4 class="text-center m-0">Orders</h4>
                    </div>                        
                </a>
                <a href="{{ action('Site\CustomerController@index') }}" class="function-box btn btn-info me-4 mb-4 rounded-3">
                    <div class="d-flex align-items-center justify-content-center" style="height:100%">
                        <span class="function-icon material-symbols-rounded">
                            people
                        </span>
                        <h4 class="text-center m-0">Customers</h4>
                    </div>                        
                </a>
                <a href="{{ action('Site\SettingController@payments') }}" class="function-box btn btn-secondary bg-payment me-4 mb-4 rounded-3">
                    <div class="d-flex align-items-center justify-content-center" style="height:100%">
                        <span class="function-icon material-symbols-rounded">
                            payments
                        </span>
                        <h4 class="text-center m-0">Payment Settings</h4>
                    </div>                        
                </a>
            </div>
        </div>
            
    </div>
@endsection
