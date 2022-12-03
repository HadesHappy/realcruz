@extends('layouts.popup.medium')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="mb-20">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        @include('admin.subscriptions._invoices')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection