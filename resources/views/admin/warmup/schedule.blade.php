@extends('layouts.core.backend')

@section('head')
<script type="text/javascript" src="{{ URL::asset('core/echarts/echarts.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/echarts/dark.js') }}"></script>
@endsection

@section('content')
<style>
body {
    font-size: 24px;
}

input {
    background: transparent;
    border: none;
    border-bottom: 1px solid #000000;
}

input[type=checkbox] {
    width: 2rem;
    height: 2rem;
}

select {
    min-height: 3rem;
}

.fill {
    flex: 1;
}

.input-form {
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-height: 7rem;
}

.title {
    display: flex;
    align-items: center;
    min-width: 300px;
}

.input-label {
    font-size: 0.8rem;
}

.input-wrap {
    display: flex;
    align-items: center;
    gap: 4rem;
}

.checkbox-wrap {
    display: flex;
    justify-content: center;
    width: 20%;
}

.flex {
    display: flex;
}

.justify-end {
    justify-content: flex-end;
}

.gap {
    gap: 4rem
}

.schedule {
    opacity: 0.5;
}
</style>
<h1 class="schedule">Schedule</h1>
<form class="input-form" action="{{ url('admin/warmup/schedule/'.$gap->id) }}" method="POST">
    @csrf
    <span class="title">Pause Between Each Email</span>
    <div class="input-wrap">
        <label for="pause_email" style="display: flex; flex-direction: column">
            <span class="input-label">Label</span>
            <input type="number" name="gap" id="pause_email" value="{{$gap->gap}}" step="1" min="10">
            <span class=" input-label">2</span>
        </label>
        <span>Second(s)</span>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
@endsection