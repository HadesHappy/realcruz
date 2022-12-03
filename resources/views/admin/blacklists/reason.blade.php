@extends('layouts.popup.small')

@section('content')
    <h4 class="mt-0 font-weight-semibold">Blacklisted reason for {{ $blacklist->email }}:</h4>
    {{ $blacklist->reason }}
@endsection