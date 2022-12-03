@extends('layouts.core.frontend')
@section('title', 'Samplel UI')
@section('page_header')
    <!-- nothing here -->
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10">
            <h1>Notifications</h1>
            <div class="alert alert-info" style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
                <div style="display: flex; flex-direction: row; align-items: center;">
                    <div style="margin-right:15px">
                        <i class="lnr lnr-bubble"></i>
                    </div>
                    <div style="padding-right: 40px">
                        <h4>Pending verification...</h4>
                        <p>We have sent an email to this sender's email address. Please click on the link included in the email to activate</p>
                    </div>
                </div>
                <button class="btn btn-secondary">Send again!</button>
            </div>

            <div class="alert alert-warning" style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                <div style="display: flex; flex-direction: row; align-items: center;">
                    <div style="margin-right:15px">
                        <span class="material-symbols-rounded">
error_outline
</span>
                    </div>
                    <div style="padding-right: 40px">
                        <h4>Verification required</h4>
                        <p>We have sent an email to this sender's email address. Please click on the link included in the email to activate</p>
                    </div>
                </div>
                <button class="btn btn-secondary">{{ trans('messages.sending_domain.verify') }}</button>
            </div>

            <div class="alert alert-danger" style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                <div style="display: flex; flex-direction: row; align-items: center;">
                    <div style="margin-right:15px">
                        <i class="lnr lnr-circle-minus"></i>
                    </div>
                    <div style="padding-right: 40px">
                        <h4>Verification required</h4>
                        <p>We have sent an email to this sender's email address. Please click on the link included in the email to activate</p>
                    </div>
                </div>
                <button class="btn btn-secondary">{{ trans('messages.sending_domain.verify') }}</button>
            </div>

            <div class="alert alert-success" style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                <div style="display: flex; flex-direction: row; align-items: center;">
                    <div style="margin-right:15px">
                        <i class="lnr lnr-checkmark-circle"></i>
                    </div>
                    <div style="padding-right: 40px">
                        <h4>Sender verified</h4>
                        <p>We have sent an email to this sender's email address. Please click on the link included in the email to activate</p>
                    </div>
                </div>
            </div>

            <h2>Table</h2>
        </div>
    </div>
@endsection
