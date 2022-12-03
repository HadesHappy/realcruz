@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.webhook.sample_request') }}
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
<pre class="prettyprint lang-js bg-light p-3">{
    "email_id": "123456789",
    "title": "New Campaign",
    "execution date": "2022-01-04 09:02:39",
    "email": "example@example.com",
    "phone": "+380631234567",
    "variables": [
        {
            "name1": "value1"
        },
        {
            "name2" : "value2"
        }
    ]
}</pre>
@endsection